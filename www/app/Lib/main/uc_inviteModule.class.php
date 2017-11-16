<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


require APP_ROOT_PATH.'app/Lib/page.php';
class uc_inviteModule extends MainBaseModule
{
	public function index()
	{
		require APP_ROOT_PATH."system/model/uc_center_service.php";
		global_run();
		init_app_page();
		assign_uc_nav_list();//左侧导航菜单
		

		//初始化参数
		$user_id = intval($GLOBALS['user_info']['id']);
		
		
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}	
		//初始化分页类
		$page = intval($_REQUEST['p']);
		$page_size = app_conf("PAGE_SIZE");
		
		if($page<=0)
		    $page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		//获取数据集
        $result = get_invite_list($limit,$user_id);
		$page = new Page($result['count'],$page_size);   //初始化分页对象
		$p = $page->show();
		
		$GLOBALS['tmpl']->assign('pages',$p);
        //end 分页
		
				
        //页面统计数据
		$referrals_data = $GLOBALS['db']->getRow("select sum(money) as money,sum(score) as score,sum(coupons) as coupons from ".DB_PREFIX."referrals where user_id = ".$user_id." and pay_time > 0");
		
		//分享链接
		$share_url = get_domain().APP_ROOT."/?ctl=user&act=register&";
		if($user_id)
		  $share_url .= "r=".base64_encode(intval($GLOBALS['user_info']['id']));
		//end 分享
	     

		//返回页面的数据集
		$GLOBALS['tmpl']->assign("is_dealers",$is_dealers);//控制是否显示邀请页面
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		$GLOBALS['tmpl']->assign("referrals_data",$referrals_data);
		$GLOBALS['tmpl']->assign("total_referral_score",$total_referral_score);
		$GLOBALS['tmpl']->assign("list",$result['list']);
		$GLOBALS['tmpl']->assign("share_url",$share_url);
		$GLOBALS['tmpl']->assign("page_title","我的邀请");
		$GLOBALS['tmpl']->display("uc/uc_invite.html");
	}
	
	/**
	 * 邀请用户返还积分汇总列表
	 */
	public function invite_user_list(){
	    require APP_ROOT_PATH."system/model/uc_center_service.php";
	    global_run();
	    init_app_page();
	    assign_uc_nav_list();//左侧导航菜单
	    $GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
	    
	    //初始化参数
	    $user_id = intval($GLOBALS['user_info']['id']);
	    
	    if(check_save_login()!=LOGIN_STATUS_LOGINED)
	    {
	        app_redirect(url("index","user#login"));
	    }
	    
	    //分页初始化
	    $page = intval($_REQUEST['p']);
	    if($page<=0)	$page = 1;
	    $page_size =  app_conf("PAGE_SIZE");
	    $limit = (($page-1)*$page_size).",".$page_size;
	    
	    //获取数据集
	    $result = get_total_invite_list($limit,$user_id);

	    $page = new Page($result['count'],$page_size);   //初始化分页对象
	    $p  =  $page->show();
	    $GLOBALS['tmpl']->assign('pages',$p);
	    //end 分页
	    
	    
        //页面统计数据
		$referrals_data = $GLOBALS['db']->getRow("select sum(money) as money,sum(score) as score,sum(coupons) as coupons from ".DB_PREFIX."referrals where user_id = ".$user_id." and pay_time > 0");
	    
	    
	    //分享URL
	    $share_url = get_domain().APP_ROOT."/";
	    if($GLOBALS['user_info'])
	        $share_url .= "?r=".base64_encode(intval($GLOBALS['user_info']['id']));
	    
	    
	    //返回页面数据集
	    $GLOBALS['tmpl']->assign("share_url",$share_url);
	    $GLOBALS['tmpl']->assign("referrals_data",$referrals_data);
	    $GLOBALS['tmpl']->assign("total_list",$result['total_list']);
	    
	    $GLOBALS['tmpl']->assign("page_title","我的邀请");
	    $GLOBALS['tmpl']->display("uc/uc_invite_total.html");
	    
	}
	

}
?>