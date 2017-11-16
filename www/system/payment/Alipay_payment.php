<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'支付宝支付',
	'alipay_partner'	=>	'合作者身份ID',
	'alipay_account'	=>	'支付宝帐号',
	'alipay_key'		=>	'校验码',
	'alipay_service'	=>	'接口方式',
	'alipay_service_0'	=>	'使用标准双接口',
	'alipay_service_1'	=>	'担保交易接口',
	'alipay_service_2'	=>	'即时到帐接口',
	'GO_TO_PAY'	=>	'前往支付宝在线支付',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',
);
$config = array(
	'alipay_partner'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //合作者身份ID
	'alipay_account'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //支付宝帐号: 
	'alipay_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //校验码
	'alipay_service'	=>	array(
		'INPUT_TYPE'	=>	'1',
		'VALUES'	=> 	array(0,1,2)
	),
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Alipay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '1';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = 'http://act.life.alipay.com/systembiz/fangwei/';
    return $module;
}

// 支付宝支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Alipay_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$money = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);

		
		$data_return_url = SITE_DOMAIN.APP_ROOT.'/index.php?ctl=payment&act=response&class_name=Alipay';
		$data_notify_url = SITE_DOMAIN.APP_ROOT.'/index.php?ctl=payment&act=notify&class_name=Alipay';

		$real_method = $payment_info['config']['alipay_service'];

        switch ($real_method){
            case '0':
                $service = 'trade_create_by_buyer';
                break;
            case '1':
                $service = 'create_partner_trade_by_buyer';
                break;
            case '2':
                $service = 'create_direct_pay_by_user';
                break;
        }	
		
		
        $parameter = array(
            'service'           => $service,
            'partner'           => $payment_info['config']['alipay_partner'],
            //'partner'           => ALIPAY_ID,
            '_input_charset'    => 'utf-8',
            'notify_url'        => $data_notify_url,
            'return_url'        => $data_return_url,
            /* 业务参数 */
            'subject'           => $order_sn,
            'out_trade_no'      => $payment_notice['notice_sn'], 
            'price'             => $money,
            'quantity'          => 1,
            'payment_type'      => 1,
            /* 物流参数 */
            'logistics_type'    => 'EXPRESS',
            'logistics_fee'     => 0,
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
            /* 买卖双方信息 */
            'seller_email'      => $payment_info['config']['alipay_account']
        );
        
        ksort($parameter);
        reset($parameter);

        $param = '';
        $sign  = '';

        foreach ($parameter AS $key => $val)
        {
        	$param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        }

        $param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1). $payment_info['config']['alipay_key'];
        $sign_md5 = md5($sign);

		
       
		$payLinks = '<form action="https://mapi.alipay.com/gateway.do?'.$param. '&sign='.$sign_md5.'&sign_type=MD5" method="POST" target="_blank" ><button type="submit" class="ui-button paybutton" rel="blue">前往支付宝在线支付</button></form>';


		
        $code = '<div style="text-align:center">'.$payLinks.'</div>';
		$code.="<br /><div style='text-align:center' class='red'>".$GLOBALS['lang']['PAY_TOTAL_PRICE'].":".format_price($money)."</div>";
        return $code;
	}
	
	public function response($request)
	{
        
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Alipay'");  
    	$payment['config'] = unserialize($payment['config']);
    	
    	
        /* 检查数字签名是否正确 */
        ksort($request);
        reset($request);
	
        foreach ($request AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key!='class_name' && $key!='act'&& $key!='ctl'&& $key!='city' )
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $payment['config']['alipay_key'];

		if (md5($sign) != $request['sign'])
        {
            showErr($GLOBALS['payment_lang']["VALID_ERROR"]);
        }
		
        $payment_notice_sn = $request['out_trade_no'];
        
    	$money = $request['total_fee'];
		
    	$outer_notice_sn = $request['trade_no'];
		
		if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED' || $request['trade_status'] == 'WAIT_SELLER_SEND_GOODS'|| $request['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS'){
			
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
			require_once APP_ROOT_PATH."system/model/cart.php";
			$rs = payment_paid($payment_notice['id']);						
			if($rs)
			{
				//开始更新相应的outer_notice_sn
				$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$outer_notice_sn."' where id = ".$payment_notice['id']);
				$rs = order_paid($payment_notice['order_id']);				
				if($rs)
				{					
					$this->auto_do_send_goods($payment_notice,$order_info);					
					if($order_info['type']==0)
					app_redirect(url("index","payment#done",array("id"=>$payment_notice['order_id']))); //支付成功
					else
					app_redirect(url("index","payment#incharge_done",array("id"=>$payment_notice['order_id']))); //支付成功
				}
				else 
				{
					if($order_info['pay_status'] == 2)
					{				
						$this->auto_do_send_goods($payment_notice,$order_info);		
						if($order_info['type']==0)
						app_redirect(url("index","payment#done",array("id"=>$payment_notice['order_id']))); //支付成功
						else
						app_redirect(url("index","payment#incharge_done",array("id"=>$payment_notice['order_id']))); //支付成功
					}
					else
					app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id']))); 
				}
			}
			else
			{
				$this->auto_do_send_goods($payment_notice,$order_info);
				app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id']))); 
			}
		}else{
		    showErr($GLOBALS['payment_lang']["PAY_FAILED"]);
		}   
	}
	
	public function notify($request)
	{
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Alipay'");  
    	$payment['config'] = unserialize($payment['config']);
    	
    	
        /* 检查数字签名是否正确 */
        ksort($request);
        reset($request);
	
        foreach ($request AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key!='class_name' && $key!='act'&& $key!='ctl'&& $key!='city'  )
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $payment['config']['alipay_key'];

		if (md5($sign) != $request['sign'])
        {
            echo "fail";
        }
		
        $payment_notice_sn = $request['out_trade_no'];
        
    	$money = $request['total_fee'];
		$outer_notice_sn = $request['trade_no'];

		if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED' || $request['trade_status'] == 'WAIT_SELLER_SEND_GOODS' || $request['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS'){
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
			require_once APP_ROOT_PATH."system/model/cart.php";
			$rs = payment_paid($payment_notice['id']);								
			if($rs)
			{			
				$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$outer_notice_sn."' where id = ".$payment_notice['id']);				
				order_paid($payment_notice['order_id']);	
				$this->auto_do_send_goods($payment_notice,$order_info);				
				echo "success";
			}
			else
			{
				$this->auto_do_send_goods($payment_notice,$order_info);	
				echo "fail";
			}
			
		}else{
		   echo "fail";
		}   
	}
	
	public function get_display_code()
	{
		$payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Alipay'");
		if($payment_item)
		{
			$html = "<label class='ui-radiobox' rel='payment_rdo'><input type='radio' name='payment' value='".$payment_item['id']."' />";

			if($payment_item['logo']!='')
			{
				$html .= "<img src='".APP_ROOT.$payment_item['logo']."' />{$payment_item['name']} ";
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
	
	public function auto_do_send_goods($payment_notice,$order_info)
	{


	}
	
	public function do_send_goods($payment_notice_id,$delivery_notice_sn)
	{
		require_once APP_ROOT_PATH."system/utils/XmlBase.php"; 
		$payment_notice  = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Alipay'");  
    	$payment['config'] = unserialize($payment['config']);

    	
		$gateway = "https://mapi.alipay.com/gateway.do";
			
		$parameter = array(
			'service'	=>	'send_goods_confirm_by_platform',
			'partner'	=>	$payment['config']['alipay_partner'],
			'_input_charset'	=>	'utf-8',
			'invoice_no'	=>	$delivery_notice_sn,
			'transport_type'	=>	'EXPRESS',
			'logistics_name'	=>	'NONE',
			'trade_no'	=>	$payment_notice['outer_notice_sn']
		);
		
		ksort($parameter);
        reset($parameter);

        $sign  = '';
        $param = '';

        foreach ($parameter AS $key => $val)
        {
            $sign  .= "$key=$val&";
            $param .= "$key=" .urlencode($val). "&";
        }

        $param  = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1).$payment['config']['alipay_key'];
        $sign_md5 = md5($sign);
        
        
		$param.="&sign=".$sign_md5."&sign_type=MD5";
        
        $curl_exists = function_exists('curl_init');
        
        if($curl_exists)
        {
	        $ch = curl_init();
	        //curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	        curl_setopt($ch, CURLOPT_URL,$gateway);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	        $result = curl_exec ($ch);
	        curl_close ($ch);
        }
        else
        {
        	$result = file_get_contents($gateway."?".$param);
        }
		
        if($result)
		$result = toArray($result,"alipay");
        else
        {
        	return "同步发货失败，请检查服务器是否开启了curl支持";
        }

		if($result['is_success'][0]=='T')
		{
			return "支付宝发货成功";
		}
		else
		{
			if($result['error']=='ILLEGAL_ARGUMENT')
			{
				return $result['error'].' 参数不正确';
			}
			elseif($result['error']=='TRADE_NOT_EXIST')
			{
				return $result['error'].' 交易单号有误';
			}
			elseif($result['error']=='GENERIC_FAILURE')
			{
				return $result['error'].' 执行命令错误';
			}
			else
			{
				return $result['error'];
			}			
		}
	}
}
?>