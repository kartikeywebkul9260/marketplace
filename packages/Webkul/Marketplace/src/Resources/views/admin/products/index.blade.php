<x-marketplace::admin.layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.products.index.title')
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="py-2.5 text-xl text-gray-800 dark:text-white font-bold">
            @lang('marketplace::app.admin.products.index.title')
        </p>
    </div>

    <!-- Datagrid -->
    <x-admin::datagrid
        src="{{ route('admin.marketplace.products.index') }}"
        :isMultiRow="true"
    >
        <!-- Datagrid Header -->
        @php 
            $hasPermission = bouncer()->hasPermission('marketplace.products.mass-update') || bouncer()->hasPermission('marketplace.products.mass-delete');
        @endphp

        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading}">
            <template v-if="! isLoading">
                <div class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center px-4 py-2.5 border-b dark:border-gray-800">
                    <div
                        class="flex gap-2.5 items-center select-none"
                        v-for="(columnGroup, index) in [['product_flat_name', 'sku', 'product_type', 'product_number'], ['base_image', 'price', 'quantity', 'product_id'], ['seller_name', 'is_owner', 'is_approved']]"
                    >
                        @if ($hasPermission)
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
                                    class="icon-uncheckbox cursor-pointer rounded-md text-2xl"
                                    :class="[
                                        applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-checked peer-checked:text-blue-600' : (
                                            applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-checkbox-partial peer-checked:text-blue-600' : ''
                                        ),
                                    ]"
                                >
                                </span>
                            </label>
                        @endif

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
                    class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 px-4 py-2.5 border-b transition-all dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-950  "
                    v-for="record in records"
                >
                    <!-- Products Name, sku, Product Number -->
                    <div class="flex gap-2.5">
                        @if ($hasPermission)
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
                                class="icon-uncheckbox rounded-md text-2xl cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"
                                :for="`mass_action_select_record_${record.marketplace_product_id}`"
                            ></label>
                        @endif

                        <div class="grid gap-y-1.5">
                            <p
                                class="text-base text-gray-800 dark:text-white font-semibold"
                                v-html="record.product_flat_name"
                            >
                            </p>

                            <div class="flex gap-x-1 5">
                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                >
                                    @{{record.sku}} |
                                </p>
                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-html="record.product_type"
                                >
                                </p>
                            </div>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="record.product_number"
                            >
                            </p>
                        </div>
                    </div>

                    <!-- Image, Price, Quantity, Product ID -->
                    <div class="flex gap-1.5">
                        <div class="relative">
                            <template v-if="record.base_image">
                                <img
                                    class="min-h-full min-w-16 max-h-16 max-w-16 rounded"
                                    :src="`{{ Storage::url('') }}`+record.base_image"
                                />

                                <span
                                    class="absolute bottom-px ltr:left-px rtl:right-px text-xs font-bold text-white leading-normal bg-darkPink rounded-full px-1.5"
                                    v-text="record.images_count"
                                >
                                </span>
                            </template>

                            <template v-else>
                                <div class="w-full h-15 max-w-15 max-h-15 relative border border-dashed border-gray-300 rounded">
                                    <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">
                                </div>
                            </template>
                        </div>

                        <div class="grid gap-y-1.5">
                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="$admin.formatPrice(record.price)"
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
                                        @{{ "@lang('marketplace::app.admin.products.index.datagrid.total-quantity')".replace(':quantity', record.quantity) }}
                                    </span>
                                </p>

                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-else
                                >
                                    <span class="text-red-600">
                                        @lang('marketplace::app.admin.products.index.datagrid.out-of-stock')
                                    </span>
                                </p>
                            </div>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                            >
                                @{{ "@lang('marketplace::app.admin.products.index.datagrid.product-id')".replace(':product_id', record.product_id) }}
                            </p>
                        </div>
                    </div>

                    <!-- Seller Name, Is Owner, Is Approved, Product Type -->
                    <div class="flex gap-x-4 justify-between items-center">
                        <div class="grid gap-1.5">
                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="record.seller_name"
                            >
                            </p>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-html="record.is_owner"
                            >
                            </p>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-html="record.is_approved"
                            >
                            </p>
                        </div>

                        <div class="flex items-center">
                            @if (bouncer()->hasPermission('marketplace.products.delete'))
                                <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                    <span
                                        :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        :title="record.actions.find(action => action.method === 'DELETE')?.title"
                                    >
                                    </span>
                                </a>
                            @endif
                        </div>
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
