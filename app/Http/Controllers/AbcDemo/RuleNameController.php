<?php

namespace App\Http\Controllers\AbcDemo;

use App\Http\Controllers\Controller;
use App\Requests\AbcDemo\RuleName\DemoRequest;
use App\Services\AbcDemo\RuleNameService;

/**
 * @desc Demo控制器
 * @package App\Http\Controllers
 */
class RuleNameController extends Controller
{
    /** @var RuleNameService  */
    protected $service;

    public function __construct(RuleNameService $service)
    {
        $this->service = $service;
    }

    public function demo(DemoRequest $request)
    {
        try {

        } catch (\Exception $e) {
            return $this->responseJson($e->getCode(), [], $e->getMessage());
        }
    }
}
