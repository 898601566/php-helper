<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;


/**
 * Class NumberHelper
 * @package Helper
 */
class NumberHelper
{

    /**
     * 转化两位小数金额 (返回float)
     *
     * @param $float double 金额
     * @param $size int 保留几位小数，默认2位
     * @param $div int 除数,例如要百分比时用，默认2位
     *
     * @return double or int
     */
    public static function moneyFloat($float, $size = 2, $div = 1)
    {
        if (is_array($float)) {
            $return = [];
            foreach ($float as $key => $value) {
                $return[$key] = static::moneyFloat($value, $size, $div);
            }
            return $return;
        }
        $float = (float)$float;
        $float = $float / $div;
        $float = sprintf('%.' . $size . 'f', $float);
        return (float)$float;
    }

    /**
     * 检测是否是正整数
     *
     * @param mixed $var 字符串或数组
     *
     * @return bool
     */
    public static function isPositiveInt($var)
    {
        if (empty($var)) {
            return FALSE;
        }
        if (is_array($var)) {
            foreach ($var as $k => $v) {
                if (static::isPositiveInt($v) == FALSE) {
                    return FALSE;
                }
            }
        } else {
            if (preg_match("/^[1-9][0-9]*$/", $var)) {
                return TRUE;
            }
            return FALSE;
        }
    }

    /**
     * 隐藏手机号为****
     *
     * @param $phone
     */
    public static function hidePhone($phone)
    {
        if (!empty($phone)) {
            if (is_array($phone)) {
                foreach ($phone as $key => $value) {
                    $phone[$key] = static::hidePhone($value);
                }
                return $phone;
            } else {
                $phone = substr_replace($phone, '****', 3, 4);
            }
        }
        return $phone;
    }
}
