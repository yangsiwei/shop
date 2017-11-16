<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo conf("APP_NAME");?><?php echo l("ADMIN_PLATFORM");?></title>
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/style.css" />
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/top.css" />
<script type="text/javascript" src="__TMPL__Common/js/jquery.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.timer.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/top.js"></script>

<script type="text/javascript" src="__TMPL__Common/js/msg_sender.js"></script>

<script type="text/javascript" src="__ROOT__/public/runtime/admin/lang.js"></script>
</head>

<body>
	<div id="info"></div>
	<div id="logo"></div>
	
	
	<div id="tips">
		<a href="<?php echo APP_ROOT."/"; ?>" target="_blank"><?php echo L("VISIT_HOME");?></a>
		<a href="<?php echo u("Index/change_password");?>" target="main"><?php echo l("CHANGE_PASSWORD");?></a>
		<a href="<?php echo u("Cache/index");?>" target="main"><?php echo l("CLEAR_CACHE");?></a>
		<a href="<?php echo u("Public/do_loginout");?>" target="_parent"><?php echo l("LOGIN_OUT");?></a>
	</div>
	
	<div class="blank5"></div>
	<div id="navs">
		<ul>
			<?php if(is_array($navs)): foreach($navs as $key=>$nav): ?><li><a href="<?php echo u("Index/left",array("key"=>$nav['key']));?>"><?php echo ($nav["name"]); ?></a></li><?php endforeach; endif; ?>
		</ul>
	</div>

	<div id="deal_msg" style="display:none; color:#ccc; font-size:12px; position:absolute; right:115px; top:40px;"><?php echo L("DEAL_MSG_LIST_RUNNING");?></div>
	<div id="promote_msg"  style="display:none; color:#ccc; font-size:12px; position:absolute; right:15px; top:40px;"><?php echo L("PROMOTE_MSG_LIST_RUNNING");?></div>
	<div id="apns_msg"  style="display:none; color:#ccc; font-size:12px; position:absolute; right:15px; top:40px;"><?php echo L("PROMOTE_MSG_LIST_RUNNING");?></div>

</body>
</html>