<?php

namespace Webkul\Marketplace\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.product.update.after' => [
            'Webkul\Marketplace\Listeners\Product@afterUpdate',
        ],

        'checkout.cart.add.before' => [
            'Webkul\Marketplace\Listeners\Cart@cartItemAddBefore',
        ],

        'checkout.cart.add.after' => [
            'Webkul\Marketplace\Listeners\Cart@cartItemAddAfter',
        ],

        'checkout.order.save.after' => [
            'Webkul\Marketplace\Listeners\Order@afterPlaceOrder',
        ],

        'sales.order.cancel.after' => [
            'Webkul\Marketplace\Listeners\Order@afterOrderCancel',
        ],

        'marketplace.sales.order.save.after' => [
            'Webkul\Marketplace\Listeners\Order@sendNewOrderMail',
        ],

        'sales.invoice.save.after' => [
            'Webkul\Marketplace\Listeners\Invoice@afterInvoice',
        ],

        'sales.shipment.save.after' => [
            'Webkul\Marketplace\Listeners\Shipment@afterShipment',
        ],

        'sales.refund.save.after' => [
            'Webkul\Marketplace\Listeners\Refund@afterRefund',
        ],

        'core.configuration.save.after' => [
            'Webkul\Marketplace\Listeners\Configuration@afterUpdate',
        ],
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (core()->getConfigData('marketplace.settings.general.status')) {
            Event::listen('bagisto.shop.products.view.additional_actions.after', function ($e) {
                $e->addTemplate('marketplace::shop.products.product-sellers');
            });

            Event::listen('bagisto.shop.products.view.after', function ($e) {
                $e->addTemplate('marketplace::shop.products.top-selling');
            });

            Event::listen('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.after', function ($e) {
                $e->addTemplate('marketplace::components.shop.layouts.header.sell');
            });

            Event::listen('bagisto.shop.components.layouts.header.mobile.mini_cart.after', function ($e) {
                $e->addTemplate('marketplace::components.shop.layouts.header.sell');
            });
        }
    }
}
