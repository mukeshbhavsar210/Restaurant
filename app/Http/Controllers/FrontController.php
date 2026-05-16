<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seat;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller {
    public function index() {
        $popularCategory = Category::where('name', 'Popular')
            ->with(['products' => function ($query) {
                $query->latest();
            }])
            ->first();

        $products = Product::with('category', 'variants')->latest()->get();
        $areas = Area::with('seat')->latest()->get();
        $seats = Seat::with('area')->latest()->get();

        // cart session
        $cart = session()->get('cart', []);

       //dd(session('cart'));

        return view('front.home.index', [
            'products' => $products,
            'popularProducts' => $popularCategory?->products ?? collect(),
            'popularCategory' => $popularCategory,
            'areas' => $areas,
            'seats' => $seats,            
            //'qty' => getCartQty(),
            'total' => getCartTotal(),
            'cartCount' => getCartCount(),
            //'productQty' => getProductQty(),
        ]);        
    }


    public function category($categorySlug = null, $menuSlug = null) {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $menus = $category->menus;
        $seats = Seat::orderBy('id','DESC')->get();  
        $variants = Variant::get();
        $query = Product::query();

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

        // product qty array
        $cart = session()->get('cart', []);

       //dd($qty);
       //dd(session('cart'));

       return view('front.shop.index', [
            'products' => $products,
            'category' => $category,
            'menus' => $menus,
            'seats' => $seats,
            'variants' => $variants,
            'menuSlug' => $menuSlug,
            //'qty' => getCartQty(),
            'total' => getCartTotal(),
            'cartCount' => getCartCount(),
        ]);       
    }

    //Add to Cart
    public function addToCart(Request $request, $id) {
        $product = Product::findOrFail($id);        

        if (!$product) {
            abort(404);
        }

        // Variant values
        $variantName  = $request->variant_name ?? null;
        $variantPrice = $request->variant_price ?? $product->price;

        // Unique cart key for variants
        $cartKey = $id . '_' . ($variantName ?? 'default');

        $cart = session()->get('cart', []);

        // If already exists        
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "product_id"    => $product->id,
                "name"          => $product->name,
                "quantity"      => 1,                
                "price"         => $variantPrice,
                "variant_name"  => $variantName,
                "image"         => $product->image
            ];
        }

        session()->put('cart', $cart);

        // total
        $total = 0;

        foreach($cart as $item){
            $total += $item['price'] * $item['quantity'];
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product added to cart successfully!'
            ]);
        }        

        return response()->json([
            'status' => true,
            'qty' => $cart[$id]['quantity'] ?? 0,
            'cartCount' => count($cart),
            'cartTotal' => $total,
            'message' => 'Added to cart'
        ]);
    }

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
        ]);
    }


    public function decreaseCart($id) {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            // stop at 1
            if ($cart[$id]['quantity'] <= 1) {

                return response()->json([
                    'status' => false,
                    'qty' => 1
                ]);
            }

            $cart[$id]['quantity']--;

            session()->put('cart', $cart);

            return response()->json([
                'status' => true,
                'qty' => $cart[$id]['quantity'],
                'cartCount' => getCartCount(),
                'cartTotal' => getCartTotal(),
            ]);
        }
    }

    // public function decreaseCart($id) {
    //     $cart = session()->get('cart', []);

    //     if(isset($cart[$id])) {

    //         $cart[$id]['quantity']--;

    //         // remove if qty = 0
    //         if($cart[$id]['quantity'] <= 0){
    //             unset($cart[$id]);
    //         }
    //     }

    //     session()->put('cart', $cart);

    //     // total
    //     $total = 0;

    //     foreach($cart as $item){
    //         $total += $item['price'] * $item['quantity'];
    //     }

    //     return response()->json([
    //         'qty' => $cart[$id]['quantity'] ?? 0,
    //         'cartCount' => count($cart),
    //         'cartTotal' => $total,
    //         'status' => true,
    //         'cartCount' => getCartCount(),
    //         'productQty' => getProductQty($id),
    //     ]);
    // }

    

    //Wishlist page
    public function wishlist() {
        $products = Product::orderBy('id','DESC')->with('product_images')->get();
        $data = [
            'products'=> $products,            
        ];        

        return view('front.home.wishlist', $data);        
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
        $seats = Seat::orderBy("table_name","ASC")->with('area')->get(); 
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
        
        return view('front.home.restaurant',$data);
    }

    //remove Item from cart
    // public function removeCartItem(Request $request) {
    //     if ($request->id) {
    //         $cart = session()->get('cart');
    //         if (isset($cart[$request->id])) {
    //             unset($cart[$request->id]);
    //             session()->put('cart', $cart);
    //         }

    //         session()->flash('success', 'Product removed successfully');
    //     }
    // }

    //Clear Cart
    public function clearCart(){
        session()->forget('cart');
        return redirect()->back();
    }


    //Wishlist
    public function addToWish($id){
        $product = Product::find($id);
        
        if (!$product) {
            abort(404);
        }

        $cart = session()->get('wishlist');

        if (!$cart) {
            $cart = [
                $id => [
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    "image" => $product->image,        
                ]
            ];

            session()->put('wishlist', $cart);
            return redirect()->back()->with('success', 'Product added to wishlist successfully!');
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('wishlist', $cart);
            return redirect()->back()->with('success', 'Product added to wishlist successfully!');
        }

        $cart[$id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->price, 
            "image" => $product->image,            
        ];

        session()->put('wishlist', $cart);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Product added to wishlist successfully!']);
        }
        return redirect()->back()->with('success', 'Product added to wishlist successfully!');
    }


    public function removeWishlistItem(Request $request) {
        if ($request->id) {
            $cart = session()->get('wishlist');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('wishlist', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }

    public function clearWishlist(){
        session()->forget('wishlist');
        return redirect()->route('front.home')->with('success','Wishlist cleared successfully.');
    }


    public function placeOrder(Request $request) {
        $validator = Validator::make($request->all(), [

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
        if ($request->order_type == 'delivery') {
            $total += 50;
        }

        $order = new Order();
        $order->order_type = $request->order_type;
        $order->session_id = session('session_id');
        $order->notes = $request->notes;
        $order->ready_time = $request->ready_time;

        // Dinein
        if ($request->order_type === 'dinein') {
            $order->seat_id = $request->seat_id;
            $order->dinein_time = $request->dinein_time;
        }

        // Takeaway & Delivery
        if (
            $request->order_type === 'takeaway' ||
            $request->order_type === 'delivery'
        ) {

            $order->customer_name = $request->name;
            $order->customer_email = $request->email;
            $order->customer_phone = $request->phone;
        }

        // Delivery only
        if ($request->order_type === 'delivery') {
            $order->delivery_address = $request->address;
        }

        $order->total_amount = $total;

        $order->save();

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

        // Clear cart
        Session::forget('cart');

        // Store flash message
        session()->flash('success', 'Order placed successfully');

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully'
        ]);
    }


    public function increase(Request $request) {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity']++;
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
}