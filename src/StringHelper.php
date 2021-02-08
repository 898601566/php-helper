<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;

/**
 * Class StringHelper
 * @package common
 */
class StringHelper
{


    /**
     * 加密密码
     * @param type $str
     * @return type
     */
    public static function encrypt_password($str)
    {
        return password_hash($str, PASSWORD_DEFAULT);
    }

    /**
     * 随机字符串
     * @param $length
     * @return string|null
     */

    public static function random_string($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[mt_rand(0, $max)];
        }

        return $str;
    }

    /**
     *  多字节的字符串替换
     * @param type $source
     * @param array $contrast
     * @param type $encoding
     * @return type
     */
    public static function mb_strtr($source, array $contrast, $encoding = "utf-8")
    {
        mb_regex_encoding($encoding);
        foreach ($contrast as $key => $value) {
            $source = mb_ereg_replace($key, $value, $source);
        }
        return $source;
    }

    /**
     * 检测是否是空字符串
     * @param mixed $var 字符串或数组
     * @return bool
     */
    public static function is_empty_string($var)
    {
        if (is_array($var)) {
            foreach ($var as $k => $v) {
                if (static::is_empty_string($v) == false) {
                    return false;
                }
            }
        } else {
            if (strlen(strval($var)) == 0) {
                return true;
            }
            return false;
        }
    }

    /**
     * cookies加密函数
     * @param string 加密后字符串
     */
    public static function encrypt($str)
    {
        $prep_code = serialize($str);
        return base64_encode($prep_code);
    }

    /**
     * cookies 解密密函数
     * @param string 解密后数组
     */
    public static function decrypt($str)
    {
        $str = base64_decode($str);
        return unserialize($str);
    }

    /**
     * 生成缓存key
     * @param $app_name
     * @param $scene
     * @param $user_flag
     * @return string
     */
    public static function generate_cache_key($app_name, $scene, $user_flag = '')
    {
        return sprintf('%s:%s:%s', $app_name, $scene, $user_flag);
    }

    /**
     * 下划线转驼峰
     * 思路:
     * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
     * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
     */
    public static function camelize($uncamelized_words,$separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }

    /**
     * 驼峰命名转下划线命名
     * 思路:
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     */
    public static function uncamelize($camelCaps,$separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
}
