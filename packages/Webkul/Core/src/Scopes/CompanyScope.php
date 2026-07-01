<?php

namespace Webkul\Core\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    /**
     * Flag to prevent infinite recursion when resolving the authenticated user.
     *
     * @var bool
     */
    protected static $resolvingUser = false;

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (static::$resolvingUser) {
            return;
        }

        static::$resolvingUser = true;

        try {
            $user = auth()->guard('user')->user();

            if ($user && $user->company_id) {
                $builder->where($model->getTable() . '.company_id', $user->company_id);
            }
        } finally {
            static::$resolvingUser = false;
        }
    }
}
