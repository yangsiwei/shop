<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['server_url'] = 'http://q.hl95.com:8061/';
	
    $module['class_name']    = 'HL';
    /* 名称 */
    $module['name']    = "鸿联短信平台";
  
    if(ACTION_NAME == "install" || ACTION_NAME == "edit"){  
	    $module['lang']  = array(
	        'epid'=>'企业ID',
	        'sign_str'=>'签  名',
	    );
	    $module['config'] = array(
	        'epid'=>'',
	        'sign_str'=>'',
	    );	
    }
    return $module;
}

// 鸿联企信通短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
require_once APP_ROOT_PATH."system/utils/transport.php";
class HL_sms implements sms
{
	public $sms;
	public $message = "";
	
    public function __construct($smsInfo = '')
    {
		if(!empty($smsInfo))
		{
			$this->sms = $smsInfo;
		}
    }
	 
	public function sendSMS($mobile_number,$content,$is_adv=0)
	{
		$sms = new transport(-1, -1, -1, true);
		if(is_array($mobile_number)){
			$mobile_number = implode(",",$mobile_number);
		}
		 
		$params['username'] = $this->sms['user_name'];
		$params['password'] = $this->sms['password'];
		$params['phone']    = $mobile_number;
		
		// 模版需这个签名 '【重在参与】'
		$content = iconv( "UTF-8", "gb2312" , $this->sms['config']['sign_str'].$content);
		
		$params['message']  = urlencode($content);
		$params['epid']     = $this->sms['config']['epid'];
		$params['linkid']   = '';
		$params['subcode']  = '';
		 
		$result_info = $sms->request($this->sms['server_url'],$params, 'GET');
		 
		$return = $result_info['body'];
		if($return === '00'){
		    $result['status'] = 1;
		    $result['msg'] = '发送成功';
		}else{
		    $result['status'] = 0;
		    $result['msg'] = '发生失败：代码 '.$return;
		}
		
		return $result;
	}
	
	public function getSmsInfo()
	{	

		return "鸿联短信平台";	
		
	}
	
	public function check_fee()
	{
		$sms = new transport(-1, -1, -1, true);
					
		$url = "http://114.255.71.158:8061/getfee/";
		 
		$params['username'] = $this->sms['user_name'];
		$params['password'] = $this->sms['password'];
		$params['epid']     = $this->sms['config']['epid'];
		
		$result = $sms->request($url, $params, 'GET');
	    if ($result['body'] > 0) {
	        return '当前剩余短信条数：'.$result['body'];
	    }else{
	        return '查询失败'.$result['body'];
	    }


	}
	
	 
}
?>