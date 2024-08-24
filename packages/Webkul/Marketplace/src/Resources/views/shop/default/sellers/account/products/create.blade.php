<x-marketplace::shop.layouts>
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.products.create.title')
    </x-slot:title>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::shop.breadcrumbs name="seller_product_add" />
    @endSection
   
    <div class="flex gap-4 justify-between items-baseline">
        <div class="grid gap-4">
            <p class="text-2xl font-medium">
                @lang('marketplace::app.shop.sellers.account.products.create.title')
            </p>
            <p class="text-xs font-medium opacity-80">
                @lang('marketplace::app.shop.sellers.account.products.create.sub-title')
            </p>
        </div>

        <div class="flex gap-x-2.5 items-center">            
            <a
                href="{{ route('shop.marketplace.seller.account.products.index') }}"
                class="primary-button px-5 py-2.5"
            >
                @lang('marketplace::app.shop.sellers.account.products.create.back')
            </a>
        </div>
    </div>

    <div class="flex justify-center gap-6 max-xl:flex-wrap mt-8">
        <div class="grid md:w-1/2">
            <div class="p-4 md:p-8 bg-white rounded-xl border border-[#E9E9E9]">
                <div class="grid gap-4">
                    <p class="text-xl font-medium text-navyBlue">
                        @lang('marketplace::app.shop.sellers.account.products.create.search-product')
                    </p>
                    
                    <p class="text-lg font-normal text-[#757575]">
                        @lang('marketplace::app.shop.sellers.account.products.create.sell-admin-product-prices')
                    </p>
                </div>

                <v-product-search>
                    <div class="grid gap-2 mt-4">
                        <p class="text-base font-normal">
                            @lang('marketplace::app.shop.sellers.account.products.create.search-product')
                        </p>
    
                        <div class="flex items-center relative w-full">                        
                            <input 
                                type="text" 
                                class="h-11 bg-white border-2 border-[#E9E9E9] rounded-lg block w-full px-2.5 py-3 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 peer"
                                placeholder="@lang('marketplace::app.shop.sellers.account.products.create.search-product')"
                            >
    
                            <i class="icon-search text-2xl absolute top-2.5 ltr:right-3 rtl:left-3"></i>
                        </div>
                    </div>
                </v-product-search>
            </div>
        </div>

        <div class="flex items-center">
            <p class="text-xl font-normal text-[#757575]">
                @lang('marketplace::app.shop.sellers.account.products.create.or')
            </p>
        </div>

        <div class="grid md:w-1/2">
            <div class="grid gap-6 p-4 md:p-8 bg-white rounded-xl box-shadow border border-[#E9E9E9]">
                <div class="grid gap-4">
                    <p class="text-xl font-medium text-navyBlue">
                        @lang('marketplace::app.shop.sellers.account.products.create.create-new-product')
                    </p>
                    <p class="text-lg font-normal text-[#757575]">
                        @lang('marketplace::app.shop.sellers.account.products.create.create-your-new-product')
                    </p>
                </div>

                <v-create-product-form>
                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.products.create.product-type')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="select"
                            name="type"
                        />
                    </x-marketplace::shop.form.control-group>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.products.create.attribute-family')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="select"
                            name="attribute_family_id"
                            class="!shadow-none h-11 border-2 border-[#E9E9E9]"
                        />
                    </x-marketplace::shop.form.control-group>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.products.create.sku')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="text"
                            name="sku"
                            class="!shadow-none h-11 border-2 border-[#E9E9E9]"
                        />
                    </x-marketplace::shop.form.control-group>

                    <div class="flex">
                        <button
                            type="submit"
                            class="min-w-full primary-button px-5 py-2.5"
                        >
                            @lang('marketplace::app.shop.sellers.account.products.create.continue')
                        </button>
                    </div>
                </v-create-product-form>
            </div> 
        </div>
    </div>
    
    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-product-form-template">
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">                   
                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.products.create.product-type')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="select"
                            name="type"
                            rules="required"
                            :label="trans('marketplace::app.shop.sellers.account.products.create.product-type')"
                        >
                            @foreach($sellerAllowedProductTypes as $key => $type)
                                <option value="{{ $key }}">
                                    @lang($key)
                                </option>
                            @endforeach
                        </x-marketplace::shop.form.control-group.control>

                        <x-marketplace::shop.form.control-group.error control-name="type" />
                    </x-marketplace::shop.form.control-group>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.products.create.attribute-family')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="select"
                            name="attribute_family_id"
                            rules="required"
                            :label="trans('marketplace::app.shop.sellers.account.products.create.attribute-family')"
                        >
                            @foreach($families as $family)
                                <option value="{{ $family->id }}">
                                    {{ $family->name }}
                                </option>
                            @endforeach
                        </x-marketplace::shop.form.control-group.control>

                        <x-marketplace::shop.form.control-group.error control-name="attribute_family_id" />
                    </x-marketplace::shop.form.control-group>

                    <x-marketplace::shop.form.control-group>
                        <x-marketplace::shop.form.control-group.label class="required">
                            @lang('marketplace::app.shop.sellers.account.products.create.sku')
                        </x-marketplace::shop.form.control-group.label>

                        <x-marketplace::shop.form.control-group.control
                            type="text"
                            name="sku"
                            ::rules="{ required: true, regex: /^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/ }"
                            :label="trans('marketplace::app.shop.sellers.account.products.create.sku')"
                        />

                        <x-marketplace::shop.form.control-group.error control-name="sku" />
                    </x-marketplace::shop.form.control-group>

                    <div v-show="attributes.length">
                        <div
                           
                            v-for="attribute in attributes"
                        >
                            <label class="block leading-6 text-xs text-gray-800  font-medium">
                                @{{ attribute.name }}
                            </label>

                            <div class="flex flex-wrap gap-1 min-h-9 p-1.5 border rounded-md">
                                <p
                                    class="flex items-center py-1 px-2 bg-gray-600 rounded text-white font-semibold"
                                    v-for="option in attribute.options"
                                >
                                    @{{ option.name }}

                                    <span
                                        class="mp-delete-icon text-white text-lg ltr:ml-1 rtl:mr-1 cursor-pointer"
                                        @click="removeOption(option)"
                                    ></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex">
                        <button
                            type="submit"
                            class="min-w-full primary-button px-5 py-2.5"
                        >
                            @lang('marketplace::app.shop.sellers.account.products.create.continue')
                        </button>
                    </div>
                </form>
            </x-marketplace::shop.form>
        </script>

        <script
            type="text/x-template" 
            id="v-product-search-template"
        >
            <div class="grid gap-2 mt-4">
                <p class="text-base font-normal">
                    @lang('marketplace::app.shop.sellers.account.products.create.search-product')
                </p>

                <div class="flex items-center relative w-full">
                    <input 
                        type="text" 
                        class="h-11 bg-white border-2 border-[#E9E9E9] rounded-lg block w-full ltr:pl-2.5 ltr:pr-11 rtl:pr-2.5 rtl:pl-11 py-3 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 peer"
                        placeholder="@lang('marketplace::app.shop.sellers.account.products.create.search-product')"
                        v-model="searchTerm"
                        v-debounce="500"
                        v-on:click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                    >

                    <i class="icon-search text-2xl absolute top-2.5 ltr:right-3 rtl:left-3"></i>
                
                    <div
                        class="absolute top-14 w-full max-h-72 overflow-y-scroll bg-white shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] border rounded-xl z-10"
                        v-if="isDropdownOpen"
                    >
                        <template v-if="isLoading">
                            <x-admin::shimmer.header.mega-search.products/>
                        </template>

                        <template v-else>
                            <template v-if="products.length">
                                <div
                                    v-for="product in products"
                                    class="grid max-h-[400px] overflow-y-auto border-b border-slate-300 last:border-b-0"
                                >
                                    <div
                                        class="flex gap-2.5 justify-between p-4"
                                    >
                                        <!-- Left Information -->
                                        <div class="flex gap-2.5">
                                            <!-- Image -->
                                            <div
                                                class="w-full h-15 max-w-15 max-h-15 relative rounded overflow-hidden"
                                                :class="{'border border-dashed border-gray-300 rounded overflow-hidden': ! product.base_image}"
                                            >
                                                <template v-if="! product.base_image">
                                                    <img src="{{ bagisto_asset('images/small-product-placeholder.webp') }}">
                                                
                                                    <p class="w-full absolute bottom-1 text-[6px] text-gray-400 text-center font-semibold">
                                                        @lang('marketplace::app.shop.sellers.account.products.create.image-placeholder')
                                                    </p>
                                                </template>

                                                <template v-else>
                                                    <img :src="product.base_image">
                                                </template>
                                            </div>

                                            <!-- Details -->
                                            <div class="grid gap-1.5">
                                                <p class="text-[14x] font-normal">
                                                    @{{ product.name }}
                                                </p>

                                                <p  
                                                    class="text-[14x] font-normal" v-html="product.formatted_price">
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Right Information -->
                                        <div class="grid gap-1 place-content-center text-right">
                                            <a
                                                class="text-navyBlue text-sm font-normal"
                                                :href="['{{ route('marketplace.account.products.assign.index') }}/' + product.id ]"
                                            >
                                                @lang('marketplace::app.shop.sellers.account.products.create.sell-as-yours')
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <p class="p-4">
                                    @lang('marketplace::app.shop.sellers.account.products.create.no-result')
                                </p>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-create-product-form', {
                template: '#v-create-product-form-template',

                data() {
                    return {
                        attributes: [],

                        superAttributes: {}
                    };
                },

                methods: {
                    create(params, { resetForm, resetField, setErrors }) {
                        this.attributes.forEach(attribute => {
                            params.super_attributes ||= {};

                            params.super_attributes[attribute.code] = this.superAttributes[attribute.code];
                        });

                        this.$axios.post("{{ route('marketplace.account.products.store') }}", params)
                            .then((response) => {
                                if (response.data.redirect_url) {
                                    window.location.href = response.data.redirect_url;
                                } else if (response.data.message) {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });
                                } else {
                                    this.attributes = response.data.attributes;

                                    this.setSuperAttributes();
                                }
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    removeOption(option) {
                        this.attributes.forEach(attribute => {
                            attribute.options = attribute.options.filter(item => item.id != option.id);
                        });

                        this.attributes = this.attributes.filter(attribute => attribute.options.length > 0);

                        this.setSuperAttributes();
                    },

                    setSuperAttributes() {
                        this.superAttributes = {};

                        this.attributes.forEach(attribute => {
                            this.superAttributes[attribute.code] = [];

                            attribute.options.forEach(option => {
                                this.superAttributes[attribute.code].push(option.id);
                            });
                        });
                    }
                }
            });

            app.component('v-product-search', {
                template: '#v-product-search-template',

                data() {
                    return {
                        products: [],
                        searchTerm: '',
                        isLoading: false,
                        isDropdownOpen: false,
                    };
                },

                watch: {
                    searchTerm: function (newVal, oldVal) {
                        this.search();
                    }
                },

                created() {
                    window.addEventListener('click', this.handleFocusOut);
                },

                beforeDestroy() {
                    window.removeEventListener('click', this.handleFocusOut);
                },

                methods: {
                    search () {
                        if (this.searchTerm.length > 2) {
                            this.isLoading = true;
                            this.isDropdownOpen = true;

                            this.$axios.get("{{ route('marketplace.account.products.search') }}", {params: {query: this.searchTerm}})
                                .then ((response) => {
                                    if (response.data.message) {
                                        this.isDropdownOpen = false;
                                        
                                        this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });
                                    } else {
                                        this.products = response.data;
                                    }

                                    this.isLoading = false;
                                })
                                .catch ((error) => {
                                    this.isLoading = false;
                                    this.isDropdownOpen = false;
                                })
                        } else {
                            this.isLoading = false;
                            this.isDropdownOpen = false;
                        }
                    },

                    handleFocusOut(e) {
                        if (! this.$el.contains(e.target)) {
                            this.isDropdownOpen = false;
                        }
                    },
                }
            })
        </script>
    @endPushOnce
</x-marketplace::shop.layouts>