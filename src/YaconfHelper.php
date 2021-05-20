<?php
/**
 * User: zhengze
 * Date: 2020/4/18
 * Time: 16:47
 */

namespace Helper;


/**
 * yaconf加载
 * Class YaconfHelper
 * @package Helper
 */
class YaconfHelper
{
    protected static $app_name = '';

    /**
     * 设置项目名称
     * @param string $app_name
     */
    public static function setAppName(string $app_name): void
    {
        static::$app_name = $app_name;
    }

    /**
     * 获取项目配置名称
     * @param $config_name
     * @return string
     */
    public static function formatAppConfigName($config_name): string
    {
        $app_name = env('app.name', '');
        static::$app_name = static::setAppName($app_name);
        if (!empty(static::$app_name)) {
            return static::$app_name . '.' . $config_name;
        } else {
            ExceptionEmun::throwException(SystemException::EMPTY_CONFIG_APP_NAME);
        }
    }

    /**
     * 获取项目配置
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public static function getByAppName(string $name, $default = NULL)
    {
        return \Yaconf::get(static::formatAppConfigName($name), $default);
    }

    /**
     * 根据公用配置
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public static function get(string $name, $default = NULL)
    {
        return \Yaconf::get($name, $default);
    }

    /**
     * 检测项目配置是否存在
     * @param string $name
     * @return mixed
     */
    public static function hasByAppName(string $name)
    {
        return \Yaconf::has(static::formatAppConfigName($name));
    }

    /**
     * 检测公用配置
     * @param string $name
     * @return mixed
     */
    public static function has(string $name)
    {
        return \Yaconf::has($name);
    }

}
