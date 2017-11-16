<?php
class uc_duobao_recordApiModule extends MainBaseApiModule{

	/**
	 *  我的夺宝记录，即参加的夺宝活动
	 *  
	 *  输入:
	 *  1. log_type:int 夺宝记录类型 0全部 1进行中 2已揭晓
	 *  2. page:当前页数
	 *  
	 * 输出：
	 * user_login_status:int   0表示未登录   1表示已登录  2表示临时登录
	 * login_info:string 未登录状态的提示信息，已登录时无此项
	 * page_title:string 页面标题
	 * page:array 分页信息 array("page"=>当前页数,"page_total"=>总页数,"page_size"=>分页量,"data_total"=>数据总量);
	 * list:array:array 夺宝记录列表，结构如下
 		Array
        (
            [0] => Array
	        (
	            [id] => 10000356 夺宝期号
	            [name] => iPhone6 4.7英寸 64G 新旧包装随机发放
	            [icon] => http://localhost/yydb/public/attachment/201601/22/20/56a2267f52861_400x400.jpg
	            [max_buy] => 6080 总需人次
	            [less] => 5390 剩余数量
	            [number] => 120 本期参与
	            [success_time] => 0 大于零表示已成功，展示中奖相关信息
	            [has_lottery] => 0 为1表示已开奖，展示中奖人信息，否则展示开奖中
	            [luck_user_id] => 0 中奖人id
	            [luck_user_name] =>  中奖人名称
	            [luck_user_total] => 0 中次人本期所购人次
	            [lottery_sn] => 0 中期号
	            [lottery_time] =>  开奖时间
	            [progress]	=>	int 0-100的进度
	        )

	 */
    public function index(){
    	
    	
    	
    	
    	$root = array();
    		
    	$user_data = $GLOBALS['user_info'];
    	
    	$page = intval($GLOBALS['request']['page'])?intval($GLOBALS['request']['page']):1; //当前分页
    	
    	$user_login_status = check_login();
    	if($user_login_status!=LOGIN_STATUS_LOGINED){
    		$root['user_login_status'] = $user_login_status;
    	
    	}else{
    		$root['user_login_status'] = 1;
    			
                //购物车
                require_once APP_ROOT_PATH."system/model/duobao.php";
		$root['cart_info']=duobao::getcart($GLOBALS['user_info']['id']);
                
    		$page_size = PAGE_SIZE;
    		
    		$log_type  = intval($GLOBALS['request']['log_type']);
    		if($log_type==1)
    			$log_type_condition = " and di.success_time = 0 ";
    		elseif($log_type==2)
    		$log_type_condition = " and di.has_lottery = 1 ";
    		else
    			$log_type_condition = "";
    		
    		$user_id   = $user_data['id'];
    		 
    		$limit = (($page - 1) * $page_size) . "," . $page_size;
    		
    		
    		$sql = "select di.*,sum(doi.number) as number from ".DB_PREFIX."deal_order_item as doi ".
    				" left join ".DB_PREFIX."duobao_item as di on doi.duobao_item_id = di.id where doi.user_id = ".$user_id." and doi.refund_status = 0 and doi.pay_status = 2 and di.is_effect = 1 and (doi.type = 2 or doi.type = 4)   ";
    
    		$sql_count = "select count(distinct(di.id)) from ".DB_PREFIX."deal_order_item as doi  ".
    				" left join ".DB_PREFIX."duobao_item as di on doi.duobao_item_id = di.id where doi.user_id = ".$user_id." and doi.refund_status = 0 and doi.pay_status = 2 and di.is_effect = 1 and (doi.type = 2 or doi.type = 4)   ";
    		
    		$sql.=$log_type_condition." group by di.id ";
    		$sql_count.=$log_type_condition;
    		
    		
    		$sql.=" order by doi.create_time desc, di.create_time desc limit ".$limit;
    		

    		 
    		$total = $GLOBALS['db']->getOne($sql_count);
    		$page_total = ceil($total/$page_size);
    		$root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$total);

    		$res = $GLOBALS['db']->getAll($sql);
    		$list = array();
    		foreach($res as $k=>$v)
    		{
    			$list[$k]['id'] = $v['id'];
    			$list[$k]['name'] = $v['name'];
    			$list[$k]['icon'] = get_abs_img_root(get_spec_image($v['icon'],200,200,1));
    			$list[$k]['max_buy'] = $v['max_buy'];
    			$list[$k]['less'] = $v['max_buy'] - $v['current_buy'];
    			$list[$k]['number'] = $v['number'];
    			$list[$k]['success_time'] = $v['success_time'];
    			$list[$k]['has_lottery'] = $v['has_lottery'];
    			$list[$k]['progress'] = $v['progress'];
          $list[$k]['is_five']=$v['is_five'];
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
    		
    			
    		//$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
    		$root['page_title']="夺宝记录";
    	
    	}
    	
    	return output($root);
    	
    	
    }
    
    
    /**
     *  我的夺宝记录,指定的某期夺宝的参与记录，即订单
     *
     *  输入:
     *  1. id:int 指定的夺宝期ID
     *
     * 输出：
     * user_login_status:int   0表示未登录   1表示已登录  2表示临时登录
     * login_info:string 未登录状态的提示信息，已登录时无此项
     * page_title:string 页面标题
     * list:array:array 夺宝订单记录列表，结构如下
     Array
     (
     [0] => Array
     (
    	 [id] => xx 订单商品ID
    	 [number] => 本次夺宝数量
		[create_time] => string 时间格式化
     )
     duobao_item: array 夺宝的活动数据，只包含id与name字段
     duobao_count: int 当前会员获得的夺宝号数量
    
     */
    public function my_no()
    {
    	$root = array();
    	
    	$user_data = $GLOBALS['user_info'];
    	 
    	$user_login_status = check_login();
    	if($user_login_status!=LOGIN_STATUS_LOGINED){
    		$root['user_login_status'] = $user_login_status;
    		 
    	}else{
    		$root['user_login_status'] = 1;
    	
    		$id  = intval($GLOBALS['request']['id']);

    		$user_id   = $user_data['id'];
    		//$user_id = 234;
    		 
  			$root['duobao_item'] = $GLOBALS['db']->getRow("select id,is_coupons,name from ".DB_PREFIX."duobao_item where id = ".$id);
  			if (intval($root['duobao_item'][is_coupons]) == 1) {
  			    $total_sql = "select sum(doi.number) from ".DB_PREFIX."deal_order_item as doi  where doi.user_id = ".$user_id." and doi.duobao_item_id = ".$id." and doi.refund_status = 0 and doi.pay_status = 2  and doi.type = 4 ";
  			    $root['duobao_count'] = $GLOBALS['db']->getOne($total_sql);
  			    	
  			    $sql = "select doi.id,doi.number,doi.create_time from ".DB_PREFIX."deal_order_item as doi where doi.user_id = ".$user_id." and doi.duobao_item_id = ".$id." and doi.refund_status = 0  and doi.pay_status = 2  and doi.type = 4 order by doi.create_time desc";  			    
  			}
  			else{
  			    $total_sql = "select sum(doi.number) from ".DB_PREFIX."deal_order_item as doi  where doi.user_id = ".$user_id." and doi.duobao_item_id = ".$id." and doi.refund_status = 0 and doi.pay_status = 2  and doi.type = 2 ";
  			    $root['duobao_count'] = $GLOBALS['db']->getOne($total_sql);
  			    	
  			    $sql = "select doi.id,doi.number,doi.create_time from ".DB_PREFIX."deal_order_item as doi where doi.user_id = ".$user_id." and doi.duobao_item_id = ".$id." and doi.refund_status = 0  and doi.pay_status = 2  and doi.type = 2 order by doi.create_time desc";
  			}
  			$list = $GLOBALS['db']->getAll($sql);
    		foreach($list as $k=>$v)
    		{
    			$list[$k]['create_time'] = to_date($v['create_time']);
    		}
    		$root['list'] = $list;
    		
    		 
    		//$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
    		$root['page_title'].="夺宝记录";
    		 
    	}
    	 
    	return output($root);
    }
    
    /**
     *  我的夺宝记录,指定的某期夺宝的参与记录，即夺宝号
     *
     *  输入:
     *  1. id:int 指定的夺宝期ID
     *
     * 输出：
     * user_login_status:int   0表示未登录   1表示已登录  2表示临时登录
     * login_info:string 未登录状态的提示信息，已登录时无此项
     * page_title:string 页面标题
     * list:array:array 夺宝幸运号列表，结构如下
     Array
     (
	     [0] => Array
	     (
	     [lottery_sn] => string 幸运号
	     )
     )
     duobao_item: array 夺宝的活动数据，只包含id与name字段
     duobao_count: int 当前会员获得的夺宝号数量
    
     */
    public function my_no_all()
    {
    	$root = array();
    	 
    	$user_data = $GLOBALS['user_info'];
    
    	$user_login_status = check_login();
    	if($user_login_status!=LOGIN_STATUS_LOGINED){
    		$root['user_login_status'] = $user_login_status;
    		 
    	}else{
    		$root['user_login_status'] = 1;
    		 
    		$id  = intval($GLOBALS['request']['id']);
    
    		$user_id   = $user_data['id'];
    		 
    		$root['duobao_item'] = $GLOBALS['db']->getRow("select id,is_coupons,name,has_lottery,log_moved from ".DB_PREFIX."duobao_item where id = ".$id);
    		
    		if (intval($root['duobao_item'][is_coupons]) == 1) {
    		    $total_sql = "select sum(doi.number) from ".DB_PREFIX."deal_order_item as doi where doi.user_id = ".$user_id." and doi.duobao_item_id = ".$id." and doi.refund_status = 0 and doi.pay_status = 2 and doi.type = 4 ";
    		}
    		else {
    		    $total_sql = "select sum(doi.number) from ".DB_PREFIX."deal_order_item as doi where doi.user_id = ".$user_id." and doi.duobao_item_id = ".$id." and doi.refund_status = 0 and doi.pay_status = 2 and doi.type = 2 ";
    		}
    		$root['duobao_count'] = $GLOBALS['db']->getOne($total_sql);
    			
    		$sql = "select lottery_sn from ".duobao_item_log_table($root['duobao_item'])." where user_id = ".$user_id." and duobao_item_id = ".$id;
    		$list = $GLOBALS['db']->getAll($sql);
    		$root['list'] = $list;
    		 
    		 
    		//$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
    		$root['page_title'].="夺宝记录";
    		 
    	}
    
    	return output($root);
    }
}