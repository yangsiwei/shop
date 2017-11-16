<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class ShareAction extends CommonAction{
	public function index()
	{
		if(!isset($_REQUEST['is_robot']))$_REQUEST['is_robot'] = -1;
		$model = D ("Share");
		$map = $this->_search ();
		if(strim($_REQUEST['keyword'])!='')
		{
			$where['content'] = array('like','%'.strim($_REQUEST['keyword']).'%');		
			$where['title'] = array('like','%'.strim($_REQUEST['keyword']).'%');		
			$where['_logic'] = 'or';
			$map['_complex'] = $where;			
		}
		if(strim($_REQUEST['user_name'])!='')
		{
			$map['user_name'] = array('like','%'.strim($_REQUEST['user_name']).'%');		
		}
		$is_robot = intval($_REQUEST['is_robot']);
		if($is_robot==-1)
		{
			unset($map['is_robot']);
		}
		//列表过滤器，生成查询Map对象		
		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	
	public function edit() {	
		//require_once APP_ROOT_PATH."system/model/topic.php";	
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		$ecv_list=M("EcvType")->where("send_type=0")->findAll();
		$this->assign ( 'ecv_list', $ecv_list );
		
		if($vo['ecv_id'] > 0){
			$ecv_send=M("Ecv")->where("id=".$vo['ecv_id'])->find();
			$this->assign ( 'ecv_send', $ecv_send );
		}
		//输出图片
		$image_list = M("ShareImage")->where("share_id=".$vo['id'])->findAll();
		$this->assign("image_list",$image_list);
		$this->display ();
	}
	
	public function update() {
			B('FilterString');
			
			if ($_POST['ecv_type_id'] <=0 ) {
			    $_POST['is_send_ecv'] = 0;
			}
			
			$data = M(MODULE_NAME)->create ();	
			$data['is_check']=1;
			$log_info = $data['id'].l("SHARE_DATA");
			
			$ecv_type_id=intval($_REQUEST['ecv_type_id']);

			//开始验证有效性
			$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
	
			// 更新数据
			$list=M(MODULE_NAME)->save($data);
			
			$data=M(MODULE_NAME)->where("id=".$data['id'])->find();
			if (false !== $list) {
				//成功提示
				save_log($log_info.L("UPDATE_SUCCESS"),1);
				
				require_once APP_ROOT_PATH."system/libs/voucher.php";
				
				if($data['is_send_ecv']==1 && $data['ecv_id']==0){
					//按会员ID
					$user = M("User")->where("id=".$data['user_id'])->find();
					
					if($user)
					{
						$need_password=1;
						$ecv_id=send_voucher($ecv_type_id,$user['id'],$need_password);
						
						if($ecv_id > 0){
							M(MODULE_NAME)->where("id=".$data['id'])->save(array("ecv_id"=>$ecv_id));
						}
						
						save_log("ID".$ecv_type_id.l("VOUCHER_SEND_SUCCESS"),1);
						$this->assign("jumpUrl",u("Share/edit",array("id"=>$data['id'])));
						
						$ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv where user_id = ".$data['user_id']." and ecv_type_id=".intval($ecv_type_id));
						$ecv1['money']=(float)$ecv['money'];
						send_msg($data['user_id'], $data['duobao_item_id']."期晒单分享获得".$ecv1['money']."元红包啦！！！", "notify", $data['id']);
						//$msg = "红包发送成功";
						$this->success(l("VOUCHER_SEND_SUCCESS"));
					}
					else
					{
							
						//$this->assign("jumpUrl",u("Share/edit"));
						$log_info="用户不存在，红包发送失败";
						save_log($log_info,0);
						$this->error($log_info);
					}
				}else{
					$this->success(L("UPDATE_SUCCESS"));
				}

				
			} else {
				//错误提示
				save_log($log_info.L("UPDATE_FAILED"),0);
				$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
			}
		}
	
	public function delete()
	{
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				
				$share_condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$condition = array ('share_id' => array ('in', explode ( ',', $id ) ) );
				
				
				$rel_data = M(MODULE_NAME)->where($share_condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $share_condition )->delete();	
						
				if ($list!==false) {
					
					//删除相关的其他数据，如回复，图片
					
					$reply_images = M("ShareImage")->where($condition)->findAll();
					foreach($reply_images as $image_data)
					{
						@unlink(APP_ROOT_PATH.$image_data['path']);
						@unlink(APP_ROOT_PATH.$image_data['o_path']);
					}
					M("ShareImage")->where($condition)->delete();				
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
	
	public function toogle_status()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$field = $_REQUEST['field'];
		
		$info = $id."_".$field;
		$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField($field);  //当前状态
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		
		M(MODULE_NAME)->where("id=".$id)->setField($field,$n_is_effect);	
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
}


?>