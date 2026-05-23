<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware 
{
    public static function middleware(): array {
        return [
                new Middleware('permission:view permissions', only: ['index']),
                new Middleware('permission:edit permissions', only: ['edit']),
                new Middleware('permission:create permissions', only: ['create']),
                new Middleware('permission:delete permissions', only: ['destroy']),
            ];
        }

 

    public function store(Request $request){
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

    public function edit($id){
        $permission = Permission::findOrFail($id);

        return view("admin.configurations.permission_edit", [
            'permission' => $permission
        ]);
    }

    public function update($id, Request $request){
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


    public function destroy($id, Request $request){
        $permission = Permission::find($id);
        $permission->delete();

        $request->session()->flash('success','Permission deleted successfully');
        return redirect()->route('configurations.index')->with('success','Permission deleted successfully.');
    }

   
}
