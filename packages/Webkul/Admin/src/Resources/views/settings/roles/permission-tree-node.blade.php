@foreach($nodes as $node)
    <div class="rounded-lg border border-gray-200/80 bg-gray-50/60 p-3 dark:border-gray-800 dark:bg-gray-950/40 space-y-2">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs font-extrabold text-gray-900 dark:text-white">
                @if(!empty($node['children']))
                    <svg class="h-4 w-4 text-sky-600 dark:text-sky-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                @else
                    <svg class="h-3.5 w-3.5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
                <span>{{ $node['name'] }}</span>
            </div>

            {{-- @if($node['is_active'])
                <span class="inline-flex items-center gap-1 rounded bg-emerald-100/90 px-2 py-0.5 text-[10px] font-bold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                    Aktif
                </span>
            @endif --}}
        </div>

        @if(!empty($node['children']))
            <div class="pl-3.5 space-y-2 border-l-2 border-sky-200 dark:border-sky-900/70 ml-2 mt-2">
                @include('admin::settings.roles.permission-tree-node', ['nodes' => $node['children']])
            </div>
        @endif
    </div>
@endforeach
