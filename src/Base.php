<?php

namespace LazyPigClient;

/**
 * 基类
 */
class Base
{
    protected $app_id = null;
    protected $app_secret = null;


    function __construct()
    {
        //加载配置
        $this->loadConfig();
    }

    /**
     * 加载配置
     */
    private function loadConfig()
    {
        //初始化参数
        $this->app_id = Config::getAppId();
        $this->app_secret = Config::getAppSecret();
    }

}