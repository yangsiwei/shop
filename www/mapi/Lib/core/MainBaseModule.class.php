<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class MainBaseApiModule{

	public function __construct()
	{		

			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/mapi/page_static_cache/");
			$GLOBALS['dynamic_cache'] = $GLOBALS['cache']->get("APP_DYNAMIC_CACHE_".APP_INDEX."_".MODULE_NAME."_".ACTION_NAME);
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/mapi/avatar_cache/");
			$GLOBALS['dynamic_avatar_cache'] = $GLOBALS['cache']->get("AVATAR_DYNAMIC_CACHE"); //头像的动态缓存
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/mapi/");
			
			if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']=="ES_FILE")
			{
				//logger::write($GLOBALS['distribution_cfg']['OSS_DOMAIN']."/es_file.php");
				global $syn_image_ci;
				global $curl_param;
				//global $syn_image_idx;
				$syn_image_idx = 0;
				$syn_image_ci  =  curl_init($GLOBALS['distribution_cfg']['OSS_DOMAIN']."/es_file.php");
				curl_setopt($syn_image_ci, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($syn_image_ci, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($syn_image_ci, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($syn_image_ci, CURLOPT_NOPROGRESS, true);
				curl_setopt($syn_image_ci, CURLOPT_HEADER, false);
				curl_setopt($syn_image_ci, CURLOPT_POST, TRUE);
				curl_setopt($syn_image_ci, CURLOPT_TIMEOUT, 1);
				curl_setopt($syn_image_ci, CURLOPT_TIMECONDITION, 1);
				$curl_param['username'] = $GLOBALS['distribution_cfg']['OSS_ACCESS_ID'];
				$curl_param['password'] = $GLOBALS['distribution_cfg']['OSS_ACCESS_KEY'];
				$curl_param['act'] = 2;
			}
	
	}

	public function index()
	{
		die("invalid access");
	}
	public function __destruct()
	{	
		if(isset($GLOBALS['cache']))
		{
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/mapi/page_static_cache/");
			$GLOBALS['cache']->set("APP_DYNAMIC_CACHE_".APP_INDEX."_".MODULE_NAME."_".ACTION_NAME,$GLOBALS['dynamic_cache']);
			if(count($GLOBALS['dynamic_avatar_cache'])<=500)
			{
				$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/mapi/avatar_cache/");
				$GLOBALS['cache']->set("AVATAR_DYNAMIC_CACHE",$GLOBALS['dynamic_avatar_cache']); //头像的动态缓存
			}
		}	
		
		if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']=="ES_FILE")
		{
			if(count($GLOBALS['curl_param']['images'])>0)
			{
				$GLOBALS['curl_param']['images'] =  base64_encode(serialize($GLOBALS['curl_param']['images']));
				curl_setopt($GLOBALS['syn_image_ci'], CURLOPT_POSTFIELDS, $GLOBALS['curl_param']);
				$rss = curl_exec($GLOBALS['syn_image_ci']);
			}
			curl_close($GLOBALS['syn_image_ci']);
		}
		unset($this);
	}
}
?>