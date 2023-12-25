<?php


namespace App\Requests\AbcDemo\BusinessName;


use App\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

    public $rules = [
        'tenant_id' => ['required|integer|min:0', '租户ID'],
        'type' => ['integer|min:0', '类型'],
    ];
}
