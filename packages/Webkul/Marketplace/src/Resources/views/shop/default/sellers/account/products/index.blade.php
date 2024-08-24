<x-marketplace::shop.layouts>
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.products.index.title')
    </x-slot:title>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::shop.breadcrumbs name="seller_products" />
    @endSection

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <!-- Page Title -->
        <h1 class="text-2xl font-medium">
            @lang('marketplace::app.shop.sellers.account.products.index.title')
        </h1>

        <!-- Create Product Button -->
        <div class="flex gap-x-2.5 items-center">            
            <a
                href="{{ route('shop.marketplace.seller.account.products.create') }}"
                class="primary-button px-5 py-2.5"
            >
                @lang('marketplace::app.shop.sellers.account.products.index.add-new-product')
            </a>
        </div>
    </div>

    <!-- Datagrid -->
    <x-shop::datagrid
        :src="route('shop.marketplace.seller.account.products.index')"
        :isMultiRow="true"
    >
        <!-- Datagrid Header -->
        <template #header="{columns, records, sortPage, selectAllRecords, applied, isLoading}">
            <template v-if="! isLoading">
                <div class="row grid grid-cols-[1.5fr_1fr_1fr] grid-rows-1 items-center px-4 py-2.5 border-b">
                    <div
                        class="flex gap-2.5 items-center select-none"
                        v-for="(columnGroup, index) in [['name', 'sku', 'product_number'], ['base_image', 'price', 'quantity', 'product_id'], ['status', 'category_name', 'product_type']]"
                    >
                        <label
                            class="flex gap-1 items-center w-max cursor-pointer select-none"
                            for="mass_action_select_all_records"
                            v-if="! index"
                        >
                            <input
                                type="checkbox"
                                name="mass_action_select_all_records"
                                id="mass_action_select_all_records"
                                class="hidden peer"
                                :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                @change="selectAllRecords"
                            >

                            <span
                                class="icon-uncheck cursor-pointer rounded-md text-2xl"
                                :class="[
                                    applied.massActions.meta.mode === 'all' ? 'mp-checked-icon text-blue-600' : (
                                        applied.massActions.meta.mode === 'partial' ? 'icon-checkbox-partial text-blue-600' : ''
                                    ),
                                ]"
                            >
                            </span>
                        </label>

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
        <template #body="{records, setCurrentSelectionMode, applied, performAction, isLoading}">
            <template v-if="! isLoading">
                <div
                    class="row grid grid-cols-[1.5fr_1fr_1fr] grid-rows-1 px-4 py-2.5 border-b transition-all"
                    v-for="record in records"
                >
                    <!-- Name, SKU, Product Number -->
                    <div class="flex gap-2.5">
                        <input
                            type="checkbox"
                            :name="`mass_action_select_record_${record.marketplace_product_id}`"
                            :id="`mass_action_select_record_${record.marketplace_product_id}`"
                            :value="record.marketplace_product_id"
                            class="hidden peer"
                            v-model="applied.massActions.indices"
                            @change="setCurrentSelectionMode"
                        >

                        <label
                            class="rounded-md text-2xl cursor-pointer peer-checked:text-blue-600"
                            :class="applied.massActions.indices.includes(record.marketplace_product_id) ? 'mp-checked-icon' : 'icon-uncheck'"
                            :for="`mass_action_select_record_${record.marketplace_product_id}`"
                        >
                        </label>

                        <div class="flex flex-col gap-1.5">
                            <p
                                class="text-sm leading-5 text-gray-800 font-semibold"
                                v-text="record.name"
                            >
                            </p>

                            <p
                                class="text-sm leading-5 text-gray-600"
                                v-text="record.sku"
                            >
                            </p>

                            <p
                                class="text-sm leading-5 text-gray-600"
                                v-text="record.product_number"
                            >
                            </p>
                        </div>
                    </div>

                    <!-- Image, Price, product Id, Stock -->
                    <div class="flex gap-3">
                        <div class="relative">
                            <template v-if="record.base_image">
                                <img
                                    class="min-h-full min-w-16 max-h-16 max-w-16 rounded"
                                    :src="`{{ Storage::url('') }}`+record.base_image"
                                />

                                <span
                                    class="absolute bottom-px ltr:left-px rtl:right-px text-xs font-bold text-white leading-normal bg-rose-500 rounded-full px-1.5"
                                    v-text="record.images_count"
                                >
                                </span>
                            </template>

                            <template v-else>
                                <div class="w-full h-15 max-w-15 max-h-15 relative border border-dashed border-gray-300 rounded">
                                    <img src="{{ bagisto_asset('images/small-product-placeholder.webp') }}">
                                </div>
                            </template>
                        </div>

                        <div class="flex flex-col">
                            <p
                                class="text-sm leading-5 text-gray-800 font-semibold"
                                v-text="record.price"
                            >
                            </p>

                            <!-- Product Quantity -->
                            <div v-if="['configurable', 'bundle', 'grouped', 'downloadable'].includes(record.product_type)">
                                <p class="text-gray-600 dark:text-gray-300">
                                    <span class="text-red-600" v-text="'N/A'"></span>
                                </p>
                            </div>

                            <div v-else>
                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-if="record.quantity > 0"
                                >
                                    <span class="text-green-600">
                                        @{{ "@lang('marketplace::app.shop.sellers.account.products.index.datagrid.total-quantity')".replace(':quantity', record.quantity) }}
                                    </span>
                                </p>

                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-else
                                >
                                    <span class="text-red-600">
                                        @lang('marketplace::app.shop.sellers.account.products.index.datagrid.out-of-stock')
                                    </span>
                                </p>
                            </div>

                            <p class="text-sm font-normal">
                                @{{ "@lang('marketplace::app.shop.sellers.account.products.index.datagrid.product_id')".replace(':product_id', record.marketplace_product_id) }}
                            </p>
                        </div>
                    </div>

                    <!-- Status, Category, Type Columns -->
                    <div class="flex gap-x-4 justify-between items-center">
                        <div class="flex flex-col gap-1.5">
                            <div class="flex gap-1">
                                <p :class="[record.status ? 'label-active': 'label-info']">
                                    @{{ record.status ? "@lang('marketplace::app.shop.sellers.account.products.index.datagrid.active')" : "@lang('marketplace::app.shop.sellers.account.products.index.datagrid.disable')" }}
                                </p>

                                <p :class="[record.is_approved ? 'label-active': 'label-info']">
                                    @{{ record.is_approved ? "@lang('marketplace::app.shop.sellers.account.products.index.datagrid.approved')" : "@lang('marketplace::app.shop.sellers.account.products.index.datagrid.disapproved')" }}
                                </p>
                            </div>
                            
                            <p
                                class="text-sm font-normal"
                                v-text="record.category_name ?? 'N/A'"
                            >
                            </p>

                            <p
                                class="text-sm font-normal"
                                v-text="record.product_type"
                            >
                            </p>
                        </div>

                        <div class="flex items-center">
                            <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                <span
                                    :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                    class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    :title="record.actions.find(action => action.method === 'DELETE')?.title"
                                >
                                </span>
                            </a>

                            <a :href="record.actions.find(action => action.method === 'GET').url">
                                <span
                                    :class="record.actions.find(action => action.method === 'GET')?.icon"
                                    class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 max-sm:place-self-center"
                                    :title="record.actions.find(action => action.method === 'GET')?.title"
                                >
                                </span>
                            </a>
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
