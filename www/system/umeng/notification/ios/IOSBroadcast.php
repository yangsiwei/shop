<?php
require_once(APP_ROOT_PATH. 'system/umeng/notification/IOSNotification.php');

class IOSBroadcast extends IOSNotification {
	function  __construct() {
		parent::__construct();
		$this->data["type"] = "broadcast";
	}
}