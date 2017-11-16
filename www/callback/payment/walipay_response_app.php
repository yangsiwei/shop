<?php

define("FILE_PATH","/callback/payment"); //文件目录
require_once '../../system/system_init.php';

require_once APP_ROOT_PATH."system/payment/Walipay_payment.php";
$o = new Walipay_payment();
$_POST['from'] = "app";
$o->response($_POST);

?>