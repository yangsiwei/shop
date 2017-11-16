<?php
$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
$adm_name = $adm_session['adm_name'];
$adm_id = intval($adm_session['adm_id']);

if($adm_id==0 && !es_session::get("user_info") && !es_session::get("account_info")){
	app_redirect("404.html");
	exit();
}
?>
