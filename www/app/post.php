<?php
/**参数列表 **/
$merchant_code = "10013646";//商户号
$service_type = 'local_service';//请求服务模式
$pay_model = "SCODE";//支付请求模型
$bank_code = $_GET['bank_code'];//网关支付渠道编码
$interface_version = "V1.0";//接口版本
$sign_type = "MD5";//签名类型
$order_no = $_GET['order_no'];//商家订单号
$order_time = date("y-M-d H:m:s",$_GET['order_time']);//商家订单时间
$order_amount = $_GET['money'];//商家订单金额(元)
$product_number = 1;//商家商品数量
$notify_url = "https://www.aliduobaodao.com/wap/index.php?ctl=skb_notify&show_prog=1";//商家异步通知地址
$return_url = "https://www.aliduobaodao.com/index.php?ctl=uc_money&act=incharge";//商家前台同步跳转地址
$product_name = "普惠宝";//商家商品名称
$order_userid = $_GET['order_userid'];//商户平台会员账号
$order_info= "";//商户商品附加信息
$notice_type= 0;//商户通知类型
$sign = "";//需要生成的签名串
/**参数列表end **/




/**签名 **/
$MARK = "~|~";
$payUrl="https://pay.shukenet.com/upay/gateway";//API支付Post请求地址
$key = "sNPuofT8SxQCJNuBHisgidQVfqSDkaD7";//商户签名秘钥
//拼成原始签名串
$initSign = $merchant_code.$MARK.$interface_version.$MARK.$sign_type.$MARK.$order_no.$MARK.$order_time.$MARK.$order_amount.$MARK.$product_number.$MARK.$notify_url.$MARK.$return_url.$MARK.$bank_code.$MARK.$notice_type.$MARK.$service_type;
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

/** 本地模式获取二维码到自己网站上显示: service_type参数值为local_service时，特殊处理>>>服务器请求 **/
$msgcode = "";//请求返回的标识代码
$msg = "";//请求返回的提示消息
$qrcode = "";//二维码信息，利用 jquery qrcode 来转换成图片显示， 拿到二维码后，支付成功会异步底层通知你url,更新你系统数据库支付状态,前台您可以做 定时扫描 数据库状态, 提示支付成功了或跳转。
if($service_type == 'local_service') {
	//封装请求参数
	$post_data = array(  
      'service_type' => $service_type,  
      'merchant_code' => $merchant_code,
	  'interface_version' => $interface_version,
	  'sign_type' => $sign_type,
	  'order_no' => $order_no,
	  'order_time' => $order_time,
	  'order_amount' => $order_amount,
	  'product_number' => $product_number,
	  'notify_url' => $notify_url,
	  'return_url' => $return_url,
	  'bank_code' => $bank_code,
	  'product_name' => $product_name,
	  'order_userid' => $order_userid,
	  'order_info' => $order_info,
	  'notice_type' => $notice_type,
	  'pay_model' => $pay_model,
	  'sign' => $sign,
    ); 
   $postArray = http_post_data($payUrl, $post_data);//开始请求
   $de_json = json_decode($postArray,true);//解析json
   $msg = $de_json["msg"];
   $msgcode = $de_json["msgcode"];
   if( isset($de_json["qrcode"]) ){
   $qrcode = $de_json["qrcode"];//注意：错误情况json返回的无qrcode key,判断null为宜
   }
//   echo $msg;
//   echo $msgcode;
//   echo $qrcode;
}

/** 
 * 发送post请求（service_type等于local_service使用）
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
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<title>支付接口-提交</title>

</head>
<body style="background:#9c9c9c;">
<?php if('local_service' == $service_type){//本地模式 ?>

<?php if( $msgcode == 'success' ){//没有得到二维码信息，不加载二维码 ?>

<script src="./jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="./jquery.qrcode.min.js" type="text/javascript"></script>

<?php }else{ ?>
    <script>
//        window.location.href="https://www.aliduobaodao.com/index.php?ctl=uc_money&act=incharge";
    </script>
<?php } ?>

    <div class="container" style="background:#fff;">
        <div class="jumbotron text-center">
            <p>支付金额</p>
            <h1 style="color:#FF6600;">¥<?php echo $order_amount ?></h1>
            <?php
            if($bank_code == 'ALIPAY'){
                echo "<img src=\"../image/zhifubao.jpg\" alt=\"\" style=\"width:150px;height:45px;\">";
            }else{
                echo "<img src=\"../image/weixin.jpg\" alt=\"\" style=\"width:150px;height:45px;\">";
            }
            ?>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <div id="qrcode" style="width:70%;margin-left:15%;margin-top:20px;"></div>
                    <div class="caption text-center">
                        <h3>扫码支付</h3>
                        <p>请扫打开<?php if($bank_code == 'ALIPAY'){echo '支付宝';}else{echo '微信';} ?>描上面的二维码进行付款</p>
                        <p>
                            <a href="https://www.aliduobaodao.com/index.php" class="btn btn-warning" role="button">返回首页</a>
                            <a href="https://www.aliduobaodao.com/index.php?ctl=uc_money&act=incharge" class="btn btn-success" role="button">继续充值</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-danger text-center" role="alert"></div>
    </div>


<!--    <div id="qrcode"></div><br/>-->
<!--    <div id="message">请求结果：--><?php //echo $msg; ?><!--</div>-->








<?php }else{ ?>

<?php } ?>

<script>
    jQuery(function(){
        jQuery('#qrcode').qrcode("<?php echo $qrcode; ?>");
    });
</script>
</body>
</html>
