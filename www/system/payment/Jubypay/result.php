<?php
include APP_ROOT_PATH."system/payment/Jubypay/jubaopay/jubaopay.php";

$jubaopay=new jubaopay(APP_ROOT_PATH."system/payment/Jubypay/jubaopay/jubaopay.ini");

$message=$_GET["message"];
$signature=$_GET["signature"];

$jubaopay->decrypt($message);
// 校验签名，然后进行业务处理
$result=$jubaopay->verify($signature);

if($result == 1) {
    $payid = $jubaopay->getEncrypt("payid");
    $mobile = $jubaopay->getEncrypt("mobile");
    $amount = $jubaopay->getEncrypt("amount");
    $remark = $jubaopay->getEncrypt("remark");
    $orderNo = $jubaopay->getEncrypt("orderNo");
    $state = $jubaopay->getEncrypt("state");
    $modifyTime = $jubaopay->getEncrypt("modifyTime");
    $partnerid = $jubaopay->getEncrypt("partnerid");
    $realReceive = $jubaopay->getEncrypt("realReceive");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>聚宝支付-让支付更简单</title>
	<link href="http://www.jubaopay.com/resources/new/css/api_base.css" rel="stylesheet" type="text/css" />
	<link href="http://www.jubaopay.com/resources/new/css/api_main.css" rel="stylesheet" type="text/css" />
	<link href="http://www.jubaopay.com/resources/new/css/api_trading.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="http://www.jubaopay.com/libs/jquery.min.js"></script>
	<script type="text/javascript" src="http://www.jubaopay.com/resources/new/js/j_validate.js"></script>
	<script language="javascript" src="http://www.jubaopay.com/resources/new/js/api_pay.js"></script>
	<script language="javascript" src="http://www.jubaopay.com/resources/new/js/api_dialog.js"></script>
	<link href="http://www.jubaopay.com/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header-container">
  <div class="header-content">
	  <div class="logo-new fll"><a href="http://www.jubaopay.comjavascript:void(0);" title="聚宝支付-让支付更简单" target="_blank"><h1>聚宝支付-让支付更简单</h1></a></div>
    </div>
</div><div class="header-Line"></div>
<div id="all-main-container">
<div class="pay-nav">
    <div class="pay-info-wrap">
        <div class="pay-info f14 clearfix">
	        <div class="L" title="testJ4uaVi1Z">聚宝支付DEMO页面</div>
        </div>
    </div>
</div>
<div class="main clearfix">
    <div class="api-back-res">
		<form method="post" action="/demo3.php">
			<table>
				<tr>
					<td>payid:(商户的订单号)</td>
					<td><?php echo $payid;?></td>
				</tr>
				<tr>
					<td>partnerid:(商户号)</td>
					<td><?php echo $partnerid;?></td>
				</tr>
				<tr>
					<td>amount:(支付成功的金额，单位元，精确到分)</td>
					<td><?php echo $amount;?></td>
				</tr>
				<tr>
					<td>remark:(备注)</td>
					<td><?php echo $remark;?></td>
				</tr>
				<tr>
					<td>orderNo:(聚宝支付的订单号)</td>
					<td><?php echo $orderNo;?></td>
				</tr>
				<tr>
					<td>state:(2:表示支付成功，非2都是失败)</td>
					<td><?php echo $state;?></td>
				</tr>
				<tr>
					<td>modifyTime:(支付成功的时间)</td>
					<td><?php echo $modifyTime;?></td>
				</tr>
				<tr>
					<td>modifyTime:(实际到帐金额)</td>
					<td><?php echo $realReceive;?></td>
				</tr>
			</table>
		</form>
    </div>
</div>
</div>
<div class="footer-container">
  <div class="footer-content">杭州凡伟网络科技有限公司©2014 浙ICP备12040171号&nbsp;&nbsp;
</div>
</div>
</body>
</html>