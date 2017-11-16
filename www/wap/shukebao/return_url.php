<?php
/**参数列表 **/
$merchant_code = $_REQUEST['merchant_code'];//商户号
$interface_version= $_REQUEST['interface_version'];//接口版本
$order_no= $_REQUEST['order_no'];//商家订单号
$trade_no= $_REQUEST['trade_no'];//平台的支付订单号
$order_amount= $_REQUEST['order_amount'];//商家订单金额
$product_number= $_REQUEST['product_number'];//商家商品数量
$order_success_time= $_REQUEST['order_success_time'];//订单支付成功时间
$order_time= $_REQUEST['order_time'];//商家订单时间
$order_status= $_REQUEST['order_status'];//订单支付状态
$bank_code= $_REQUEST['bank_code'];//支付渠道编码
$sign_type= $_REQUEST['sign_type'];//签名类型
$bank_name= $_REQUEST['bank_name'];//消费者支付渠道名称
$product_name= $_REQUEST['product_name'];//商品名称
$order_userid= $_REQUEST['order_userid'];//商户平台支付会员账号
$order_info= $_REQUEST['order_info'];//商户平台支付会员账号
$sign = $_REQUEST['sign'];//MD5或RSA签名,根据您请求签名模型生成的
/**参数列表end **/


/**-------------签名--------------- **/
$MARK = "~|~";
//商户签名秘钥
$key = "";
//拼成原始签名串
$initSign = $merchant_code.$MARK.$interface_version.$MARK.$order_no.$MARK.$trade_no.$MARK.$order_amount.$MARK
.$product_number.$MARK.$order_success_time.$MARK.$order_time.$MARK.$order_status.$MARK.$bank_code;
//验证签名串是否正确
$valiSign = false;

//根据您商户设置的签名模型自行选择对应的签名方式
if($sign_type == 'MD5') {
	//MD5商户签名秘钥
	$key="w5iHGtXB1DC4vyvYa885vAmoInpx7pVg";
    //生成MD5签名串和sign进行比较
	$notitysign = md5($initSign.$MARK.$key);
    if($notitysign == $sign){
    $valiSign = true;//验证签名通过
    }
}else if($sign_type == 'RSA'){//RSA签名模型
$sign = urldecode($sign);//同步跳转签名才解码:RSA模式签名字符串中有已被编码的URL特殊符号
$sign = base64_decode($sign);//RSA编码转换

/**
1)这里是<平台RSA公钥>，每个商家对应一个固定的平台RSA公钥（不是使用工具生成的密钥merchant_public_key.txt，不要混淆），
平台字符串公钥内容,请复制出来之后调成4行（换行位置任意，前面三行一定要<对齐>，第四行短一些）,如下demo平台公钥格式，
前后并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----" <注释后面必须换行>
2）使用平台的公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
$key='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCyt4xRBmwlYPK4SeYqc/hslRwA
9Q/tqMCsPm9xvXmPtmS4C8S5xCeliSPRIU34C27yNJczqRTDhsHdNChc1CHR+YpM
wZTgYk5H8nt+YyfmSwExEez4aL8KD6quHPu4fH522Drzg856FQFZuwzyZWcfG/L4
9/pboNX7kIQ2iWIofwIDAQAB
-----END PUBLIC KEY-----';//平台公钥
$key = openssl_get_publickey($key);
$valiSign = openssl_verify($initSign,$sign,$key,OPENSSL_ALGO_MD5);	
}
/**-------------签名end--------------- **/


if($valiSign){
	//------------订单支付成功，签名验证正确，开始处理你的平台逻辑,注意多次重复通知----------------
	echo "<script>alert('支付成功');</script>";
}else{
	echo "<script>alert('同步页面跳转验证签名失败');</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>同步页面跳转通知结果</title>
</head>
<body>
<form id="form1" method="get" name="form1">
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td height="30" align="center">
				<h1>
					※ 在线支付完成 ※
				</h1>
			</td>
		</tr>
	</table>
	<table bordercolor="#cccccc" cellspacing="5" cellpadding="0" width="400" align="center"
		border="0">		
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				商家订单号：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input  name='TransID' value= "<?php echo $order_no; ?>" />
				</td>
		</tr>
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				验证签名结果：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<?php if( $valiSign ){ ?>
			<input name='resultDesc' value="验证签名成功"/>
			<?php }else{ ?> 
			<input name='resultDesc' value="验证签名失败"/>
			<?php } ?> 
			</td>
		</tr>
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				交易成功金额：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input  name='FactMoney'  value= "<?php echo $order_amount; ?>"/>
				</td>
		</tr>
		<tr>		
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				订单附加消息：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input  name='additionalInfo' value= "<?php echo $order_info; ?>"/>
				</td>
		</tr>
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				交易成功时间：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input  name='SuccTime' value= "<?php echo $order_success_time; ?>"/>
				</td>
		</tr>		
	</table> 
</form>
</body>
</html>