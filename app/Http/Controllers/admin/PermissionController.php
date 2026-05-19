<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Spatie\Permission\Models\Role;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller implements HasMiddleware  {
    public static function middleware(): array {
        return [
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

    public function index(){
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

        return view("admin.permissions.list", [
            'permissions' => $permissions,
            'totalPermissions' => $totalPermissions,
            'roles' => $roles,            
            'totalRoles' => $totalRoles,
            'permissionCount' => $permissionCount,
        ]);
    }

    public function permissions_create(){
        return view("admin.permissions.create");
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
}
