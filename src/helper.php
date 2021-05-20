<?php
if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('sdump')) {

    /**
     * 输出内容到页面
     *
     * @param mixed ...$param
     */
    function sdump(...$param)
    {
        return \Helper\DebugHelper::sdump($param);
    }
}
if (!function_exists('env')) {

    /**
     * 获取.env配置相应内容
     * @param $var
     * @param null $default
     *
     * @return array|bool|mixed|null
     */
    function env($var, $default = NULL)
    {
        return \Helper\EnvHelper::instance()->get($var, $default);
    }
}

if (!function_exists('yaconf')) {

    /**
     * 获取.yaconf配置相应内容
     * @param $var
     * @param null $default
     *
     * @return mixed
     */
    function yaconf($var, $default = NULL)
    {
        return \Helper\YaconfHelper::get($var, $default);
    }
}
