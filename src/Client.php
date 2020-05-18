<?php

namespace LazyPigClient;

/**
 * 懒猪客户端
 */
class Client
{
    function getProducts()
    {
        $ca = new ClientAgency();

        $response = $ca->products();

        return $response;
    }
}