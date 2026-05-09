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
    public function show() {
        $popularCategory = Category::where('name', 'Popular')
            ->with(['products' => function ($query) {
                $query->latest();
            }])
            ->first();

        $products = Product::with('category','variants')->latest()->get();
        $areas = Area::with('seat')->latest()->get();
        $seats = Seat::with('area')->latest()->get();             

        return view('front.home.index', [
            'products' => $products,
            'popularProducts' => $popularCategory?->products ?? collect(),
            'popularCategory' => $popularCategory,
            'areas' => $areas,
            'seats' => $seats,
        ]);
    }



    public function index_old(Request $request, $menuSlug = null) {
        // Categories with menus
        $categories = getCategories();
        $seats = Seat::orderBy('id','DESC')->get();  

        // ✅ Default to 'popular' if no slug
        if (empty($menuSlug)) {
            $menuSlug = Category::whereRaw("LOWER(name) = 'popular'")
                ->value('slug');
        }   

        // Base query        
        $products = Product::with('category','variants')->where('status', 1);
        $variants = Variant::get();

        // ✅ Filter by menu slug (ONLY if exists)
        if (!empty($menuSlug)) {
            $products->whereHas('category', function ($q) use ($menuSlug) {
                $q->where('slug', $menuSlug);
            });
        }

        // ✅ Price filter
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $min = (int) $request->price_min;
            $max = (int) $request->price_max;

            if ($max == 1000) {
                $max = 1000000; // max cap
            }

            $products->whereBetween('price', [$min, $max]);
        }

        // ✅ Search
        if ($request->filled('search')) {
            $products->where('title', 'like', '%' . $request->search . '%');
        }

        // ✅ Sorting
        switch ($request->get('sort')) {
            case 'latest':
                $products->orderBy('id', 'DESC');
                break;

            case 'price_asc':
                $products->orderBy('price', 'ASC');
                break;

            case 'price_desc':
                $products->orderBy('price', 'DESC');
                break;

            default:
                $products->orderBy('id', 'DESC');
                break;
        }

        // Pagination
        $products = $products->orderBy('id', 'DESC')->paginate(10);        

        // Pass data
        return view('front.shop.index', [
            'categories'   => $categories,
            'products'     => $products,
            'variants'     => $variants,
            'seats'        => $seats,
            'menuSelected' => $menuSlug, 
            'priceMax'     => $request->price_max ?? 1000,
            'priceMin'     => $request->price_min ?? 0,
        ]);
    }



    public function index($categorySlug = null, $menuSlug = null) {
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

        return view('front.shop.index', compact(
            'products', 'category', 'menus', 'seats', 'variants', 'menuSlug'
        ));
    }

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
    

    //Add to Cart
    public function addToCart(Request $request, $id) {
        $product = Product::find($id);

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
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id"    => $product->id,
                "name"          => $product->name,
                "quantity"      => 1,                
                "price"         => $variantPrice,
                "variant_name"  => $variantName,
                "image"         => $product->image
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product added to cart successfully!'
            ]);
        }

        return redirect()->back()->with(
            'success',
            'Product added to cart successfully!'
        );
    }

    // public function addToCart($id){
    //     $product = Product::find($id);

    //     if (!$product) {
    //         abort(404);
    //     }

    //     $cart = session()->get('cart');

    //     if (!$cart) {
    //         $cart = [
    //             $id => [
    //                 "name" => $product->name,
    //                 "quantity" => 1,
    //                 "price" => $product->price,
    //                 //"seat" => $product->seat,   
    //                 "image" => $product->image,                 
    //             ]
    //         ];

    //         session()->put('cart', $cart);
    //         return redirect()->back()->with('success', 'Product added');
    //     }

    //     if (isset($cart[$id])) {
    //         $cart[$id]['quantity']++;
    //         session()->put('cart', $cart);
    //         return redirect()->back()->with('success', 'Product added');
    //     }

    //     $cart[$id] = [
    //         "name" => $product->name,
    //         "quantity" => 1,
    //         "price" => $product->price,
    //         //"seat" => $product->seat,
    //         "image" => $product->image            
    //     ];

    //     session()->put('cart', $cart);

    //     if (request()->wantsJson()) {
    //         return response()->json(['message' => 'Product added to cart successfully!']);
    //     }

    //     return redirect()->route('front.home')->with('success','Product added to cart successfully!');
    // }

    //remove Item from cart
    public function removeCartItem(Request $request) {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }

            session()->flash('success', 'Product removed successfully');
        }
    }

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