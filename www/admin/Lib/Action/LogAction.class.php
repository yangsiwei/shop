<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class LogAction extends CommonAction{
	public function index()
	{
		if(strim($_REQUEST['log_info'])!='')
		{
			$map['log_info'] = array('like','%'.strim($_REQUEST['log_info']).'%');			
		}
		
		$log_begin_time  = strim($_REQUEST['log_begin_time'])==''?0:to_timespan($_REQUEST['log_begin_time']);
		$log_end_time  = strim($_REQUEST['log_end_time'])==''?0:to_timespan($_REQUEST['log_end_time']);
		if($log_end_time==0)
		{
			$map['log_time'] = array('gt',$log_begin_time);	
		}
		else
		$map['log_time'] = array('between',array($log_begin_time,$log_end_time));	
		
		
		$this->assign("default_map",$map);
		parent::index();
	}
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );			
				
				$list = M(MODULE_NAME)->where ( $condition )->delete();
				if ($list!==false) {
					
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
		
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	
	public function coupon()
	{
		if(strim($_REQUEST['msg'])!='')
		{
			$map['msg'] = array('like','%'.strim($_REQUEST['msg']).'%');			
		}
		if(strim($_REQUEST['query_id'])!='')
		{
			$map['query_id'] = strim($_REQUEST['query_id']);			
		}
		if(strim($_REQUEST['coupon_sn'])!='')
		{
			$map['coupon_sn'] = strim($_REQUEST['coupon_sn']);			
		}
		
		
		$this->assign("default_map",$map);
		
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		//追加默认参数
		if($this->get("default_map"))
		$map = array_merge($map,$this->get("default_map"));
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$model = D ("CouponLog");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	
	public function foreverdeletelog() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );			
				
				$list = M("CouponLog")->where ( $condition )->delete();
				if ($list!==false) {
					
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
		
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
}
?>