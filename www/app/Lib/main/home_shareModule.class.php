<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



class home_shareModule extends MainBaseModule
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
		
		
		
		
		
		//已经晒单的数据
		$page =intval($_REQUEST['p']);
		$page_size =app_conf("PAGE_SIZE");
		
		//分页
		require_once APP_ROOT_PATH."app/Lib/page.php";
		if ($page == 0) $page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		$GLOBALS['tmpl']->assign("page",$page);
		
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."share where is_effect = 1 and user_id=".$home_user['id']);

		$page = new Page($count, $page_size); // 初始化分页对象
		$p = $page->show();
		
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


		
		$GLOBALS['tmpl']->assign("page_title","Ta的晒单");
		
		$GLOBALS['tmpl']->assign('pages', $p);
		$GLOBALS['tmpl']->assign("count", $count);
		
		$GLOBALS['tmpl']->assign("home_user",$home_user);
        
		assign_uc_nav_list();//左侧导航菜单	
		
		$GLOBALS['tmpl']->display("home/share.html");
	    
	}
    
}
?>