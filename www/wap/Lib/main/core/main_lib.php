<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

/**
 * 刷新会员安全登录状态
 */
function refresh_user_info()
{
	global $user_info;
	global $user_logined;
	//实时刷新会员数据
	if($user_info)
	{
		$user_info = load_user($user_info['id']);
		$user_level = load_auto_cache("cache_user_level");
		$user_info['level'] = $user_level[$user_info['level_id']]['level'];
		$user_info['level_name'] = $user_level[$user_info['level_id']]['name'];
		es_session::set('user_info',$user_info);

		$user_logined_time = intval(es_session::get("user_logined_time"));
		$user_logined = es_session::get("user_logined");
		if(NOW_TIME-$user_logined_time>=MAX_LOGIN_TIME)
		{
			es_session::set("user_logined_time",0);
			es_session::set("user_logined", false);
			$user_logined = false;
		}
		else
		{
			if($user_logined)
				es_session::set("user_logined_time",NOW_TIME);
		}
	}
}

/**
 * 前端全运行函数，生成系统前台使用的全局变量
 * 1. 定位城市 GLOBALS['city'];
 * 2. 加载会员 GLOBALS['user_info'];
 * 3. 定位经纬度 GLOBALS['geo'];
 * 4. 加载推荐人与来路
 * 5. 更新购物车
 */
function global_run()
{
        global $ref_uid;
        global $global_is_run;
        $global_is_run = true;
	//保存返利的cookie
	if($_REQUEST['r'])
	{
		$rid = intval(base64_decode($_REQUEST['r']));
		$ref_uid = intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where id = ".intval($rid)));
		es_cookie::set("ref_uid",intval($ref_uid));
	}
	else
	{
		//获取存在的推荐人ID
		if(intval(es_cookie::get("ref_uid"))>0)
			$ref_uid = intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where id = ".intval(es_cookie::get("ref_uid"))));
	}

	//会员自动登录及输出
	global $cookie_uname;
	global $cookie_upwd;
	global $user_info;
	global $user_logined;
	global $wx_info;
	global $is_weixin; //是否为微信访问

	$is_weixin=isWeixin();
	$user_info = es_session::get('user_info');
	$wx_info = es_session::get("wx_info"); //微信在平台的信息记录，openid为平台公众号的openid，wx_openid为商家公众号的openid

	$weixin_login = intval($_REQUEST['weixin_login']);
	if($weixin_login==1)
	{
		es_cookie::delete("deny_weixin_".intval($GLOBALS['user_info']['id']));
		$deny_weixin = 0;
	}
	else
	{
		$deny_weixin = intval(es_cookie::get("deny_weixin_".intval($GLOBALS['user_info']['id'])));
	}

	//$account_id = 23;
	if(empty($user_info))
	{

        $m_config = getMConfig();//初始化手机端配置
        // 微博登录
        if ( isset($_REQUEST['code'])  && $_REQUEST['wb_login'] == 1 ){
            require APP_ROOT_PATH . "system/weibo/saetv2.ex.class.php";

            $o = new SaeTOAuthV2( app_conf('WB_APP_KEY'), app_conf('WB_APP_SECRET') );

            $keys = array();
            $keys['code'] = trim($_REQUEST['code']);
            $keys['redirect_uri'] = get_domain().wap_url("index", "index#index", array('wb_login'=>1) );

            // 授权过的code会过期，授权过就不能再用了
            if( $_REQUEST['code'] != es_session::get('wb_code') ){

                try{
                    $token = $o->getAccessToken('code', $keys);
                }catch (Exception $e){
                    echo $e->getMessage();
                    exit();
                }

                if ($token) {
                    es_session::set('wb_code', $keys['code']);
                    // 通过session 和 cookie 缓存当前用户的token
                    es_session::set('wb_token', $token);
                    es_cookie::set("weibojs_". $o->client_id, http_build_query($token));

                    $c          = new SaeTClientV2( app_conf('WB_APP_KEY'), app_conf('WB_APP_SECRET'), $token['access_token'] );
                    $uid_get    = $c->get_uid();
                    $uid        = $uid_get['uid'];
                    $wb_user_info  = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息

                    if ($wb_user_info['error_code'] > 0) {
                        echo 'error:'.$wb_user_info['error'].' code:'.$wb_user_info['error_code'];exit;
                    }
                    
                    // 登录或者创建后自动登录用户
                    wb_info_login($wb_user_info); 

                } else {
                    echo '获取用户信息失败';
                    exit;
                }

            }

        }else{
            //关于微信登录
            if($is_weixin&&$deny_weixin==0)
            {
                require_once APP_ROOT_PATH.'system/utils/weixin.php';
                $weixin_conf = load_auto_cache("weixin_conf");
                if(WEIXIN_TYPE=="platform")
                {
                    //方维云平台saas模式接入
                    $appid = FANWE_APP_ID;
                    $appsecret = FANWE_AES_KEY;
                    $server = new SAASAPIServer($appid, $appsecret);
                    $ret = $server->takeSecurityParams($_SERVER['QUERY_STRING']);


                    if($ret['openid'])
                    {
                        $wx_info = $ret;
                        wx_info_login($wx_info, $GLOBALS['is_app']);

                    }else
                    {
                        //加密
                        $client = new SAASAPIClient($appid, $appsecret);
                        $widthAppid = true;  // 生成的安全地址是否附带appid参数
                        $timeoutMinutes = 10; // 安全参数过期时间（单位：分钟），小于等于0表示永不过期
                        $params['from'] = SITE_DOMAIN.wap_url("index");
                        $params['appsys_name'] = $GLOBALS['_FANWE_SAAS_ENV']['APPSYS_ID'];

                        $url = 'http://service.yun.fanwe.com/weixin/create_url';
                        $wx_url = $client->makeSecurityUrl($url, $params, $widthAppid, $timeoutMinutes);
                        //var_dump($wx_url);exit;
                        //$wx_url = 'http://service.yun.fanwe.com/weixin/create_url?from='.urlencode($back_url);
                        app_redirect($wx_url);
                    }

                }
                else
                {
                    if($m_config['wx_appid']&&$m_config['wx_secrit'])
                    {
                        $wx_code = strim($_REQUEST['code']);
                        $wx_status = intval($_REQUEST['state']);
                        if($wx_code&&$wx_status)
                        {
                            //微信端回跳回wap
                            $url =  get_current_url();
                            $weixin=new weixin($m_config['wx_appid'],$m_config['wx_secrit'],SITE_DOMAIN.$url);
                            $wx_info=$weixin->scope_get_userinfo($wx_code);
                        }

                        if($wx_info&&$wx_info['openid'])
                        {
                            wx_info_login($wx_info, $GLOBALS['is_app']);
                        }
                        else
                        {
                            //跳转至微信的授权页
// 						$url = get_current_url();
                            $url = wap_url("index");
                            $weixin = new weixin($m_config['wx_appid'],$m_config['wx_secrit'],SITE_DOMAIN.$url);
                            $wx_url=$weixin->scope_get_code();
                            app_redirect($wx_url);
                        }
                    }
                    else
                    {
                        //showErr("微信功能未开通");
                    }
                }//end platform

            }
            else
            {
                $cookie_uname = es_cookie::get("user_name")?es_cookie::get("user_name"):'';
                $cookie_upwd = es_cookie::get("user_pwd")?es_cookie::get("user_pwd"):'';
                if($cookie_uname!=''&&$cookie_upwd!=''&&!es_session::get("user_info"))
                {
                    $cookie_uname = strim($cookie_uname);
                    $cookie_upwd = strim($cookie_upwd);
                    auto_do_login_user($cookie_uname,$cookie_upwd);
                    $user_info = es_session::get('user_info');
                }
            }
        }
	}

	refresh_user_info();




	//刷新购物车
	require_once APP_ROOT_PATH."system/model/cart.php";
	refresh_cart_list();



	global $referer;
	//保存来路
	// 	es_cookie::delete("referer_url");
	if(!es_cookie::get("referer_url"))
	{
		if(!preg_match("/".urlencode(SITE_DOMAIN.APP_ROOT)."/",urlencode($_SERVER["HTTP_REFERER"])))
		{
			$ref_url = $_SERVER["HTTP_REFERER"];
			if(substr($ref_url, 0,7)=="http://"||substr($ref_url, 0,8)=="https://")
			{
				preg_match("/http[s]*:\/\/[^\/]+/", $ref_url,$ref_url);
				$referer = $ref_url[0];
				if($referer)
					es_cookie::set("referer_url",$referer);
			}
		}
	}
	else
	{
		$referer = es_cookie::get("referer_url");
	}
	$referer = strim($referer);

	es_cookie::delete("is_pc");
}

/**
 * 初始化页面信息，如会员登录状态的显示输出
 */
function init_app_page()
{
    $ajax_refresh = intval($_REQUEST['ajax_refresh']);
    unset($_REQUEST['ajax_refresh']);
    unset($_GET['ajax_refresh']);
    
    $user_id = intval($GLOBALS['user_info']['id']);
    $is_weixin = isWeixin();
    if ($is_weixin && $user_id) {
        // 微信分享验证
        require_once APP_ROOT_PATH."system/model/weixin_jssdk.php";
        $signPackage = getSignPackage();
        $GLOBALS['tmpl']->assign("signPackage",$signPackage);
         
        //分享url
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        // 不使用uri避免首页有的时候获取不到分享的url
        unset($_GET['r']);
        $get = http_build_query($_GET);
        
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]";
        $rd = rand(999, 10000);
        $share_url = $url."?rand_num={$rd}".'&r='.base64_encode($GLOBALS['user_info']['id']).'&'.$get;
        $GLOBALS['tmpl']->assign("wx_share_url", $share_url);
    }
    
    $logo_image = app_conf("SHOP_LOGO")?app_conf("SHOP_LOGO"):TMPL."/images/default_logo.png";
    $GLOBALS['tmpl']->assign("logo_image", $logo_image);
    
    
    $is_lottery = 0;
    $lottery_html = '';
    // 判断是否中奖，提示
    if ($user_id > 0 && $is_weixin) {
        $site_name  = app_conf("SHOP_TITLE");
        $is_app = $GLOBALS['is_app'];
        $sql = "select id, name, duobao_item_id from ".DB_PREFIX."deal_order_item where type=0 and user_id={$user_id} and lottery_sn>0 and is_read=0";
        $oi = $GLOBALS['db']->getRow($sql);
        if ($oi['id'] > 0) {
            
            // 如果是app，设置分享参数
            $share_data['share_content'] = 'http://'.$_SERVER['HTTP_HOST'].wap_url("index", "index");
            $share_data['share_imageUrl'] =$logo_image;
            $share_data['share_url'] = 'http://'.$_SERVER['HTTP_HOST'].wap_url("index", "index");
            $share_data['share_key'] = '';
            $share_data['share_title'] = $site_name;
            $share_data = json_encode($share_data);
            
            
            $lottery_html  = file_get_contents(APP_ROOT_PATH."wap/Tpl/main/inc/winner.html");
            
            $tmpl_path = SITE_DOMAIN.APP_ROOT."/".FOLDER_NAME."/Tpl/".APP_TYPE;
            $img_path = $tmpl_path."/images/winner.png";
            $share_img_path = $tmpl_path."/images/share.png"; 
            $t_view = wap_url("index", "uc_winlog");
            $t_share = wap_url("index", "uc_winlog", array('is_share'=>1));
            $lottery_html  = str_replace('##img##', $img_path, $lottery_html);
            $lottery_html  = str_replace('##site_name##', $site_name, $lottery_html);
            $lottery_html  = str_replace('##goods_name##', $oi['name'], $lottery_html);
            $lottery_html  = str_replace('##issue_no##', $oi['duobao_item_id'], $lottery_html);
            $lottery_html  = str_replace('##view##', $t_view, $lottery_html);
            $lottery_html  = str_replace('##share##', $t_share, $lottery_html);
            $lottery_html  = str_replace('##share_img##', $share_img_path, $lottery_html);
            $lottery_html  = str_replace('##share_data##', $share_data, $lottery_html);
            $lottery_html  = str_replace('##is_app##', $is_app, $lottery_html);
           
            $is_lottery = 1;
            
            // 更新中奖提醒为1
            $GLOBALS['db']->query( "UPDATE `".DB_PREFIX."deal_order_item` SET `is_read`='1' WHERE (`type`='0') AND (`is_read`='0') AND (`user_id`='".$user_id."')" );
            
        }
    }
    
    $GLOBALS['tmpl']->assign("is_lottery", $is_lottery);
    $GLOBALS['tmpl']->assign("lottery_html", $lottery_html);
    $GLOBALS['tmpl']->assign("host",'http://'.$_SERVER['HTTP_HOST']);
    $share_url='http://'.$_SERVER['HTTP_HOST'].wap_url("index", "index");
    $rd = rand(999, 10000);
    $share_url=$share_url."&rand_num={$rd}".'&r='.base64_encode($GLOBALS['user_info']['id']);
    $GLOBALS['tmpl']->assign("index_share_url",$share_url);
    
	if($GLOBALS['user_info'])
	{
	    //输出签到结果
	    $signin_result = es_session::get("signin_result");
	    if($signin_result['status'])
	    {
	        $GLOBALS['tmpl']->assign("signin_result",json_encode($signin_result));
	        es_session::delete("signin_result");
	    }
		$GLOBALS['tmpl']->assign("is_login",1);
	}
	else
	{
		$GLOBALS['tmpl']->assign("is_login",0);
	}

	if ($GLOBALS['account_info']){
	    $GLOBALS['tmpl']->assign("biz_is_login",1);
	}else{
	    $GLOBALS['tmpl']->assign("biz_is_login",0);
	}
	$GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);

	$GLOBALS['tmpl']->assign("account_info",$GLOBALS['account_info']);

	if($GLOBALS['geo']['address'])
	$GLOBALS['tmpl']->assign("geo",$GLOBALS['geo']);

	$GLOBALS['tmpl']->assign("is_weixin",$GLOBALS['is_weixin']);

	$GLOBALS['tmpl']->assign("pc_url",url("index","index",array("is_pc"=>1)));
    //底部菜单
    $GLOBALS['tmpl']->assign("m_biz_nav_list",$GLOBALS['m_biz_nav_list']);
    
    $GLOBALS['tmpl']->assign("is_app", intval($GLOBALS['is_app']));

    $m_config = getMConfig();
    $GLOBALS['tmpl']->assign("m_config", $m_config);

    $GLOBALS['tmpl']->assign("WB_APP_KEY", app_conf('WB_APP_KEY') );
    $GLOBALS['tmpl']->assign("WB_APP_SECRET", app_conf('WB_APP_SECRET') );

    $GLOBALS['tmpl']->assign("WB_APP_KEY", app_conf('WB_APP_KEY') );
    $GLOBALS['tmpl']->assign("WB_APP_SECRET", app_conf('WB_APP_SECRET') );

    $GLOBALS['tmpl']->assign("QQ_HL_APPID", app_conf('QQ_HL_APPID') );
    $GLOBALS['tmpl']->assign("QQ_HL_APPKEY", app_conf('QQ_HL_APPKEY') );

    $GLOBALS['tmpl']->assign("ajax_refresh", $ajax_refresh);
        
}

//编译生成css文件
function parse_css($urls)
{

	$showurl = $url = md5(implode(',',$urls).SITE_DOMAIN);
	$css_url = 'public/runtime/statics/'.CACHE_SUBDIR.'/'.$url.'.css';
	$pathwithoupublic = 'runtime/statics/'.CACHE_SUBDIR.'/';
	$url_path = APP_ROOT_PATH.$css_url;

	if(!file_exists($url_path)||IS_DEBUG)
	{
		$cfg_file = APP_ROOT_PATH.FOLDER_NAME."/Tpl/".APP_TYPE."/tmpl_".TMPL_NAME."/color_cfg.php";
		if(file_exists($cfg_file))
		{
			$color_cfg = require_once $cfg_file;
		}
		else
		{
			$color_cfg = require_once APP_ROOT_PATH.FOLDER_NAME."/Tpl/".APP_TYPE."/color_cfg.php";
		}

		if(!file_exists(APP_ROOT_PATH.'public/runtime/statics/'))
			mkdir(APP_ROOT_PATH.'public/runtime/statics/',0777);

		if(!file_exists(APP_ROOT_PATH.'public/runtime/statics/'.CACHE_SUBDIR.'/'))
			mkdir(APP_ROOT_PATH.'public/runtime/statics/'.CACHE_SUBDIR.'/',0777);
		$tmpl_path = $GLOBALS['tmpl']->_var['TMPL'];

		$css_content = '';
		foreach($urls as $url)
		{
			$css_content .= @file_get_contents($url);
		}
		$css_content = preg_replace("/[\r\n]/",'',$css_content);
		$css_content = str_replace("../images/",$tmpl_path."/images/",$css_content);

		if($GLOBALS['distribution_cfg']['CSS_JS_OSS']&&$GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
		{
			curl_download($GLOBALS['distribution_cfg']['OSS_FILE_DOMAIN']."/public/iconfont/iconfont.css", APP_ROOT_PATH."public/iconfont/iconfont.css");
			curl_download($GLOBALS['distribution_cfg']['OSS_FILE_DOMAIN']."/public/iconfont/iconfont.eot", APP_ROOT_PATH."public/iconfont/iconfont.eot");
			curl_download($GLOBALS['distribution_cfg']['OSS_FILE_DOMAIN']."/public/iconfont/iconfont.svg", APP_ROOT_PATH."public/iconfont/iconfont.svg");
			curl_download($GLOBALS['distribution_cfg']['OSS_FILE_DOMAIN']."/public/iconfont/iconfont.ttf", APP_ROOT_PATH."public/iconfont/iconfont.ttf");
			curl_download($GLOBALS['distribution_cfg']['OSS_FILE_DOMAIN']."/public/iconfont/iconfont.woff", APP_ROOT_PATH."public/iconfont/iconfont.woff");
			//	$css_content = str_replace("./public/iconfont/",$GLOBALS['distribution_cfg']['OSS_FILE_DOMAIN']."/public/iconfont/",$css_content);
		}

		$css_content = str_replace("./public/",SITE_DOMAIN.APP_ROOT."/public/",$css_content);
		$css_content = str_replace("@rand",time(),$css_content);
		foreach($color_cfg as $k=>$v)
		{
			$css_content = str_replace($k,$v,$css_content);
		}
		//		@file_put_contents($url_path, unicode_encode($css_content));
		@file_put_contents($url_path, $css_content);
		if($GLOBALS['distribution_cfg']['CSS_JS_OSS']&&$GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
		{
			syn_to_remote_file_server($css_url);
			$GLOBALS['refresh_page'] = true;
		}
	}
	if($GLOBALS['distribution_cfg']['CSS_JS_OSS']&&$GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
	{
		$domain = $GLOBALS['distribution_cfg']['OSS_FILE_DOMAIN'];
	}
	else
	{
		$domain = SITE_DOMAIN.APP_ROOT;
	}
	return $domain."/".$css_url."?v=".app_conf("DB_VERSION").".".app_conf("APP_SUB_VER");
}

/**
 *
 * @param $urls 载入的脚本
 * @param $encode_url 需加密的脚本
 */
function parse_script($urls,$encode_url=array())
{
	$showurl = $url = md5(implode(',',$urls));
	$js_url = 'public/runtime/statics/'.CACHE_SUBDIR.'/'.$url.'.js';
	$pathwithoupublic = 'runtime/statics/'.CACHE_SUBDIR.'/';
	$url_path = APP_ROOT_PATH.$js_url;
	if(!file_exists($url_path)||IS_DEBUG)
	{
		if(!file_exists(APP_ROOT_PATH.'public/runtime/statics/'))
			mkdir(APP_ROOT_PATH.'public/runtime/statics/',0777);

		if(!file_exists(APP_ROOT_PATH.'public/runtime/statics/'.CACHE_SUBDIR.'/'))
			mkdir(APP_ROOT_PATH.'public/runtime/statics/'.CACHE_SUBDIR.'/',0777);

		if(count($encode_url)>0)
		{
			require_once APP_ROOT_PATH."system/libs/javascriptpacker.php";
		}

		$js_content = '';
		foreach($urls as $url)
		{
			$append_content = @file_get_contents($url)."\r\n";
			if(in_array($url,$encode_url))
			{
				$packer = new JavaScriptPacker($append_content);
				$append_content = $packer->pack();
			}
			$js_content .= $append_content;
		}
		//		require_once APP_ROOT_PATH."system/libs/javascriptpacker.php";
		//	    $packer = new JavaScriptPacker($js_content);
		//		$js_content = $packer->pack();
		@file_put_contents($url_path,$js_content);
		if($GLOBALS['distribution_cfg']['CSS_JS_OSS']&&$GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
		{
			syn_to_remote_file_server($js_url);
			$GLOBALS['refresh_page'] = true;
		}
	}
	if($GLOBALS['distribution_cfg']['CSS_JS_OSS']&&$GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
	{
		$domain = $GLOBALS['distribution_cfg']['OSS_FILE_DOMAIN'];
	}
	else
	{
		$domain = SITE_DOMAIN.APP_ROOT;
	}
	return $domain."/".$js_url."?v=".app_conf("DB_VERSION").".".app_conf("APP_SUB_VER");
}




function getWebAdsUrl($data){
	//2:URL广告;9:团购列表;10:商品列表;11:活动列表;12:优惠列表;14:团购明细;15:商品明细;17:优惠明细;22:商家列表;23：商家明细; 24:门店自主下单

	if($data['ctl']=="url")
	{
		$url = $data['data']['url'];
		if(empty($url))
		{
			$url = "javascript:void(0);";
		}
		else
		{
			$url = "javascript:open_url('".$data['data']['url']."');";
		}
	}
	else
	$url = wap_url("index",$data['ctl'],$data['data']);

	return $url;

}




/**
 * 获取前次停留的页面地址
 * @return string url
 */
function get_gopreview()
{
	$gopreview = es_session::get("wap_gopreview");
	if($gopreview==get_current_url())
	{
		$gopreview = wap_url("index");
	}
	if(empty($gopreview))
		$gopreview = wap_url("index");
	return $gopreview;
}


/**
 * 获取当前的url地址，包含分页
 * @return string
 */
function get_current_url()
{
	$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");
	$parse = parse_url($url);
	if(isset($parse['query'])) {
		parse_str($parse['query'],$params);
		$url   =  $parse['path'].'?'.http_build_query($params);
	}

	$url = preg_replace("/&code=[^&]*/i", "", $url);
	$url = preg_replace("/&state=[^&]*/i", "", $url);
	$url = preg_replace("/&appid=[^&]*/i", "", $url);
	return $url;
}

/**
 * 将当前页设为回跳的上一页地址
 */
function set_gopreview()
{
	$url =  get_current_url();
	// 删除ajax刷新的url条件
	$url = str_replace('ajax_refresh=1', '', $url);
	es_session::set("wap_gopreview",$url);
}

function load_sms_lesstime()
{
	$data	=	es_session::get("send_sms_code_0_ip");
	$lesstime = SMS_TIMESPAN -(NOW_TIME - $data['time']);  //剩余时间
	if($lesstime<0)$lesstime=0;
	return $lesstime;
}

/**
 * 微信登录
 * @param unknown_type $wx_info
 * @param unknown_type $type 0 wap端（公众号登录） 1 app登录
 */
function wx_info_login($wx_info,$type=0)
{
	if(!$wx_info['openid'])
	{
		return false;
	}
	//用户未登陆
	
	if($wx_info['unionid'])
	{
		if($type==0)
			$wx_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where wx_openid='".$wx_info['openid']."' or union_id = '".$wx_info['unionid']."' order by id desc limit 1");
		else
			$wx_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where m_openid='".$wx_info['openid']."' or union_id = '".$wx_info['unionid']."' order by id desc limit 1");
	}
	else
	{
		if($type==0)
			$wx_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where wx_openid='".$wx_info['openid']."' order by id desc limit 1");
		else
			$wx_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where m_openid='".$wx_info['openid']."' order by id desc limit 1");
	}
	if($wx_user_info){
		if($wx_user_info['user_logo'] == ''){
			$GLOBALS['db']->query("update ".DB_PREFIX."user set user_logo='".$wx_info['headimgurl']."' where id='".$wx_user_info['id']."'");
			delete_avatar($wx_user_info['id']);
		}
		
		if($type==0)
			$GLOBALS['db']->query("update ".DB_PREFIX."user set wx_openid = '".$wx_info['openid']."',union_id='".$wx_info['unionid']."' where id = ".$wx_user_info['id']);
		else
			$GLOBALS['db']->query("update ".DB_PREFIX."user set m_openid = '".$wx_info['openid']."',union_id='".$wx_info['unionid']."' where id = ".$wx_user_info['id']);
		//如果会员存在，直接登录
		auto_do_login_user($wx_user_info['user_name'],$wx_user_info['user_pwd'],false);
	}else{
		//会员不存在进入自动创建流程
		$user_data = array();
		$user_data['user_name'] = $wx_info['nickname'];
		if($type==0)
			$user_data['wx_openid'] = $wx_info['openid'];
		else
			$user_data['m_openid'] = $wx_info['openid'];
		$user_data['union_id'] = $wx_info['unionid'];
	
		$pid = $GLOBALS['db']->getOne("select pid from ".DB_PREFIX."scan_subscribe_log where openid='{$wx_info['openid']}'");
		// 查看分享的用户是否有权限进行渠道分享
		$pid = intval($pid);
		$is_open_scan = $GLOBALS['db']->getOne("select is_open_scan from ".DB_PREFIX."user where id={$pid}");
		if ($pid && $is_open_scan) {
		    $user_data['pid'] = $pid;
		}
		
		$rs = auto_create($user_data,null,false,$wx_info['headimgurl']);
		$user_data = $rs['user_data'];
		auto_do_login_user($user_data['user_name'],$user_data['user_pwd'],false);
	}
	$user_info = es_session::get('user_info');
	es_cookie::set("user_name",$user_info['user_name'],3600*24*30);
	es_cookie::set("user_pwd",md5($user_info['user_pwd']."_EASE_COOKIE"),3600*24*30);
	return true;
}

/**
 * 微博登录
 * @param array  $new_user_info
 */
function wb_info_login( $new_user_info )
{
    if(!$new_user_info['id']){
        return false;
    }
    //用户未登陆
    $wb_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where sina_id='".$new_user_info['id']."' order by id desc limit 1");

    if($wb_user_info){
        if($wb_user_info['user_logo'] == ''){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set user_logo='".$new_user_info['avatar_large']."' where id='".$wb_user_info['id']."'");
            delete_avatar($wb_user_info['id']);
        }
        //如果会员存在，直接登录
        auto_do_login_user($wb_user_info['user_name'],$wb_user_info['user_pwd'],false);
    }else{
        //会员不存在进入自动创建流程
        $user_data = array();
        $user_data['user_name']     = $new_user_info['screen_name'];
        $user_data['sina_id']       = $new_user_info['id'];

        $rs = auto_create($user_data, 0, false, $new_user_info['avatar_large']);
        $user_data = $rs['user_data'];
        auto_do_login_user($user_data['user_name'],$user_data['user_pwd'],false);
    }
    $user_info = es_session::get('user_info');
    es_cookie::set("user_name",$user_info['user_name'],3600*24*30);
    es_cookie::set("user_pwd",md5($user_info['user_pwd']."_EASE_COOKIE"),3600*24*30);
    return true;
}

/**
 * qq登录
 * @param array  $new_user_info
 */
function qq_info_login( $new_user_info )
{
    if(!$new_user_info['nickname']){
        return false;
    }
    //用户未登陆
    $qq_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where qq_openid='".$new_user_info['openid']."' order by id desc limit 1");

    if($qq_user_info){
        if($qq_user_info['user_logo'] == ''){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set user_logo='".$new_user_info['figureurl_qq_2']."' where id='".$qq_user_info['id']."'");
            delete_avatar($qq_user_info['id']);
        }
        //如果会员存在，直接登录
        auto_do_login_user($qq_user_info['user_name'],$qq_user_info['user_pwd'],false);
    }else{
        //会员不存在进入自动创建流程
        $user_data = array();
        $user_data['user_name']     = $new_user_info['nickname'];
        $user_data['qq_openid']       = $new_user_info['openid'];

        $rs = auto_create($user_data, 0, false, $new_user_info['figureurl_qq_2']);
        $user_data = $rs['user_data'];
        auto_do_login_user($user_data['user_name'],$user_data['user_pwd'],false);
    }
    $user_info = es_session::get('user_info');
    es_cookie::set("user_name",$user_info['user_name'],3600*24*30);
    es_cookie::set("user_pwd",md5($user_info['user_pwd']."_EASE_COOKIE"),3600*24*30);
    return true;
}


?>