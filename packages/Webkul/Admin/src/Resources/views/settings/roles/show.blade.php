<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.roles.index.show.title') - {{ $role->name }}
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header Card -->
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.settings.roles.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white">
                        @lang('admin::app.settings.roles.index.show.back-btn')
                    </a>
                </div>
                <div class="flex items-center gap-3 mt-1">
                    <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $role->name }}</h1>
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold {{ $role->permission_type == 'all' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300' }}">
                        {{ $role->permission_type == 'all' ? trans('admin::app.settings.roles.index.show.all-access') : trans('admin::app.settings.roles.index.show.custom-access') }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $role->description ?: trans('admin::app.settings.roles.index.show.no-description') }}</p>
            </div>

            <div class="flex items-center gap-2">
                @if (bouncer()->hasPermission('settings.user.roles.edit'))
                    <a href="{{ route('admin.settings.roles.edit', $role->id) }}" class="primary-button">
                        @lang('admin::app.settings.roles.index.show.edit-btn')
                    </a>
                @endif
            </div>
        </div>

        <!-- Top Summary Cards (Role ID Card Removed) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">@lang('admin::app.settings.roles.index.show.total-users')</span>
                <p class="text-3xl font-extrabold text-sky-600 mt-1 dark:text-sky-400">{{ $users->count() }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">@lang('admin::app.settings.roles.index.show.permission-type')</span>
                <p class="text-lg font-extrabold text-gray-800 mt-1 dark:text-white capitalize flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full {{ $role->permission_type == 'all' ? 'bg-emerald-500' : 'bg-sky-500' }}"></span>
                    {{ $role->permission_type == 'all' ? trans('admin::app.settings.roles.index.show.all-access') : trans('admin::app.settings.roles.index.show.custom-access') }}
                </p>
            </div>
        </div>

        <!-- Split 2-Column Section: Left (Permissions) & Right (User List) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
            <!-- Left Column: System Permissions & Access Rights (5 cols) -->
            <div class="lg:col-span-5 rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 space-y-4">
                <div class="border-b border-gray-100 pb-3 dark:border-gray-800">
                    <h2 class="text-base font-extrabold text-gray-900 dark:text-white">@lang('admin::app.settings.roles.index.show.permissions-title')</h2>
                </div>

                @if($role->permission_type == 'all')
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-emerald-800 text-xs font-bold flex items-center gap-3 dark:bg-emerald-950/40 dark:border-emerald-900 dark:text-emerald-300">
                        <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>@lang('admin::app.settings.roles.index.show.all-permissions-notice')</span>
                    </div>
                @else
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">@lang('admin::app.settings.roles.index.show.custom-permissions-notice')</p>
                    
                    @if(empty($grantedPermissions))
                        <p class="text-xs text-gray-400 font-medium py-2">Belum ada izin khusus yang ditambahkan.</p>
                    @else
                        <div class="flex flex-wrap gap-2 max-h-[420px] overflow-y-auto pr-1">
                            @foreach($grantedPermissions as $perm)
                                <div class="inline-flex items-center gap-1.5 rounded-lg border border-sky-200/80 bg-sky-50/50 px-2.5 py-1.5 text-xs font-bold text-sky-800 dark:bg-sky-950/30 dark:border-sky-900 dark:text-sky-300">
                                    <svg class="h-3.5 w-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ trans($perm['name']) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

            <!-- Right Column: User List (7 cols) -->
            <div class="lg:col-span-7 rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 space-y-4">
                <div class="border-b border-gray-100 pb-3 dark:border-gray-800 flex items-center justify-between">
                    <h2 class="text-base font-extrabold text-gray-900 dark:text-white">@lang('admin::app.settings.roles.index.show.users-title')</h2>
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400">({{ $users->count() }})</span>
                </div>

                @if($users->isEmpty())
                    <div class="py-8 text-center text-xs text-gray-400 font-medium">
                        @lang('admin::app.settings.roles.index.show.empty-users')
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="border-b border-gray-200 bg-gray-50 text-gray-600 uppercase font-bold dark:border-gray-800 dark:bg-gray-950 dark:text-gray-400">
                                <tr>
                                    <th class="px-3 py-2.5">@lang('admin::app.settings.roles.index.show.user')</th>
                                    <th class="px-3 py-2.5">@lang('admin::app.settings.roles.index.show.email')</th>
                                    <th class="px-3 py-2.5">@lang('admin::app.settings.roles.index.show.groups')</th>
                                    <th class="px-3 py-2.5">@lang('admin::app.settings.roles.index.show.status')</th>
                                    <th class="px-3 py-2.5 text-right">@lang('admin::app.settings.roles.index.show.action')</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($users as $u)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-950/50">
                                        <td class="px-3 py-3 font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                            <div class="h-7 w-7 rounded-full bg-sky-600 text-white flex items-center justify-center font-bold text-[11px]">
                                                {{ substr($u->name, 0, 1) }}
                                            </div>
                                            <span class="truncate max-w-[120px]">{{ $u->name }}</span>
                                        </td>
                                        <td class="px-3 py-3 text-gray-600 dark:text-gray-300 font-medium truncate max-w-[140px]">{{ $u->email }}</td>
                                        <td class="px-3 py-3 text-gray-600 dark:text-gray-300">
                                            {{ $u->groups->pluck('name')->implode(', ') ?: '-' }}
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $u->status ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300' }}">
                                                {{ $u->status ? trans('admin::app.settings.roles.index.show.active') : trans('admin::app.settings.roles.index.show.inactive') }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 text-right font-bold">
                                            <a href="{{ route('admin.settings.users.index') }}" class="text-sky-600 hover:underline dark:text-sky-400">
                                                @lang('admin::app.settings.roles.index.show.view-user-list')
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
    </div>
</x-admin::layouts>
