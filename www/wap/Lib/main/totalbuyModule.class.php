<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class totalbuyModule extends MainBaseModule
{
    /**
     * 直购列表
     */
    public function lists(){
        global_run();
        init_app_page();
        
        $param['page']    = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $param['data_id'] = intval($_REQUEST['data_id']);
        $param['keyword'] = strim($_REQUEST['keyword']);
        $data = call_api_core("totalbuy","lists", $param);
        
       
        	
        $page = new Page($data['page']['total'], $data['page']['page_size']); // 初始化分页对象
        $p = $page->show();
        
        /* 数据 */
        $GLOBALS['tmpl']->assign('pages', $p);
        $GLOBALS['tmpl']->assign("list", $data['list']);
        $GLOBALS['tmpl']->assign("data", $data);
        
        $GLOBALS['tmpl']->display("totalbuy_lists.html");
    }
    
    /**
     * 确认订单页面
     * @see MainBaseModule::index()
     */
	public function index()
	{		
		global_run();
		init_app_page();
		// 避免重复提交
		//assign_form_verify();
		$param['consignee_id'] =  intval($_REQUEST['conid']);
		$data = call_api_core("totalbuy","index", $param);
		if($data['user_login_status'] != LOGIN_STATUS_LOGINED){
		    app_redirect(wap_url("index","user#login"));
		}
		 
		$GLOBALS['tmpl']->assign("data", $data);
		$GLOBALS['tmpl']->display("totalbuy.html");
	}
	
	/**
	 * 添加收货地址页面
	 */
	public function add_consignee(){
	    global_run();
	    init_app_page();
	    $cart = intval($_REQUEST['cart']);
	    $order_id = intval($_REQUEST['order_id']);
	    if($cart)
	    {
	        if($order_id)
	            es_session::set("wap_cart_set_address_url",wap_url("index","cart#order",array("id"=>$order_id)));
	        else
	            es_session::set("wap_cart_set_address_url",wap_url("index","cart#check"));
	    }
	    else
	    {
	        es_session::set("wap_cart_set_address_url","");
	    }
	    
	    $param=array();
	    $param['id'] = intval($_REQUEST['id']);
	    //订单选择地址时传递的参数,用于返回订单选择地址页ctl=uc_winlog&act=winlog_address
	    $param['order_item_id'] = intval($_REQUEST['order_item_id']);
	    $data = call_api_core("uc_address","add",$param);
	    if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        app_redirect(wap_url("index","user#login"));
	    }
	    $GLOBALS['tmpl']->assign("data",$data);
	    
	    $GLOBALS['tmpl']->assign("order_item_id",$data['order_item_id']);
	    
	    $GLOBALS['tmpl']->display("totalbuy_consignee.html");
	}
	
	/**
	 * 收货地址页面
	 */
	public function consignee_list(){
	    global_run();
	    init_app_page();
	    
	    $cart     = intval($_REQUEST['cart']);
	    $order_id = intval($_REQUEST['order_id']);
	    $selected_consignee = intval($_REQUEST['conid']);
	    if($cart)
	    {
	        if($order_id)
	            es_session::set("wap_cart_set_address_url",wap_url("index","cart#order",array("id"=>$order_id)));
	        else
	            es_session::set("wap_cart_set_address_url",wap_url("index","cart#check"));
	    }
	    else
	    {
	        es_session::set("wap_cart_set_address_url","");
	    }
	    
	    $param=array();
	    $data = call_api_core("uc_address","index",$param);
	    
	    if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        app_redirect(wap_url("index","user#login"));
	    }
	    
	    foreach($data['consignee_list'] as $k=>$v){
	        if ( $selected_consignee > 0 ) {
	            if ( $selected_consignee == $v['id']) {
	                $data['consignee_list'][$k]['is_select'] = 1;
	            } 
	        }else{
	            if ($v['is_default'] == 1) {
	                $data['consignee_list'][$k]['is_select'] = 1;
	            }
	        }
	        $data['consignee_list'][$k]['url']     = wap_url("index","totalbuy#index",array("conid"=>$v['id']));
	    }
	    
	    $GLOBALS['tmpl']->assign("data",$data);
	    $GLOBALS['tmpl']->display("consignee_list.html");
	}
	
	/**
	 * 订单提交页
	 */
	public function done()
	{
	    global_run();
	    init_app_page();
	    //check_form_verify();
	    $param['consignee_id'] = intval($_REQUEST['consignee_id']);
	    $data = call_api_core("totalbuy","done",$param);
	    ajax_return($data);
	 
	}
	
	
	/**
	 * 购物车的提交页
	 */
	public function pay_check()
	{
	    
	    global_run();
	    init_app_page();
	    $param['id'] = intval($_REQUEST['id']);
	    $data = call_api_core("totalbuy", "pay_check", $param);
	    // 避免重复提交
	    //assign_form_verify();
	    
	    $data['cencel_url'] = wap_url("index");
	    if(!$GLOBALS['is_weixin'])
	    {
	        foreach($data['payment_list'] as $k=>$v)
	        {
	            if($v['code']=="Wwxjspay")
	            {
	                unset($data['payment_list'][$k]);
	            }
	        }
	    }
	    else
	    {
	        foreach($data['payment_list'] as $k=>$v)
	        {
	            if($v['code']=="Upacpwap")
	            {
	                unset($data['payment_list'][$k]);
	            }
	        }
	    }
	    
	    if($data['user_login_status']==-1)
	    {
	        app_redirect(wap_url("index","user#login"));
	    }
	    
	    if ($data['status'] == 0) {
	        showErr($data['info']);
	    }
	    
	    if(empty($data['order_info']))
	    {
	        app_redirect(wap_url("index"));
	    }
	    //wap端默认支付id
	    $value=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."payment where is_default_wap = 1");
	    if($value){
	        $GLOBALS['tmpl']->assign("payment_id",$value);
	    }
	    $account_id=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."payment where is_default_wap = 1 and is_effect = 1 and class_name = 'Account'");
	    $GLOBALS['tmpl']->assign("account_id",$account_id);
	    
	    $account_amount = round($GLOBALS['user_info']['money'],2);
	    $GLOBALS['tmpl']->assign("account_amount",$account_amount);
	    $GLOBALS['tmpl']->assign("data",$data);
	    
	     $GLOBALS['tmpl']->display("totalbuy_pay_check.html");
	
	}
	
	public function pay_done(){
	    
	    global_run();
	    init_app_page();
	    
	    
	    $param['ecvsn']             = $_REQUEST['ecvsn']?addslashes(trim($_REQUEST['ecvsn'])):'';
	    $param['ecvpassword']       = $_REQUEST['ecvpassword']?addslashes(trim($_REQUEST['ecvpassword'])):'';
	    $param['payment']           = intval($_REQUEST['payment']);
	    $param['all_account_money'] = intval($_REQUEST['all_account_money']);
	    $param['content']           = strim($_REQUEST['content']);
	    $param['order_id']          = intval($_REQUEST['order_id']);
	    //check_form_verify();
	    
	    $data = call_api_core("totalbuy","pay_done",$param);
	    
	    $ajaxobj['is_app'] = $data['is_app'];
	    $ajaxobj['order_id'] = $data['order_id'];
	    // $ajaxobj['reload_url'] = SITE_DOMAIN.wap_url("index","cart#order",array("id"=>$data['order_id']));
	    $ajaxobj['reload_url'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id']));
	    $ajaxobj['success_url'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id'], 'is_done'=>1));
	    if( $data['user_login_status']==-1 )
	    {
	        $ajaxobj['status'] = 1;
	        $ajaxobj['jump'] = wap_url("index","user#login");
	        ajax_return($ajaxobj);
	    }
	    elseif($data['status']==1)
	    {
	        $ajaxobj['status'] = 1;
	        $ajaxobj['jump'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id']));
	    
	        ajax_return($ajaxobj);
	    }
	    elseif($data['status']==2) //sdk
	    {
	        $ajaxobj['status'] = 2;
	        $ajaxobj['sdk_code'] = $data['sdk_code'];
	        ajax_return($ajaxobj);
	    }
	    else
	    {
	        $ajaxobj['status'] = $data['status'];
	        $ajaxobj['info'] = $data['info'];
	        ajax_return($ajaxobj);
	    }
	}
	
	
	 
 
}
?>