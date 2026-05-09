<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Flash;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BranchController extends Controller
{
    public function index(Request $request){
        $branches = Branch::all();
            
        return view('admin.branches.index', compact('branches'));
    }

   


    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',            
        ]);

        if ($validator->passes()) {
            $area = new Area();
            $area->name = $request->name;            
            $area->save();

            $request->session()->flash('success', 'Category added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }



    public function edit($areaId, Request $request){
        $area = Area::find($areaId);

        if (empty($area)) {
            return redirect()->route('areas.index');
        }

        return view('admin.areas.edit', compact('area'));
    }



    public function update($areaId, Request $request){
        $area = Area::find($areaId);
        if (empty($area)) {
            $request->session()->flash('error', 'area not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'area not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',            
        ]);

        if ($validator->passes()) {
            $area->name = $request->name;            
            $area->save();

            $request->session()->flash('success', 'Area updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Area updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($areaId, Request $request){
        $area = Area::find($areaId);

        if(empty($area)){
            $request->session()->flash('error', 'Area not found');
            return response()->json([
                'status' => true,
                'message' => 'Area not found'
            ]);            
        }

        $area->delete();
        $request->session()->flash('success', 'Area deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Area deleted successfully'
        ]);
    }
}
