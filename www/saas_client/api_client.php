<?php
define("FILE_PATH","/saas_client"); //文件目录
require_once './system/system_init.php';
require_once('./Saas/SAASAPIClient.php');

$appid = FANWE_APP_ID;
$appsecret = FANWE_AES_KEY;
class api_client{
    var $client;
    
    function __construct(){
        if(empty($this->client)){
            $this->client = new SAASAPIClient($GLOBALS['appid'], $GLOBALS['appsecret']);
        }
            
    }


    /**
     * 生成用户登录连接
     */
    function invoke_data($url,$params){
        // 调用服务
        $ret = $this->client->invoke($url, $params);
        return $ret;
    }
}




?>