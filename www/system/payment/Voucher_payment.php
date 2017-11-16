<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'代金券支付',
	'ecvsn'	=>	'序列号',
	'ecvpassword'	=>	'密码',
	'verify'	=>	'验证',
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Voucher';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '0';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
     $module['reg_url'] = '';
    return $module;
}

// 余额支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Voucher_payment implements payment {
	public function get_payment_code($payment_notice_id)
	{
		return false;		
	}
	
	public function response($request)
	{
		return false;
	}
	
	public function notify($request)
	{
		return false;
	}
	
	public function get_display_code($str=array(),$total_money=0)
	{
		$sql = "select e.sn as sn,e.is_all as is_all,e.data as data,t.name as name from ".DB_PREFIX."ecv as e left join ".DB_PREFIX."ecv_type as t on e.ecv_type_id = t.id where ".
				" e.user_id = '".$GLOBALS['user_info']['id']."' and (e.begin_time < ".NOW_TIME.") and (e.end_time = 0 or e.end_time > ".NOW_TIME.") ".
				" and (e.use_limit = 0 or e.use_count<e.use_limit)";
		$voucher_list = $GLOBALS['db']->getAll($sql);
		
		$html = "<table><tr><td>红包支付：</td><td><select name='ecvsn' class='ui-select voucher_select'><option value=''>=不使用红包=</option>";
		
		$use_all=array();
		// 统计哪些红包可以用 1	P K区 2十元区 3百元区 4直购区 5极速区 6选号区 7一元区
		foreach ($voucher_list as $key=>$value){
		    // 判断data 里面的数据，在订单专区里面是否有
		    if($value['is_all']==1){
		        $use_all[$key] =  $value;
		    }else{
		        if($value['data']){
		            $json_data = json_decode($value['data'], 1);
		            foreach($str as $k => $v){
		                if(in_array($v,$json_data['domain'])){
		                    $use_all[$key] =  $value;
		                }
		            }
		        }
		
		    }
		}
		$voucher_list=$use_all;
		foreach($voucher_list as $k=>$v)
		{
			$html.="<option value='".$v['sn']."'>".$v['name']."</option>";
		}
		
		$html.="</select></td></tr></table>";
// 		$html = "<span style='display:block;'><div class='f_l'  style='margin-right:5px; height:40px; '>代金券支付：序列号".
// 				"：<input type='text' value='' style='margin-bottom:-5px;' maxlength='20' name='ecvsn' class='ui-textbox' />&nbsp;密码".				
// 				"：<input type='password' value='' style='margin-bottom:-5px;' maxlength='20' name='ecvpassword' class='ui-textbox' />&nbsp;".
// 				"</div><button class='ui-button f_l' rel='white' type='button' id='verify_ecv' >验证</button></span>";				
		return $html;
	}
	
	// 直接支付
	// 修正订单pay_amount,ecv_money,ecv_sn的值， 修改payment_notice要应的is_paid为1
	public function direct_pay($ecv_sn,$ecv_password,$payment_notice_id)
	{
	    $now_time = NOW_TIME;
	    
	    $GLOBALS['db']->query("update ".DB_PREFIX."ecv set use_count = use_count + 1 where (begin_time <= ".$now_time." or begin_time=0) and (end_time > ".$now_time." or end_time=0) and (use_limit=0 or use_limit > use_count) and sn = '".$ecv_sn."'");
		if($GLOBALS['db']->affected_rows()>0)
		{
		    $rs = payment_paid($payment_notice_id);
		}	
	}
}
?>