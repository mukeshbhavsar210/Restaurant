<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Menu;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class MenuController extends Controller {
    public function index(Request $request){
        $categories = Category::orderBy('name','ASC')->get();
        $menus = Menu::orderBy('name','ASC')->get();

        $totalMenu = DB::table('categories')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $menuCount = DB::table('menus')
                    ->select(DB::raw('count(*) as total_menu'))
                    ->get()[0]->total_menu;

        // $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
        //     ->latest('sub_categories.id')
        //     ->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        // if(!empty($request->get('keyword'))){
        //     $subCategories = $subCategories->where('sub_categories.name', 'like', '%'.$request->get('keyword').'%');
        //     $subCategories = $subCategories->orWhere('categories.name', 'like', '%'.$request->get('keyword').'%');
        // }

        $data = [];
        $data['categories'] = $categories;
        $data['menus'] = $menus;
        $data['menuCount'] = $menuCount;
        
        //dd($menuCount);

        return view('admin.category.list', $data);      
    }

    
    public function store(Request $request){
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

            // Image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();

                $fileName = $menu->id . '_' . $menu->slug . '.' . $extension;

                $path = public_path('/uploads/menu/' . $fileName);

                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);

                $image->toJpeg(100)->save($path);
                $image->cover(400, 300)->save($path);

                $menu->image = $fileName;
                $menu->save(); // update with image
            }            

            return redirect()->route('categories.index')->with('success','Menu Item added successfully.');
        } else {
            return redirect()->route('categories.index')->withInput()->withErrors($validator);
        }
    }


    public function edit($id, Request $request){
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
    //         return redirect()->route('menu.index');
    //     }

    //     $categories = Category::orderBy('name','ASC')->get();
    //     $data['categories'] = $categories;
    //     $data['subCategory'] = $subCategory;
    //     return view("admin.category.edit", $data);
    // }

    public function update($id, Request $request){

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

    public function deleteAll(Request $request){
        $ids = $request->ids;
        Menu::whereIn('id',$ids)->delete();

        return response()->json(["success"=> "Menu deleted"]);        
    }
    
    public function delete($id){
        $menu = Menu::find($id);
        File::delete(public_path().'/uploads/menu/'.$menu->image);        
        $menu->delete();

        return redirect()->route('categories.index')->with('success','Menu deleted successfully.');
    }    

}
