<?php
define("FILE_PATH","/saas_server"); //文件目录
require_once './system/system_init.php';
require_once('./Saas/SAASAPIServer.php');

$appid = FANWE_APP_ID;
$appsecret = FANWE_AES_KEY;

class url_server{
    var $server;
    function __construct(){
        if(empty($this->server)){
            $this->server = new SAASAPIServer($GLOBALS['appid'], $GLOBALS['appsecret']);
        }
    }
    
    /**
     * 解析SAAS过来的URL
     */
    function decode_saas_url(){
        $ret = $this->server->takeSecurityParams($_SERVER['QUERY_STRING']);
        return $ret;
    }
}
?>