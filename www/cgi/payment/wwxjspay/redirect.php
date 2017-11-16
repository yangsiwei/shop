<?php
define("FILE_PATH","/cgi/payment/wwxjspay"); //文件目录
require_once '../../../system/system_init.php';
$payment_notice_id = intval($_REQUEST['notice_id']);
require_once APP_ROOT_PATH."system/payment/Wwxjspay_payment.php";
$o = new Wwxjspay_payment();
echo $o->get_redirect_url($payment_notice_id);
?>