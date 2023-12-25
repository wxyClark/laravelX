<?php


namespace App\Models\AbcDemo;


use App\Models\BaseModel;

class RuleNameModel extends BaseModel
{
    protected $table = 'demo_rule_name';

    protected $guarded = ['id'];

    public $uniqCode = 'rule_name_code';
}
