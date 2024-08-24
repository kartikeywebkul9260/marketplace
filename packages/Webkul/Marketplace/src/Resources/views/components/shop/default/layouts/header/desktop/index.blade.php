@php
    $seller = auth()->guard('seller')->user();
@endphp

<header class="hidden md:flex justify-between items-center px-4 py-2.5 bg-white border-b sticky top-0 z-10">
    <div class="flex gap-1.5 items-center">
        <!-- Logo -->
        <a href="{{ route('shop.marketplace.seller.account.dashboard.index') }}">
            @if ($seller->logo)
                <img
                    class="h-10"
                    src="{{ Storage::url($seller->logo) }}"
                    alt="Seller Logo"
                />
            @else
                <img
                    src="{{ bagisto_asset('images/logo.svg') }}"
                    alt="Seller Logo"
                />
            @endif
        </a>

        <!-- Search Bar Component -->
        <x-marketplace::shop.layouts.header.search />
    </div>

    <div class="flex gap-7">
        <a 
            href="{{ route('shop.home.index') }}"
            target="_blank"
            class="flex"
        >
            <span 
                class="mp-home-icon p-1 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-100"
                title="@lang('marketplace::app.shop.components.layouts.header.home-page')"
            >
            </span>
        </a>

        <a 
            href="{{ route('marketplace.seller.show', $seller->url)}}" 
            target="_blank"
            class="flex"
        >
            <span 
                class="mp-store-icon p-1 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-100"
                title="@lang('marketplace::app.shop.components.layouts.header.visit-shop')"
            >
            </span>
        </a>

        <!-- Seller profile -->
        <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
            <x-slot:toggle>
                <div class="flex items-baseline">
                    <span class="mp-customers-icon p-1 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-100">
                    </span>
                </div>
            </x-slot>

            <!-- Seller Dropdown -->
            <x-slot:content class="!p-2.5 shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] border rounded-[20px]">
                <div class="grid gap-1">
                    <a
                        class="px-5 py-2 text-base hover:bg-gray-100 rounded-xl cursor-pointer"
                        href="{{ route('shop.marketplace.seller.account.profile.index') }}"
                    >
                        @lang('marketplace::app.shop.components.layouts.header.my-profile')
                    </a>

                    <!--Seller logout-->
                    <x-shop::form
                        method="DELETE"
                        action="{{ route('marketplace.seller.session.destroy') }}"
                        id="sellerLogout"
                    >
                    </x-shop::form>

                    <a
                        class="px-5 py-2 text-base hover:bg-gray-100 rounded-xl cursor-pointer"
                        href="{{ route('admin.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('sellerLogout').submit();"
                    >
                        @lang('marketplace::app.shop.components.layouts.header.logout')
                    </a>
                </div>
            </x-slot>
        </x-shop::dropdown>
    </div>
</header>
