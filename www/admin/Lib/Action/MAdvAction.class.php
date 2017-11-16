<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class MAdvAction extends CommonAction{
	public function add()
	{
		
		$nav_cfg = $GLOBALS['mobile_cfg'];
		$this->assign("nav_cfg",$nav_cfg);
		
		foreach($nav_cfg as $k=>$v)
		{
			if($v['mobile_type']==0)
			{
				$this->assign("nav_list",$v['nav']);
			}
		}	

		$this->assign("new_sort",intval(M(MODULE_NAME)->max("sort"))+1);
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		foreach($city_list as $k=>$v)
		{
			if($v['pid']==0)$city_list[$k]['id'] = 0;
		}
		$this->assign("city_list",$city_list);
		
		//输出专题位列表		
		$this->assign("zt_list",M("MZt")->findAll());
		$this->display();
	}
	
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$nav_cfg = $GLOBALS['mobile_cfg'];	
		
		$data = M(MODULE_NAME)->create ();
		
		foreach($nav_cfg as $k=>$v)
		{
			if($v['mobile_type']==$data['mobile_type'])
			{
				$navs = $v['nav'];
			}
		}
		
		foreach($navs as $ctl=>$v)
		{
			if($v['type']==$data['type'])
			{
				$data['ctl'] = $ctl;				
				$cfg = array($v['field']=>$_POST[$v['field']]);				
				$data['data'] = serialize($cfg);
			}
		}
			
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['name']))
		{
			$this->error(L("NAME_EMPTY_TIP"));
		}	

		$log_info = $data['name'];
		
		if($data['position']!=2)
		{
			unset($data['zt_id']);
			unset($data['zt_position']);
		}
		
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}

	
	
	public function edit()
	{
		$nav_cfg = $GLOBALS['mobile_cfg'];
		$this->assign("nav_cfg",$nav_cfg);
		
		$id = intval($_REQUEST['id']);
		$vo = M("MAdv")->getById($id);
		$vo['data'] = unserialize($vo['data']);
		
		
		$this->assign ('vo', $vo);
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		foreach($city_list as $k=>$v)
		{
			if($v['pid']==0)$city_list[$k]['id'] = 0;
		}
		$this->assign("city_list",$city_list);
		
		
		foreach($nav_cfg as $k=>$v)
		{
			if($v['mobile_type']==$vo['mobile_type'])
				$this->assign("nav_list",$v['nav']);
		}

		$this->assign("zt_list",M("MZt")->findAll());
		$this->display();
	}
	
	
	public function update() {
		B('FilterString');
		
		$nav_cfg = $GLOBALS['mobile_cfg'];
		
		$data = M(MODULE_NAME)->create ();
		
		foreach($nav_cfg as $k=>$v)
		{
			if($v['mobile_type']==$data['mobile_type'])
			{
				$navs = $v['nav'];
			}
		}
		
		foreach($navs as $ctl=>$v)
		{
			if($v['type']==$data['type'])
			{
				$data['ctl'] = $ctl;
				$cfg = array($v['field']=>$_POST[$v['field']]);
				$data['data'] = serialize($cfg);
			}
		}

		$log_info = $data['id'];
		
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['name']))
		{
			$this->error(L("NAME_EMPTY_TIP"));
		}
		
		$log_info = $data['name'];
		
		if($data['position']!=2)
		{
			unset($data['zt_id']);
			unset($data['zt_position']);
		}
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );	
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
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
	
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M(MODULE_NAME)->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M(MODULE_NAME)->where("id=".$id)->setField("sort",$sort);
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
	
	
	public function load_zt_id()
	{
		$adv_id = intval($_REQUEST['adv_id']);
		$adv_item = M("MAdv")->where("id=".$adv_id)->find();
		
		$zt_id = intval($_REQUEST['zt_id']);
		$zt = M("MZt")->where("id=".$zt_id)->find();
		$zt_moban = $zt['zt_moban'];
		$key = explode(".",$zt_moban);
		$key = $key[0];
		
		$file_content = file_get_contents(APP_ROOT_PATH."mapi/mobile_zt/".$zt_moban);
		preg_match_all("/<!--([^-]+)-->/",$file_content,$layout_array);
		foreach($layout_array[1] as $item)
		{
			$layout_item = array();
			$layout_item['key'] = $item;
			if($item==$adv_item['zt_position'])
			$layout_item['selected'] = true;
			$adv_ids[] = $layout_item;
		}
		if($zt)
		{
			$data['preview'] = APP_ROOT."/mapi/mobile_zt/preview/".$key.".png";
			$data['data'] = $adv_ids;
			$data['status'] = 1;
		}
		else
		{
			$data['status'] = 0;
		}
		
		ajax_return($data);
	}
}
?>