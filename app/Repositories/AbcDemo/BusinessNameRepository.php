<?php

namespace App\Repositories\AbcDemo;

use App\Helper\ArrayHelper;
use App\Helper\DatetimeHelper;
use App\Models\AbcDemo\BusinessNameDetailModel;
use App\Models\AbcDemo\BusinessNameLogModel;
use App\Models\AbcDemo\BusinessNameModel;
use App\Repositories\BaseRepository;

class BusinessNameRepository extends BaseRepository
{
    const TYPE_A = 1;
    const TYPE_B = 2;
    const TYPE_MAP = [
        self::TYPE_A => 'A类型',
        self::TYPE_B => 'B类型',
    ];

    const STATUS_A = 1;
    const STATUS_B = 2;
    const STATUS_MAP = [
        self::STATUS_A => 'A1类型',
        self::STATUS_B => 'B1类型',
    ];

    /** @var BusinessNameModel  */
    protected $model;

    /** @var BusinessNameDetailModel  */
    protected $detailModel;

    /** @var BusinessNameLogModel  */
    protected $logModel;

    public function __construct(
        BusinessNameModel $model,
        BusinessNameDetailModel $detailModel,
        BusinessNameLogModel $logModel
    ) {
        $this->model = $model;
        $this->detailModel = $detailModel;
        $this->logModel = $logModel;
    }

    /**
     * @desc 新增详情
     * @param  array  $data
     * @return mixed
     */
    public function addDetail(array $data)
    {
        return $this->detailModel->insert($data);
    }

    /**
     * @desc 删除详情
     * @param  array  $params
     * @return mixed
     * @author wxy
     * @ctime 2023/2/13 18:22
     */
    public function delDetail(array $params)
    {
        return $this->detailModel->where('tenant_id', $params['tenant_id'])
                 ->where('business_name_code', $params['business_name_code'])
                 ->delete();
    }

    /**
     * @desc 详情列表
     * @param  array  $params
     * @return mixed
     * @author wxy
     * @ctime 2023/2/14 16:16
     */
    public function getDetailList(array $params)
    {
        return $this->detailCondition($params['tenant_id'],  $params['uniq_code'])->orderBy('id', 'asc')->get()->toArray();
    }

    /**
     * @desc 通用查询条件
     * @param  array  $userInfo
     * @param  array  $params
     * @return mixed
     */
    protected function condition(array $userInfo, array $params)
    {
        $query = $this->model->where('tenant_id', $userInfo['tenant_id']);

        //  ID
        if (!empty($params['id'])) {
            $query->whereIn('id', (array)$params['id']);
        }

        //  唯一编码
        if (!empty($params['uniq_code'])) {
            $query->whereIn($this->model->uniqCode, (array)$params['uniq_code']);
        }

        //  状态
        if (!empty($params['status'])) {
            $query->whereIn('status', (array)$params['status']);
        }

        //  操作人 (created_by、updated_by、checked_by)
        if (!empty($params['operator_type']) && !empty($params['operator'])) {
            $query->whereIn($params['operator_type'], (array)$params['operator']);
        }

        //  时间段(全局时间段查询只精确到日期)
        if (!empty($params['date_type'])) {
            if (!empty($params['date_start'])) {
                $query->where($params['date_type'], '>=', DatetimeHelper::getDateStart($params['date_start']));
            }
            if (!empty($params['date_end'])) {
                $query->where($params['date_type'], '<=', DatetimeHelper::getDateEnd($params['date_start']));
            }
        }

        //  模糊查询默认只支持右模糊
        if (!empty($params['keywords_type']) && !empty($params['keywords'])) {
            $query->where($params['keywords_type'], 'like', $params['keywords'].'%');
        }

        return $query;
    }

    /**
     * @desc 详情通用查询
     * @param  int  $tenantId
     * @param  int  $uniqCode
     * @return mixed
     * @author wxy
     * @ctime 2023/2/14 16:16
     */
    private function detailCondition(int $tenantId, int $uniqCode)
    {
        return $this->detailModel->where('tenant_id', $tenantId)->where($this->detailModel->uniqCode, $uniqCode);
    }
}
