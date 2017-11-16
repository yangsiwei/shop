<?php

/*
 *url 请求
 *
 **/

require_once './saas_server/url_server.php';
$saas_server = new url_server();

$ret = $saas_server->decode_saas_url();
if ($ret === false) {
    echo 'Parameters expired!';
} else {
    print_r($ret);
}

?>