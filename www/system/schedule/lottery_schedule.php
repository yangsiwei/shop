<?php
/**
 * 开奖计划任务
 */
require_once(APP_ROOT_PATH.'system/libs/schedule.php');
class lottery_schedule implements schedule {
	
	/**
	 * $data 格式
	 * array("duobao_item_id"=>xxx);
	 */
	public function exec($data){
		require_once APP_ROOT_PATH."system/model/duobao.php";
		
		$duobao_item_id = $data['duobao_item_id'];
		$duobao_item = new duobao($duobao_item_id);
		if(!$duobao_item->duobao_item['id'])
		{
			$result['status'] = 1;
			$result['attemp'] = 0;
			$result['info'] = "活动过期";
			return $result;
		}
		
		if($duobao_item->duobao_item['fair_type']=="yydb")
		{
			$duobao_item->draw_lottery_yydb();
		}
		else
		{
			$fair_type = $duobao_item->duobao_item['fair_type'];
			$cname = $fair_type."_fair_fetch";
			
			$sql = "select * from ".DB_PREFIX."fair_fetch where fair_type = '".$duobao_item->duobao_item['fair_type']."' and period = '".$duobao_item->duobao_item['fair_period']."'";
			$fair_period = $GLOBALS['db']->getRow($sql);
			if($fair_period['number'])
			{
				
				//当前期已开奖
				$duobao_item->draw_lottery($fair_period['period'], $fair_period['number']);
			}
			else
			{
			
					//采集最新的开奖
					require_once APP_ROOT_PATH."system/fair_fetch/".$cname.".php";
					$fetch_obj = new $cname;
					$fetch_obj->createData();
					$fetch_infos = $fetch_obj->collectData();  //开奖并获取开奖的信息
					if($fetch_infos)
						$fair_period = $fetch_infos[count($fetch_infos)-1];
						
					if($fair_period&&$fair_period['number'])
					{
						$duobao_item->draw_lottery($fair_period['period'], $fair_period['number']);
					}
					else
					{
						$duobao_item->draw_lottery($duobao_item->duobao_item['fair_period'], DEFAULT_LOTTERY);
					}
				
			}
		}
		
		
		$result['status'] = 1;
		$result['attemp'] = 0;
		$result['info'] = "开奖计划执行成功";
		return $result;
		
		
				
	}	
}
?>