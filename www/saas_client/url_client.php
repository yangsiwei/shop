<?php
define("FILE_PATH","/saas_client"); //文件目录
require_once './system/system_init.php';
require_once('./Saas/SAASAPIClient.php');

$appid = 'fw9ae7883339a8a55f';
$appsecret = '5cce8819673f948c40e60fcade608dbb';
class url_client{
    var $client;
    
    function __construct(){
        if(empty($this->client)){
            $this->client = new SAASAPIClient($GLOBALS['appid'], $GLOBALS['appsecret']);
        }
            
    }


    /**
     * 生成用户登录连接
     */
    function encode_saas_url($url,$params){
        // 生成方维系统间信息安全传递地址
        $widthAppid = true;  // 生成的安全地址是否附带appid参数
        $timeoutMinutes = 10; // 安全参数过期时间（单位：分钟），小于等于0表示永不过期

        $url = strim($url);
        $url = $this->client->makeSecurityUrl($url, $params, $widthAppid, $timeoutMinutes);
        return $url;
    }
}




?>