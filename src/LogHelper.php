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

    /**
     * 设置日志根目录
     *
     * @param string $log_root_path
     */
    public static function setPathName(string $log_root_path): void
    {
        static::$log_root_path = $log_root_path. "/runtime/";
        if (!is_dir(static::$log_root_path)) {
            if (!mkdir($concurrentDirectory = static::$log_root_path, 0777, TRUE) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
    }


    /**
     * 写入日志内容
     *
     * @param mixed $log
     *
     * @return mixed
     */
    public static function writeLog($log)
    {
        if (empty(static::$log_root_path)) {
            throw new \RuntimeException(sprintf('Directory log_root_path was not set'));
        }
        $log_root_path = static::$log_root_path . date("Y-m-d") . ".log";
        $log = print_r($log, TRUE)."\n";
        echo $log;
        file_put_contents($log_root_path, $log, FILE_APPEND);
        return TRUE;
    }

}
