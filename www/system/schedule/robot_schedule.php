<?php

require_once(APP_ROOT_PATH.'system/libs/schedule.php');
class robot_schedule implements schedule {
	
	/**
	 * $data 格式
	 * array("end_time"=>计划结束时间,"begin_time"=>"计划开始时间","last_time"=>"上次运行计划的时间","duobao_item_id"=>夺宝的活动ID);
	 */
	public function exec($data){
		
		//机器人计划任务
		require_once APP_ROOT_PATH."system/model/robot.php";
		$duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$data['duobao_item_id']." and success_time = 0");	
		if(empty($duobao_item))
		{
			$result['status'] = 1;
			$result['attemp'] = 0;
			$result['info'] = "夺宝已过期";
		}
		else
		{
			$user_data = $GLOBALS['db']->getRow("select id,user_name,id from ".DB_PREFIX."user where is_robot = 1 and is_effect = 1 order by RAND() asc limit 1");
			$robot = new robot($user_data['user_name']);
			$robot->set_user($user_data['id']);
			
			
			if(NOW_TIME>=$data['end_time'])
			{
				$duobao_numbers[] = ceil(rand(100,2000)/10)*10;
				$duobao_numbers[] = rand(100,2000);
				$duobao_number = $duobao_numbers[rand(0,1)];
				
				$robot_schedule_time = NOW_TIME + 10;
			}
			else
			{
				$less = $duobao_item['max_buy'] - $duobao_item['current_buy']; //剩余未购数量
				$less_time = $data['end_time'] - NOW_TIME;  //剩余时间
				$rate_sec = $less/$less_time;  //平均每秒要求的下单量(浮点)
				if($rate_sec<1)
				{
					//无需每秒都下单
					$duobao_number = rand(1,5);  //每次下单随机1-5
					$robot_schedule_time = NOW_TIME + $duobao_number/$rate_sec;
			
				}
				else
				{
					$number_sec = ceil($rate_sec);
			
					//每秒一单不足以支持进度按时走完
					if($number_sec<5) //每秒下单5个之冗余
					{
						$duobao_number = rand(1,5);
					}
					elseif($number_sec<10)
					{
						$duobao_number = rand(5,10);
					}
					elseif($number_sec<100) //每秒下单100之内按整数或随机下单
					{
						$duobao_numbers[] = ceil(rand(10,100)/10)*10;
						$duobao_numbers[] = rand(10,100);
						$duobao_number = $duobao_numbers[rand(0,1)];
					}
					else //每秒下单100以上按整数或随机下单(下单上限不能超过2000)
					{
						$max = 2000;
						if($less<2000)
						{
							$max = 500;
						}
						elseif($less<1000)
						{
							$max = 200;
						}
							
						$duobao_numbers[] = ceil(rand(100,$max)/10)*10;
						$duobao_numbers[] = rand(100,$max);
						$duobao_number = $duobao_numbers[rand(0,1)];
					}
			
					if($duobao_number>100)
						$robot_schedule_time = NOW_TIME + 10;
					else
						$robot_schedule_time = NOW_TIME + $duobao_number;
				}
					
			}
			
			$duobao_number*=$duobao_item['min_buy'];
			$duobao_number = $robot->make_order($data['duobao_item_id'],$duobao_number);

			if($duobao_number==0)
			{
				$robot_schedule_time = 0;
			}
			
			
			if($robot_schedule_time>0)
			{
				$robot_schedule_data['duobao_item_id'] = $data['duobao_item_id'];
				$robot_schedule_data['begin_time'] = $data['begin_time'];
				$robot_schedule_data['end_time'] = $data['end_time'];
					
				send_schedule_plan("robot", "机器人下单任务", $robot_schedule_data, $robot_schedule_time,$data['duobao_item_id']);
				$GLOBALS['db']->query("update ".DB_PREFIX."user set update_time = '".NOW_TIME."' where id = ".$robot->id); //更新时间
					
				$result['status'] = 1;
				$result['attemp'] = 0;
				if($duobao_number>0)
					$result['info'] = "下单".$duobao_number."成功";
				else
					$result['info'] = "下单失败";
			}
			else
			{
				$result['status'] = 1;
				$result['attemp'] = 0;
				$result['info'] = "下单".$duobao_number."成功,停止任务";	
			}
		}
		
		return $result;
	}	
}
?>