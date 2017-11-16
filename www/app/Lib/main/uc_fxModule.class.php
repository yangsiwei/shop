<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


require APP_ROOT_PATH.'app/Lib/page.php';
class uc_fxModule extends MainBaseModule
{
	public function index()
	{
		require APP_ROOT_PATH."system/model/uc_center_service.php";
		global_run();
		init_app_page();
		assign_uc_nav_list();//左侧导航菜单
		
		//初始化参数
		$user_id = intval($GLOBALS['user_info']['id']);
		
		// var_dump($GLOBALS['user_info']);
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}	
		 
		$user_data['id'] = $user_id;
		$user_info_my = $GLOBALS['db']->getOne("select fx_level from ".DB_PREFIX."user where id = ".$user_data['id']);

		switch ($user_info_my['fx_level']) {
			case 0:
				$level_fx = '普通会员';
				break;
			case 1:
				$level_fx = '白银会员';
				break;
			case 2:
				$level_fx = '黄金会员';
				break;
			default:
				$level_fx = '钻石会员';
		}

		// if($user_info_my['fx_level'] = 1){

		// }

		// 我的分销用户
		$first_all_fx_user = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo,total_money from ".DB_PREFIX."user where pid = ".$user_data['id']);
		$root['first_user_count'] = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."user where pid = ".$user_data['id']);

		// 我的二级分销用户
		foreach ($first_all_fx_user as $key=>$value){
		    $first_uid[] = $value['id'];
		    if ($key <= 3) {
		        $value['user_logo'] = $value['user_logo']?get_abs_img_root($value['user_logo']):'';
		        $root['first_fx_user'][$key] = $value;
		    }
		}
		$first_uid = join(',', $first_uid);
		$second_all_fx_user = '';
		$root['second_user_count'] = 0;
		if ($first_uid) {
		    $second_all_fx_user = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo,total_money from ".DB_PREFIX."user where pid in ({$first_uid})");
		    $root['second_user_count'] = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."user where pid in ({$first_uid})");
		}
		
		
		// 我的三级分销用户
		foreach ($second_all_fx_user as $key=>$value){
		    $second_uid[] = $value['id'];
		    if ($key <= 3) {
		        $value['user_logo'] = $value['user_logo']?get_abs_img_root($value['user_logo']):'';
		        $root['second_fx_user'][$key] = $value;
		    }
		}
		$second_uid = join(',', $second_uid);
		$three_fx_user = '';
		$root['three_user_count'] = 0;
		if ($second_uid) {
		    $three_fx_user = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo,total_money from ".DB_PREFIX."user where pid in ({$second_uid})"." limit 4");
		    foreach ($three_fx_user as $key=>$value){
		        $value['user_logo'] = $value['user_logo']?get_abs_img_root($value['user_logo']):'';
		        $root['three_fx_user'][$key] = $value;
		    }
		    $root['three_user_count'] = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."user where pid in ({$second_uid})");
		}

        $three_all_fx_user = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo,avatar from ".DB_PREFIX."user where pid in ({$second_uid})");
        foreach($three_all_fx_user as $value){
            $third_uid[] = $value['id'];
        }
        $third_uid = join(',', $third_uid);


//        $root['first_fx_user'] = $first_all_fx_user;
//        $root['second_fx_user'] = $second_all_fx_user;
//        $root['three_fx_user'] = $three_fx_user;



		
		


		 
//		// 获取推广奖分成多少
//		$fx_salary = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."fx_salary");
//		// 定额0， 比率1
//		$fx_salary_type = $fx_salary[0]['fx_salary_type'];
		
		$now_time = to_date(NOW_TIME, 'Y-m-d');
		$start_time = strtotime($now_time);
		$end_time   = $start_time + 24*60*60;
			
//		// 今日三级分销金额信息
//		$today_three_info = $GLOBALS['db']->getRow("select sum(r.money) total_money,  sum(r.order_money) total_order_money, count(*) t_count from ".DB_PREFIX.
//		    "user u left join ".DB_PREFIX."fx_user_reward r on u.id=r.user_id where r.fx_salary_type={$fx_salary_type} and r.pid = ".$user_data['id']." and r.fx_level=1 and (r.create_time > {$start_time} and r.create_time < {$end_time}) " );
//
//
//		// 今日我的二级分销金额信息
//		if ($first_uid) {
//		    $today_second_info = $GLOBALS['db']->getRow("select sum(r.money) total_money,  sum(r.order_money)  total_order_money, count(*) t_count from ".DB_PREFIX.
//		        "user u left join ".DB_PREFIX."fx_user_reward r on u.id=r.user_id where r.fx_salary_type={$fx_salary_type} and r.pid in ({$first_uid}) and r.fx_level=1 and (r.create_time > {$start_time} and r.create_time < {$end_time}) " );
//		}
//
//
//		// 今日我的一级分销金额信息
//		if ($second_uid) {
//		    $today_first_info = $GLOBALS['db']->getRow("select sum(r.money) total_money,  sum(r.order_money)  total_order_money, count(*) t_count from ".DB_PREFIX.
//		        "user u left join ".DB_PREFIX."fx_user_reward r on u.id=r.user_id where r.fx_salary_type={$fx_salary_type} and r.pid in ({$second_uid}) and r.fx_level=1 and (r.create_time > {$start_time} and r.create_time < {$end_time}) " );
//		}


		//今日一级分销累计充值金额
        $root['first_money'] = $GLOBALS['db']->getAll("select sum(pay_amount) money,count(id) num from ".DB_PREFIX."deal_order where user_id in ({$first_uid}) and create_time > {$start_time} and create_time < {$end_time} and type = 1");
		//今日二级分销累计充值金额
        $root['second_money'] = $GLOBALS['db']->getAll("select sum(pay_amount) money,count(id) num from ".DB_PREFIX."deal_order where user_id in ({$second_uid}) and create_time > {$start_time} and create_time < {$end_time} and type = 1");

        //今日三级分销累计充值金额
        $root['third_money'] = $GLOBALS['db']->getAll("select sum(pay_amount) money,count(id) num from ".DB_PREFIX."deal_order where user_id in ({$third_uid}) and create_time > {$start_time} and create_time < {$end_time} and type = 1");

        //计算今日获得推广奖情况
        $root['fx_salary'] = $GLOBALS['db']->getAll("select fx_salary from ".DB_PREFIX."fx_salary");
        $root['first_fx_money'] = $root['first_money'][0]['sum(pay_amount)']*$root['fx_salary'][0]['fx_salary'];
        $root['second_fx_money'] = $root['second_money'][0]['sum(pay_amount)']*$root['fx_salary'][1]['fx_salary'];
        $root['third_fx_money'] = $root['third_money'][0]['sum(pay_amount)']*$root['fx_salary'][2]['fx_salary'];

        $root['fx_salary']['first'] = $root['fx_salary'][0]['fx_salary']*100;
        $root['fx_salary']['second'] = $root['fx_salary'][1]['fx_salary']*100;
        $root['fx_salary']['third'] = $root['fx_salary'][02]['fx_salary']*100;

        // 邀请好友累积收入
        $root['total_brokerage_money'] = $GLOBALS['db']->getOne("select fx_total_balance from ".DB_PREFIX."user where id = ".$user_data['id']);
        $root['total_brokerage_money'] = round($root['total_brokerage_money'], 2);

        // 今日获得多宝币
        $root['today_total_brokerage_money'] = round($root['first_fx_money']+$root['second_fx_money']+$root['third_fx_money'], 2);


//		$root['fx_count']['three_fx_count'] = $today_three_info['t_count'];
//		$root['fx_count']['second_fx_count'] = $today_second_info['t_count'];
//		$root['fx_count']['first_fx_count'] = $today_first_info['t_count'];
//
//		foreach ($fx_salary as $value){
//		    // 定额0， 比率1
//		    $root['fx_salary_type'] = $value['fx_salary_type'];
//
//		    if($value['fx_level'] == 1){
//		        if ( $root['fx_salary_type'] == 1) {
//		            $root['fx_level_one_salary'] = round($value['fx_salary']*100, 2);
//		            // 今日我的一级分销创收
//		            $root['today_first_money'] = round($today_three_info['total_order_money'] * $value['fx_salary'], 2);
//
//		        }else{
//		            $root['today_first_money'] = round($value['fx_salary'] * $today_three_info['t_count'], 2);
//
//		            $root['fx_level_one_salary'] = round($value['fx_salary'], 2);
//		        }
//
//		    }
//		    if($value['fx_level'] == 2){
//		        if ( $root['fx_salary_type'] == 1) {
//		            $root['fx_level_two_salary'] = round($value['fx_salary']*100, 2);
//		            // 今日我二级分销创收
//		            $root['today_second_money'] = round($today_second_info['total_order_money'] * $value['fx_salary'], 2);
//		        }else{
//		            $root['today_second_money'] = round($value['fx_salary'] * $today_second_info['t_count'], 2);
//		            $root['fx_level_two_salary'] = round($value['fx_salary'], 2);
//		        }
//		    }
//
//		    if($value['fx_level'] == 3){
//		        if ( $root['fx_salary_type'] == 1) {
//		            $root['fx_level_three_salary'] = round($value['fx_salary']*100, 2);
//		            // 今日我三级分销创收
//		            $root['today_three_money'] = round($today_first_info['total_order_money'] * $value['fx_salary'], 2);
//		        }else{
//		            $root['today_three_money'] = round($value['fx_salary'] * $today_first_info['t_count'], 2);
//		            $root['fx_level_three_salary'] = round($value['fx_salary'], 2);
//		        }
//		    }
//
//		}
			

			
			
//		// 今日第一级分销交易金额
//		$root['today_first_order_money'] = round($today_three_info['total_order_money'], 2);
//
//		// 今日第二级分销交易金额
//		$root['today_second_order_money'] = round($today_second_info['total_order_money'], 2);
//
//		// 今日第三级分销交易金额
//		$root['today_three_order_money'] = round($today_first_info['total_order_money'], 2);
			
//		// 今日交易的总金额
//		$root['today_total_money'] = round($today_first_info['total_order_money'] + $today_second_info['total_order_money'] + $today_three_info['total_order_money'], 2);
			
			
		$root['no_user_logo'] = SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar_middle.gif";


	 	//判断是否是经销商
	    $dealers = $GLOBALS['user_info']['dealers'];

	    switch ($dealers) {
	    	case 2:
	    		$dealers = 2;
	    		$pass = null;
	    		$no_pass = null;
	    		break;
	    	case 1:
	    		$no_pass = null;
	    		$dealers = null;
	    		$pass = 1;
	    		break;
	    	default:
	    		$dealers = null;
	    		$pass = null;
	    		$no_pass = 1;
	    		break;
	    }
	    $GLOBALS['tmpl']->assign("dealers",$dealers);
	    $GLOBALS['tmpl']->assign("pass",$pass);
	    $GLOBALS['tmpl']->assign("no_pass",$no_pass);
		$GLOBALS['tmpl']->assign("page_title","我的邀请");
		$GLOBALS['tmpl']->assign("level",$level_fx);
		$GLOBALS['tmpl']->assign("fx_level_my",$user_info_my['fx_level']);
		$GLOBALS['tmpl']->assign("data",$root);
		$GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
		$GLOBALS['tmpl']->display("uc/uc_fx.html");
	}
	
	public function user_list_one(){
	    require APP_ROOT_PATH."system/model/uc_center_service.php";
	    global_run();
	    init_app_page();
	    assign_uc_nav_list();//左侧导航菜单
	    
	   
	    $user_id = intval($GLOBALS['user_info']['id']);
	    
	    if(check_save_login()!=LOGIN_STATUS_LOGINED)
	    {
	        app_redirect(url("index","user#login"));
	    }
	    
	    
	    $user_info = $GLOBALS['db']->getAll("select id, user_name, user_logo from ".DB_PREFIX."user where pid = ".$user_id);
	    $root['no_user_logo'] = SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar_small.gif";
	    
	    foreach ($user_info as $key=>$value){
	        $fx_user_id[] = $value['id'];
	    }
	     
	    $now_time = to_date(NOW_TIME, 'Y-m-d');
	    $start_time = strtotime($now_time);
	    $end_time   = $start_time + 24*60*60;
	     
	    $fx_user_id = join(',', $fx_user_id);
	    
	    if ($fx_user_id) {
	        // 今天
            $today_amount = $GLOBALS['db']->getAll("select sum(pay_amount) today_money,count(id) order_number,user_id uid from ".DB_PREFIX."deal_order where user_id in ({$fx_user_id}) and (create_time > {$start_time} and create_time < {$end_time})  and type = 1 and pay_amount>=100 group by id");

            // 总的
            $amount   = $GLOBALS['db']->getAll("select u.id uid, sum(r.pay_amount) amount_money  from ".DB_PREFIX."deal_order r left join ".DB_PREFIX."user u on u.id=r.user_id where u.id in({$fx_user_id}) and r.pay_amount>=100  and r.type=1 "." group by r.id");
            // 用户信息
            $user_list = $GLOBALS['db']->getAll("select id uid, user_name, user_logo from ".DB_PREFIX."user where id in({$fx_user_id})");
        }

        $fx_salary = $GLOBALS['db']->getOne("select fx_salary from ".DB_PREFIX."fx_salary where fx_level = 1");

        foreach ($user_list as $key=>$value){
            foreach ($today_amount as $k1=>$v1){
                if ($value['uid'] == $v1['uid']) {
                    $user_list[$key]['today_money'] = round($v1['today_money'], 2);
                    $user_list[$key]['order_number'] = intval($v1['today_money']*$fx_salary);
                }
            }

            foreach ($amount as $k2=>$v2){
                if ($value['uid'] == $v2['uid']) {
                    $user_list[$key]['amount_money'] = round($v2['amount_money']*$fx_salary, 2);
                }
            }
	         
	        $user_list[$key]['today_money']  = $user_list[$key]['today_money'] ? $user_list[$key]['today_money'] : 0;
	        $user_list[$key]['order_number'] = $user_list[$key]['order_number'] ? $user_list[$key]['order_number'] : 0;
	         
	        $user_list[$key]['amount_money'] = $user_list[$key]['amount_money'] ? $user_list[$key]['amount_money'] : 0;
	         
	        $user_list[$key]['user_logo'] = $value['user_logo']?get_abs_img_root($value['user_logo']):'';
	    }
	     
	    $root['amount_data'] = $user_list;
	    
	    $GLOBALS['tmpl']->assign("data", $root);
	    $GLOBALS['tmpl']->display("uc/user_list_one.html");
	}
	
	public function user_list_two(){
	    require APP_ROOT_PATH."system/model/uc_center_service.php";
	    global_run();
	    init_app_page();
	    assign_uc_nav_list();//左侧导航菜单
	    
	    
	  
	    $user_id = intval($GLOBALS['user_info']['id']);
	     
	    if(check_save_login()!=LOGIN_STATUS_LOGINED)
	    {
	        app_redirect(url("index","user#login"));
	    }
	    
	    $root['no_user_logo'] = SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar_small.gif";
	    
        // 我的佃户
        $first_user = $GLOBALS['db']->getAll("select id, user_name, user_logo from ".DB_PREFIX."user where pid = ".$user_id);
         
        // 我的佃户的佃户
        foreach ($first_user as $value){
            $first_uid[] = $value['id'];
        }
        $first_uid = join(',', $first_uid);
        
        $fx_user_id = '';
        if ($first_uid) {
            $user_info = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo from ".DB_PREFIX."user where pid in ({$first_uid})");
            foreach ($user_info as $key=>$value){
                $fx_user_id[] = $value['id'];
            }
            
            $fx_user_id = join(',', $fx_user_id);
        }
	    
	    $now_time = to_date(NOW_TIME, 'Y-m-d');
	    $start_time = strtotime($now_time);
	    $end_time   = $start_time + 24*60*60;
	    
	     
	   
	    if ($fx_user_id) {
            // 今天
            $today_amount = $GLOBALS['db']->getAll("select sum(pay_amount) today_money,count(id) order_number,user_id uid from ".DB_PREFIX."deal_order where user_id in ({$fx_user_id}) and (create_time > {$start_time} and create_time < {$end_time}) and total_price>=100 and pay_status=2  and type = 1 group by id");

            // 总的
            $amount   = $GLOBALS['db']->getAll("select u.id uid, sum(r.pay_amount) amount_money  from ".DB_PREFIX."deal_order r left join ".DB_PREFIX."user u on u.id=r.user_id where u.id in({$fx_user_id}) and r.total_price>=100 and r.pay_status=2 and r.type=1 "." group by r.id");
            // 用户信息
            $user_list = $GLOBALS['db']->getAll("select id uid, user_name, user_logo from ".DB_PREFIX."user where id in({$fx_user_id})");
        }

        $fx_salary = $GLOBALS['db']->getOne("select fx_salary from ".DB_PREFIX."fx_salary where fx_level = 2");

        foreach ($user_list as $key=>$value){
            foreach ($today_amount as $k1=>$v1){
                if ($value['uid'] == $v1['uid']) {
                    $user_list[$key]['today_money'] = round($v1['today_money'], 2);
                    $user_list[$key]['order_number'] = intval($v1['today_money']*$fx_salary);
                }
            }

            foreach ($amount as $k2=>$v2){
                if ($value['uid'] == $v2['uid']) {
                    $user_list[$key]['amount_money'] = round($v2['amount_money']*$fx_salary, 2);
                }
            }
	    
	        $user_list[$key]['today_money']  = $user_list[$key]['today_money'] ? $user_list[$key]['today_money'] : 0;
	        $user_list[$key]['order_number'] = $user_list[$key]['order_number'] ? $user_list[$key]['order_number'] : 0;
	    
	        $user_list[$key]['amount_money'] = $user_list[$key]['amount_money'] ? $user_list[$key]['amount_money'] : 0;
	    
	        $user_list[$key]['user_logo'] = $value['user_logo']?get_abs_img_root($value['user_logo']):'';
	    }
	    
	    $root['amount_data'] = $user_list;
	    
	    $GLOBALS['tmpl']->assign("data", $root);
	    $GLOBALS['tmpl']->display("uc/user_list_one.html");
	}
	
	public function user_list_three(){
	    require APP_ROOT_PATH."system/model/uc_center_service.php";
	    global_run();
	    init_app_page();
	    assign_uc_nav_list();//左侧导航菜单
	    
	    
	    $user_id = intval($GLOBALS['user_info']['id']);
	    
	    if(check_save_login()!=LOGIN_STATUS_LOGINED)
	    {
	        app_redirect(url("index","user#login"));
	    }
	    
        // 我的佃户
        $first_user = $GLOBALS['db']->getAll("select id, user_name, user_logo from ".DB_PREFIX."user where pid = ".$user_id);
    
        // 我的佃户的佃户
        foreach ($first_user as $value){
            $first_uid[] = $value['id'];
        }
        $first_uid = join(',', $first_uid);
        
        $second_uid = '';
        if ($first_uid) {
            $second_user = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo from ".DB_PREFIX."user where pid in ({$first_uid})");
            // 我的佃户的佃户的佃户
            foreach ($second_user as $value){
                $second_uid[] = $value['id'];
            }
            
            $second_uid = join(',', $second_uid);
            
        }
        
        if ($second_uid) {
            $user_info = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo from ".DB_PREFIX."user where pid in ({$second_uid})");
        }
       
    
	    $root['no_user_logo'] = SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar_small.gif";
	    foreach ($user_info as $key=>$value){
	        $fx_user_id[] = $value['id'];
	    }
	     
	    $now_time = to_date(NOW_TIME, 'Y-m-d');
	    $start_time = strtotime($now_time);
	    $end_time   = $start_time + 24*60*60;
	     
	    $fx_user_id = join(',', $fx_user_id);
	     
	    if ($fx_user_id) {
            // 今日
            $today_amount = $GLOBALS['db']->getAll("select sum(pay_amount) today_money,count(id) order_number,user_id uid from ".DB_PREFIX."deal_order where user_id in ({$fx_user_id}) and (create_time > {$start_time} and create_time < {$end_time})  and type = 1 and pay_amount>=100 group by id");

            // 总的
            $amount   = $GLOBALS['db']->getAll("select u.id uid, sum(r.pay_amount) amount_money  from ".DB_PREFIX."deal_order r left join ".DB_PREFIX."user u on u.id=r.user_id where u.id in({$fx_user_id}) and r.pay_amount>=100  and r.type=1 "." group by r.id");
            // 用户信息
            $user_list = $GLOBALS['db']->getAll("select id uid, user_name, user_logo from ".DB_PREFIX."user where id in({$fx_user_id})");
        }

        $fx_salary = $GLOBALS['db']->getOne("select fx_salary from ".DB_PREFIX."fx_salary where fx_level = 3");

        foreach ($user_list as $key=>$value){
            foreach ($today_amount as $k1=>$v1){
                if ($value['uid'] == $v1['uid']) {
                    $user_list[$key]['today_money'] = round($v1['today_money'], 2);
                    $user_list[$key]['order_number'] = intval($v1['today_money']*$fx_salary);
                }
            }

            foreach ($amount as $k2=>$v2){
                if ($value['uid'] == $v2['uid']) {
                    $user_list[$key]['amount_money'] = round($v2['amount_money']*$fx_salary, 2);
                }
            }
	         
	        $user_list[$key]['today_money']  = $user_list[$key]['today_money'] ? $user_list[$key]['today_money'] : 0;
	        $user_list[$key]['order_number'] = $user_list[$key]['order_number'] ? $user_list[$key]['order_number'] : 0;
	         
	        $user_list[$key]['amount_money'] = $user_list[$key]['amount_money'] ? $user_list[$key]['amount_money'] : 0;
	         
	        $user_list[$key]['user_logo'] = $value['user_logo']?get_abs_img_root($value['user_logo']):'';
	    }
        $root['amount_data'] = $user_list;

        $GLOBALS['tmpl']->assign("data", $root);
	    $GLOBALS['tmpl']->display("uc/user_list_one.html");
	}
	 
	

}
?>