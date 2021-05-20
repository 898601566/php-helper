<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;

/**
 * 请求类
 * Class RequestHelper
 * @package Helper
 */
class RequestHelper
{

    /**
     * 按照规则(默认值,部分提取)返回request数组
     *
     * @param mixed $field 数组
     * <br>['field'=>'default_value']
     * @param string default 默认null
     *
     * @return Array
     */
    public static function input($field, $default = null)
    {
        switch (TRUE) {
            case is_array($field):
                $val = [];
                if (ArrayHelper::isContinuousIndexedArray($field)) {
                    $field = array_fill_keys($field, $default);
                }
                foreach ($field as $key => $value) {
                    $val[$key] = static::input($key, $value);
                }
                return $val;
                break;
            case is_string($field):
                $val = static::request($field);
                return isset($val) ? trim($val) : $default;
                break;
            default:
                break;
        }
    }

    /**
     * 获取get数据
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function get($key = '', $default = NULL)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    /**
     * 获取post数据
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function post($key = '', $default = NULL)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    /**
     * 获取env数据
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function env($key = '', $default = NULL)
    {
        return isset($_ENV[$key]) ? $_ENV[$key] : $default;
    }

    /**
     * 获取cookie数据
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function cookie($key = '', $default = NULL)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
    }

    /**
     * 获取server数据
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function server($key = '', $default = NULL)
    {
        return isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
    }

    /**
     * 获取files数据
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function files($key = '', $default = NULL)
    {
        return isset($_FILES[$key]) ? $_FILES[$key] : $default;
    }

    /**
     * 获取request数据
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function request($key = '', $default = NULL)
    {
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
    }

    /**
     * 获取session数据
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function session($key = '', $default = NULL)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
}
