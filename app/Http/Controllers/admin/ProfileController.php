<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\View\View;

class ProfileController extends Controller {

    public function index(Request $request){
        $data['profileForm'] = [
                'title' => 'Profile',
                'modal_id' => 'updateProfileModal',            

                'formConfig' => [
                    'action' => route('profile.update'),
                    'method' => 'PUT',
                    'button' => 'Update Profile',
                                    
                    'fields' => [                                        
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
                            'col' => 'col-12',                        
                        ],
                        [
                            'type' => 'text',
                            'name' => 'mobile',
                            'label' => 'Mobile',
                            'required' => true,
                            'placeholder' => 'Mobile',
                            'col' => 'col-12',                        
                        ],
                        [
                            'type' => 'file',
                            'name' => 'image',
                            'label' => 'Photo',
                            'required' => true,
                            'placeholder' => 'Photo',
                            'col' => 'col-12',                        
                        ],
                    ]
                ]
            ]; 

            $data['passwordForm'] = [
                'title' => 'Change Password',
                'modal_id' => 'updatePasswordModal',            

                'formConfig' => [
                    'action' => route('password.update'),
                    'method' => 'PUT',
                    'button' => 'Change Password',
                                    
                    'fields' => [                                        
                        [
                            'type' => 'text',
                            'name' => 'current_password',
                            'label' => 'Current Password',
                            'required' => true,
                            'placeholder' => 'Current Password',
                            'col' => 'col-12',                        
                        ],
                        [
                            'type' => 'text',
                            'name' => 'password',
                            'label' => 'Password',
                            'required' => true,
                            'placeholder' => 'Password',
                            'col' => 'col-12',                        
                        ],
                        [
                            'type' => 'text',
                            'name' => 'password_confirmation',
                            'label' => 'Confirm Password',
                            'required' => true,
                            'placeholder' => 'Confirm Password',
                            'col' => 'col-12',                        
                        ],
                    ]
                ]
            ]; 
                        
            return view('admin.profile.index', $data);        
        }

    public function update_profile(ProfileUpdateRequest $request): RedirectResponse {
        $user = $request->user();

        // Fill validated fields
        $user->fill($request->validated());

        // Mobile
        $user->mobile = $request->mobile;

        // Image Upload        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($user->image && file_exists(public_path('uploads/users/' . $user->image))) {
                unlink(public_path('uploads/users/' . $user->image));
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            // Create filename from user name
            $fileName = strtolower(str_replace(' ', '-', $user->name)) . '.' . $extension;
            $path = public_path('uploads/users/' . $fileName);
            $manager = new ImageManager(new Driver());
            $logo = $manager->read($file);
            $logo->cover(200, 200)->save($path);
            $user->image = $fileName;
        }

        $user->save();

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profile updated successfully.');
    }
}