<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Product;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seat;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller {  

    public function index($categorySlug = null, $menuSlug = null,  ) {
        // Home page
        if (!$categorySlug) {
            $popularCategory = Category::where('name', 'Popular')
            ->with(['products' => function ($query) {
                $query->latest();
            }])
            ->first();
                        
            $products = Product::with(['category.menu', 'category', 'variants'])->latest()->get();
            
            $seats = collect();
            if (session('branch_id')) {
                $seats = Seat::where('branch_id', session('branch_id'))->orderBy('table')->get();
            }

            $seats = Seat::where('area_id', session('area_id'))->orderBy('table_order', 'ASC')->get();
            $config = Configuration::first();
            $tableSlug = request()->segment(2);
            
            $cart = session()->get('cart', []);

            if (!session()->has('area_id')) {
                $defaultArea = Area::where('area_name', 'Default')->first();
                if ($defaultArea) {
                    session([
                        'area_id' => $defaultArea->id,
                        'area_name' => $defaultArea->area_name,
                    ]);
                }
            }
            $seats = Seat::where('area_id', session('area_id'))->orderBy('table_order')->get();

            //dd(session('cart'));            
            //dd(session()->all());

            return view('front.shop.index', [
                'products' => $products,
                'tableSlug' => $tableSlug,
                'popularProducts' => $popularCategory?->products ?? collect(),
                'popularCategory' => $popularCategory,
                'category' => null,
                'menus' => collect(),
                'seats' => $seats,
                //'menuSlug' => null,
                'config' => $config,                
                'total' => getCartTotal(),
                'cartCount' => getCartCount(),
            ]);
        }

        $category = Category::where('slug',  $categorySlug)->firstOrFail();
        $menus = $category->menus;        
        //$variants = Variant::get();
        $query = Product::query();
        $config = Configuration::first();  
        
        $popularCategory = Category::where('name', 'Popular')
            ->with(['products' => function ($query) {
                $query->latest();
            }])
            ->first();

        // ALL products of category
        $query->where(function($q) use ($category, $menus) {
            // Direct category products
            $q->where('category_id', $category->id);

            // OR menu linked products
            if ($menus->isNotEmpty()) {
                $q->orWhereIn('menu_id', $menus->pluck('id'));
            }
        });

        // Specific menu filter
        if ($menuSlug && $menuSlug != 'all') {
            $menu = Menu::where('slug', $menuSlug)->first();

            if ($menu) {
                $query->where('menu_id', $menu->id);
            }
        }

        $products = $query->get();  
        
        $seats = collect();
        if (session('branch_id')) {
            $seats = Seat::where('branch_id', session('branch_id'))->orderBy('table')->get();
        }

        if (session('area_id')) {            
            $seats = Seat::where('area_id', session('area_id'))->orderBy('table_order', 'ASC')->get();
        }

        $cart = session()->get('cart', []);
            
        //dd(session('cart'));             
        //dd(session()->all());
        
       return view('front.shop.index', [
            'products' => $products,
            'popularProducts' => $popularCategory?->products ?? collect(),
            'popularCategory' => $popularCategory,
            'category' => $category,
            'menus' => $menus,
            'seats' => $seats,            
            'menuSlug' => $menuSlug,
            'config' => $config,            
            'total' => getCartTotal(),
            'cartCount' => getCartCount(),
        ]);       
    }    

    
    private function handleCart(Request $request, $id, $sessionKey, $createdBy) {
        $product = Product::findOrFail($id);

        $variantName  = $request->variant_name ?? null;
        $variantPrice = $request->variant_price ?? $product->price;

        $itemKey = $id . '_' . ($variantName ?? 'default');

        $cart = session()->get($sessionKey, []);

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity']++;
        } else {
            $cart[$itemKey] = [
                'product_id' => $product->id,
                'quantity' => 1,
                'name' => $product->name,
                'variant' => $variantName,
                'price' => $variantPrice,
                'area_id' => session('area_id'),                
                'seat_id' => session('seat_id'),
                'area_name' => session('area_name'),                
                //'role' => $createdBy == 'admin' ? 1 : null,
                'created_by' => $createdBy,
            ];
        }

        session()->put($sessionKey, $cart);

        return back()->with('success', 'Product added to cart.');
    }

    public function addToCart(Request $request, $id) {
        return $this->handleCart($request, $id, 'cart', 'customer');
    }
    
    public function addToKOT(Request $request, $id) {
        return $this->handleCart($request, $id, 'kot_cart', 'admin');
    }
  

    public function tableQr($branchSlug, $table) {
        $branch = Area::where('area_slug', $branchSlug)->firstOrFail();

        $seat = Seat::where('area_id', $branch->id)
            ->where('table', $table)
            ->firstOrFail();

        session([
            'area_id'   => $branch->id,
            'table_id'  => $seat->id,
            'area_name' => $branch->area_name,
            'table'     => $seat->table,
        ]);

        return redirect()->route('front.menu');
    }

    // public function tableQr($branchSlug, $tableSlug) {
    //     $branch = Area::where('area_slug', $branchSlug)->firstOrFail();
    //     $seat = Seat::where('area_id', $branch->id)->where('table', $tableSlug)->firstOrFail();        

    //     session([
    //         'area_id'      => $branch->id,
    //         'table_id'     => $seat->id,
    //         'area_name'    => $branch->area_name,
    //         'table'   => $seat->table,
    //     ]);
        
    //     return redirect()->route('front.menu');
    // }

    public function increaseCart($id) {
        $cart = session()->get('cart', []);

        // increase qty
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        }

        // update session
        session()->put('cart', $cart);

        // total
        $total = 0;

        foreach($cart as $item){
            $total += $item['price'] * $item['quantity'];
        }        

        return response()->json([
            'status' => true,
            'qty' => $cart[$id]['quantity'],
            'cartCount' => getCartCount(),
            'cartTotal' => getCartTotal(),
            'activeCart' => session('activeCart'),
        ]);
    }

    

    public function decreaseCart($id) {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] <= 1) {

                return response()->json([
                    'status' => false,
                    'qty' => 1
                ]);
            }

            $cart[$id]['quantity']--;

            session()->put('cart', $cart);

            session()->put('activeCart', $id);

            return response()->json([
                'status' => true,
                'qty' => $cart[$id]['quantity'],
                'cartCount' => getCartCount(),
                'cartTotal' => getCartTotal(),
            ]);
        }
    }


    public function customerRemoveCart($id) {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item deleted from cart');
    }

   
    //Wishlist page
    public function wishlist() {
        $wishlistIds = array_keys(session('wishlist', []));

         $products = Product::with('product_images')
        ->whereIn('id', $wishlistIds)
        ->orderByDesc('id')
        ->get();

        $seats = collect();
        if (session('branch_id')) {
            $seats = Seat::where('branch_id', session('branch_id'))->orderBy('table')->get();
        }

        $seats = Seat::where('area_id', session('area_id'))->orderBy('table_order', 'ASC')->get();

        $config = Configuration::first();

        $data = [
            'products'=> $products,
            'config'=> $config,
            'seats'=> $seats,
        ];        

        //dd(session('wishlist'));
        //dd(session()->all());

        return view('front.shop.wishlist', $data);        
    }

    //Slug
    public function area_index(Request $request, $areaSlug = null,) {
        $areaSlug = ' ';
        $products = Product::orderBy('id','DESC')->get();
        $areas = Area::orderBy('id','DESC')->with('seating')->orderBy('id','DESC')->get();
        $seat_number = Seat::orderBy('id','DESC')->get();

        $products = Product::where('status',1);

        if(!empty($areaSlug)) {
            $areas = Area::where('slug',$areaSlug)->first();
            $seat_number = $seat_number->where('area_id',$areas->id);
            $areaSlug = $areas->id;
        }

        $products = $products->paginate(10);

        $data['products'] = $products;
        $data['areaSlug'] = $areaSlug;

        return view('front.shop.index',$data);
    }

    //Area Slug
    public function restaurant(Request $request, $areaSlug = null) {       
        $areaSelected = ' ';

        $products = Product::orderBy('id','DESC')->get();
        $seats = Seat::orderBy("table","ASC")->with('area')->get(); 
        $areas = Area::where('status',1);

        // if(!empty($areaSlug)) {
        //     $restaurant = Area::where('area_slug',$areaSlug)->first();
        //     $seats = $seats->where('area_id',$restaurant->id);
        //     $areaSelected = $restaurant->id;
        // }

        //$seatings = $seatings->paginate(10);
        
        $data['seats'] = $seats;  
        $data['products'] = $products;  
        $data['areas'] = $areas;        
        $data['areaSelected'] = $areaSelected;
        
        return view('front.shop.restaurant',$data);
    }   

    //Clear Cart
    public function clearCart(){
        session()->forget('cart');
        return back()->with('success', 'Cart cleared successfully.');        
    }
    
    //Wishlist
    public function addToWish($id) {
        $product = Product::findOrFail($id);

        $wishlist = session()->get('wishlist', []);

        // Add only if not already exists
        if (!isset($wishlist[$id])) {
            $wishlist[$id] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => $product->price,
                'image' => $product->image,
            ];

            session()->put('wishlist', $wishlist);

            return redirect()->back()
                ->with('success', 'Product added to wishlist successfully!');
        }

        return redirect()->back()
            ->with('info', 'Product already exists in wishlist.');
    }

    public function removeWish($id) {
        $wishlist = session()->get('wishlist', []);

        if (isset($wishlist[$id])) {
            unset($wishlist[$id]);
            session()->put('wishlist', $wishlist);
        }

        return redirect()->back()
            ->with('success', 'Product removed from wishlist.');
    }


    public function removeWishlist($id) {
        $wishlist = session('wishlist', []);

        unset($wishlist[$id]);

        if (empty($wishlist)) {
            session()->forget('wishlist');
        } else {
            session(['wishlist' => $wishlist]);
        }

        return back()->with('success', 'Product removed from wishlist.');
    }


    public function placeOrder(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_type' => 'required|in:Dinein,Takeaway,Delivery',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $session_id = mt_rand(1000000000, 9999999999);

        Session::put('session_id', $session_id);

        $cart = Session::get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Add delivery charge
        if ($request->order_type == 'Delivery') {
            $total += 50;
        }        

        if ($request->order_type === 'Takeaway' || $request->order_type === 'Delivery') {            
            $order = Order::create([
                'who'         => $request->who,
                'order_type'  => $request->order_type,
                'session_id'  => session('session_id'),
                'notes'       => $request->notes,
                'phone'       => $request->phone,
                'area_id'     => $request->active_outlet_id,
                'name'        => $request->active_name,
                'email'       => $request->active_email,
                'address'     => $request->address,                
                'total'       => $request->total,
                'payment_status' => 'Pending',
                'status'      => 'pending',
            ]);                         

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['product_id'],
                    'product_name'  => $item['name'],
                    'quantity'      => $item['quantity'],
                    'price'         => $item['price'],
                    'total'         => $item['quantity'] * $item['price'],
                ]);
            }

            Session::forget('cart');
        
            // if (session('role') == 1) {
            //     Session::forget('kot_cart');
            //     return redirect()->back()->with('success', 'Order placed successfully.');
            // }  
            
            Session::flush();
          
            return redirect()->route('razorpay.checkout', $order->id); 
        } elseif($request->order_type === 'Dinein' && $request->filled('seat_id')) {
            $order = Order::create([
                'who'         => $request->who,
                'order_type' => $request->order_type,
                'session_id' => session('session_id'),
                'area_id'  => session('area_id'),
                'branch'  => session('area_name'),
                'table'   => session('table_name'),
                'seat_id' => $request->seat_id ?? session('seat_id'),
                'notes' => $request->notes,
                'phone' => $request->phone,
                'total' => $request->total,
                'payment_method' => 'Pay at table',
                'payment_status' => 'Pending',
                'status' => 'running',
            ]);                            

            // Order Items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['product_id'],
                    'product_name'  => $item['name'],
                    'quantity'      => $item['quantity'],
                    'price'         => $item['price'],
                    'total'         => $item['quantity'] * $item['price'],
                ]);
            }

            //Seat status changed
            Seat::where('id', $request->seat_id)->update(['status' => 'running']);

            Session::forget('cart');
            Session::forget('kot_cart');

            return redirect()->route('order.success', $order->id)->with('success', 'Order placed successfully.');

            // return session('role') == 1
            //         ? redirect()->back()->with('success', 'Order placed successfully.')
            //         : redirect()->route('order.success', $order->id)->with('success', 'Order placed successfully.');
                        
        }               
    }


    public function checkout($id) {
        $order = Order::find($id);

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );       

        $config = Configuration::first();
        $cgstPercent = $config->cgst;
        $sgstPercent = $config->sgst;

        $subtotal = $order->total;
        $cgst = ($subtotal * $cgstPercent) / 100;
        $sgst = ($subtotal * $sgstPercent) / 100;
        $shipping = 0;

        if ($order->order_type == 'Delivery') {
            $shipping = $config->shipping;
        }

        $grandTotal = $subtotal + $cgst + $sgst + $shipping;

        $razorpayOrder = $api->order->create([
            'receipt' => 'ORD-'.$order->id,
            'amount' => round($grandTotal * 100), // paise
            'currency' => 'INR',
        ]);

        $order->update([
            'razorpay_order_id' => $razorpayOrder['id']
        ]);

        return view('front.checkout.razorpay-checkout', compact('order', 'config'));
    }


    public function orderSuccess($orderId) {
        $order = Order::with(['items.product','seat.area'])->findOrFail($orderId);
        return view('front.checkout.success', compact('order'));
    }

    public function adminOrderSuccess($orderId) {
        $order = Order::with(['items.product','seat.area'])->findOrFail($orderId);
        return view('admin.checkout.success', compact('order'));
    }


    public function paymentSuccess(Request $request, $orderId) {
        $order = Order::findOrFail($orderId);       
        $config = Configuration::first();
        $cart = Session::get('checkout_cart');
        $paymentId = $request->payment_id;
       
        $order->update([
            'payment_status' => 'Paid',
            'payment_method' => 'Razorpay',
            'razorpay_payment_id' => $request->payment_id,            
            'status' => 'placed',
        ]);        

        Session::forget('cart');

        return view('front.checkout.success', compact('order','config'));
    }


    public function paymentFailed($orderId) {
        $order = Order::findOrFail($orderId);
        return view('front.checkout.failed', compact('order'));
    }


    public function increase(Request $request) {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity']++;
        }

        session()->put('cart', $cart);

        return response()->json(['status' => true]);
    }


    public function increaseMain($id) {
        $cart = session()->get('cart', []);

        $cartKey = $id . '_default';
        
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        }
        session()->put('cart', $cart);
        return response()->json(['status' => true]);        
    }
    

    public function decrease(Request $request) {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {

            if ($cart[$request->id]['quantity'] > 1) {
                $cart[$request->id]['quantity']--;
            } else {
                unset($cart[$request->id]); // remove item
            }
        }

        session()->put('cart', $cart);

        return response()->json(['status' => true]);
    }


    public function invoiceCustoerPdf($id){
        $order = Order::with(['items.product'])->findOrFail($id);
        $config = Configuration::first();
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order','config'))->setPaper([0, 0, 250, 800], 'portrait');

        return $pdf->download(
            'invoice_'.$order->id.
            '_table_'.$order->seat?->table.
            '_'.$order->seat?->area?->area_name.            
            '.pdf'
        );
    }


    public function increaseKotCart($seatId, $productId) {
        $cart = session()->get('kot_cart', []);

        // increase qty
        if(isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        }        

        foreach ($cart as $key => $item) {
            if (
                $item['seat_id'] == $seatId &&
                $item['product_id'] == $productId
            ) {
                $cart[$key]['quantity']++;
                $qty = $cart[$key]['quantity'];
                break;
            }
        }

        session()->put('kot_cart', $cart);          

        return response()->json([       
            'success' => true,
            'message' => 'Quantity updated',     
            'qty' => $cart[$productId]['quantity'],            
            'kotCount' => collect($cart)->sum('quantity'),
            'kotTotal' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
        ]);        
    }


    public function decreaseKotCart($seatId, $productId) {
        $cart = session()->get('kot_cart', []);        
       
        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] <= 1) {

                return response()->json([
                    'status' => false,
                    'qty' => 1
                ]);
            }

            $cart[$productId]['quantity']--;

            session()->put('kot_cart', $cart);            

            return response()->json([                
                'qty' => $cart[$productId]['quantity'],
                'kotCount' => collect($cart)->sum('quantity'),
                'kotTotal' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),                 
            ]);
        }
    }
    

     public function KotRemoveCart($id) {
        $kot_cart = session()->get('kot_cart', []);

        if (isset($kot_cart[$id])) {
            unset($kot_cart[$id]);
            session()->put('kot_cart', $kot_cart);
        }

        return back()->with('success', 'Item deleted from cart');
    }

    public function clearKOT(){
        session()->forget('kot_cart');
        return back()->with('success', 'KOT cleared successfully.');        
    }

}