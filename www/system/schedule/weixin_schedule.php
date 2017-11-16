<?php

require_once(APP_ROOT_PATH.'system/libs/schedule.php');
class weixin_schedule implements schedule {
	
	/**
	 * $data 格式
	 * array("dest"=>openid,"content"=>序列化的消息配置);
	 */
	public function exec($data){
		
		
		$msg_result = unserialize($data['content']);
		$openid = $data['dest'];
		
		
		if(WEIXIN_TYPE=='platform'){
			
			$info=array(
					'touser'=>$openid,
					'template_id'=>$msg_result['template_id_short'],
					'url'=>$msg_result['url'],
					'topcolor'=>'#000000',
					'data'=>$msg_result['data']
			);
			
			$saas_url = "http://service.yun.fanwe.com/weixin/send_msg";
			//加密

			$client = new SAASAPIClient(FANWE_APP_ID, FANWE_AES_KEY);
			$ret = $client->invoke($saas_url, array("tmpl_data"=>json_encode($info)));
			if($ret['errcode']==0)
			{
				$is_success = 1;
				$err = "发送成功";
			}
			else
			{
				$is_success = 0;
				$err = $ret['errmsg'];
			}			
			
		}else{
			require_once APP_ROOT_PATH."system/wechat/wechat.class.php";
			$weixin_res = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_account_conf");
			foreach($weixin_res as $k=>$v){
				$option[$v['name']]=$v['value'];
			}
			$platform= new Wechat($option);
			
			$info=array(
					'touser'=>$openid,
					'template_id'=>$msg_result['template_id'],
					'url'=>$msg_result['url'],
					'topcolor'=>'#000000',
					'data'=>$msg_result['data']
			);
			$result=$platform->sendTemplateMessage($info);
			if($result){
				if(isset($result['errcode'])&&$result['errcode']>0){
					$is_success = 0;
					$err = $result['errMsg'];
				}else{
					$is_success = 1;
					$err = "发送成功";
				}
			}else{
				$is_success = 0;
				$err = "通讯失败";
			}

		}

	
		$result['status'] = $is_success;
		$result['attemp'] = 0;
		$result['info'] = $err;
		return $result;
	}	
}
?>