<?php

namespace Helper\traits;

/**
 * 单例模式类
 * Trait InstanceTrait
 * @package common\traits
 */
trait InstanceTrait
{

    /**
     * 单例实例
     * @var static|null
     */
    protected static $instance = null;

    /**
     * 手动设置实例(可用于替换默认实例或注入 mock)
     *
     * @param mixed $instance 实例对象
     */
    public static function setInstance($instance): void
    {
        self::$instance = $instance;
    }

    /**
     * 获取单例(instance 的别名)
     *
     * @param array $options 构造函数参数
     * @return static
     */
    public static function getInstance($options = [])
    {
        return static::instance($options);
    }

    /**
     * 获取单例,首次调用时以 $options 作为构造参数创建实例
     * 使用 static::$instance 保证子类各自持有独立单例
     *
     * @param array $options 构造函数参数
     * @return static
     */
    public static function instance($options = [])
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($options);
        }
        return static::$instance;
    }
}
