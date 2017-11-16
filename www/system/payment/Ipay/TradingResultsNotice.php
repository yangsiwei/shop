<?php
header("Content-type: text/html; charset=utf-8");
/*
 * Created on 2015-9-1
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once ("base.php");
require_once ("IpayBase.class.php");
class IpayTradingResultsNotice extends IpayBase{
    function TradingResultsNotice() {
    	$string = $_POST;//接收post请求数据
    	if($string ==null){
    		echo "请使用post方式提交数据";
    	}else{
			$transdata=$string['transdata'];
		 
			if(stripos("%22",$transdata)){ //判断接收到的数据是否做过 Urldecode处理，如果没有处理则对数据进行Urldecode处理
				$string= array_map ('urldecode',$string);
			}
            $respData = 'transdata='.$string['transdata'].'&sign='.$string['sign'].'&signtype='.$string['signtype'];//把数据组装成验签函数要求的参数格式
			//  验签函数parseResp（） 中 只接受明文数据。数据如：transdata={"appid":"3003686553","appuserid":"10123059","cporderid":"1234qwedfq2as123sdf3f1231234r","cpprivate":"11qwe123r23q232111","currency":"RMB","feetype":0,"money":0.12,"paytype":403,"result":0,"transid":"32011601231456558678","transtime":"2016-01-23 14:57:15","transtype":0,"waresid":1}&sign=jeSp7L6GtZaO/KiP5XSA4vvq5yxBpq4PFqXyEoktkPqkE5b8jS7aeHlgV5zDLIeyqfVJKKuypNUdrpMLbSQhC8G4pDwdpTs/GTbDw/stxFXBGgrt9zugWRcpL56k9XEXM5ao95fTu9PO8jMNfIV9mMMyTRLT3lCAJGrKL17xXv4=&signtype=RSA
			 
			if(!parseResp($respData, $this->platpkey, $respJson)) {
				//验签失败
				return false;
			}else{
			    //验签成功
			    $transdata=$string['transdata'];
			    $arr=json_decode($transdata, true);
			    return $arr;
			    
			    
			    
			    
				/*
				以下是 验签通过之后 对数据的解析。
				$appid=$arr->appid;
				$appuserid=$arr->appuserid;
				$cporderid=$arr->cporderid;
				$cpprivate=$arr->cpprivate;
				$money=$arr->paytype;
				$result=$arr->result;
				$transid=$arr->transid;
				$transtime=$arr->transtime;
				$waresid=$arr->waresid;
				*/
				 
			}
    	}
			
	 }
}
?>
