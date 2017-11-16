<?php
require_once(APP_ROOT_PATH. 'system/umeng/notification/IOSNotification.php');

class IOSGroupcast extends IOSNotification {
	function  __construct() {
		parent::__construct();
		$this->data["type"] = "groupcast";
		$this->data["filter"]  = NULL;
	}
}