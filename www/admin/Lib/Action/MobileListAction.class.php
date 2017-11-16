<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class MobileListAction extends CommonAction{
	public function index()
	{
		//输出团购城市
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		$this->assign("city_list",$city_list);
		
		parent::index();
	}
	public function add()
	{
		//输出团购城市
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		$this->assign("city_list",$city_list);
		$this->display();
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;	
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );

		//输出团购城市
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		$this->assign("city_list",$city_list);
		$this->display ();
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
					$info[] = $data['mobile'];	
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
	
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M(MODULE_NAME)->create ();

		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['mobile']))
		{
			$this->error(L("MOBILE_EMPTY_TIP"));
		}
		if(!check_mobile($data['mobile']))
		{
			$this->error(L("MOBILE_ERROR_FORMAT_TIP"));
		}				
		if($data['city_id']==0)
		{
			$this->error(L("MOBILE_CITY_EMPTY_TIP"));
		}
		if(M("MobileList")->where("mobile='".$data['mobile']."'")->count()>0)
		{
			$this->error(L("MOBILE_EXIST_TIP"));
		}
		// 更新数据
		$log_info = $data['mobile'];
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
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("mobile");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['mobile']))
		{
			$this->error(L("MOBILE_EMPTY_TIP"));
		}
		if(!check_mobile($data['mobile']))
		{
			$this->error(L("MOBILE_ERROR_FORMAT_TIP"));
		}				
		if($data['city_id']==0)
		{
			$this->error(L("MOBILE_CITY_EMPTY_TIP"));
		}
		if(M("MobileList")->where("mobile='".$data['mobile']."' and id<>".$data['id'])->count()>0)
		{
			$this->error(L("MOBILE_EXIST_TIP"));
		}
		// 更新数据
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
	
	
	public function set_effect()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$info = M(MODULE_NAME)->where("id=".$id)->getField("mobile");
		$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField("is_effect");  //当前状态
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M(MODULE_NAME)->where("id=".$id)->setField("is_effect",$n_is_effect);	
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
	
	
	public function export_csv($page = 1)
	{
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
			
		$city_id = intval($_REQUEST['city_id']);
		
		if($city_id>0)
		$list=  M(MODULE_NAME)->where("city_id = ".$city_id)->limit($limit)->findAll();
		else
		$list = M(MODULE_NAME)->limit($limit)->findAll();
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$list_value = array('mobile'=>'""');
			if($page == 1)$content = "";
	    	
	    	foreach($list as $k=>$v)
			{
								
				$list_value['mobile'] = iconv('utf-8','gbk', $v['mobile'] );
				

			
				$content .= implode(",", $list_value) . "\n";
			}	
			
			
			header("Content-Disposition: attachment; filename=mobile_list.csv");
	    	echo $content;  
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}		
	}
	
	public function import_csv()
	{
		//输出团购城市
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		$this->assign("city_list",$city_list);		
		$this->display();
	}
	
	public function do_import_csv()
	{
		$file = $_FILES['file'];		
		$city_id = intval($_REQUEST["city_id"]);
		$content = @file_get_contents($file['tmp_name']);
		$content = explode("\n",$content);

		$count = 0;
		foreach($content as $k=>$v)
		{
				if($v!='')
				{
					$data = array();
					$data['mobile'] = $v;
					$data['city_id'] = $city_id;
					if(!M(MODULE_NAME)->where($data)->find())
					{
						$data['is_effect'] = 1;
						$res = M(MODULE_NAME)->add($data);
						if($res)
						{
							$count++;
						}
					}
				}
		}
		save_log(sprintf(L("IMPORT_MOBILE_SUCCESS"),$count),1);
		$this->success(sprintf(L("IMPORT_MOBILE_SUCCESS"),$count));
	}
}
?>