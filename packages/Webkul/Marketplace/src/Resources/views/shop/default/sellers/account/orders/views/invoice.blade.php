@foreach ($sellerOrder->invoices as $invoice)
    <div class="flex justify-between items-center md:mt-6">
        <div class="w-4/5 flex max-sm:flex-wrap">
            <p class="text-base font-medium">
                @lang('marketplace::app.shop.sellers.account.orders.view.invoices.individual-invoice', ['invoice_id' => $invoice->increment_id ?? $invoice->id]),
            </p>&nbsp;
            <p class="text-base font-medium">
                @lang('marketplace::app.shop.sellers.account.orders.view.invoices.created-on', ['date_time' => core()->formatDate($invoice->created_at, 'd/m/y h:i:s')])
            </p>
        </div>

        <a
            class="text-[#0A49A7]"
            href="{{ route('shop.marketplace.seller.account.invoices.print', $invoice->id) }}"
        >
            @lang('marketplace::app.shop.sellers.account.orders.view.invoices.print')
        </a>
    </div>

    <div class="relative overflow-x-auto mt-6 border rounded-xl">
        <table class="w-full text-sm text-left">
            <thead class="bg-[#F5F5F5] border-b border-[#E9E9E9] text-sm text-black">
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.orders.view.invoices.name')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.orders.view.invoices.price')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.orders.view.invoices.qty')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.orders.view.invoices.total')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.orders.view.invoices.tax-amount')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.orders.view.invoices.discount')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.orders.view.invoices.sub-total')
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($invoice->items as $orderItem)
                    <tr class="bg-white border-b">
                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.orders.view.invoices.name')"
                        >
                            <div class="flex flex-col">
                                <p class="text-sm font-medium">
                                    {{ $orderItem->item->name }}
                                </p>
                                <p class="text-sm font-normal">
                                    @lang('marketplace::app.shop.sellers.account.orders.view.invoices.sku', ['sku' => $orderItem->item->sku])
                                </p>

                                @if (isset($orderItem->item->additional['attributes']))
                                    <div>
                                        @foreach ($orderItem->item->additional['attributes'] as $attribute)
                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}<br>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.orders.view.invoices.price')"
                        >
                            {{ core()->formatPrice($orderItem->item->price, $sellerOrder->order_currency_code) }}
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.orders.view.invoices.qty')"
                        >
                            {{ $orderItem->item->qty }}
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.orders.view.invoices.sub-total')"
                        >
                            {{ core()->formatPrice($orderItem->item->total, $sellerOrder->order_currency_code) }}
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.orders.view.invoices.tax-amount')"
                        >
                            {{ core()->formatPrice($orderItem->item->tax_amount, $sellerOrder->order_currency_code) }}
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.orders.view.invoices.discount')"
                        >
                            {{ core()->formatPrice($orderItem->item->discount, $sellerOrder->order_currency_code) }}
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.orders.view.invoices.sub-total')"
                        >
                            {{ core()->formatPrice($orderItem->item->total + $orderItem->item->tax_amount, $sellerOrder->order_currency_code) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex gap-10 items-start py-8 max-lg:gap-5 max-md:grid">
        <div class="flex-auto max-md:mt-8">
            <div class="flex justify-end">
                <div class="grid gap-2 max-w-max">
                    <div class="flex gap-5 justify-between w-full">
                        <p class="text-sm">
                            @lang('marketplace::app.shop.sellers.account.orders.view.invoices.subtotal')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatPrice($invoice->sub_total, $sellerOrder->order_currency_code) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5 justify-between w-full">
                        <p class="text-sm">
                            @lang('marketplace::app.shop.sellers.account.orders.view.invoices.shipping-handling')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatPrice(0, $sellerOrder->order_currency_code) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5 justify-between w-full">
                        <p class="text-sm">
                            @lang('marketplace::app.shop.sellers.account.orders.view.invoices.discount')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatPrice($invoice->discount_amount, $sellerOrder->order_currency_code) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5 justify-between w-full">
                        <p class="text-sm">
                            @lang('marketplace::app.shop.sellers.account.orders.view.invoices.tax')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm">
                                {{ core()->formatPrice($invoice->tax_amount, $sellerOrder->order_currency_code) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5 justify-between w-full">
                        <p class="text-sm font-semibold">
                            @lang('marketplace::app.shop.sellers.account.orders.view.invoices.grand-total')
                        </p>

                        <div class="flex gap-5">
                            <p class="text-sm">-</p>

                            <p class="text-sm font-semibold">
                                {{ core()->formatPrice($invoice->base_sub_total, $sellerOrder->order_currency_code) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
