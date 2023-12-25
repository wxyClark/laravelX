<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MiddlerwareController extends Controller
{
    /**
     * 列表
     */
    public function index(Request $request)
    {
        print_r('<br>');

        print_r($request->all());

        print_r('<br>' . __METHOD__. ' | line: ' . __LINE__ . '<br>');

        return true;
    }

    /**
     * 详情
     */
    public function detail(Request $request)
    {
        print_r('<br> <br>');

        print_r($request->all());

        print_r('<br>' . __METHOD__. ' | line: ' . __LINE__ . '<br>');

        return true;
    }
}
