<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class EcvTypeAction extends CommonAction{
	public function index()
	{
// 		parent::index();
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		//追加默认参数
		if($this->get("default_map"))
		    $map = array_merge($map,$this->get("default_map"));
		
		if (method_exists ( $this, '_filter' )) {
		    $this->_filter ( $map );
		}
		
		$map['send_type']  = array('neq', '3');
		
		$name=$this->getActionName();
		$model = D ($name);
			
		if (! empty ( $model )) {
		    $this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	public function add()
	{
	    $this->assign('tpls',$this->load_tpl_file());
		$this->display();
	}
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M(MODULE_NAME)->create ();

		$range_value['range_value1'] = intval($_REQUEST['range_value1']);
		$range_value['range_value2'] = intval($_REQUEST['range_value2']);
		$range_value['range_value3'] = intval($_REQUEST['range_value3']);
		$range_value['range_value4'] = intval($_REQUEST['range_value4']);
		$range_value['range_value5'] = intval($_REQUEST['range_value5']);
		$range_value['range_value6'] = intval($_REQUEST['range_value6']);
		$range_value['range_value7'] = intval($_REQUEST['range_value7']);
		
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['name']))
		{
			$this->error(L("VOUCHER_NAME_EMPTY_TIP"));
		}	
		if(floatval($data['money'])<=0 && $data['sm_way'] != 1)
		{
			$this->error(L("VOUCHER_MONEY_ERROR_TIP"));
		}
		if(floor($data['money'])!=$data['money'] && $data['sm_way'] != 1 && $data['sm_way'] != 2 )
		{
			$this->error("代金券面额请填写整数");
		}	
		if($range_value['range_value1']==0&&$range_value['range_value2']==0&&$range_value['range_value3']==0&&$range_value['range_value4']==0&&$range_value['range_value5']==0&&$range_value['range_value6']==0&&$range_value['range_value7']==0&&$data['is_all']==0)
		{
		    $this->error("请最少选择一个使用区域");
		}
		if($data['send_type']==0)
		{
			unset($data['exchange_sn']);
			$data['total_limit'] = 0;
		}
		elseif($data['send_type']==1)
		{
			unset($data['exchange_sn']);
			$data['exchange_limit'] = intval($_REQUEST['exchange_limit_score']);
		}
		elseif($data['send_type']==2)
		{
			if(strim($data['exchange_sn'])=="")
			{
				unset($data['exchange_sn']);
			}
			$data['exchange_limit'] = intval($_REQUEST['exchange_limit_bonus']);
		}
		elseif($data['send_type']==4 || $data['send_type']==5)
		{
		    $e = M(MODULE_NAME)->where( 'send_type='.$data['send_type'] )->find();
		    if ($e) {
		        $err = $data['send_type']==4 ? '注册送红包':'充值送红包';
		        $this->error($err.'已经存在');
		    }
		    
		    unset($data['exchange_sn']);
		    if ( $data['sm_way'] == 1) {
		        $json_data['rand_value1'] = round($_REQUEST['rand_value1'], 2);
		        $json_data['rand_value2'] = round($_REQUEST['rand_value2'], 2);
		        $data['money'] = 0;
		    }
		}
		elseif($data['send_type']==6 || $data['send_type']==7)
		{
		    $e = M(MODULE_NAME)->where( 'send_type='.$data['send_type'] )->find();
		    if ($e) {
		        $err = $data['send_type']==6 ? '满返红包':'分享红包';
		        $this->error($err.'已经存在');
		    }
		
		    unset($data['exchange_sn']);
		    if ( $data['sm_way'] == 1) {
		        $json_data['rand_value1'] = round($_REQUEST['rand_value1'],2);
		        $json_data['rand_value2'] = round($_REQUEST['rand_value2'],2);
		        $data['money'] = 0;
		    }
		}
	       
		$json_data['domain']=$range_value;
		$data['data'] = json_encode($json_data);		
		
		$data['begin_time'] = strim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
		$data['end_time'] = strim($data['end_time'])==''?0:to_timespan($data['end_time']);
		// 更新数据
		$log_info = $data['name'];
		$list=M(MODULE_NAME)->add($data);
		 
		if (false !== $list) {
			
			if($data['send_type']==2&&empty($data['exchange_sn']))
			{
				$data['id'] = $list;
				do{
					$data['exchange_sn'] = rand(10000000,99999999);
					$data['share_url'] = SITE_DOMAIN.wap_url("index","ecv#index",array("sn"=>$data['exchange_sn']));
					$res = M(MODULE_NAME)->save($data);
				}while(!$res);				
			}
			
			if($data['send_type']==2&&!empty($data['exchange_sn'])){
                                $data['id'] = $list;
				$data['share_url'] = SITE_DOMAIN.wap_url("index","ecv#index",array("sn"=>$data['exchange_sn']));
				$res = M(MODULE_NAME)->save($data);
			}
			
			
			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo        = M(MODULE_NAME)->where($condition)->find();
		$json_data = json_decode($vo['data'], 1);
		 
		if ($json_data) {
		    $vo = array_merge($vo, $json_data);
		}
		
		$this->assign ( 'vo', $vo );
		$this->assign('tpls',$this->load_tpl_file());
		$this->display ();
	}
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		$data['is_all']=intval($_REQUEST['is_all']);
		
		$range_value['range_value1'] = intval($_REQUEST['range_value1']);
		$range_value['range_value2'] = intval($_REQUEST['range_value2']);
		$range_value['range_value3'] = intval($_REQUEST['range_value3']);
		$range_value['range_value4'] = intval($_REQUEST['range_value4']);
		$range_value['range_value5'] = intval($_REQUEST['range_value5']);
		$range_value['range_value6'] = intval($_REQUEST['range_value6']);
		$range_value['range_value7'] = intval($_REQUEST['range_value7']);
				
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("name");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['name']))
		{
			$this->error(L("VOUCHER_NAME_EMPTY_TIP"));
		}	
		if(floatval($data['money'])<=0 && $data['sm_way'] != 1)
		{
			$this->error(L("VOUCHER_MONEY_ERROR_TIP"));
		}	
		if(floor($data['money'])!=$data['money'] && $data['sm_way'] != 1 && $data['sm_way'] != 2 )
		{
			$this->error("代金券面额请填写整数");
		}
		if($range_value['range_value1']==0&&$range_value['range_value2']==0&&$range_value['range_value3']==0&&$range_value['range_value4']==0&&$range_value['range_value5']==0&&$range_value['range_value6']==0&&$range_value['range_value7']==0&&$data['is_all']==0)
		{
		    $this->error("请最少选择一个使用区域");
		}
		if($data['send_type']==0)
		{
			$data['exchange_sn'] = null;
			$data['total_limit'] = 0;
		}
		elseif($data['send_type']==1)
		{
			$data['exchange_sn'] = null;
			$data['exchange_limit'] = intval($_REQUEST['exchange_limit_score']);
		}
		elseif($data['send_type']==2)
		{
			if(strim($data['exchange_sn'])=="")
			{
				$data['exchange_sn'] = null;
			}
			$data['exchange_limit'] = intval($_REQUEST['exchange_limit_bonus']);
		}elseif($data['send_type']==4 || $data['send_type']==5){
		    $where['send_type']   = array('eq', $data['send_type']);
		    $where['id']          = array('neq', $data['id']);
		   
		    $e = M(MODULE_NAME)->where( $where )->find();
		  
		    if ($e) {
		        $err = $data['send_type']==4 ? '注册送红包':'充值送红包';
		        $this->error($err.'已经存在');
		    }
		    
		    unset($data['exchange_sn']);
		    if ( $data['sm_way'] == 1) {
		        $json_data['rand_value1'] = round($_REQUEST['rand_value1'], 2);
		        $json_data['rand_value2'] = round($_REQUEST['rand_value2'], 2);
		        $data['money'] = 0;
		    }
		}elseif($data['send_type']==6 || $data['send_type']==7){
		    $where['send_type']   = array('eq', $data['send_type']);
		    $where['id']          = array('neq', $data['id']);
		     
		    $e = M(MODULE_NAME)->where( $where )->find();
		    
		    if ($e) {
		        $err = $data['send_type']==6 ? '满返红包':'分享红包';
		        $this->error($err.'已经存在');
		    }
		    
		    unset($data['exchange_sn']);
		    if ( $data['sm_way'] == 1) {
		        $json_data['rand_value1'] = round($_REQUEST['rand_value1'], 2);
		        $json_data['rand_value2'] = round($_REQUEST['rand_value2'], 2);
		        $data['money'] = 0;
		    }
		}
		
		$json_data['domain']=$range_value;
		$data['data'] = json_encode($json_data);
		
		$data['begin_time'] = strim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
		$data['end_time'] = strim($data['end_time'])==''?0:to_timespan($data['end_time']);
		
		// 
		// 更新数据
		$list=M(MODULE_NAME)->save ($data);
		 
		if (false !== $list) {
			$data['id'] = $list;
			if($data['send_type']==2&&empty($data['exchange_sn']))
			{
				do{
					$data['exchange_sn'] = rand(10000000,99999999);
					$data['share_url'] = SITE_DOMAIN.wap_url("index","ecv#index",array("sn"=>$data['exchange_sn']));
					$res = M(MODULE_NAME)->save($data);
				}while(!$res);
			}
			if($data['send_type']==2&&!empty($data['exchange_sn'])){
				$data['share_url'] = SITE_DOMAIN.wap_url("index","ecv#index",array("sn"=>$data['exchange_sn']));
				$res = M(MODULE_NAME)->save($data);
			}
			//成功提示
			M("Ecv")->where("ecv_type_id=".$data['id'])->setField("use_limit",$data['use_limit']);  //同步可用次数
			M("Ecv")->where("ecv_type_id=".$data['id'])->setField("begin_time",$data['begin_time']);
			M("Ecv")->where("ecv_type_id=".$data['id'])->setField("end_time",$data['end_time']);
			M("Ecv")->where("ecv_type_id=".$data['id'])->setField("money",$data['money']);
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
				if(M("Ecv")->where(array ('ecv_type_id' => array ('in', explode ( ',', $id ) ) ))->count()>0)
				{
					$this->error(l("VOUCHER_EXIST"),$ajax);
				}
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
	
	public function send()
	{
		$id = intval($_REQUEST['id']);
		$ecv_type = M("EcvType")->getById($id);
		if(!$ecv_type)
		{
			$this->error(l("INVALID_ECV_TYPE"));
		}

		$this->assign("ecv_type",$ecv_type);
		$this->display();
	}	
	
	public function doSend()
	{
		require_once APP_ROOT_PATH."system/libs/voucher.php";
		$ecv_type_id = intval($_REQUEST['ecv_type_id']);
		$need_password = 1;
		$send_type = intval($_REQUEST['send_type']);
		$user_ids = strim($_REQUEST['user_id']);
		$gen_count = intval($_REQUEST['gen_count']);
		$page = intval($_REQUEST['page'])==0?1:intval($_REQUEST['page']);  //大数据量时的载入的页数
		$page_size = app_conf("BATCH_PAGE_SIZE"); //每次运行的次数， 开发时可根据实际环境改变此大小
		$page_limit = ($page-1)*$page_size.",".$page_size;
		switch($send_type)
		{
			case 0:
				//按会员组
				$user_list = M("User")->order("id asc")->limit($page_limit)->findAll();
				if($user_list)
				{
					foreach($user_list as $v)
					{
						send_voucher($ecv_type_id,$v['id'],$need_password);
					}
					$this->assign("jumpUrl",u("EcvType/doSend",array("ecv_type_id"=>$ecv_type_id,'need_password'=>$need_password,'send_type'=>$send_type,'user_group'=>$user_group,'user_id'=>$user_ids,'gen_count'=>$gen_count,'page'=>($page+1))));
					$msg = sprintf(l("SEND_VOUCHER_PAGE_SUCCESS"),($page-1)*$page_size,$page*$page_size);
					$this->success($msg);
				}
				else
				{
					save_log("ID".$ecv_type_id.l("VOUCHER_SEND_SUCCESS"),1);
					$this->assign("jumpUrl",u("EcvType/index"));
					$this->success(l("VOUCHER_SEND_SUCCESS"));
				}
				break;
			case 1:
				//按会员ID
				$user_list = M("User")->where("id in(".$user_ids.")")->order("id asc")->limit($page_limit)->findAll();
				if($user_list)
				{
					foreach($user_list as $v)
					{
						send_voucher($ecv_type_id,$v['id'],$need_password);
					}
					$this->assign("jumpUrl",u("EcvType/doSend",array("ecv_type_id"=>$ecv_type_id,'need_password'=>$need_password,'send_type'=>$send_type,'user_group'=>$user_group,'user_id'=>$user_ids,'gen_count'=>$gen_count,'page'=>($page+1))));
					$msg = sprintf(l("SEND_VOUCHER_PAGE_SUCCESS"),($page-1)*$page_size,$page*$page_size);
					$this->success($msg);
				}
				else
				{
					save_log("ID".$ecv_type_id.l("VOUCHER_SEND_SUCCESS"),1);
					$this->assign("jumpUrl",u("EcvType/index"));
					$this->success(l("VOUCHER_SEND_SUCCESS"));
				}
				break;
			case 2:
				//线下
				for($i=0;$i<$page_size;$i++)
				{					
					if(($page-1)*$page_size+$i==$gen_count)
					{
						save_log("ID".$ecv_type_id.l("VOUCHER_SEND_SUCCESS"),1);
						$this->assign("jumpUrl",u("EcvType/index"));
						$this->success(l("VOUCHER_SEND_SUCCESS"));
						break;
					}
					send_voucher($ecv_type_id,0,$need_password);	
				}
				$this->assign("jumpUrl",u("EcvType/doSend",array("ecv_type_id"=>$ecv_type_id,'need_password'=>$need_password,'send_type'=>$send_type,'user_group'=>$user_group,'user_id'=>$user_ids,'gen_count'=>$gen_count,'page'=>($page+1))));
				$msg = sprintf(l("SEND_VOUCHER_PAGE_SUCCESS"),($page-1)*$page_size,$page*$page_size);
				$this->success($msg);
				break;
		}
		
	}
	
	
	public function load_tpl_file(){
	    $directory = APP_ROOT_PATH."system/ecv_tpl/";
	    $files = get_all_files($directory);
	    $tmpl_files = array();
	    
	    foreach($files as $item)
	    {
	        if(substr($item,-5)==".html")
	        {
	            $item = explode($directory,$item);
	            $item = $item[1];
	            $tmpl_files[] = $item;
	                
	        }
	    }
	    return $tmpl_files;
	}
	
}
?>