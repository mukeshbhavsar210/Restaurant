<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryMenu;
use App\Models\Menu;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductView;
use App\Models\TempImage;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller {

    public function index(Request $request){
        $products = Product::with('menu')->latest('id');
        $categories = Category::orderBy('name','ASC')->get();  
        
        $menuCount = DB::table('products')
                    ->select(DB::raw('count(*) as total_products'))
                    ->get()[0]->total_products;

        $products = $products->paginate();

        $data['products'] = $products;
        $data['categories'] = $categories;  
        $data['menuCount'] = $menuCount;  
        
        $data['productForm'] = [
            'title' => 'Create Product',            
            'modal_id' => 'createProductModal',            

            'formConfig' => [
                'action' => route('products.store'),
                'method' => 'POST',
                'button' => 'Create Product',                
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Product Name',
                        'required' => true,
                        'placeholder' => 'Enter Product Name',
                        'class' => 'slug-source',
                        'data'  => [
                            'target' => '#slug'
                        ], 
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'slug',
                        'label' => 'Slug',
                        'required' => true,
                        'id'    => 'slug',
                        'col' => 'd-none'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'category',
                        'label' => 'Category',
                        'required' => true,
                        'options' => $categories,
                        'option_value' => 'id',
                        'option_text' => 'name',
                        'col' => 'col-md-6 col-12'
                    ],
                    [
                        'type' => 'selectLoad',
                        'name' => 'menu_id',
                        'label' => 'Menu Item',
                        'required' => true,
                        'id' => 'menu_item',
                        'options' => $categories,
                        'option_value' => 'menu_item',
                        'option_text' => 'name',                        
                        'col' => 'col-md-6 col-12'
                    ],
                    [
                        'type' => 'price',
                        'name' => 'price',
                        'label' => 'Price',
                        'required' => true,
                        'placeholder' => 'Price',                        
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'variants',
                        'name' => 'categories',
                        'required' => true,
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'textarea',
                        'name' => 'description',
                        'label' => 'Description',
                        'required' => true,
                        'placeholder' => 'Description',
                        'col' => 'col-md-12 col-12'
                    ],
                    [
                        'type' => 'dropzone',
                        'name' => 'image', 
                        'label' => 'Images',
                        'required' => true,                       
                        'col' => 'col-md-12 col-12'
                    ],
                ]
            ]
        ]; 
        
        return view ('admin.products.list', $data);
    }

    public function product_create(){
        $data = [];
        $categories = Category::orderBy('name','ASC')->get();        
        $data['categories'] = $categories;

        return view('admin.products.create', $data);
    }


    public function product_store(Request $request){
        $rules = [
            'name' => 'required',                            
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            $product = new Product;
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->category_id = $request->category;
            $product->menu_id = $request->menu_id;
            $product->description = $request->description;
            $product->price = $request->price;         
            $product->save();

            if (!empty($request->variants)) {
                foreach ($request->variants as $variant) {
                    if (
                        empty($variant['name']) &&
                        empty($variant['price'])
                    ) {
                        continue;
                    }

                    $productVariant = new Variant();
                    $productVariant->product_id = $product->id;
                    $productVariant->name = $variant['name'];
                    $productVariant->price = $variant['price'];
                    $productVariant->save();
                }
            }

            if (!empty($request->image_array)) {                
                foreach ($request->image_array as $temp_image_id) {  
                    $tempImageInfo = TempImage::find($temp_image_id);
                    if (!$tempImageInfo) {
                        continue;
                    }

                    $ext = pathinfo($tempImageInfo->name, PATHINFO_EXTENSION);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;                    
                    $productImage->image = "NULL";
                    $productImage->save();

                    $imageName = $product->id. '-' .$product->name. '-' .$productImage->id.'.'.$ext;                    
                    $productImage->image = $imageName;
                    $productImage->save();

                    //Large Image
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/large/'.$imageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sourcePath);
                    $image->cover(500,350);
                    $image->save($destPath);

                    //Generate Thumnail
                    $destPath = public_path().'/uploads/product/small/'.$imageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sourcePath);
                    $image->cover(200,200);
                    $image->save($destPath);
                }
            }
            
            $product->save();

            return redirect()->route('products.index')->with('success','Product added successfully.');
        } else {
            return redirect()->route('products.index')->withInput()->withErrors($validator);
        }
    }


    public function view_store(Request $request){
        $rules = [
                                    
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            $product = new ProductView;
            $product->toggleActive()->save();          
            //$product->name = $request->view;  
            //$product->save();

            $request->session()->flash('success','View set successfully');

            return response()->json([
                'status' => true,
                'message' => 'View set successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    


    public function product_update($id, Request $request){
        $product = Product::find($id);
       
        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            $product->name = $request->name;            
            $product->slug = $request->slug;     
            $product->category_id = $request->category;
            $product->menu_id = $request->menu_id;
            $product->price = $request->price; 
            $product->description = $request->description;                       
            $product->save();

            if (!empty($request->variants)) {
                foreach ($request->variants as $variant) {
                    if (
                        empty($variant['name']) &&
                        empty($variant['price'])
                    ) {
                        continue;
                    }

                    $productVariant = new Variant();
                    $productVariant->product_id = $product->id;
                    $productVariant->name = $variant['name'];
                    $productVariant->price = $variant['price'];
                    $productVariant->save();
                }
            }

            if (!empty($request->image_array)) {                
                foreach ($request->image_array as $temp_image_id) {  
                    $tempImageInfo = TempImage::find($temp_image_id);
                    if (!$tempImageInfo) {
                        continue;
                    }

                    $ext = pathinfo($tempImageInfo->name, PATHINFO_EXTENSION);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;                    
                    $productImage->image = "NULL";
                    $productImage->save();

                    $imageName = $product->id. '-' .$product->name. '-' .$productImage->id.'.'.$ext;                    
                    $productImage->image = $imageName;
                    $productImage->save();

                    //Large Image
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/large/'.$imageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sourcePath);
                    $image->cover(500,400);
                    $image->save($destPath);

                    //Generate Thumnail
                    $destPath = public_path().'/uploads/product/small/'.$imageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sourcePath);
                    $image->cover(250,150);
                    $image->save($destPath);
                }
            }

            return redirect()->route('products.index')->with('success','Product updated successfully.');
        } else {
            return redirect()->route('products.index')->withInput()->withErrors($validator);
        }
    }


    public function product_delete($id, Request $request){
        $product = Product::find($id);

        if (empty($product)) {
            $request->session()->flash('error','Product not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $productImages = ProductImage::where('product_id',$id)->get();

        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path('uploads/product/large/'.$productImage->image));
                File::delete(public_path('uploads/product/small/'.$productImage->image));
            }

            ProductImage::where('product_id',$id)->delete();
        }

        $product->delete();

        $request->session()->flash('success','Product deleted successfully');

        return redirect()->route('products.index')->with('success','Product deleted successfully.');
    }



    public function getProducts(Request $request){

        $tempProduct = [];

        if($request->term != ""){
            $products = Product::where('title','like','%'.$request->term.'%')->get();

            if ($products != null){
                foreach ($products as $product){
                    $tempProduct[] = array(
                        'id' => $product->id,
                        'text' => $product->title,
                    );
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true,
        ]);


    }
}
