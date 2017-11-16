<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class uc_orderModule extends MainBaseModule
{
	
	public function cancel()
	{
		global_run();
		$param=array();
		$param['id'] = intval($_REQUEST['id']);
		
		$data = call_api_core("uc_order","cancel",$param);
// 		print_r($data);exit;
		if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
			app_redirect(wap_url("index","user#login"));
		}
		
		if($data['status']==0)
		{
			showErr($data['info']);
		}
		else
		{
			showErr($data['info'],0,get_gopreview());
	
		}		
	}
	
	
	/**
	 * 查看物流
	 */
	public function check_delivery()
	{
		$item_id = intval($_REQUEST['item_id']);
		$data = call_api_core("uc_order","check_delivery",array("item_id"=>$item_id));
		
		if($data['status']==0)
		{
			showErr($data['info']);
		}
		else
		{
		    $GLOBALS['tmpl']->assign("data", $data);
		    $GLOBALS['tmpl']->display("uc_order_express.html");
// 			app_redirect($data['url']);
		}
	}
	
	
	/**
	 * 确认收货
	 */
	public function verify_delivery()
	{
		global_run();
		$item_id = intval($_REQUEST['item_id']);
				
		$data = call_api_core("uc_order","verify_delivery",array("item_id"=>$item_id));

		if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
			app_redirect(wap_url("index","user#login"));
		}
		
		if($data['status']==0)
		{
			showErr($data['info']);
		}
		else
		{
			//showSuccess($data['info'],0,get_gopreview());
			showConfirm($data['info'],0,wap_url("index","uc_share#rule&id=".$data['duobao_item_id']),wap_url("index","uc_winlog"));
		}
	}
	
}
?>