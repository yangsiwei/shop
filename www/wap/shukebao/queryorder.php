<?php
/**订单查询接口**/

/**参数列表 **/
$merchant_code = "10013646";//商户号
$interface_version= "V1.0";//接口版本
$sign_type= "MD5";//签名类型-MD5或RSA(大写)
$order_no= $_GET['order_no'];//商家唯一订单号
$sign = "";//需要生成的签名串
/**参数列表end **/
/**签名 **/
$MARK = "~|~";
$payUrl="https://pay.shukenet.com/qpay/queryorder";//API订单查询请求地址
$key = "";//商户签名秘钥
//拼成原始签名串
$initSign = $merchant_code.$MARK.$interface_version.$MARK.$sign_type.$MARK.$order_no;
//根据您商户设置的签名模型自行选择对应的签名方式
if($sign_type == 'MD5') {
	
	//MD5签名秘钥
	$key="sNPuofT8SxQCJNuBHisgidQVfqSDkaD7";
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
$json_interface_version = "";
$json_order_no = "";
$json_order_time = "";
$json_order_amount = "";
$json_trade_no = "";
$json_trade_time = "";
$json_trade_status = "";
$json_product_number = "";
$json_order_userid = "";
$json_order_info = "";
$json_bank_code = "";
$json_bank_name = "";
//封装请求参数
$post_data = array(  
'merchant_code' => $merchant_code,
'interface_version' => $interface_version,
'sign_type' => $sign_type,
'order_no' => $order_no,
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
if(isset($de_json["interface_version"])){
$json_interface_version = $de_json["interface_version"];
}
if(isset($de_json["order_no"])){
$json_order_no = $de_json["order_no"];
}
if(isset($de_json["order_time"])){
$json_order_time = $de_json["order_time"];
}
if(isset($de_json["order_amount"])){
$json_order_amount = $de_json["order_amount"];
}
if(isset($de_json["trade_no"])){
$json_trade_no = $de_json["trade_no"];
}
if(isset($de_json["trade_time"])){
$json_trade_time = $de_json["trade_time"];
}
if(isset($de_json["trade_status"])){
$json_trade_status = $de_json["trade_status"];
}
if(isset($de_json["product_number"])){
$json_product_number = $de_json["product_number"];
}
if(isset($de_json["order_userid"])){
$json_order_userid = $de_json["order_userid"];
}
if(isset($de_json["order_info"])){
$json_order_info = $de_json["order_info"];
}
if(isset($de_json["bank_code"])){
$json_bank_code = $de_json["bank_code"];
}
if(isset($de_json["bank_name"])){
$json_bank_name = $de_json["bank_name"];
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
    <link rel="stylesheet" type="text/css" href="../Tpl/main/css/tb/iconfont.css">
<title>支付结果查询</title>
    <style>
        .header{
            width: 100%;
            height: 10%;
            background: #e9ede5;
            position: fixed;
            top:0;
            left:0;
        }
        a{
            text-decoration: none;
        }
        .title_nav{
            margin-left: 25%;
            font-size: 38px;
            color: #d93a55;
        }
        .res{
            position: fixed;
            top: 17%;
            width: 60%;
            height: 35%;
            left: 20%;
        }
        .res>p{
            color: #d73c3c;
            font-size: 47px;
            text-align: center;
        }
        .res>button{
            width: 50%;
            height: 17%;
            font-size: 36px;
            background: #f23c5a;
            border: none;
            border-radius: 19px;
            color: #f3f9f3;
            margin-left: 25%;
            margin-top: 20%;
        }
    </style>
</head>
<body>
<div class="header">
</div>
    <div class="header">
        <a href="http://122.114.94.153/wap/index.php?ctl=uc_money&act=balance&show_prog=1">
            <span class="iconfont" style="font-size:49px;color:#d93a55;line-height:257%;    margin-left: 5%;">&#xe61e;</span>
        </a>
        <span class="title_nav">支付结果查询</span>

        <a href="http://122.114.94.153/wap/index.php?show_prog=1">
            <i class="iconfont" style="font-size: 60px;color: #d93a55;line-height: 229%;float: right;margin-right: 5%;">&#xe62b;</i>
        </a>
    </div>

    <div class="res">
        <?php
        if($json_msgcode != '00000'){
            echo "<p>$json_msg</p>";
        }
        if($json_trade_status == 'SUCCESS'){
                echo "<p>支付成功</p>";
        }else{
            echo "<p>未完成支付</p>";
        }
        ?>
        <button><a href="http://122.114.94.153/wap/index.php?ctl=user_center&show_prog=1" style="color:#f3f9f3;">
                <div style="width:100%;height:100%;line-height: 207%;">
                    确定
                </div>
            </a> </button>
    </div>


</body>
</html>
