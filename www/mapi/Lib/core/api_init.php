<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

//开始定义IOS/android的客户端版本号
define("IOS_CLIENT_VERSION","3.03.01");
define("ANDROID_CLIENT_VERSION","4.5.2");
define("IS_IOS_UPGRADING",false); //IOS正在审核中，审核结束改为false,true时将会关闭相关审核不允许出现的内容
define("IS_ANDROID_UPGRADING",false);

require_once APP_ROOT_PATH.'mapi/Lib/core/common.php';
define("CACHE_SUBDIR","mapi");

$IMG_APP_ROOT = APP_ROOT;

if(defined("APP_INDEX")&&APP_INDEX=="wap_index")
{

}
else
{
	$_REQUEST = array_merge($_GET,$_POST);
	filter_request($_REQUEST);
	convert_req($_REQUEST);
	
	if (isset($_REQUEST['i_type']))
	{
		$i_type = intval($_REQUEST['i_type']);
	}
	
	if ($i_type == 1)
	{
		$request = $_REQUEST;
	}
	else
	{
		if (isset($_REQUEST['requestData']))
		{
			require_once APP_ROOT_PATH.'/system/libs/crypt_aes.php';
			$aes = new CryptAES();
			$aes->set_key(FANWE_AES_KEY);
			$aes->require_pkcs5();
	
			$str  = $_REQUEST['requestData'];
			$str = str_replace(" ", "+", $str);
			$str = trim($str);
	
			$decString = $aes->decrypt($str);
	
			$request = json_decode($decString, 1);
		}else
		{
			$request = $_REQUEST;
		}
	}
	
	if($request['from']=="wap"){
		define('APP_INDEX','wap');
	}else{
		define('APP_INDEX','app');
	}
	
	if(IS_DEBUG)
	{
		if($i_type==0)
		$url = SITE_DOMAIN.APP_ROOT."/".FOLDER_NAME."/index.php?requestData=".$_REQUEST['requestData']."&r_type=2";
		else
		{
			$url_request = $request;
			unset($url_request['r_type']);
			$debug_param_str = http_build_query($url_request);
			$url = SITE_DOMAIN.APP_ROOT."/".FOLDER_NAME."/index.php?r_type=2&".$debug_param_str;
		}
		$api_log = array();
		$api_log['api'] = $url;
		$api_log['act'] = $request['ctl']."#".$request['act'];
		$api_log['param_json'] = json_encode($request);
		$api_log['param_array'] = print_r($request,1);
		$GLOBALS['db']->autoExecute(DB_PREFIX."api_log", $api_log, 'INSERT');
	}
	
	$m_config = getMConfig();//初始化手机端配置
}//end wap_index

//定义一些常量
if (intval($m_config['page_size']) == 0){
	define('PAGE_SIZE',20); //分页的常量
}else{
	define('PAGE_SIZE',intval($m_config['page_size'])); //分页的常量
}
define('VERSION',3.05); //接口版本号,float 类型


// $image_zoom = 1.5;
// if(intval($GLOBALS['request']['image_zoom'])>0)
// {
// 	$image_zoom = intval($GLOBALS['request']['image_zoom']);
// }
// define("IMAGE_ZOOM",$image_zoom);


if(defined("APP_INDEX")&&APP_INDEX=="wap_index")
{
	
}
else
{
	//初始化session
	global $sess_id;
	global $define_sess_id;
	$sess_id = strim($request['sess_id']);
	if($sess_id)
	{
		$define_sess_id = true;
		es_session::set_sessid($sess_id);
	}
	else
	{
		$define_sess_id = false;
		$sess_id= es_session::id();
	}
	
}

if(!$GLOBALS['global_is_run'])
	global_run();

//开始定位模板引擎，用于专题代码的编译
$GLOBALS['zt_tmpl'] = $zt_tmpl = new AppTemplate;
if(!file_exists(APP_ROOT_PATH.'public/runtime/mapi/'))
{
	mkdir(APP_ROOT_PATH.'public/runtime/mapi/',0777);
}
if(!file_exists(APP_ROOT_PATH.'public/runtime/mapi/tpl_caches/'))
	mkdir(APP_ROOT_PATH.'public/runtime/mapi/tpl_caches/',0777);
if(!file_exists(APP_ROOT_PATH.'public/runtime/mapi/tpl_compiled/'))
	mkdir(APP_ROOT_PATH.'public/runtime/mapi/tpl_compiled/',0777);
$GLOBALS['zt_tmpl']->cache_dir      = APP_ROOT_PATH . 'public/runtime/mapi/tpl_caches';
$GLOBALS['zt_tmpl']->compile_dir    = APP_ROOT_PATH . 'public/runtime/mapi/tpl_compiled';
$GLOBALS['zt_tmpl']->template_dir   = APP_ROOT_PATH .'mapi/mobile_zt';

//定义模板路径
$tmpl_path = SITE_DOMAIN.APP_ROOT."/mapi/mobile_zt/";


$GLOBALS['zt_tmpl']->assign("TMPL",$tmpl_path);
$GLOBALS['zt_tmpl']->assign("TMPL_REAL",APP_ROOT_PATH."mapi/mobile_zt");
$GLOBALS['zt_tmpl']->assign("APP_INDEX",APP_INDEX);

$GLOBALS['zt_tmpl']->assign("APP_ROOT",APP_ROOT);
//end tmpl

?>