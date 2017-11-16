<?php
/**
 * url 请求
  */
require_once './saas_client/url_client.php';
$saas_client = new url_client();
echo $saas_client->encode_saas_url("http://localhost/o2onew/saas_url_server.php", array("user_name"=>"jobin","user_pwd"=>"aaaa"));

exit;