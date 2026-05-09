<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Area;
use App\Models\Seat;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

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
        $roles = Role::orderBy('name','ASC')->get();

        $totalUsers = DB::table('users')
                    ->select(DB::raw('count(*) as total'))
                    ->get()[0]->total;

        return view("admin.users.list", [
            'users' => $users,
            'totalUsers' => $totalUsers,
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

        return view("admin.users.edit", [
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = User::find($request->id);

        if($user == null){
            session()->flash('error','User not found');
            return response()->json([
                'status' => false
            ]);
        }

        $user->delete();

        session()->flash('success','User deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }


    public function show(){
        $products = Product::orderBy('id','DESC')->get();
        $areas = Area::orderBy('id','DESC')->with('seat')->orderBy('id','DESC')->get();
        $seats = Seat::orderBy('id','DESC')->get();        
      
        //$cartCount = (count(Session::get('cart', array())));

        $data = [
            'products'=> $products,
            'areas'=> $areas,
            'seats'=> $seats,
        ];
        
        //dd($data);

        return view('welcome', $data);    
    }
}
