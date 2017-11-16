<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'新银联支付(wap)',
	'upacp_mer_id'	=>	'商户号',
	'upacp_cert_pwd'	=>	'签名证书密码',

);
$config = array(
	'upacp_mer_id'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //商户代码: 
	'upacp_cert_pwd'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //校验码
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Upacpwap';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付  2:仅wap支付 3:仅app支付 4:兼容wap和app 5:app与wap的线下支付  6:wap,app,pc 7：wap,pc */
    $module['online_pay'] = '7';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = 'https://open.unionpay.com/ajweb/product';
    return $module;
}

// 支付宝支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');

require_once(APP_ROOT_PATH.'system/payment/upacp/common.php');
require_once(APP_ROOT_PATH.'system/payment/upacp/httpClient.php');
require_once(APP_ROOT_PATH.'system/payment/upacp/PublicEncrypte.php');
require_once(APP_ROOT_PATH.'system/payment/upacp/secureUtil.php');
require_once(APP_ROOT_PATH.'system/payment/upacp/log.class.php');
require_once(APP_ROOT_PATH.'system/payment/upacp/PinBlock.php');

// cvn2加密 1：加密 0:不加密
define('SDK_CVN2_ENC','0');
// 有效期加密 1:加密 0:不加密
define('SDK_DATE_ENC','0');
// 卡号加密 1：加密 0:不加密
define('SDK_PAN_ENC','0');
//日志级别
define('SDK_LOG_LEVEL','INFO');


// ######(以下配置为PM环境：入网测试环境用，生产环境配置见文档说明)#######
// 签名证书路径
define('SDK_SIGN_CERT_PATH',APP_ROOT_PATH.'system/payment/upacp/certs/sign_cert_acp.pfx');

// 密码加密证书（这条用不到的请随便配）
define('SDK_ENCRYPT_CERT_PATH', APP_ROOT_PATH.'system/payment/upacp/certs/verify_sign_acp.cer');

// 验签证书路径（请配到文件夹，不要配到具体文件）
define('SDK_VERIFY_CERT_DIR',APP_ROOT_PATH.'system/payment/upacp/certs');

define('SDK_FILE_DOWN_PATH',APP_ROOT_PATH.'system/payment/upacp/file');
//define('SDK_LOG_FILE_PATH',APP_ROOT_PATH.'system/payment/upacp/logs');



// 前台请求地址 正式
//define('SDK_FRONT_TRANS_URL','https://gateway.95516.com/gateway/api/frontTransReq.do');
//前台请求地址 测试
//define('SDK_FRONT_TRANS_URL','https://101.231.204.80:5000/gateway/api/frontTransReq.do');
/*
// 后台请求地址
define('SDK_BACK_TRANS_URL','https://101.231.204.80:5000/gateway/api/backTransReq.do');

// 批量交易
define('SDK_BATCH_TRANS_URL','https://101.231.204.80:5000/gateway/api/batchTrans.do');

//单笔查询请求地址
define('SDK_SINGLE_QUERY_URL','https://101.231.204.80:5000/gateway/api/queryTrans.do');

//文件传输请求地址
define('SDK_FILE_QUERY_URL','https://101.231.204.80:9080/');

//有卡交易地址
define('SDK_Card_Request_Url','https://101.231.204.80:5000/gateway/api/cardTransReq.do');

//App交易地址
define('SDK_App_Request_Url','https://101.231.204.80:5000/gateway/api/appTransReq.do');

*/



class Upacpwap_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{
	    // 不是手机端，直接调用跳转链接
	    if ( !(isMobile() || IS_MOBILE == 1) ) {
	        return $this->get_redirect_url($payment_notice_id);
	    }
	    
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$txnAmt = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo,name,class_name from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		$sql = "select name ".
				"from ".DB_PREFIX."deal_order_item ".
				"where order_id =". intval($payment_notice['order_id']). " limit 1";
		$title_name = $GLOBALS['db']->getOne($sql);
		if(empty($title_name))
		{
			$title_name = "充值".round($payment_notice['money'],2)."元";
		}
		
		$pay = array();
		$pay['pay_info'] = $title_name;
		$pay['pay_action'] = SITE_DOMAIN.APP_ROOT."/cgi/payment/upacpwap/redirect.php?notice_id=".$payment_notice_id."&from=".$GLOBALS['request']['from'];
		$pay['payment_name'] = "银联支付";
		$pay['pay_money'] = $txnAmt;
		$pay['class_name'] = "Upacpwap";
		return $pay;		
		
	}	
	
	public function get_redirect_url($payment_notice_id)
	{
		$from = strim($_REQUEST['from']);
		
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$txnAmt = round($payment_notice['money'], 2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo,name from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);
		
		if($from=="wap")
		  	$data_return_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/upacpwap_response.php';
		else
			$data_return_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/upacpwap_response_app.php';
		
		$txnAmt = $payment_notice['money'] * 100;
		$txnAmt = round($txnAmt);
		
		$data_notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/upacpwap_notify.php';
		
		// 签名证书密码
		define('SDK_SIGN_CERT_PWD',$payment_info['config']['upacp_cert_pwd']);
		 
		//print_r(getSignCertId ());exit;
		
		$params = array(
				'version' => '5.0.0',				//版本号
				'encoding' => 'utf-8',				//编码方式
				'certId' => getSignCertId (),			//证书ID
				'txnType' => '01',				//交易类型
				'txnSubType' => '01',				//交易子类
				'bizType' => '000201',				//业务类型
				'frontUrl' =>  $data_return_url,  		//前台通知地址
				'backUrl' => $data_notify_url,		//后台通知地址
				'signMethod' => '01',		//签名方法
				'channelType' => '08',		//渠道类型，07-PC，08-手机
				'accessType' => '0',		//接入类型
				'merId' => $payment_info['config']['upacp_mer_id'],		        //商户代码，请改自己的测试商户号
				'orderId' => $payment_notice['notice_sn'],	//商户订单号
				'txnTime' => to_date(get_gmtime(), 'YmdHis'),	//订单发送时间
				'txnAmt' => $txnAmt,		//交易金额，单位分
				'currencyCode' => '156',	//交易币种
				'defaultPayType' => '0001',	//默认支付方式
				//'orderDesc' => '订单描述',  //订单描述，网关支付和wap支付暂时不起作用
				'reqReserved' =>$payment_notice_id, //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
		);
		
		sign ( $params );
		
		// 前台请求地址 正式
        //define('SDK_FRONT_TRANS_URL','https://gateway.95516.com/gateway/api/frontTransReq.do');
        //前台请求地址 测试
		//define('SDK_FRONT_TRANS_URL','https://101.231.204.80:5000/gateway/api/frontTransReq.do');
		
		if ($payment_info['config']['upacp_mer_id'] == '700000000000001'){		
			//测试帐户
			$html = create_html ($params,'https://101.231.204.80:5000/gateway/api/frontTransReq.do' );
		}else{
			//正式帐户
			$html = create_html ($params,'https://gateway.95516.com/gateway/api/frontTransReq.do' );
		}
		
		return $html;
	
	}

	public function response($request)
	{		
	    
	    $from = $_REQUEST['from'];
		unset($request['from']);
		unset($_POST['from']);
		
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Upacpwap'");  
    	$payment['config'] = unserialize($payment['config']);
    	
    	if(!isset ( $_POST ['signature'] )){
    		die("签名为空");
    	}
    	elseif($_POST ['respCode']=="00" && verify ( $_POST )){
    		$payment_notice_sn = strim($_POST['orderId']);
    		$outer_notice_sn = strim($_POST['queryId']);
    		
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
			require_once APP_ROOT_PATH."system/model/cart.php";
			
			$rs = payment_paid($payment_notice['id']);						
			if($rs)
			{
				//开始更新相应的outer_notice_sn
				$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$outer_notice_sn."' where id = ".$payment_notice['id']);
				order_paid($payment_notice['order_id']);				
			}
			
			if ($from == 'wap'){				
				$url = wap_url("index","payment#done",array("id"=>$payment_notice['order_id']));
				app_redirect($url);
			}else{
				echo $GLOBALS['tmpl']->fetch(APP_ROOT_PATH."system/payment/close_page/close_page.html");
			}			
		}else{
			
			if ($from == 'wap'){
				$url = wap_url("index");
				app_redirect($url);
			}else{
				echo "验证失败<br />请点左上角<b>返回</b>按钮";
			}
		}   
	}
	
	public function notify($request)
	{

		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Upacpwap'");  
    	$payment['config'] = unserialize($payment['config']);
    	
    	if(!isset ( $_POST ['signature'] )){
    		echo "fail";
    	}
    	elseif( verify( $_POST ) ){
    		$payment_notice_sn    = strim($_POST['orderId']);
    		$outer_notice_sn      = strim($_POST['queryId']);
    		
    		$txnAmt = strim($_POST['txnAmt']);
    		$txnAmt = $txnAmt / 100;
    		
    		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
    		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
    		
    		if( round( $payment_notice['money'], 2 ) == $txnAmt ){ //支付成功
    		    require_once APP_ROOT_PATH."system/model/cart.php";
    		    $rs = payment_paid($payment_notice['id']);
    		    if($rs)
    		    {
    		        $GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$outer_notice_sn."' where id = ".$payment_notice['id']);
    		        order_paid($payment_notice['order_id']);
    		    }
    		    echo "success";
    		}else{
    		        echo "fail";
    		    }
		}else{
		   echo "fail";
		}   
	}
	
	
	public function get_display_code(){
	    // 如果是手机端，直接返回名称，否则返回组合好的html
	    if(isMobile() || IS_MOBILE == 1){
	        return '银联支付';
	    }else{
	        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Upacpwap'");
	        if($payment_item)
	        {
	            $html = "<label class='ui-radiobox' rel='payment_rdo'><input type='radio' name='payment' value='".$payment_item['id']."' />";
	
	            if($payment_item['logo']!='')
	            {
	                $html .= "<img src='".APP_ROOT.$payment_item['logo']."' />银联支付 ";
	            }
	            else
	            {
	                $html .= '银联支付';
	            }
	            $html.="</label>";
	            return $html;
	        }
	    }
	
	}

	

}
?>