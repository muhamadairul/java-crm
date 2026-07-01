<?php

namespace Webkul\User\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\User\Contracts\Role as RoleContract;

class Role extends Model implements RoleContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'permission_type',
        'permissions',
        'company_id',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Get the users.
     */
    public function users()
    {
        return $this->hasMany(UserProxy::modelClass());
    }

    /**
     * Get the company that owns this role.
     */
    public function company()
    {
        return $this->belongsTo(\Webkul\Core\Models\Company::class);
    }

    /**
     * Check if this role is a default seeded role (cannot be deleted by Company Admin).
     */
    public function isDefault(): bool
    {
        return in_array($this->name, ['Company Admin', 'Sales User']);
    }
}
