<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

/*以下为动态载入的函数库*/

//动态加载用户提示
function insert_load_user_tip()
{
	//输出未读的消息数
	if($GLOBALS['user_info'])
	{
		$GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
		//输出签到结果
		$signin_result = es_session::get("signin_result");
		if($signin_result['status'])
		{
			$GLOBALS['tmpl']->assign("signin_result",json_encode($signin_result));
			es_session::delete("signin_result");
		}
	}
	else 
	{

		$m_config = getMConfig();//初始化手机端配置
		if($m_config['wx_appid']&&$m_config['wx_secrit'])
		{
			$GLOBALS['tmpl']->assign("wx_login",true);
		}
	}
	return $GLOBALS['tmpl']->fetch("inc/insert/load_user_tip.html");
}

//动态获取可同步登录的图片
function insert_get_app_login()
{
	$apis = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."api_login");	
	foreach($apis as $k=>$api)
	{
		$class_name = $api['class_name'];
		if(file_exists(APP_ROOT_PATH."system/api_login/".$class_name."_api.php"))
		{
			require_once APP_ROOT_PATH."system/api_login/".$class_name."_api.php";
			$api_class = $class_name."_api";
			$api_obj = new $api_class($api);
			$url = $api_obj->get_api_url();
			if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
			{
				$domain = $GLOBALS['distribution_cfg']['OSS_DOMAIN'];
			}
			else
			{
				$domain = SITE_DOMAIN.$GLOBALS['IMG_APP_ROOT'];
			}
			$url = str_replace("./public/",$domain."/public/",$url);
			
			$str .= $url;
		}		
	}
	return $str;

}



?>