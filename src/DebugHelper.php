<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;


/**
 * 调试类
 * Class DebugHelper
 * @package Helper
 */
class DebugHelper
{
    /**
     * 打印数据然后退出
     * @param ...$param
     */
    public static function sdump(...$param)
    {
        static::_sdump(...$param);
        static::end();
    }

    /**
     * 打印数据(配上加载过程)然后退出
     * @param ...$param
     */
    public static function sdumpWithTrace(...$param)
    {
        static::_sdump(...$param);
        static::printFileTrace();
        static::end();
    }

    /**
     * 输出多个参数内容,终止运行
     * 如果参数中有数字零,则终止运行
     *
     * @param mixed $param
     */
    protected static function _sdump(...$param)
    {
        static::printBr('debug start');
        echo '<pre>';
        foreach ($param as $key => $value) {
            if (is_bool($value) || empty($value)) {
                var_dump($value);
            } elseif (is_array($value)) {
                var_export($value);
            } else {
                static::println($value);
            }
            static::println('next');
        }
    }

    /**
     * 告知结束然后退出
     *
     * @param $str
     */
    protected static function end()
    {
        echo '</pre>';
        static::printBr('debug finish');
        exit();
    }

    /**
     * 命令行打印字符串
     *
     * @param $str
     */
    public static function println($str)
    {
        echo $str . "\n";
    }

    /**
     * html打印字符串
     *
     * @param $str
     */
    public static function printBr($str)
    {
        echo $str;
        echo '<br>';
    }

    /**
     * 打印文件加载过程
     */
    public static function printFileTrace()
    {
        $file = get_included_files();
        if (!empty($file)) {
            $common_path = dirname(dirname($file[0]));
            static::printBr(__FUNCTION__);
            foreach ($file as $key => $value) {
                $value = $value . ' ( ' . number_format(filesize($value) / 1024, 2) . ' KB )';
                $info[$key] = str_replace($common_path, '', $value);
            }
            print_r($info);
        }
    }
}
