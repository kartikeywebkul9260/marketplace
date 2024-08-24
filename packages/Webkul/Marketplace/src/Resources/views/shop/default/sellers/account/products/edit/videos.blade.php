<div class="relative p-4 bg-white border rounded-xl box-shadow">
    <!-- Panel Header -->
    <div class="flex gap-5 justify-between mb-4">
        <div class="flex flex-col gap-2">
            <p class="text-base text-gray-800 font-semibold">
                @lang('marketplace::app.shop.sellers.account.products.edit.videos.title')
            </p>

            <p class="text-xs text-gray-500 font-medium">
                @lang('marketplace::app.shop.sellers.account.products.edit.videos.info', ['size' => core()->getMaxUploadSize()])
            </p>
        </div>
    </div>

    <!-- Video Blade Component -->
    <x-marketplace::shop.media.videos
        name="videos[files]"
        :allow-multiple="true"
        :uploaded-videos="$product?->videos"
    />

    <x-admin::form.control-group.error control-name='videos.files[0]' />
</div>
