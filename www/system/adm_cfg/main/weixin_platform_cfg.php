<?php 
return array( 	
	"mobile"	=>	array(
			"name"	=>	"移动平台",
			"key"	=>	"mobile",
			"groups"	=>	array(				
				"weixinconf"	=>	array(
						"name"	=>	"微信平台",
						"key"	=>	"WeixinConf",
						"nodes"	=>	array(
								array("name"=>"公众号开放平台","module"=>"WeixinAccount","action"=>"index"),
								array("name"=>"公众号消息","module"=>"WeixinTemplate","action"=>"index"),
// 								array("name"=>"平台公众号","module"=>"WeixinInfo","action"=>"index"),
				
						),
				),
// 				"weixininfo"	=>	array(
// 						"name"	=>	"微信配置",
// 						"key"	=>	"weixininfo",
// 						"nodes"	=>	array(
// 								array("name"=>"自定义菜单","module"=>"WeixinInfo","action"=>"nav_setting"),
// 								array("name"=>"默认回复设置","module"=>"WeixinReply","action"=>"index"),
// 								array("name"=>"关注时回复","module"=>"WeixinReply","action"=>"onfocus"),
// 								array("name"=>"文本回复","module"=>"WeixinReply","action"=>"txt"),
// 								array("name"=>"图文回复","module"=>"WeixinReply","action"=>"news"),
// 								array("name"=>"LBS回复","module"=>"WeixinReply","action"=>"lbs"),
				
// 						),
// 				),
					
			),
	),
	
);
?>