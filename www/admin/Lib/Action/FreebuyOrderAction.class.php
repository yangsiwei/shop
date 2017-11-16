<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

class FreebuyOrderAction extends CommonAction{
	
	public function index()
	{
	    $search_user_name = trim($_REQUEST['user_name']);
	    if(trim($_REQUEST['user_name'])){
	        $result_data = $GLOBALS['db']->getAll("select id from ".DB_PREFIX."user where user_name like '%".$search_user_name."%'");
	       foreach ($result_data as $v){
	           $user_ids[] = $v['id'];
	       }
	    }

		//列表过滤器，生成查询Map对象
		$model = D ('DealOrderItem');
		$map = $this->_search ($model);
		
		
		
		
		
		if(strim($_REQUEST['order_sn']))
		$map['order_sn'] = strim($_REQUEST['order_sn']);
		if(strim($_REQUEST['duobao_item_id']))
		$map['duobao_item_id'] = strim($_REQUEST['duobao_item_id']);
		$map['type'] = 4; 
		if($search_user_name)
		$map['user_id'] = array('in',implode(",", $user_ids));

		$user_id = intval($_REQUEST['user_id']);
		if($user_id){
		    unset($map['user_id']);
		    $map['user_id'] = $user_id;
		}
		
		$this->_list( $model, $map ,"id");
		$this->display ();
		
	}
	
	

	public function view_order()
	{
		$id = intval($_REQUEST['id']);
		$order_info = M("DealOrder")->where("id=".$id." and type = 4")->find();
		
		if(!$order_info)
		{
			$this->error(l("INVALID_ORDER"));
		}
		$order_deal_items = M("DealOrderItem")->where("order_id=".$order_info['id'])->findAll();
		
		//var_dump($order_info);
		//exit;
		
		require_once APP_ROOT_PATH."system/model/cart.php";
				
		$this->assign("order_deals",$order_deal_items);
		$this->assign("order_info",$order_info);
		
		
		$payment_notice = M("PaymentNotice")->where("order_id = ".$order_info['id']." and is_paid = 1")->order("pay_time desc")->findAll();
		//var_dump($payment_notice);exit;
		$this->assign("payment_notice",$payment_notice);
	
	
		
		//输出订单日志
		$log_list = M("DealOrderLog")->where("order_id=".$order_info['id'])->order("log_time desc")->findAll();
		$this->assign("log_list",$log_list);
		
		$this->display();
	}
	
	
	
	public function trash()
	{
		//列表过滤器，生成查询Map对象
		$model = D ('DealOrderHistoryView');
		$map = $this->_search ($model);
		$map['type'] = 4;
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
	
	
	public function view_order_history()
	{
		$id = intval($_REQUEST['id']);
		$order_info = M("DealOrderHistory")->where("id=".$id." and type = 4")->find();
		if(!$order_info)
		{
			$this->error(l("INVALID_ORDER"));
		}
		$order_deal_items = unserialize($order_info['history_deal_order_item']);
		
		
		
		$this->assign("order_deals",$order_deal_items);
		$this->assign("order_info",$order_info);
	
		$payment_notice = unserialize($order_info['history_payment_notice']);
		$this->assign("payment_notice",$payment_notice);
		
		
	
	
		//输出订单日志
		$log_list = unserialize($order_info['history_deal_order_log']);
		$this->assign("log_list",$log_list);
	
		$this->display();
	}
	
	
	
}
?>