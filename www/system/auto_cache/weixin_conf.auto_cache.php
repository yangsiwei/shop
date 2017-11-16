<?php
//底部文章
class weixin_conf_auto_cache extends auto_cache{
	public function load($param)
	{
// 		$param=array();
// 		$key = $this->build_key(__CLASS__,$param);
//  		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
// 		$weixin = $GLOBALS['cache']->get($key);
// 		if(true)
// 		{
// 			$weixin_res = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_conf");
			 
// 			foreach($weixin_res as $k=>$v){
//  				$weixin[$v['name']]=$v['value'];
// 			}
		 
//  			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
// 			$GLOBALS['cache']->set($key,$weixin);
// 		}
// 		return $weixin;
		static $weixin;
		if(!$weixin)
		{
			$weixin_res = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_conf");			
			foreach($weixin_res as $k=>$v){
				$weixin[$v['name']]=$v['value'];
			}
		}		
		return $weixin;
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