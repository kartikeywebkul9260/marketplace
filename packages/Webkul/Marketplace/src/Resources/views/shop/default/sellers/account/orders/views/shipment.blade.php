@foreach ($sellerOrder->shipments as $sellerShipment)
    <div class="flex justify-between items-center">
        <div class="flex max-sm:flex-wrap">
            <p class="text-base font-medium">
                @lang('marketplace::app.shop.sellers.account.orders.view.shipments.individual-shipment', ['track_number' => $sellerShipment->shipment->track_number]),
            </p>&nbsp;
            <p class="text-base font-medium">
                @lang('marketplace::app.shop.sellers.account.orders.view.shipments.created-on', ['date_time' => core()->formatDate($sellerShipment->shipment->created_at, 'd/m/y h:i:s')])
            </p>
        </div>
    </div>

    <div class="relative overflow-x-auto border rounded-xl mt-6">
        <table class="w-full text-sm text-left">
            <thead class="text-sm text-black bg-[#F5F5F5] border-b border-[#E9E9E9]">
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.orders.view.shipments.name')
                    </th>

                    <th
                        scope="col"
                        class="px-6 py-4 font-medium"
                    >
                        @lang('marketplace::app.shop.sellers.account.orders.view.shipments.qty')
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($sellerShipment->shipment->items as $item)
                    <tr class="bg-white border-b">
                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.orders.view.shipments.name')"
                        >
                            <div class="flex flex-col">
                                <p class="text-sm font-medium">
                                    {{ $item->name }}
                                </p>
                                <p class="text-sm font-normal">
                                    @lang('marketplace::app.shop.sellers.account.orders.view.shipments.sku', ['sku' => $item->sku])
                                </p>

                                @if (isset($item->additional['attributes']))
                                    <div>
                                        @foreach ($item->additional['attributes'] as $attribute)
                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}<br>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td
                            class="px-6 py-4 text-black font-medium"
                            data-value="@lang('marketplace::app.shop.sellers.account.orders.view.shipments.qty')"
                        >
                            {{ $item->qty }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="py-8">
        <table class="text-sm text-left">
            <tr>
                <td class="text-sm">
                    @lang('marketplace::app.shop.sellers.account.orders.view.shipments.inventory-source')
                </td>

                <td class="text-sm px-2.5">-</td>

                <td class="text-sm">
                    {{ $sellerShipment->shipment->inventory_source_name }}
                </td>
            </tr>

            <tr>
                <td class="text-sm">
                    @lang('marketplace::app.shop.sellers.account.orders.view.shipments.carrier-title')
                </td>

                <td class="text-sm px-2.5">-</td>

                <td class="text-sm">
                    {{ $sellerShipment->shipment->carrier_title }}
                </td>
            </tr>

            <tr>
                <td class="text-sm">
                    @lang('marketplace::app.shop.sellers.account.orders.view.shipments.tracking-number')
                </td>

                <td class="text-sm px-2.5">-</td>

                <td class="text-sm">
                    {{ $sellerShipment->shipment->track_number }}
                </td>
            </tr>
        </table>
    </div>
@endforeach
