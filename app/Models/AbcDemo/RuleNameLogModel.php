<?php


namespace App\Models\AbcDemo;


use App\Models\BaseModel;

class RuleNameLogModel extends BaseModel
{
    protected $table = 'demo_rule_name_log';

    protected $guarded = ['id'];

    public $uniqCode = null;
}
