<?php


namespace App\Models;

use HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    //  如果需要自定义用于存储时间戳的字段的名称，可以在模型上定义 CREATED_AT 和 UPDATED_AT 常量：
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';

    /**
     * 与数据表关联的主键. 不指定 primaryKey 时，默认为id
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 指示模型是否主动维护时间戳。
     * 默认情况下，Eloquent 期望 created_at 和 updated_at 列存在于模型对应的数据库表中。
     * 创建或更新模型时，Eloquent 会自动设置这些列的值。
     * 如果您不希望 Eloquent 自动管理这些列，您应该在模型上定义一个 $timestamps 属性，其值为 false
     *
     * @var bool
     */
    public $timestamps = false;

}
