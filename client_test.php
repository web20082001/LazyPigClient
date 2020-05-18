<?php
/**
 *
 */

//自动加载类
require_once __DIR__ . '/vendor/autoload.php';

use LazyPigClient\Client;


$client = new Client();
$res = $client->getProducts();

var_dump($res);

