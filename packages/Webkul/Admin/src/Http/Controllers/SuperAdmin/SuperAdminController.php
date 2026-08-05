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
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // ── Core Metrics ──────────────────────────────────────────────
        $totalCompanies = DB::table('companies')->count();
        $totalActiveCompanies = DB::table('companies')->where('is_active', true)->count();
        $totalUsers = DB::table('users')->whereNotNull('company_id')->count();
        $totalLeads = DB::table('leads')->count();

        // Last month counts for month-over-month % change
        $lastMonthCompanies = DB::table('companies')
            ->where('created_at', '<', $startOfMonth)
            ->count();
        $lastMonthUsers = DB::table('users')
            ->whereNotNull('company_id')
            ->where('created_at', '<', $startOfMonth)
            ->count();
        $lastMonthLeads = DB::table('leads')
            ->where('created_at', '<', $startOfMonth)
            ->count();

        // ── Revenue Metrics ───────────────────────────────────────────
        $revenueThisMonth = DB::table('invoices')
            ->where('status', 'paid')
            ->where('paid_at', '>=', $startOfMonth)
            ->sum('amount');

        $revenueLastMonth = DB::table('invoices')
            ->where('status', 'paid')
            ->where('paid_at', '>=', $startOfLastMonth)
            ->where('paid_at', '<=', $endOfLastMonth)
            ->sum('amount');

        $totalRevenue = DB::table('invoices')
            ->where('status', 'paid')
            ->sum('amount');

        // Percentage changes (safe division)
        $companiesChange = $lastMonthCompanies > 0
            ? round((($totalCompanies - $lastMonthCompanies) / $lastMonthCompanies) * 100, 1)
            : ($totalCompanies > 0 ? 100 : 0);

        $usersChange = $lastMonthUsers > 0
            ? round((($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1)
            : ($totalUsers > 0 ? 100 : 0);

        $leadsChange = $lastMonthLeads > 0
            ? round((($totalLeads - $lastMonthLeads) / $lastMonthLeads) * 100, 1)
            : ($totalLeads > 0 ? 100 : 0);

        $revenueChange = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : ($revenueThisMonth > 0 ? 100 : 0);

        // ── Tenant Growth Chart (6 months) ────────────────────────────
        $tenantGrowth = collect();
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $now->copy()->subMonths($i)->endOfMonth();
            $tenantGrowth->push([
                'label' => $monthStart->translatedFormat('M Y'),
                'count' => DB::table('companies')
                    ->where('created_at', '<=', $monthEnd)
                    ->count(),
            ]);
        }

        // ── Revenue Chart (6 months) ──────────────────────────────────
        $revenueChart = collect();
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $now->copy()->subMonths($i)->endOfMonth();
            $revenueChart->push([
                'label' => $monthStart->translatedFormat('M Y'),
                'amount' => (float) DB::table('invoices')
                    ->where('status', 'paid')
                    ->whereBetween('paid_at', [$monthStart, $monthEnd])
                    ->sum('amount'),
            ]);
        }

        // ── Plans Distribution (Doughnut) ─────────────────────────────
        $plansDistribution = DB::table('companies')
            ->join('plans', 'companies.plan_id', '=', 'plans.id')
            ->select('plans.name', DB::raw('count(companies.id) as count'))
            ->groupBy('plans.name')
            ->get();

        // ── Invoice Status Summary ────────────────────────────────────
        $invoiceStatus = DB::table('invoices')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $invoiceSummary = [
            'pending' => $invoiceStatus['pending'] ?? 0,
            'paid'    => $invoiceStatus['paid'] ?? 0,
            'failed'  => $invoiceStatus['failed'] ?? 0,
            'expired' => $invoiceStatus['expired'] ?? 0,
        ];

        // ── Recent Tenants ────────────────────────────────────────────
        $recentTenants = DB::table('companies')
            ->leftJoin('plans', 'companies.plan_id', '=', 'plans.id')
            ->select('companies.*', 'plans.name as plan_name')
            ->orderBy('companies.created_at', 'desc')
            ->limit(5)
            ->get();

        // ── Recent Invoices ───────────────────────────────────────────
        $recentInvoices = DB::table('invoices')
            ->leftJoin('companies', 'invoices.company_id', '=', 'companies.id')
            ->select('invoices.*', 'companies.name as company_name')
            ->orderBy('invoices.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin::super-admin.dashboard', compact(
            'totalCompanies',
            'totalActiveCompanies',
            'totalUsers',
            'totalLeads',
            'revenueThisMonth',
            'totalRevenue',
            'companiesChange',
            'usersChange',
            'leadsChange',
            'revenueChange',
            'tenantGrowth',
            'revenueChart',
            'plansDistribution',
            'invoiceSummary',
            'recentTenants',
            'recentInvoices'
        ));
    }
}
