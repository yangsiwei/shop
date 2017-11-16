<?php

require_once(APP_ROOT_PATH.'system/libs/schedule.php');
class robot_cfg_schedule implements schedule {
	
	/**
	 * 带设置项的机器人
	 * $data 格式
	 * array("robot_cfg"=>配置项（数组）,"duobao_item_id"=>夺宝的活动ID);
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
			$user_data = $GLOBALS['db']->getRow("select id,user_name,id from ".DB_PREFIX."user where is_robot = 1 and is_effect = 1 order by update_time asc limit 1");
			$robot = new robot($user_data['user_name']);
			$robot->set_user($user_data['id']);
			
			
			$data['robot_cfg'] = json_decode($data['robot_cfg'],1);
			$duobao_number = rand($data['robot_cfg']['robot_buy_min'],$data['robot_cfg']['robot_buy_max'])*$duobao_item['min_buy'];			
						
			
			$duobao_number = $robot->make_order($data['duobao_item_id'],$duobao_number);
			if($duobao_number==0)
			{
				$robot_schedule_time = 0;
			}
			else
			{
				$robot_schedule_time = NOW_TIME + rand($data['robot_cfg']['robot_buy_min_time'],$data['robot_cfg']['robot_buy_max_time']);
			}
	
			
			if($robot_schedule_time>0)
			{
				$robot_schedule_data['duobao_item_id'] = $data['duobao_item_id'];
				$robot_schedule_data['robot_cfg'] = json_encode($data['robot_cfg']);
					
				send_schedule_plan("robot_cfg", "机器人设定下单任务", $robot_schedule_data, $robot_schedule_time,$data['duobao_item_id']);
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