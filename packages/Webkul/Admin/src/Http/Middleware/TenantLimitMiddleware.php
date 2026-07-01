<?php

namespace Webkul\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TenantLimitMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->guard('user')->user();

        // If not logged in or is Super Admin, bypass limits
        if (! $user || $user->company_id === null) {
            return $next($request);
        }

        $company = $user->company;

        // If no company or no plan associated, bypass limits
        if (! $company || ! $company->plan) {
            return $next($request);
        }

        $plan = $company->plan;

        // 1. Check User Limit when creating a new user
        // Route path checks (e.g. POST to tenant/settings/users)
        if ($request->isMethod('post') && ($request->routeIs('admin.settings.users.store') || $request->is('*/settings/users'))) {
            $currentUsersCount = DB::table('users')->where('company_id', $user->company_id)->count();

            if ($plan->max_users > 0 && $currentUsersCount >= $plan->max_users) {
                session()->flash('error', "Batas maksimum pengguna ({$plan->max_users} pengguna) untuk paket Anda telah tercapai. Harap tingkatkan paket Anda.");
                return redirect()->back();
            }
        }

        // 2. Check Lead Limit when creating a new lead
        // Route path checks (e.g. POST to tenant/leads)
        if ($request->isMethod('post') && ($request->routeIs('admin.leads.store') || $request->is('*/leads'))) {
            $currentLeadsCount = DB::table('leads')->where('company_id', $user->company_id)->count();

            if ($plan->max_leads > 0 && $currentLeadsCount >= $plan->max_leads) {
                session()->flash('error', "Batas maksimum prospek ({$plan->max_leads} prospek) untuk paket Anda telah tercapai. Harap tingkatkan paket Anda.");
                return redirect()->back();
            }
        }

        // 3. Check Storage Limit when uploading files
        if ($request->allFiles()) {
            $incomingSize = 0;
            foreach ($request->allFiles() as $file) {
                $incomingSize += $file->getSize(); // Size in bytes
            }
            $incomingSizeMb = $incomingSize / (1024 * 1024);

            // Calculate current usage
            $files = DB::table('activity_files')
                ->join('activities', 'activity_files.activity_id', '=', 'activities.id')
                ->where('activities.company_id', $user->company_id)
                ->pluck('path');

            $totalSize = 0;
            foreach ($files as $path) {
                if (Storage::exists($path)) {
                    $totalSize += Storage::size($path);
                }
            }
            $currentUsageMb = $totalSize / (1024 * 1024);

            if ($plan->max_storage_mb > 0 && ($currentUsageMb + $incomingSizeMb) > $plan->max_storage_mb) {
                session()->flash('error', "Ukuran file melebihi kapasitas penyimpanan tersisa untuk paket Anda (Maksimum {$plan->max_storage_mb} MB).");
                return redirect()->back();
            }
        }

        return $next($request);
    }
}
