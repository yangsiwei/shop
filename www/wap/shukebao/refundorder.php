<?php
session_start();
/**订单查询接口**/
var_dump($_SESSION['order_no']);
/**参数列表 **/
$merchant_code = "10020244";//商户号
$interface_version= "V1.0";//接口版本
$sign_type= "MD5";//签名类型-MD5或RSA(大写)
$order_no= "20170806114639";//商家唯一订单号
$ref_amount= "0.5";//退款金额（元）
$ref_desc= "订单API退款";//退款描述
$sign = "";//需要生成的签名串
/**参数列表end **/

/**签名 **/
$MARK = "~|~";
$payUrl="https://pay.shukenet.com/orefund/ref_order";//API退款订单请求地址
$key = "";//商户签名秘钥
//拼成原始签名串
$initSign = $merchant_code.$MARK.$interface_version.$MARK.$sign_type.$MARK.$order_no.$MARK.$ref_amount;
//根据您商户设置的签名模型自行选择对应的签名方式
if($sign_type == 'MD5') {
	
	//MD5签名秘钥
	$key="w5iHGtXB1DC4vyvYa885vAmoInpx7pVg";
    //生成MD5签名串
	$sign = md5($initSign.$MARK.$key);
	
}else if($sign_type == 'RSA'){//RSA签名模型

//商家自行生成的RSA私钥（打开 merchant_private_key.txt，将私钥内容 <不做任何改变的> 复制到变量引号中）
$key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAMrFdRYys1pZqqcA
5m0lUKPG/d3XwLUlq1qZ4ifuutquCRwESu29wFmUwG0egoVQ8toKIlCHcV9wSy3h
zkaOue63F+IlpDkNf7BKt+/GrUjyT946bs2He/BFI9jhxYrTw9MmEo2YhjQEZupM
vj28Ytf8lFl65nH2yOYUvZXIN/mBAgMBAAECgYEAwIiFCLLTgfKqCzDbmr9Xtmr4
GnEGVfqSndCH4QrY+VNO6v9Ydi06OtT3caUKobOfJFGDC5vPlqN1fvdteamD+Oe4
Mn7vpkXY0u0NnHBIaL6U6GebwTjimW9s3Qk8VhhK5AOt+L+638FAE4oFZ0i1kk69
iIe22fPcKWlgG1z5opECQQD7Th6QVFLbPAD7aGfJCB7QNNm7IfdjD/16NI/pNOTx
tyxF0u3qEuQwfRAOwFdBr0VWCwCd2YS3JGUf6zT7IstrAkEAzo845h3m7kHOQU4C
22n8aD/AK+LPPqcUkVjYjdQJVpFMn3Aqgq4X6T2bvC+fsexIbzagtByJSTo+ng/B
KSXVwwJBAJfVPwocqGLlAgLjtbD0QwmwpMw3XWxwwMkQ8NIJrzmLXihhpHUELPJO
3WDMPOvmpZGy3BCC13h/eMmsJjqFbzkCQCXo60xwmH2J3kzmAGg8n3KSoLZtPhQF
niS+5Z/CFqSoriNk9qhdQ04vXHHBW9HPx8uBfyUPR7ME2ZEw2wauRvkCQDeXj/qr
W8Mzy87hAXRHdMyOoLsbO/5snXDw0DJfKs0XNGZ0dGwCgw/hy/myDdH0VOhZW0Uk
VwL9UfgR/GfnONU=
-----END PRIVATE KEY-----';

$key= openssl_get_privatekey($key);//检查RSA私钥正确性
openssl_sign($initSign,$sign_info,$key,OPENSSL_ALGO_MD5);//生产签名
$sign = base64_encode($sign_info);//将签名内容进行编码，以便于http协议传输

}
/**签名End **/

//Post 查询订单
$json_msgcode = "";
$json_msg = "";
$json_merchant_code = "";
$json_order_no = "";
$json_trade_no = "";
$json_ref_amount = "";
$json_ref_time = "";
$json_ref_desc = "";


//封装请求参数
$post_data = array(  
'merchant_code' => $merchant_code,
'interface_version' => $interface_version,
'sign_type' => $sign_type,
'order_no' => $order_no,
'ref_amount' => $ref_amount,
'ref_desc' => $ref_desc,
'sign' => $sign,
); 
$postArray = http_post_data($payUrl, $post_data);//开始请求
$de_json = json_decode($postArray,true);//解析json
if(isset($de_json["msgcode"])){
$json_msgcode = $de_json["msgcode"];
}
if(isset($de_json["msg"])){
$json_msg = $de_json["msg"];
}
if(isset($de_json["merchant_code"])){
$json_merchant_code = $de_json["merchant_code"];
}
if(isset($de_json["order_no"])){
$json_order_no = $de_json["order_no"];
}
if(isset($de_json["trade_no"])){
$json_trade_no = $de_json["trade_no"];
}
if(isset($de_json["ref_amount"])){
$json_ref_amount = $de_json["ref_amount"];
}
if(isset($de_json["ref_time"])){
$json_ref_time = $de_json["ref_time"];
}
if(isset($de_json["ref_desc"])){
$json_ref_desc = $de_json["ref_desc"];
}
/** 
 * 发送post请求
 * @param string $url 请求地址 
 * @param array $post_data post键值对数据 
 * @return json 
 */  
function http_post_data($url, $post_data) {  
      $postdata = http_build_query($post_data);    
      $options = array(    
            'http' => array(    
                'method' => 'POST',    
                'header' => 'Content-type:application/x-www-form-urlencoded',    
                'content' => $postdata,    
                'timeout' => 15 * 60
            )    
        );    
        $context = stream_context_create($options);    
        $result = file_get_contents($url, false, $context);             
        return $result;     
}   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>支付订单查询接口-提交</title>
</head>
<body>
   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td height="30" align="center">
				<h1>
					※ 订单查询结果 ※
				</h1>
			</td>
		</tr>
	</table>
<table bordercolor="#cccccc" cellspacing="5" cellpadding="0" width="400" align="center"
		border="0">		
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				msgcode：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input value= "<?php echo $json_msgcode; ?>" />
			</td>
		</tr>
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				msg：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input value= "<?php echo $json_msg; ?>" />
			</td>
		</tr>
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				merchant_code：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input value= "<?php echo $json_merchant_code; ?>" />
			</td>
		</tr>
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				order_no：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input value= "<?php echo $json_order_no; ?>" />
			</td>
		</tr>
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				trade_no：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input value= "<?php echo $json_trade_no; ?>" />
			</td>
		</tr>
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				ref_amount：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input value= "<?php echo $json_ref_amount; ?>" />
			</td>
		</tr>
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				ref_time：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input value= "<?php echo $json_ref_time; ?>" />
			</td>
		</tr>
		<tr>
			<td class="text_12" bordercolor="#ffffff" align="right" width="150" height="20">
				ref_desc：</td>
			<td class="text_12" bordercolor="#ffffff" align="left">
			<input value= "<?php echo $json_ref_desc; ?>" />
			</td>
		</tr>
</table>
</body>
</html>
