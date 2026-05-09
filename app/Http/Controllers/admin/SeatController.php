<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Seat;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class SeatController extends Controller
{
    public function index(Request $request){
        $seatings = Seat::orderBy('table_name','ASC')->get();
        $areas = Area::orderBy('area_name','ASC')->with('seating')->get();

        $areaCount = DB::table('areas')
                    ->select(DB::raw('count(*) as total_tables'))
                    ->get()[0]->total_tables;

        $data = [];
        $data['seatings'] = $seatings;
        $data['areas'] = $areas;
        $data['areaCount'] = $areaCount;

        return view('admin.tables.list', $data);      
    }


    function getAreas(){
        return Area::orderBy('area_name','ASC')->with('seating')->take(4)->orderBy('id','DESC')->get();
    }

   
    public function store(Request $request){
        //QR CODE
        $number = mt_rand(1000000000, 9999999999);        
        $request['product_code'] = $number;

        $validator = Validator::make($request->all(), [
             
        ]);
       
        if ($validator->passes()) {
            $menu = new Seat();
            $menu->area_id = $request->area_name;
            $menu->table_name = $request->table_name;
            $menu->table_slug = $request->slug;          
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

    // public function productCodeExists($number){
    //     return Seat::whereProductCode($number)->exists();
    // }

    public function edit($id, Request $request){

        $subCategory = Seat::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('menu.index');
        }

        $categories = Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;
        return view("admin.tables.edit", $data);
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
