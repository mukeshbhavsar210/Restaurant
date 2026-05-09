<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Seating;
use App\Models\Table;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function index(Request $request){
        $seatings = Seating::orderBy('table_name','ASC')->get();
        $areas = Area::orderBy('area_name','ASC')->get();
        
        $data = [];
        $data['seatings'] = $seatings;
        $data['areas'] = $areas;

        return view('admin.qr_codes.list', $data);      
    }



    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',           
        ]);

        if ($validator->passes()) {
            $menu = new Seating();
            $menu->area_id = $request->area;
            $menu->name = $request->name;            
            $menu->capacity = $request->capacity;
            $menu->save();

            $request->session()->flash('success', 'Table added successfully');

            return response([
                'status' => true,
                'message' => 'Table added successfully',
            ]);

        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function edit($id, Request $request){

        $subCategory = Menu::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('menu.index');
        }

        $categories = Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;
        return view("admin.qr_codes.edit", $data);
    }

    public function update($id, Request $request){

        $subCategory = SubCategory::find($id);

        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            $request->session()->flash('success', 'Sub Category updated successfully');

            return response([
                'status' => true,
                'message' => 'Sub Category updated successfully',
            ]);

        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    

    public function destroy($id, Request $request){
        $subCategory = Menu::find($id);

        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $subCategory->delete();

        $request->session()->flash('success', 'Sub Category deleted successfully');

        return response([
            'status' => true,
            'message' => 'Sub Category deleted successfully',
        ]);
    }
}
