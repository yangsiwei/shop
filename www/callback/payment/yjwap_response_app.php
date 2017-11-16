<?php
define("FILE_PATH","/callback/payment"); //文件目录
require_once '../../system/system_init.php';

require_once APP_ROOT_PATH."system/payment/Yjwap_payment.php";
$o = new Yjwap_payment();
$_REQUEST['from'] = "app";
$o->response($_REQUEST);
?>