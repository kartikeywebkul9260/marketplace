<?php

namespace Webkul\Marketplace\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Tree;
use Webkul\Marketplace\Cart;
use Webkul\Marketplace\Console\Commands\InstallMarketplace;
use Webkul\Marketplace\Http\Middleware\Marketplace;
use Webkul\Marketplace\Http\Middleware\Seller;
use Webkul\Marketplace\Models\Catalog\Product;
use Webkul\Marketplace\Repositories\Admin\InvoiceRepository;
use Webkul\Product\Contracts\Product as ProductContract;
use Webkul\Sales\Repositories\InvoiceRepository as BaseInvoiceRepository;

class MarketplaceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('seller', Seller::class);

        $router->aliasMiddleware('marketplace', Marketplace::class);

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');

        $this->loadRoutesFrom(__DIR__.'/../Routes/breadcrumbs.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'marketplace');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'marketplace');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'marketplace');

        $this->app->register(ModuleServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this->app->bind('cart', Cart::class);

        $this->app->bind(BaseInvoiceRepository::class, InvoiceRepository::class);

        $this->app->concord->registerModel(ProductContract::class, Product::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallMarketplace::class,
            ]);
        }

        $this->composeView();

        if (core()->getConfigData('marketplace.settings.general.status')) {
            $this->mergeConfigFrom(
                dirname(__DIR__).'/Config/admin-menu.php',
                'menu.admin'
            );

            $this->mergeConfigFrom(
                dirname(__DIR__).'/Config/seller-menu.php',
                'menu.seller'
            );
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php', 'acl'
        );
    }

    /**
     * Bind the data to the views.
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer([
            'marketplace::components.shop.layouts.header.desktop.index',
            'marketplace::components.shop.layouts.header.mobile.index',
            'marketplace::components.shop.layouts.sidebar.index',
        ], function ($view) {
            $menu = Tree::create();

            foreach (config('menu.seller') as $item) {
                $menu->add($item, 'menu');
            }

            $menu->items = core()->sortItems($menu->items);

            $view->with('menu', $menu);
        });
    }
}
