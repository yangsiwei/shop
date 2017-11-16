<?php
class anno_user_centerApiModule extends MainBaseApiModule{
    /**
     * 查看他人晒单--个人夺宝记录中心：夺宝记录，幸运记录，晒单
     * @see MainBaseApiModule::index()
     */
    
    public function index(){
		$root = array();
		
		$user_data = $GLOBALS['user_info'];
		 
		$page = intval($GLOBALS['request']['page'])?intval($GLOBALS['request']['page']):1; //当前分页
		$page_size = PAGE_SIZE;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
	    $id = intval($GLOBALS['request']['uid']);
	    
	    require_once APP_ROOT_PATH."system/model/user.php";
	    $home_user = load_user($id);
	    $root['user_info']=$home_user;
	     
	    $log_type  = intval($GLOBALS['request']['log_type']);
	    if($log_type==1)//幸运记录
	    {
	        $log_type_condition = " and has_lottery=1  and di.luck_user_id =".$id;
	    }
	    if($log_type==2)//晒单
	    {
	        $share_list = $GLOBALS['db']->getAll("select di.id,title,content,a.id as share_id,a.user_name,di.name,a.is_robot,a.image_list,a.create_time from ".DB_PREFIX."share a
	            left join ".DB_PREFIX."duobao_item di on a.duobao_item_id = di.id where a.user_id = ".$id." and a.is_effect=1 order by a.create_time desc limit ".$limit);
	        $total = $GLOBALS['db']->getOne("select count(di.id) from ".DB_PREFIX."share a left join ".DB_PREFIX."duobao_item di on a.duobao_item_id = di.id where a.user_id = ".$id." and a.is_effect=1 and a.is_check=1");
	        
	        foreach ($share_list as $key => $value) {
	            $share_list[$key]['create_time']=to_date($value['create_time'],"m-d H:i");
	            $share_list[$key]['image_list']=unserialize($value['image_list']);
	        }
	        $root['share_list']=$share_list;
	    }else{//夺宝记录
	        
	    
    	    $limit = (($page - 1) * $page_size) . "," . $page_size;
    	    $sql = "select di.*,sum(doi.number) as number from ".DB_PREFIX."deal_order_item as doi ".
    	    				" left join ".DB_PREFIX."duobao_item as di on doi.duobao_item_id = di.id where doi.user_id = ".$id." and doi.refund_status = 0 and doi.pay_status = 2 and di.is_effect = 1  and (doi.type = 2 or doi.type = 4) ";
    	
    	    $sql_count = "select count(distinct(di.id)) from ".DB_PREFIX."deal_order_item as doi  ".
    	    				" left join ".DB_PREFIX."duobao_item as di on doi.duobao_item_id = di.id where doi.user_id = ".$id."  and doi.refund_status = 0  and doi.pay_status = 2 and di.is_effect = 1 and (doi.type = 2 or doi.type = 4) ";
    	
    	    $sql.=$log_type_condition." group by di.id ";
    	    $sql_count.=$log_type_condition;
    	    if($log_type == 0){//夺宝记录
    	        $sql.=" order by doi.create_time desc, di.create_time desc limit ".$limit;
    	    }
    	    elseif($log_type == 1){//幸运记录，按开奖时间排序
    	        $sql.=" order by di.lottery_time desc limit ".$limit;
    	    }
    	    $total = $GLOBALS['db']->getOne($sql_count);
    	    
    	    $res = $GLOBALS['db']->getAll($sql);
    	    
    	    $list = array();
    	    foreach($res as $k=>$v)
    	    {
    	        $list[$k]['id'] = $v['id'];
    	        $list[$k]['name'] = $v['name'];
    	        $list[$k]['icon'] = get_abs_img_root(get_spec_image($v['icon'],200,200,1));
    	        $list[$k]['max_buy'] = $v['max_buy'];
    	        $list[$k]['min_buy'] = $v['min_buy'];
    	        $list[$k]['less'] = $v['max_buy'] - $v['current_buy'];
    	        $list[$k]['number'] = $v['number'];
    	        $list[$k]['success_time'] = $v['success_time'];
    	        $list[$k]['has_lottery'] = $v['has_lottery'];
    	        $list[$k]['progress'] = $v['progress'];
                $list[$k]['is_pk']=$v['is_pk'];
                $list[$k]['is_topspeed']=$v['is_topspeed'];
                $list[$k]['is_number_choose']=$v['is_number_choose'];
    	        if($v['has_lottery']==1)
    	        {
    	            $list[$k]['luck_user_id'] = $v['luck_user_id'];
    	            $list[$k]['luck_user_name'] = $v['luck_user_name'];
    	            $list[$k]['luck_user_total'] = $v['luck_user_buy_count'];
    	            $list[$k]['lottery_sn'] = $v['lottery_sn'];
    	            $list[$k]['lottery_time'] = to_date($v['lottery_time']);
    	        }
    	        else
    	        {
    	            $list[$k]['luck_user_id'] = 0;
    	            $list[$k]['luck_user_name'] ="--";
    	            $list[$k]['luck_user_total'] = "--";
    	            $list[$k]['lottery_sn'] = "--";
    	            $list[$k]['lottery_time'] = "--";
    	        }
    	    }
    	    $root['list'] = $list;
	    }
	       $page_total = ceil($total/$page_size);
	        $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$total);
//    	    $root['is_my'] = $is_my;
    	    
    	    //$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
    	    $root['page_title']="TA的个人中心";
		return output($root);
    } 
    
    
    public function my_no(){
    	$root = array();
    	
    	$user_data = $GLOBALS['user_info'];
    	$user_login_status = check_login();
    	
    		
		$uid = intval($GLOBALS['request']['uid']);
		//id:夺宝期号
		$id  = intval($GLOBALS['request']['id']);
		
		require_once APP_ROOT_PATH."system/model/user.php";
		$home_user = load_user($uid);
		
		$root['user_info']=$home_user;
		
		$root['duobao_item'] = $GLOBALS['db']->getRow("select id,name from ".DB_PREFIX."duobao_item where id = ".$id);
		$total_sql = "select sum(doi.number) from ".DB_PREFIX."deal_order_item as doi  where doi.user_id = ".$uid." and doi.duobao_item_id = ".$id." and doi.refund_status = 0 and doi.pay_status = 2  and (doi.type = 2 or doi.type = 4) ";

		$root['duobao_count'] = $GLOBALS['db']->getOne($total_sql);
		
		$sql = "select doi.id,doi.number,doi.create_time from ".DB_PREFIX."deal_order_item as doi where doi.user_id = ".$uid." and doi.duobao_item_id = ".$id." and doi.refund_status = 0  and doi.pay_status = 2  and (doi.type = 2 or doi.type=4) order by doi.create_time desc";
		
		$list = $GLOBALS['db']->getAll($sql);
		
		foreach($list as $k=>$v)
		{
			$list[$k]['create_time'] = to_date($v['create_time']);
		}
		$root['list'] = $list;
		//$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
		$root['page_title'].="夺宝记录";
		 
    	return output($root);
    } 
    
    public function my_no_all(){
    	$root = array();
    	 
    	$user_data = $GLOBALS['user_info'];
    	$user_login_status = check_login();
    	
		$uid = intval($_REQUEST['uid']);
		//id:夺宝期号
		$id  = intval($_REQUEST['id']);
		//user_id:登陆用户id
		$user_id   = $user_data['id'];
		 
		
		require_once APP_ROOT_PATH."system/model/user.php";
		$home_user = load_user($id);
		
		$root['user_info']=$home_user;
		 
		$root['duobao_item'] = $GLOBALS['db']->getRow("select id,name,has_lottery,log_moved from ".DB_PREFIX."duobao_item where id = ".$id);
		$total_sql = "select sum(doi.number) from ".DB_PREFIX."deal_order_item as doi where doi.user_id = ".$uid." and doi.duobao_item_id = ".$id." and doi.refund_status = 0 and doi.pay_status = 2 and (doi.type = 2 or doi.type = 4) ";
		$root['duobao_count'] = $GLOBALS['db']->getOne($total_sql);
			
		$sql = "select lottery_sn from ".duobao_item_log_table($root['duobao_item'])." where user_id = ".$uid." and duobao_item_id = ".$id;
		
		$list = $GLOBALS['db']->getAll($sql);
		$root['list'] = $list;
		 
		//$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
		$root['page_title'].="夺宝记录";
	
    	return output($root);
    }
}