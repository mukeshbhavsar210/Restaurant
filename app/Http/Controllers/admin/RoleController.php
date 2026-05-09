<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
                new Middleware('permission:view roles', only: ['index']),
                new Middleware('permission:edit roles', only: ['edit']),
                new Middleware('permission:create roles', only: ['create']),
                new Middleware('permission:delete roles', only: ['destroy']),
            ];
    }

    public function index(){
        $roles = Role::orderBy('created_at','DESC')->paginate(10);
        $permissions = Permission::orderBy('name','ASC')->get();

        $totalRoles = DB::table('roles')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        $permissionCount = DB::table('permissions')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        return view("admin.roles.list", [
            'roles' => $roles,
            'permissions' => $permissions,
            'totalRoles' => $totalRoles,
            'permissionCount' => $permissionCount,
        ]);
    }

    public function create(){
        $permissions = Permission::orderBy('name','ASC')->get();
        return view("admin.roles.create", [
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request){
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

            return redirect()->route('roles.index')->with('success','Role added successfully.');
        } else {
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }


    public function edit($id){
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name','ASC')->get();

        return view("admin.roles.edit", [
            'role' => $role,
            'permissions' => $permissions,
            'hasPermissions' => $hasPermissions
        ]);
    }



    public function update($id, Request $request){
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

            return redirect()->route('roles.index')->with('success','role updated successfully.');
        } else {
            return redirect()->route('roles.edit',$id)->withInput()->withErrors($validator);
        }
    }



    public function destroy(Request $request){
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
