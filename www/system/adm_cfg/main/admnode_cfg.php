<?php

return array(
    "Admin" => array(
        "name" => "管理员",
        "node" => array(
            "index" => array("name" => "管理员列表", "action" => "index"),
            "insert" => array("name" => "添加", "action" => "insert"),
            "update" => array("name" => "编辑", "action" => "update"),
            "foreverdelete" => array("name" => "删除", "action" => "foreverdelete"),
            "set_default" => array("name" => "设置默认管理员", "action" => "set_default"),
            "set_effect" => array("name" => "设置生效", "action" => "set_effect"),
        )
    ),
    "Role" => array(
        "name" => "角色管理",
        "node" => array(
            "index" => array("name" => "管理员分组列表", "action" => "index"),
            "insert" => array("name" => "添加执行", "action" => "insert"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "foreverdelete" => array("name" => "删除", "action" => "foreverdelete"),
        )
    ),
		"Deal" => array(
				"name" => "商品管理",
				"node" => array(
						"index" => array("name" => "商品列表", "action" => "index"),
						"toogle_status" => array("name" => "状态修改", "action" => "toogle_status"),
						//"edit" => array("name" => "编辑", "action" => "edit"),
						//"add" => array("name" => "添加", "action" => "add"),
						"insert" => array("name" => "添加提交", "action" => "insert"),
						"update" => array("name" => "编辑提交", "action" => "update"),
						"foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
				)
		),
		"DealCate" => array(
				"name" => "分类管理",
				"node" => array(
						"index" => array("name" => "分类列表", "action" => "index"),
						"set_effect" => array("name" => "设置生效", "action" => "set_effect"),
						//"edit" => array("name" => "编辑", "action" => "edit"),
						//"add" => array("name" => "添加", "action" => "add"),
						"insert" => array("name" => "添加提交", "action" => "insert"),
						"update" => array("name" => "编辑提交", "action" => "update"),
						"delete" => array("name" => "删除", "action" => "delete"),
				)
		),
		"Brand" => array(
				"name" => "品牌管理",
				"node" => array(
						"index" => array("name" => "品牌列表", "action" => "index"),
						//"edit" => array("name" => "编辑", "action" => "edit"),
						//"add" => array("name" => "添加", "action" => "add"),
						"insert" => array("name" => "添加提交", "action" => "insert"),
						"update" => array("name" => "编辑提交", "action" => "update"),
						"foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
				)
		),
    "Duobao" => array(
        "name" => "夺宝计划",
        "node" => array(
            "index" => array("name" => "夺宝计划", "action" => "index"),
            "toogle_status" => array("name" => "状态修改", "action" => "toogle_status"),
            //"edit" => array("name" => "编辑", "action" => "edit"),
            //"add" => array("name" => "添加", "action" => "add"),
            "insert" => array("name" => "添加提交", "action" => "insert"),
            "update" => array("name" => "编辑提交", "action" => "update"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    "DuobaoItem" => array(
        "name" => "夺宝活动",
        "node" => array(
            "index" => array("name" => "夺宝计划", "action" => "index"),
            "prepare_lottery" => array("name" => "机器人凑单", "action" => "prepare_lottery"),
            "prepare_lottery_1" => array("name" => "机器人凑单设置", "action" => "prepare_lottery_1"),
            "set_sort" => array("name" => "排序", "action" => "set_sort"),
            "draw_lottery" => array("name" => "人工开奖", "action" => "draw_lottery"),
            "robot_share_add" => array("name" => "机器人晒单", "action" => "robot_share_add"),
            "robot_share_view" => array("name" => "查看机器人晒单", "action" => "robot_share_view"),
            "robot_share_update" => array("name" => "修改机器人晒单", "action" => "robot_share_update"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    "DuobaoItemHistory" => array(
        "name" => "往期夺宝",
        "node" => array(
            "index" => array("name" => "往期夺宝列表", "action" => "index"),
        )
    ),
    "DuobaoOrder" => array(
        "name" => "夺宝订单",
        "node" => array(
            "index" => array("name" => "夺宝订单列表", "action" => "index"),
            "view_order" => array("name" => "查看详情", "action" => "view_order"),
            "trash" => array("name" => "往期夺宝订单", "action" => "trash"),
            "view_order_history" => array("name" => "往期夺宝订单详情", "action" => "view_order_history"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    "EcvType" => array(
        "name" => "红包管理",
        "node" => array(
            "index" => array("name" => "代金券类型列表", "action" => "index"),
            "add" => array("name" => "新增", "action" => "add"),
            "insert" => array("name" => "新增提交", "action" => "insert"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "update" => array("name" => "编辑提交", "action" => "update"),
            "send" => array("name" => "发放", "action" => "send"),
            "view" => array("name" => "查看", "action" => "view"),
            "foreverdelete" => array("name" => "彻底删除", "action" => "foreverdelete"),
        )
    ),
    "RedSet" => array(
        "name" => "拆分红包管理",
        "node" => array(
            "index" => array("name" => "代金券页面", "action" => "index"),
            "update" => array("name" => "编辑", "action" => "update"),
        )
    ),
    "DealOrder" => array(
        "name" => "中奖订单",
        "node" => array(
            "index" => array("name" => "中奖订单列表", "action" => "index"),
            "view_order" => array("name" => "查看详情", "action" => "view_order"),
            "trash" => array("name" => "往期中奖订单", "action" => "trash"),
            "view_order_history" => array("name" => "往期中奖订单详情", "action" => "view_order_history"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    
    "TotalbuyOrder" => array(
        "name" => "直购订单",
        "node" => array(
            "index" => array("name" => "直购订单列表", "action" => "index"),
            "view_order" => array("name" => "查看详情", "action" => "view_order"),
            "delivery" => array("name" => "发货", "action" => "delivery"),
            "do_delivery" => array("name" => "确认发货", "action" => "do_delivery"),
            "check_delivery" => array("name" => "查看快递", "action" => "check_delivery"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    
    "InchargeOrder" => array(
        "name" => "充值订单",
        "node" => array(
            "index" => array("name" => "充值订单列表", "action" => "index"),
            "trash" => array("name" => "往期充值订单", "action" => "trash"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    "PaymentNotice" => array(
        "name" => "收款单",
        "node" => array(
            "index" => array("name" => "收款单列表", "action" => "index"),
        )
    ),
    "User" => array(
        "name" => "会员",
        "node" => array(
              
            "modify_batch_add" => array("name" => "执行批量添加机器人", "action" => "modify_batch_add"),
            "delete" => array("name" => "删除", "action" => "delete"),
            "edit" => array("name" => "编辑页面", "action" => "edit"),
            "export_csv" => array("name" => "导出csv", "action" => "export_csv"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "foreverdelete_account_detail" => array("name" => "永久删除帐户详情", "action" => "foreverdelete_account_detail"),
            "index" => array("name" => "会员列表", "action" => "index"),
            "insert" => array("name" => "添加执行", "action" => "insert"),
            "modify_account" => array("name" => "更新账户金额，积分，经验", "action" => "modify_account"),
            "restore" => array("name" => "恢复", "action" => "restore"),
            "set_effect" => array("name" => "设置生效", "action" => "set_effect"),
            "trash" => array("name" => "会员回收站", "action" => "trash"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "withdrawal_index" => array("name" => "会员提现列表", "action" => "withdrawal_index"),
            "withdrawal_edit" => array("name" => "会员提现弹出框", "action" => "withdrawal_edit"),
            "do_withdrawal" => array("name" => "会员提现审核", "action" => "do_withdrawal"),
            "del_withdrawal" => array("name" => "会员提现记录删除", "action" => "del_withdrawal"),
        )
    ),
    "UserGroup" => array(
        "name" => "会员组别",
        "node" => array(
            "index" => array("name" => "会员组别列表", "action" => "index"),
            "insert" => array("name" => "添加执行", "action" => "insert"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    "UserLevel" => array(
        "name" => "会员等级",
        "node" => array(
            "index" => array("name" => "会员等级列表", "action" => "index"),
            "insert" => array("name" => "添加提交", "action" => "insert"),
            "update" => array("name" => "编辑提交", "action" => "update"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    "Referrals" => array(
        "name" => "邀请返利",
        "node" => array(
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "index" => array("name" => "邀请返利列表", "action" => "index"),
            "pay" => array("name" => "发放返利", "action" => "pay"),
        )
    ),
    "MsgSystem" => array(
        "name" => "站内消息",
        "node" => array(
            "add" => array("name" => "新增", "action" => "add"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "insert" => array("name" => "新增执行", "action" => "insert"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    "MsgSystem" => array(
        "name" => "晒单管理",
        "node" => array(
            "index" => array("name" => "晒单列表", "action" => "index"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "delete" => array("name" => "删除晒单", "action" => "delete"),
            "toogle_status" => array("name" => "状态修改", "action" => "toogle_status"),
        )
    ),
    "MsgBroadcast" => array(
        "name" => "消息推送列表",
        "node" => array(
            "index" => array("name" => "邀请返利列表", "action" => "index"),
            "add" => array("name" => "新增", "action" => "add"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "insert" => array("name" => "新增执行", "action" => "insert"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "view" => array("name" => "查看", "action" => "view"),
            "appbroadcast" => array("name" => "执行推送计划", "action" => "appbroadcast"),
            "get_push_model" => array("name" => "执行推送计划", "action" => "get_push_model"),
            "get_user_info" => array("name" => "执行推送计划", "action" => "get_user_info"),
        )
    ),
    "Conf" => array(
        "name" => "系统配置",
        "node" => array(
            "index" => array("name" => "系统配置", "action" => "index"),
            "update" => array("name" => "更新配置", "action" => "update"),
            "mobile" => array("name" => "手机端配置", "action" => "mobile"),
            "savemobile" => array("name" => "保存手机端配置", "action" => "savemobile"),
            "news" => array("name" => "手机端公告", "action" => "news"),
            "insertnews" => array("name" => "添加手机端公告", "action" => "insertnews"),
            "updatenews" => array("name" => "编辑手机端公告", "action" => "updatenews"),
            "foreverdelete" => array("name" => "删除公告", "action" => "foreverdelete"),
        )
    ),
    "Agreement" => array(
        "name" => "公告与服务协议",
        "node" => array(
            "index" => array("name" => "公告与服务协议列表", "action" => "index"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "insert" => array("name" => "执行添加", "action" => "insert"),
            "update" => array("name" => "执行更新", "action" => "update"),
            "set_effect" => array("name" => "设置有效性", "action" => "set_effect"),
            "send_demo" => array("name" => "发送邮件", "action" => "send_demo"),
            "set_sort" => array("name" => "设置排序", "action" => "set_sort"),
        )
    ),
    "FxSalary" => array(
        "name" => "营销管理",
        "node" => array(
            "index" => array("name" => "全局邀请推广奖设置", "action" => "index"),
            "save" => array("name" => "执行保存设置", "action" => "save"),
        )
    ),
    "Article" => array(
        "name" => "手机端帮助列表",
        "node" => array(
            "index" => array("name" => "全局邀请推广奖设置", "action" => "index"),
            "add" => array("name" => "新增", "action" => "add"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "insert" => array("name" => "新增执行", "action" => "insert"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "set_sort" => array("name" => "设置排序", "action" => "set_sort"),
            "set_effect" => array("name" => "设置生效", "action" => "set_effect"),
        )
    ),
    "ArticleCate" => array(
        "name" => "文章分类列表",
        "node" => array(
            "index" => array("name" => "文章分类列表", "action" => "index"),
            "add" => array("name" => "新增", "action" => "add"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "insert" => array("name" => "新增执行", "action" => "insert"),
            "set_sort" => array("name" => "设置排序", "action" => "set_sort"),
            "set_effect" => array("name" => "设置生效", "action" => "set_effect"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    "WebArticle" => array(
        "name" => "文章分类列表",
        "node" => array(
            "index" => array("name" => "文章分类列表", "action" => "index"),
            "add" => array("name" => "新增", "action" => "add"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "insert" => array("name" => "新增执行", "action" => "insert"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "set_sort" => array("name" => "设置排序", "action" => "set_sort"),
            "set_effect" => array("name" => "设置生效", "action" => "set_effect"),
        )
    ),
    "WebArticleCate" => array(
        "name" => "文章标题",
        "node" => array(
            "index" => array("name" => "文章标题", "action" => "index"),
            "add" => array("name" => "新增", "action" => "add"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "insert" => array("name" => "新增执行", "action" => "insert"),
            "set_sort" => array("name" => "设置排序", "action" => "set_sort"),
            "set_effect" => array("name" => "设置生效", "action" => "set_effect"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    "FxUser" => array(
        "name" => "分销会员",
        "node" => array(
            "index" => array("name" => "分销会员列表", "action" => "index"),
            "get_scan" => array("name" => "渠道二维码", "action" => "get_scan"),
            "edit_remark" => array("name" => "渠道标注", "action" => "edit_remark"),
            "update_remark" => array("name" => "执行添加", "action" => "update_remark"),
            "edit_referrer" => array("name" => "执行更新", "action" => "edit_referrer"),
            "update_referrer" => array("name" => "执行修改推荐人", "action" => "update_referrer"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "set_effect" => array("name" => "设置分销状态", "action" => "set_effect"),
            "save" => array("name" => "保存", "action" => "save"),
            "money_log" => array("name" => "查看分销资金日志", "action" => "money_log"),
            "log_delete" => array("name" => "删除分销资金日志", "action" => "log_delete"),
            "close_fx_all" => array("name" => "开启分销渠道", "action" => "close_fx_all"),
            "open_fx_all" => array("name" => "关闭分销渠道", "action" => "open_fx_all"),
        )
    ),
    "MAdv" => array(
        "name" => "移动平台设置",
        "node" => array(
            "index" => array("name" => "手机广告列表", "action" => "index"),
            "insert" => array("name" => "执行添加", "action" => "insert"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "foreverdelete" => array("name" => "删除", "action" => "foreverdelete"),
            "set_sort" => array("name" => "设置排序", "action" => "set_sort"),
        )
    ),
    "MNav" => array(
        "name" => "手机导航标签",
        "node" => array(
            "index" => array("name" => "手机导航标签", "action" => "index"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "update" => array("name" => "编辑执行", "action" => "update"),
        )
    ),
    "WeixinAccount" => array(
        "name" => "微信账号",
        "node" => array(
            "index" => array("name" => "微信公众号配置", "action" => "index"),
            "wx_templ" => array("name" => "行业模版", "action" => "wx_templ"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "nav_setting" => array("name" => "自定义菜单", "action" => "nav_setting"),
            "nav_save" => array("name" => "添加主菜单", "action" => "nav_save"),
            "new_nav_row" => array("name" => "添加子菜单", "action" => "new_nav_row"),
            "syn_to_weixin" => array("name" => "同步到微信公众平台", "action" => "syn_to_weixin"),
            "syn_industry" => array("name" => "同步行业设置", "action" => "syn_industry"),
            "syn_template" => array("name" => "同步消息模板", "action" => "syn_template"),
            "del_template" => array("name" => "删除消息模板", "action" => "del_template"),
            "send_test_template" => array("name" => "发送测试模板", "action" => "send_test_template"),
        )
    ),
    "WeixinReplyAccount" => array(
        "name" => "微信账号配置",
        "node" => array(
            "index" => array("name" => "默认回复设置", "action" => "index"),
            "save_dtext" => array("name" => "保存默认文本回复", "action" => "save_dtext"),
            "ajaxnews" => array("name" => "编辑执行", "action" => "ajaxnews"),
            "save_dnews" => array("name" => "执行保存默认回复", "action" => "save_dnews"),
            "onfocus" => array("name" => "关注时回复", "action" => "onfocus"),
            "save_onfocus" => array("name" => "同步到微信公众平台", "action" => "save_onfocus"),
            "onfocusn" => array("name" => "同步行业设置", "action" => "onfocusn"),
            "save_onfocusn" => array("name" => "同步消息模板", "action" => "save_onfocusn"),
            "txt" => array("name" => "文本回复", "action" => "txt"),
            "edittext" => array("name" => "编辑文本回复", "action" => "edittext"),
            "save_text" => array("name" => "新增/修改文本回复", "action" => "save_text"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "news" => array("name" => "图文回复列表", "action" => "news"),
            "editnews" => array("name" => "添加/编辑图文回复", "action" => "editnews"),
            "save_news" => array("name" => "保存图文回复", "action" => "save_news"),
            "lbs" => array("name" => "LBS回复", "action" => "lbs"),
            "editlbs" => array("name" => "添加/编辑lbs回复", "action" => "editlbs"),
            "save_lbs" => array("name" => "保存lbs图文回复", "action" => "save_lbs"),
        )
    ),
    "Database" => array(
        "name" => "数据库",
        "node" => array(
            "delete" => array("name" => "删除备份", "action" => "delete"),
            "dump" => array("name" => "备份数据", "action" => "dump"),
            "execute" => array("name" => "执行SQL语句", "action" => "execute"),
            "index" => array("name" => "数据库备份列表", "action" => "index"),
            "restore" => array("name" => "恢复备份", "action" => "restore"),
            "sql" => array("name" => "SQL操作", "action" => "sql"),
        )
    ),
    "Log" => array(
        "name" => "系统日志",
        "node" => array(
            "index" => array("name" => "系统日志列表", "action" => "index"),
            "coupon" => array("name" => "第三方验证日志", "action" => "coupon"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "foreverdeletelog" => array("name" => "永久删除第三方验证日志", "action" => "foreverdeletelog"),
        )
    ),
    "ApiLogin" => array(
        "name" => "API登录",
        "node" => array(
            "index" => array("name" => "API插件列表", "action" => "index"),
            "insert" => array("name" => "API插件安装", "action" => "insert"),
            "update" => array("name" => "API插件编辑", "action" => "update"),
            "uninstall" => array("name" => "API插件卸载", "action" => "uninstall"),
        )
    ),
    "Integrate" => array(
        "name" => "会员整合",
        "node" => array(
            "index" => array("name" => "会员整合插件", "action" => "index"),
            "install" => array("name" => "安装页面", "action" => "install"),
            "save" => array("name" => "保存", "action" => "save"),
            "uninstall" => array("name" => "卸载", "action" => "uninstall"),
        )
    ),
    "MIndex" => array(
        "name" => "手机端首页菜单",
        "node" => array(
            "index" => array("name" => "首页菜单列表", "action" => "index"),
            "insert" => array("name" => "添加提交", "action" => "insert"),
            "update" => array("name" => "编辑提交", "action" => "update"),
            "foreverdelete" => array("name" => "删除菜单", "action" => "foreverdelete"),
        )
    ),
    "Nav" => array(
        "name" => "导航菜单",
        "node" => array(
            "index" => array("name" => "导航菜单列表", "action" => "index"),
            "insert" => array("name" => "添加执行", "action" => "insert"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "set_effect" => array("name" => "设置生效", "action" => "set_effect"),
            "set_sort" => array("name" => "排序", "action" => "set_sort"),
        )
    ),
    "Adv" => array(
        "name" => "广告模块",
        "node" => array(
            "index" => array("name" => "广告列表", "action" => "index"),
            "save" => array("name" => "保存", "action" => "save"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "set_effect" => array("name" => "设置生效", "action" => "set_effect"),
        )
    ),
    "DeliveryRegion" => array(
        "name" => "配送地区列表",
        "node" => array(
            "index" => array("name" => "配送地区列表", "action" => "index"),
            "add" => array("name" => "新增", "action" => "add"),
            "insert" => array("name" => "新增执行", "action" => "insert"),
            "edit" => array("name" => "编辑", "action" => "edit"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
        )
    ),
    "MMore" => array(
        "name" => "手机端发现列表",
        "node" => array(
            "index" => array("name" => "发现列表", "action" => "index"),
            "save" => array("name" => "保存", "action" => "save"),
            "foreverdelete" => array("name" => "永久删除", "action" => "foreverdelete"),
            "set_effect" => array("name" => "设置生效", "action" => "set_effect"),
        )
    ),

    "Payment" => array(
        "name" => "支付方式",
        "node" => array(
            "index" => array("name" => "支付接口列表", "action" => "index"),
            "insert" => array("name" => "安装保存", "action" => "insert"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "uninstall" => array("name" => "卸载", "action" => "uninstall"),
        )
    ),
    "MsgTemplate" => array(
        "name" => "计划任务",
        "node" => array(
            "index" => array("name" => "消息模板管理", "action" => "index"),
            "update" => array("name" => "编辑执行", "action" => "update"),
        )
    ),
    "Sms" => array(
        "name" => "短信接口",
        "node" => array(
            "index" => array("name" => "短信接口列表", "action" => "index"),
            "insert" => array("name" => "安装保存", "action" => "insert"),
            "update" => array("name" => "编辑执行", "action" => "update"),
            "install" => array("name" => "安装", "action" => "install"),
            "uninstall" => array("name" => "卸载", "action" => "uninstall"),
            "send_demo" => array("name" => "发送测试短信", "action" => "send_demo"),
            "set_effect" => array("name" => "设置生效", "action" => "set_effect"),
        )
    ),
   
    "Balance" => array(
        "name" => "报表",
        "node" => array(
            "foreverdelete" => array("name" => "删除", "action" => "foreverdelete"),
            "index" => array("name" => "统计报表", "action" => "index"),
            "export_excel" => array("name" => "导出Excel", "action" => "export_excel"),
        )
    ),
);
?>