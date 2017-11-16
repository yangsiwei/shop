<?php
if(!defined('APP_ROOT_PATH')) 
		define('APP_ROOT_PATH', str_replace('system/payment/wjdpay/config/config.php', '', str_replace('\\', '/', __FILE__)));

$payment_config = $GLOBALS['db']->getOne("select config from ".DB_PREFIX."payment where class_name='Wjdpay'");
$db_config=unserialize($payment_config);
$CallbackUrl=get_domain().APP_ROOT.'/callback/payment/wjdpay_web/wjdpay_response.php';
return array(
	"merchantNum" => $db_config['merchantNum'],
	"desKey" => $db_config['desKey'],
	"md5Key" => $db_config['md5Key'],
	"serverPayUrl" => "https://m.jdpay.com/wepay/web/pay",
	//"serverPayUrl" => "http://plus.jdpay.com/pay.htm",
	
	"successCallbackUrl"=>$CallbackUrl,
	"failCallbackUrl"=>$CallbackUrl,
	"notifyUrl"=>get_domain().APP_ROOT.'/callback/payment/wjdpay_web/WebAsynNotificationCtrl.php',
	"forPayLayerUrl"=>get_domain().APP_ROOT.'/forPayLayer.html',
	"serverQueryUrl"=>"http://m.jdpay.com/wepay/query",
	"serverRefundUrl"=>"http://m.jdpay.com/wepay/refund"
);
?>
