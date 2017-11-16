<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

//app项目用到的函数库



/**
 * 获取前次停留的页面地址
 * @return string url
 */
function get_gopreview()
{
	$gopreview = es_session::get("gopreview");
	if($gopreview==get_current_url())
	{
		$gopreview = url("index");
	}
	if(empty($gopreview))
		$gopreview = url("index");
	return $gopreview;
}


/**
 * 获取当前的url地址，包含分页
 * @return string
 */
function get_current_url()
{
	$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");
	$parse = parse_url($url);
	if(isset($parse['query'])) {
		parse_str($parse['query'],$params);
		$url   =  $parse['path'].'?'.http_build_query($params);
	}
	if(app_conf("URL_MODEL")==1)
	{
		$url = $GLOBALS['current_url'];
		if(isset($_REQUEST['p'])&&intval($_REQUEST['p'])>0)
		{
			$req = $_REQUEST;
			unset($req['ctl']);
			unset($req['act']);
			unset($req['p']);
			if(count($req)>0)
			{
				$url.="-p-".intval($_REQUEST['p']);
			}
			else
			{
				$url.="/p-".intval($_REQUEST['p']);
			}
		}
	}
	if(substr($url, -1)=="?")$url = substr($url,0,-1);
	return $url;
}

/**
 * 将当前页设为回跳的上一页地址
 */
function set_gopreview()
{
	$url =  get_current_url();
	es_session::set("gopreview",$url);
}



/**
 * 跳转回上一页
 */
function app_redirect_preview()
{
	app_redirect(get_gopreview());
}



//显示错误
function showErr($msg,$ajax=0,$jump='',$stay=0)
{
	if($ajax==1)
	{
		$result['status'] = 0;
		$result['info'] = $msg;
		$result['jump'] = $jump;
		ajax_return($result);
	}
	else
	{

		$GLOBALS['tmpl']->assign('page_title',$GLOBALS['lang']['ERROR_TITLE']);
		$GLOBALS['tmpl']->assign('msg',$msg);
		if($jump=='')
		{
			$jump = get_gopreview();
		}

		$GLOBALS['tmpl']->assign('jump',$jump);
		$GLOBALS['tmpl']->assign("stay",$stay);
		$GLOBALS['tmpl']->display("msg_page.html");
		exit;
	}
}

//显示成功
function showSuccess($msg,$ajax=0,$jump='',$stay=0)
{
	if($ajax==1)
	{
		$result['status'] = 1;
		$result['info'] = $msg;
		$result['jump'] = $jump;
		ajax_return($result);
	}
	else
	{
		$GLOBALS['tmpl']->assign('page_title',$GLOBALS['lang']['SUCCESS_TITLE']);
		$GLOBALS['tmpl']->assign('msg',$msg);
		if($jump=='')
		{
			$jump = get_gopreview();
		}
		$GLOBALS['tmpl']->assign('jump',$jump);
		$GLOBALS['tmpl']->assign("stay",$stay);
		$GLOBALS['tmpl']->display("msg_page.html");
		exit;
	}
}



//解析URL标签
// $str = u:shop|acate#index|id=10&name=abc
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
	$GLOBALS[$key]= url($app_index,$route,$param);
	set_dynamic_cache($key,$GLOBALS[$key]);
	return $GLOBALS[$key];
}

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

	return "<div style='width:940px; padding:10px; line-height:22px; border:1px solid #ccc; text-align:left; margin:30px auto; font-size:14px; color:#999; height:150px; overflow-y:auto;'>".$str."</div>";
}




/**
 * 前台初始化图片控件
 * @param string $type 对应类中上传的目录类型代码须补充
 * @return string
 */
function load_web_uploadimg($type){
	$tmpl_path = SITE_DOMAIN.APP_ROOT."/app/Tpl/fanwe";
	$plugins_path =$tmpl_path."/js/utils/kindeditor/plugins/";
	$upload_json = url("index","upload#".$type);
	return '<script>
			if(K == undefined)
				var K = KindEditor;
					var editor = K.editor({
					allowFileManager : true,
					pluginsPath:"'.$plugins_path.'",
					uploadJson:"'.$upload_json.'"
				});</script>';
}


function load_compatible()
{
	return "";
	//return '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />';
}

function get_abs_img_root($content)
{
    return format_image_path($content);
}

function get_muser_avatar($id,$type)
{
    return get_user_avatar($id,$type);
}

?>