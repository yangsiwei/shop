<?php
// +----------------------------------------------------------------------
// | Fanwe 方维系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// 前端可配置的导航菜单
// +----------------------------------------------------------------------

return array(

		
		"wap"=>array(
			"name"=>"Wap端",
			"mobile_type"=>1,
			"nav"=>array(
				"url" => array(
					"name"	=>	"自定义url",
					"type"	=>	"0",
					"fname"	=>	"url地址",
					"field"	=>	"url",
				),
				"cate" => array(
					"name"	=>	"分类页",
					"type"	=>	"1",
					"fname"	=>	"",
					"field"	=>	"",
				),
				"duobaos" => array(
					"name"	=>	"夺宝列表",
					"type"	=>	"2",
					"fname"	=>	"分类ID",
					"field"	=>	"cate_id",
				),				
				"anno" => array(
						"name"	=>	"揭晓列表",
						"type"	=>	"3",
						"fname"	=>	"分类ID",
						"field"	=>	"cate_id",
				),					
				"duobao" => array(
					"name"	=>	"明细页",
					"type"	=>	"4",
					"fname"	=>	"数据ID",
					"field"	=>	"data_id",
				),					
				"helps" => array(
						"name"	=>	"帮助列表",
						"type"	=>	"5",
						"fname"	=>	"",
						"field"	=>	"",
				),	
				"helps#show" => array(
						"name"	=>	"帮助明细页",
						"type"	=>	"6",
						"fname"	=>	"数据ID",
						"field"	=>	"data_id",
				),
				"duobaost" => array(
						"name"	=>	"10元区",
						"type"	=>	"7",
						"fname"	=>	"",
						"field"	=>	"",
				),
                "topspeed" => array(
                    "name"	=>	"极速专区",
                    "type"	=>	"11",
                    "fname"	=>	"",
                    "field"	=>	"",
                ),
				"duobaosh" => array(
						"name"	=>	"百元区",
						"type"	=>	"8",
						"fname"	=>	"",
						"field"	=>	"",
				),
			    "duobaozg" => array(
			        "name"	=>	"直购区",
			        "type"	=>	"10",
			        "fname"	=>	"",
			        "field"	=>	"",
			    ),
			    "coupons" => array(
			        "name"	=>	"免费购",
			        "type"	=>	"12",
			        "fname"	=>	"",
			        "field"	=>	"",
			    ),
			    "share" => array(
			        "name"	=>	"晒单分享",
			        "type"	=>	"9",
			        "fname"	=>	"",
			        "field"	=>	"",
			    ),
                "number_choose"=>array(
                    "name"  =>  "选号专区",
                    "type"  =>  "13",
                    "fname" =>  "",
                    "field" =>  "",
                ),
                "pk"=>array(
                    "name"  =>  "pk专区",
                    "type"  =>  "14",
                    "fname" =>  "",
                    "field" =>  "",
                ),
			    
			    "more"=>array(
			        "name"  =>  "发现列表",
			        "type"  =>  "15",
			        "fname" =>  "",
			        "field" =>  "",
			    ),
                 "duobaof"=>array(
                    "name"  =>  "五倍专区",
                    "type"  =>  "16",
                    "fname" =>  "",
                    "field" =>  "",
                )			    
			
			)
		)
    
);
?>