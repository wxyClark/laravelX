<?php


namespace App\Enums;


class ErrorCodeEnums
{
    const HTTP_STATUS_CODE_SEND_SUCCESS = 200;

    const HTTP_STATUS_CODE_MAP = [
        self::HTTP_STATUS_CODE_SEND_SUCCESS => '请求发送成功',
    ];

    //  默认错误
    const ERROR_CODE_DEFAULT = 999999;

    //  参数错误
    const ERROR_CODE_PARAMS_EMPTY = 100000;
    const ERROR_CODE_PARAMS_INVALID = 100001;
    const ERROR_CODE_PARAMS_OUT_OF_RANGE = 100002;
    const ERROR_CODE_PARAMS_PAGINATION_NOT_EXIST = 100003;
    const ERROR_CODE_PARAMS_RECORD_NOT_EXIST = 100004;
    const ERROR_CODE_PARAMS_ARR = [
        self::ERROR_CODE_PARAMS_EMPTY,
        self::ERROR_CODE_PARAMS_INVALID,
        self::ERROR_CODE_PARAMS_OUT_OF_RANGE,
        self::ERROR_CODE_PARAMS_PAGINATION_NOT_EXIST,
        self::ERROR_CODE_PARAMS_RECORD_NOT_EXIST,
    ];

    //  业务逻辑错误
    const ERROR_CODE_RECORD_OPERATION_NOT_SUPPORTED = 200001;
    const ERROR_CODE_RECORD_IS_IN_LOCK = 200002;
    const ERROR_CODE_TRANSACTION_COMMIT_FAILED = 200003;
    const ERROR_CODE_UPDATE_ROW_OUT_OF_RANGE = 200004;

    //  网络错误
    const ERROR_CODE_MYSQL_IS_GONE = 300001;
    const ERROR_CODE_REDIS_IS_GONE = 300002;
    const ERROR_CODE_MEMCACHED_IS_GONE = 300003;
    const ERROR_CODE_KAFKA_IS_GONE = 300004;

    //  内部调用错误
    const ERROR_CODE_INNER_API_OUT_OF_TIME = 400001;
    const ERROR_CODE_INNER_API_INNER_ERROR = 400002;
    const ERROR_CODE_THIRD_PARTY_API_OUT_OF_TIME = 400003;
    const ERROR_CODE_THIRD_PARTY_API_ERROR = 400004;

    const ERROR_CODE_MAP = [
        self::ERROR_CODE_DEFAULT                 => '成功',

        self::ERROR_CODE_PARAMS_EMPTY                => '参数为空',
        self::ERROR_CODE_PARAMS_INVALID              => '参数校验失败',
        self::ERROR_CODE_PARAMS_OUT_OF_RANGE         => '参数超出有效范围',
        self::ERROR_CODE_PARAMS_PAGINATION_NOT_EXIST => '未指定分页参数',
        self::ERROR_CODE_PARAMS_RECORD_NOT_EXIST     => '参数指向数据不存在',

        self::ERROR_CODE_RECORD_OPERATION_NOT_SUPPORTED => '指定数据不支持当前操作',
        self::ERROR_CODE_RECORD_IS_IN_LOCK              => '指定数据被锁，不可操作',
        self::ERROR_CODE_TRANSACTION_COMMIT_FAILED      => '事务提交失败',
        self::ERROR_CODE_UPDATE_ROW_OUT_OF_RANGE        => '更新数据行数超出上限',

        self::ERROR_CODE_MYSQL_IS_GONE     => 'MySQL连接失败',
        self::ERROR_CODE_REDIS_IS_GONE     => 'Redis连接失败',
        self::ERROR_CODE_MEMCACHED_IS_GONE => 'Memcached连接失败',
        self::ERROR_CODE_KAFKA_IS_GONE     => 'Kafka连接失败',

        self::ERROR_CODE_INNER_API_OUT_OF_TIME       => '内部调用超时',
        self::ERROR_CODE_INNER_API_INNER_ERROR       => '内部调用报错',
        self::ERROR_CODE_THIRD_PARTY_API_OUT_OF_TIME => '第三方接口超时',
        self::ERROR_CODE_THIRD_PARTY_API_ERROR       => '第三方接口报错',
    ];

    /**
     * @desc 获取错误类型定义
     * @param  int  $code
     * @return string
     */
    public static function getCodeDefinition(int $code)
    {
        return self::ERROR_CODE_MAP[$code] ?? '未知错误($code=)'.$code;
    }
}
