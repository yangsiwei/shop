<?php
 
$payment_lang = array(
	'name'	       =>	'爱贝支付SDK版',
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
    $module['class_name']    = 'IpayApp';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付  2:仅wap支付 3:仅app支付 4:兼容wap和app 6:wap,app,pc */
    $module['online_pay'] = '3';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

require_once(APP_ROOT_PATH.'system/libs/payment.php');
class IpayApp_payment implements payment {
    
    public function __construct(){
        $_POST['is_app'] = 1;
    }
    
	public function get_payment_code($payment_notice_id)
	{
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
		$pay['pay_action'] = SITE_DOMAIN.APP_ROOT."/cgi/payment/IpayApp/redirect.php?notice_id=".$payment_notice_id."&from=".$GLOBALS['request']['from'];
		$pay['payment_name'] = "爱贝支付";
		$pay['pay_money'] = $money;
		$pay['class_name'] = "IpayApp";
		
		$config = $payment_info['config'];
		
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
		
		if($order_info['type']==1)  // 用户充值
		{
		    $return_url = SITE_DOMAIN.wap_url("index","user_center#index");
		}
		else
		{
		    $return_url = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$payment_notice['order_id'], "is_done"=>1));
		}
		
		 
		
		$money = round($payment_notice['money'],2);
		$notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/IpayApp_notify.php';
		
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
		
		$agent_arr = agentArr();
		$pay['sdk_code'] = array("pay_sdk_type"=>"iapppay","config"=>array("transid"=>$transid,"appid"=>$config['appid']));
		
		if ($transid) {
		    // 在跳转的时候，notify已经执行了
		    
		    if($order_info['type']==1)
	        {
	            $trade->redirecturl = SITE_DOMAIN.wap_url("index","user_center#index");
	        }
	        else
	        {
	            $trade->redirecturl = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$payment_notice['order_id']));
	        }
	        
		}
		 
		return $pay;
	}
		
	
	
	public function get_redirect_url($payment_notice_id)
	{

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
	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");

	        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
	        
	        if(round($payment_notice['money'],2) == $money){ //支付成功
	            require_once APP_ROOT_PATH."system/model/cart.php";
	            $rs = payment_paid($payment_notice['id']);
	            if ($rs)
	            {
	                $GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);
	                order_paid($payment_notice['order_id']);
	                echo "success";
	            }
	        }else{
	            echo "failed";
	        }
	    }else {
	        echo "failed";
	    }
	}
	
	public function get_display_code(){
	    // 如果是手机端，直接返回名称，否则返回组合好的html
	    if(isMobile() || IS_MOBILE == 1){
	        return '爱贝支付';
	    }else{
	        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='IpayApp'");
	        if($payment_item)
	        {
	            $html = "<label class='ui-radiobox' rel='payment_rdo'><input type='radio' name='payment' value='".$payment_item['id']."' />";
	             
	            if($payment_item['logo']!='')
	            {
	                $html .= "<img src='".APP_ROOT.$payment_item['logo']."' />爱贝支付 ";
	            }
	            else
	            {
	                $html .= '爱贝支付';
	            }
	            $html.="</label>";
	            return $html;
	        }
	    }
	     
	}
}
?>