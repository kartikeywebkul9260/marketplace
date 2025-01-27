<div class="flex gap-10 items-start md:mt-9 max-lg:gap-5">
    <!-- Desktop Filter Shimmer Effect -->
    <div class="max-md:hidden">
        <x-shop::shimmer.categories.filters />
    </div>

    <div class="flex-1">
        <!-- Desktop Toolbar Shimmer Effect -->
        <div class="max-md:hidden">
            <x-marketplace::shop.shimmer.toolbar />
        </div>

        <!-- Product Card Container -->
        @if (request()->query('mode') =='list')
            <div class="grid grid-cols-1 gap-6 mt-8">
                <x-shop::shimmer.products.cards.list count="9" />
            </div>
        @else
            <div class="grid grid-cols-3 gap-8 mt-8 max-sm:mt-5 max-1060:grid-cols-2 max-sm:justify-items-center max-sm:gap-4">
                <!-- Product Card Shimmer Effect -->
                <x-shop::shimmer.products.cards.grid count="9" />
            </div> 
        @endif

        <button class="shimmer block w-[171.516px] h-12 mt-14 mx-auto py-3 rounded-2xl"></button>
    </div>
</div>