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
		$param['receive_time'] = strtotime($notifyData['receive_time']);
		$param['complete_time'] = strtotime($notifyData['complete_time']);
		$param['optional'] = $notifyData['optional'];
		$param['ret_info'] = $notifyData['ret_info'];
		$param['charge_id'] = $notifyData['charge_id'];
		$param['order_no'] = $notifyData['order_no'];
		$param['ret_code'] = $notifyData['ret_code'];

        if($param['ret_code'] != '0000'){
            die;
        }

		$user_yzm = $GLOBALS['db']->getRow("select souhu_id,kaixin_id from ".DB_PREFIX."user where id = ".$param['optional']);

		//最后充值时间
		$last_recharge_date = date('d',$GLOBALS['user_info']['last_recharge_date']);
		//单日累计充值金额
		$day_recharge_money = $GLOBALS['user_info']['day_recharge_money']+$param['money'];
		//查询充值限制数据
        $rest = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."rest where id = 1");
		$order = $GLOBALS['db']->getRow("select id,pay_status from ".DB_PREFIX."deal_order where user_id = ".$param['optional']." and type = 1 and pay_status  = 0 and total_price = ".$param['money']);
        $order_id = $order['id'];
        if($order['pay_status'] = 2){
            echo 'success';
            die;
        }



		if($order_id){
			$GLOBALS ['db']->query ( "update ".DB_PREFIX."deal_order set pay_status = 2 , pay_amount = ".$param['money'].",create_date_ymd = '" . to_date ( NOW_TIME, "Y-m-d" ) . "',create_date_ym = '" . to_date ( NOW_TIME, "Y-m" ) . "',create_date_y = '" . to_date ( NOW_TIME, "Y" ) . "',create_date_m = '" . to_date ( NOW_TIME, "m" ) . "',create_date_d = '" . to_date ( NOW_TIME, "d" ) . "' where id =" . $order_id . " and pay_status <> 2" );

			//修改付款订单列表
            $GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set pay_time = ".$param['complete_time'].",is_paid = 1 where order_id = ".$order_id);

            //添加用户余额 添加充值单号验证码
            $GLOBALS['db']->query("update ".DB_PREFIX."user set money = money+".$param['money']." where id = ".$param['optional']);

            //充值成功，开始给邀请人添加推广奖
            $pid = $GLOBALS['db']->getOne("select pid from ".DB_PREFIX."user where id = ".$param['optional']);
            $user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".$param['optional']);
            if($param['money'] >= 100){
                $this->fx_money($pid,$param['money'],$param['receive_time'],$user_name);
            }
            $date = time();

            $order['order_sn'] = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$order_id);
            //添加订单日志
            $order_log['id'] = 0;
            $order_log['log_info'] = $order['order_sn'].'付款完成';
            $order_log['log_time'] = NOW_TIME;
            $order_log['order_id'] = $order_id;
            $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_log", $order_log);

            $payment_notice['notice_sn'] = $GLOBALS['db']->getOne("select notice_sn from ".DB_PREFIX."payment_notice where order_id = ".$order_id);
            //添加用户日志
            $user_log['id'] = 0;
            $user_log['log_info'] = '充值：订单号'.$order['order_sn'].'付款单号：'.$payment_notice['notice_sn'];
            $user_log['log_time'] = $param['complete_time'];
            $user_log['log_user_id'] = $param['optional'];
            $user_log['money'] = $param['money'];
            $user_log['user_id'] = $param['optional'];
            $user_log['payment'] = '支付宝';
            $GLOBALS['db']->autoExecute(DB_PREFIX."user_log", $user_log);

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
                $level = $GLOBALS['user_info']['level_id'];
                if($level>=1 && $level<=4){
                    $give_money = $param['money']*0.06;
                }elseif($level>=5 && $level<=7){
                    $give_money = $param['money']*0.08;
                }elseif($level>=8){
                    $give_money = $param['money']*0.1;
                }
                $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money = give_money + ".$give_money." where id = ".$param['optional']);
            }

            //添加累计充值金额
            $GLOBALS['db']->query("update ".DB_PREFIX."user set total_money = total_money+".$param['money']." where id =".$param['optional']);
            echo "success";

		}
	}

	public function fx_money($pid,$money,$user_name,$fx_lv=1){
		if($fx_lv>4){
			$fx_lv = 4;
		}
        $user = $GLOBALS['db']->getRow("select fx_level,pid from ".DB_PREFIX."user where id = ".$pid);
        if($user['fx_level'] >= $fx_lv){
        	$fx_salary = $GLOBALS['db']->getRow("select fx_salary from ".DB_PREFIX."fx_salary  where fx_level =".$fx_lv)['fx_salary'];
	        $fx_money_befor = $GLOBALS['db']->getOne("select fx_money from ".DB_PREFIX."user where id =".$pid);
	        $fx_money_now = $fx_salary*$money;
	        $fx_money = $fx_money_befor+$fx_money_now;
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_money = ".$fx_money." where id = ".$pid);
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_total_balance = fx_total_balance+".$fx_money_now." where id = ".$pid);
	        //添加用户日志
            $user_log['id'] = 0;
            $user_log['log_info'] = '线下'.$fx_lv.'级会员'.$user_name.'充值'.$money.'夺宝币获得推广奖'.$fx_money_now.'夺宝币';
            $user_log['log_time'] = time();
            $user_log['log_user_id'] = $pid;
            $user_log['money'] = $fx_money_now;
            $user_log['user_id'] = $pid;
            $GLOBALS['db']->autoExecute(DB_PREFIX."user_log", $user_log);

	        $fx_lv++;
	        if($user['pid']){
                $this->fx_money($user['pid'],$money,$user_name,$fx_lv);
            }
        }

    }




}