<!-- Top Selling Products Vue Component -->
<v-dashboard-top-customers>
    <!-- Shimmer -->
    <x:marketplace::shop.shimmer.dashboard.top-customers />
</v-dashboard-top-customers>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-top-customers-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x:marketplace::shop.shimmer.dashboard.top-customers />
        </template>

        <!-- Top Customers Section -->
        <template v-else>
            <div class="grid gap-4 content-start border border-[#E9E9E9] rounded-xl p-7">
                <div class="max-h-11 flex justify-between items-center">
                    <h3 class="py-2.5 text-xl font-medium text-navyBlue">
                        @lang('marketplace::app.shop.sellers.account.dashboard.top-customers')
                    </h3>

                    <div
                        class="secondary-button py-2.5 px-5"
                        v-if="report.statistics.length >= 5"
                    >
                        <a href="{{route('shop.marketplace.seller.account.customers.index')}}">
                            @lang('marketplace::app.shop.sellers.account.dashboard.view-all-btn')
                        </a>
                    </div>
                </div>

                <template v-if="report.statistics.length">
                    <div
                        class="flex justify-between items-center py-4 border-b last:border-b-0"
                        v-for="customer in report.statistics"
                    >
                        <div class="grid gap-1">
                            <span class="text-sm font-medium">
                                @{{ customer.full_name }}
                            </span>
                            <span class="text-sm font-normal opacity-80">
                                @{{ customer.email }}
                            </span>
                            <span class="text-sm font-normal opacity-80">
                                @{{ customer.group_name }}
                            </span>
                        </div>

                        <div class="flex gap-5">
                            <div class="flex flex-col gap-1">
                                <span class="text-sm font-medium">
                                    @{{ $shop.formatPrice(customer.total) }}
                                </span>
                                <span class="text-sm font-normal text-[#EB5757] opacity-80">
                                    @{{ "@lang('marketplace::app.shop.sellers.account.dashboard.order-count')".replace(":total", customer.orders) }}
                                </span>
                            </div>

                            <div class="flex items-center">
                                <a href="{{ route('shop.marketplace.seller.account.customers.index') }}">
                                    <span
                                        class="icon-arrow-right cursor-pointer rounded-md p-1 text-2xl transition-all hover:bg-gray-100 max-sm:place-self-center"
                                        title="View"
                                    >
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>

                <template v-else>
                    <div class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5">
                        <img
                            src="{{ bagisto_asset('images/icon-add-product.svg', 'marketplace') }}"
                            class="w-20 h-20"
                        >

                        <div class="flex flex-col items-center">
                            <p class="text-base text-[#6E6E6E] font-semibold">
                                @lang('marketplace::app.shop.sellers.account.dashboard.no-customer')
                            </p>

                            <p class="text-[#6E6E6E] text-center">
                                @lang('marketplace::app.shop.sellers.account.dashboard.customer-info')
                            </p>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-top-customers', {
            template: '#v-dashboard-top-customers-template',

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

                    filtets.type = 'top-customers';

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