<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



class update_dataModule extends MainBaseModule
{
	public function update_order_item()
	{
		set_time_limit(0);
		global_run();
		init_app_page();
		/*
            #SELECT * from fanwe_duobao_item where has_lottery=1 and luck_user_id = 240
            #select * from fanwe_duobao_item_log_history where lottery_sn = 100000025 and duobao_item_id = 100000175
            #select * from fanwe_deal_order_item where id = 1073
		 */
		echo "正在更新.......</br>";
		//查询所有中奖记录
		$duobao_item_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where has_lottery=1");
		$count = 0;
		foreach ($duobao_item_list as $k=>$v){
		    $duobao_item_log = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($v)." where lottery_sn = ".$v['lottery_sn']." and duobao_item_id = ".$v['id']);
		    $order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id=".$duobao_item_log['order_item_id']." and type=2");
	
		    if($order_item){
		        $sql = "update ".DB_PREFIX."deal_order_item set 
		            buy_create_time='".substr($order_item['create_time'], 0,strpos($order_item['create_time'],"."))."' ,buy_number=".$order_item['number']." 
		                where type=0 and duobao_item_id=".$v['id'];
		        $GLOBALS['db']->query($sql);
		        $count++;
		    }
            
		}
		
		echo "</br>OK,共执行：".$count;
	}
	
}
?>