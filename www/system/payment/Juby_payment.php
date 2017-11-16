<?php
 
$payment_lang = array(
	'name'	            =>	'聚宝云支付',
	'partnerid'         =>	'商户号',  //商户申请 梓微兴 支付后，由 支付平台 分配的商户收款账号
    'psw'               =>  'API密钥', // 签名需要的key
);
$config = array(
    
    // 商户号
	'partnerid'	=>	array(
		'INPUT_TYPE'	=>	'0',
	),
    
    // API密钥
    'psw'	=>	array(
        'INPUT_TYPE'	=>	'0',
    ),
	
   
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Juby';

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
class Juby_payment implements payment {
    public function __construct(){
        $_GET['pay_class_name'] = 'Juby';
    }

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
		$pay['pay_action'] = SITE_DOMAIN.APP_ROOT."/cgi/payment/Juby/redirect.php?notice_id=".$payment_notice_id."&from=".$GLOBALS['request']['from'];
		$pay['payment_name'] = "聚宝云支付";
		$pay['pay_money'] = $money;
		$pay['class_name'] = "Juby";
		$pay['is_ajax_submit'] = 0;  // 设置为0不跳转
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
		$money = $payment_notice['money'];
  		$money = round($money);
 		$notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/Juby_notify.php';
 		 
 		
 		// 支付成功按钮链接地址
//  		$response_url = SITE_DOMAIN.APP_ROOT.'/index.php?ctl=payment&act=pay&id='.$payment_notice['id'].'&check=1';

 		
 		$_POST['payid']                     = "{$payment_notice['notice_sn']}";                 // 必填 商户系统内部的订单号,32个字符内、可包含字母,其他说明见商户订单号
        $_POST['partnerid']                 = $config['partnerid'];                             // 是 商户号
        $_POST['amount']                    = "{$money}";                                       // 是 订单总金额，单位为分，只能为整数，详见支付金额
        $_POST['payerName']                 = "{$payment_notice['user_id']}";                   // 是 用户ID(保证用户唯一，否则可能导致支付出现问题) 
        $_POST['goodsName']                 = "$title_name";                                    // 是 商品名称
        $_POST['remark']                    = '';                                               // 是 备注字段
        $_POST['callBackURL']               = $notify_url;                                      // 否 后台回调 URL(如果后台没有设置，则使用该 URL，否则使用后台配置的 URL)
        $_POST['config']                    = $config;
        
       
        if (isMobile() ||  $from == 'wap' || IS_MOBILE == 1){
            $return_url = SITE_DOMAIN.wap_url("index","uc_duobao_record#index");
            $_POST['returnURL']             = $return_url;                                      // 否 页面回调 URL(如果后台没有设置，则使用该 URL，否则使用后台配置的 URL)
            $_POST['payMethod']             = '';                                               // 是 支付方式
            require_once APP_ROOT_PATH."system/payment/Jubypay/wapPost.php";
        }else{
            $return_url = SITE_DOMAIN.APP_ROOT.url("index","uc_duobao#index");
            $_POST['returnURL']             = $return_url;                                      // 否 页面回调 URL(如果后台没有设置，则使用该 URL，否则使用后台配置的 URL)
            $_POST['payMethod']             = 'WANGYIN';                                        // 是 支付方式
            require_once APP_ROOT_PATH."system/payment/Jubypay/pcPost.php";
        }
        exit;
	}
	
	public function response($request)
	{	
	     
	}
	
	public function notify($request){
	    include APP_ROOT_PATH."system/payment/Jubypay/jubaopay/jubaopay.php";
	    
	    $message=$_POST["message"];
	    $signature=$_POST["signature"];
	    
	    $payment_info = $GLOBALS['db']->getOne("select config from ".DB_PREFIX."payment where class_name = 'Juby'");
        $config = unserialize($payment_info);
	    $jubaopay=new jubaopay($config);
	    
	    $jubaopay->decrypt($message);
	    // 校验签名，然后进行业务处理
	    $result=$jubaopay->verify($signature);
	    if($result==1) {
	        
	        
	        // 得到解密的结果后，进行业务处理
	        // echo "payid=".$jubaopay->getEncrypt("payid")."<br />";
	        // echo "mobile=".$jubaopay->getEncrypt("mobile")."<br />";
	        // echo "amount=".$jubaopay->getEncrypt("amount")."<br />";
	        // echo "remark=".$jubaopay->getEncrypt("remark")."<br />";
	        // echo "orderNo=".$jubaopay->getEncrypt("orderNo")."<br />";
	        // echo "state=".$jubaopay->getEncrypt("state")."<br />";
	        // echo "partnerid=".$jubaopay->getEncrypt("partnerid")."<br />";
	        // echo "modifyTime=".$jubaopay->getEncrypt("modifyTime")."<br />";
	        
	        
	        
	        // 商户订单号： payment_notice 的notice_sn
	        $payid    = $jubaopay->getEncrypt("payid");
	        
	        // 聚宝云平台支付订单号
	        $orderNo  = $jubaopay->getEncrypt("orderNo"); 
	        
	        // 价格
	        $money    = $jubaopay->getEncrypt("amount");
	        
	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payid."'");

	        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
	        if(round($payment_notice['money'],2) == $money){ //支付成功
	            require_once APP_ROOT_PATH."system/model/cart.php";
	            $rs = payment_paid($payment_notice['id']);
	            if ($rs)
	            {
	                $GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$orderNo."' where id = ".$payment_notice['id']);
	                order_paid($payment_notice['order_id']);
	            }
	            echo "success"; // 像服务返回 "success"
	        }else{
	            echo "verify failed";
	        }
	    }else{
	        echo "verify failed";
	    }
	    				
	}
	
	public function get_display_code(){
	    // 如果是手机端，直接返回名称，否则返回组合好的html
	    if(isMobile() || IS_MOBILE == 1){
	        return '聚宝云支付';
	    }else{
	        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Juby'");
	        if($payment_item)
	        {
	            $html = "<label class='ui-radiobox' rel='payment_rdo'><input type='radio' name='payment' value='".$payment_item['id']."' />";
	             
	            if($payment_item['logo']!='')
	            {
	                $html .= "<img src='".APP_ROOT.$payment_item['logo']."' />聚宝云支付  ";
	            }
	            else
	            {
	                $html .= '聚宝云支付';
	            }
	            $html.="</label>";
	            return $html;
	        }
	    }
	     
	}
}
?>