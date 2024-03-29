<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;


/**
 * 日期时间类
 * Class DateHelper
 * @package Helper
 */
class DateHelper
{


    /**
     * 日期(时间戳)比较,$date1>$date2 返回true
     *
     * @param string $date1
     * @param string $date2
     *
     * @return string
     */
    public static function dateCompare($date1, $date2)
    {
        $time1 = static::toTime($date1);
        $time2 = static::toTime($date2);
        return $time1 > $time2;
    }

    /**
     * 返回某年的开始和结束
     *
     * @param string $date
     * @param int $ret_date //1返回日期,2返回时间戳
     *
     * @return string
     */
    public static function year($date, $ret_date = 1)
    {
        [$y, $m, $d] = explode('-', static::toDate($date, 'Y-m-d'));
        $year_begin_time = mktime(0, 0, 0, 1, 1, $y);
        $year_end_time = mktime(0, 0, 0, 1, 1, $y + 1) - 1;
        $time_cycle = [
            $year_begin_time, $year_end_time,
        ];
        return $ret_date == 1 ? static::toDate($time_cycle) : $time_cycle;
    }

    /**
     * 返回某年去年的数组
     *
     * @param string $date
     * @param int $ret_date //1返回日期,2返回时间戳
     *
     * @return string
     */
    public static function lastYear($date, $ret_date = 1)
    {
        [$y, $m, $d] = explode('-', static::toDate($date, 'Y-m-d'));
        $year_begin_time = mktime(0, 0, 0, 1, 1, $y - 1);
        $year_end_time = mktime(0, 0, 0, 1, 1, $y) - 1;
        $time_cycle = [
            $year_begin_time, $year_end_time,
        ];
        return $ret_date == 1 ? static::toDate($time_cycle) : $time_cycle;
    }

    /**
     * 返回某月的开始和结束
     *
     * @param string $date
     * @param int $ret_date //1返回日期,2返回时间戳
     *
     * @return string
     */
    public static function month($date, $ret_date = 1)
    {
        [$y, $m, $d] = explode('-', static::toDate($date, 'Y-m-d'));
        $month_begin_time = mktime(0, 0, 0, $m, 1, $y);
        $month_end_time = mktime(0, 0, 0, $m + 1, 1, $y) - 1;
        $time_cycle = [
            $month_begin_time, $month_end_time,
        ];
        return $ret_date == 1 ? static::toDate($time_cycle) : $time_cycle;
    }

    /**
     * 返回某月上月的开始和结束
     *
     * @param string $date
     * @param int $ret_date //1返回日期,2返回时间戳
     *
     * @return string
     */
    public static function lastMonth($date, $ret_date = 1)
    {
        [$y, $m, $d] = explode('-', static::toDate($date, 'Y-m-d'));
        $last_month_begin_time = mktime(0, 0, 0, $m - 1, 1, $y);
        $last_month_end_time = mktime(0, 0, 0, $m, 1, $y) - 1;
        $time_cycle = [
            $last_month_begin_time, $last_month_end_time,
        ];
        return $ret_date == 1 ? static::toDate($time_cycle) : $time_cycle;
    }

    /**
     * 返回某周的开始和结束
     *
     * @param string $date
     * @param int $ret_date //1返回日期,2返回时间戳
     *
     * @return string
     */
    public static function week($date, $ret_date = 1)
    {
        [$y, $m, $d, $w] = explode('-', static::toDate($date, 'Y-m-d-w'));
        if ($w == 0) {
            $w = 7;
        } //修正周日的问题
        $week_start_time = mktime(0, 0, 0, $m, $d - $w + 1, $y);
        $week_end_time = mktime(23, 59, 59, $m, $d - $w + 7, $y);
        $time_cycle = [
            $week_start_time, $week_end_time,
        ];
        return $ret_date == 1 ? static::toDate($time_cycle) : $time_cycle;
    }

    /**
     * 返回某周上周的开始和结束
     *
     * @param string $date
     * @param int $ret_date //1返回日期,2返回时间戳
     *
     * @return string
     */
    public static function lastWeek($date, $ret_date = 1)
    {
        $time_cycle = static::week($date);
        $time_cycle = [
            static::toDate(static::toTime($time_cycle[0], "-7 days"), "Y-n-j 00:00:00"),
            static::toDate(static::toTime($time_cycle[1], "-7 days"), "Y-n-j 23:59:59"),
        ];
        return $ret_date == 1 ? $time_cycle : static::toTime($time_cycle);
    }

    /**
     * 返回某天的开始和结束
     *
     * @param string $date
     * @param int $ret_date //1返回日期,2返回时间戳
     *
     * @return string
     */
    public static function day($date, $ret_date = 1)
    {
        [$y, $m, $d] = explode('-', static::toDate($date, 'Y-m-d'));
        $day_begin_time = mktime(0, 0, 0, $m, $d, $y);
        $day_end_time = mktime(0, 0, 0, $m, $d + 1, $y) - 1;
        $time_cycle = [
            $day_begin_time, $day_end_time,
        ];
        return $ret_date == 1 ? static::toDate($time_cycle) : $time_cycle;
    }

    /**
     * 返回某天昨天的开始和结束
     *
     * @param string $date
     * @param int $ret_date //1返回日期,2返回时间戳
     *
     * @return string
     */
    public static function lastDay($date, $ret_date = 1)
    {
        [$y, $m, $d] = explode('-', static::toDate($date, 'Y-m-d'));
        $day_begin_time = mktime(0, 0, 0, $m, $d - 1, $y);
        $day_end_time = mktime(0, 0, 0, $m, $d, $y) - 1;
        $time_cycle = [
            $day_begin_time, $day_end_time,
        ];
        return $ret_date == 1 ? static::toDate($time_cycle) : $time_cycle;
    }

    /**
     * 返回关于$date所在季度的始止数组
     *
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     *
     * @return string
     */
    public static function quarter($date, $ret_date = 1)
    {
        [$y, $m, $d] = explode('-', static::toDate($date, 'Y-m-d'));
        switch (TRUE) {
            case in_array($m, [1, 2, 3]):
                $m = 1;
                break;
            case in_array($m, [4, 5, 6]):
                $m = 4;
                break;
            case in_array($m, [7, 8, 9]):
                $m = 7;
                break;
            case in_array($m, [10, 11, 12]):
                $m = 10;
                break;
        }
        $quarter_begin_time = mktime(0, 0, 0, $m, 1, $y);
        $quarter_end_time = mktime(0, 0, 0, $m + 3, 1, $y) - 1;
        $time_cycle = [
            $quarter_begin_time, $quarter_end_time,
        ];
        return $ret_date == 1 ? static::toDate($time_cycle) : $time_cycle;
    }

    /**
     * 返回关于$date所在上个季度的始止数组
     *
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     *
     * @return string
     */
    public static function lastQuarter($date, $ret_date = 1)
    {
        $time_cycle = static::quarter($date);
        $time_cycle = [
            static::toDate(static::toTime($time_cycle[0], "-3 months"), "Y-n-j 00:00:00"),
            static::toDate(static::toTime($time_cycle[1], "-3 months"), "Y-n-j 23:59:59"),
        ];
        return $ret_date == 1 ? static::toDate($time_cycle) : $time_cycle;
    }

    /**
     * 转换为日期
     *
     * @param string $source 时间戳或者日期,必须完整,如年月日,年月日时分秒(数组可以批量转换)
     * @param string $format ,格式字符串
     *
     * @return mixed
     */
    public static function toDate($source = '', $format = "Y-m-d H:i:s")
    {
        if (empty($source)) {
            $source = time();
        }
        if (is_array($source)) {
            foreach ($source as $key => $value) {
                $source[$key] = static::toDate($value, $format);
            }
        } else {
            if (strlen($source) <= 4) {
                $source .= "年";
            }
            //不是时间戳
            if (!is_numeric($source)) {
                $contrast = [
                    "年|月" => '-',
                    "日|(日\s)" => ' ',
                    "时|分" => ':',
                    "秒" => '',
                    "\-$" => '-1',
                    ":$" => ':0',
                ];
                mb_regex_encoding("utf-8");
                foreach ($contrast as $key => $value) {
                    $source = mb_ereg_replace($key, $value, $source);
                }
                $source = strtotime($source) ? strtotime($source) : NULL;
            }
            return date($format, $source);
        }
        return $source;
    }

    /**
     * 转换为时间戳(支持数组)
     *
     * @param string $source 日期
     * @param string $condition
     * @param string $now
     *
     * @return string
     */
    public static function toTime($source, $condition = "", $now = NULL)
    {
        if (is_numeric($source) || empty($source)) {
            return $source;
        }
        //添加空格
        if (!empty($condition)) {
            $condition = " " . $condition;
        }
        if (is_array($source)) {
            if (!empty($source[1]) && is_array($source[1])) {
                //between 格式
                $resoruce = $source;
                $source = $source[1];
            }
            $source = static::toDate($source);
            foreach ($source as $key => $value) {
                if (!empty($now)) {
                    $source[$key] = strtotime($value . "$condition", $now);
                } else {
                    $source[$key] = strtotime($value . "$condition");
                }
            }
            if (!empty($resoruce[1]) && is_array($resoruce[1])) {
                //between 格式
                $resoruce[1] = $source;
                $source = $resoruce;
            }
        } else {
            $source = static::toDate($source);
            if (!empty($now)) {
                $source = strtotime($source . "$condition", $now);
            } else {
                $source = strtotime($source . "$condition");
            }
            return $source;
        }
        return $source;
    }

    /**
     * 返回今日开始和结束的时间戳
     *
     * @return array
     */
    public static function today()
    {
        [$y, $m, $d] = explode('-', date('Y-m-d'));
        return [
            mktime(0, 0, 0, $m, $d, $y),
            mktime(23, 59, 59, $m, $d, $y),
        ];
    }

    /**
     * 返回昨日开始和结束的时间戳
     *
     * @return array
     */
    public static function yesterday()
    {
        $yesterday = date('d') - 1;
        return [
            mktime(0, 0, 0, date('m'), $yesterday, date('Y')),
            mktime(23, 59, 59, date('m'), $yesterday, date('Y')),
        ];
    }

    /**
     * 获取几天前零点到现在/昨日结束的时间戳
     *
     * @param int $day 天数
     * @param bool $now 返回现在或者昨天结束时间戳
     *
     * @return array
     */
    public static function dayToNow($day = 1, $now = TRUE)
    {
        $end = time();
        if (!$now) {
            [$foo, $end] = self::yesterday();
        }

        return [
            mktime(0, 0, 0, date('m'), date('d') - $day, date('Y')),
            $end,
        ];
    }

    /**
     * 返回几天前的时间戳
     *
     * @param int $day
     *
     * @return int
     */
    public static function daysAgo($day = 1)
    {
        $nowTime = time();
        return $nowTime - self::daysToSecond($day);
    }

    /**
     * 返回几天后的时间戳
     *
     * @param int $day
     *
     * @return int
     */
    public static function daysAfter($day = 1)
    {
        $nowTime = time();
        return $nowTime + self::daysToSecond($day);
    }

    /**
     * 天数转换成秒数
     *
     * @param int $day
     *
     * @return int
     */
    public static function daysToSecond($day = 1)
    {
        return $day * 86400;
    }

    /**
     * 周数转换成秒数
     *
     * @param int $week
     *
     * @return int
     */
    public static function weekToSecond($week = 1)
    {
        return self::daysToSecond() * 7 * $week;
    }

    /**
     * 求两个时间段是否有交集,有返回TURE没有返回FALSE,参数有误返回NULL
     *
     * @param numeric $time1_start 时间段A的开始
     * @param numeric $time1_end 时间段A的结束
     * @param numeric $time2_start 时间段B的开始
     * @param numeric $time2_end 时间段B的结束
     *
     * @return string
     */
    public static function timeQuantumCompare($time1_start, $time1_end, $time2_start, $time2_end)
    {
        $ret = FALSE;
        $use_case = 0;
        if ($time1_start <= $time1_end && $time2_start <= $time2_end === FALSE) {
            return NULL;
        }
        switch (TRUE) {
            //A1在B1左边
            case $time1_start < $time2_start && $time1_end < $time2_start:
                //A2远离B1
                $ret = FALSE;
                $use_case = 1;
                break;
            case $time1_start < $time2_start && $time1_end === $time2_start:
                //A2贴B1
                $ret = FALSE;
                $use_case = 2;
                break;
            case $time1_start < $time2_start && $time1_end > $time2_start && $time1_end < $time2_end:
                //A2在B1和B2之间
                $ret = TRUE;
                $use_case = 3;
                break;
            case $time1_start < $time2_start && $time1_end === $time2_end:
                //A2贴B2
                $ret = TRUE;
                $use_case = 4;
                break;
            case $time1_start < $time2_start && $time1_end > $time2_end:
                //A2在B2右边
                $ret = TRUE;
                $use_case = 5;
                break;
            //A1贴B1
            case $time1_start === $time2_start && $time1_end < $time2_end:
                //A2在B1和B2之间
                $ret = TRUE;
                $use_case = 6;
                break;
            case $time1_start === $time2_start && $time1_end === $time2_end:
                //A2贴B2
                $ret = TRUE;
                $use_case = 7;
                break;
            case $time1_start === $time2_start && $time1_end > $time2_end:
                //A2在B2右边
                $ret = TRUE;
                $use_case = 8;
                break;
            //A1在B1和B2之间
            case $time1_start > $time2_start && $time1_start < $time2_end && $time1_end < $time2_end:
                //A2在B2左边
                $ret = TRUE;
                $use_case = 9;
                break;
            case $time1_start > $time2_start && $time1_start < $time2_end && $time1_end === $time2_end:
                //A2贴B2
                $ret = TRUE;
                $use_case = 10;
                break;
            case $time1_start > $time2_start && $time1_start < $time2_end && $time1_end > $time2_end:
                //A2在B2右边
                $ret = TRUE;
                $use_case = 11;
                break;
            //A1贴B2
            case $time1_start === $time2_end :
                $ret = FALSE;
                $use_case = 12;
                break;
            //A1在B2右边
            case $time1_start > $time2_end:
                $ret = FALSE;
                $use_case = 13;
                break;
            default:
                break;
        }
//        var_dump($use_case);
        return $ret;
    }

}
