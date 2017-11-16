<?php 
return array( 
	    //fx 分销模块 -------start---------------------------	
	"FxStatement"	=>	array(
			"name"	=>	"分销报表",
			"node"	=>	array(
					"index"	=>	array("name"=>"报表查看","action"=>"index"),
					"foreverdelete"	=>	array("name"=>"删除报表","action"=>"foreverdelete"),
			)
	),
		
    "Fxsalary"	=>	array(
        "name"	=>	"分销设置",
        "node"	=>	array(
            "index"	=>	array("name"=>"全局分销推广奖设置","action"=>"index"),
            "level_index"	=>	array("name"=>"会员等级推广奖设置","action"=>"level_index"),
            "add_level"	=>	array("name"=>"添加分销等级","action"=>"add_level"),
            "edit_level"	=>	array("name"=>"编辑分销等级","action"=>"edit_level"),
            "deal_index"	=>	array("name"=>"分销商品推广奖设置","action"=>"deal_index"),
            "add_deal"	=>	array("name"=>"添加分销商品","action"=>"add_deal"),
            "save"	=>	array("name"=>"保存分销数据","action"=>"save"),
            "save_1"	=>	array("name"=>"保存全局分销推广奖设置","action"=>"save_1"),
            "save_2"	=>	array("name"=>"保存会员等级推广奖设置","action"=>"save_2"),
            "save_3"	=>	array("name"=>"保存分销商品推广奖设置","action"=>"save_3"),
        )
    ),
    
    //fx 分销模块 -------end---------------------------
    
);
?>