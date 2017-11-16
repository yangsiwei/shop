<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class MainBaseModule{
	public function __construct()
	{		
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".CACHE_SUBDIR."/page_static_cache/");
		$GLOBALS['dynamic_cache'] = $GLOBALS['cache']->get("APP_DYNAMIC_CACHE_".APP_INDEX."_".MODULE_NAME."_".ACTION_NAME);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".CACHE_SUBDIR."/avatar_cache/");
		$GLOBALS['dynamic_avatar_cache'] = $GLOBALS['cache']->get("AVATAR_DYNAMIC_CACHE"); //头像的动态缓存


		$GLOBALS['tmpl']->assign("MODULE_NAME",MODULE_NAME);
		$GLOBALS['tmpl']->assign("ACTION_NAME",ACTION_NAME);		
		
		
		/* 返回上一页后续再做*/
		if(
				MODULE_NAME=="index"&&ACTION_NAME=="index"||
				MODULE_NAME=="cart"&&ACTION_NAME=="check"||
				MODULE_NAME=="cart"&&ACTION_NAME=="index"||
				MODULE_NAME=="duobao"&&ACTION_NAME=="index"||				
				MODULE_NAME=="anno"&&ACTION_NAME=="index"||
				MODULE_NAME=="duobaos"&&ACTION_NAME=="index"||
				MODULE_NAME=="duobaost"&&ACTION_NAME=="index"||
				MODULE_NAME=="uc_duobao_record"&&ACTION_NAME=="index"||
				MODULE_NAME=="duobaosh"&&ACTION_NAME=="index"||
                MODULE_NAME=="pk"&&ACTION_NAME=="index"||
                MODULE_NAME=="number_choose"&&ACTION_NAME=="index"||
                MODULE_NAME=="pk"&&ACTION_NAME=="choose_pkgoods"||
                MODULE_NAME=="topspeed"&&ACTION_NAME=="index"||
		        MODULE_NAME=="redset"&&ACTION_NAME=="index"
				)
		{
            if(MODULE_NAME=="uc_address"&&ACTION_NAME=="index"&&intval($_REQUEST['cart'])==1)
            {

            }
            else
			set_gopreview();
		}
	}

	public function index()
	{
		showErr("invalid access");
	}
	public function __destruct()
	{	
		if(isset($GLOBALS['cache']))
		{
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".CACHE_SUBDIR."/page_static_cache/");
			$GLOBALS['cache']->set("APP_DYNAMIC_CACHE_".APP_INDEX."_".MODULE_NAME."_".ACTION_NAME,$GLOBALS['dynamic_cache']);
			if(count($GLOBALS['dynamic_avatar_cache'])<=500)
			{
				$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".CACHE_SUBDIR."/avatar_cache/");
				$GLOBALS['cache']->set("AVATAR_DYNAMIC_CACHE",$GLOBALS['dynamic_avatar_cache']); //头像的动态缓存
			}
		}	
		if($GLOBALS['refresh_page']&&!IS_DEBUG)
		{
			echo "<script>location.reload();</script>";
			exit;
		}
		unset($this);
	}
}
?>