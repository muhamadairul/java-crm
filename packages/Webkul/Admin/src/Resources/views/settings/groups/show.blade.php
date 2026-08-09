<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.settings.groups.index.show.title') - {{ $group->name }}
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header Card -->
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.settings.groups.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white">
                        @lang('admin::app.settings.groups.index.show.back-btn')
                    </a>
                </div>
                <div class="flex items-center gap-3 mt-1">
                    <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $group->name }}</h1>
                    <span class="inline-flex items-center rounded-md bg-sky-100 px-2.5 py-1 text-xs font-bold text-sky-800 dark:bg-sky-950 dark:text-sky-300">
                        User Group
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $group->description ?: 'Tidak ada deskripsi' }}</p>
            </div>

            <div class="flex items-center gap-2">
                @if (bouncer()->hasPermission('settings.user.groups.edit'))
                    <a href="{{ route('admin.settings.groups.edit', $group->id) }}" class="primary-button">
                        @lang('admin::app.settings.groups.index.show.edit-btn')
                    </a>
                @endif
            </div>
        </div>

        <!-- Info Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">@lang('admin::app.settings.groups.index.show.total-members')</span>
                <p class="text-3xl font-extrabold text-sky-600 mt-1 dark:text-sky-400">{{ $users->count() }} User</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">@lang('admin::app.settings.groups.index.show.group-id')</span>
                <p class="text-base font-bold text-gray-800 mt-1 dark:text-white">#{{ $group->id }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">@lang('admin::app.settings.groups.index.show.created-at')</span>
                <p class="text-base font-bold text-gray-800 mt-1 dark:text-white">{{ $group->created_at ? $group->created_at->format('d M Y') : '-' }}</p>
            </div>
        </div>

        <!-- Users Assigned Table -->
        <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-base font-extrabold text-gray-900 mb-4 dark:text-white">@lang('admin::app.settings.groups.index.show.users-title')</h2>

            @if($users->isEmpty())
                <div class="py-8 text-center text-xs text-gray-400 font-medium">
                    @lang('admin::app.settings.groups.index.show.empty-users')
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-gray-200 bg-gray-50 text-gray-600 uppercase font-bold dark:border-gray-800 dark:bg-gray-950 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">@lang('admin::app.settings.groups.index.show.user')</th>
                                <th class="px-4 py-3">@lang('admin::app.settings.groups.index.show.email')</th>
                                <th class="px-4 py-3">@lang('admin::app.settings.groups.index.show.role')</th>
                                <th class="px-4 py-3">@lang('admin::app.settings.groups.index.show.data-permission')</th>
                                <th class="px-4 py-3">@lang('admin::app.settings.groups.index.show.status')</th>
                                <th class="px-4 py-3 text-right">@lang('admin::app.settings.groups.index.show.action')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($users as $u)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-950/50">
                                    <td class="px-4 py-3.5 font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                                        <div class="h-8 w-8 rounded-full bg-sky-600 text-white flex items-center justify-center font-bold text-xs">
                                            {{ substr($u->name, 0, 1) }}
                                        </div>
                                        <span>{{ $u->name }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-600 dark:text-gray-300 font-medium">{{ $u->email }}</td>
                                    <td class="px-4 py-3.5 font-bold text-gray-800 dark:text-gray-200">
                                        {{ $u->role->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 font-bold capitalize text-gray-700 dark:text-gray-300">{{ $u->view_permission ?: 'global' }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $u->status ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300' }}">
                                            {{ $u->status ? trans('admin::app.settings.groups.index.show.active') : trans('admin::app.settings.groups.index.show.inactive') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right font-bold">
                                        <a href="{{ route('admin.settings.users.index') }}" class="text-sky-600 hover:underline dark:text-sky-400">
                                            @lang('admin::app.settings.groups.index.show.view-user-list')
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
