<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Country;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Page;
use App\Models\Seat;
use App\Models\Configuration;
use App\Models\View;
use App\Models\Theme;
use Illuminate\Support\Facades\Mail;

function getCategories() {
    return Category::orderByRaw("
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

function getProducts(){
    return Menu::orderBy('name','ASC')->orderBy('id','DESC')->get();
} 

function getTheme(){
    return Theme::get();
} 

function gridTableView(){
    return View::get();
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
