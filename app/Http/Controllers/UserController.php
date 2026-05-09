<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller implements HasMiddleware 
{

    public static function middleware(): array {
        return [
                new Middleware('permission:view users', only: ['index']),
                new Middleware('permission:edit users', only: ['edit']),
                //new Middleware('permission:create roles', only: ['create']),
                //new Middleware('permission:delete roles', only: ['destroy']),
            ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(){
        $users = User::latest()->paginate(25);

        return view("admin.users.list", [
            'users' => $users
        ]);
    }

    public function create(){
        $roles = Role::orderBy('name','ASC')->get();

        return view("admin.users.create", [  
            'roles' => $roles
        ]);
    }


    public function store(Request $request){
        
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

        return redirect()->route('users.index')->with('success','User added successfully');
    }
   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name','ASC')->get();
        $hasRoles = $user->roles()->pluck('id');
        
        //dd($roles);

        return view("users.edit", [
            'user' => $user,
            'roles' => $roles,
            'hasRoles' => $hasRoles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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

        return redirect()->route('users.index')->with('success','User updated successfully');
           
    }

    public function delete(Request $request){
        $users = User::find($request->id);
        $users->delete();

        return redirect()->with('success','User deleted successfully.');
    }


    public function showPage(){
        return view("welcome");
    }
}
