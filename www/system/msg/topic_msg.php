<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

require_once(APP_ROOT_PATH.'system/libs/msg.php');
class topic_msg implements msg 
{		
	public function send_msg($user_id,$content,$data_id)
	{
		$msg = array();
		$msg['content'] = $content;
		$msg['user_id'] = $user_id;
		$msg['create_time'] = NOW_TIME;
		$msg['type'] = "topic";
		$msg['data_id'] = $data_id;
		require_once APP_ROOT_PATH."system/model/topic.php";
		$data = get_topic_item($data_id);
		$msg['data'] = serialize($data);
		$GLOBALS['db']->autoExecute(DB_PREFIX."msg_box",$msg,"INSERT","","SILENT");
	} 	

	/**
	 * 加载相应的类型消息
	 * @param unknown_type $msg  数据集(即数据库中的对应消息行)
	 *
	 * 返回：array("id"=>"当前消息ID",title="标题",is_read=>"是否已读","icon"=>"相关数据的图片(可为空)","content"=>"内容","create_time"=>"时间","link"=>"(可为空)相关数据的跳转链接");
	 */
	public function load_msg($msg)
	{
		if(!$msg['data'])
		{
			require_once APP_ROOT_PATH."system/model/topic.php";
			$data = get_topic_item($msg['data_id']);
			$msg['data'] = serialize($data);
			$GLOBALS['db']->autoExecute(DB_PREFIX."msg_box",$msg,"UPDATE","id=".$msg['id'],"SILENT");
		}
		
		$data = unserialize($msg['data']);
		//图标，取分享的第一张
		if($data['id']!=$data['origin_id']&&$data['origin'])
		{
			$msg['title'] = $data['origin']['forum_title'];
			$msg['icon'] = $data['origin']['images'][0]['o_path'];
			$msg['link'] = $data['origin']['url'];
		}
		else
		{
			$msg['title'] = $data['forum_title'];
			$msg['icon'] = $data['images'][0]['o_path'];
			$msg['link'] = $data['url'];
		}
		if($msg['title']=="") $msg['title'] = "会员分享";
		$msg['short_title'] = msubstr($msg['title']);
		return $msg;		
	}
	
	public function load_type()
	{
		return "用户分享相关消息";
	}
}
?>