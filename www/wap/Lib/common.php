<?php

//解析URL标签
// $str = u:wap#index|id=10&name=abc
function parse_url_tag($str)
{
	$key = md5("URL_TAG_".$str);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}

	$url = load_dynamic_cache($key);
	$url=false;
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}
	$str = substr($str,2);
	$str_array = explode("|",$str);
	$app_index = $str_array[0];
	$route = $str_array[1];
	$param_tmp = explode("&",$str_array[2]);
	$param = array();

	foreach($param_tmp as $item)
	{
		if($item!='')
			$item_arr = explode("=",$item);
		if($item_arr[0]&&$item_arr[1])
			$param[$item_arr[0]] = $item_arr[1];
	}
	
	$GLOBALS[$key]= wap_url($app_index,$route,$param);
	set_dynamic_cache($key,$GLOBALS[$key]);
	return $GLOBALS[$key];
}
//封装url



/**
 * 获得查询次数以及查询时间
 *
 * @access  public
 * @return  string
 */
function run_info()
{

	if(!SHOW_DEBUG)return "";

	$query_time = number_format($GLOBALS['db']->queryTime,6);

	if($GLOBALS['begin_run_time']==''||$GLOBALS['begin_run_time']==0)
	{
		$run_time = 0;
	}
	else
	{
		if (PHP_VERSION >= '5.0.0')
		{
			$run_time = number_format(microtime(true) - $GLOBALS['begin_run_time'], 6);
		}
		else
		{
			list($now_usec, $now_sec)     = explode(' ', microtime());
			list($start_usec, $start_sec) = explode(' ', $GLOBALS['begin_run_time']);
			$run_time = number_format(($now_sec - $start_sec) + ($now_usec - $start_usec), 6);
		}
	}

	/* 内存占用情况 */
	if (function_exists('memory_get_usage'))
	{
		$unit=array('B','KB','MB','GB');
		$size = memory_get_usage();
		$used = @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		$memory_usage = lang("MEMORY_USED",$used);
	}
	else
	{
		$memory_usage = '';
	}

	/* 是否启用了 gzip */
	$enabled_gzip = (app_conf("GZIP_ON") && function_exists('ob_gzhandler'));
	$gzip_enabled = $enabled_gzip ? lang("GZIP_ON") : lang("GZIP_OFF");

	$str = lang("QUERY_INFO_STR",$GLOBALS['db']->queryCount, $query_time,$gzip_enabled,$memory_usage,$run_time);

	foreach($GLOBALS['db']->queryLog as $K=>$sql)
	{
		if($K==0)$str.="<br />SQL语句列表：";
		$str.="<br />行".($K+1).":".$sql;
	}

	return "<div style='width:640px; padding:10px; line-height:22px; border:1px solid #ccc; text-align:left; margin:30px auto; font-size:14px; color:#999; height:150px; overflow-y:auto;'>".$str."</div>";
}


//显示错误
function showErr($msg,$ajax=0,$jump='',$stay=0)
{
	if($jump=="")
		$jump = get_gopreview();
	echo "<script>alert('".$msg."');location.href='".$jump."';</script>";exit;
}

//显示成功
function showSuccess($msg,$ajax=0,$jump='',$stay=0)
{
	if($jump=="")
		$jump = get_gopreview();
	echo "<script>alert('".$msg."');location.href='".$jump."';</script>";exit;
}

//显示确定
function showConfirm($msg,$ajax=0,$jump_confirm='',$jump_cancel='',$stay=0)
{
	if($jump=="")
		$jump = get_gopreview();
	echo "<script>if(confirm('".$msg."')){
                location.href='".$jump_confirm."';
              }else{
				location.href='".$jump_cancel."';
    }</script>";exit;
}


function agentArr(){
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$agent_array = array();
	if($agent){
		$agent_arr = explode(" ",$agent);
		foreach($agent_arr as $k=>$v){
			$kkv = explode("/",$v);
			$agent_array[$kkv[0]] = strim($kkv[1]);
		}
	}
	return $agent_array;
}


function get_sdk_guid(){
	$agent = agentArr();
	return $agent['sdk_guid'];
}

function isApp(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$is_app = strpos($agent, 'fanwe_app_sdk') ? true : false ;
	if($is_app){
		return true;
	}else{
		return false;
	}
}
?>