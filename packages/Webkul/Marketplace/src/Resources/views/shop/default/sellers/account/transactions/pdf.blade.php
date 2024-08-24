@inject ('productRepository', 'Webkul\Marketplace\Repositories\ProductRepository')
@inject ('orderItemRepository', 'Webkul\Sales\Repositories\OrderItemRepository')

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <style type="text/css">
            * {
                font-family: sans-serif;
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
                margin-top: 20px;
            }

            .table table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }

            .table thead th {
                font-weight: 700;
                border-top: solid 1px #d3d3d3;
                border-bottom: solid 1px #d3d3d3;
                border-left: solid 1px #d3d3d3;
                padding: 5px 10px;
                background: #F4F4F4;
            }

            .table thead th:last-child {
                border-right: solid 1px #d3d3d3;
            }

            .table tbody td {
                padding: 5px 10px;
                border-bottom: solid 1px #d3d3d3;
                border-left: solid 1px #d3d3d3;
                color: #3A3A3A;
                vertical-align: middle;
            }

            .table tbody td p {
                margin: 0;
            }

            .table tbody td:last-child {
                border-right: solid 1px #d3d3d3;
            }

           .sale-summary {
                margin-top: 40px;
                float: right;
            }

            .sale-summary tr td {
                padding: 3px 5px;
            }

            .sale-summary tr.bold {
                font-weight: 600;
            }

            .label {
                color: #000;
                font-weight: bold;
            }
        </style>
    </head>

    <body style="background-image: none;background-color: #fff;">
        <div class="container">

            <div class="invoice-summary">

                <div class="row">
                    <span class="label">@lang('marketplace::app.shop.sellers.account.transactions.view.id') -</span>
                    <span class="value">#{{ $transaction->transaction_id }}</span>
                </div>

                <div class="row">
                    <span class="label">@lang('marketplace::app.shop.sellers.account.transactions.view.method') -</span>
                    <span class="value">{{ $transaction->method }}</span>
                </div>

                <div class="row">
                    <span class="label">@lang('marketplace::app.shop.sellers.account.transactions.view.date') -</span>
                    <span class="value">{{ core()->formatDate($transaction->created_at, 'd M Y') }}</span>
                </div>

                <div class="table items">
                    <table>
                        <thead>
                            <tr>
                                <th>@lang('marketplace::app.shop.sellers.account.transactions.view.name')</th>
                                <th>@lang('marketplace::app.shop.sellers.account.transactions.view.price')</th>
                                <th>@lang('marketplace::app.shop.sellers.account.transactions.view.qty')</th>
                                <th>@lang('marketplace::app.shop.sellers.account.transactions.view.total')</th>
                                <th>@lang('marketplace::app.shop.sellers.account.transactions.view.commission')</th>
                                <th>@lang('marketplace::app.shop.sellers.account.transactions.view.seller-total')</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($transaction->order->items as $item)
                                @php
                                    $product = $productRepository->find($item->marketplace_product_id);
                                    $orderItem = $orderItemRepository->find($item->order_item_id)
                                @endphp
                                <tr>
                                    <td>{{ $product->product->name }}</td>

                                    <td>
                                        {{ core()->formatPrice($product->price, $transaction->order->order->order_currency_code) }}
                                    </td>

                                    <td>{{$orderItem->qty_shipped}}</td>

                                    <td>
                                        {{ core()->formatPrice($orderItem->total, $transaction->order->order->order_currency_code) }}
                                    </td>

                                    <td>
                                        {{ core()->formatPrice($item->commission, $transaction->order->order->order_currency_code) }}
                                    </td>

                                    <td>
                                        {{ core()->formatPrice($item->seller_total, $transaction->order->order->order_currency_code) }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <table class="sale-summary">
                    <tr>
                        <td>@lang('marketplace::app.shop.sellers.account.transactions.view.subtotal')</td>
                        <td>-</td>
                        <td>{{ core()->formatPrice($transaction->order->sub_total, $transaction->order->order->order_currency_code) }}</td>
                    </tr>

                    @if ($transaction->order->order->haveStockableItems())
                    <tr>
                        <td>@lang('marketplace::app.shop.sellers.account.transactions.view.shipping-handling')</td>
                        <td>-</td>
                        <td>{{ core()->formatPrice(0, $transaction->order->order->order_currency_code) }}</td>
                    </tr>
                    @endif

                    <tr>
                        <td>@lang('marketplace::app.shop.sellers.account.transactions.view.tax')</td>
                        <td>-</td>
                        <td>{{ core()->formatPrice($transaction->order->tax_amount, $transaction->order->order->order_currency_code) }}</td>
                    </tr>

                    <tr>
                        <td>@lang('marketplace::app.shop.sellers.account.transactions.view.commission')</td>
                        <td>-</td>
                        <td>{{ core()->formatPrice($transaction->order->base_commission, $transaction->order->order->order_currency_code) }}</td>
                    </tr>

                    <tr class="bold">
                        <td>@lang('marketplace::app.shop.sellers.account.transactions.view.seller-total')</td>
                        <td>-</td>
                        <td>{{ core()->formatPrice($transaction->order->base_seller_total, $transaction->order->order->order_currency_code) }}</td>
                    </tr>
                </table>

            </div>
        </div>
    </body>
</html>
