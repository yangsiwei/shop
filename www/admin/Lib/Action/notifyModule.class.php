<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class notifyModule extends MainBaseModule
{
	public function index()
	{
		global_run();

		$postData = file_get_contents("php://input"); //接收POST数据
		$notifyData = json_decode($postData,true);
		$param['money'] = $notifyData['amount']/100;
		$param['receive_time'] = $notifyData['receive_time'];
		$param['complete_time'] = $notifyData['complete_time'];
		$param['optional'] = $notifyData['optional'];
		$param['ret_info'] = $notifyData['ret_info'];
		$param['order_no'] = $notifyData['order_no'];

		$param['money'] = 100;
		$param['receive_time'] = time();
		$param['complete_time'] = time();
		$param['optional'] = 261;
		$param['ret_info'] = '';
		$param['order_no'] = '';


		if($param['optional'] != $GLOBALS['user_info']['id']){
			die;
		}
		//最后充值时间
		$last_recharge_date = date('d',$GLOBALS['user_info']['last_recharge_date']);
		//单日累计充值金额
		$day_recharge_money = $GLOBALS['user_info']['day_recharge_money']+$param['money'];
		//查询充值限制数据
        $rest = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."rest where id = 1");
		$order_id = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."deal_order where user_id = ".$param['optional']." and type = 1 and pay_status  = 0 and total_price = ".$param['money']);
		$order_id = 104;
		if($order_id){
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set pay_status = 2,create_date_ymd = '" . to_date ( NOW_TIME, "Y-m-d" ) . "',create_date_ym = '" . to_date ( NOW_TIME, "Y-m" ) . "',create_date_y = '" . to_date ( NOW_TIME, "Y" ) . "',create_date_m = '" . to_date ( NOW_TIME, "m" ) . "',create_date_d = '" . to_date ( NOW_TIME, "d" ) . "' where id =" . $order_id . " and pay_status <> 2" );

				//添加用户余额
				$GLOBALS['db']->query("update ".DB_PREFIX."user set money = money+".$param['money']." where id = ".$GLOBALS['user_info']['id']);
				//添加累计充值金额
                $aaa = $GLOBALS['db']->query("update ".DB_PREFIX."user set total_money = total_money+".$param['money']." where id =".$param['optional']);
                //充值成功，开始给邀请人添加推广奖
                $res = $this->fx_money($param['optional'],$param['money']);
                $date = time();

                $today = date('d',$date);
                if($today == $last_recharge_date){
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set day_recharge_money = ".$day_recharge_money." where id =".$param['optional']);
                }else{
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set day_recharge_money = ".$param['money']." where id =".$param['optional']);
                }
                //记录最后充值时间
                $GLOBALS['db']->query("update ".DB_PREFIX."user set last_recharge_date=".$date." where id =".$param['optional']);
                $total_money = $GLOBALS['user_info']['total_money'];
                //首冲功能
                if($total_money<=0 && $param['money']<100){
                    //第一次充值金额不满100夺宝币，

                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay=1 where id = ".$param['optional']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay_date=".$date." where id =".$param['optional']);



                }elseif($total_money<=0 && 1000>=$param['money'] && $param['money']>=100){
                    //判断是否是首冲，充值金额在100到1000之间，赠送20夺宝币
                    echo 333;
                    $give_money = $rest['hundred'];
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money=".$give_money." where id = ".$param['optional']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay=1 where id = ".$param['optional']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay_date=".$date." where id = ".$param['optional']);
                }elseif($total_money<=0 && $param['money']>=1000){
                    //判断是否是首冲，且充值金额大于1000，赠送200夺宝币
                    $give_money = $rest['thousand'];
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money=".$give_money." where id = ".$param['optional']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay=1 where id = ".$param['optional']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay_date=".$date." where id =".$param['optional']);
                }elseif($total_money>0 && $param['money']>=100){
                    //不是首冲，满100以上送金额
                    $give_money = $param['money']*$rest['usual_day'];
                    var_dump($rest['usual_day']);
                    var_dump($give_money,$GLOBALS['user_info']['give_money']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money = give_money + ".$give_money." where id = ".$param['optional']);
                }
                send_wx_msg ( "OPENTM201490080", $param['optional'], array (), array ("order_id" => $order_id) );

		}
	}

	public function fx_money($pid,$money,$fx_lv=1){
		if($fx_lv>4){
			$fx_lv = 4;
		}
        $user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$pid);
        if($user['fx_level'] >= $fx_lv){
        	$fx_salary = $GLOBALS['db']->getRow("select fx_salary from ".DB_PREFIX."fx_salary  where fx_level =".$fx_lv)['fx_salary'];
	        $fx_money_befor = $GLOBALS['db']->getOne("select fx_money from ".DB_PREFIX."user where id =".$pid);
	        $fx_money_now = $fx_salary*$money;
	        $fx_money = $fx_money_befor+$fx_money_now;
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_money=".$fx_money." where id = ".$pid);
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_total_balance=fx_total_balance+".$fx_money_now." where id = ".$pid);
	        $fx_lv++;
	        $this->fx_money($user['pid'],$money,$fx_lv);
        }
    }
}