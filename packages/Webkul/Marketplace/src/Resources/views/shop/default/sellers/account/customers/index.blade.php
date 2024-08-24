<x-marketplace::shop.layouts>
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.customers.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::shop.breadcrumbs name="seller_customers" />
    @endSection

    <div class="flex justify-between items-center">
        <div class="">
            <h2 class="text-2xl font-medium">
                @lang('marketplace::app.shop.sellers.account.customers.index.title')
            </h2>
        </div>
    </div>

    <!-- Datagrid -->
    <x-shop::datagrid
        :src="route('shop.marketplace.seller.account.customers.index')"
        :isMultiRow="true"
    >
        <!-- Datagrid Header -->
        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading}">
            <template v-if="! isLoading">
                <div class="row grid grid-cols-[3fr_2fr_1fr] grid-rows-1 items-center px-4 py-2.5 border-b">
                    <div
                        class="flex gap-2.5 items-center select-none"
                        v-for="(columnGroup, index) in [['full_name', 'email','gender'], ['revenue', 'order_count'], ['status', 'group']]"
                    >
                        <p class="text-sm leading-5 font-medium">
                            <span class="[&>*]:after:content-['_/_']">
                                <template v-for="column in columnGroup">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 font-medium': applied.sort.column == column,
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

        <!-- Datagrid Body -->
        <template #body="{ records, setCurrentSelectionMode, applied, performAction, isLoading }">
            <template v-if="! isLoading">
                <div
                    class="row grid grid-cols-[3fr_2fr_1fr] grid-rows-1 items-center px-4 py-2.5 border-b"
                    v-for="record in records"
                >
                    <div class="flex gap-2.5">
                        <div class="flex flex-col gap-1.5">
                            <p
                                class="text-sm leading-5 text-gray-800 font-semibold"
                                v-text="record.full_name"
                            >
                            </p>

                            <p
                                class="text-sm leading-5 text-gray-600"
                                v-text="record.email"
                            >
                            </p>

                            <p
                                class="text-sm leading-5 text-gray-600"
                                v-text="record.gender"
                            >
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-x-4 justify-between items-center">
                        <div class="flex flex-col gap-1.5">
                            <p
                                class="text-sm leading-5 text-gray-800 font-semibold"
                                v-text="$shop.formatPrice(record.revenue)"
                            >
                            </p>

                            <p
                                class="text-sm leading-5 text-gray-600"
                                v-text="record.order_count"
                            >
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-x-4 justify-between items-center">
                        <div class="flex flex-col gap-1.5">
                            <p v-html="record.status"></p>

                            <p
                                class="text-sm leading-5 text-gray-600"
                                v-text="record.group"
                            >
                            </p>
                        </div>
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
