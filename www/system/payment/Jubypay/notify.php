<?php

include 'jubaopay/jubaopay.php';

$message=$_POST["message"];
$signature=$_POST["signature"];

$jubaopay=new jubaopay('jubaopay/jubaopay.ini');

$jubaopay->decrypt($message);
// 校验签名，然后进行业务处理
$result=$jubaopay->verify($signature);
if($result==1) {
   // 得到解密的结果后，进行业务处理
   // echo "payid=".$jubaopay->getEncrypt("payid")."<br />";
   // echo "mobile=".$jubaopay->getEncrypt("mobile")."<br />";
   // echo "amount=".$jubaopay->getEncrypt("amount")."<br />";
   // echo "remark=".$jubaopay->getEncrypt("remark")."<br />";
   // echo "orderNo=".$jubaopay->getEncrypt("orderNo")."<br />";
   // echo "state=".$jubaopay->getEncrypt("state")."<br />";
   // echo "partnerid=".$jubaopay->getEncrypt("partnerid")."<br />";
   // echo "modifyTime=".$jubaopay->getEncrypt("modifyTime")."<br />";
	echo "success"; // 像服务返回 "success"
} else {
	echo "verify failed";
}

?>