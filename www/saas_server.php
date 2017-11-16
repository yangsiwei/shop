<?php

/*
 *url 请求
require_once './saas_server/url_server.php';
$saas_server = new saas_server();

 

$ret = $saas_server->decode_saas_url();
if ($ret === false) {
    echo 'Parameters expired!';
} else {
    print_r($ret);
}

*/

/*
 * 
 * api 接口请求
 * */
require_once './saas_server/api_server.php';
$server = new api_server();



?>