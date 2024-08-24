@php
    $seller = auth()->guard('seller')->user();
@endphp

<header class="hidden max-lg:block p-4 bg-white border-b sticky top-0 z-10">
    <!-- Mobile Menu -->
    <div class="grid gap-2">
        <div class="flex gap-1.5 items-center justify-between">
            <div class="flex gap-2.5">
                <!-- Hamburger Menu -->
                <i
                    class="hidden icon-hamburger text-2xl py-1 rounded-md cursor-pointer hover:bg-gray-100 max-lg:block"
                    @click="$refs.sideMenuDrawer.open()"
                >
                </i>

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
            </div>
    
            <div class="flex gap-2.5">
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
        </div>
    
        <!-- Search Bar Component -->
        <x-marketplace::shop.layouts.header.search />
    </div>
</header>

<!-- Menu Sidebar Drawer -->
<x-marketplace::shop.drawer
    position="left"
    width="270px"
    ref="sideMenuDrawer"
>
    <!-- Drawer Header -->
    <x-slot:header>
        <div class="flex justify-between items-center">
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
        </div>
    </x-slot>

    <!-- Drawer Content -->
    <x-slot:content>
        <div class="h-[calc(100vh-120px)] overflow-auto journal-scroll">
            <!-- Account Navigation Menus -->
            @foreach ($menu->items as $menuItem)
                @foreach ($menuItem['children'] as $subMenuItem)
                    <a href="{{ $subMenuItem['url'] }}">
                        <div class="flex justify-between py-5 border-b cursor-pointer">
                            <div class="flex gap-x-4 items-center">
                                <span class="{{ $subMenuItem['icon'] }} text-2xl"></span>

                                <span class="font-medium whitespace-nowrap">
                                    @lang($subMenuItem['name'])
                                </span>
                            </div>

                            @if ($menu->getActive($subMenuItem))
                                <span class="mp-arrow-right-icon text-2xl"></span>
                            @endif
                        </div>
                    </a>
                @endforeach
            @endforeach
        </div>
    </x-slot>
</x-marketplace::shop.drawer>