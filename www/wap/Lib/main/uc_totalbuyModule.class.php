<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class uc_totalbuyModule extends MainBaseModule
{

	public function index()
	{
		global_run();
		init_app_page();
		
		$param=array();
		$param['page'] = intval($_REQUEST['page']);
		$param['data_id'] = intval($_REQUEST['data_id']);
		$param['log_type'] = intval($_REQUEST['log_type']);
		$data = call_api_core("uc_totalbuy","index",$param);
		
		if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
		    app_redirect(wap_url("index","user#login"));
		}
		
		foreach($data['list'] as $k=>$v){
		    	
		    $data['list'][$k]['url']=wap_url("index","duobao",array("data_id"=>$v['duobao_item_id']));
		}

		$page = new Page($data['page']['data_total'], $data['page']['page_size']); // 初始化分页对象
		$p = $page->show();
		
		$GLOBALS['tmpl']->assign('pages', $p);
		$GLOBALS['tmpl']->assign("data",$data);
		$GLOBALS['tmpl']->display("uc_totalbuy.html");
	}
	
	public function detail()
	{
	    global_run();
	    init_app_page();
	    $param['id'] = intval($_REQUEST['id']);
	    $data = call_api_core("uc_totalbuy","detail",$param);
	    
	    
	
	    $GLOBALS['tmpl']->assign("data",$data);
	    $GLOBALS['tmpl']->display("uc_totalbuy_detail.html");
	}
	
	
	
	
	public function verify_delivery()
	{
	    global_run();
	    init_app_page();
	    $param['id'] = intval($_REQUEST['id']);
	    $data = call_api_core("uc_totalbuy","verify_delivery",$param);
	    
	    if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        app_redirect(wap_url("index","user#login"));
	    }
	    ajax_return($data);
	}
	
	public function close()
	{
	    global_run();
	    init_app_page();
	    $param['id'] = intval($_REQUEST['id']);
	    $data = call_api_core("uc_totalbuy","close",$param);
	     
	    if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        app_redirect(wap_url("index","user#login"));
	    }
	    ajax_return($data);
	}
}
?>
