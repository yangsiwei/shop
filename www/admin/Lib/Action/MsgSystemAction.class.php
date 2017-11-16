<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class MsgSystemAction extends CommonAction{
	public function add()
	{
		$this->assign("default_end_time",to_date(NOW_TIME+3600*24*7));
		$this->display();
	}
	
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
		$this->assign ( 'vo', $vo );
		$this->display ();
	}	
	
	public function insert() {
		B('FilterString');
		
		$ajax = intval($_REQUEST['ajax']);
		$data = M(MODULE_NAME)->create ();
		$data['end_time'] = strim($data['end_time'])==''?0:to_timespan($data['end_time']);		
		$data['create_time'] = NOW_TIME;
		$user_names = preg_split("/[ ,]/i",$data['user_names']);
		$user_ids = array();
		foreach($user_names as $k=>$v)
		{
			$uid = M("User")->where("user_name = '".$v."'")->getField("id");
			if($uid)
			$user_ids[] = str_pad($uid, 6,0,STR_PAD_LEFT);
		}
		$data['user_ids'] = implode(",", $user_ids);		
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['content']))
		{
			$this->error(L("MSY_CONTENT_EMPTY_TIP"));
		}	
		// 更新数据
		$log_info = $data['title'];
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		
		$user_names = preg_split("/[ ,]/i",$data['user_names']);
		$user_ids = array();
		foreach($user_names as $k=>$v)
		{
			$uid = M("User")->where("user_name = '".$v."'")->getField("id");
			if($uid)
			$user_ids[] = str_pad($uid, 6,0,STR_PAD_LEFT);
		}
		$data['user_ids'] = implode(",", $user_ids);	
		$data['end_time'] = strim($data['end_time'])==''?0:to_timespan($data['end_time']);
		
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("title");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));

		if(!check_empty($data['content']))
		{
			$this->error(L("MSY_CONTENT_EMPTY_TIP"));
		}	

		// 更新数据
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
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
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();			
				if ($list!==false) {
					M("MsgBox")->where(array ('system_msg_id' => array ('in', explode ( ',', $id ) )))->delete();
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
}
?>