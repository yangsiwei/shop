<?php


class syncloginApiModule  extends MainBaseApiModule
{
	/**
	 * 同步登录的接口
	 * 输入
	 * login_type:string 同步登录的类型: Sina/Qq
	 * nickname: string 会员名,新浪取:screen_name, QQ取nickname
	 * sina_id: string 新浪的唯一会员ID 
	 * qqv2_id: string QQ的唯一会员ID
	 * access_token: string 相应的access_token
	 * 
	 * 输出
	 * status: int 状态 0,1
	 * info: string 消息返回
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
	public function index()
	{			
		$func_name = strim($GLOBALS['request']['login_type']);
		$func_name();
	}	
}


function Sina()
{
		$sina_id = strim($GLOBALS['request']['sina_id']);
		$access_token = strim($GLOBALS['request']['access_token']);
		$name = strim($GLOBALS['request']['nickname']);

		
		$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where sina_id = '".$sina_id."' and sina_id <> '' and is_delete = 0");	
		
		if($user_data)
		{
			if($user_data['is_effect']==0)
			{
				return output(array(),0,"会员被禁用");
			}
			else
			{
				//同步登录
				require_once APP_ROOT_PATH."system/model/user.php";
				auto_do_login_user($user_data['user_name'],$user_data['user_pwd'],$from_cookie = false);
				
				$s_user_info = es_session::get("user_info");
				$data['id'] = $s_user_info['id'];
				$data['user_name'] = $s_user_info['user_name'];
				$data['user_pwd'] = $s_user_info['user_pwd'];
				$data['email'] = $s_user_info['email'];
				$data['mobile'] = $s_user_info['mobile'];
				$data['is_tmp'] = $s_user_info['is_tmp'];
				return output($data,1,"登录成功");
			}					
		}
		else
		{
			//自动创建
			$user_data = array();
			$user_data['user_name'] = $name;
			
			$user_data['sina_id'] = $sina_id;
			$user_data['sina_token'] = $access_token;
			
			$result = auto_create($user_data, 0);
			if($result['status']){
				$s_user_info = $result['user_data'];
				$data['id'] = $s_user_info['id'];
				$data['user_name'] = $s_user_info['user_name'];
				$data['user_pwd'] = $s_user_info['user_pwd'];
				$data['email'] = $s_user_info['email'];
				$data['mobile'] = $s_user_info['mobile'];
				$data['is_tmp'] = $s_user_info['is_tmp'];
				return output($data,1,"登录成功");
			}else{
				return output(array(),0,"注册失败");
			}
		}
}

function Qq()
{
		$qqv2_id = strim($GLOBALS['request']['qqv2_id']);
		$access_token = strim($GLOBALS['request']['access_token']);
		$name = strim($GLOBALS['request']['nickname']);

		$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where qqv2_id = '".$qqv2_id."' and qqv2_id <> '' and is_delete = 0");	
	
		if($user_data)
		{
			if($user_data['is_effect']==0)
			{
				return output(array(),0,"会员被禁用");
			}
			else
			{
				//同步登录
				require_once APP_ROOT_PATH."system/model/user.php";
				auto_do_login_user($user_data['user_name'],$user_data['user_pwd'],$from_cookie = false);
				
				$s_user_info = es_session::get("user_info");
				$data['id'] = $s_user_info['id'];
				$data['user_name'] = $s_user_info['user_name'];
				$data['user_pwd'] = $s_user_info['user_pwd'];
				$data['email'] = $s_user_info['email'];
				$data['mobile'] = $s_user_info['mobile'];
				$data['is_tmp'] = $s_user_info['is_tmp'];
				return output($data,1,"登录成功");
			}
		}
		else
		{
			//自动创建
			$user_data = array();
			$user_data['user_name'] = $name;
			
			$user_data['qqv2_id'] = $qqv2_id;
			$user_data['qq_token'] = $access_token;
			
			$result = auto_create($user_data, 0);
			
			if($result['status']){
				$s_user_info = $result['user_data'];
				$data['id'] = $s_user_info['id'];
				$data['user_name'] = $s_user_info['user_name'];
				$data['user_pwd'] = $s_user_info['user_pwd'];
				$data['email'] = $s_user_info['email'];
				$data['mobile'] = $s_user_info['mobile'];
				$data['is_tmp'] = $s_user_info['is_tmp'];
				return output($data,1,"登录成功");
			}else{
				return output(array(),0,"注册失败");
			}
		}

}

?>