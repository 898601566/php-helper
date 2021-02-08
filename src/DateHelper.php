<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;



/**
 * Class DateHelper
 * @package common
 */
class DateHelper
{


    /**
     * 日期(时间戳)比较,$date1>$date2 返回true
     * @param string $date1
     * @param string $date2
     * @return string
     */
    public static function date_compare($date1, $date2)
    {
        $time1 = static::to_time($date1);
        $time2 = static::to_time($date2);
        return $time1 > $time2;
    }

    /**
     * 返回关于$date所在年的始止数组
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     * @return string
     */
    public static function year_cycle($date, $ret_date = 1)
    {
        list($y, $m, $d) = explode('-', static::to_date($date, 'Y-m-d'));
        $year_begin_time = mktime(0, 0, 0, 1, 1, $y);
        $year_end_time = mktime(0, 0, 0, 1, 1, $y + 1) - 1;
        $time_cycle = [
            $year_begin_time, $year_end_time
        ];
        return $ret_date == 1 ? static::to_date($time_cycle) : $time_cycle;
    }

    /**
     * 返回关于$date所在月的始止数组
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     * @return string
     */
    public static function month_cycle($date, $ret_date = 1)
    {
        list($y, $m, $d) = explode('-', static::to_date($date, 'Y-m-d'));
        $month_begin_time = mktime(0, 0, 0, $m, 1, $y);
        $month_end_time = mktime(0, 0, 0, $m + 1, 1, $y) - 1;
        $time_cycle = [
            $month_begin_time, $month_end_time
        ];
        return $ret_date == 1 ? static::to_date($time_cycle) : $time_cycle;
    }

    /**
     * 返回关于$date所在周的始止数组
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     * @return string
     */
    public static function week_cycle($date, $ret_date = 1)
    {
        list($y, $m, $d, $w) = explode('-', static::to_date($date, 'Y-m-d-w'));
        if ($w == 0) $w = 7; //修正周日的问题
        $week_start_time = mktime(0, 0, 0, $m, $d - $w + 1, $y);
        $week_end_time = mktime(23, 59, 59, $m, $d - $w + 7, $y);
        $time_cycle = [
            $week_start_time, $week_end_time
        ];
        return $ret_date == 1 ? static::to_date($time_cycle) : $time_cycle;
    }

    /**
     * 返回关于$date所在日的始止数组
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     * @return string
     */
    public static function day_cycle($date, $ret_date = 1)
    {
        list($y, $m, $d) = explode('-', static::to_date($date, 'Y-m-d'));
        $day_begin_time = mktime(0, 0, 0, $m, $d, $y);
        $day_end_time = mktime(0, 0, 0, $m, $d + 1, $y) - 1;
        $time_cycle = [
            $day_begin_time, $day_end_time
        ];
        return $ret_date == 1 ? static::to_date($time_cycle) : $time_cycle;
    }

    /**
     * 返回关于$date所在季度的始止数组
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     * @return string
     */
    public static function quarter_cycle($date, $ret_date = 1)
    {
        list($y, $m, $d) = explode('-', static::to_date($date, 'Y-m-d'));
        switch (true) {
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
            $quarter_begin_time, $quarter_end_time
        ];
        return $ret_date == 1 ? static::to_date($time_cycle) : $time_cycle;
    }

    /**
     * 返回关于$date所在上个季度的始止数组
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     * @return string
     */
    public static function last_quarter_cycle($date, $ret_date = 1)
    {
        $time_cycle = static::quarter_cycle($date);
        $time_cycle = [
            static::to_date(static::to_time($time_cycle[0], "-3 months"), "Y-n-j 00:00:00"),
            static::to_date(static::to_time($time_cycle[1], "-3 months"), "Y-n-j 23:59:59"),
        ];
        return $ret_date == 1 ? static::to_date($time_cycle) : $time_cycle;
    }

    /**
     * 返回关于$date去年的数组
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     * @return string
     */
    public static function last_year_cycle($date, $ret_date = 1)
    {
        list($y, $m, $d) = explode('-', static::to_date($date, 'Y-m-d'));
        $year_begin_time = mktime(0, 0, 0, 1, 1, $y - 1);
        $year_end_time = mktime(0, 0, 0, 1, 1, $y) - 1;
        $time_cycle = [
            $year_begin_time, $year_end_time
        ];
        return $ret_date == 1 ? static::to_date($time_cycle) : $time_cycle;
    }

    /**
     * 返回关于$date上月的数组
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     * @return string
     */
    public static function last_month_cycle($date, $ret_date = 1)
    {
        list($y, $m, $d) = explode('-', static::to_date($date, 'Y-m-d'));
        $last_month_begin_time = mktime(0, 0, 0, $m - 1, 1, $y);
        $last_month_end_time = mktime(0, 0, 0, $m, 1, $y) - 1;
        $time_cycle = [
            $last_month_begin_time, $last_month_end_time
        ];
        return $ret_date == 1 ? static::to_date($time_cycle) : $time_cycle;
    }

    /**
     * 返回关于$date上周的数组
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     * @return string
     */
    public static function last_week_cycle($date, $ret_date = 1)
    {
        $time_cycle = static::week_cycle($date);
        $time_cycle = [
            static::to_date(static::to_time($time_cycle[0], "-7 days"), "Y-n-j 00:00:00"),
            static::to_date(static::to_time($time_cycle[1], "-7 days"), "Y-n-j 23:59:59"),
        ];
        return $ret_date == 1 ? $time_cycle : static::to_time($time_cycle);
    }

    /**
     * 返回关于$date所在日昨天的始止数组
     * @param string $date
     * @param int $ret_date //1是日期,2是时间戳
     * @return string
     */
    public static function last_day_cycle($date, $ret_date = 1)
    {
        list($y, $m, $d) = explode('-', static::to_date($date, 'Y-m-d'));
        $day_begin_time = mktime(0, 0, 0, $m, $d - 1, $y);
        $day_end_time = mktime(0, 0, 0, $m, $d, $y) - 1;
        $time_cycle = [
            $day_begin_time, $day_end_time
        ];
        return $ret_date == 1 ? static::to_date($time_cycle) : $time_cycle;
    }

    /**
     * 转换为日期
     * @param string $source 时间戳或者日期,必须完整,如年月日,年月日时分秒(数组可以批量转换)
     * @param string $format ,格式字符串
     * @return mixed
     */
    public static function to_date($source = '', $format = "Y-m-d H:i:s")
    {
        if (empty($source)) {
            $source = time();
        }
        if (is_array($source)) {
            foreach ($source as $key => $value) {
                $source[$key] = static::to_date($value, $format);
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
                $source = strtotime($source) ? strtotime($source) : null;
            }
            return date($format, $source);
        }
        return $source;
    }

    /**
     * 转换为时间戳(支持数组)
     * @param string $source 日期
     * @param string $condition
     * @param string $now
     * @return string
     */
    public static function to_time($source, $condition = "", $now = null)
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
            $source = static::to_date($source);
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
            $source = static::to_date($source);
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
        list($y, $m, $d) = explode('-', date('Y-m-d'));
        return [
            mktime(0, 0, 0, $m, $d, $y),
            mktime(23, 59, 59, $m, $d, $y)
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
            mktime(23, 59, 59, date('m'), $yesterday, date('Y'))
        ];
    }

    /**
     * 返回本周开始和结束的时间戳
     *
     * @return array
     */
    public static function week()
    {
        list($y, $m, $d, $w) = explode('-', date('Y-m-d-w'));
        if ($w == 0) $w = 7; //修正周日的问题
        return [
            mktime(0, 0, 0, $m, $d - $w + 1, $y), mktime(23, 59, 59, $m, $d - $w + 7, $y)
        ];
    }

    /**
     * 返回上周开始和结束的时间戳
     *
     * @return array
     */
    public static function lastWeek()
    {
        $timestamp = time();
        return [
            strtotime(date('Y-m-d', strtotime("last week Monday", $timestamp))),
            strtotime(date('Y-m-d', strtotime("last week Sunday", $timestamp))) + 24 * 3600 - 1
        ];
    }

    /**
     * 返回本月开始和结束的时间戳
     *
     * @return array
     */
    public static function month($everyDay = false)
    {
        list($y, $m, $t) = explode('-', date('Y-m-t'));
        return [
            mktime(0, 0, 0, $m, 1, $y),
            mktime(23, 59, 59, $m, $t, $y)
        ];
    }

    /**
     * 返回上个月开始和结束的时间戳
     *
     * @return array
     */
    public static function lastMonth()
    {
        $y = date('Y');
        $m = date('m');
        $begin = mktime(0, 0, 0, $m - 1, 1, $y);
        $end = mktime(23, 59, 59, $m - 1, date('t', $begin), $y);

        return [$begin, $end];
    }

    /**
     * 返回今年开始和结束的时间戳
     *
     * @return array
     */
    public static function year()
    {
        $y = date('Y');
        return [
            mktime(0, 0, 0, 1, 1, $y),
            mktime(23, 59, 59, 12, 31, $y)
        ];
    }

    /**
     * 返回去年开始和结束的时间戳
     *
     * @return array
     */
    public static function lastYear()
    {
        $year = date('Y') - 1;
        return [
            mktime(0, 0, 0, 1, 1, $year),
            mktime(23, 59, 59, 12, 31, $year)
        ];
    }

    public static function dayOf()
    {

    }

    /**
     * 获取几天前零点到现在/昨日结束的时间戳
     *
     * @param int $day 天数
     * @param bool $now 返回现在或者昨天结束时间戳
     * @return array
     */
    public static function dayToNow($day = 1, $now = true)
    {
        $end = time();
        if (!$now) {
            list($foo, $end) = self::yesterday();
        }

        return [
            mktime(0, 0, 0, date('m'), date('d') - $day, date('Y')),
            $end
        ];
    }

    /**
     * 返回几天前的时间戳
     *
     * @param int $day
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
     * @return int
     */
    public static function weekToSecond($week = 1)
    {
        return self::daysToSecond() * 7 * $week;
    }

}
