<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class uc_accountApiModule extends MainBaseApiModule
{
	
	/**
	 * 用户信息完善页面接口
	 * 
	 * 输入：无
	 * 
	 * 输出：
	 * user_login_status:int 用户登录状态(1 已经登录/0 用户未登录/2临时用户)
	 * user_info:array 会员信息
	 * Array(
	 *  id:int 会员ID
	 *  user_name:string 会员名
	 *  user_pwd:string 加密过的密码
	 *  email:string 邮箱
	 *  mobile:string 手机号
	 *  is_tmp: int 是否为临时会员 0:否 1:是
	 * )
	 */
	public function index()
	{
		$root = array();	
		$user_data = $GLOBALS['user_info'];
		$user_login_status = check_login();
		$root['user_login_status'] = $user_login_status;				
			
		$s_user_info = $GLOBALS['user_info'];
		$data['id'] = $s_user_info['id'];
		$data['user_name'] = $s_user_info['user_name'];
		$data['user_pwd'] = $s_user_info['user_pwd'];
		$data['email'] = $s_user_info['email'];
		$data['mobile'] = $s_user_info['mobile'];
		$data['is_tmp'] = $s_user_info['is_tmp'];
                $data['is_phone_register'] = intval($s_user_info['is_phone_register']);
		$root['user_info'] = $data;
		
		$root['page_title'] = "会员资料";
		
		return output($root);

		

	}
	
	
	/**
	 * 临时会员更新会员资料接口
	 * 
	 * 输入:
	 * user_name:string 用户名
	 * user_email:string 邮箱
	 * user_pwd:string 密码
	 * 
	 * 输出:
	 * user_login_status:int 用户登录状态(1 已经登录/0 用户未登录/2临时用户)
	 * status:int 结果状态 0失败 1成功
	 * info:信息返回
	 * 
	 * 	 以下六项仅在status为1时会返回
	 *  id:int 会员ID
	 *  user_name:string 会员名
	 *  user_pwd:string 加密过的密码
	 *  email:string 邮箱
	 *  mobile:string 手机号
	 *  is_tmp: int 是否为临时会员 0:否 1:是
	 */
	public function save()
	{
		$root = array();
		$user_login_status = check_login();
		$root['user_login_status'] = $user_login_status;
		
		$user_name = strim($GLOBALS['request']['user_name']);
		$email = strim($GLOBALS['request']['user_email']);
		$user_pwd = strim($GLOBALS['request']['user_pwd']);
		
		if($GLOBALS['user_info']['is_tmp']==1)
		{			
			if($user_name=="")
			{
				return output($root,0,"请输入您的用户名");
			}
            if($GLOBALS['user_info']['is_phone_register']==0){
                if($user_pwd=="")
                {
                        return output($root,0,"请设置您的登录密码");
                }
                $user_data['user_pwd'] = $user_pwd;
            }else{
                $user_data['user_pwd'] = '';
            }
			
			
			//
			$user_data['user_name'] = $user_name;
			$user_data['email'] = $email;
			
			$user_data['id'] = $GLOBALS['user_info']['id'];
			$res = save_user($user_data,'UPDATE');
			if($res['status'] == 1)
			{			
				do_login_user($user_data['user_name'],$user_data['user_pwd']);
				
				$s_user_info = es_session::get("user_info");
				$root['id'] = $s_user_info['id'];
				$root['user_name'] = $s_user_info['user_name'];
				$root['user_pwd'] = $s_user_info['user_pwd'];
				$root['email'] = $s_user_info['email'];
				$root['mobile'] = $s_user_info['mobile'];
				$root['is_tmp'] = $s_user_info['is_tmp'];
				return output($root,1,"资料更新成功");
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
				
				return output($root,0,$error_msg);
			}			
			//
		}
		else
		{
			return output($root,0,"资料已经更新");
		}
		
		
		
	}
	
	/**
	 * 上传用户头像
	 * 
	 * 输入：
	 * $_FILES['file']：头像文件
	 * 
	 * 输出：
	 * status: int 0失败 1成功
	 * info:string 信息提示
	 * small_url: string 头像小图
	 * middle_url:string 头像中图
	 * big_url:string 头像大图
	 */
	public function upload_avatar()
	{
		$root = array();

		if($GLOBALS['user_info'])
		{
			if($_FILES['file'])
			{
				$res = upload_avatar($_FILES, $GLOBALS['user_info']['id']);
				if($res['error']==0)
				{
					$root['small_url'] = $res['small_url'];
					$root['middle_url'] = $res['middle_url'];
					$root['big_url'] = $res['big_url'];
					
					return output($root);
				}
				else
				{
					return output($root,0,$res['message']);
				}
			}
			else
			{
				return output($root,0,"请上传文件");
			}
		}
		else
		{
			return output($root,0,"请先登录");
		}		
		
	}
	
}
?>