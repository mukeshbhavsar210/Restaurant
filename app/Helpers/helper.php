<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Country;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Page;
use App\Models\Seat;
use App\Models\Area;
use App\Models\Configuration;
use App\Models\View;
use App\Models\Theme;
use Illuminate\Support\Facades\Mail;

    function getCategories()
    {
        return Category::whereHas('products')
            ->orderByRaw("
                CASE 
                    WHEN name = 'Popular' THEN 0
                    ELSE 1
                END
            ")
            ->orderBy('name', 'ASC')
            ->with('menus')
            ->take(10)
            ->get();
    }

    if (!function_exists('configData')) {
        function configData()
        {
            return Configuration::first();
        }
    }

    if (!function_exists('seatData')) {
        function seatData()
        {
            return Seat::with('area')->latest()->get();
        }
    }

    if (!function_exists('areaData')) {
        function areaData()
        {
            return Area::get();
        }
    }

    if (!function_exists('getCartCount')) {
        function getCartCount() {
            $cart = session()->get('cart', []);
            return array_sum(array_column($cart, 'quantity'));
        }
    }

    if (!function_exists('getProductQty')) {
        function getProductQty($productId) {
            $cart = session()->get('cart', []);
            return isset($cart[$productId])
                ? $cart[$productId]['quantity']
                : 0;
        }
    }

    function getCartTotal() {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }


    function getCartCount() {
        return count(session()->get('cart', []));
    }


function getProducts(){
    return Menu::orderBy('name','ASC')->orderBy('id','DESC')->get();
} 

function getSeats(){
    return Seat::orderBy('table_name','ASC')->with('area')->orderBy('id','DESC')->get();
}  

function orderEmail($orderId, $userType="customer"){
    $order = Order::where('id',$orderId)->with('items')->first();

    if($userType == 'customer'){
        $subject = 'Thanks for your order';
        $email = $order->email;
    } else {
        $subject = 'You have received an order';
        $email = env('ADMIN_EMAIL');
    }

    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $userType,
    ];

    Mail::to($email)->send(new OrderEmail($mailData));
}

function getCountryInfo($id){
    return Country::where('id',$id)->first();
}

function staticPages(){
    $pages = Page::orderBy('name','ASC')->get();
    return $pages;
}
?>
