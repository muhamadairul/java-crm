<?php

namespace Webkul\Admin\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    /**
     * Show Super Admin Login Form.
     */
    public function showLoginForm(): RedirectResponse|View
    {
        if (auth()->guard('user')->check()) {
            $user = auth()->guard('user')->user();
            if ($user->role_id == 1 && $user->company_id === null) {
                return redirect()->route('super_admin.dashboard.index');
            }
        }

        return view('admin::super-admin.login');
    }

    /**
     * Handle Super Admin Login.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard('user')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $user = auth()->guard('user')->user();

            // Check if Super Admin
            if ($user->role_id == 1 && $user->company_id === null) {
                if ($user->status == 0) {
                    auth()->guard('user')->logout();
                    session()->flash('error', 'Akun Anda dinonaktifkan.');
                    return redirect()->back();
                }

                return redirect()->route('super_admin.dashboard.index');
            }

            // Not a Super Admin -> log out immediately
            auth()->guard('user')->logout();
            session()->flash('error', 'Hanya Super Admin yang dapat mengakses panel ini.');
            return redirect()->back();
        }

        session()->flash('error', 'Kredensial yang Anda masukkan salah.');
        return redirect()->back();
    }

    /**
     * Handle Super Admin Logout.
     */
    public function logout(): RedirectResponse
    {
        auth()->guard('user')->logout();
        return redirect()->route('super_admin.session.create');
    }

    /**
     * Show Super Admin Dashboard.
     */
    public function index(): View
    {
        // Statistics for dashboard
        $totalCompanies = DB::table('companies')->count();
        $totalActiveCompanies = DB::table('companies')->where('is_active', true)->count();
        $totalUsers = DB::table('users')->whereNotNull('company_id')->count();
        $totalLeads = DB::table('leads')->count();
        
        // Active plans distribution
        $plansDistribution = DB::table('companies')
            ->join('plans', 'companies.plan_id', '=', 'plans.id')
            ->select('plans.name', DB::raw('count(companies.id) as count'))
            ->groupBy('plans.name')
            ->get();

        return view('admin::super-admin.dashboard', compact(
            'totalCompanies',
            'totalActiveCompanies',
            'totalUsers',
            'totalLeads',
            'plansDistribution'
        ));
    }
}
