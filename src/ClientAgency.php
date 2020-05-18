<?php
/**
 * Created by PhpStorm.
 * User: xiaoniao
 * Date: 2020/5/16
 * Time: 15:07
 */

namespace LazyPigClient;


class ClientAgency
{

    /**
     * 商品列表
     */
    function products()
    {
        $path = Config::get('api_path_products');

        $data = [];

        $ra = new RequestAgency();
        $success = $ra->sendRequest($path, $data);

        if($success){
            //成功
            $products = $ra->getResponseData();
        }else{
            //失败
            $products = false;

             //http状态码
            $status_code = $ra->getStatusCode();

            //错误原因
            $error = $ra->getMessage();

            if($status_code == RequestAgency::STATUS_CODE_NOT_ACCEPTABLE){
                //请求不正确

            }else if($status_code == RequestAgency::STATUS_CODE_REQUEST_TIMEOUT){
                //超时

            }else if($status_code == RequestAgency::STATUS_CODE_SERVER_ERROR){
                //服务器出错

            }
        }

        //成功返回数组,失败返回false
        return $products;
    }
}