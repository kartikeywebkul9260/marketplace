<!-- Seperator -->
<span class="block w-full absolute ltr:left-0 rtl:right-0 my-1 border border-[#E9E9E9]"></span>

<v-product-customer-group-price>

</v-product-customer-group-price>

@inject('customerGroupRepository', 'Webkul\Customer\Repositories\CustomerGroupRepository')

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-customer-group-price-template">
        <div>
            <!-- Header -->
            <div class="flex items-center justify-between mt-1.5 py-4">
                <p class="text-gray-800 text-base py-2.5 font-semibold">
                    @lang('marketplace::app.shop.sellers.account.products.edit.price.group.title')
                </p>

                <p
                    class="text-blue-600 cursor-pointer transition-all hover:underline"
                    @click="resetForm(); $refs.groupPriceCreateModal.open()"
                >
                    @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create-btn')
                </p>
            </div>

            <!-- Content -->
            <div class="grid">
                <!-- Card -->
                <div
                    class="flex flex-col gap-2 py-2.5"
                    v-for="(item, index) in prices"
                >
                    <!-- Hidden Inputs -->
                    <input type="hidden" :name="'customer_group_prices[' + item.id + '][customer_group_id]'" :value="item.customer_group_id"/>

                    <input type="hidden" :name="'customer_group_prices[' + item.id + '][qty]'" :value="item.qty"/>

                    <input type="hidden" :name="'customer_group_prices[' + item.id + '][value_type]'" :value="item.value_type"/>

                    <input type="hidden" :name="'customer_group_prices[' + item.id + '][value]'" :value="item.value"/>

                    <div class="flex justify-between">
                        <p class="text-gray-600">
                            @{{ getGroupNameById(item.customer_group_id) }}
                        </p>

                        <p
                            class="text-blue-600 cursor-pointer transition-all hover:underline"
                            @click="selectedPrice = item; $refs.groupPriceCreateModal.open()"
                        >
                            @lang('marketplace::app.shop.sellers.account.products.edit.price.group.edit-btn')
                        </p>
                    </div>

                    <p
                        class="text-gray-600"
                        v-if="item.value_type == 'fixed'"
                    >
                        @{{ "@lang('marketplace::app.shop.sellers.account.products.edit.price.group.fixed-group-price-info')".replace(':qty', item.qty).replace(':price', item.value) }}
                    </p>

                    <p
                        class="text-gray-600"
                        v-else
                    >
                        @{{ "@lang('marketplace::app.shop.sellers.account.products.edit.price.group.discount-group-price-info')".replace(':qty', item.qty).replace(':price', item.value) }}
                    </p>
                </div>

                <!-- Empty Container -->
                <div
                    class="flex gap-5 items-center py-2.5"
                    v-if="! prices.length"
                >
                    <img
                        src="{{ bagisto_asset('images/icon-discount.svg', 'marketplace') }}"
                        class="w-20 h-20 border border-dashed border-gray-300 rounded"
                    />

                    <div class="flex flex-col gap-1.5">
                        <p class="text-base text-gray-400 font-semibold">
                            @lang('marketplace::app.shop.sellers.account.products.edit.price.group.add-group-price')
                        </p>

                        <p class="text-gray-400">
                            @lang('marketplace::app.shop.sellers.account.products.edit.price.group.empty-info')
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Modal -->
            <x-marketplace::shop.form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">
                    <!-- Customer Create Modal -->
                    <x-marketplace::shop.modal ref="groupPriceCreateModal">
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p
                                class="text-lg text-gray-800 font-bold"
                                v-if="! selectedPrice.id"
                            >
                                @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.create-title')
                            </p>

                            <p
                                class="text-lg text-gray-800 font-bold"
                                v-else
                            >
                                @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.update-title')
                            </p>    
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            <x-marketplace::shop.form.control-group>
                                <x-marketplace::shop.form.control-group.label class="flex">
                                    @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.customer-group')
                                </x-marketplace::shop.form.control-group.label>
    
                                <x-marketplace::shop.form.control-group.control
                                    type="select"
                                    name="customer_group_id"
                                    v-model="selectedPrice.customer_group_id"
                                    :label="trans('marketplace::app.shop.sellers.account.products.edit.price.group.create.customer-group')"
                                >
                                    <option value="">
                                        @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.all-groups')
                                    </option>

                                    <option
                                        v-for="group in groups"
                                        :value="group.id"
                                    >
                                        @{{ group.name }}
                                    </option>
                                </x-marketplace::shop.form.control-group.control>
                            </x-marketplace::shop.form.control-group>

                            <div class="flex gap-4">
                                <x-marketplace::shop.form.control-group class="flex-1">
                                    <x-marketplace::shop.form.control-group.label class="flex required">
                                        @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.qty')
                                    </x-marketplace::shop.form.control-group.label>
        
                                    <x-marketplace::shop.form.control-group.control
                                        type="text"
                                        name="qty"
                                        v-model="selectedPrice.qty"
                                        rules="required|numeric|min_value:1"
                                        :label="trans('marketplace::app.shop.sellers.account.products.edit.price.group.create.qty')"
                                    />
        
                                    <x-marketplace::shop.form.control-group.error
                                        class="flex"
                                        control-name="qty"
                                    />
                                </x-marketplace::shop.form.control-group>

                                <x-marketplace::shop.form.control-group class="flex-1">
                                    <x-marketplace::shop.form.control-group.label class="flex required">
                                        @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.price-type')
                                    </x-marketplace::shop.form.control-group.label>
        
                                    <x-marketplace::shop.form.control-group.control
                                        type="select"
                                        name="value_type"
                                        v-model="selectedPrice.value_type"
                                        rules="required"
                                        :label="trans('marketplace::app.shop.sellers.account.products.edit.price.group.create.price-type')"
                                    >
                                        <option value="fixed">
                                            @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.fixed')
                                        </option>

                                        <option value="discount">
                                            @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.discount')
                                        </option>
                                    </x-marketplace::shop.form.control-group.control>
        
                                    <x-marketplace::shop.form.control-group.error
                                        class="flex"
                                        control-name="value_type"
                                    />
                                </x-marketplace::shop.form.control-group>

                                <x-marketplace::shop.form.control-group class="flex-1">
                                    <x-marketplace::shop.form.control-group.label class="flex required">
                                        @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.price')
                                    </x-marketplace::shop.form.control-group.label>
        
                                    <x-marketplace::shop.form.control-group.control
                                        type="text"
                                        name="value"
                                        v-model="selectedPrice.value"
                                        ::rules="{required: true, min_value: 0, ...(selectedPrice.value_type === 'discount' ? {max_value: 100} : {})}"
                                        :label="trans('marketplace::app.shop.sellers.account.products.edit.price.group.create.price')"
                                    />
        
                                    <x-marketplace::shop.form.control-group.error
                                        class="flex"
                                        control-name="value"
                                    />
                                </x-marketplace::shop.form.control-group>
                            </div>
                        </x-slot:content>

                        <x-slot:footer>
                            <div class="flex justify-end items-center py-4">
                                <button
                                    type="button"
                                    class="text-red-600 font-semibold whitespace-nowrap px-3 py-1.5 border-2 border-transparent rounded-md transition-all hover:bg-gray-100 cursor-pointer"
                                    @click="remove"
                                    v-if="selectedPrice.id"
                                >
                                    @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.delete-btn')
                                </button>

                                <button 
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('marketplace::app.shop.sellers.account.products.edit.price.group.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-marketplace::shop.modal>
                </form>
            </x-marketplace::shop.form>
        </div>
    </script>

    <script type="module">
        app.component('v-product-customer-group-price', {
            template: '#v-product-customer-group-price-template',

            data: function() {
                return {
                    groups: @json($customerGroupRepository->all()),

                    prices: @json($product->customer_group_prices),

                    selectedPrice: {
                        customer_group_id: null,
                        qty: 0,
                        value_type: 'fixed',
                        value: 0,
                    }
                }
            },

            methods: {
                getGroupNameById(id) {
                    let group = this.groups.find(group => group.id == id);

                    return group ? group.name : "@lang('marketplace::app.shop.sellers.account.products.edit.price.group.all-groups')";
                },

                create(params) {
                    if (this.selectedPrice.id == undefined) {
                        params.id = 'price_' + this.prices.length;

                        this.prices.push(params);
                    } else {
                        const indexToUpdate = this.prices.findIndex(price => price.id === this.selectedPrice.id);

                        this.prices[indexToUpdate] = this.selectedPrice;
                    }

                    this.resetForm();

                    this.$refs.groupPriceCreateModal.close();
                },

                resetForm() {
                    this.selectedPrice = {
                        customer_group_id: null,
                        qty: 0,
                        value_type: 'fixed',
                        value: 0,
                    };
                },

                remove() {
                    this.$refs.groupPriceCreateModal.close();

                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            let index = this.prices.indexOf(this.selectedPrice);

                            this.prices.splice(index, 1);

                            this.resetForm();
                        }
                    });
                }
            }
        });
    </script>
@endPushOnce
