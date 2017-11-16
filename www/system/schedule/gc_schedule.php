<?php
/**
 * 定期处理的杂项事务计划任务
 */
require_once(APP_ROOT_PATH.'system/libs/schedule.php');
class gc_schedule implements schedule {
	
	/**
	 * $data 格式
	 * array();
	 */
	public function exec($data){
				
		$path = APP_ROOT_PATH."public/lottery_data_dir/";
		if ( $dir = opendir( $path ) )
		{
			while ( $file = readdir( $dir ) )
			{				
				if($file!='.'&&$file!='..')
				{
					preg_match("/\d+/", $file,$matches);
					$duobao_item_id = intval($matches[0]);
					if($duobao_item_id>0)
					{
						require_once APP_ROOT_PATH."system/model/duobao.php";
						duobao::init_robot($duobao_item_id);
						duobao::create_lottery_pool($duobao_item_id);
					}
					break;
				}				
			}
			closedir($dir);
		}
		
		
		$GLOBALS['db']->query("delete from ".DB_PREFIX."schedule_list where (exec_status = 2 and exec_end_time < ".(NOW_TIME-24*3600).") or ((type='robot' or type = 'robot_cfg') and exec_status = 2 and exec_end_time < ".(NOW_TIME-3600).")");  //清空1天前的计划任务 或 清空关于机器人下单的海量记录1小时
		
		require_once APP_ROOT_PATH."system/model/deal_order.php";
		//清空过期的购物清单
		$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_cart where update_time < ".(NOW_TIME-1200));
	
		//删除2天前的开奖彩集
		$sql = "delete from ".DB_PREFIX."fair_fetch where updatetime < ".(NOW_TIME-24*3600*2);
		$GLOBALS['db']->query($sql);
		//关闭未付款的定单(20分钟)
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where type !=3 and pay_status = 0 and update_time < ".(NOW_TIME-1200)." order by update_time asc limit 1");
		if($order_info)
		{
			cancel_order($order_info['id']);
			del_order($order_info['id']);
		}

		
		// 关闭过期未付款的直购订单（20分钟）
		$totalbuy_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order where type=3 and pay_status = 0 and create_time < ".(NOW_TIME-1200)." order by create_time asc limit 10");
		if($totalbuy_list)
		{
		    foreach ($totalbuy_list as $key=>$value){
		        // 关闭订单，退库存，退金额
		        cancel_totalbuy_order($value);
		    }
		    
		}
		//90天内pk计划未完成取消退单
        $pk_duobao_item_id=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."duobao_item where is_pk=1 and create_time<".(NOW_TIME-90*24*3600));
        if($pk_duobao_item_id){
            $deal_order_sql="select do.id,do.is_delete from ".DB_PREFIX."deal_order_item as doi LEFT JOIN ".DB_PREFIX."deal_order as do on  doi.order_id =do.id where doi.duobao_item_id=".$duobao_item_id." and do.is_delete=0 order by do.id limit 0,100";
            $deal_order_ids=$GLOBALS['db']->getCol($deal_order_sql);
          if($deal_order_ids){
              foreach($deal_order_ids as $id){
                  cancel_order($id);
                  del_order($id);
              }
          }else{
              $duobao_id=$GLOBALS['db']->getOne("select duobao_id from ".DB_PREFIX."duobao_item where id=".$pk_duobao_item_id);
              $GLOBALS['db']->query("update ".DB_PREFIX."duobao set current_schedule=current_schedule-1, is_effect=1 where id=".$duobao_id);
              $GLOBALS['db']->query("delete from ".DB_PREFIX."duobao_item where id=".$pk_duobao_item_id);
          }

        }
		// 删除7天内过期或者关闭的直购订单
		$sql = "delete d,doi from ".DB_PREFIX."deal_order d, ".DB_PREFIX."deal_order_item doi where d.type=3 and d.id=doi.order_id and (d.order_status = 2 or d.order_status = 3) and d.create_time < ".(NOW_TIME-24*3600*7);
		$GLOBALS['db']->query($sql);
	
		//7天未完善配送地址的订单取消  //by hc4.18
	
		$sql = "select * from ".DB_PREFIX."deal_order where type = 0 and region_info = '' and create_date_ymd  < '".to_date(NOW_TIME-24*3600*7,"Y-m-d")."' order by create_date_ymd asc limit 1";
		//$sql = "select * from ".DB_PREFIX."deal_order where type = 0 and region_info = '' and update_time < ".(NOW_TIME-24*3600*7)." order by update_time asc  limit 1";
		$order_info = $GLOBALS['db']->getRow($sql);
		if($order_info)
		{
			over_order($order_info['id']);
			del_order($order_info['id']);
		}
	
		//7天未收货的自动收货
		$sql = "select * from ".DB_PREFIX."deal_order_item where delivery_status = 1 and is_arrival = 0 and create_date_ymd < '".to_date(NOW_TIME-24*3600*7,"Y-m-d")."' order by create_date_ymd asc limit 1";
		//$sql = "select doi.* from ".DB_PREFIX."deal_order as do left join ".DB_PREFIX."deal_order_item as doi on doi.order_id = do.id where doi.delivery_status = 1 and doi.is_arrival = 0 and do.update_time < ".(NOW_TIME-24*3600*7)." order by do.update_time asc limit 1";
		$order_item = $GLOBALS['db']->getRow($sql);
		if($order_item)
		{
			$delivery_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_notice where order_item_id = ".$order_item['id']." and is_arrival = 0 order by delivery_time desc limit 1");
			if($delivery_notice)
			{
				confirm_delivery($delivery_notice['notice_sn'],$order_item['id']);
			}
		}
		
		$del_time = es_session::get("del_time");
		if(empty($del_time)){
		    $del_time = NOW_TIME-3600;
		    es_session::set("del_time",NOW_TIME);
		}

        if(NOW_TIME-$del_time>=3600){
            //定期清理，事务表
            $from_del_time = to_date((NOW_TIME-3600),"Y-m-d-H");
            $sql = "delete from ".DB_PREFIX."form_verify where update_time='".$from_del_time."'";

            $GLOBALS['db']->query($sql);
            es_session::set("del_time",NOW_TIME);
        }
		
		
		
		
		send_schedule_plan("gc", "定时任务", array(), NOW_TIME);
		
		$result['status'] = 1;
		$result['attemp'] = 0;
		$result['info'] = "处理成功";
		return $result;
		
		
				
	}	
}



?>