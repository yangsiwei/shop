<?php
require_once(APP_ROOT_PATH. 'system/umeng/notification/AndroidNotification.php');

class AndroidBroadcast extends AndroidNotification {
	function  __construct() {
		parent::__construct();
		$this->data["type"] = "broadcast";
	}
}