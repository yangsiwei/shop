<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



class uc_duobaoModule extends MainBaseModule
{
	public function index()
	{
	    require APP_ROOT_PATH."system/model/uc_center_service.php";
		global_run();
		init_app_page();
		assign_uc_nav_list();//左侧导航菜单
		
// 		require_once APP_ROOT_PATH.'system/model/user.php';
// 		$GLOBALS['user_info'] = load_user(230);
		
		if(empty($GLOBALS['user_info']))
		{
			app_redirect(url("index","user#login"));
		}
		
		
		$id = $GLOBALS['user_info']['id'];
        $log_type=intval($_REQUEST['log_type']); //1进行 2已开奖 3进度满未开奖 0所有
        
        $log_type_condition = " ";
        if($log_type==1)
        	$log_type_condition = " and duobao_status = 0 ";
        elseif($log_type==2)
        	$log_type_condition = " and duobao_status = 2 ";
        elseif($log_type==3)
        	$log_type_condition = " and duobao_status = 1 ";

        //时间区间
        $log_time_type=intval($_REQUEST['log_time_type']); //1今天 2最近七天 3最近30天 5一年内 默认3个月内
        if($log_time_type==0)$log_time_type=4;
        
        //时间
        if($log_time_type==1)  //今天
        	$log_time_type_condition = " and create_date_ymd = '".to_date(NOW_TIME,"Y-m-d")."' ";//今天
        elseif($log_time_type==2)  //最近7天
        {
        	for($i=0;$i<7;$i++)
        	{
        		$week_day[] = "'".to_date((NOW_TIME-$i*24*3600),"Y-m-d")."'";
        	}
        	
       		$log_time_type_condition = " and  create_date_ymd in (".implode(",", $week_day).") ";//7天内
        }
        elseif($log_time_type==3)  //最近30天
		{
        	for($i=0;$i<30;$i++)
        	{
        		$month_day[] = "'".to_date((NOW_TIME-$i*24*3600),"Y-m-d")."'";
        	}
        	
       		$log_time_type_condition = " and  create_date_ymd in (".implode(",", $month_day).") ";//30天内
        }
        elseif($log_time_type==5)  //1年内
        	$log_time_type_condition = " and create_date_y = '".to_date(NOW_TIME,"Y")."' ";//1年内
        else   //默认，最近三个月
		{
			$month_day[] = "'".date('Y-m',time())."'";
        	for($i=1;$i<=2;$i++)
        	{
        		$month_day[] = "'".date('Y-m',strtotime('-'.$i.' month'))."'";
        	}
        	
       		$log_time_type_condition = " and  create_date_ym in (".implode(",", $month_day).") ";
       		
        }
        

		$page =intval($_REQUEST['p']);
        $page_size =app_conf("PAGE_SIZE");

        $sql_total = "select count(distinct(duobao_item_id)) from ".DB_PREFIX."deal_order_item where type = 2 and pay_status = 2 and user_id = ".$id." ".$log_type_condition." ".$log_time_type_condition;
        $total = $GLOBALS['db']->getOne($sql_total);

        
        
        //分页
        require_once APP_ROOT_PATH."app/Lib/page.php";
        $page =intval($_REQUEST['p']);
        $page_size =app_conf("PAGE_SIZE");
        if ($page == 0) $page = 1;
        $limit = (($page - 1) * $page_size) . "," . $page_size;
        
        $sql = "select *,sum(number) as number from ".DB_PREFIX."deal_order_item where (type = 2 or type=4) and pay_status = 2 and user_id = ".$id." ".$log_type_condition." ".$log_time_type_condition." group by duobao_item_id order by create_time desc limit ".$limit;

        $list = $GLOBALS['db']->getAll($sql);
        foreach($list as $k=>$v)
        {
        	$list[$k]['duobao_item'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$v['duobao_item_id']);
        	$list[$k]['duobao_item']['less'] = $list[$k]['duobao_item']['max_buy'] - $list[$k]['duobao_item']['current_buy'];
        		
        }
        
        $page = new Page($total, $page_size); // 初始化分页对象
        $p = $page->show();
        
        $GLOBALS['tmpl']->assign("list",$list);
        $GLOBALS['tmpl']->assign("pages",$p);
        
        

        $sql_total = "select count(distinct(duobao_item_id)) from ".DB_PREFIX."deal_order_item where type = 2 and pay_status = 2 and user_id = ".$id." ".$log_time_type_condition;        
        $data['success_count']=$GLOBALS['db']->getOne($sql_total);
        
        $sql_total = "select count(distinct(duobao_item_id)) from ".DB_PREFIX."deal_order_item where type = 2 and pay_status = 2 and user_id = ".$id." and duobao_status = 1 ".$log_time_type_condition;
        $data['soon_count']=$GLOBALS['db']->getOne($sql_total);
        
        $sql_total = "select count(distinct(duobao_item_id)) from ".DB_PREFIX."deal_order_item where type = 2 and pay_status = 2 and user_id = ".$id." and duobao_status = 0 ".$log_time_type_condition;
        $data['in_count']=$GLOBALS['db']->getOne($sql_total);
        
        $sql_total = "select count(distinct(duobao_item_id)) from ".DB_PREFIX."deal_order_item where type = 2 and pay_status = 2 and user_id = ".$id." and duobao_status = 2 ".$log_time_type_condition;
        $data['complete_count']=$GLOBALS['db']->getOne($sql_total);;
        $data['log_type']=$log_type;
        $data['log_time_type']=$log_time_type;
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
        
		$GLOBALS['tmpl']->display("uc/uc_duobao.html");
	    
	}
    
	
}
?>