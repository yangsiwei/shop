<?php

require_once __DIR__.'/SAASCryptAES.php';

date_default_timezone_set('Asia/Shanghai');

class SAASAPIClient
{
    
    /**
     * 应用开发的AppID
     */ 
    protected $appid;
    
    /**
     * 应用开发的App密钥（用于API服务接口调用时的参数签名）
     */ 
    protected $appsecret;
    
    /**
     * 构造函数
     * 
     * @param $appid 应用开发的AppID
     * @param $appsecret 应用开发的App密钥（用于API服务接口调用时的参数签名）
     * @return void
     */
    public function __construct($appid=null, $appsecret=null)
    {
        if (empty($appid) && empty($appsecret)) {
            $this->loadDefaultAppIdAndSecret();
        } else {
            $this->appid = $appid;
            $this->appsecret = $appsecret;
        }
    }

    
    /**
     * 调用各种SAAS系统HTTP请求方式的API服务接口，这些服务接口都遵循SAAS系统API服务接口规范，接口参数和签名都是按规范进行传递和验证。
     *
     * @param $url 服务地址
     * @param $args 具体的接口调用参数（除appid, timestamp, signature外），PHP数组对象，如：array("domain"=>"xxx.yydb.fanwe.com")
     * @return 接口调用结果，数组对象，如：array("errcode"=>0,"errmsg"=>"","data"=>array())
     */
    public function invoke($url, $args)
    {
        try {
            // 生成HTTP请求参数
            $params = $this->makeRequestParameters($args);
            // 执行HTTP POST请求
            $ch = curl_init(); // 初始化curl
            curl_setopt($ch, CURLOPT_URL, $url); // 服务地址
            curl_setopt($ch, CURLOPT_HEADER, false); // 设置header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // POST请求方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $data = curl_exec($ch); // 运行curl
            if(empty($data))
            {
            	$data = curl_error($ch);
            }
            curl_close($ch);
            // 将返回值转为PHP数组对象并返回
            $result = json_decode($data, true);
            return is_null($result) ? array("errcode"=>1001,"errmsg"=>"Remote service return data error: ".$data) : $result;
        } catch (Exception $e) {
            return array("errcode"=>1001,"errmsg"=>"Remote service error: ".$e->getMessage());
        }
    }
    
    /**
     * 生成SAAS系统API调用的请求参数，请求参数中将包含基本的参数（如：appid,timestamp,signature），并返回包含这些参数的数组数据。
     *
     * @param $args 具体的接口调用参数（除appid, timestamp, signature外），PHP数组对象，如：array("domain"=>"xxx.yydb.fanwe.com")
     * @return 已生成的参数数组，如：array("appid"=>"","timestamp"=>1457839056,"signature"=>"")
     */
    public function makeRequestParameters($args)
    {
        // 计算参数签名，并设置返回值
        $systime = time();
        $params = array();
        $result = array();
        foreach($args as $key=>$value) {
            if ($key == 'appid' || $key == 'timestamp' || $key == 'signature') continue;
            $params[] = $key.$value;
            $result[$key] = $value;
        }
        $params['appid'] = 'appid'.$this->appid;
        $params['timestamp'] = 'timestamp'.$systime;
        sort($params, SORT_STRING);
        $paramsStr = implode($params);
        $signature = md5($this->appsecret.$paramsStr.$this->appsecret);
        $result['appid'] = $this->appid;
        $result['timestamp'] = $systime;
        $result['signature'] = $signature;
        // 返回结果
        return $result;
    }
    
    /**
     * 生成方维系统间信息安全传递地址，将要传递的参数加密后附加到指定地址后面。
     * @param $url 原始地址，用于附加安全参数
     * @param $params 待附加的参数数组
     * @param $widthAppid 可选参数，生成的安全地址是否附带appid参数（参数名：_saas_appid），默认不附带
     * @param $timeoutMinutes 安全参数过期时间（单位：分钟），小于等于0表示永不过期
     * @return 附加安全参数后的安全地址
     */
    public function makeSecurityUrl($url, $params, $withAppid = false, $timeoutMinutes = 0)
    {
        // 将参数数组加密成安全参数字符串
        $encstr = $this->encodeSecurityParams($params, $timeoutMinutes);
        // 将加密后的参数附加到$url后面，然后返回
        $split = strpos($url, '?') === false ? '?' : '&';
        $url .= $split.'_saas_params='.urlencode($encstr);
        if ($withAppid) {
            $url .= '&_saas_appid='.$this->appid;
        }
        return $url;
    }
    
    /**
     * 加密指定的数组成生成方维系统间传递的安全参数字符串，以便通过HTTP的GET请求或POST请求进行传递
     * @param $params 待附加的参数数组
     * @param $timeoutMinutes 安全参数过期时间（单位：分钟），小于等于0表示永不过期
     * @return 加密后的安全参数字符串
     */
    public function encodeSecurityParams($params, $timeoutMinutes = 0)
    {
        // 添加验证过期参数
        if ($timeoutMinutes > 0) {
            $params['_saas_timestamp'] = time();
            $params['_saas_timeout'] = $timeoutMinutes;
        }
        // 先将参数数组转为json格式字符串，然后加密
        $json = json_encode($params);
        $aes = new SAASCryptAES();
        $aes->set_key($this->appsecret);
        $aes->require_pkcs5();
        $encstr = $aes->encrypt($json);
        // 返回加密后的字符串
        return $encstr;
    }

    /**
     * 从saasapi.key配置文件中加载默认的appid和appsecret配置
     */
    private function loadDefaultAppIdAndSecret()
    {
        $keyfile = __DIR__.'/saasapi.key';
        if (!file_exists($keyfile)) {
            return;
        }
        $keystr = file_get_contents($keyfile);
        $keyinfo = json_decode($keystr, true);
        $this->appid = array_key_exists('appid', $keyinfo) ? $keyinfo['appid'] : '';
        $this->appsecret = array_key_exists('appsecret', $keyinfo) ? $keyinfo['appsecret'] : '';
    }
    
}
