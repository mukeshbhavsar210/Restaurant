<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Configuration;
use App\Models\Menu;
use App\Models\Payment;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
use App\Models\Theme;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
//use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\User;

class ConfigurationController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
    return [
            new Middleware('permission:view permissions', only: ['index']),
            new Middleware('permission:edit permissions', only: ['edit']),
            new Middleware('permission:create permissions', only: ['create']),
            new Middleware('permission:delete permissions', only: ['destroy']),
        ];
    }

    public function index(){
        $configurations = Configuration::get();
        $payments = Payment::get();        
        $branches = Area::get();
        $theme = Theme::get();

        return view("admin.configurations.list", [
            'configurations' => $configurations,
            'branches' => $branches,
            'payments' => $payments,
            'theme' => $theme,
        ]);
    }

    public function create(){        
        return view("admin.configurations.create");
    }

    public function edit($id, Request $request){
        $configuration = Configuration::find($id);

        return view('admin.configurations.edit', compact('configuration'));
    }

    public function update(Request $request){
        $configurations = Configuration::first()->update($request->all());
        //$configurations->name = $request->name;
        // $configurations->logo = $request->logo;
        // $configurations->email = $request->email;
        // $configurations->phone = $request->phone;
        // $configurations->address = $request->address;
        // $configurations->theme = $request->theme;
        $configurations->save();

        return redirect()->route('configurations.index')->with('success','Configuration updated successfully.');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            //'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);   

        if($validator->passes()){
            $data = new Configuration();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->address = $request->address;

            //Image upload
            if ($request->hasFile('image')) { 
                $file = $request->file('image');
                $extenstion = $file->getClientOriginalExtension();
                $fileName = $data->name.'_'.time().'.'.$extenstion;
                $path = public_path().'/uploads/logo/'.$fileName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image->toJpeg(80)->save($path);
                $image->cover(300,300)->save($path);
                $data->image = $fileName;
            }

            $data->save();

            return redirect()->route('configurations.index')->with('success','Configurations added successfully.');
        } else {
            return redirect()->route('configurations.index')->withInput()->withErrors($validator);
        }
    }


    public function store_theme(Request $request){
        $theme = new Theme();
        $theme->primary_color = $request->primary_color;
        $theme->secondary_color = $request->secondary_color;
        $theme->sidebar_color = $request->sidebar_color;
        $theme->save();

        return redirect()->route('configurations.index')->with('success','Theme added successfully.');
    }

    public function store_branch(Request $request){
        $validator = Validator::make($request->all(), [
            'area_name' => 'required',            
        ]);

        if ($validator->passes()) {
            $area = new Area();
            $area->area_name = $request->area_name;
            $area->area_slug = $request->area_slug;
            $area->save();

            $request->session()->flash('success', 'Branch added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Branch added successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function store_payment(Request $request){
        $validator = Validator::make($request->all(), [
            'your_key_id' => 'required',
        ]);

        if ($validator->passes()) {
            $payment = new Payment();
            $payment->your_key_id = $request->your_key_id;
            $payment->your_key_secret = $request->your_key_secret;
            $payment->save();

            return redirect()->route('configurations.index')->with('success','Payment Gateway added successfully.');
        } else {
            return redirect()->route('configurations.index')->withInput()->withErrors($validator);
        }
    }


    public function delete($id){
        $area = Area::find($id);
        $area->delete();

        return redirect()->route('configurations.index')->with('success','Branch deleted successfully.');
    }

}
