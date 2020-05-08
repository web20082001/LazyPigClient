<?php

namespace LazyPigClient;

/**
 * 帮助类
 */
class Helper
{

    /**
     * 获取数组指定元素
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public static function arrayGet($array, $key, $default=null)
    {
        if(is_array($array)){
            if(array_key_exists($key, $array)){
                return $array[$key];
            }else{
                return $default;
            }
        }else{
            //不是数组
            return $default;
        }
    }
}