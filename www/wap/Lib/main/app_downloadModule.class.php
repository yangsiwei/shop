<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class app_downloadModule extends MainBaseModule
{
	public function index()
	{	
		if(isWeixin()||isQQ())
		{
			if (isios()){
				//$str = '请使用浏览器打开下载：<br>';
				//$str = $str.'1.点击右上角的按钮<br>';
				//$str = $str.'2.选择 在Safari中打开 即可下载app';
				//header("Content-Type:text/html; charset=utf-8");
				//echo $str;
				echo $GLOBALS['tmpl']->fetch("downapp.html");
				exit;
			}else{
				//$str = '请使用浏览器打开下载：<br>';
			//	$str = $str.'1.点击右上角的按钮<br>';
				//$str = $str.'2.选择 在浏览器中打开 即可下载app';
				//header("Content-Type:text/html; charset=utf-8"); 
			//	echo $str;
				echo $GLOBALS['tmpl']->fetch("downapp.html");
				exit;
			}
		}
		else
		{
			//用户app下载地址连接
			if (isios()){
				//$down_url = app_conf("APPLE_PATH");
				$down_url = $GLOBALS['db']->getOne("select val from ".DB_PREFIX."m_config where code = 'ios_down_url'");
			}else{
				//$down_url = app_conf("ANDROID_PATH");
				$down_url = $GLOBALS['db']->getOne("select val from ".DB_PREFIX."m_config where code = 'android_filename'");
			}	
			if(substr($down_url, 0,7)!="http://"&&substr($down_url, 0,8)!="https://")
			{
				$down_url = SITE_DOMAIN.APP_ROOT."/".$down_url;
			}
			app_redirect($down_url);	
		}	
	}	
	
}
?>