<?php
header("Content-type: text/html; charset=utf-8");
/**
 *功能：配置文件
 *版本：1.0
 *修改日期：2014-06-26
 '说明：
 '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己的需要，按照技术文档编写,并非一定要使用该代码。
 '该代码仅供学习和研究爱贝云计费接口使用，只是提供一个参考。
 */
class IpayBase{
    public $redirecturl;
    public $h5url;
    public $pcurl;
    public $h5OrPcUrl;   // h5和pc中选一个值
    public function __construct(){
        //爱贝商户后台接入url
        // $coolyunCpUrl="http://pay.coolyun.com:6988";
        $this->iapppayCpUrl="http://ipay.iapppay.com:9999";
        //登录令牌认证接口 url
        $this->tokenCheckUrl=$this->iapppayCpUrl . "/openid/openidcheck";
        
        //下单接口 url
        // $orderUrl=$coolyunCpUrl . "/payapi/order";
        $this->orderUrl=$this->iapppayCpUrl . "/payapi/order";
        
        //支付结果查询接口 url
        $this->queryResultUrl=$this->iapppayCpUrl ."/payapi/queryresult";
        
        //契约查询接口url
        $this->querysubsUrl=$this->iapppayCpUrl."/payapi/subsquery";
        
        //契约鉴权接口Url
        $this->ContractAuthenticationUrl=$this->iapppayCpUrl."/payapi/subsauth";
        
        //取消契约接口Url
        $this->subcancel=$this->iapppayCpUrl."/payapi/subcancel";
        //H5和PC跳转版支付接口Url
        $this->h5url="https://web.iapppay.com/czb/exbegpay?";
        $this->pcurl="https://web.iapppay.com/czb-pc/exbegpay?";
        
       
        if ($_POST['is_app'] == 1) {
            $payment_info = $GLOBALS['db']->getRow("select id,config,logo,name from ".DB_PREFIX."payment where class_name='IpayApp'");
        }else{
            $payment_info = $GLOBALS['db']->getRow("select id,config,logo,name from ".DB_PREFIX."payment where class_name='IpayLink'");
        }
       
        $config = unserialize($payment_info['config']);
        
        //应用编号
        $this->appid= $config['appid'];
        // 应用中的商品编号
        $this->waresid = $config['waresid'];
        //应用私钥
        $this->appkey= $config['appkey'];
        //平台公钥
        $this->platpkey= $config['platpkey'];
    }
}

 
?>