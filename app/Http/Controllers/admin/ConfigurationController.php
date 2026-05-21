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
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
//use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\User;
use App\Models\Page;
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
            //new Middleware('permission:view permissions', only: ['index']),
            //new Middleware('permission:edit permissions', only: ['edit']),
            //new Middleware('permission:create permissions', only: ['create']),
            //new Middleware('permission:delete permissions', only: ['destroy']),
            //new Middleware('permission:view roles', only: ['index']),
            //new Middleware('permission:edit roles', only: ['edit']),
            //new Middleware('permission:create roles', only: ['create']),
            //new Middleware('permission:delete roles', only: ['destroy']),
        ];
    }

    public function index(Request $request){
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

        $permissions = Permission::orderBy('created_at','DESC')->paginate(10);
        $totalPermissions = DB::table('permissions')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $roles = Role::orderBy('created_at','DESC')->paginate(10);        
        $totalRoles = DB::table('roles')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;
        $permissionCount = DB::table('permissions')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $pages = Page::get();

        if($request->keyword != ''){
            $pages = $pages->where('name','like','%'.$request->keyword.'%');
        }        

        //dd($pages);       

        $data = [
            'configurations'        => $configurations,
            'branches'              => $branches,
            'payments'              => $payments,
            'theme'                 => $theme,
            'areas'                 => $areas,
            'seats'                 => $seats,
            'tableIndividual'       => $tableIndividual,
            'totalTable'            => $totalTable,
            'totalArea'             => $totalArea,
            'tableRunning'          => $tableRunning,
            'permissions'           => $permissions,
            'totalPermissions'      => $totalPermissions,
            'roles'                 => $roles,            
            'totalRoles'            => $totalRoles,
            'permissionCount'       => $permissionCount,
            'pages'                 => $pages
        ];

        $data['branchForm'] = [
            'title' => 'Create Branch',
            'button_name' => 'Add Branch',
            'modal_id' => 'createBranchModal',            

            'formConfig' => [
                'action' => route('branch.store'),
                'method' => 'POST',
                'button' => 'Add Branch',
                'modal' => '',
                'modalSize' => 'modal-sm modal-dialog-centered',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'area_name',
                        'label' => 'Branch Name',
                        'placeholder' => 'Enter Branch Name',
                        'class' => 'slug-source',
                        'data'  => [
                            'target' => '#area_slug'
                        ], 
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'area_slug',
                        'label' => 'Slug',
                        'id'    => 'area_slug',
                        'col' => 'd-none'
                    ],
                ]
            ]
        ];  

        $seatingCapacities = [1, 2, 4, 6, 8, 10];
        
        $data['tableForm'] = [
            'title' => 'Add Table',
            'button_name' => 'Add Table',
            'modal_id' => 'createTableModal',            

            'formConfig' => [
                'action' => route('table.store'),
                'method' => 'POST',
                'button' => 'Add Table',
                'modal' => 'drawer right-align',
                'modalSize' => '',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'table_name',
                        'label' => 'Table Name',
                        'placeholder' => 'e.g. Table_01',                        
                        'class' => 'slug-source',
                        'data'  => [
                            'target' => '#table_slug'
                        ], 
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'table_slug',
                        'label' => 'Slug',
                        'id'    => 'table_slug',
                        'col' => 'd-none'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'area_id',
                        'label' => 'Select Branch',
                        'options' => $branches,
                        'option_value' => 'id',
                        'option_text' => 'area_name',
                        //'option_label' => 'area_name',
                        'col' => 'col-12'
                    ],
                    [
                        'type' => 'radio',
                        'name' => 'capacity',
                        'label' => 'Select Capacity',
                        'options' => $seatingCapacities,
                        'col' => 'col-md-12'
                    ]
                ]
            ]
        ];

        $data['pageForm'] = [
            'title' => 'Create Page',
            'button_name' => 'Create Page',
            'modal_id' => 'createPageModal',            

            'formConfig' => [
                'action' => route('pages.store'),
                'method' => 'POST',
                'button' => 'Create Page',
                'modal' => 'drawer right-align',
                'modalSize' => '',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'page_name',
                        'label' => 'Page Name',
                        'placeholder' => '',                        
                        'class' => 'slug-source',
                        'data'  => [
                            'target' => '#page_slug'
                        ], 
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'page_slug',
                        'label' => 'Slug',
                        'id'    => 'page_slug',
                        'col' => 'd-none'
                    ],
                    [
                        'type' => 'textarea',
                        'name' => 'content',
                        'label' => 'Content',
                        'id'    => 'content',
                        'summer_class' => 'summernote',
                        'col' => 'col-md-12 col-12'
                    ],
                ]
            ]
        ];  

        $data['permissionForm'] = [
            'title' => 'Create Permission',
            'button_name' => 'Create Permission',
            'modal_id' => 'createPermissionModal',            

            'formConfig' => [
                'action' => route('permissions.store'),
                'method' => 'POST',
                'button' => 'Create Permission',
                'modal' => 'drawer right-align',
                'modalSize' => '',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Permission Name',
                        'placeholder' => 'Permission Name',
                        'col' => 'col-md-12'
                    ],                    
                ]
            ]
        ];

        $data['roleForm'] = [
            'title' => 'Create Role',
            'button_name' => 'Create Role',
            'modal_id' => 'createRoleModal',            

            'formConfig' => [
                'action' => route('roles.store'),
                'method' => 'POST',
                'button' => 'Create Role',
                'modal' => 'drawer right-align',
                'modalSize' => '',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Role Name',
                        'placeholder' => 'Role Name',
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'checkbox',
                        'name' => 'permission',
                        'label' => 'Select Permission',
                        'options' => $permissions,
                        'option_value' => 'id',
                        'option_text' => 'name',                        
                        'col' => 'col-md-12'
                    ]
                ]
            ]
        ];        

        return view('admin.configurations.list', $data);        
    }

    public function index_old(Request $request){
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

        $permissions = Permission::orderBy('created_at','DESC')->paginate(10);
        $totalPermissions = DB::table('permissions')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $roles = Role::orderBy('created_at','DESC')->paginate(10);        
        $totalRoles = DB::table('roles')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;
        $permissionCount = DB::table('permissions')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $pages = Page::get();

        if($request->keyword != ''){
            $pages = $pages->where('name','like','%'.$request->keyword.'%');
        }        

        //dd($pages);       

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
            'permissions' => $permissions,
            'totalPermissions' => $totalPermissions,
            'roles' => $roles,            
            'totalRoles' => $totalRoles,
            'permissionCount' => $permissionCount,
            'pages' => $pages
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


    public function permissions_store(Request $request){
        $validator = Validator::make($request->all(), [ 
            'name' => 'required|unique:permissions|min:3'
        ]);        

        if($validator->passes()){
            Permission::create([ 'name' => $request->name ]);
        
            return redirect()->route('configurations.index')->with('success','Permission added successfully.');
        } else {
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }
    }

    public function permissions_edit($id){
        $permission = Permission::findOrFail($id);

        return view("admin.permissions.edit", [
            'permission' => $permission
        ]);
    }

    public function permissions_update($id, Request $request){
        $permission = Permission::findOrFail($id);

        $validator = Validator::make($request->all(), [ 
            'name' => 'required|min:3|unique:permissions,name,'.$id.',id'
        ]);        

        if($validator->passes()){
            $permission->name = $request->name;
            $permission->save();

            return redirect()->route('configurations.index')->with('success','Permission updated successfully.');
        } else {
            return redirect()->route('permissions.edit',$id)->withInput()->withErrors($validator);
        }
    }



    public function permissions_destroy(Request $request){
        $id = $request->id;

        $permission = Permission::findOrFail($id);

        if($permission == null){
            session()->flash('error','Permission not found');
            return response()->json([
                'status' => false
            ]);
        }

        $permission->delete();

        session()->flash('success','Permission deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }


    //Roles
    public function role_store(Request $request){
        $validator = Validator::make($request->all(), [ 
            'name' => 'required|unique:roles|min:3'
        ]);        

        if($validator->passes()){
            $role = Role::create([ 'name' => $request->name ]);

            if(!empty($request->permission)){
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name);
                }
            }

            return redirect()->route('configurations.index')->with('success','Role added successfully.');
        } else {
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }


    public function role_edit($id){
        $role = Role::findOrFail($id);

        return view("admin.permissions.edit", [
            'role' => $role
        ]);
    }

    public function role_destroy(Request $request){
        $id = $request->id;

        $role = Role::findOrFail($id);

        if($role == null){
            session()->flash('error','Role not found');
            return response()->json([
                'status' => false
            ]);
        }

        $role->delete();

        session()->flash('success','Role deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }


    
    //Pages
    public function page_store(Request $request){
        $validator = Validator::make($request->all(), [
            'page_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $page = new Page();        
        $page->page_name = $request->page_name;
        $page->page_slug = Str::slug($request->page_name);
        $page->content = $request->content;
        $page->save();

        return redirect()->back()->with('success', 'Page added successfully');
       
    }


    public function page_store2(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $page = new Page;
        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->save();

        return redirect()->back()->with('success', 'Page created successfully');        
    }


    public function page_edit($id){
        $page = Page::find($id);

        if ($page == null){
            session()->flash('error','Page not found');
            return redirect()->route('configurations.index');
        }

        return view('admin.pages.edit',[
            'page' => $page
        ]);
    }


    public function page_update(Request $request, $id){
        $page = Page::find($id);

        if($page == null) {
            session()->flash('error','Page not found');
            return response()->json([
                'status' => true,
            ]);
        };

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->save();

        $message = 'Page updated successfully.';

        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }   


    public function page_destroy($id){
        $page = Page::find($id);

        if($page == null) {
            session()->flash('error','Page not found');
            return response()->json([
                'status' => true,
            ]);
        };

        $page->delete();
        $message = 'Page deleted successfully.';
        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}