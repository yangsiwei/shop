<?php 
return array( 	
	"mobile"	=>	array(
			"name"	=>	"移动平台",
			"key"	=>	"mobile",
			"groups"	=>	array(	
				"weixinaccount"	=>	array(
					"name"	=>	"微信账号",
					"key"	=>	"WeixinAccount",
					"nodes"	=>	array(
 						array("name"=>"平台公众号","module"=>"WeixinAccount","action"=>"index"),
						array("name"=>"行业模板","module"=>"WeixinAccount","action"=>"wx_templ"),
					),
				),
				"weixininfopz"	=>	array(
					"name"	=>	"微信账号配置",
					"key"	=>	"weixininfopz",
					"nodes"	=>	array(
						array("name"=>"自定义菜单","module"=>"WeixinAccount","action"=>"nav_setting"),
						array("name"=>"默认回复设置","module"=>"WeixinReplyAccount","action"=>"index"),
						array("name"=>"关注时回复","module"=>"WeixinReplyAccount","action"=>"onfocus"),
						array("name"=>"文本回复","module"=>"WeixinReplyAccount","action"=>"txt"),
						array("name"=>"图文回复","module"=>"WeixinReplyAccount","action"=>"news"),
						array("name"=>"LBS回复","module"=>"WeixinReplyAccount","action"=>"lbs"),

					),
				),
					
			),
	),
	
);
?>