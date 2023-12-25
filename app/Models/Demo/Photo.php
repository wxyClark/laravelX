<?php

namespace App\Models\Demo;

use App\Models\BaseModel;

/**
 * 指定目录创建Model
 * php artisan make:model Demo/Photo --migration
 */
class Photo extends BaseModel
{

    /**
     * 设置当前模型使用的数据库连接名。
     * 默认情况下，所有 Eloquent 模型使用的是应用程序配置的默认数据库连接。
     * 如果想指定在与特定模型交互时应该使用的不同连接，可以在模型上定义 $connection 属性
     *
     * @var string
     */
    protected $connection = 'mysql';   //  sqlite

    /**
     * 与模型关联的数据表.
     * 如果不指定 $table 的值,Eloquent 将假定 Model 名称 小写首字母 + s(models) 作为表名
     *
     * @var string
     */
    protected $table = 'photos';




    /**
     * Eloquent 默认有一个 integer 值的主键，Eloquent 会自动转换这个主键为一个 integer 类型，
     * 如果你的主键不是自增或者不是数字类型，你可以在你的模型上定义一个 public 属性的 $incrementing ，并将其设置为 false
     *  如：分布式id 使用 bigInt 类型
     *
     * @var bool
     */
    // public $incrementing = false;


    /**
     * 模型日期字段的存储格式。
     * 如果需要自定义模型时间戳的格式，请在模型上设置 $dateFormat 属性。
     * 以此来定义时间戳在数据库中的存储方式以及模型序列化为数组或 JSON 时的格式
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * 模型的属性默认值。
     * 默认情况下，被实例化的模型不会包含任何属性值。
     * 如果想为模型的某些属性定义默认值，可以在模型上定义一个 $attributes 属性
     *
     * @var array
     */
    protected $attributes = [
        'delayed' => false,
    ];

}
