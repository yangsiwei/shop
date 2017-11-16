<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class delivery_regionApiModule extends MainBaseApiModule
{
	
	/**
	 * 获取所有的配送地区的数据集
	 * 输入：
	 * 无
	 * 
	 * 
	 * 输出：
	 * region_list:array 地区列表
	 * Array(
	 * 	Array(
	 * 		"id"=> int 地区ID,
	 * 		"pid" => int父级地区ID
	 * 		"name" => string 地区名称
	 * 		"region_level" => int 地区等级
	 *  )
	 * )
	 */
    public function index()
	{
	    $root = array ();
		$sql = "select id,pid,name,region_level from " . DB_PREFIX . "delivery_region order by pid";
		$region_list = $GLOBALS ['db']->getAll ( $sql );
		$root ['region_list'] = $region_list?$region_list:array();
		output ( $root );
	}
	
}
?>