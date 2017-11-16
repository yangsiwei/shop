<?php
//导航
class cache_nav_list_auto_cache extends auto_cache{
	public function load($param)
	{
		$param = array();
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$nav_list = $GLOBALS['cache']->get($key);
		if(!$nav_list)
		{
			$nav_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."nav where is_effect = 1 order by sort desc");
			$nav_list = format_nav_list($nav_list);
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$nav_list);
		}
		return $nav_list;
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