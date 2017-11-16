<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



class home_luckModule extends MainBaseModule
{
	public function index()
	{
		global_run();
		init_app_page();
		
		require_once APP_ROOT_PATH.'system/model/user.php';
		//点用户查看其他用户
		$id = intval($_REQUEST['id']);
		 
		//已登录的用户自己
		$my_user = $GLOBALS['user_info'];
		if($id==$my_user['id']){//为用户自己跳回个人主页
		    app_redirect(url("index","uc_luck"));
		}else{
		    //其他用户
		    $home_user = load_user($id);
		}
		
		if(empty($home_user))
		{
			app_redirect(url("index"));
		}
		
		
		$sql_total = "select count(*) from ".DB_PREFIX."duobao_item where luck_user_id = ".$id;

		$total = $GLOBALS['db']->getOne($sql_total);
		
		//分页
		require_once APP_ROOT_PATH."app/Lib/page.php";
		$page =intval($_REQUEST['p']);
		$page_size =9;
		if ($page == 0) $page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$sql = "select *  from ".DB_PREFIX."duobao_item where luck_user_id = ".$id." order by create_time desc limit ".$limit;
		$list = $GLOBALS['db']->getAll($sql);

		
		$page = new Page($total, $page_size); // 初始化分页对象
		$p = $page->show();
		
		
		$GLOBALS['tmpl']->assign("total",$total);
		$GLOBALS['tmpl']->assign("list",$list);
		$GLOBALS['tmpl']->assign("pages",$p);
		
		$GLOBALS['tmpl']->assign("home_user",$home_user);
		$GLOBALS['tmpl']->display("home/luck.html");
	    
	}
	
}
?>