<?php
/**
 * 验证签名测试
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
    'users' => [
            ['name' => '小明','age'=>18],
            ['name' => '小红','age'=>19],
        ],
    //时间戳
    'timestamp' => 1588920582,

    //签名
    'sign' => 'f1e6538b675dc524cb61d82804e92d300b2ae618',
];

//生成签名字符串
$verify = $sign->verify($data);


echo '1.用于生成签名的参数 ' .PHP_EOL. var_export($sign->getSignData(), true) . PHP_EOL;
echo '2.用于生成签名字符串 ' . $sign->getSignStr() . PHP_EOL;

if ($verify) {
    //验证成功
    echo '3.验证成功, 参数生成签名字符串 '.$sign->getMakeSignStr().' 与参数sign相等'. PHP_EOL;

} else {
    //验证失败
    echo '3.验证失败,原因:'.$sign->getMessage().' 参数生成签名字符串 '.$sign->getMakeSignStr().'与参数sign不相等'. PHP_EOL;
}

