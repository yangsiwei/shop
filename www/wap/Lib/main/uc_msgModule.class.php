<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class uc_msgModule extends MainBaseModule
{

	public function index()
	{
		global_run();		
		init_app_page();

		
		$param=array();	
		$param['page'] = intval($_REQUEST['page']);	
		$data = call_api_core("uc_msg","index",$param);
		
		if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
			app_redirect(wap_url("index","user#login"));
		}
		

		if(isset($data['page']) && is_array($data['page'])){
			$page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
			$p  =  $page->show();
			$GLOBALS['tmpl']->assign('pages',$p);
		}

		//print_r($data);exit;
		
		$GLOBALS['tmpl']->assign("data",$data);	
		$GLOBALS['tmpl']->display("uc_msg.html");
	}
	
	public function remove_msg()
	{
		global_run();
		init_app_page();

		$param=array();
		$param['id'] = intval($_REQUEST['id']);		
		$data = call_api_core("uc_msg","remove_msg",$param);
		if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
			app_redirect(wap_url("index","user#login"));
		}
		if($data['del_status']==1){
					$result['status'] = 1;
					$result['url'] = wap_url("index","uc_msg");
					ajax_return($result);			
		}else{
					$result['status'] =0;					
					ajax_return($result);		
		}
	}
	


}
?>