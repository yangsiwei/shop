<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class ScheduleListAction extends CommonAction{
	public function index()
	{
		parent::index();
	}
	
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();	
				
				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function exec()
	{
		$id = intval($_REQUEST['id']);
		$schedule_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."schedule_list where id = ".$id);
		if($schedule_data)
			$data = exec_schedule_plan($schedule_data);
		$GLOBALS['db']->query("update ".DB_PREFIX."schedule_list set exec_lock = 0 where id = '".$id."'");
		$this->success ($data['info'],1);
	}
	
	public function view()
	{
		$id = intval($_REQUEST['id']);
		$schedule_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."schedule_list where id = ".$id);
		
		$data = unserialize($schedule_data['data']);
		$html = "名称:".$schedule_data['name']."<br />";
		$html .= "任务详情:<br />";
		foreach($data as $k=>$v)
		{
			$html .= $k.":".$v."<br />";
		}
		$obj['html'] = $html;
		ajax_return($obj);
	}
	
	public function make_script()
	{
		make_app_js();
		ajax_return(array("status"=>1,"info"=>"生成成功"));
	}
}
?>