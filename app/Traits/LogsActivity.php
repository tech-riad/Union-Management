<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

trait LogsActivity
{
    /**
     * Log activity to database
     */
    protected function logActivity(
        string $action,
        string $module,
        string $description,
        array $oldData = null,
        array $newData = null,
        Request $request = null
    ): void {
        $user = Auth::user();
        $request = $request ?? request();

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'user_role' => $user?->role ?? 'system',
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method()
        ]);
    }

    /**
     * Log user login
     */
    protected function logLogin(): void
    {
        $this->logActivity('LOGIN', 'AUTH', 'User logged in successfully');
    }

    /**
     * Log user logout
     */
    protected function logLogout(): void
    {
        $this->logActivity('LOGOUT', 'AUTH', 'User logged out');
    }

    /**
     * Log create action
     */
    protected function logCreate(string $module, string $description, array $data = []): void
    {
        $this->logActivity('CREATE', $module, $description, null, $data);
    }

    /**
     * Log update action
     */
    protected function logUpdate(string $module, string $description, array $oldData, array $newData): void
    {
        $this->logActivity('UPDATE', $module, $description, $oldData, $newData);
    }

    /**
     * Log delete action
     */
    protected function logDelete(string $module, string $description, array $data = []): void
    {
        $this->logActivity('DELETE', $module, $description, $data, null);
    }

    /**
     * Log approve action
     */
    protected function logApprove(string $module, string $description, array $data = []): void
    {
        $this->logActivity('APPROVE', $module, $description, null, $data);
    }

    /**
     * Log reject action
     */
    protected function logReject(string $module, string $description, array $data = []): void
    {
        $this->logActivity('REJECT', $module, $description, null, $data);
    }
}