<?php

namespace Helper;

use Helper\traits\InstanceTrait;

/**
 * 配置文件加载类
 * 支持点分语法读取 PHP 配置文件,如 load('database.connections.host')
 * 对应 <配置目录>/database.php 文件中 connections 数组的 host 键
 * Class ConfigHelper
 * @package Helper
 */
class ConfigHelper
{
    use InstanceTrait;

    /**
     * 配置文件内容缓存,key为文件绝对路径,value为require_once返回的数组
     * @var array
     */
    static $file = [];

    /**
     * 配置文件所在目录,需以 / 结尾,如 APP_PATH.'config/'
     * @var string
     */
    static $dir = '';

    /**
     * 获取配置文件目录
     *
     * @return string
     */
    public static function getDir(): string
    {
        return static::$dir;
    }

    /**
     * 设置config配置的文件夹,如APP_PATH.'config/'
     *
     * @param string $dir 配置文件目录,需以 / 结尾
     */
    public static function setDir($dir): void
    {
        static::$dir = $dir;
    }


    /**
     * 加载配置项,请先设置配置项路径(setDir)
     * 用法: load('文件名.一级键.二级键'),文件名后每一段依次向下取数组键
     * 文件只会被 require_once 一次,后续读取走 static::$file 缓存
     * 配置项不存在时返回空数组
     *
     * @param string $name 点分语法配置名,如'database.connections.host'
     *
     * @return array|mixed 配置项不存在返回[],存在则返回对应值
     */
    public static function load($name)
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
