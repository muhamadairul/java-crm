<?php

namespace Webkul\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\Admin\Helpers\CompanySmtpMailer;

class CompanySmtpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Applies company-specific SMTP configuration so that any
     * Mail::send() or Mail::queue() call within this request
     * uses the company's own SMTP credentials.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guard('user')->check()) {
            CompanySmtpMailer::apply();
        }

        return $next($request);
    }
}
