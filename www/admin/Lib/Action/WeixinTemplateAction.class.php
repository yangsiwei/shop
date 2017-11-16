<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class WeixinTemplateAction extends CommonAction
{
		 
	public function index()
	{
		$template_list = require_once APP_ROOT_PATH."system/wechat/wx_template_cfg.php";
		$this->assign("template_list",$template_list);			
		$this->assign("send_test_template_url",U("WeixinTemplate/send_test_template"));
		
		$this->display();
		
	}
	
	public function send_test_template()
	{
		$wx_user = strim($_REQUEST['weixin_user']);
		$template_id_short = trim($_REQUEST['template_id_short']);
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where user_name = '".$wx_user."'");

		if($user['wx_openid'])
		{
			$user_id = $user['id'];
		}
		else
		{
			$this->error("用户非微信授权用户");
		}
		
		$wx_account = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_account where type = 1 and user_id = 0");
		$rs = send_wx_msg($template_id_short,$user_id, $wx_account);
		$this->success("发送成功",1);
	}
		
 }
?>