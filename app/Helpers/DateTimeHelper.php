<?php


namespace App\Helpers;


class DateTimeHelper
{
    const ONE_DAY = 86400;
    const TWO_DAY = 86400 * 2;
    const THREE_DAY = 86400 * 3;

    const ONE_WEEK = 86400 * 7;
    const TWO_WEEK = 86400 * 14;

    const ONE_MONTH = 86400 * 30;

    const HALF_YEAR = 86400 * 180;

    /**
     * @desc    计算时间差
     * 和前端计算时效逻辑保持一致，精确到小时，向下取整
     * @param string $endTime
     * @param string $startTime
     * @return string
     * @author  wxy
     * @ctime   2022/7/21 17:01
     */
    public static function getTimeDiff(string $endTime, string $startTime)
    {
        if(is_numeric($endTime)){
            if(strlen($endTime)>=13){
                $end = (int)$endTime/1000;
            }else{
                $end = (int)$endTime;
            }
        }else{
            $end = strtotime($endTime);
        }

        if(is_numeric($startTime)){
            if(strlen($startTime)>=13){
                $start = (int)$startTime/1000;
            }else{
                $start = (int)$startTime;
            }
        }else{
            $start = strtotime($startTime);
        }


        $diff = floor(($end - $start) / 3600);
        $day = floor($diff / 24);
        $hour = $diff % 24;

        $diffStr = '';
        if ($day > 0) {
            $diffStr .= $day.'天';
        }

        $diffStr .= $hour.'小时';

        return $diffStr;
    }

    /**
     * @desc 获取指定日期最后一秒
     * @param string $date
     * @return false|string
     * @author wxy
     * @ctime 2022/11/26 19:02
     */
    public static function getDateEnd(string $date)
    {
        return date('Y-m-d 23:59:59', strtotime($date));
    }

    /**
     * @desc 获取指定日期开始时间
     * @param string $date
     * @return false|string
     * @author wxy
     * @ctime 2023/1/4 17:13
     */
    public static function getDateStart(string $date)
    {
        return date('Y-m-d 00:00:00', strtotime($date));
    }

    /**
     * @desc 处理时间为默认值，表示没有处理 不展示处理时间
     * @param  string  $time
     * @return string
     * @author wxy
     * @ctime 2023/2/25 11:51
     */
    public static function getHandleTime(string $time)
    {
        return $time == DateTimeEnums::DEFAULT_DATETIME ? '' : $time;
    }
}
