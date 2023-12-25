<?php


namespace App\Helpers;

use \Throwable;

class ArrayHelper
{
    /**
     * @desc    处理异常日志信息
     * @param \Throwable $throwable
     * @param string $title 异常标题
     * @return array|string[]
     * @author  wxy
     * @ctime   2022/6/6 16:55
     */
    public static function makeLogData(Throwable $throwable, string $title = '')
    {
        if (empty($throwable)) {
            return [
                'title' => $title,
                'msg' => '不是一个Throwable对象',
            ];
        }

        return [
            'title' => $title,
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'msg'  => $throwable->getMessage(),
            'request' => $_REQUEST,
            //  TODO 获取上一层的调用入口
        ];
    }

    /**
     * @desc    日志数组转字符串
     * @param array $array
     * @return string
     * @author  wxy
     * @ctime   2022/6/6 16:56
     */
    public static function logArrayToString(array $array)
    {
        $str = '';
        if (empty($array)) {
            return $str;
        }

        foreach ($array as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
            $str .= $key . '=' . $value . ',';
        }

        return trim($str, ',');
    }

    /**
     * @desc    数组转map，指定索引列，对索引字段去空格转大写
     *          原因：mysql查询不区分大小写，且能兼容varchar末尾的空格，php数组区分大小写，不兼容字符串尾部的空格
     * @param array $array
     * @return array
     * @author  wxy
     * @ctime   2022/6/6 16:58
     */
    public static function arrayToMapWithUpperIndex(array $array): array
    {
        $map = [];

        if (empty($array)) {
            return $map;
        }

        foreach ($array as $index => $item) {
            $upperIndex = strtoupper(trim($index));
            $map[$upperIndex] = $item;
        }

        return $map;
    }

    /**
     * @desc    获取指定key的新数组
     * @param array $data
     * @param array $keyList
     * @return array
     * @author  wxy
     * @ctime   2022/6/6 16:58
     */
    public static function getArrayByKeyList(array $data, array $keyList)
    {
        $array = [];
        foreach ($keyList as $key) {
            if (isset($data[$key])) {
                $array[$key] = $data[$key];
            }
        }

        return $array;
    }


    /**
     * @desc    获取一组字符串的共同前缀
     * @param array $strArray
     * @return false|mixed|string
     * @author  wxy
     * @ctime   2022/6/6 16:59
     */
    public static function getSamePrefix(array $strArray)
    {
        if (count($strArray) == 1) {
            return current($strArray);
        }

        $strCharArray = [];
        $lengthArray = [];
        foreach ($strArray as $str) {
            $str = strtoupper($str);
            $strCharArray[] = str_split($str);
            $lengthArray[] = strlen($str);
        }

        $lengthMin = min($lengthArray);

        $prefixLength = 0;
        for ($i =0; $i< $lengthMin; $i++) {
            $tmpColumn = array_column($strCharArray, $i);
            if (count(array_unique($tmpColumn)) != 1) {
                break;
            }

            $prefixLength = $i + 1;
        }

        if ($prefixLength) {
            return substr(current($strArray), 0, $prefixLength);
        }

        return '';
    }

    /**
     * @desc    excel导入数据转数组(首行做key)
     * @param array $excelData
     * @return array
     * @author  wxy
     * @ctime   2022/6/7 12:07
     */
    public static function excelToArrayWithKey(array $excelData)
    {
        $keys = $excelData[0];
        $count = count($keys);

        $arrayData = [];
        foreach ($excelData as $key => $row) {
            if ($key == 0) {
                continue;
            }

            $item = [];
            for ($i = 0; $i < $count; $i++) {
                $item[$keys[$i]] = $row[$i];
            }

            $arrayData[] = $item;
        }

        return $arrayData;
    }

}
