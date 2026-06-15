<?php

namespace Webkul\Core\Traits;

use Webkul\Core\Scopes\CompanyScope;

trait BelongsToCompany
{
    /**
     * Boot the BelongsToCompany trait.
     *
     * Automatically applies CompanyScope to all queries and
     * sets company_id on new records from the authenticated user.
     */
    public static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(new CompanyScope);

        static::creating(function ($model) {
            if (empty($model->company_id)) {
                $user = auth()->guard('user')->user();

                if ($user && $user->company_id) {
                    $model->company_id = $user->company_id;
                }
            }
        });
    }

    /**
     * Get the company that owns this model.
     */
    public function company()
    {
        return $this->belongsTo(\Webkul\Core\Models\Company::class);
    }
}
