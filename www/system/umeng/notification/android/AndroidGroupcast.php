<?php
require_once(APP_ROOT_PATH. 'system/umeng/notification/AndroidNotification.php');

class AndroidGroupcast extends AndroidNotification {
	function  __construct() {
		parent::__construct();
		$this->data["type"] = "groupcast";
		$this->data["filter"]  = NULL;
	}
}