<?php

namespace App\Repositories\AbcDemo;

use App\Models\AbcDemo\RuleNameDetailModel;
use App\Models\AbcDemo\RuleNameLogModel;
use App\Models\AbcDemo\RuleNameModel;
use App\Repositories\BaseRepository;

class RuleNameRepository extends BaseRepository
{
    /** @var RuleNameModel  */
    protected $model;

    /** @var RuleNameDetailModel  */
    protected $detailModel;

    /** @var RuleNameLogModel  */
    protected $logModel;

    public function __construct(
        RuleNameModel $model,
        RuleNameDetailModel $detailModel,
        RuleNameLogModel $logModel
    ) {
        $this->model = $model;
        $this->detailModel = $detailModel;
        $this->logModel = $logModel;
    }

    protected function condition(array $userInfo, array $params)
    {
        // TODO: Implement condition() method.
    }
}
