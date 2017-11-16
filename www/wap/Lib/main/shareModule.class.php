<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

/**
 * 晒单
 * @author jobin.lin
 *
 */

class shareModule extends MainBaseModule
{
	public function index()
	{
		global_run();
		
		init_app_page();
		$param = array();
		$param['page'] = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$param['data_id']      = intval($_REQUEST['data_id']);
		$data = call_api_core( "share", "index", $param );

		if(isset($data['page']) && is_array($data['page'])){
			require_once APP_ROOT_PATH."wap/Lib/page.php";
			$page = new Page($data['page']['total'], $data['page']['page_size']); // 初始化分页对象
			$p = $page->show();
			$GLOBALS['tmpl']->assign('pages', $p);
		
		}	
		$GLOBALS['tmpl']->assign("data",$data);
		$GLOBALS['tmpl']->assign("list",$data['list']['list']);
		$GLOBALS['tmpl']->display("share_index.html");
	}
	
	public function detail(){
	   
	    global_run();
	    init_app_page();
	    $param = array();
	    $param['id'] = intval($_REQUEST['id']);
	    $data = call_api_core("share","detail",$param);
		 
	    if ($data['status']==0){
	        app_redirect(wap_url("index","share#index"));
	    }
		
	    $GLOBALS['tmpl']->assign("data",$data);
		$GLOBALS['tmpl']->display("share_detail.html");
	}
}
?>