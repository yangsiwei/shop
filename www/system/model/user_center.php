<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

//会员中心的函数库

//查询会员日志
function get_user_log($limit,$user_id,$t='')
{
	if(!in_array($t,array("money","score","point")))
	{
		$t = "";
	}
	if($t=='')
	{
		$condition = "";
	}
	else
	{
		$condition = " and ".$t." <> 0 ";
	}

	$user_id = intval($user_id);
	$list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_log where user_id = ".$user_id." $condition order by id desc limit ".$limit);
	foreach ($list as $k=>$v){
//	    $list[$k]['flog_time'] = to_date($v['log_time']);
	    $list[$k]['flog_time'] = date('Y-m-d H:i:s',$v['log_time']);

	}
	$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_log where user_id = ".$user_id." $condition");
	return array("list"=>$list,'count'=>$count);
}

//查询会员充值订单
function get_user_incharge($limit,$user_id)
{
	$user_id = intval($user_id);
	require_once APP_ROOT_PATH."system/model/deal_order.php";
	$order_table_name = get_user_order_table_name($user_id);
	$list = $GLOBALS['db']->getAll("select * from ".$order_table_name." where user_id = ".$user_id." and type = 1 and is_delete = 0 and pay_status = 2 order by create_time desc limit ".$limit);
	$count = $GLOBALS['db']->getOne("select count(*) from ".$order_table_name." where user_id = ".$user_id." and type = 1 and is_delete = 0 and pay_status = 2");
	foreach($list as $k=>$v)
	{
		$list[$k]['payment_notice'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where order_id = ".$v['id']);
		$list[$k]['payment'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$v['payment_id']);
	}
	return array("list"=>$list,'count'=>$count);
}


function get_user_withdraw($limit,$user_id)
{
	$user_id = intval($user_id);
	$list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."withdraw where user_id = ".$user_id." and is_delete = 0 order by create_time desc limit ".$limit);
	foreach($list as $k=>$v)
	{
		$bank_account_end = substr($v['bank_account'],-4,4);
		$bank_account_show_length = strlen($v['bank_account']) - 4;
		$bank_account = "";
		for($i=0;$i<$bank_account_show_length;$i++)
		{
			$bank_account.="*";
		}
		$bank_account.=$bank_account_end;
		$list[$k]['bank_account'] =  $bank_account;
		
		$bank_user_end = msubstr($v['bank_user'],-1,1,"utf-8",false);
		$bank_user_show_length = mb_strlen($v['bank_user'],"utf-8")-1;
		$bank_user = "";
		for($i=0;$i<$bank_user_show_length;$i++)
		{
			$bank_user.="*";
		}
		$bank_user.=$bank_user_end;
		$list[$k]['bank_user'] =  $bank_user;
	}
	$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."withdraw where user_id = ".$user_id." and is_delete = 0");

	return array("list"=>$list,'count'=>$count);
}

?>