<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

require_once APP_ROOT_PATH.'app/Lib/common.php';
require_once APP_ROOT_PATH.'app/Lib/'.APP_TYPE.'/core/main_lib.php';
$IMG_APP_ROOT = APP_ROOT;
define("APP_INDEX","index");

filter_injection($_REQUEST);
if(!file_exists(APP_ROOT_PATH.'public/runtime/app/'))
{
	mkdir(APP_ROOT_PATH.'public/runtime/app/',0777);
}

if(!file_exists(APP_ROOT_PATH.'public/runtime/app/tpl_caches/'))
	mkdir(APP_ROOT_PATH.'public/runtime/app/tpl_caches/',0777);
if(!file_exists(APP_ROOT_PATH.'public/runtime/app/tpl_compiled/'))
	mkdir(APP_ROOT_PATH.'public/runtime/app/tpl_compiled/',0777);
$GLOBALS['tmpl']->cache_dir      = APP_ROOT_PATH . 'public/runtime/app/tpl_caches';
$GLOBALS['tmpl']->compile_dir    = APP_ROOT_PATH . 'public/runtime/app/tpl_compiled';
$GLOBALS['tmpl']->template_dir   = APP_ROOT_PATH . 'app/Tpl/'.APP_TYPE."/". app_conf("TEMPLATE");

//定义模板路径
$tmpl_path = SITE_DOMAIN.APP_ROOT."/app/Tpl/".APP_TYPE."/";
$GLOBALS['tmpl']->assign("TMPL",$tmpl_path.app_conf("TEMPLATE"));
$GLOBALS['tmpl']->assign("TMPL_REAL",APP_ROOT_PATH."app/Tpl/".APP_TYPE."/".app_conf("TEMPLATE")); 
$GLOBALS['tmpl']->assign("APP_INDEX",APP_INDEX);

$GLOBALS['tmpl']->assign("APP_ROOT",APP_ROOT);
convert_req($_REQUEST);
//为main应用解析url
if(app_conf("URL_MODEL")==1)
{
	//重写模式
	$current_url = APP_ROOT;

	$rewrite_param = strim($_REQUEST['rewrite_param']);
	$rewrite_param = str_replace(array( "\"","'" ), array("",""), $rewrite_param);
	$rewrite_param = explode("/",$rewrite_param);

	foreach($rewrite_param as $k=>$param_item)
	{
		if($param_item!='')
			$rewrite_param_array[] = $param_item;
	}
	foreach ($rewrite_param_array as $k=>$v)
	{
		if($k==0)
		{
			//解析ctl
			if(preg_match("/-/", $v))
			{
				//有-表示为参数
				//扩展参数
				$ext_param = explode("-",$v);
				foreach($ext_param as $kk=>$vv)
				{
					if($kk%2==0)
					{
						if(preg_match("/(\w+)\[(\w+)\]/",$vv,$matches))
						{
							$_GET[$matches[1]][$matches[2]] = $ext_param[$kk+1];
						}
						else
							$_GET[$ext_param[$kk]] = $ext_param[$kk+1];
			
						if($ext_param[$kk]!="p")
						{
							if($kk==0)$current_url.="/";
							$current_url.=$ext_param[$kk];
							$current_url.="-".$ext_param[$kk+1]."-";
						}
					}
				}
			}
			else 
			{
				$_GET['ctl'] = $v;
				$current_url.= "/".$v;
			}
		}
		elseif($k==1)
		{
			//解析act
			if(preg_match("/-/", $v))
			{
				//有-表示为参数
				//扩展参数
				$ext_param = explode("-",$v);
				foreach($ext_param as $kk=>$vv)
				{
					if($kk%2==0)
					{
						if(preg_match("/(\w+)\[(\w+)\]/",$vv,$matches))
						{
							$_GET[$matches[1]][$matches[2]] = $ext_param[$kk+1];
						}
						else
							$_GET[$ext_param[$kk]] = $ext_param[$kk+1];
				
						if($ext_param[$kk]!="p")
						{
							if($kk==0)$current_url.="/";
							$current_url.=$ext_param[$kk];
							$current_url.="-".$ext_param[$kk+1]."-";
						}
					}
				}
			}
			else
			{
				$_GET['act'] = $v;
				$current_url.="/".$v;
			}			
				
		}
		elseif($k==2)
		{
			//扩展参数
			$ext_param = explode("-",$v);
			
			foreach($ext_param as $kk=>$vv)
			{
				if($kk%2==0)
				{
					if(preg_match("/(\w+)\[(\w+)\]/",$vv,$matches))
					{
						$_GET[$matches[1]][$matches[2]] = $ext_param[$kk+1];
					}
					else
						$_GET[$ext_param[$kk]] = $ext_param[$kk+1];
						
					if($ext_param[$kk]!="p")
					{
						if($kk==0)$current_url.="/";
						$current_url.=$ext_param[$kk];
						$current_url.="-".$ext_param[$kk+1]."-";
					}
				}
			}
		}
	}
	$current_url = substr($current_url,-1)=="-"?substr($current_url,0,-1):$current_url;


	$domain = get_host();
	$domain_root = $GLOBALS['distribution_cfg']['DOMAIN_ROOT'];
	if($domain_root!=""&&strpos($domain,".".$domain_root))
	{
		$city = str_replace(".".$domain_root,"",$domain);
		if($city!='')
			$_GET['city'] = $city;
	}
}
unset($_REQUEST['rewrite_param']);
unset($_GET['rewrite_param']);


$_REQUEST = array_merge($_GET,$_POST);
filter_request($_REQUEST);


?>