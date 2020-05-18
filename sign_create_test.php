<?php
/**
 * 生成签名测试
 */

//自动加载类
require_once __DIR__ . '/vendor/autoload.php';

use LazyPigClient\Signature;


//签名类
$sign = new Signature();

//生成签名的数据,自定义
$data = [
    'name' => 'xiaoniao',
    'adult' => false,
    //可传数组,将转换为json字符串
    'users' => [
        ['name' => '小明','age'=>18],
        ['name' => '小红','age'=>19],
    ],

    //时间戳(默认可不传,调用createSign方法将自动添加)
    'timestamp' => 1588920582
];

//生成签名字符串
$sign_str = $sign->createSign($data);

echo '1.用于生成签名的参数 ' .PHP_EOL. var_export($sign->getSignData(), true) . PHP_EOL;
echo '2.用于生成签名字符串 ' . $sign->getSignStr() . PHP_EOL;
echo '3.生成签名字符串 ' . $sign_str . PHP_EOL;
