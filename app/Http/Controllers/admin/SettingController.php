<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{

    public function index(){
        $id = Auth::user()->id;
        $user = User::where('id',$id)->first();

        $data=['user' => $user];        

        return view('admin.settings.list', $data);
    }
   

    public function update(Request $request){
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:30',
            'email' => 'required|email|unique:users,email,'.$id.',id',                        
        ]);

        if($validator->passes()) {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;            
            $user->save();

            session()->flash('success','Profile updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function update_logo(Request $request){
        $validator = Validator::make($request->all(),[
            'image' => 'required|image',
        ]);

        $id = Auth::user()->id;
        
        if($validator->passes()) {
            $user = User::find($id);
            $user->restaurant_logo = $request->image;
            $ext = $user->restaurant_logo->getClientOriginalExtension();
            $imageName = $id.'-'.$user->restaurant_name.'.'.$ext;
            $user->restaurant_logo->move(public_path('/website_logo/'), $imageName);

            //Delete old profile pic
            File::delete(public_path('/website_logo/'.Auth::user()->restaurant_logo));            
            User::where('id',$id)->update(['restaurant_logo' => $imageName]);

            session()->flash('success','Logo updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    

    public function branch(Request $request){
        $validator = Validator::make(request()->all(), [
           
        ]);

        $id = Auth::user()->id;

        if($validator->passes()){
            $user = User::find($id);
            $user->branch_name = $request->branch_name;
            $user->branch_address = $request->branch_address;
            $user->save();

            session()->flash('success','You have successfully created branch.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function websiteInformation(Request $request){
        $validator = Validator::make(request()->all(), [
           
        ]);

        $id = Auth::user()->id;

        if($validator->passes()){
            $user = User::find($id);
            $user->restaurant_name = $request->restaurant_name;
            $user->restaurant_email = $request->restaurant_email;
            $user->restaurant_phone = $request->restaurant_phone;
            $user->restaurant_theme = $request->restaurant_theme;
            $user->restaurant_address = $request->restaurant_address;
            $user->save();

            session()->flash('success','You have successfully changed your password.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function processChangePassword(Request $request){
        $validator = Validator::make(request()->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|min:5|same:new_password',
        ]);

        $id = Auth::guard('admin')->user()->id;

        $admin = User::where('id',$id)->first();

        if($validator->passes()){
            if(!Hash::check($request->old_password,$admin->password)){
                session()->flash('error','Your old password is incorrect.');
                return response()->json([
                    'status' => true,
                ]);
            }

            User::where('id',$id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            session()->flash('success','You have successfully changed your password.');
            return response()->json([
                'status' => true,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}
