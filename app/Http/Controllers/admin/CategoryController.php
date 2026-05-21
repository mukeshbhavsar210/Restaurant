<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\CategoryMenu;
use App\Models\Menu;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller {

    public function index(Request $request) {
        $categories = Category::withCount(['menus'])->orderBy('order_list', 'ASC')->latest();
        $totalCategories = Category::count();        

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where(
                'name',
                'like',
                '%'.$request->get('keyword').'%'
            );
        }

        $categories = $categories->paginate(10);


        //$categories = Category::orderBy('name','ASC')->get();
        $menus = Menu::orderBy('name','ASC')->get();

        $totalMenu = DB::table('categories')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $menuCount = DB::table('menus')
                    ->select(DB::raw('count(*) as total_menu'))
                    ->get()[0]->total_menu;
    
        $data['categories'] = $categories;        
        $data['totalCategories'] = $totalCategories;           
        $data['menus'] = $menus;
        $data['menuCount'] = $menuCount;
        
        $data['categoryForm'] = [
            'title' => 'Create Category',
            'button_name' => 'Add Category',
            'modal_id' => 'createCategoryModal',            

            'formConfig' => [
                'action' => route('categories.store'),
                'method' => 'POST',
                'button' => 'Add Category',
                'modal' => 'drawer right-align',
                'modalSize' => '',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Category Name',
                        'placeholder' => 'Enter Category Name',
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
                        'id'    => 'slug',
                        'col' => 'd-none'
                    ],
                    [
                        'type' => 'file',
                        'name' => 'image',
                        'label' => 'Item Picture',
                        'col' => 'col-md-12 col-12'
                    ],
                ]
            ]
        ];  

        $vegDetails = ['NA', 'Veg', 'Non-Veg', 'Egg'];

        $data['menuForm'] = [
            'title' => 'Create Menu',
            'button_name' => 'Create Menu',
            'modal_id' => 'createMenuModal',            

            'formConfig' => [
                'action' => route('menu.store'),
                'method' => 'POST',
                'button' => 'Create Menu',
                'modal' => 'drawer right-align',
                'modalSize' => '',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Category Name',
                        'placeholder' => 'Enter Category Name',
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
                        'id'    => 'slug',
                        'col' => 'd-none'
                    ],
                    [
                        'type' => 'checkbox',
                        'name' => 'categories',
                        'label' => 'Select Category',
                        'options' => $categories,
                        'option_value' => 'id',
                        'option_text' => 'name',                        
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'veg_radio',
                        'name' => 'veg_nonveg',
                        'label' => 'Veg/Non-Veg?',
                        'options' => $vegDetails, 
                        'checked' => 0,                       
                        'col' => 'col-md-12'
                    ]
                ]
            ]
        ];  

        return view('admin.category.list', $data);
    }
    
   
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->passes()) {
            $data = new Category();
            $data->name = $request->name;
            $data->slug = $request->slug;

            //Image upload
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $fileName = $data->slug.'_'.time().'.'.$extenstion;
            $path = public_path().'/uploads/category/'.$fileName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $image->toJpeg(100)->save($path);
            $image->cover(300,300)->save($path);
            $data->image = $fileName;
            $data->save();

            return redirect()->route('categories.index')->with('success','Category added successfully.');
        } else {
            return redirect()->route('categories.index')->withInput()->withErrors($validator);
        }            
    }




    public function store_menu(Request $request){
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            //'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);   

        if($validator->passes()){
            $data = new Menu();
            $data->name = $request->name;
            $data->slug = $request->slug;            

            //Image upload
            if ($request->hasFile('image')) { 
                // $file = $request->file('image');
                // $extenstion = $file->getClientOriginalExtension();
                // $fileName = $data->name.'_'.time().'.'.$extenstion;
                // $path = public_path().'/uploads/logo/'.$fileName;
                // $manager = new ImageManager(new Driver());
                // $image = $manager->read($file);
                // $image->toJpeg(80)->save($path);
                // $image->cover(300,300)->save($path);
                // $data->image = $fileName;
            }
            
            $data->save();

            return redirect()->route('configurations.index')->with('success','Configurations added successfully.');
        } else {
            return redirect()->route('configurations.index')->withInput()->withErrors($validator);
        }
    }


    public function edit($categoryId, Request $request){
        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.index');
        }

        return view('admin.category.edit', compact('category'));
    }



    public function update($categoryId, Request $request){

        $category = Category::find($categoryId);

        if (empty($category)) {
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $category->image;

            // Save image here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);

                //Generate image thumbnail
                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                File::copy($sPath,$dPath);

                $category->image = $newImageName;
                $category->save();

                //Delete old image
                File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/category/'.$oldImage);
            }

            $request->session()->flash('success', 'Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($categoryId, Request $request){
        $category = Category::find($categoryId);

        if(empty($category)){
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);
            //return redirect()->route('categories.index');
        }

        //Delete old image
        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);

        $category->delete();

        $request->session()->flash('success', 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function delete_category($id){
        $category = Category::find($id);
        File::delete(public_path().'/uploads/category/'.$category->image);
        $category->delete();

        return redirect()->route('categories.index')->with('success','Category deleted successfully.');
    }



    public function menu_store(Request $request){
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
        ]);   

        if($validator->passes()){
            $menu = new Menu();
            $menu->name = $request->name;
            $menu->slug = $request->slug;
            $menu->veg_nonveg = $request->veg_nonveg;
            $menu->save();             

            // Categories
            $categories = $request->categories;

            // If no category selected
            if (empty($categories)) {
                $categories = [$request->default_category_id];
            }

            // Save categories
            $menu->categories()->sync($categories);

            //$data->category_id = $request->category;
            $menu->save(); // 🔥 Save first to get ID                    

            return redirect()->route('categories.index')->with('success','Menu Item added successfully.');
        } else {
            return redirect()->route('categories.index')->withInput()->withErrors($validator);
        }
    }


    public function menu_edit($id, Request $request){
        $menu = Menu::find($id);

        // if (empty($product)) {
        //     return redirect()->route('products.index')->with('error','Product not found');
        // }

        //Fetch Product Images
        // $subCategories = Menu::where('category_id',$product->category_id)->get();
        $categories = Category::orderBy('name','ASC')->get();

        $data = [];
        
        $data['categories'] = $categories;
        $data['menu'] = $menu;
        //$data['subCategories'] = $subCategories;
             
        return view('admin.category.edit',$data);
    }


    // public function edit($id, Request $request){
    //     $subCategory = Menu::find($id);
    //     if(empty($subCategory)){
    //         $request->session()->flash('error','Record not found');
    //         return redirect()->route('categories.index');
    //     }

    //     $categories = Category::orderBy('name','ASC')->get();
    //     $data['categories'] = $categories;
    //     $data['subCategory'] = $subCategory;
    //     return view("admin.category.edit", $data);
    // }

    public function menu_update($id, Request $request){

        $data = Menu::find($id);

        if(empty($data)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->passes()) {
            $data->name = $request->name;
            $data->slug = $request->slug;
            $data->category_id = $request->category;

            $oldImage = $data->image;

             //Image upload
             if ($request->hasFile('image')) { 
                $file = $request->file('image');
                $extenstion = $file->getClientOriginalExtension();
                $fileName = $data->slug.'_'.time().'.'.$extenstion;
                $path = public_path().'/uploads/menu/'.$fileName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image->toJpeg(80)->save($path);
                $image->cover(300,300)->save($path);
                $data->image = $fileName;
             }
             $data->save();

            File::delete(public_path().'/uploads/menu/'.$oldImage);            
 
             return redirect()->route('categories.index')->with('success','Menu updated successfully.');
         } else {
             return redirect()->route('categories.index')->withInput()->withErrors($validator);
         }
    }

    public function menu_deleteAll(Request $request){
        $ids = $request->ids;
        Menu::whereIn('id',$ids)->delete();

        return response()->json(["success"=> "Menu deleted"]);        
    }
    
    public function menu_delete($id){
        $menu = Menu::find($id);
        File::delete(public_path().'/uploads/menu/'.$menu->image);        
        $menu->delete();

        return redirect()->route('categories.index')->with('success','Menu deleted successfully.');
    }    
}
