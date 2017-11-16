<?php
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['server_url'] = 'http://api.sms.cn/sms/';

    $module['class_name']    = 'YX';
    /* 名称 */
    $module['name']    = "中国云信使";

    if(ACTION_NAME == "install" || ACTION_NAME == "edit"){
        $module['lang']  = array();
        $module['config'] = array();
    }
    return $module;
}

//中国云信使
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
require_once APP_ROOT_PATH."system/utils/transport.php";

class YX_sms implements sms
{

    public $sms;
    public $message = "";

    private $statusStr = array(
        "100" => "发送成功",
        "101" => "验证失败",
        "102" => "短信不足",
        "103" => "操作失败",
        "104" => "非法字符",
        "105" => "内容过多",
        "106" => "号码过多",
        "107" => "频率过快",
        "108" => "号码内容空",
        "109" => "账号冻结",
        "112" => "号码错误",
        "113" => "定时出错",
        "116" => "禁止接口发送",
        "117" => "绑定IP不正确",
        "161" => "未添加短信模板",
        "162" => "模板格式不正确",
        "163" => "模板ID不正确",
        "164" => "全文模板不匹配"
    );


    public function __construct($smsInfo = '')
    {
        if (!empty($smsInfo)) {
            $this->sms = $smsInfo;
        }
    }

    public function getSmsInfo()
    {

        return "中国云信使";

    }

    public function sendSMS($mobile_number, $content, $is_adv = 0)
    {
        $sms = new transport();

        if (is_array($mobile_number)) {
            $mobile_number = implode(",", $mobile_number);
        }
        $code = '测试0';
        $content_ss = json_encode(array("code"=>$code,'product'=>'夺宝联盟'));
        $params = array(
            "ac" => 'send',
            "uid" => $this->sms['user_name'],
            "pwd" => $this->sms['password'],
            "mobile" => $mobile_number,
            "content" => urlencode($content_ss),
        );

        $result_info = $sms->request($this->sms['server_url'], $params,'get');

        $res = json_decode($result_info['body'],true);

        $code = $res['stat'];

        if ($code == 100) {
            $result['status'] = 100;
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

        $url = "http://api.sms.cn/sms/";

        $params['ac'] = 'number';
        $params['uid'] = $this->sms['user_name'];
        $params['pwd'] = $this->sms['password'];

        $res = $sms->request($url, $params, 'get');
        $result = json_decode($res['body'],true);
        $code = $result['stat'];
        if ($code == 100) {
            return '当前剩余短信条数：' . $result['number'];
        } else {
            return $this->statusStr[$code];
        }


    }
}

?>