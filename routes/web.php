<?php

use App\Http\Controllers\Demo\DemoController;
use App\Http\Controllers\Demo\MiddlerwareController;
use App\Http\Controllers\Demo\SingleActionController;
use App\Http\Controllers\Demo\ColumnController;
use App\Http\Controllers\Demo\PhotoController;
use App\Http\Controllers\Demo\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| 命中第一条符合规则的解析，就不向下执行
*/


Route::get('/', function () {
    return view('welcome');
});

// 心跳检测
Route::get('/health', function () {
    echo 'route(\'profile\') = ' . route('laravel9-health') . '<br>';
    return true;
})->name('laravel9-health');
// 命名路由后，可通过 $url = route('profile'); 获取路由的url 用于重定向 或 条件判定

// 解析到 controller action
Route::get('/demo/index', [DemoController::class, 'index']);

// 必填参数; 如果id传入是index，则走第一条命中的解析
Route::get('/demo/{id}', function ($id) {
    return 'User '.$id;
});
// 多个参数
Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return $postId . ' : '.$commentId;
});

// 正则表达式约束
Route::get('/userTest/{name}', function ($name) {
    return 'user/name：' . $name;
})->where('name', '[A-Za-z]+');

Route::get('/userTest/{id}', function ($id) {
    return 'user/id' . $id;
})->where('id', '[0-9]+');

Route::get('/userTest/{id}/{name}', function ($id, $name) {
    return 'user/id/name:' . $id . '/' . $name;
})->where(['id' => '[0-9]+', 'name' => '[a-z]+']);


//  路由组使用中间件
Route::middleware(['before.base', 'after.base'])->group(function () {
    Route::get('/middleware/index', [MiddlerwareController::class, 'index'])->middleware('before.special_auth');

    Route::get('/middleware/detail', [MiddlerwareController::class, 'detail'])->middleware('after.special_handle');

    # 单action控制器路由
    Route::get('/single-action', SingleActionController::class);
});

//  生成资源类代码  --api 比 --resource 少 create() 和 edit() 两个方法
//  php artisan make:controller Demo/PhotoController --resource --model=Photo --requests
//  php artisan make:controller PhotoController --api
Route::resource('photos', PhotoController::class)->missing(function (Request $request) {
    return Redirect::route('photos.index');
})->names([
    //  命名资源路由
    'create' => 'photos.build'
]);
//  命名资源路由参数  /users/{admin_user}
/**
 * ->parameters([
 *    'users' => 'admin_user'
 * ]);
 */

//  未定义的资源路由将重定向到404
Route::resources([
    'columns' => ColumnController::class,
    'posts' => PostController::class,
]);
//  TODO 嵌套资源  /photos/{photo}/comments/{comment}
// Route::resource('photos.comments', PhotoCommentController::class);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::group(['prefix' => 'v2'], function () {
    //  用户权限
    Route::group(['namespace' => 'rbac'], function () {
        //  用户管理
        Route::post('user/create', 'UserController@create');    //  创建
        Route::put('user/update', 'UserController@update');     //  编辑
        Route::put('user/changeStatus', 'UserController@changeStatus');     //  修改状态
        Route::delete('user/delete', 'UserController@delete');  //  逻辑删除
        Route::get('user/detail', 'UserController@detail');     //  详情
        Route::post('user/index', 'UserController@index');      //  列表。使用POST兼容参数过程的情况
        Route::post('user/export', 'UserController@export');    //  导出。使用POST兼容参数过程的情况

        //  角色管理
        Route::post('role/create', 'RoleController@create');    //  创建
        Route::put('role/update', 'RoleController@update');     //  编辑
        Route::delete('role/delete', 'RoleController@delete');  //  逻辑删除
        Route::get('role/detail', 'RoleController@detail');     //  详情
        Route::post('role/index', 'RoleController@index');      //  列表
        Route::post('role/export', 'RoleController@export');    //  导出

        //  权限管理
        Route::post('authority/create', 'AuthorityController@create');    //  创建
        Route::put('authority/update', 'AuthorityController@update');     //  编辑
        Route::delete('authority/delete', 'AuthorityController@delete');  //  逻辑删除
        Route::get('authority/detail', 'AuthorityController@detail');     //  详情
        Route::post('authority/index', 'AuthorityController@index');      //  列表
        Route::post('authority/export', 'AuthorityController@export');    //  导出

        //  授权管理
        Route::post('authorize/create', 'AuthorizeController@create');    //  创建
        Route::put('authorize/update', 'AuthorizeController@update');     //  编辑
        Route::delete('authorize/delete', 'AuthorizeController@delete');  //  逻辑删除
        Route::get('authorize/detail', 'AuthorizeController@detail');     //  详情
        Route::post('authorize/index', 'AuthorizeController@index');      //  列表
        Route::post('authorize/export', 'AuthorizeController@export');    //  导出
    });

    //  单点登录
    Route::group(['namespace' => 'sso'], function () {
        //  登录操作
        Route::post('sso/login', 'SsoController@login');        //  登录
        Route::post('sso/logout', 'SsoController@logout');      //  退出
        Route::post('sso/reset', 'SsoController@reset');        //  修改密码
        Route::post('ssoLog/index', 'SsoLogController@index');  //  登录日志
    });

    //  操作日志
    Route::group(['namespace' => 'operate'], function () {
        //  登录操作
        Route::post('operateLog/create', 'OperateLogController@create');    //  创建
        Route::post('operateLog/index', 'OperateLogController@index');      //  列表
        Route::post('operateLog/export', 'OperateLogController@export');    //  导出
    });
});

//  运维、demo、灰度
Route::group(['prefix' => 'v1'], function () {
    //  运维检测
    Route::group(['namespace' => 'DevOps'], function () {
        Route::get('/health/web', function () {
            $response = [
                'code' => '200',
                'data' => ['health' => true],
                'msg' => 'web is health',
            ];
            return response()->json($response);
        });
        Route::get('/health/db', function () {return response()->json([1]);});
        Route::get('/health/redis', function () {return response()->json([1]);});
        Route::get('/health/memcached', function () {return response()->json([1]);});
    });

    //  AbcDemo 代码模板
    Route::group(['namespace' => 'AbcDemo', 'prefix' => 'demo'], function () {
        //  BusinessName 业务名称
        Route::get('/BusinessName/demo', 'BusinessNameController@demo');
        Route::get('/BusinessName/index', 'BusinessNameController@index');
        Route::get('/BusinessName/detail', 'BusinessNameController@detail');
        Route::get('/BusinessName/export', 'BusinessNameController@export');
        Route::get('/BusinessName/log', 'BusinessNameController@log');
        Route::post('/BusinessName/add', 'BusinessNameController@add');
        Route::post('/BusinessName/batchUpdate', 'BusinessNameController@batchUpdate');
        Route::post('/BusinessName/changeStatus', 'BusinessNameController@changeStatus');
        Route::post('/BusinessName/update', 'BusinessNameController@update');

        //  RuleName 规则名称
        Route::get('/RuleName/index', 'RuleNameController@index');
        Route::get('/RuleName/detail', 'RuleNameController@detail');
        Route::get('/RuleName/export', 'RuleNameController@export');
        Route::get('/RuleName/log', 'RuleNameController@log');
        Route::post('/RuleName/add', 'RuleNameController@add');
        Route::post('/RuleName/batchUpdate', 'RuleNameController@batchUpdate');
        Route::post('/RuleName/changeStatus', 'RuleNameController@changeStatus');
        Route::post('/RuleName/update', 'RuleNameController@update');
        Route::post('/RuleName/match', 'RuleNameController@match');
    });

});

require __DIR__.'/auth.php';
