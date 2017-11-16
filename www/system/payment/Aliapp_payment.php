<?php

$payment_lang = array(
	'name'	=>	'新支付宝(SDK版本)',
	'aliapp_partner'	=>	'合作者身份ID',
	'aliapp_seller_id'	=>	'支付宝帐号',
	'aliapp_rsa_public'	=>	'支付宝(RSA)公钥',
	'aliapp_rsa_private_key'	=>	'商家RSA私钥',
);
$config = array(
	'aliapp_partner'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //合作者身份ID
	'aliapp_seller_id'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //支付宝帐号: 
	//支付宝(RSA)公钥
	'aliapp_rsa_public'	=>	array(
		'INPUT_TYPE'	=>	'0'
	),
	//商家RSA私钥
	'aliapp_rsa_private_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	)		
);

/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Aliapp';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '3';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

// 支付宝手机支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Aliapp_payment implements payment {

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

		
		//$subject = msubstr($title_name,0,40);
		$subject = $order_sn;
		//$data_return_url = get_domain().APP_ROOT.'/../payment.php?act=return&class_name=Malipay';
		//$notify_url = get_domain().APP_ROOT.'/../shop.php?ctl=payment&act=response&class_name=Malipay';
		//$notify_url = SITE_DOMAIN.APP_ROOT.'/index.php?ctl=payment&act=notify&class_name=Aliapp';
		//$data_return_url = "http://tuan.7dit.com/payment.php?act=return&class_name=Malipay";
		//$data_return_url = "http://tuan.7dit.com";
		$notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/aliapp_notify.php';
		
		$pay = array();
		$pay['pay_info'] = $title_name;
		$pay['payment_name'] = "支付宝";
		$pay['pay_money'] = $money;
		$pay['class_name'] = "Malipay";
		
		$pay['config'] = array();	
				
		$pay['config']['subject'] = $subject;
		$pay['config']['body'] = $title_name;
		$pay['config']['total_fee'] = $money;
		$pay['config']['total_fee_format'] = format_price($money);
		$pay['config']['out_trade_no'] = $payment_notice['notice_sn'];
		$pay['config']['notify_url'] = $notify_url;
		
		$pay['config']['payment_type'] = 1;//支付类型。默认值为：1（商品购买）。
		$pay['config']['service'] = 'mobile.securitypay.pay';
		$pay['config']['_input_charset'] = 'utf-8';
		
		$pay['config']['partner'] = $payment_info['config']['aliapp_partner'];//合作商户ID
		$pay['config']['seller_id'] = $payment_info['config']['aliapp_seller_id'];//账户ID
					

		$order_spec = '';
		$order_spec .= 'partner="'.$pay['config']['partner'].'"';//合作商户ID
		$order_spec .= '&seller_id="'.$pay['config']['seller_id'].'"';//账户ID
		$order_spec .= '&out_trade_no="'.$pay['config']['out_trade_no'].'"';
		$order_spec .= '&subject="'.$pay['config']['subject'].'"';		
		$order_spec .= '&body="'.$pay['config']['body'].'"';
		$order_spec .= '&total_fee="'.$pay['config']['total_fee'].'"';
		$order_spec .= '&notify_url="'.$pay['config']['notify_url'].'"';
		$order_spec .= '&service="'.$pay['config']['service'].'"';
		$order_spec .= '&payment_type="'.$pay['config']['payment_type'].'"';
		$order_spec .= '&_input_charset="'.$pay['config']['_input_charset'].'"';				
		$order_spec .= '&it_b_pay="30m"';
		

		$sign = $this->sign($order_spec,$payment_info['config']['aliapp_rsa_private_key']);
		$sign = urlencode($sign);

		
		
		/*
		$pubkey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC6IQ/HH06GbTIhKNN/YSQXxllnP7cNERMuN16GgZDfuf9NrY/Bw2ZINkq1RMNlbP66Vu5y0gwYPC/7PbO5l6pbnl3N4rw5VY3U6rtIC0f8ADDLrIZwShYUitaFq+Ao7rhk/GbpfSD7vgnugQz74fVewi17S3Apujq4U4LAxFmVowIDAQAB';
		$pubkey = $this->getPublicKeyFromX509($pubkey);
		
		$res = openssl_pkey_get_public($pubkey);		
		$sign = base64_decode($sign);
		$verify = openssl_verify($order_spec, $sign, $res);
		if ($verify == 1)
		{
			$pay['openssl_verify'] = 'ok';
		}else{
			$pay['openssl_verify'] = 'error';
		}		
		*/
//			
//		print_r($payment_info['config']);
//		print_r($pay);exit;

		$pay['sdk_code'] = array("pay_sdk_type"=>"alipay","config"=>array("order_spec"=>addslashes($order_spec),"sign"=>$sign,"sign_type"=>"RSA"));
		
		return $pay;
	}
	
	public function notify($request)
	{	
		//echo APP_ROOT_PATH."/alipaylog/ealipay_".date("Y-m-d H:i:s").".txt";exit;
		//file_put_contents(APP_ROOT_PATH."/system/payment/Aliapp/alipaylog/ealipay_".date("Y-m-dHis").".txt",print_r($_REQUEST,true));
		
		require_once(APP_ROOT_PATH.'system/payment/Aliapp/alipay_notify.class.php');
		
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Aliapp'");
		$payment['config'] = unserialize($payment['config']);
		
		$pubkey = $payment['config']['aliapp_rsa_public'];
		
		
		//计算得出通知验证结果
		$alipay_config = array();
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('RSA');		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
				
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		
		$alipay_config['aliapp_rsa_public']    = $payment['config']['aliapp_rsa_public'];
		
		
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		
		if($verify_result) {//验证成功
			
			$trade_status = $_POST['trade_status'];
			$outer_notice_sn = $_POST['trade_no'];
			$payment_notice_sn = $_POST['out_trade_no'];
			
			if ($trade_status == 'TRADE_SUCCESS' || $trade_status == 'TRADE_FINISHED' || $trade_status == 'WAIT_SELLER_SEND_GOODS'){
				
				$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");

		
			 	//file_put_contents(APP_ROOT_PATH."/alipaylog/payment_notice_sn_3.txt",$payment_notice_sn);
			
				$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
				require_once APP_ROOT_PATH."system/model/cart.php";
				$rs = payment_paid($payment_notice['id']);
				if ($rs)
				{
					//file_put_contents(APP_ROOT_PATH."/alipaylog/1.txt","");
					$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$outer_notice_sn."' where id = ".$payment_notice['id']);
					order_paid($payment_notice['order_id']);
					echo "success";
				}else{
					//file_put_contents(APP_ROOT_PATH."/alipaylog/2.txt","");
					echo "success";
				}
				
			}else{
				//file_put_contents(APP_ROOT_PATH."/alipaylog/3.txt","");
				echo "fail";
			}
			
		}else{
			echo "fail";
		}	
		
	}
	
	function getPublicKeyFromX509($certificate)  
	{  
	    $publicKeyString = "-----BEGIN PUBLIC KEY-----\n" .  
	          wordwrap($certificate, 64, "\n", true) .  
	          "\n-----END PUBLIC KEY-----";     
	    return $publicKeyString;  
	}	
	
	function getPrivateKeyFromX509($certificate)
	{
		$privateKeyString = "-----BEGIN RSA PRIVATE KEY-----\n" .
				wordwrap($certificate, 64, "\n", true) .
				"\n-----END RSA PRIVATE KEY-----";
		return $privateKeyString;
	}
	
	/**RSA签名
	 * $data待签名数据
	 * 签名用商户私钥，必须是没有经过pkcs8转换的私钥
	 * 最后的签名，需要用base64编码
	 * return Sign签名
	 */
	function sign($data,$rsa_private_key) {
	    //读取私钥文件
	    //$priKey = file_get_contents(APP_ROOT_PATH.'/mapi/key/rsa_private_key.pem');
	    
	    $priKey = $this->getPrivateKeyFromX509($rsa_private_key);
	    
		//print_r($priKey); exit;
	    //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
	    $res = openssl_get_privatekey($priKey);
	
	    //调用openssl内置签名方法，生成签名$sign
	    openssl_sign($data, $sign, $res);
	
	    //释放资源
	    openssl_free_key($res);
	    
	    //base64编码
	    $sign = base64_encode($sign);
	    return $sign;
	}	
		//响应通知
	function response($request)
	{}
	
	//获取接口的显示
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
	                $html .= "<img src='".APP_ROOT.$payment_item['logo']."' />支付宝支付 ";
	            }
	            else
	            {
	                $html .= '支付宝支付';
	            }
	            $html.="</label>";
	            return $html;
	        }
	    }
	
	}
}

?>