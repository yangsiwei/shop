<?php

/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['server_url'] = 'http://yzm.mb345.com/ws/BatchSend2.aspx';
	
    $module['class_name']    = 'LK';
    /* 名称 */
    $module['name']    = "凌凯短信平台";
  
    if(ACTION_NAME == "install" || ACTION_NAME == "edit"){
	  $module['lang']  = array();
      $module['config'] = array();
    }
    return $module;
}

//凌凯短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
require_once APP_ROOT_PATH."system/utils/transport.php";

class LK_sms implements sms
{

    public $sms;
    public $message = "";

    private $statusStr = array(
        "-1" => "账号未注册",
        "-2" => "网络访问超时，请稍后再试",
        "-3" => "帐号或密码错误",
        "-4" => "只支持单发",
        "-5" => "余额不足，请充值",
        "-6" => "定时发送时间不是有效的时间格式",
        "-7" => "提交信息末尾未签名，请添加中文的企业签名【 】或未采用gb2312编码",
        "-8" => "发送内容需在1到300字之间",
        "-9" => "发送号码为空",
        "-10" => "定时时间不能小于系统当前时间",
        "-11" => "屏蔽手机号码",
        "-100" => "限制IP访问"
    );

    private $statusCheckFee = array(
        "-1" => "账号未注册",
        "-2" => "网络访问超时，请稍后重试",
        "-3" => "密码错误",
        "-101" => "调用接口频率过快（大于30s调用一次）"
    );

    public function __construct($smsInfo = '')
    {
        if (!empty($smsInfo)) {
            $this->sms = $smsInfo;
        }
    }

    public function getSmsInfo()
    {

        return "凌凯短信平台";

    }

    public function sendSMS($mobile_number, $content, $is_adv = 0)
    {
        $sms = new transport();

        if (is_array($mobile_number)) {
            $mobile_number = implode(",", $mobile_number);
        }

        $params = array(
            "CorpID" => $this->sms['user_name'],
            "Pwd" => $this->sms['password'],
            "Mobile" => $mobile_number,
            "Content" => urlencode($content),
        );
        $result_info = $sms->request($this->sms['server_url'], $params);
        $code = $result_info['body'];
        if ($code >= 0) {
            $result['status'] = 1;
            $result['msg'] = '发送成功';
        } else {
            $result['status'] = 0;
            $result['msg'] = $this->statusStr[$code];
        }

        return $result;
    }

    public function check_fee()
    {

        $sms = new transport();

        $url = "http://yzm.mb345.com/ws/SelSum.aspx";

        $params['CorpID'] = $this->sms['user_name'];
        $params['Pwd'] = $this->sms['password'];

        $result = $sms->request($url, $params, 'get');
        $code = $result['body'];
        if ($result['body'] > 0) {
            return '当前剩余短信条数：' . $result['body'];
        } else {
            return $this->statusCheckFee[$code];
        }


    }
}
