<?php
define("FILE_PATH","/saas_server"); //文件目录
require_once './system/system_init.php';
require_once('./Saas/SAASAPIServer.php');

define("CTL",'ctl');
define("ACT",'act');

/**
 * 错误代码列表
 * 2001 接口不存在
 * 2002 接口函数不存在
 * 
 */

$appid = FANWE_APP_ID;
$appsecret = FANWE_AES_KEY;
class api_server{
    var $server;
    function __construct(){
        if(empty($this->server)){
            $this->server = new SAASAPIServer($GLOBALS['appid'], $GLOBALS['appsecret']);
        }
        $GLOBALS['saas_server'] = $this->server;
        // 验证客户端请求参数（时间戳、参数验证等）
        $ret = $this->server->verifyRequestParameters();
        if ($ret['errcode'] != 0) {
            die($this->server->toResponse($ret));
        }
        
        $module = strtolower($_REQUEST[CTL]?$_REQUEST[CTL]:"index");
        $action = strtolower($_REQUEST[ACT]?$_REQUEST[ACT]:"index");
        
        $module = filter_ctl_act_req($module);
		$action = filter_ctl_act_req($action);
		//echo APP_ROOT_PATH.FILE_PATH."/Lib/".$module."Module.class.php";exit;
        if(!file_exists(APP_ROOT_PATH."saas_server/Lib/".$module."Module.class.php")){
            die($this->server->toResponse(array("errcode"=>2001,"errmsg"=>"Remote service error: api not exist")));
        }
            
       
        require_once APP_ROOT_PATH."saas_server/Lib/".$module."Module.class.php";;
        
        if(!method_exists($module."Module",$action))
            die($this->server->toResponse(array("errcode"=>2002,"errmsg"=>"Remote service error: action not exist")));
        
        define("MODULE_NAME",$module);
        define("ACTION_NAME",$action);
        
        $module_name = $module."Module";
        $this->module_obj = new $module_name;
        $this->module_obj->$action();
        
        
        
    }

}
?>