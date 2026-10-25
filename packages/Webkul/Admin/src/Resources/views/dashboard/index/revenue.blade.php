{!! view_render_event('admin.dashboard.index.revenue.after') !!}

<!-- Over Details Vue Component -->
<v-dashboard-revenue-stats>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.index.revenue />
</v-dashboard-revenue-stats>

{!! view_render_event('admin.dashboard.index.revenue.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-revenue-stats-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.revenue />
        </template>

        <!-- Total Sales Section -->
        <template v-else>
            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex gap-4 max-md:flex-wrap">
                    <!-- Total Revenue -->
                    <div class="flex flex-col justify-between gap-3 min-w-[210px] shrink-0 max-sm:w-full">
                        <!-- Won Revenue Card -->
                        <div class="flex flex-col justify-center gap-2 rounded-lg border border-gray-200 px-4 py-4 dark:border-gray-800 h-[88px] max-sm:w-full">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                @lang('admin::app.dashboard.index.revenue.won-revenue')
                            </p>

                            <div class="flex items-center gap-2">
                                <p class="text-xl font-bold text-green-600">
                                    @{{ report.statistics.total_won_revenue.formatted_total }}
                                </p>

                                <div class="flex items-center gap-0.5">
                                    <span
                                        class="text-base !font-semibold text-green-500"
                                        :class="[report.statistics.total_won_revenue.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                                    ></span>

                                    <p
                                        class="text-xs font-semibold text-green-500"
                                        :class="[report.statistics.total_won_revenue.progress < 0 ?  'text-red-500' : 'text-green-500']"
                                    >
                                        @{{ Math.abs(report.statistics.total_won_revenue.progress.toFixed(2)) }}%
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Lost Revenue Card -->
                        <div class="flex flex-col justify-center gap-2 rounded-lg border border-gray-200 px-4 py-4 dark:border-gray-800 h-[88px] max-sm:w-full">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                @lang('admin::app.dashboard.index.revenue.lost-revenue')
                            </p>

                            <div class="flex items-center gap-2">
                                <p class="text-xl font-bold text-red-500">
                                    @{{ report.statistics.total_lost_revenue.formatted_total }}
                                </p>

                                <div class="flex items-center gap-0.5">
                                    <span
                                        class="text-base !font-semibold text-green-500"
                                        :class="[report.statistics.total_lost_revenue.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                                    ></span>

                                    <p
                                        class="text-xs font-semibold text-green-500"
                                        :class="[report.statistics.total_lost_revenue.progress < 0 ?  'text-red-500' : 'text-green-500']"
                                    >
                                        @{{ Math.abs(report.statistics.total_lost_revenue.progress.toFixed(2)) }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bar Chart -->
                    <div class="flex flex-1 flex-col justify-between gap-3 min-w-0">
                        <div class="relative h-[188px] w-full">
                            <canvas
                                :id="$.uid + '_chart'"
                                class="w-full h-full"
                            ></canvas>
                        </div>

                        <div class="flex justify-center gap-5">
                            <div class="flex items-center gap-2">
                                <span class="h-3.5 w-3.5 rounded-sm bg-green-500 opacity-80"></span>

                                <p class="text-xs dark:text-gray-300">
                                    @lang('admin::app.dashboard.index.revenue.won-revenue')
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="h-3.5 w-3.5 rounded-sm bg-red-500 opacity-80"></span>

                                <p class="text-xs dark:text-gray-300">
                                    @lang('admin::app.dashboard.index.revenue.lost-revenue')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-revenue-stats', {
            template: '#v-dashboard-revenue-stats-template',

            data() {
                return {
                    report: [],

                    isLoading: true,

                    chart: undefined,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    var filters = Object.assign({}, filters);

                    filters.type = 'revenue-stats';

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;

                            setTimeout(() => {
                                this.prepare();
                            }, 0);
                        })
                        .catch(error => {});
                },

                prepare() {
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(document.getElementById(this.$.uid + '_chart'), {
                        type: 'bar',

                        data: {
                            labels: [
                                "@lang('admin::app.dashboard.index.revenue.won-revenue')",
                                "@lang('admin::app.dashboard.index.revenue.lost-revenue')"
                            ],

                            datasets: [{
                                axis: 'y',
                                data: [
                                    this.report.statistics.total_won_revenue.current,
                                    this.report.statistics.total_lost_revenue.current
                                ],

                                backgroundColor: [
                                    'rgba(34, 197, 94, 0.85)',
                                    'rgba(239, 68, 68, 0.85)',
                                ],

                                borderRadius: 6,
                                barThickness: 36,
                                categoryPercentage: 0.9,
                            }],
                        },

                        options: {
                            indexAxis: 'y',

                            maintainAspectRatio: false,

                            responsive: true,

                            plugins: {
                                legend: {
                                    display: false,
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const val = context.raw || 0;
                                            return ' ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(val);
                                        }
                                    }
                                }
                            },

                            scales: {
                                x: {
                                    beginAtZero: true,

                                    ticks: {
                                        display: false,
                                    },

                                    grid: {
                                        display: true,
                                        borderDash: [8, 4],
                                        color: 'rgba(156, 163, 175, 0.25)',
                                    },

                                    border: {
                                        display: false,
                                    }
                                },

                                y: {
                                    display: false,

                                    grid: {
                                        display: false,
                                    },

                                    border: {
                                        display: false,
                                    }
                                }
                            },

                            layout: {
                                padding: {
                                    left: 0,
                                    right: 8,
                                    top: 0,
                                    bottom: 0
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
@endPushOnce
