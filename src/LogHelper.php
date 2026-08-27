<?php
/**
 * User: zhengze
 * Date: 2020/4/18
 * Time: 16:47
 */

namespace Helper;


/**
 * 记录日志
 * 使用前先调用 setPathName 设置日志根目录和分类目录(自动创建)
 * 日志文件按小时切分,分类日志写入对应子目录
 * Class LogHelper
 * @package Helper
 */
class LogHelper
{
    /**
     * 日志根目录,以 / 结尾
     * @var string
     */
    protected static $log_root_path = '';

    /**
     * 已注册的日志分类列表,如['mysql','request']
     * @var array
     */
    protected static $types = [];

    /**
     * 设置日志根目录
     *
     * @param string $log_root_path 日志根目录
     * @param array $types 日志分类目录, eg["mysql","request"]
     */
    public static function setPathName(string $log_root_path, $types = []): void
    {
        static::$log_root_path = rtrim($log_root_path, "/") . "/";
        static::$types = $types;
        //创建根目录
        if (!is_dir(static::$log_root_path)) {
            if (!mkdir($concurrentDirectory = static::$log_root_path, 0777, TRUE) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
        //创建分类目录
        foreach ($types as $value) {
            $path = rtrim(static::$log_root_path, "/") . sprintf("/%s/", $value);
            if (!is_dir($path)) {
                if (!mkdir($concurrentDirectory = $path, 0777, TRUE) && !is_dir($concurrentDirectory)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
                }
            }
        }
    }


    /**
     * 写入日志内容
     * 内容经 print_r 转字符串后追加写入,文件按小时切分
     * 已注册分类 -> <根目录>/<分类>/Y-m-d H.log
     * 未注册分类 -> <根目录>/Y-m-d H.<分类>.log
     * 无分类     -> <根目录>/Y-m-d H.log
     * 写入前需先调用 setPathName 设置日志根目录
     *
     * @param mixed $log string 日志内容
     * @param mixed $type string 日志分类,需在 setPathName 的 $types 中注册
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
        $log_file = rtrim($file_path, "/") . $filename;
        $log = print_r($log, TRUE) . "\n";
        echo $log;
        file_put_contents($log_file, $log, FILE_APPEND);
        return TRUE;
    }

}
