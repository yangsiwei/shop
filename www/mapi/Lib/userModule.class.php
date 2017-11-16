<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class userApiModule extends MainBaseApiModule
{
	
	/**
	 * 更新当前会员的设备类型与设备号
	 */
	public function update_dev_token()
	{
		require_once APP_ROOT_PATH."system/model/user.php";
		$user = $GLOBALS['user_info'];
		$user_login_status = check_login();
		if($user_login_status==LOGIN_STATUS_NOLOGIN){
			$root['user_login_status'] = $user_login_status;
		}
		else
		{
			$data = array();
			$data['dev_type'] = strim($GLOBALS['request']['dev_type']);
			$data['device_token'] = strim($GLOBALS['request']['dev_token']);
			$GLOBALS['db']->autoExecute(DB_PREFIX."user", $data, 'UPDATE','id = '.intval($user['id']));
	
			return output(array(),1,"更新成功");
		}
			
	
			
	}
	
	
	/**
	 * 	 普通登录接口
	 * 
	 * 	 输入:  
	 *  user_key: string 会员账号： 手机号/邮箱/email
	 *  user_pwd: string 密码
	 *  
	 *  输出:
	 *  status:int 结果状态 0失败 1成功
	 *  info:信息返回
	 *  
	 *  以下六项仅在status为1时会返回
	 *  id:int 会员ID
	 *  user_name:string 会员名
	 *  user_pwd:string 加密过的密码
	 *  email:string 邮箱
	 *  mobile:string 手机号
	 *  is_tmp: int 是否为临时会员 0:否 1:是
	 */
	public function dologin()
	{
		$root = array();	

		if(strim($GLOBALS['request']['user_key'])=="")
		{
			return output("",0,"请输入登录帐号");
		}
		if(strim($GLOBALS['request']['user_pwd'])=="")
		{
			return output("",0,"请输入密码");
		}
		
		
		require_once APP_ROOT_PATH."system/model/user.php";
		if(check_ipop_limit(get_client_ip(),"user_dologin",intval(app_conf("SUBMIT_DELAY"))))
			$result = do_login_user(strim($GLOBALS['request']['user_key']),strim($GLOBALS['request']['user_pwd']));
		else
		{
			return output("",0,"提交太快了");
		}
			
		if($result['status'])
		{
			$s_user_info = es_session::get("user_info");
			$data['id'] = $s_user_info['id'];
			$data['user_name'] = $s_user_info['user_name'];
			$data['user_pwd'] = $s_user_info['user_pwd'];
			$data['email'] = $s_user_info['email'];
			$data['mobile'] = $s_user_info['mobile'];
			$data['is_tmp'] = $s_user_info['is_tmp'];
			return output($data,1,"登录成功");
		}
		else
		{
			if($result['data'] == ACCOUNT_NO_EXIST_ERROR)
			{
				$field = "user_key";
				$err = "用户不存在";
			}
			if($result['data'] == ACCOUNT_PASSWORD_ERROR)
			{
				$field = "user_pwd";
				$err = "密码错误";
			}
			if($result['data'] == ACCOUNT_NO_VERIFY_ERROR)
			{
				$field = "user_key";
				$err = "用户未通过验证";
			}
			return output("",0,$err);
		}
	}
	
	
	/**
	 * 	 手机短信登录接口
	 *
	 * 	 输入:
	 *  mobile: string 手机号
	 *  sms_verify: string 验证码
	 *
	 *  输出:
	 *  status:int 结果状态 0失败 1成功
	 *  info:信息返回
	 *
	 *  以下六项仅在status为1时会返回
	 *  id:int 会员ID
	 *  user_name:string 会员名
	 *  user_pwd:string 加密过的密码
	 *  email:string 邮箱
	 *  mobile:string 手机号
	 *  is_tmp: int 是否为临时会员 0:否 1:是
	 */
	public function dophlogin()
	{
		$user_mobile = strim($GLOBALS['request']['mobile']);
		$sms_verify = strim($GLOBALS['request']['sms_verify']);
		if(app_conf("SMS_ON")==0)
		{
			return output("",0,"短信功能未开启");
		}
		if($user_mobile=="")
		{
			return output("",0,"请输入手机号");
		}
		if($sms_verify=="")
		{
			return output("",0,"请输入收到的验证码");
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
			if(check_ipop_limit(get_client_ip(),"user_dophlogin",intval(app_conf("SUBMIT_DELAY"))))
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
						$data['id'] = $s_user_info['id'];
                        $data['is_new']=0;
						$data['user_name'] = $s_user_info['user_name'];
						$data['user_pwd'] = $s_user_info['user_pwd'];
						$data['email'] = $s_user_info['email'];
						$data['mobile'] = $s_user_info['mobile'];
						$data['is_tmp'] = $s_user_info['is_tmp'];
						return output($data,1,"登录成功");							
							
					}
					else
					{
						if($result['data'] == ACCOUNT_NO_EXIST_ERROR)
						{
							$field = "";
							$err = "用户不存在";
						}
						if($result['data'] == ACCOUNT_PASSWORD_ERROR)
						{
							$field = "";
							$err = "密码错误";
						}
						if($result['data'] == ACCOUNT_NO_VERIFY_ERROR)
						{
							$field = "";
							$err = "用户未通过验证";
						}
						return output("",0,$err);
					}
				}
				else
				{
					//ip限制
					$ip = get_client_ip();
					$ip_nums = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where login_ip = '".$ip."'");
					if($ip_nums>intval(app_conf("IP_LIMIT_NUM"))&&intval(app_conf("IP_LIMIT_NUM"))>0)
					{
						return output("",0,"IP受限");
					}
						
						
					if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".$user_mobile."' or mobile = '".$user_mobile."' or email = '".$user_mobile."'")>0)
					{
						return output("",0,"手机号已被抢占");
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
					$user_data['login_ip'] = get_client_ip();
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
						return output("",0,$rs_data['info']);
					}
						
					$result = do_login_user($rs_data['user_data']['user_name'],$rs_data['user_data']['user_pwd']);
						
					if($result['status'])
					{
						$s_user_info = es_session::get("user_info");
						$data['id'] = $s_user_info['id'];
						$data['user_name'] = $s_user_info['user_name'];
						$data['user_pwd'] = $s_user_info['user_pwd'];
						$data['email'] = $s_user_info['email'];
						$data['mobile'] = $s_user_info['mobile'];
						$data['is_tmp'] = $s_user_info['is_tmp'];
                        $data['is_new']=1;
						$str="<p style='text-align:center;'>注册成功</p>";
						if(app_conf("USER_REGISTER_SCORE")){
						    $str.="<p style='text-align:center;color:#dd344f'>+".app_conf("USER_REGISTER_SCORE")."积分</p>";
						}
						return output($data,1,$str);	

					}
				}
			}
			else
			{
				return output("",0,"提交太快了");
			}
		}
		else
		{
			return output("",0,"验证码错误");
		}
	}
	
	
	/**
	 * 	 手机号码绑定接口
	 *
	 * 	 输入:
	 *  mobile: string 手机号
	 *  sms_verify: string 验证码
	 *
	 *  输出:
	 *  status:int 结果状态 0失败 1成功
	 *  info:信息返回
	 *  user_login_status:int 用户登录状态(1 已经登录/0 用户未登录/2临时用户)
	 *
	 *  以下六项仅在status为1时会返回
	 *  id:int 会员ID
	 *  user_name:string 会员名
	 *  user_pwd:string 加密过的密码
	 *  email:string 邮箱
	 *  mobile:string 手机号
	 *  is_tmp: int 是否为临时会员 0:否 1:是
	 */
	public function dophbind()
	{
		$user_mobile = strim($GLOBALS['request']['mobile']);
		$sms_verify = strim($GLOBALS['request']['sms_verify']);
		global_run();
		$data['user_login_status'] = check_login();
		if(app_conf("SMS_ON")==0)
		{
			return output($data,0,"短信功能未开启");
		}
		if($user_mobile=="")
		{
			return output($data,0,"请输入手机号");
		}
		if($sms_verify=="")
		{
			return output($data,0,"请输入收到的验证码");
		}
	
		$sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
		$GLOBALS['db']->query($sql);
	
		$mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
	
		if($mobile_data['code']==$sms_verify)
		{
			//开始绑定
			//1. 未登录状态提示登录
			//2. 已登录状态绑定
			require_once APP_ROOT_PATH."system/model/user.php";
			
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile = '".$user_mobile."'");
			if($user_info)
			{
				return output($data,0,"手机号已被抢占");
			}
			else
			{
				
				if($GLOBALS['user_info'])
				{
					$GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
					$GLOBALS['db']->query("update ".DB_PREFIX."user set mobile = '".$user_mobile."' where id = ".$GLOBALS['user_info']['id']);

					$result = do_login_user($user_mobile,$GLOBALS['user_info']['user_pwd']);
					
					if($result['status'])
					{
						$s_user_info = es_session::get("user_info");
						$data['id'] = $s_user_info['id'];
						$data['user_name'] = $s_user_info['user_name'];
						$data['user_pwd'] = $s_user_info['user_pwd'];
						$data['email'] = $s_user_info['email'];
						$data['mobile'] = $s_user_info['mobile'];
						$data['is_tmp'] = $s_user_info['is_tmp'];
						$data['user_login_status'] = check_login();
						return output($data,1,"绑定成功");
					
					}
				}
				else
				{					
					return output($data,0,"您还未登录");
				}

				
			}
		}
		else
		{
			return output($data,0,"验证码错误");
		}
	}
	
	
	
	/**
	 * 	 手机短信修改密码接口
	 *
	 * 	 输入:
	 *  mobile: string 手机号
	 *  sms_verify: string 验证码
	 *  new_pwd:string 新密码
	 *
	 *  输出:
	 *  status:int 结果状态 0失败 1成功
	 *  info:信息返回
	 *
	 *  以下六项仅在status为1时会返回
	 *  id:int 会员ID
	 *  user_name:string 会员名
	 *  user_pwd:string 加密过的密码
	 *  email:string 邮箱
	 *  mobile:string 手机号
	 *  is_tmp: int 是否为临时会员 0:否 1:是
	 */
	public function phmodifypassword()
	{
		$user_mobile = strim($GLOBALS['request']['mobile']);
		$sms_verify = strim($GLOBALS['request']['sms_verify']);
		$new_pwd = strim($GLOBALS['request']['new_pwd']);
		
		if(app_conf("SMS_ON")==0)
		{
			return output("",0,"短信功能未开启");
		}
		if($user_mobile=="")
		{
			return output("",0,"请输入手机号");
		}
		if($sms_verify=="")
		{
			return output("",0,"请输入收到的验证码");
		}
		if($new_pwd=="")
		{
			return output("",0,"请输入密码");
		}
		if(strlen($new_pwd)<4||strlen($new_pwd)>30){
			return output("",0,"密码必须4位以上");
		}	
		$sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
		$GLOBALS['db']->query($sql);
	
		$mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
	
		if($mobile_data['code']==$sms_verify)
		{
			//开始绑定
			//1. 未登录状态提示登录
			//2. 已登录状态绑定
			require_once APP_ROOT_PATH."system/model/user.php";
				
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile = '".$user_mobile."'");
			if(!$user_info)
			{
				return output("",0,"手机号未注册");
			}
			else
			{
	
				
				$GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
							
				
				$user_info['user_pwd'] = $new_pwd;
				$new_pwd = md5($new_pwd.$user_info['code']);
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
					$GLOBALS['db']->query("update ".DB_PREFIX."user set user_pwd = '".$new_pwd."',password_verify='' where id = ".$user_info['id'] );					
				}
				else
				{
					return output("",0,"密码修改失败");				
				}
				
				$result = do_login_user($user_mobile,$new_pwd);
					
				if($result['status'])
				{
					$s_user_info = es_session::get("user_info");
					$data['id'] = $s_user_info['id'];
					$data['user_name'] = $s_user_info['user_name'];
					$data['user_pwd'] = $s_user_info['user_pwd'];
					$data['email'] = $s_user_info['email'];
					$data['mobile'] = $s_user_info['mobile'];
					$data['is_tmp'] = $s_user_info['is_tmp'];
					return output($data,1,"密码修改成功");						
				}	
			}
		}
		else
		{
			return output("",0,"短信验证码错误");
		}
	}
	
	
	/**
	 * 注销接口
	 * 传入
	 * 无
	 * 
	 * 传出
	 * 无
	 */
	public function loginout()
	{
		require_once APP_ROOT_PATH."system/model/user.php";
		loginout_user();

		return output("",1,"登出成功");
	}
	
	
	/**
	 * 会员普通注册接口
	 * 输入参数:
	 * user_name: string 注册的用户名
	 * user_email: string 注册的邮箱
	 * user_pwd: string 注册的密码
	 * ref_uid: int 推荐人
	 * ref_uname: string 推荐人用户名
	 * 
	 *  输出:
	 *  status:int 结果状态 0失败 1成功
	 *  info:信息返回
	 *
	 *  以下六项仅在status为1时会返回
	 *  id:int 会员ID
	 *  user_name:string 会员名
	 *  user_pwd:string 加密过的密码
	 *  email:string 邮箱
	 *  mobile:string 手机号
	 *  is_tmp: int 是否为临时会员 0:否 1:是
	 * 
	 */
	public function doregister()
	{		

		//ip限制
		$ip = get_client_ip();
		$ip_nums = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where login_ip = '".$ip."'");
		if($ip_nums>intval(app_conf("IP_LIMIT_NUM"))&&intval(app_conf("IP_LIMIT_NUM"))>0)
		{
			return output("",0,"IP受限");
		}

		$user_name = strim($GLOBALS['request']['user_name']);
		$email = strim($GLOBALS['request']['user_email']);
		$user_pwd = strim($GLOBALS['request']['user_pwd']);
		$ref_uid = intval($GLOBALS['request']['ref_uid']);
		if($ref_uid==0&&$GLOBALS['request']['ref_uname'])
		{
			$ref_uid = intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where user_name = '".strim($GLOBALS['request']['ref_uname'])."'"));
		}
		
		
		require_once APP_ROOT_PATH."system/model/user.php";
		$user_data['user_name'] = $user_name;
		$user_data['email'] = $email;
		$user_data['user_pwd'] = $user_pwd;
				
		if($user_data['user_pwd']=='')
		{
			return output("",0,"请输入密码");
		}
		
		$user_data['pid'] = $ref_uid;
		
		$res = save_user($user_data);
		
		if($res['status'] == 1)
		{
			//自动订阅邮箱
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."mail_list where mail_address = '".$user_data['email']."'")==0)
			{
				$mail_item['city_id'] = intval($GLOBALS['city']['id']);
				$mail_item['mail_address'] = $user_data['email'];
				$mail_item['is_effect'] = app_conf("USER_VERIFY");
				$GLOBALS['db']->autoExecute(DB_PREFIX."mail_list",$mail_item,'INSERT','','SILENT');
			}
			$user_id = intval($res['data']);
			//更新来路
			$GLOBALS['db']->query("update ".DB_PREFIX."user set referer = '".$GLOBALS['referer']."' where id = ".$user_id);
			
			//在此自动登录
			do_login_user($user_data['email'],$user_data['user_pwd']);
			
			$s_user_info = es_session::get("user_info");
			$data['id'] = $s_user_info['id'];
			$data['user_name'] = $s_user_info['user_name'];
			$data['user_pwd'] = $s_user_info['user_pwd'];
			$data['email'] = $s_user_info['email'];
			$data['mobile'] = $s_user_info['mobile'];
			$data['is_tmp'] = $s_user_info['is_tmp'];
			
			//原来为直接挑战 现改为 完善资料
			return output($data,1,"注册成功");
			
		}
		else
		{
			$error = $res['data'];
			if($error['field_name']=="user_name")
			{
				$error_field = "用户名";
			}
			elseif($error['field_name']=="email")
			{
				$error_field = "邮箱";
			}
			elseif($error['field_name']=="user_pwd")
			{
				$error_field = "密码";
			}
			
			if($error['error']==EMPTY_ERROR)
			{
				$error_msg = $error_field."不能为空";
			}
			if($error['error']==FORMAT_ERROR)
			{
				$error_msg = $error_field."格式错误";
			}
			if($error['error']==EXIST_ERROR)
			{
				$error_msg = $error_field."已经存在";
			}
			
			return output("",0,$error_msg);
			
		}
	}
        
    public function dophregister()
    {

		//ip限制
		$ip = get_client_ip();
		$ip_nums = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where login_ip = '".$ip."'");
		if($ip_nums>intval(app_conf("IP_LIMIT_NUM"))&&intval(app_conf("IP_LIMIT_NUM"))>0)
		{
			return output("",0,"IP受限");
		}

		$user_mobile = strim($GLOBALS['request']['mobile']);
		$sms_verify = strim($GLOBALS['request']['sms_verify']);
		if(app_conf("SMS_ON")==0)
		{
			return output("",0,"短信功能未开启");
		}
		if($user_mobile=="")
		{
			return output("",0,"请输入手机号");
		}
		if($sms_verify=="")
		{
			return output("",0,"请输入收到的验证码");
		}

		$sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
		$GLOBALS['db']->query($sql);

		$mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");

		if($mobile_data['code']!=$sms_verify)
		{
			return output("",0,"验证码错误");
		}

		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".$user_mobile."' or mobile = '".$user_mobile."' or email = '".$user_mobile."'")>0)
		{
			return output("",0,"手机号已被抢占");
		}

		// $user_name = strim($GLOBALS['request']['user_name']);
		// $email = strim($GLOBALS['request']['user_email']);
		$user_pwd = strim($GLOBALS['request']['user_pwd']);
		$ref_uid = intval($GLOBALS['request']['ref_uid']);
		if($ref_uid==0&&$GLOBALS['request']['ref_uname'])
		{
			$ref_uid = intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where user_name = '".strim($GLOBALS['request']['ref_uname'])."'"));
		}


		require_once APP_ROOT_PATH."system/model/user.php";
		$user_data['user_pwd'] = md5($user_pwd);
                $user_data['mobile'] = $user_mobile;
                
		if($user_data['user_pwd']=='')
		{
			return output("",0,"请输入密码");
		}

		$user_data['pid'] = $ref_uid;

		$res = auto_create($user_data,1);

		if($res['status'] == 1)
		{
			
			$user_id = intval($res['data']);
            //更新用户名为手机号 
            $auto_user_name = $user_mobile."_".$user_id;
			//更新来路
			$GLOBALS['db']->query("update ".DB_PREFIX."user set user_name = '".$auto_user_name."',referer = '".$GLOBALS['referer']."' where id = ".$user_id);

			//在此自动登录
			$result = do_login_user($user_data['mobile'],$user_data['user_pwd']);

			$s_user_info = es_session::get("user_info");
			if($result['status'])
			{
			    	
			    $GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$user_mobile."'");
			    $return['status'] = true;
			    	
			    $str="<p style='text-align:center;'>注册成功</p>";
			    if(app_conf("USER_REGISTER_SCORE")){
			        $str.="<p style='text-align:center;color:#dd344f'>+".app_conf("USER_REGISTER_SCORE")."积分</p>";
			    }
			}
			$data['id'] = $s_user_info['id'];
			$data['user_name'] = $s_user_info['user_name'];
			$data['user_pwd'] = $s_user_info['user_pwd'];
			$data['email'] = $s_user_info['email'];
			$data['mobile'] = $s_user_info['mobile'];
			$data['is_tmp'] = $s_user_info['is_tmp'];

			//原来为直接挑战 现改为 完善资料
			return output($data,1,$str);

		}
		else
		{
			$error = $res['data'];
			if($error['field_name']=="user_name")
			{
				$error_field = "用户名";
			}
			elseif($error['field_name']=="email")
			{
				$error_field = "邮箱";
			}
			elseif($error['field_name']=="user_pwd")
			{
				$error_field = "密码";
			}

			if($error['error']==EMPTY_ERROR)
			{
				$error_msg = $error_field."不能为空";
			}
			if($error['error']==FORMAT_ERROR)
			{
				$error_msg = $error_field."格式错误";
			}
			if($error['error']==EXIST_ERROR)
			{
				$error_msg = $error_field."已经存在";
			}

			return output("",0,$error_msg);

		}
	}

    /**
     * 更新用户名
     * 如果用户名存在(不包含自己)则返回错误
     * 如果用户不存在则更新用户名
     */
    public function update_user_name(){
            $user_info = es_session::get("user_info");
            $id=$user_info["id"];
            if(empty($id)){
                return output("",0,"当前无用户登录");
            }
            $user_name=$GLOBALS['request']["user_name"];
            $user_name_number=intval($GLOBALS["db"]->getOne("select count(*) from ".DB_PREFIX."user where user_name ='".$user_name."' and id!=".$user_info['id']));
            if($user_name_number>0){
                return output("",0,"你所要更改的用户名已存在");
            }
            else{
                $GLOBALS["db"]->query("update ".DB_PREFIX."user set user_name='".$user_name."' where id='".$id."'");
                return output("",1,"更新用户成功");
            }
    }
    public function check_user_name_is_repeat(){
        $user_info = es_session::get("user_info");
        $id=$user_info["id"];
        if(empty($id)){
            return output("",0,"当前无用户登录");
        }
        $user_name=$GLOBALS['request']["user_name"];
        $user_name_number=intval($GLOBALS["db"]->getOne("select count(*) from ".DB_PREFIX."user where user_name ='".$user_name."' and id!=".$user_info['id']));
        if($user_name_number>0){
            return output("",0,"你所要更改的用户名已存在");
        }
        else{
            return output("",1,"更新用户成功");
        }
    }
}
?>