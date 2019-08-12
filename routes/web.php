<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//商品展示
Route::get('/goods/show', 'Goods\GoodsController@goodsShow')->middleware('auth');
//添加到购物车
Route::post('/add/Care', 'Goods\GoodsController@addCare')->middleware('auth');



//购物车展示
Route::get('/cart/show', 'Care\CarController@carShow')->middleware('auth');
//删除该商品
Route::post('/cart/del', 'Care\CarController@carDel')->middleware('auth');
//商品数量添加
Route::post('/cart/num/add', 'Care\CarController@carUpdateNum')->middleware('auth');
//减少商品数量
Route::post('/cart/num/jian', 'Care\CarController@carUpdateNum')->middleware('auth');




//生成订单
Route::post('/add/order', 'Order\OrderController@addOrder')->middleware('auth');
//订单列表
Route::get('/order/show', 'Order\OrderController@orderShow')->middleware('auth');
//订单详情
Route::get('/orderdetail/show/{id}', 'Order\OrderController@orderdetail')->middleware('auth');




//支付宝
Route::get('/alipay/{id}', 'PayController@alipay')->middleware('auth');

Route::get('/alipayNotify', 'PayController@alipayNotify')->middleware('auth');
Route::get('/alipayA', 'PayController@alipayA')->middleware('auth');



