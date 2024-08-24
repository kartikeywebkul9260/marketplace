<x-marketplace::shop.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.transactions.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::shop.breadcrumbs name="seller_transactions" />
    @endSection

    <!-- Page Header -->
    <h2 class="text-2xl font-medium">
        @lang('marketplace::app.shop.sellers.account.transactions.index.title')
    </h2>

    <div class="grid gap-4 md:gap-8 md:grid-cols-3 border rounded-lg mt-8 p-5">
        <div class="grid py-2.5">
            <h3 class="text-2xl font-medium">
                {{ core()->formatPrice($statistics['total_sale']) }}
            </h3>
            <p class="text-sm text-[#757575] font-normal">
                @lang('marketplace::app.shop.sellers.account.transactions.index.total-sale')
            </p>
        </div>

        <div class="grid py-2.5">
            <h3 class="text-2xl font-medium">
                {{ core()->formatPrice($statistics['total_payout']) }}
                @php
                    $payoutPercentage = 0;

                    if ($statistics['total_payout']) {
                        $payoutPercentage = $statistics['total_payout'] * 100 / $statistics['total_sale'];
                    }
                @endphp
                <span class="text-sm font-normal">
                    {{ number_format($payoutPercentage, 2) }} %
                </span>
            </h3>
            <p class="text-sm text-[#757575] font-normal">
                @lang('marketplace::app.shop.sellers.account.transactions.index.total-payout')
            </p>
        </div>

        <div class="grid py-2.5">
            <h3 class="text-2xl font-medium">
                {{ core()->formatPrice($statistics['remaining_payout']) }}
                @php
                    $remainingPercentage = 0;
    
                    if ($statistics['remaining_payout']) {
                        $remainingPercentage = $statistics['remaining_payout'] * 100 / $statistics['total_sale'];
                    }
                @endphp
                <span class="text-sm font-normal">
                    {{ number_format($remainingPercentage, 2) }} %
                </span>
            </h3>
            <p class="text-sm text-[#757575] font-normal">
                @lang('marketplace::app.shop.sellers.account.transactions.index.remaining-payout')
            </p>
        </div>
    </div>

    {!! view_render_event('marketplace.sellers.account.sales.transactions.list.before') !!}

    <!-- Datagrid -->
    <x-shop::datagrid :src="route('shop.marketplace.seller.account.transaction.index')"></x-shop::datagrid>

    {!! view_render_event('marketplace.sellers.account.sales.transactions.list.after') !!}

</x-marketplace::shop.layouts>