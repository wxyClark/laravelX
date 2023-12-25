<?php


namespace App\Models\AbcDemo;


use App\Models\BaseModel;

class BusinessNameModel extends BaseModel
{
    protected $table = 'demo_business_name';

    protected $guarded = ['id'];

    public $uniqCode = 'business_name_code';
}
