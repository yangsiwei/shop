<?php

$payment_lang = array(
	'name'	=>	'云支付支付(WAP版本)',
	'yunpay_partner'	=>	'合作者身份ID',
    'yunpay_email'	=>	'云会员账户（邮箱）',
	'yunpay_key'	=>	'安全检验码',
);
$config = array(
	'yunpay_partner'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //合作者身份ID
	'yunpay_email'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //云会员账户（邮箱）: 
	//安全检验码
	'yunpay_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	)
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Wyunpay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付  2:仅wap支付 3:仅app支付 4:兼容wap和app*/
    $module['online_pay'] = '4';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

//云支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
require_once APP_ROOT_PATH."system/payment/Wyunpay/yun_md5.function.php";
class Wyunpay_payment implements payment {

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
		$pay['pay_action'] = SITE_DOMAIN.APP_ROOT."/cgi/payment/wyunpay/redirect.php?notice_id=".$payment_notice_id."&from=".$GLOBALS['request']['from'];
		$pay['payment_name'] = "云支付";
		$pay['pay_money'] = $money;
		$pay['class_name'] = "Wyunpay";
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
		
		$order_sn = $order_info['order_sn'];
		$money = round($payment_notice['money'],2);
		$money_fee=intval($money*100);
		$notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/wyunpay_notify.php';
		$response_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/wyunpay_response.php';
		$body = iconv_substr($title_name,0,50, 'UTF-8');
		
		$pay_url = "http://www.cyh.org.cn/i2eorder/yunpay/newapi.php"; 
	    //构造要请求的参数数组，无需改动
        $parameter = array(
        		"partner" => trim($config['yunpay_partner']),
        		"seller_email"	=> $config['yunpay_email'],
        		"out_trade_no"	=> $payment_notice['notice_sn'],
        		"subject"	=> $title_name,
        		"total_fee"	=> $money,
        		"body"	=> $body,
        		"nourl"	=> $notify_url,
        		"reurl"	=> $response_url,
        		"orurl"	=> "",
        		"orimg"	=> ""
        );

        $html_text = i2e($parameter, "支付进行中...",$config['yunpay_key']);
        $html_text = str_replace("__pay_url__", $pay_url, $html_text);
		return $html_text;
		
	}
	
	public function response($request)
	{	
	    $from = $_POST['from'];
	    unset($request['from']);
	    unset($_POST['from']);
	    unset($_GET['from']);
	    unset($_REQUEST['from']);
// 	    $_REQUEST['i1']=0.01;
// 	    $_REQUEST['i2']='2016041912260368';
// 	    $_REQUEST['i3']='71564b90ea4f117de19aa30f992a2782';
// 	    $_REQUEST['i4']='201604191226044051';

	    
	    //获取配置信息
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Wyunpay'");
		$payment_info['config'] = unserialize($payment_info['config']);
		$yun_config=$payment_info['config'];
	    
	    //计算得出通知验证结果
	    $yunNotify = yun_md5Verify($_REQUEST['i1'],$_REQUEST['i2'],$_REQUEST['i3'],$yun_config['yunpay_key'],$yun_config['yunpay_partner']);
	    if($yunNotify) {//验证成功
	        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        //商户订单号
	       
	        $out_trade_no = $_REQUEST['i2'];
	        
	        //云支付交易号
	         
	        $trade_no = $_REQUEST['i4'];
	        
	        //价格
	        $yunprice=$_REQUEST['i1'];
	        
	    
	        /*
        	         加入您的入库及判断代码;
        	         判断返回金额与实金额是否想同;
        	         判断订单当前状态;
        	         完成以上才视为支付成功
	        */

	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");

	        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);

	        if(round($payment_notice['money'],2)==$yunprice){ //支付成功
	            require_once APP_ROOT_PATH."system/model/cart.php";

	            $rs = payment_paid($payment_notice['id']);
	            if ($rs)
	            {
	                echo 2;
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
	                echo file_get_contents(APP_ROOT_PATH."system/payment/close_page/close_page.html");
	            }
	        }else{
	            echo "支付失败";
	        }
    	        
	    }else {
			if ($from == 'wap'){
				$url = wap_url("index");
				app_redirect($url);
			}else{
				echo "验证失败<br />请点左上角<b>返回</b>按钮";
			}
		}				
	}
	
	public function notify($request){

		$from = $_POST['from'];
		unset($request['from']);
		unset($_POST['from']);
		unset($_GET['from']);
		unset($_REQUEST['from']);
		
	   //获取配置信息
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Wyunpay'");
		$payment_info['config'] = unserialize($payment_info['config']);
		$yun_config=$payment_info['config'];
	    
	    //计算得出通知验证结果
	    $yunNotify = yun_md5Verify($_REQUEST['i1'],$_REQUEST['i2'],$_REQUEST['i3'],$yun_config['yunpay_key'],$yun_config['yunpay_partner']);
	    if($yunNotify) {//验证成功
	        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        //商户订单号
	        
	        $out_trade_no = $_REQUEST['i2'];
	        
	        //云支付交易号
	         
	        $trade_no = $_REQUEST['i4'];
	        
	        //价格
	        $yunprice=$_REQUEST['i1'];
	        
	    
	        /*
        	         加入您的入库及判断代码;
        	         判断返回金额与实金额是否想同;
        	         判断订单当前状态;
        	         完成以上才视为支付成功
	        */
	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");

	        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
	        if(round($payment_notice['money'],2)==$yunprice){ //支付成功
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
	        return '云支付';
	    }else{
	        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='ZwxApp'");
	        if($payment_item)
	        {
	            $html = "<label class='ui-radiobox' rel='payment_rdo'><input type='radio' name='payment' value='".$payment_item['id']."' />";
	
	            if($payment_item['logo']!='')
	            {
	                $html .= "<img src='".APP_ROOT.$payment_item['logo']."' />云支付 ";
	            }
	            else
	            {
	                $html .= '云支付';
	            }
	            $html.="</label>";
	            return $html;
	        }
	    }
	
	}
}
?>