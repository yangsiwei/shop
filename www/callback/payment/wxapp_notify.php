<?php

define("FILE_PATH","/callback/payment"); //文件目录
require_once '../../system/system_init.php';

require_once APP_ROOT_PATH."system/payment/WxApp_payment.php";
$o = new WxApp_payment();
$o->notify($_POST);

?>