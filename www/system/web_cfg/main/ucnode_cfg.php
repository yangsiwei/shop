<?php 
return array(
			"OrderManager"	=>	array(
					"name"	=>	"订单中心",
					"node"	=>	array(
							"uc_duobao"=>array("name"=>"夺宝记录","module"=>"uc_duobao","action"=>"index"),
						  	"uc_luck"=>array("name"=>"幸运记录","module"=>"uc_luck","action"=>"index"),
//					        "uc_totalbuy"=>array("name"=>"购买记录","module"=>"uc_totalbuy","action"=>"index"),
					)
			),
			"Bills"	=>	array(
					"name"	=>	"账户中心",
					"node"	=>	array(
							"uc_account"=>array("name"=>"账户设置","module"=>"uc_account","action"=>"index"),
							"uc_log"=>array("name"=>"我的资产","module"=>"uc_log","action"=>"index"),  //关于账户的余额，积分，经验，uc的兑换日志(index:余额，score:积分,exp:经验,exchange:uc兑换)
							// "uc_voucher"=>array("name"=>"我的红包","module"=>"uc_voucher","action"=>"index"),
					        "uc_share"=>array("name"=>"我的晒单","module"=>"uc_share","action"=>"index"),
							"uc_msg"=>array("name"=>"我的消息","module"=>"uc_msg","action"=>"index"),
							"uc_money"=>array("name"=>"立即充值","module"=>"uc_money","action"=>"incharge"),
					        "uc_money_cash"=>array("name"=>"申请提现","module"=>"uc_money_cash","action"=>"withdraw"),
							"uc_consignee"=>array("name"=>"配送地址","module"=>"uc_consignee","action"=>"index"),		
					        "uc_fx"=>array("name"=>"我的团队","module"=>"uc_fx","action"=>"index"),
					        "uc_invite"=>array("name"=>"邀请链接","module"=>"uc_invite","action"=>"index"),
					)
			),
		
		);
				
?>