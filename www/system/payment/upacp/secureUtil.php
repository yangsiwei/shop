<?php



/**
 * 签名
 *
 * @param String $params_str
 */
function sign(&$params) {
	
	if(isset($params['transTempUrl'])){
		unset($params['transTempUrl']);
	}
	
	
	// 转换成key=val&串
	$params_str = coverParamsToString ( $params );
	
	$params_sha1x16 = sha1 ( $params_str, FALSE );
	// 签名证书路径
	$cert_path = SDK_SIGN_CERT_PATH;
	$private_key = getPrivateKey ( $cert_path );
	// 签名
	$sign_falg = openssl_sign ( $params_sha1x16, $signature, $private_key, OPENSSL_ALGO_SHA1 );
	if ($sign_falg) {
		$signature_base64 = base64_encode ( $signature );
		$params ['signature'] = $signature_base64;
	} else {
	}
}

/**
 * 验签
 *
 * @param String $params_str        	
 * @param String $signature_str        	
 */
function verify($params) {
	global $log;
	// 公钥
	$public_key = getPulbicKeyByCertId ( $params ['certId'] );	
//	echo $public_key.'<br/>';
	// 签名串
	$signature_str = $params ['signature'];
	unset ( $params ['signature'] );
	$params_str = coverParamsToString ( $params );
	$signature = base64_decode ( $signature_str );
//	echo date('Y-m-d',time());
	$params_sha1x16 = sha1 ( $params_str, FALSE );
	$isSuccess = openssl_verify ( $params_sha1x16, $signature,$public_key, OPENSSL_ALGO_SHA1 );
	return $isSuccess;
}

/**
 * 根据证书ID 加载 证书
 *
 * @param unknown_type $certId        	
 * @return string NULL
 */
function getPulbicKeyByCertId($certId) {
	global $log;
	// 证书目录
	$cert_dir = SDK_VERIFY_CERT_DIR;
	$handle = opendir ( $cert_dir );
	if ($handle) {
		while ( $file = readdir ( $handle ) ) {
			clearstatcache ();
			$filePath = $cert_dir . '/' . $file;
			if (is_file ( $filePath )) {
				if (pathinfo ( $file, PATHINFO_EXTENSION ) == 'cer') {
					if (getCertIdByCerPath ( $filePath ) == $certId) {
						closedir ( $handle );
						return getPublicKey ( $filePath );
					}
				}
			}
		}
	} else {
	}
	closedir ( $handle );
	return null;
}

/**
 * 取证书ID(.pfx)
 *
 * @return unknown
 */
function getCertId($cert_path) {
	$pkcs12certdata = file_get_contents ( $cert_path );
	openssl_pkcs12_read ( $pkcs12certdata, $certs, SDK_SIGN_CERT_PWD );
	$x509data = $certs ['cert'];
	openssl_x509_read ( $x509data );
	$certdata = openssl_x509_parse ( $x509data );
	$cert_id = $certdata ['serialNumber'];
	return $cert_id;
}

/**
 * 取证书ID(.cer)
 *
 * @param unknown_type $cert_path        	
 */
function getCertIdByCerPath($cert_path) {
	$x509data = file_get_contents ( $cert_path );
	openssl_x509_read ( $x509data );
	$certdata = openssl_x509_parse ( $x509data );
	$cert_id = $certdata ['serialNumber'];
	return $cert_id;
}

/**
 * 签名证书ID
 *
 * @return unknown
 */
function getSignCertId() {
	// 签名证书路径
	return getCertId ( SDK_SIGN_CERT_PATH );
}
function getEncryptCertId() {
	// 签名证书路径
	return getCertIdByCerPath ( SDK_ENCRYPT_CERT_PATH );
}

/**
 * 取证书公钥 -验签
 *
 * @return string
 */
function getPublicKey($cert_path) {
	return file_get_contents ( $cert_path );
}
/**
 * 返回(签名)证书私钥 -
 *
 * @return unknown
 */
function getPrivateKey($cert_path) {
	$pkcs12 = file_get_contents ( $cert_path );
	openssl_pkcs12_read ( $pkcs12, $certs, SDK_SIGN_CERT_PWD );
	return $certs ['pkey'];
}

/**
 * 加密 卡号
 *
 * @param String $pan
 *        	卡号
 * @return String
 */
function encryptPan($pan) {
	$cert_path = MPI_ENCRYPT_CERT_PATH;
	$public_key = getPublicKey ( $cert_path );
	
	openssl_public_encrypt ( $pan, $cryptPan, $public_key );
	return base64_encode ( $cryptPan );
}
/**
 * pin 加密
 *
 * @param unknown_type $pan        	
 * @param unknown_type $pwd        	
 * @return Ambigous <number, string>
 */
function encryptPin($pan, $pwd) {
	$cert_path = SDK_ENCRYPT_CERT_PATH;
	$public_key = getPublicKey ( $cert_path );

	return EncryptedPin ( $pwd, $pan, $public_key );
}
/**
 * cvn2 加密
 *
 * @param unknown_type $cvn2        	
 * @return unknown
 */
function encryptCvn2($cvn2) {
	$cert_path = SDK_ENCRYPT_CERT_PATH;
	$public_key = getPublicKey ( $cert_path );
	
	openssl_public_encrypt ( $cvn2, $crypted, $public_key );
	
	return base64_encode ( $crypted );
}
/**
 * 加密 有效期
 *
 * @param unknown_type $certDate        	
 * @return unknown
 */
function encryptDate($certDate) {
	$cert_path = SDK_ENCRYPT_CERT_PATH;
	$public_key = getPublicKey ( $cert_path );
	
	openssl_public_encrypt ( $certDate, $crypted, $public_key );
	
	return base64_encode ( $crypted );
}

/**
 * 加密 数据
 *
 * @param unknown_type $certDatatype
 * @return unknown
 */
function encryptDateType($certDataType) {
	$cert_path = SDK_ENCRYPT_CERT_PATH;
	$public_key = getPublicKey ( $cert_path );

	openssl_public_encrypt ( $certDataType, $crypted, $public_key );

	return base64_encode ( $crypted );
}

?>