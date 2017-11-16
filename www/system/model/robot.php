<?php 
/**
 * 机器人类
 * @author hc
 *
 */
class robot
{
	public $id; //机器人用户ID
	public $user_name;  //机器人用户名
	public $ip; //机器人IP
	public $area; //ip所在地
	
	public function __construct($user_name,$ip='',$id=0,$user_logo='',$robot_id)
	{
		$user_name = btrim($user_name);
        if ($id>0){ //更新操作
            $GLOBALS['db']->query("update ".DB_PREFIX."user set user_name='".$user_name."',login_ip = '".$ip."',user_logo='".$user_logo."',is_robot =1 where id = ".$id);
            return true;
        }
		$robot = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where user_name = '".$user_name."' and is_robot = 1");
		if($robot)
		{
			$this->id = $robot['id'];
			$this->user_name = $robot['user_name'];
			$this->ip = $robot['login_ip'];
			
		}
		else
		{
			require_once APP_ROOT_PATH."system/model/user.php";
			
			$result = auto_create(array("user_name"=>$user_name), 0);			
			$this->id = $result['user_data']['id'];
			$this->user_name = $result['user_data']['user_name'];
                        if(empty($ip)){
                            $ip = self::rand_ip();
                        }
			$this->ip = $ip;			
			$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".$ip."',user_logo='".$user_logo."',is_robot =1 where id = ".$this->id);
		}
		require_once APP_ROOT_PATH."system/extend/ip.php";
		$ip = new iplocate();
		$area = $ip->getaddress($this->ip);
		$this->area = $area['area1'];
	}
	
	public function set_user($id)
	{
	    $robot = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = '".$id."' and is_robot = 1");
	    if($robot)
	    {
	        $this->id = $robot['id'];
	        $this->user_name = $robot['user_name'];
	        $this->ip = $robot['login_ip'];	     
	        
	        require_once APP_ROOT_PATH."system/extend/ip.php";
	        $ip = new iplocate();
	        $area = $ip->getaddress($this->ip);
	        $this->area = $area['area1'];
	    }
	}
	
	
	/**
	 * 
	 * @param unknown_type $duobao_item_id  夺宝的期数
	 * @param unknown_type $duobao_number   购买数量 
	 */
	public function make_order($duobao_item_id,$duobao_number)
	{
		require_once APP_ROOT_PATH."system/model/duobao.php";
		$duobao = new duobao($duobao_item_id);
		
		$duobao_item = $duobao->duobao_item;
// 		if($duobao_number>=0)
// 		{
// 			$duobao_number = ceil($duobao_number/$duobao_item['min_buy'])*$duobao_item['min_buy'];
// 			$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set current_buy = current_buy +".$duobao_number.",progress = floor(current_buy/max_buy*100) where id = ".$duobao_item_id." and current_buy +".$duobao_number."<=max_buy");
// 			$affected_rows = $GLOBALS['db']->affected_rows();
			
// 			if($affected_rows==0)
// 			{
// 				$duobao_number = intval($GLOBALS['db']->getOne("select max_buy - current_buy from ".DB_PREFIX."duobao_item where id = ".$duobao_item_id));
// 				$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set current_buy = max_buy,progress = 100 where id = ".$duobao_item_id);
// 				$affected_rows = $GLOBALS['db']->affected_rows();
// 			}
// 		}
// 		else 
// 		{
// 			$affected_rows = 0;
// 		}

		
		if($duobao_number>0)
		{
			$order_info['type'] = 2;
			$order_info['user_id'] = $this->id;
			$order_info['create_time'] = NOW_TIME;
			$order_info['update_time'] = NOW_TIME;
			$order_info['pay_status'] = 2;
			$order_info['total_price'] = $duobao_number;
			$order_info['pay_amount'] = $duobao_number;
			$order_info['duobao_ip'] = $this->ip;			
			$order_info['duobao_area'] = $this->area;
			$order_info['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
			$order_info['create_date_ym'] = to_date(NOW_TIME,"Y-m");
			$order_info['create_date_y'] = to_date(NOW_TIME,"Y");
			$order_info['create_date_m'] = to_date(NOW_TIME,"m");
			$order_info['create_date_d'] = to_date(NOW_TIME,"d");
			
			do
			{
				$order_info['order_sn'] = to_date(NOW_TIME,"Ymdhis").rand(10,99);
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order_info,'INSERT','','SILENT');
				$order_id = intval($GLOBALS['db']->insert_id());
			}while($order_id==0);
			
			
			$goods_item['deal_id'] = $duobao_item['deal_id'];
			$goods_item['duobao_id'] = $duobao_item['duobao_id'];
			$goods_item['duobao_item_id'] = $duobao_item['id'];
			$goods_item['number'] = $duobao_number;
			$goods_item['unit_price'] = 1;
			$goods_item['total_price'] = $duobao_number;
			$goods_item['name'] = $duobao_item['name'];
			$goods_item['order_id'] = $order_id;
			$goods_item['deal_icon'] = $duobao_item['icon'];
			$goods_item['user_id'] = $this->id;
			$goods_item['order_sn'] = $order_info['order_sn'];
			$goods_item['duobao_ip'] = $this->ip;
			$goods_item['duobao_area'] = $this->area;
			$goods_item['pay_status'] = 2;
			$goods_item['type'] = 2;
			
			$goods_item['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
			$goods_item['create_date_ym'] = to_date(NOW_TIME,"Y-m");
			$goods_item['create_date_y'] = to_date(NOW_TIME,"Y");
			$goods_item['create_date_m'] = to_date(NOW_TIME,"m");
			$goods_item['create_date_d'] = to_date(NOW_TIME,"d");
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_item",$goods_item,'INSERT','','SILENT');
			$order_item_id = $GLOBALS['db']->insert_id();
			
			$res = $duobao->make_lottery_sn($this->id, $order_item_id);		
			$duobao_number = intval($res['total']);
			
			if($duobao_number>0)
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set number = ".$duobao_number.",total_price=".$duobao_number." where id = ".$order_item_id);
				$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set current_buy = current_buy +".$duobao_number.",progress = floor(current_buy/max_buy*100) where id = ".$duobao_item_id);
			}
			else
			{
				if($duobao_number==0)
				{
					$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set current_buy = max_buy,progress = 100 where id = ".$duobao_item_id);
				}
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set refund_status = 2 where id = ".$order_item_id);
			}
			
			require_once APP_ROOT_PATH."system/model/deal_order.php";
			auto_over_status($order_id);
		}
		
		$duobao->check_progress();
		return $duobao_number;
	}
	
	/**
	 * 为夺宝指定机器人策略
	 * 机器人由数据库中查找50个机器人进行指定下单任务, 机器人不能少于5个，否则不可开启机器人模式
	 * @param unknown_type $robot_end_time (分) 不能小于5分钟
	 * @param unknown_type $duobao_item_id
	 */
	public static function set_robot_schedule($robot_end_time,$duobao_item_id)
	{
		$GLOBALS['db']->query("delete from ".DB_PREFIX."schedule_list where type ='robot' and dest = '".$duobao_item_id."' and exec_status = 0"); //删除原未执行的计划任务
		$duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$duobao_item_id);
		$robot_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where is_robot = 1 and is_effect = 1");
		
		if($robot_count<5)
		{
			return array("status"=>0,"info"=>"机器人数量太少，最少需要五个机器人，请去用户管理处增加。");
		}
		
		if($robot_end_time<5)
		{
			return array("status"=>0,"info"=>"截止时间太短，最少需要5分钟");
		}
		
		$robot_schedule_time = NOW_TIME;
		
		//array("end_time"=>运行结束时间,"begin_time"=>"任务开始时间","duobao_item_id"=>夺宝的活动ID);
		$robot_schedule_data['duobao_item_id'] = $duobao_item_id;
		$robot_schedule_data['begin_time'] = NOW_TIME;
		$robot_schedule_data['end_time'] = NOW_TIME+$robot_end_time*60;
		
		send_schedule_plan("robot", "机器人下单任务", $robot_schedule_data, $robot_schedule_time,$duobao_item['id']);
		
		return array("status"=>1,"info"=>"机器人设置成功");
		
	}
	
	/**
	 * 为夺宝指定机器人策略(新)
	 * 机器人由数据库中查找50个机器人进行指定下单任务, 机器人不能少于5个，否则不可开启机器人模式
	 * @param unknown_type $robot_cfg 配置: buy_min_time buy_max_time buy_min buy_max
	 * @param unknown_type $duobao_item_id
	 */
	public static function set_robot_schedule_by_cfg($robot_cfg,$duobao_item_id)
	{
		$GLOBALS['db']->query("delete from ".DB_PREFIX."schedule_list where type ='robot' and dest = '".$duobao_item_id."' and exec_status = 0"); //删除原未执行的计划任务
		$duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$duobao_item_id);
		$robot_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where is_robot = 1 and is_effect = 1");
	
		if($robot_count<5)
		{
			return array("status"=>0,"info"=>"机器人数量太少，最少需要五个机器人，请去用户管理处增加。");
		}
	
	
		$robot_schedule_time = NOW_TIME;
	
		//array("robot_cfg"=>配置,"duobao_item_id"=>夺宝的活动ID);
		$robot_schedule_data['duobao_item_id'] = $duobao_item_id;
		$robot_schedule_data['robot_cfg'] = json_encode($robot_cfg);
	
		send_schedule_plan("robot_cfg", "机器人设定下单任务", $robot_schedule_data, $robot_schedule_time,$duobao_item['id']);
	
		return array("status"=>1,"info"=>"机器人设置成功");
	
	}
	
	
	/**
	 * 获取国内随机IP地址
	 * 注：适用于32位操作系统
	 */
	private static function rand_ip()
	{
	        $ip_long = array(
	            array('607649792', '608174079'), //36.56.0.0-36.63.255.255
	            array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
	            array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
	            array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
	            array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
	            array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
	            array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
	            array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
	            array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
	            array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
	        );
	        $rand_key = mt_rand(0, 9);
	        $ip       = long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
	        return $ip;
	}
}
?>