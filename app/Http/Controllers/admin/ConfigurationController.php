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
use Illuminate\Support\Facades\Hash;


class ConfigurationController extends Controller implements HasMiddleware {
    public static function middleware(): array {
        return [
                new Middleware('permission:view permissions', only: ['index']),
                new Middleware('permission:edit permissions', only: ['edit']),
                new Middleware('permission:create permissions', only: ['create']),
                new Middleware('permission:delete permissions', only: ['destroy']),

                new Middleware('permission:view roles', only: ['index']),
                new Middleware('permission:edit roles', only: ['edit']),
                new Middleware('permission:create roles', only: ['create']),
                new Middleware('permission:delete roles', only: ['destroy']),

                new Middleware('permission:view users', only: ['index']),
                new Middleware('permission:edit users', only: ['edit']),
                new Middleware('permission:create roles', only: ['create']),
                new Middleware('permission:delete roles', only: ['destroy']),
            ];
        }


    public function index(Request $request){
        $areas = Area::orderBy('area_name','ASC')->get();
        $seats = Seat::where('area_id',NULL)->with('seat')->get();
        $tableRunning = OrderItem::with('seat')->get();

        $totalTable = DB::table('seats')
                    ->select(DB::raw('count(*) as total_tables'))
                    ->get()[0]->total_tables;

        $branchCounts = DB::table('areas')
                    ->select(DB::raw('count(*) as total_tables'))
                    ->get()[0]->total_tables;

        $pageCounts = DB::table('pages')
                    ->select(DB::raw('count(*) as total_pages'))
                    ->get()[0]->total_pages;

        $userCounts = DB::table('users')
                    ->select(DB::raw('count(*) as total_users'))
                    ->get()[0]->total_users;

        $tableIndividual = DB::table('seats')
                    //->join('areas','seatings.area_id','=','areas.id')
                    ->select(DB::raw('count(*) as number'), 'area_id')
                    ->groupBy('area_id')
                    ->get()[0]->number;
        
        $totalPermissions = DB::table('permissions')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;
               
        $totalRoles = DB::table('roles')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;
        $permissionCount = DB::table('permissions')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;
        
        $payments = Payment::get();        
        $branches = Area::withCount('seat as total_seats')->with('seats')->get();
        $theme = Theme::get();
        $users = User::get();        
        $pages = Page::get();
        $roles = Role::get();
        $permissions = Permission::get();
        $config = Configuration::first();

        if($request->keyword != ''){
            $pages = $pages->where('name','like','%'.$request->keyword.'%');
        }                      

        $data = [
            'config'                => $config,
            'branches'              => $branches,
            'payments'              => $payments,
            'theme'                 => $theme,
            'areas'                 => $areas,
            'seats'                 => $seats,
            'tableIndividual'       => $tableIndividual,
            'totalTable'            => $totalTable,
            'tableRunning'          => $tableRunning,
            'permissions'           => $permissions,
            'totalPermissions'      => $totalPermissions,
            'roles'                 => $roles,            
            'totalRoles'            => $totalRoles,
            'permissionCount'       => $permissionCount,
            'pages'                 => $pages,
            'users'                 => $users,
            'branchCounts'          => $branchCounts,
            'pageCounts'            => $pageCounts,
            'userCounts'            => $userCounts,
        ];

        $seatingCapacities = [1, 2, 4, 6, 8, 10];
        $businessTypes = [
            ['id' => 'Dinein', 'name' => 'Dinein'],
            ['id' => 'Takeaway', 'name' => 'Takeaway'],
            ['id' => 'Delivery', 'name' => 'Delivery'],
        ];

        $data['configForm'] = [
            'title' => 'Setup Restaurant',
            'modal_id' => 'createConfigModal',            

            'formConfig' => [
                'action' => route('configurations.store'),
                'method' => 'POST',
                'button' => 'Add Restaurant',
                                
                'fields' => [
                    [
                        'type' => 'checkbox',
                        'name' => 'business_types',
                        'label' => 'Business Types',
                        'options' => $businessTypes,
                        'option_value' => 'id',
                        'option_text' => 'name',
                        'col' => 'col-md-6',
                        'class' => 'flex-2',
                    ],
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'Name',
                        'required' => true,
                        'placeholder' => 'Name',
                        'col' => 'col-12',                        
                    ],
                    [
                        'type' => 'email',
                        'name' => 'email',
                        'label' => 'Email',
                        'required' => true,
                        'placeholder' => 'Email',
                        'col' => 'col-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'phone',
                        'label' => 'Phone',
                        'required' => true,
                        'placeholder' => 'Phone',
                        'col' => 'col-12'
                    ],
                    [
                        'type' => 'file',
                        'name' => 'logo',
                        'label' => 'Logo',
                        'required' => true,
                        'placeholder' => '',
                        'col' => 'col-12'
                    ],
                    [
                        'type' => 'textarea',
                        'name' => 'address',
                        'label' => 'Address',
                        'required' => true,
                        'placeholder' => 'Address',
                        'col' => 'col-12'
                    ],
                    
                    [
                        'type' => 'text',
                        'name' => 'primary_color',
                        'label' => 'Primary Color',
                        'required' => false,
                        'placeholder' => 'Primary Color',
                        'col' => 'col-6'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'secondary_color',
                        'label' => 'Secondary Color',
                        'required' => false,
                        'placeholder' => 'Secondary Color',
                        'col' => 'col-6'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'payment_key_id',
                        'label' => 'Payment key id',
                        'required' => false,
                        'placeholder' => 'Payment key id',
                        'col' => 'col-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'payment_key_secret',
                        'label' => 'Payment key secret',
                        'required' => false,
                        'placeholder' => 'Payment key secret',
                        'col' => 'col-12'
                    ],
                ]
            ]
        ]; 

        $data['branchForm'] = [
            'title' => 'Create Branch',
            'modal_id' => 'createBranchModal',            

            'formConfig' => [
                'action' => route('branch.store'),
                'method' => 'POST',
                'button' => 'Add Branch',
                                
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'area_name',
                        'label' => 'Branch Name',
                        'required' => true,
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
                        'required' => true,
                        'id'    => 'area_slug',
                        'col' => 'd-none'
                    ],
                ]
            ]
        ];  

        
        
        $data['tableForm'] = [
            'title' => 'Add Table',
            'modal_id' => 'createTableModal',            

            'formConfig' => [
                'action' => route('table.store'),
                'method' => 'POST',
                'button' => 'Add Table',                
                
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'table_name',
                        'label' => 'Table',
                        'required' => true,
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
                        'required' => true,
                        'id'    => 'table_slug',
                        'col' => 'd-none'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'area_id',
                        'label' => 'Branch',
                        'required' => true,
                        'options' => $branches,
                        'option_value' => 'id',
                        'option_text' => 'area_name',
                        'option_label' => 'area_name',
                        'col' => 'col-12'
                    ],
                    [
                        'type' => 'radio',
                        'name' => 'capacity',
                        'label' => 'Seat',
                        'required' => true,
                        'options' => $seatingCapacities,
                        'col' => 'col-md-12'
                    ]
                ]
            ]
        ];

        $data['pageForm'] = [
            'title' => 'Create Page',
            'modal_id' => 'createPageModal',            

            'formConfig' => [
                'action' => route('pages.store'),
                'method' => 'POST',
                'button' => 'Create Page',                
                
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'page_name',
                        'label' => 'Page',
                        'required' => true,
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
                        'required' => true,
                        'id'    => 'page_slug',
                        'col' => 'd-none'
                    ],
                    [
                        'type' => 'textarea',
                        'name' => 'content',
                        'label' => 'Content',
                        'required' => true,
                        'id'    => 'content',
                        'summer_class' => 'summernote',
                        'col' => 'col-md-12 col-12'
                    ],
                ]
            ]
        ];          

        $data['permissionForm'] = [
            'title' => 'Create Permission',
            'modal_id' => 'createPermissionModal',            

            'formConfig' => [
                'action' => route('permissions.store'),
                'method' => 'POST',
                'button' => 'Create Permission',                
                
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
            'modal_id' => 'createRoleModal',            

            'formConfig' => [
                'action' => route('roles.store'),
                'method' => 'POST',
                'button' => 'Create Role',                
                
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
        
        $data['userForm'] = [
            'title' => 'Create User',
            'modal_id' => 'createUserModal',            

            'formConfig' => [
                'action' => route('users.store'),
                'method' => 'POST',
                'button' => 'Create User',
                            
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'name',
                        'label' => 'User Name',
                        'placeholder' => 'Enter User Name',
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'email',
                        'name' => 'email',
                        'label' => 'Email',
                        'placeholder' => 'Enter Email',
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'password',
                        'name' => 'password',
                        'label' => 'Password',
                        'placeholder' => 'Password',
                        'col' => 'col-6'
                    ],
                    [
                        'type' => 'password',
                        'name' => 'confirm_password',
                        'label' => 'Confirm Password',
                        'placeholder' => 'Confirm Password',
                        'col' => 'col-6'
                    ],
                    [
                        'type' => 'checkbox',
                        'name' => 'role',
                        'label' => 'Select Permission',
                        'options' => $roles,
                        'option_value' => 'id',
                        'option_text' => 'name',                        
                        'col' => 'col-12'
                    ]
                ]
            ]
        ]; 

        return view('admin.configurations.list', $data);        
    }
    

    public function configurations_store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator);
        }

        $data = new Configuration();

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->business_types = implode(',', $request->business_types ?? []);
        $data->primary_color = $request->primary_color;
        $data->secondary_color = $request->secondary_color;
        $data->payment_key_id = $request->payment_key_id;
        $data->payment_key_secret = $request->payment_key_secret;

        // Image upload
        if ($request->hasFile('logo')) {

            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $fileName = $data->name . '.' . $extension;

            $path = public_path('/uploads/logo/' . $fileName);

            $manager = new ImageManager(new Driver());

            $logo = $manager->read($file);

            $logo->cover(300, 300)->save($path);

            $data->logo = $fileName;
        }

        $data->save();

        return back()->with('success', 'Configurations added successfully.');
    }


    public function store_theme(Request $request){
        $theme = new Theme();
        $theme->primary_color = $request->primary_color;
        $theme->secondary_color = $request->secondary_color;
        $theme->sidebar_color = $request->sidebar_color;
        $theme->save();

        return redirect()->route('configurations.index')->with('success','Theme added successfully.');
    }


    public function configurations_update(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = Configuration::first();

        if (!$data) {
            $data = new Configuration();
        }

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->business_types = $request->business_types;
        $data->primary_color = $request->primary_color;
        $data->secondary_color = $request->secondary_color;
        $data->payment_key_id = $request->payment_key_id;
        $data->payment_key_secret = $request->payment_key_secret;
        
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

        return redirect()->back()
            ->with('success', 'Restaurant details updated successfully.');
    }


    public function branch_store(Request $request){
        $area = new Area();
        $area->area_name = $request->area_name;
        $area->area_slug = $request->area_slug;
        $area->save();

        return redirect()->route('configurations.index')->with('success','Branch added successfully.'); 
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
            'page_name' => 'required',
            'page_slug' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $page->page_name = $request->page_name;
        $page->page_slug = $request->page_slug;
        $page->content = $request->content;
        $page->save();

        $message = 'Page updated successfully.';

        session()->flash('success',$message);

        return redirect()->route('configurations.index')->with('success','Page updated successfully.');
    }   

    //Page Delete
    public function page_delete($id, Request $request){
        $page = Page::find($id);
        $page->delete();

        $request->session()->flash('success','Page deleted successfully');
        return redirect()->route('configurations.index')->with('success','Page deleted successfully.');
    }
    


    //Permissions
    public function permission_store(Request $request){
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

    public function permission_edit($id){
        $permission = Permission::findOrFail($id);
        $permissions = Permission::get();
        $totalPermissions = DB::table('permissions')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        return view("admin.configurations.permission_edit", [
            'permission' => $permission,
            'permissions' => $permissions,
            'totalPermissions' => $totalPermissions
        ]);
    }

    public function permission_update($id, Request $request){
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

    public function permission_destroy($id, Request $request){
        $permission = Permission::find($id);
        $permission->delete();

        $request->session()->flash('success','Permission deleted successfully');
        return redirect()->route('configurations.index')->with('success','Permission deleted successfully.');
    }



    //Roles
     public function roles_store(Request $request){
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


    public function roles_edit($id){
        $role = Role::findOrFail($id);
        $roles = Role::get();
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name','ASC')->get();

        $totalRoles = DB::table('roles')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        return view("admin.configurations.roles_edit", [
            'role' => $role,
            'roles' => $roles,
            'totalRoles' => $totalRoles,
            'permissions' => $permissions,
            'hasPermissions' => $hasPermissions
        ]);
    }



    public function roles_update($id, Request $request){
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [ 
            'name' => 'required|unique:roles,name,'.$id.',id'
        ]);        

        if($validator->passes()){
            $role->name = $request->name;
            $role->save();

            if(!empty($request->permission)){
                $role->syncPermissions($request->permission);
            } else {
                $role->syncPermissions([]);
            }

            return redirect()->route('configurations.index')->with('success','role updated successfully.');
        } else {
            return redirect()->route('roles.edit',$id)->withInput()->withErrors($validator);
        }
    }

    public function roles_destroy($id, Request $request){
        $roles = Role::find($id);
        $roles->delete();

        $request->session()->flash('success','Role deleted successfully');

        return redirect()->route('configurations.index')->with('success','Role deleted successfully.');
    } 


    //Users
    public function users_store(Request $request){
        
        $validator = Validator::make($request->all(), [ 
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);        

        if($validator->fails()){
            return redirect()->route('users.create')->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route('configurations.index')->with('success','User added successfully');
    }
   

    public function users_edit(string $id) {
        $users = User::get();    
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name','ASC')->get();
        $hasRoles = $user->roles()->pluck('id');
        $userCounts = DB::table('users')
                    ->select(DB::raw('count(*) as total_users'))
                    ->get()[0]->total_users;

        return view("admin.configurations.user_edit", [
            'user' => $user,
            'users' => $users,
            'userCounts' => $userCounts,
            'roles' => $roles,
            'hasRoles' => $hasRoles,
        ]);
    }

    public function users_update(Request $request, string $id) {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [ 
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);        

        if($validator->fails()){
            return redirect()->route('users.edit',$id)->withInput()->withErrors($validator);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route('configurations.index')->with('success','User updated successfully');           
    }

    public function users_destroy($id, Request $request){
        $user = User::find($id);
        $user->delete();

        $request->session()->flash('success','User deleted successfully');
        return redirect()->route('configurations.index')->with('success','User deleted successfully.');
    }
    
}