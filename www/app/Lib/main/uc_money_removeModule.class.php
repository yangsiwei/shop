<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

require_once APP_ROOT_PATH.'system/model/user.php';
class uc_money_removeModule extends MainBaseModule
{
    public function index(){
	    global_run();
	    if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}
		init_app_page();
		assign_uc_nav_list();//左侧导航菜单

	    // var_dump($GLOBALS['user_info']);
    	$GLOBALS['tmpl']->display("uc/uc_money_remove.html");
    }

    public function remove_done(){
    	var_dump($_POST);die;
    	global_run();
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			$data['status'] = 1000;
			ajax_return($data);
		}

		$money = floatval($_POST['num']);
		$mobile = $GLOBALS['user_info']['mobile'];
		$sms_verify = strim($_REQUEST['sms_verify']);


		if($money<=0)
		{
			$data['status'] = 0;
			$data['info'] = "请输入正确的金额";
			ajax_return($data);
		}

		if($sms_verify){
			//验证是否有绑定手机
			if($mobile=="")
			{
				$data['status'] = 0;
				$data['info'] = "请先完善会员的手机号码";
				$data['jump'] = url("index","uc_account");
				ajax_return($data);
			}
			//验证手机验证码是否正确
			if($sms_verify=="")
			{
				$data['status'] = 0;
				$data['info']	=	"请输入收到的验证码";
				ajax_return($data);
			}
			//短信码验证
			$sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
			$GLOBALS['db']->query($sql);
		
			$mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$mobile."'");
		
			if($mobile_data['code']!=$sms_verify)
			{
				$data['status'] = 1;
				$data['info']	=  "验证码错误";
				ajax_return($data);
			}
		}




		if($_POST['remove_method'] == "fx_money"){
			if($money>$GLOBALS['user_info']['fx_money']){
				$data['status'] = 0;
				$data['info'] ="申请转移推广奖大于推广奖余额，请重新输入";
				ajax_return($data);
			}
		}
		if($_POST['remove_method'] == "give_money"){
			if($money>$GLOBALS['user_info']['can_use_give_money']){
				$data['status'] = 0;
				$data['info'] ="申请转移充值赠送金额大于赠送余额，请重新输入";
				ajax_return($data);
			}
		}if($_POST['remove_method'] == "admin_money"){
			if($money>$GLOBALS['user_info']['admin_money']){
				$data['status'] = 0;
				$data['info'] ="申请转移管理奖大于管理奖余额，请重新输入";
				ajax_return($data);
			}
		}



    }

}