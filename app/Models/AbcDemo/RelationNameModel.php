<?php

namespace App\Models\AbcDemo;

use App\Models\BaseModel;

class RelationNameModel extends BaseModel
{
    protected $table = 'demo_relation_name';

    protected $guarded = ['id'];

    public $uniqCode = 'relation_code';
}
