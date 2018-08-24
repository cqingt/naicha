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

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth' ], function () {
    //Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@home');

    // 跳转
    Route::get('/', function () {
        return redirect()->route('home');
    });

    Route::get('/dashboard', 'IndexController@index')->name('home');

    Route::get('down','ConfigController@down');
    Route::get('up','ConfigController@up');
    Route::get('/users/index','UsersController@index');
    Route::get('/users/create','UsersController@create');
    Route::get('/users/show','UsersController@show');
    //Route::resource('roles','RolesController');
    Route::get('/roles/index','RolesController@index');
    Route::get('/roles/create','RolesController@create');
    //Route::resource('permissions','PermissionController');
    Route::get('/permissions/index','PermissionController@index');

    Route::get('/members/list','MembersController@list');
    Route::resource('members','MembersController');

    Route::get('/orders/list','OrdersController@list');
    Route::resource('orders','OrdersController');

    Route::get('/coupons/list','CouponsController@list');
    Route::resource('coupons','CouponsController');

    Route::get('/pushes/list','PushesController@list');
    Route::resource('pushes','PushesController');

    Route::get('/goods/list','GoodsController@list');
    Route::resource('goods','GoodsController');

    Route::get('/shops/list','ShopsController@list');
    Route::resource('shops','ShopsController');

    Route::post('/upload/image','UploadController@image');
});

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');