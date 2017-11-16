<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class ecvModule extends MainBaseModule
{

	public function index()
	{
		global_run();		
		init_app_page();		

		$param['exchange_sn'] = strim($_REQUEST['sn']); //分类ID

		$request = $param;
		//获取品牌
		$data = call_api_core("ecv","index",$param);

		if($data['user_login_status']!=LOGIN_STATUS_NOLOGIN){
		    $data['is_login'] = 1;
		}


		$GLOBALS['tmpl']->assign("data",$data);	
		$GLOBALS['tmpl']->assign("ajax_url",wap_url("index","ecv"));
		$GLOBALS['tmpl']->display("ecv.html");
	}
	

	
	public function do_snexchange(){
	   
	    global_run();

        /*获取参数*/
	    $exchange_sn = trim($_REQUEST['sn']);
	    $param=array();
	    $param['exchange_sn'] = $exchange_sn;
	    
	    $data = call_api_core("ecv","do_snexchange",$param);
		if ($data['user_login_status']!=LOGIN_STATUS_LOGINED){
			$data['status'] = -1;
			$data['info'] = "请先登录后领取";
	        $data['jump'] = wap_url("index","user#login");
	    }

	    ajax_return($data);
	    
	}
	
	
}
?>