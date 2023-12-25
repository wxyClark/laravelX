<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| 系统内部调用
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'api'], function () {
    Route::group(['namespace' => 'auth'], function () {
        //  租户
        Route::get('tenant/all', 'TenantController@all');      //  所有
        Route::get('tenant/auth', 'TenantController@auth');    //  已授权

        //  国家
        Route::get('country/all', 'CountryController@all');      //  所有
        Route::get('country/auth', 'CountryController@auth');    //  已授权

        //  币种
        Route::get('currency/all', 'CurrencyController@all');      //  所有
        Route::get('currency/auth', 'CurrencyController@auth');    //  已授权

        //  币种
        Route::get('exchange/all', 'ExchangeController@all');      //  所有
        Route::get('exchange/auth', 'ExchangeController@auth');    //  已授权

        //  平台
        Route::get('platform/all', 'PlatformController@all');      //  所有
        Route::get('platform/auth', 'PlatformController@auth');    //  已授权

        //  站点
        Route::get('website/all', 'WebsiteController@all');      //  所有
        Route::get('website/auth', 'WebsiteController@auth');    //  已授权

    });
});

