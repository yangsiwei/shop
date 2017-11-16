<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



class uc_msgModule extends MainBaseModule
{
	public function index()
	{
		global_run();
		init_app_page();
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}	
		
		
		$GLOBALS['tmpl']->assign("page_title","我的消息");
		$user_info = $GLOBALS['user_info'];
		
		require_once APP_ROOT_PATH."app/Lib/page.php";
		$page_size = app_conf("PAGE_SIZE");
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$sql = "select * from ".DB_PREFIX."msg_box where user_id = ".$GLOBALS['user_info']['id']." and is_delete = 0 order by create_time desc limit ".$limit;
		$sql_count = "select count(*) from ".DB_PREFIX."msg_box where user_id = ".$GLOBALS['user_info']['id']." and is_delete = 0 ";
		$list = $GLOBALS['db']->getAll($sql);
		$ids[] = 0;
		foreach($list as $k=>$v)
		{
			$list[$k] = load_msg($v['type'], $v);
			$list[$k]['create_time'] = to_date($v['create_time']);
			$ids[] = $v['id'];
		}
		
		$count = $GLOBALS['db']->getOne($sql_count);
		
		$page = new Page($count,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$GLOBALS['tmpl']->assign("list",$list);
		$ids_str = implode(",", $ids);
 		$GLOBALS['db']->query("update ".DB_PREFIX."msg_box set is_read = 1 where user_id = ".$GLOBALS['user_info']['id']." and id in (".$ids_str.")");
		
 		require_once APP_ROOT_PATH."system/model/user.php";
 		load_user($GLOBALS['user_info']['id'],true);
 		
 		assign_uc_nav_list();
		$GLOBALS['tmpl']->assign("user_info",$user_info);
		$GLOBALS['tmpl']->display("uc/uc_msg.html");
	}
	
	
	public function remove_msg()
	{
		global_run();

		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			$data['status'] = 1000;
			ajax_return($data);
		}
		else
		{
			$id = intval($_REQUEST['id']);
			$GLOBALS['db']->query("update ".DB_PREFIX."msg_box set is_delete = 1 where id = ".$id." and user_id = ".$GLOBALS['user_info']['id']);
			if($GLOBALS['db']->affected_rows())
			{
				$data['status'] = 1;
				ajax_return($data);
			}
			else
			{
				$data['status'] = 0;
				$data['info'] = "删除失败";
				ajax_return($data);
			}
			
		}
	}
	
	
}
?>