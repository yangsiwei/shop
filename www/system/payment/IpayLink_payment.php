<?php

/* 爱贝跳转支付 开发流程
 1.	商户网页端Ajax(同步请求)向商户服务端发送下单请求。
 2.	商户服务端请求爱贝服务端下单（参考服务端接入指南）
 3.	爱贝服务端返回下单结果，商户服务端获取transid。
 4.	商户服务端组装数据并签名然后拼装请求连接，打开爱贝收银台。
 5.	爱贝支付收银台处理支付计费流程。
 6.	爱贝支付收银台通知商户网页端支付结果。（参考服务端接入指南 ）
 7.	商户网页端调用商户服务端查询支付状态。（参考服务端接入指南 ）
 8.	链接跳转版支付方式的流程代码可参考服务端示例代码，Java版本和PHP版本的order.java和trade.php文件。
 */

$payment_lang = array(
	'name'	       =>	'爱贝支付链接跳转版',
	'appid'        =>	'应用编号',
    'waresid'       =>  '应用商品编号',
    'appkey'	   =>	'应用私钥',
	'platpkey'	   =>	'平台公钥',
);
$config = array(

    //应用编号
	'appid'	=>	array(
		'INPUT_TYPE'	=>	'0',
	),

    //应用商品编号
    'waresid'	=>	array(
        'INPUT_TYPE'	=>	'0',
    ),

    //应用私钥
	'appkey'	=>	array(
		'INPUT_TYPE'	=>	'0'
	),

	// 平台公钥
	'platpkey'	=>	array(
		'INPUT_TYPE'	=>	'0'
	)
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'IpayLink';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付  2:仅wap支付 3:仅app支付 4:兼容wap和app 5:app与wap的线下支付  6:wap,app,pc 7：wap,pc */
    $module['online_pay'] = '7';

    /* 配送 */
    $module['config'] = $config;

    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

require_once(APP_ROOT_PATH.'system/libs/payment.php');
class IpayLink_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{
	    // 不是手机端，直接调用跳转链接
	    if ( !(isMobile() || IS_MOBILE == 1) ) {
	        return $this->get_redirect_url($payment_notice_id);
	    }


		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$money = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);


		$sql = "select name ".
						  "from ".DB_PREFIX."deal_order_item ".
						  "where order_id =". intval($payment_notice['order_id']);
		$title_name = $GLOBALS['db']->getOne($sql);
		if(empty($title_name))
		{
			$title_name = "充值".round($payment_notice['money'],2)."元";
		}

		$pay['pay_info'] = $title_name;
		$pay['pay_action'] = SITE_DOMAIN.APP_ROOT."/cgi/payment/IpayLink/redirect.php?notice_id=".$payment_notice_id."&from=".$GLOBALS['request']['from'];
		$pay['payment_name'] = "爱贝支付";
		$pay['pay_money'] = $money;
		$pay['class_name'] = "IpayLink";
		return $pay;
	}


	public function get_redirect_url($payment_notice_id)
	{
		$from = strim($_REQUEST['from']);

		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$money = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo,name from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$config = unserialize($payment_info['config']);

		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		if($order_info['type']==1)
		{
		    $title_name = "会员充值".round($order_info['total_price'],2)."元";
		}
		else
		{
		    $sql = "select name ".
		        "from ".DB_PREFIX."deal_order_item ".
		        "where order_id =". intval($payment_notice['order_id']);
		    $title_name = $GLOBALS['db']->getOne($sql);
		}

  		$money = round($payment_notice['money'],2);
 		$notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/IpayLink_notify.php';
 		$response_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/IpayLink_response.php';

        $orderReq = array(
            'appid'         => $config['appid'],                    // 平台分配的应用编号
            'waresid'       => intval($config['waresid']),          // 应用中的商品编号, 这里一定要是int型
            'cporderid'     => "{$payment_notice['notice_sn']}",    // 商户生成的订单号，需要保证系统唯一, 如果重复会下单失败
            'waresname'     => "购买充值卡",
            'price'         => $money,                              //单位：元
            'currency'      => 'RMB',
            'appuserid'     => "{$order_info['user_id']}",          // 用户id
            'cpprivateinfo' => '',                                  // 商户私有信息，支付完成后发送支付结果通知时会透传给商户
            'notifyurl'     => "{$notify_url}",
        );

        require_once APP_ROOT_PATH."system/payment/Ipay/trade.php";

        $trade = new IpayTrade();
        $transid = $trade->addOrder($orderReq);
        if ($transid) {
           // 在跳转的时候，notify已经执行了
           if($from=="wap"){
               // 调用h5版本支付
               $trade->h5OrPcUrl = $trade->h5url;
               if($order_info['type']==1)
               {
                   $trade->redirecturl = SITE_DOMAIN.wap_url("index","user_center#index");
               }
               else
               {
                   $trade->redirecturl = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$payment_notice['order_id']));
               }
           }else{
               // 调用pc端支付
               $trade->h5OrPcUrl = $trade->pcurl;
               if($order_info['type']==1){
                   $trade->redirecturl = SITE_DOMAIN.url("index","uc_log#money");
               }else {
                   $trade->redirecturl = SITE_DOMAIN.url("index","uc_duobao#index");
               }
           }

           if (isMobile() ||  $from == 'wap' || IS_MOBILE == 1) {
               return $trade->H5orPCpay();
           }else{
               return $trade->H5orPCpayUrl($money);
           }

        }
	}

	public function response($request)
	{

	}

	public function notify($request){
		require_once APP_ROOT_PATH."system/payment/Ipay/TradingResultsNotice.php";
		$tradingResults = new IpayTradingResultsNotice();
		$result = $tradingResults->TradingResultsNotice();
		// 验签成功
	    if($result) {
	        // 商户订单号： payment_notice 的notice_sn
	        $out_trade_no = $result['cporderid'];

	        // 爱贝支付交易号
	        $trade_no  = $result['transid'];

	        // 价格
	        $money     =$result['money'];
            //时间
            $date = time();

	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");

	        $user= $GLOBALS['db']->getRow("select id,pid,user_name,total_money,level_id,last_recharge_date from ".DB_PREFIX."user where id = ".$payment_notice['user_id']);
	        //记录最后充值时间
            $last_recharge_date = date("d",$user['last_recharge_dae']);
            $today = date('d',$date);
            if($today == $last_recharge_date){
                $GLOBALS['db']->query("update ".DB_PREFIX."user set day_recharge_money = day_recharge_money + ".$money." where id =".$user['id']);
            }else{
                $GLOBALS['db']->query("update ".DB_PREFIX."user set day_recharge_money = ".$money." where id =".$user['id']);
            }
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set last_recharge_date=".$date." where id =".$user['id']);
            //充值赠送
	        if($user['total_money']>0 && $money>=100){
	            $level = $user['level_id'];
                if($level>=1 && $level<=4){
                    $give_money = $money*0.06;
                }elseif($level>=5 && $level<=7){
                    $give_money = $money*0.07;
                }elseif($level>=8){
                    $give_money = $money*0.08;
                }
                $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money = give_money + ".$give_money." where id = ".$user['id']);
            }

	        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);

	        $this->fx_money($user['pid'],$money,$order_info['create_time'],$user['user_name']);

	        if(round($payment_notice['money'],2) == $money){ //支付成功
	            require_once APP_ROOT_PATH."system/model/cart.php";
	            $rs = payment_paid($payment_notice['id']);
	            if ($rs)
	            {
	                $GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);
	                order_paid($payment_notice['order_id']);
	            }
	            echo "success";
	        }else{
	            echo "failed";
	        }
	    }else {
			echo "failed";
		}
	}


    public function fx_money($pid,$money,$order_success_time,$user_name,$fx_lv=1){
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
            //添加用户日志
            $user_log['id'] = 0;
            $user_log['log_info'] = '线下'.$fx_lv.'级会员'.$user_name.'充值'.$money.'夺宝币获得推广奖'.$fx_money_now.'夺宝币';
            $user_log['log_time'] = $order_success_time;
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


	public function get_display_code(){
	    // 如果是手机端，直接返回名称，否则返回组合好的html
	    if(isMobile() || IS_MOBILE == 1){
	        return '在线支付';
	    }else{
	        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='IpayLink'");
	        if($payment_item)
	        {
	            $html = "<label class='ui-radiobox' rel='payment_rdo'><input type='radio' name='payment' value='".$payment_item['id']."' />";

	            if($payment_item['logo']!='')
	            {
	                $html .= "<img src='".APP_ROOT.$payment_item['logo']."' />在线支付 ";
	            }
	            else
	            {
	                $html .= '在线支付';
	            }
	            $html.="</label>";
	            return $html;
	        }
	    }




	}
}
?>