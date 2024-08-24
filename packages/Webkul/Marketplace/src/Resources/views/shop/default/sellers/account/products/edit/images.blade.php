<div class="relative p-4 bg-white border rounded-xl box-shadow mb-2.5">
    <!-- Panel Header -->
    <div class="flex gap-5 justify-between mb-4">
        <div class="flex flex-col gap-2">
            <p class="text-base text-gray-800 font-semibold">
                @lang('marketplace::app.shop.sellers.account.products.edit.images.title')
            </p>

            <p class="text-xs text-gray-500 font-medium">
                @lang('marketplace::app.shop.sellers.account.products.edit.images.info')
            </p>
        </div>
    </div>

    <!-- Image Blade Component -->
    <x-marketplace::shop.media.images
        name="images[files]"
        allow-multiple="true"
        show-placeholders="true"
        :uploaded-images="$product?->images"
    >
    </x-marketplace::shop.media.images>
</div>