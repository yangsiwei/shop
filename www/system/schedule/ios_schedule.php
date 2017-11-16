<?php

require_once(APP_ROOT_PATH.'system/libs/schedule.php');
class ios_schedule implements schedule {
	
	/**
	 * $data 格式
	 * array("dest"=>device_tokens,"content"=>序列化的消息配置);
	 */
	public function exec($data){
		
		
		require_once(APP_ROOT_PATH. 'system/umeng/notification/ios/IOSUnicast.php');
				
		try {
			$appMasterSecret = $GLOBALS['db']->getOne("select val from ".DB_PREFIX."m_config where code = 'ios_master_secret'");
			$appkey = $GLOBALS['db']->getOne("select val from ".DB_PREFIX."m_config where code = 'ios_app_key'");


			$unicast = new IOSUnicast();
			$unicast->setAppMasterSecret($appMasterSecret);
			$unicast->setPredefinedKeyValue("appkey",           $appkey);
			$unicast->setPredefinedKeyValue("timestamp",        strval(time()));
			// Set your device tokens here
			$unicast->setPredefinedKeyValue("device_tokens",    $data['dest']);
			$unicast->setPredefinedKeyValue("alert", $data['content']);
			$unicast->setPredefinedKeyValue("badge", 1);
			$unicast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$unicast->setPredefinedKeyValue("production_mode", "true");
			$result = $unicast->send();

			$res = json_decode($result,1);
			//print("Sent SUCCESS\r\n");
			if ($res['ret'] == 'SUCCESS'){
				$is_success = 1;
			}else{
				$is_success = 0;
				$message = addslashes(print_r($result,true));
			}
				
		} catch (Exception $e) {
			$is_success = 0;
			$message = strim($e->getMessage());
			return false;
		}
	
		$result = array();
		$result['status'] = $is_success;
		$result['attemp'] = 0;
		$result['info'] = $message;
		return $result;
	}	
}
?>