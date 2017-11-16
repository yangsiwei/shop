<?php
//帮助信息
class get_help_cache_auto_cache extends auto_cache
{

	public function load($param)
	{
		$param = array();
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$help_list = $GLOBALS['cache']->get($key);
		if($help_list===false)
		{
			$ids_util = new ChildIds("article_cate");
			$help_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."article_cate where type_id = 1 and is_effect=1 and is_delete = 0 and pid=0 order by sort desc");
			foreach($help_list as $k=>$v)
			{
				$ids = $GLOBALS['cache']->get("CACHE_HELP_ARTICLE_CATE_".$v['id']);
				if($ids===false)
				{
					$ids = $ids_util->getChildIds($v['id']);
					$ids[] = $v['id'];
					$GLOBALS['cache']->set("CACHE_HELP_ARTICLE_CATE_".$v['id'],$ids);
				}
				$help_cate_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."article where cate_id in (".implode(",",$ids).") and is_effect=1  and is_delete = 0 order by sort desc");
				foreach($help_cate_list as $kk=>$vv)
				{
					if($vv['rel_url']!='')
					{
						if(!preg_match ("/http:\/\//i", $vv['rel_url']))
						{
							if(substr($vv['rel_url'],0,2)=='u:')
							{
								$help_cate_list[$kk]['url'] = parse_url_tag($vv['rel_url']);
							}
							else
							$help_cate_list[$kk]['url'] = APP_ROOT."/".$vv['rel_url'];
						}
						else
						$help_cate_list[$kk]['url'] = $vv['rel_url'];
						
						$help_cate_list[$kk]['new'] = 1;
					}
					else
					{
						if($vv['uname']!='')
						$hurl = url("index","help#".$vv['uname']);
						else
						$hurl = url("index","help#".$vv['id']);
						$help_cate_list[$kk]['url'] = $hurl;
					}
				}
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