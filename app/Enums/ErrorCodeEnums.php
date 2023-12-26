<?php


namespace App\Enums;


class ErrorCodeEnums
{
    const HTTP_STATUS_CODE_SEND_SUCCESS = 200;

    const HTTP_STATUS_CODE_MAP = [
        self::HTTP_STATUS_CODE_SEND_SUCCESS => '请求发送成功',
    ];

    //  不怕一万，就怕万一
    const ERROR_CODE_SUCCESS = 10000;
    //  默认错误
    const ERROR_CODE_DEFAULT = 10001;

    //  参数错误
    const ERROR_CODE_PARAMS_EMPTY = 1000;
    const ERROR_CODE_PARAMS_INVALID = 1001;
    const ERROR_CODE_PARAMS_OUT_OF_RANGE = 1002;
    const ERROR_CODE_PARAMS_PAGINATION_NOT_EXIST = 1003;
    const ERROR_CODE_PARAMS_RECORD_NOT_EXIST = 1004;
    const ERROR_CODE_PARAMS_ARR = [
        self::ERROR_CODE_PARAMS_EMPTY,
        self::ERROR_CODE_PARAMS_INVALID,
        self::ERROR_CODE_PARAMS_OUT_OF_RANGE,
        self::ERROR_CODE_PARAMS_PAGINATION_NOT_EXIST,
        self::ERROR_CODE_PARAMS_RECORD_NOT_EXIST,
    ];

    //  业务逻辑错误
    const ERROR_CODE_RECORD_OPERATION_NOT_SUPPORTED = 20001;
    const ERROR_CODE_RECORD_IS_IN_LOCK = 20002;
    const ERROR_CODE_TRANSACTION_COMMIT_FAILED = 20003;
    const ERROR_CODE_UPDATE_ROW_OUT_OF_RANGE = 20004;

    //  网络错误
    const ERROR_CODE_MYSQL_IS_GONE = 30001;
    const ERROR_CODE_REDIS_IS_GONE = 30002;
    const ERROR_CODE_MEMCACHED_IS_GONE = 30003;
    const ERROR_CODE_KAFKA_IS_GONE = 30004;

    //  接口调用错误
    const ERROR_CODE_INNER_API_TIMEOUT = 40001;
    const ERROR_CODE_INNER_API_INNER_ERROR = 40002;
    const ERROR_CODE_THIRD_PARTY_API_TIMEOUT = 40003;
    const ERROR_CODE_THIRD_PARTY_API_ERROR = 40004;

    //  服务器错误
    const ERROR_CODE_SERVER_INNER_ERROR = 50001;
    const ERROR_CODE_SERVER_LOCK = 50002;
    const ERROR_CODE_SERVER_TIMEOUT = 50003;
    const ERROR_CODE_SERVER_TRANSACTION_FAILED = 50004;
    const ERROR_CODE_SERVER_DB_ERROR = 50005;

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

        self::ERROR_CODE_INNER_API_TIMEOUT       => '内部调用超时',
        self::ERROR_CODE_INNER_API_INNER_ERROR   => '内部调用报错',
        self::ERROR_CODE_THIRD_PARTY_API_TIMEOUT => '第三方接口超时',
        self::ERROR_CODE_THIRD_PARTY_API_ERROR   => '第三方接口报错',

        self::ERROR_CODE_SERVER_INNER_ERROR        => '服务器内部错误',
        self::ERROR_CODE_SERVER_LOCK               => '服务器锁错误',
        self::ERROR_CODE_SERVER_TIMEOUT            => '服务器超时',
        self::ERROR_CODE_SERVER_TRANSACTION_FAILED => '服务器事务提交失败',
        self::ERROR_CODE_SERVER_DB_ERROR           => '服务器数据库错误',
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
