<?php
header("Content-type: text/html; charset=utf-8");
/**
 *类名：trade.php
 *功能  服务器端创建交易Demo
 *版本：1.0
 *日期：2014-06-26
 '说明：
 '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己的需要，按照技术文档编写,并非一定要使用该代码。
 '该代码仅供学习和研究爱贝云计费接口使用，只是提供一个参考。
*/
require_once ("base.php");
require_once ("IpayBase.class.php");

class IpayTrade extends IpayBase{
    //此为下单函数。使用时请把下列参数按要求更换成你们自己的数据。另外也需要更换config.php 中的公钥和私钥
    function addOrder($orderReq) {
        
        //组装请求报文  对数据签名
        $reqData = composeReq($orderReq, $this->appkey);
    
        //发送到爱贝服务后台请求下单
        $respData = request_by_curl($this->orderUrl, $reqData, 'order test');

        //验签数据并且解析返回报文
        if(!parseResp($respData, $this->platpkey, $respJson)) {
            return false;
        }else{
            return $this->transid=$respJson->transid;
        }
    
    }
    //此为H5 和PC 版本调收银台时需要的参数组装函数   特别提醒的是  下面的函数中有  $h5url 和$pcurl 两个url地址。 只需要更换这两个地址就可以 调出 H5 收银台和PC版本收银台。
    function H5orPCpay() {
        //下单接口
        $orderReq['transid'] = $this->transid;
        $orderReq['redirecturl'] = $this->redirecturl; //支付成功后支付回调URL地址
        $orderReq['cpurl'] = ''; // 选填：返回商户URL地址
        //组装请求报文   对数据签名
        $reqData = composeReq($orderReq, $this->appkey);
    
        $html .= "<script language=\"javascript\">";
        $html .= "location.href=\"{$this->h5OrPcUrl}{$reqData}\"";//我们的常连接版本 有PC 版本 和移动版本。 根据使用的环境不同请更换相应的URL:$h5url,$pcurl.
        $html .= "</script>";
        return $html;
    }
    
    // 返回pc端的html组合
    function H5orPCpayUrl($money) {
        //下单接口
        $orderReq['transid'] = $this->transid;
        $orderReq['redirecturl'] = $this->redirecturl; //支付成功后支付回调URL地址
        $orderReq['cpurl'] = ''; // 选填：返回商户URL地址
        //组装请求报文   对数据签名
        $reqData = composeReq($orderReq, $this->appkey);
        $payLinks = '<form action="'.$this->h5OrPcUrl.$reqData.'" method="post" target="_blank" ><button type="submit" class="ui-button paybutton" rel="blue">前往爱贝在线支付</button></form>';
        $code = '<div style="text-align:center">'.$payLinks.'</div>';
        $code.="<br /><div style='text-align:center' class='red'>".$GLOBALS['lang']['PAY_TOTAL_PRICE'].":".format_price($money)."</div>";
        return $code;
    }
    
    
    //在使用H5 Iframe版本时 生成签名数据  次函数只适用于H5  Iframe版本支付。
    function H5IframeSign($transid,$redirecturl,$cpurl, $appkey){
        $content=trim($transid).''.trim($redirecturl).''.trim($cpurl);//拼接$transid   $redirecturl    $cpurl
        $appkey=formatPriKey($appkey);
        $sign=sign($content,$appkey);
        return $sign;
    }
}






?>