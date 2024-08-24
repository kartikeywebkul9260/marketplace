<?php

return [
    [
        'key'   => 'marketplace',
        'name'  => 'marketplace::app.admin.menu.title',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 3,
        'icon'  => 'icon-store',
    ], [
        'key'   => 'marketplace.sellers',
        'name'  => 'marketplace::app.admin.menu.sellers',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.products',
        'name'  => 'marketplace::app.admin.menu.products',
        'route' => 'admin.marketplace.products.index',
        'sort'  => 2,
    ], [
        'key'   => 'marketplace.product-reviews',
        'name'  => 'marketplace::app.admin.menu.product-reviews',
        'route' => 'admin.marketplace.product_reviews.index',
        'sort'  => 3,
    ], [
        'key'   => 'marketplace.seller-reviews',
        'name'  => 'marketplace::app.admin.menu.seller-reviews',
        'route' => 'admin.marketplace.seller_reviews.index',
        'sort'  => 4,
    ], [
        'key'   => 'marketplace.orders',
        'name'  => 'marketplace::app.admin.menu.orders',
        'route' => 'admin.marketplace.orders.index',
        'sort'  => 5,
    ], [
        'key'   => 'marketplace.transactions',
        'name'  => 'marketplace::app.admin.menu.transactions',
        'route' => 'admin.marketplace.transactions.index',
        'sort'  => 6,
    ], [
        'key'   => 'marketplace.seller-flag-reasons',
        'name'  => 'marketplace::app.admin.menu.seller-flag-reasons',
        'route' => 'admin.marketplace.seller_flag_reasons.index',
        'sort'  => 7,
    ], [
        'key'   => 'marketplace.seller-categories',
        'name'  => 'marketplace::app.admin.menu.seller-categories',
        'route' => 'admin.marketplace.seller_categories.index',
        'sort'  => 8,
    ], [
        'key'   => 'marketplace.payment-requests',
        'name'  => 'marketplace::app.admin.menu.payment-requests',
        'route' => 'admin.marketplace.payment_request.index',
        'sort'  => 9,
    ],
];
