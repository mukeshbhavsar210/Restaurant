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
use App\Models\OrderItem;
use App\Models\Theme;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
//use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ConfigurationController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
    return [
            // new Middleware('permission:view permissions', only: ['index']),
            // new Middleware('permission:edit permissions', only: ['edit']),
            // new Middleware('permission:create permissions', only: ['create']),
            // new Middleware('permission:delete permissions', only: ['destroy']),
        ];
    }

    public function index(){
        $configurations = Configuration::get();
        $payments = Payment::get();        
        $branches = Area::withCount('seat as total_seats')->with('seats')->get();
        $theme = Theme::get();
        $areas = Area::orderBy('area_name','ASC')->get();
        $seats = Seat::where('area_id',NULL)->with('seat')->get();
        $tableRunning = OrderItem::with('seat')->get();

        $totalTable = DB::table('seats')
                    ->select(DB::raw('count(*) as total_tables'))
                    ->get()[0]->total_tables;

        $totalArea = DB::table('areas')
                    ->select(DB::raw('count(*) as total_tables'))
                    ->get()[0]->total_tables;

        $tableIndividual = DB::table('seats')
                    //->join('areas','seatings.area_id','=','areas.id')
                    ->select(DB::raw('count(*) as number'), 'area_id')
                    ->groupBy('area_id')
                    ->get()[0]->number;

        //$seatings = $seatings->paginate(10);

        //dd($branches);

        return view("admin.configurations.list", [
            'configurations' => $configurations,
            'branches' => $branches,
            'payments' => $payments,
            'theme' => $theme,
            'areas' => $areas,
            'seats' => $seats,
            'tableIndividual' => $tableIndividual,
            'totalTable' => $totalTable,
            'totalArea' => $totalArea,
            'tableRunning' => $tableRunning,
        ]);
    }

    public function configurations_create(){        
        return view("admin.configurations.create");
    }

    public function configurations_edit($id, Request $request){
        $configuration = Configuration::find($id);

        return view('admin.configurations.edit', compact('configuration'));
    }

    public function configurations_update(Request $request) {
        $config = Configuration::find($request->id);

        if (!$config) {
            return redirect()->back()
                ->with('error', 'Configuration not found');
        }

        $config->name = $request->name;
        $config->value = $request->value;

        $config->save();

        return redirect()->back()
            ->with('success', 'Configuration updated successfully');
    }

    // public function configurations_update(Request $request){
    //     $configurations = Configuration::first()->update($request->all());
    //     //$configurations->name = $request->name;
    //     // $configurations->logo = $request->logo;
    //     // $configurations->email = $request->email;
    //     // $configurations->phone = $request->phone;
    //     // $configurations->address = $request->address;
    //     // $configurations->theme = $request->theme;
    //     $configurations->save();

    //     return redirect()->route('configurations.index')->with('success','Configuration updated successfully.');
    // }

    public function configurations_store(Request $request){
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
            if ($request->hasFile('logo')) { 
                $file = $request->file('logo');
                $extenstion = $file->getClientOriginalExtension();
                $fileName = $data->name.'.'.$extenstion;
                $path = public_path().'/uploads/logo/'.$fileName;
                $manager = new ImageManager(new Driver());
                $logo = $manager->read($file);
                $logo->toJpeg(80)->save($path);
                $logo->cover(300,300)->save($path);
                $data->logo = $fileName;
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

    public function branch_store(Request $request){
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
    
    public function branch_edit($areaId, Request $request){
        $area = Area::find($areaId);

        if (empty($area)) {
            return redirect()->route('configurations.index');
        }

        return view('admin.areas.edit', compact('area'));
    }


    public function branch_update($areaId, Request $request){
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

            $request->session()->flash('success', 'Branch updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Branch updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function branch_delete($id){
        $area = Area::find($id);
        $area->delete();

        return redirect()->route('configurations.index')->with('success','Branch deleted successfully.');
    }



    public function table_store(Request $request){
        //QR CODE
        $number = mt_rand(1000000000, 9999999999);        
        $request['product_code'] = $number;

         $validator = Validator::make($request->all(), [
            'area_id'    => 'required',
            'table_name' => 'required',
            'capacity'   => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $table = new Seat();
        $table->area_id = $request->area_id;
        $table->table_name = $request->table_name;
        $table->table_slug = Str::slug($request->table_name);
        $table->capacity = $request->capacity;
        $table->save();

        return redirect()->back()->with('success', 'Table added successfully');
       
    }

    // public function productCodeExists($number){
    //     return Seat::whereProductCode($number)->exists();
    // }    

    public function table_delete($id) {
        $table = Seat::find($id);

        if (!$table) {
            return redirect()->back()
                ->with('error', 'Seat not found');
        }

        $table->delete();

        return redirect()->route('configurations.index')
            ->with('success', 'Seat deleted successfully.');
    }
    

    public function table_destroy($id, Request $request){
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