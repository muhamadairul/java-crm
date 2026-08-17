<?php

namespace Webkul\Admin\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Models\SuperAdminAuditLog;

class AuditLogController extends Controller
{
    /**
     * Display a listing of super admin audit logs.
     */
    public function index(Request $request): View
    {
        $query = SuperAdminAuditLog::with('superAdmin')
            ->orderBy('created_at', 'desc');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20);

        return view('admin::super-admin.audit-logs.index', compact('logs'));
    }
}
