<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class IndexAction extends AuthAction{
	//首页
    public function index(){
		$this->display();
    }
    

    //框架头
	public function top()
	{
		$navs = require_once APP_ROOT_PATH."system/adm_cfg/".APP_TYPE."/admnav_cfg.php";	
		if(OPEN_WEIXIN)
		{
			$config_file = APP_ROOT_PATH."system/adm_cfg/".APP_TYPE."/wxadmnav_cfg.php";
			$navs = array_merge_admnav($navs, $config_file);
		}
		if(OPEN_FX)
		{
			$config_file = APP_ROOT_PATH."system/adm_cfg/".APP_TYPE."/fxadmnav_cfg.php";
			$navs = array_merge_admnav($navs, $config_file);
		}
		if(OPEN_DC)
		{
			$config_file = APP_ROOT_PATH."system/adm_cfg/".APP_TYPE."/dcadmnav_cfg.php";
			$navs = array_merge_admnav($navs, $config_file);
		}
		$this->assign("navs",$navs);
		$this->display();
	}
	//框架左侧
	public function left()
	{
		$navs = require_once APP_ROOT_PATH."system/adm_cfg/".APP_TYPE."/admnav_cfg.php";
		if(OPEN_WEIXIN)
		{
			$config_file = APP_ROOT_PATH."system/adm_cfg/".APP_TYPE."/wxadmnav_cfg.php";
			$navs = array_merge_admnav($navs, $config_file);
		}
		if(OPEN_FX)
		{
			$config_file = APP_ROOT_PATH."system/adm_cfg/".APP_TYPE."/fxadmnav_cfg.php";
			$navs = array_merge_admnav($navs, $config_file);
		}
		if(OPEN_DC)
		{
			$config_file = APP_ROOT_PATH."system/adm_cfg/".APP_TYPE."/dcadmnav_cfg.php";
			$navs = array_merge_admnav($navs, $config_file);
		}
		if(WEIXIN_TYPE=="platform")
		{
			$config_file = APP_ROOT_PATH."system/adm_cfg/".APP_TYPE."/weixin_platform_cfg.php";
			$navs = array_merge_admnav($navs, $config_file);
		}
		else
		{
			$config_file = APP_ROOT_PATH."system/adm_cfg/".APP_TYPE."/weixin_single_cfg.php";
			$navs = array_merge_admnav($navs, $config_file);
		}
		$adm_session = es_session::get(md5(conf("AUTH_KEY")));
		$adm_id = intval($adm_session['adm_id']);
		
		$nav_key = strim($_REQUEST['key']);
		$nav_group = $navs[$nav_key]['groups'];
		$this->assign("menus",$nav_group);
		$this->display();
	}
	//默认框架主区域
	public function main()
	{
		$this->assign("apptype",APP_TYPE);
		$this->assign("FANWE_APP_ID",FANWE_APP_ID);
		//关于订单
		$income_order = M("Statements")->sum("income_order");
		$this->assign("income_order",$income_order);
		
		$dealing_order  =  $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order_item where type = 0 and delivery_status=0");
		$this->assign("dealing_order",$dealing_order);
		
		//关于用户
		$user_count = M("User")->count();
		$this->assign("user_count",$user_count);
		$income_incharge = M("Statements")->sum("income_incharge");
		$this->assign("income_incharge",$income_incharge);
		$withdraw = M("Withdraw")->where("is_paid = 0 and is_delete = 0")->count();
		$this->assign("withdraw",$withdraw);
		
		//上线的夺宝
		$duobao_count = M("DuobaoItem")->count();
		$this->assign("duobao_count",$duobao_count);

		$duobaoing_count = M("DuobaoItem")->where("progress<100")->count(); //进行中
		$this->assign("duobaoing_count",$duobaoing_count);
		
		$lotterying_count = M("DuobaoItem")->where("progress=100 and has_lottery = 0")->count(); //揭晓中
		$this->assign("lotterying_count",$lotterying_count);
		
		$lottery_count = M("DuobaoItem")->where("progress=100 and has_lottery = 1")->count(); //已揭晓
		$this->assign("lottery_count",$lottery_count);
		
		$this->display();
	}	
	//底部
	public function footer()
	{
		$this->display();
	}
	
	//修改管理员密码
	public function change_password()
	{
		$adm_session = es_session::get(md5(conf("AUTH_KEY")));
		$this->assign("adm_data",$adm_session);
		$this->display();
	}
	public function do_change_password()
	{
		$adm_id = intval($_REQUEST['adm_id']);
		if(!check_empty($_REQUEST['adm_password']))
		{
			$this->error(L("ADM_PASSWORD_EMPTY_TIP"));
		}
		if(!check_empty($_REQUEST['adm_new_password']))
		{
			$this->error(L("ADM_NEW_PASSWORD_EMPTY_TIP"));
		}
		if($_REQUEST['adm_confirm_password']!=$_REQUEST['adm_new_password'])
		{
			$this->error(L("ADM_NEW_PASSWORD_NOT_MATCH_TIP"));
		}		
		if(M("Admin")->where("id=".$adm_id)->getField("adm_password")!=md5($_REQUEST['adm_password']))
		{
			$this->error(L("ADM_PASSWORD_ERROR"));
		}
		M("Admin")->where("id=".$adm_id)->setField("adm_password",md5($_REQUEST['adm_new_password']));
		save_log(M("Admin")->where("id=".$adm_id)->getField("adm_name").L("CHANGE_SUCCESS"),1);
		$this->success(L("CHANGE_SUCCESS"));
		
		
	}
	
	public function reset_sending()
	{
		$field = strim($_REQUEST['field']);
		if($field=='DEAL_MSG_LOCK'||$field=='PROMOTE_MSG_LOCK'||$field=='APNS_MSG_LOCK')
		{
			M("Conf")->where("name='".$field."'")->setField("value",'0');
			$this->success(L("RESET_SUCCESS"),1);
		}
		else
		{
			$this->error(L("INVALID_OPERATION"),1);
		}
	}
}
?>