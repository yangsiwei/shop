<?php

require_once __DIR__.'/SAASCryptAES.php';

date_default_timezone_set('Asia/Shanghai');

class SAASAPIServer
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
     * @param $appsecret 可选参数，应用开发的App密钥（用于API服务接口调用时的参数签名），若为空，则通过$appid从数据库中获取$appsecret
     * @return void
     */
    public function __construct($appid, $appsecret=null)
    {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
    }

    /**
     * 从HTTP请求参数中获取远程调用者上传的最新授权信息内容，并以此更新本地授权目录下的各授权域名授权文件。
     *
     * @param $licenseRootDir 本地授权文件根目录路径
     * @param $requests HTTP请求参数数组，如果为空时，直接从$_REQUEST变量中获取请求参数，请求参数包含内容：
     *                1) domains     授权域名列表，json格式字符串，如：["u1.o2o.fanwe.com","*.yydb.fanwe.com"]
     *                2) license     授权文件内容
     * @return 更新结果，数组对象，如：array("errcode"=>0,"errmsg"=>"")。
     */
    public function updateLocalLicense($licenseRootDir, $requests=null)
    {
        // 初始化相关参数
        if (empty($licenseRootDir)) {
            $licenseRootDir = __DIR__;
        }
        if (empty($requests)) {
            $requests = $_REQUEST;
        }
        // 验证请求参数签名验证
        $ret = $this->verifyRequestParameters($requests);
        if ($ret['errcode'] != 0) {
            return $ret;
        }
        // 授权域名和授权文件内容参数获取和验证
        $domains = array_key_exists('domains', $requests) ? trim($requests['domains']) : '';
        $content = array_key_exists('license', $requests) ? trim($requests['license']) : '';
        if (empty($domains)) {
            return array('errcode'=>1002,'errmsg'=>'Invalid "domains" argument!');
        }
        if (empty($content)) {
            return array('errcode'=>1002,'errmsg'=>'Invalid "license" argument!');
        }
        $domainsArr = json_decode($domains, true);
        if (is_null($domainsArr)) {
            return array('errcode'=>1002,'errmsg'=>'Invalid "domains" argument!');
        }
        // 更新本地授权
        $failDomains = array();
        foreach($domainsArr as $domain) {
            // 域名有效性判断
            $fixedDomain = trim($domain);
            if (empty($fixedDomain)) continue;
            // 替换掉域名中的星号
            if (strpos($fixedDomain, '*.') === 0) { // start with *.
                $fixedDomain = str_replace('*', '^', $fixedDomain); // 替换掉星号
            }
            // 创建保存授权文件的目录
            $licenseDir = $licenseRootDir.'/'.$fixedDomain;
            if (!file_exists($licenseDir)) {
                mkdir($licenseDir, 0777, true);
            }
            // 保存授权文件
            $saveRet = true;
            $tryCount = 0;
            do {
                $saveRet = file_put_contents($licenseDir.'/license', $content);
            } while($saveRet === false && $tryCount++ < 3);
            if ($saveRet === false) {
                $failDomains[] = $domain;
            }
        }
        // 输出结果
        if (!empty($failDomains)) {
            return array('errcode'=>3001,'errmsg'=>'Save license file error!','data'=>$failDomains);
        } else {
            return array('errcode'=>0,'errmsg'=>'');
        }
    }

    /**
     * 验证SAAS系统API请求参数是否有效，这些请求参数必须按照SAAS系统API服务接口参数和签名规范进行传递。这些参数通过$_REQUEST变量获取。
     *
     * 
     * @param $params HTTP请求参数数组，如果为空时，直接从$_REQUEST变量中获取请求参数
     * @return 验证结果，数组对象，如：array("errcode"=>0,"errmsg"=>"")，验证通过时，errcode为0。
     */
    public function verifyRequestParameters($params=null)
    {
        // 获取请求参数
        if (empty($params)) {
            $params = $_REQUEST;
        }
        // 获取HTTP请求参数并进行有效性判断
        $appid = array_key_exists('appid', $params) ? trim($params['appid']) : '';
        $timestamp = array_key_exists('timestamp', $params) ? trim($params['timestamp']) : '';
        $signature = array_key_exists('signature', $params) ? trim($params['signature']) : '';
        if (empty($appid) || empty($timestamp) || empty($signature) || !is_numeric($timestamp)) {
            return array('errcode'=>1002,'errmsg'=>'Invalid argument!');
        }
        // 验证appid
        if ($appid != $this->appid) {
            return array('errcode'=>1005,'errmsg'=>'Invalid appid!');
        }
        // 计算参数签名
        $signParams = array();
        foreach($params as $key=>$value) {
            if ($key == 'signature') continue;
            $signParams[] = $key.$value;
        }
        sort($signParams, SORT_STRING);
        $signParamsStr = implode($signParams);
        $calcSignature = md5($this->appsecret.$signParamsStr.$this->appsecret);
        // 验证参数签名
        if (strtolower($signature) != strtolower($calcSignature)) {
            return array('errcode'=>1004,'errmsg'=>'Invalid signature!');
        }
        // 验证时间戳
        $timestamp = intval($timestamp);
        if (abs($timestamp - time()) > 600) { // 误差在10分钟之内
            return array('errcode'=>1003,'errmsg'=>'Invalid timestamp!');
        }
        // 一切顺利，返回成功结果
        return array('errcode'=>0,'errmsg'=>'');
    }
    
    /**
     * 将数组格式的结果数据转换成指定输出格式的字符串（目前只支持json格式字符串）
     * @param $result 数组格式结果数据
     * @return 指定输出格式的字符串（目前为json格式字符串）
     */
    public function toResponse($result)
    {
        return json_encode($result);
    }

    /**
     * 从方维系统间传递的安全地址中提取所传递的安全参数，并返回安全参数数组。如果参数已超时，那么返回false
     * @param $url 方维系统间传递的安全地址（带有加密后的安全参数），也可直接为请求参数字符串
     * @return 提取到的安全参数数组，参数超时时返回false
     */
    public function takeSecurityParams($url)
    {
        // 从$url中获取加密后的参数
        $paramName = '_saas_params';
        $pos = strpos($url, '?'.$paramName.'=');
        $start = 0;
        if ($pos === false) {
            $pos = strpos($url, '&'.$paramName.'=');
        }
        if ($pos === false) { // 未找到安全参数
            if (strpos($url, $paramName.'=') == 0) { // 判断地址只包含参数，首个参数就是安全参数，且没带问号和&号
                $pos = 0;
                $start = $pos + strlen($paramName) + 1;
            } else {
                return array();
            }
        } else {
            $start = $pos + strlen($paramName) + 2;
        }
        $end = strpos($url, '&', $start);
        $encstr = ($end === false) ? substr($url, $start) : substr($url, $start, $end - $start);
        $encstr = urldecode($encstr);
        // 对加密的参数进行解密并返回
        return $this->decodeSecurityParams($encstr);
    }
    
    /**
     * 解密方维系统间传递的安全地址中的加密参数。如果参数已超时，那么返回false
     * @param $paramsStr 加密参数字符串
     * @return 解密后得到的原始安全参数数组，参数超时时返回false
     */
    public function decodeSecurityParams($paramsStr)
    {
        // 对加密的参数进行解密
        $aes = new SAASCryptAES();
        $aes->set_key($this->appsecret);
        $aes->require_pkcs5();
        $decstr = $aes->decrypt($paramsStr);
        // 将解密后的json字符串转成数组
        $ret = empty($decstr) ? array() : json_decode($decstr, true);
        // 验证参数是否过期
        $timestamp = array_key_exists('_saas_timestamp', $ret) ? $ret['_saas_timestamp'] : 0;
        $timeout = array_key_exists('_saas_timeout', $ret) ? $ret['_saas_timeout'] : 0;
        if ($timestamp <= 0) {
            return false;
        }
        if ($timeout > 0) {
            if (abs($timestamp - time()) > $timeout * 60) { // 误差超过指定时间，已超时
                return false;
            }
        }
        // 删除数组中的时间戳和超时设置参数
        if (array_key_exists('_saas_timestamp', $ret)) {
            unset($ret['_saas_timestamp']);
        }
        if (array_key_exists('_saas_timeout', $ret)) {
            unset($ret['_saas_timeout']);
        }
        // 返回结果
        return $ret;
    }
    
}
