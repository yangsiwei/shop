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

//爱贝商户后台接入url
// $coolyunCpUrl="http://pay.coolyun.com:6988";
$iapppayCpUrl="http://ipay.iapppay.com:9999";
//登录令牌认证接口 url
$tokenCheckUrl=$iapppayCpUrl . "/openid/openidcheck";

//下单接口 url
// $orderUrl=$coolyunCpUrl . "/payapi/order";
$orderUrl=$iapppayCpUrl . "/payapi/order";

//支付结果查询接口 url
$queryResultUrl=$iapppayCpUrl ."/payapi/queryresult";

//契约查询接口url
$querysubsUrl=$iapppayCpUrl."/payapi/subsquery";

//契约鉴权接口Url
$ContractAuthenticationUrl=$iapppayCpUrl."/payapi/subsauth";

//取消契约接口Url
$subcancel=$iapppayCpUrl."/payapi/subcancel";
//H5和PC跳转版支付接口Url
//$h5url="https://web.iapppay.com/h5/exbegpay?";
$h5url = "https://web.iapppay.com/czb/exbegpay?";
$pcurl = "https://web.iapppay.com/czb-pc/exbegpay?"; 

//应用编号
$appid="";
//应用私钥
$appkey="";
//平台公钥
$platpkey="";

?>