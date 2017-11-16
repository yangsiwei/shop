<?php 
return array( 
	"index"	=>	array(
		"name"	=>	"首页", 
		"key"	=>	"index", 
		"groups"	=>	array( 
			"index"	=>	array(
				"name"	=>	"首页", 
				"key"	=>	"index", 
				"nodes"	=>	array( 
					array("name"=>"首页","module"=>"Index","action"=>"main"),
				),
			),
			"balance"	=>	array(
				"name"	=>	"报表",
				"key"	=>	"balance",
				"nodes"	=>	array(
						array("name"=>"报表统计","module"=>"Balance","action"=>"index"),
				        array("name"=>"收益统计","module"=>"IncomeBalance","action"=>"index")
				),
			),
		),
	),
	"deal"	=>	array(
		"name"	=>	"项目管理", 
		"key"	=>	"deal", 
		"groups"	=>	array( 			
			"deal"	=>	array(
				"name"	=>	"商品维护", 
				"key"	=>	"deal", 
				"nodes"	=>	array( 
					array("name"=>"商品管理","module"=>"Deal","action"=>"index"),
					array("name"=>"分类管理","module"=>"DealCate","action"=>"index"),
					array("name"=>"品牌管理","module"=>"Brand","action"=>"index"),
				),
			),			
			"duobao"	=>	array(
					"name"	=>	"夺宝活动",
					"key"	=>	"duobao",
					"nodes"	=>	array(
							array("name"=>"夺宝计划","module"=>"Duobao","action"=>"index"),
							array("name"=>"夺宝活动","module"=>"DuobaoItem","action"=>"index"),
							//array("name"=>"往期夺宝","module"=>"DuobaoItemHistory","action"=>"index"),
					),
			),
		),
	),
	"order"	=>	array(
			"name"	=>	"订单管理",
			"key"	=>	"order",
			"groups"	=>	array(
					"order"	=>	array(
							"name"	=>	"订单管理",
							"key"	=>	"order",
							"nodes"	=>	array(
									array("name"=>"夺宝订单","module"=>"DuobaoOrder","action"=>"index"),
								 
									array("name"=>"中奖订单","module"=>"DealOrder","action"=>"index"),
								
							        array("name"=>"直购订单","module"=>"TotalbuyOrder","action"=>"index"),
							    
									array("name"=>"充值订单","module"=>"InchargeOrder","action"=>"index"),
							    
							        array("name"=>"免费购订单","module"=>"FreebuyOrder","action"=>"index"),
								 
									array("name"=>"收款单列表","module"=>"PaymentNotice","action"=>"index"),
									 
							),
					),					
					"orderinterface"	=>	array(
							"name"	=>	"交易相关业务",
							"key"	=>	"orderinterface",
							"nodes"	=>	array(
									array("name"=>"支付设置","module"=>"Payment","action"=>"index"),
									array("name"=>"红包管理","module"=>"EcvType","action"=>"index"),
							        array("name"=>"拆分红包设置","module"=>"RedSet","action"=>"index"),
							),
					),
// 					"delivery"	=>	array(
// 							"name"	=>	"配送方式",
// 							"key"	=>	"delivery",
// 							"nodes"	=>	array(
// 									array("name"=>"配送地区列表","module"=>"DeliveryRegion","action"=>"index"),									
// 							),
// 					),
					
			),
	),		
	
	"user"	=>	array(
			"name"	=>	"会员管理",
			"key"	=>	"user",
			"groups"	=>	array(
					"user"	=>	array(
							"name"	=>	"会员管理",
							"key"	=>	"user",
							"nodes"	=>	array(
									array("name"=>"会员列表","module"=>"User","action"=>"index"),
									array("name"=>"会员提现","module"=>"User","action"=>"withdrawal_index"),
							),
					),								
					"notice"	=>	array(
							"name"	=>	"站内消息",
							"key"	=>	"notice",
							"nodes"	=>	array(
									array("name"=>"消息群发","module"=>"MsgSystem","action"=>"index"),
									array("name"=>"消息列表","module"=>"MsgBox","action"=>"index"),
							),
					),					
					"msgadmin"	=>	array(
							"name"	=>	"会员晒单",
							"key"	=>	"msgadmin",
							"nodes"	=>	array(
									array("name"=>"晒单管理","module"=>"Share","action"=>"index"),	
							),
					),
                    "msgbroadcast" => array(
                            "name" => "app推送消息",
                            "key"  => "msgbroadcast",
                            "nodes" =>array(
                                array("name"=>"消息推送","module"=>"MsgBroadcast","action"=>"index"),
                            )
                    )
			),
	),	
	"promote"	=>	array(
		"name"	=>	"计划任务", 
		"key"	=>	"promote", 
		"groups"	=>	array( 
			"msg"	=>	array(
				"name"	=>	"消息模板管理", 
				"key"	=>	"msg", 
				"nodes"	=>	array( 
					array("name"=>"消息模板管理","module"=>"MsgTemplate","action"=>"index"),
				),
			),
		"mail"	=>	array(
				"name"	=>	"邮件管理", 
				"key"	=>	"mail", 
				"nodes"	=>	array( 
					array("name"=>"邮件服务器列表","module"=>"MailServer","action"=>"index"),
 					array("name"=>"邮件列表","module"=>"PromoteMsg","action"=>"mail_index"),
				),
 			),
			"sms"	=>	array(
				"name"	=>	"短信管理", 
				"key"	=>	"sms", 
				"nodes"	=>	array( 
					array("name"=>"短信接口列表","module"=>"Sms","action"=>"index"),
				),
			),
			"msglist"	=>	array(
				"name"	=>	"计划任务", 
				"key"	=>	"msglist", 
				"nodes"	=>	array( 
					array("name"=>"计划任务列表","module"=>"ScheduleList","action"=>"index"),
					array("name"=>"第三方开奖结果","module"=>"FairFetch","action"=>"index"),
				),
			),
		),
	),		
	"mobile"	=>	array(
			"name"	=>	"移动平台",
			"key"	=>	"mobile",
			"groups"	=>	array(						
				"mobile"	=>	array(
					"name"	=>	"移动平台设置", 
					"key"	=>	"mobile", 
					"nodes"	=>	array( 
						array("name"=>"App端配置","module"=>"Conf","action"=>"mobile"),
						//array("name"=>"手机端专题位","module"=>"MZt","action"=>"index"),
						array("name"=>"手机端导航标签","module"=>"MNav","action"=>"index"),
						array("name"=>"手机端广告列表","module"=>"MAdv","action"=>"index"),
						array("name"=>"首页菜单列表","module"=>"MIndex","action"=>"index"),
                        array("name"=>"发现列表","module"=>"MMore","action"=>"index"),
					),
				),					
			),
	),
	"system"	=>	array(
		"name"	=>	"系统设置", 
		"key"	=>	"system", 
		"groups"	=>	array( 
			"sysconf"	=>	array(
				"name"	=>	"系统设置", 
				"key"	=>	"sysconf", 
				"nodes"	=>	array( 
					array("name"=>"系统配置","module"=>"Conf","action"=>"index"),
					array("name"=>"导航菜单","module"=>"Nav","action"=>"index"),
					array("name"=>"广告设置","module"=>"Adv","action"=>"index"),
					array("name"=>"配送地区列表","module"=>"DeliveryRegion","action"=>"index"),
					array("name"=>"公告与服务协议","module"=>"Agreement","action"=>"index"),
						
				),
			),
			"article"	=>	array(
					"name"	=>	"站点帮助",
					"key"	=>	"article",
					"nodes"	=>	array(
						array("name"=>"手机端帮助列表","module"=>"Article","action"=>"index"),
						array("name"=>"手机端帮助分类","module"=>"ArticleCate","action"=>"index"),
						array("name"=>"网页端帮助列表","module"=>"WebArticle","action"=>"index"),
						array("name"=>"网页端帮助分类","module"=>"WebArticleCate","action"=>"index"),
					),
			),				
			"admin"	=>	array(
				"name"	=>	"系统管理员", 
				"key"	=>	"admin", 
				"nodes"	=>	array( 
					array("name"=>"角色管理","module"=>"Role","action"=>"index"),
					array("name"=>"管理员管理","module"=>"Admin","action"=>"index"),
				),
			),
			"datebase"	=>	array(
				"name"	=>	"数据库", 
				"key"	=>	"datebase", 
				"nodes"	=>	array( 
					array("name"=>"数据库备份","module"=>"Database","action"=>"index"),
					array("name"=>"SQL操作","module"=>"Database","action"=>"sql"),
				),
			),
			"syslog"	=>	array(
				"name"	=>	"系统日志", 
				"key"	=>	"syslog", 
				"nodes"	=>	array( 
					array("name"=>"系统日志列表","module"=>"Log","action"=>"index"),
					
				),
			),
			
		),
	),
);
?>