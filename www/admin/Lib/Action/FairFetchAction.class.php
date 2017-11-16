<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class FairFetchAction extends CommonAction{
	public function index()
	{
		$_REQUEST ['listRows'] = 120;
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if(empty($_REQUEST['drawdate']))
		$map['drawdate'] = to_date(NOW_TIME,"Ymd");
		
		$this->assign("drawdate",$map['drawdate']);
		
		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}	
	
	public function start_fetch()
	{
		//输出现有模板文件夹
		$directory = APP_ROOT_PATH."system/fair_fetch/";
		$dir = @opendir($directory);
		$tmpls     = array();
			
		while (false !== ($file = @readdir($dir)))
		{
			if($file!='.'&&$file!='..')
			{
				$fair_type = str_replace("_fair_fetch.php", "", $file);				
				$cname = $fair_type."_fair_fetch";				
				require_once APP_ROOT_PATH."system/fair_fetch/".$cname.".php";
				$fetch_obj = new $cname;			

				send_schedule_plan("fair", $fetch_obj->name."开奖采集", array("fair_type"=>$fair_type), NOW_TIME);				
			}
		}
		
		$this->success("采集任务开启");
	}
	
}
?>