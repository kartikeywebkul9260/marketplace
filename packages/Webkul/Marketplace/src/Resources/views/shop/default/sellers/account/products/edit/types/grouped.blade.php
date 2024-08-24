<v-group-products :errors="errors"></v-group-products>

@pushOnce('scripts')
    <script type="text/x-template" id="v-group-products-template">
        <div class="relative p-5 bg-white border rounded-xl box-shadow">
            <!-- Panel Header -->
            <div class="grid grid-cols-3 gap-5 justify-items-end mb-2.5">
                <div class="grid col-span-2 gap-2">
                    <p class="text-base text-gray-800 font-semibold">
                        @lang('marketplace::app.shop.sellers.account.products.edit.types.grouped.title')
                    </p>

                    <p class="text-xs text-gray-500 font-medium">
                        @lang('marketplace::app.shop.sellers.account.products.edit.types.grouped.info')
                    </p>
                </div>
                
                <!-- Add Button -->
                <div
                    class="h-14 secondary-button"
                    @click="$refs.productSearch.openDrawer()"
                >
                    @lang('marketplace::app.shop.sellers.account.products.edit.types.grouped.add-btn')
                </div>
            </div>

            <!-- Panel Content -->
            <div
                class="grid"
                v-if="groupProducts.length"
            >
                <!-- Draggable Products -->
                <draggable
                    ghost-class="draggable-ghost"
                    v-bind="{animation: 200}"
                    :list="groupProducts"
                    item-key="id"
                >
                    <template #item="{ element, index }">
                        <div class="flex gap-2.5 justify-between py-4 border-b border-slate-300 cursor-pointer">
                            <!-- Information -->
                            <div class="flex gap-2.5">
                                <!-- Drag Icon -->
                                <i class="mp-drag-icon text-xl text-gray-600 transition-all pointer-events-none"></i>
                                
                                <!-- Image -->
                                <div
                                    class="w-full h-15 max-w-15 max-h-15 relative rounded overflow-hidden"
                                    :class="{'border border-dashed border-gray-300 rounded': ! element.associated_product.images.length}"
                                >
                                    <template v-if="! element.associated_product.images.length">
                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg', 'marketplace') }}">
                                    
                                        <p class="w-full absolute bottom-1 text-[6px] text-gray-400 text-center font-semibold">
                                            @lang('marketplace::app.shop.sellers.account.products.edit.types.grouped.image-placeholder')
                                        </p>
                                    </template>

                                    <template v-else>
                                        <img :src="element.associated_product.images[0].url">
                                    </template>
                                </div>

                                <!-- Details -->
                                <div class="grid gap-1.5 content-start">
                                    <p class="text-[16x] text-gray-800 font-semibold">
                                        @{{ element.associated_product.name }}
                                    </p>

                                    <p class="text-gray-600">
                                        @{{ "@lang('marketplace::app.shop.sellers.account.products.edit.types.grouped.sku')".replace(':sku', element.associated_product.sku) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid gap-1 text-right">
                                <p class="text-gray-800 font-semibold">
                                    @{{ $shop.formatPrice(element.associated_product.price) }}    
                                </p>
                                
                                <!-- Hidden Input -->
                                <input
                                    type="hidden"
                                    :name="'links[' + (element.id ? element.id : 'link_' + index) + '][associated_product_id]'"
                                    :value="element.associated_product.id"
                                />
                                
                                <input
                                    type="hidden"
                                    :name="'links[' + (element.id ? element.id : 'link_' + index) + '][sort_order]'"
                                    :value="index"
                                />

                                <x-shop::form.control-group class="grid justify-items-end !mb-0">
                                    <x-shop::form.control-group.label class="required">
                                        @lang('marketplace::app.shop.sellers.account.products.edit.types.grouped.default-qty')
                                    </x-shop::form.control-group.label>

                                    <v-field
                                        type="text"
                                        :name="'links[' + (element.id ? element.id : 'link_' + index) + '][qty]'"
                                        v-model="element.qty"
                                        class="flex w-[86px] min-h-10 py-2 px-3 border rounded-md text-sm text-gray-600 transition-all hover:border-gray-400"
                                        :class="[errors['links[' + (element.id ? element.id : 'link_' + index) + '][qty]'] ? 'border border-red-600 hover:border-red-600' : '']"
                                        rules="required|numeric|min_value:1"
                                    ></v-field>
                                </x-shop::form.control-group>

                                <p
                                    class="text-red-600 cursor-pointer transition-all hover:underline"
                                    @click="remove(element)"
                                >
                                    @lang('marketplace::app.shop.sellers.account.products.edit.types.grouped.delete')
                                </p>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!-- For Empty Variations -->
            <div
                class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5"
                v-else
            >
                <!-- Placeholder Image -->
                <img
                    src="{{ bagisto_asset('images/icon-add-product.svg', 'marketplace') }}"
                    class="w-20 h-20"
                />

                <!-- Add Variants Information -->
                <div class="flex flex-col items-center">
                    <p class="text-base text-gray-400 font-semibold">
                        @lang('marketplace::app.shop.sellers.account.products.edit.types.grouped.empty-title')
                    </p>

                    <p class="text-gray-400">
                        @lang('marketplace::app.shop.sellers.account.products.edit.types.grouped.empty-info')
                    </p>
                </div>
                
                <!-- Add Row Button -->
                <div
                    class="secondary-button text-sm"
                    @click="$refs.productSearch.openDrawer()"
                >
                    @lang('marketplace::app.shop.sellers.account.products.edit.types.grouped.add-btn')
                </div>
            </div>

            <!-- Product Search Blade Component -->
            <x-marketplace::shop.products.search
                ref="productSearch"
                ::added-product-ids="addedProductIds"
                ::query-params="{type: 'simple'}"
                @onProductAdded="addSelected($event)"
            >
            </x-marketplace::shop.products.search>
        </div>
    </script>

    <script type="module">
        app.component('v-group-products', {
            template: '#v-group-products-template',

            props: ['errors'],

            data() {
                return {
                    groupProducts: @json($product->grouped_products()->with(['associated_product.inventory_indices', 'associated_product.images'])->orderBy('sort_order', 'asc')->get())
                }
            },

            computed: {
                addedProductIds() {
                    return this.groupProducts.map(product => product.associated_product.id);
                }
            },

            methods: {
                addSelected(selectedProducts) {
                    let self = this;

                    selectedProducts.forEach(function (product) {
                        self.groupProducts.push({
                            associated_product: product,
                            qty: 1,
                        });
                    });
                },

                remove(product) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            let index = this.groupProducts.indexOf(product)

                            this.groupProducts.splice(index, 1);
                        }
                    });
                },
            }
        });
    </script>
@endPushOnce