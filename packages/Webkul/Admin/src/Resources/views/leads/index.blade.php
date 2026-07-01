<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.leads.index.title')
    </x-slot>

    <!-- Header -->
    {!! view_render_event('admin.leads.index.header.before') !!}

    <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        {!! view_render_event('admin.leads.index.header.left.before') !!}

        <div class="flex flex-col gap-2">
            <!-- Breadcrumb's -->
            <x-admin::breadcrumbs name="leads" />

            <div class="text-xl font-bold dark:text-white">
                @lang('admin::app.leads.index.title')
            </div>
        </div>

        {!! view_render_event('admin.leads.index.header.left.after') !!}

        {!! view_render_event('admin.leads.index.header.right.before') !!}

        <div class="flex items-center gap-x-2.5">
            <!-- Upload File for Lead Creation -->
            @if(core()->getConfigData('general.magic_ai.doc_generation.enabled'))
                @include('admin::leads.index.upload')
            @endif

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
                            href="{{ route('admin.leads.export', ['format' => 'xlsx']) }}"
                        >
                            Excel (.xlsx)
                        </a>
                        <a
                            class="flex items-center gap-2 cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                            href="{{ route('admin.leads.export', ['format' => 'pdf']) }}"
                        >
                            PDF (.pdf)
                        </a>
                    </div>
                </x-slot>
            </x-admin::dropdown>

            <!-- Create button for Leads -->
            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('leads.create'))
                    <a
                        href="{{ route('admin.leads.create', request()->query()) }}"
                        class="primary-button"
                    >
                        @lang('admin::app.leads.index.create-btn')
                    </a>
                @endif
            </div>
        </div>

        {!! view_render_event('admin.leads.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.leads.index.header.after') !!}

    {!! view_render_event('admin.leads.index.content.before') !!}

    <!-- Content -->
    <div class="[&>*>*>*.toolbarRight]:max-lg:w-full [&>*>*>*.toolbarRight]:max-lg:justify-between [&>*>*>*.toolbarRight]:max-md:gap-y-2 [&>*>*>*.toolbarRight]:max-md:flex-wrap mt-3.5 [&>*>*:nth-child(1)]:max-lg:!flex-wrap">
        @if ((request()->view_type ?? "kanban") == "table")
            @include('admin::leads.index.table')
        @else
            @include('admin::leads.index.kanban')
        @endif
    </div>

    {!! view_render_event('admin.leads.index.content.after') !!}
</x-admin::layouts>
