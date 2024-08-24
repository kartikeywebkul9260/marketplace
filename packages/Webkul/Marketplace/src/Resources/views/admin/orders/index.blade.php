<x-marketplace::admin.layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.orders.index.title')
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="py-2.5 text-xl text-gray-800 dark:text-white font-bold">
            @lang('marketplace::app.admin.orders.index.title')
        </p>
    </div>

    <!-- Datagrid -->
    <x-admin::datagrid
        src="{{ route('admin.marketplace.orders.index') }}"
        :isMultiRow="true"
    >
        <!-- Datagrid Header -->
        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading}">
            <template v-if="! isLoading">
                <div class="row grid grid-cols-[0.4fr_0.5fr_0.5fr_0.6fr_0.3fr_0.1fr] grid-rows-1 items-center px-4 py-2.5 border-b dark:border-gray-800">
                    <div
                        class="flex gap-2.5 items-center select-none"
                        v-for="(columnGroup, index) in [['increment_id', 'created_at', 'status'], ['customer_name', 'customer_email', 'location'], ['total_item_count', 'payment_method', 'shipping_method'], ['base_sub_total', 'discount_amount', 'commission'], ['shop_title', 'seller_name', 'seller_total']]"
                    >
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="[&>*]:after:content-['_/_']">
                                <template v-for="column in columnGroup">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 dark:text-white font-medium': applied.sort.column == column,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': columns.find(columnTemp => columnTemp.index === column)?.sortable,
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
                                class="ltr:ml-1 rtl:mr-1 text-base text-gray-800 dark:text-white align-text-bottom"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="columnGroup.includes(applied.sort.column)"
                            ></i>
                        </p>
                    </div>
                </div>
            </template>

            <!-- Datagrid Head Shimmer -->
            <template v-else>
                <x-admin::shimmer.datagrid.table.head :isMultiRow="true"></x-admin::shimmer.datagrid.table.head>
            </template>
        </template>

        <!-- Datagrid Body -->
        <template #body="{ records, setCurrentSelectionMode, applied, performAction, isLoading }">
            <template v-if="! isLoading">
                <div
                    class="row grid grid-cols-[0.4fr_0.5fr_0.5fr_0.6fr_0.3fr_0.1fr] grid-rows-1 items-center px-4 py-2.5 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    v-for="record in records"
                >
                    <!-- Order Id, Created, Status Section -->
                    <div class="grid gap-y-1.5">
                        <p
                            class="text-gray-600 dark:text-gray-300 font-semibold"
                        >
                            @{{ "@lang('marketplace::app.admin.orders.index.datagrid.order-id')".replace(':id', record.increment_id) }}
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.created_at"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-html="record.status"
                        >
                        </p>
                    </div>

                    <!-- Customer Name, Email, Location Section -->
                    <div class="grid gap-y-1.5">
                        <p
                            class="text-gray-600 dark:text-gray-300 font-semibold"
                            v-text="record.customer_name"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.customer_email"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.location"
                        >
                        </p>
                    </div>

                    <!-- Items, Payment, Shipping -->
                    <div class="grid gap-y-1.5">
                        <p
                            class="text-gray-600 dark:text-gray-300 font-semibold"
                            v-text="record.total_item_count"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.payment_method"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.shipping_method"
                        >
                        </p>
                    </div>

                    <!-- Base Total Amount, Discount, Commission -->
                    <div class="grid gap-y-1.5">
                        <p
                            class="text-gray-600 dark:text-gray-300 font-semibold"
                            v-text="$admin.formatPrice(record.base_sub_total)"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="$admin.formatPrice(record.discount_amount)"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="$admin.formatPrice(record.commission)"
                        >
                        </p>
                    </div>

                    <!-- Shop Title, Seller Name, Seller Total -->
                    <div class="grid gap-y-1.5">
                        <p
                            class="text-gray-600 dark:text-gray-300 font-semibold"
                            v-text="record.shop_title"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.seller_name"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="$admin.formatPrice(record.seller_total)"
                        >
                        </p>
                    </div>
                        
                    <!-- Action -->
                    <div class="flex gap-x-4 justify-between items-center">
                        <a :href="record.actions.find(action => action.method === 'GET').url">
                            <span
                                :class="record.actions.find(action => action.method === 'GET')?.icon"
                                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                :title="record.actions.find(action => action.method === 'GET')?.title"
                            >
                            </span>
                        </a>
                    </div>
                </div>
            </template>

            <!-- Datagrid Body Shimmer -->
            <template v-else>
                <x-admin::shimmer.datagrid.table.body :isMultiRow="true"></x-admin::shimmer.datagrid.table.body>
            </template>
        </template>
    </x-admin::datagrid>
</x-marketplace::admin.layouts>
