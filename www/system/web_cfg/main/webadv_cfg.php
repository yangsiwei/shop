<?php
// +----------------------------------------------------------------------
// | Fanwe 方维系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// web平台广告位设置的选项
// +----------------------------------------------------------------------

return array(
	"index|index#index"=>array(
		"page_name" => "平台首页",
		"groups" => array(
			array(
				"group" => "slide",
				"name" => "主轮播广告",
			),
			array(
					"group" => "cate_banner",
					"name" => "分类页面",
			),
		)		
	), 
	"index|duobaost#index"=>array(
			"page_name" => "十元专区",
			"groups" => array(
					array(
							"group" => "slide",
							"name" => "主轮播广告",
					),
			)
	),
	"index|uc_money#incharge"=>array(
			"page_name" => "会员充值页面",
			"groups" => array(
					array(
							"group" => "slide",
							"name" => "主广告",
					),
			)
	),
	
	"index|uc_center#index"=>array(
			"page_name" => "我的夺宝",
			"groups" => array(
					array(
							"group" => "uc_center",
							"name" => "右侧广告",
					)
			)
	),
	
	"index|duobaozg#index"=>array(
	    "page_name" => "直购专区",
	    "groups" => array(
	        array(
	            "group" => "slide",
	            "name" => "主广告",
	        ),
	    )
	),
	
	"siteroot"=>array(
			"page_name" => "全站",
			"groups" => array(
					array(
							"group" => "header_adv",
							"name" => "头部广告位",
					),
					array(
							"group" => "footer_adv",
							"name" => "底部广告位",
					)
			)
	),
);
?>