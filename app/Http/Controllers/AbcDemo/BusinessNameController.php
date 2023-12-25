<?php


namespace App\Http\Controllers\AbcDemo;

use App\Enums\ErrorCodeEnums;
use App\Helper\ArrayHelper;
use App\Http\Controllers\Controller;
use App\Repositories\AbcDemo\BusinessNameRepository;
use App\Requests\AbcDemo\BusinessName\ExportRequest;
use App\Requests\AbcDemo\BusinessName\IndexRequest;
use App\Services\AbcDemo\BusinessNameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @desc Demo控制器
 * @package App\Http\Controllers
 */
class BusinessNameController extends Controller
{
    const LOG_NAME = 'BusinessName';

    const ADD_RULES = [
        'tenant_id'     => ['required|integer|min:0', '租户ID'],
        'business_name' => ['string|max:255', '业务名称'],
        'color'         => ['string|min:6|max:6', '颜色值'],
        'type'          => ['integer|in:A,B', '业务类型'],
        'status'        => ['integer|in:A,B', '业务状态'],
        'percent'       => ['integer|min:0|max"100', '百分比'],

        'details'       => ['required|array', '详请'],
        'details.desc'       => ['required|string', '详细描述'],
        'details.attributes' => ['required|array|max:200', '属性'],
        'details.attributes.*.label' => ['required|string', '属性标题'],
    ];

    const LIST_RULES = [
        'tenant_id'     => ['required|integer|min:0', '租户ID'],
        'uniq_code'     => ['integer|min:0', '业务编码'],

        'business_name' => ['string|max:255', '业务名称(右模糊)'],
        'color'         => ['string|min:6|max:6', '颜色值(精准匹配)'],

        'type'          => ['integer|in:A,B', '业务类型'],
        'status'        => ['integer|in:A,B', '业务状态'],

        'range_type'    => ['string|in:percent', '范围类型'],
        'range_min'     => ['integer|min:0', '范围最小值'],
        'range_max'     => ['integer|min:0', '范围最大值'],

        'time_type'     => ['string|in:created_at,updated_at', '时间类型'],
        'date_start'    => ['string', '开始时间'],
        'date_end'      => ['string', '结束时间'],
    ];

    /** @var BusinessNameService  */
    protected $service;

    public function __construct(BusinessNameService $service)
    {
        $this->service = $service;
    }

    /**
     * @desc 如果参数需要校验，必须使用 App\Requests\BaseRequest 的子类 如果参数不需要校验，直接使用 BaseRequest
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function demo(Request $request)
    {
        try {
            $this->validateRequest($request);

            return $this->responseJson(ErrorCodeEnums::ERROR_CODE_DEFAULT, ['params' => $request->input()], __METHOD__);
        } catch (\Exception $e) {
            Log::error(__METHOD__.' 异常', ArrayHelper::getThrowableInfo($e, 'BusinessName Demo'));
            return $this->responseJson($e->getCode(), [], $e->getMessage());
        }
    }

    /**
     * @desc 新增
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        try {
            $this->validateParams($request->input(), self::ADD_RULES);
            $data = $this->service->add($request->input());

            return $this->responseJson(ErrorCodeEnums::ERROR_CODE_DEFAULT, $data);
        } catch (\Throwable $t) {
            return $this->responseJson($t->getCode(), [], $t->getMessage());
        }
    }

    /**
     * @desc 编辑
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        try {
            $rules = self::ADD_RULES;
            $rules['uniq_code'] =  ['integer|min:0', '业务编码'];
            $this->validateParams($request->input(), $rules);

            return $this->responseJson(ErrorCodeEnums::ERROR_CODE_DEFAULT, ['params' => $request->input()]);
        } catch (\Throwable $t) {
            $this->errorLog($t, 'BusinessName导出', __METHOD__, self::LOG_NAME);

            return $this->responseJson($t->getCode(), [], $t->getMessage());
        }
    }

    /**
     * @desc 列表
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $params = $request->input();
            $this->validateParams($params, self::LIST_RULES);
            $data = $this->service->getList($params);

            return $this->responseJson(ErrorCodeEnums::ERROR_CODE_DEFAULT, $data);
        } catch (\Throwable $t) {
            $this->errorLog($t, 'BusinessName列表', __METHOD__, self::LOG_NAME);

            return $this->responseJson($t->getCode(), [], $t->getMessage());
        }
    }

    /**
     * @desc 如果参数需要校验，必须使用 App\Requests\BaseRequest 的子类 如果参数不需要校验，直接使用 BaseRequest
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        try {
            $rules = [
                'tenant_id' => ['required|integer|min:0', '租户ID'],
                'uniq_code' => ['required|integer|min:0', '业务编码'],
            ];
            $this->validateParams($request->input(), $rules);

            $data = $this->service->getDetail($request->input());

            return $this->responseJson(ErrorCodeEnums::ERROR_CODE_DEFAULT, $data);
        } catch (\Throwable $t) {
            $this->errorLog($t, 'BusinessName详情', __METHOD__, self::LOG_NAME);

            return $this->responseJson($t->getCode(), [], $t->getMessage());
        }
    }

    /**
     * @desc 导出
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        try {
            $this->validateParams($request->input(), self::LIST_RULES);

            return $this->responseJson(ErrorCodeEnums::ERROR_CODE_DEFAULT, ['params' => $request->input()]);
        } catch (\Throwable $t) {
            $this->errorLog($t, 'BusinessName导出', __METHOD__, self::LOG_NAME);

            return $this->responseJson($t->getCode(), [], $t->getMessage());
        }
    }
}
