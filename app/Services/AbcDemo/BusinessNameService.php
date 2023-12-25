<?php

namespace App\Services\AbcDemo;

use App\Repositories\AbcDemo\BusinessNameRepository;
use App\Repositories\AbcDemo\RelationNameRepository;
use App\Services\BaseService;
use Godruoyi\Snowflake\Snowflake;
use Illuminate\Support\Facades\DB;

class BusinessNameService extends BaseService
{
    const LOG_NAME = 'BusinessNameService';

    const LOG_TYPE_ADD = 1;
    const LOG_TYPE_EDIT = 2;
    const LOG_TYPE_CHANGE_STATUS = 3;
    const LOG_MAP = [
        self::LOG_TYPE_ADD => '新增',
        self::LOG_TYPE_EDIT => '编辑',
        self::LOG_TYPE_CHANGE_STATUS => '修改状态',
    ];

    //  需要记录日志的字段
    const LOG_COLUMNS = [
        'business_name' => [],
        'color'         => [],
        'type'          => BusinessNameRepository::TYPE_MAP,
        'status'        => BusinessNameRepository::STATUS_MAP,
        'percent'       => [],
        'detail_list'   => [],
    ];

    /** @var BusinessNameRepository  */
    protected $businessNameRe;

    /** @var RelationNameRepository  */
    protected $relationNameRe;

    public function __construct(
        BusinessNameRepository $businessNameRe,
        RelationNameRepository $relationNameRe
    ) {
        $this->businessNameRe = $businessNameRe;
        $this->relationNameRe = $relationNameRe;
    }

    /**
     * @desc 新增记录
     * @param array $params
     * @return string[]
     * @author wxy
     * @ctime 2023/2/13 17:05
     */
    public function add(array $params)
    {
        $data = [];
        $detailList = [];
        $logList = [];

        try {
            $snowObj = new Snowflake(mt_rand(0, 31), getmypid());
            $uniqCode = $snowObj->id();
            $data = [
                'tenant_id'            => $params['tenant_id'],
                'business_name_code'   => $uniqCode,
                'type'                 => $params['type'],
                'status'               => $params['status'],
                'percent'              => $params['percent'],
                'business_name'        => $params['business_name'],
                'color'                => $params['color'],
                'created_by_uniq_code' => $params['user_code'],
            ];

            foreach ($params['details'] as $detail) {
                //  【注意】
                $detailUniqCode = $snowObj->id();
                $detailList[] = [
                    'tenant_id'                 => $params['tenant_id'],
                    'business_name_code'        => $uniqCode,
                    'business_name_detail_code' => $detailUniqCode,
                    'desc'                      => $detail['desc'],
                    'attributes'                => json_encode($detail['attributes'], JSON_UNESCAPED_UNICODE),
                ];
            }

            $actionType = self::LOG_TYPE_ADD;
            foreach ($data as $key => $value) {
                if (!isset(self::LOG_COLUMNS[$key])) {
                    continue;
                }

                $map = self::LOG_COLUMNS[$key];
                $logList[] = [
                    'tenant_id'          => $data['tenant_id'],
                    'business_name_code' => $data['business_name_code'],
                    'action_type'        => $actionType,
                    'remark'             => self::LOG_MAP[$actionType] ?? '',
                    'column_name'        => $key,
                    'before_change'      => json_encode(['value' => '', 'transform' => ''], JSON_UNESCAPED_UNICODE),
                    'after_change'       => json_encode(['value' => $value, 'transform' => $map[$value] ?? ''], JSON_UNESCAPED_UNICODE),
                    'operator_uniq_code' => $params['user_code'],
                ];
            }
            if ($detailList && isset(self::LOG_COLUMNS['detail_list'])) {
                $logList[] = [
                    'tenant_id'          => $data['tenant_id'],
                    'business_name_code' => $data['business_name_code'],
                    'action_type'        => $actionType,
                    'remark'             => self::LOG_MAP[$actionType] ?? '',
                    'column_name'        => 'detail_list',
                    'before_change'      => json_encode(['value' => [], 'transform' => []], JSON_UNESCAPED_UNICODE),
                    'after_change'       => json_encode(['value' => $detailList, 'transform' => []], JSON_UNESCAPED_UNICODE),
                    'operator_uniq_code' => $params['user_code'],
                ];
            }
        } catch (\Throwable $t) {
            $this->errorLog($t, 'BusinessName新增 数据组装', __METHOD__, self::LOG_NAME);
            throw $t;
        }

        try {
            DB::beginTransaction();

            if ($data) {
                $this->businessNameRe->add($data);
            }
            if ($detailList) {
                $this->businessNameRe->addDetail($detailList);
            }

            if ($logList) {
                $this->businessNameRe->addLog($logList);
            }

            DB::commit();
        } catch (\Throwable $t) {
            DB::rollBack();
            $this->errorLog($t, 'BusinessName新增 事务提交', __METHOD__, self::LOG_NAME);
            throw $t;
        }

        return [
            'status' => '成功'
        ];
    }

    public function edit($params)
    {
        $data = [];
        $detailList = [];
        $logList = [];

        try {
            $oldData = $this->businessNameRe->getByUniqCode($params['tenant_id'], $params['uniq_code']);
            $data = array_merge($oldData, [
                'type'                 => $params['type'],
                'status'               => $params['status'],
                'percent'              => $params['percent'],
                'business_name'        => $params['business_name'],
                'color'                => $params['color'],
                'updated_by_uniq_code' => $params['user_code'],
            ]);

            $snowObj = new Snowflake(mt_rand(0, 31), getmypid());
            foreach ($params['details'] as $detail) {
                //  【注意】
                $detailUniqCode = $snowObj->id();
                $detailList[] = [
                    'tenant_id'                 => $params['tenant_id'],
                    'business_name_code'        => $data['business_name_code'],
                    'business_name_detail_code' => $detailUniqCode,
                    'desc'                      => $detail['desc'],
                    'attributes'                => json_encode($detail['attributes'], JSON_UNESCAPED_UNICODE),
                ];
            }

            $actionType = self::LOG_TYPE_EDIT;
            foreach ($data as $key => $value) {
                if (!isset(self::LOG_COLUMNS[$key])) {
                    continue;
                }

                $map = self::LOG_COLUMNS[$key];
                $logList[] = [
                    'tenant_id'          => $data['tenant_id'],
                    'business_name_code' => $data['business_name_code'],
                    'action_type'        => $actionType,
                    'remark'             => self::LOG_MAP[$actionType] ?? '',
                    'column_name'        => $key,
                    'before_change'      => json_encode(['value' => $data[$key], 'transform' => ''], JSON_UNESCAPED_UNICODE),
                    'after_change'       => json_encode(['value' => $value, 'transform' => $map[$value] ?? ''], JSON_UNESCAPED_UNICODE),
                    'operator_uniq_code' => $params['user_code'],
                ];
            }

            $oldDetailList = $this->businessNameRe->getDetailList($params);
            if ($detailList && isset(self::LOG_COLUMNS['detail_list'])) {
                $logList[] = [
                    'tenant_id'          => $data['tenant_id'],
                    'business_name_code' => $data['business_name_code'],
                    'action_type'        => $actionType,
                    'remark'             => self::LOG_MAP[$actionType] ?? '',
                    'column_name'        => 'detail_list',
                    'before_change'      => json_encode(['value' => $oldDetailList, 'transform' => []], JSON_UNESCAPED_UNICODE),
                    'after_change'       => json_encode(['value' => $detailList, 'transform' => []], JSON_UNESCAPED_UNICODE),
                    'operator_uniq_code' => $params['user_code'],
                ];
            }
        } catch (\Throwable $t) {
            $this->errorLog($t, 'BusinessName新增 数据组装', __METHOD__, self::LOG_NAME);
            throw $t;
        }

        try {
            DB::beginTransaction();

            if ($data) {
                $this->businessNameRe->updateByUniqData($params['user'], ['uniq_code' => $data['business_name_code']], $data);
            }
            if ($detailList) {
                $this->businessNameRe->addDetail($detailList);
            }

            if ($logList) {
                $this->businessNameRe->addLog($logList);
            }

            DB::commit();
        } catch (\Throwable $t) {
            DB::rollBack();
            $this->errorLog($t, 'BusinessName新增 事务提交', __METHOD__, self::LOG_NAME);
            throw $t;
        }

        return [
            'status' => '成功'
        ];
    }

    /**
     * @desc 获取详情
     * @param array $params
     * @return mixed
     * @author wxy
     * @ctime 2023/2/14 16:17
     */
    public function getDetail(array $params)
    {
        try {
            $fields = ['business_name_code', 'type',  'status',  'percent',  'business_name',  'color',  'created_by_uniq_code', 'updated_by_uniq_code', 'created_at',  'updated_at'];

            $data = $this->businessNameRe->getByUniqCode($params['tenant_id'], $params['uniq_code'], $fields);
            $data = $this->formatDataList([$data]);
            $data = current($data);

            unset($params['page'], $params['page_size']);
            $data['detail_list'] = $this->businessNameRe->getDetailList($params);
            $data['detail_list'] = $this->formatDetailList($data['detail_list']);

            return $data;
        } catch (\Throwable $t) {
            $this->errorLog($t, 'BusinessName新增', __METHOD__, self::LOG_NAME);
        }
    }

    /**
     * @desc 列表
     * @param array $params
     * @return array
     * @throws \Exception
     * @author wxy
     * @ctime 2023/2/14 18:14
     */
    public function getList(array $params)
    {
        $params = $this->initPageSize($params);
        $result = [
            'total'     => 0,
            'list'      => [],
            'page'      => $params['page'],
            'page_size' => $params['page_size'],
        ];
        $result['total'] = $this->businessNameRe->getTotal(['tenant_id' => $params['tenant_id']], $params);
        if (empty($result['total'])) {
            return $result;
        }

        $fields = ['business_name_code', 'type',  'status',  'percent',  'business_name',  'color',  'created_by_uniq_code', 'updated_by_uniq_code', 'created_at',  'updated_at'];
        $result['list'] = $this->businessNameRe->getList(['tenant_id' => $params['tenant_id']], $params, $fields);
        $result['list'] = $this->formatDataList($result['list']);

        return $result;
    }

    public function getListFromEs($params)
    {

    }

    public function changeStatus($params)
    {

    }


    public function batchUpdate($params)
    {

    }

    /**
     * @desc 格式化列表
     * @param  array  $data
     * @return array
     * @author wxy
     * @ctime 2023/2/14 17:17
     */
    private function formatDataList(array $data)
    {
        foreach ($data as &$item) {

        }
        unset($item);

        return $data;
    }

    private function formatListFromEs($params)
    {

    }

    private function getRecord()
    {

    }

    /**
     * @desc 格式化详情
     * @param array $detailList
     * @return mixed
     * @author wxy
     * @ctime 2023/2/14 17:16
     */
    private function formatDetailList(array $detailList)
    {
        foreach ($detailList as &$detail) {
            $detail['attributes'] = json_decode($detail['attributes'], true);
        }
        unset($detail);

        return $detailList;
    }
}
