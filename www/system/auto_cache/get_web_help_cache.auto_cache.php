<?php
//帮助信息
class get_web_help_cache_auto_cache extends auto_cache
{

	public function load($param)
	{

		$param = array();
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$help_list = $GLOBALS['cache']->get($key);
		if(!$help_list)
		{
	
			$help_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."web_article_cate where is_effect=1 and is_delete = 0 order by sort asc");
			
			
			foreach($help_list as $k=>$v)
			{
	
				$help_cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."web_article where cate_id =".$v['id']." and is_effect=1  and is_delete = 0 order by sort asc");
				$help_list[$k]['help_list'] = $help_cate_list;
			}
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
	
			$GLOBALS['cache']->set($key,$help_list);	
		}
		return $help_list;

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