<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Show register form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'mobile'   => 'required|string|max:15|unique:users,mobile',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ Citizen only user create
        $user = User::create([
            'name'     => $request->name,
            'mobile'   => $request->mobile,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'citizen', // 🔒 HARD FIX (admin কখনোই হবে না)
        ]);

        // Auto login after register
        Auth::login($user);

        return redirect()->route('citizen.dashboard')
            ->with('success', 'রেজিস্ট্রেশন সফল হয়েছে ✅');
    }
}
