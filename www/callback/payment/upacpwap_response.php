<?php

define("FILE_PATH","/callback/payment"); //文件目录
require_once '../../system/system_init.php';

require_once APP_ROOT_PATH."system/payment/Upacpwap_payment.php";
$o = new Upacpwap_payment();
$_REQUEST['from'] = "wap";
$o->response($_REQUEST);

?>