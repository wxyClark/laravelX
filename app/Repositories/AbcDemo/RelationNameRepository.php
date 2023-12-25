<?php

namespace App\Repositories\AbcDemo;

use App\Models\AbcDemo\RelationNameModel;
use App\Repositories\BaseRepository;

class RelationNameRepository extends BaseRepository
{
    protected $model;

    public function __construct(RelationNameModel $model) {
        $this->model = $model;
    }

    protected function condition(array $userInfo, array $params)
    {
        // TODO: Implement condition() method.
    }
}
