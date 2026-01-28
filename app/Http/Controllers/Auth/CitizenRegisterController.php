<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CitizenRegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.citizen-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'mobile'   => 'required|string|unique:users,mobile',
            'email'    => 'nullable|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'mobile'   => $request->mobile,
            'email'    => $request->email,
            'role'     => 'citizen', // 🔒 fixed
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'রেজিস্ট্রেশন সফল হয়েছে');
    }
}
