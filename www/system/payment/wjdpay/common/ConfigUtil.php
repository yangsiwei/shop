<?php

//namespace wepay\join\demo\common;

class ConfigUtil {
	public static function get_val_by_key($key) {
		$settings = new Settings_INI ();
		$settings->load ();
		return $settings->get ( $key );
	}
	public static function get_trade_num() {
		return ConfigUtil::get_val_by_key ( 'merchantNum' ) . ConfigUtil::getMillisecond ();
	}
	public static function getMillisecond() {
		list ( $s1, $s2 ) = explode ( ' ', microtime () );
		return ( float ) sprintf ( '%.0f', (floatval ( $s1 ) + floatval ( $s2 )) * 1000 );
	}
}
class Settings {
	var $_settings = array ();
	/**
	 * 获取某些设置的值
	 *
	 * @param unknown_type $var        	
	 * @return unknown
	 */
    function get($var) {
		$result = $this->_settings;
		return $result [$var];
	}
	function load($file) {
		trigger_error ( 'Not yet implemented', E_USER_ERROR );
	}
}
class Settings_INI extends Settings {
	function load() {
	    $payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Wjdpay'");
	    $config = unserialize($payment_info['config']);
		if ( $config ) {
			$this->_settings = $config;
		}
	}
}

//echo ConfigUtil::get_val_by_key("merchantNum");
//echo ConfigUtil::get_trade_num();

?>