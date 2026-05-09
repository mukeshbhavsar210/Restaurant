<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller {
    public function category(Request $request, $categorySlug = null) {       
        $categorySelected = ' ';

        $categories = Category::orderBy("name","ASC")->with('menus')->get();        
        $products = Product::where('status',1);

        if(!empty($categorySlug)) {
            $menus = Category::where('slug',$categorySlug)->first();
            $products = $products->where('category_id',$menus->id);
            $categorySelected = $menus->id;
        }

        $data['categories'] = $categories;
        $data['products'] = $products;        
        $data['categorySelected'] = $categorySelected;
        
        return view('front.shop.index',$data);
    }


    public function product($slug){
        $product = Product::where('slug',$slug)->first();

        if($product == null){
            abort(404);
        }

        //Fetch Related products
        // $relatedProducts = [];
        // if ($product->related_products != '') {
        //     $productArray = explode(',',$product->related_products);
        
        // }

        $data['product'] = $product;
        //$data['relatedProducts'] = $relatedProducts;

        return view('front.products.index',$data);
    }
}
