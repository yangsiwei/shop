<?php
//帮助
class cache_helps_auto_cache extends auto_cache{
	public function load($param)
	{
		$param = array();
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$helps_list = $GLOBALS['cache']->get($key);
		if($helps_list === false)
		{
			$helps_list = $GLOBALS['db']->getAll("select id,title from ".DB_PREFIX."article_cate  order by sort desc");
			foreach($helps_list as $k=>$v){
				$helps_list[$k]['article_list']=$GLOBALS['db']->getAll("select id,title from ".DB_PREFIX."article where is_effect=1 and cate_id=".$v['id']."  order by sort desc");
				if(!$helps_list[$k]['article_list'])unset($helps_list[$k]);
			}

			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$helps_list);
		}
		return $helps_list;
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