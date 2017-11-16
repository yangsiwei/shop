<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
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

log_result(var_export($_REQUEST,true));
//log_result(var_export($_SERVER,true));

require APP_ROOT_PATH.'system/wechat/wechat.class.php';
//require APP_ROOT_PATH."system/wechat/CIpLocation.php";
require APP_ROOT_PATH."system/libs/words.php";

class weixinModule
{
	public $option;
	public $platform;
	public $account;

	private function init_option($authorizer_appid=0)
	{

		//添加微信接口
		//$weixin_conf = load_auto_cache("weixin_conf_account");
		$weixin_res = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_account_conf");
		foreach($weixin_res as $k=>$v){
			$weixin_conf[$v['name']]=$v['value'];
		}
		//log_result(var_export($weixin_single,true));
		//log_result(var_export($weixin_conf,true));
		//log_result(3);
		$this->option = array(
				'appid'=>$weixin_conf['appid'],
				'appsecret'=>$weixin_conf['appsecret'],
				'token'=>$weixin_conf['token'],
				'encodingAesKey'=>$weixin_conf['encodingAesKey'],
				'debug'=>true,
		);

	}

	public function __construct()
	{
		//logger::write(print_r($_REQUEST,1));
		$this->init_option();
		//log_result(4);
		$this->platform = new Wechat($this->option);
		//log_result(var_export($this->option,true));
		$this->platform->log($_REQUEST);
	}
	//微信验证
	public function valid(){
		$re = $this->platform->valid();
		return $re;
	}

	//授权事件接收URL
	public function accept()
	{

		$platform= $this->platform;
		$result=$platform->valid();
		if($result['status']==1)
		{
			$msg=$result['info'];
			$platform->log($result);

			echo 'success';
		}
		else
		{
			//$platform->log($result);
		}
	}

	//公众号消息与事件接收URL
	public function gz_accept()
	{

		$platform= $this->platform;
		$platform->log("公众号消息与事件接收URL1");

		$platform->valid();

		if(true)
		{
 			//$msg= $platform->postxml;
			$msg =   $platform-> getRev()->getRevData();
 			$platform->log($msg);
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
						$condition =" i_msg_type='text'   ";
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
					    $pid    = str_replace('qrscene_', '', $msg['EventKey']);

					    // 查看分享的用户是否有权限进行渠道分享
					    $is_open_scan = $GLOBALS['db']->getOne("select is_open_scan from ".DB_PREFIX."user where id=".intval($pid));
					    //logger::write('开启：'.$is_open_scan.', pid:'.$pid);
					    if ($pid && $is_open_scan){
					        $openid = $msg['FromUserName'];
					        $nowtime = $now = (time() - date('Z'));
					        //logger::write('开启：'.$is_open_scan.', pid:'.$pid);
					        $GLOBALS['db']->query("INSERT INTO ".DB_PREFIX."scan_subscribe_log (`openid`, `pid`, `create_time`) VALUES ('{$openid}', $pid, $nowtime) ON DUPLICATE KEY UPDATE pid={$pid}");
					    }


						//关注
						$condition ="   type=4 and default_close=0 ";
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
					from ".DB_PREFIX."weixin_reply where scale_meter - ((ACOS(SIN(($ypoint * $pi) / 180 ) * SIN((y_point * $pi) / 180 ) + COS(($ypoint * $pi) / 180 ) * COS((y_point * $pi) / 180 ) * COS(($xpoint * $pi) / 180 - (x_point * $pi) / 180 ) ) * $r)) > 0   and i_msg_type='location' order by distance asc";
					$reply=$GLOBALS['db']->getRow($sql);
					$this->responseReply($reply);

				}
				elseif($msg['MsgType']=='text')
				{
					//点击事件 查询关键字
					$condition ="  i_msg_type='text' ";
					$keywords = $msg['Content'];
					if($keywords)
					{
						$unicode_tag = str_to_unicode_string($keywords);
						$condition .= " and (MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) or keywords = '".$keywords."') ";
					}
					$platform->log("select * ,MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) AS similarity from ".DB_PREFIX."weixin_reply where ".$condition);
	 				$reply=$GLOBALS['db']->getRow("select * ,MATCH(keywords_match) AGAINST('".$unicode_tag."' IN BOOLEAN MODE) AS similarity from ".DB_PREFIX."weixin_reply where ".$condition);
	 				$platform->log($reply);
					$this->responseReply($reply);
				}
			}
		}
	}
	private function responseReply($reply)
	{
		if(!$reply)
		{
			$condition ="    type=1 and default_close=0   ";
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

}

$a = strim($_REQUEST['a']);
$obj = new weixinModule();
if($_SERVER['REQUEST_METHOD'] == "POST"){
 	$obj->gz_accept();
}else{
 	$obj->valid();
}

?>