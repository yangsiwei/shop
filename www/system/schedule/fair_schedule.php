<?php
/**
 * 开奖计划采集任务
 */
require_once(APP_ROOT_PATH.'system/libs/schedule.php');
class fair_schedule implements schedule {
	
	/**
	 * $data 格式
	 * array("fair_type"=>wy);
	 */
	public function exec($data){
		require_once APP_ROOT_PATH."system/model/duobao.php";
		
		$fair_type = $data['fair_type'];
		$cname = $fair_type."_fair_fetch";
		
		
		require_once APP_ROOT_PATH."system/fair_fetch/".$cname.".php";
		$fetch_obj = new $cname;
		$fetch_obj->createData();
		$fetch_obj->collectData();
		

		
		$next_schedule_time = $fetch_obj->getNextCollectTime();	

		if($next_schedule_time<NOW_TIME)
		{
			$next_schedule_time = NOW_TIME + $fetch_obj->waitsec;
		}
		else
		{
			$next_schedule_time = $next_schedule_time + $fetch_obj->waitsec;
		}
		send_schedule_plan("fair", $fetch_obj->name."开奖采集", array("fair_type"=>$data['fair_type']), $next_schedule_time);
		
		
		$result['status'] = 1;
		$result['attemp'] = 0;
		$result['info'] = "开奖采集执行成功";
		return $result;
				
	}	
}
?>