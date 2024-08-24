<div class="relative max-sm:overflow-x-auto md:mt-6 border rounded-xl">
    <table class="w-full text-sm text-left">
        <thead class="bg-[#F5F5F5] border-b border-[#E9E9E9] text-sm text-black">
            <tr>
                <th
                    scope="col"
                    class="w-3/5 px-6 py-4 font-medium"
                >
                    @lang('marketplace::app.shop.sellers.account.orders.view.product')
                </th>

                <th
                    scope="col"
                    class="px-6 py-4 font-medium"
                >
                    @lang('marketplace::app.shop.sellers.account.orders.view.price')
                </th>

                <th
                    scope="col"
                    class="px-6 py-4 font-medium"
                >
                    @lang('marketplace::app.shop.sellers.account.orders.view.item-status')
                </th>

                <th
                    scope="col"
                    class="px-6 py-4 font-medium"
                >
                    @lang('marketplace::app.shop.sellers.account.orders.view.sub-total')
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sellerOrder->items as $orderItem)
                <tr class="bg-white border-b">
                    <td
                        class="px-6 py-4 text-black font-medium"
                        data-value="@lang('marketplace::app.shop.sellers.account.orders.view.product')"
                    >
                        <div class="flex gap-2">
                            <x-shop::media.images.lazy
                                class="h-15 min-w-15 max-w-15 rounded-lg object-cover"
                                :src="$orderItem->item->product?->base_image_url ?: bagisto_asset('images/small-product-placeholder-64b7f208.webp', 'marketplace')"
                                alt="Product Image"
                                width="60"
                                height="60"
                            >
                            </x-shop::media.images.lazy>
                            <div class="flex flex-col">
                                <p class="text-sm font-medium">
                                    {{ $orderItem->item->name }}
                                </p>
                                <p class="text-sm font-normal">
                                    @lang('marketplace::app.shop.sellers.account.orders.view.sku', ['sku' => $orderItem->item->sku])
                                </p>

                                @if (isset($orderItem->item->additional['attributes']))
                                    <div>
                                        @foreach ($orderItem->item->additional['attributes'] as $attribute)
                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}<br>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td
                        class="px-6 py-4 text-black font-medium"
                        data-value="@lang('marketplace::app.shop.sellers.account.orders.view.price')"
                    >
                        {{ core()->formatPrice($orderItem->item->price, $sellerOrder->order->order_currency_code) }}
                    </td>

                    <td
                        class="px-6 py-4 text-black font-medium"
                        data-value= "@lang('marketplace::app.shop.sellers.account.orders.view.item-status')"
                    >
                        <div class="grid">
                            <span>
                                @lang('marketplace::app.shop.sellers.account.orders.view.item-ordered', ['qty_ordered' => $orderItem->item->qty_ordered])
                            </span>

                            <span>
                                {{ $orderItem->item->qty_invoiced ? __('marketplace::app.shop.sellers.account.orders.view.item-invoice', ['qty_invoiced' => $orderItem->item->qty_invoiced]) : '' }}
                            </span>

                            <span>
                                {{ $orderItem->item->qty_shipped ? __('marketplace::app.shop.sellers.account.orders.view.item-shipped', ['qty_shipped' => $orderItem->item->qty_shipped]) : '' }}
                            </span>

                            <span>
                                {{ $orderItem->item->qty_refunded ? __('marketplace::app.shop.sellers.account.orders.view.item-refunded', ['qty_refunded' => $orderItem->item->qty_refunded]) : '' }}
                            </span>

                            <span>
                                {{ $orderItem->item->qty_canceled ? __('marketplace::app.shop.sellers.account.orders.view.item-canceled', ['qty_canceled' => $orderItem->item->qty_canceled]) : '' }}
                            </span>
                        </div>
                    </td>

                    <td
                        class="px-6 py-4 text-black font-medium"
                        data-value="@lang('marketplace::app.shop.sellers.account.orders.view.sub-total')"
                    >
                        <div class="flex gap-2 items-center">
                            {{ core()->formatPrice($orderItem->item->total, $sellerOrder->order->order_currency_code) }}

                            <x-shop::dropdown>
                                <x-slot:toggle>
                                    <span class="mp-toast-info-icon text-2xl cursor-pointer"></span>
                                </x-slot:toggle>

                                <x-slot:content class="bg-[#000000] text-white bg-opacity-80">
                                    <div class="grid gap-2">
                                        <div class="flex gap-5 justify-between w-full">
                                            <p class="text-sm">
                                                @lang('marketplace::app.shop.sellers.account.orders.view.total')
                                            </p>
        
                                            <div class="flex gap-5">
                                                <p class="text-sm">-</p>
        
                                                <p class="text-sm">
                                                    {{ core()->formatPrice($orderItem->seller_total, $sellerOrder->order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
        
                                        <div class="flex gap-5 justify-between w-full">
                                            <p class="text-sm">
                                                @lang('marketplace::app.shop.sellers.account.orders.view.discount')
    
                                                @if ($sellerOrder->order->coupon_code)
                                                    ({{ $sellerOrder->order->coupon_code }})
                                                @endif
                                            </p>
    
                                            <div class="flex gap-5">
                                                <p class="text-sm">-</p>
    
                                                <p class="text-sm">
                                                    {{ core()->formatPrice($orderItem->item->discount_amount, $sellerOrder->order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex gap-5 justify-between w-full">
                                            <p class="text-sm">
                                                @lang('marketplace::app.shop.sellers.account.orders.view.admin-commission')
                                            </p>
        
                                            <div class="flex gap-5">
                                                <p class="text-sm">-</p>
        
                                                <p class="text-sm">
                                                    {{ core()->formatPrice($orderItem->base_commission, $sellerOrder->order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
        
                                        <div class="flex gap-5 justify-between w-full">
                                            <p class="text-sm">
                                                @lang('marketplace::app.shop.sellers.account.orders.view.tax')
                                            </p>
        
                                            <div class="flex gap-5">
                                                <p class="text-sm">-</p>
        
                                                <p class="text-sm">
                                                    {{ core()->formatPrice($orderItem->item->tax_amount, $sellerOrder->order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
        
                                        <div class="flex gap-5 justify-between w-full">
                                            <p class="text-sm">
                                                @lang('marketplace::app.shop.sellers.account.orders.view.sub-total')
                                            </p>
        
                                            <div class="flex gap-5">
                                                <p class="text-sm">-</p>
        
                                                <p class="text-sm">
                                                    {{ core()->formatPrice($orderItem->item->base_total, $sellerOrder->order->order_currency_code) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </x-slot:content>
                            </x-shop::dropdown>
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
                        @lang('marketplace::app.shop.sellers.account.orders.view.sub-total')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatPrice($sellerOrder->sub_total, $sellerOrder->order->order_currency_code) }}
                        </p>
                    </div>
                </div>

                @if ($sellerOrder->order->haveStockableItems())
                    <div class="flex w-full gap-5 justify-between">
                        <p class="text-sm">
                            @lang('marketplace::app.shop.sellers.account.orders.view.shipping-handling')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatPrice(0, $sellerOrder->order->order_currency_code) }}
                            </p>
                        </div>
                    </div>
                @endif

                <div class="flex gap-5 justify-between w-full">
                    <p class="text-sm">
                        @lang('marketplace::app.shop.sellers.account.orders.view.discount')

                        @if ($sellerOrder->order->coupon_code)
                            ({{ $sellerOrder->order->coupon_code }})
                        @endif
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatPrice($sellerOrder->discount_amount, $sellerOrder->order->order_currency_code) }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-5 justify-between w-full">
                    <p class="text-sm">
                        @lang('marketplace::app.shop.sellers.account.orders.view.tax')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatPrice($sellerOrder->tax_amount, $sellerOrder->order->order_currency_code) }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-5 justify-between w-full">
                    <p class="text-sm">
                        @lang('marketplace::app.shop.sellers.account.orders.view.grand-total')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatPrice($sellerOrder->base_sub_total, $sellerOrder->order->order_currency_code) }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-5 justify-between w-full">
                    <p class="text-sm">
                        @lang('marketplace::app.shop.sellers.account.orders.view.total-paid')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatPrice($sellerOrder->base_sub_total, $sellerOrder->order->order_currency_code) }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-5 justify-between w-full">
                    <p class="text-sm">
                        @lang('marketplace::app.shop.sellers.account.orders.view.total-refunded')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatPrice($sellerOrder->grand_total_refunded, $sellerOrder->order->order_currency_code) }}
                        </p>
                    </div>
                </div>
                <div class="flex gap-5 justify-between w-full">
                    <p class="text-sm">
                        @lang('marketplace::app.shop.sellers.account.orders.view.total-due')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            @if($sellerOrder->status !== 'canceled')
                                {{ core()->formatPrice($sellerOrder->total_due ? $sellerOrder->base_total_due - $sellerOrder->shipping_amount : 0, $sellerOrder->order->order_currency_code) }}
                            @else
                                {{ core()->formatPrice(0.00, $sellerOrder->order->order_currency_code) }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex gap-5 justify-between w-full">
                    <p class="text-sm">
                        @lang('marketplace::app.shop.sellers.account.orders.view.total-seller-amt')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatPrice($sellerOrder->base_seller_total, $sellerOrder->order->order_currency_code) }}
                        </p>
                    </div>
                </div>
                <div class="flex gap-5 justify-between w-full">
                    <p class="text-sm">
                        @lang('marketplace::app.shop.sellers.account.orders.view.admin-commission')
                    </p>

                    <div class="flex gap-5">
                        <p class="text-sm">-</p>

                        <p class="text-sm">
                            {{ core()->formatPrice($sellerOrder->base_commission, $sellerOrder->order->order_currency_code) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
