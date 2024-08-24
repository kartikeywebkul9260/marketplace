<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <!-- meta tags -->
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <!-- lang supports inclusion -->
        <style type="text/css">
            @font-face {
                font-family: 'Hind';
                src: url({{ asset('vendor/webkul/ui/assets/fonts/Hind/Hind-Regular.ttf') }}) format('truetype');
            }

            @font-face {
                font-family: 'Noto Sans';
                src: url({{ asset('vendor/webkul/ui/assets/fonts/Noto/NotoSans-Regular.ttf') }}) format('truetype');
            }
        </style>

        @php
            /* main font will be set on locale based */
            $mainFontFamily = app()->getLocale() === 'ar' ? 'DejaVu Sans' : 'Noto Sans';
        @endphp

        <!-- main css -->
        <style type="text/css">
            * {
                font-family: '{{ $mainFontFamily }}';
            }

            body, th, td, h5 {
                font-size: 12px;
                color: #000;
            }

            .container {
                padding: 20px;
                display: block;
            }

            .invoice-summary {
                margin-bottom: 20px;
            }

            .table {
                margin: 20px 6px 0px 6px;
                border-spacing: 0px 0px 15px 0px;
            }

            .table table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
                table-layout: fixed;
            }

            .table thead th {
                font-weight: 700;
                border-top: solid 1px #d3d3d3;
                border-bottom: solid 1px #d3d3d3;
                border-left: solid 1px #d3d3d3;
                padding: 5px 12px;
                background: #005aff0d;
            }

            .table thead th:last-child {
                border-right: solid 1px #d3d3d3;
            }

            .table tbody td {
                padding: 5px 10px;
                color: #3A3A3A;
                vertical-align: middle;
                border-bottom: solid 1px #d3d3d3;
            }

            .table tbody td, p {
                margin: 0;
                color: #000;
            }

            .sale-summary {
                margin-top: 20px;
                float: right;
                background-color: #005aff0d;
            }

            .sale-summary tr td {
                padding: 3px 5px;
            }

            .sale-summary tr.bold {
                font-weight: 700;
            }

            .label {
                color: #000;
                font-weight: bold;
            }

            .logo {
                height: 70px;
                width: 70px;
            }

            .merchant-details {
                margin-bottom: 5px;
            }

            .merchant-details-title {
                font-weight: bold;
            }

            .text-center {
                text-align: center;
            }

            .col-6 {
                width: 42%;
                display: inline-block;
                vertical-align: top;
                margin: 0px 5px;
            }

            .table-header {
                color: #0041FF;
            }

            .align-left {
                text-align: left;
            }

            .invoice-text {
                font-size: 40px; 
                color: #3c41ff; 
                font-weight: bold;
                position: absolute; 
                width: 100%; 
                left: 0;
                text-align: center;
                top: -6px;
            }

            .without_logo {
                height: 35px;
                width: 35px;
            }
            
            .header {
                padding: 0px 2px;
                width: 100%;
                position: relative;
                border-bottom: solid 1px #d3d3d3;
                padding-bottom: 20px;
            }
        </style>
    </head>

    <body style="background-image: none; background-color: #fff;">
        <div class="container">
            <div class="row">
                <div class="col-12 header">
                    <div class="without_logo" style="display:inline-block; vertical-align: middle; padding-top:8px">
                    </div>

                    <div class="invoice-text">
                        <span>{{ Str::upper(trans('marketplace::app.shop.sellers.account.orders.view.invoices.title')) }}</span>
                    </div>
                </div>
            </div>

            <div class="row" style="padding: 5px">
                <div class="col-12">
                    <div class="col-6">
                        <div class="merchant-details">
                            <div class="row">
                                <span class="label">@lang('marketplace::app.shop.sellers.account.orders.view.invoices.invoice-id'): </span>
                                <span class="value">#{{ $sellerInvoice->id }}</span>
                            </div>

                            <div class="row">
                                <span class="label">@lang('marketplace::app.shop.sellers.account.orders.view.invoices.created-on', ['date_time' => core()->formatDate($sellerInvoice->created_at, 'd-m-Y')])
                            </div>

                            <div style="padding-top: 20px">
                                <span class="merchant-details-title">{{ core()->getConfigData('sales.shipping.origin.store_name') ? core()->getConfigData('sales.shipping.origin.store_name') : '' }}</span>
                            </div>

                            <div>{{ core()->getConfigData('sales.shipping.origin.address') ?? '' }}</div>

                            <div>
                                <span>{{ core()->getConfigData('sales.shipping.origin.zipcode') ?? '' }}</span>
                                <span>{{ core()->getConfigData('sales.shipping.origin.city') ?? '' }}</span>
                            </div>

                            <div>{{ core()->getConfigData('sales.shipping.origin.state') ?? '' }}</div>

                            <div>{{ core()->getConfigData('sales.shipping.origin.country') ?? '' }}</div>
                        </div>
                        <div class="merchant-details">
                            @if (core()->getConfigData('sales.shipping.origin.contact'))
                                <div><span class="merchant-details-title">@lang('marketplace::app.shop.sellers.account.orders.view.invoices.contact'): </span> {{ core()->getConfigData('sales.shipping.origin.contact') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-6" style="padding-left: 80px">
                        <div class="row">
                            <span class="label">@lang('marketplace::app.shop.sellers.account.orders.view.invoices.order-id'): </span>
                            <span class="value">#{{ $sellerInvoice->order->order->increment_id }}</span>
                        </div>
                        
                        <div class="row">
                            <span class="label">@lang('marketplace::app.shop.sellers.account.orders.view.invoices.order-date'): </span>
                            <span class="value">{{ core()->formatDate($sellerInvoice->order->created_at, 'd-m-Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="invoice-summary">
                <!-- Billing & Shipping Address Details --> 
                <div class="table address">
                    <table>
                        <thead>
                            <tr>
                                <th class="table-header align-left" style="width: 50%;">
                                    {{ ucwords(trans('marketplace::app.shop.sellers.account.orders.view.invoices.billed-to')) }}
                                </th>

                                @if ($sellerInvoice->order->order->shipping_address)
                                    <th class="table-header align-left">
                                        {{ ucwords(trans('marketplace::app.shop.sellers.account.orders.view.invoices.shipped-to')) }}
                                    </th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                @foreach (['billing_address', 'shipping_address'] as $addressType)
                                    @if ($sellerInvoice->order->order->$addressType)
                                        <td>
                                            <p>{{ $sellerInvoice->order->order->$addressType->company_name ?? '' }}</p>

                                            <p>{{ $sellerInvoice->order->order->$addressType->name }}</p>

                                            <p>{{ $sellerInvoice->order->order->$addressType->address }}</p>

                                            <p>{{ $sellerInvoice->order->order->$addressType->postcode . ' ' . $sellerInvoice->order->order->$addressType->city }}</p>

                                            <p>{{ $sellerInvoice->order->order->$addressType->state }}</p>

                                            <p>{{ core()->country_name($sellerInvoice->order->order->$addressType->country) }}</p>

                                            @lang('marketplace::app.shop.sellers.account.orders.view.invoices.contact') : {{ $sellerInvoice->order->order->$addressType->phone }}
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Payment & Shipping Methods -->
                <div class="table payment-shipment">
                    <table>
                        <thead>
                            <tr>
                                <th class="table-header align-left" style="width: 50%;">@lang('marketplace::app.shop.sellers.account.orders.view.invoices.payment-method')</th>

                                @if ($sellerInvoice->order->order->shipping_address)
                                    <th class="table-header align-left">@lang('marketplace::app.shop.sellers.account.orders.view.invoices.shipping-method')</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    {{ core()->getConfigData('sales.payment_methods.' . $sellerInvoice->order->order->payment->method . '.title') }}

                                    @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($sellerInvoice->order->order->payment->method); @endphp

                                    @if (! empty($additionalDetails))
                                        <div>
                                            <label class="label">{{ $additionalDetails['title'] }}:</label>
                                            <p class="value">{{ $additionalDetails['value'] }}</p>
                                        </div>
                                    @endif
                                </td>

                                @if ($sellerInvoice->order->order->shipping_address)
                                    <td>
                                        {{ $sellerInvoice->order->order->shipping_title }}
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table items">
                    <table>
                        <thead>
                            <tr>
                                @foreach (['sku', 'product-name', 'price', 'qty', 'subtotal', 'tax-amount', 'grand-total'] as $item)
                                    <th class="text-center table-header">@lang('marketplace::app.shop.sellers.account.orders.view.invoices.' . $item)</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($sellerInvoice->invoice->items as $item)
                                <tr>
                                    <td class="text-center">{{ $item->getTypeInstance()->getOrderedItem($item)->sku }}</td>

                                    <td class="text-center">
                                        {{ $item->name }}

                                        @if (isset($item->additional['attributes']))
                                            <div class="item-options">

                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                @endforeach

                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-center">{!! core()->formatBasePrice($item->base_price, true) !!}</td>

                                    <td class="text-center">{{ $item->qty }}</td>

                                    <td class="text-center">{!! core()->formatBasePrice($item->base_total, true) !!}</td>

                                    <td class="text-center">{!! core()->formatBasePrice($item->base_tax_amount, true) !!}</td>

                                    <td class="text-center">{!! core()->formatBasePrice($item->base_total + $item->base_tax_amount, true) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Sale Summary -->
                <table class="sale-summary">
                    <tr>
                        <td>@lang('marketplace::app.shop.sellers.account.orders.view.invoices.subtotal')</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($sellerInvoice->base_sub_total, true) !!}</td>
                    </tr>

                    <tr>
                        <td>@lang('marketplace::app.shop.sellers.account.orders.view.invoices.shipping-handling')</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice(0, true) !!}</td>
                    </tr>

                    <tr>
                        <td>@lang('marketplace::app.shop.sellers.account.orders.view.invoices.tax')</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($sellerInvoice->base_tax_amount, true) !!}</td>
                    </tr>

                    <tr>
                        <td>@lang('marketplace::app.shop.sellers.account.orders.view.invoices.discount')</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($sellerInvoice->base_discount_amount, true) !!}</td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <hr>
                        </td>
                    </tr>

                    <tr>
                        <td>@lang('marketplace::app.shop.sellers.account.orders.view.invoices.grand-total')</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($sellerInvoice->base_sub_total, true) !!}</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>
