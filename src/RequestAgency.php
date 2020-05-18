<?php

namespace LazyPigClient;


/**
 * 统一的发起请求的代理
 * Class RequestAgency
 * @package App\Model
 */
class RequestAgency extends Base
{

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';

    /**
     *  请求成功状态码
     */
    const STATUS_CODE_SUCCESS = 200;

    /**
     * Forbidden
     */
    const STATUS_CODE_FORBIDDEN = 403;

    /**
     * Not Found
     */
    const STATUS_CODE_NOT_FUND = 404;

    /**
     * 请求的资源的内容特性无法满足请求头中的条件，因而无法生成响应实体。
     */
    const STATUS_CODE_NOT_ACCEPTABLE = 406;

    /**
     * Request Timeout
     */
    const STATUS_CODE_REQUEST_TIMEOUT = 408;

    /**
     * Internal Server Error
     */
    const STATUS_CODE_SERVER_ERROR = 500;

    protected $host = '';
    protected $path = '';
    protected $method = self::METHOD_GET;
    protected $headers = [];
    protected $data = [];
    protected $response = false;

    /**
     *  请求是否成功
     */
    public $success = false;
    public $message = '';

    /**
     *  返回状态码
     */
    public $status_code = null;

    /**
     * 返回数据
     */
    public $response_body = [];

    /**
     * 请求地址
     * @var null
     */
    protected $request_url = null;


    function __construct()
    {
        parent::__construct();

        $this->host = Config::get('api_host');

    }


    /**
     * 发送请求
     * @param $path
     * @param array $data
     * @param array $headers
     * @return bool
     *
     */
    function sendRequest($path, $data=[], $headers=[])
    {
        /**
         * 设置参数
         */
        $this->path = $path;
        $this->data = $data;
        $this->headers = $headers;


        //签名类
        $sign = new Signature();

        //生成签名字符串
        $this->data['app_id'] = $this->app_id;
        $this->data['timestamp'] = time();

        $this->data['sign'] = $sign->createSign($this->data);

       
    
        /**
         * 请求地址
         */
        $this->createRequestUrl();
     
        if($this->method ==  self::METHOD_GET){

            $this->response = \Requests::get($this->request_url);

        }else if($this->method ==  self::METHOD_POST){

            $this->response = \Requests::post($this->request_url, $this->headers, $this->data);
        }
       
        $this->responseDispose();

        return $this->success;
    }

    /**
     * 返回结果处理
     */
    private function responseDispose()
    {
        if($this->response)
        {
            $this->status_code = $this->response->status_code;

            if($this->status_code == self::STATUS_CODE_SUCCESS){
                $this->success = true; 
            }

            $this->response_data = json_decode($this->response->body, true);

            if($this->response_data){
                $this->message =  Helper::arrayGet($this->response_data, 'message');
            }
        }
    }

    /**
     * 返回的数据
     */
    function getResponseData()
    {
        return $this->response_data;
    }

    /**
     * 状态码
     */
    function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * 状态码
     */
    function getMessage()
    {
        return $this->message;
    }

    /**
     * 返回的数据
     */
    function getResponse()
    {
        return $this->response;
    }

    /**
     * 创建请求链接
     * @return string
     */
    function createRequestUrl()
    {
        if($this->method ==  self::METHOD_GET){
            $this->request_url = $this->host.$this->path.'?'.http_build_query($this->data);
        }else if($this->method ==  self::METHOD_POST){
            $this->request_url = $this->host.$this->path;
        }

        return $this->request_url;
    }

    function getRequestUrl()
    {
        return $this->request_url;
    }

}
