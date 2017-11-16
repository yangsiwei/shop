<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class user_apnsApiModule extends MainBaseApiModule
{
	
	/**
	 * 推送注册与注销
	 * 输入
	 * dev_type: string 设备类型 android/ios
	 * device_token:string 
	 * 
	 * 输出
	 * 
	 */
	public function index()
	{
		$root = array();
		//检查用户,用户密码
		$user = $GLOBALS['user_info'];
        $user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED){
		    $root['user_login_status'] = $user_login_status;
		}
		else
		{
			$root['user_login_status'] = $user_login_status;
			$user_id  = intval($user['id']);
			
			if ($user_id > 0){
				//手机类型dev_type=android,ios		
				$data = array();
				$data['dev_type'] = strim($GLOBALS['request']['dev_type']);
				$data['device_token'] = strim($GLOBALS['request']['device_token']);
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."user", $data, 'UPDATE','id = '.$user_id);
			}
		}
		
		return output($root);
	}
	
}
?>

