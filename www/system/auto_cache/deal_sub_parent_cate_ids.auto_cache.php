<?php
//生活服务分类父类下的所有子类ID,包含父分类
class deal_sub_parent_cate_ids_auto_cache extends auto_cache{
	public function load($param)
	{
		$param = array("cate_id"=>$param['cate_id']);		
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$ids = $GLOBALS['cache']->get($key);
		if($ids === false)
		{
			$param['cate_id'] = intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."deal_cate where id = ".intval($param['cate_id'])));
			
			$key = $this->build_key(__CLASS__,$param);
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$ids = $GLOBALS['cache']->get($key);
			if($ids!==false)return $ids;
			
			$cate_id = $param['cate_id'];
			$ids_util = new ChildIds("deal_cate");
			$ids = $ids_util->getChildIds($cate_id);
			$ids[] = $cate_id;
			
			//开始取出父分类ID
			$r_cate_id = $cate_id;
			while($r_cate_id!=0){
				$r_cate_id = $GLOBALS['db']->getOne("select pid from ".DB_PREFIX."deal_cate where id = ".$r_cate_id);
				if($r_cate_id!=0)
				$ids[] = $r_cate_id;
			}				
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$ids);
		}
		return $ids;
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