<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.super_admin.plans.edit_title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-6 py-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.plans.edit_title')</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.edit_desc', ['name' => $plan->name])</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.plans.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    @lang('admin::app.super_admin.plans.back')
                </a>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-300">Super Admin Mode</span>
            </div>
        </div>

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-950">
                <ul class="list-disc pl-5 text-sm font-medium text-red-700 dark:text-red-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-2xl rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-5 flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </span>
                @lang('admin::app.super_admin.plans.edit_desc', ['name' => $plan->name])
            </h3>
            
            <form action="{{ route('super_admin.plans.update', ['id' => $plan->id]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.name')</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $plan->name) }}" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm shadow-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="price" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.price')</label>
                        <input type="number" step="1000" name="price" id="price" required value="{{ old('price', $plan->price) }}" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm shadow-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label for="max_users" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.user_limit')</label>
                        <input type="number" name="max_users" id="max_users" required value="{{ old('max_users', $plan->max_users) }}" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm shadow-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="max_leads" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.lead_limit')</label>
                        <input type="number" name="max_leads" id="max_leads" required value="{{ old('max_leads', $plan->max_leads) }}" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm shadow-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label for="max_storage_mb" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.storage_limit')</label>
                        <input type="number" name="max_storage_mb" id="max_storage_mb" required value="{{ old('max_storage_mb', $plan->max_storage_mb) }}" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm shadow-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.description_label')</label>
                    <textarea name="description" id="description" rows="3" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm shadow-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">{{ old('description', $plan->description) }}</textarea>
                </div>

                <div class="flex items-center gap-3 border-t border-gray-100 pt-6 dark:border-gray-800">
                    <a href="{{ route('super_admin.plans.index') }}" class="rounded-xl border border-gray-200 bg-white px-6 py-3 text-xs font-bold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        @lang('admin::app.super_admin.plans.cancel')
                    </a>
                    <button type="submit" class="rounded-xl bg-blue-600 px-6 py-3 text-xs font-bold text-white shadow-md shadow-blue-100 transition-all hover:bg-blue-700 dark:shadow-none">
                        @lang('admin::app.super_admin.plans.update')
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin::layouts>
