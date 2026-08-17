<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Webkul\Admin\Helpers\CompanyDefaultSeeder;
use Webkul\Core\Models\Company;
use Webkul\Core\Models\Plan;
use Webkul\Core\Models\Subscription;
use Webkul\Core\Models\Invoice;
use Webkul\Core\Services\XenditService;
use Webkul\User\Models\Role;
use Webkul\User\Models\User;

class RegistrationController extends Controller
{
    /**
     * Show Registration Step 1 Form.
     */
    public function showStep1(Request $request): View
    {
        $planCode = $request->query('plan', 'pro');
        $sessionData = session()->get('registration_data', []);
        
        return view('admin::front.register.step1', compact('planCode', 'sessionData'));
    }

    /**
     * Handle Registration Step 1 Submission.
     */
    public function postStep1(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name'    => 'required|string|max:255',
            'company_phone'   => 'required|string|max:30',
            'company_email'   => 'required|email|max:255',
            'company_address' => 'required|string|max:1000',
            'admin_email'     => 'required|email|max:255|unique:users,email',
            'admin_password'  => 'required|string|min:6',
        ]);

        $companySlug = Str::slug($request->company_name);
        
        // Ensure slug is unique
        $originalSlug = $companySlug;
        $count = 1;
        while (DB::table('companies')->where('slug', $companySlug)->exists()) {
            $companySlug = $originalSlug . '-' . $count;
            $count++;
        }

        // Store step 1 data in session
        $registrationData = [
            'company' => [
                'name'    => $request->company_name,
                'slug'    => $companySlug,
                'phone'   => $request->company_phone,
                'email'   => $request->company_email,
                'address' => $request->company_address,
            ],
            'admin' => [
                'email'    => $request->admin_email,
                'password' => $request->admin_password,
            ]
        ];

        session()->put('registration_data', $registrationData);

        return redirect()->route('tenant.register.step2');
    }

    /**
     * Show Registration Step 2 (Plan Selection).
     */
    public function showStep2(Request $request): View|RedirectResponse
    {
        if (! session()->has('registration_data')) {
            return redirect()->route('tenant.register.step1');
        }

        $selectedCurrency = 'IDR';
        session()->put('registration_data.currency', $selectedCurrency);

        $currencies = [
            'IDR' => ['symbol' => 'Rp ', 'rate' => 16000.0],
        ];

        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        foreach ($plans as $plan) {
            $plan->converted_price = $plan->price;
        }

        $selectedPlanCode = session()->get('registration_data.plan_code', 'pro');

        return view('admin::front.register.step2', compact('plans', 'selectedPlanCode', 'currencies', 'selectedCurrency'));
    }

    /**
     * Handle Registration Step 2 Submission.
     */
    public function postStep2(Request $request): RedirectResponse
    {
        if (! session()->has('registration_data')) {
            return redirect()->route('tenant.register.step1');
        }

        $request->validate([
            'plan_code' => 'required|exists:plans,code',
        ]);

        session()->put('registration_data.plan_code', $request->plan_code);
        session()->put('registration_data.currency', 'IDR');

        return redirect()->route('tenant.register.step3');
    }

    /**
     * Show Registration Step 3 (Payment).
     */
    public function showStep3(): View|RedirectResponse
    {
        if (! session()->has('registration_data') || ! session()->has('registration_data.plan_code')) {
            return redirect()->route('tenant.register.step1');
        }

        $registrationData = session()->get('registration_data');
        $plan = Plan::where('code', $registrationData['plan_code'])->first();

        $selectedCurrency = 'IDR';
        $currencySymbol = 'Rp ';

        $plan->converted_price = $plan->price;

        return view('admin::front.register.step3', compact('registrationData', 'plan', 'selectedCurrency', 'currencySymbol'));
    }

    /**
     * Handle Registration Step 3 Completion (Creation + Payment Setup).
     */
    public function postStep3(Request $request): RedirectResponse
    {
        if (! session()->has('registration_data') || ! session()->has('registration_data.plan_code')) {
            return redirect()->route('tenant.register.step1');
        }

        $validator = Validator::make($request->all(), [
            'payment_method_type' => 'required|in:VIRTUAL_ACCOUNT,QR_CODE',
        ]);

        if ($validator->fails()) {
            Log::error('Validator failed: ' . $validator->errors()->first());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $registrationData = session()->get('registration_data');
        $plan = Plan::where('code', $registrationData['plan_code'])->first();

        // 1. Validate payment details if paid plan
        $paymentType = 'FREE';
        $details = [];
        
        if ($plan->price > 0) {
            $paymentType = $request->payment_method_type;

            if ($paymentType === 'VIRTUAL_ACCOUNT') {
                $validator = Validator::make($request->all(), [
                    'va_bank' => 'required|in:MANDIRI,BRI,BNI,PERMATA,BCA',
                ]);
                if ($validator->fails()) {
                    Log::error('Validator failed: ' . $validator->errors()->first());
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                $details = [
                    'channel_code'  => $request->va_bank,
                    'customer_name' => 'Administrator',
                    'success_url'   => route('java-crm.home'),
                ];
            } elseif ($paymentType === 'QR_CODE') {
                $details = [
                    'channel_code' => 'QRIS',
                    'success_url'  => route('java-crm.home'),
                ];
            }
        }

        // 2. Database Transaction to Create Company, Role, and User
        DB::beginTransaction();
        try {
            $isPaid = $plan->price > 0;
            $companyActive = !$isPaid;


            // Create Company
            $company = Company::create([
                'name'          => $registrationData['company']['name'],
                'slug'          => $registrationData['company']['slug'],
                'phone'         => $registrationData['company']['phone'],
                'email'         => $registrationData['company']['email'],
                'address'       => $registrationData['company']['address'],
                'plan_id'       => $plan->id,
                'is_active'     => $companyActive,
                'trial_ends_at' => !$isPaid ? now()->addDays(30) : null,
            ]);

            // Auto-seed 2 default roles for this company
            $companyAdminRole = Role::create([
                'name'            => 'Company Admin',
                'description'     => 'Administrator perusahaan dengan akses penuh ke semua fitur CRM.',
                'permission_type' => 'all',
                'permissions'     => [],
                'company_id'      => $company->id,
            ]);

            Role::create([
                'name'            => 'Sales User',
                'description'     => 'Pengguna sales dengan akses terbatas sesuai permission yang diberikan.',
                'permission_type' => 'custom',
                'permissions'     => [
                    'dashboard',
                    'leads', 'leads.create', 'leads.view', 'leads.edit',
                    'quotes', 'quotes.create', 'quotes.view', 'quotes.edit',
                    'mail', 'mail.create', 'mail.view',
                    'activities', 'activities.create', 'activities.view', 'activities.edit',
                    'contacts', 'contacts.persons', 'contacts.persons.create', 'contacts.persons.view', 'contacts.persons.edit',
                    'contacts.organizations', 'contacts.organizations.view',
                    'products', 'products.view',
                    'settings.other_settings', 'settings.other_settings.tags',
                ],
                'company_id'      => $company->id,
            ]);

            // Create Company Admin User (assigned to the company-specific Company Admin role)
            $adminUser = User::create([
                'name'            => 'Administrator',
                'email'           => $registrationData['admin']['email'],
                'password'        => bcrypt($registrationData['admin']['password']),
                'status'          => 1,
                'view_permission' => 'global',
                'role_id'         => $companyAdminRole->id,
                'company_id'      => $company->id,
            ]);

            // Seed default initial data (pipeline, stages, sources, types, groups, tags)
            CompanyDefaultSeeder::seed($company->id, $adminUser->id);

            // Create Subscription Record
            $subscription = Subscription::create([
                'company_id' => $company->id,
                'plan_id'    => $plan->id,
                'status'     => !$isPaid ? 'active' : 'pending',
                'starts_at'  => now(),
                'ends_at'    => !$isPaid ? now()->addDays(30) : now()->addMonth(),
            ]);

            if ($isPaid) {
                $selectedCurrency = 'IDR';
                $convertedAmount = $plan->price;

                $invoiceNumber = 'INV-' . strtoupper(Str::random(10));
                
                // Create pending Invoice
                $invoice = Invoice::create([
                    'company_id'      => $company->id,
                    'subscription_id' => $subscription->id,
                    'invoice_number'  => $invoiceNumber,
                    'amount'          => $convertedAmount,
                    'currency'        => 'IDR',
                    'status'          => 'pending',
                    'payment_method'  => $paymentType,
                ]);

                // Call Xendit
                $xenditService = new XenditService();
                $paymentResponse = $xenditService->createPaymentRequest(
                    $invoiceNumber,
                    $convertedAmount,
                    $details
                );

                // Update invoice with Xendit response info
                $xenditInvoiceId = $paymentResponse['payment_request_id'] ?? ($paymentResponse['id'] ?? null);
                
                $xenditInvoiceUrl = null;
                if (!empty($paymentResponse['actions']) && is_array($paymentResponse['actions'])) {
                    foreach ($paymentResponse['actions'] as $act) {
                        if (!empty($act['value'])) {
                            $xenditInvoiceUrl = $act['value'];
                            break;
                        } elseif (!empty($act['url'])) {
                            $xenditInvoiceUrl = $act['url'];
                            break;
                        }
                    }
                }
                
                $bankCode = $paymentResponse['channel_code'] ?? null;

                Log::info('xenditInvoiceId', [$xenditInvoiceId]);
                Log::info('xenditInvoiceUrl', [$xenditInvoiceUrl]);
                Log::info('bankCode', [$bankCode]);

                $invoice->xendit_invoice_id  = $xenditInvoiceId;
                $invoice->xendit_invoice_url = $xenditInvoiceUrl;
                $invoice->bank_code          = $bankCode;
                $invoice->response_request   = json_encode($paymentResponse);
                
                // Immediate success check (e.g. simulation or immediate approval)
                if (isset($paymentResponse['status']) && strtoupper($paymentResponse['status']) === 'SUCCEEDED') {
                    $invoice->status  = 'paid';
                    $invoice->paid_at = now();
                    $invoice->save();

                    // Activate subscription and company
                    $subscription->status    = 'active';
                    $subscription->starts_at = now();
                    $subscription->ends_at   = $plan->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth();
                    $subscription->save();

                    $company->is_active = true;
                    $company->save();

                    DB::commit();
                    session()->forget('registration_data');
                    auth()->guard('user')->login($adminUser);
                    session()->flash('success', 'Pembayaran berhasil dan akun Anda telah diaktifkan!');
                    return redirect()->route('admin.dashboard.index');
                }

                $invoice->save();
                DB::commit();

                // Clear session data
                session()->forget('registration_data');

                // Redirect to pending page
                return redirect()->route('tenant.register.payment_pending', ['invoice_id' => $invoice->id]);
            }

            DB::commit();

            // Clear session data
            session()->forget('registration_data');

            // Log in the user immediately
            auth()->guard('user')->login($adminUser);

            // Redirect to tenant dashboard
            session()->flash('success', 'Pendaftaran Perusahaan dan Akun Admin berhasil!');
            return redirect()->route('admin.dashboard.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data pendaftaran: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show Payment Pending page.
     */
    public function paymentPending($invoiceId): View|RedirectResponse
    {
        $invoice = Invoice::with(['company', 'subscription.plan'])->find($invoiceId);
        if (!$invoice || $invoice->status === 'paid') {
            // If already paid, log in and redirect
            if ($invoice && $invoice->status === 'paid') {
                $adminUser = User::where('company_id', $invoice->company_id)->first();
                if ($adminUser) {
                    auth()->guard('user')->login($adminUser);
                }
                return redirect()->route('admin.dashboard.index');
            }
            return redirect()->route('tenant.register.step1');
        }

        $paymentDetails = json_decode($invoice->response_request, true) ?: (json_decode($invoice->notes, true) ?: []);

        return view('admin::front.register.payment-pending', compact('invoice', 'paymentDetails'));
    }

    /**
     * Check payment status.
     */
    public function checkPaymentStatus(Request $request, $invoiceId): JsonResponse|RedirectResponse
    {
        $invoice = Invoice::find($invoiceId);
        
        if (!$invoice) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'not_found', 'message' => 'Tagihan tidak ditemukan'], 404);
            }
            return redirect()->route('tenant.register.step1');
        }

        if ($invoice->status === 'paid') {
            $adminUser = User::where('company_id', $invoice->company_id)->first();
            if ($adminUser) {
                auth()->guard('user')->login($adminUser);
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status'       => 'paid',
                    'redirect_url' => route('admin.dashboard.index'),
                    'message'      => 'Pembayaran Berhasil! Akun Admin Perusahaan Anda telah diaktifkan.',
                ]);
            }

            session()->flash('success', 'Pembayaran berhasil dikonfirmasi! Selamat datang di JavaCRM.');
            return redirect()->route('admin.dashboard.index');
        }

        if ($invoice->status === 'failed') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status'       => 'failed',
                    'redirect_url' => route('tenant.register.step3'),
                    'message'      => 'Pembayaran Gagal atau Dibatalkan. Silakan selesaikan ulang pembayaran.',
                ]);
            }

            session()->flash('error', 'Pembayaran Gagal. Silakan selesaikan ulang pembayaran.');
            return redirect()->route('tenant.register.step3');
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status'  => 'pending',
                'message' => 'Menunggu Pembayaran',
            ]);
        }

        session()->flash('info', 'Pembayaran Anda masih diproses. Silakan selesaikan pembayaran sesuai petunjuk.');
        return redirect()->back();
    }

    /**
     * Simulate E-Wallet Redirect Checkout Page
     */
    public function simulateEwalletRedirect(Request $request): View|RedirectResponse
    {
        $ref = $request->query('ref');
        if (!$ref) {
            return redirect()->route('tenant.register.step1');
        }

        $invoice = Invoice::where('invoice_number', $ref)->first();
        if (!$invoice || $invoice->status === 'paid') {
            return redirect()->route('tenant.register.step1');
        }

        return view('admin::front.register.simulate-ewallet', compact('invoice'));
    }
}
