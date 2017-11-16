<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class AdvAction extends CommonAction{
	public function index()
	{
		$webadv_cfg = require_once APP_ROOT_PATH."system/web_cfg/main/webadv_cfg.php";
		if(!$webadv_cfg)$webadv_cfg = array();
		
		$this->assign("webadv_cfg",$webadv_cfg);
		$this->assign("webadv_cfg_json",json_encode($webadv_cfg));
		
		//输出团购城市
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		$this->assign("city_list",$city_list);
		
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if($_REQUEST['city_id'])
		$map['city_id'] = intval($_REQUEST['city_id']);
		if($_REQUEST['group'])
		$map['group'] = strim($_REQUEST['group']);
		if($_REQUEST['page_module'])
		$map['page_module'] = strim($_REQUEST['page_module']);

		if(strim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.strim($_REQUEST['name']).'%');
		}
		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		

		$list = $this->get("list");
		foreach($list as $k=>$v)
		{
			$groups = $webadv_cfg[$v['page_module']]['groups'];
			foreach($groups as $kk=>$vv)
			{
				if($vv['group']==$v['group'])
				$list[$k]['group_name'] = $vv['name'];
			}
		}
		$this->assign("list",$list);
		$this->assign("show_page",$this->get("page"));
		$this->display ();
		return;
	}
	
	private function edit_form($id=0)
	{
		$webadv_cfg = require_once APP_ROOT_PATH."system/web_cfg/main/webadv_cfg.php";		
		if(!$webadv_cfg)$webadv_cfg = array();
		
		$vo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."adv where id = '".$id."'");
		$le= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_cate");
		
		$this->assign("webadv_cfg",$webadv_cfg);
		$this->assign("webadv_cfg_json",json_encode($webadv_cfg));
		
		
		$this->assign("vo",$vo);
		$this->assign("le",$le);
		
		//输出团购城市
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		$this->assign("city_list",$city_list);
		
		$navs = require_once APP_ROOT_PATH."system/web_cfg/".APP_TYPE."/webnav_cfg.php";
		$this->assign("navs",$navs);
		
		$this->display("edit_form");
	}
	
	public function load_module()
	{
		$navs = require_once APP_ROOT_PATH."system/web_cfg/".APP_TYPE."/webnav_cfg.php";
		$id = intval($_REQUEST['id']);
		$module = strim($_REQUEST['module']);
		$act = M(MODULE_NAME)->where("id=".$id)->getField("u_action");
		$this->ajaxReturn($navs[$module]['acts'],$act);
	}
	
	public function add()
	{
		$this->edit_form();
	}
		
	public function edit() {		
		$this->edit_form(intval($_REQUEST['id']));
	}
	
	public function save() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$le=$_REQUEST;
		$data = M(MODULE_NAME)->create ();
		$data['u_param'] = trim($data['u_param']);
		$data['url'] = trim($data['url']);
		$data['cate_id'] = $le['page_module_ins'];
		$webadv_cfg = require_once APP_ROOT_PATH."system/web_cfg/main/webadv_cfg.php";
		$data['page_name'] = $webadv_cfg[$data['page_module']]['page_name'];
		$data['app_index'] = "index";
		
		//开始验证有效性
		if(!check_empty($data['name']))
		{
			$this->error(L("ADV_NAME_EMPTY_TIP"));
		}
		if(!check_empty($data['image']))
		{
			$this->error("请输入广告图片");
		}
		if($data['page_module']=='')
		{
			$this->error("请选择所在页面");
		}
		if($data['group']=='')
		{
			$this->error("请选择广告类型");
		}
		// 更新数据
		$log_info = $data['name'];
		
		if($data['id'])
		$list = M(MODULE_NAME)->save($data);
		else
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info."保存成功",1);
			$this->success("保存成功");
		} else {
			//错误提示
			save_log($log_info."保存失败",0);
			$this->error("保存失败");
		}
// 		$this->ajaxReturn("2333","6666666",1);
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
	
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M("Adv")->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M("Adv")->where("id=".$id)->setField("sort",$sort);
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
}
?>