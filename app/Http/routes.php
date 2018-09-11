<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// 后台管理
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth' ], function () {
    //Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@home');

    // 跳转
    Route::get('/', function () {
        return redirect()->route('home');
    });

    Route::get('/dashboard', 'IndexController@index')->name('home');

    // 重置密码
    Route::get('/users/resetPwd', 'UsersController@resetPwd');
    Route::post('/users/reset', 'UsersController@postReset');

    // 用户管理
    Route::get('/users/index','UsersController@index');
//    Route::get('/users/create','UsersController@create');
    Route::get('/users/show','UsersController@show');
    Route::post('/users/store','UsersController@store');
    Route::put('/users/update/{id}','UsersController@update');
    Route::post('/users/{id}/edit','UsersController@edit');
//    Route::get('/users','UsersController@index');
    Route::resource('users','UsersController');

    //Route::resource('roles','RolesController');
    Route::get('/roles/index','RolesController@index');
    Route::get('/roles/create','RolesController@create');
    Route::get('/roles/roles','RolesController@roles');
    Route::get('/roles/list','RolesController@list');
    Route::resource('roles','RolesController');

    //Route::resource('permissions','PermissionController');
    Route::get('/permissions/index','PermissionController@index');

    // 会员管理
    Route::get('/members/list','MembersController@list');
    Route::resource('members','MembersController');

    // 订单列表
    Route::get('/orders/list','OrdersController@list');
    Route::resource('orders','OrdersController');

    // 优惠券
    Route::get('/coupons/list','CouponsController@list');
    Route::post('/coupons/{id}/grant','CouponsController@grant');
    Route::resource('coupons','CouponsController');

    // 推送列表
    Route::get('/pushes/list','PushesController@list');
    Route::resource('pushes','PushesController');

    // 商品列表
    Route::get('/goods/list','GoodsController@list');
    Route::resource('goods','GoodsController');

    // 店铺
    Route::get('/shops/list','ShopsController@list');
    Route::resource('shops','ShopsController');

    // 数据统计
    Route::post('/upload/image','UploadController@image');
    Route::get('/data/index','DataController@index');
});

// 店员管理
Route::group(['prefix' => 'clerk', 'namespace' => 'Clerk', 'middleware' => 'auth'], function () {
    Route::get('/','IndexController@index');
    Route::get('/index','IndexController@index');
    Route::get('/index/listen','IndexController@listen');
    Route::get('/index/data','IndexController@data');
    Route::post('/orders/compile','OrdersController@compile');
    Route::get('/orders','OrdersController@index');
    Route::get('/orders/create','OrdersController@create');
    Route::get('/messages','MessagesController@index');

    Route::get('/orders/list','OrdersController@list');
    Route::resource('orders','OrdersController');

});

// API
Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
    Route::get('/','IndexController@index');
    Route::get('/index/index','IndexController@index');
    Route::get('/index/like/{id}','IndexController@like');

    Route::get('/user/coupons', 'UserController@coupons');
    Route::get('/user/tastes', 'UserController@tastes');
    Route::get('/user/orders', 'UserController@orders');
    Route::get('/user/joinTaste/{id}', 'UserController@joinTaste');
    Route::get('/user/setIndex/{id}', 'UserController@setIndex');
    Route::get('/user/deleteTaste/{id}', 'UserController@deleteTaste');
    Route::get('/user/index','UserController@index');
});

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');