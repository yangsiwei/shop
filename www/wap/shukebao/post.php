<?php
session_start();
/**参数列表 **/
$merchant_code = "10013646";//商户号
$service_type = 'connect_service';//请求服务模式
$pay_model = "H5";//支付请求模型
$bank_code = $_GET['bank_code'];//网关支付渠道编码
$interface_version = "V1.0";//接口版本
$sign_type = "MD5";//签名类型
$order_no = $_GET['order_no'];//商家订单号
$order_time = date("y-M-d H:m:s",$_GET['order_time']);//商家订单时间
$order_amount = $_GET['money'];//商家订单金额(元)
$product_number = 1;//商家商品数量
$notify_url = "https://www.aliduobaodao.com/wap/index.php?ctl=skb_notify&show_prog=1";//商家异步通知地址
$return_url = "https://www.aliduobaodao.com/wap/index.php?ctl=uc_money&act=balance&show_prog=1";//商家前台同步跳转地址
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
<title>支付接口-提交</title>

    <link rel="stylesheet" type="text/css" href="../Tpl/main/css/tb/iconfont.css">
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
        .is_pay{
            width: 65%;
            height: 22%;
            border: 1px solid #e6e6e6;
            position: fixed;
            top: 30%;
            left: 19%;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 40px #999;
        }
        .is_pay>p{
            text-align: center;
            color: #f66709;
            font-weight: bold;
            font-size: 43px;
            margin-top: 11%;
        }
        .is_pay>button{
            width: 32%;
            height: 26%;
            margin-left: 10%;
            margin-top: 2%;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size:33px;
        }
        .is_pay_bgc{
            width: 100%;
            height: 100%;
            position: fixed;
            background: #000;
            top: 0;
            opacity: 0.4;
            -moz-opacity: 0.4;
            filter: alpha(Opacity=80);
        }
    </style>
</head>
<body>
<div id="tishi">
    <div class="header">
        <a href="https://www.aliduobaodao.com/wap/index.php?ctl=uc_charge&show_prog=1">
            <i class="iconfont" style="font-size:49px;color:#d93a55;line-height:257%;    margin-left: 5%;">&#xe601;</i>
        </a>
        <span class="title_nav">充值跳转...</span>

        <a href="https://www.aliduobaodao.com/wap/index.php?show_prog=1">
            <i class="iconfont" style="font-size: 60px;color: #d93a55;line-height: 229%;float: right;margin-right: 5%;">&#xe62b;</i>
        </a>
    </div>
    <!--<div class="is_pay_bgc"></div>-->
    <div class="is_pay">
        <p>是否完成支付</p>
        <button style="margin-left: 14%;background: #1d71e5;color: #fff;"> <a href="https://www.aliduobaodao.com/wap/shukebao/queryorder.php?order_no=<?php echo $order_no; ?>">
                <div style="width:100%;height:100%;line-height: 221%;">确定</div>
            </a> </button>
        <button style="background:#e5910f;"> <a href="https://www.aliduobaodao.com/wap/index.php?ctl=uc_charge&show_prog=1">
                <div style="width:100%;height:100%;line-height: 221%;">取消</div>
            </a> </button>
    </div>

</div>

<!---->
<?php ////if('local_service' == $service_type){//本地模式 ?>
<!---->
<!--    <div id="qrcode"></div><br/>-->
<!--    <div id="message">请求结果：--><?php //echo $msg; ?><!--</div>-->
<?php //if( $msg == 'success' ){//没有得到二维码信息，不加载二维码 ?>
<!---->
<!--    <script src="http://--><?php //echo $_SERVER['HTTP_HOST']; ?><!--/phpDemo/js/jquery-1.7.2.min.js" type="text/javascript"></script>-->
<!--    <script src="http://--><?php //echo $_SERVER['HTTP_HOST']; ?><!--/phpDemo/js/jquery.qrcode.min.js" type="text/javascript"></script>-->
<!--    <script>-->
<!--        jQuery(function(){-->
<!--            jQuery('#qrcode').qrcode("--><?php //echo $qrcode; ?>//");
//        });
<!--//    </script>-->
<?php //} ?>
<!---->
<!---->
<!---->
<?php //}else{ ?>


<!--提交from表单方式-->
<form method="get" name="pay" id="pay" action="<?php echo $payUrl; ?>">
<table>
<tr>
	<td>
	<input name='service_type' type='hidden' value="<?php echo $service_type; ?>"/>
    <input name='merchant_code' type='hidden' value="<?php echo $merchant_code; ?>"/>
    <input name='interface_version' type='hidden' value="<?php echo $interface_version; ?>"/>
    <input name='sign_type' type='hidden' value="<?php echo $sign_type; ?>"/>
    <input name='order_no' type='hidden' value="<?php echo $order_no; ?>"/>
    <input name='order_time' type='hidden' value="<?php echo $order_time; ?>"/>
    <input name='order_amount' type='hidden' value="<?php echo $order_amount; ?>"/>
    <input name='product_number' type='hidden' value="<?php echo $product_number; ?>"/>
    <input name='notify_url' type='hidden' value="<?php echo $notify_url; ?>"/>
    <input name='return_url' type='hidden' value="<?php echo $return_url; ?>"/>
    <input name='bank_code' type='hidden' value="<?php echo $bank_code; ?>"/>
    <input name='product_name' type='hidden' value="<?php echo $product_name; ?>"/>
    <input name='order_userid' type='hidden' value="<?php echo $order_userid; ?>"/>
    <input name='order_info' type='hidden' value="<?php echo $order_info; ?>"/>
    <input name='notice_type' type='hidden' value="<?php echo $notice_type; ?>"/>
    <input name='sign' type='hidden' value="<?php echo $sign; ?>"/>
    <input name='pay_model' type='hidden' value="<?php echo $pay_model; ?>"/>
	</td>
</tr>
</table>
</form>

<script type="text/javascript">

		function subpay(){
			document.pay.submit();
		}
		//设置等待1秒提交from，防止Post提交太快里面的数据丢失;
		//等待1秒提交,页面会空白1秒，请接入者自行在本页中设计增加等待的之类的图片作为显示渲染;


        function setCookie(cname,cvalue,exdays)
        {
            var d = new Date();
            d.setTime(d.getTime()+(exdays*24*60*60*1000));
            var expires = "expires="+d.toGMTString();
            document.cookie = cname + "=" + cvalue + "; " + expires;
        }

        function getCookie(cname)
        {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++)
            {
                var c = ca[i].trim();
                if (c.indexOf(name)==0) return c.substring(name.length,c.length);
            }
            return "";
        }

        function checkCookie()
        {
            var order_no_now = <?php echo $order_no ?>;
            var obj = document.getElementById("tishi");
            var order_no=getCookie("order_no");
            if (order_no == order_no_now)
            {
                obj.style.display= "block";
            }
            else
            {
                obj.style.display= "none";
                setCookie("order_no",order_no_now,1);
                setTimeout(subpay,100);
            }
        }
        checkCookie();

        //      subpay();

        //		setTimeout(subpay,100);


//		$(function(){
//            $(".yes").click(function(){
//                window.location.href="https://www.aliduobaodao.com/wap/shukebao/refundorder.php";
//            });
//            $(".no").click(function(){
//                window.location.href="https://www.aliduobaodao.com/wap/index.php?ctl=uc_charge&show_prog=1";
//            });
//        });
</script>

<!--提交from表单方式-->


<?php //} ?>
<?php
//$_SESSION['order_no'] = $order_no;  // 把username存在$_SESSION['user'] 里面
////echo $_SESSION['order_no'];          // 直接输出 username
//?>

</body>
</html>
