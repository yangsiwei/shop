<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>数据输入页</title>
<?php
    $merchant_code= "10020244";//商户号
    $order_no =  date("Ymdhis");//商户唯一订单
	$order_time = date("Y-m-d H:i:s");//商户订单时间
?>
</head>
<SCRIPT LANGUAGE="JavaScript">
    function dosubmit(){
       form1.action = "post.php";
       document.form1.submit();
    }
</SCRIPT>
<body>
<form method="post" name="form1" id="form1" >
<table style="border-collapse:separate; border-spacing:0px 10px;">

<tr>
<td width="250">请求服务模式(service_type)</td>
<td width="300">
<select name="service_type" style="width:300px;">
<option value="checkstand_service">收银台模式</option>
<option value="connect_service">直连模式</option>
<option value="local_service">本地模式</option>
</select>
</td>
</tr>

<tr>
<td width="250">支付请求模型(pay_model)</td>
<td width="300">
<select name="pay_model" style="width:300px;">
<option value="PC">电脑端网页渠道支付</option>
<option value="SCODE">支付宝和微信扫码支付</option>
<option value="H5">移动端H5或WAP支付</option>
<option value="GONGZONGHAO">微信公众号支付</option>
<option value="APP">原生APP支付</option>
<option value="OFFLINE">线下支付</option>
</select>
</td>
</tr>

<tr>
<td width="250">网关支付渠道编码(bank_code)</td>
<td width="300">
<select name="bank_code" style="width:300px;">
<option value="">收银台空编码</option>
<option value="WEBCHAT">微信支付</option>
<option value="ALIPAY">支付宝</option>
<option value='ICBC'>工商银行</option>
<option value='CMB'>招商银行</option>
<option value='ABC'>农业银行</option>
<option value='CCB'>建设银行</option>
<option value='BOB'>北京银行</option>
<option value='BCOM'>交通银行</option>
<option value='CIB'>兴业银行</option>
<option value='CMBC'>中国民生银行</option>
<option value='CEB'>中国光大银行</option>
<option value='BOC'>中国银行</option>
<option value='PAB'>平安银行</option>
<option value='CITIC'>中信银行</option>
<option value='GDB'>广东发展银行</option>
<option value='SHB'>上海银行</option>
<option value='SPDB'>上海浦东发展银行</option>
<option value='PSBC'>中国邮政储蓄银行</option>
<option value='BJRCB'>北京农村商业银行</option>
<option value='HXB'>华夏银行</option>		
</select>
</td>
</tr>

<tr>
<td width="250">商家号(merchant_code)</td>
<td width="300">
<input style="width:300px;" type="text" name="merchant_code" value="<?php echo $merchant_code;?>"/>
</td>
</tr>

<tr>
<td width="250">接口版本(interface_version)</td>
<td width="300">
<input style="width:300px;" type="text" name="interface_version" value="V1.0"/>
</td>
</tr>

<tr>
<td width="250">签名类型(sign_type)</td>
<td width="300">
<select name="sign_type" style="width:300px;">
<option value="MD5">MD5签名类型</option>
<option value="RSA">RSA签名类型</option>
</select>
</td>
</tr>

<tr>
<td width="250">商家订单号(order_no)</td>
<td width="300">
<input style="width:300px;" type="text" name="order_no" value="<?php echo $order_no;?>"/>
</td>
</tr>

<tr>
<td width="250">商家订单时间(order_time)</td>
<td width="300">
<input style="width:300px;" type="text" name="order_time" value="<?php echo $order_time;?>"/>
</td>
</tr>

<tr>
<td width="250">商家订单金额(order_amount)元</td>
<td width="300">
<input style="width:300px;" type="text" name="order_amount" value="1"/>
</td>
</tr>

<tr>
<td width="250">商家商品数量(product_number)</td>
<td width="300">
<input style="width:300px;" type="text" name="product_number" value="1"/>
</td>
</tr>

<tr>
<td width="250">商家异步通知地址(notify_url)</td>
<td width="300">
<input style="width:300px;" type="text" name="notify_url" value="http://fm.jinkalian.com/phpDemo/notify_url.php"/>
</td>
</tr>

<tr>
<td width="250">商家前台跳转地址(return_url)</td>
<td width="300">
<input style="width:300px;" type="text" name="return_url" value="http://fm.jinkalian.com/phpDemo/return_url.php"/>
</td>
</tr>

<tr>
<td width="250">商家商品名称(product_name)</td>
<td width="300">
<input style="width:300px;" type="text" name="product_name" value="pname"/>
</td>
</tr>

<tr>
<td width="250">商户平台会员账号(order_userid)</td>
<td width="300">
<input style="width:300px;" type="text" name="order_userid" value="ouserid"/>
</td>
</tr>

<tr>
<td width="250">商户商品附加信息(order_info)</td>
<td width="300">
<input style="width:300px;" type="text" name="order_info" value="oinfo"/>
</td>
</tr>

<tr>
<td width="250">商户通知类型(notice_type)</td>
<td width="300">
<select name="notice_type" style="width:300px;">
<option value="1">前台跳转和异步通知</option>
<option value="0">只底层异步通知</option>
</select>
</td>
</tr>

<tr>
<td colspan="2" align="center"><input type="button" name="button" value="提交" onClick="dosubmit()" /></td>
</tr>

</table>
</form>
</body>
</html>