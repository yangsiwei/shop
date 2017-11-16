<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class paymentModule extends MainBaseModule
{

	
	public function done()
	{
		global_run();
		init_app_page();
		$id = intval($_REQUEST['id']);
		$is_done = intval($_REQUEST['is_done']);
		$is_app  = intval($_REQUEST['is_app']);

		$data = call_api_core("payment","done",array("id"=>$id));
		$data['re_payment_url'] = SITE_DOMAIN.wap_url("index","cart#order",array("id"=>$data['order_id']));
		if(!$data['status'])
		{
			showErr($data['info']);
		}
		if($data['pay_status']==1 || $is_done==1)
		{			
			if($data['pay_type']==1){
				//会员充值夺宝币，跳到资金日志页面
				app_redirect(wap_url("index","uc_money"));
			}else{
                $GLOBALS['db']->query("update ".DB_PREFIX."user set total_use_money = total_use_money +".$data['pay_amount']. "where id =".$GLOBALS['user_info']['id']);
				$data['page_title'] = "支付结果";
				$GLOBALS['tmpl']->assign("data",$data);
				$GLOBALS['tmpl']->display("payment_done.html");				
			}
		}
		else
		{
		    $ass_data                 = $data['payment_code'];
		    $ass_data['is_app']       = $is_app;
		    $ass_data['reload_url']   = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id']));
		    $ass_data['success_url']  = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id'], "is_done"=>1));
		    $ass_data['http_host']    = $_SERVER['HTTP_HOST'];
			$ass_data['page_title']       = "订单付款";
			$GLOBALS['tmpl']->assign("data",$ass_data);
			$GLOBALS['tmpl']->display("payment_pay.html");
		}
		
	}
	
	public function order_share(){
	    global_run();
	    init_app_page();
	    $id = intval($_REQUEST['id']);
	    $is_share = intval($_REQUEST['is_share']);

	    if($is_share){
	        $data = call_api_core("payment","order_share",array("id"=>$id));
	        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
	            app_redirect(wap_url("index","user#login"));
	            exit;
	        }
	    }
	    app_redirect(wap_url("index","uc_order#index",array('pay_status'=>1)));
	}
		
}
?>