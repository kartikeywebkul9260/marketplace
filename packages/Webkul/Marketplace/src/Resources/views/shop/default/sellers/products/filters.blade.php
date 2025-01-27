<!-- Desktop Filters Naviation -->
<div v-if="! isMobile">
    <!-- Filters Vue Compoment -->
    <v-mp-filters
        @filter-applied="setFilters('filter', $event)"
        @filter-clear="clearFilters('filter', $event)"
    >
        <!-- Category Filter Shimmer Effect -->
        <x-shop::shimmer.categories.filters />
    </v-mp-filters>
</div>

<!-- Mobile Filters Naviation -->
<div
    class="grid grid-cols-[1fr_auto_1fr] justify-items-center items-center w-full max-w-full fixed bottom-0 ltr:left-0 rtl:right-0 px-5 bg-white border-t border-[#E9E9E9] z-50"
    v-if="isMobile"
>
    <!-- Filter Drawer -->
    <x-shop::drawer
        position="left"
        width="100%"
        ::is-active="isDrawerActive.filter"
    >
        <!-- Drawer Toggler -->
        <x-slot:toggle>
            <div
                class="flex items-center gap-x-2.5 px-2.5 py-3.5 text-base font-medium uppercase cursor-pointer"
                @click="isDrawerActive.filter = true"
            >
                <span class="icon-filter-1 text-2xl"></span>

                @lang('marketplace::app.shop.sellers.profile.products.filters.filters')
            </div>
        </x-slot>

        <!-- Drawer Header -->
        <x-slot:header>
            <div class="flex justify-between items-center pb-5 border-b border-[#E9E9E9]">
                <p class="text-lg font-semibold">
                    @lang('marketplace::app.shop.sellers.profile.products.filters.filters')
                </p>

                <p
                    class="ltr:mr-[50px] rtl:ml-[50px] text-xs font-medium cursor-pointer"
                    @click="clearFilters('filter', '')"
                >
                     @lang('marketplace::app.shop.sellers.profile.products.filters.clear-all')
                </p>
            </div>
        </x-slot>

        <!-- Drawer Content -->
        <x-slot:content>
            <!-- Filters Vue Compoment -->
            <v-mp-filters
                @filter-applied="setFilters('filter', $event)"
                @filter-clear="clearFilters('filter', $event)"
            >
                <!-- Category Filter Shimmer Effect -->
                <x-shop::shimmer.categories.filters />
            </v-mp-filters>
        </x-slot>
    </x-shop::drawer>

    <!-- Seperator -->
    <span class="h-5 w-0.5 bg-[#E9E9E9]"></span>

    <!-- Sort Drawer -->
    <x-shop::drawer
        position="bottom"
        width="100%"
        ::is-active="isDrawerActive.toolbar"
    >
        <!-- Drawer Toggler -->
        <x-slot:toggle>
            <div
                class="flex items-center gap-x-2.5 px-2.5 py-3.5 text-base font-medium uppercase cursor-pointer"
                @click="isDrawerActive.toolbar = true"
            >
                <span class="icon-sort-1 text-2xl"></span>

                @lang('marketplace::app.shop.sellers.profile.products.filters.sort')
            </div>
        </x-slot>

        <!-- Drawer Header -->
        <x-slot:header>
            <div class="flex justify-between items-center pb-5 border-b border-[#E9E9E9]">
                <p class="text-lg font-semibold">
                    @lang('marketplace::app.shop.sellers.profile.products.filters.sort')
                </p>
            </div>
        </x-slot>

        <!-- Drawer Content -->
        <x-slot:content>
            @include('marketplace::shop.sellers.products.toolbar')
        </x-slot>
    </x-shop::drawer>
</div>

@pushOnce('scripts')
    <!-- Filters Vue template -->
    <script type="text/x-template" id="v-mp-filters-template">
        <!-- Filter Shimmer Effect -->
        <template v-if="isLoading">
            <x-shop::shimmer.categories.filters />
        </template>

        <!-- Filters Container -->
        <template v-else>
            <div class="panel-side grid grid-cols-[1fr] max-h-[1320px] overflow-y-auto overflow-x-hidden journal-scroll min-w-[342px] max-xl:min-w-[270px] md:max-w-[400px] md:pr-7">
                <!-- Filters Header Container -->
                <div class="flex justify-between items-center h-[50px] pb-2.5 border-b border-[#E9E9E9] max-md:hidden">
                    <p class="text-lg font-semibold">
                        @lang('marketplace::app.shop.sellers.profile.products.filters.filters')
                    </p>

                    <p
                        class="text-xs font-medium cursor-pointer"
                        tabindex="0"
                        @click="clear()"
                    >
                        @lang('marketplace::app.shop.sellers.profile.products.filters.clear-all')
                    </p>
                </div>

                <!-- Filters Items Vue Component -->
                <v-filter-item
                    ref="filterItemComponent"
                    :key="filterIndex"
                    :filter="filter"
                    v-for='(filter, filterIndex) in filters.available'
                    @values-applied="applyFilter(filter, $event)"
                >
                </v-filter-item>
            </div>
        </template>
    </script>

    <!-- Filter Item Vue template -->
    <script type="text/x-template" id="v-filter-item-template">
        <template v-if="filter.type === 'price' || filter.options.length">
            <x-shop::accordion class="last:border-b-0">
                <!-- Filter Item Header -->
                <x-slot:header class="px-0 py-2.5">
                    <div class="flex justify-between items-center">
                        <p
                            class="text-lg font-semibold"
                            v-text="filter.name"
                        >
                        </p>
                    </div>
                </x-slot>

                <!-- Filter Item Content -->
                <x-slot:content class="!p-0">
                    <!-- Price Range Filter -->
                    <ul v-if="filter.type === 'price'">
                        <li>
                            <v-price-filter
                                :key="refreshKey"
                                :default-price-range="appliedValues"
                                @set-price-range="applyValue($event)"
                            >
                            </v-price-filter>
                        </li>
                    </ul>

                    <!-- Checkbox Filter Options -->
                    <ul class="pb-3 text-sm text-gray-700" v-else>
                        <li
                            :key="option.id"
                            v-for="(option, optionIndex) in filter.options"
                        >
                            <div class="items-center flex gap-x-4 ltr:pl-2 rtl:pr-2 rounded hover:bg-gray-100 select-none">
                                <input
                                    type="checkbox"
                                    :id="'option_' + option.id"
                                    class="hidden peer"
                                    :value="option.id"
                                    v-model="appliedValues"
                                    @change="applyValue"
                                />

                                <label
                                    class="icon-uncheck text-2xl text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                                    role="checkbox"
                                    aria-checked="false"
                                    :aria-label="option.name"
                                    :aria-labelledby="'label_option_' + option.id"
                                    tabindex="0"
                                    :for="'option_' + option.id"
                                >
                                </label>

                                <label
                                    class="w-full p-2 ltr:pl-0 rtl:pr-0 text-base text-gray-900 cursor-pointer"
                                    :id="'label_option_' + option.id"
                                    :for="'option_' + option.id"
                                    role="button"
                                    tabindex="0"
                                    v-text="option.name"
                                >
                                </label>
                            </div>
                        </li>
                    </ul>
                </x-slot>
            </x-shop::accordion>
        </template>
    </script>

    <script type="text/x-template" id="v-price-filter-template">
        <div>

            <!-- Price range filter shimmer -->
            <template v-if="isLoading">
                <x-shop::shimmer.range-slider />
            </template>

            <template v-else>
                <x-shop::range-slider
                    ::key="refreshKey"
                    default-type="price"
                    ::default-allowed-max-range="allowedMaxPrice"
                    ::default-min-range="minRange"
                    ::default-max-range="maxRange"
                    @change-range="setPriceRange($event)"
                />
            </template>
        </div>
    </script>

    <script type='module'>
        app.component('v-mp-filters', {
            template: '#v-mp-filters-template',

            data() {
                return {
                    isLoading: true,

                    filters: {
                        available: {},

                        applied: {},
                    },
                };
            },

            mounted() {
                this.getFilters();

                this.setFilters();
            },

            methods: {
                getFilters() {
                    this.$axios.get('{{ route("shop.api.categories.attributes") }}', {
                            params: { 
                                category_id: "{{ isset($category) ? $category->id : ''  }}",
                            }
                        })
                        .then((response) => {
                            this.isLoading = false;

                            this.filters.available = response.data.data;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },

                setFilters() {
                    let queryParams = new URLSearchParams(window.location.search);

                    queryParams.forEach((value, filter) => {
                        /**
                         * Removed all toolbar filters in order to prevent key duplication.
                         */
                        if (! ['sort', 'limit', 'mode'].includes(filter)) {
                            this.filters.applied[filter] = value.split(',');
                        }
                    });

                    this.$emit('filter-applied', this.filters.applied);
                },

                applyFilter(filter, values) {
                    if (values.length) {
                        this.filters.applied[filter.code] = values;
                    } else {
                        delete this.filters.applied[filter.code];
                    }

                    this.$emit('filter-applied', this.filters.applied);
                },

                clear() {
                    /**
                     * Clearing parent component.
                     */
                    this.filters.applied = {};

                    /**
                     * Clearing child components. Improvisation needed here.
                     */
                    this.$refs.filterItemComponent.forEach((filterItem) => {
                        if (filterItem.filter.code === 'price') {
                            filterItem.$data.appliedValues = null;
                        } else {
                            filterItem.$data.appliedValues = [];
                        }
                    });

                    this.$emit('filter-applied', this.filters.applied);
                },
            },
        });

        app.component('v-filter-item', {
            template: '#v-filter-item-template',

            props: ['filter'],

            data() {
                return {
                    active: true,

                    appliedValues: null,

                    refreshKey: 0,
                }
            },

            watch: {
                appliedValues() {
                    if (this.filter.code === 'price' && ! this.appliedValues) {
                        ++this.refreshKey;
                    }
                },
            },

            mounted() {
                if (this.filter.code === 'price') {
                    /**
                     * Improvisation needed here for `this.$parent.$data`.
                     */
                    this.appliedValues = this.$parent.$data.filters.applied[this.filter.code]?.join(',');

                    ++this.refreshKey;

                    return;
                }

                /**
                 * Improvisation needed here for `this.$parent.$data`.
                 */
                this.appliedValues = this.$parent.$data.filters.applied[this.filter.code] ?? [];
            },

            methods: {
                applyValue($event) {
                    if (this.filter.code === 'price') {
                        this.appliedValues = $event;

                        this.$emit('values-applied', this.appliedValues);

                        return;
                    }

                    this.$emit('values-applied', this.appliedValues);
                },
            },
        });

        app.component('v-price-filter', {
            template: '#v-price-filter-template',

            props: ['defaultPriceRange'],

            data() {
                return {
                    refreshKey: 0,

                    isLoading: true,

                    allowedMaxPrice: 100,

                    priceRange: this.defaultPriceRange ?? [0, 100].join(','),
                };
            },

            computed: {
                minRange() {
                    let priceRange = this.priceRange.split(',');

                    return priceRange[0];
                },

                maxRange() {
                    let priceRange = this.priceRange.split(',');

                    return priceRange[1];
                }
            },

            mounted() {
                this.getMaxPrice();
            },

            methods: {
                getMaxPrice() {
                    this.$axios.get('{{ route("shop.api.categories.max_price", $category->id ?? '') }}')
                        .then((response) => {
                            this.isLoading = false;

                            /**
                             * If data is zero, then default price will be displayed.
                             */
                            if (response.data.data.max_price) {
                                this.allowedMaxPrice = response.data.data.max_price;
                            }

                            if (! this.defaultPriceRange) {
                                this.priceRange = [0, this.allowedMaxPrice].join(',');
                            }

                            ++this.refreshKey;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },

                setPriceRange($event) {
                    this.priceRange = [$event.minRange, $event.maxRange].join(',');

                    this.$emit('set-price-range', this.priceRange);
                },
            },
        });
    </script>
@endPushOnce
