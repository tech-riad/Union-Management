<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use Carbon\Carbon;

class UserProfileController extends Controller
{
    public function edit()
    {
        $profile = Auth::user()->profile ?? new UserProfile();
        return view('users.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = Auth::user()->profile ?? new UserProfile(['user_id' => Auth::id()]);

        $request->validate([
            'profile_photo' => 'nullable|image|max:2048',
            'name_bangla' => 'required|string',
            'name_english' => 'required|string',
            'father_name_bangla' => 'required|string',
            'father_name_english' => 'required|string',
            'mother_name_bangla' => 'required|string',
            'mother_name_english' => 'required|string',
            'dob' => 'required|date',
            'nid' => 'nullable|string',
            'present_address' => 'required|string',
            'permanent_address' => 'required|string',
            'birth_place' => 'required|string',
            'religion' => 'required|string',
            'height' => 'required|string',
            'birth_mark' => 'nullable|string',
            'gender' => 'required|string',
            'marital_status' => 'required|string',
            'quota' => 'required|string',
            'profession' => 'required|string',
            'education' => 'required|string',
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles','public');
            $profile->profile_photo = $path;
        }

        $profile->fill($request->all());

        // Check if profile 100% complete
        $requiredFields = [
            'profile_photo','name_bangla','name_english','father_name_bangla','father_name_english',
            'mother_name_bangla','mother_name_english','dob','present_address','permanent_address',
            'birth_place','religion','height','gender','marital_status','quota','profession','education'
        ];

        $profile->is_complete = true;
        foreach($requiredFields as $field){
            if(empty($profile->$field)){
                $profile->is_complete = false;
                break;
            }
        }

        $profile->save();

        return redirect()->route('dashboard')->with('success','Profile updated successfully!');
    }
}
