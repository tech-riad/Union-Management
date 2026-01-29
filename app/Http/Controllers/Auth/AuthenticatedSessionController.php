<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{

    public function create()
    {
        return view('auth.login');
    }
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($request->only('email','password'), $request->filled('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        // Role-based redirect
        return $this->redirectByRole(Auth::user());
    }

    /**
     * Redirect user based on role
     */
    protected function redirectByRole($user)
    {
        switch ($user->role) {
            case 'super_admin':
                return redirect('/super-admin/dashboard');
            case 'admin':
                return redirect('/admin/dashboard');
            case 'secretary':
                return redirect('/secretary/dashboard');
            case 'citizen':
                return redirect('/citizen/dashboard');
            default:
                return redirect('/dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
