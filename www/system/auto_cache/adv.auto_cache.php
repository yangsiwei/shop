<?php
//广告位
/**
 * 返回的数据集
 * array(
 * 		"group" => array(
 * 			array(),array()
 * 		)
 * )
 * group表示为该页面的分组key
 * 值是一个数组，包含该类型广告的所有数据行，按sort从小到大排序
 *
 */
class adv_auto_cache extends auto_cache{
	public function load($param)
	{
		$param = array("page_module"=>$param['page_module'],"city_id"=>$param['city_id']); //重新定义缓存的有效参数，过滤非法参数
		
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$adv_group = $GLOBALS['cache']->get($key);				
		if($adv_group===false)
		{		
			$sql = "select * from ".DB_PREFIX."adv where (page_module = '".$param['page_module']."' or page_module ='siteroot') and is_effect = 1 order by sort asc";
			$adv_group_rs = $GLOBALS['db']->getAll($sql);
			$adv_group = array();
			foreach($adv_group_rs as $k=>$v)
			{
				$adv_group[$v['group']][] = $v;
			}
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$adv_group);
		}
		return $adv_group;	
	}
	public function rm($param)
	{
		$param = array("page_module"=>$param['page_module'],"city_id"=>$param['city_id']); //重新定义缓存的有效参数，过滤非法参数
		
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$GLOBALS['cache']->rm($key);
	}
	public function clear_all()
	{
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$GLOBALS['cache']->clear();
	}
}
?>