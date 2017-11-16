<?php
class syncbindApiModule  extends MainBaseApiModule
{
	/**
	 * 微博绑定接口
	 * 输入:
	 * login_type:string 同步登录的类型: Sina/Qq
	 * sina_id: string 新浪的唯一会员ID 
	 * qqv2_id: string QQ的唯一会员ID
	 * access_token: string 相应的access_token
	 * 
	 * 输出:
	 * user_login_status:int   0表示未登录   1表示已登录  2表示临时登录
	 * status: int 状态 0,1
	 * info: string 消息返回
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
		$user_login_status = check_login();
		$root['user_login_status'] = $user_login_status;
		if($user_login_status!=LOGIN_STATUS_LOGINED)
		{
			return output($root);
		}
		
		$sina_id = strim($GLOBALS['request']['sina_id']);
		$access_token = trim($GLOBALS['request']['access_token']);
		

		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where sina_id = '".$sina_id."'")==0)
    		$GLOBALS['db']->query("update ".DB_PREFIX."user set sina_token ='".$access_token."', sina_id = '".$sina_id."' where id =".intval($GLOBALS['user_info']['id']));				
		elseif(intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where sina_id = '".$sina_id."'"))==intval($GLOBALS['user_info']['id']))
   		{
   			$GLOBALS['db']->query("update ".DB_PREFIX."user set sina_token ='".$access_token."', sina_id = '".$sina_id."' where id =".intval($GLOBALS['user_info']['id']));							
   		}
   		else
   		{
   			return output(array(),0,"该微博帐号已被其他会员绑定");
   		}
   		
		return output($root,1,"绑定成功");
}

function Qq()
{

		$user_login_status = check_login();
		$root['user_login_status'] = $user_login_status;
		if($user_login_status!=LOGIN_STATUS_LOGINED)
		{
			return output($root);
		}
	
		$qqv2_id = trim($GLOBALS['request']['qqv2_id']);
		$access_token = trim($GLOBALS['request']['access_token']);
		

		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where qqv2_id = '".$qqv2_id."'")==0)
    		$GLOBALS['db']->query("update ".DB_PREFIX."user set qqv2_id = '".$qqv2_id."',qq_token='".$access_token."' where id =".intval($GLOBALS['user_info']['id']));				
		elseif(intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where qqv2_id = '".$qqv2_id."'"))==intval($GLOBALS['user_info']['id']))
   		{
   			$GLOBALS['db']->query("update ".DB_PREFIX."user set qqv2_id = '".$qqv2_id."',qq_token='".$access_token."' where id =".intval($GLOBALS['user_info']['id']));							
   		}
   		else
   		{
   			return output(array(),0,"帐号已被其他会员绑定");
   		}

		return output($root,1,"绑定成功");
}
?>