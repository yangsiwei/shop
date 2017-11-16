<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

require_once(APP_ROOT_PATH.'system/libs/msg.php');
class notify_msg implements msg 
{		
	public function send_msg($user_id,$content,$data_id)
	{
		$msg = array();
		$msg['content'] = $content;
		$msg['user_id'] = $user_id;
		$msg['create_time'] = NOW_TIME;
		$msg['type'] = "notify";
		$msg['data_id'] = $data_id;

		$GLOBALS['db']->autoExecute(DB_PREFIX."msg_box",$msg,"INSERT","","SILENT");
	} 	

	public function load_msg($msg)
	{
		$msg['title'] = "通知";
		$msg['short_title'] = msubstr($msg['title']);
		return $msg;
	}
	
	public function load_type()
	{
		return "用户事件通知";
	}
}
?>