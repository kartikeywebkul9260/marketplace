<x-marketplace::shop.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.seller-info.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::shop.breadcrumbs name="seller_info" />
    @endSection

    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div class="">
            <h2 class="text-2xl font-medium">
                @lang('marketplace::app.shop.sellers.account.seller-info.title')
            </h2>
        </div>
    </div>

    <div class="grid gap-6 p-5 rounded-xl md:w-[710px] border border-[#E9E9E9] mt-8">
        <h3 class="text-xl text-navyBlue leading-8">
            @lang('marketplace::app.shop.sellers.account.seller-info.general-info')
        </h3>

        <div class="grid gap-2">
            <p class="text-base font-medium">
                @lang('marketplace::app.shop.sellers.account.seller-info.admin-commission-rate')
            </p>
            <div class="flex flex-wrap gap-2.5">
                <div class="inline-block bg-[#F5F5F5] rounded-lg px-3 py-3">
                    @php
                        $commission = $seller->commission_enable
                            ? round($seller->commission_percentage, 2)
                            : core()->getConfigData('marketplace.settings.general.commission_per_unit');
                    @endphp
                    
                    @lang('marketplace::app.shop.sellers.account.seller-info.commission-rate', ['rate' => $commission])
                </div>
            </div>
        </div>

        <div class="grid gap-2">
            <p class="text-base font-medium">
                @lang('marketplace::app.shop.sellers.account.seller-info.allowed-categories')
            </p>
            <div class="flex flex-wrap gap-2.5">
                @forelse ($allowedCategories as $category)
                    <div class="inline-block bg-[#F5F5F5] rounded-lg px-3 py-3">
                        {{$category->name}}
                    </div>
                @empty
                    <div class="inline-block bg-[#F5F5F5] rounded-lg px-3 py-3">
                        @lang('marketplace::app.shop.sellers.account.seller-info.not-available')
                    </div>
                @endforelse
            </div>
        </div>

        <div class="grid gap-2">
            <p class="text-base font-medium">
                @lang('marketplace::app.shop.sellers.account.seller-info.allowed-product-type')
            </p>
            <div class="flex flex-wrap gap-2.5">
                @foreach ($seller->allowed_product_types as $type)                  
                    <div class="inline-block bg-[#F5F5F5] rounded-lg px-3 py-3">
                        @lang($type['name'])
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-2">
            <p class="text-base font-medium">
                @lang('marketplace::app.shop.sellers.account.seller-info.payment-methods')
            </p>
            <div class="flex flex-wrap gap-2.5">
                @foreach (payment()->getPaymentMethods() as $paymentMethod)                            
                    <div class="inline-block bg-[#F5F5F5] rounded-lg px-3 py-3">
                        {{$paymentMethod['method_title']}}
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-2">
            <p class="text-base font-medium">
                @lang('marketplace::app.shop.sellers.account.seller-info.shipping-methods')
            </p>
            <div class="flex flex-wrap gap-2.5">
                @foreach (shipping()->getShippingMethods() as $shippingMethod)                            
                    <div class="inline-block bg-[#F5F5F5] rounded-lg px-3 py-3">
                        {{$shippingMethod['method_title']}}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Need To Implement This In the Future -->

        {{-- <div class="grid gap-2">
            <p class="text-base font-medium">
                @lang('marketplace::app.shop.sellers.account.seller-info.allowed-extensions')
            </p>
            <div class="flex flex-wrap gap-2.5">
                <div class="inline-block bg-[#F5F5F5] rounded-lg px-3 py-3">
                    @lang('marketplace::app.shop.sellers.account.seller-info.not-available')
                </div>
            </div>
        </div> --}}
    </div>
</x-marketplace::shop.layouts>
