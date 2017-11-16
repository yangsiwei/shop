<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


require_once APP_ROOT_PATH.'system/model/user.php';
class uc_logModule extends MainBaseModule
{
    private $creditsettings;
    private $allow_exchange = false;
    private  $credits_CFG = array(
        '1' => array('title'=>'经验', 'unit'=>'' ,'field'=>'point'),
        '2' => array('title'=>'积分', 'unit'=>'' ,'field'=>'score'),
        '3' => array('title'=>'资金', 'unit'=>'' ,'field'=>'money'),
    );
    public function __construct(){
        
        if(file_exists(APP_ROOT_PATH."public/uc_config.php"))
        {
            require_once APP_ROOT_PATH."public/uc_config.php";
        }
        if(app_conf("INTEGRATE_CODE")=='Ucenter'&&UC_CONNECT=='mysql')
        {
            if(file_exists(APP_ROOT_PATH."public/uc_data/creditsettings.php"))
            {
                require_once APP_ROOT_PATH."public/uc_data/creditsettings.php";
                $this->creditsettings = $_CACHE['creditsettings'];
                if(count($this->creditsettings)>0)
                {
                    foreach($this->creditsettings as $k=>$v)
                    {
                        $this->creditsettings[$k]['srctitle'] = $this->credits_CFG[$v['creditsrc']]['title'];
                    }
                    $this->allow_exchange = true;
                    
                }
            }
        }
        $GLOBALS['tmpl']->assign("allow_exchange",$this->allow_exchange);
        parent::__construct();
        global_run();
        if(check_save_login()!=LOGIN_STATUS_LOGINED)
        {
            app_redirect(url("index","user#login"));
        }
        init_app_page();
    }

	public function index()
	{
		 app_redirect(url("index","uc_log#money"));
	}

    
    /**
     * 资金日志
     */
	public function money()
	{
	    $user_info = $GLOBALS['user_info'];
	    //业务逻辑部分
	    //分页
	    require_once APP_ROOT_PATH."app/Lib/page.php";
		$page_size = app_conf("PAGE_SIZE");
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		require_once APP_ROOT_PATH.'system/model/user_center.php';
		$data = get_user_log($limit,$user_info['id'],'money'); //获取资金数据
		$money_gift = $GLOBALS['user_info']['can_use_give_money'];//获取充值赠送的资金
		$money_extension = $GLOBALS['user_info']['fx_money'];//获取推广奖资金
		$money_administration = $GLOBALS['user_info']['admin_money'];//获取管理奖资金
		
		//分页输出
		$page = new Page($data['count'],$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
	    //数据
		$GLOBALS['tmpl']->assign("user_info",$user_info);
		$GLOBALS['tmpl']->assign('data',$data);
	    //通用模版参数定义
		assign_uc_nav_list();//左侧导航菜单
	    $GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
	    $GLOBALS['tmpl']->assign("page_title","用户资金日志"); //title
	    $GLOBALS['tmpl']->display("uc/uc_log.html"); //title
	}
	/**
	 * 积分日志
	 */
	public function score(){
	    $user_info = $GLOBALS['user_info'];
	     
	    //业务逻辑部分
	    //取出积分信息
	    $uc_query_data['cur_score'] = $user_info['score'];
	     
	    //分页
	    require_once APP_ROOT_PATH."app/Lib/page.php";
	    $page_size = 10;
	    $page = intval($_REQUEST['p']);
	    if($page==0)
	        $page = 1;
	    $limit = (($page-1)*$page_size).",".$page_size;
	    
	    require_once APP_ROOT_PATH.'system/model/user_center.php';
	    $data = get_user_log($limit,$user_info['id'],'score'); //获取积分数据
	    
	    //分页输出
	    $page = new Page($data['count'],$page_size);   //初始化分页对象
	    $p  =  $page->show();
	    $GLOBALS['tmpl']->assign('pages',$p);
	    
	    //数据
	    $GLOBALS['tmpl']->assign("user_info",$user_info);
	    $GLOBALS['tmpl']->assign("uc_query_data",$uc_query_data);
	    $GLOBALS['tmpl']->assign('data',$data);
	     
	    //通用模版参数定义
	    assign_uc_nav_list();//左侧导航菜单
	    $GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
	    $GLOBALS['tmpl']->assign("page_title","用户积分日志"); //title
	    $GLOBALS['tmpl']->display("uc/uc_log.html");
	    
	}
	/**
	 * 经验日志
	 */
	public function point(){
	    $user_info = $GLOBALS['user_info'];
	    
	    //业务逻辑部分
	    //取出等级信息
	    $level_data = load_auto_cache("cache_user_level");
	    $cur_level = $level_data[$user_info['level_id']];
	     
	    //游标移动获取下一个等级
	    reset($level_data);
	    do{
	        $current_data = current($level_data);
	    
	        if($current_data['id']==$cur_level['id'])
	        {
	             
	            $next_data = next($level_data);
	            break;
	        }
	    }while(next($level_data));
	    $uc_query_data = array();
	    $uc_query_data['cur_level'] = $cur_level['level']; //当前等级
	    $uc_query_data['cur_point'] = $user_info['point'];
	    $uc_query_data['cur_level_name'] = $cur_level['name'];
	    if($next_data){
	        $uc_query_data['next_level'] = $next_data['id'];
	        $uc_query_data['next_point'] =$next_data['point'] - $user_info['point']; //我再增加：100 经验值，就可以升级为：青铜五
	        $uc_query_data['next_level_name'] = $next_data['name'];
	    }

	    
	    //分页
	    require_once APP_ROOT_PATH."app/Lib/page.php";
		$page_size = 10;
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		
		require_once APP_ROOT_PATH.'system/model/user_center.php';
		$data = get_user_log($limit,$user_info['id'],'point');    //获取经验数据

		//分页输出
		$page = new Page($data['count'],$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
	    //数据
		$GLOBALS['tmpl']->assign("user_info",$user_info);
		$GLOBALS['tmpl']->assign("uc_query_data",$uc_query_data);
		$GLOBALS['tmpl']->assign('data',$data);
	    
	    //通用模版参数定义
		assign_uc_nav_list();//左侧导航菜单
	    $GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
	    $GLOBALS['tmpl']->assign("page_title","用户成长日志"); //title
	    $GLOBALS['tmpl']->display("uc/uc_log.html"); 
	}
    
	/**
	 * 兑换
	 */
	public function exchange(){
	    $user_info = $GLOBALS['user_info'];
	     
	    //业务逻辑部分
	    //分页
	    require_once APP_ROOT_PATH."app/Lib/page.php";
	    $page_size = 10;
	    $page = intval($_REQUEST['p']);
	    if($page==0)
	        $page = 1;
	    $limit = (($page-1)*$page_size).",".$page_size;
	    
	    require_once APP_ROOT_PATH.'system/model/user_center.php';

	    
	    //数据
	    $GLOBALS['tmpl']->assign("user_info",$user_info);
	    $GLOBALS['tmpl']->assign("exchange_data",$this->creditsettings);
		$GLOBALS['tmpl']->assign("exchange_json_data",json_encode($this->creditsettings));
	     
	    //通用模版参数定义
	    assign_uc_nav_list();//左侧导航菜单
	    $GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
	    $GLOBALS['tmpl']->assign("page_title","用户uc兑换"); //title
	    $GLOBALS['tmpl']->display("uc/uc_log.html"); //title
	}
	
}
?>