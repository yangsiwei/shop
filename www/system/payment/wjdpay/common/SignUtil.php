<?php

//namespace wepay\join\demo\common;

require_once(APP_ROOT_PATH.'system/payment/wjdpay/common/RSAUtils.php');
/**
 * 签名
 *
 * @author wylitu
 *        
 */
class SignUtil {
	
	public static function signWithoutToHex($params,$unSignKeyList) {
		ksort($params);
  		$sourceSignString = SignUtil::signString ( $params, $unSignKeyList );
  		$sha256SourceSignString = hash ( "sha256", $sourceSignString);	
	   return RSAUtils::encryptByPrivateKey ($sha256SourceSignString);
	}
	
	public static function sign($params,$unSignKeyList) {
		ksort($params);
		$sourceSignString = SignUtil::signString ( $params, $unSignKeyList );
		error_log($sourceSignString, 0);
		$sha256SourceSignString = hash ( "sha256", $sourceSignString);
		error_log($sha256SourceSignString, 0);
		return RSAUtils::encryptByPrivateKey ($sha256SourceSignString);
	}
	
	public static function signString($data, $unSignKeyList) {
		$linkStr="";
		$isFirst=true;
		ksort($data);
		foreach($data as $key=>$value){
			if($value==null || $value==""){
				continue;
			}
			$bool=false;
			foreach ($unSignKeyList as $str) {
				if($key."" == $str.""){
					$bool=true;
					break;
				}
			}
			if($bool){
				continue;
			}
			if(!$isFirst){
				$linkStr.="&";
			}
			$linkStr.=$key."=".$value;
			if($isFirst){
				$isFirst=false;
			}
		}
		return $linkStr;
	}
}

// $params['currency']="CNY";
// $params['version']="";


// $unSignKeyList = array ("sign");
// echo SignUtil::signWithoutToHex ( $params ,$unSignKeyList);

?>