<?php 
return array(
//fx 营销菜单位于订单菜单后面
	"marketing"	=>	array(
			"name"	=>	"营销管理",
			"key"	=>	"marketing",
			"groups"	=>	array(
					"fx"	=>	array(
							"name"	=>	"分销管理",
							"key"	=>	"fx",
							"nodes"	=>	array(
								array("name"=>"分销设置","module"=>"FxSalary","action"=>"index"),
							    array("name"=>"分销会员","module"=>"FxUser","action"=>"index"),
							    array("name"=>"分销等级","module"=>"FxLevel","action"=>"index"),
							    array("name"=>"充值管理","module"=>"Recharge","action"=>"index"),
							    array("name"=>"提现管理","module"=>"Withdraw","action"=>"index"),
							    array("name"=>"支付方式","module"=>"Payment_shan","action"=>"index"),
							    array("name"=>"首页置顶商品","module"=>"Top_goods","action"=>"index"),
							    array("name"=>"消费级别场","module"=>"Consume_level","action"=>"index"),
							),
					),
			),
	),
);
?>