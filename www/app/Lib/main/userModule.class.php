<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class userModule extends MainBaseModule
{
	public function ajax_login()
	{		
		global_run();

		$fanwe_mobile = es_cookie::get("fanwe_mobile");

        $GLOBALS['tmpl']->assign("WB_APP_KEY", app_conf('WB_APP_KEY') );
        $GLOBALS['tmpl']->assign("WB_APP_SECRET", app_conf('WB_APP_SECRET') );

        $GLOBALS['tmpl']->assign("QQ_HL_APPID", app_conf('QQ_HL_APPID') );
        $GLOBALS['tmpl']->assign("QQ_HL_APPKEY", app_conf('QQ_HL_APPKEY') );

		$GLOBALS['tmpl']->assign("fanwe_mobile",$fanwe_mobile);
		$GLOBALS['tmpl']->assign("sms_lesstime",load_sms_lesstime());
		$GLOBALS['tmpl']->assign("sms_ipcount",load_sms_ipcount());
		$GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
		$GLOBALS['tmpl']->assign("form_prefix","ajax");
		$GLOBALS['tmpl']->display("user_ajax_login.html");
	}
	
	public function login()
	{
		global_run();
		init_app_page();	
		

		//$GLOBALS['tmpl']->assign("wrap_type","1"); //窄屏
		if($GLOBALS['user_info']['is_tmp']==1)
		{
			app_redirect(url("index","uc_account"));
		}

        $m_config = getMConfig();
		$fanwe_mobile = es_cookie::get("fanwe_mobile");
		
		$GLOBALS['tmpl']->assign("WB_APP_KEY", app_conf('WB_APP_KEY') );
		$GLOBALS['tmpl']->assign("WB_APP_SECRET", app_conf('WB_APP_SECRET') );
		
		$GLOBALS['tmpl']->assign("QQ_HL_APPID", app_conf('QQ_HL_APPID') );
		$GLOBALS['tmpl']->assign("QQ_HL_APPKEY", app_conf('QQ_HL_APPKEY') );

        $GLOBALS['tmpl']->assign("m_config",$m_config);
		$GLOBALS['tmpl']->assign("fanwe_mobile",$fanwe_mobile);
		$GLOBALS['tmpl']->assign("sms_lesstime",load_sms_lesstime());
		$GLOBALS['tmpl']->assign("sms_ipcount",load_sms_ipcount());
		$GLOBALS['tmpl']->assign("form_prefix","page");
		
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		
		$GLOBALS['tmpl']->assign("page_title","用户登录");
		$GLOBALS['tmpl']->display("user_login.html");
	}
	
	public function register()
	{
		global_run();
		init_app_page();

        $pid =  base64_decode($_GET['r']);

        if(empty($pid)){
            $pid = 268;
        }

		$GLOBALS['tmpl']->assign("pid",$pid); //窄屏
		$GLOBALS['tmpl']->assign("wrap_type","1"); //窄屏
		$GLOBALS['tmpl']->assign("sms_lesstime",load_sms_lesstime());
		$GLOBALS['tmpl']->assign("sms_ipcount",load_sms_ipcount());
		$GLOBALS['tmpl']->assign("form_prefix","page");
		
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		
		$GLOBALS['tmpl']->assign("page_title","用户注册");
		$GLOBALS['tmpl']->display("user_register.html");
	}

	public function register_agree(){
		global_run();
		$GLOBALS['tmpl']->display("register_agree.html");
	}
	public function register_privacy(){
		global_run();
		$GLOBALS['tmpl']->display("register_privacy.html");
	}
	
	
	public function dologin()
	{
		if(!$_POST)
		{
			app_redirect(APP_ROOT."/");
		}
		//验证码		
		$verify = md5(strim($_POST['verify_code']));
		$session_verify = es_session::get('verify');
		if($verify!=$session_verify)
		{
			$data['status'] = false;
			$data['info']	=	"图片验证码错误";
			$data['field'] = "verify_code";
			ajax_return($data);
		}
		es_session::delete("verify");
		if(strim($_POST['user_key'])=="")
		{
			$data['status'] = false;
			$data['info']	=	"请输入登录帐号";
			$data['field'] = "user_key";
			ajax_return($data);
		}
		if(strim($_POST['user_pwd'])=="")
		{
			$data['status'] = false;
			$data['info']	=	"请输入密码";
			$data['field'] = "user_pwd";
			ajax_return($data);
		}
		
		
		require_once APP_ROOT_PATH."system/model/user.php";
		if(check_ipop_limit(CLIENT_IP,"user_dologin",intval(app_conf("SUBMIT_DELAY"))))
			$result = do_login_user(strim($_POST['user_key']),strim($_POST['user_pwd']));
		else
		{
			showErr("提交太快了",1);
		}
			
		if($result['status'])
		{
			$s_user_info = es_session::get("user_info");
			if(intval($_POST['auto_login'])==1)
			{
				//自动登录，保存cookie
				$user_data = $s_user_info;
				es_cookie::set("user_name",$user_data['email'],3600*24*30);
				es_cookie::set("user_pwd",md5($user_data['user_pwd']."_EASE_COOKIE"),3600*24*30);
			}
			if(strim($_REQUEST['form_prefix'])=="ajax")
			{
				$GLOBALS['user_info'] = $s_user_info;
				if($GLOBALS['user_info'])
				{
					$user_level = load_auto_cache("cache_user_level");
					$GLOBALS['user_info']['level'] = $user_level[$GLOBALS['user_info']['level_id']]['level'];
					$GLOBALS['user_info']['level_name'] = $user_level[$GLOBALS['user_info']['level_id']]['name'];
					
					$msg_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."msg_box where user_id = ".intval($GLOBALS['user_info']['id'])." and is_read = 0 and is_delete = 0 and type = 0");
					$GLOBALS['tmpl']->assign("msg_count",intval($msg_count));
					$GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
					//输出签到结果
					$signin_result = es_session::get("signin_result");
					if($signin_result['status'])
					{
						$GLOBALS['tmpl']->assign("signin_result",json_encode($signin_result));
						es_session::delete("signin_result");
					}
				}
				$tip = $GLOBALS['tmpl']->fetch("inc/insert/load_user_tip.html");
			}
			
			$return['status'] = true;
			$return['info'] = "登录成功";
			$return['data'] = $result['msg'];			
			$return['jump'] = get_gopreview();
			$return['tip'] = $tip;
			ajax_return($return);
			
			
		}
		else
		{
			if($result['data'] == ACCOUNT_NO_EXIST_ERROR)
			{
				$field = "user_key";
				$err = $GLOBALS['lang']['USER_NOT_EXIST'];
			}
			if($result['data'] == ACCOUNT_PASSWORD_ERROR)
			{
				$field = "user_pwd";
				$err = $GLOBALS['lang']['PASSWORD_ERROR'];
			}
			if($result['data'] == ACCOUNT_NO_VERIFY_ERROR)
			{
				$field = "user_key";
				$err = $GLOBALS['lang']['USER_NOT_VERIFY'];		
			}
			$data['status'] = false;
			$data['info']	=	$err;
			$data['field'] = $field;
			ajax_return($data);
		}
	}
	
	public function loginout()
	{
		require_once APP_ROOT_PATH."system/model/user.php";
		$result = loginout_user();
		es_cookie::delete("user_name");
		es_cookie::delete("user_pwd");
		
		$jump = get_gopreview();
		if($result['msg'])
		app_redirect($jump,0,$result['msg']);
		else
		app_redirect($jump);
	}
	
	
	public function dophlogin()
	{
		$user_mobile = strim($_POST['user_mobile']);
		$sms_verify = strim($_POST['sms_verify']);
		if(app_conf("SMS_ON")==0)
		{
			$data['status'] = false;
			$data['info'] = "短信功能未开启";
			ajax_return($data);
		}
		if($user_mobile=="")
		{
			$data['status'] = false;
			$data['info']	=	"请输入手机号";
			$data['field'] = "user_mobile";
			ajax_return($data);
		}
		if($sms_verify=="")
		{
			$data['status'] = false;
			$data['info']	=	"请输入收到的验证码";
			$data['field'] = "sms_verify";
			ajax_return($data);
		}
		
		$sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
		$GLOBALS['db']->query($sql);
		
		$mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
		
		if($mobile_data['code']==$sms_verify)
		{
			//开始登录
			//1. 有用户使用已有用户登录
			//2. 无用户产生一个用户登录
			require_once APP_ROOT_PATH."system/model/user.php";
			if(check_ipop_limit(CLIENT_IP,"user_dophlogin",intval(app_conf("SUBMIT_DELAY"))))
			{
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile = '".$user_mobile."'");
				$GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
				if($user_info)
				{
					//使用已有用户					
					$result = do_login_user($user_info['user_name'],$user_info['user_pwd']);
						
					if($result['status'])
					{
						$s_user_info = es_session::get("user_info");
						if(strim($_REQUEST['form_prefix'])=="ajax")
						{
							$GLOBALS['user_info'] = $s_user_info;
							refresh_user_info();
							if($GLOBALS['user_info'])
							{
								$msg_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."msg_box where user_id = ".intval($GLOBALS['user_info']['id'])." and is_read = 0 and is_delete = 0");
								$GLOBALS['tmpl']->assign("msg_count",intval($msg_count));
								$GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
								//输出签到结果
								$signin_result = es_session::get("signin_result");
								if($signin_result['status'])
								{
									$GLOBALS['tmpl']->assign("signin_result",json_encode($signin_result));
									es_session::delete("signin_result");
								}
							}
							$tip = $GLOBALS['tmpl']->fetch("inc/insert/load_user_tip.html");
						}
							
						if(intval($_REQUEST['save_mobile'])==1)
						{
							es_cookie::set("fanwe_mobile", $user_mobile,3600*24*7);
						}
						$GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
						$return['status'] = true;
						$return['info'] = "登录成功";
						$return['data'] = $result['msg'];
						$return['jump'] = get_gopreview();
						$return['tip'] = $tip;
						ajax_return($return);
							
							
					}
					else
					{
						if($result['data'] == ACCOUNT_NO_EXIST_ERROR)
						{
							$field = "";
							$err = $GLOBALS['lang']['USER_NOT_EXIST'];
						}
						if($result['data'] == ACCOUNT_PASSWORD_ERROR)
						{
							$field = "";
							$err = $GLOBALS['lang']['PASSWORD_ERROR'];
						}
						if($result['data'] == ACCOUNT_NO_VERIFY_ERROR)
						{
							$field = "";
							$err = $GLOBALS['lang']['USER_NOT_VERIFY'];
						}
						$data['status'] = false;
						$data['info']	=	$err;
						$data['field'] = $field;
						ajax_return($data);
					}
				}
				else
				{
					//ip限制
					$ip = CLIENT_IP;
					$ip_nums = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where login_ip = '".$ip."'");
					if($ip_nums>intval(app_conf("IP_LIMIT_NUM"))&&intval(app_conf("IP_LIMIT_NUM"))>0)
					{
						$data['status'] = false;
						$data['info'] = $GLOBALS['lang']['IP_LIMIT_ERROR'];
						ajax_return($data);
					}
					
					global_run();
					
					if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".$user_mobile."' or mobile = '".$user_mobile."' or email = '".$user_mobile."'")>0)
					{
						$data['status'] = false;
						$data['info']	=	"手机号已被抢占";
						ajax_return($data);
					}
					
					//生成新用户
					$user_data = array();
					$user_data['mobile'] = $user_mobile;
					
					/*
					$user_data['user_pwd'] = md5(rand(100000,999999));
					$user_data['is_effect'] = 1;
					$user_data['pid'] = $GLOBALS['ref_uid'];
					$user_data['create_time'] = NOW_TIME;
					$user_data['update_time'] = NOW_TIME;
					$user_data['login_time'] = NOW_TIME;
					$user_data['login_ip'] = CLIENT_IP;
					$user_data['is_tmp'] = 1;
					$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user_data,"INSERT","","SILENT");
					$user_id = intval($GLOBALS['db']->insert_id());
					if($user_id==0)
					{
						$data['status'] = false;
						$data['info']	=	"手机号已被抢占";
						ajax_return($data);
					}
					$user_name = "游客_".$user_id;
					$GLOBALS['db']->query("update ".DB_PREFIX."user set user_name = '".$user_name."' where id = ".$user_id,"SILENT");	
					$result = do_login_user($user_name,$user_data['user_pwd']);
					*/	
					
					$rs_data = auto_create($user_data, 1);
					if(!$rs_data['status'])
					{
						$data['status'] = false;
						$data['info']	=	$rs_data['info'];
						ajax_return($data);
					}
					
					$result = do_login_user($rs_data['user_data']['user_name'],$rs_data['user_data']['user_pwd']);
					
					if($result['status'])
					{
						$s_user_info = es_session::get("user_info");
						if(strim($_REQUEST['form_prefix'])=="ajax")
						{
							$GLOBALS['user_info'] = $s_user_info;
							refresh_user_info();
							if($GLOBALS['user_info'])
							{
								$msg_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."msg_box where user_id = ".intval($GLOBALS['user_info']['id'])." and is_read = 0 and is_delete = 0");
								$GLOBALS['tmpl']->assign("msg_count",intval($msg_count));
								$GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
								//输出签到结果
								$signin_result = es_session::get("signin_result");
								if($signin_result['status'])
								{
									$GLOBALS['tmpl']->assign("signin_result",json_encode($signin_result));
									es_session::delete("signin_result");
								}
							}
							$tip = $GLOBALS['tmpl']->fetch("inc/insert/load_user_tip.html");
						}
							
						if(intval($_REQUEST['save_mobile'])==1)
						{
							es_cookie::set("fanwe_mobile", $user_mobile,3600*24*7);
						}
						$GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
						//原来为直接挑战 现改为 完善资料
						$str="<p style='text-align:center;'>".$GLOBALS['lang']['REGISTER_SUCCESS']."</p>";
						if(app_conf("USER_REGISTER_SCORE")){
						    $str.="<p style='text-align:center;color:#dd344f'>+".app_conf("USER_REGISTER_SCORE")."积分</p>";
						}
						$return['status'] = true;
						$return['info'] = "登录成功";
						$return['data'] = $result['msg'];
						$return['str'] = $str;
						$return['jump'] = get_gopreview();	;
						$return['tip'] = $tip;
						ajax_return($return);							
							
					}
				}
			}
			else
			{
				showErr("提交太快了",1);
			}
		}
		else
		{
			$data['status'] = false;
			$data['info']	=  "验证码错误";
			$data['field'] = "sms_verify";
			ajax_return($data);
		}
		
	}
	
	
	/**
	 * 注册
	 */
	public function doregister()
	{
		global_run();

		$agree = $_REQUEST['agree'];
        if(!$agree){
            $data['status'] = false;
            $data['info'] = "请先确定阅读并同意用户注册协议";
            $data['field'] = "agree";
            ajax_return($data);
        }
		
		//验证码
        $pid = intval($_REQUEST['pid']);
		$verify = strim($_REQUEST['verify_code']);		
		$data = check_field("verify_code",$verify,0);
		
		if(!$data['status'])
		{
			ajax_return($data);
		}
		es_session::delete("verify");
		//ip限制
		$ip = CLIENT_IP;
		$ip_nums = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where login_ip = '".$ip."'");
		if($ip_nums>intval(app_conf("IP_LIMIT_NUM"))&&intval(app_conf("IP_LIMIT_NUM"))>0)
		{
			$data['status'] = false;
			$data['info'] = $GLOBALS['lang']['IP_LIMIT_ERROR'];
			ajax_return($data);
		}
		 
		require_once APP_ROOT_PATH."system/model/user.php";
		$user_data = $_POST;
		foreach($user_data as $k=>$v)
		{
			$user_data[$k] = strim($v);
		}
		
		if($user_data['user_pwd']!=$user_data['user_pwd_confirm'])
		{
			$data['status'] = false;
			$data['info'] = "您两次输入的密码不匹配";
			$data['field'] = "user_pwd_confirm";
			ajax_return($data);
		}
		
		if($user_data['user_pwd']=='')
		{
			$data['status'] = false;
			$data['info'] = "请输入密码";
			$data['field'] = "user_pwd";
			ajax_return($data);			
		}
		
		$user_data['pid'] = $pid;
		
		$res = save_user($user_data);

		if($res['status'] == 1)
		{			
			//自动订阅邮箱和手机
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mail_list where mail_address = '".$user_data['email']."'")==0)
			{
				$mail_item['city_id'] = intval($GLOBALS['city']['id']);
				$mail_item['mail_address'] = $user_data['email'];
				$mail_item['is_effect'] = app_conf("USER_VERIFY");
				$GLOBALS['db']->autoExecute(DB_PREFIX."mail_list",$mail_item,'INSERT','','SILENT');
			}
			if($user_data['mobile']!=''&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mobile_list where mobile = '".$user_data['mobile']."'")==0)
			{
				$mobile['city_id'] = intval($GLOBALS['city']['id']);
				$mobile['mobile'] = $user_data['mobile'];
				$mobile['is_effect'] = app_conf("USER_VERIFY");
				$GLOBALS['db']->autoExecute(DB_PREFIX."mobile_list",$mobile,'INSERT','','SILENT');
			}
			
			$user_id = intval($res['data']);
			//更新来路
			$GLOBALS['db']->query("update ".DB_PREFIX."user set referer = '".$GLOBALS['referer']."' where id = ".$user_id);
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
			if($user_info['is_effect']==1)
			{				
				//在此自动登录
				do_login_user($user_data['email'],$user_data['user_pwd']);
				//原来为直接挑战 现改为 完善资料
				$str="<p style='text-align:center;'>".$GLOBALS['lang']['REGISTER_SUCCESS']."</p>";
				if(app_conf("USER_REGISTER_SCORE")){
					$str.="<p style='text-align:center;color:#dd344f'>+".app_conf("USER_REGISTER_SCORE")."积分</p>";
				}
				showSuccess($str,1,get_gopreview());			
			}
			else
			{
				//以下代码不会再被运行，因为USER_VERIFY不可再配置，固定为1
				if(app_conf("MAIL_ON")==1)
				{
					//发邮件
					send_user_verify_mail($user_id);
					$user_email = $GLOBALS['db']->getOne("select email from ".DB_PREFIX."user where id =".$user_id);
					//开始关于跳转地址的解析
					$domain = explode("@",$user_email);
					$domain = $domain[1];
					$gocheck_url = '';
					switch($domain)
					{
						case '163.com':
							$gocheck_url = 'http://mail.163.com';
							break;
						case '126.com':
							$gocheck_url = 'http://www.126.com';
							break;
						case 'sina.com':
							$gocheck_url = 'http://mail.sina.com';
							break;
						case 'sina.com.cn':
							$gocheck_url = 'http://mail.sina.com.cn';
							break;
						case 'sina.cn':
							$gocheck_url = 'http://mail.sina.cn';
							break;
						case 'qq.com':
							$gocheck_url = 'http://mail.qq.com';
							break;
						case 'foxmail.com':
							$gocheck_url = 'http://mail.foxmail.com';
							break;
						case 'gmail.com':
							$gocheck_url = 'http://www.gmail.com';
							break;
						case 'yahoo.com':
							$gocheck_url = 'http://mail.yahoo.com';
							break;
						case 'yahoo.com.cn':
							$gocheck_url = 'http://mail.cn.yahoo.com';
							break;
						case 'hotmail.com':
							$gocheck_url = 'http://www.hotmail.com';
							break;
						default:
							$gocheck_url = "";
							break;
					}
					//更新用户注册ip防止多次注册
					$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".$ip."' where id =".$user_id);
					$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['REGISTER_MAIL_SEND_SUCCESS']);
					$GLOBALS['tmpl']->assign("user_email",$user_email);
					$GLOBALS['tmpl']->assign("gocheck_url",$gocheck_url);
					//end

					//$GLOBALS['tmpl']->display("user_register_email.html");
					showSuccess($GLOBALS['lang']['WAIT_VERIFY_USER'],1,get_gopreview());
				}
				else
					showSuccess($GLOBALS['lang']['WAIT_VERIFY_USER'],1,get_gopreview());
			}
		}
		else
		{
			$error = $res['data'];
			$data['status'] = false;
			if(!$error['field_show_name'])
			{
				$error['field_show_name'] = $GLOBALS['lang']['USER_TITLE_'.strtoupper($error['field_name'])];
				$data['field'] = $error['field_name'];
			}
			if($error['error']==EMPTY_ERROR)
			{
				$error_msg = sprintf($GLOBALS['lang']['EMPTY_ERROR_TIP'],$error['field_show_name']);
			}
			if($error['error']==FORMAT_ERROR)
			{
				$error_msg = sprintf($GLOBALS['lang']['FORMAT_ERROR_TIP'],$error['field_show_name']);
			}
			if($error['error']==EXIST_ERROR)
			{
				$error_msg = sprintf($GLOBALS['lang']['EXIST_ERROR_TIP'],$error['field_show_name']);
			}
			$data['info'] = $error_msg;
			ajax_return($data);
		}
	}
	
	
	public function dophregister()
	{
		global_run();
		$agree = $_POST('agree');
		if(!$agree){
			$data['status'] = false;
			$data['info'] = "请先确定阅读并同意用户注册协议";
			$data['field'] = "agree";
			ajax_return($data);
		}
        $pid = intval($_REQUEST['pid']);
		$user_mobile = strim($_POST['user_mobile']);
		$sms_verify = strim($_POST['sms_verify']);
		$user_pwd = strim($_REQUEST['user_pwd']);
		$user_pwd_confirm = strim($_REQUEST['user_pwd_confirm']);

		if(app_conf("SMS_ON")==0)
		{
			$data['status'] = false;
			$data['info'] = "短信功能未开启";
			ajax_return($data);
		}
		
		if($user_pwd!=$user_pwd_confirm)
		{
			$data['status'] = false;
			$data['info'] = "您两次输入的密码不匹配";
			$data['field'] = "user_pwd_confirm";
			ajax_return($data);
		}
		
		if($user_pwd=='')
		{
			$data['status'] = false;
			$data['info'] = "请输入密码";
			$data['field'] = "user_pwd";
			ajax_return($data);
		}

		if(strlen($user_pwd)<4||strlen($user_pwd)>30)
		{
			$data['status'] = false;
			$data['info'] = "密码格式错误，请重新输入";
			$data['field'] = "user_pwd";
			ajax_return($data);
		}	
		
		if($user_mobile=="")
		{
			$data['status'] = false;
			$data['info']	=	"请输入手机号";
			$data['field'] = "user_mobile";
			ajax_return($data);
		}
		if($sms_verify=="")
		{
			$data['status'] = false;
			$data['info']	=	"请输入收到的验证码";
			$data['field'] = "sms_verify";
			ajax_return($data);
		}
		
		//ip限制
		$ip = CLIENT_IP;
		$ip_nums = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where login_ip = '".$ip."'");
		if($ip_nums>intval(app_conf("IP_LIMIT_NUM"))&&intval(app_conf("IP_LIMIT_NUM"))>0)
		{
			$data['status'] = false;
			$data['info'] = $GLOBALS['lang']['IP_LIMIT_ERROR'];
			ajax_return($data);
		}
		
		$sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
		$GLOBALS['db']->query($sql);
		
		$mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
		
		if($mobile_data['code']!=$sms_verify)
		{
			$data['status'] = false;
			$data['info']	=  "验证码错误";
			$data['field'] = "sms_verify";
			ajax_return($data);
		}
		//验证成功		
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".$user_mobile."' or mobile = '".$user_mobile."' or email = '".$user_mobile."'")>0)
		{
			$data['status'] = false;
			$data['field'] = "user_mobile";
			$data['info']	=	"手机号已被抢占";
			ajax_return($data);
		}
		$user_data = array();
 		$user_data['pid'] = $pid;
		$user_data['mobile'] = $user_mobile;
 		$user_data['user_pwd'] = md5($user_pwd);
 		$user_data['is_effect'] = 1;
 		$user_data['create_time'] = NOW_TIME;
 		$user_data['update_time'] = NOW_TIME;
 		$user_data['login_time'] = NOW_TIME;
 		$user_data['login_ip'] = CLIENT_IP;
 		$user_data['is_tmp'] = 1;
 		$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user_data,"INSERT","","SILENT");
 		$user_id = intval($GLOBALS['db']->insert_id());
 		if($user_id==0)
 		{
 			$data['status'] = false;
 			$data['field'] = "user_mobile";
 			$data['info']	=	"手机号已被抢占";
 			ajax_return($data);
 		}
 		$user_name = "游客_".$user_id;
 		$GLOBALS['db']->query("update ".DB_PREFIX."user set user_name = '".$user_name."' where id = ".$user_id,"SILENT");

		$rs_data = auto_create($user_data, 1);
		if(!$rs_data['status'])
		{
			$data['status'] = false;
			$data['info']	=	$rs_data['info'];
			ajax_return($data);
		}
			
		$result = do_login_user($rs_data['user_data']['user_name'],$rs_data['user_data']['user_pwd']);
		
		$GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'"); //删除验证码
			
			
		//$result = do_login_user($user_name,$user_data['user_pwd']);
		if($result['status'])
		{
			$s_user_info = es_session::get("user_info");
			
			$GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
			$return['status'] = true;
			
			$str="<p style='text-align:center;'>注册成功</p>";
			if(app_conf("USER_REGISTER_SCORE")){
				$str.="<p style='text-align:center;color:#dd344f'>+".app_conf("USER_REGISTER_SCORE")."积分</p>";
			}
			$return['info'] = $str;
			$return['jump'] = get_gopreview();	
			ajax_return($return);
				
		}
	}
	
	public function getpassword()
	{
		global_run();
		init_app_page();
		$GLOBALS['tmpl']->assign("wrap_type","1"); //窄屏
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		$GLOBALS['tmpl']->assign("page_title","找回密码");
		$GLOBALS['tmpl']->display("user_getpassword.html");
	}
	
	public function e_getpassword()
	{
		global_run();
		init_app_page();
		$GLOBALS['tmpl']->assign("wrap_type","1"); //窄屏
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		$GLOBALS['tmpl']->assign("page_title","邮箱找回");
		$GLOBALS['tmpl']->display("user_e_getpassword.html");
	}
	
	public function m_getpassword()
	{
		global_run();
		init_app_page();
		$GLOBALS['tmpl']->assign("wrap_type","1"); //窄屏
		$GLOBALS['tmpl']->assign("sms_lesstime",load_sms_lesstime());
		$GLOBALS['tmpl']->assign("sms_ipcount",load_sms_ipcount());
		
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		$GLOBALS['tmpl']->assign("page_title","短信找回");
		$GLOBALS['tmpl']->display("user_m_getpassword.html");
	}
	
	public function dogetpassword()
	{
		global_run();
		
		//验证码
		$verify = strim($_REQUEST['verify_code']);
		$data = check_field("verify_code",$verify,0);
		
		if(!$data['status'])
		{
			ajax_return($data);
		}
		es_session::delete("verify");
		$email = strim($_REQUEST['getpassword_email']);
		$data = check_field("getpassword_email",$email,0);
		if(!$data['status'])
		{
			ajax_return($data);
		}
		else
		{
			$user_info = $GLOBALS['db']->getRow('select * from '.DB_PREFIX."user where email='".$email."'");
			send_user_password_mail($user_info['id']);
			showSuccess($GLOBALS['lang']['SEND_HAS_SUCCESS'],1,get_gopreview());
		}
	}
	
	public function dogetpassword_m()
	{
		global_run();
	
		//验证码
		$verify = strim($_REQUEST['sms_verify']);
		if(empty($verify))
		{
			showErr("请输入验证码",1);
		}
		$mobile = strim($_REQUEST['user_mobile']);
		$data = check_field("getpassword_mobile",$mobile,0);
		if(!$data['status'])
		{
			ajax_return($data);
		}
		else
		{
			$user_info = $GLOBALS['db']->getRow('select * from '.DB_PREFIX."user where mobile='".$mobile."' and password_verify = '".$verify."'");
			if($user_info)
			{
				$GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$mobile."'");
				showSuccess("验证成功",1,url("index","user#modify_password",array("id"=>$user_info['id'],"code"=>$verify)));
			}
			else
			{
				showErr("验证码错误",1);
			}
		}
	}
	
	
	public function modify_password()
	{
		
		$id = intval($_REQUEST['id']);
		$user_info  = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id);
		if(!$user_info)
		{
			app_redirect(url("index"));
		}
		$verify = strim($_REQUEST['code']);
		if($user_info['password_verify'] == $verify&&$user_info['password_verify']!='')
		{
			//成功	
			global_run();
			init_app_page();
			$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
			$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['SET_NEW_PASSWORD']);				
			$GLOBALS['tmpl']->assign("find_user_info",$user_info);
			$GLOBALS['tmpl']->display("user_modify_password.html");
		}
		else
		{
			app_redirect(url("index"));
		}
	}
	
	public function domodifypassword()
	{
		$id = intval($_REQUEST['id']);
		$user_info  = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id);
		if(!$user_info)
		{
			showErr($GLOBALS['lang']['NO_THIS_USER'],1);
		}
		$verify = strim($_REQUEST['code']);
		if($user_info['password_verify'] == $verify&&$user_info['password_verify']!='')
		{
			if(strlen(strim($_REQUEST['user_pwd']))<4||strlen(strim($_REQUEST['user_pwd']))>30)
			{
				$data['status'] = false;
				$data['info'] = "密码格式错误，请重新输入";
				$data['field'] = "user_pwd";
				ajax_return($data);
			}
			if(strim($_REQUEST['user_pwd'])!=strim($_REQUEST['user_pwd_confirm']))
			{
				$data['status'] = false;
				$data['info'] = $GLOBALS['lang']['PASSWORD_VERIFY_FAILED'];
				$data['field'] = "user_pwd_confirm";
				ajax_return($data);
			}
			else
			{
				$password = strim($_REQUEST['user_pwd']);
				$user_info['user_pwd'] = $password;
				$password = md5($password.$user_info['code']);
				$result = 1;  //初始为1
				//载入会员整合
				$integrate_code = trim(app_conf("INTEGRATE_CODE"));
				if($integrate_code!='')
				{
					$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
					if(file_exists($integrate_file))
					{
						require_once $integrate_file;
						$integrate_class = $integrate_code."_integrate";
						$integrate_obj = new $integrate_class;
					}
				}
		
				if($integrate_obj)
				{
					$result = $integrate_obj->edit_user($user_info,$user_info['user_pwd']);
				}
				if($result>0)
				{
					$GLOBALS['db']->query("update ".DB_PREFIX."user set user_pwd = '".$password."',password_verify='' where id = ".$user_info['id'] );
					showSuccess($GLOBALS['lang']['NEW_PWD_SET_SUCCESS'],1,get_gopreview());
				}
				else
				{
					showErr($GLOBALS['lang']['NEW_PWD_SET_FAILED'],1);
				}
			}
		}
		else
		{
			showErr($GLOBALS['lang']['VERIFY_FAILED'],1);
		}
	}
	
	
	public function wx_login()
	{
		$img = url("index","file#qr_code",array("rand"=>time()));
		$GLOBALS['tmpl']->assign("img",$img);
		$GLOBALS['tmpl']->display("inc/wx_login.html");
	}

	public function wb_login()
    {
        require APP_ROOT_PATH."system/weibo/saetv2.ex.class.php";

        $sae  =  new SaeTOAuthV2( app_conf('WB_APP_KEY'), app_conf('WB_APP_SECRET') );
        $aUrl = get_domain().'?wb_login=1';
        $code_url = $sae->getAuthorizeURL($aUrl);

        // 直接跳转到授权页面
        app_redirect($code_url);
    }

    public function qq_login()
    {
        require APP_ROOT_PATH."system/qqconnect/API/qqConnectAPI.php";
        $qc = new QC();
        $qc->qq_login();

    }
    function check_login(){
        global_run();
        $is_login=check_save_login();
        $data['status']=$is_login;
        if($is_login==LOGIN_STATUS_NOLOGIN){
            $data['info']="未登录";
            $data['jump']=url("index","user#login");
        }
        ajax_return($data);
    }
}
?>