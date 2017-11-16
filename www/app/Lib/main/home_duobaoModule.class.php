<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



class home_duobaoModule extends MainBaseModule
{
	public function index()
	{
	    require APP_ROOT_PATH."system/model/uc_center_service.php";
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
		    $user_data = load_user($user_id);
		}
		
        $log_type=intval($_REQUEST['log_type']);

        //时间区间
        $log_time_type=intval($_REQUEST['log_time_type']);
        if($log_time_type==0)$log_time_type=4;

		$page =intval($_REQUEST['p']);
        $page_size =app_conf("PAGE_SIZE");

        $sql_count="select 
        			count(distinct(di.id)) 
        			from ".DB_PREFIX."deal_order_item as doi left join ".DB_PREFIX."deal_order as do on doi.order_id = do.id left join fanwe_duobao_item as di on doi.duobao_item_id = di.id 
        			where 
        			do.user_id = ".intval($user_data['id'])." and doi.refund_status = 0 and do.pay_status = 2 and di.is_effect = 1 and do.type = 2";
        //各分类数量
		$soon_count=$GLOBALS['db']->getOne($sql_count." and di.success_time <> 0 and di.lottery_time <> 0 and di.has_lottery = 0 ");
        $in_count=$GLOBALS['db']->getOne($sql_count." and di.success_time = 0 ");
        $complete_count=$GLOBALS['db']->getOne($sql_count." and di.success_time <> 0 and di.has_lottery = 1 ");
        

        $sql="select 
				di.*,sum(doi.number) as number
				from ".DB_PREFIX."deal_order_item as doi left join ".DB_PREFIX."deal_order as do on doi.order_id = do.id left join fanwe_duobao_item as di on doi.duobao_item_id = di.id 
				where 
				do.user_id = ".intval($user_data['id'])." and doi.refund_status = 0 and do.pay_status = 2 and di.is_effect = 1 and do.type = 2 ";
		//分类
        if($log_type==1)
    		$log_type_condition = " and di.success_time = 0 ";
    	elseif($log_type==2)
    		$log_type_condition = " and di.success_time <> 0 and di.has_lottery = 1 ";
        elseif($log_type==3)
            $log_type_condition = " and di.success_time <> 0 and di.lottery_time <> 0 and di.has_lottery = 0 ";
    	else
    		$log_type_condition = "";

        //时间
        if($log_time_type==1)
            $log_type_condition .= " and do.create_time > ".mktime(0, 0, 0, date('m'), date('d'), date('Y'));//今天
        elseif($log_time_type==2)
            $log_type_condition .= " and do.create_time > ".mktime(0, 0, 0, date('m'), date('d')-7, date('Y'));//7天内
        elseif($log_time_type==3)
            $log_type_condition .= " and do.create_time > ".mktime(0, 0, 0, date('m'), date('d')-30, date('Y'));//30天内
        elseif($log_time_type==5)
            $log_type_condition .= " and do.create_time > ".mktime(0, 0, 0, date('m'), date('d'), date('Y')-1);//1年内
        else
            $log_type_condition .= " and do.create_time > ".mktime(0, 0, 0, date('m')-3, date('d'), date('Y'));//3个月内
        
    	$sql.=$log_type_condition." group by di.id ";
    	$sql_count.=$log_type_condition;

    	//分页
    	require_once APP_ROOT_PATH."app/Lib/page.php";
        if ($page == 0) $page = 1;
        $limit = (($page - 1) * $page_size) . "," . $page_size;
    	$sql.=" order by do.create_time desc, di.create_time desc limit ".$limit;

    	$total = $GLOBALS['db']->getOne($sql_count);
		$res = $GLOBALS['db']->getAll($sql);

		foreach($res as $k=>$v)
    		{
    			$list[$k]['id'] = $v['id'];
    			$list[$k]['name'] = $v['name'];
    			$list[$k]['icon'] = get_spec_image($v['icon'],200,200,1);
    			$list[$k]['max_buy'] = $v['max_buy'];
                $list[$k]['min_buy'] = $v['min_buy'];
    			$list[$k]['less'] = $v['max_buy'] - $v['current_buy'];
    			$list[$k]['number'] = $v['number'];
                $list[$k]['time'] = $v['time'];
    			$list[$k]['success_time'] = $v['success_time'];
    			$list[$k]['has_lottery'] = $v['has_lottery'];
    			$list[$k]['progress'] = $v['progress'];
    			
    			
    			if($v['has_lottery']==1)
    			{
    				$list[$k]['luck_user_id'] = $v['luck_user_id'];
    				$list[$k]['luck_user_name'] = $v['luck_user_name'];
    				$list[$k]['lottery_sn'] = $v['lottery_sn'];
    				$list[$k]['lottery_time'] = to_date($v['lottery_time']);
    			}
    			else
    			{
    				$list[$k]['luck_user_id'] = 0;
    				$list[$k]['luck_user_name'] ="--";
    				$list[$k]['lottery_sn'] = "--";
    				$list[$k]['lottery_time'] = "--";
    			}
    		}

    	$data['list']=$list;
    	$data['title']="Ta夺宝记录";
        $data['total']=$total;
        $data['soon_count']=$soon_count;
        $data['in_count']=$in_count;
        $data['complete_count']=$complete_count;
        $data['log_type']=$log_type;
        $data['log_time_type']=$log_time_type;

		$page = new Page($total, $page_size); // 初始化分页对象
        $p = $page->show();
        /* 数据 */
        $GLOBALS['tmpl']->assign('pages', $p);
        $GLOBALS['tmpl']->assign("list", $data['list']);
        $GLOBALS['tmpl']->assign("data", $data);
        
		assign_uc_nav_list();//左侧导航菜单	
		
		$GLOBALS['tmpl']->display("home/duobao.html");
	    
	}
    public function my_no_all()
    {
        
        global_run();
        init_app_page();
        
        $user_data = $GLOBALS['user_info'];
        $user_id   = $user_data['id'];

        $duobao_item_id = intval($_REQUEST['id']);

        $data['duobao_item'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$duobao_item_id);

        require_once APP_ROOT_PATH."system/model/duobao.php";
        $list = duobao::get_user_no_all(array("user_id"=>$user_id,"duobao_item"=>$data['duobao_item']));
        $data['duobao_count']=0;
        foreach ($list as $key => $value) {
            $data['duobao_count']+=count($value['list']);
        }       

        $data['list'] = $list;
        $data['page_title'].="夺宝记录";

        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("list",$data['list']);

        $html=$GLOBALS['tmpl']->fetch("inc/my_no_all.html");
        $data['status']=1;
        $data['html']=$html;
        ajax_return($data);

    }
	
}
?>