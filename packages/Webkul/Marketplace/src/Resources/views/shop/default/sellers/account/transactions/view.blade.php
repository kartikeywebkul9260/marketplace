@inject ('productRepository', 'Webkul\Marketplace\Repositories\ProductRepository')
@inject ('orderItemRepository', 'Webkul\Sales\Repositories\OrderItemRepository')

<x-marketplace::shop.layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('marketplace::app.shop.sellers.account.transactions.view.title', ['transaction_id' => $transaction->transaction_id])
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-marketplace::shop.breadcrumbs name="seller_transactions_view" />
    @endSection

    <!-- Page Header -->
    <h2 class="text-2xl font-medium">
        @lang('marketplace::app.shop.sellers.account.transactions.view.title', ['transaction_id' => $transaction->transaction_id])
    </h2>

    <div class="flex justify-between items-center mt-4">
        <div class="flex gap-2 py-4 items-center">
            <span class="h-5 flex items-center bg-[#02B1FD] text-xs font-medium text-white p-2.5 rounded-xl">
                @lang('marketplace::app.shop.sellers.account.transactions.view.payment-method', ['method' => $transaction->method])
            </span>

            <span class="text-xs font-medium opacity-80">
                @lang('marketplace::app.shop.sellers.account.transactions.view.created-on', ['date' => core()->formatDate($transaction->created_at, 'd M Y')])
            </span>
        </div>

        <div class="secondary-button flex gap-x-2.5 items-center py-3 px-5 border-[#E9E9E9] font-normal">
            <a href="{{route('shop.marketplace.seller.account.transaction.print', $transaction->id)}}">
                @lang('marketplace::app.shop.sellers.account.transactions.view.print')
            </a>
        </div>
    </div>

    <div class="relative overflow-x-auto mt-4 border rounded-xl">
        <table class="w-full text-sm text-left">
            <thead class="bg-[#F5F5F5] border-b border-[#E9E9E9] text-sm text-black">
                <tr>
                    <th
                        scope="col"
                        class="w-3/5 px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.transactions.view.name')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.transactions.view.price')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.transactions.view.qty')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.transactions.view.total')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.transactions.view.commission')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.transactions.view.seller-total')
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($transaction->order->items as $item)
                    @php
                        $product = $productRepository->find($item->marketplace_product_id);
                        $orderItem = $orderItemRepository->find($item->order_item_id);
                    @endphp
                    <tr class="bg-white border-b">
                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.transactions.view.name')"
                        >
                            {{ $product->product->name }}
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.transactions.view.price')"
                        >
                            {{ core()->formatPrice($product->price, $transaction->order->order->order_currency_code) }}
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value= "@lang('marketplace::app.shop.sellers.account.transactions.view.item-status')"
                        >
                            @if (in_array($product->product->type, ['downloadable', 'virtual']))
                                {{ 'N/A' }}
                            @else
                                {{ $orderItem->qty_shipped}}
                            @endif
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.transactions.view.sub-total')"
                        >
                            <div class="flex gap-2 items-center">
                                {{ core()->formatPrice($orderItem->total, $transaction->order->order->order_currency_code) }}
                            </div>
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.transactions.view.sub-total')"
                        >
                            <div class="flex gap-2 items-center">
                                {{ core()->formatPrice($item->commission, $transaction->order->order->order_currency_code) }}
                            </div>
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.transactions.view.sub-total')"
                        >
                            <div class="flex gap-2 items-center">
                                {{ core()->formatPrice($item->seller_total, $transaction->order->order->order_currency_code) }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex gap-10 items-start mt-8 max-lg:gap-5 max-md:grid">
        <div class="flex-auto max-md:mt-8">
            <div class="flex justify-end">
                <div class="grid gap-2 max-w-max">
                    <div class="flex gap-5 justify-between w-full">
                        <p class="text-sm">
                            @lang('marketplace::app.shop.sellers.account.transactions.view.subtotal')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatPrice($transaction->order->sub_total, $transaction->order->order->order_currency_code) }}
                            </p>
                        </div>
                    </div>

                    @if ($transaction->order->order->haveStockableItems())
                        <div class="flex w-full gap-5 justify-between">
                            <p class="text-sm">
                                @lang('marketplace::app.shop.sellers.account.transactions.view.shipping-handling')
                            </p>

                            <div class="flex gap-5">
                                <p class="text-sm">-</p>

                                <p class="text-sm">
                                    {{ core()->formatPrice(0, $transaction->order->order->order_currency_code) }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-5 justify-between w-full">
                        <p class="text-sm">
                            @lang('marketplace::app.shop.sellers.account.transactions.view.tax')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatPrice($transaction->order->tax_amount, $transaction->order->order->order_currency_code) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex gap-5 justify-between w-full">
                        <p class="text-sm">
                            @lang('marketplace::app.shop.sellers.account.transactions.view.commission')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatPrice($transaction->order->base_commission, $transaction->order->order->order_currency_code) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5 justify-between w-full">
                        <p class="text-sm font-semibold">
                            @lang('marketplace::app.shop.sellers.account.transactions.view.seller-total')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm font-semibold">
                                {{ core()->formatPrice($transaction->order->base_seller_total, $transaction->order->order->order_currency_code) }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-marketplace::shop.layouts>