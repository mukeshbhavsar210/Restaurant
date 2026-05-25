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

class UserController extends Controller implements HasMiddleware {

    public static function middleware(): array {
        return [
                new Middleware('permission:view users', only: ['index']),
                new Middleware('permission:edit users', only: ['edit']),
                new Middleware('permission:create roles', only: ['create']),
                new Middleware('permission:delete roles', only: ['destroy']),
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


    


    public function showPage(){
        return view("welcome");
    }
}
