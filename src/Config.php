<?php

namespace LazyPigClient;

/**
 * 配置类
 */
class Config
{
    private static $config = null;

    private static function loadConfig()
    {
        self::$config = require_once('Conf.php');
    }

    /**
     * 获取配置
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    static function get($key, $default=null)
    {
        if(is_null(self::$config)){
            self::loadConfig();
        }

        if(is_null($key)){
            return self::$config;
        }

        return Helper::arrayGet(self::$config, $key, $default);
    }

    static function getAll()
    {
        return self::get(null);
    }

    static function getAppId()
    {
        return self::get('app_id');
    }

    static function getAppSecret()
    {
        return self::get('app_secret');
    }
}