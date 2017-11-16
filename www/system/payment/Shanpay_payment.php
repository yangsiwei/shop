<?php

$payment_lang = array(
	'name'	=>	'云通付(PC版)',
	'shanpay_seller'	=>	'商户号',
	'shanpay_partner'	=>	'商户PID',
	'shanpay_key'	=>	'安全检验码',
);
$config = array(
	'shanpay_seller'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), 
	'shanpay_partner'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), 
	'shanpay_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	)
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Shanpay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付  2:仅wap支付 3:仅app支付 4:兼容wap和app*/
    $module['online_pay'] = '1';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

//云支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
require_once APP_ROOT_PATH."system/payment/Shanpay/shanpayfunction.php";
class Shanpay_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{
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
		
		$order_sn = $order_info['order_sn'];
		$money = round($payment_notice['money'],2);
		$money_fee=intval($money*100);
		$notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/shanpay_notify.php';
		$response_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/shanpay_response.php';
		$body = iconv_substr($title_name,0,50, 'UTF-8');	

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"partner" =>  trim($config['shanpay_partner']),
		                	"user_seller"  => $config['shanpay_seller'],
				"out_order_no"	=> $payment_notice['notice_sn'],
				"subject"	=> $title_name,
				"total_fee"	=> $money,
				"body"	=> $body,
				"notify_url"	=> $notify_url,
				"return_url"	=> $response_url
		);

        		$html_text = buildRequestFormShan($parameter,$config['shanpay_key']);
        		$code = '<div style="text-align:center">'.$html_text.'</div>';
		$code.="<br /><div style='text-align:center' class='red'>".$GLOBALS['lang']['PAY_TOTAL_PRICE'].":".format_price($money)."</div>";
        		return $code;
	
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
		
		$order_sn = $order_info['order_sn'];
		$money = round($payment_notice['money'],2);
		$money_fee=intval($money*100);
		$notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/shanpay_notify.php';
		$response_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/shanpay_response.php';
		$body = iconv_substr($title_name,0,50, 'UTF-8');	

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"partner" =>  trim($config['shanpay_partner']),
		                	"user_seller"  => $config['shanpay_seller'],
				"out_order_no"	=> $payment_notice['notice_sn'],
				"subject"	=> $title_name,
				"total_fee"	=> $money,
				"body"	=> $body,
				"notify_url"	=> $notify_url,
				"return_url"	=> $response_url
		);


        		$html_text = buildRequestFormShan2($parameter,$config['shanpay_key']);
		return $html_text;
		
	}
	
	public function response($request)
	{	
	    $from = $_POST['from'];
	    unset($request['from']);
	    unset($_POST['from']);
	    unset($_GET['from']);
	    unset($_REQUEST['from']);

	    //获取配置信息
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Shanpay'");
		$payment_info['config'] = unserialize($payment_info['config']);
		$shan_config=$payment_info['config'];
	    
		$shanNotify = md5VerifyShan($_REQUEST['out_order_no'],$_REQUEST['total_fee'],$_REQUEST['trade_status'],$_REQUEST['sign'],$shan_config['shanpay_key'],$shan_config['shanpay_partner']);
		if($shanNotify) {//验证成功
			if($_REQUEST['trade_status']=='TRADE_SUCCESS'){

					//商户订单号
					$out_trade_no = $_REQUEST['out_order_no'];
					//云通付交易号
					$trade_no = $_REQUEST['trade_no'];
					//价格
					$price=$_REQUEST['total_fee'];

	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");

	        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);

	        if(round($payment_notice['money'],2)==$price){ //支付成功
	            require_once APP_ROOT_PATH."system/model/cart.php";

	            $rs = payment_paid($payment_notice['id']);
	            if ($rs){
	                $GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);
	                order_paid($payment_notice['order_id']);
	            }

	            if ($from == 'wap'){
	                
	                if($order_info['type']==1)
	                {
	                    $url = wap_url("index","user_center#index");
	                }
	                else
	                {
	                    $url = wap_url("index","payment#done",array("id"=>$payment_notice['order_id']));
	                }
	                
	                app_redirect($url);
	            }else{
	               app_redirect(url("index","payment#done",array("id"=>$payment_notice['order_id']))); //支付成功
	            }
	        }else{
	           showErr($GLOBALS['payment_lang']["PAY_FAILED"]);
	        }
    	        
	    }else {
			if ($from == 'wap'){
				$url = wap_url("index");
				app_redirect($url);
			}else{
				showErr($GLOBALS['payment_lang']["PAY_FAILED"]);
			}
		}				
	}else{
			if ($from == 'wap'){
				$url = wap_url("index");
				app_redirect($url);
			}else{
				showErr($GLOBALS['payment_lang']["PAY_FAILED"]);
			}		
	}
	
	}
	
	public function notify($request){

		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Shanpay'");
		$payment_info['config'] = unserialize($payment_info['config']);
		$shan_config=$payment_info['config'];
	    
		$shanNotify = md5VerifyShan($_REQUEST['out_order_no'],$_REQUEST['total_fee'],$_REQUEST['trade_status'],$_REQUEST['sign'],$shan_config['shanpay_key'],$shan_config['shanpay_partner']);
		if($shanNotify) {//验证成功
			if($_REQUEST['trade_status']=='TRADE_SUCCESS'){

					//商户订单号
					$out_trade_no = $_REQUEST['out_order_no'];
					//云通付交易号
					$trade_no = $_REQUEST['trade_no'];
					//价格
					$price=$_REQUEST['total_fee'];

	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");

	        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);

	        if(round($payment_notice['money'],2)==$price){ //支付成功
	            require_once APP_ROOT_PATH."system/model/cart.php";

	            $rs = payment_paid($payment_notice['id']);
	            if ($rs){
	                $GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);
	                order_paid($payment_notice['order_id']);
	            }

	            echo 'success';
	        }else{
	           echo 'fail';
	        }
    	        
	    }else {
			echo 'fail';
		}				
	}else{
			echo 'fail';		
	}				
	}
	
	public function get_display_code()
	{
		$payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Shanpay'");
		if($payment_item)
		{
			$html = "<label class='ui-radiobox' rel='payment_rdo'><input type='radio' name='payment' value='".$payment_item['id']."' />";

			if($payment_item['logo']!='')
			{
				$html .= "<img src='".APP_ROOT.$payment_item['logo']."' />";
			}
			else
			{
				$html .= $payment_item['name'];
			}
			$html.="</label>";
			return $html;
		}
		else
		{
			return '';
		}
	}
}
?>