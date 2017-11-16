<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class cartModule extends MainBaseModule
{

	public function index()
	{
		global_run();		
		init_app_page();
		$type = strim($_REQUEST['type']);
		
		$data = call_api_core("cart","index",array('type'=>$type));
 
		if(empty($data['cart_list']))
		{
			//app_redirect(wap_url("index"));
		}
		
		$GLOBALS['tmpl']->assign("sms_lesstime",load_sms_lesstime());
		$GLOBALS['tmpl']->assign("data",$data);
                
		//生成json数据
		$jsondata = array();
		foreach($data['cart_list'] as $k=>$v)
		{       
			$bind_data = array();
			$bind_data['id'] = $v['id'];
			$bind_data['residue_count'] = $v['residue_count'];
			$bind_data['number'] = $v['number'];
			$bind_data['min_buy'] = $v['min_buy'];
            $bind_data['unit_price'] = $v['unit_price'];
			
			$jsondata[$v['id']] = $bind_data;
		}
		
		//判断是否是X天内注册的新用户
		$is_new_member = 1; //是新用户
		$times = $GLOBALS['user_info']['create_time'];
		$days = intval(app_conf("USER_REGISTER_COUPONS_DAYS"));
		$deadline = $times + $days*24*3600;
		if( $deadline < NOW_TIME){
		    $is_new_member = 0;
		}

        $balance = $GLOBALS['user_info']['money']+$GLOBALS['user_info']['can_use_give_money']+$GLOBALS['user_info']['fx_money']+$GLOBALS['user_info']['admin_money'];


		$GLOBALS['tmpl']->assign('balance',$balance);//判断用户账户可用余额
		$GLOBALS['tmpl']->assign("type",$type);//是否是免费购专区
		$GLOBALS['tmpl']->assign("is_new_member",$is_new_member);//是否为新会员
		$GLOBALS['tmpl']->assign("jsondata",json_encode($jsondata));
		$GLOBALS['tmpl']->display("cart.html");
	}
	
	public function addcart(){
	    global_run();
		$is_relate = false;
		$ids = $_REQUEST['id'];

	    if( !empty($ids)&&(is_array($ids)) ){
			$is_relate = true;
			$data = call_api_core("cart","addcartByRelate",array("ids"=>$ids,"deal_attr"=>$_REQUEST['dealAttrArray'], "staff_id"=>$_REQUEST['staff_id'], "main_id"=>$_REQUEST['main_id']));
		}else{
			$id = intval($ids);
			$deal_attr = array();
			if($_REQUEST['deal_attr'])
			{
				foreach($_REQUEST['deal_attr'] as $k=>$v)
				{
					$deal_attr[$k] = intval($v);
				}
			}
			$data = call_api_core("cart","addcart",array("id"=>$id,"deal_attr"=>$deal_attr, 'staff_id'=>$_REQUEST['staff_id']));
		}
		
	    $ajax_data = array();
	    $ajax_data['status'] = $data['status'];
	    if($data['status']==1)
	    {
	    	$ajax_data['jump'] = wap_url("index","cart");
	    }
	    elseif($data['status']==-1)
	    {
	    	$ajax_data['jump'] = wap_url("index","user#login");
	    }
	    else
	    {
			if( $is_relate ){
				//有没有购买成功的商品
//				$ajax_data['info'] = array();
//				foreach($data as $kk=>$info){
//					if( in_array($kk,$ids) ){
//						$ajax_data['info'][$kk] = $info;
//					}
//				}
				$ajax_data['jump'] = wap_url("index","cart");
			}else{
				$ajax_data['info'] = $data['info'];
			}
	    }
	    
	    ajax_return($ajax_data);
	}
	
	public function check_cart()
	{
		global_run();
		$type = $_REQUEST['type'];

		$num = array();
	    if($_REQUEST['num'])
	    {
	    	foreach($_REQUEST['num'] as $k=>$v)
	    	{
	    		$num[$k] = intval($v);
	    	}
	    }
	    
	    $mobile = strim($_REQUEST['mobile']);
	    $sms_verify = strim($_REQUEST['sms_verify']);
	    
	    $data = call_api_core("cart","check_cart",array("num"=>$num, "mobile"=>$mobile,"sms_verify"=>$sms_verify,"type"=>$type));


	    if($data['status'])
	    {
	    	if ($type == 'free') {
	    	    $ajaxdata['jump'] = wap_url("index","cart#free_check",array('type'=>$type));
	    	}
	    	else{
	    	    $ajaxdata['jump'] = wap_url("index","cart#check");
	    	}
	    	$ajaxdata['status'] = 1;
	    	ajax_return($ajaxdata);
	    }
	    elseif($data['status']==-1)
	    {
	    	$ajaxdata['status'] = -1;
	    	$ajaxdata['info'] = $data['info'];
	    	$ajaxdata['jump'] = wap_url("index","user#login");
	    	ajax_return($ajaxdata);
	    }
	    else
	    {
	    	$ajaxdata['status'] = 0;
	    	$ajaxdata['info'] = $data['info'];
            $ajaxdata['expire_ids'] = $data['expire_ids']?$data['expire_ids']:array();
	    	ajax_return($ajaxdata);
	    }
	}
	

	/* 免费购购物车提交  */
	public function free_check(){
	    global_run();
	    init_app_page();
	    //避免重复提交
	    //assign_form_verify();
	    
	    //判断是否是X天内注册的新用户
	    $is_new_member = 1; //是新用户
	    $times = $GLOBALS['user_info']['create_time']; 
	    $days = intval(app_conf("USER_REGISTER_COUPONS_DAYS"));
	    $deadline = $times + $days*24*3600;
	    if( $deadline < NOW_TIME){
	        $is_new_member = 0;
	    }
	    if($is_new_member == 0){
	        app_redirect(wap_url("index"));
	    }
	    
	    $data = call_api_core("cart","free_check");
	    $data['cencel_url'] = wap_url("index");
	    
	    if($data['status']==-1)
	    {
	        app_redirect(wap_url("index","user#login"));
	    }
	    
	    if(empty($data['cart_list']))
	    {
	        app_redirect(wap_url("index"));
	    }
	    
	    $data['type']='free';
	    $GLOBALS['tmpl']->assign("data",$data);
	    $GLOBALS['tmpl']->display("free_cart_check.html");
	    
	}
	
	public function check()
	{
		global_run();		
		init_app_page();

        //避免重复提交
        //assign_form_verify();

        $balance = $GLOBALS['user_info']['money']+$GLOBALS['user_info']['can_use_give_money']+$GLOBALS['user_info']['fx_money']+$GLOBALS['user_info']['admin_money'];

        if($balance<1){
            app_redirect(wap_url("index","uc_charge"));
        }

		$data = call_api_core("cart","check");
		$data['cencel_url'] = wap_url("index");
		if(!$GLOBALS['is_weixin'])
		{
			foreach($data['payment_list'] as $k=>$v)
			{
				if($v['code']=="Wwxjspay")
				{
					unset($data['payment_list'][$k]);
				}
				if (!empty($v['logo'])){
				    $data['payment_list'][$k]['logo']=$v['logo'];
				}
			}
		}
		/* else
		{
			foreach($data['payment_list'] as $k=>$v)
			{
				if($v['code']=="Upacpwap")
				{
					unset($data['payment_list'][$k]);
				}
			}
		} */

		if($data['status']==-1)
		{
			app_redirect(wap_url("index","user#login"));
		}
		
		if(empty($data['cart_list']))
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
        $account_amount_now = $GLOBALS['user_info']['money']+$GLOBALS['user_info']['can_use_give_money']+$GLOBALS['user_info']['fx_money']+$GLOBALS['user_info']['admin_money'];
		$account_amount = round($account_amount_now,2);
		$GLOBALS['tmpl']->assign("account_amount",$account_amount);
		$GLOBALS['tmpl']->assign("data",$data);
                
		$GLOBALS['tmpl']->display("cart_check.html");
	}
	
	public function order()
	{
	    global_run();		
		init_app_page();
		
		$order_id = intval($_REQUEST['id']);
	    $data = call_api_core("cart","order",array("id"=>$order_id));
	    $data['order_id'] = $order_id;
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
		/* else
		{
			foreach($data['payment_list'] as $k=>$v)
			{
				if($v['code']=="Upacpwap")
				{
					unset($data['payment_list'][$k]);
				}
			}
		} */

		if($data['status']==-1)
		{
			app_redirect(wap_url("index","user#login"));
		}
		
		if(empty($data['cart_list']))
		{
			app_redirect(wap_url("index"));
		}
		
		$account_amount = round($GLOBALS['user_info']['money'],2);
		$GLOBALS['tmpl']->assign("account_amount",$account_amount);
		$GLOBALS['tmpl']->assign("data",$data);
                
		$GLOBALS['tmpl']->display("cart_order.html");
	}

    public function done()
	{
		global_run();
		$param['ecvsn'] = $_REQUEST['ecvsn']?addslashes(trim($_REQUEST['ecvsn'])):'';
		$param['ecvpassword'] = $_REQUEST['ecvpassword']?addslashes(trim($_REQUEST['ecvpassword'])):'';
		$param['payment'] = intval($_REQUEST['payment']);
		$param['all_account_money'] = intval($_REQUEST['all_account_money']);
		$param['content'] = strim($_REQUEST['content']);
		//check_form_verify();
		$data = call_api_core("cart","done",$param);
		$ajaxobj['is_app'] = $data['is_app'];
		$ajaxobj['order_id'] = $data['order_id'];
		// $ajaxobj['reload_url'] = SITE_DOMAIN.wap_url("index","cart#order",array("id"=>$data['order_id']));
		$ajaxobj['reload_url'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id']));
		$ajaxobj['success_url'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id'], 'is_done'=>1));
		if($data['status']==-1)
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
	
	public function order_done()
	{
	    global_run();
	    $param['ecvsn'] = $_REQUEST['ecvsn']?addslashes(trim($_REQUEST['ecvsn'])):'';
	    $param['ecvpassword'] = $_REQUEST['ecvpassword']?addslashes(trim($_REQUEST['ecvpassword'])):'';
	    $param['payment'] = intval($_REQUEST['payment']);
	    $param['all_account_money'] = intval($_REQUEST['all_account_money']);
	    $param['content'] = strim($_REQUEST['content']);
	    $param['order_id'] = intval($_REQUEST['order_id']);
	
	    
	    $data = call_api_core("cart","order_done",$param);
	
	    $ajaxobj['is_app'] = $data['is_app'];
	    $ajaxobj['order_id'] = $data['order_id'];
	   
	    $ajaxobj['reload_url'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id']));
	    $ajaxobj['success_url'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id'], 'is_done'=>1));
	    if($data['status']==-1)
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
	
	public function free_done(){
	    global_run();
	 
		$param['payment'] = 0;
		$param['all_account_money'] = 1;
		$param['content'] = strim($_REQUEST['content']);

		//check_form_verify();
		$data = call_api_core("cart","free_done",$param);
		
		$ajaxobj['is_app'] = $data['is_app'];
		$ajaxobj['order_id'] = $data['order_id'];
		$ajaxobj['reload_url'] = SITE_DOMAIN.wap_url("index","payment#free_done",array("id"=>$data['order_id']));
		$ajaxobj['success_url'] = SITE_DOMAIN.wap_url("index","payment#free_done",array("id"=>$data['order_id'], 'is_done'=>1));
		
		
		if($data['status']==-1)
		{
			$ajaxobj['status'] = 1;
			$ajaxobj['jump'] = wap_url("index","user#login");
			
			ajax_return($ajaxobj);
		}
		elseif($data['status']==1)
		{
			$ajaxobj['status'] = 1;
			$ajaxobj['jump'] = SITE_DOMAIN.wap_url("index","payment#free_done",array("id"=>$data['order_id']));
			
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