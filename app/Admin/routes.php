<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    //商品管理
    $router->resource('/goods', PostController::class);
    //sku管理
    $router->resource('/skus', SkuController::class);
    //商品-sku管理
    $router->get('/goods/sku/{id}', 'SkuController@grid')->name('admin.home');
});
