<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users (Citizens only)
     */
    public function index(Request $request)
    {
        // Default query - শুধু citizen users দেখাবে
        $query = User::where('role', 'citizen')
            ->withCount([
                'applications',
                'applications as pending_applications_count' => function($q) {
                    $q->where('status', 'pending');
                },
                'applications as approved_applications_count' => function($q) {
                    $q->where('status', 'approved');
                },
                'applications as rejected_applications_count' => function($q) {
                    $q->where('status', 'rejected');
                }
            ]);
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by role (admin/super_admin-এর জন্য সব role দেখাবে)
        if ($request->filled('role') && (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')) {
            $query->where('role', $request->role);
        }
        
        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $users = $query->latest()->paginate(20);
        
        // Statistics - শুধু citizen এর জন্য
        $stats = [
            'total' => User::where('role', 'citizen')->count(),
            'active' => User::where('role', 'citizen')->where('status', 'active')->count(),
            'inactive' => User::where('role', 'citizen')->where('status', 'inactive')->count(),
            'banned' => User::where('role', 'citizen')->where('status', 'banned')->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }
    
    /**
     * Show user details
     */
    public function show($id)
    {
        $user = User::withCount([
            'applications',
            'applications as pending_applications_count' => function($q) {
                $q->where('status', 'pending');
            },
            'applications as approved_applications_count' => function($q) {
                $q->where('status', 'approved');
            },
            'applications as rejected_applications_count' => function($q) {
                $q->where('status', 'rejected');
            }
        ])
        ->with(['applications' => function($q) {
            $q->with('certificateType')->latest()->limit(10);
        }])
        ->findOrFail($id);
        
        // Permission check
        if (auth()->user()->role === 'citizen' && $user->id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Statistics for the user
        $stats = [
            'total_applications' => $user->applications_count ?? 0,
            'pending_applications' => $user->pending_applications_count ?? 0,
            'approved_applications' => $user->approved_applications_count ?? 0,
            'rejected_applications' => $user->rejected_applications_count ?? 0,
        ];
        
        // Recent applications (for display)
        $recentApplications = $user->applications()
            ->with('certificateType')
            ->latest()
            ->take(5)
            ->get();
            
        return view('admin.users.show', compact('user', 'stats', 'recentApplications'));
    }
    
    /**
     * Store new user - শুধু admin/super_admin এর জন্য
     */
    public function store(Request $request)
    {
        // Permission check
        if (auth()->user()->role === 'citizen') {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'nullable|string|max:20',
            'username' => 'nullable|string|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:citizen,user,admin,super_admin',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string|max:500',
        ]);
        
        // Super admin ছাড়া অন্য কেউ super_admin role create করতে পারবে না
        if ($request->role === 'super_admin' && auth()->user()->role !== 'super_admin') {
            return redirect()->back()->with('error', 'You are not authorized to create super admin users.');
        }
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'address' => $request->address,
            'email_verified_at' => now(),
        ];
        
        // শুধু যদি status column থাকে
        if (Schema::hasColumn('users', 'status')) {
            $userData['status'] = $request->status;
        }
        
        $user = User::create($userData);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }
    
    /**
     * Update user status - শুধু admin/super_admin এর জন্য
     */
    public function updateStatus(Request $request, $id)
    {
        // Permission check
        if (auth()->user()->role === 'citizen') {
            abort(403, 'Unauthorized action.');
        }
        
        // যদি status column না থাকে, তাহলে কাজ করবে না
        if (!Schema::hasColumn('users', 'status')) {
            return back()->with('error', 'Status feature is not available.');
        }
        
        $request->validate([
            'status' => 'required|in:active,inactive,banned'
        ]);
        
        $user = User::findOrFail($id);
        
        // নিজের account status change করতে পারবে না
        if ($user->id == auth()->id()) {
            return back()->with('error', 'You cannot change your own status.');
        }
        
        $user->update(['status' => $request->status]);
        
        return back()->with('success', 'User status updated successfully.');
    }
    
    /**
     * Edit user - শুধু super_admin এর জন্য
     */
    public function edit($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }
        
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update user - শুধু super_admin এর জন্য
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }
        
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'mobile' => 'nullable|string|max:20',
            'username' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:citizen,user,admin,super_admin',
            'address' => 'nullable|string|max:500',
        ]);
        
        $updateData = $request->only([
            'name', 'email', 'mobile', 'username', 
            'role', 'address'
        ]);
        
        // শুধু যদি status column থাকে
        if (Schema::hasColumn('users', 'status') && $request->has('status')) {
            $updateData['status'] = $request->status;
        }
        
        $user->update($updateData);
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User updated successfully.');
    }
    
    /**
     * Bulk actions - শুধু admin/super_admin এর জন্য
     */
    public function bulkAction(Request $request)
    {
        // Permission check
        if (auth()->user()->role === 'citizen') {
            abort(403, 'Unauthorized action.');
        }
        
        // যদি status column না থাকে, তাহলে শুধু delete কাজ করবে
        if (!Schema::hasColumn('users', 'status') && $request->action !== 'delete') {
            return back()->with('error', 'Status features are not available. Only delete action works.');
        }
        
        $request->validate([
            'action' => 'required|in:activate,deactivate,ban,delete',
            'user_ids' => 'required|array',
        ]);
        
        $userIds = json_decode($request->user_ids);
        $action = $request->action;
        
        // নিজের ID remove করুন
        $userIds = array_diff($userIds, [auth()->id()]);
        
        switch ($action) {
            case 'activate':
                if (Schema::hasColumn('users', 'status')) {
                    User::whereIn('id', $userIds)->update(['status' => 'active']);
                    $message = 'Selected users have been activated.';
                }
                break;
                
            case 'deactivate':
                if (Schema::hasColumn('users', 'status')) {
                    User::whereIn('id', $userIds)->update(['status' => 'inactive']);
                    $message = 'Selected users have been deactivated.';
                }
                break;
                
            case 'ban':
                if (Schema::hasColumn('users', 'status')) {
                    User::whereIn('id', $userIds)->update(['status' => 'banned']);
                    $message = 'Selected users have been banned.';
                }
                break;
                
            case 'delete':
                // শুধু super_admin user delete করতে পারবে
                if (auth()->user()->role !== 'super_admin') {
                    return back()->with('error', 'You are not authorized to delete users.');
                }
                User::whereIn('id', $userIds)->delete();
                $message = 'Selected users have been deleted.';
                break;
                
            default:
                $message = 'No action performed.';
        }
        
        return back()->with('message', $message ?? 'Action completed.');
    }
    
    /**
     * Export users
     */
    public function export(Request $request)
    {
        // Permission check
        if (auth()->user()->role === 'citizen') {
            abort(403, 'Unauthorized action.');
        }
        
        // CSV export logic
        return back()->with('info', 'Export feature coming soon.');
    }
    
    /**
     * Check if users table has status column
     */
    private function hasStatusColumn()
    {
        return \Schema::hasColumn('users', 'status');
    }
}