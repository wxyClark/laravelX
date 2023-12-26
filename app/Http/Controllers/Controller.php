<?php

namespace App\Http\Controllers;

use App\Enums\PageEnums;
use App\Requests\BaseRequest;
use App\Traits\LoggerTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, LoggerTrait;

    /**
     * @desc 通用返回json响应的方法
     * @param  int  $code
     * @param  array  $data
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseJson(int $code, array $data, string $message = '成功')
    {
        $code = $code == 0 ? 50000 : $code;

        $response = array(
            'code' => $code,
            'data' => $data,
            'msg'  => empty($message) ? ErrorCodeEnums::getCodeDefinition($code) : $message,
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc 通用BaseRequest校验方法
     * @param  BaseRequest  $request
     * @throws \Exception
     */
    public function validateRequest(BaseRequest $request)
    {
        $rs = $request->validateParams();
        if (!$rs['status']) {
            $errorCode = ErrorCodeEnums::ERROR_CODE_PARAMS_INVALID;
            $errorType = ErrorCodeEnums::getCodeDefinition($errorCode);
            throw new \Exception($errorType.':'.$rs['msg'], $errorCode);
        }
    }

    /**
     * @desc 通用参数校验方法
     * @param  array  $params
     * @param  array  $rules
     * @throws \Exception
     */
    public function validateParams(array $params, array $rules)
    {
        $field_names = [];
        $rule_data = [];
        foreach ($rules as $key => $rule) {
            $rule_data[$key] = $rule[0];
            $field_names[$key] = $rule[1];
        }

        $rs = ['status' => true];

        $validator = Validator::make($params, $rule_data, [], $field_names);
        if ($validator->fails()) {
            $message = '';
            if ($validator->errors() && !empty($validator->errors())) {
                $errors = json_decode($validator->errors(), true);

                if (!empty($errors)) {
                    foreach ($errors as $key => $value) {
                        $keyArray = explode('.', $key);
                        $originalValue = $params;
                        foreach ($keyArray as $_key) {
                            $originalValue = $originalValue[$_key] ?? [];
                        }
                        $message .= $originalValue; //  打印数组中校验不通过的具体项
                        $message .= implode(',', $value) . ';';
                    }
                }
            }
            return ['status' => false, 'msg' => $message];
        }

        if (!$rs['status']) {
            $errorCode = ErrorCodeEnums::ERROR_CODE_PARAMS_INVALID;
            $errorType = ErrorCodeEnums::getCodeDefinition($errorCode);
            throw new \Exception($errorType.':'.$rs['msg'], $errorCode);
        }
    }

    /**
     * 打印当前路由的 controller@action
     */
    protected function printMethod()
    {
        print_r('<br>' . request()->route()->getActionName() . '<br>');
    }

    /**
     * @desc    获取页码
     * @param $params
     * @param string $key
     * @return int
     * @author  wxy
     * @ctime   2022/5/17 17:34
     */
    protected function getPageIndex($params, $key = 'page')
    {
        $page_index = $params[$key] ?? PageEnums::DEFAULT_PAGE;

        return $this->isValidPageIndex($page_index) ? $page_index : PageEnums::DEFAULT_PAGE;
    }

    /**
     * @desc    获取分页数
     * @param $params
     * @param string $key
     * @return int
     * @author  wxy
     * @ctime   2022/5/17 17:34
     */
    protected function getPageSize($params, $key = 'page_size')
    {
        $page_size = $params[$key] ?? PageEnums::DEFAULT_PAGE_SIZE;

        return $this->isValidPageSize($page_size) ? $page_size : PageEnums::DEFAULT_PAGE_SIZE;
    }

    /**
     * @desc    页码是否有效
     * @param $page_index
     * @return bool
     * @author  wxy
     * @ctime   2022/5/17 17:34
     */
    private function isValidPageIndex($page_index)
    {
        return is_integer($page_index) && $page_index > 0 ? true : false;
    }

    /**
     * @desc    分页数是否有效
     * @param $page_size
     * @return bool
     * @author  wxy
     * @ctime   2022/5/17 17:35
     */
    private function isValidPageSize($page_size)
    {
        return is_integer($page_size) && $page_size > 0 || $page_size < 1000 ? true : false;
    }
}
