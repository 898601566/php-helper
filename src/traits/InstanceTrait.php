<?php

namespace Helper\traits;

/**
 * Trait InstanceTrait
 * @package common\traits
 */
trait InstanceTrait
{

    protected static $instance = null;

    /**
     * @param null $instance
     */
    public static function setInstance($instance): void
    {
        self::$instance = $instance;
    }

    /**
     * @param array $options
     * @return static
     */
    public static function getInstance($options = [])
    {
        return static::instance($options);
    }

    /**
     * @param array $options
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
