<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SingleActionController extends Controller
{
    /**
     * 单action控制器不需要指定方法名，固定使用__invoke方法
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        print_r('<br> php artisan make:controller Demo/SingleActionController --invokable <br>');

        print_r('<br> 为单动作控制器注册路由时，不需要指定控制器方法。相反，您可以简单地将控制器的名称传递给路由器: <br>');

        print_r("Route::get('/single-action', SingleActionController::class); <br>");
    
        return true;
    }
}
