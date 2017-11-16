<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

/**
 * 站内消息的接口标准
 */
interface msg{
	
	/**
	 * 发送消息的标准接口
	 * @param unknown_type $user_id 发给的会员ID 
	 * @param unknown_type $data_id 相关的数据ID，用该ID实现相关的数据封装入data字段
	 */
	function send_msg($user_id,$content,$data_id);
	
	/**
	 * 加载相应的类型消息
	 * @param unknown_type $msg  数据集(即数据库中的对应消息行)
	 * 
	 * 返回：array("id"=>"当前消息ID",is_read=>"是否已读","icon"=>"相关数据的图片(可为空)","content"=>"内容","create_time"=>"时间","link"=>"(可为空)相关数据的跳转链接");
	 */
	function load_msg($msg);
	
	/**
	 * 消息类型
	 */
	function load_type();
	
}
?>