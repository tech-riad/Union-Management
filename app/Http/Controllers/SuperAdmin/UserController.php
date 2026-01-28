<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display all users
     */
    public function index()
    {
        $users = User::where('role', '!=', 'super_admin')
            ->latest()
            ->paginate(20);
        
        return view('super_admin.users.index', compact('users'));
    }

    /**
     * Display admin users
     */
    public function adminList()
    {
        $admins = User::where('role', 'admin')
            ->latest()
            ->paginate(20);
            
        return view('super_admin.users.admins.index', compact('admins'));
    }

    /**
     * Display secretary users
     */
    public function secretaryList()
    {
        $secretaries = User::where('role', 'secretary')
            ->latest()
            ->paginate(20);
            
        return view('super_admin.users.secretaries.index', compact('secretaries'));
    }

    /**
     * Display citizen users
     */
    public function citizenList()
    {
        $citizens = User::where('role', 'citizen')
            ->latest()
            ->paginate(20);
            
        return view('super_admin.users.citizens.index', compact('citizens'));
    }

    /**
     * Show create admin form
     */
    public function createAdmin()
    {
        return view('super_admin.users.admins.create');
    }

    /**
     * Store new admin
     */
    public function storeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:15', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            return redirect()->route('super_admin.users.admins.index')
                ->with('success', 'Admin created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating admin: ' . $e->getMessage());
        }
    }

    /**
     * Show edit admin form
     */
    public function editAdmin(User $user)
    {
        if ($user->role !== 'admin') {
            abort(404, 'User is not an admin.');
        }
        
        return view('super_admin.users.admins.edit', compact('user'));
    }

    /**
     * Update admin
     */
    public function updateAdmin(Request $request, User $user)
    {
        if ($user->role !== 'admin') {
            abort(404, 'User is not an admin.');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:15', 'unique:users,mobile,' . $user->id],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            $data = [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'status' => $request->status,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('super_admin.users.admins.index')
                ->with('success', 'Admin updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating admin: ' . $e->getMessage());
        }
    }

    /**
     * Show create secretary form
     */
    public function createSecretary()
    {
        return view('super_admin.users.secretaries.create');
    }

    /**
     * Store new secretary
     */
    public function storeSecretary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:15', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'secretary',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            return redirect()->route('super_admin.users.secretaries.index')
                ->with('success', 'Secretary created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating secretary: ' . $e->getMessage());
        }
    }

    /**
     * Show edit secretary form
     */
    public function editSecretary(User $user)
    {
        if ($user->role !== 'secretary') {
            abort(404, 'User is not a secretary.');
        }
        
        return view('super_admin.users.secretaries.edit', compact('user'));
    }

    /**
     * Update secretary
     */
    public function updateSecretary(Request $request, User $user)
    {
        if ($user->role !== 'secretary') {
            abort(404, 'User is not a secretary.');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:15', 'unique:users,mobile,' . $user->id],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            $data = [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'status' => $request->status,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('super_admin.users.secretaries.index')
                ->with('success', 'Secretary updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating secretary: ' . $e->getMessage());
        }
    }

    /**
     * Show create citizen form
     */
    public function createCitizen()
    {
        return view('super_admin.users.citizens.create');
    }

    /**
     * Store new citizen
     */
    public function storeCitizen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:15', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Optional fields (removed required validation)
            'nid' => ['nullable', 'string', 'max:20', 'unique:users'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'nid' => $request->nid ?? null,
                'father_name' => $request->father_name ?? null,
                'mother_name' => $request->mother_name ?? null,
                'address' => $request->address ?? null,
                'password' => Hash::make($request->password),
                'role' => 'citizen',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            return redirect()->route('super_admin.users.citizens.index')
                ->with('success', 'Citizen created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating citizen: ' . $e->getMessage());
        }
    }

    /**
     * Show edit citizen form
     */
    public function editCitizen(User $user)
    {
        if ($user->role !== 'citizen') {
            abort(404, 'User is not a citizen.');
        }
        
        return view('super_admin.users.citizens.edit', compact('user'));
    }

    /**
     * Update citizen
     */
    public function updateCitizen(Request $request, User $user)
    {
        if ($user->role !== 'citizen') {
            abort(404, 'User is not a citizen.');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:15', 'unique:users,mobile,' . $user->id],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // Optional fields (removed required validation)
            'nid' => ['nullable', 'string', 'max:20', 'unique:users,nid,' . $user->id],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            $data = [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'nid' => $request->nid ?? $user->nid,
                'father_name' => $request->father_name ?? $user->father_name,
                'mother_name' => $request->mother_name ?? $user->mother_name,
                'address' => $request->address ?? $user->address,
                'status' => $request->status,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('super_admin.users.citizens.index')
                ->with('success', 'Citizen updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating citizen: ' . $e->getMessage());
        }
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        return view('super_admin.users.show', compact('user'));
    }

    /**
     * Show edit form (all users)
     */
    public function edit(User $user)
    {
        return view('super_admin.users.edit', compact('user'));
    }

    /**
     * Update user (all users)
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:15', 'unique:users,mobile,' . $user->id],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'in:active,inactive'],
        ];

        if ($user->role === 'citizen') {
            // Optional fields for citizen
            $rules['nid'] = ['nullable', 'string', 'max:20', 'unique:users,nid,' . $user->id];
            $rules['father_name'] = ['nullable', 'string', 'max:255'];
            $rules['mother_name'] = ['nullable', 'string', 'max:255'];
            $rules['address'] = ['nullable', 'string'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            $data = [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'status' => $request->status,
            ];

            if ($user->role === 'citizen') {
                $data['nid'] = $request->nid ?? $user->nid;
                $data['father_name'] = $request->father_name ?? $user->father_name;
                $data['mother_name'] = $request->mother_name ?? $user->mother_name;
                $data['address'] = $request->address ?? $user->address;
            }

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('super_admin.users.index')
                ->with('success', 'User updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Prevent deleting super admin
        if ($user->role === 'super_admin') {
            return redirect()->back()
                ->with('error', 'Super admin cannot be deleted.');
        }

        try {
            $user->delete();
            return redirect()->back()
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    /**
     * Update user status via AJAX
     */
    public function updateStatus(Request $request, User $user)
    {
        // Prevent changing super admin status
        if ($user->role === 'super_admin') {
            return response()->json([
                'success' => false, 
                'message' => 'Super admin status cannot be changed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'message' => 'Invalid status.'
            ]);
        }

        try {
            $user->update(['status' => $request->status]);
            return response()->json([
                'success' => true, 
                'message' => 'Status updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error updating status.'
            ]);
        }
    }

    /**
     * Show create user form (all types)
     */
    public function create()
    {
        return view('super_admin.users.create');
    }

    /**
     * Store new user (all types)
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:15', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:admin,secretary,citizen'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if ($request->role === 'citizen') {
            // Optional fields for citizen
            $rules['nid'] = ['nullable', 'string', 'max:20', 'unique:users'];
            $rules['father_name'] = ['nullable', 'string', 'max:255'];
            $rules['mother_name'] = ['nullable', 'string', 'max:255'];
            $rules['address'] = ['nullable', 'string'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            $data = [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 'active',
                'email_verified_at' => now(),
            ];

            if ($request->role === 'citizen') {
                $data['nid'] = $request->nid ?? null;
                $data['father_name'] = $request->father_name ?? null;
                $data['mother_name'] = $request->mother_name ?? null;
                $data['address'] = $request->address ?? null;
            }

            User::create($data);

            return redirect()->route('super_admin.users.index')
                ->with('success', 'User created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action for users
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => ['required', 'in:activate,deactivate,delete'],
            'users' => ['required', 'array'],
            'users.*' => ['exists:users,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid request.');
        }

        try {
            $users = User::whereIn('id', $request->users)
                ->where('role', '!=', 'super_admin') // Protect super admin
                ->get();

            foreach ($users as $user) {
                switch ($request->action) {
                    case 'activate':
                        $user->update(['status' => 'active']);
                        break;
                    case 'deactivate':
                        $user->update(['status' => 'inactive']);
                        break;
                    case 'delete':
                        $user->delete();
                        break;
                }
            }

            $message = $request->action . 'd ' . count($users) . ' users successfully.';
            return redirect()->back()
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error performing bulk action: ' . $e->getMessage());
        }
    }

    /**
     * Show super admin profile edit form
     */
    public function editSuperAdminProfile(User $user)
    {
        // Check if user is super admin and matches the logged in user
        if ($user->role !== 'super_admin' || $user->id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('super_admin.profile.edit', compact('user'));
    }

    /**
     * Update super admin profile
     */
    public function updateSuperAdminProfile(Request $request, User $user)
    {
        // Check if user is super admin and matches the logged in user
        if ($user->role !== 'super_admin' || $user->id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:15', 'unique:users,mobile,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'designation' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'remove_profile_photo' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            $data = [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'designation' => $request->designation,
                'address' => $request->address,
                'bio' => $request->bio,
            ];

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $data['profile_photo'] = $path;
            } elseif ($request->boolean('remove_profile_photo')) {
                $data['profile_photo'] = null;
            }

            $user->update($data);

            return redirect()->route('super_admin.profile.show')
                ->with('success', 'Profile updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }
}