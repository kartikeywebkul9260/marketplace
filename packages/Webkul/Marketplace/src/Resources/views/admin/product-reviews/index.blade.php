<x-marketplace::admin.layouts>
    <x-slot:title>
        @lang('marketplace::app.admin.product-reviews.index.title')
    </x-slot:title>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="py-2.5 text-xl text-gray-800 dark:text-white font-bold">
            @lang('marketplace::app.admin.product-reviews.index.title')
        </p>
    </div>

    <v-review-drawer></v-review-drawer>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-review-drawer-template">
            <!-- Datagrid -->
            <x-admin::datagrid
                src="{{ route('admin.marketplace.product_reviews.index') }}"
                :isMultiRow="true"
                ref="review_data"
            >
                <!-- Datagrid Header -->
                <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading }">
                    <template v-if="! isLoading">
                        <div class="row grid grid-rows-1 grid-cols-[1.5fr_1fr_2fr_0.2fr] items-center px-4 py-2.5 border-b dark:border-gray-800">
                            <div
                                class="flex gap-2.5 items-center"
                                v-for="(columnGroup, index) in [['customer_full_name', 'product_name', 'product_review_status'], ['rating', 'created_at', 'product_review_id'], ['shop_title', 'title', 'comment']]"
                            >
                                <!-- Product Name, Review Status -->
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
                            <!-- Name, Product, Description -->
                            <div class="grid content-baseline gap-y-1.5">
                                <p
                                    class="text-base text-gray-800 dark:text-white font-semibold"
                                    v-text="record.customer_full_name"
                                >
                                </p>
                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                    v-text="record.product_name"
                                >
                                </p>

                                <p v-html="record.product_review_status"></p>
                            </div>

                            <!-- Rating, Date, Id Section -->
                            <div class="grid content-baseline gap-y-1.5">
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

                                <p
                                    class="text-gray-600 dark:text-gray-300"
                                >
                                    @{{ "@lang('marketplace::app.admin.product-reviews.index.datagrid.review-id')".replace(':review_id', record.product_review_id) }}
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

            <!-- Drawer content -->
            <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
                <x-admin::drawer ref="review">
                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="flex justify-between items-center">
                            <p class="text-xl py-2.5 font-medium dark:text-white">
                                @lang('marketplace::app.admin.product-reviews.index.review-details')
                            </p>
                        </div>
                    </x-slot:header>

                    <!-- Drawer Content -->
                    <x-slot:content>
                        <div class="flex flex-col gap-4 px-1 py-2.5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <!-- Customer Name -->
                                    <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                        @lang('marketplace::app.admin.product-reviews.index.datagrid.customer-name')
                                    </p>

                                    <p 
                                        class="text-gray-800 font-semibold dark:text-white" 
                                        v-text="review.name !== '' ? review.name : 'N/A'"
                                    >
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                        @lang('marketplace::app.admin.product-reviews.index.datagrid.product-name')
                                    </p>

                                    <p 
                                        class="text-gray-800 font-semibold dark:text-white" 
                                        v-text="review.product.name"
                                    >
                                    </p>
                                </div>
        
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                        @lang('marketplace::app.admin.product-reviews.index.datagrid.id')
                                    </p>

                                    <p 
                                        class="text-gray-800 font-semibold dark:text-white" 
                                        v-text="review.id"
                                    >
                                    </p>
                                </div>
        
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                        @lang('marketplace::app.admin.product-reviews.index.datagrid.date')
                                    </p>

                                    <p 
                                        class="text-gray-800 font-semibold dark:text-white" 
                                        v-text="review.date"
                                    >
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">                
                                <div>
                                    <p class="text-gray-600 dark:text-gray-300 font-semibold">
                                        @lang('marketplace::app.admin.product-reviews.index.datagrid.rating') 
                                    </p>

                                    <div class="flex">
                                        <x-admin::star-rating 
                                            :is-editable="false"
                                            ::value="review.rating"
                                        >
                                        </x-admin::star-rating>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-gray-600 dark:text-gray-300 font-semibold">
                                        @lang('marketplace::app.admin.product-reviews.index.datagrid.status') 
                                    </p>

                                    <div class="flex">
                                        <template v-if="review.status == 'approved'">
                                            <p class="label-active">@lang('marketplace::app.admin.product-reviews.index.datagrid.approved')</p>
                                        </template>
                                        <template v-else-if="review.status == 'pending'">
                                            <p class="label-pending">@lang('marketplace::app.admin.product-reviews.index.datagrid.pending')</p>
                                        </template>
                                        <template v-else-if="review.status == 'disapproved'">
                                            <p class="label-canceled">@lang('marketplace::app.admin.product-reviews.index.datagrid.disapproved')</p>
                                        </template>
                                    </div>
                                </div>
                            </div>
        
                            <div class="w-full">
                                <p class="block text-xs text-gray-800 dark:text-white font-medium leading-6">
                                    @lang('marketplace::app.admin.product-reviews.index.datagrid.title') 
                                </p>

                                <p 
                                    class="text-gray-800 font-semibold dark:text-white" 
                                    v-text="review.title"
                                >
                                </p>
                            </div>
        
                            <div class="w-full">
                                <p class="block text-xs text-gray-600 dark:text-gray-300 font-semibold leading-6">
                                    @lang('marketplace::app.admin.product-reviews.index.datagrid.comment')     
                                </p>

                                <p 
                                    class="text-gray-800 dark:text-white" 
                                    v-text="review.comment"
                                >
                                </p>
                            </div>

                            <div
                                class="w-full"
                                v-if="review.images.length"
                            >
                                <x-admin::form.control-group.label>
                                    @lang('marketplace::app.admin.product-reviews.index.datagrid.images')     
                                </x-admin::form.control-group.label>
                            
                                <div class="flex flex-wrap gap-4">
                                    <div v-for="image in review.images" :key="image.id">
                                        <img
                                            v-if="image.type === 'image'"
                                            class="h-15 w-15 rounded"
                                            :src="image.url"
                                            alt="Image"
                                        />

                                        <video
                                            v-else
                                            class="h-15 w-15 rounded"
                                            controls
                                            autoplay
                                        >
                                            <source
                                                :src="image.url"
                                                type="video/mp4"
                                            >
                                        </video>
                                    </div>
                                </div>
                            </div>                                    
                        </div>
                    </x-slot:content>
                </x-admin::drawer>
            </div>
        </script>

        <script type="module">
            app.component('v-review-drawer', {
                template: '#v-review-drawer-template',

                data() {
                    return {
                        review: {},
                    }
                },

                methods: {
                    edit(url) {
                        this.$axios.get(url)
                            .then((response) => {
                                this.$refs.review.open(),

                                this.review = response.data.data
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });                
                    },
                }
            });
        </script>        
    @endPushOnce
</x-marketplace::admin.layouts>
