<?php
 
$payment_lang = array(
	'name'	       =>	'威富通支付宝支付',
	'mch_id'       =>	'商户号', //商户申请 梓微兴 支付后，由 支付平台 分配的商户收款账号
    'key'          =>   'API密钥', // 签名需要的key
);
$config = array(
    
    // 商户号
	'mch_id'	=>	array(
		'INPUT_TYPE'	=>	'0',
	),
    
    // API密钥
    'key'	=>	array(
        'INPUT_TYPE'	=>	'0',
    ),
	
   
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'WftALY';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付  2:仅wap支付 3:仅app支付 4:兼容wap和app 6:wap,app,pc */
    $module['online_pay'] = '1';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

require_once(APP_ROOT_PATH.'system/libs/payment.php');
class WftALY_payment implements payment {

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
		$pay['pay_action'] = SITE_DOMAIN.APP_ROOT."/cgi/payment/WftALY/redirect.php?notice_id=".$payment_notice_id."&from=".$GLOBALS['request']['from'];
		$pay['payment_name'] = "支付宝支付";
		$pay['pay_money'] = $money;
		$pay['class_name'] = "WftALY";
		$pay['is_ajax_submit'] = 1;
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
		
		$money = $payment_notice['money'] * 100;
		$money = round($money);
  		 
 		$notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/WftALY_notify.php';
 		$response_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/WftALY_response.php';
 		
 		// 支付成功按钮链接地址
 		$response_url = SITE_DOMAIN.APP_ROOT.'/index.php?ctl=payment&act=pay&id='.$payment_notice['id'].'&check=1';

 		
 		if (isMobile() ||  $from == 'wap' || IS_MOBILE == 1) {
 		    exit;
 		}else{

 		    $return_url = SITE_DOMAIN.APP_ROOT.url("index","uc_duobao#index");
 		    
 		    $trade_type = 'pay.alipay.native';
 		}
 		 
        $_POST['service']               = $trade_type;                              // 是 接口类型 
        $_POST['charset']               = 'UTF-8';                                  // 否 字符集 可选值UTF-8,默认为UTF-8
        $_POST['sign_type']             = "";                                       // 否 签名方式 签名类型,取值:MD5 默认:MD5
        $_POST['groupno']               = '';                                       // 否 大商户编号 如果不为空，则用大商户密钥进行签名
        $_POST['out_trade_no']          =  "{$payment_notice['notice_sn']}";        // 是 商户订单号 商户系统内部的订单号，32个字符内、可包含字母，确保在商户系统唯一
        $_POST['device_info']           = '';                                       // 否 设备号 终端设备号
        $_POST['body']                  = $title_name;                              // 是 商品描述
        $_POST['attach']                = '';                                       // 否 商户附加信息，可做扩展参数，255字符内
        $_POST['total_fee']             = $money;                                   // 是 总金额，以分为单位，不允许包含任何字、符号
        $_POST['mch_create_ip']         = gethostbyname($_SERVER["SERVER_NAME"]);   // 是 终端IP 订单生成的机器IP
        $_POST['notify_url']            = $notify_url;                              // 是 通知地址 接收威富通通知的URL,需给绝对路径,255字符内格式如:http://wap.tenpay.com/tenpay.asp,确保威富通能通过互联网访问该地址
        $_POST['callback_url']          = $return_url;                              // 否 前台地址 交易完成后跳转的URL,需给绝对路径,255字符内格式如:http://wap.tenpay.com/callback.asp,注:该地址只作为前段页面的一个跳转，须试用notify_url通知作为支付最终结果
        $_POST['time_start']            = '';                                       // 否 订单生成时间 该时间取自商户服务器
        $_POST['time_expire']           = '';                                       // 否 订单超时时间 该时间取自商户服务器
        $_POST['op_user_id']            = '';                                       // 否 操作员 操作员账号,默认为商户号
        
        $_POST['op_shop_id']            = '';                                       // 否 门店编号
        $_POST['op_device_id']          = '';                                       // 否 设备编号
        $_POST['goods_tag']             = '';                                       // 否 商品标记
        $_POST['sign']                  = '';                                       // 是 签名 MD5签名结果
        

        require_once APP_ROOT_PATH."system/payment/WftPay/request.php";
        $req = new Request();
        $result = $req->index('submitOrderInfo'); 

        //var_dump($result);exit;
        // result_code交易成功标识，return_code通信成功标识
        if ($result['status'] === 0 && $result['result_code'] === 0) {
            if (isMobile() ||  $from == 'wap' || IS_MOBILE == 1) {
                if (isWeixin()) {
                    $html .= "<script language=\"javascript\">";
                    $html .= "location.href=\"{$result['prepay_url']}\"";
                    $html .= "</script>";
                    return $html;
                }else{
                    $package_json = json_decode($result['package_json'], true);
                    $html .= "<script language=\"javascript\">";
                    $html .= "location.href=\"{$package_json['package']}\"";
                    $html .= "</script>";
                    $html = $package_json['package'];
                    $a['status'] = 1;
                    $a['html']   = $html;
                    
                    return json_encode($a); 
                }
                
            }else{
                $html = '<img src="'.$result['code_img_url'].'">';
                $html .= '<span style="width:500px; color:red; font-size:18px; text-align: center;margin: auto;display: block;">请使用“支付宝”扫描支付</span><br /><a style="width: 85px;text-align: center;margin: auto;display: block;" class="btn btn-main" rel="orange" id="pay_done" href="'.$response_url.'">已完成付款</a>';
                return $html;
            }
        }else{
            if ($result['status'] === '0') {
                echo $result['msg'];
                exit;
            }else{
                echo 'Response Code:'.$result['return_code'].' Error Info:'.$result['return_msg'];
                exit;
            }
            
        }
        
	}
	
	public function response($request)
	{	
	     
	}
	
	public function notify($request){
	    require_once APP_ROOT_PATH."system/payment/WftPay/request.php";
	    $req = new Request();
	    $result = $req->index('callback');
	    
		// 验签成功
	    if($result) {
	        // 商户订单号： payment_notice 的notice_sn
	        $out_trade_no = $result['out_trade_no'];
	        
	        // 梓微兴平台支付订单号
	        $trade_no  = $result['transaction_id'];
	        
	        // 价格
	        $money     = $result['total_fee'];
	        $money     = $money / 100;
	        
	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");

	        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
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
	            echo "fail";
	        }
	    }else {
			echo "fail";
		}				
	}
	
	public function get_display_code(){
	    // 如果是手机端，直接返回名称，否则返回组合好的html
	    if(isMobile() || IS_MOBILE == 1){
	        return '支付宝支付';
	    }else{
	        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='WftALY'");
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