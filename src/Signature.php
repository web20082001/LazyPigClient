<?php

namespace LazyPigClient;


/**
 * 签名类
 *
 * 功能:1 生成签名, 2 验证签名
 */
class Signature extends Base
{

    /**
     * 时间差偏移量
     */
    const TIME_OFFSET_LIMIT = 60;

    /**
     * 是否验证时间差
     */
    const VERIFY_TIME = false;

    private $sign = null;
    private $data = null;
    private $message = '';

    /**
     * 生成签名的参数
     * @var array
     */
    private $sign_data = [];

    /**
     * 用于生成签名的字符串
     * @var array
     */
    private $sign_str = '';

    /**
     * 生成的签名的字符串
     * @var array
     */
    private $make_sign_str = '';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 验证签名有效性
     * @param $data
     * @return bool
     */
    public function verify($data)
    {
        //赋值验证参数
        $this->data = $data;

        //验证数据完成性并做过滤
        $check_success = $this->checkData();

        if ($check_success) {
            //参数检查通过

            //签名验证结果
            $verify_success = ($this->sign == $this->makeSign());

            if (!$verify_success) {
                $this->message = '签名验证未通过,签名不相等';
            }

            return $verify_success;

        } else {
            //参数检查未通过
            return false;
        }
    }

    /**
     * 生成签名字符串
     * @param $data
     * @return string
     */
    public function createSign(array $data)
    {
        //若没有时间戳则加
        $timestamp = Helper::arrayGet($data, 'timestamp');

        if (!$timestamp) {
            $data['timestamp'] = time();
        }

        //用于生成签名的参数
        $this->sign_data = $data;

        //用于生成签名的字符串
        $this->sign_str = self::signDataFormat($data) . '&' . $this->app_secret;

        $this->make_sign_str = sha1($this->sign_str);

        return $this->make_sign_str;
    }

    /**
     * 检查参数有效性
     * @return bool
     */
    private function checkData()
    {
        //验证必填参数
        $require_params = [
            'sign',
            'timestamp',
        ];

        foreach ($require_params as $key) {

            $value = Helper::arrayGet($this->data, $key);

            if (empty($value)) {
                //缺少必填参数
                $this->message = sprintf('缺少必填参数 %s', $key);

                return false;
            }

            //排除签名参数,不参与生成签名
            if ($key == 'sign') {
                $this->sign = $value;
                unset($this->data['sign']);
            } else if ($key == 'timestamp') {

                //验证时间差
                if (self::VERIFY_TIME) {
                    //时间戳偏移量
                    $time_offset = abs(intval($value) - time());

                    if ($time_offset >= self::TIME_OFFSET_LIMIT) {
                        $this->message = sprintf('参数 %s 的值 %s 时间戳时差超过 %d 秒', $key, $value, self::TIME_OFFSET_LIMIT);
                        return false;
                    }
                }

            }
        }

        //验证通过
        return true;
    }

    /**
     * 签名字符串
     * @return string
     */
    private function makeSign()
    {
        return self::createSign($this->data);
    }


    /**
     * 签名数据格式化
     * @param $data
     * @return string
     */
    private static function signDataFormat($data)
    {
        //null或空字符串不参与签名验证
        $data = array_filter($data, function ($v, $k) {
            return !is_null($v) && $v !== '';
        }, ARRAY_FILTER_USE_BOTH);

        //正序排列
        ksort($data);

        $signString = '';

        foreach ($data as $key => $val) {

            //类型转换
            if(is_bool($val)){
                $val = intval($val);
            }else if(is_array($val)){
                //数组转json字符串
                $val = json_encode($val);
            }

            $signString .= $key . '=' . $val . '&';
        }

        return rtrim($signString, '&');
    }

    /**
     * 获取返回消息
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 获取用于生成签名的参数
     * @return string
     */
    public function getSignData()
    {
        return $this->sign_data;
    }

    /**
     * 获取用于生成签名的字符串
     * @return string
     */
    public function getSignStr()
    {
        return $this->sign_str;
    }
    /**
     * 获取生成的签名字符串
     * @return string
     */
    public function getMakeSignStr()
    {
        return $this->make_sign_str;
    }
}