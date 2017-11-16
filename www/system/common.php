<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


//获取真实路径
function get_real_path()
{
	return APP_ROOT_PATH;
}

//获取GMTime
function get_gmtime()
{
	$now = (time() - date('Z'));
	return $now;
}

function get_gmmtime()
{
    $hs = microtime();
    $hs = explode(" ",$hs);
    $hs = $hs[0];
    $hs = explode(".",$hs);
    $hs = substr($hs[1],3,3);
    $now = NOW_TIME.".".$hs;
	return $now;
}

function to_date($utc_time, $format = 'Y-m-d H:i:s') {
	if (empty ( $utc_time )) {
		return '';
	}
	$timezone = intval(app_conf('TIME_ZONE'));
	$time = intval($utc_time) + $timezone * 3600;
//	$time = intval($utc_time);
	return date ($format, $time );
}

function to_timespan($str, $format = 'Y-m-d H:i:s')
{
	$timezone = intval(app_conf('TIME_ZONE'));
	//$timezone = 8;
	$time = intval(strtotime($str));
	if($time!=0)
	$time = $time - $timezone * 3600;
    return $time;
}

/**
 *
 * 获取商品是否为当天上线商品
 */
function get_is_today($deal)
{
	if($deal['begin_time']==0) return 0;
	$day_begin =  to_timespan(to_date(NOW_TIME,"Y-m-d"),"Y-m-d");
	$day_end  = $day_begin+3600*24-1;
	if($deal['begin_time']>=$day_begin&&$deal['begin_time']<$day_end)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

//获取客户端IP
function get_client_ip() {
	//使用wap时，是通过中转方式，所以要在wap/index.php获取客户ip,转入到:sjmapi上 chenfq by add 2014-11-01
	if (isset($GLOBALS['request']['client_ip']) && !empty($GLOBALS['request']['client_ip']))
		$ip = $GLOBALS['request']['client_ip'];
	else if (isset($_REQUEST['client_ip']) && !empty($_REQUEST['client_ip']))
		$ip = $_REQUEST['client_ip'];
	else if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
		$ip = getenv ( "HTTP_CLIENT_IP" );
	else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
		$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
	else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
		$ip = getenv ( "REMOTE_ADDR" );
	else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
		$ip = $_SERVER ['REMOTE_ADDR'];
	else
		$ip = "0.0.0.0";
	if(!preg_match("/(\d+)\.(\d+)\.(\d+)\.(\d+)/", $ip))
		$ip = "0.0.0.0";
	return strim($ip);
}

//过滤注入
function filter_injection(&$request)
{
	$pattern = "/(select[\s])|(insert[\s])|(update[\s])|(delete[\s])|(from[\s])|(where[\s])/i";
	foreach($request as $k=>$v)
	{
				if(preg_match($pattern,$k,$match))
				{
						die("SQL Injection denied!");
				}

				if(is_array($v))
				{
					filter_injection($v);
				}
				else
				{

					if(preg_match($pattern,$v,$match))
					{
						die("SQL Injection denied!");
					}
				}
	}

}

//过滤请求
function filter_request(&$request)
{
		if(MAGIC_QUOTES_GPC)
		{
			foreach($request as $k=>$v)
			{
				if(is_array($v))
				{
					filter_request($request[$k]);
				}
				else
				{
					$request[$k] = stripslashes(trim($v));
				}
			}
		}

}

function adddeepslashes(&$request)
{

			foreach($request as $k=>$v)
			{
				if(is_array($v))
				{
					adddeepslashes($v);
				}
				else
				{
					$request[$k] = addslashes(trim($v));
				}
			}
}

//request转码
function convert_req(&$req)
{
	foreach($req as $k=>$v)
	{
		if(is_array($v))
		{
			convert_req($req[$k]);
		}
		else
		{
			if(!is_u8($v))
			{
				$req[$k] = iconv("gbk","utf-8",$v);
			}
		}
	}
}

function is_u8($string)
{
	if(strlen($string)>255)
	$tag = true;
	else
	$tag = preg_match('%^(?:
		 [\x09\x0A\x0D\x20-\x7E]            # ASCII
	   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   )*$%xs', $string);

   return $tag;
// 	$encode = mb_detect_encoding($string,array("GB2312","GBK","UTF-8"));
// 	if($encode=="UTF-8")
// 		return true;
// 	else
// 		return false;
}

//清除缓存
function clear_cache()
{
		//系统后台缓存
		clear_dir_file(get_real_path()."public/runtime/admin/Cache/");
		clear_dir_file(get_real_path()."public/runtime/admin/Data/_fields/");
		clear_dir_file(get_real_path()."public/runtime/admin/Temp/");
		clear_dir_file(get_real_path()."public/runtime/admin/Logs/");
		@unlink(get_real_path()."public/runtime/admin/~app.php");
		@unlink(get_real_path()."public/runtime/admin/~runtime.php");
		@unlink(get_real_path()."public/runtime/admin/lang.js");
		@unlink(get_real_path()."public/runtime/app/config_cache.php");


		//数据缓存
		clear_dir_file(get_real_path()."public/runtime/app/data_caches/");
		clear_dir_file(get_real_path()."public/runtime/app/db_caches/");
		$GLOBALS['cache']->clear();
		clear_dir_file(get_real_path()."public/runtime/data/");

		//模板页面缓存
		clear_dir_file(get_real_path()."public/runtime/app/tpl_caches/");
		clear_dir_file(get_real_path()."public/runtime/app/tpl_compiled/");
		@unlink(get_real_path()."public/runtime/app/lang.js");

		//脚本缓存
		clear_dir_file(get_real_path()."public/runtime/statics/");



}
function clear_dir_file($path,$include_path=true)
{
   if ( $dir = opendir( $path ) )
   {
            while ( $file = readdir( $dir ) )
            {
                $check = is_dir( $path. $file );
                if ( !$check )
                {
                    @unlink( $path . $file );
                }
                else
                {
                 	if($file!='.'&&$file!='..')
                 	{
                 		clear_dir_file($path.$file."/");
                 	}
                 }
            }
            closedir( $dir );
            if($include_path)
            rmdir($path);
            return true;
   }
}


function check_install()
{
	if(!file_exists(get_real_path()."public/install.lock"))
	{
	    clear_cache();
		header('Location:'.APP_ROOT.'/install');
		exit;
	}
}


function send_user_withdraw_sms($user_id,$money)
{

		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);

		$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_USER_WITHDRAW_SMS'");

		//chenfq by add 添加支持：app, 推送模板
		if(app_conf("SMS_ON") == 1 || $tmpl['is_allow_app'] == 1)
		{

				$tmpl_content = $tmpl['content'];

				$GLOBALS['tmpl']->assign("user_name",$user_info['user_name']);
				$GLOBALS['tmpl']->assign("money_format",round($money,2)."元");
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);


				$schedule_data['content'] = addslashes($msg);

				if(app_conf("SMS_ON") == 1)
				{
					$schedule_data['dest'] = $user_info['mobile'];
					send_schedule_plan("sms", $user_info['user_name']."提现短信通知", $schedule_data, NOW_TIME,$schedule_data['dest']);
				}

				if($tmpl['is_allow_app'] == 1)
				{
					$schedule_data['dest'] = $user_info['device_token'];

					if ($user_info['dev_type'] == 'ios'){
						send_schedule_plan("ios", $user_info['user_name']."提现通知", $schedule_data, NOW_TIME,$schedule_data['dest']);
					}else{
						send_schedule_plan("android", $user_info['user_name']."提现通知", $schedule_data, NOW_TIME,$schedule_data['dest']);
					}
				}
		}

}
function send_lottery_sms($user_id,$duobao_item)
{

    $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);

    $tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_USER_LOTTERY'");

    //chenfq by add 添加支持：app, 推送模板
    if(app_conf("SMS_ON") == 1 || $tmpl['is_allow_app'] == 1)
    {

        $tmpl_content = $tmpl['content'];

        $GLOBALS['tmpl']->assign("user_name",$user_info['user_name']);
        $GLOBALS['tmpl']->assign("duobao_item",$duobao_item);
        $msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);

        $schedule_data['content'] = addslashes($msg);

        if(app_conf("SMS_ON") == 1)
        {
            $schedule_data['dest'] = $user_info['mobile'];
            send_schedule_plan("sms", $user_info['user_name']."夺宝中奖短信通知", $schedule_data, NOW_TIME,$schedule_data['dest']);
        }

        if($user_info['device_token'])
        {
            $schedule_data['dest'] = $user_info['device_token'];

            if ($user_info['dev_type'] == 'ios'){
                send_schedule_plan("ios", $user_info['user_name']."中奖通知", $schedule_data, NOW_TIME,$schedule_data['dest']);
            }elseif($user_info['dev_type']=="android"){
                send_schedule_plan("android", $user_info['user_name']."中奖通知", $schedule_data, NOW_TIME,$schedule_data['dest']);
            }
        }
    }

}

function send_user_withdraw_mail($user_id,$money)
{
	if(app_conf("MAIL_ON")==1)
	{
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		if($user_info['email'])
		{
			$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_USER_WITHDRAW_MAIL'");
			$tmpl_content = $tmpl['content'];

			$GLOBALS['tmpl']->assign("user_name",$user_info['user_name']);
			$GLOBALS['tmpl']->assign("money_format",round($money,2)."元");
			$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
			$msg_data['dest'] = $user_info['email'];
			$msg_data['content'] = addslashes($msg);
			$msg_data['is_html'] = $tmpl['is_html'];

			send_schedule_plan("mail", $user_info['user_name']."提现通知", $msg_data, NOW_TIME,$msg_data['dest']);

		}
	}
}


//发密码验证邮件
function send_user_password_mail($user_id)
{
		$verify_code = rand(111111,999999);
		$GLOBALS['db']->query("update ".DB_PREFIX."user set password_verify = '".$verify_code."' where id = ".$user_id);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		if($user_info)
		{
			$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_MAIL_USER_PASSWORD'");
			$tmpl_content=  $tmpl['content'];
			$user_info['password_url'] = SITE_DOMAIN.url("index","user#modify_password", array("code"=>$user_info['password_verify'],"id"=>$user_info['id']));
			$GLOBALS['tmpl']->assign("user",$user_info);
			$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);

			$msg_data['dest'] = $user_info['email'];
			$msg_data['content'] = addslashes($msg);
			$msg_data['is_html'] = $tmpl['is_html'];

			send_schedule_plan("mail", $user_info['user_name']."重置密码", $msg_data, NOW_TIME,$msg_data['dest']);
		}
}


//发短信收款单
function send_payment_sms($notice_id)
{
	if(app_conf("SMS_SEND_PAYMENT")==1)
	{
		$notice_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$notice_id);
		if($notice_data)
		{
			$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_SMS_PAYMENT'");

			if(app_conf("SMS_ON") == 1 || $tmpl['is_allow_app'] == 1){

				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$notice_data['user_id']);
				$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$notice_data['order_id']);

				$tmpl_content = $tmpl['content'];
				$notice_data['user_name'] = $user_info['user_name'];
				$notice_data['order_sn'] = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$notice_data['order_id']);
				$notice_data['pay_time_format'] = to_date($notice_data['pay_time']);
				$notice_data['money_format'] = round($notice_data['money'],2)."元";
				$GLOBALS['tmpl']->assign("payment_notice",$notice_data);
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);


				$schedule_data['content'] = addslashes($msg);

				if(app_conf("SMS_ON") == 1)
				{
					if($user_info['mobile']!='')
					{
						$schedule_data['dest'] = $user_info['mobile'];
					}
					else
					{
						$schedule_data['dest'] = $order_info['mobile'];
					}
					send_schedule_plan("sms", $user_info['user_name']."交易通知", $schedule_data, NOW_TIME,$schedule_data['dest']);
				}

				if($tmpl['is_allow_app'] == 1)
				{
					$schedule_data['dest'] = $user_info['device_token'];

					if ($user_info['dev_type'] == 'ios'){
						send_schedule_plan("ios", $user_info['user_name']."交易通知", $schedule_data, NOW_TIME,$schedule_data['dest']);
					}else{
						send_schedule_plan("android", $user_info['user_name']."交易通知", $schedule_data, NOW_TIME,$schedule_data['dest']);
					}
				}

			}
		}
	}
}

//发邮件收款单
function send_payment_mail($notice_id)
{
	if(app_conf("MAIL_ON")==1&&app_conf("MAIL_SEND_PAYMENT")==1)
	{
		$notice_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$notice_id);
		if($notice_data)
		{
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$notice_data['user_id']);
			if($user_info['email']!='')
			{
				$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_MAIL_PAYMENT'");
				$tmpl_content = $tmpl['content'];
				$notice_data['user_name'] = $user_info['user_name'];
				$notice_data['order_sn'] = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$notice_data['order_id']);
				$notice_data['pay_time_format'] = to_date($notice_data['pay_time']);
				$notice_data['money_format'] = round($notice_data['money'],2)."元";
				$GLOBALS['tmpl']->assign("payment_notice",$notice_data);
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
				$msg_data['dest'] = $user_info['email'];
				$msg_data['title'] = $GLOBALS['lang']['PAYMENT_NOTICE'];
				$msg_data['content'] = addslashes($msg);
				$msg_data['is_html'] = $tmpl['is_html'];

				send_schedule_plan("mail", $user_info['user_name']."交易通知", $msg_data, NOW_TIME,$msg_data['dest']);
			}
		}
	}
}



//发邮件发货单
function send_delivery_mail($notice_sn,$deal_names = '',$order_id)
{
	if(app_conf("MAIL_ON")==1&&app_conf("MAIL_SEND_DELIVERY")==1)
	{
		$notice_data = $GLOBALS['db']->getRow("select dn.* from ".DB_PREFIX."delivery_notice as dn left join ".DB_PREFIX."deal_order_item as doi on dn.order_item_id = doi.id where dn.notice_sn = '".$notice_sn."' and doi.order_id = ".$order_id);
		if($notice_data)
		{
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$notice_data['user_id']);
			if($user_info['email']!='')
			{
				$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_MAIL_DELIVERY'");
				$tmpl_content = $tmpl['content'];
				$notice_data['user_name'] = $user_info['user_name'];
				$notice_data['order_sn'] = $GLOBALS['db']->getOne("select do.order_sn from ".DB_PREFIX."deal_order_item as doi left join ".DB_PREFIX."deal_order as do on doi.order_id = do.id where doi.id = ".$notice_data['order_item_id']);
				$notice_data['delivery_time_format'] = to_date($notice_data['delivery_time']);
				$notice_data['deal_names'] = $deal_names;
				$GLOBALS['tmpl']->assign("delivery_notice",$notice_data);
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);

				$msg_data['dest'] = $user_info['email'];
				$msg_data['title'] = $GLOBALS['lang']['DELIVERY_NOTICE'];
				$msg_data['content'] = addslashes($msg);
				$msg_data['is_html'] = $tmpl['is_html'];
				send_schedule_plan("mail", $user_info['user_name']."发货通知", $msg_data, NOW_TIME,$msg_data['dest']);
			}
		}
	}
}

//发短信发货单
function send_delivery_sms($notice_sn,$deal_names = '',$order_id)
{
	if(app_conf("SMS_SEND_DELIVERY")==1)
	{
		$notice_data = $GLOBALS['db']->getRow("select dn.* from ".DB_PREFIX."delivery_notice as dn left join ".DB_PREFIX."deal_order_item as doi on dn.order_item_id = doi.id where dn.notice_sn = '".$notice_sn."' and doi.order_id = ".$order_id);
		if($notice_data)
		{
			$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_SMS_DELIVERY'");
			if(app_conf("SMS_ON") == 1 || $tmpl['is_allow_wx'] == 1 || $tmpl['is_allow_app'] == 1)
			{
				$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$notice_data['user_id']);

				$tmpl_content = $tmpl['content'];
				$notice_data['user_name'] = $user_info['user_name'];
				$notice_data['order_sn'] = $GLOBALS['db']->getOne("select do.order_sn from ".DB_PREFIX."deal_order_item as doi left join ".DB_PREFIX."deal_order as do on doi.order_id = do.id where doi.id = ".$notice_data['order_item_id']);
				$notice_data['delivery_time_format'] = to_date($notice_data['delivery_time']);
				$notice_data['deal_names'] = $deal_names;
				$GLOBALS['tmpl']->assign("delivery_notice",$notice_data);
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
				if($user_info['mobile']!='')
				{
					$schedule_data['dest'] = $user_info['mobile'];
					$schedule_data['content'] = addslashes($msg);

					send_schedule_plan("sms", $user_info['user_name']."发货通知", $schedule_data, NOW_TIME,$schedule_data['dest']);

				}

				if($order_info['mobile']!=''&&$order_info['mobile']!=$user_info['mobile'])
				{
					$schedule_data['dest'] = $order_info['mobile'];
					$schedule_data['content'] = addslashes($msg);;
					send_schedule_plan("sms", $user_info['user_name']."发货通知", $schedule_data, NOW_TIME,$schedule_data['dest']);
				}
			}
		}
	}
}


//发短信验证码
function send_verify_sms($mobile,$code)
{
	if(app_conf("SMS_ON")==1)
	{

				$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_SMS_VERIFY_CODE'");
				$tmpl_content = $tmpl['content'];
				$verify['mobile'] = $mobile;
				$verify['code'] = $code;
				$GLOBALS['tmpl']->assign("verify",$verify);
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
				$schedule_data['dest'] = $mobile;
				$schedule_data['content'] = addslashes($msg);

				send_schedule_plan("sms", "短信验证码", $schedule_data, NOW_TIME,$schedule_data['dest']);


	}
}


function format_sprintf_price($price){
    return sprintf( "%.2f", round($price, 2) );
}

function format_price($price)
{
	return round($price,2)."元";
}

function format_duobao_price($price)
{
	return round($price,2)." 夺宝币";
}
function format_coupons_price($price)
{
    return round($price,2)." 优惠币";
}
function format_score($score)
{
	return intval($score)."".app_conf("SCORE_UNIT");
}

//utf8 字符串截取
function msubstr($str, $start=0, $length=15, $charset="utf-8", $suffix=true)
{
	if(function_exists("mb_substr"))
    {
        $slice =  mb_substr($str, $start, $length, $charset);
        if($suffix&$slice!=$str) return $slice."…";
    	return $slice;
    }
    elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix&&$slice!=$str) return $slice."…";
    return $slice;
}


//字符编码转换
if(!function_exists("iconv"))
{
	function iconv($in_charset,$out_charset,$str)
	{
		require 'libs/iconv.php';
		$chinese = new Chinese();
		return $chinese->Convert($in_charset,$out_charset,$str);
	}
}

//JSON兼容
if(!function_exists("json_encode"))
{
	function json_encode($data)
	{
		require_once 'libs/json.php';
		$JSON = new JSON();
		return $JSON->encode($data);
	}
}
if(!function_exists("json_decode"))
{
	function json_decode($data)
	{
		require_once 'libs/json.php';
		$JSON = new JSON();
		return $JSON->decode($data,1);
	}
}

//邮件格式验证的函数
function check_email($email)
{
	if(!empty($email) && !preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/",$email))
	{
		return false;
	}
	else
	return true;
}

//验证手机号码
function check_mobile($mobile)
{
	if(!empty($mobile) && !preg_match("/^(1[34578]\d{9})$/",$mobile))
	{
		return false;
	}
	else
	return true;
}

/**
 * 验证用户名格式
 * @param unknown_type $username
 */
function check_username($username)
{
	if(strlen($username)<4)
	{
		return false;
	}
	if(preg_match("/^(1[3458]\d{9})$/",$username))
	{
		return false;
	}
	if(preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/",$username))
	{
		return false;
	}
	if(preg_match("/^游客_\d+$/",$username))
	{
		return false;
	}
	return true;
}

/**
 * 页面跳转
 */
function app_redirect($url,$time=0,$msg='')
{
    //多行URL地址支持
    $url = str_replace(array("\n", "\r"), '', $url);
    if (!headers_sent()) {
        // redirect
        if(0===$time&&$msg=="") {
        	if(substr($url,0,1)=="/")
        	{
        		if(defined("SITE_DOMAIN"))
        			header("Location:".SITE_DOMAIN.$url);
        		else
        			header("Location:".$url);
        	}
        	else
        	{
        		header("Location:".$url);
        	}

        }else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    }else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if($time!=0)
            $str   .=   $msg;
        exit($str);
    }
}



/**
 * 验证访问IP的有效性
 * @param ip地址 $ip_str
 * @param 访问页面 $module
 * @param 时间间隔 $time_span
 * @param 数据ID $id
 */
function check_ipop_limit($ip_str,$module,$time_span=0,$id=0)
{
		$op = es_session::get($module."_".$id."_ip");
    	if(empty($op))
    	{
    		$check['ip']	=	 CLIENT_IP;
    		$check['time']	=	NOW_TIME;
    		es_session::set($module."_".$id."_ip",$check);
    		return true;  //不存在session时验证通过
    	}
    	else
    	{
    		$check['ip']	=	 CLIENT_IP;
    		$check['time']	=	NOW_TIME;
    		$origin	=	es_session::get($module."_".$id."_ip");

    		if($check['ip']==$origin['ip'])
    		{
    			if($check['time'] - $origin['time'] < $time_span)
    			{
    				return false;
    			}
    			else
    			{
    				es_session::set($module."_".$id."_ip",$check);
    				return true;  //不存在session时验证通过
    			}
    		}
    		else
    		{
    			es_session::set($module."_".$id."_ip",$check);
    			return true;  //不存在session时验证通过
    		}
    	}
    }

//发放返利的函数
function pay_referrals($id)
{
	$referrals_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."referrals where id = ".$id);
	if($referrals_data)
	{
		$sql = "update ".DB_PREFIX."referrals set pay_time = ".NOW_TIME." where id = ".$id." and pay_time = 0 ";
		$GLOBALS['db']->query($sql);
		$rs = $GLOBALS['db']->affected_rows();
		if($rs)
		{
			//开始发放返利
			require_once APP_ROOT_PATH."system/model/user.php";
			$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$referrals_data['order_id']);
			$user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".$referrals_data['user_id']);
			$rel_user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".$referrals_data['rel_user_id']);
			$referral_amount = $referrals_data['money']>0?format_price($referrals_data['money']):format_score($referrals_data['score']);
			$msg = sprintf($GLOBALS['lang']['REFERRALS_LOG'],$order_sn,$rel_user_name,$referral_amount);
			modify_account(array('money'=>$referrals_data['money'],'score'=>$referrals_data['score']),$referrals_data['user_id'],$msg);
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
//扣除返利的函数
function return_referrals($id)
{
	$referrals_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."referrals where order_id = ".$id);
	if($referrals_data)
	{
			$sql = "update ".DB_PREFIX."referrals set money = 0,score=0 where order_id = ".$id;
			$GLOBALS['db']->query($sql);
			$referrals_data['money'] = -($referrals_data['money']);
			$referrals_data['score'] = -($referrals_data['score']);
			//开始扣除返利
			require_once APP_ROOT_PATH."system/model/user.php";
			$order_sn = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$referrals_data['order_id']);
			$user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".$referrals_data['user_id']);
			$rel_user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".$referrals_data['rel_user_id']);
			$referral_amount = $referrals_data['money']<0?format_price($referrals_data['money']):format_score($referrals_data['score']);
			$msg = sprintf($GLOBALS['lang']['REFERRALS_RETURN_LOG'],$order_sn,$rel_user_name,$referral_amount);
			modify_account(array('money'=>$referrals_data['money'],'score'=>$referrals_data['score']),$referrals_data['user_id'],$msg);
			return true;

	}
	else
	{
		return false;
	}
}
//发货的通用函数
/**
 *
 * @param $order_id 订单ID
 * @param $order_deal_id  发货的订单商品ID
 * @param $delivery_sn  发货号
 */
function make_delivery_notice($order_id,$order_deal_id,$delivery_sn,$memo='',$express_id = 0,$location_id=0)
{
	//先删除原先相关的发货单号
	$GLOBALS['db']->query("delete from ".DB_PREFIX."delivery_notice where order_item_id = ".$order_deal_id);
	$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
	$delivery_notice['notice_sn'] = $delivery_sn;
	$delivery_notice['delivery_time'] = NOW_TIME;
	$delivery_notice['order_item_id'] = $order_deal_id;

	$delivery_notice['order_id'] = $order_info['id'];
	$delivery_notice['user_id'] = $order_info['user_id'];
	$delivery_notice['deal_id'] = $GLOBALS['db']->getOne("select deal_id from ".DB_PREFIX."deal_order_item where id = ".$order_deal_id);
	$delivery_notice['location_id'] = $location_id;
	$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
	$adm_id = intval($adm_session['adm_id']);
	$delivery_notice['admin_id'] = $adm_id;
	$delivery_notice['memo'] = $memo;
	$delivery_notice['express_id'] = $express_id;
	$GLOBALS['db']->autoExecute(DB_PREFIX."delivery_notice",$delivery_notice,'INSERT','','SILENT');
	return $GLOBALS['db']->insert_id();
}



function trim_bom($contents)
{
	$charset[1] = substr($contents, 0, 1);
	$charset[2] = substr($contents, 1, 1);
	$charset[3] = substr($contents, 2, 1);
	if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191)
	{
		$contents = substr($contents, 3);
		return $contents;
	}
	else
	{
		return $contents;
	}
}

function gzip_out($content)
{
	if($GLOBALS['refresh_page']&&!IS_DEBUG)
	{
		echo "<script>location.reload();</script>";
		exit;
	}

	if($distribution_cfg["CACHE_TYPE"]!="File")
	{
		if(preg_match_all("/href=\"([^\"]+)\"/i", $content, $matches))
		{
			foreach($matches[1] as $k=>$v)
			{
				$content = str_replace($v, trim_bom($v), $content);
			}
		}
	}

	header("Content-type: text/html; charset=utf-8");
    header("Cache-control: private");  //支持页面回跳
	$gzip = app_conf("GZIP_ON");
	if( intval($gzip)==1 )
	{
		if(!headers_sent()&&extension_loaded("zlib")&&preg_match("/gzip/i",$_SERVER["HTTP_ACCEPT_ENCODING"]))
		{
			$content = gzencode($content,9);
			header("Content-Encoding: gzip");
			header("Content-Length: ".strlen($content));
			echo $content;
		}
		else
		echo $content;
	}else{
		echo $content;
	}

}

function order_log($log_info,$order_id)
{
	$u = $GLOBALS['db']->getRow("select u.is_robot from ".DB_PREFIX."user as u left join ".DB_PREFIX."deal_order as o on o.user_id = u.id where o.id = '".$order_id."'");
	if($u&&$u['is_robot']==0)
	{
		$data['id'] = 0;
		$data['log_info'] = $log_info;
		$data['log_time'] = NOW_TIME;
		$data['order_id'] = $order_id;
		$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_log", $data);
	}
}


/**
	 * 保存图片
	 * @param array $upd_file  即上传的$_FILES数组
	 * @param array $key $_FILES 中的键名 为空则保存 $_FILES 中的所有图片
	 * @param string $dir 保存到的目录
	 * @param array $whs
	 	可生成多个缩略图
		数组 参数1 为宽度，
			 参数2为高度，
			 参数3为处理方式:0(缩放,默认)，1(剪裁)，
			 参数4为是否水印 默认为 0(不生成水印)
	 	array(
			'thumb1'=>array(300,300,0,0),
			'thumb2'=>array(100,100,0,0),
			'origin'=>array(0,0,0,0),  宽与高为0为直接上传
			...
		)，
	 * @param array $is_water 原图是否水印
	 * @return array
	 	array(
			'key'=>array(
				'name'=>图片名称，
				'url'=>原图web路径，
				'path'=>原图物理路径，
				有略图时
				'thumb'=>array(
					'thumb1'=>array('url'=>web路径,'path'=>物理路径),
					'thumb2'=>array('url'=>web路径,'path'=>物理路径),
					...
				)
			)
			....
		)
	 */
//$img = save_image_upload($_FILES,'avatar','temp',array('avatar'=>array(300,300,1,1)),1);
function save_image_upload($upd_file, $key='',$dir='temp', $whs=array(),$is_water=false,$need_return = false)
{
		require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
		$image = new es_imagecls();
		$image->max_size = intval(app_conf("MAX_IMAGE_SIZE"));

		$list = array();

		if(empty($key))
		{
			foreach($upd_file as $fkey=>$file)
			{
				$list[$fkey] = false;
				$image->init($file,$dir);
				if($image->save())
				{
					$list[$fkey] = array();
					$list[$fkey]['url'] = $image->file['target'];
					$list[$fkey]['path'] = $image->file['local_target'];
					$list[$fkey]['name'] = $image->file['prefix'];
				}
				else
				{
					if($image->error_code==-105)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'上传的图片太大');
						}
						else
						echo "上传的图片太大";
					}
					elseif($image->error_code==-104||$image->error_code==-103||$image->error_code==-102||$image->error_code==-101)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'非法图像');
						}
						else
						echo "非法图像";
					}
					exit;
				}
			}
		}
		else
		{
			$list[$key] = false;
			$image->init($upd_file[$key],$dir);
			if($image->save())
			{
				$list[$key] = array();
				$list[$key]['url'] = $image->file['target'];
				$list[$key]['path'] = $image->file['local_target'];
				$list[$key]['name'] = $image->file['prefix'];
			}
			else
				{
					if($image->error_code==-105)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'上传的图片太大');
						}
						else
						echo "上传的图片太大";
					}
					elseif($image->error_code==-104||$image->error_code==-103||$image->error_code==-102||$image->error_code==-101)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'非法图像');
						}
						else
						echo "非法图像";
					}
					exit;
				}
		}

		$water_image = APP_ROOT_PATH.app_conf("WATER_MARK");
		$alpha = app_conf("WATER_ALPHA");
		$place = app_conf("WATER_POSITION");

		foreach($list as $lkey=>$item)
		{
				//循环生成规格图
				foreach($whs as $tkey=>$wh)
				{

					$list[$lkey]['thumb'][$tkey]['url'] = false;
					$list[$lkey]['thumb'][$tkey]['path'] = false;
					if($wh[0] > 0 || $wh[1] > 0)  //有宽高度
					{
						$thumb_type = isset($wh[2]) ? intval($wh[2]) : 0;  //剪裁还是缩放， 0缩放 1剪裁
						if($thumb = $image->thumb($item['path'],$wh[0],$wh[1],$thumb_type))
						{
							$list[$lkey]['thumb'][$tkey]['url'] = $thumb['url'];
							$list[$lkey]['thumb'][$tkey]['path'] = $thumb['path'];
							if(isset($wh[3]) && intval($wh[3]) > 0)//需要水印
							{
								$paths = pathinfo($list[$lkey]['thumb'][$tkey]['path']);
								$path = $paths['dirname'];
				        		$path = $path."/origin/";
				        		if (!is_dir($path)) {
						             @mkdir($path);
						             @chmod($path, 0777);
					   			}
				        		$filename = $paths['basename'];
								@file_put_contents($path.$filename,@file_get_contents($list[$lkey]['thumb'][$tkey]['path']));
								$image->water($list[$lkey]['thumb'][$tkey]['path'],$water_image,$alpha, $place);
							}
						}
					}
				}
			if($is_water)
			{
				$paths = pathinfo($item['path']);
				$path = $paths['dirname'];
        		$path = $path."/origin/";
        		if (!is_dir($path)) {
		             @mkdir($path);
		             @chmod($path, 0777);
	   			}
        		$filename = $paths['basename'];
				@file_put_contents($path.$filename,@file_get_contents($item['path']));
				$image->water($item['path'],$water_image,$alpha, $place);
			}
		}
		return $list;
}

function empty_tag($string)
{
	$string = preg_replace(array("/\[img\]\d+\[\/img\]/","/\[[^\]]+\]/"),array("",""),$string);
	if(strim($string)=='')
	return $GLOBALS['lang']['ONLY_IMG'];
	else
	return $string;
	//$string = str_replace(array("[img]","[/img]"),array("",""),$string);
}

//验证是否有非法字汇，未完成
function valid_str($string)
{
	$string = msubstr($string,0,5000);
	if(app_conf("FILTER_WORD")!='')
	$string = preg_replace("/".app_conf("FILTER_WORD")."/","*",$string);
	return $string;
}


/**
 * utf8字符转Unicode字符
 * @param string $char 要转换的单字符
 * @return void
 */
function utf8_to_unicode($char)
{
	switch(strlen($char))
	{
		case 1:
			return ord($char);
		case 2:
			$n = (ord($char[0]) & 0x3f) << 6;
			$n += ord($char[1]) & 0x3f;
			return $n;
		case 3:
			$n = (ord($char[0]) & 0x1f) << 12;
			$n += (ord($char[1]) & 0x3f) << 6;
			$n += ord($char[2]) & 0x3f;
			return $n;
		case 4:
			$n = (ord($char[0]) & 0x0f) << 18;
			$n += (ord($char[1]) & 0x3f) << 12;
			$n += (ord($char[2]) & 0x3f) << 6;
			$n += ord($char[3]) & 0x3f;
			return $n;
	}
}

/**
 * utf8字符串分隔为unicode字符串
 * @param string $str 要转换的字符串
 * @param string $depart 分隔,默认为空格为单字
 * @return string
 */
function str_to_unicode_word($str,$depart=' ')
{
	$arr = array();
	$str_len = mb_strlen($str,'utf-8');
	for($i = 0;$i < $str_len;$i++)
	{
		$s = mb_substr($str,$i,1,'utf-8');
		if($s != ' ' && $s != '　')
		{
			$arr[] = 'ux'.utf8_to_unicode($s);
		}
	}
	return implode($depart,$arr);
}


/**
 * utf8字符串分隔为unicode字符串
 * @param string $str 要转换的字符串
 * @return string
 */
function str_to_unicode_string($str)
{
	$string = str_to_unicode_word($str,'');
	return $string;
}

//分词
function div_str($str)
{
	require_once APP_ROOT_PATH."system/libs/words.php";
	$words = words::segment($str);
	$words[] = $str;
	return $words;
}

/**
 *
 * @param $tag  //要插入的关键词
 * @param $table  //表名
 * @param $id  //数据ID
 * @param $field		// tag_match/name_match/cate_match/locate_match
 */
function insert_match_item($tag,$table,$id,$field)
{
	if($tag=='')
	return;

	$unicode_tag = str_to_unicode_string($tag);
	$sql = "select count(*) from ".DB_PREFIX.$table." where match(".$field.") against ('".$unicode_tag."' IN BOOLEAN MODE) and id = ".$id;
	$rs = $GLOBALS['db']->getOne($sql);
	if(intval($rs) == 0)
	{
		$match_row = $GLOBALS['db']->getRow("select * from ".DB_PREFIX.$table." where id = ".$id);
		if($match_row[$field]=="")
		{
				$match_row[$field] = $unicode_tag;
				$match_row[$field."_row"] = $tag;
		}
		else
		{
				$match_row[$field] = $match_row[$field].",".$unicode_tag;
				$match_row[$field."_row"] = $match_row[$field."_row"].",".$tag;
		}
		$GLOBALS['db']->autoExecute(DB_PREFIX.$table, $match_row, $mode = 'UPDATE', "id=".$id, $querymode = 'SILENT');

	}
}

function get_all_parent_id($id,$table,&$arr = array())
{
	if(intval($id)>0)
	{
		$arr[] = $id;
		$pid = $GLOBALS['db']->getOne("select pid from ".$table." where id = ".$id);
		if($pid>0)
		{
			get_all_parent_id($pid,$table,$arr);
		}
	}
}

/**
 *
 * @param $title_name 标题名称
 * @param $type  类型 0:话题 1:活动
 */
function syn_topic_title($title_name,$type=0)
{
	$data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."topic_title where name = '".$title_name."'");
	if(!$data)
	{
		$data = array("name"=>$title_name);
		$GLOBALS['db']->autoExecute(DB_PREFIX."topic_title", $data, $mode = 'INSERT', "", $querymode = 'SILENT');
	}
	$topic_group = intval($type)==0?"share":"event";
	$count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."topic where title like '%".$title_name."%' and topic_group = '".$topic_group."'"));
	$GLOBALS['db']->query("update ".DB_PREFIX."topic_title set count = ".$count);
}


function syn_topic_match($topic_id)
{
	$topic = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."topic where id = ".$topic_id);
	if(preg_match_all("/@([^\f\n\r\t\v: ]+)/i",$topic['content'],$name_matches))
	{
		$name_matches[1] = array_unique($name_matches[1]);
		foreach($name_matches[1] as $match_item)
		{
			$uinfo = $GLOBALS['db']->getRow("select id,user_name from ".DB_PREFIX."user where user_name = '".$match_item."' and is_effect = 1 and is_delete = 0");
			if($uinfo)
			{
				insert_match_item($match_item,"topic",$topic_id,"user_name_match");
			}

		}
	}
	$tags = explode(" ",$topic['tags']);
	foreach($tags as $tag)
	{
		insert_match_item(trim($tag),"topic",$topic_id,"keyword_match");
		syn_topic_cate(trim($tag),$topic_id);
	}

	require_once APP_ROOT_PATH."system/libs/words.php";
	$segments = words::segment($topic['content']);
	foreach($segments as $segment)
	{
		insert_match_item($segment,"topic",$topic_id,"keyword_match");
	}
	$segments = div_str($topic['title']);
	foreach($segments as $segment)
	{
		insert_match_item($segment,"topic",$topic_id,"keyword_match");
	}


	$image_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."topic_image where topic_id = ".$topic_id);
	$has_image = intval($image_count)>0?1:0;
	$GLOBALS['db']->query("update ".DB_PREFIX."topic set has_image = ".$has_image." where id = ".$topic_id);

}


//封装url

function url($app_index,$route="index",$param=array())
{
	if($route=="tuan")$route = "cate";
	//关于分类频道的封装
	if($param['un']=="")
	{
		if($route=="cate"||$route=="stores"||$route=="staffs"
		||$route=="cate#index"||$route=="stores#index"||$route=="staffs#index")
		{

			$deal_cate_id = intval($param['cid']);
			$cate_list = load_auto_cache("cache_deal_cate"); //分类缓存
			if($cate_list[$deal_cate_id])
			{
				$channel_uname = $cate_list[$deal_cate_id]['channel_id'];
			}
			$channel = load_auto_cache("channel",array("un"=>$channel_uname));
			if($channel)
			{
				$param['un'] = $channel['uname'];
			}
			elseif($GLOBALS['channel'])
			{
				$param['un'] = $GLOBALS['channel']['uname'];
			}
		}
	}

	$key = md5("URL_KEY_".$app_index.$route.serialize($param));
	if(isset($GLOBALS[$key]))
	{
		$url = $GLOBALS[$key];
		return $url;
	}

	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}

	$show_city = intval($GLOBALS['city_count'])>1?true:false;  //有多个城市时显示城市名称到url
	$route_array = explode("#",$route);

	if(isset($param)&&$param!=''&&!is_array($param))
	{
		$param['id'] = $param;
	}

	$module = strtolower(trim($route_array[0]));
	$action = strtolower(trim($route_array[1]));

	if(!$module||$module=='index')$module="";
	if(!$action||$action=='index')$action="";

	if(app_conf("URL_MODEL")==0 || $GLOBALS['request']['from']=="wap")//fwb改过
	{
		//过滤主要的应用url
		if($app_index==app_conf("MAIN_APP"))
		$app_index = "index";

		//原始模式
		$url = APP_ROOT."/".$app_index.".php";
		if($module!=''||$action!=''||count($param)>0||$show_city) //有后缀参数
		{
			$url.="?";
		}

		if(isset($param['city']))
		{
			$url .= "city=".$param['city']."&";
			unset($param['city']);
		}
		if($module&&$module!='')
		$url .= "ctl=".$module."&";
		if($action&&$action!='')
		$url .= "act=".$action."&";
		if(count($param)>0)
		{
			foreach($param as $k=>$v)
			{
				if($k&&$v)
				$url =$url.$k."=".urlencode($v)."&";
			}
		}
		if(substr($url,-1,1)=='&'||substr($url,-1,1)=='?') $url = substr($url,0,-1);
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}
	else
	{
		//重写的默认
		$url = APP_ROOT;

		if($app_index!='index')
		$url .= "/".$app_index;

		if($module&&$module!='')
		$url .= "/".$module;
		if($action&&$action!='')
		$url .= "/".$action;

		if(count($param)>0)
		{
			$url.="/";
			foreach($param as $k=>$v)
			{
				if($k!='city')
				$url =$url.$k."-".urlencode($v)."-";
			}
		}

		//过滤主要的应用url
		if($app_index==app_conf("MAIN_APP"))
		$url = str_replace("/".app_conf("MAIN_APP"),"",$url);

		$route = $module."#".$action;
		switch ($route)
		{
				case "xxx":
					break;
				default:
					break;
		}

		if(substr($url,-1,1)=='/'||substr($url,-1,1)=='-') $url = substr($url,0,-1);



		if(isset($param['city']))
		{
			$city_uname = $param['city'];

			if($GLOBALS['distribution_cfg']['DOMAIN_ROOT']!="")
			{
				$domain = "http://".$city_uname.".".$GLOBALS['distribution_cfg']['DOMAIN_ROOT'];
				return $domain.$url;
			}
			else
			{
				return $url."/city/".$city_uname;
			}

		}
		if($url=='')$url="/";
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}


}

function wap_url($app_index,$route="index",$param=array())
{
	$param['show_prog'] = 1;
	global $page_type;
	if($page_type)
	{
		$param['page_type'] = $page_type;
	}
	global $spid;
	if($spid)
	{
		if(!isset($param['spid']))
		$param['spid'] = $spid;
	}

	$key = md5("WAP_URL_KEY_".$app_index.$route.serialize($param));
	if(isset($GLOBALS[$key]))
	{
		$url = $GLOBALS[$key];
		return $url;
	}

	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}

	$show_city = intval($GLOBALS['city_count'])>1?true:false;  //有多个城市时显示城市名称到url
	$route_array = explode("#",$route);

	if(isset($param)&&$param!=''&&!is_array($param))
	{
		$param['id'] = $param;
	}

	$module = strtolower(trim($route_array[0]));
	$action = strtolower(trim($route_array[1]));

	if(!$module||$module=='index')$module="";
	if(!$action||$action=='index')$action="";

	//原始模式
	$url = APP_ROOT."/wap/".$app_index.".php";
	if($module!=''||$action!=''||count($param)>0||$show_city) //有后缀参数
	{
		$url.="?";
		/** 关闭url传输自定义session到url中，很重要，如有遇到浏览器不支持cookie的再议*/
		if($GLOBALS['define_sess_id']&&$GLOBALS['page_type']=="app")
		{
			$url.="sess_id=".$GLOBALS['sess_id']."&";
		}
	}
	else
	{
		/** 关闭url传输自定义session到url中，很重要，如有遇到浏览器不支持cookie的再议*/
		if($GLOBALS['define_sess_id']&&$GLOBALS['page_type']=="app")
		{
			$url.="?sess_id=".$GLOBALS['sess_id']."&";
		}

	}


	if(isset($param['city']))
	{
		$url .= "city=".$param['city']."&";
		unset($param['city']);
	}
	if($module&&$module!='')
		$url .= "ctl=".$module."&";
	if($action&&$action!='')
		$url .= "act=".$action."&";
	if(count($param)>0)
	{
		foreach($param as $k=>$v)
		{
			if($k&&$v)
				$url =$url.$k."=".urlencode($v)."&";
		}
	}
	if(substr($url,-1,1)=='&'||substr($url,-1,1)=='?') $url = substr($url,0,-1);
	$GLOBALS[$key] = $url;
	set_dynamic_cache($key,$url);
	return $url;
}

function unicode_encode($name) {//to Unicode
    $name = iconv('UTF-8', 'UCS-2', $name);
    $len = strlen($name);
    $str = '';
    for($i = 0; $i < $len - 1; $i = $i + 2) {
        $c = $name[$i];
        $c2 = $name[$i + 1];
        if (ord($c) > 0) {// 两个字节的字
            $cn_word = '\\'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
            $str .= strtoupper($cn_word);
        } else {
            $str .= $c2;
        }
    }
    return $str;
}

function unicode_decode($name) {//Unicode to
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $name, $matches);
    if (!empty($matches)) {
        $name = '';
        for ($j = 0; $j < count($matches[0]); $j++) {
            $str = $matches[0][$j];
            if (strpos($str, '\\u') === 0) {
                $code = base_convert(substr($str, 2, 2), 16, 10);
                $code2 = base_convert(substr($str, 4), 16, 10);
                $c = chr($code).chr($code2);
                $c = iconv('UCS-2', 'UTF-8', $c);
                $name .= $c;
            } else {
                $name .= $str;
            }
        }
    }
    return $name;
}


//载入动态缓存数据
function load_dynamic_cache($name)
{
	if(isset($GLOBALS['dynamic_cache'][$name]))
	{
		return $GLOBALS['dynamic_cache'][$name];
	}
	else
	{
		return false;
	}
}

function set_dynamic_cache($name,$value,$timeout = 300)
{
	global $dynamic_time_out;
	$dynamic_time_out = $timeout;
	if(!isset($GLOBALS['dynamic_cache'][$name]))
	{
		if(count($GLOBALS['dynamic_cache'])>MAX_DYNAMIC_CACHE_SIZE)
		{
			array_shift($GLOBALS['dynamic_cache']);
		}
		$GLOBALS['dynamic_cache'][$name] = $value;
	}
}


//同步一张图片到分享图片表(图片可以为本地获远程。 远程需要开启file_get_contents()的远程权限)
function syn_image_to_topic($image)
{
    $image = str_replace("./public", APP_ROOT_PATH."public", $image);
	$image_str = @file_get_contents($image);
	$file_name = md5(microtime(true)).rand(10,99).".jpg";

	//创建comment目录
		if (!is_dir(APP_ROOT_PATH."public/comment")) {
	             @mkdir(APP_ROOT_PATH."public/comment");
	             @chmod(APP_ROOT_PATH."public/comment", 0777);
	        }

	    $dir = to_date(NOW_TIME,"Ym");
	    if (!is_dir(APP_ROOT_PATH."public/comment/".$dir)) {
	             @mkdir(APP_ROOT_PATH."public/comment/".$dir);
	             @chmod(APP_ROOT_PATH."public/comment/".$dir, 0777);
	        }

	    $dir = $dir."/".to_date(NOW_TIME,"d");
	    if (!is_dir(APP_ROOT_PATH."public/comment/".$dir)) {
	             @mkdir(APP_ROOT_PATH."public/comment/".$dir);
	             @chmod(APP_ROOT_PATH."public/comment/".$dir, 0777);
	        }

	    $dir = $dir."/".to_date(NOW_TIME,"H");
	    if (!is_dir(APP_ROOT_PATH."public/comment/".$dir)) {
	             @mkdir(APP_ROOT_PATH."public/comment/".$dir);
	             @chmod(APP_ROOT_PATH."public/comment/".$dir, 0777);
	        }

	   $file_url = "./public/comment/".$dir."/".$file_name;
	   $file_path = APP_ROOT_PATH."public/comment/".$dir."/".$file_name;
	   @file_put_contents($file_path,$image_str);
	   $filesize = intval(@filesize($file_path));

	   if($filesize>0)
	   {
		   	if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
		   	{
		   		syn_to_remote_image_server($file_url);
		   	}

		    $icon_url = get_spec_image($file_url,100,100,1);
		    require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
			$image = new es_imagecls();

			$info = $image->getImageInfo($file_path);
			$image_data['width'] = intval($info[0]);
			$image_data['height'] = intval($info[1]);
			$image_data['name'] =$file_name;
			$image_data['filesize'] = $filesize;
			$image_data['create_time'] = NOW_TIME;
			$image_data['user_id'] = intval($GLOBALS['user_info']['id']);
			$image_data['user_name'] = addslashes($GLOBALS['user_info']['user_name']);
			$image_data['path'] = $icon_url;
			$image_data['o_path'] = $file_url;
			$GLOBALS['db']->autoExecute(DB_PREFIX."topic_image",$image_data);
			$data['id'] = intval($GLOBALS['db']->insert_id());
			$data['url'] = $icon_url;
	   }
	   return $data;

}

function load_auto_cache($key,$param=array())
{
	require_once APP_ROOT_PATH."system/libs/auto_cache.php";
	$file =  APP_ROOT_PATH."system/auto_cache/".APP_TYPE."/".$key.".auto_cache.php";
	if(!file_exists($file))
	$file =  APP_ROOT_PATH."system/auto_cache/".$key.".auto_cache.php";
	if(file_exists($file))
	{
		require_once $file;
		$class = $key."_auto_cache";
		$obj = new $class;
		$result = $obj->load($param);
	}
	else
	$result = false;
	return $result;
}

function rm_auto_cache($key,$param=array())
{
	require_once APP_ROOT_PATH."system/libs/auto_cache.php";
	$file =  APP_ROOT_PATH."system/auto_cache/".$key.".auto_cache.php";
	if(file_exists($file))
	{
		require_once $file;
		$class = $key."_auto_cache";
		$obj = new $class;
		$obj->rm($param);
	}
}


function clear_auto_cache($key)
{
	require_once APP_ROOT_PATH."system/libs/auto_cache.php";
	$file =  APP_ROOT_PATH."system/auto_cache/".$key.".auto_cache.php";
	if(file_exists($file))
	{
		require_once $file;
		$class = $key."_auto_cache";
		$obj = new $class;
		$obj->clear_all();
	}
}


/*ajax返回*/
function ajax_return($data,$jsonp=false)
{
	if($jsonp)
	{
			$json = json_encode($data);
			header("Content-Type:text/html; charset=utf-8");
			echo $_GET['callback']."(".$json.")";exit;


	}
	else
	{
		header("Content-Type:text/html; charset=utf-8");
        echo(json_encode($data));
        exit;
	}
}


//增加会员活跃度
function increase_user_active($user_id,$log)
{
	$t_begin_time = to_timespan(to_date(NOW_TIME,"Y-m-d"));  //今天开始
	$t_end_time = to_timespan(to_date(NOW_TIME,"Y-m-d"))+ (24*3600 - 1);  //今天结束
	$y_begin_time = $t_begin_time - (24*3600); //昨天开始
	$y_end_time = $t_end_time - (24*3600);  //昨天结束

	$point = intval(app_conf("USER_ACTIVE_POINT"));
	$score = intval(app_conf("USER_ACTIVE_SCORE"));
	$money = floatval(app_conf("USER_ACTIVE_MONEY"));
	$point_max = intval(app_conf("USER_ACTIVE_POINT_MAX"));
	$score_max = intval(app_conf("USER_ACTIVE_SCORE_MAX"));
	$money_max = floatval(app_conf("USER_ACTIVE_MONEY_MAX"));

	$sum_money = floatval($GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."user_active_log where user_id = ".$user_id." and create_time between ".$t_begin_time." and ".$t_end_time));
	$sum_score = intval($GLOBALS['db']->getOne("select sum(score) from ".DB_PREFIX."user_active_log where user_id = ".$user_id." and create_time between ".$t_begin_time." and ".$t_end_time));
	$sum_point = intval($GLOBALS['db']->getOne("select sum(point) from ".DB_PREFIX."user_active_log where user_id = ".$user_id." and create_time between ".$t_begin_time." and ".$t_end_time));

	if($sum_money>=$money_max)$money = 0;
	if($sum_score>=$score_max)$score = 0;
	if($sum_point>=$point_max)$point = 0;

	if($money>0||$score>0||$point>0)
	{
		require_once  APP_ROOT_PATH."system/model/user.php";
		modify_account(array("money"=>$money,"score"=>$score,"point"=>$point),$user_id,$log);
		$data['user_id'] = $user_id;
		$data['create_time'] = NOW_TIME;
		$data['money'] = $money;
		$data['score'] = $score;
		$data['point'] = $point;
		$GLOBALS['db']->autoExecute(DB_PREFIX."user_active_log",$data);
	}
}


function is_animated_gif($filename){
 $fp=fopen($filename, 'rb');
 $filecontent=fread($fp, filesize($filename));
 fclose($fp);
 return strpos($filecontent,chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0')===FALSE?0:1;
}




function make_delivery_region_js()
{
	$path = APP_ROOT_PATH."public/runtime/region.js";
	if(!file_exists($path))
	{
		$jsStr = "var regionConf = ".get_delivery_region_js();
		@file_put_contents($path,$jsStr);
	}
}
function get_delivery_region_js($pid = 0)
{

		$jsStr = "";
		$childRegionList = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."delivery_region where pid = ".$pid." order by id asc");
		foreach($childRegionList as $childRegion)
		{
			if(empty($jsStr))
				$jsStr .= "{";
			else
				$jsStr .= ",";

			$childStr = get_delivery_region_js($childRegion['id']);
			$jsStr .= "\"r$childRegion[id]\":{\"i\":$childRegion[id],\"n\":\"$childRegion[name]\",\"c\":$childStr}";
		}

		if(!empty($jsStr))
			$jsStr .= "}";
		else
			$jsStr .= "\"\"";

		return $jsStr;

}

function update_sys_config()
{
	$filename = APP_ROOT_PATH."public/sys_config.php";
	if(!file_exists($filename))
	{
		//定义DB
		require APP_ROOT_PATH.'system/db/db.php';
		$dbcfg = require APP_ROOT_PATH."public/db_config.php";
		define('DB_PREFIX', $dbcfg['DB_PREFIX']);
		if(!file_exists(APP_ROOT_PATH.'public/runtime/app/db_caches/'))
			mkdir(APP_ROOT_PATH.'public/runtime/app/db_caches/',0777);
		$pconnect = false;
		$db = new mysql_db($dbcfg['DB_HOST'].":".$dbcfg['DB_PORT'], $dbcfg['DB_USER'],$dbcfg['DB_PWD'],$dbcfg['DB_NAME'],'utf8',$pconnect);
		//end 定义DB

		$sys_configs = $db->getAll("select * from ".DB_PREFIX."conf");
		$config_str = "<?php\n";
		$config_str .= "return array(\n";
		foreach($sys_configs as $k=>$v)
		{
			$config_str.="'".$v['name']."'=>'".addslashes($v['value'])."',\n";
		}
		$config_str.=");\n ?>";
		file_put_contents($filename,$config_str);
		$url = APP_ROOT."/";
		app_redirect($url);
	}
}


function get_dstatus($status,$id)
{
		if($status)
		{
			$delivery_notice = $GLOBALS['db']->getRow("select dn.notice_sn,dn.delivery_time,de.name,dn.memo from ".DB_PREFIX."delivery_notice as dn left join ".DB_PREFIX."express as de on dn.express_id = de.id where dn.order_item_id = ".$id);
			return "已发货，发货单号：".$delivery_notice['name'].$delivery_notice['notice_sn']."，发货时间：".to_date($delivery_notice['delivery_time'])." 发货备注：<span title='".$delivery_notice['memo']."'>".msubstr($delivery_notice['memo'])."</span>";
		}
		else
		return "未发货";
}


function gen_qrcode($str,$size = 5,$img=false)
{

	require_once APP_ROOT_PATH."system/phpqrcode/qrlib.php";

	if($img)
	{
		QRcode::png($str, false, 'Q', $size, 2);
		return;
	}

	$root_dir = APP_ROOT_PATH."public/images/qrcode/";
 	if (!is_dir($root_dir)) {
            @mkdir($root_dir);
            @chmod($root_dir, 0777);
     }

     $filename = md5($str."|".$size);
     $hash_dir = $root_dir. '/c' . substr(md5($filename), 0, 1)."/";
     if (!is_dir($hash_dir))
     {
        @mkdir($hash_dir);
        @chmod($hash_dir, 0777);
     }

	$filesave = $hash_dir.$filename.'.png';

	$fileurl =  "./public/images/qrcode/c". substr(md5($filename), 0, 1)."/".$filename.".png";
	if(!file_exists($filesave))
	{
		QRcode::png($str, $filesave, 'Q', $size, 2);
		if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
		syn_to_remote_image_server($fileurl);
	}
	return $fileurl;
}


function valid_tag($str)
{

	return preg_replace("/<(?!div|ol|ul|li|sup|sub|span|br|img|p|h1|h2|h3|h4|h5|h6|\/div|\/ol|\/ul|\/li|\/sup|\/sub|\/span|\/br|\/img|\/p|\/h1|\/h2|\/h3|\/h4|\/h5|\/h6|blockquote|\/blockquote|strike|\/strike|b|\/b|i|\/i|u|\/u)[^>]*>/i","",$str);
}

//显示语言
// lang($key,p1,p2......) 用于格式化 sprintf %s
function lang($key)
{
	$args = func_get_args();//取得所有传入参数的数组
	$key = strtoupper($key);
	if(isset($GLOBALS['lang'][$key]))
	{
		if(count($args)==1)
			return $GLOBALS['lang'][$key];
		else
		{
			$result = $key;
			$cmd = '$result'." = sprintf('".$GLOBALS['lang'][$key]."'";
			for ($i=1;$i<count($args);$i++)
			{
				$cmd .= ",'".$args[$i]."'";
			}
			$cmd.=");";
			eval($cmd);
			return $result;
		}
	}
	else
		return $key;
}


function filter_ctl_act_req($str){
	$search = array("../","\n","\r","\t","\r\n","'","<",">","\"","%");

	return str_replace($search,"",$str);
}
function isMobile() {
     $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
	 	$mobile_browser = '0';
	 if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
	 	$mobile_browser++;
	 if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
	 	 $mobile_browser++;
	 if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
	  	$mobile_browser++;
	 if(isset($_SERVER['HTTP_PROFILE']))
	  	$mobile_browser++;
	 $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
	 $mobile_agents = array(
	    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
	    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
	    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
	    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
	    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
	    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
	    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
	    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
	    'wapr','webc','winw','winw','xda','xda-'
	 );
	 if(in_array($mobile_ua, $mobile_agents))
	  	$mobile_browser++;
	 if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
	 	 $mobile_browser++;
	 // Pre-final check to reset everything if the user is on Windows
	 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
	  	$mobile_browser=0;
	 // But WP7 is also Windows, with a slightly different characteristic
	 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
	  	$mobile_browser++;
	 if($mobile_browser>0)
	  	return true;
	 else
	  	return false;
}


/**
 * 转义html编码去空格
 */
function strim($str)
{
	return quotes(htmlspecialchars(trim($str)));
}

//去空格，不允许非法的路径引入
function sltrim($str)
{
	$str =  addslashes(htmlspecialchars(trim($str)));
	$str = preg_replace("/[\.|\/]/", "", $str);
	return $str;
}

/**
 * 转义去空格
 */
function btrim($str)
{
	return quotes(trim($str));
}

function quotes($content)
{
	//if $content is an array
	if (is_array($content))
	{
		foreach ($content as $key=>$value)
		{
			//$content[$key] = mysql_real_escape_string($value);
// 			$content[$key] = preg_replace("/\.\//", "", addslashes($value));
// 			$content[$key] = preg_replace("/\.\.\//", "",addslashes($content));
			$content[$key] = addslashes($content);
		}
	} else
	{
		//if $content is not an array
// 		$content = preg_replace("/\.\//", "",addslashes($content));
// 		$content = preg_replace("/\.\.\//", "",addslashes($content));
		$content = addslashes($content);
		//mysql_real_escape_string($content);
	}
	return $content;
}




/**
 * 变更平台财务报表
 * @param unknown_type $money
 * @param unknown_type $type 0.收入 1.订单支付收入 2.会员充值收入 3.支出 4.会员提现支出
 * @param unknown_type $info 日志内容
  `income_money` '收入',
  `income_order` '收入中用于订单支付',
  `income_incharge` '收入用于会员充值(含超额充值)',
  `out_money` '支出',
  `out_uwd_money` '会员提现支出'
 */
function modify_statements($money,$type,$info)
{

		$field_array = array(
				'income_money',
				'income_order',
				'income_incharge',
				'out_money',
				'out_uwd_money',
				'out_swd_money'
				);

		$stat_time = to_date(NOW_TIME,"Y-m-d");
		$stat_month = to_date(NOW_TIME,"Y-m");
		$state_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."statements where stat_time = '".$stat_time."'");
		if($state_data)
		{
			$state_data[$field_array[$type]] = $state_data[$field_array[$type]]+floatval($money);
			$GLOBALS['db']->autoExecute(DB_PREFIX."statements",$state_data, $mode = 'UPDATE', "id=".$state_data['id'], $querymode = 'SILENT');
			$rs = $GLOBALS['db']->affected_rows();
		}
		else
		{
			$state_data[$field_array[$type]] = floatval($money);
			$state_data["stat_time"] = $stat_time;
			$state_data["stat_month"] = $stat_month;
			$GLOBALS['db']->autoExecute(DB_PREFIX."statements",$state_data, $mode = 'INSERT', "", $querymode = 'SILENT');
			$rs = $GLOBALS['db']->insert_id();
		}

		if($rs)
		{
			$log_data = array();
			$log_data['log_info'] = $info;
			$log_data['create_time'] = NOW_TIME;
			$log_data['money'] = floatval($money);
			$log_data['type'] = $type;

			$GLOBALS['db']->autoExecute(DB_PREFIX."statements_log",$log_data);
		}


}



/**
 * 发送消息函数
 * @param unknown_type $user_id
 * @param unknown_type $content
 * @param unknown_type $type
 * @param unknown_type $id
 */
function send_msg($user_id,$content,$type,$id)
{
	$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where is_delete = 0 and is_effect = 1 and id = ".$user_id);

	if($user_info['is_robot']==0) //by hc4.18 机器人才发消息
	{
		$interface_file = APP_ROOT_PATH."system/msg/". $type."_msg.php";
		$class_name = $type."_msg";
		if(file_exists($interface_file))
		{
			require_once $interface_file;
			if(class_exists($class_name))
			{
				$obj = new $class_name;
				$obj->send_msg($user_id,$content,$id);
				require_once APP_ROOT_PATH."system/model/user.php";
				load_user($user_id,true);
			}
		}
	}
}

/**
 * 加载消息内容
 * @param unknown_type $type
 * @param unknown_type $id
 * @return NULL
 */
function load_msg($type,$msg)
{
	$result = null;
	$interface_file = APP_ROOT_PATH."system/msg/". $type."_msg.php";
	$class_name = $type."_msg";
	if(file_exists($interface_file))
	{
		require_once $interface_file;
		if(class_exists($class_name))
		{
			$obj = new $class_name;
			$result = $obj->load_msg($msg);
		}
	}
	return $result;
}

function get_msg_box_type($type)
{
	$result = null;
	$interface_file = APP_ROOT_PATH."system/msg/". $type."_msg.php";
	$class_name = $type."_msg";
	if(file_exists($interface_file))
	{
		require_once $interface_file;
		if(class_exists($class_name))
		{
			$obj = new $class_name;
			$result = $obj->load_type();
		}
	}
	return $result;
}

function isios() {
	//判断手机发送的客户端标志,兼容性有待提高
	if (isset ($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array (
				'iphone',
				'ipod',
				'mac',
		);
		// 从HTTP_USER_AGENT中查找手机浏览器的关键字
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}
}


function ofc_max($max_value)
{
	$max_value = floor($max_value);
	$begin_val = substr($max_value,0,1);
	$max_length = strlen($max_value)-1;
	$begin_val = intval($begin_val)+1;

	$multi = "1";
	for($i=0;$i<$max_length;$i++)
	{
	$multi.="0";
	}
	$multi = intval($multi);
	$max_value = $begin_val*$multi;

	if($max_value<=10)$max_value = 10;
	if($max_value>10&&$max_value<=200)$max_value = 200;

	return $max_value;
}

/**
 * 散列算法
 * @param unknown_type $value  计算散列的基础值
 * @param unknown_type $count  散列的总基数
 * @return number
 */

function hash_table($value,$count)
{
	$pid = intval(round(hexdec(md5($value))/pow(10,32))%$count);
	return $pid;
}

/**
 * 获取快递查询api的内容
 * @param unknown_type $url
 */
function get_delivery_api_content($url)
{
	$content = file_get_contents($url);
	$json_data = json_decode($content,true);
	$html = "查询失败";
	$status = false;
	if($json_data['status']==1)
	{
		$status = true;
		$html = "";
		foreach($json_data['data'] as $row)
		{
			$html.="<div style='margin-bottom:5px;'><span style='color:#f30;'>".$row['time']."</span> ".$row['context']."</div>";
		}
	}

	return array("status"=>$status,"html"=>$html);
}


//获取相应规格的图片地址
//gen=0:保持比例缩放，不剪裁,如高为0，则保证宽度按比例缩放  gen=1：保证长宽，剪裁
function get_spec_image($img_path,$width=0,$height=0,$gen=0,$is_preview=true)
{

	//关于ALIOSS的生成
	if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']=="ALI_OSS")
	{
		$pathinfo = pathinfo($img_path);
		$file = $pathinfo['basename'];
		$dir = $pathinfo['dirname'];
		$dir = str_replace("./public/", "/public/", $dir);

		if($width==0)
		{
			$file_name = $GLOBALS['distribution_cfg']['OSS_DOMAIN'].$dir."/".$file;
		}
		else if($height==0)
		{
			$file_name = $GLOBALS['distribution_cfg']['OSS_DOMAIN'].$dir."/".$file."@".$width."w_1l_1x.jpg";
		}
		else if($gen==0)
			$file_name = $GLOBALS['distribution_cfg']['OSS_DOMAIN'].$dir."/".$file."@".$width."w_".$height."h_0c_1e_1x.jpg"; //以短边缩放 1e 不剪裁
		else
			$file_name = $GLOBALS['distribution_cfg']['OSS_DOMAIN'].$dir."/".$file."@".$width."w_".$height."h_1c_1e_1x.jpg"; //以短边缩放 1e 剪裁
		return $file_name;
	}

	if($width==0||substr($img_path, 0,2)!="./")
		$new_path = $img_path;
	else
	{
		//$img_name = substr($img_path,0,-4);
		//$img_ext = substr($img_path,-3);
		$fileinfo = pathinfo($img_path);
		$img_ext = $fileinfo['extension'];
		$len = strlen($img_ext) + 1;
		$img_name =substr($img_path,0,-$len);

		if($is_preview)
			$new_path = $img_name."_".$width."x".$height.".jpg";
		else
			$new_path = $img_name."o_".$width."x".$height.".jpg";
		if(!file_exists(APP_ROOT_PATH.$new_path))
		{
			require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
			$imagec = new es_imagecls();
			$thumb = $imagec->thumb(APP_ROOT_PATH.$img_path,$width,$height,$gen,true,"",$is_preview);

			if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']=="ES_FILE")
			{
				$paths = pathinfo($new_path);
				$path = str_replace("./","",$paths['dirname']);
				$filename = $paths['basename'];
				$pathwithoupublic = str_replace("public/","",$path);

				$file_array['path'] = $pathwithoupublic;
				$file_array['file'] = get_domain().APP_ROOT."/".$path."/".$filename;
				$file_array['name'] = $filename;
				$GLOBALS['curl_param']['images'][] = $file_array;
			}

		}
	}
	//return APP_ROOT."/test.php?path=".$new_path."&rand=".rand(1000000,9999999);
	return $new_path;
}


function get_spec_gif_anmation($url,$width,$height)
{
	require_once APP_ROOT_PATH."system/utils/gif_encoder.php";
	require_once APP_ROOT_PATH."system/utils/gif_reader.php";
	require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
	$gif = new GIFReader();
	$gif->load($url);
	$imagec = new es_imagecls();
	foreach($gif->IMGS['frames'] as $k=>$img)
	{
		$im = imagecreatefromstring($gif->getgif($k));
		$im = $imagec->make_thumb($im,$img['FrameWidth'],$img['FrameHeight'],"gif",$width,$height,$gen=1);
		ob_start();
		imagegif($im);
		$content = ob_get_contents();
		ob_end_clean();
		$frames [ ] = $content;
		$framed [ ] = $img['frameDelay'];
	}

	$gif_maker = new GIFEncoder (
			$frames,
			$framed,
			0,
			2,
			0, 0, 0,
			"bin"   //bin为二进制   url为地址
	);
	return $gif_maker->GetAnimation ( );
}
function isWeixin(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$is_weixin = strpos($agent, 'micromessenger') ? true : false ;
	if($is_weixin){
		return true;
	}else{
		return false;
	}
}
function isQQ(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$is_qq = strpos($agent, 'qq/') ? true : false ;
	if($is_qq){
		return true;
	}else{
		return false;
	}
}


function getMConfig(){


	$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/mapi/");
	$m_config = $GLOBALS['cache']->get("m_config_sj");

	if($m_config===false)
	{
		$m_config = array();
		$sql = "select code,val from ".DB_PREFIX."m_config";
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $item){
			$m_config[$item['code']] = $item['val'];
		}


		$m_config['program_title'] = app_conf("SHOP_TITLE");
		$m_config['wx_appid'] = app_conf("WEIXIN_APPID");
		$m_config['wx_secrit'] = app_conf("WEIXIN_APPSCECRET");
		$m_config['wx_mappid'] = $GLOBALS['db']->getOne("select value from ".DB_PREFIX."weixin_account_conf where name = 'mappid'");
		$m_config['wx_mappsecret'] = $GLOBALS['db']->getOne("select value from ".DB_PREFIX."weixin_account_conf where name = 'mappsecret'");

		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/mapi/");
		$GLOBALS['cache']->set("m_config_sj",$m_config);

	}
	return $m_config;
}



/**
 * 按宽度格式化html内容中的图片
 * @param unknown_type $content
 * @param unknown_type $width
 * @param unknown_type $height
 */
function format_html_content_image($content,$width,$height=0)
{
	global $is_app;
    $res = preg_match_all("/<img.*?src=[\"|\']([^\"|\']*)[\"|\'][^>]*>/i", $content, $matches);
    if($res)
    {
        foreach($matches[0] as $k=>$match)
        {
            $old_path = $matches[1][$k];
            if(preg_match("/\.\/public\//i", $old_path))
            {
            	$origin_path = $matches[1][$k];
                $new_path = get_spec_image($matches[1][$k],$width,$height,0);
                if($is_app)
                	$content = str_replace($match, "<a href='javascript:open_url(\"".$origin_path."\")'><img src='".$new_path."' lazy='true' /></a>", $content);
                else
               		$content = str_replace($match, "<a href='".$origin_path."'><img src='".$new_path."' lazy='true' /></a>", $content);
            }
        }
    }

    return $content;
}
/**
 * 带域名连接替换成public
 * @param unknown $str
 * @return mixed
 */
function replace_domain_to_public($str){
    //对图片路径的修复
    if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
    {
        $domain = $GLOBALS['distribution_cfg']['OSS_DOMAIN'];
    }
    else
    {
        $domain = SITE_DOMAIN.APP_ROOT;
    }

    return str_replace($domain."/public/","./public/",$str);
}

function check_remote_file_exists($url)
{
	$curl = curl_init($url);
	// 不取回数据
	curl_setopt($curl, CURLOPT_NOBODY, true);
	// 发送请求
	$result = curl_exec($curl);
	$found = false;
	// 如果请求没有发送失败
	if ($result !== false) {
		// 再检查http响应码是否为200
		$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($statusCode == 200) {
			$found = true;
		}
	}
	curl_close($curl);

	return $found;
}

/**
 * 通过curl下载文件到指定位置
 * @param unknown_type $file 远程文件
 * @param unknown_type $dest 存储位置
 */
function curl_download($file,$dest)
{
	$ch = curl_init($file);
	$fp = fopen($dest, "wb");
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$res=curl_exec($ch);
	curl_close($ch);
	fclose($fp);
	return $res;
}

function gen_scan_qrcode($url,$size=3)
{
	if(substr($url, 0,1)=="/")
	{
		$url = SITE_DOMAIN.$url;
	}
	return gen_qrcode($url,$size);
}



/**
 * 合并adm_cfg中的配置文件
 * @param unknown_type $config  //原配置
 * @param unknown_type $config_file  //新配置文件
 */
function array_merge_admnav($config,$config_file)
{
	if(!file_exists($config_file))
	{
		return $config;
	}
	$new_config = require $config_file;
	foreach($new_config as $k=>$v)
	{
		if($config[$k])
		{
			foreach($v['groups'] as $kk=>$vv)
			{
				if($config[$k]['groups'][$kk])
				{
					foreach($vv['nodes'] as $kkk=>$vvv)
					{
						$config[$k]['groups'][$kk]['nodes'][] = $vvv;
					}
				}
				else
				{
					$config[$k]['groups'][$kk] = $vv;
				}
			}
		}
		else
		{
			$config[$k] = $v;
		}
	}
	return $config;
}


/**
 * 合并adm_node中的配置文件
 * @param unknown_type $config  //原配置
 * @param unknown_type $config_file  //新配置文件
 */
function array_merge_admnode($config,$config_file)
{
	if(!file_exists($config_file))
	{
		return $config;
	}
	$new_config = require $config_file;
	foreach($new_config as $k=>$v)
	{
		if($config[$k])
		{
			foreach($v['node'] as $kk=>$vv)
			{
				$config[$k]['node'][$kk] = $vv;
			}
		}
		else
		{
			$config[$k] = $v;
		}
	}
	return $config;
}

/**
 * 合并biz_nav中的配置文件
 * @param unknown_type $config  //原配置
 * @param unknown_type $config_file  //新配置文件
 */
function array_merge_biznav($config,$config_file)
{
	if(!file_exists($config_file))
	{
		return $config;
	}
	$new_config = require $config_file;
	foreach($new_config as $k=>$v)
	{
		if($config[$k])
		{
			foreach($v['node'] as $kk=>$vv)
			{
				$config[$k]['node'][$kk] = $vv;
			}
		}
		else
		{
			$config[$k] = $v;
		}
	}
	return $config;
}

/**
 * 合并biz_node中的配置文件
 * @param unknown_type $config  //原配置
 * @param unknown_type $config_file  //新配置文件
 */
function array_merge_biznode($config,$config_file)
{
	if(!file_exists($config_file))
	{
		return $config;
	}
	$new_config = require $config_file;
	foreach($new_config as $k=>$v)
	{
		if($config[$k])
		{
			foreach($v['node'] as $kk=>$vv)
			{
				$config[$k]['node'][$kk] = $vv;
			}
		}
		else
		{
			$config[$k] = $v;
		}
	}
	return $config;
}

/**
 * 合并mobile_cfg中的配置文件
 * @param unknown_type $config  //原配置
 * @param unknown_type $config_file  //新配置文件
 */
function array_merge_mobile_cfg($config,$config_file)
{
	if(!file_exists($config_file))
	{
		return $config;
	}
	$new_config = require $config_file;
	foreach($new_config as $k=>$v)
	{
		if($config[$k])
		{
			foreach($v['nav'] as $kk=>$vv)
			{
				$config[$k]['nav'][$kk] = $vv;
			}
		}
		else
		{
			$config[$k] = $v;
		}
	}
	return $config;
}

/**
 * 合并web_cfg_web_nav中的配置文件
 * @param unknown_type $config  //原配置
 * @param unknown_type $config_file  //新配置文件
 */
function array_merge_web_nav($config,$config_file)
{
	if(!file_exists($config_file))
	{
		return $config;
	}
	$new_config = require $config_file;
	foreach($new_config as $k=>$v)
	{
		if($config[$k])
		{
			foreach($v['acts'] as $kk=>$vv)
			{
				$config[$k]['acts'][$kk] = $vv;
			}
		}
		else
		{
			$config[$k] = $v;
		}
	}
	return $config;
}


/**
 * 合并uc_node中的配置文件
 * @param unknown_type $config  //原配置
 * @param unknown_type $config_file  //新配置文件
 */
function array_merge_ucnode($config,$config_file)
{
	if(!file_exists($config_file))
	{
		return $config;
	}
	$new_config = require $config_file;
	foreach($new_config as $k=>$v)
	{
		if($config[$k])
		{
			foreach($v['node'] as $kk=>$vv)
			{
				$config[$k]['node'][$kk] = $vv;
			}
		}
		else
		{
			$config[$k] = $v;
		}
	}
	return $config;
}



//以下是微信公众平台的消息发送函数

/**
 * 获取微信消息模板的内容
 * @param unknown_type $template_id 模板ID，详见wx_template_cfg.php
 * @param unknown_type $tmpl  对应的DB中的模板数据集
 * @param unknown_type $param 对应ID传入的参数
 *
 * 返回
 * array(
 * 	status=>必返回   info=>status为false时返回  url=>可为空，表示消息的跳转页  data=>必返回，为指定模板的实际内容
 * )
 *
 */
function get_wx_msg_content($template_id,$tmpl,$user_id,$wx_account,$param=array())
{
	$data=unserialize($tmpl['msg']);

	switch ($template_id)
	{
		case "OPENTM201490080": //订单支付成功模板
			if(empty($param))
			{
// 				{{first.DATA}}
// 				订单编号：{{keyword1.DATA}}
// 				商品详情：{{keyword2.DATA}}
// 				订单金额：{{keyword3.DATA}}
// 				{{remark.DATA}}
				$data['keyword1']=array('value'=>'00000000','color'=>'#000000');
				$data['keyword2']=array('value'=>'这是一款测试的商品','color'=>'#000000');
				$data['keyword3']=array('value'=>'100元','color'=>'#000000');
				$url = SITE_DOMAIN.wap_url("index");
				return array("status"=>true,"url"=>$url,"data"=>$data);
			}
			else
			{
				$order_id = intval($param['order_id']);
				$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
				if(empty($order_info))
				{
					return array("status"=>false,"info"=>"订单不存在");
				}
				$deal_order_items = $GLOBALS['db']->getAll("select name from ".DB_PREFIX."deal_order_item where order_id = ".$order_id." and user_id = ".$user_id);
				if(empty($deal_order_items))
				{
					return array("status"=>false,"info"=>"订单不存在");
				}
				$order_price = round($order_info['total_price'],2)."元";
				if(count($deal_order_items)>1)
				{
					$item_name = $deal_order_items[0]['name']."等";
				}
				else
				{
					$item_name = $deal_order_items[0]['name'];
				}

				$data['keyword1']=array('value'=>$order_info['order_sn'],'color'=>'#000000');
				$data['keyword2']=array('value'=>$item_name,'color'=>'#000000');
				$data['keyword3']=array('value'=>$order_price,'color'=>'#000000');
				$url = SITE_DOMAIN.wap_url("index","uc_duobao_record");
				return array("status"=>true,"url"=>$url,"data"=>$data);
			}
			break;
		case "OPENTM200565259": //订单发货提醒
			if(empty($param))
			{
// 				{{first.DATA}}
// 				订单编号：{{keyword1.DATA}}
// 				物流公司：{{keyword2.DATA}}
// 				物流单号：{{keyword3.DATA}}
// 				{{remark.DATA}}
				$data['keyword1']=array('value'=>'00000000','color'=>'#000000');
				$data['keyword2']=array('value'=>'顺风快递','color'=>'#000000');
				$data['keyword3']=array('value'=>'00000000','color'=>'#000000');
				$url = SITE_DOMAIN.wap_url("index");
				return array("status"=>true,"url"=>$url,"data"=>$data);
			}
			else
			{
				$order_id = intval($param['order_id']);
				$order_sn = strim($param['order_sn']);
				$company_name = strim($param['company_name']);
				$delivery_sn = strim($param['delivery_sn']);
				$order_item_id = intval($param['order_item_id']);

				$data['keyword1']=array('value'=>$order_sn,'color'=>'#000000');
				$data['keyword2']=array('value'=>$company_name,'color'=>'#000000');
				$data['keyword3']=array('value'=>$delivery_sn,'color'=>'#000000');
				$url = SITE_DOMAIN.wap_url("index","uc_order#check_delivery",array("item_id"=>$order_item_id));
				return array("status"=>true,"url"=>$url,"data"=>$data);
			}
			break;
		case "OPENTM202314085": //订单确认收货通知
			if(empty($param))
			{
// 				{{first.DATA}}
// 				订单号：{{keyword1.DATA}}
// 				商品名称：{{keyword2.DATA}}
// 				下单时间：{{keyword3.DATA}}
// 				发货时间：{{keyword4.DATA}}
// 				确认收货时间：{{keyword5.DATA}}
// 				{{remark.DATA}}
				$data['keyword1']=array('value'=>'00000000','color'=>'#000000');
				$data['keyword2']=array('value'=>'这是一款测试商品','color'=>'#000000');
				$data['keyword3']=array('value'=>'2015-07-01 12:00:00','color'=>'#000000');
				$data['keyword4']=array('value'=>'2015-07-01 14:00:00','color'=>'#000000');
				$data['keyword5']=array('value'=>'2015-07-05 14:00:00','color'=>'#000000');
				$url = SITE_DOMAIN.wap_url("index");
				return array("status"=>true,"url"=>$url,"data"=>$data);
			}
			else
			{
				$order_item_id = intval($param['order_item_id']);
				$order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$order_item_id);
				$order_id = $order_item['order_id'];
				if(empty($order_item))
				{
					return array("status"=>false,"info"=>"订单不存在");
				}
				$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
				if(empty($order_info))
				{
					return array("status"=>false,"info"=>"订单不存在");
				}
				$delivery_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_notice where order_item_id = ".$order_item_id);
				if(empty($delivery_notice))
				{
					return array("status"=>false,"info"=>"订单不存在");
				}


				$total_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."delivery_notice where notice_sn = '".$delivery_notice['notice_sn']."' and order_id=".$order_id." and is_arrival = 1");
				if($total_count>1)
				{
					$deal_name = $order_item['name']."等";
				}
				else
				{
					$deal_name = $order_item['name'];
				}
				$data['keyword1']=array('value'=>$order_info['order_sn'],'color'=>'#000000');
				$data['keyword2']=array('value'=>$deal_name,'color'=>'#000000');
				$data['keyword3']=array('value'=>to_date($order_info['create_time']),'color'=>'#000000');
				$data['keyword4']=array('value'=>to_date($delivery_notice['delivery_time']),'color'=>'#000000');
				$data['keyword5']=array('value'=>to_date(NOW_TIME),'color'=>'#000000');
				$url = SITE_DOMAIN.wap_url("index","uc_winlog",array("data_id"=>$order_item_id));
				return array("status"=>true,"url"=>$url,"data"=>$data);

			}
			break;
		case "OPENTM204623681": //活动结果通知
			if(empty($param))
			{
// 				{{first.DATA}}
// 				活动名称：{{keyword1.DATA}}
// 				开奖时间：{{keyword2.DATA}}
// 				{{remark.DATA}}
				$data['keyword1']=array('value'=>'xxxx夺宝活动','color'=>'#000000');
				$data['keyword2']=array('value'=>'xxxx年xx月xx日  xx:xx:xx','color'=>'#000000');
				$data['remark'] = array('value'=>"恭喜xxx中奖",'color'=>'#000000');

				$url = "";
				return array("status"=>true,"url"=>$url,"data"=>$data);
			}
			else
			{
				$duobao_item_id = intval($param['duobao_item_id']);
				$duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$duobao_item_id);
				if($duobao_item['has_lottery']==0)
				{
					return array("status"=>false,"info"=>"活动未开奖");
				}

				$user = $GLOBALS['db']->getRow("select user_name from ".DB_PREFIX."user where id = '".$duobao_item['luck_user_id']."'");
				$data['keyword1']=array('value'=>$duobao_item['name'],'color'=>'#000000');
				$data['keyword2']=array('value'=>to_date($duobao_item['lottery_time']),'color'=>'#000000');

				$data['remark'] = array('value'=>"恭喜".$user['user_name']."中奖，幸运号".$duobao_item['lottery_sn'],'color'=>'#000000');
				$url = SITE_DOMAIN.wap_url("index","duobao",array("data_id"=>$duobao_item_id));
				return array("status"=>true,"url"=>$url,"data"=>$data);

			}
			break;
		default:
			return array("status"=>false,"info"=>"模板编号不存在");
			break;
	}

}


/**
 * 发送微信消费
 * @param unknown_type $template_id_short 模板类型 的ID，即模板编号
 * @param unknown_type $user_id  会员ID
 * @param unknown_type $wx_account 公众平台授权帐号
 * @param unknown_type $param 不同模板类型传入的参数，在get_wx_msg_content函数中细分，不传为演示
 * @return array(status,info);
 */
function send_wx_msg($template_id_short,$user_id,$wx_account,$param=array())
{

	$openid =  $GLOBALS['db']->getOne("select wx_openid from ".DB_PREFIX."user where id = '".$user_id."'");

	if(!$openid)
	{
		return array(
				"status" => false,
				"info" => "微信用户未授权"
		);
	}

	if(WEIXIN_TYPE=="platform")
	{
		$template_list = require_once APP_ROOT_PATH."system/wechat/wx_template_cfg.php";
		$v = $template_list[$template_id_short];
		if($v)
		{
			$data = array('first'=>$v['name'],'remark'=>array('value'=>$v['remark'],'color'=>'#173177'));
			$tmpl['msg'] = serialize($data);
		}
		else
		{
			return array(
					"status" => false,
					"info" => "模板不存在"
			);
		}

	}
	else
	{
		$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_tmpl where account_id = '".intval($wx_account['user_id'])."' and template_id_short = '".$template_id_short."'");
		if(!$tmpl)
		{
			return array(
					"status" => false,
					"info" => "未安装该消息模板"
			);
		}
	}

	$result = get_wx_msg_content($template_id_short,$tmpl,$user_id,$wx_account,$param);
	if(!$result['status'])
	{
		return $result;
	}

	if($param)
	{
		require_once APP_ROOT_PATH."system/model/user.php";
		$user_info = load_user($user_id);


		$schedule_data = array();
		$msg_result = array();
		$schedule_data['dest'] = $openid;

		$msg_result['url'] = $result['url'];
		$msg_result['data'] = $result['data'];
		$msg_result['template_id'] = $tmpl['template_id'];
		$msg_result['template_id_short'] = $template_id_short;
		$msg_result['account_id'] = $wx_account['id'];
		$schedule_data['content'] = serialize($msg_result);


		$result = send_schedule_plan("weixin", "微信消息通知", $schedule_data, NOW_TIME,$openid);
	}
	else
	{

		$schedule_data = array();
		$msg_result = array();
		$schedule_data['dest'] = $openid;

		$msg_result['url'] = $result['url'];
		$msg_result['data'] = $result['data'];
		$msg_result['template_id'] = $tmpl['template_id'];
		$msg_result['template_id_short'] = $template_id_short;
		$msg_result['account_id'] = $wx_account['id'];
		$schedule_data['content'] = serialize($msg_result);


		$result = send_schedule_plan("weixin", "微信消息通知", $schedule_data, 0,$openid);

	}
	return $result;
}


//end微信公众平台消息发送
/**
 * 验证关键词的是否重复
 * @param unknown_type $keywords
 * @param unknown_type $reply_id
 * @param unknown_type $match_type
 */
function word_check($keywords,$reply_id = 0,$match_type = 0,$supplier_id = 0)
{
	if($match_type == 0){
		$keywords = preg_split("/[ ,]/i",$keywords);
		$exists_keywords = array();
		foreach($keywords as $tag){
			$tag = trim($tag);
			if($tag != ''){
				$unicode_tag =  str_to_unicode_string(trim($tag));

				$condition =" account_id=".$supplier_id."  and id <> ".$reply_id." ";
				if($unicode_tag){
					$condition .= " and (match(keywords_match) AGAINST ('".$unicode_tag."' IN BOOLEAN MODE) or keywords = '".$tag."')";
					//$where['keywords_match'] = array('match',$unicode_tag);
				}
				$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."weixin_reply where ".$condition);
				if($count > 0){
					$exists_keywords[] = trim($tag);
					break;
				}
			}
		}
	}else{
		$keywords = trim($keywords);
		if($keywords != ''){


			$unicode_tag =  str_to_unicode_string(trim($keywords));

			$condition =" account_id=".$supplier_id."  and id <> ".$reply_id." ";
			if($unicode_tag){
				$condition .= " and (match(keywords_match) AGAINST ('".$unicode_tag."' IN BOOLEAN MODE) or keywords = '".$keywords."')";
			}
			$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."weixin_reply where ".$condition);


			if($count > 0){
				$exists_keywords[] = $keywords;
			}
		}
	}
	return $exists_keywords;
}


/**
 * 同步公众号回复的索引
 * @param unknown_type $reply_id
 */
function syncMatch($reply_id){

	$reply_data['keywords_match'] = "";
	$reply_data['keywords_match_row'] = "";
	$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_reply", $reply_data, $mode = 'UPDATE', "id=".$reply_id, $querymode = 'SILENT');

	$reply_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_reply where id = ".$reply_id);

	$keywords = $reply_data['keywords'];
	$keywords = preg_split("/[ ,]/i",$keywords);
	foreach($keywords as $tag)
	{
		insert_match_item(trim($tag),"weixin_reply",$reply_id,"keywords_match");
	}

}


function toFormatTree($list,$title = 'title')
{
	$list = toTree($list);
	$formatTree = array();
	_toFormatTree($list,0,$title,$formatTree);
	return $formatTree;
}

function toTree($list=null, $pk='id',$pid = 'pid',$child = '_child')
{
	if(null === $list) {
		// 默认直接取查询返回的结果集合
		$list   =   &$this->dataList;
	}
	// 创建Tree
	$tree = array();
	if(is_array($list)) {
		// 创建基于主键的数组引用
		$refer = array();

		foreach ($list as $key => $data) {
			$_key = is_object($data)?$data->$pk:$data[$pk];
			$refer[$_key] =& $list[$key];
		}
		foreach ($list as $key => $data) {
			// 判断是否存在parent
			$parentId = is_object($data)?$data->$pid:$data[$pid];
			$is_exist_pid = false;
			foreach($refer as $k=>$v)
			{
				if($parentId==$k)
				{
					$is_exist_pid = true;
					break;
				}
			}
			if ($is_exist_pid) {
				if (isset($refer[$parentId])) {
					$parent =& $refer[$parentId];
					$parent[$child][] =& $list[$key];
				}
			} else {
				$tree[] =& $list[$key];
			}
		}
	}
	return $tree;
}

function _toFormatTree($list,$level=0,$title = 'title',&$formatTree)
{
	foreach($list as $key=>$val)
	{
		$tmp_str=str_repeat("&nbsp;&nbsp;",$level*2);
		$tmp_str.="|--";

		$val['level'] = $level;
		$val['title_show'] = $tmp_str.$val[$title];
		if(!array_key_exists('_child',$val))
		{
			array_push($formatTree,$val);
		}
		else
		{
			$tmp_ary = $val['_child'];
			unset($val['_child']);
			array_push($formatTree,$val);
			_toFormatTree($tmp_ary,$level+1,$title,$formatTree); //进行下一层递归
		}
	}
	return;
}

function csrf_gate()
{
	$http_referer = $_SERVER['HTTP_REFERER'];
	if($http_referer)
	{
		if(strpos($http_referer, SITE_DOMAIN)!==0)
		{
			header("Content-Type:text/html; charset=utf-8");
			die("非法的操作访问");
		}
	}
	else
	{
		header("Content-Type:text/html; charset=utf-8");
		die("非法的操作访问");
	}
}




/**
 * 同一IP的短信验证码发送量，用于判断是否显示验证码
 */
function load_sms_ipcount()
{
	$sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
	$GLOBALS['db']->query($sql);

	if((APP_INDEX=="app"&&APP_SMS_VERIFY==1)||APP_INDEX!="app")
	{
		$ipcount = $GLOBALS['db']->getOne("select sum(send_count) from ".DB_PREFIX."sms_mobile_verify where ip = '".CLIENT_IP."'");
		$total_count = $GLOBALS['db']->getOne("select sum(send_count) from ".DB_PREFIX."sms_mobile_verify");
		if($total_count>60)
		{
			$ipcount = $total_count;
		}
		return 2;
		return intval($ipcount);
	}
	else
	{
		return 0;
	}
}


function make_app_js()
{
	$content = @file_get_contents(APP_ROOT_PATH."system/app.js");
	$content = str_replace("__HOST__", get_host(), $content);
	$content = str_replace("__APP_ROOT__", APP_ROOT, $content);

	require_once APP_ROOT_PATH.'/system/libs/crypt_aes.php';
	$aes = new CryptAES();
	$aes->set_key(FANWE_AES_KEY);
	$aes->require_pkcs5();


	$json = json_encode(array("type"=>"fair","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_FAIR__", $encText, $content);

	$json = json_encode(array("type"=>"mass","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_MASS__", $encText, $content);

	$json = json_encode(array("type"=>"robot","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_ROBOT__", $encText, $content);

	$json = json_encode(array("type"=>"robot_cfg","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_ROBOT_CFG__", $encText, $content);

	$json = json_encode(array("type"=>"lottery","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_LOTTERY__", $encText, $content);

	$json = json_encode(array("type"=>"logmoving","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_LOGMOVING__", $encText, $content);

	$json = json_encode(array("type"=>"mail","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_MAIL__", $encText, $content);

	$json = json_encode(array("type"=>"sms","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_SMS__", $encText, $content);

	$json = json_encode(array("type"=>"weixin","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_WEIXIN__", $encText, $content);

	$json = json_encode(array("type"=>"android","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_ANDROID__", $encText, $content);

	$json = json_encode(array("type"=>"ios","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_IOS__", $encText, $content);

	$json = json_encode(array("type"=>"gc","key"=>FANWE_APP_ID));
	$encText = $aes->encrypt($json);
	$content = str_replace("__TYPE_GC__", $encText, $content);


	$gc_schedule_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."schedule_list where type = 'gc' and exec_status = 0");
	if(intval($gc_schedule_count)==0)
	{
		send_schedule_plan("gc", "定时任务", array(), NOW_TIME);
	}

	@file_put_contents(APP_ROOT_PATH."public/app.js", $content);
}


//删除会员头像，用于后台
function delete_avatar($id)
{
	$user_id = $id;
	$uid = sprintf("%09d", $id);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$path = $dir1.'/'.$dir2.'/'.$dir3;

	$id = str_pad($id, 2, "0", STR_PAD_LEFT);
	$id = substr($id,-2);
	$avatar_file_relative = APP_ROOT_PATH."public/avatar/".$path."/".$id."avatar.jpg";
	@unlink($avatar_file_relative);

	$avatar_file_relative = APP_ROOT_PATH."public/avatar/".$path."/".$id."avatar_48x48.jpg";
	@unlink($avatar_file_relative);
	
	$avatar_file_relative = APP_ROOT_PATH."public/avatar/".$path."/".$id."avatar_72x72.jpg";
	@unlink($avatar_file_relative);

	$avatar_file_relative = APP_ROOT_PATH."public/avatar/".$path."/".$id."avatar_120x120.jpg";
	@unlink($avatar_file_relative);

	$avatar_file_relative = APP_ROOT_PATH."public/avatar/".$path."/".$id."avatar_200x200.jpg";
	@unlink($avatar_file_relative);
	
	$avatar_file_relative = APP_ROOT_PATH."public/avatar/".$path."/".$id."avatar_300x300.jpg";
	@unlink($avatar_file_relative);

}



//40,120,200
function get_user_avatar($id,$type)
{
	if($type=="small")
		$aw = 48;
	elseif($type=="middle")
		$aw = 120;
	else
		$aw = 200;

	$user_id = $id;
	$uid = sprintf("%09d", $id);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$path = $dir1.'/'.$dir2.'/'.$dir3;

	$id = str_pad($id, 2, "0", STR_PAD_LEFT);
	$id = substr($id,-2);
	$avatar_file_relative = "./public/avatar/".$path."/".$id."avatar.jpg";
	$avatar_file = format_image_path($avatar_file_relative);
	$avatar_check_file = APP_ROOT_PATH."public/avatar/".$path."/".$id."avatar.jpg";
	if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
	{
		if(!check_remote_file_exists($avatar_file))
		{
			$user_row = $GLOBALS['db']->getRow("select avatar,user_logo from ".DB_PREFIX."user where id = '".$user_id."'");
			$user_logo = $user_row['user_logo'];
			$user_avatar = $user_row['avatar'];
			if($user_logo!=""){
			    $user_logo = APP_ROOT_PATH.str_replace("./public/", "public/", $user_logo);
			}
				
			elseif($user_avatar!=""&&$user_avatar!="./public/avatar/noavatar.gif"){
			    $user_logo = APP_ROOT_PATH.str_replace("./public/", "public/", $user_logo);
			}
				
			else{
			    $user_logo = SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar.gif";
			}
			
			move_avatar_file($user_logo, $user_id);
			//$avatar_file =  SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar_".$type.".gif";
		}
	}
	else
	{
	   if(!file_exists($avatar_check_file)||filesize($avatar_check_file)==0)
		{
			$user_row = $GLOBALS['db']->getRow("select avatar,user_logo from ".DB_PREFIX."user where id = '".$user_id."'");
			$user_logo = $user_row['user_logo'];
			$user_avatar = $user_row['avatar'];
			if($user_logo!=""&& substr($user_logo, 0,7)=="http://")
			{}
			elseif($user_logo!=""){
			    $user_logo = APP_ROOT_PATH.str_replace("./public/", "public/", $user_logo);
			}
				
			else{
			    $user_logo = SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar.gif";
			}
			
			move_avatar_file($user_logo, $user_id);
			//$avatar_file =  SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar_".$type.".gif";
		}
	}

	$avatar_file = format_image_path(get_spec_image($avatar_file_relative,$aw,$aw,1));
	return  $avatar_file;
	//@file_put_contents($avatar_check_file,@file_get_contents(APP_ROOT_PATH."public/avatar/noavatar_".$type.".gif"));
}

//将指定文件移动到头像路径，并更新会员表的user_logo
function move_avatar_file($file,$user_id)
{
	$id = $user_id;
	//开始移动图片到相应位置

	$uid = sprintf("%09d", $id);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$path = $dir1.'/'.$dir2.'/'.$dir3;

	//创建相应的目录
	if (!is_dir(APP_ROOT_PATH."public/avatar/".$dir1)) {
		@mkdir(APP_ROOT_PATH."public/avatar/".$dir1);
		@chmod(APP_ROOT_PATH."public/avatar/".$dir1, 0777);
	}
	if (!is_dir(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2)) {
		@mkdir(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2);
		@chmod(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2, 0777);
	}
	if (!is_dir(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2.'/'.$dir3)) {
		@mkdir(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2.'/'.$dir3);
		@chmod(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2.'/'.$dir3, 0777);
	}

	$id = str_pad($id, 2, "0", STR_PAD_LEFT);
	$id = substr($id,-2);
	$avatar_file = APP_ROOT_PATH."public/avatar/".$path."/".$id."avatar.jpg";


	@file_put_contents($avatar_file, file_get_contents($file));

// 	if($file!=APP_ROOT_PATH."public/avatar/noavatar.gif")
// 	@unlink($file);

	if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
	{
		syn_to_remote_image_server("./public/avatar/".$path."/".$id."avatar.jpg");
	}

	$avatar_file_relative = "./public/avatar/".$path."/".$id."avatar.jpg";
	$GLOBALS['db']->query("update ".DB_PREFIX."user set avatar = '".$avatar_file_relative."' where id = ".$user_id);

	
	//上传成功更新用户头像的动态缓存
	if(function_exists("update_avatar")){
	    update_avatar($user_id);
	}
	
}

function  log_result($word)
{

	$file = APP_ROOT_PATH."/public/msg_url.log";;
	$fp = fopen($file,"a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}


/**
 * 请求api接口
 * @param unknown_type $act 接口名
 * @param unknown_type $param 参数
 *
 * 返回：array();
 */
function call_api_core($ctl,$act="index",$request_param=array())
{
	//定义基础数据
	$request_param['ctl']=$ctl;
	$request_param['act']=$act;
	//$request_param['r_type']=0;
	//$request_param['i_type']=1;
	$request_param['from']='wap';
	$request_param['sess_id'] = $GLOBALS['sess_id'];
	$request_param['email'] = $GLOBALS['cookie_uname'];
	$request_param['pwd'] = $GLOBALS['cookie_upwd'];
	$request_param['biz_uname'] = $GLOBALS['cookie_biz_uname'];
	$request_param['biz_upwd'] = $GLOBALS['cookie_biz_upwd'];
	$request_param['client_ip'] = CLIENT_IP;
	$request_param['image_zoom'] = 2;
	$request_param['ref_uid'] = $GLOBALS['ref_uid'];

	//以下是定位的传参，api端为可选参数，由wap端进行传参生成数据
	$request_param['city_id'] = $GLOBALS['city']['id'];
	$request_param['m_longitude'] = $GLOBALS['geo']['xpoint'];
	$request_param['m_latitude'] = $GLOBALS['geo']['ypoint'];

	filter_request($request_param);

	require_once APP_ROOT_PATH.'mapi/Lib/core/MainApp.class.php';

	$ApiApp = new MainApiApp($request_param);

	return $ApiApp->data();

}

//回收已开奖的早期数据(3个月前)
function delete_lotteryed_data($day=90)
{
	$date_str = to_date(NOW_TIME-3600*24*$day,"Y-m-d");

	$GLOBALS['db']->query("delete from ".DB_PREFIX."duobao_item_log_history where create_date_ymd < '".$date_str."'");
	$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order where create_date_ymd < '".$date_str."' and type = 2");
	$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order_item where create_date_ymd < '".$date_str."' and type = 2 and order_status = 2");
	$GLOBALS['db']->query("delete from ".DB_PREFIX."payment_notice where create_date_ymd < '".$date_str."'");
}


/**
 * 获取指定期数的夺宝号表名
 * @param unknown_type $duobao_item 必需有log_moved字段
 */
function duobao_item_log_table($duobao_item)
{
	if($duobao_item['log_moved']==1)
	{
		return " ".DB_PREFIX."duobao_item_log_history ";
	}
	else
	{
		return " ".DB_PREFIX."duobao_item_log ";
	}
}

/**
 * 把从数据库得到的二维索引数据集转换为以  指定键名  的二维关联数据集
 * @param unknown_type $data_info 从数据库得到的二维索引数据集
 * @param string $key指定的键名
 * @return $data_info_new 返回 指定键名的二维关联数据集
 */

function data_format_idkey($data_info,$key='id'){
	$data_info_new=array();
	foreach($data_info as $k=>$v){
		$data_info_new[$v[$key]]=$v;
	}
	return $data_info_new;

}


/**
 * 获取指定的流览历史ID
 * @param unknown_type $type deal/store
 * 以下两个函数，需要开启user登录，即在页面端action，需要执行global_run
 */
function get_view_history($type)
{
	$ids = load_auto_cache("cache_history",array("type"=>$type,"session_id"=>es_session::id(),"uid"=>$GLOBALS['user_info']['id'],"city_id"=>$GLOBALS['city']['id']));
	return $ids;
}

function set_view_history($type,$id)
{
	load_auto_cache("cache_history",array("type"=>$type,"rel_id"=>$id,"session_id"=>es_session::id(),"uid"=>$GLOBALS['user_info']['id'],"city_id"=>$GLOBALS['city']['id']));
}
/**
 * 固定宽高，剪切图片
 *
 * @param string $source_path 源图片
 * @param int $target_width 目标宽度
 * @param int $target_height 目标高度
 * @return string
 */
function imagecropper($source_path, $target_width, $target_height){
	$source_info = getimagesize($source_path);
	$source_width = $source_info[0];
	$source_height = $source_info[1];
	$source_mime = $source_info['mime'];
	$source_ratio = $source_height / $source_width;
	$target_ratio = $target_height / $target_width;
	
	// 源图过高
	if ($source_ratio > $target_ratio){
		$cropped_width = $source_width;
		$cropped_height = $source_width * $target_ratio;
		$source_x = 0;
		$source_y = ($source_height - $cropped_height) / 2;
	}elseif ($source_ratio < $target_ratio){ // 源图过宽
		$cropped_width = $source_height / $target_ratio;
		$cropped_height = $source_height;
		$source_x = ($source_width - $cropped_width) / 2;
		$source_y = 0;
	}else{ // 源图适中
		$cropped_width = $source_width;
		$cropped_height = $source_height;
		$source_x = 0;
		$source_y = 0;
	}
	
	switch ($source_mime){
		case 'image/gif':
			$source_image = imagecreatefromgif($source_path);
			break;
			
		case 'image/jpeg':
			$source_image = imagecreatefromjpeg($source_path);
			break;
		
		case 'image/png':
			$source_image = imagecreatefrompng($source_path);
			break;
			
		default:
			return false;
			break;
	}
	
	$target_image = imagecreatetruecolor($target_width, $target_height);
	$cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);
	
	// 裁剪
	imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
	// 缩放
	imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);
	
	$dotpos = strrpos($source_path, '.');
	$imgName = substr($source_path, 0, $dotpos);
	$suffix = substr($source_path, $dotpos);
	$imgNew = $imgName . '_255x255' . $suffix;
	
	imagejpeg($target_image, $imgNew, 100);
	imagedestroy($source_image);
	imagedestroy($target_image);
	imagedestroy($cropped_image);
	
	return $imgNew;
	}
	
/**
 * 等比例剪切图片：图片缩放函数（可设置高度固定，宽度固定或者最大宽高，支持gif/jpg/png三种类型）
 *
 * @param string $source_path 源图片
 * @param int $target_width 目标宽度
 * @param int $target_height 目标高度
 * @param string $fixed_orig 锁定宽高（可选参数 width、height或者空值）
 * @return string
 */
function imageproportion($source_path, $target_width = 200, $target_height = 200, $fixed_orig = ''){
	$source_info = getimagesize($source_path);
	$source_width = $source_info[0];
	$source_height = $source_info[1];
	$source_mime = $source_info['mime'];
	$ratio_orig = $source_width / $source_height;
	if ($fixed_orig == 'width'){
		//宽度固定
		$target_height = $target_width / $ratio_orig;
	}elseif ($fixed_orig == 'height'){
		//高度固定
		$target_width = $target_height * $ratio_orig;
	}else{
		//最大宽或最大高
		if ($target_width / $target_height > $ratio_orig){
			$target_width = $target_height * $ratio_orig;
		}else{
			$target_height = $target_width / $ratio_orig;
		}
	}
	switch ($source_mime){
		case 'image/gif':
			$source_image = imagecreatefromgif($source_path);
			break;
			
		case 'image/jpeg':
			$source_image = imagecreatefromjpeg($source_path);
			break;
			
		case 'image/png':
			$source_image = imagecreatefrompng($source_path);
			break;
			
		default:
			return false;
		break;
	}
	$target_image = imagecreatetruecolor($target_width, $target_height);
	imagecopyresampled($target_image, $source_image, 0, 0, 0, 0, $target_width, $target_height, $source_width, $source_height);
	//header('Content-type: image/jpeg');
	$imgArr = explode('.', $source_path);
	$target_path = '.'.$imgArr[1].'.'.$imgArr[2];
	imagejpeg($target_image, $target_path, 100);
	
	return $target_path;
}

function get_share_attach_list()
{
	$result = array();
	foreach($_REQUEST['share_image_ids'] as $id)
	{
	$share_image =array();
	$share_image['id'] =  intval($id);
	$result[] = $share_image;
	}
	return $result;
}

?>
