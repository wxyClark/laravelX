<?php


namespace App\Repositorys;

use App\Models\BaseModel;

/**
 * @desc    基础仓库
 *
 * @package App\Repositorys
 */
class BaseRepository
{
    /**
     * @var BaseModel
     * 子类应使用具体 Model 注入构造函数
     */
    protected $model;

    //  数据库 int类型数据默认为0  0表示异常数据
    const BOOLEAN_TRUE = 1;
    const BOOLEAN_FALSE = 2;

    const BOOLEAN_MAP = [
        1 => BaseRepository::BOOLEAN_TRUE,
        0 => BaseRepository::BOOLEAN_FALSE,
    ];


    /**
     * @desc    获取分页偏移量
     * @param $page
     * @param $page_size
     * @return float|int
     * @author  wxy
     * @ctime   2022/5/17 16:33
     */
    protected function getPaginateOffset($page, $page_size)
    {
        return ($page - 1) * $page_size;
    }

    /**
     * @desc    批量入库
     * @param int $tenant_id
     * @param array $data_list
     * @author  wxy
     * @ctime   2022/5/17 16:42
     */
    protected function insert(int $tenant_id, array $data_list)
    {
        foreach ($data_list as &$data) {
            $data['tenant_id'] = $tenant_id;
        }

        $this->model->insert($data_list);
    }


}
