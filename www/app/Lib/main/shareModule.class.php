<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class shareModule extends MainBaseModule
{
    public function index()
    { 
       global_run();
		init_app_page();
		assign_uc_nav_list();//左侧导航菜单
		
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉

		
		//已经晒单的数据
		$page =intval($_REQUEST['p']);
		$page_size =app_conf("PAGE_SIZE");
		
		//分页
		require_once APP_ROOT_PATH."app/Lib/page.php";
		if ($page == 0) $page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		$GLOBALS['tmpl']->assign("page",$page);
		
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."share where is_effect = 1");

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
		$GLOBALS['tmpl']->assign("page_title","晒单分享");
		
		$GLOBALS['tmpl']->assign('pages', $p);
		$GLOBALS['tmpl']->assign("count", $count);
        /* 数据 */
        $GLOBALS['tmpl']->display("share.html");
    }
    

    
    public function detail(){

        global_run();
        init_app_page();

        $id = intval($_REQUEST['id']);
        
        $share_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."share where id =".$id);
        if(empty($share_info)){
            showErr("数据不存在",0,url("index"));
        }
        
        $is_my = 0;
        if($GLOBALS['user_info']['id'] == $share_info['user_id'])
            $is_my=1;
        //获得夺宝计划id
        $duobao_id = $GLOBALS['db']->getOne("select duobao_id from ".DB_PREFIX."duobao_item where id =".$share_info['duobao_item_id']);
        $GLOBALS['tmpl']->assign("duobao_id",$duobao_id);
        //分享数据
        $GLOBALS['tmpl']->assign("share_title",app_conf('SHOP_TITLE')."-".$share_info['title']);
        $share_url = get_domain().get_current_url();
        $GLOBALS['tmpl']->assign("share_url",$share_url);
        
        $GLOBALS['tmpl']->assign("share_info",$share_info);
        $GLOBALS['tmpl']->assign("img_list",unserialize($share_info['image_list']));
        
        $GLOBALS['tmpl']->assign("is_my",$is_my);
        $GLOBALS['tmpl']->assign("duobao_item",unserialize($share_info['cache_duobao_item_data']));
        /* 数据 */
        $GLOBALS['tmpl']->display("share_detail.html");
    }
    
}
?>