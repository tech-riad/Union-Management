<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\UserProfile;

class ProfileController extends Controller
{
    /**
     * Show profile (view only)
     */
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('citizen.profile.show', compact('profile'));
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('citizen.profile.edit', compact('user', 'profile'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Age calculation
        $dob = $request->dob ? Carbon::parse($request->dob) : null;
        $age = $dob ? $dob->age : 0;

        // Validation rules
        $rules = [
            // Names
            'name_bn' => 'required|string|max:255|regex:/^[\p{Bengali}\s]+$/u',
            'name_en' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'father_name_bn' => 'required|string|max:255|regex:/^[\p{Bengali}\s]+$/u',
            'father_name_en' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'mother_name_bn' => 'required|string|max:255|regex:/^[\p{Bengali}\s]+$/u',
            'mother_name_en' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',

            // Personal
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'religion' => 'required|string|max:50',
            'height' => 'nullable|string|max:20',
            'birth_mark' => 'nullable|string|max:255',

            // Address
            'present_address' => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255',
            'village' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:10',
            

            // Other
            'occupation' => 'required|string|max:100',
            'education' => 'required|string|max:100',
            'quota' => 'nullable|string|max:100',
        ];

        // Conditional NID for 18+
        if ($age >= 18) {
            $rules['nid_number'] = 'required|string|max:20';
        } else {
            $request->merge(['nid_number' => null]);
        }

        // Profile photo
        if ($request->hasFile('profile_photo')) {
            $rules['profile_photo'] = 'image|max:2048';
        }

        $data = $request->validate($rules);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        // Save or update profile
        if ($profile) {
            $profile->update($data + ['is_complete' => true]);
        } else {
            $user->profile()->create($data + ['is_complete' => true]);
        }

        return redirect()->route('citizen.dashboard')
                         ->with('success', 'প্রোফাইল সফলভাবে সম্পূর্ন হয়েছে ✅');
    }
}
