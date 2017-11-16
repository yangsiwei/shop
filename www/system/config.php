<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

// 前后台加载的系统配置文件


// 加载数据库中的配置与数据库配置
if(file_exists(APP_ROOT_PATH.'public/db_config.php'))
{
	$db_config	=	require APP_ROOT_PATH.'public/db_config.php';
}


if($GLOBALS['distribution_cfg']['CACHE_TYPE']!="File")
{
	$cfg_link = @mysql_connect($db_config['DB_HOST'].":".$db_config['DB_PORT'], $db_config['DB_USER'], $db_config['DB_PWD'], true);
	
	$db_version = @mysql_get_server_info($cfg_link);
	/* 如果mysql 版本是 4.1+ 以上，需要对字符集进行初始化 */
	if ($db_version > '4.1')
	{
		mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary", $cfg_link);
		if ($db_version > '5.0.1')
		{
			mysql_query("SET sql_mode=''",$cfg_link);
		}
	}	
	mysql_select_db($db_config['DB_NAME'], $cfg_link);
	$query = mysql_query("select * from ".$db_config['DB_PREFIX']."conf", $cfg_link);
	while ($row = mysql_fetch_assoc($query))
	{
		$db_conf[$row['name']] = addslashes($row['value']);
	}
	@mysql_close($cfg_link);
}
else
{
	//加载系统配置信息
	if(file_exists(APP_ROOT_PATH.'public/sys_config.php'))
	{
		$db_conf	=	require APP_ROOT_PATH.'public/sys_config.php';
	}
}

//加载系统信息
if(file_exists(APP_ROOT_PATH.'public/version.php'))
{
	$version	=	require APP_ROOT_PATH.'public/version.php';
}

//加载时区信息
if($GLOBALS['distribution_cfg']['CACHE_TYPE']!="File")
{
	$var = array(
			'0'	=>	'UTC',
			'8'	=>	'PRC',
	);
	$zone = $db_conf['TIME_ZONE'];
	$timezone['DEFAULT_TIMEZONE'] = $var[$zone];
}
else
{
	if(file_exists(APP_ROOT_PATH.'public/timezone_config.php'))
	{
		$timezone	=	require APP_ROOT_PATH.'public/timezone_config.php';
	}
}

if(is_array($db_config))
$config = array_merge($db_conf,$db_config,$version,$timezone);
elseif(is_array($db_conf))
$config = array_merge($db_conf,$version,$timezone);
else
$config = array_merge($version,$timezone);

$config['AUTH_KEY'] = "fanwe";
$config['ALLOW_IMAGE_EXT'] = "jpg,gif,png,jpeg";

return $config;
?>