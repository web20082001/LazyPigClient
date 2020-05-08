<?php
/**
 * 测试
 */

//自动加载
require_once __DIR__ . '/vendor/autoload.php';

use LazyPigClient\Signature;

//签名类
$sign = new Signature();

//生成签名的数据,自定义
$data = [
    'name' => 'xiaoniao',
];

//生成签名字符串
$sign_str = $sign->createSign($data);

echo '生成签名字符串 sign:' . $sign_str;
