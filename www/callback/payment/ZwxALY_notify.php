<?php

define("FILE_PATH","/callback/payment"); //文件目录
require_once '../../system/system_init.php';

require_once APP_ROOT_PATH."system/payment/ZwxALY_payment.php";
$o = new ZwxALY_payment();
$o->notify($_REQUEST);

?>