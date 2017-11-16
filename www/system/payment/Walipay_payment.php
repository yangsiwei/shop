<?php

$payment_lang = array(
	'name'	=>	'支付宝手机支付(WAP版本)',
	'alipay_partner'	=>	'合作者身份ID',
	'alipay_account'	=>	'支付宝帐号',
	'alipay_key'	=>	'安全校验码（Key）',
);
$config = array(
	'alipay_partner'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //合作者身份ID
	'alipay_account'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //支付宝帐号: 
	//支付宝公钥
	'alipay_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	)
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Walipay';

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

// 支付宝手机支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
require_once APP_ROOT_PATH."system/payment/Walipay/alipay_submit.class.php";
require_once APP_ROOT_PATH."system/payment/Walipay/alipay_notify.class.php";
class Walipay_payment implements payment {

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
		$pay['pay_action'] = SITE_DOMAIN.APP_ROOT."/cgi/payment/walipay/redirect.php?notice_id=".$payment_notice_id."&from=".$GLOBALS['request']['from'];
		$pay['payment_name'] = "支付宝支付";
		$pay['pay_money'] = $money;
		$pay['class_name'] = "Walipay";
		return $pay;		
	}
	
	private function load_alipay_config()
	{
		$payment_info = $GLOBALS['db']->getRow("select config from ".DB_PREFIX."payment where class_name='Walipay'");
		$payment_info['config'] = unserialize($payment_info['config']);
		
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner']		= $payment_info['config']['alipay_partner'];
		
		//合作者支付宝帐号
		$alipay_config['account']		= $payment_info['config']['alipay_account'];
		
		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为“MD5”时，请设置该参数
		$alipay_config['key']			= $payment_info['config']['alipay_key'];
		
		
		//商户的私钥（后缀是.pen）文件相对路径
		//如果签名方式设置为“0001”时，请设置该参数
		$alipay_config['private_key_path']	= '';//key/rsa_private_key.pem';
		
		//支付宝公钥（后缀是.pen）文件相对路径
		//如果签名方式设置为“0001”时，请设置该参数
		$alipay_config['ali_public_key_path']= '';//'key/alipay_public_key.pem';
		
		
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = 'MD5';
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= 'utf-8';
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = '';//getcwd().'\\cacert.pem';
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		
		return $alipay_config;
	}
	
	public function get_redirect_url($payment_notice_id)
	{
		$from = strim($_REQUEST['from']);
		
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$money = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo,name from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		
		
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config = $this->load_alipay_config();
		
		
		if($from=="wap")
		  	$data_return_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/walipay_response.php';
		else
			$data_return_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/walipay_response_app.php';
		
		$data_notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/walipay_notify.php';
		
		
		
		

		//返回格式
		$format = "xml";
		//必填，不需要修改
		
		//返回格式
		$v = "2.0";
		//必填，不需要修改
		
		//请求号
		$req_id = date('Ymdhis');
		//必填，须保证每次请求都是唯一
		
		//**req_data详细信息**
		
		
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//卖家支付宝帐户
		$seller_email = $alipay_config['account'];
		//必填
		
		
		
		//商户网站订单系统中唯一订单号，必填
		
		//订单名称
		$subject = $order_sn;
		
		//必填		
		//付款金额
		$pay_price = $money;		

		//商户订单号
		$out_trade_no = $payment_notice['notice_sn'];
		
		$total_fee = $money;
		
		//必填
		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $data_notify_url . '</notify_url><call_back_url>' . $data_return_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee></direct_trade_create_req>';
		//必填
		
		/************************************************************/
		
		//构造要请求的参数数组，无需改动
		$para_token = array(
				"service" => "alipay.wap.trade.create.direct",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		
		
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);
		
		
		//URLDECODE返回的信息
		$html_text = urldecode($html_text);
		
		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);
		
		
		//获取request_token
		$request_token = $para_html_text['request_token'];
		
		
		/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/
		
		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填
		
		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.wap.auth.authAndExecute",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
// 		print_r($alipay_config);exit;
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		//$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '页面跳转中，如果未跳转点此');
		//echo $html_text;
		
		$link = $alipaySubmit->alipay_gateway_new.$alipaySubmit->buildRequestParaToString($parameter);
		$html_text = '<script type="text/javascript" src="'.SITE_DOMAIN.APP_ROOT.'/cgi/payment/walipay/ap.js"></script>';
		$html_text.='<script>_AP.pay("'.$link.'");</script>';
		return $html_text;
		
	}
	
	public function response($request)
	{	

		$from = $_POST['from'];
		unset($request['from']);
		unset($_POST['from']);
		unset($_GET['from']);
		unset($_REQUEST['from']);
		
		$alipay_config = $this->load_alipay_config();
		
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代码
		
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
		
			//商户订单号
			$out_trade_no = $_GET['out_trade_no'];
		
			//支付宝交易号
			$trade_no = $_GET['trade_no'];
		
			//交易状态
			$result = $_GET['result'];
		
		
			//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		
		
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");
			//file_put_contents(APP_ROOT_PATH."/alipaylog/payment_notice_sn_3.txt",$payment_notice_sn);
			 
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
			require_once APP_ROOT_PATH."system/model/cart.php";
			$rs = payment_paid($payment_notice['id']);
			if ($rs)
			{
				//file_put_contents(APP_ROOT_PATH."/alipaylog/1.txt","");
				$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);
				order_paid($payment_notice['order_id']);
				//echo "支付成功<br />请点左上角<b>返回</b>按钮";
				//app_redirect(APP_ROOT."/wap/index.php?ctl=pay_order&act=index&order_id=".$payment_notice['order_id']);
			}
			 
			if ($from == 'wap'){				
				$url = wap_url("index","payment#done",array("id"=>$payment_notice['order_id']));
				app_redirect($url);
			}else{
				echo file_get_contents(APP_ROOT_PATH."system/payment/close_page/close_page.html");
			}
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
		
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
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
		
		$alipay_config = $this->load_alipay_config();
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		//echo "verify_result:".$verify_result;
		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代
		
		
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		
			//解密（如果是RSA签名需要解密，如果是MD5签名则下面一行清注释掉）
			//$notify_data = decrypt($_POST['notify_data']);
			$notify_data = $_POST['notify_data'];
		
			//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
		
			//解析notify_data
			//注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
			$doc = new DOMDocument();
			$doc->loadXML($notify_data);
		
			if( ! empty($doc->getElementsByTagName("notify")->item(0)->nodeValue) ) {
				//商户订单号
				$out_trade_no = $doc->getElementsByTagName("out_trade_no")->item(0)->nodeValue;
				//支付宝交易号
				$trade_no = $doc->getElementsByTagName("trade_no")->item(0)->nodeValue;
				//交易状态
				$trade_status = $doc->getElementsByTagName("trade_status")->item(0)->nodeValue;
		
				if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
					//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//如果有做过处理，不执行商户的业务程序
						
					//注意：
					//该种交易状态只在两种情况下出现
					//1、开通了普通即时到账，买家付款成功后。
					//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
		
					//调试用，写文本函数记录程序运行情况是否正常
					//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
						
					$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$out_trade_no."'");
					 
					$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
					require_once APP_ROOT_PATH."system/model/cart.php";
					$rs = payment_paid($payment_notice['id']);
					if ($rs)
					{
						//file_put_contents(APP_ROOT_PATH."/alipaylog/1.txt","");
						$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$trade_no."' where id = ".$payment_notice['id']);
						order_paid($payment_notice['order_id']);
						//echo "验证成功<br />";
					}
						
					echo "success";		//请不要修改或删除
				}
			}
		
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
		
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
			//验证失败
			echo "fail";
		
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
	}
	
	public function get_display_code(){
	    // 如果是手机端，直接返回名称，否则返回组合好的html
	    if(isMobile() || IS_MOBILE == 1){
	        return '支付宝wap支付';
	    }else{
	        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Upacpwap'");
	        if($payment_item)
	        {
	            $html = "<label class='ui-radiobox' rel='payment_rdo'><input type='radio' name='payment' value='".$payment_item['id']."' />";
	
	            if($payment_item['logo']!='')
	            {
	                $html .= "<img src='".APP_ROOT.$payment_item['logo']."' />支付宝wap支付 ";
	            }
	            else
	            {
	                $html .= '支付宝wap支付';
	            }
	            $html.="</label>";
	            return $html;
	        }
	    }
	
	}
	
}
?>