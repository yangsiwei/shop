<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

class InchargeOrderAction extends CommonEnhanceAction{
	
	public function index()
	{
		//列表过滤器，生成查询Map对象
		$model = D ('DealOrder');
		$map = $this->_search ($model);
		$map['type'] = 1; 
		if(strim($_REQUEST['order_sn']))
		$map['order_sn'] = strim($_REQUEST['order_sn']);
		
		$this->_list( $model, $map ,"id");
		$this->display ();
		
	}
	
	
	public function trash()
	{
		//列表过滤器，生成查询Map对象
		$model = D ('DealOrderHistoryView');
		$map = $this->_search ($model);
		$map['type'] = 1;
// 		$map['is_robot'] = 0;
		
		$this->_list( $model, $map ,"id");
		$this->display ();
		
	}
	
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("DealOrderHistory")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['order_sn'];
				}
				if($info) $info = implode(",",$info);
				$list = M("DealOrderHistory")->where ( $condition )->delete();	
		
				if ($list!==false) {
					//删除关联数据
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