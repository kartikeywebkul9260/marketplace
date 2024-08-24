<x-marketplace::shop.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.orders.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::shop.breadcrumbs name="seller_orders" />
    @endSection

    <div class="flex justify-between items-center">
        <div class="">
            <h2 class="text-2xl font-medium">
                @lang('marketplace::app.shop.sellers.account.orders.index.title')
            </h2>
        </div>
    </div>

    <!-- Datagrid -->
    <x-shop::datagrid
        :src="route('shop.marketplace.seller.account.orders.index')"
        :isMultiRow="true"
    >
        <!-- Datagrid Header -->
        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading}">
            <template v-if="! isLoading">
                <div class="row grid grid-cols-[0.5fr_0.6fr_0.5fr_0.5fr_0.3fr_0.1fr] grid-rows-1 items-center px-4 py-2.5 border-b">
                    <div
                        class="flex gap-2.5 items-center select-none"
                        v-for="(columnGroup, index) in [['increment_id', 'created_at', 'order_status'], ['customer_name', 'customer_email', 'location'], ['total_item_count', 'payment_method', 'shipping_method'], ['base_sub_total', 'discount_amount', 'commission'], ['seller_total', 'seller_payout_status']]"
                    >
                        <p class="text-sm font-medium text-[#000000]">
                            <span class="[&>*]:after:content-['_/_']">
                                <template v-for="column in columnGroup">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-sm font-medium text-[#000000]': applied.sort.column == column,
                                            'cursor-pointer hover:text-gray-800': columns.find(columnTemp => columnTemp.index === column)?.sortable,
                                        }"
                                        @click="
                                            columns.find(columnTemp => columnTemp.index === column)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === column)): {}
                                        "
                                    >
                                        @{{ columns.find(columnTemp => columnTemp.index === column)?.label }}
                                    </span>
                                </template>
                            </span>

                            <i
                                class="ltr:ml-1 rtl:mr-1 text-base text-gray-800 align-text-bottom"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="columnGroup.includes(applied.sort.column)"
                            ></i>
                        </p>
                    </div>
                </div>
            </template>

            <!-- Datagrid Head Shimmer -->
            <template v-else>
                <x-shop::shimmer.datagrid.table.head :isMultiRow="true"></x-shop::shimmer.datagrid.table.head>
            </template>
        </template>

        <template #body="{ columns, records, setCurrentSelectionMode, applied, performAction, isLoading }">
            <template v-if="! isLoading">
                <div
                    class="row grid grid-cols-[0.5fr_0.6fr_0.5fr_0.5fr_0.3fr_0.1fr] grid-rows-1 items-center px-4 py-2.5 border-b"
                    v-for="record in records"
                >
                    <!-- Order Id, Created, Status Section -->
                    <div class="grid gap-y-1.5">
                        <p class="text-sm font-semibold">
                            @{{ "@lang('marketplace::app.shop.sellers.account.orders.index.datagrid.order-id')".replace(':id', record.increment_id) }}
                        </p>

                        <p
                            class="text-sm"
                            v-text="record.created_at"
                        >
                        </p>

                        <p
                            class="text-sm"
                            v-html="record.order_status"
                        >
                        </p>
                    </div>

                    <!-- Customer Name, Email, Location Section -->
                    <div class="grid gap-y-1.5">
                        <p
                            class="text-sm font-semibold"
                            v-text="record.customer_name"
                        >
                        </p>

                        <p
                            class="text-sm"
                            v-text="record.customer_email"
                        >
                        </p>

                        <p
                            class="text-sm"
                            v-text="record.location"
                        >
                        </p>
                    </div>

                    <!-- Items, Payment, Shipping -->
                    <div class="grid gap-y-1.5">
                        <p
                            class="text-sm font-semibold"
                            v-text="record.total_item_count"
                        >
                        </p>

                        <p
                            class="text-sm"
                            v-text="record.payment_method"
                        >
                        </p>

                        <p
                            class="text-sm"
                            v-text="record.shipping_method"
                        >
                        </p>
                    </div>

                    <!-- Base Total Amount, Discount, Commission -->
                    <div class="grid gap-y-1.5">
                        <p class="text-sm font-semibold">
                            @{{ $shop.formatPrice(record.base_sub_total) }}
                        </p>

                        <p class="text-sm">
                            @{{ $shop.formatPrice(record.discount_amount) }}
                        </p>

                        <p class="text-sm">
                            @{{ $shop.formatPrice(record.commission) }}
                        </p>
                    </div>

                    <!-- Seller Total Amount, Status -->
                    <div class="grid gap-y-1.5">
                        <p class="text-sm font-semibold">
                            @{{ $shop.formatPrice(record.seller_total) }}
                        </p>

                        <p
                            class="text-sm"
                            v-html="record.seller_payout_status"
                        >
                        </p>
                    </div>

                    <!-- Action -->
                    <div class="flex gap-x-4 justify-between items-center">
                        <a :href="record.actions.find(action => action.method === 'GET').url">
                            <span
                                :class="record.actions.find(action => action.method === 'GET')?.icon"
                                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 max-sm:place-self-center"
                                :title="record.actions.find(action => action.method === 'GET')?.title"
                            >
                            </span>
                        </a>
                    </div>
                </div>
            </template>

            <!-- Datagrid Body Shimmer -->
            <template v-else>
                <x-shop::shimmer.datagrid.table.body :isMultiRow="true"></x-shop::shimmer.datagrid.table.body>
            </template>
        </template>
    </x-shop::datagrid>
</x-marketplace::shop.layouts>
