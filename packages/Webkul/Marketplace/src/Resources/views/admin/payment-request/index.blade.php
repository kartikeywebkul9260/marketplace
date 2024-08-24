<x-marketplace::admin.layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.payment-request.index.title')
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="py-2.5 text-xl text-gray-800 dark:text-white font-bold">
            @lang('marketplace::app.admin.payment-request.index.title')
        </p>
    </div>

    <payment-request></payment-request>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="payment-request-template"
        >
            <!-- Datagrid -->
            <x-admin::datagrid
                src="{{ route('admin.marketplace.payment_request.index') }}"
                ref="datagrid"
            >
                <!-- Datagrid Header -->
                <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading}">
                    <template v-if="! isLoading">
                        <div class="row grid grid-cols-[0.4fr_0.5fr_0.7fr_0.4fr_0.1fr] grid-rows-1 items-center px-4 py-2.5 border-b dark:border-gray-800">
                            <div
                                class="flex gap-2.5 items-center select-none"
                                v-for="(columnGroup, index) in [['increment_id', 'created_at', 'status'], ['customer_name', 'customer_email', 'base_grand_total'], ['base_remaining_total', 'pay', 'base_seller_total_invoiced'], ['seller_name', 'shop_title', 'total_paid']]"
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

                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction, applied }">
                    <div
                        v-for="record in records"
                        class="row grid grid-cols-[0.4fr_0.5fr_0.7fr_0.4fr_0.1fr] grid-rows-1 items-center px-4 py-2.5 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    >
                        <!-- Order Id, Created, Status Section -->
                        <div class="grid gap-y-1.5">
                            <p
                                class="text-gray-600 dark:text-gray-300 font-semibold"
                            >
                                @{{ "@lang('marketplace::app.admin.payment-request.index.datagrid.order-id')".replace(':id', record.increment_id) }}
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
                                v-text="$admin.formatPrice(record.base_grand_total)"
                            >
                            </p>
                        </div>

                        <!-- Remaining Total, Pay, Seller Total Invoiced -->
                        <div class="flex gap-5">
                            <div class="grid gap-y-1.5">
                                <p v-text="record.base_remaining_total"></p>

                                <p v-text="$admin.formatPrice(record.base_seller_total_invoiced)"></p>
                            </div>

                            <template v-if="record.seller_payout_status == 'requested'">
                                <template v-if="record.base_seller_total_invoiced - (record.total_paid ? record.total_paid : 0 ) > 0">
                                    <div class="flex">
                                        <button
                                            class="secondary-button !py-1 px-2 text-sm"
                                            @click="openModal(record.id, record.marketplace_seller_id)"
                                        >
                                            @lang('marketplace::app.admin.payment-request.index.pay-btn')
                                        </button>
                                    </div>
                                </template>
                                <template v-else>
                                    <p v-html="record.pay"></p>
                                </template>
                            </template>

                            <template v-else>
                                <p v-html="record.pay"></p>
                            </template>
                        </div>

                        <!-- Seller Name, Shop Title, Seller Total Paid -->
                        <div class="grid gap-y-1.5">
                            <p v-text="record.seller_name"></p>

                            <p v-text="record.shop_title"></p>

                            <p v-text="$admin.formatPrice(record.total_paid)"></p>
                        </div>

                        <!-- Actions -->
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
            </x-admin::datagrid>

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    @submit="handleSubmit($event, create)"
                    ref="sellerPayForm"
                >
                    <x-admin::modal ref="sellerPayModal">
                        <x-slot:header>
                            <p class="text-lg text-gray-800 dark:text-white font-bold">
                                @lang('marketplace::app.admin.payment-request.index.pay-to-seller')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <x-admin::form.control-group class="py-3">
                                <x-admin::form.control-group.label class="required">
                                    @lang('marketplace::app.admin.payment-request.index.comment')
                                </x-admin::form.control-group.label>
    
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="comment"
                                    :value="old('comment')"
                                    rules="required|max:200"
                                    :label="trans('marketplace::app.admin.payment-request.index.comment')"
                                    :placeholder="trans('marketplace::app.admin.payment-request.index.comment')"
                                />
    
                                <x-admin::form.control-group.error control-name="comment" />
                            </x-admin::form.control-group>
                        </x-slot:content>
                        
                        <x-slot:footer>
                            <!-- Save Button -->
                            <button class="primary-button">
                                @lang('marketplace::app.admin.payment-request.index.pay-btn')
                            </button>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('payment-request', {
                template: "#payment-request-template",

                data() {
                    return {
                        order_id: null,
                        seller_id: null
                    }
                },

                methods: {
                    openModal(order_id, seller_id) {
                        this.order_id = order_id;
                        this.seller_id = seller_id;

                        this.$refs.sellerPayModal.open();
                    },

                    create(params, { resetForm, setErrors }) {
                        let formData = new FormData(this.$refs.sellerPayForm);

                        formData.append('seller_id', this.seller_id);
                        formData.append('order_id', this.order_id);

                        this.$axios.post("{{route('admin.marketplace.payment_request.pay')}}", formData)
                            .then((response) => {
                                this.$refs.sellerPayModal.close();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: "@lang('marketplace::app.admin.payment-request.index.something-went-wrong')" });
                            });
                    }
                }
            });
        </script>
    @endPushOnce
</x-marketplace::admin.layouts>
