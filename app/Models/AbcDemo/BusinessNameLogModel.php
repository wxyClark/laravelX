<?php


namespace App\Models\AbcDemo;


use App\Models\BaseModel;

class BusinessNameLogModel extends BaseModel
{
    protected $table = 'demo_business_name_log';

    protected $guarded = ['id'];

    public $uniqCode = null;
}
