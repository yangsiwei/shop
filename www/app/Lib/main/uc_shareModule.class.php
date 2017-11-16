<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



class uc_shareModule extends MainBaseModule
{
	public function index()
	{
		
		global_run();
		init_app_page();
		assign_uc_nav_list();//左侧导航菜单
		
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		
		
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
		    app_redirect(url("index","user#login"));
		}
		$user_info = $GLOBALS['user_info'];
		
		//未晒单的数量
		$not_share_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order_item where type=0 and is_send_share=0 and user_id=".$user_info['id']);
		
		
		
		//已经晒单的数据
		$page =intval($_REQUEST['p']);
		$page_size =app_conf("PAGE_SIZE");
		
		//分页
		require_once APP_ROOT_PATH."app/Lib/page.php";
		if ($page == 0) $page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		$GLOBALS['tmpl']->assign("page",$page);
		
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."share where user_id = ".$user_info['id']."");
		
		$page_size = PIN_PAGE_SIZE;
		$page = new Page($count,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$remain_count = $count-($page-1)*$page_size;  //从当前页算起剩余的数量
		$remain_page = ceil($remain_count/$page_size); //剩余的页数
		if($remain_page == 1)
		{
		    //末页
		    $step_size = ceil($remain_count/PIN_SECTOR);
		}
		else
		{
		    $step_size = ceil(PIN_PAGE_SIZE/PIN_SECTOR);
		}
		$GLOBALS['tmpl']->assign('step_size',$step_size);

		foreach($list as $k=>$v){
		    $img_list = array();
		    $img_list = unserialize($v['image_list']);
		    $list[$k]['img'] = $img_list[0];
		    $list[$k]['duobao_item'] = unserialize($v['cache_duobao_item_data']);
		}

		
		$GLOBALS['tmpl']->assign("page_title","我的晒单");
		
		$GLOBALS['tmpl']->assign('pages', $p);
		$GLOBALS['tmpl']->assign("count", $count);
		$GLOBALS['tmpl']->assign("not_share_count",$not_share_count);
		$GLOBALS['tmpl']->display("uc/uc_share.html");
		
	}
	
	public function add()
	{
		global_run();
		init_app_page();
		assign_uc_nav_list();//左侧导航菜单
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}
		
		$id = intval($_REQUEST['id']); //订单对象ID
		//判断是否有晒单信息，没有则创建一个
		if(!$id){
		    showErr("数据不存在",0,url("index","uc_luck#index"));
		    exit;
		}
		$order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id=".$id." and user_id =".$GLOBALS['user_info']['id']);
		if(empty($order_item)){
		    showErr("数据不存在",0,url("index","uc_luck#index"));
		    exit;
		}
		if($order_item['is_arrival']==0 || $order_item['delivery_status']==0){
		    showErr("暂时无法晒单",0,url("index","uc_luck#index"));
		    exit;
		}
		
		if($order_item['is_send_share']){
		    showErr("已经发布过晒单",0,url("index","uc_luck#index"));
		    exit;
		}
		$duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$order_item['duobao_item_id']." and luck_user_id=".$GLOBALS['user_info']['id']);

	    

		$GLOBALS['tmpl']->assign("page_title","添加晒单");
		$GLOBALS['tmpl']->assign("duobao_item",$duobao_item);
		$GLOBALS['tmpl']->assign("img_max_size",round(app_conf("MAX_IMAGE_SIZE")/1000000));
		$GLOBALS['tmpl']->display("uc/uc_share_add.html");	
	}
	
	public function save()	
	{
		global_run();
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			$result['status'] = 1000;
			ajax_return($result);	
		}
		
		
		$duobao_item_id = intval($_REQUEST['duobao_item_id']);
		$title = strim($_REQUEST['title']);
		$content = strim($_REQUEST['content']);

		if(!$duobao_item_id){
		    $result['status'] = 0;
		    $result['info'] = "数据不存在";
		    ajax_return($result);
		}
		if(mb_strlen($title,'UTF-8')<6){
		    $result['status'] = 0;
		    $result['info'] = "请留下一个最少6个字的晒单主题吧~";
		    ajax_return($result);
		}
		
		if(mb_strlen($content,'UTF-8')<5){
		    $result['status'] = 0;
		    $result['info'] = "幸运感言，字不在多最少5个~";
		    ajax_return($result);
		}
		
		
		$duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$duobao_item_id." and luck_user_id =".$GLOBALS['user_info']['id']);
		if(empty($duobao_item)){
		    $result['status'] = 0;
		    $result['info'] = "数据不存在";
		    ajax_return($result);
		}
		
		if($duobao_item['is_send_share']){
		    $result['status'] = 0;
		    $result['info'] = "已经发布过晒单";
		    ajax_return($result);
		}

		require_once APP_ROOT_PATH.'/system/model/share.php';
		$share_obj = new share();
		
		$attach_list = get_share_attach_list();
		$id = $share_obj->insert_share($duobao_item, $title, $content,$attach_list);
		
		$result['status'] = 1;
		$result['info'] = "晒单成功  ^_^ ";
		$result['jump'] = url('index','uc_share#detail',array("id"=>$id));
		ajax_return($result);		
		
	}
	
}
?>