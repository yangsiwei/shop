<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------\
define("FILE_PATH",""); //文件目录，空为根目录
require_once './system/system_init.php';
$sess_id = strim($_REQUEST['sess_id']);
if($sess_id)
{
	es_session::set_sessid($sess_id);
}
es_session::start();
require_once APP_ROOT_PATH."system/utils/es_image.php";
es_image::buildImageVerify(4,1);
?>