<?php
// +----------------------------------------------------------------------
// | Fanwe 方维系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// 前端可配置的导航菜单
// +----------------------------------------------------------------------

return array(

		"web"=>array(
				"name"=>"web端",
				"mobile_type"=>2,
				"nav"=>array(
						"url" => array(
								"name"	=>	"自定义url",
								"type"	=>	"0",
								"fname"	=>	"url地址",
								"field"	=>	"url",
						),
						"channel" => array(
								"name"	=>	"频道列表",
								"type"	=>	"11",
								"fname"	=>	"频道ID",
								"field"	=>	"cate_id",
						),
						"goods" => array(
								"name"	=>	"项目列表",
								"type"	=>	"12",
								"fname"	=>	"分类ID",
								"field"	=>	"cate_id",
						),
						"scores" => array(
								"name"	=>	"积分商品列表",
								"type"	=>	"13",
								"fname"	=>	"分类ID",
								"field"	=>	"cate_id",
						),
						"stores" => array(
								"name"	=>	"门店列表",
								"type"	=>	"14",
								"fname"	=>	"分类ID",
								"field"	=>	"cate_id",
						),
						"staffs" => array(
								"name"	=>	"服务人员列表",
								"type"	=>	"15",
								"fname"	=>	"分类ID",
								"field"	=>	"cate_id",
						),
						"notices" => array(
								"name"	=>	"公告列表",
								"type"	=>	"17",
								"fname"	=>	"",
								"field"	=>	"",
						),
						"deal" => array(
								"name"	=>	"购物明细",
								"type"	=>	"21",
								"fname"	=>	"数据ID",
								"field"	=>	"data_id",
						),
						"store" => array(
								"name"	=>	"门店明细",
								"type"	=>	"24",
								"fname"	=>	"数据ID",
								"field"	=>	"data_id",
						),
						"staff" => array(
								"name"	=>	"服务人员明细",
								"type"	=>	"25",
								"fname"	=>	"数据ID",
								"field"	=>	"data_id",
						),
						"notice" => array(
								"name"	=>	"公告详细",
								"type"	=>	"27",
								"fname"	=>	"数据ID",
								"field"	=>	"data_id",
						),
						"uc_fx"	=>	array(
								"name"	=>	"推广中心",
								"type"	=>	"41",
								"fname"	=>	"",
								"field"	=>	"",
						),
						"home" => array(
								"name"	=>	"商户首页",
								"type"	=>	"51",
								"fname"	=>	"商户ID",
								"field"	=>	"spid",
						),
						"discover" => array(
								"name"	=>	"发现",
								"type"	=>	"61",
								"fname"	=>	"",
								"field"	=>	"",
						),
							
				)
		),
    
);
?>