<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| 管理员后台路由
*/

Route::middleware('auth:sanctum')->get('/admin', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'admin'], function () {

    //  SaaS系统
    Route::group(['namespace' => 'system'], function () {
        //  功能模块-无限级分类
        Route::post('module/create', 'ModuleController@create');    //  创建
        Route::put('module/update', 'ModuleController@update');     //  编辑
        Route::delete('module/delete', 'ModuleController@delete');  //  逻辑删除
        Route::get('module/detail', 'ModuleController@detail');     //  详情
        Route::post('module/index', 'ModuleController@index');      //  列表
        Route::post('module/export', 'ModuleController@export');    //  导出

        //  数据字典-二级数据
        Route::post('dict/create', 'DictController@create');    //  创建
        Route::put('dict/update', 'DictController@update');     //  编辑
        Route::delete('dict/delete', 'DictController@delete');  //  逻辑删除
        Route::get('dict/detail', 'DictController@detail');     //  详情
        Route::post('dict/index', 'DictController@index');      //  列表
        Route::post('dict/export', 'DictController@export');    //  导出
    });

    //  公共数据
    Route::group(['namespace' => 'common'], function () {
        //  国家
        Route::post('country/create', 'CountryController@create');    //  创建
        Route::put('country/update', 'CountryController@update');     //  编辑
        Route::delete('country/delete', 'CountryController@delete');  //  逻辑删除
        Route::get('country/detail', 'CountryController@detail');     //  详情
        Route::post('country/index', 'CountryController@index');      //  列表
        Route::post('country/export', 'CountryController@export');    //  导出

        //  行政区域划分
        Route::post('region/create', 'RegionController@create');    //  创建
        Route::put('region/update', 'RegionController@update');     //  编辑
        Route::delete('region/delete', 'RegionController@delete');  //  逻辑删除
        Route::get('region/detail', 'RegionController@detail');     //  详情
        Route::post('region/index', 'RegionController@index');      //  列表
        Route::post('region/export', 'RegionController@export');    //  导出

        //  币种
        Route::post('currency/create', 'CurrencyController@create');    //  创建
        Route::put('currency/update', 'CurrencyController@update');     //  编辑
        Route::delete('currency/delete', 'CurrencyController@delete');  //  逻辑删除
        Route::get('currency/detail', 'CurrencyController@detail');     //  详情
        Route::post('currency/index', 'CurrencyController@index');      //  列表
        Route::post('currency/export', 'CurrencyController@export');    //  导出

        //  汇率
        Route::post('exchange/create', 'ExchangeController@create');    //  创建
        Route::put('exchange/update', 'ExchangeController@update');     //  编辑
        Route::delete('exchange/delete', 'ExchangeController@delete');  //  逻辑删除
        Route::get('exchange/detail', 'ExchangeController@detail');     //  详情
        Route::post('exchange/index', 'ExchangeController@index');      //  列表
        Route::post('exchange/export', 'ExchangeController@export');    //  导出

        //  平台
        Route::post('platform/create', 'PlatformController@create');    //  创建
        Route::put('platform/update', 'PlatformController@update');     //  编辑
        Route::delete('platform/delete', 'PlatformController@delete');  //  逻辑删除
        Route::get('platform/detail', 'PlatformController@detail');     //  详情
        Route::post('platform/index', 'PlatformController@index');      //  列表
        Route::post('platform/export', 'PlatformController@export');    //  导出

        //  站点
        Route::post('website/create', 'WebsiteController@create');    //  创建
        Route::put('website/update', 'WebsiteController@update');     //  编辑
        Route::delete('website/delete', 'WebsiteController@delete');  //  逻辑删除
        Route::get('website/detail', 'WebsiteController@detail');     //  详情
        Route::post('website/index', 'WebsiteController@index');      //  列表
        Route::post('website/export', 'WebsiteController@export');    //  导出
    });

    //  Saas客户管理
    Route::group(['namespace' => 'customer'], function () {
        //  租户管理
        Route::post('tenant/create', 'TenantController@create');    //  创建
        Route::put('tenant/update', 'TenantController@update');     //  编辑
        Route::delete('tenant/delete', 'TenantController@delete');  //  逻辑删除
        Route::get('tenant/detail', 'TenantController@detail');     //  详情
        Route::post('tenant/index', 'TenantController@index');      //  列表。使用POST兼容参数过程的情况
        Route::post('tenant/export', 'TenantController@export');    //  导出。使用POST兼容参数过程的情况

        //  租户权限
        Route::post('tenantModule/create', 'TenantModuleController@create');    //  创建
        Route::put('tenantModule/update', 'TenantModuleController@update');     //  编辑
        Route::delete('tenantModule/delete', 'TenantModuleController@delete');  //  逻辑删除
        Route::get('tenantModule/detail', 'TenantModuleController@detail');     //  详情
        Route::post('tenantModule/index', 'TenantModuleController@index');      //  列表
        Route::post('tenantModule/export', 'TenantModuleController@export');    //  导出

        //  TODO 模式配置，如：支持 平台、站点从属关系对调
    });

    //  系统分析
    Route::group(['namespace' => 'analyse'], function () {
        //  错误日志
        Route::post('errorLog/index', 'ErrorLogController@index');      //  列表
        Route::post('errorLog/export', 'ErrorLogController@export');    //  导出
        Route::get('errorLog/detail', 'ErrorLogController@detail');     //  详情
        Route::put('errorLog/update', 'ErrorLogController@update');     //  编辑-标记已处理
        Route::get('errorLog/report', 'ErrorLogController@report');     //  统计表

        //  功能使用情况
        Route::post('moduleUse/create', 'ModuleUseController@create');    //  创建
        Route::post('moduleUse/index', 'ModuleUseController@index');      //  列表
        Route::post('moduleUse/export', 'ModuleUseController@export');    //  导出
        Route::get('moduleUse/detail', 'ModuleUseController@detail');     //  详情
        Route::put('moduleUse/update', 'ModuleUseController@update');     //  编辑-标记忽略
        Route::get('moduleUse/report', 'ModuleUseController@report');     //  统计表
    });
});
