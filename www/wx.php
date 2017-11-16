<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

define("FILE_PATH",""); //文件目录，空为根目录
require_once './system/system_init.php';

function wx_show($message)
{
	header("Content-Type:text/html; charset=utf-8");
	echo $message;
}



require_once APP_ROOT_PATH.'system/wechat/platform_wechat.class.php';
//require APP_ROOT_PATH."system/wechat/CIpLocation.php";
require_once APP_ROOT_PATH."system/libs/words.php";

class weixinModule
{
	public $option;
	public $platform;
	public $account;

	private function init_option($authorizer_appid=0)
	{
		//添加微信接口
		$weixin_conf = load_auto_cache("weixin_conf");

		$this->option = array(
				'platform_token'=>$weixin_conf['platform_token'], //填写你设定的token
				'platform_encodingAesKey'=>$weixin_conf['platform_encodingAesKey'], //填写加密用的EncodingAESKey
				'platform_appid'=>$weixin_conf['platform_appid'], //填写高级调用功能的app id
				'platform_appsecret'=>$weixin_conf['platform_appsecret'], //填写高级调用功能的密钥

				'platform_component_verify_ticket'=>$weixin_conf['platform_component_verify_ticket'], //第三方通知
				'platform_component_access_token'=>$weixin_conf['platform_component_access_token'], //第三方平台令牌
				'platform_pre_auth_code'=>$weixin_conf['platform_pre_auth_code'], //第三方平台预授权码

				'platform_component_access_token_expire'=>$weixin_conf['platform_component_access_token_expire'],
				'platform_pre_auth_code_expire'=>$weixin_conf['platform_pre_auth_code_expire'],



				'logcallback'=>'log_result',
				'debug'=>false,
		);

		if($authorizer_appid=='')
			$authorizer_appid = strim($_REQUEST['appid']);
		if($authorizer_appid){
			$authorizer_appid=trim($authorizer_appid,'/');
			$account = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_account where authorizer_appid='".$authorizer_appid."' ");
			$this->account = $account ;
			if($account){
				$option_account=array(
						'authorizer_access_token'=>$this->account['authorizer_access_token'],
						'authorizer_access_token_expire'=>$this->account['expires_in'],
						'authorizer_appid'=>$this->account['authorizer_appid'],
						'authorizer_refresh_token'=>$this->account['authorizer_refresh_token'],
				);
				$this->option=array_merge($this->option,$option_account);
			}
		}
		else
		{
			$this->account['user_id'] = -1;
		}

	}

	public function __construct()
	{
		//logger::write(print_r($_REQUEST,1));
		$this->init_option();
		$this->platform = new PlatformWechat($this->option);
	}
	//微信验证
	public function valid(){
		echo 'valid';
	}
	//发起授权页的体验URL
	public function valid_url(){
		$weixin_conf = load_auto_cache("weixin_conf");

		if($weixin_conf['platform_status']==0)
		{
			wx_show("公众平台功能已关闭");
		}
		if($weixin_conf['platform_all_publish']==1)
		{
			wx_show("已全网发布");
		}
		else
		{
			$platform_pre_auth_code=$this->platform->check_platform_get_pre_auth_code();
			$return_url=SITE_DOMAIN.APP_ROOT."/wx.php?a=platform_get_auth_code"; //授权体验
			$return_url=urlencode($return_url);
			$sq_url='https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$this->option['platform_appid'].'&pre_auth_code='.$platform_pre_auth_code.'&redirect_uri='.$return_url;
			
			wx_show("<a href='".$sq_url."' target='_blank'>测试授权登录</a>");
		}

	}
	//授权事件接收URL
	public function accept()
	{

		$platform= $this->platform;
		$result=$platform->platform_DecryptMsg();
		if($result['status']==1)
		{
			$msg=$result['info'];
			//$platform->log($result);
			if($msg['InfoType']=='component_verify_ticket')
			{
				if($msg['ComponentVerifyTicket'])
				{
					//保存component_verify_ticket
					$GLOBALS['db']->query("update ".DB_PREFIX."weixin_conf set value='".$msg['ComponentVerifyTicket']."' where name='platform_component_verify_ticket'");
					rm_auto_cache("weixin_conf");
					//load_auto_cache("weixin_conf");
				}
				else
				{
					$info['msg']='ComponentVerifyTicket 为空';
					//$platform->log($result);
				}
			}
			echo 'success';
		}
		else
		{
			$platform->log($result);
		}
	}
	
	//公众号消息与事件接收URL
	public function gz_accept()
	{
		$weixin_conf = load_auto_cache("weixin_conf");
		if($weixin_conf['platform_status']==0)
		{
			exit;
		}
		$platform= $this->platform;
		$platform->log("公众号消息与事件接收URL");
		$platform->log($_REQUEST);
		$result=$platform->platform_DecryptMsg();
		$platform->log($result);
		//$platform->log($this->option);
		if($result['status']==1)
		{
			$msg=$result['info'];
			if($msg['ToUserName']=='gh_3c884a361561')
			{
				//测试
				if($msg['MsgType']=='event')
				{
					$this->platform->text($msg['Event'].'from_callback')->reply();
				}
				elseif($msg['MsgType']=='text')
				{
					if($msg['Content']=='TESTCOMPONENT_MSG_TYPE_TEXT')
					{
						$this->platform->text('TESTCOMPONENT_MSG_TYPE_TEXT_callback')->reply();
					}
					else
					{
						$query_auth_code = str_replace('QUERY_AUTH_CODE:','',$msg['Content']);
						if($query_auth_code)
						{
							$sendData = array();
							$sendData['msgtype'] =  'text';
							$sendData['text']['content'] = $query_auth_code.'_from_api';
							$sendData['touser'] = $msg['FromUserName'];
							$platform->test_sendCustomMessage($sendData,$query_auth_code);
						}
					}
				}
			}
			else
			{
					
				if($msg['MsgType']=='event')
				{
					if($msg['Event']=='CLICK')
					{
						//点击事件 查询关键字
						$condition =" account_id=".$this->account['user_id']." and i_msg_type='text'   ";
						$keywords = $msg['EventKey'];
						if($keywords)
						{
							$unicode_tag = str_to_unicode_string($keywords);
							$condition .= " and (MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) or keywords = '".$keywords."') ";
						}
						$reply=$GLOBALS['db']->getRow("select * ,MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) AS similarity from ".DB_PREFIX."weixin_reply where ".$condition);
						$this->responseReply($reply);
					}
					elseif($msg['Event']=='subscribe')
					{
						//关注
						$condition =" account_id=".$this->account['user_id']."   and type=4 and default_close=0 ";
						$reply=$GLOBALS['db']->getRow("select *  from ".DB_PREFIX."weixin_reply where ".$condition);
						//$platform->log($reply);
						$this->responseReply($reply);
					}
					elseif($msg['Event']=='unsubscribe')
					{
						//用户取消关注
					}
				}
				elseif($msg['MsgType']=='location')
				{
					$ypoint = strim($msg['Location_X']);
					$xpoint = strim($msg['Location_Y']);
					$pi = 3.14159265;  //圆周率
					$r = 6378137;  //地球平均半径(米)
						
					$sql = "select * ,(ACOS(SIN(($ypoint * $pi) / 180 ) *SIN((y_point * $pi) / 180 ) + COS(($ypoint * $pi) / 180 ) * COS((y_point * $pi) / 180 ) * COS(($xpoint * $pi) / 180 - (x_point * $pi) / 180 ) ) * $r) as distance
					from ".DB_PREFIX."weixin_reply where scale_meter - ((ACOS(SIN(($ypoint * $pi) / 180 ) * SIN((y_point * $pi) / 180 ) + COS(($ypoint * $pi) / 180 ) * COS((y_point * $pi) / 180 ) * COS(($xpoint * $pi) / 180 - (x_point * $pi) / 180 ) ) * $r)) > 0 and account_id = ".$this->account['user_id']." and i_msg_type='location' order by distance asc";
					$reply=$GLOBALS['db']->getRow($sql);
					$this->responseReply($reply);

				}
				elseif($msg['MsgType']=='text')
				{
					//点击事件 查询关键字
					$condition =" account_id=".$this->account['user_id']." and i_msg_type='text' ";
					$keywords = $msg['Content'];
					if($keywords)
					{
						$unicode_tag = str_to_unicode_string($keywords);
						$condition .= " and (MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) or keywords = '".$keywords."') ";
					}
	 				$reply=$GLOBALS['db']->getRow("select * ,MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) AS similarity from ".DB_PREFIX."weixin_reply where ".$condition);
	 				$this->responseReply($reply);
				}
			}
		}
	}
	private function responseReply($reply)
	{
		if(!$reply)
		{
			$condition =" account_id=".$this->account['user_id']." and type=1 and default_close=0   ";
		  	$reply=$GLOBALS['db']->getRow("select *  from ".DB_PREFIX."weixin_reply where ".$condition);
		}
		if($reply['o_msg_type']=='text')
		{
		   	$content = htmlspecialchars_decode(stripslashes($reply['reply_content']));
			$content = str_replace(array('<br/>','<br />','&nbsp;'), array("\n","\n",' '), $content);
			$this->platform->text($content)->reply();
		 }
		 elseif($reply['o_msg_type']=='news')
		 {
  			$new=array();		  						
		  	$url_data = unserialize($reply['data']);
		  	if($reply['ctl']!="url")
		  		$url = SITE_DOMAIN.wap_url("index",$reply['ctl'],$url_data);
		  	else
		  		$url = htmlspecialchars_decode(stripslashes($url_data['url']));

		  						
		  						
		  	$new[]=array('Title'=>$reply['reply_news_title'],'Description'=>$reply['reply_news_description'],'PicUrl'=>format_image_path($reply['reply_news_picurl']),'Url'=>$url);
		  	$article_count = 1;

		  	$sql = "select r.* from ".DB_PREFIX."weixin_reply as r left join ".DB_PREFIX."weixin_reply_relate as rr on r.id = rr.relate_reply_id  where rr.main_reply_id = ".$reply['id'];
                	
            $relate_replys=$GLOBALS['db']->getAll($sql);
            
            $article_count = $article_count + intval(count($relate_replys));
                	
            foreach($relate_replys as $k=>$item)
            {
                if($item)
                {
                	$url_data = unserialize($item['data']);
                	if($item['ctl']!="url")
                		$url = SITE_DOMAIN.wap_url("index",$item['ctl'],$url_data);
                	else
				 		$url = $url_data['url'];

                	$new[]=array('Title'=>$item['reply_news_title'],'Description'=>$item['reply_news_description'],'PicUrl'=>format_image_path($item['reply_news_picurl']),'Url'=>$url);
                }
            }
           	$this->platform->news($new)->reply();
        }
	}
	//接受验证码并展示
	public function platform_get_auth_code()
	{
		$weixin_conf = load_auto_cache("weixin_conf");
		$platform= new PlatformWechat($this->option);
		$auth_code= $_REQUEST['auth_code'];
		$type = intval($_REQUEST['type']);
		if($type==1)
			$user_id = 0;
		else
		{
			wx_show("授权成功");
			exit;
		}
		$re=$platform->platform_api_query_auth($auth_code,$type,$user_id);
		if($re)
		{
			$this->init_option($re['authorizer_appid']);
			$platform= new PlatformWechat($this->option);
			$info=$platform->platform_get_authrizer_info();
			if($info)
			{
				wx_show("授权成功");
			}
			else
			{
				wx_show($platform->errMsg);
			}				
		}
		else
		{
			wx_show($platform->errMsg);
		}
	}
}



function wxpay_qr_code_transapp()
{

	$payment_notice_id = intval($_REQUEST['notice_id']);
	$prepay_id = strim($_REQUEST['prepay_id']);


	require_once APP_ROOT_PATH."system/payment/Wxjspay/WxPayPubHelper.php";
	require_once APP_ROOT_PATH."system/payment/Wxjspay/WxPay.NativePay.php";
	require_once APP_ROOT_PATH."system/model/cart.php";
	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
	$payment_notice_id = make_payment_notice($payment_notice['money'],$payment_notice['order_id'],$payment_notice['payment_id'],'二维码下单',0,true);
	$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);

	$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
	if($order_info['type']==1)
		{
			$title_name = "会员充值".round($order_info['total_price'])."元";
		}
		else
		{
			$sql = "select name ".
					"from ".DB_PREFIX."deal_order_item ".
					"where order_id =". intval($payment_notice['order_id']);
			$title_name = $GLOBALS['db']->getOne($sql);
		}
	
	$order_sn = $order_info['order_sn'];
	
	$money = round($payment_notice['money'],2);
	$money_fen=intval($money*100);

	$payment_info = $GLOBALS['db']->getRow("select id,config,logo,name from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
	$payment_info['config'] = unserialize($payment_info['config']);
	$wx_config=$payment_info['config'];
	$data_notify_url = SITE_DOMAIN.APP_ROOT.'/callback/payment/wwxjspay_notify.php';

	//二维码
	$notify = new NativePay();

	$input = new WxPayUnifiedOrder();
	$input->SetBody(iconv_substr($title_name,0,50, 'UTF-8'));
	$input->SetOut_trade_no($payment_notice['notice_sn']);
	$input->SetTotal_fee($money_fen);
	$input->SetNotify_url($data_notify_url);
	$input->SetTrade_type("NATIVE");
	$input->SetProduct_id($prepay_id);
	// 		logger::write(print_r($input,1));
	$result = $notify->GetPayUrl($input);
	// 		logger::write(print_r($result,1));
	$url = $result["code_url"];
	gen_qrcode($url,5,true);
}


$a = strim($_REQUEST['a']);
$obj = new weixinModule();
if($a=="valid")
{
	$obj->valid();
}elseif($a=="valid_url")
{
	$obj->valid_url();
}elseif($a=="accept")
{
	$obj->accept();
}elseif($a=="gz_accept")
{
	$obj->gz_accept();
}elseif($a=="platform_get_auth_code")
{
	$obj->platform_get_auth_code();
}
elseif($a=="pay_qrcode")
{
	wxpay_qr_code_transapp();
}
?>