<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

define("FILE_PATH",""); //文件目录，空为根目录
require_once './system/system_init.php';

define("IOS_CLIENT_VERSION","3.03.01");
define("ANDROID_CLIENT_VERSION","1.3.2");
define("IS_IOS_UPGRADING",false); //IOS正在审核中，审核结束改为false,true时将会关闭相关审核不允许出现的内容
define("IS_ANDROID_UPGRADING",false);

$m_config = getMConfig();//初始化手机端配置


$act = strim($_REQUEST['act']);
$sdk_type = strim($_REQUEST['sdk_type']);
if($act=="init")
{
	global $m_config;
	
	//客服端手机类型dev_type=android;dev_type=ios
	$dev_type = strim($_REQUEST['sdk_type']);
	$sdk_version_name = strim($_REQUEST['sdk_version_name']); //sdk版本号对于对比审核
	$sdk_version = strim($_REQUEST['sdk_version']);//升级版本号yyyymmddnn： 2016021601

	//开始定义关于分享的接口
	$root = array();
	$root['sina_app_api'] = 0;
	if(strim($m_config['sina_app_key'])!=""&&strim($m_config['sina_app_secret'])!="")
	{
		$root['sina_app_api'] = 1;
	}
	
	$root['qq_app_api'] = 0;
	if(strim($m_config['qq_app_key'])!=""&&strim($m_config['qq_app_secret'])!="")
	{
		$root['qq_app_api'] = 1;
	}

	
	$root['wx_app_api'] = 0;
	if(strim($m_config['wx_appid'])!=""&&strim($m_config['wx_secrit'])!="")
	{
		$root['wx_app_api'] = 1;
	}


	
	$root['statusbar_hide'] = 0;//0:显示状态栏;1隐藏状态栏
	$root['statusbar_color'] = '#d93a55';//状态栏,颜色
	$root['topnav_color'] = '#d93a55';//顶部导航栏,颜色  
	
	$root['ad_img'] = '';//启动时的广告图
	$root['ad_http'] = '';//启动时的广告连接内容
	$root['ad_open'] = 0;//点击广告内容，打开方式：0:在第一个webveiw中打开;1:新建一个webview打开连接
	
	
	$default_url= SITE_DOMAIN.APP_ROOT."/wap/index.php?show_prog=1";//h5首页地址
	//审核的时候开启这个 审核通过注释掉
	if($sdk_type=="ios"){
	    $url = "";//填写O2O的地址方便通过审核
	}
	$root['site_url'] = $url?$url:$default_url;
 	
//	$root['site_url'] = "http://o2o.fanwe.net/wap/index.php?show_prog=1";
// 	$root['site_url'] = "http://devsh.o2o.fanwe.net/hwap/index.php?show_prog=1";
//  $root['site_url'] = "http://1.fanwe.cn/wap/index.php?show_prog=1";
//  $root['site_url'] = "http://192.168.3.191/yydb/wap/index.php?show_prog=1";
	$root['version'] = app_version($dev_type, $sdk_version);//版本升级检查
	$root['reload_time'] = 60;//秒；程序暂停，超过 60 秒，再进去时，需要重新清空程序加载
	$root['top_url'] = array($root['site_url']);
	ajax_return($root);
}


function app_version($dev_type,$version)
{

	global $m_config;//初始化手机端配置
	$site_url = SITE_DOMAIN.APP_ROOT."/";//站点域名;

	$root = array();
	if ($dev_type == 'android'){
		$root['serverVersion'] = $m_config['android_version'];//android版本号

		if ($version < $root['serverVersion']){
			$root['filename'] = $site_url.$m_config['android_filename'];//android下载包名
			$root['android_upgrade'] = $m_config['android_upgrade'];//android版本升级内容

			if(file_exists(APP_ROOT_PATH.$m_config['android_filename']))
			{
				$root['hasfile'] = 1;
				$root['filesize'] = filesize(APP_ROOT_PATH.$m_config['android_filename']);
				
				$root['has_upgrade'] = 1;//1:可升级;0:不可升级
				$root['forced_upgrade'] = intval($m_config['android_forced_upgrade']);//0:非强制升级;1:强制升级

			}
			else
			{
				$root['hasfile'] = 0;
				$root['filesize'] = 0;
				$root['has_upgrade'] = 0;//1:可升级;0:不可升级
			}

		}else{
			$root['hasfile'] = 0;
			$root['has_upgrade'] = 0;//1:可升级;0:不可升级
		}
	}else if ($dev_type == 'ios'){
		$root['serverVersion'] = $m_config['ios_version'];//ios版本号

		if ($version < $root['serverVersion']){
			$root['ios_down_url'] = $m_config['ios_down_url'];//ios下载地址
			$root['ios_upgrade'] = $m_config['ios_upgrade'];//ios版本升级内容
			$root['has_upgrade'] = 1;//1:可升级;0:不可升级
			$root['forced_upgrade'] = intval($m_config['ios_forced_upgrade']);//0:非强制升级;1:强制升级
		}else{
			$root['has_upgrade'] = 0;//1:可升级;0:不可升级
		}
	}else{
		$root['hasfile'] = 0;
		$root['has_upgrade'] = 0;//1:可升级;0:不可升级
	}
	return $root;
}


?>