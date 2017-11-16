<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



//ignore_user_abort(true);
set_time_limit(0);
define("FILE_PATH",""); //文件目录，空为根目录
require_once './system/system_init.php';

//计划任务
$auth = trim($_POST['auth']);

require_once APP_ROOT_PATH.'/system/libs/crypt_aes.php';
$aes = new CryptAES();
$aes->set_key(FANWE_AES_KEY);
$aes->require_pkcs5();

$decString = $aes->decrypt($auth);
$data = json_decode($decString,1);
if($data['key']!=FANWE_APP_ID)
	die("auth error");
else
	$type = $data['type'];


global $schedule_data;




$GLOBALS['db']->query("start transaction");
$schedule_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."schedule_list where type='".$type."' and exec_status in (0,1) and exec_lock = 0 and  schedule_time <=".NOW_TIME." order by schedule_time asc limit 1");
if($schedule_data['id'])
{
	$GLOBALS['db']->query("update ".DB_PREFIX."schedule_list set exec_lock = 1,lock_time=".NOW_TIME." where id = '".$schedule_data['id']."'");
	$affected_rows = $GLOBALS['db']->affected_rows();
	if($affected_rows>0)
	{
		if($schedule_data)
			$res = exec_schedule_plan($schedule_data);			
	}
	if($res)
	{
		$GLOBALS['db']->query("commit");
	}
	else
	{
		$GLOBALS['db']->query("rollback");
	}
}
else
{
	$GLOBALS['db']->query("rollback");
}

$result = array("type"=>$type,"time"=>to_date(NOW_TIME));

ajax_return($result);


?>