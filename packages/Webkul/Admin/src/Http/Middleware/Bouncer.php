<?php

namespace Webkul\Admin\Http\Middleware;

use Illuminate\Support\Facades\Route;

class Bouncer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = 'user')
    {
        if (! auth()->guard($guard)->check()) {
            if ($request->is(config('app.admin_path') . '*')) {
                return redirect()->route('super_admin.session.create');
            }
            return redirect()->route('admin.session.create');
        }

        $user = auth()->guard($guard)->user();

        // 1. Check user status
        if (! (bool) $user->status) {
            auth()->guard($guard)->logout();

            session()->flash('error', __('admin::app.errors.401'));

            return $request->is(config('app.admin_path') . '*')
                ? redirect()->route('super_admin.session.create')
                : redirect()->route('admin.session.create');
        }

        // 2. Check if Super Admin tries to access Tenant panel
        if ($request->is(config('app.tenant_path') . '*')) {
            if ($user->company_id === null) {
                return redirect()->route('super_admin.dashboard.index');
            }

            if ($user->company && ! $user->company->is_active) {
                auth()->guard($guard)->logout();

                session()->flash('error', 'Perusahaan Anda sedang tidak aktif. Hubungi administrator.');

                return redirect()->route('admin.session.create');
            }
        }

        // 3. Check if Tenant User tries to access Super Admin panel
        if ($request->is(config('app.admin_path') . '*')) {
            if ($user->company_id !== null) {
                return redirect()->route('admin.dashboard.index');
            }
        }

        // 4. Permissions check for tenant users
        if ($user->company_id !== null) {
            if ($this->isPermissionsEmpty()) {
                auth()->guard($guard)->logout();

                session()->flash('error', __('admin::app.errors.401'));

                return redirect()->route('admin.session.create');
            }
        }

        return $next($request);
    }

    /**
     * Check for user, if they have empty permissions or not except admin.
     *
     * @return bool
     */
    public function isPermissionsEmpty()
    {
        if (! $role = auth()->guard('user')->user()->role) {
            abort(401, 'This action is unauthorized.');
        }

        if ($role->permission_type === 'all') {
            return false;
        }

        if ($role->permission_type !== 'all' && empty($role->permissions)) {
            return true;
        }

        $this->checkIfAuthorized();

        return false;
    }

    /**
     * Check authorization.
     *
     * @return null
     */
    public function checkIfAuthorized()
    {
        $roles = acl()->getRoles();

        if (isset($roles[Route::currentRouteName()])) {
            bouncer()->allow($roles[Route::currentRouteName()]);
        }
    }
}
