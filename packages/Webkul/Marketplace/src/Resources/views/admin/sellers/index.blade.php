<x-marketplace::admin.layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('marketplace::app.admin.sellers.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('marketplace::app.admin.sellers.index.title')
        </p>

        <div class="flex gap-x-2.5 items-center">
            <div class="flex gap-x-2.5 items-center">
                <!-- Seller Create Vue Component -->
                <v-create-sellers-form>
                    <button class="primary-button">
                        @lang('marketplace::app.admin.sellers.index.add-btn')
                    </button>
                </v-create-sellers-form>
            </div>
        </div>
    </div>

    <!-- Datagrid -->
    <x-admin::datagrid
        src="{{ route('admin.marketplace.sellers.index') }}"
        :isMultiRow="true"
        ref="seller_data"
    >
        <!-- Datagrid Header -->
        @php 
            $hasPermission = bouncer()->hasPermission('marketplace.sellers.mass-update') || bouncer()->hasPermission('marketplace.sellers.mass-delete');
        @endphp

        <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading}">
            <template v-if="! isLoading">
                <div class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 items-center px-4 py-2.5 border-b dark:border-gray-800">
                    <div
                        class="flex gap-2.5 items-center select-none"
                        v-for="(columnGroup, index) in [['name', 'email', 'url'], ['created_at', 'is_approved', 'id'], ['assign_product', 'flags']]"
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
        <template #body="{ records, setCurrentSelectionMode, performAction, applied, isLoading }">
            <template v-if="! isLoading">
                <div
                    class="row grid grid-cols-[2fr_1fr_1fr] grid-rows-1 px-4 py-2.5 border-b dark:border-gray-800 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    v-for="record in records"
                >
                    <!-- Customer Name, email, Shop URL -->
                    <div class="flex gap-2.5">
                        @if ($hasPermission)
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

                        <div class="flex flex-col gap-1.5">
                            <p
                                class="text-base text-gray-800 dark:text-white font-semibold"
                                v-html="record.name"
                            >
                            </p>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="record.email"
                            >
                            </p>

                            <a
                                class="text-base text-blue-800"
                                target="_blank"
                                :href="`{{ route('marketplace.seller.show', '') }}/${record.url}`"
                                v-text="record.url"
                            >
                            </a>
                        </div>
                    </div>

                    <!-- Created At, Is Approved, Id-->
                    <div class="flex gap-1.5">
                        <div class="flex flex-col gap-1.5">
                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-text="record.created_at"
                            >
                            </p>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                                v-html="record.is_approved"
                            >
                            </p>

                            <p
                                class="text-gray-600 dark:text-gray-300"
                            >
                                @{{ "@lang('marketplace::app.admin.sellers.index.datagrid.seller-id')".replace(':seller_id', record.id) }}
                            </p>
                        </div>
                    </div>

                    <!-- Assign Product -->
                    <div class="flex gap-x-4 justify-between items-center">
                        <div class="flex flex-col gap-1.5">
                            <p
                                class="flex"
                                v-html="record.assign_product"
                            >
                            </p>

                            <p class="text-gray-600 dark:text-gray-300">
                                @{{ "@lang('marketplace::app.admin.sellers.index.datagrid.total-flags')".replace(':count', record.flags)}}                             
                            </p>
                        </div>

                        <div class="flex items-center">
                            @if (bouncer()->hasPermission('marketplace.sellers.edit'))
                                <a @click="performAction(record.actions.find(action => action.method === 'GET'))">
                                    <span
                                        :class="record.actions.find(action => action.method === 'GET')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        :title="record.actions.find(action => action.method === 'GET')?.title"
                                    >
                                    </span>
                                </a>
                            @endif

                            @if (bouncer()->hasPermission('marketplace.sellers.delete'))
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

    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-create-sellers-form-template"
        >
            <!-- Create Button -->
            @if (bouncer()->hasPermission('marketplace.sellers.create'))
                <button
                    class="primary-button"
                    @click="$refs.sellerCreateModal.toggle()"
                >
                    @lang('marketplace::app.admin.sellers.index.add-btn')
                </button>
            @endif

            <!-- Seller Create form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">
                    <x-admin::modal ref="sellerCreateModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg text-gray-800 dark:text-white font-bold">
                                @lang('marketplace::app.admin.sellers.index.create.title')
                            </p>
                        </x-slot:header>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- Name -->
                            <x-admin::form.control-group class="w-full mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('marketplace::app.admin.sellers.index.create.name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    id="name"
                                    rules="required"
                                    :label="trans('marketplace::app.admin.sellers.index.create.name')"
                                    :placeholder="trans('marketplace::app.admin.sellers.index.create.name')"
                                />

                                <x-admin::form.control-group.error control-name="name" />
                            </x-admin::form.control-group>

                            <!-- Email -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('marketplace::app.admin.sellers.index.create.email')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="email"
                                    name="email"
                                    id="email"
                                    rules="required|email"
                                    :label="trans('marketplace::app.admin.sellers.index.create.email')"
                                    placeholder="email@example.com"
                                />

                                <x-admin::form.control-group.error control-name="email" />
                            </x-admin::form.control-group>

                            <!-- Shop Url -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('marketplace::app.admin.sellers.index.create.shop-url')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="url"
                                    id="url"
                                    rules="required"
                                    :label="trans('marketplace::app.admin.sellers.index.create.shop-url')"
                                    placeholder="jhon-shop"
                                />

                                <x-admin::form.control-group.error control-name="url" />
                            </x-admin::form.control-group>
                        </x-slot:content>
                        
                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-2.5 items-center">
                                <button class="primary-button">
                                    <div
                                        v-if="isLoading"
                                        class="inline-block h-4 w-4 animate-spin rounded-full border-4 border-solid border-r-transparent align-center motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                    >
                                    </div>
                                    @lang('marketplace::app.admin.sellers.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-create-sellers-form', {
                template: '#v-create-sellers-form-template',

                data() {
                    return {
                        isLoading: false,
                    }
                },

                methods: {
                    create(params, { resetForm, setErrors }) {
                        this.isLoading = true;

                        this.$axios.post("{{ route('admin.marketplace.sellers.store')}}", params)
                            .then((response) => {
                                this.isLoading = false;
                                
                                this.$refs.sellerCreateModal.close();

                                this.$root.$refs.seller_data.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                                
                                this.isLoading = false;
                            });
                    }
                }
            });
        </script>
    @endPushOnce
</x-marketplace::admin.layouts>
