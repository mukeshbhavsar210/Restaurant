<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    public function index(){
        $orders_count = DB::table('orders')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $users_count = DB::table('users')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $total_sale = Order::all()->sum('total');

        $sales_count = DB::table('orders')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $total_categories = DB::table('categories')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $total_menu = DB::table('menus')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $total_items = DB::table('products')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $data['orders_count'] = $orders_count;
        $data['users_count'] = $users_count;
        $data['sales_count'] = $sales_count;
        $data['total_sale'] = $total_sale;
        $data['total_categories'] = $total_categories;
        $data['total_menu'] = $total_menu;
        $data['total_items'] = $total_items;

        return view('admin.dashboard', $data);

        //$admin = Auth::guard('admin')->user();
        //echo 'Welcome '.$admin->name.' <a href="'.route('admin.logout').'">Logout</a>';
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
