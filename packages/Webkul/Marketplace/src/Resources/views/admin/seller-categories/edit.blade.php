<x-marketplace::admin.layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('marketplace::app.admin.seller-categories.edit.title')
    </x-slot:title>

    <!-- Category Edit Form -->
    <x-admin::form
        :action="route('admin.marketplace.seller_categories.update', $sellerCategory->id)"
        enctype="multipart/form-data"
    >
        @method('PUT')

        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('marketplace::app.admin.seller-categories.edit.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <!-- Cancel Button -->
                <a
                    href="{{ route('admin.marketplace.seller_categories.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white "
                >
                    @lang('marketplace::app.admin.seller-categories.edit.back-btn')
                </a>

                <!-- Update Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('marketplace::app.admin.seller-categories.edit.update-btn')
                </button>
            </div>
        </div>

        <!-- Full Pannel -->
        <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
            <div class="w-full p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                <div class="mb-2.5">
                    <!-- Parent category -->
                    <div class="flex flex-col gap-3">
                        <x-admin::tree.view
                            name-field="categories"
                            value-field="id"
                            selection-type="individual"
                            :items="json_encode($categories)"
                            :value="json_encode($sellerCategory->categories)"
                            :fallback-locale="config('app.fallback_locale')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-marketplace::admin.layouts>
