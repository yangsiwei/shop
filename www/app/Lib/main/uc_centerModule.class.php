<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


require APP_ROOT_PATH."system/model/uc_center_service.php";
class uc_centerModule extends MainBaseModule
{
	public function index()
	{

		global_run();
		init_app_page();
		assign_uc_nav_list();//左侧导航菜单

		$user_info = $GLOBALS['user_info'];

		//==业务逻辑部分==
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}
		

		$id = intval( $GLOBALS['user_info']['id'] );
		$sql = "select *,sum(number) as number from ".DB_PREFIX."deal_order_item where (type = 2 or type=4) and pay_status = 2 and user_id = ".$id." group by duobao_item_id order by create_time desc limit 5";
		
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $k=>$v)
		{
			$list[$k]['duobao_item'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$v['duobao_item_id']);
			$list[$k]['duobao_item']['less'] = $list[$k]['duobao_item']['max_buy'] - $list[$k]['duobao_item']['current_buy'];
			if($list[$k]['duobao_item']['progress']==100 && $list[$k]['duobao_item']['success_time'] > 0){
				//最新一期
				$new_item_data= $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where duobao_id=".$list[$k]['duobao_item']['duobao_id']." and progress <100 order by create_time desc limit 0,1");
				if($new_item_data){
					$list[$k]['duobao_item']['new_duobao_item_id']=$new_item_data['id'];
				}
				
			}
		
		}

        //刷新下为会员发放系统的群发消息
        send_system_msg($id);

		//红包个数
		$result = get_voucher_list(0,$id,1);
		
		$history_ids = get_view_history("duobao");
		if($history_ids)
		$history_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where is_effect = 1 and id in (".implode(",", $history_ids).") limit 6");
		//计算成为经销商的条件
		$total_money = $GLOBALS['user_info']['total_money'];
		$is_fx = 100-$total_money;
		if($is_fx<=0){
			$pass = 1;
		}else{
			$no_pass = $is_fx;
		}
		$dealers = $GLOBALS['user_info']['dealers'];
		if($dealers == 2){
			$dealers = 2;
			//会员等级
			$fx_level = $GLOBALS['user_info']['fx_level'];
			switch ($fx_level) {
				case 3:
					$level = '钻石会员';
					break;
                case 4:
                    $level = '钻石会员';
                    break;
				case 2:
					$level = '黄金会员';
					break;
				default:
					$level = '白银会员';
					break;
			}

		}else{
			$dealers = null;
		}
		if($GLOBALS['user_info']['fx_level'] == 4){
			$admin_money = $GLOBALS['user_info']['admin_money'];
		}
        $GLOBALS['user_info']['voucher_count'] = $result['count'];
		$GLOBALS['tmpl']->assign("dealers",$dealers);
		$GLOBALS['tmpl']->assign("level",$level);
		$GLOBALS['tmpl']->assign("pass",$pass);
		$GLOBALS['tmpl']->assign("no_pass",$no_pass);
		$GLOBALS['tmpl']->assign("list",$list);
		$GLOBALS['tmpl']->assign("history_list",$history_list);
		$GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
		$GLOBALS['tmpl']->display("uc/uc_center.html");

	}

	
}
?>