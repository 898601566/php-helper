<?php
/**
 * User: zhengze
 * Date: 2019/7/2
 * Time: 17:45
 */

namespace Helper;



/**
 * Class DebugHelper
 * @package common
 */
class DebugHelper
{


    public static int $printSqlLog = 1;
    public static int $printFileTrace = 0;
    public static int $exit = 1;

    public static function sdump(...$param)
    {
        static::_sdump(...$param);
    }


    /**
     * 输出多个参数内容,终止运行
     * 如果参数中有数字零,则终止运行
     * @param mixed $param
     */
    protected
    static function _sdump(...$param)
    {
        static::printBr('debug start');
        echo '<pre>';
        foreach ($param as $key => $value) {
            if (is_bool($value) || empty($value)) {
                var_dump($value);
            } else {
                print_r($value);
            }
            static::printBr('next');
        }

        if (static::$exit) {
            if (!empty(static::$printSqlLog)) {
                static::printSqlLog();
            }
            if (!empty(static::$printFileTrace)) {
                static::printFileTrace();
            }
            echo '</pre>';
            static::printBr('debug finish');
            exit();
        }
    }

    public
    static function printBr($str)
    {
        echo '<br>';
        echo str_pad($str, 50, '=', STR_PAD_BOTH);
        echo '<br>';
    }


    public
    static function printSqlLog()
    {
        $log = \think\facade\Log::getLog();
        if (!empty($log['sql'])) {
            static::printBr(__FUNCTION__);
            $sql_source = $log['sql'];
            $sql = [];
            foreach ($sql_source as $key => $value) {
                    if (strpos($value, 'SHOW') === false) {
                        $sql[] = preg_replace('/(\[.*\])([\s\S]+)(\[.*\])/', '$1$3$2', $value);
                    }
            }
            print_r($sql);
        }
    }

    public static function printFileTrace()
    {
        $file = get_included_files();
        if (!empty($file)) {
            $common_path = dirname(dirname($file[0]));
            static::printBr(__FUNCTION__);
            foreach ($file as $key => $value) {
                $value = $value . ' ( ' . number_format(filesize($value) / 1024, 2) . ' KB )';
                $info[$key] = str_replace($common_path, '', $value,);
            }
            print_r($info);
        }
    }
}
