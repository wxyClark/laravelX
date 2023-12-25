<?php


namespace App\Repositorys\Demo;


use App\Models\Demo\Photo;
use App\Repositorys\BaseRepository;

class PhotoRepository extends BaseRepository
{
    /** @var Photo */
    protected $model;

    public function __construct
    (
        Photo $model,
    )
    {
        $this->model = $model;
    }

    /**
     * @desc    联表查询走ES
     * @param int $tenant_id
     * @param array $search_params
     * @author  wxy
     * @ctime   2022/5/17 16:50
     */
    public function getListFromEs(int $tenant_id, array $search_params)
    {

    }

    /**
     * @desc    单表查询
     * @param int $tenant_id
     * @param array $search_params
     * @param array|string[] $fields
     * @return array
     * @author  wxy
     * @ctime   2022/5/17 16:38
     */
    public function getList(int $tenant_id, array $search_params, array $fields = ['*'])
    {
        $query = $this->condition($tenant_id, $search_params);

        //  排序
        $sortOrder = [
            //  排序列 => 排序规则(ASC、DESC、asc、desc)
            'sort_column' => 'order_type',
            //  默认id 正序, 确保排序幂等性
            'id' => 'ASC',
        ];
        foreach ($sortOrder as $sort_column => $sort_type) {
            $query->sortOrder($sort_column, $sort_type);
        }

        //  分页
        if (!empty($search_params['page']) && !empty($search_params['page_size'])) {
            $query->offset($this->getPaginateOffset($search_params['page'], $search_params['page_size']))->limit($search_params['page_size']);
        }

        $list = $this->select($fields)->get();

        //  TODO 返回数组好还是返回集合好？
        return $list ? $list->toArray() : [];
    }

    /**
     * @desc    单表通用查询条件
     * @param int $tenant_id
     * @param array $search_params
     * @return mixed
     * @author  wxy
     * @ctime   2022/5/17 16:37
     */
    private function condition(int $tenant_id, array $search_params)
    {
        $query = $this->model->where('tenant_id', $tenant_id);

        //  查询条件
        if (!empty($search_params['column_name'])) {
            $query->where('column_name', trim($search_params['column_name']));
        }

        //  查询条件
        if (!empty($search_params['column_values'])) {
            $values = array_unique(array_values($search_params['column_values']));
            $query->whereIn('column_name', $values);
        }

        return $query;
    }

    /**
     * @desc    ES 通用查询条件
     * @param int $tenant_id
     * @param array $search_params
     * @author  wxy
     * @ctime   2022/6/6 16:53
     */
    private function esCondition(int $tenant_id, array $search_params)
    {

    }
}
