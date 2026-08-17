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
    /**
     * Show Super Admin Dashboard with Advanced Analytics (Fase 1-4).
     */
    public function index(Request $request): View
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // ── Date Range Filter Handling ────────────────────────────────
        $preset = $request->query('preset', 'all');
        $startDate = null;
        $endDate = null;

        if ($preset === '7days') {
            $startDate = $now->copy()->subDays(7)->startOfDay();
            $endDate = $now->copy()->endOfDay();
        } elseif ($preset === '30days') {
            $startDate = $now->copy()->subDays(30)->startOfDay();
            $endDate = $now->copy()->endOfDay();
        } elseif ($preset === 'this_month') {
            $startDate = $startOfMonth;
            $endDate = $now->copy()->endOfDay();
        } elseif ($preset === 'last_month') {
            $startDate = $startOfLastMonth;
            $endDate = $endOfLastMonth;
        } elseif ($preset === 'this_year') {
            $startDate = $now->copy()->startOfYear();
            $endDate = $now->copy()->endOfDay();
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->query('start_date'))->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->query('end_date'))->endOfDay();
        }

        // ── Core Metrics ──────────────────────────────────────────────
        $companyQuery = DB::table('companies');
        $userQuery = DB::table('users')->whereNotNull('company_id');
        $leadQuery = DB::table('leads');
        $invoiceQuery = DB::table('invoices')->where('status', 'paid');

        if ($startDate && $endDate) {
            $companyQuery->whereBetween('created_at', [$startDate, $endDate]);
            $userQuery->whereBetween('created_at', [$startDate, $endDate]);
            $leadQuery->whereBetween('created_at', [$startDate, $endDate]);
            $invoiceQuery->whereBetween('paid_at', [$startDate, $endDate]);
        }

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

        // ── SaaS Advanced Metrics: MRR, ARR, ARPU ─────────────────────
        $activeCompaniesWithPlans = DB::table('companies')
            ->join('plans', 'companies.plan_id', '=', 'plans.id')
            ->where('companies.is_active', true)
            ->select('plans.price', 'plans.billing_cycle')
            ->get();

        $mrr = 0.0;
        foreach ($activeCompaniesWithPlans as $p) {
            $price = (float) $p->price;
            if ($price > 0 && $price < 1000) {
                $price = $price * 16000.0; // Standardize IDR
            }
            if ($p->billing_cycle === 'yearly') {
                $mrr += ($price / 12.0);
            } else {
                $mrr += $price;
            }
        }

        $arr = $mrr * 12.0;
        $arpu = $totalActiveCompanies > 0 ? round($mrr / $totalActiveCompanies) : 0;

        // ── Churn Rate Analytics ──────────────────────────────────────
        $thirtyDaysAgo = $now->copy()->subDays(30);
        $churnedCompaniesCount = DB::table('subscriptions')
            ->whereIn('status', ['cancelled', 'expired'])
            ->where('updated_at', '>=', $thirtyDaysAgo)
            ->distinct('company_id')
            ->count('company_id');

        $activeStartOf30Days = DB::table('companies')
            ->where('created_at', '<=', $thirtyDaysAgo)
            ->count();

        $churnRate = $activeStartOf30Days > 0
            ? round(($churnedCompaniesCount / $activeStartOf30Days) * 100, 1)
            : 0;

        // ── Tenant Health Score Matrix ─────────────────────────────────
        $companies = DB::table('companies')
            ->leftJoin('plans', 'companies.plan_id', '=', 'plans.id')
            ->where('companies.is_active', true)
            ->select('companies.id', 'companies.name', 'companies.email', 'plans.name as plan_name')
            ->get();

        $healthScores = collect();
        foreach ($companies as $comp) {
            $userCount = DB::table('users')->where('company_id', $comp->id)->count();
            $leadCount30d = DB::table('leads')
                ->where('company_id', $comp->id)
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->count();

            $sub = DB::table('subscriptions')
                ->where('company_id', $comp->id)
                ->orderBy('id', 'desc')
                ->first();

            // Kriteria Health Score (Max 100):
            // 1. Leads Activity (40%): >=10 leads = 40, else prop
            $activityScore = min(40, round(($leadCount30d / 10) * 40));
            
            // 2. User Engagement (40%): >=3 users = 40, else prop
            $userScore = min(40, round(($userCount / 3) * 40));

            // 3. Subscription Status (20%): active = 20, pending = 10, else 0
            $subStatus = $sub->status ?? 'inactive';
            $subScore = $subStatus === 'active' ? 20 : ($subStatus === 'pending' ? 10 : 0);

            $score = $activityScore + $userScore + $subScore;

            $statusCategory = 'Healthy';
            $statusBadgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            if ($score < 40) {
                $statusCategory = 'Critical';
                $statusBadgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
            } elseif ($score < 80) {
                $statusCategory = 'At Risk';
                $statusBadgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
            }

            $healthScores->push([
                'id'                => $comp->id,
                'name'              => $comp->name,
                'plan_name'         => $comp->plan_name,
                'score'             => $score,
                'lead_count'        => $leadCount30d,
                'user_count'        => $userCount,
                'sub_status'        => $subStatus,
                'status_category'   => $statusCategory,
                'status_badge_class'=> $statusBadgeClass,
            ]);
        }

        $atRiskTenants = $healthScores->sortBy('score')->take(5)->values();

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

        // ── Audit Logs Feed (5 recent) ────────────────────────────────
        $recentAuditLogs = \Webkul\Admin\Models\SuperAdminAuditLog::with('superAdmin')
            ->orderBy('created_at', 'desc')
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
            'mrr',
            'arr',
            'arpu',
            'churnRate',
            'atRiskTenants',
            'preset',
            'tenantGrowth',
            'revenueChart',
            'plansDistribution',
            'invoiceSummary',
            'recentTenants',
            'recentInvoices',
            'recentAuditLogs'
        ));
    }
}
