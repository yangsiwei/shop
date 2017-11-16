<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class NavAction extends CommonAction{
	private $navs;

	public function __construct()
	{
		parent::__construct();
		$this->navs = require_once APP_ROOT_PATH."system/web_cfg/".APP_TYPE."/webnav_cfg.php";
	}
	public function index()
	{
		parent::index();
	}

	public function add() {		
		//定义菜单的部份可选项		
		
		$this->assign("navs",$this->navs);
		$this->display ();
	}
	public function edit() {		
		//定义菜单的部份可选项		
		$id = intval($_REQUEST ['id']);		
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		$this->assign("navs",$this->navs);
		$this->display ();
	}
	
	public function load_module()
	{
		$id = intval($_REQUEST['id']);
		$module = strim($_REQUEST['module']);
		$act = M(MODULE_NAME)->where("id=".$id)->getField("u_action");
		$this->ajaxReturn($this->navs[$module]['acts'],$act);
	}	
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("name");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['name']))
		{
			$this->error(L("NAV_NAME_EMPTY_TIP"));
		}	
		if(!check_empty($data['url'])&&$_REQUEST['u_module']=='')
		{
			$this->error(L("NAV_URL_EMPTY_TIP"));
		}		
		
		if($_REQUEST['u_module']!='')
		{
			$data['app_index'] = $this->navs[$data['u_module']]['app_index'];
			$data['url'] = '';
		}
		if($data['url']!='')
		{
			$data['u_module'] = '';
			$data['u_action'] = '';
			$data['u_param'] = '';
		}
		if(!isset($_REQUEST['u_action']))
		$data['u_action'] = '';
		// 更新数据
		
		$data['u_param'] = btrim($data['u_param']);
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			$dbErr = M()->getDbError();
			save_log($log_info.L("UPDATE_FAILED").$dbErr,0);
			$this->error(L("UPDATE_FAILED").$dbErr);
		}
	}
	
	
	public function insert() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();

		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['name']))
		{
			$this->error(L("NAV_NAME_EMPTY_TIP"));
		}	
		if(!check_empty($data['url'])&&$_REQUEST['u_module']=='')
		{
			$this->error(L("NAV_URL_EMPTY_TIP"));
		}		
		
		if($_REQUEST['u_module']!='')
		{
			$data['app_index'] = $this->navs[$data['u_module']]['app_index'];
			$data['url'] = '';
		}
		if($data['url']!='')
		{
			$data['u_module'] = '';
			$data['u_action'] = '';
			$data['u_param'] = '';
		}
		if(!isset($_REQUEST['u_action']))
		$data['u_action'] = '';
		// 更新数据

		$data['u_param'] = btrim($data['u_param']);
		$list=M(MODULE_NAME)->add ($data);
		$log_info = $data['name'];
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			$dbErr = M()->getDbError();
			save_log($log_info.L("INSERT_FAILED").$dbErr,0);
			$this->error(L("INSERT_FAILED").$dbErr);
		}
	}
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M("Nav")->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M("Nav")->where("id=".$id)->setField("sort",$sort);
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
	
	public function set_effect()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$info = M(MODULE_NAME)->where("id=".$id)->getField("name");
		$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField("is_effect");  //当前状态
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M(MODULE_NAME)->where("id=".$id)->setField("is_effect",$n_is_effect);	
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();	
					
				if ($list!==false) {
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
}
?>