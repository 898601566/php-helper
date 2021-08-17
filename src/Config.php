<?php

namespace Helper;

use Helper\traits\InstanceTrait;

class Config
{
    use InstanceTrait;

    static $file = [];
    static $dir = '';

    /**
     * @return string
     */
    public static function getDir(): string
    {
        return static::$dir;
    }

    /**
     * 先设置config配置的文件夹,如APP_PATH.'config/'
     * @param string $dir
     */
    public static function setDir($dir): void
    {
        static::$dir = $dir;
    }


    /**
     * 加载配置项,请先设置配置项路径
     *
     * @param $name
     *
     * @return array|mixed
     */
    public function load($name)
    {
        $ret = [];
        if (!empty($name)) {
            $file_path = explode(".", $name);
            if (!empty($file_path)) {
                $real_path = sprintf('%s%s%s', static::$dir, $file_path[0], '.php');
                if (is_file($real_path)) {
                    if (empty(static::$file[$real_path])) {
                        static::$file[$real_path] = require_once($real_path);
                    }
                    $ret = static::$file[$real_path];
                    unset($file_path[0]);
                    foreach ($file_path as $key => $value) {
                        if (isset($ret[$value])) {
                            $ret = $ret[$value];
                        }
                    }
                }
            }
        }
        return $ret;
    }
}
