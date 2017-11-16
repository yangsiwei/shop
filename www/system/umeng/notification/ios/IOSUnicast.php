<?php
require_once(APP_ROOT_PATH. 'system/umeng/notification/IOSNotification.php');

class IOSUnicast extends IOSNotification {
	function __construct() {
		parent::__construct();
		$this->data["type"] = "unicast";
		$this->data["device_tokens"] = NULL;
	}

}