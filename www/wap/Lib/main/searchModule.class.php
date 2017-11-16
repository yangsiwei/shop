<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

/**
 * 搜索主页
 * @author jobin.lin
 *
 */
class searchModule extends MainBaseModule
{
	public function index()
	{
		global_run();
		
		init_app_page();
		$data['page_title'] = '搜索';
		
		$ajax		 	= intval($_REQUEST['ajax']);
		$search_type 	= intval($_REQUEST['search_type']);
		$search_keyword = $_POST['keyword']?strim($_POST['keyword']):urldecode(strim($_GET['keyword']));
		$orderby		= strim($_REQUEST['orderby']);	//排序规则
		$page 			= intval($_REQUEST['p']);
		$page			= $page>0?$page:1;

		
		$GLOBALS['tmpl']->assign("hot_kw",$data['hot_kw']);
		$GLOBALS['tmpl']->assign("data",$data);
		$GLOBALS['tmpl']->display("search_index.html");
	}
	
	public function do_search(){
	   
	    $keyword = strim($_REQUEST['keyword']);
	    

	    app_redirect(wap_url("index","duobaos#index",array("keyword"=>$keyword)));
	}
}
?>