<?php
define('IS_MOBILE', 1);
define("FOLDER_NAME","wap");
define("FILE_PATH","/".FOLDER_NAME); //文件目录，空为根目录
require_once '../system/system_init.php';
require_once APP_ROOT_PATH.FOLDER_NAME.'/Lib/'.APP_TYPE.'/core/MainApp.class.php';
define("MODULE_PREFIX", "");
//实例化一个网站应用实例
$AppWeb = new MainApp();

?>