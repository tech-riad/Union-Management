<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog; // Add this line

class LoginController extends Controller
{
    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Log login activity - Add this block
            try {
                ActivityLog::create([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_role' => $user->role,
                    'action' => 'LOGIN',
                    'module' => 'AUTH',
                    'description' => "User {$user->name} ({$user->role}) logged in successfully",
                    'old_data' => null,
                    'new_data' => json_encode([
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'user_role' => $user->role,
                        'login_time' => now()->toDateTimeString(),
                        'login_ip' => $request->ip()
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method()
                ]);
            } catch (\Exception $e) {
                // Log error but don't break the application
                \Log::error('Failed to log login activity: ' . $e->getMessage());
            }

            // Role-based redirect
            switch($user->role) {
                case 'super_admin': return redirect('/super-admin/dashboard');
                case 'admin': return redirect('/admin/dashboard');
                case 'secretary': return redirect('/secretary/dashboard');
                case 'citizen': return redirect('/citizen/dashboard');
                default: return redirect('/');
            }
        }

        // Log failed login attempt - Add this block
        try {
            ActivityLog::create([
                'user_id' => null,
                'user_name' => 'Unknown',
                'user_role' => 'guest',
                'action' => 'LOGIN_FAILED',
                'module' => 'AUTH',
                'description' => "Failed login attempt for email: {$request->email}",
                'old_data' => null,
                'new_data' => json_encode([
                    'email' => $request->email,
                    'ip_address' => $request->ip(),
                    'attempt_time' => now()->toDateTimeString()
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method()
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Failed to log failed login attempt: ' . $e->getMessage());
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log logout activity - Add this block
        if ($user) {
            try {
                ActivityLog::create([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_role' => $user->role,
                    'action' => 'LOGOUT',
                    'module' => 'AUTH',
                    'description' => "User {$user->name} ({$user->role}) logged out",
                    'old_data' => null,
                    'new_data' => json_encode([
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'user_role' => $user->role,
                        'logout_time' => now()->toDateTimeString(),
                        'logout_ip' => $request->ip()
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method()
                ]);
            } catch (\Exception $e) {
                // Log error but don't break the application
                \Log::error('Failed to log logout activity: ' . $e->getMessage());
            }
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}