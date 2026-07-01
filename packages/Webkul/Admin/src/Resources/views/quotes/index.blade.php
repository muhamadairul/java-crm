<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.quotes.index.title')
    </x-slot>

    <v-qoute>
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <!-- Bredcrumbs -->
                    <x-admin::breadcrumbs name="quotes" />
        
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.quotes.index.title')
                    </div>
                </div>
        
                <div class="flex items-center gap-x-2.5">
                    <!-- Export Dropdown -->
                    <x-admin::dropdown position="bottom-right">
                        <x-slot:toggle>
                            <button class="secondary-button flex items-center gap-1">
                                <span class="icon-download text-2xl"></span>
                                Export
                            </button>
                        </x-slot>

                        <x-slot:content class="mt-2 border-t-0 !p-0 min-w-[120px]">
                            <div class="grid gap-1 py-1.5 text-left">
                                <a
                                    class="flex items-center gap-2 cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                                    href="{{ route('admin.quotes.export', ['format' => 'xlsx']) }}"
                                >
                                    Excel (.xlsx)
                                </a>
                                <a
                                    class="flex items-center gap-2 cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                                    href="{{ route('admin.quotes.export', ['format' => 'pdf']) }}"
                                >
                                    PDF (.pdf)
                                </a>
                            </div>
                        </x-slot>
                    </x-admin::dropdown>

                    <!-- Create button for Quote -->
                    <div class="flex items-center gap-x-2.5">
                        @if (bouncer()->hasPermission('quotes.create'))
                            <a 
                                href="{{ route('admin.quotes.create') }}"
                                class="primary-button"
                            >
                                @lang('admin::app.quotes.index.create-btn')
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        
            <!-- DataGrid Shimmer -->
            <x-admin::shimmer.datagrid />
        </div>
    </v-qoute>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-qoute-template"
        >
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <div class="flex flex-col gap-2">
                        <!-- Bredcrumbs -->
                        <x-admin::breadcrumbs name="quotes" />
        
                        <div class="text-xl font-bold dark:text-white">
                            @lang('admin::app.quotes.index.title')
                        </div>
                    </div>

                    <div class="flex items-center gap-x-2.5">
                        <!-- Export Dropdown -->
                        <x-admin::dropdown position="bottom-right">
                            <x-slot:toggle>
                                <button class="secondary-button flex items-center gap-1">
                                    <span class="icon-download text-2xl"></span>
                                    Export
                                </button>
                            </x-slot>

                            <x-slot:content class="mt-2 border-t-0 !p-0 min-w-[120px]">
                                <div class="grid gap-1 py-1.5 text-left">
                                    <a
                                        class="flex items-center gap-2 cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                                        href="{{ route('admin.quotes.export', ['format' => 'xlsx']) }}"
                                    >
                                        Excel (.xlsx)
                                    </a>
                                    <a
                                        class="flex items-center gap-2 cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                                        href="{{ route('admin.quotes.export', ['format' => 'pdf']) }}"
                                    >
                                        PDF (.pdf)
                                    </a>
                                </div>
                            </x-slot>
                        </x-admin::dropdown>

                        <!-- Create button for person -->
                        <div class="flex items-center gap-x-2.5">
                            {!! view_render_event('admin.quotes.index.create_button.before') !!}
                            
                            @if (bouncer()->hasPermission('quotes.create'))
                                <a 
                                    href="{{ route('admin.quotes.create') }}"
                                    class="primary-button"
                                >
                                    @lang('admin::app.quotes.index.create-btn')
                                </a>
                            @endif
            
                            {!! view_render_event('admin.quotes.index.create_button.after') !!}
                        </div>
                    </div>
                </div>
            
                {!! view_render_event('admin.quotes.index.datagrid.before') !!}
            
                <!-- DataGrid -->
                <x-admin::datagrid :src="route('admin.quotes.index')" />

                {!! view_render_event('admin.quotes.index.datagrid.after') !!}
            </div>
        </script>

        <script type="module">
            app.component('v-qoute', {
                template: '#v-qoute-template',
            });
        </script>
    @endPushOnce
</x-admin::layouts>
