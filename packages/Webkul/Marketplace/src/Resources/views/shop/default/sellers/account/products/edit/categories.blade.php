<!-- Panel -->
<div class="p-4 bg-white border rounded-xl box-shadow">
    <!-- Panel Header -->
    <p class="flex justify-between text-base text-gray-800 font-semibold mb-4">
        @lang('marketplace::app.shop.sellers.account.products.edit.categories.title')
    </p>

    <!-- Panel Content -->
    <div class="mb-5 text-sm text-gray-600">

        <x-marketplace::shop.tree.view
            name-field="categories"
            value-field="id"
            selection-type="individual"
            :items=$categories
            :value="json_encode($product->categories->pluck('id'))"
            :fallback-locale="config('app.fallback_locale')"
        >
        </x-marketplace::shop.tree.view>

    </div>
</div>