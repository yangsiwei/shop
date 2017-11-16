<?php
require_once(APP_ROOT_PATH. 'system/umeng/notification/AndroidNotification.php');

class AndroidUnicast extends AndroidNotification {
	function __construct() {
		parent::__construct();
		$this->data["type"] = "unicast";
		$this->data["device_tokens"] = NULL;
	}

}