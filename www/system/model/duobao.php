<?php 

/**
 * 夺宝类
 * @author hc
 *
 */
class duobao
{
	var $duobao_item;
	
	/**
	 * 
	 * @param unknown_type $id 夺宝期号
	 */
	public function __construct($id)
	{

		$this->duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = '".$id."'");
		

		if($this->duobao_item['progress']<100)
		{
			$pool_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$id);
			if($pool_count<$this->duobao_item['max_buy'])
			{
				self::create_lottery_pool($id);
			}

			if($this->duobao_item['robot_is_db'])
			{
				$sql = "select count(*) from ".DB_PREFIX."schedule_list where type in ('robot','robot_cfg') and exec_status = 0 and dest = '".$id."'";			
				$robot_schedule_count = $GLOBALS['db']->getOne($sql);
				if($robot_schedule_count==0)
					self::init_robot($id);
			}
		}
	}
	
	/**
	 * 某个会员将夺宝加入购物车
	 * @param unknown_type $user_id  会员ID
	 * @param unknown_type $number   数量
	 * @param unknown_type $update   是否为数据更新 true:则number为替换  false:为累加
	 * 
	 * 
	 * 返回
	 * array("status"=>xxx,"info"=>xxx);
	 */
	public function addcart($user_id,$number,$update=false)
	{
		if(!$this->duobao_item)
		{
			return array("status"=>0,"info"=>"夺宝活动不存在");
		}
		if($this->duobao_item['progress']>=100)
		{
			return array("status"=>0,"info"=>"夺宝活动已满额");
		}
		
		$cart_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_cart where is_total_buy=0 and user_id = ".$user_id." and duobao_item_id = ".$this->duobao_item['id']);
		
		$number = intval(floor($number/$this->duobao_item['min_buy'])*$this->duobao_item['min_buy']);
		$number = $number<=0?$this->duobao_item['min_buy']:$number;
		$number = $number >= $this->duobao_item['max_buy'] ? $this->duobao_item['max_buy']:$number;
		
		if(empty($cart_item))
		{
			$cart_item['session_id'] = es_session::id();
			$cart_item['user_id'] = $user_id;
			$cart_item['deal_id'] = $this->duobao_item['deal_id'];
			$cart_item['duobao_id'] = $this->duobao_item['duobao_id'];
			$cart_item['duobao_item_id'] = $this->duobao_item['id'];
			$cart_item['name'] = $this->duobao_item['name'];
			$cart_item['unit_price'] = $this->duobao_item['unit_price'];
			$cart_item['number'] = $number;
			$cart_item['is_coupons'] = $this->duobao_item['is_coupons'];
			$cart_item['total_price'] = $number*$this->duobao_item['unit_price'];
			$cart_item['create_time'] = NOW_TIME;
			$cart_item['update_time'] = NOW_TIME;
			$cart_item['return_score'] = $this->duobao_item['duobao_score'];
			$cart_item['return_total_score'] = $number*$this->duobao_item['duobao_score'];
			$cart_item['deal_icon'] = $this->duobao_item['icon'];
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_cart",$cart_item);
		}
		else
		{
			if($update)
			{
			    $cart_item['is_coupons'] = $cart_item['is_coupons'];
				$cart_item['number']=$number;
				$cart_item['total_price']=($number*$cart_item['unit_price']);
				$cart_item['return_total_score']=($number*$cart_item['return_score']);
			}
			else
			{
			    $number = $cart_item['number'] + $number;
			    // 可以购买的数量
			    $can_buy_number = $this->duobao_item['max_buy'] - $this->duobao_item['current_buy'];
			    if ($number > $can_buy_number) {
			        $number = $can_buy_number;
			    } 
			    $cart_item['is_coupons']            = $cart_item['is_coupons'];
				$cart_item['number']                = $number;
				$cart_item['total_price']           = $number*$cart_item['unit_price'];
				$cart_item['return_total_score']    = $number*$cart_item['return_score'];
			}
			$cart_item['session_id'] = es_session::id();
			$cart_item['update_time'] = NOW_TIME;
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_cart",$cart_item,"UPDATE","id=".$cart_item['id']);
		}

		$cart_item_num = $GLOBALS['db']->getOne("select count(distinct(duobao_item_id)) from ".DB_PREFIX."deal_cart where is_total_buy=0 and is_coupons=0 and is_number_choose=0 and is_pk=0 and user_id=".$user_id); 
		return array("status"=>1,"info"=>"已加入清单","cart_item_num"=>$cart_item_num);
	}
    public function addcart_choose_number($user_id,$number,$update=false,$choose_number)
    {
        if(!$this->duobao_item)
        {
            return array("status"=>0,"info"=>"夺宝活动不存在");
        }
        if($this->duobao_item['progress']>=100)
        {
            return array("status"=>0,"info"=>"夺宝活动已满额");
        }

        $cart_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_cart where is_total_buy=0 and user_id = ".$user_id." and duobao_item_id = ".$this->duobao_item['id']);

        $number = intval(floor($number/$this->duobao_item['min_buy'])*$this->duobao_item['min_buy']);
        $number = $number<=0?$this->duobao_item['min_buy']:$number;
        $number = $number >= $this->duobao_item['max_buy'] ? $this->duobao_item['max_buy']:$number;

        if(empty($cart_item))
        {
            $cart_item['session_id'] = es_session::id();
            $cart_item['user_id'] = $user_id;
            $cart_item['is_number_choose']=1;
            $cart_item['choose_number']=$choose_number;
            $cart_item['deal_id'] = $this->duobao_item['deal_id'];
            $cart_item['duobao_id'] = $this->duobao_item['duobao_id'];
            $cart_item['duobao_item_id'] = $this->duobao_item['id'];
            $cart_item['name'] = $this->duobao_item['name'];
            $cart_item['unit_price'] = $this->duobao_item['unit_price'];
            $cart_item['number'] = $number;
//            $cart_item['is_coupons'] = $this->duobao_item['is_coupons'];
            $cart_item['total_price'] = $number*$this->duobao_item['unit_price'];
            $cart_item['create_time'] = NOW_TIME;
            $cart_item['update_time'] = NOW_TIME;
            $cart_item['return_score'] = $this->duobao_item['duobao_score'];
            $cart_item['return_total_score'] = $number*$this->duobao_item['duobao_score'];
            $cart_item['deal_icon'] = $this->duobao_item['icon'];
            $GLOBALS['db']->autoExecute(DB_PREFIX."deal_cart",$cart_item);
            $deal_cart_id=$GLOBALS['db']->insert_id();
        }
        else
        {
            if($update)
            {
//                $cart_item['is_coupons'] = $cart_item['is_coupons'];
                $cart_item['number']=$number;
                $cart_item['is_number_choose']=1;
                $cart_item['total_price']=($number*$cart_item['unit_price']);
                $cart_item['return_total_score']=($number*$cart_item['return_score']);
            }
            else
            {
                $number=count(explode(",",$choose_number));
                // 可以购买的数量
//                $can_buy_number = $this->duobao_item['max_buy'] - $this->duobao_item['current_buy'];
//                if ($number > $can_buy_number) {
//                    $number = $can_buy_number;
//                }
                $cart_item['choose_number']=$choose_number;
//                $cart_item['is_coupons'] = $cart_item['is_coupons'];
                $cart_item['number']                = $number;
                $cart_item['total_price']           = $number*$cart_item['unit_price'];
                $cart_item['return_total_score']    = $number*$cart_item['return_score'];
            }
            $cart_item['session_id'] = es_session::id();
            $cart_item['update_time'] = NOW_TIME;
            $GLOBALS['db']->autoExecute(DB_PREFIX."deal_cart",$cart_item,"UPDATE","id=".$cart_item['id']);
            $deal_cart_id=$cart_item['id'];
        }
        $cart_item_num = $GLOBALS['db']->getOne("select count(distinct(duobao_item_id)) from ".DB_PREFIX."deal_cart where is_total_buy=0 and is_coupons=0 and user_id=".$user_id);

        return array("status"=>1,"info"=>"已加入清单","cart_item_num"=>$cart_item_num,'deal_cart_id'=>$deal_cart_id);
    }
    public function addcart_pk($user_id,$number,$update=false)
    {
        if(!$this->duobao_item)
        {
            return array("status"=>0,"info"=>"夺宝活动不存在");
        }
        if($this->duobao_item['progress']>=100)
        {
            return array("status"=>0,"info"=>"夺宝活动已满额");
        }

        $cart_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_cart where is_total_buy=0 and user_id = ".$user_id." and duobao_item_id = ".$this->duobao_item['id']);

        $number = intval(floor($number/$this->duobao_item['min_buy'])*$this->duobao_item['min_buy']);
        $number = $number<=0?$this->duobao_item['min_buy']:$number;
        $number = $number >= $this->duobao_item['max_buy'] ? $this->duobao_item['max_buy']:$number;

        if(empty($cart_item))
        {
            $cart_item['session_id'] = es_session::id();
            $cart_item['user_id'] = $user_id;
            $cart_item['is_number_choose']=0;
            $cart_item['is_pk']=1;
            $cart_item['deal_id'] = $this->duobao_item['deal_id'];
            $cart_item['duobao_id'] = $this->duobao_item['duobao_id'];
            $cart_item['duobao_item_id'] = $this->duobao_item['id'];
            $cart_item['name'] = $this->duobao_item['name'];
            $cart_item['unit_price'] = $this->duobao_item['unit_price'];
            $cart_item['number'] = $number;
//            $cart_item['is_coupons'] = $this->duobao_item['is_coupons'];
            $cart_item['total_price'] = $number*$this->duobao_item['unit_price'];
            $cart_item['create_time'] = NOW_TIME;
            $cart_item['update_time'] = NOW_TIME;
            $cart_item['return_score'] = $this->duobao_item['duobao_score'];
            $cart_item['return_total_score'] = $number*$this->duobao_item['duobao_score'];
            $cart_item['deal_icon'] = $this->duobao_item['icon'];
            $GLOBALS['db']->autoExecute(DB_PREFIX."deal_cart",$cart_item);
            $deal_cart_id=$GLOBALS['db']->insert_id();
        }
        else
        {
            if($update)
            {
//                $cart_item['is_coupons'] = $cart_item['is_coupons'];
                $cart_item['number']=$number;
                $cart_item['is_number_choose']=1;
                $cart_item['total_price']=($number*$cart_item['unit_price']);
                $cart_item['return_total_score']=($number*$cart_item['return_score']);
            }
            else
            {

                // 可以购买的数量
//                $can_buy_number = $this->duobao_item['max_buy'] - $this->duobao_item['current_buy'];
//                if ($number > $can_buy_number) {
//                    $number = $can_buy_number;
//                }
//                $cart_item['is_coupons'] = $cart_item['is_coupons'];
                $cart_item['number']                = $number;
                $cart_item['total_price']           = $number*$cart_item['unit_price'];
                $cart_item['return_total_score']    = $number*$cart_item['return_score'];
            }
            $cart_item['session_id'] = es_session::id();
            $cart_item['update_time'] = NOW_TIME;
            $GLOBALS['db']->autoExecute(DB_PREFIX."deal_cart",$cart_item,"UPDATE","id=".$cart_item['id']);
            $deal_cart_id=$cart_item['id'];
        }
        $cart_item_num = $GLOBALS['db']->getOne("select count(distinct(duobao_item_id)) from ".DB_PREFIX."deal_cart where is_total_buy=0 and is_coupons=0 and user_id=".$user_id);

        return array("status"=>1,"info"=>"已加入清单","cart_item_num"=>$cart_item_num,'deal_cart_id'=>$deal_cart_id);
    }
	public function add_cart_total_buy($user_id, $number, $update=0)
	{
	    
	    
	    $check_number = self::check_totalbuy_number($this->duobao_item['id']);
	    
	    if ( $check_number['status'] <= 0 ) {
	        return array("status"=>0,"info"=>$check_number['info']);
	    }
	    
	    $total_buy_stock = $check_number['total_buy_stock'];
	    
	    if ( $update == 1 ) {
	        $cart_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_cart where is_total_buy=1 and user_id = ".$user_id." and duobao_item_id = ".$this->duobao_item['id']);
	        // 可以购买的数量
	        if ($number > $total_buy_stock) {
	            $number = $total_buy_stock;
	        }
	        
	        if ($number <=0 ) {
	            $number = 1;
	        }
	        
	        $cart_item['number']                = $number;
	        $cart_item['total_price']           = $number * $this->duobao_item['total_buy_price'];
	        $cart_item['return_score']          = $this->duobao_item['duobao_score'];
	        $cart_item['return_total_score']    = $number * $this->duobao_item['duobao_score'];
	        $cart_item['session_id']            = es_session::id();
	        $cart_item['update_time']           = NOW_TIME;
	        $GLOBALS['db']->autoExecute(DB_PREFIX."deal_cart",$cart_item,"UPDATE","id=".$cart_item['id']);
	    }else{
	        // 可购买的数量
	        if ( $number > $total_buy_stock ) {
	            $number = $total_buy_stock;
	        }
	        
	        if ($number <=0 ) {
	            $number = 1;
	        }
	        
	        // 清空当前用户购物车的直购产品
	        $sql = "delete from ".DB_PREFIX."deal_cart where is_total_buy=1 and user_id = '".$user_id."'";
	        $GLOBALS['db']->query($sql);
	         
	        $cart_item['session_id']       = es_session::id();
	        $cart_item['user_id']          = $user_id;
	        $cart_item['deal_id']          = $this->duobao_item['deal_id'];
	        $cart_item['duobao_id']        = $this->duobao_item['duobao_id'];
	        $cart_item['duobao_item_id']   = $this->duobao_item['id'];
	        $cart_item['name']             = $this->duobao_item['name'];
	        $cart_item['unit_price']       = $this->duobao_item['total_buy_price'];
	        $cart_item['number']           = $number;
	        $cart_item['total_price']      = $number * $this->duobao_item['total_buy_price'];
	        $cart_item['create_time']      = NOW_TIME;
	        $cart_item['update_time']      = NOW_TIME;
	        $cart_item['return_score']     = $this->duobao_item['duobao_score'];
	        $cart_item['return_total_score'] =  $number * $this->duobao_item['duobao_score'];
	        $cart_item['deal_icon']        = $this->duobao_item['icon'];
	        $cart_item['is_total_buy']     = 1;
	        $GLOBALS['db']->autoExecute(DB_PREFIX."deal_cart",$cart_item);
	    }
	    
	    $cart_item = $GLOBALS['db']->getRow("select id, deal_id, duobao_id, duobao_item_id, unit_price, number, total_price, is_total_buy from ".DB_PREFIX."deal_cart where is_total_buy=1 and user_id = ".$user_id." and duobao_item_id = ".$this->duobao_item['id']);
	    return array("status"=>1,"info"=>"已加入清单","cart_item"=>$cart_item);
	    
	}
	
	public static function getcart($user_id){
			if($user_id)
			{
            $cart_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cart where is_total_buy=0 and is_coupons=0 and is_pk=0 and is_number_choose=0 and user_id = ".intval($user_id)." and is_effect=1");
            $result = array();
            foreach($cart_list as $k=>$v){
                $temp = array();
                $temp['id']=$v['duobao_item_id'];
                $temp['name']=$v['name'];
                $temp['number']=$v['number'];
                $temp['unit_price']=$v['unit_price'];
                $temp['id']=$v['duobao_item_id'];
                $temp['id']=$v['duobao_item_id'];
                $result['cart_list'][] = $temp;
            }
            $result['cart_item_num'] = count($result['cart_list']);
			}
			else
			{
				$result['cart_item_num'] = 0;
			}
            return $result;
        }
	
	/**
	 * 为指定的用户下的单子发幸运号
	 * @param unknown_type $user_id
	 * @param unknown_type $order_item_id
	 */
	public function make_lottery_sn($user_id,$order_item_id)
	{
	   
		$deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where user_id = ".$user_id." and id=".$order_item_id." and duobao_item_id = ".$this->duobao_item['id']);
		$deal_cart=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_cart where user_id = ".$user_id." and duobao_item_id = ".$this->duobao_item['id']);
        require_once APP_ROOT_PATH."system/model/user.php";
		$user_info = load_user($deal_order_item['user_id']);
		
		if($deal_order_item['lottery_sn_send']==1)
		{
			return array("status"=>0,"info"=>"幸运号已发放");
		}
		
		$duobao_item_log['deal_id'] = $this->duobao_item['deal_id'];
		$duobao_item_log['duobao_id'] = $this->duobao_item['duobao_id'];
		$duobao_item_log['duobao_item_id'] = $this->duobao_item['id'];
		$duobao_item_log['user_id'] = $user_id;
		$duobao_item_log['order_id'] = $deal_order_item['order_id'];
		$duobao_item_log['order_item_id'] = $deal_order_item['id'];
		$duobao_item_log['duobao_ip'] =  $deal_order_item['duobao_ip'];
		$duobao_item_log['create_time'] = round(get_gmmtime(),3);
		$duobao_item_log['is_robot'] = $user_info['is_robot'];
		
		require_once APP_ROOT_PATH."system/extend/ip.php";
		$ip = new iplocate();
		$area = $ip->getaddress($duobao_item_log['duobao_ip']);
		$duobao_item_log['duobao_area'] = $area['area1'];

		$total = $deal_order_item['number'];		
		
		$duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao where id=".$this->duobao_item['duobao_id']);


       if($deal_cart['is_number_choose']){
           $total = self::update_lottery_by_number($duobao_item_log,$deal_cart['choose_number']);
       }else{
           $total = self::rand_update_lottery($duobao_item_log,$total);
       }

		$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set lottery_sn_send = 1,create_time = ".round(get_gmmtime(),3).",user_name='".$user_info['user_name']."' where id = ".$deal_order_item['id']);
		
		//开始处理积分返还与推荐人返还
		$buy_num = intval($total/$duobao_item['min_buy']);
		$invite_score = intval($this->duobao_item['invite_score'])*$buy_num;
		$duobao_score = intval($this->duobao_item['duobao_score'])*$buy_num;

		
		if($duobao_score)
		{
			modify_account(array("score"=>$duobao_score), $user_id,"参与".$this->duobao_item['name']."夺宝活动");			
		}
		if($user_info['pid'])
		{
			if($invite_score)
			{	
				modify_account(array("score"=>$invite_score), $user_info['pid'],"参与".$this->duobao_item['name']."夺宝活动［邀请购买返利］");
			}
		}
		
		return array("status"=>1,"info"=>"幸运号成功发放","total"=>$total);
	}
	
	
	/**
	 * 检测凑单进度
	 * 完成开启下一期
	 */
	public function check_progress()
	{
	    $now_time = NOW_TIME;
		$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set success_time = ".$now_time." where id = ".$this->duobao_item['id']." and current_buy = max_buy and success_time = 0");
		
		if($GLOBALS['db']->affected_rows())
		{
		    //获取和更新50条记录的时间
		    $success_time_50 = $GLOBALS['db']->getOne("select max(t_doi.create_time) from ".DB_PREFIX."deal_order_item t_doi where (t_doi.type=2 or t_doi.type=4) and t_doi.duobao_item_id = ".$this->duobao_item['id']);
		    $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set success_time_50 = ".$success_time_50." where id = ".$this->duobao_item['id']);
		    
		    
			//将相关订单的duobao_status更新为1
			$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set duobao_status = 1 where duobao_item_id = ".$this->duobao_item['id']);
			
			//生成开奖计划
			//获取完成时间前的全站投注记录作为50条作为数值A
			$sql = "select create_time from ".DB_PREFIX."deal_order_item where lottery_sn=0 and create_time <=".$success_time_50." order by create_time desc limit 50";
			$deal_order_item_logs = $GLOBALS['db']->getAll($sql);
			$fair_sn_local = 0;
			foreach($deal_order_item_logs as $log)
			{
				$create_time = $log['create_time'];
				$data_arr = explode(".", $create_time);
				$date_str = to_date(intval($data_arr[0]),"H:i:s");
				$mmtime = trim($data_arr[1]);
				$res = intval(str_replace(":", "", $date_str).$mmtime);
				$fair_sn_local+=$res;
			}			
			
			$sql = "select * from ".DB_PREFIX."fair_fetch where fair_type = '".$this->duobao_item['fair_type']."' and number is null and drawtime>'".to_date($now_time)."' order by drawtime asc limit 1";

			$fair_periods = $GLOBALS['db']->getAll($sql); 

			if(empty($fair_periods)) //采集表被意外清空，12分钟后以000000开奖
			{
				$lottery_time = NOW_TIME;
				if($this->duobao_item['is_topspeed']){
                    require_once APP_ROOT_PATH."system/model/lottery.php";
                    $lottery=new lottery();
                    $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set lottery_time = ".$lottery_time.",fair_period='000000',fair_sn_local='".$fair_sn_local."' where id = '".$this->duobao_item['id']."'");
                    $lottery->exec(array("duobao_item_id"=>$this->duobao_item['id']));
                }else{
                    $lottery_time = NOW_TIME+12*60;
                    $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set lottery_time = ".$lottery_time.",fair_period='000000',fair_sn_local='".$fair_sn_local."' where id = '".$this->duobao_item['id']."'");
                    send_schedule_plan("lottery", $this->duobao_item['name']."开奖计划", array("duobao_item_id"=>$this->duobao_item['id']), $lottery_time,$this->duobao_item['id']);
                }

			}
			else
			{
				
				$fair_type = $this->duobao_item['fair_type'];
				$cname = $fair_type."_fair_fetch";
				require_once APP_ROOT_PATH."system/fair_fetch/".$cname.".php";
				$fetch_obj = new $cname;
				
				
				$fair_period = $fair_periods[count($fair_periods)-1];
				$lottery_time = to_timespan($fair_period['drawtime']); //延时开奖,比采集延时10分钟

			
	            //logger::write("update ".DB_PREFIX."duobao_item set lottery_time = ".$lottery_time.",fair_period='".$fair_period['period']."',fair_sn_local='".$fair_sn_local."' where id = '".$this->duobao_item['id']."'");
                if($this->duobao_item['is_topspeed']){
                    require_once APP_ROOT_PATH."system/model/lottery.php";
                    $lottery=new lottery();
                    $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set lottery_time = ".$lottery_time.",fair_period='".$fair_period['period']."',fair_sn_local='".$fair_sn_local."' where id = '".$this->duobao_item['id']."'");
                    $lottery->exec(array("duobao_item_id"=>$this->duobao_item['id']));
                }else{
                    $lottery_time +=$fetch_obj->waitsec;
                    $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set lottery_time = ".$lottery_time.",fair_period='".$fair_period['period']."',fair_sn_local='".$fair_sn_local."' where id = '".$this->duobao_item['id']."'");
                    send_schedule_plan("lottery", $this->duobao_item['name']."开奖计划", array("duobao_item_id"=>$this->duobao_item['id']), $lottery_time,$this->duobao_item['id']);
                }

            }
            //如果是pk场，将状态设置为有效
            if($this->duobao_item['is_pk']){
                $GLOBALS['db']->query("update ".DB_PREFIX."duobao set is_effect=1 where id = ".$this->duobao_item['duobao_id']);
            }else{
                self::new_duobao($this->duobao_item['duobao_id']);
            }
			return true;
		}
		else
			return false;
	}
	
	
	/**
	 * 开奖
	 */
	public function draw_lottery($fair_period,$fair_sn)
	{

		if($this->duobao_item['has_lottery']==1)return false; //已开奖跳过
		require_once APP_ROOT_PATH."system/model/user.php";
		$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set fair_sn = '".$fair_sn."',fair_period ='".$fair_period."',lottery_time = ".NOW_TIME." where id = ".$this->duobao_item['id']." and fair_sn = 0");
		if($GLOBALS['db']->affected_rows()||$this->duobao_item['fair_sn']>0)
		{
			$duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = '".$this->duobao_item['id']."'");
			$fair_a_b = intval($duobao_item['fair_sn'])+floatval($duobao_item['fair_sn_local']);
			$lottery_sn = 100000001+(fmod($fair_a_b,intval($duobao_item['max_buy'])));
	
			//logger::write($lottery_sn.":".print_r($duobao_item,1));
			$GLOBALS['db']->query("update ".duobao_item_log_table($this->duobao_item)." set is_luck = 1 where lottery_sn = '".$lottery_sn."' and duobao_item_id = ".$this->duobao_item['id']." and is_luck = 0");
			
			if($GLOBALS['db']->affected_rows())
			{
				$duobao_item_log_luck = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($this->duobao_item)." where is_luck = 1 and duobao_item_id = ".$this->duobao_item['id']);
				
				$luck_user = load_user($duobao_item_log_luck['user_id']);
				//累加机器人下单量
				$robot_buy_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where is_robot = 1 and duobao_item_id = ".$this->duobao_item['id']);
				$luck_user_buy_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$this->duobao_item['id']." and user_id=".$luck_user['id']);
// 				$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set lottery_sn = '".$lottery_sn."',has_lottery = 1,luck_user_id = '".$duobao_item_log_luck['user_id']."',
// 				    luck_user_name='".$luck_user['user_name']."',robot_buy_count=".$robot_buy_count." where id = ".$this->duobao_item['id']." and has_lottery = 0");			
			    //获取下单订单对象
			    $duobao_item_data = array(
				    "lottery_sn"=>$lottery_sn,
				    "has_lottery"=>1,
				    "luck_user_id"=>$duobao_item_log_luck['user_id'],
				    "luck_user_name"=>$luck_user['user_name'],
				    "robot_buy_count"=>$robot_buy_count,
				    "luck_user_buy_count"=>$luck_user_buy_count,
				    "duobao_ip"=>$duobao_item_log_luck['duobao_ip'],
				    "duobao_area"=>$duobao_item_log_luck['duobao_area'],
			        "is_robot"=>$luck_user['is_robot'],
				);
				$GLOBALS['db']->autoExecute(DB_PREFIX."duobao_item",$duobao_item_data,"UPDATE","id =".$this->duobao_item['id']." and has_lottery = 0");
				
				//将相关订单的duobao_status更新为2表示为已开奖
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set duobao_status = 2 where duobao_item_id = ".$this->duobao_item['id']);
				
				if($luck_user['is_robot']==0) //机器人不生成中奖单
				{
				//生成中奖订单
				$duobao_deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$duobao_item_log_luck['order_item_id']);
				$duobao_deal_order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$duobao_deal_order_item['order_id']);
				
				unset($duobao_deal_order['id']);
				$duobao_deal_order['order_sn'] = $duobao_deal_order['order_sn']."_".$this->duobao_item['id'];
				$duobao_deal_order['type'] = 0; //改为中奖订单
				$duobao_deal_order['order_status'] = 0; //进行中
				
				$duobao_deal_order['create_time'] = NOW_TIME;
				$duobao_deal_order['update_time'] = NOW_TIME;
				$duobao_deal_order['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
				$duobao_deal_order['create_date_ym'] = to_date(NOW_TIME,"Y-m");
				$duobao_deal_order['create_date_y'] = to_date(NOW_TIME,"Y");
				$duobao_deal_order['create_date_m'] = to_date(NOW_TIME,"m");
				$duobao_deal_order['create_date_d'] = to_date(NOW_TIME,"d");
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$duobao_deal_order);
				$order_id = $GLOBALS['db']->insert_id();
				if($order_id)
				{
					unset($duobao_deal_order_item['id']);
					
					$duobao_deal_order_item['buy_create_time']=substr($duobao_deal_order_item['create_time'], 0,strpos($duobao_deal_order_item['create_time'],"."));
					$duobao_deal_order_item['buy_number']=$duobao_deal_order_item['number'];
					
					$duobao_deal_order_item['order_id'] = $order_id;
					$duobao_deal_order_item['number'] = 1;
					$duobao_deal_order_item['lottery_sn'] = $duobao_item_log_luck['lottery_sn'];
					
					$duobao_deal_order_item['create_time'] = NOW_TIME;
					$duobao_deal_order_item['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
					$duobao_deal_order_item['create_date_y'] = to_date(NOW_TIME,"Y-m");
					$duobao_deal_order_item['create_date_y'] = to_date(NOW_TIME,"Y");
					$duobao_deal_order_item['create_date_m'] = to_date(NOW_TIME,"m");
					$duobao_deal_order_item['create_date_d'] = to_date(NOW_TIME,"d");
					$duobao_deal_order_item['type'] = 0;
					
					$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_item",$duobao_deal_order_item);
				}

				
				send_msg($duobao_item_log_luck['user_id'], "恭喜您，您参与的".$duobao_item['name']."夺宝活动，中奖了", "orderitem",$duobao_item_log_luck['duobao_item_id']);
				
				send_wx_msg("OPENTM204623681", $duobao_item_log_luck['user_id'], array(),array("duobao_item_id"=>$duobao_item_log_luck['duobao_item_id']));
				
				send_lottery_sms($duobao_item_log_luck['user_id'],$duobao_item);
				}

				
				$order_item_sql = "update ".DB_PREFIX."deal_order_item set create_date_ymd = '".to_date(NOW_TIME,"Y-m-d")."',create_date_ym = '".to_date(NOW_TIME,"Y-m")."',create_date_y = '".to_date(NOW_TIME,"Y")."',create_date_m = '".to_date(NOW_TIME,"m")."',create_date_d = '".to_date(NOW_TIME,"d")."' where type = 2 and  duobao_item_id = '".$this->duobao_item['id']."'";
				$GLOBALS['db']->query($order_item_sql);
			
				//$this->move_duobao_log();
				send_schedule_plan("logmoving", $this->duobao_item['name']."奖池迁移", array("duobao_item_id"=>$this->duobao_item['id']), NOW_TIME,$this->duobao_item['id']); //奖夺宝数据迁移改为生成计划任务
				
			}
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 站内开奖，随机指定一个中奖号，由中奖号倒推出数值B
	 * @param unknown_type $lottery_sn  //指定的中奖号
	 * @return boolean
	 */
	public function draw_lottery_yydb($lottery_sn='')
	{			
		if($this->duobao_item['has_lottery']==1)return false; //已开奖跳过
		$lottery_sn = $GLOBALS['db']->getOne("select lottery_sn from ".duobao_item_log_table($this->duobao_item)." where lottery_sn = '".$lottery_sn."' and duobao_item_id = ".$this->duobao_item['id']);
		if(!$lottery_sn)
		{
			$robot_is_lottery = $GLOBALS['db']->getOne("select robot_is_lottery from ".DB_PREFIX."duobao where id = ".intval($this->duobao_item['duobao_id'])); //机器人必中
			if($robot_is_lottery==1)
			{
				$sql = "select dil.lottery_sn from ".duobao_item_log_table($this->duobao_item)." as dil left join ".DB_PREFIX."user as u on dil.user_id = u.id where dil.duobao_item_id = ".$this->duobao_item['id']." and u.is_robot = 1 order by rand() limit 1";
				$lottery_sn = $GLOBALS['db']->getOne($sql);
				//logger::write($sql);
			}
			
			if(!$lottery_sn)
			$lottery_sn = $GLOBALS['db']->getOne("select lottery_sn from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$this->duobao_item['id']." order by rand() limit 1");
		}					
	
		require_once APP_ROOT_PATH."system/model/user.php";
		$GLOBALS['db']->query("update ".duobao_item_log_table($this->duobao_item)." set is_luck = 1 where lottery_sn = '".$lottery_sn."' and duobao_item_id = ".$this->duobao_item['id']." and is_luck = 0");
		if($GLOBALS['db']->affected_rows()||$this->duobao_item['fair_sn']>0)
		{
			//开始生成数值B
			$mod = $lottery_sn - 100000001;  //应得的余数
			$rand_b = rand(111111,999999); //随机生成的数值B
			$fair_a_b = intval($rand_b)+floatval($this->duobao_item['fair_sn_local']);//a,b值总和
			$rand_mod = fmod($fair_a_b,intval($this->duobao_item['max_buy']));  //随机数值b产生的余数
			$mod_offset = $mod-$rand_mod;  //余数的差额
			$rand_b+=$mod_offset;
			
			
			$duobao_item_log_luck = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($this->duobao_item)." where is_luck = 1 and duobao_item_id = ".$this->duobao_item['id']);
			$luck_user = load_user($duobao_item_log_luck['user_id']);

			//累加机器人下单量
			$robot_buy_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where is_robot = 1 and duobao_item_id = ".$this->duobao_item['id']);
			$luck_user_buy_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$this->duobao_item['id']." and user_id=".$luck_user['id']);
			
			$duobao_item_data = array(
			    "lottery_sn"=>$lottery_sn,
			    "fair_sn"=>$rand_b,
			    "lottery_time" => NOW_TIME,
			    "has_lottery"=>1,
			    "luck_user_id"=>$duobao_item_log_luck['user_id'],
			    "luck_user_name"=>$luck_user['user_name'],
			    "robot_buy_count"=>$robot_buy_count,
			    "luck_user_buy_count"=>$luck_user_buy_count,
			    "duobao_ip"=>$duobao_item_log_luck['duobao_ip'],
			    "duobao_area"=>$duobao_item_log_luck['duobao_area'],
			);
			$GLOBALS['db']->autoExecute(DB_PREFIX."duobao_item",$duobao_item_data,"UPDATE","id =".$this->duobao_item['id']." and has_lottery = 0");
			
			//将相关订单的duobao_status更新为2表示为已开奖
			$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set duobao_status = 2 where duobao_item_id = ".$this->duobao_item['id']);
			
			
			if($luck_user['is_robot']==0) //机器人不生成中奖单
			{
				//生成中奖订单
				$duobao_deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$duobao_item_log_luck['order_item_id']);
				$duobao_deal_order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$duobao_deal_order_item['order_id']);
			
				unset($duobao_deal_order['id']);
				$duobao_deal_order['order_sn'] = $duobao_deal_order['order_sn']."_".$this->duobao_item['id'];
				$duobao_deal_order['type'] = 0; //改为中奖订单
				$duobao_deal_order['order_status'] = 0; //进行中
				
				$duobao_deal_order['create_time'] = NOW_TIME;
				$duobao_deal_order['update_time'] = NOW_TIME;
				$duobao_deal_order['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
				$duobao_deal_order['create_date_y'] = to_date(NOW_TIME,"Y-m");
				$duobao_deal_order['create_date_y'] = to_date(NOW_TIME,"Y");
				$duobao_deal_order['create_date_m'] = to_date(NOW_TIME,"m");
				$duobao_deal_order['create_date_d'] = to_date(NOW_TIME,"d");
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$duobao_deal_order);
				$order_id = $GLOBALS['db']->insert_id();
				if($order_id)
				{
					unset($duobao_deal_order_item['id']);
					
					$duobao_deal_order_item['buy_create_time']=substr($duobao_deal_order_item['create_time'], 0,strpos($duobao_deal_order_item['create_time'],"."));
					$duobao_deal_order_item['buy_number']=$duobao_deal_order_item['number'];
					
					$duobao_deal_order_item['order_id'] = $order_id;
					$duobao_deal_order_item['number'] = 1;
					$duobao_deal_order_item['lottery_sn'] = $duobao_item_log_luck['lottery_sn'];
					
					$duobao_deal_order_item['create_time'] = NOW_TIME;
					$duobao_deal_order_item['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
					$duobao_deal_order_item['create_date_y'] = to_date(NOW_TIME,"Y-m");
					$duobao_deal_order_item['create_date_y'] = to_date(NOW_TIME,"Y");
					$duobao_deal_order_item['create_date_m'] = to_date(NOW_TIME,"m");
					$duobao_deal_order_item['create_date_d'] = to_date(NOW_TIME,"d");
					$duobao_deal_order_item['type'] = 0;
					
					$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_item",$duobao_deal_order_item);
				}	
				
				send_msg($duobao_item_log_luck['user_id'], "恭喜您，您参与的".$this->duobao_item['name']."夺宝活动，中奖了", "orderitem",$duobao_item_log_luck['duobao_item_id']);
				
				send_wx_msg("OPENTM204623681", $duobao_item_log_luck['user_id'], array(),array("duobao_item_id"=>$duobao_item_log_luck['duobao_item_id']));
					
				send_lottery_sms($duobao_item_log_luck['user_id'],$this->duobao_item);
			}
				
				$order_item_sql = "update ".DB_PREFIX."deal_order_item set create_date_ymd = '".to_date(NOW_TIME,"Y-m-d")."',create_date_ym = '".to_date(NOW_TIME,"Y-m")."',create_date_y = '".to_date(NOW_TIME,"Y")."',create_date_m = '".to_date(NOW_TIME,"m")."',create_date_d = '".to_date(NOW_TIME,"d")."' where type = 2 and  duobao_item_id = '".$this->duobao_item['id']."'";
				$GLOBALS['db']->query($order_item_sql);
				
				//$this->move_duobao_log();
				send_schedule_plan("logmoving", $this->duobao_item['name']."奖池迁移", array("duobao_item_id"=>$this->duobao_item['id']), NOW_TIME,$this->duobao_item['id']); //奖夺宝数据迁移改为生成计划任务
				
			return true;
		}
		else
		{
			return false;
		}
		
		
		
	}
    public function topspeed_draw_lottery_yydb($lottery_sn='')
    {
        if($this->duobao_item['has_lottery']==1)return false; //已开奖跳过
        $lottery_sn = $GLOBALS['db']->getOne("select lottery_sn from ".duobao_item_log_table($this->duobao_item)." where lottery_sn = '".$lottery_sn."' and duobao_item_id = ".$this->duobao_item['id']);
        if(!$lottery_sn)
        {
            $robot_is_lottery = $GLOBALS['db']->getOne("select robot_is_lottery from ".DB_PREFIX."duobao where id = ".intval($this->duobao_item['duobao_id'])); //机器人必中
            if($robot_is_lottery==1)
            {
                $sql = "select dil.lottery_sn from ".duobao_item_log_table($this->duobao_item)." as dil left join ".DB_PREFIX."user as u on dil.user_id = u.id where dil.duobao_item_id = ".$this->duobao_item['id']." and u.is_robot = 1 order by rand() limit 1";
                $lottery_sn = $GLOBALS['db']->getOne($sql);
                //logger::write($sql);
            }

            if(!$lottery_sn)
                $lottery_sn = $GLOBALS['db']->getOne("select lottery_sn from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$this->duobao_item['id']." order by rand() limit 1");
        }

        require_once APP_ROOT_PATH."system/model/user.php";
        $GLOBALS['db']->query("update ".duobao_item_log_table($this->duobao_item)." set is_luck = 1 where lottery_sn = '".$lottery_sn."' and duobao_item_id = ".$this->duobao_item['id']." and is_luck = 0");
        if($GLOBALS['db']->affected_rows()||$this->duobao_item['fair_sn']>0)
        {
            //开始生成数值B
            $mod = $lottery_sn - 100000001;  //应得的余数
            $rand_b = rand(111111,999999); //随机生成的数值B
            $fair_a_b = intval($rand_b)+floatval($this->duobao_item['fair_sn_local']);//a,b值总和
            $rand_mod = fmod($fair_a_b,intval($this->duobao_item['max_buy']));  //随机数值b产生的余数
            $mod_offset = $mod-$rand_mod;  //余数的差额
            $rand_b+=$mod_offset;


            $duobao_item_log_luck = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($this->duobao_item)." where is_luck = 1 and duobao_item_id = ".$this->duobao_item['id']);
            $luck_user = load_user($duobao_item_log_luck['user_id']);

            //累加机器人下单量
            $robot_buy_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where is_robot = 1 and duobao_item_id = ".$this->duobao_item['id']);
            $luck_user_buy_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$this->duobao_item['id']." and user_id=".$luck_user['id']);

            $duobao_item_data = array(
                "lottery_sn"=>$lottery_sn,
                "fair_sn"=>$rand_b,
                "lottery_time" => NOW_TIME,
                "has_lottery"=>1,
                "luck_user_id"=>$duobao_item_log_luck['user_id'],
                "luck_user_name"=>$luck_user['user_name'],
                "robot_buy_count"=>$robot_buy_count,
                "luck_user_buy_count"=>$luck_user_buy_count,
                "duobao_ip"=>$duobao_item_log_luck['duobao_ip'],
                "duobao_area"=>$duobao_item_log_luck['duobao_area'],
            );
            $GLOBALS['db']->autoExecute(DB_PREFIX."duobao_item",$duobao_item_data,"UPDATE","id =".$this->duobao_item['id']." and has_lottery = 0");

            //将相关订单的duobao_status更新为2表示为已开奖
            $GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set duobao_status = 2 where duobao_item_id = ".$this->duobao_item['id']);


            if($luck_user['is_robot']==0) //机器人不生成中奖单
            {
                //生成中奖订单
                $duobao_deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$duobao_item_log_luck['order_item_id']);
                $duobao_deal_order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$duobao_deal_order_item['order_id']);

                unset($duobao_deal_order['id']);
                $duobao_deal_order['order_sn'] = $duobao_deal_order['order_sn']."_".$this->duobao_item['id'];
                $duobao_deal_order['type'] = 0; //改为中奖订单
                $duobao_deal_order['order_status'] = 0; //进行中

                $duobao_deal_order['create_time'] = NOW_TIME;
                $duobao_deal_order['update_time'] = NOW_TIME;
                $duobao_deal_order['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
                $duobao_deal_order['create_date_y'] = to_date(NOW_TIME,"Y-m");
                $duobao_deal_order['create_date_y'] = to_date(NOW_TIME,"Y");
                $duobao_deal_order['create_date_m'] = to_date(NOW_TIME,"m");
                $duobao_deal_order['create_date_d'] = to_date(NOW_TIME,"d");

                $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$duobao_deal_order);
                $order_id = $GLOBALS['db']->insert_id();
                if($order_id)
                {
                    unset($duobao_deal_order_item['id']);

                    $duobao_deal_order_item['buy_create_time']=substr($duobao_deal_order_item['create_time'], 0,strpos($duobao_deal_order_item['create_time'],"."));
                    $duobao_deal_order_item['buy_number']=$duobao_deal_order_item['number'];

                    $duobao_deal_order_item['order_id'] = $order_id;
                    $duobao_deal_order_item['number'] = 1;
                    $duobao_deal_order_item['lottery_sn'] = $duobao_item_log_luck['lottery_sn'];

                    $duobao_deal_order_item['create_time'] = NOW_TIME;
                    $duobao_deal_order_item['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
                    $duobao_deal_order_item['create_date_y'] = to_date(NOW_TIME,"Y-m");
                    $duobao_deal_order_item['create_date_y'] = to_date(NOW_TIME,"Y");
                    $duobao_deal_order_item['create_date_m'] = to_date(NOW_TIME,"m");
                    $duobao_deal_order_item['create_date_d'] = to_date(NOW_TIME,"d");
                    $duobao_deal_order_item['type'] = 0;

                    $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_item",$duobao_deal_order_item);
                }

                send_msg($duobao_item_log_luck['user_id'], "恭喜您，您参与的".$this->duobao_item['name']."夺宝活动，中奖了", "orderitem",$duobao_item_log_luck['duobao_item_id']);

                send_wx_msg("OPENTM204623681", $duobao_item_log_luck['user_id'], array(),array("duobao_item_id"=>$duobao_item_log_luck['duobao_item_id']));

                send_lottery_sms($duobao_item_log_luck['user_id'],$this->duobao_item);
            }

            $order_item_sql = "update ".DB_PREFIX."deal_order_item set create_date_ymd = '".to_date(NOW_TIME,"Y-m-d")."',create_date_ym = '".to_date(NOW_TIME,"Y-m")."',create_date_y = '".to_date(NOW_TIME,"Y")."',create_date_m = '".to_date(NOW_TIME,"m")."',create_date_d = '".to_date(NOW_TIME,"d")."' where type = 2 and  duobao_item_id = '".$this->duobao_item['id']."'";
            $GLOBALS['db']->query($order_item_sql);

            //$this->move_duobao_log();
//            send_schedule_plan("logmoving", $this->duobao_item['name']."奖池迁移", array("duobao_item_id"=>$this->duobao_item['id']), NOW_TIME,$this->duobao_item['id']); //奖夺宝数据迁移改为生成计划任务
            require_once APP_ROOT_PATH."system/model/logmoving.php";
            $logmoving=new logmoving();
            $result=$logmoving->exec(array("duobao_item_id"=>$this->duobao_item['id']));
            return true;
        }
        else
        {
            return false;
        }



    }
    public function tospeed_draw_lottery($fair_period,$fair_sn)
    {
        if($this->duobao_item['has_lottery']==1)return false; //已开奖跳过
        require_once APP_ROOT_PATH."system/model/user.php";
        $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set fair_sn = '".$fair_sn."',fair_period ='".$fair_period."',lottery_time = ".NOW_TIME." where id = ".$this->duobao_item['id']." and fair_sn = 0");
        if($GLOBALS['db']->affected_rows()||$this->duobao_item['fair_sn']>0)
        {
            $duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = '".$this->duobao_item['id']."'");
//            $fair_a_b = intval($duobao_item['fair_sn'])+floatval($duobao_item['fair_sn_local']);
            $fair_a_b = floatval($duobao_item['fair_sn_local']);
            $lottery_sn = 100000001+(fmod($fair_a_b,intval($duobao_item['max_buy'])));

            //logger::write($lottery_sn.":".print_r($duobao_item,1));
            $GLOBALS['db']->query("update ".duobao_item_log_table($this->duobao_item)." set is_luck = 1 where lottery_sn = '".$lottery_sn."' and duobao_item_id = ".$this->duobao_item['id']." and is_luck = 0");

            if($GLOBALS['db']->affected_rows())
            {
                $duobao_item_log_luck = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($this->duobao_item)." where is_luck = 1 and duobao_item_id = ".$this->duobao_item['id']);

                $luck_user = load_user($duobao_item_log_luck['user_id']);
                //累加机器人下单量
                $robot_buy_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where is_robot = 1 and duobao_item_id = ".$this->duobao_item['id']);
                $luck_user_buy_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$this->duobao_item['id']." and user_id=".$luck_user['id']);
// 				$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set lottery_sn = '".$lottery_sn."',has_lottery = 1,luck_user_id = '".$duobao_item_log_luck['user_id']."',
// 				    luck_user_name='".$luck_user['user_name']."',robot_buy_count=".$robot_buy_count." where id = ".$this->duobao_item['id']." and has_lottery = 0");
                //获取下单订单对象
                $duobao_item_data = array(
                    "lottery_sn"=>$lottery_sn,
                    "has_lottery"=>1,
                    "luck_user_id"=>$duobao_item_log_luck['user_id'],
                    "luck_user_name"=>$luck_user['user_name'],
                    "robot_buy_count"=>$robot_buy_count,
                    "luck_user_buy_count"=>$luck_user_buy_count,
                    "duobao_ip"=>$duobao_item_log_luck['duobao_ip'],
                    "duobao_area"=>$duobao_item_log_luck['duobao_area'],
                    "is_robot"=>$luck_user['is_robot'],
                );
                $GLOBALS['db']->autoExecute(DB_PREFIX."duobao_item",$duobao_item_data,"UPDATE","id =".$this->duobao_item['id']." and has_lottery = 0");

                //将相关订单的duobao_status更新为2表示为已开奖
                $GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set duobao_status = 2 where duobao_item_id = ".$this->duobao_item['id']);

                if($luck_user['is_robot']==0) //机器人不生成中奖单
                {
                    //生成中奖订单
                    $duobao_deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$duobao_item_log_luck['order_item_id']);
                    $duobao_deal_order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$duobao_deal_order_item['order_id']);

                    unset($duobao_deal_order['id']);
                    $duobao_deal_order['order_sn'] = $duobao_deal_order['order_sn']."_".$this->duobao_item['id'];
                    $duobao_deal_order['type'] = 0; //改为中奖订单
                    $duobao_deal_order['order_status'] = 0; //进行中

                    $duobao_deal_order['create_time'] = NOW_TIME;
                    $duobao_deal_order['update_time'] = NOW_TIME;
                    $duobao_deal_order['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
                    $duobao_deal_order['create_date_ym'] = to_date(NOW_TIME,"Y-m");
                    $duobao_deal_order['create_date_y'] = to_date(NOW_TIME,"Y");
                    $duobao_deal_order['create_date_m'] = to_date(NOW_TIME,"m");
                    $duobao_deal_order['create_date_d'] = to_date(NOW_TIME,"d");

                    $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$duobao_deal_order);
                    $order_id = $GLOBALS['db']->insert_id();
                    if($order_id)
                    {
                        unset($duobao_deal_order_item['id']);

                        $duobao_deal_order_item['buy_create_time']=substr($duobao_deal_order_item['create_time'], 0,strpos($duobao_deal_order_item['create_time'],"."));
                        $duobao_deal_order_item['buy_number']=$duobao_deal_order_item['number'];

                        $duobao_deal_order_item['order_id'] = $order_id;
                        $duobao_deal_order_item['number'] = 1;
                        $duobao_deal_order_item['lottery_sn'] = $duobao_item_log_luck['lottery_sn'];

                        $duobao_deal_order_item['create_time'] = NOW_TIME;
                        $duobao_deal_order_item['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
                        $duobao_deal_order_item['create_date_y'] = to_date(NOW_TIME,"Y-m");
                        $duobao_deal_order_item['create_date_y'] = to_date(NOW_TIME,"Y");
                        $duobao_deal_order_item['create_date_m'] = to_date(NOW_TIME,"m");
                        $duobao_deal_order_item['create_date_d'] = to_date(NOW_TIME,"d");
                        $duobao_deal_order_item['type'] = 0;

                        $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_item",$duobao_deal_order_item);
                    }


                    send_msg($duobao_item_log_luck['user_id'], "恭喜您，您参与的".$duobao_item['name']."夺宝活动，中奖了", "orderitem",$duobao_item_log_luck['duobao_item_id']);

                    send_wx_msg("OPENTM204623681", $duobao_item_log_luck['user_id'], array(),array("duobao_item_id"=>$duobao_item_log_luck['duobao_item_id']));

                    send_lottery_sms($duobao_item_log_luck['user_id'],$duobao_item);
                }


                $order_item_sql = "update ".DB_PREFIX."deal_order_item set create_date_ymd = '".to_date(NOW_TIME,"Y-m-d")."',create_date_ym = '".to_date(NOW_TIME,"Y-m")."',create_date_y = '".to_date(NOW_TIME,"Y")."',create_date_m = '".to_date(NOW_TIME,"m")."',create_date_d = '".to_date(NOW_TIME,"d")."' where type = 2 and  duobao_item_id = '".$this->duobao_item['id']."'";
                $GLOBALS['db']->query($order_item_sql);

                //$this->move_duobao_log();
                require_once APP_ROOT_PATH."system/model/logmoving.php";
                $logmoving=new logmoving();
                $result=$logmoving->exec(array("duobao_item_id"=>$this->duobao_item['id']));
//                send_schedule_plan("logmoving", $this->duobao_item['name']."奖池迁移", array("duobao_item_id"=>$this->duobao_item['id']), NOW_TIME,$this->duobao_item['id']); //奖夺宝数据迁移改为生成计划任务

            }
            return true;
        }
        else
        {
            return false;
        }



    }
	
	
	/**
	 * 将开奖后的夺宝号迁移到历史表
	 */
	public function move_duobao_log()
	{
		$duobao_item_id = $this->duobao_item['id'];
		$sql = "insert into ".DB_PREFIX."duobao_item_log_history (`id`,`deal_id`,`duobao_id`,`duobao_item_id`,`lottery_sn`,`user_id`,`order_id`,`order_item_id`,`create_time`,`is_luck`,`duobao_ip`,`duobao_area`,`is_robot`,`create_date_ymd`,`create_date_ym`,`create_date_y`,`create_date_m`,`create_date_d`) 
				select `id`,`deal_id`,`duobao_id`,`duobao_item_id`,`lottery_sn`,`user_id`,`order_id`,`order_item_id`,`create_time`,`is_luck`,`duobao_ip`,`duobao_area`,`is_robot`,'".to_date(NOW_TIME,"Y-m-d")."','".to_date(NOW_TIME,"Y-m")."','".to_date(NOW_TIME,"Y")."','".to_date(NOW_TIME,"m")."','".to_date(NOW_TIME,"d")."' from ".DB_PREFIX."duobao_item_log where duobao_item_id = ".$duobao_item_id;
		$GLOBALS['db']->query($sql);
		if($GLOBALS['db']->affected_rows())
		{
			$GLOBALS['db']->query("delete from ".DB_PREFIX."duobao_item_log where duobao_item_id = ".$duobao_item_id);
			$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set log_moved = 1 where id = ".$duobao_item_id);
		}
	}
	
	/**
	 * 删除夺宝活动
	 */
	public function del_duobao()
	{
		$history_duobao_item = $this->duobao_item;
		//$history_duobao_item['history_duobao_item_log'] = serialize($GLOBALS['db']->getRow("select * from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$this->duobao_item['id']." where is_luck = 1 limit 1"));
		$GLOBALS['db']->query("delete from ".DB_PREFIX."duobao_item where id = ".$this->duobao_item['id']);
		if($GLOBALS['db']->affected_rows())
		{
			//$GLOBALS['db']->autoExecute(DB_PREFIX."duobao_item_history",$history_duobao_item);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order_item where duobao_item_id = ".$this->duobao_item['id']);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."duobao_item_log where duobao_item_id = ".$this->duobao_item['id']);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."duobao_item_log_history where duobao_item_id = ".$this->duobao_item['id']);
			$GLOBALS['db']->query("delete from ".DB_PREFIX."share where duobao_item_id = ".$this->duobao_item['id']);
		}
	}
	

	/**
	 * 生成新的一期夺宝
	 * 1. 为夺宝计划更新已开期数
	 * 2. 同步夺宝计划表的相关数据，动态生成当前的夺宝活动
	 * 3. 依据机器人的设置生成机器人计划任务
	 * return array("status"=>1,"info"=>"xxx","duobao_item"=>NULL);
	 */
	public static function new_duobao($duobao_id)
	{
	    
		$GLOBALS['db']->query("update ".DB_PREFIX."duobao set current_schedule = current_schedule + 1 where id = ".$duobao_id." and current_schedule + 1 <= max_schedule and is_effect = 1 and is_pk=0");
		//var_dump("update ".DB_PREFIX."duobao set current_schedule = current_schedule + 1 where id = ".$duobao_id." and current_schedule + 1 <= max_schedule and is_effect = 1 and is_pk=0");exit;
		if($GLOBALS['db']->affected_rows())
		{

			$duobao = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao where id = ".intval($duobao_id));
			$duobao_item['deal_id'] = $duobao['deal_id'];
			$duobao_item['duobao_id'] = $duobao['id'];
			$duobao_item['name'] = $duobao['name'];
			$duobao_item['cate_id'] = $duobao['cate_id'];
			$duobao_item['description'] = $duobao['description'];
			$duobao_item['is_effect'] = $duobao['is_effect'];
			$duobao_item['brief'] = $duobao['brief'];
			$duobao_item['icon'] = $duobao['icon'];
			$duobao_item['brand_id'] = $duobao['brand_id'];
			$duobao_item['deal_gallery'] = $duobao['deal_gallery'];
			$duobao_item['create_time'] = NOW_TIME;
			$duobao_item['duobao_score'] = $duobao['duobao_score'];
			$duobao_item['invite_score'] = $duobao['invite_score'];
			$duobao_item['max_buy'] = $duobao['max_buy'];
			$duobao_item['min_buy'] = $duobao['min_buy'];
			$duobao_item['fair_type'] = $duobao['fair_type'];
			$duobao_item['robot_end_time'] = $duobao['robot_end_time'];
			$duobao_item['robot_is_db'] = $duobao['robot_is_db'];
			$duobao_item['origin_price'] = $duobao['origin_price'];
			$duobao_item['unit_price'] = $duobao['unit_price'];
			$duobao_item['user_max_buy'] = $duobao['user_max_buy'];
			// 免费购参数
			$duobao_item['is_coupons']    = $duobao['is_coupons'];
			// 直购参数
			$duobao_item['is_total_buy']    = $duobao['is_total_buy'];
            //选号专区判断
            $duobao_item['is_number_choose']=$duobao['is_number_choose'];
			$duobao_item['total_buy_price'] = $duobao['total_buy_price'];
            //极速专区判断
			$duobao_item['is_topspeed']=$duobao['is_topspeed'];
            //pk专区判断
            $duobao_item['is_pk']=$duobao['is_pk'];
             //五倍专场
            $duobao_item['is_five']=$duobao['is_five'];
			$GLOBALS['db']->autoExecute(DB_PREFIX."duobao_item",$duobao_item);
			$duobao_item_id = $GLOBALS['db']->insert_id();

			
			self::init_robot($duobao_item_id);
			
			//生成开奖池
			$total = self::create_lottery_pool($duobao_item_id);
            //如果为pk场开完一期就设置无效
//            if($duobao_item['is_pk']){
//            $GLOBALS['db']->query("update ".DB_PREFIX."duobao set is_effect=0 where id = ".$duobao_id." and current_schedule + 1 <= max_schedule and is_effect = 1");
//            }


            //开启机器人计划任务
// 			$robot_count = $duobao_item['robot_count'];
// 			$robot_list = $duobao_item['robot_list'];
// 			$robot_list = unserialize($robot_list);
// 			$robot_end_time = $duobao_item['robot_end_time'];  //机器人任务结束时间(分)
// 			$robot_min_time = $duobao_item['robot_min_time']; //(分)
// 			$robot_order_total = $duobao_item['robot_order_total'];
// 			$robot_is_db = $duobao_item['robot_is_db'];
// 			$min_buy = $duobao_item['min_buy'];
			
// 			$schedule_count = ceil($robot_end_time/$robot_min_time);
// 			$schedule_order = ceil($robot_order_total/$schedule_count/$min_buy);  //机器人每次买的最大份数
// 			$robot_schedule_time = NOW_TIME;
// 			for($i=1;$i<=$schedule_count;$i++)
// 			{
// 				$robot = $robot_list[rand(0,$robot_count-1)];
// 				$robot_schedule_time = $robot_schedule_time + rand($robot_min_time*60,$robot_min_time*2*60);  //下单时间
// 				$robot_schedule_data['user_name'] = $robot['user_name'];
// 				$robot_schedule_data['duobao_item_id'] = $duobao_item_id;
				
// 				if($robot_schedule_time>=NOW_TIME+$robot_end_time*60&&$robot_is_db)
// 				{
// 					//最后一单
// 					$duobao_number = -1;
// 				}
// 				else
// 				{
// 					$duobao_number = rand(1,$schedule_order)*$min_buy;
// 				}
// 				$robot_schedule_data['duobao_number'] = $duobao_number;
// 				send_schedule_plan("robot", "机器人下单任务", $robot_schedule_data, $robot_schedule_time);
// 				if($duobao_number==-1)
// 				{
// 					break;
// 				}
// 			}
						
			return array("status"=>1,"info"=>"夺宝开启","duobao_item"=>new duobao($duobao_item_id));
		}
		else
		{
			return array("status"=>0,"info"=>"夺宝活动已期满");
		}
	}
    /**
     * 生成新的一期pk
     * 1. 为pk计划更新已开期数
     * 2. 同步夺宝计划表的相关数据，动态生成当前的夺宝活动
     * 3. 依据机器人的设置生成机器人计划任务
     * $config array(pk_password,has_password,min_buy,user_max_buy,pk_user_id)
     * return array("status"=>1,"info"=>"xxx","duobao_item"=>NULL);
     */
    public static function new_pk($config)
    {
        //查找该计划的未激活的夺宝活动
        $number=$GLOBALS['db']->getRow("select *,count(*) as pk_number  from ".DB_PREFIX."duobao_item where duobao_id = ".$config['duobao_id']." and  has_lottery=0 and is_active=0 and is_pk=1 ");
        if($number['pk_number']>0){
            //已有的夺宝计划
            if($number['create_time']<(NOW_TIME-5*60)||$number['pk_user_id']==$config['pk_user_id']){
                $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item
                set pk_password='".$config['pk_password']."',
                has_password='".$config['has_password']."',
                min_buy='".$config['min_buy']."',
                create_time='".NOW_TIME."',
                pk_user_id=".$config['pk_user_id'].",
                user_max_buy='".$config['user_max_buy']."'
                where duobao_id = ".$config['duobao_id']." and is_effect = 1 and is_active=0 and current_buy=0");
                if($GLOBALS['db']->affected_rows()){
                    return array("status"=>1,"info"=>"pk开启","duobao_item"=>new duobao($number['id']));
                }else{
                    return array("status"=>0,"info"=>"pk活动发起失败");
                }
            }else{
                return array("status"=>0,"info"=>"pk活动有人发起中");
            }
        }
        //重新开一期
        $GLOBALS['db']->query("update ".DB_PREFIX."duobao set current_schedule = current_schedule + 1 where id = ".$config['duobao_id']." and current_schedule + 1 <= max_schedule and is_effect = 1");
        if($GLOBALS['db']->affected_rows())
        {
            $duobao = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao where id = ".intval($config['duobao_id']));
            $duobao_item['deal_id'] = $duobao['deal_id'];
            $duobao_item['duobao_id'] = $duobao['id'];
            $duobao_item['name'] = $duobao['name'];
            $duobao_item['pk_user_id']=$config['pk_user_id'];
            $duobao_item['cate_id'] = $duobao['cate_id'];
            $duobao_item['description'] = $duobao['description'];
            $duobao_item['is_effect'] = 1;
            $duobao_item['brief'] = $duobao['brief'];
            $duobao_item['icon'] = $duobao['icon'];
            $duobao_item['brand_id'] = $duobao['brand_id'];
            $duobao_item['deal_gallery'] = $duobao['deal_gallery'];
            $duobao_item['create_time'] = NOW_TIME;
            $duobao_item['duobao_score'] = $duobao['duobao_score'];
            $duobao_item['invite_score'] = $duobao['invite_score'];
            $duobao_item['max_buy'] = $duobao['max_buy'];
            $duobao_item['min_buy'] = $config['min_buy'];
            $duobao_item['fair_type'] = $duobao['fair_type'];
            $duobao_item['robot_end_time'] = $duobao['robot_end_time'];
            $duobao_item['robot_is_db'] = $duobao['robot_is_db'];
            $duobao_item['origin_price'] = $duobao['origin_price'];
            $duobao_item['unit_price'] = $duobao['unit_price'];
            $duobao_item['user_max_buy'] = $config['user_max_buy'];
            // 免费购参数
            $duobao_item['is_coupons']    = $duobao['is_coupons'];
            // 直购参数
            $duobao_item['is_total_buy']    = $duobao['is_total_buy'];
            //选号专区判断
            $duobao_item['is_number_choose']=$duobao['is_number_choose'];
            $duobao_item['total_buy_price'] = $duobao['total_buy_price'];
            //极速专区判断
            $duobao_item['is_topspeed']=$duobao['is_topspeed'];
            //pk专区判断
            $duobao_item['is_pk']=$duobao['is_pk'];
            $duobao_item['has_password']=$config['has_password'];
            $duobao_item['pk_password']=$config['pk_password'];
            $GLOBALS['db']->autoExecute(DB_PREFIX."duobao_item",$duobao_item);
            $duobao_item_id = $GLOBALS['db']->insert_id();


            self::init_robot($duobao_item_id);

            //生成开奖池
            $total = self::create_lottery_pool($duobao_item_id);
//            //如果为pk场开完一期就设置无效
//            if($duobao_item['is_pk']){
//                $GLOBALS['db']->query("update ".DB_PREFIX."duobao set is_effect=0 where id = ".$config['duobao_id']." and current_schedule + 1 <= max_schedule and is_effect = 1");
//            }
            return array("status"=>1,"info"=>"pk开启","duobao_item"=>new duobao($duobao_item_id));
        }
        else
        {
            return array("status"=>0,"info"=>"pk活动已期满");
        }
    }
	
	/**
	 * 获取夺宝数据列表
	 */
	public static function get_list($cate_id=0,$orderby="",$success=false,$page=1,$order_dir=0)
	{
		$sql = "select *, (max_buy - current_buy) surplus_buy  from ".DB_PREFIX."duobao_item where is_effect=1 and is_coupons=0 and is_pk=0 and is_number_choose=0 and unit_price=1 and is_topspeed=0 and is_five=0";
		if($cate_id>0)
			$sql.=" and cate_id = ".$cate_id." ";
		if($success)
		{
			$sql.=" and progress = 100 ";
		}
		else
		{
			$sql.=" and success_time = 0 and progress < 100";
		}
		

		if($orderby!="")
		{
			if($order_dir==1){
				$orderby = $orderby." asc";  //升序
			}else{
				$orderby = $orderby." desc ";  //降序
			}

		}else{
			$orderby = " progress desc ";
		}	
		if($orderby != "create_time desc "){
		    $orderby .= ", create_time desc ";
		}
		$sql.=" order by ".$orderby;
        $page=$page==0?1:$page;
        $page_size = PAGE_SIZE;
        $limit = (($page-1)*$page_size).",".$page_size;
        
        $condition=$sql;
        $sql.=" limit ".$limit;
        $count = intval(count($GLOBALS['db']->getAll($condition)));
        $list = $GLOBALS['db']->getAll($sql);
        $result['list'] = $list;
        $result['count'] = $count;
        $result['condition'] = $condition;
        return $result;
	}
	
	
	/**
	 * 获取最新揭晓列表
	 */
	public static function get_newest_list($num)
	{
		$sql = "select di.id , di.icon , di.lottery_time,di.has_lottery,di.lottery_sn,di.luck_user_id,u.user_name as luck_user_name  from ".DB_PREFIX."duobao_item as di left join ".DB_PREFIX."user as u on di.luck_user_id = u.id where di.progress = 100 and di.is_effect = 1 order by di.has_lottery,di.lottery_time desc";
		$sql.=" limit ".$num;
		 
		//logger::write($sql);
		$list = $GLOBALS['db']->getAll($sql);
		return $list;
	}
	
	
	/**
	 * 获取中奖列表
	 */
	public static function get_lottery_list($num)
	{
		$sql = "select di.name,di.id , di.lottery_time,di.max_buy, u.user_name,u.avatar,di.luck_user_id from ".DB_PREFIX."duobao_item as di left join ".DB_PREFIX."user as u on di.luck_user_id=u.id where di.progress=100 and di.has_lottery=1 and u.user_name!='' ";
		$sql.="order by di.id desc limit ".$num;
			
		//logger::write($sql);
		$list = $GLOBALS['db']->getAll($sql);
		return $list;
	}
	/**
	 * 格式化中奖时间
	 * @param unknown_type $lottery_time
	 */
	public static function format_lottery_time($lottery_time){
		
		$time_span = NOW_TIME - $lottery_time;
		if($time_span>3600*24*365)
		{
			//一年以前
			//			$time_span_lang = round($time_span/(3600*24*365)).$GLOBALS['lang']['SUPPLIER_YEAR'];
			//$time_span_lang = to_date($time,"Y".$GLOBALS['lang']['SUPPLIER_YEAR']."m".$GLOBALS['lang']['SUPPLIER_MON']."d".$GLOBALS['lang']['SUPPLIER_DAY']);
			$time_span_lang = to_date($lottery_time,"Y-m-d");
		}
		elseif($time_span>3600*24*30)
		{
			//一月
			//			$time_span_lang = round($time_span/(3600*24*30)).$GLOBALS['lang']['SUPPLIER_MON'];
			//$time_span_lang = to_date($time,"Y".$GLOBALS['lang']['SUPPLIER_YEAR']."m".$GLOBALS['lang']['SUPPLIER_MON']."d".$GLOBALS['lang']['SUPPLIER_DAY']);
			$time_span_lang = to_date($lottery_time,"Y-m-d");
		}
		elseif($time_span>3600*24)
		{
			//一天
			$time_span_lang = round($time_span/(3600*24))."天前";
			//$time_span_lang = to_date($time,"Y-m-d");
		}
		elseif($time_span>3600)
		{
			//一小时
			$time_span_lang = round($time_span/(3600))."小时前";
		}
		elseif($time_span>60)
		{
			//一分
			$time_span_lang = round($time_span/(60))."分钟前";
		}
		else
		{
			//一秒
			$time_span_lang = $time_span."秒前";
		}
		return $time_span_lang;
	}
	
    public static function check_duobao_number($id,$number, $is_check_cart=true){
    	$id = intval($id);
    	
    	require_once APP_ROOT_PATH."system/model/cart.php";
    	$is_coupons = $GLOBALS['db']->getOne("select is_coupons from ".DB_PREFIX."duobao_item where id = ".$id);
    	if (intval($is_coupons) == 1) {
    	    $cart_result = load_free_list();
    	} else {
    	    $cart_result = load_cart_list();
    	}
    	
    	$duobao_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$id);
    	$order_number = $GLOBALS['db']->getOne("select sum(number) from ".DB_PREFIX."deal_order_item where duobao_item_id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id'])." and pay_status = 2 and refund_status = 0 ");
      
    	/*验证数量*/	
    	$current_buy = $duobao_info['current_buy'];
    	//2. 本团购当前会员的购物车中数量
    	$cart_count = 0;
    	foreach($cart_result['cart_list'] as $k=>$v)
    	{
    		if($v['duobao_item_id']==$id)
    		{
    			$cart_count += intval($v['number']);
    		}
    	}
    
    	$invalid_count = 0;
    	foreach($cart_result['cart_list'] as $k=>$v)
    	{
    		if($v['number']<=0)
    		{
    			$invalid_count++;
    		}
    	}
  
   
    	if($invalid_count>0)
    	{
    		$result['status'] = 0;
    		$result['data'] = "用户最小购买数不足";  //用户最小购买数不足
    		$result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_USER_MIN_BOUGHT'],1);
    		return $result;
    	}
    	
    	if ($is_check_cart) {
    	    if($duobao_info['max_buy'] == 0||($cart_count+$number>$duobao_info['max_buy']&&$duobao_info['max_buy']>=0))
    	    {
    	        $result['status'] = 0;
    	        $result['data'] = "库存不足";  //库存不足
    	        $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_MAX_BUY'],$duobao_info['max_buy']);
    	        return $result;
    	    }
    	}else{
    	    if($duobao_info['max_buy'] == 0)
    	    { 

    	        $result['status'] = 0;
    	        $result['data'] = "库存不足";  //库存不足
    	        $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_MAX_BUY'],$duobao_info['max_buy']);
    	        return $result;
    	    }
    	}
    	
            
    	if($cart_count+$number < $duobao_info['min_buy'] && $duobao_info['min_buy'] > 0)
    	{
    		$result['status'] = 0;
    		$result['data'] = "用户最小购买数不足";  //
    		$result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_USER_MIN_BOUGHT'],$duobao_info['min_buy']);
    		return $result;
    	}
    	//限购
    	if($cart_count+$number+$order_number > $duobao_info['user_max_buy'] && $duobao_info['user_max_buy'] > 0)
    	{
    		$result['status'] = 0;
    		$result['data'] = "用户最大购买数超出";  //
    		$result['info'] = $duobao_info['name']." 每个用户最多只可购买".$duobao_info['user_max_buy']."人次";
    		return $result;
    	}
    	
    	
    	/*验证数量*/
    	
    	$result['status'] = 1;
    	$result['info'] = $duobao_info['name'];
    	return $result;	
    }
    public static function check_number_choose_duobao_number($id,$number, $is_check_cart=true){
        require_once APP_ROOT_PATH."system/model/cart.php";
        $cart_result = load_number_choose_cart_list();
        $id = intval($id);
        $duobao_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$id);
        $order_number = $GLOBALS['db']->getOne("select sum(number) from ".DB_PREFIX."deal_order_item where duobao_item_id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id'])." and pay_status = 2 and refund_status = 0 ");

        /*验证数量*/
        $current_buy = $duobao_info['current_buy'];
        //2. 本团购当前会员的购物车中数量
        $cart_count = 0;
        foreach($cart_result['cart_list'] as $k=>$v)
        {
            if($v['duobao_item_id']==$id)
            {
                $cart_count += intval($v['number']);
            }
        }

        $invalid_count = 0;
        foreach($cart_result['cart_list'] as $k=>$v)
        {
            if($v['number']<=0)
            {
                $invalid_count++;
            }
        }
         if(!isset($duobao_info) || empty($duobao_info)){
                echo "<script>console.log($duobao_info);</script>";
         }
        if($invalid_count>0)
        {
            $result['status'] = 0;
            $result['data'] = "用户最小购买数不足";  //用户最小购买数不足
            $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_USER_MIN_BOUGHT'],1);
            return $result;
        }

        if ($is_check_cart) {
            if($duobao_info['max_buy'] == 0||($cart_count+$number>$duobao_info['max_buy']&&$duobao_info['max_buy']>=0))
            {
                $result['status'] = 0;
                $result['data'] = "库存不足";  //库存不足
                $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_MAX_BUY'],$duobao_info['max_buy']);
                return $result;
            }
        }else{
            if($duobao_info['max_buy'] == 0)
            {
                $result['status'] = 0;
                $result['data'] = "库存不足";  //库存不足
                $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_MAX_BUY'],$duobao_info['max_buy']);
                return $result;
            }
        }
        if($cart_count+$number < $duobao_info['min_buy'] && $duobao_info['min_buy'] > 0)
        {
            $result['status'] = 0;
            $result['data'] = "用户最小购买数不足";  //
            $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_USER_MIN_BOUGHT'],$duobao_info['min_buy']);
            return $result;
        }
        //限购
//        if($cart_count+$number+$order_number > $duobao_info['user_max_buy'] && $duobao_info['user_max_buy'] > 0)
//        {
//            $result['status'] = 0;
//            $result['data'] = "用户最大购买数超出";  //
//            $result['info'] = $duobao_info['name']." 每个用户最多只可购买".$duobao_info['user_max_buy']."人次";
//            return $result;
//        }


        /*验证数量*/

        $result['status'] = 1;
        $result['info'] = $duobao_info['name'];
        return $result;
    }
    public static function check_pk_duobao_number($id,$number, $is_check_cart=true){
        require_once APP_ROOT_PATH."system/model/cart.php";
        $cart_result = load_pk_cart_list();
        $id = intval($id);
        $duobao_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$id);
        $order_number = $GLOBALS['db']->getOne("select sum(number) from ".DB_PREFIX."deal_order_item where duobao_item_id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id'])." and pay_status = 2 and refund_status = 0 ");

        /*验证数量*/
        $current_buy = $duobao_info['current_buy'];
        //2. 本团购当前会员的购物车中数量
        $cart_count = 0;
        foreach($cart_result['cart_list'] as $k=>$v)
        {
            if($v['duobao_item_id']==$id)
            {
                $cart_count += intval($v['number']);
            }
        }

        $invalid_count = 0;
        foreach($cart_result['cart_list'] as $k=>$v)
        {
            if($v['number']<=0)
            {
                $invalid_count++;
            }
        }

        if($invalid_count>0)
        {
            $result['status'] = 0;
            $result['data'] = "用户最小购买数不足";  //用户最小购买数不足
            $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_USER_MIN_BOUGHT'],1);
            return $result;
        }

        if ($is_check_cart) {
            if($duobao_info['max_buy'] == 0||($cart_count+$number>$duobao_info['max_buy']&&$duobao_info['max_buy']>=0))
            {
                $result['status'] = 0;
                $result['data'] = "库存不足";  //库存不足
                $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_MAX_BUY'],$duobao_info['max_buy']);
                return $result;
            }
        }else{
            if($duobao_info['max_buy'] == 0)
            {
                $result['status'] = 0;
                $result['data'] = "库存不足";  //库存不足
                $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_MAX_BUY'],$duobao_info['max_buy']);
                return $result;
            }
        }
        if($cart_count+$number < $duobao_info['min_buy'] && $duobao_info['min_buy'] > 0)
        {
            $result['status'] = 0;
            $result['data'] = "用户最小购买数不足";  //
            $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_USER_MIN_BOUGHT'],$duobao_info['min_buy']);
            return $result;
        }
        //限购
        if($cart_count+$number+$order_number > $duobao_info['user_max_buy'] && $duobao_info['user_max_buy'] > 0)
        {
            $result['status'] = 0;
            $result['data'] = "用户最大购买数超出";  //
            $result['info'] = $duobao_info['name']." 每个用户最多只可购买".$duobao_info['user_max_buy']."人次";
            return $result;
        }


        /*验证数量*/

        $result['status'] = 1;
        $result['info'] = $duobao_info['name'];
        return $result;
    }
    public static function check_order_duobao_number($id,$number,$order_id){
        require_once APP_ROOT_PATH."system/model/cart.php";
        $cart_result = load_deal_order_list($order_id);
         
        $id = intval($id);
        $duobao_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$id);
        $order_number = $GLOBALS['db']->getOne("select sum(number) from ".DB_PREFIX."deal_order_item where duobao_item_id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id'])." and pay_status = 2 and refund_status = 0 ");
    
        /*验证数量*/
        $current_buy = $duobao_info['current_buy'];
        //2. 本团购当前会员的购物车中数量
        $cart_count = 0;
        foreach($cart_result['cart_list'] as $k=>$v)
        {
            if($v['duobao_item_id']==$id)
            {
                $cart_count += intval($v['number']);
            }
        }
    
        $invalid_count = 0;
        foreach($cart_result['cart_list'] as $k=>$v)
        {
            if($v['number']<=0)
            {
                $invalid_count++;
            }
        }
    
        if($invalid_count>0)
        {
            $result['status'] = 0;
            $result['data'] = "用户最小购买数不足";  //用户最小购买数不足
            $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_USER_MIN_BOUGHT'],1);
            return $result;
        }
         
        if($duobao_info['max_buy'] == 0||($cart_count+$number>$duobao_info['max_buy']&&$duobao_info['max_buy']>=0))
        {
            $result['status'] = 0;
            $result['data'] = "库存不足";  //库存不足
            $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_MAX_BOUGHT'],$duobao_info['max_buy']);
            return $result;
        }
    
        if($cart_count+$number < $duobao_info['min_buy'] && $duobao_info['min_buy'] > 0)
        {
            $result['status'] = 0;
            $result['data'] = "用户最小购买数不足";  //
            $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_USER_MIN_BOUGHT'],$duobao_info['min_buy']);
            return $result;
        }

        //限购
        if($cart_count+$number+$order_number > $duobao_info['user_max_buy'] && $duobao_info['user_max_buy'] > 0)
        {
            $result['status'] = 0;
            $result['data'] = "用户最大购买数超出";  //
            $result['info'] = $duobao_info['name']." 每个用户最多只可购买".$duobao_info['user_max_buy']."人次";
            return $result;
        }
         
         
        /*验证数量*/
         
        $result['status'] = 1;
        $result['info'] = $duobao_info['name'];
        return $result;
    }
    
    
    public static function check_duobao_number_2($order_item){

        $duobao_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$order_item['duobao_item_id']);
        $order_number = $GLOBALS['db']->getOne("select sum(number) from ".DB_PREFIX."deal_order_item where duobao_item_id = ".$order_item['duobao_item_id']." and user_id = ".intval($GLOBALS['user_info']['id'])." and pay_status = 2 and refund_status = 0 ");
        


        /*验证数量*/
        if($order_item['number']<$duobao_info['min_buy'] && $duobao_info['min_buy'] > 0){
            $result['status'] = 0;
            $result['data'] = "用户最小购买数不足";  //用户最小购买数不足
            $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_USER_MIN_BOUGHT'],1);
            return $result;
        }
        
        if($duobao_info['max_buy']==0 ||($duobao_info['current_buy']+$order_item['number']>$duobao_info['max_buy']&&$duobao_info['max_buy']>=0) || $duobao_info['progress']==100){
            $result['status'] = 0;
            $result['data'] = "库存不足";  //库存不足
            $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_MAX_BOUGHT'],$duobao_info['max_buy']);
            return $result;
        }
    
       
    
        if($duobao_info['max_buy'] == 0||($cart_count+$number>$duobao_info['max_buy']&&$duobao_info['max_buy']>=0))
        {
            $result['status'] = 0;
            $result['data'] = "库存不足";  //库存不足
            $result['info'] = $duobao_info['name']." ".sprintf($GLOBALS['lang']['DEAL_MAX_BOUGHT'],$duobao_info['max_buy']);
            return $result;
        }
    
       
        //限购
        if(($order_number+$order_item['number']) > $duobao_info['user_max_buy'] && $duobao_info['user_max_buy'] > 0)
        {
            $result['status'] = 0;
            $result['data'] = "用户最大购买数超出";  //
            $result['info'] = $duobao_info['name']." 每个用户最多只可购买".$duobao_info['user_max_buy']."人次";
            return $result;
        }
    
    
        /*验证数量*/
    
        $result['status'] = 1;
        $result['info'] = $duobao_info['name'];
        return $result;
    }
    
    
    public static function check_totalbuy_number($duobao_item_id, $number=0){
        $number         = intval($number);
        $duobao_item_id = intval($duobao_item_id);
        
        // 查询全价购买的库存
        $sql         = "select deal_id, duobao_id, progress from ".DB_PREFIX."duobao_item where is_total_buy=1 and id = ".$duobao_item_id;
        $duobao_item =  $GLOBALS['db']->getRow($sql);
        
        $sql                = "select total_buy_stock from ".DB_PREFIX."deal where id = ".$duobao_item['deal_id'];
        $total_buy_stock    = $GLOBALS['db']->getOne($sql);
        
        if (!$duobao_item) {
            return array("status"=>0,"info"=>"夺宝活动不存在");
        }
        
        if($duobao_item['progress']>=100)
        {
            return array("status"=>0,"info"=>"本期活动已经结束");
        } 
        
        if ( $total_buy_stock <= 0 || ($number && $number > $total_buy_stock ) ) {
            return array("status"=>0,"info"=>"库存不足");
        }
         
        /*验证数量*/
        $result['status'] = 1;
        $result['total_buy_stock'] = $total_buy_stock;
        return $result;
    }
    
    /**
     * 生成开奖池
     * @param unknown $duobao_item_id
     * @param unknown $num
     */
    public static function create_lottery_pool($duobao_item_id){
         
        /**
         * 1：完全新生成
         * 2：数量不够新增
         * 3:生成失败，删除生成的数据，并删除这一期
         */
        
        set_time_limit(60);
        ini_set('memory_limit','512M');
        
        //获取期号对应的计划
        $duobao_item_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$duobao_item_id);
        $duobao_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao where id =".intval($duobao_item_info['duobao_id']));
        
        
        $deal_id        = $duobao_info['deal_id'];
        $duobao_id      = $duobao_info['id'];
         
        
        $vals = '';
        $sql = "INSERT INTO ".DB_PREFIX."duobao_item_log(`deal_id`, `duobao_id`, `duobao_item_id`, `lottery_sn`) VALUES";
        for($i=0;$i<$duobao_item_info['max_buy'];$i++)
        {
            $lottery_sn = 100000001+$i;
            $vals .= " ('{$deal_id}', '{$duobao_id}', '{$duobao_item_id}', {$lottery_sn}),";
            
        }

        $sql = $sql.$vals;
        $sql = substr($sql, 0, -1);
        $GLOBALS['db']->query($sql);
        unset($sql);
        unset($vals);
       
        return intval($GLOBALS['db']->affected_rows());
    }
    
    /**
     * 生成开奖池
     * @param unknown $duobao_item_id
     * @param unknown $num
     */
    public static function create_lottery_pool_bak($duobao_item_id){
        
    	set_time_limit(60);
    	ini_set('memory_limit','512M');
    	
    	$lottery_data_dir = APP_ROOT_PATH."public/lottery_data_dir";
    	$lottery_data = $lottery_data_dir."/".$duobao_item_id.".sql";
    	
        //获取期号对应的计划
        $duobao_item_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$duobao_item_id);
        $duobao_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao where id =".intval($duobao_item_info['duobao_id']));
        
        if(!file_exists($lottery_data))
        {
	        $duobao_item_log = array();
	
	        $duobao_item_log['deal_id'] = $duobao_info['deal_id'];
	        $duobao_item_log['duobao_id'] = $duobao_info['id'];
	        $duobao_item_log['duobao_item_id'] = $duobao_item_id;
	        
	        //后续要补充的字段
	        $duobao_item_log['order_id'] = "''";
	        $duobao_item_log['order_item_id'] = "''";
	        $duobao_item_log['user_id'] = 0;
	        $duobao_item_log['duobao_ip'] =  "''";
	        $duobao_item_log['duobao_area'] = "''";
	        $duobao_item_log['create_time'] = "''"; //round(get_gmmtime(),3);
	
	        $lotterys = array();
	        for($i=0;$i<$duobao_item_info['max_buy'];$i++)
	        {
	            $duobao_item_log['lottery_sn'] = 100000001+$i;
	            $lotterys[] = $duobao_item_log;
	        }
	        
	        
	        
	        $sql = "";
	        $table_field = '';
	        
	        foreach ($lotterys as $k=>$v){
	            
	            $sql.="\r\n".$v['deal_id']."\t".$v['duobao_id']."\t".$v['duobao_item_id']."\t".$v['lottery_sn'];
	        }
	        $sql = substr($sql,2,strlen($sql));
	        if(!is_dir($lottery_data_dir)){
	            mkdir($lottery_data_dir,0777);
	        }
	        file_put_contents($lottery_data, $sql);
        }
        
        $table_field.="LOAD DATA local INFILE '".$lottery_data."' INTO TABLE ".DB_PREFIX."duobao_item_log( `deal_id`,`duobao_id`,`duobao_item_id`,`lottery_sn`)";

        $GLOBALS['db']->query($table_field);
        @unlink($lottery_data);
        unset($sql);
        unset($table_field);
        return intval($GLOBALS['db']->affected_rows()); 
    }
    
    /**
     * @param unknown_type 初始化机器人
     */
    public static function init_robot($duobao_item_id)
    {
	    	$duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$duobao_item_id);
	    	$duobao = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao where id =".intval($duobao_item['duobao_id']));
	    	
    		$robot_is_db = $duobao_item['robot_is_db'];
			if($robot_is_db)
			{
				require_once APP_ROOT_PATH."system/model/robot.php";
				if($duobao['robot_type']==0)				
				$result = robot::set_robot_schedule($duobao_item['robot_end_time'], $duobao_item_id);
				else
				$result = robot::set_robot_schedule_by_cfg(
						array(
								"robot_buy_min_time"=>$duobao['robot_buy_min_time'],
								"robot_buy_max_time"=>$duobao['robot_buy_max_time'],
								"robot_buy_min"=>$duobao['robot_buy_min'],
								"robot_buy_max"=>$duobao['robot_buy_max']
						)
						, $duobao_item_id);
			}
    		
    }

    /**
     * 随机为用户分配夺宝号
     * @param $duobao_item_log
     * @param $num
     * @return 分配成功数
     */
    public static function rand_update_lottery($duobao_item_log,$num){
        set_time_limit(60);
        //判断数据够不够
        $condition = "user_id=0 and duobao_item_id=".$duobao_item_log['duobao_item_id'];
    
        $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item_log 
            set user_id=".$duobao_item_log['user_id'].",
            order_id=".$duobao_item_log['order_id'].",
            order_item_id=".$duobao_item_log['order_item_id'].",
            create_time=".$duobao_item_log['create_time'].",
            duobao_ip = '".$duobao_item_log['duobao_ip']."',
            duobao_area = '".$duobao_item_log['duobao_area']."',
        	is_robot = '".$duobao_item_log['is_robot']."' 
            where ".$condition." order by rand() limit ".$num);
        return $GLOBALS['db']->affected_rows();
        
    }

    /**
     * 根据deal_cart中的choose_number来分配夺宝号
     * @param $duobao_item_log
     * @param $choose_number
     * @return 分配成功
     */
    public static function update_lottery_by_number($duobao_item_log,$choose_number){
        set_time_limit(60);
        $condition = "user_id=0 and duobao_item_id=".$duobao_item_log['duobao_item_id']." and lottery_sn in(".$choose_number.")";

        $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item_log
            set user_id=".$duobao_item_log['user_id'].",
            order_id=".$duobao_item_log['order_id'].",
            order_item_id=".$duobao_item_log['order_item_id'].",
            create_time=".$duobao_item_log['create_time'].",
            duobao_ip = '".$duobao_item_log['duobao_ip']."',
            duobao_area = '".$duobao_item_log['duobao_area']."',
        	is_robot = '".$duobao_item_log['is_robot']."'
            where ".$condition);
        return $GLOBALS['db']->affected_rows();
    }
    /**
     * 获取用户所有中奖号
     * @param unknown $param 
     * array(user_id=>1,duobao_item_id=>10000065)
     * @return mixed
     */
    public static function get_user_no_all($param)
	{
		static $no_all_data;
		$key = $param['user_id']."_".$param['duobao_item']['id'].$param['order_item_id'];
		if($no_all_data[$key]){
			return $no_all_data[$key];
		}else{
			
			$user_id = $param['user_id'];
			$duobao_item = $param['duobao_item'];
			$order_item_id = $param['order_item_id'];
			
			if($order_item_id){
				$order_sql="select doi.order_id,doi.id,doi.create_time,doi.lottery_sn_data from ".DB_PREFIX."deal_order_item as doi where doi.id=".$order_item_id." and doi.user_id=".$user_id." and doi.lottery_sn = 0 ";
			}else
				$order_sql="select doi.order_id,doi.id,doi.create_time,doi.lottery_sn_data from ".DB_PREFIX."deal_order_item as doi where doi.user_id=".$user_id." and doi.duobao_item_id = '".$duobao_item['id']."' and doi.lottery_sn = 0 ";
			$order=$GLOBALS['db']->getAll($order_sql);
			
			
			foreach($order as $k =>$value)
			{
				if($value){
					$sql = "select lottery_sn from ".duobao_item_log_table($duobao_item)." where user_id = ".$user_id." and duobao_item_id = ".$duobao_item['id']." and order_item_id=".$value['id'];
					$duobao_item_log = $GLOBALS['db']->getAll($sql);
					if($duobao_item_log){
						$list[$k]['order_id']=$value['order_id'];
						$list[$k]['create_time']=to_date($value['create_time']);
						$list[$k]['list']=$duobao_item_log;
					}
				}
			
			}
			$no_all_data[$key] = $list;
		}
	    
		return $no_all_data[$key];	
	}
	
	/**
	 * 
	 * 机器人晒单图片处理，更新数据表
	 */
	public  function insert_robot_share($duobao_item,$share_data=array(),$attach_list=array(),$xpoint="", $ypoint=""){
        $now_time = NOW_TIME;
        $ins_data = array();
        $ins_data['duobao_item_id'] = $duobao_item['id'];
        $ins_data['duobao_id']      = $duobao_item['duobao_id'];
        $ins_data['type'] = 1;
        $ins_data['create_time'] = $now_time;
        $ins_data['user_id'] = $duobao_item['user_id'];
        $ins_data['user_name'] = $duobao_item['luck_user_name'];
        $ins_data['title'] = $share_data['title'];
        $ins_data['content'] = $share_data['content'];
        $ins_data['is_effect'] = $share_data['is_effect'];
        $ins_data['is_top'] =$share_data['is_top'];
        $ins_data['is_recommend'] = $share_data['is_recommend'];
        $ins_data['is_index'] = $share_data['is_index'];
        $ins_data['is_check']=1;
        $ins_data['xpoint'] = $xpoint;
        $ins_data['ypoint'] = $ypoint;
        $ins_data['images_count'] = count($attach_list);
        if($duobao_item['buy_number']==1)
            $ins_data['buy_number_1'] = 1;
        //是否为机器人
        $ins_data['is_robot'] = intval($duobao_item['is_robot']);
       
        $GLOBALS['db']->autoExecute(DB_PREFIX."share",$ins_data);
        $id = $GLOBALS['db']->insert_id();
        
       if($id){
          
            //更新图片缓存
            foreach($attach_list as $attach)
            {
                    //插入图片
                    $GLOBALS['db']->query("update ".DB_PREFIX."share_image set share_id = ".$id." where id = ".$attach['id']);
            }
            
            //缓存图集
            $img_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."share_image where share_id = '".$id."' order by id");
            $cache_imgs = serialize($img_list);
            //缓存夺宝数据
            $cache_duobao_item = array();
            $cache_duobao_item['id'] = $duobao_item['id'];
            $cache_duobao_item['name'] = $duobao_item['name'];
            $cache_duobao_item['description'] = $duobao_item['description'];
            $cache_duobao_item['icon'] = $duobao_item['icon'];
            $cache_duobao_item['max_buy'] = $duobao_item['max_buy'];
            $cache_duobao_item['min_buy'] = $duobao_item['min_buy'];
            $cache_duobao_item['fair_type'] = $duobao_item['fair_type'];
            $cache_duobao_item['success_time'] = $duobao_item['success_time'];
            $cache_duobao_item['lottery_time'] = $duobao_item['lottery_time'];
            $cache_duobao_item['fair_sn'] = $duobao_item['fair_sn'];
            $cache_duobao_item['fair_sn_local'] = $duobao_item['fair_sn_local'];
            $cache_duobao_item['fair_period'] = $duobao_item['fair_period'];
            $cache_duobao_item['lottery_sn'] = $duobao_item['lottery_sn'];
            $cache_duobao_item['luck_user_id'] = $duobao_item['luck_user_id'];
            $cache_duobao_item['luck_user_name'] = $duobao_item['luck_user_name'];
            $cache_duobao_item['luck_user_buy_count'] = $duobao_item['luck_user_buy_count'];
            $cache_duobao_item['duobao_ip'] = $duobao_item['duobao_ip'];
            $cache_duobao_item['duobao_area'] = $duobao_item['duobao_area'];
            $cache_duobao_item['origin_price'] = $duobao_item['origin_price'];
            
            $GLOBALS['db']->autoExecute(DB_PREFIX."share",array("image_list"=>$cache_imgs,"cache_duobao_item_data"=>serialize($cache_duobao_item)),"UPDATE"," id=".$id);
            
            $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set share_id=".$id.",is_send_share=1 where id=".$duobao_item['id']);
            $GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set is_send_share=1 where type=0 and duobao_item_id=".$duobao_item['id']." and user_id = ".$duobao_item['luck_user_id']);
            
            
            return $id;
        }else{
            return false;
        }
    }
    
    
    
}
?>