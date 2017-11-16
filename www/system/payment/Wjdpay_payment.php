<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'京东支付(WAP版本)',
	'merchantNum'	=>	'商户号',
	'desKey'	=>	'商户DES密钥',
	'md5Key'	=>	'商户MD5密钥',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',
	'GO_TO_PAY'	=>	'前往京东在线支付',
);
$config = array(
	'merchantNum'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //商户编号
	'desKey'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //商户DES密钥
	'md5Key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	) //商户MD5密钥

);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    if(ACTION_NAME=='install')
	{
		//更新字段
		$GLOBALS['db']->query("ALTER TABLE `".DB_PREFIX."user`  ADD COLUMN `wjdpay_token`  varchar(255) NOT NULL",'SILENT');
	}
    $module['class_name']    = 'Wjdpay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];

   /* 支付方式：1：在线支付；0：线下支付;2:手机wap;3:手机sdk */
    $module['online_pay'] = '6';
    
    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

require_once(APP_ROOT_PATH.'system/payment/wjdpay/config/config.php');//京东支付配置文件

// 京东支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');

class Wjdpay_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{
	    // 不是手机端，直接调用跳转链接
	    if ( !(isMobile() || IS_MOBILE == 1) ) {
	        echo $this->get_redirect_url($payment_notice_id);
	        exit;
	    }

		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$money = round($payment_notice['money'],2);
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
		$pay['pay_action'] = SITE_DOMAIN.APP_ROOT."/cgi/payment/wjdpay/redirect.php?notice_id=".$payment_notice_id."&from=".$GLOBALS['request']['from'];
		$pay['payment_name'] = "京东支付";
		$pay['pay_money'] = $money;
		$pay['class_name'] = "Wjdpay";
		return $pay;
	}
	
	
	public function get_redirect_url($payment_notice_id)
	{
		require_once(APP_ROOT_PATH.'system/payment/wjdpay/common/SignUtil.php');
		require_once(APP_ROOT_PATH.'system/payment/wjdpay/common/TDESUtil.php');
		require_once(APP_ROOT_PATH.'system/payment/wjdpay/common/ConfigUtil.php');
		$from = strim($_REQUEST['from']);
		
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$money = intval( round($payment_notice['money'], 2 ) * 100);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		
		 
		$payment_info['config'] = unserialize($payment_info['config']);
		$user=$GLOBALS['db']->getRow("select wjdpay_token from ".DB_PREFIX."user where id=".intval($payment_notice['user_id']));
		
		$order_info = $GLOBALS['db']->getRow("select order_sn,bank_id from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		
		$sql = "select name ".
				"from ".DB_PREFIX."deal_order_item ".
				"where order_id =". intval($payment_notice['order_id']);
		$title_name = $GLOBALS['db']->getOne($sql);
		
		if(empty($title_name))
		{
		    $title_name = "充值".round($payment_notice['money'],2)."元";
		}
		
		
		if (isMobile() ||  $from == 'wap' || IS_MOBILE == 1) {
		    $pay_url = 'https://h5pay.jd.com/jdpay/saveOrder';
		    if($order_info['type']==1)
		    {
		        $callbackUrl = SITE_DOMAIN.wap_url("index","user_center#index");
		    }
		    else
		    {
		        $callbackUrl = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$payment_notice['order_id']));
		    }
		}else{
		    $pay_url = 'https://wepay.jd.com/jdpay/saveOrder';
		    
		    if($order_info['type']==1){
		        $callbackUrl = SITE_DOMAIN.url("index","uc_log#money");
		    }else {
		        $callbackUrl = SITE_DOMAIN.url("index","uc_duobao#index");
		    }
		}
		$notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/Wjdpay_notify.php';
		 
		$param = array();
		$param = array(
		    "version"             =>  "V2.0",
		    "merchant"            =>  "{$payment_info['config']["merchantNum"]}",
		    "device"              =>  "",
		    "tradeNum"            =>  "{$payment_notice['notice_sn']}",
		    "tradeName"           =>  "{$title_name}",
		    "tradeDesc"           =>  "",
		    "tradeTime"           =>  "".to_date($payment_notice['create_time'], 'YmdHis'),
		    "amount"              =>  "{$money}",
		    "currency"            =>  "CNY",
		    "note"                =>  "",
		    "callbackUrl"         =>  $callbackUrl,
		    "notifyUrl"           =>  $notify_url,
		    "ip"                  =>  "".gethostbyname($_SERVER["SERVER_NAME"]),
		    "specCardNo"          =>  "",
		    "specId"              =>  "",
		    "specName"            =>  "",
		    "userType"            =>  "BIZ",
		    "userId"              =>  "{$payment_notice['user_id']}",
		    "expireTime"          =>  "",
		    "orderType"           =>  "0",
		    "industryCategoryCode"=>  "",
		  );
		  
        $unSignKeyList = array ("sign");
        $desKey = trim($payment_info['config']["desKey"]);//商户DES密钥
		$sign = SignUtil::signWithoutToHex($param, $unSignKeyList);//交易信息签名 String(256)
		$param["sign"] = $sign;
		$keys   = base64_decode($desKey);
	  
		 
		
		$no_des_arr=array("merchant", "version", "sign"); 
		 
		foreach($param as $k=>$v){
		    if ($v == "") {
		        continue;
		    }
		    if(!in_array($k, $no_des_arr)){
		        $param[$k]= TDESUtil::encrypt2HexStr($keys, $v);
		    }
		}
		  
		$payLinks .= '<form method="post" action="'.$pay_url.'" id="payForm">';
		$payLinks .= '<input type="hidden" name="version" value="'.$param['version'].'"/>';
		$payLinks .= '<input type="hidden" name="merchant" value="'.$param['merchant'].'"/>';
		$payLinks .= '<input type="hidden" name="device" value="'.$param['device'].'"/>';
		$payLinks .= '<input type="hidden" name="tradeNum" value="'.$param['tradeNum'].'"/>';
		$payLinks .= '<input type="hidden" name="tradeName" value="'.$param['tradeName'].'"/>';
		$payLinks .= '<input type="hidden" name="tradeDesc" value="'.$param['tradeDesc'].'"/>';
		$payLinks .= '<input type="hidden" name="tradeTime" value="'.$param['tradeTime'].'"/>';
		$payLinks .= '<input type="hidden" name="amount" value="'.$param['amount'].'"/>';
		$payLinks .= '<input type="hidden" name="currency" value="'.$param['currency'].'"/>';
		$payLinks .= '<input type="hidden" name="note" value="'.$param['note'].'"/>';
		$payLinks .= '<input type="hidden" name="callbackUrl" value="'.$param['callbackUrl'].'"/>';
		$payLinks .= '<input type="hidden" name="notifyUrl" value="'.$param['notifyUrl'].'"/>';
		$payLinks .= '<input type="hidden" name="ip" value="'.$param['ip'].'"/>';
		$payLinks .= '<input type="hidden" name="userType" value="'.$param['userType'].'"/>';
		$payLinks .= '<input type="hidden" name="userId" value="'.$param['userId'].'"/>';
		$payLinks .= '<input type="hidden" name="expireTime" value="'.$param['expireTime'].'"/>';
		$payLinks .= '<input type="hidden" name="orderType" value="'.$param['orderType'].'"/>';
		$payLinks .= '<input type="hidden" name="industryCategoryCode" value="'.$param['industryCategoryCode'].'"/>';
		$payLinks .= '<input type="hidden" name="specCardNo" value="'.$param['specCardNo'].'"/>';
		$payLinks .= '<input type="hidden" name="specId" value="'.$param['specId'].'"/>';
		$payLinks .= '<input type="hidden" name="specName" value="'.$param['specName'].'"/>';
		$payLinks .= '<input type="hidden" name="sign" value="'.$param['sign'].'"/>';
		$payLinks .= '</form>';
		$payLinks .= '<script type="text/javascript">document.getElementById("payForm").submit();</script>';
		 
		
		return $payLinks;
		
	
	
	}
	public function response($request)
	{
		
	}
	
	public function notify($request)
	{
	    include APP_ROOT_PATH.'system/payment/wjdpay/common/ConfigUtil.php';
	    include APP_ROOT_PATH.'system/payment/wjdpay/common/XMLUtil.php';
	   
	    
	    $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
	    $resdata = '';
	    $falg = XMLUtil::decryptResXml($xml, $resdata);
	    // 验签成功
	    if($falg && $resdata['status'] == 2 && $resdata['result']['desc'] == 'success') {
	        // 商户订单号：  一元收款单的，付款单号 notice_sn
	        $out_trade_no = $resdata['tradeNum'];
	        // 价格
	        $money  = $resdata['amount'] / 100;
	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");
	        if(round($payment_notice['money'],2) == $money){ //支付成功
	            require_once APP_ROOT_PATH."system/model/cart.php";
	            $rs = payment_paid($payment_notice['id']);
	            if ($rs)
	            {
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
	
	public function get_display_code()
	{
		// 如果是手机端，直接返回名称，否则返回组合好的html
		if(isMobile() || IS_MOBILE == 1){
		    return '京东支付';
		}else{
		    $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Wjdpay'");
		    if($payment_item)
		    {
		        $html = "<label class='ui-radiobox' rel='payment_rdo'><input type='radio' name='payment' value='".$payment_item['id']."' />";
		
		        if($payment_item['logo']!='')
		        {
		            $html .= "<img src='".APP_ROOT.$payment_item['logo']."' />京东支付 ";
		        }
		        else
		        {
		            $html .= '京东支付';
		        }
		        $html.="</label>";
		        return $html;
		    }
		}
	}
	
	
}

?>