<?php
//促销
class cache_promote_auto_cache extends auto_cache{
	public function load($param)
	{
		$param = array();
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$promote = $GLOBALS['cache']->get($key);
		if($promote===false)
		{
			$promote_rs = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."promote order by sort");
			foreach($promote_rs as $k=>$v)
			{
				$v['config'] = unserialize($v['config']);
				$promote[$v['id']] = $v;
			}
			
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$promote);
		}	
		return $promote;
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