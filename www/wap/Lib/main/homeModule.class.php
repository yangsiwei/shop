<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

require_once APP_ROOT_PATH."wap/Lib/page.php";
class homeModule extends MainBaseModule
{	
	function __construct()
	{
		parent::__construct();
		global_run();
		init_app_page();
		
		// 商家不存在则跳转至wap首页
		if(!$GLOBALS['supplier_info']){
			app_redirect(wap_url("index","index"));
		}
		
	}
	
	// 商家主页
	public function index()
	{
		$this->tuan();
	}
	
	// 团购列表
	public function tuan(){
		
		$param = array();
		$param['page'] = intval($_REQUEST['page']);
		$data = call_api_core("home","tuan",$param);

		if(isset($data['page']) && is_array($data['page'])){

			//感觉这个分页有问题,查询条件处理;分页数10,需要与sjmpai同步,是否要将分页处理移到sjmapi中?或换成下拉加载的方式,这样就不要用到分页了
			$page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
			//$page->parameter
			$p  =  $page->show();
			//print_r($p);exit;
			$GLOBALS['tmpl']->assign('pages',$p);
		}

		$_checked["tuan"] = 1;
		$GLOBALS['tmpl']->assign("_checked",$_checked);
		
		$GLOBALS['tmpl']->assign("data",$data);		
// 		print_r($data);exit;

		//统计数据
		$data_goods = call_api_core("home","goods",$param);
		$data_youhui = call_api_core("home","youhui",$param);
		$data_event = call_api_core("home","event",$param);
		$GLOBALS['tmpl']->assign("tuan_total",$data['page']['data_total']);
		$GLOBALS['tmpl']->assign("goods_total",$data_goods['page']['data_total']);
		$GLOBALS['tmpl']->assign("youhui_total",$data_youhui['page']['data_total']);
		$GLOBALS['tmpl']->assign("event_total",$data_event['page']['data_total']);
		
		$GLOBALS['tmpl']->display("home_tuan.html");
	}
	
	// 商品列表
	public function goods(){
		
		$param = array();
		$param['page'] = intval($_REQUEST['page']);
		$request = $param;
		$data = call_api_core("home","goods",$param);
		
		if(isset($data['page']) && is_array($data['page'])){

			//感觉这个分页有问题,查询条件处理;分页数10,需要与sjmpai同步,是否要将分页处理移到sjmapi中?或换成下拉加载的方式,这样就不要用到分页了
			$page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
			//$page->parameter
			$p  =  $page->show();
			//print_r($p);exit;
			$GLOBALS['tmpl']->assign('pages',$p);
		}
		
		$_checked["goods"] = 1;
		$GLOBALS['tmpl']->assign("_checked",$_checked);
		
		$GLOBALS['tmpl']->assign("data",$data);	

		//统计数据
		$data_tuan = call_api_core("home","tuan",$param);
		$data_youhui = call_api_core("home","youhui",$param);
		$data_event = call_api_core("home","event",$param);
		$GLOBALS['tmpl']->assign("tuan_total",$data_tuan['page']['data_total']);
		$GLOBALS['tmpl']->assign("goods_total",$data['page']['data_total']);
		$GLOBALS['tmpl']->assign("youhui_total",$data_youhui['page']['data_total']);
		$GLOBALS['tmpl']->assign("event_total",$data_event['page']['data_total']);
		
		$GLOBALS['tmpl']->display("home_goods.html");
	}
	
	// 优惠
	public function youhui(){
		
		$param = array();
		$param['page'] = intval($_REQUEST['page']);
		$request = $param;

		$data = call_api_core("home","youhui",$param);
// 		print_r($data);exit;
				
		if(isset($data['page']) && is_array($data['page'])){

			//感觉这个分页有问题,查询条件处理;分页数10,需要与sjmpai同步,是否要将分页处理移到sjmapi中?或换成下拉加载的方式,这样就不要用到分页了
			$page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
			//$page->parameter
			$p  =  $page->show();
			//print_r($p);exit;
			$GLOBALS['tmpl']->assign('pages',$p);
		}
		
		$_checked["youhui"] = 1;
		$GLOBALS['tmpl']->assign("_checked",$_checked);
		
		$GLOBALS['tmpl']->assign("data",$data);		
		
		//统计数据
		$data_tuan = call_api_core("home","tuan",$param);
		$data_goods = call_api_core("home","goods",$param);
		$data_event = call_api_core("home","event",$param);
		$GLOBALS['tmpl']->assign("tuan_total",$data_tuan['page']['data_total']);
		$GLOBALS['tmpl']->assign("goods_total",$data_goods['page']['data_total']);
		$GLOBALS['tmpl']->assign("youhui_total",$data['page']['data_total']);
		$GLOBALS['tmpl']->assign("event_total",$data_event['page']['data_total']);
		
		$GLOBALS['tmpl']->display("home_youhuis.html");
	}
	
	// 活动
	public function event(){
		
		$param = array();
		$param['page'] = intval($_REQUEST['page']);
		$request = $param;
	
		$data = call_api_core("home","event",$param);
			
		if(isset($data['page']) && is_array($data['page'])){

			//感觉这个分页有问题,查询条件处理;分页数10,需要与sjmpai同步,是否要将分页处理移到sjmapi中?或换成下拉加载的方式,这样就不要用到分页了
			$page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
			//$page->parameter
			$p  =  $page->show();
			//print_r($p);exit;
			$GLOBALS['tmpl']->assign('pages',$p);
		}
		
		$GLOBALS['tmpl']->assign("data",$data);		
		
		$_checked["event"] = 1;
		$GLOBALS['tmpl']->assign("_checked",$_checked);
		
		//统计数据
		$data_tuan = call_api_core("home","tuan",$param);
		$data_goods = call_api_core("home","goods",$param);
		$data_youhui = call_api_core("home","youhui",$param);
		$GLOBALS['tmpl']->assign("tuan_total",$data_tuan['page']['data_total']);
		$GLOBALS['tmpl']->assign("goods_total",$data_goods['page']['data_total']);
		$GLOBALS['tmpl']->assign("youhui_total",$data_youhui['page']['data_total']);
		$GLOBALS['tmpl']->assign("event_total",$data['page']['data_total']);
		
		$GLOBALS['tmpl']->display("home_events.html");
	}
	
}
?>