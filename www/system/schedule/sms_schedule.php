<?php

require_once(APP_ROOT_PATH.'system/libs/schedule.php');
class sms_schedule implements schedule {
	
	/**
	 * $data 格式
	 * array("dest"=>xxxxx,"content"=>xxxxxx);
	 */
	public function exec($data){
		
		//短信
		require_once APP_ROOT_PATH."system/utils/es_sms.php";
		$sms = new sms_sender();
		$return = $sms->sendSms($data['dest'],$data['content']);
		
		$result['status'] = intval($return['status']);
		$result['attemp'] = 0;
		$result['info'] = $return['msg'];
		return $result;
				
	}	
}
?>