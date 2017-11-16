<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
error_reporting(E_ALL^E_NOTICE^E_WARNING);
if (PHP_VERSION >= '5.0.0')
{
	$begin_run_time = @microtime(true);
	if(PHP_VERSION>='5.5.0')
	{
		error_reporting(E_ALL^E_DEPRECATED^E_STRICT^E_NOTICE^E_WARNING);
	}
}
else
{
	$begin_run_time = @microtime();
}
@set_magic_quotes_runtime (0);
define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()?True:False);
if(!defined('IS_CGI'))
define('IS_CGI',substr(PHP_SAPI, 0,3)=='cgi' ? 1 : 0 );
 if(!defined('_PHP_FILE_')) {
        if(IS_CGI) {
            //CGI/FASTCGI模式下
            $_temp  = explode('.php',$_SERVER["PHP_SELF"]);
            define('_PHP_FILE_',  rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/'));
        }else {
            define('_PHP_FILE_',  rtrim($_SERVER["SCRIPT_NAME"],'/'));
        }
    }
if(!defined('APP_ROOT')) {
        // 网站URL根目录
        $_root = dirname(_PHP_FILE_);
        $_root = (($_root=='/' || $_root=='\\')?'':$_root);
        if(defined("FILE_PATH"))
        $_root = str_replace(FILE_PATH,"",$_root);
        define('APP_ROOT', $_root  );
}
if(!defined('APP_ROOT_PATH')) 
define('APP_ROOT_PATH', str_replace('system/system_init.php', '', str_replace('\\', '/', __FILE__)));
//define('LICENSE_PATH',APP_ROOT_PATH);

//定义$_SERVER['REQUEST_URI']兼容性
if (!isset($_SERVER['REQUEST_URI']))
{
	if (isset($_SERVER['argv']))
	{
		$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
	}
	else
	{
		$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
	}
	$_SERVER['REQUEST_URI'] = $uri;
}



require_once APP_ROOT_PATH."system/common.php"; //加载全局函数库

filter_request($_GET);
filter_request($_POST);
$_REQUEST = array_merge($_GET,$_POST);
//关于安装的检测
if(!file_exists(APP_ROOT_PATH."public/install.lock"))
{
	app_redirect(APP_ROOT."/install/index.php");
}
if(!defined("IS_INIT")||IS_INIT!=1)
{
	if(!file_exists(APP_ROOT_PATH."public/init.lock"))
	{
		app_redirect(APP_ROOT."/install/init.php");
	}
}

//开始创建runtime目录
$runtime = APP_ROOT_PATH."public/runtime/app/";
if(!file_exists($runtime))@mkdir($runtime,0777);
$runtime = APP_ROOT_PATH."public/runtime/admin/";
if(!file_exists($runtime))@mkdir($runtime,0777);
$runtime = APP_ROOT_PATH."public/runtime/data/";
if(!file_exists($runtime))@mkdir($runtime,0777);
$runtime = APP_ROOT_PATH."public/runtime/statics/";
if(!file_exists($runtime))@mkdir($runtime,0777);

update_sys_config();
$license_file = APP_ROOT_PATH."license.php";
require $license_file;

if(file_exists($license_file))
	require_once $license_file;
else
{
	die('未授权');
}




if(IS_DEBUG)
{
	ini_set("display_errors", true);
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
else
	error_reporting(0);

require_once APP_ROOT_PATH.'system/utils/es_cookie.php';
require_once APP_ROOT_PATH."system/utils/es_session.php";


function get_http()
{
	return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
}
function get_domain()
{
	/* 协议 */
	$protocol = get_http();

	/* 域名或IP地址 */
	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
	{
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	elseif (isset($_SERVER['HTTP_HOST']))
	{
		$host = $_SERVER['HTTP_HOST'];
	}
	else
	{
		/* 端口 */
		if (isset($_SERVER['SERVER_PORT']))
		{
			$port = ':' . $_SERVER['SERVER_PORT'];

			if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
			{
				$port = '';
			}
		}
		else
		{
			$port = '';
		}

		if (isset($_SERVER['SERVER_NAME']))
		{
			$host = $_SERVER['SERVER_NAME'] . $port;
		}
		elseif (isset($_SERVER['SERVER_ADDR']))
		{
			$host = $_SERVER['SERVER_ADDR'] . $port;
		}
	}

	return $protocol . $host;
}
function get_host()
{


	/* 域名或IP地址 */
	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
	{
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	elseif (isset($_SERVER['HTTP_HOST']))
	{
		$host = $_SERVER['HTTP_HOST'];
	}
	else
	{
		if (isset($_SERVER['SERVER_NAME']))
		{
			$host = $_SERVER['SERVER_NAME'];
		}
		elseif (isset($_SERVER['SERVER_ADDR']))
		{
			$host = $_SERVER['SERVER_ADDR'];
		}
	}
	return $host;
}

require_once APP_ROOT_PATH."system/utils/logger.php";
$app_lib_file = APP_ROOT_PATH."system/common/".APP_TYPE."_libs.php";
if(file_exists($app_lib_file))
{
	require_once $app_lib_file;
}

$refresh_page = false;
?>