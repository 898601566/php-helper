<?php
if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
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
     * @param mixed ...$param
     */
    function sdump(...$param)
    {
        return \Helper\DebugHelper::sdump($param);
    }
}
