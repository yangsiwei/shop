<?php
//商圈缓存
class cache_history_auto_cache extends auto_cache{
	/**
	 * 
	 * @param unknown_type $param
	 * 参数说明：
	 * type: deal/store
	 * un: 频道名称
	 * rel_id: 相关类型浏览历史的ID
	 * session_id: 当前的session_id
	 * uid: 当前的会员ID
	 * 
	 * 如将历史存于cookie，无需使用session_id与uid
	 * 如将历史存于服务端,则需要session_id
	 * 如将历史存于服务端，并需要按会员保存，则需要uid
	 * 默认功能存储于cookie
	 * 
	 * @return unknown
	 */
	public function load($param)
	{			
		$rel_id = intval($param['rel_id']);
		unset($param['rel_id']);
		//为默认cookie准备的参数
		unset($param['session_id']);
		unset($param['uid']);
	
		
		$key = $this->build_key(__CLASS__,$param);


		$history_ids = strim(es_cookie::get($key));				
		if($history_ids)
		{
			$history_ids = explode(",", $history_ids);
		}
		if(!is_array($history_ids))
		{
			$history_ids = array();
		}	
		
		
		if($rel_id)
		{
			foreach($history_ids as $k=>$id_item)
			{
				if($id_item==$rel_id)
				{
					unset($history_ids[$k]);
				}
			}			
			$history_ids[] = $rel_id;
			
			while(count($history_ids)>10)
			{
				array_shift($history_ids);
			}			
			$history_ids_rs = implode(",", $history_ids);
			es_cookie::set($key,$history_ids_rs,24*3600);
		}	

		$tmp_history_ids = $history_ids;  //用于倒序排序的临时数组
		$return_history = array();
		while(count($tmp_history_ids)>0)
		{
			$return_history[] = array_pop($tmp_history_ids);
		}
		return $return_history;	
	}
	public function rm($param)
	{
		$rel_id = intval($param['rel_id']);
		unset($param['rel_id']);
		//为默认cookie准备的参数
		unset($param['session_id']);
		unset($param['uid']);

		
		$key = $this->build_key(__CLASS__,$param);
		es_cookie::delete($key);
		
// 		$key = $this->build_key(__CLASS__,$param);
// 		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
// 		$GLOBALS['cache']->rm($key);
	}
	public function clear_all()
	{
// 		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
// 		$GLOBALS['cache']->clear();
	}
}
?>