<?php
/**
 * 微信分享
 */
function getSignPackage(){
    require_once APP_ROOT_PATH."system/wechat/wechat.class.php";
    $wx = getWxObject();
    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $signPackage = $wx->getJsSign($url);
    return $signPackage;
} 

/**
 * 微信获取带参数的二维码
 */
function getQrCode($user_id=''){
    require_once APP_ROOT_PATH."system/wechat/wechat.class.php";
    $wx = getWxObject();
    $num = rand(999, 10000);
    $user_info = es_session::get('user_info');
    
    // 判断是否开启二渠道维码功能
    // 时时查询用户是否开启渠道功能
    if(!$user_id){
        $user_id = $user_info['id'];
    }
    
    $user_id = intval($user_id); 
    $is_open_scan = $GLOBALS['db']->getOne("select is_open_scan from ".DB_PREFIX."user where id={$user_id}");
    
    $fx_type_qrcode = $GLOBALS['db']->getOne("select fx_type_qrcode from ".DB_PREFIX."fx_salary");
    
    $qrcode_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=';
    if($is_open_scan){
        if($fx_type_qrcode == 1){
            $result = $wx->getQRCode($user_id, 1);
            $img_url = $qrcode_url.urlencode($result['ticket']);
        }else{
            $result = $wx->getQRCode($user_id);
            $img_url = $qrcode_url.urlencode($result['ticket']);
        }
        return $img_url;
    }else{
        echo '用户不支持渠道二维码功能';
        exit;
    }
}


function getWxObject(){
    $weixin_res = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_account_conf");
    foreach($weixin_res as $k=>$v){
        $weixin_conf[$v['name']]=$v['value'];
    }
     
    $option = array(
        'appid'=>$weixin_conf['appid'],
        'appsecret'=>$weixin_conf['appsecret'],
        'token'=>$weixin_conf['token'],
        'encodingAesKey'=>$weixin_conf['encodingAesKey'],
        'debug'=>false,
    );
    
    require_once APP_ROOT_PATH."system/wechat/wechat.class.php";
    $wx= new Wechat($option);
    
    
    return $wx;
}