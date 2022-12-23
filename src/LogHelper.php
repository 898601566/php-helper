<?php
/**
 * User: zhengze
 * Date: 2020/4/18
 * Time: 16:47
 */

namespace Helper;


/**
 * 记录日志
 * Class LogHelper
 * @package Helper
 */
class LogHelper
{
    protected static $log_root_path = '';
    protected static $types = [];

    /**
     * 设置日志根目录
     *
     * @param string $log_root_path 日志根目录
     * @param array $types 日志分类目录, eg["mysql","request"]
     */
    public static function setPathName(string $log_root_path, $types = []): void
    {
        static::$log_root_path = trim("/", $log_root_path) . "/";
        static::$types = $types;
        //创建根目录
        if (!is_dir(static::$log_root_path)) {
            if (!mkdir($concurrentDirectory = static::$log_root_path, 0777, TRUE) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
        //创建分类目录
        foreach ($types as $value) {
            $path = static::$log_root_path . sprintf("/%s/", $value);
            if (!is_dir($path)) {
                if (!mkdir($concurrentDirectory = $path, 0777, TRUE) && !is_dir($concurrentDirectory)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
                }
            }
        }
    }


    /**
     * 写入日志内容
     *
     * @param mixed $log string 日志内容
     * @param mixed $type string 日志分类
     *
     * @return mixed
     */
    public static function writeLog($log, $type = "")
    {
        if (empty(static::$log_root_path)) {
            throw new \RuntimeException(sprintf('Directory log_root_path was not set'));
        }
        $file_path = static::$log_root_path;
        $filename = "/" . date("Y-m-d H");
        if (!empty($type)) {
            //有设置分类
            if (in_array($type, static::$types)) {
                //分类目录已创建
                $file_path .= sprintf("/%s/", $type);
                $filename .= ".log";
            } else {
                //分类目录未创建
                $filename .= (!empty($type) ? ".$type" : "") . ".log";
            }
        } else {
            //没有设置分类
            $filename .= ".log";
        }
        $log_file = $file_path . $filename;
        $log = print_r($log, TRUE) . "\n";
        echo $log;
        file_put_contents($log_file, $log, FILE_APPEND);
        return TRUE;
    }

}
