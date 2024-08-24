<x-marketplace::admin.layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('marketplace::app.admin.seller-flag-reasons.index.title')
    </x-slot:title>

    <v-seller-flag-reasons>
        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('marketplace::app.admin.seller-flag-reasons.index.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <!-- Create reason Button -->
                <button
                    type="button"
                    class="primary-button"
                >
                    @lang('marketplace::app.admin.seller-flag-reasons.index.create-btn')
                </button>
            </div>
        </div>

        <!-- DataGrid Shimmer -->
        <x-admin::shimmer.datagrid/>
    </v-seller-flag-reasons>

    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-seller-flag-reasons-template"
        >
            <!-- Create Button -->
            <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
                <p class="text-xl text-gray-800 dark:text-white font-bold">
                    @lang('marketplace::app.admin.seller-flag-reasons.index.title')
                </p>

                <div class="flex gap-x-2.5 items-center">
                    <!-- Create reason Button -->
                    @if (bouncer()->hasPermission('marketplace.seller-flag-reasons.create'))
                        <button 
                            type="button"
                            class="primary-button"
                            @click="selectedReason={}; $refs.sellerFlagReasons.toggle()"
                        >
                            @lang('marketplace::app.admin.seller-flag-reasons.index.create-btn')
                        </button>
                    @endif
                </div>
            </div>

            <!-- Datagrid -->
            <x-admin::datagrid
                src="{{ route('admin.marketplace.seller_flag_reasons.index') }}"
                ref="datagrid"
            >
                @php
                    $hasPermission = bouncer()->hasPermission('marketplace.seller-flag-reasons.mass-delete');
                @endphp

                <!-- Datagrid Header -->
                <template #header="{ columns, records, sortPage, selectAllRecords, applied}">
                    <div class="row grid gap-2.5 items-center px-4 py-4 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                    :style="'grid-template-columns: repeat(' + ({{$hasPermission}} ? 5 : 4) + ', 1fr);'">
                        @if ($hasPermission)
                            <label
                                class="flex gap-1 w-max items-center cursor-pointer select-none"
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

                        <div
                            class="flex gap-2.5 cursor-pointer"
                            v-for="(columnGroup, index) in ['id', 'reason', 'status']"
                        >
                            <p class="text-gray-600 dark:text-gray-300">
                                <span class="[&>*]:after:content-['_/_']">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 dark:text-white font-medium': applied.sort.column == columnGroup,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable,
                                        }"
                                        @click="
                                            columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === columnGroup)): {}
                                        "
                                    >
                                        @{{ columns.find(columnTemp => columnTemp.index === columnGroup)?.label }}
                                    </span>
                                </span>

                                <!-- Filter Arrow Icon -->
                                <i
                                    class="ltr:ml-1 rtl:mr-1 text-base text-gray-800 dark:text-white align-text-bottom"
                                    :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                    v-if="columnGroup.includes(applied.sort.column)"
                                ></i>
                            </p>
                        </div>

                        <!-- Actions -->
                        @if (
                            bouncer()->hasPermission('marketplace.seller-flag-reasons.edit')
                            || bouncer()->hasPermission('marketplace.seller-flag-reasons.delete')
                        )
                            <p class="flex gap-2.5 justify-end">
                                @lang('admin::app.components.datagrid.table.actions')
                            </p>
                        @endif
                    </div>
                </template>

                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction, applied }">
                    <div
                        v-for="record in records"
                        class="row grid gap-2.5 items-center px-4 py-4 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        :style="'grid-template-columns: repeat(' + (record.actions.length ? 5 : 4) + ', 1fr);'"
                    >
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

                        <!-- Id -->
                        <p v-text="record.id"></p>

                        <!-- Reason -->
                        <p v-text="record.reason"></p>

                        <!-- Status -->
                        <p v-html="record.status"></p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            @if (bouncer()->hasPermission('marketplace.seller-flag-reasons.edit'))
                                <a @click="editModal(record.actions.find(action => action.title === 'Edit')?.url)">
                                    <span
                                        :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        :title="record.actions.find(action => action.title === 'Edit')?.title"
                                    >
                                    </span>
                                </a>
                            @endif

                            @if (bouncer()->hasPermission('marketplace.seller-flag-reasons.delete'))
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
                </template>
            </x-admin::datagrid>

            <!-- Seller Flag Reason Create form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="flagCreateForm"
                >
                    <x-admin::modal ref="sellerFlagReasons">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p
                                class="text-lg text-gray-800 dark:text-white font-bold"
                                v-if="selectedReason.id"
                            >
                                @lang('marketplace::app.admin.seller-flag-reasons.index.edit.title')
                            </p>

                            <p 
                                class="text-lg text-gray-800 dark:text-white font-bold"
                                v-else
                            >
                                @lang('marketplace::app.admin.seller-flag-reasons.index.create.title')
                            </p>
                        </x-slot:header>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- Id -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                                v-model="selectedReason.id"
                            />

                            <!-- Reason -->
                            <x-admin::form.control-group class="mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('marketplace::app.admin.seller-flag-reasons.index.create.reason')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="reason"
                                    :value="old('reason')"
                                    rules="required"
                                    v-model="selectedReason.reason"
                                    :label="trans('marketplace::app.admin.seller-flag-reasons.index.create.reason')"
                                    :placeholder="trans('marketplace::app.admin.seller-flag-reasons.index.create.reason')"
                                />

                                <x-admin::form.control-group.error control-name="reason" />
                            </x-admin::form.control-group>

                            <!-- Status -->
                            <x-admin::form.control-group class="w-full flex-1 !mb-0">
                                <x-admin::form.control-group.label>
                                    @lang('marketplace::app.admin.seller-flag-reasons.index.create.status')
                                </x-admin::form.control-group.label>

                                <div class="gap-2.5 w-full mt-2.5">    
                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="status"
                                        value="0"
                                    />
                                    
                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        :value="1"
                                        :label="trans('marketplace::app.admin.seller-flag-reasons.index.create.status')"
                                        ::checked="selectedReason.status"
                                    />
                                    
                                    <x-admin::form.control-group.error control-name="status" />
                                </div>
                            </x-admin::form.control-group>
                        </x-slot:content>
                        
                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-2.5 items-center">
                                <button class="primary-button">
                                    @lang('marketplace::app.admin.seller-flag-reasons.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-seller-flag-reasons', {
                template: '#v-seller-flag-reasons-template',

                data() {
                    return {
                        selectedReason: {},
                    }
                },

                methods: {
                    updateOrCreate(params, { resetForm, setErrors }) {
                        let formData = new FormData(this.$refs.flagCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.marketplace.seller_flag_reasons.update')}}" : "{{ route('admin.marketplace.seller_flag_reasons.store') }}", formData)
                            .then((response) => {
                                this.$refs.sellerFlagReasons.toggle();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then((response) => {
                                if (response.data.id) {
                                    this.selectedReason = response.data;

                                    this.$refs.sellerFlagReasons.toggle();
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });
                                }
                            })
                            .catch(error => [
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message })
                            ]);
                    },
                }
            });
        </script>
    @endPushOnce
</x-marketplace::admin.layouts>
