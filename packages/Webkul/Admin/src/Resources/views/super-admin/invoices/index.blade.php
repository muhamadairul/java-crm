<x-admin::layouts>
    <x-slot:title>
        Kelola Tagihan & Invoice (Billing)
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Title Header -->
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-4 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="text-xl font-bold dark:text-white">
                    Kelola Tagihan & Invoice (Billing)
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 px-3 py-1 rounded-full font-semibold border border-blue-100 dark:border-blue-900">Super Admin Mode</span>
                <span class="text-sm text-gray-500 dark:text-gray-400 font-semibold">{{ $invoices->count() }} Invoice</span>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <form action="{{ route('super_admin.invoices.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex flex-col gap-1 min-w-[200px]">
                    <label for="company_id" class="text-xs font-bold text-gray-500 uppercase">Perusahaan</label>
                    <select name="company_id" id="company_id" class="rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white px-3 py-2 text-sm focus:border-blue-500 bg-white">
                        <option value="">Semua Perusahaan</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1 min-w-[150px]">
                    <label for="status" class="text-xs font-bold text-gray-500 uppercase">Status</label>
                    <select name="status" id="status" class="rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white px-3 py-2 text-sm focus:border-blue-500 bg-white">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>

                <div class="flex items-end h-full pt-5">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl text-xs transition-all shadow-md shadow-blue-100 dark:shadow-none">
                        Filter
                    </button>
                    @if(request()->has('company_id') || request()->has('status'))
                        <a href="{{ route('super_admin.invoices.index') }}" class="ml-2 bg-white hover:bg-gray-50 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-800 border border-gray-200 text-gray-700 py-2 px-6 rounded-xl text-xs transition-colors shadow-sm">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Invoices List Table -->
        <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            @if($invoices->isEmpty())
                <div class="p-8 text-center text-gray-400 font-medium">
                    Tidak ada invoice/tagihan yang ditemukan.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                <th class="py-4 px-6">No. Invoice</th>
                                <th class="py-4 px-6">Perusahaan / Tenant</th>
                                <th class="py-4 px-6">Paket Langganan</th>
                                <th class="py-4 px-6">Jumlah Tagihan</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6">Tanggal Dibuat</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm font-medium">
                            @foreach($invoices as $invoice)
                                <tr class="hover:bg-gray-50/30 transition-colors">
                                    <td class="py-4 px-6 font-mono text-xs font-bold dark:text-white">
                                        {{ $invoice->invoice_number }}
                                    </td>
                                    <td class="py-4 px-6 dark:text-white">
                                        {{ $invoice->company->name ?? '-' }}
                                    </td>
                                    <td class="py-4 px-6 dark:text-white">
                                        @if($invoice->subscription && $invoice->subscription->plan)
                                            <span class="text-blue-600 font-bold bg-blue-50/80 dark:bg-blue-950 dark:text-blue-300 px-2.5 py-1 rounded-full text-xs border border-blue-100 dark:border-blue-900">
                                                {{ $invoice->subscription->plan->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 font-semibold">-</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 font-extrabold text-gray-800 dark:text-white">
                                        {{ $invoice->currency }} {{ number_format($invoice->amount, 2) }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($invoice->status === 'paid')
                                            <span class="text-emerald-600 font-bold bg-emerald-50/80 px-2.5 py-1 rounded-full text-xs border border-emerald-100 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-900">Lunas</span>
                                        @elseif($invoice->status === 'pending')
                                            <span class="text-amber-600 font-bold bg-amber-50/80 px-2.5 py-1 rounded-full text-xs border border-amber-100 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-900">Pending</span>
                                        @elseif($invoice->status === 'expired')
                                            <span class="text-gray-600 font-bold bg-gray-50/80 px-2.5 py-1 rounded-full text-xs border border-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700">Expired</span>
                                        @else
                                            <span class="text-red-600 font-bold bg-red-50/80 px-2.5 py-1 rounded-full text-xs border border-red-100 dark:bg-red-950 dark:text-red-300 dark:border-red-900">Gagal</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-gray-450 dark:text-gray-500 font-semibold text-xs">
                                        {{ $invoice->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('super_admin.invoices.show', ['id' => $invoice->id]) }}" class="text-xs font-bold bg-white border border-gray-200 hover:border-gray-300 text-gray-700 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-800 px-3 py-1.5 rounded-xl shadow-sm transition-all inline-block">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin::layouts>
