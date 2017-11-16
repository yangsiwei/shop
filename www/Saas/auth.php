<?php

require_once(__DIR__.'/SAASAPIClient.php');
require_once(__DIR__.'/SAASAPIServer.php');

define("_FANWE_SAAS_LICENSE_AUTH_URL", "http://saas.fanwe.com/license/get");
define("_FANWE_SAAS_LICENSE_ROOT_DIR", __DIR__."/licenses/");

function _fsauth_failAndExit($errmsg)
{
    echo $errmsg;
    exit;
}

function _fsauth_findLicenseFile()
{
    // 获取域名信息
    $domain = $_SERVER["HTTP_HOST"];
    // 直接查找当前域名对应子目录下的License文件是否存在，若存在则返回License文件路径，否则继续
    $licfile = _FANWE_SAAS_LICENSE_ROOT_DIR.$domain.'/license';
    if (file_exists($licfile)) {
        return $licfile;
    }
    // 查找通配子域名目录，只要找到匹配的License文件，就认为是当前域名的授权文件，不管License文件中是否包含该域名
    $licfile = '';
    $fanwe = new Fanwe;
    $fixedDomain = $domain;
    do {
        // 获取子域名值
        $dotpos = strpos($fixedDomain, '.');
        if ($dotpos === false) {
            break;
        }
        $fixedDomain = trim(substr($fixedDomain, $dotpos+1));
        if ($fixedDomain == '') {
            break;
        }
        // 根据通配子域名查找
        $tempfile = _FANWE_SAAS_LICENSE_ROOT_DIR.'^.'.$fixedDomain.'/license';
        if (file_exists($tempfile)) {
            $licfile = $tempfile;
        }
    } while(empty($licfile));
    // 通配域名目录没找到时，在到License根目录查找，是否存在license文件
    if ($licfile == '' && file_exists(_FANWE_SAAS_LICENSE_ROOT_DIR.'license')) {
        $licfile = _FANWE_SAAS_LICENSE_ROOT_DIR.'license';
    }
    // 返回结果
    return $licfile;
}

function _fsauth_getLicenseFile()
{
    // 从本地查找匹配当前域名的License文件，若找到且存在则返回Liccense文件路径，否则继续
    $licfile = _fsauth_findLicenseFile();
    if ($licfile != '' && file_exists($licfile)) {
        return $licfile;
    }
    // 判断是否存在公共的接口证书文件，若不存在则返回空路径，否则继续（获取最新授权文件）
    $keyfile = __DIR__.'/saasapi.key';
    if (!file_exists($keyfile)) {
        return '';
    }
    // 到SAAS基础平台获取最新的授权文件
    $domain = $_SERVER["HTTP_HOST"];
    $keystr = file_get_contents($keyfile);
    $keyinfo = json_decode($keystr, true);
    $appid = array_key_exists('appid', $keyinfo) ? $keyinfo['appid'] : '';
    $appsecret = array_key_exists('appsecret', $keyinfo) ? $keyinfo['appsecret'] : '';
    $client = new SAASAPIClient($appid, $appsecret);
    $ret = $client->invoke(_FANWE_SAAS_LICENSE_AUTH_URL, array('domain'=>$domain));
    if (empty($ret) || $ret['errcode'] != 0) {
        _fsauth_failAndExit('The domain is not licensed, Online license error: '.$ret['errmsg']);
        return '';
    }
    // 保存授权文件（保存到SAAS服务端返回的授权域名目录下）
    $retdata = $ret['data'];
    $licenseDomain = $retdata['domain'];
    $licenseContent = $retdata['license'];
    if (strpos($licenseDomain, '*.') === 0) { // 替换掉域名中的星号
        $licenseDomain = str_replace('*', '^', $licenseDomain); // 替换掉星号
    }
    $licpath = _FANWE_SAAS_LICENSE_ROOT_DIR.$licenseDomain.'/';
    $licfile = $licpath.'license';
    if (!file_exists($licpath)) {
        mkdir($licpath, 0777, true);
    }
    file_put_contents($licfile, $licenseContent);
    // 返回
    return $licfile;
}

function _fsauth_strEndWith($haystack, $needle)
{   
    $length = strlen($needle);  
    if ($length == 0) {    
        return true;  
    }  
    return (substr($haystack, -$length) === $needle);
}

// 进行授权认证
// 1. 获取授权文件路径，授权文件不存在时，根据情况到SAAS基础平台获取
$licfile = _fsauth_getLicenseFile();
if ($licfile == '' || !file_exists($licfile)) {
    _fsauth_failAndExit('The domain is not licensed!');
    return;
}
// 2. 进行授权判断
define("LICENSE_PATH", dirname($licfile).'/');
$fanwe = new Fanwe;
$fanwe->init();

