<x-marketplace::admin.layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.seller-reviews.index.title')
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="py-2.5 text-xl text-gray-800 dark:text-white font-bold">
            @lang('marketplace::app.admin.seller-reviews.index.title')
        </p>
    </div>

    <x-admin::datagrid
        src="{{ route('admin.marketplace.seller_reviews.index') }}"
        :isMultiRow="true"
    >
        <!-- Datagrid Header -->
        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading }">
            <template v-if="! isLoading">
                <div class="row grid grid-rows-1 grid-cols-[1.5fr_1fr_2fr_0.2fr] items-center px-4 py-2.5 border-b dark:border-gray-800">
                    <div
                        class="flex gap-2.5 items-center"
                        v-for="(columnGroup, index) in [['customer_name', 'status', 'id'], ['seller_name', 'rating', 'created_at'], ['shop_title', 'title', 'comment']]"
                    >
                        @if (bouncer()->hasPermission('marketplace.seller-reviews.mass-update'))
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
        <template #body="{ columns, records, setCurrentSelectionMode, applied, isLoading, performAction }">
            <template v-if="! isLoading">
                <div
                    class="row grid grid-cols-[1.5fr_1fr_2fr_0.2fr] px-4 py-2.5 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    v-for="record in records"
                >
                    <div class="flex gap-2.5">
                        @if (bouncer()->hasPermission('marketplace.seller-reviews.mass-update'))
                            <input
                                type="checkbox"
                                :name="`mass_action_select_record_${record.id}`"
                                :id="`mass_action_select_record_${record.id}`"
                                :value="record.id"
                                class="hidden peer"
                                v-model="applied.massActions.indices"
                                @change="setCurrentSelectionMode"
                            >

                            <label
                                class="icon-uncheckbox rounded-md text-2xl cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"
                                :for="`mass_action_select_record_${record.id}`"
                            ></label>
                        @endif

                        <!-- Customer Name, Status, Id -->
                        <div class="grid content-baseline gap-y-1.5">
                            <p
                                class="text-base text-gray-800 dark:text-white font-semibold"
                                v-text="record.customer_name"
                            >
                            </p>

                            <p v-html="record.status"></p>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                            >
                                @{{ "@lang('marketplace::app.admin.seller-reviews.index.datagrid.review-id')".replace(':review_id', record.id) }}
                            </p>
                        </div>
                    </div>

                    <!-- Seller Name, Rating, Date -->
                    <div class="grid content-baseline gap-y-1.5">
                        <p
                            class="text-base text-gray-800 dark:text-white font-semibold"
                            v-text="record.seller_name"
                        >
                        </p>

                        <div class="flex">
                            <x-admin::star-rating 
                                :is-editable="false"
                                ::value="record.rating"
                            >
                            </x-admin::star-rating>
                        </div>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.created_at"
                        >
                        </p>
                    </div>

                    <!-- Shop Title, Review Title, Comment -->
                    <div class="grid content-baseline gap-y-1.5">
                        <p
                            class="text-base text-gray-800 dark:text-white font-semibold"
                            v-text="record.shop_title"
                        >
                        </p>

                        <p
                            class="text-base text-gray-600 dark:text-white font-semibold"
                            v-text="record.title"
                        >
                        </p>

                        <p
                            class="text-gray-600 dark:text-gray-300"
                            v-text="record.comment"
                        >
                        </p>
                    </div>

                    <!-- View Button -->
                    <a
                        v-if="record.actions.find(action => action.title === 'View')"
                        @click="edit(record.actions.find(action => action.title === 'View')?.url)"
                    >
                        <span class="icon-view text-xl ltr:ml-1 rtl:mr-1 p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800">
                        </span>
                    </a>
                </div>
            </template>

            <!-- Datagrid Body Shimmer -->
            <template v-else>
                <x-admin::shimmer.datagrid.table.body :isMultiRow="true"></x-admin::shimmer.datagrid.table.body>
            </template>
        </template>
    </x-admin::datagrid>

</x-marketplace::admin.layouts>
