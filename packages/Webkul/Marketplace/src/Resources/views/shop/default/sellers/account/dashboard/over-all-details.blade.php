<!-- Over Details Vue Component -->
<v-dashboard-overall-details>
    <!-- Shimmer -->
    <x:marketplace::shop.shimmer.dashboard.over-all-details />
</v-dashboard-overall-details>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-overall-details-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x:marketplace::shop.shimmer.dashboard.over-all-details />
        </template>

        <!-- Overall Details -->
        <template v-else>
            <div class="grid gap-2.5 p-5 border border-[#E9E9E9] rounded-xl mt-8">
                <h4 class="text-xl font-medium">
                    @lang('marketplace::app.shop.sellers.account.dashboard.overall-details')
                </h4>

                <div class="grid gap-2.5 grid-cols-2 md:grid-cols-3">
                    <!-- Total Sales -->
                    <div class="py-2.5">
                        <div class="flex gap-1 items-end">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.total_sales.formatted_total }}
                            </h3>

                            <p
                                class="text-sm font-normal"
                                :class="[report.statistics.total_sales.progress < 0 ?  'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ Math.abs(report.statistics.total_sales.progress.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm text-[#757575] font-normal">
                            @lang('marketplace::app.shop.sellers.account.dashboard.total-sales')
                        </p>
                    </div>

                    <!-- Total Payout -->
                    <div class="py-2.5">
                        <div class="flex gap-1 items-end">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.total_payout.formatted_total }}
                            </h3>

                            <p class="text-sm  text-[#060C3B] font-normal">
                                @{{ Math.abs(report.statistics.total_payout.percent.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm text-[#757575] font-normal">
                            @lang('marketplace::app.shop.sellers.account.dashboard.total-payout')
                        </p>
                    </div>

                    <!-- Remaining Payout -->
                    <div class="py-2.5">
                        <div class="flex gap-1 items-end">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.remaining_payout.formatted_total }}
                            </h3>

                            <p class="text-sm text-[#060C3B] font-normal">
                                @{{ Math.abs(report.statistics.remaining_payout.percent.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm text-[#757575] font-normal">
                            @lang('marketplace::app.shop.sellers.account.dashboard.remaining-payout')
                        </p>
                    </div>

                    <!-- Total Orders -->
                    <div class="py-2.5">
                        <div class="flex gap-1 items-end">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.total_orders.current }}
                            </h3>

                            <p
                                class="text-sm font-normal"
                                :class="[report.statistics.total_orders.progress < 0 ? 'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ Math.abs(report.statistics.total_orders.progress.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm text-[#757575] font-normal">
                            @lang('marketplace::app.shop.sellers.account.dashboard.total-orders')
                        </p>
                    </div>

                    <!-- Total Customers -->
                    <div class="py-2.5">
                        <div class="flex gap-1 items-end">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.total_customers.current }}
                            </h3>

                            <p
                                class="text-sm font-normal"
                                :class="[report.statistics.total_customers.progress < 0 ? 'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ Math.abs(report.statistics.total_customers.progress.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm text-[#757575] font-normal">
                            @lang('marketplace::app.shop.sellers.account.dashboard.total-customers')
                        </p>
                    </div>

                    <!-- Avg Sale -->
                    <div class="py-2.5">
                        <div class="flex gap-1 items-end">
                            <h3 class="text-2xl font-medium">
                                @{{ report.statistics.avg_sales.formatted_total }}
                            </h3>

                            <p
                                class="text-sm font-normal"
                                :class="[report.statistics.avg_sales.progress < 0 ? 'text-red-500' : 'text-emerald-500']"
                            >
                                @{{ Math.abs(report.statistics.avg_sales.progress.toFixed(2)) }}%
                            </p>
                        </div>

                        <p class="text-sm text-[#757575] font-normal">
                            @lang('marketplace::app.shop.sellers.account.dashboard.avg-order-sell')
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-overall-details', {
            template: '#v-dashboard-overall-details-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filtets) {
                    this.isLoading = true;

                    var filtets = Object.assign({}, filtets);

                    filtets.type = 'over-all';

                    this.$axios.get("{{ route('shop.marketplace.seller.account.dashboard.stats') }}", {
                            params: filtets
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {});
                }
            }
        });
    </script>
@endPushOnce
