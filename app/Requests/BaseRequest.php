<?php


namespace App\Requests;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BaseRequest extends Request
{
    public $rules;

    public function validateParams()
    {
        //  TODO $this->input() 没有获取到参数
        $params = $this->input();
        $field_names = [];
        $rule_data = [];
        foreach ($this->rules as $key => $rule) {
            $rule_data[$key] = $rule[0];
            $field_names[$key] = $rule[1];
        }

        $validator = Validator::make($params, $rule_data, [], $field_names);
        if ($validator->fails()) {
            $message = '';
            if ($validator->errors() && !empty($validator->errors())) {
                $params = json_decode($validator->errors(), true);
                if (!empty($params)) {
                    foreach ($params as $value) {
                        $message .= implode(',', $value) . ';';
                    }
                }
            }
            return ['status' => false, 'msg' => $message];
        }

        return ['status' => true];
    }
}
