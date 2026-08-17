<?php

namespace Webkul\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\User\Models\User;

class SuperAdminAuditLog extends Model
{
    protected $table = 'super_admin_audit_logs';

    protected $fillable = [
        'super_admin_id',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the Super Admin user who performed the action.
     */
    public function superAdmin()
    {
        return $this->belongsTo(User::class, 'super_admin_id');
    }

    /**
     * Helper to record an audit log entry easily.
     */
    public static function log(string $action, string $module, string $description): self
    {
        $superAdmin = auth()->guard('user')->user();

        return static::create([
            'super_admin_id' => $superAdmin ? $superAdmin->id : null,
            'action'         => $action,
            'module'         => $module,
            'description'    => $description,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }
}
