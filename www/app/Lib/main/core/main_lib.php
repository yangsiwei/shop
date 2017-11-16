<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

/**
 * 获取短信发送的倒计时
 */
function load_sms_lesstime()
{
	$data	=	es_session::get("send_sms_code_0_ip");
	$lesstime = SMS_TIMESPAN -(NOW_TIME - $data['time']);  //剩余时间
	if($lesstime<0)$lesstime=0;
	return $lesstime;
}


/**
 * 加载png图片，主要用于模板端调用
 * @param unknown_type $img
 * @return boolean
 */
function load_page_png($img)
{
	return load_auto_cache("page_image",array("img"=>$img));
}

function get_nav_list()
{
	return load_auto_cache("cache_nav_list");
}

function init_nav_list($nav_list)
{
	foreach($nav_list as $k=>$v)
	{
		if($v['url']=='')
		{
			if($v['app_index']=="")$v['app_index']="index";
			if($v['u_module']=="")$v['u_module']="index";
			if($v['u_action']=="")$v['u_action']="index";
			
			$route = $v['u_module'];
			if($v['u_action']!='')
				$route.="#".$v['u_action'];

			$app_index = $v['app_index'];

			$str = "u:".$app_index."|".$route."|".$v['u_param'];
			$nav_list[$k]['url'] =  parse_url_tag($str);
			if(ACTION_NAME==$v['u_action']&&MODULE_NAME==$v['u_module']&&APP_INDEX==$v['app_index'])
			{
				$nav_list[$k]['current'] = 1;
			}
		}
	}
	return $nav_list;
}

/**
 * 获取导航菜单
 */
function format_nav_list($nav_list)
{
	foreach($nav_list as $k=>$v)
	{
		if($v['url']!='')
		{
			if(substr($v['url'],0,7)!="http://")
			{
				//开始分析url
				$nav_list[$k]['url'] = APP_ROOT."/".$v['url'];
			}
		}
	}
	return $nav_list;
}

/**
 * 加载下拉菜单的模板展示
 * count:允许显示的大类个数
 */
function load_cate_tree($count=0)
{
	$cate_tree = load_cate_list($count);
	$GLOBALS['tmpl']->assign("cate_tree",$cate_tree);
	return $GLOBALS['tmpl']->fetch("inc/cate_tree.html");
}
function load_cate_list($count=0)
{
	$cate_list = load_auto_cache("cate_list");
	if($count)
	{
		$cate_tree = array();
		foreach($cate_list as $k=>$v)
		{
			if($k<$count)
			{
				$cate_tree[] = $v;
			}
		}
	}
	else
		$cate_tree = $cate_list;
	return $cate_tree;
}


/**
 * 针对模板进行配置的布局总宽度
 * @param unknown_type $type 0:默认宽度 1:首页宽度...
 */
function load_wrap($type=0)
{

	if(intval($type)==1)return "wrap_full main_layout";
	if(intval($type)==0)return "wrap_full_w main_layout";
}

/**
 * 关于页面初始化时需要输出的信息
 * 全属使用的模板信息输出
 * 1. seo 基本信息
 * $GLOBALS['tmpl']->assign("shop_info",get_shop_info());
 * 2. 当前城市名称, 单城市不显示
 * 3. 输出APP_ROOT
 */
function init_app_page()
{
	//输出根路径
	$GLOBALS['tmpl']->assign("APP_ROOT",APP_ROOT);
	
	//定义当前语言包
	$GLOBALS['tmpl']->assign("LANG",$GLOBALS['lang']);
	
	$GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);

	
	//开始输出site_seo
	$site_seo['keyword']	=	$GLOBALS['city']['seo_keyword']==''?app_conf('SHOP_KEYWORD'):$GLOBALS['city']['seo_keyword'];
	$site_seo['description']	= $GLOBALS['city']['seo_description']==''?app_conf('SHOP_DESCRIPTION'):$GLOBALS['city']['seo_description'];
	$site_seo['title']  = app_conf("SHOP_TITLE");
	$seo_title =	$GLOBALS['city']['seo_title']==''?app_conf('SHOP_SEO_TITLE'):$GLOBALS['city']['seo_title'];
	if($seo_title!="")$site_seo['title'].=" - ".$seo_title;
	$GLOBALS['tmpl']->assign("site_seo",$site_seo);
	 

	//输出导航菜单
	$nav_list = get_nav_list();
	$nav_list= init_nav_list($nav_list);
	$nav_list = array_chunk($nav_list, 7, false);
	$new_nav_list['one'] = $nav_list[0];
	$new_nav_list['two'] = $nav_list[1];
	$GLOBALS['tmpl']->assign("nav_list", $new_nav_list);
	
	//输出帮助
	$deal_help = get_web_help();
	//print_r($deal_help);exit;
	$GLOBALS['tmpl']->assign("deal_help",$deal_help);
	require_once APP_ROOT_PATH."system/model/duobao.php";
	$cart_info=duobao::getcart($GLOBALS['user_info']['id']);
	$GLOBALS['tmpl']->assign("cart_info",$cart_info);
	//输出接收到的关键词
	global $kw;
	$kw = strim($_REQUEST['kw']);
	$GLOBALS['tmpl']->assign("kw",$kw);
	
}


/**
 * 前端全运行函数，生成系统前台使用的全局变量
 * 1. 定位城市 GLOBALS['city'];
 * 2. 加载会员 GLOBALS['user_info'];
 * 3. 生成语言包
 * 4. 加载推荐人与来路
 * 5. 更新购物车
 */
function global_run()
{
	
	//输出语言包的js
	if(!file_exists(get_real_path()."public/runtime/app/lang.js"))
	{
		$str = "var LANG = {";
		foreach($GLOBALS['lang'] as $k=>$lang_row)
		{
			$str .= "\"".$k."\":\"".str_replace("nbr","\\n",addslashes($lang_row))."\",";
		}
		$str = substr($str,0,-1);
		$str .="};";
		@file_put_contents(get_real_path()."public/runtime/app/lang.js",$str);
	}


	//会员自动登录及输出
	global $user_info;
	global $user_logined; 
	require_once APP_ROOT_PATH."system/model/user.php";
	$user_info = es_session::get('user_info');

	if(empty($user_info))
	{
	    // 微博登录
	    if ( isset($_REQUEST['code']) && $_REQUEST['wb_login'] == 1 ){
            require APP_ROOT_PATH . "system/weibo/saetv2.ex.class.php";

            $o  = new SaeTOAuthV2( app_conf('WB_APP_KEY'), app_conf('WB_APP_SECRET') );

            $keys = array();
            $keys['code'] = trim($_REQUEST['code']);
            $keys['redirect_uri'] = get_domain().'?wb_login=1';


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

        } elseif ( isset($_REQUEST['code']) && isset($_REQUEST['state']) && ( stripos($_SERVER['REQUEST_URI'], 'qq_login') > 0 ) ){
            // QQ登录
            if( $_REQUEST['code'] != es_session::get('qq_code') ) {

                es_session::set('qq_code', $_REQUEST['code']);

                require APP_ROOT_PATH . "system/qqconnect/API/qqConnectAPI.php";
                $qc = new QC();
                $access_token = $qc->qq_callback();
                $openid = $qc->get_openid();

                // 必须传这两个参数，example没有写会报错
                $qc = new QC($access_token, $openid);
                $qq_user_info = $qc->get_user_info();

                // 登录或者创建后自动登录用户
                $qq_user_info['openid'] = $openid;

                qq_info_login($qq_user_info);
            }


        } else {
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
	refresh_user_info();


	
	global $ref_uid;
	
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
	
	
	//关于PC与WAP的自动跳转
	pc_wap_jump();

}

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
 * 验证会员字段的有效性
 * @param unknown_type $field  字段名称
 * @param unknown_type $value	字段内容
 * @param unknown_type $user_id	会员ID
 */
function check_field($field,$value,$user_id)
{
	require_once APP_ROOT_PATH."system/model/user.php";
	$data = array();
	$data['status'] = true;
	$data['info'] = "";
	$user_data['id'] = $user_id;
	if($field=="email")
	{		
		$check_rs = check_user("email",$value,$user_data);
		if(!$check_rs['status'])
		{
			$check_data = $check_rs['data'];
			if($check_data['error']==FORMAT_ERROR)
			{
				$data['status'] = false;
				$data['info'] = "邮箱格式不正确";
				$data['field'] = "email";
				return $data;
			}
			if($check_data['error']==EXIST_ERROR)
			{
				$data['status'] = false;
				$data['info'] = "邮箱已被注册";
				$data['field'] = "email";
				return $data;
			}
		}		
	}
	
	if($field=="getpassword_email")
	{
		if(!check_email($value))
		{
			$data['status'] = false;
			$data['info'] = "邮箱格式不正确";
			$data['field'] = "getpassword_email";
			return $data;
		}
		$rs = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email = '".$value."' and id <> ".$user_id);
		if(intval($rs)==0)
		{
			$data['status'] = false;
			$data['info'] = "邮箱未在本站注册过";
			$data['field'] = "getpassword_email";
			return $data;
		}
	
	}
	
	if($field=="getpassword_mobile")
	{
		if(!check_mobile($value))
		{
			$data['status'] = false;
			$data['info'] = "手机号码格式不正确";
			$data['field'] = "user_mobile";
			return $data;
		}
		$rs = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile = '".$value."' and id <> ".$user_id);
		if(intval($rs)==0)
		{
			$data['status'] = false;
			$data['info'] = "手机号未在本站注册过";
			$data['field'] = "user_mobile";
			return $data;
		}
	
	}
	
	if($field=="user_name")
	{
		$check_rs = check_user("user_name",$value,$user_data);
		if(!$check_rs['status'])
		{
			$check_data = $check_rs['data'];
			if($check_data['error']==FORMAT_ERROR)
			{
				$data['status'] = false;
				$data['info'] = "用户名格式不正确";
				$data['field'] = "user_name";
				return $data;
			}
			if($check_data['error']==EXIST_ERROR)
			{
				$data['status'] = false;
				$data['info'] = "用户名已被注册";
				$data['field'] = "user_name";
				return $data;
			}
		}
	}
	if($field=="mobile")
	{
		$check_rs = check_user("mobile",$value,$user_data);
		if(!$check_rs['status'])
		{
			$check_data = $check_rs['data'];
			if($check_data['error']==FORMAT_ERROR)
			{
				$data['status'] = false;
				$data['info'] = "手机号格式不正确";
				$data['field'] = "user_mobile";
				return $data;
			}
			if($check_data['error']==EXIST_ERROR)
			{
				$data['status'] = false;
				$data['info'] = "手机号已被注册";
				$data['field'] = "user_mobile";
				return $data;
			}
		}		
	}
	
	if($field=="verify_code")
	{
		
		$verify = md5($value);
		$session_verify = es_session::get('verify');
		if($verify!=$session_verify)
		{
			$data['status'] = false;
			$data['info']	=	"图片验证码错误";
			$data['field'] = "verify_code";
			return $data;
		}
	}
	return $data;
}



//获取已过时间
function pass_date($time)
{
	$time_span = get_gmtime() - $time;
	if($time_span>3600*24*365)
	{
		//一年以前
		//			$time_span_lang = round($time_span/(3600*24*365)).$GLOBALS['lang']['SUPPLIER_YEAR'];
		//$time_span_lang = to_date($time,"Y".$GLOBALS['lang']['SUPPLIER_YEAR']."m".$GLOBALS['lang']['SUPPLIER_MON']."d".$GLOBALS['lang']['SUPPLIER_DAY']);
		$time_span_lang = to_date($time,"Y-m-d");
	}
	elseif($time_span>3600*24*30)
	{
		//一月
		//			$time_span_lang = round($time_span/(3600*24*30)).$GLOBALS['lang']['SUPPLIER_MON'];
		//$time_span_lang = to_date($time,"Y".$GLOBALS['lang']['SUPPLIER_YEAR']."m".$GLOBALS['lang']['SUPPLIER_MON']."d".$GLOBALS['lang']['SUPPLIER_DAY']);
		$time_span_lang = to_date($time,"Y-m-d");
	}
	elseif($time_span>3600*24)
	{
		//一天
		//$time_span_lang = round($time_span/(3600*24)).$GLOBALS['lang']['SUPPLIER_DAY'];
		$time_span_lang = to_date($time,"Y-m-d");
	}
	elseif($time_span>3600)
	{
		//一小时
		$time_span_lang = round($time_span/(3600)).$GLOBALS['lang']['SUPPLIER_HOUR'];
	}
	elseif($time_span>60)
	{
		//一分
		$time_span_lang = round($time_span/(60)).$GLOBALS['lang']['SUPPLIER_MIN'];
	}
	else
	{
		//一秒
		$time_span_lang = $time_span.$GLOBALS['lang']['SUPPLIER_SEC'];
	}
	return $time_span_lang;
}


//编译生成css文件
function parse_css($urls)
{
	$color_cfg = require_once APP_ROOT_PATH."app/Tpl/".APP_TYPE."/".app_conf("TEMPLATE")."/color_cfg.php";
	$showurl = $url = md5(implode(',',$urls).SITE_DOMAIN);	
	$css_url = 'public/runtime/statics/'.$url.'.css';
	$pathwithoupublic = 'runtime/statics/';
	$url_path = APP_ROOT_PATH.$css_url;
	if(!file_exists($url_path)||IS_DEBUG)
	{
		if(!file_exists(APP_ROOT_PATH.'public/runtime/statics/'))
			mkdir(APP_ROOT_PATH.'public/runtime/statics/',0777);
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
	$js_url = 'public/runtime/statics/'.$url.'.js';
	$pathwithoupublic = 'runtime/statics/';
	$url_path = APP_ROOT_PATH.$js_url;
	if(!file_exists($url_path)||IS_DEBUG)
	{
		if(!file_exists(APP_ROOT_PATH.'public/runtime/statics/'))
			mkdir(APP_ROOT_PATH.'public/runtime/statics/',0777);

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


/**
 * 同步发微博
 * @param unknown $topic_id
 * @param unknown $class_name 首字母大写如： Sina ,Qqv2 ,Tencent
 */
function syn_to_weibo($topic_id,$api_class_name)
{
    $user_info = $GLOBALS['user_info'];
   
    set_time_limit(0);
    $user_id = $user_info['id'];

    es_session::close();
    $topic = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."topic where id = ".$topic_id);
    if($topic['topic_group']!="share")
    {
        $group = $topic['topic_group'];
        
        if(file_exists(APP_ROOT_PATH."system/fetch_topic/".$group."_fetch_topic.php"))
        {
            require_once APP_ROOT_PATH."system/fetch_topic/".$group."_fetch_topic.php";
            $class_name = $group."_fetch_topic";
            if(class_exists($class_name))
            {
                $fetch_obj = new $class_name;
                $data = $fetch_obj->decode_weibo($topic);
            }
        }
    }
    else
    {
        $data['content'] =  msubstr($topic['content'],0,140);
        	
        //图片
        $topic_image = $GLOBALS['db']->getRow("select o_path from ".DB_PREFIX."topic_image where topic_id = ".$topic['id']);
        if($topic_image)
            $data['img'] = APP_ROOT_PATH.$topic_image['o_path'];
    }

//     $api = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."api_login where is_weibo = 1 and class_name = '".$api_class_name."'");

//     if($user_info["is_syn_".strtolower($api['class_name'])]==1)
//     {
//         //发送本微博
//         require_once APP_ROOT_PATH."system/api_login/".$api_class_name."_api.php";        
//         $api_class = $api_class_name."_api";
//         $api_obj = new $api_class($api);
//         $api_obj->send_message($data);
//     }
}




//自动切换跳转到相关的pc与wap页
function pc_wap_jump()
{
	$is_pc = intval(es_cookie::get("is_pc"));
	if($is_pc==0)
	{
		$is_pc = intval($_REQUEST['is_pc']);
		if($is_pc==1)es_cookie::set("is_pc","1",24*3600*30);
	}

	if($is_pc==0&&isMobile())
	{
		//开始自动跳转
		if(MODULE_NAME!="app_download")
		{
			//除了手机端下载以外全部需要跳转
			if(MODULE_NAME=="duobao")
			{
				if(intval($GLOBALS['ref_uid'])>0)
				{
					$url = wap_url("index","duobao",array("data_id"=>intval($_REQUEST['id']),"r"=>base64_encode(intval($GLOBALS['ref_uid']))));
				}
				else
				$url = wap_url("index","duobao",array("data_id"=>intval($_REQUEST['id'])));
			}
			elseif(MODULE_NAME=="duobaos")
			{
				$url = wap_url("index",MODULE_NAME,array("data_id"=>intval($_REQUEST['id'])));
			}
			elseif(MODULE_NAME=="duobaost")
			{
				$url = wap_url("index","duobaost");
			}
			elseif(MODULE_NAME=="duobaosh")
			{
				$url = wap_url("index","duobaosh");
			}
			elseif(MODULE_NAME=="user")
			{
				$url = wap_url("index",MODULE_NAME."#".ACTION_NAME);
			}
		    elseif(MODULE_NAME=="redset")
			{
				$url = wap_url("index",MODULE_NAME,array("order_sn"=>$_REQUEST['order_sn'],'limit'=>intval($_REQUEST['limit'])));
			}
			else
			{
				$url = wap_url("index");
			}
			app_redirect($url);
		}
	}

}

function get_web_help()
{
	return load_auto_cache("get_web_help_cache");
}


//获取指定的文章分类列表
function get_acate_tree($pid = 0,$type_id=0,$act_name)
{
	return load_auto_cache("cache_shop_acate_tree",array("pid"=>$pid,"type_id"=>$type_id,"act_name"=>$act_name));
}


/**
 * 会员中心左侧菜单
 */
function assign_uc_nav_list(){
	$nav_list = require APP_ROOT_PATH."system/web_cfg/".APP_TYPE."/ucnode_cfg.php";

	foreach($nav_list as $k=>$v)
	{

		foreach($v['node'] as $kk=>$vv)
		{
		     
			if($vv['module'] == MODULE_NAME){
				$nav_list[$k]['node'][$kk]['current'] = 1;
			}
			$module_name = $vv['module'];
			$action_name = $vv['action'];
			$nav_list[$k]['node'][$kk]['url'] = url("index",$module_name."#".$action_name);
			
			//如果不是经销商，则不显示邀请链接
			if($vv['module'] == 'uc_invite'){
				$dealers = $GLOBALS['user_info']['dealers'];
				if($dealers != 2){
					unset($nav_list[$k]['node'][$kk]);
				}
			}

			// 如果关闭了三级分销，则不显示我的团队
			if ($vv['module'] == 'uc_fx') {
			    $user_id = intval($GLOBALS['user_info']['id']);
			    $dealers = $GLOBALS['user_info']['dealers'];
			    $fx_is_open   = $GLOBALS['db']->getOne("select fx_is_open from ".DB_PREFIX."fx_salary");
			    if ( !($dealers=2 && $fx_is_open) ) {
		    		unset($nav_list[$k]['node'][$kk]);
			    }
			}
		}
	}
	 
	//用户信息
	if($GLOBALS['user_info'])
	{
		$user_id = intval($GLOBALS['user_info']['id']);
		$c_user_info = $GLOBALS['user_info'];
		//$c_user_info['user_group'] = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."user_group where id = ".intval($GLOBALS['user_info']['group_id']));
		$GLOBALS['tmpl']->assign("user_info",$c_user_info);

		//签到数据
		$t_begin_time = to_timespan(to_date(get_gmtime(),"Y-m-d"));  //今天开始
		$t_end_time = to_timespan(to_date(get_gmtime(),"Y-m-d"))+ (24*3600 - 1);  //今天结束
		$y_begin_time = $t_begin_time - (24*3600); //昨天开始
		$y_end_time = $t_end_time - (24*3600);  //昨天结束

		$t_sign_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_sign_log where user_id = ".$user_id." and sign_date between ".$t_begin_time." and ".$t_end_time);
		if($t_sign_data)
		{
			$GLOBALS['tmpl']->assign("t_sign_data",$t_sign_data);
		}
		else
		{
			$y_sign_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_sign_log where user_id = ".$user_id." and sign_date between ".$y_begin_time." and ".$y_end_time);
			$total_signcount = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_sign_log where user_id = ".$user_id);
			if($y_sign_data&&$total_signcount>=3)
			{
				$tip = "";
				if(floatval(app_conf("USER_LOGIN_KEEP_MONEY"))>0)
					$tip .= "资金+".format_price(app_conf("USER_LOGIN_KEEP_MONEY"));
				if(intval(app_conf("USER_LOGIN_KEEP_SCORE"))>0)
					$tip .= "积分+".format_score(app_conf("USER_LOGIN_KEEP_SCORE"));
				if(intval(app_conf("USER_LOGIN_KEEP_POINT"))>0)
					$tip .= "经验+".(app_conf("USER_LOGIN_KEEP_POINT"));
				$GLOBALS['tmpl']->assign("sign_tip",$tip);
			}
			else
			{
				if(!$y_sign_data)
					$GLOBALS['db']->query("delete from ".DB_PREFIX."user_sign_log where user_id = ".$user_id);
				$tip = "";
				if(floatval(app_conf("USER_LOGIN_MONEY"))>0)
					$tip .= "资金+".format_price(app_conf("USER_LOGIN_MONEY"));
				if(intval(app_conf("USER_LOGIN_SCORE"))>0)
					$tip .= "积分+".format_score(app_conf("USER_LOGIN_SCORE"));
				if(intval(app_conf("USER_LOGIN_POINT"))>0)
					$tip .= "经验+".(app_conf("USER_LOGIN_POINT"));
				$GLOBALS['tmpl']->assign("sign_tip",$tip);
			}
			$GLOBALS['tmpl']->assign("sign_day",$total_signcount);
			$GLOBALS['tmpl']->assign("y_sign_data",$y_sign_data);
		}


	}
	$GLOBALS['tmpl']->assign("uc_nav_list",$nav_list);
}


function show_avatar($u_id,$type="middle",$is_card=true)
{
	$key = md5("AVATAR_".$u_id.$type);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}
	else
	{
		$avatar_key = md5("USER_AVATAR_".$u_id);
		$avatar_data = $GLOBALS['dynamic_avatar_cache'][$avatar_key];// 当前用户所有头像的动态缓存
		if(!isset($avatar_data)||!isset($avatar_data[$key]))
		{
			$avatar_file = get_user_avatar($u_id,$type);
			if($is_card){
				$avatar_str = "<a href='".url("index","uc_home",array("id"=>$u_id))."' style='text-align:center; display:inline-block;'  onmouseover='userCard.load(this,\"".$u_id."\");'>".
						"<img src='".$avatar_file."'  />".
						"</a>";
			}else{
				$avatar_str = "<img src='".$avatar_file."'  />";
			}
				
			$avatar_data[$key] = $avatar_str;
			if(count($GLOBALS['dynamic_avatar_cache'])<500) //保存500个用户头像缓存
			{
				$GLOBALS['dynamic_avatar_cache'][$avatar_key] = $avatar_data;
			}
		}
		else
		{
			$avatar_str = $avatar_data[$key];
		}
		$GLOBALS[$key]= $avatar_str;
		return $GLOBALS[$key];
	}
}
function update_avatar($u_id)
{
	$avatar_key = md5("USER_AVATAR_".$u_id);
	unset($GLOBALS['dynamic_avatar_cache'][$avatar_key]);
	$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/avatar_cache/");
	$GLOBALS['cache']->set("AVATAR_DYNAMIC_CACHE",$GLOBALS['dynamic_avatar_cache']); //头像的动态缓存
}

function update_order_consignee($order_id,$consignee_info){
    // 获取用户地址
    $region_conf = load_auto_cache ( "delivery_region" );
    $region_lv1 = intval ( $consignee_info ['region_lv1'] );
    $region_lv2 = intval ( $consignee_info ['region_lv2'] );
    $region_lv3 = intval ( $consignee_info ['region_lv3'] );
    $region_lv4 = intval ( $consignee_info ['region_lv4'] );
    $region_info = $region_conf [$region_lv1] ['name'] . " " . $region_conf [$region_lv2] ['name'] . " " . $region_conf [$region_lv3] ['name'] . " " . $region_conf [$region_lv4] ['name'];
    
    $order ['region_info'] = $region_info;
    $order ['address'] = strim ( $consignee_info ['address'] );
    $order ['mobile'] = strim ( $consignee_info ['mobile'] );
    $order ['consignee'] = strim ( $consignee_info ['consignee'] );
    $order ['zip'] = strim ( $consignee_info ['zip'] );

    $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order,"UPDATE"," id = ".$order_id);
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