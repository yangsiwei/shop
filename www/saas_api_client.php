<?php

/**
 * api 请求
 */
define("FILE_PATH",""); //文件目录，空为根目录
require_once './system/system_init.php';


require_once './saas_client/api_client.php';
$client = new api_client();

$user_info = serialize($GLOBALS['db']->getRow("select * from fanwe_user where id =251"));
// print_r($user_info);exit;
// $parmat = array(
//     'id'=>$deal['id'],
//     'name'=>$deal['name'],
//     'sub_name'=>$deal['sub_name'],
//     'img'=>str_replace("./public",SITE_DOMAIN.APP_ROOT."/public",$deal['img']),
//     'origin_price' => $deal['origin_price'],
//     'current_price' => $deal['current_price'],
// );

$res = $client->invoke_data("http://localhost/o2onew/saas_api_server.php", array("ctl"=>"user","act"=>"edit_user","data"=>$user_info));

print_r($res);