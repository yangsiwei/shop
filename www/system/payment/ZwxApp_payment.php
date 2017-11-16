<?php
 
$payment_lang = array(
	'name'	       =>	'梓微兴APP微信支付',
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
    $module['class_name']    = 'ZwxApp';

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
class ZwxApp_payment implements payment {
    public function __construct(){
        $_GET['pay_class_name'] = 'ZwxApp';
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
		$pay['pay_action'] = SITE_DOMAIN.APP_ROOT."/cgi/payment/ZwxApp/redirect.php?notice_id=".$payment_notice_id."&from=".$GLOBALS['request']['from'];
		$pay['payment_name'] = "微信支付";
		$pay['pay_money'] = $money;
		$pay['class_name'] = "ZwxApp";
		
		 
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
		$money = $payment_notice['money'] * 100;
		$money = round($money);
		$notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/ZwxApp_notify.php';
		 
		if($order_info['type']==1)  // 用户充值
		{
		    $return_url = SITE_DOMAIN.wap_url("index","user_center#index");
		}
		else
		{
		    $return_url = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$payment_notice['order_id'], "is_done"=>1));
		}
		 
		$trade_type = 'trade.weixin.apppay';
		
		//$body = mb_substr($title_name, 0,  10, 'utf-8');
		
		//$_POST['mch_id']            = $config['mch_id'];                          // 必填  商户号
		$_POST['body']              = "$order_sn";                                  // 是 商品或支付简要描述, 说明是32位长度，但是不准所以使用订单号
		$_POST['detail']            = "$title_name";                                // 否 商品名称明细列表    
		$_POST['attach']            = '';                                           // 否 附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
		$_POST['out_trade_no']      = "{$payment_notice['notice_sn']}";             // 是 商户系统内部的订单号,32个字符内、可包含字母,其他说明见商户订单号
		$_POST['total_fee']         = $money;                                       // 是 订单总金额，单位为分，只能为整数，详见支付金额
		$_POST['fee_type']          = 'CNY';                                        // 否 默讣人民币：CNY，其他值列表详见货币类型
		$_POST['spbill_create_ip']  = gethostbyname($_SERVER["SERVER_NAME"]);       // 是 调用微信支付API的机器IP
		$_POST['goods_tag']         = '';                                           // 否 商品标记，代金券戒立减优惠功能的参数 通知
		$_POST['notify_url']        = $notify_url;                                  // 是 接收支付结果异步通知回调地址，PC网站必填，POS机器扫码支付填写空字符串即可
		$_POST['return_url']        = $return_url;                                  // 否 接收页面回调的地址，该地址不带支付结果参数，最终支付结果需要调用查询接口迚行获取
		$_POST['trade_type']        = $trade_type;                                  // 是 取值：例子trade.weixin.jspay，详细说明见参数规定
		$_POST['limit_pay']         = '';                                           // 否 no_credit--指定丌能使用信用卡支付
		$_POST['op_term_tp']        = '';                                           // 否 取值：POS，WEB，PC
		$_POST['op_term_no']        = '';                                           // 否 终端设备号
		$_POST['op_shop_no']        = '';                                           // 否 门店编号 操作
		$_POST['op_user_id']        = '';                                           // 否 操作员帐号
		$_POST['sign']              = '';                                           // 是 签名，详见签名生成算法
		
		 
		 
		require_once APP_ROOT_PATH."system/payment/ZwxPay/request.php";
		$req = new Request();
		$result = $req->index('submitOrderInfo');
		
	 
		
		// result_code交易成功标识，return_code通信成功标识
		if ($result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS') {
		     
		    // app
		    $package_json = json_decode($result['package_json'], true);
		   
		    
		   /*  "sign":"BEAB08A20F7C495437728DD613E06554",
		    "appId":"wx440a6e416a63981a",
		    "timeStamp":"1466750116",
		    "package":"Sign=WXPay",
		    "partnerId":"12653538",
		    "nonceStr":"3h1165fp4rp9ijczcnsx6oqthogd3eii",
		    "prepayId":"wx20160624143528f6db6b1d8b0034417693" */
		    
		    $agent_arr = agentArr();
		    if($agent_arr['sdk_type']=="android")
		    {
		        $pay['sdk_code'] = array(
		            "pay_sdk_type"=>"wxpay",
		            "config"=>
    		            array(
    		                "appid"=>$package_json['appId'],
    		                "partnerid"=>$package_json['partnerId'],
    		                "prepayid"=>$package_json['prepayId'],
    		                "noncestr"=>$package_json['nonceStr'],
    		                "timestamp"=>$package_json['timeStamp'],
    		                "packagevalue"=>'prepay_id='.$package_json['prepayId'],
    		                "sign"=>$package_json['sign']
    		            )
		        );
		        	
		    }
		    if($agent_arr['sdk_type']=="ios")
		    {
		        $pay['sdk_code'] = array(
		            "pay_sdk_type"=>"wxpay",
		            "config"=>
    		            array(
    		                "appid"=>$package_json['appId'],
    		                "partnerid"=>$package_json['partnerId'],
    		                "prepayid"=>$package_json['prepayId'],
    		                "noncestr"=>$package_json['nonceStr'],
    		                "timestamp"=>$package_json['timeStamp'],
    		                "package"=>$package_json['package'], 
    		                "sign" => $package_json['sign']
    		            )
		        );
		    }
		  
		    return $pay;
		
		}else{
		    if ($result['status'] === 0) {
		        echo $result['msg'];
		        exit;
		    }else{
		        echo 'Response Code:'.$result['return_code'].' Error Info:'.$result['return_msg'];
		        exit;
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
	    require_once APP_ROOT_PATH."system/payment/ZwxPay/request.php";
	    $req = new Request();
	    $result = $req->index('callback');
	    
		// 验签成功
	    if($result) {
	        // 商户订单号： payment_notice 的notice_sn
	        $out_trade_no = $result['out_trade_no'];
	        
	        // 梓微兴平台支付订单号
	        $trade_no  = $result['transaction_id'];
	        
	        // 价格
	        $money     =$result['total_fee'];
	        
	        $money     = $money  / 100;
	        
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
	        return '微信支付';
	    }else{
	        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='ZwxApp'");
	        if($payment_item)
	        {
	            $html = "<label class='ui-radiobox' rel='payment_rdo'><input type='radio' name='payment' value='".$payment_item['id']."' />";
	             
	            if($payment_item['logo']!='')
	            {
	                $html .= "<img src='".APP_ROOT.$payment_item['logo']."' />微信支付 ";
	            }
	            else
	            {
	                $html .= '微信支付';
	            }
	            $html.="</label>";
	            return $html;
	        }
	    }
	     
	}
}
?>