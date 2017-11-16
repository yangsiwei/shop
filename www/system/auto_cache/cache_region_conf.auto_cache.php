<?php
//地区信息
class cache_region_conf_auto_cache extends auto_cache{
	public function load($param)
	{
		static $region_list;
		if($region_list)return $region_list;	
		$param = array();
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$region_list = $GLOBALS['cache']->get($key);				
		if($region_list === false)
		{		
			$region_list_rs = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf");
			foreach($region_list_rs as $k=>$v)
			{
				$region_list[$v['id']] = $v;
			}
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$region_list);
		}
		return $region_list;	
	}
	public function rm($param)
	{
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