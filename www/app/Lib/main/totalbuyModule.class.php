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
     * 显示购物车商品，准备提交订单
     * @see MainBaseModule::index()
     */
	public function index()
	{		
		global_run();
		init_app_page();
		//避免重复提交
		//assign_form_verify();
		
		if( check_save_login() != LOGIN_STATUS_LOGINED )
		{
		    app_redirect(url("index","user#login"));
		}
		
		$user_id = intval($GLOBALS['user_info']['id']);
		$user_money = $GLOBALS['user_info']['money'];
		
		require_once APP_ROOT_PATH."system/model/cart.php";
		$cart_result = load_totalbuy_cart_list();
	 
		
		//输出所有配送方式
		$consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id = ".$user_id);
		foreach($consignee_list as $k=>$v){
		    $consignee_info=load_auto_cache("consignee_info",array("consignee_id"=>$v['id']));
		    $consignee_list[$k]['del_url']    = url('index','uc_consignee#del',array('id'=>$v['id']));
		    $consignee_list[$k]['dfurl']      = url('index','uc_consignee#set_default',array('id'=>$v['id']));
		    $consignee_list[$k]['region_lv2'] = $consignee_info['consignee_info']['region_lv2_name'];
		    $consignee_list[$k]['region_lv3'] = $consignee_info['consignee_info']['region_lv3_name'];
		    $consignee_list[$k]['region_lv4'] = $consignee_info['consignee_info']['region_lv4_name'];
		}
		 
		$GLOBALS['tmpl']->assign("consignee_list", $consignee_list);
        $GLOBALS['tmpl']->assign("is_totalbuy",1);
		$GLOBALS['tmpl']->assign("count_consignee", count($consignee_list));
		$GLOBALS['tmpl']->assign("cart_result", $cart_result);
		$GLOBALS['tmpl']->assign("user_money",$user_money);
		$GLOBALS['tmpl']->display("totalbuy.html");
	}
	
	/**
	 * 提交订单
	 */
	public function done(){
	    global_run();
	    require_once APP_ROOT_PATH . "system/model/cart.php";
	    require_once APP_ROOT_PATH . "system/model/deal_order.php";
	    $cart_result = load_totalbuy_cart_list();
	    
	    // 验证购物车
	    $goods_item = $cart_result['cart_data'];
	    $ajax = 1;
	    //check_form_verify();
	    
	    if($cart_result['cart_data']['is_fictitious'] != 1){
	        $consignee_id = intval($_REQUEST['consignee_id']);
	        if ($consignee_id <= 0) {
	            showErr('请选择收货地址', $ajax);
	        }
	    }
	    
	    // 验证登录
	    if( check_save_login() != LOGIN_STATUS_LOGINED )
	    {
	        showErr($GLOBALS['lang']['PLEASE_LOGIN_FIRST'], $ajax,url("index","user#login"));
	    }
	    
	    if (! $goods_item) {
	        showErr($GLOBALS['lang']['CART_EMPTY_TIP'],$ajax,url("index"));
	    }
	
	    require_once APP_ROOT_PATH . "system/model/duobao.php";
	     
	    // 检测库存
	    $res = duobao::check_totalbuy_number($goods_item['duobao_item_id'], $goods_item['number']);
	    if($res['status']==0){
	        showErr($res['info'],$ajax);
	    }
	    
	
	    $user_id = $GLOBALS ['user_info'] ['id'];
	    
        if($cart_result['cart_data']['is_fictitious'] != 1){
            // 获取用户地址
            $consignee_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user_consignee where id=".$consignee_id." and user_id=" . $user_id );
            if (!$consignee_info) {
                showErr('收货地址不存在', $ajax);
            }
        }
	    
	    $region_conf   = load_auto_cache ( "delivery_region" );
	    $region_lv1    = intval ( $consignee_info ['region_lv1'] );
	    $region_lv2    = intval ( $consignee_info ['region_lv2'] );
	    $region_lv3    = intval ( $consignee_info ['region_lv3'] );
	    $region_lv4    = intval ( $consignee_info ['region_lv4'] );
	    $region_info   = $region_conf [$region_lv1] ['name'] . " " . $region_conf [$region_lv2] ['name'] . " " . $region_conf [$region_lv3] ['name'] . " " . $region_conf [$region_lv4] ['name'];
	    
	    
	    // 避免库存为负数，提交订单的时候先减库存：1.如果减库存成功，提交订单  2.如果减库存失败，提交订单失败  3：如果用户未付款，取消订单，或订单到期自动关闭的，库存退回
	    $number = $goods_item['number'];
        $GLOBALS['db']->query("UPDATE `".DB_PREFIX."deal` SET `total_buy_stock`=total_buy_stock-{$number} WHERE total_buy_stock >={$number}  and id={$goods_item['deal_id']}");
        $affected_rows = $GLOBALS['db']->affected_rows();
        if($affected_rows <= 0){
            showErr('库存不足,订单提交失败。', $ajax );
        }
	    
	    // 开始生成订单
	    $now                       = NOW_TIME;
	    $order ['type']            = 3; // 直购订单
	    $order ['user_id']         = $user_id;
	    $order ['create_time']     = $now;
	    $order ['update_time']     = $now;
	    $order ['total_price']     = $goods_item['total_price']; // 应付总额 商品价 - 会员折扣 + 运费
	 
	    // + 支付手续费
	    $order ['pay_amount']          = 0;
	    $order ['pay_status']          = 0; // 新单都为零， 等下面的流程同步订单状态
	    $order ['delivery_status']     = 0;
	    $order ['order_status']        = 0; // 新单都为零， 等下面的流程同步订单状态
	    $order ['return_total_score']  = $goods_item ['return_total_score']; // 结单后送的积分
	    $order ['memo']                = '';
	    
	    // 地址待定
	    $order ['region_info']     = $region_info;
	    $order ['address']         = strim ( $consignee_info ['address'] );
	    $order ['mobile']          = strim ( $consignee_info ['mobile'] );
	    $order ['consignee']       = strim ( $consignee_info ['consignee'] );
	    $order ['zip']             = strim ( $consignee_info ['zip'] );
	
	    $order ['ecv_money']       = 0;
	    $order ['account_money']   = 0;
	    $order ['ecv_sn']          = '';
	    
	    $order ['payment_id']  = 0;
	    $order ['bank_id']     = "";
	
	    // 更新来路
	    $order ['referer']     = $GLOBALS ['referer'];
	    $user_info             = es_session::get ( "user_info" );
	    $order ['user_name']   = $user_info ['user_name'];
	
	    $order ['duobao_ip']   = CLIENT_IP;
	    
	    require_once APP_ROOT_PATH . "system/extend/ip.php";
	    $ip                    = new iplocate ();
	    $area                  = $ip->getaddress ( CLIENT_IP );
	    $order ['duobao_area'] = $area ['area1'];
	
	    $order['create_date_ymd']  = to_date(NOW_TIME,"Y-m-d");
	    $order['create_date_ym']   = to_date(NOW_TIME,"Y-m");
	    $order['create_date_y']    = to_date(NOW_TIME,"Y");
	    $order['create_date_m']    = to_date(NOW_TIME,"m");
	    $order['create_date_d']    = to_date(NOW_TIME,"d");
	     
	    do {
	        $order ['order_sn'] = to_date ( NOW_TIME, "Ymdhis" ) . rand ( 10, 99 );
	        $GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order", $order, 'INSERT', '', 'SILENT' );
	        $order_id = intval ( $GLOBALS ['db']->insert_id () );
	    } while ( $order_id == 0 );
	    
	    // 生成订单商品
	    $goods_item['id']                       = '';
        $goods_item['deal_id']                  = $goods_item['deal_id'];
        $goods_item['duobao_id']                = $goods_item['duobao_id'];
        $goods_item['duobao_item_id']           = $goods_item['duobao_item_id'];
        $goods_item['number']                   = $goods_item['number'];
        $goods_item['unit_price']               = $goods_item['unit_price'];
        $goods_item['total_price']              = $goods_item['total_price'];
        $goods_item['name']                     = $goods_item['name'];
        $goods_item['delivery_status']          = 0;
        $goods_item['return_score']             = $goods_item['return_score'];
        $goods_item['return_total_score']       = $goods_item['return_total_score'];
        $goods_item['verify_code']              = $goods_item['verify_code'];
        $goods_item['order_sn']                 = $order['order_sn'];
        $goods_item['order_id']                 = $order_id;
        $goods_item['is_arrival']               = 0;
        $goods_item['deal_icon']                = $goods_item['deal_icon'];
        $goods_item['user_id']                  = $user_id;
        $goods_item['duobao_ip']                = $order['duobao_ip'];
        $goods_item['duobao_area']              = $order['duobao_area'];
        $goods_item['type']                     = $order['type'];
        $goods_item['create_time']              = NOW_TIME;
        $goods_item['create_date_ymd']          = to_date(NOW_TIME,"Y-m-d");
        $goods_item['create_date_ym']           = to_date(NOW_TIME,"Y-m");
        $goods_item['create_date_y']            = to_date(NOW_TIME,"Y");
        $goods_item['create_date_m']            = to_date(NOW_TIME,"m");
        $goods_item['create_date_d']            = to_date(NOW_TIME,"d");
        
        $goods_item['consignee']                = strim ( $consignee_info ['consignee'] );
        $goods_item['mobile']                   = strim ( $consignee_info ['mobile'] );
        $goods_item['region_info']              = $region_info;
        $goods_item['address']                  = strim ( $consignee_info ['address'] );
        $goods_item['is_set_consignee']         = 1;
        
        
        $GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order_item", $goods_item, 'INSERT', '', 'SILENT' );
	    
	    // 删除购物车直购商品
	    $GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "deal_cart where is_total_buy=1 and session_id = '" . es_session::id () . "'" );
	    
	    $return_data['status'] = 1;
        $return_data['is_total_buy']=1;
	    $return_data['jump'] = url("index","totalbuy#pay_check",array("id"=>$order_id));
	    ajax_return($return_data);
	   
	}
	
	public function pay_check(){
	    
	    global_run();
	    init_app_page();
	    require_once APP_ROOT_PATH . "system/model/deal_order.php";
	    //避免重复提交
	    //assign_form_verify();
	    if( check_save_login() != LOGIN_STATUS_LOGINED ){
	        app_redirect(url("index","user#login"));
	    }
	    
	    $user_id = intval($GLOBALS['user_info']['id']);
	    $user_money = $GLOBALS['user_info']['money'];
	    
	    // 获取订单信息
	    $order_id = intval($_REQUEST['id']);
	    $order_info = $GLOBALS['db']->getRow("select do.*, doi.deal_id, doi.name deal_name, doi.number  from ".DB_PREFIX."deal_order do, ".DB_PREFIX."deal_order_item doi  where doi.order_id=do.id and do.order_status=0 and do.pay_status=0 and do.user_id = ".$user_id." and do.id=".$order_id);
	    
	    // 判断失效，20分钟后失效
	    $expir_time = 20 * 60 + $order_info['create_time'];
	    if (NOW_TIME > $expir_time) {
	        // 过期的订单，修改状态为关闭 返还库存
	        cancel_totalbuy_order($order_info);
	        showErr('商品已过期，请重新下单。', 0, url("index","index#index") );
	    }
	    
	    // 统计几个专区 1P K区 2十夺宝币区 3百夺宝币区 4直购区 5极速区 6选号区 7一夺宝币区
	    $area = array();
	    $area['range_value5']=4;
	    
	    // 有效订单操作
	    if ($order_info) {
	        $GLOBALS['tmpl']->assign("order_info", $order_info);
	        //输出支付方式
	        $payment_list = load_auto_cache("cache_payment");
	        
	        
	         
	        $icon_paylist = array(); //用图标展示的支付方式
	        $disp_paylist = array(); //特殊的支付方式(Voucher,Account,Otherpay)
	        $bank_paylist = array(); //网银直连
	         
	        $wx_payment = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name = 'Wwxjspay'");
	        if($wx_payment)
	        {
	            $wx_payment['config'] = unserialize($wx_payment['config']);
	            if($wx_payment['config']['scan']==1)
	            {
	                $directory = APP_ROOT_PATH."system/payment/";
	                $file = $directory. '/' .$wx_payment['class_name']."_payment.php";
	                if(file_exists($file))
	                {
	                    require_once($file);
	                    $payment_class = $wx_payment['class_name']."_payment";
	                    $payment_object = new $payment_class();
	                    $wx_payment['display_code'] = $payment_object->get_web_display_code();
	                    $disp_paylist[] = $wx_payment;
	                }
	            }
	        }
	         
	        foreach($payment_list as $k=>$v)
	        {
	            if($v['class_name']=="Voucher"||$v['class_name']=="Account"||$v['class_name']=="Otherpay")
	            {
	                if($v['class_name']=="Account")
	                {
	                    $directory = APP_ROOT_PATH."system/payment/";
	                    $file = $directory. '/' .$v['class_name']."_payment.php";
	                    if(file_exists($file))
	                    {
	                        require_once($file);
	                        $payment_class = $v['class_name']."_payment";
	                        $payment_object = new $payment_class();
	                        $v['display_code'] = $payment_object->get_display_code();
	                    }
	                }
	                if($v['class_name']=="Voucher")
	                {
	                    // 判断是否使用过红包
	                    $evc = $GLOBALS['db']->getOne("select ecv_money from ".DB_PREFIX."deal_order where id = ".$order_info['id']);
	                    if ($evc > 0) {
	                         unset($payment_list[$k]);
	                         continue;
	                    }
	                   
	                    
	                    $directory = APP_ROOT_PATH."system/payment/";
	                    $file = $directory. '/' .$v['class_name']."_payment.php";
	                    if(file_exists($file))
	                    {
	                        require_once($file);
	                        $payment_class = $v['class_name']."_payment";
	                        $payment_object = new $payment_class();
	                        $v['display_code'] = $payment_object->get_display_code($area,$order_info['total_price']);
	                    }
	                     
	                }
	                 
	                $disp_paylist[] = $v;
	            }
	            else
	            {
	                if($v['is_bank']==1)
	                    $bank_paylist[] = $v;
	                else
	                    $icon_paylist[] = $v;
	            }
	        }
	         
	        //pc端支付方式后台设置默认id
	        $value=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."payment where is_effect = 1 and is_default_pc = 1");
	        if($value){
	            $GLOBALS['tmpl']->assign("payment_id",$value);
	        }
	         
	        //支付方式显示
	        $GLOBALS['tmpl']->assign("icon_paylist",$icon_paylist);
	        $GLOBALS['tmpl']->assign("disp_paylist",$disp_paylist);
	        $GLOBALS['tmpl']->assign("bank_paylist",$bank_paylist);
	         
	        //用户信息显示
	        $GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
	         
	         
	        //支付方式显示
	        $GLOBALS['tmpl']->assign("show_payment",true);
	        
	        
	         
	        //关于短信发送的条件
	        $GLOBALS['tmpl']->assign("sms_lesstime",load_sms_lesstime());
	        $GLOBALS['tmpl']->assign("sms_ipcount",load_sms_ipcount());
	    }else{
	        $order_info['order_status'] = -1;
	    }
        $GLOBALS['tmpl']->assign("is_total_buy",1);
	    $GLOBALS['tmpl']->assign("order_info", $order_info);
	    $GLOBALS['tmpl']->display("totalbuy_check.html");
	}
	
	public function pay_done(){
	    global_run();
	    init_app_page();
	    require_once APP_ROOT_PATH . "system/model/deal_order.php";
	    
	    $ajax = 1;
	    if( check_save_login() != LOGIN_STATUS_LOGINED ){
	        app_redirect(url("index","user#login"));
	    }
	    
	    $order_id          = intval($_REQUEST['id']);
	    $payment           = intval ( $_REQUEST['payment'] );
	    $account_money     = floatval($_REQUEST['account_money']);
	    $all_account_money = intval($_REQUEST['all_account_money']);
	    
	    $ecvsn         = $_REQUEST['ecvsn'] ? strim ( $_REQUEST['ecvsn'] ) : '';
	    $ecvpassword   = $_REQUEST['ecvpassword'] ? strim ( $_REQUEST['ecvpassword'] ) : '';
	    $memo          = strim ( $_REQUEST['content'] );
	    
	    
	    $GLOBALS['db']->query("UPDATE `".DB_PREFIX."deal_order` SET `payment_id`={$payment} WHERE id={$order_id}");
	    $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where type=3 and id = ".$order_id);
	        
	    if (!$order_info) {
	        showErr('支付订单不存，请重新下单。', $ajax, url("index","index#index") );
	    }
	    
	    // 更新订单表
	    // 开始验证订单接交信息
	    require_once APP_ROOT_PATH."system/model/cart.php";
	    $data = count_buy_totalbuy ( $payment, $account_money, $all_account_money, $ecvsn, $ecvpassword, $order_info, $order_info['account_money'], $order_info['ecv_money'], '' );
	     
	    if( round($data['pay_price'],4)>0&&!$data['payment_info'] )
	    {
	        showErr($GLOBALS['lang']['PLEASE_SELECT_PAYMENT'], $ajax);
	    }
	    
	    // 检查避免重复提交
	    //check_form_verify();
	   
	    $deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where order_id = ".$order_id);
	    
	    // 判断失效，20分钟后失效
	    $expir_time = 20 * 60 + $order_info['create_time'];
	    if (NOW_TIME > $expir_time) {
	        // 过期的订单，修改状态为关闭 返还库存
	        cancel_totalbuy_order($order_info);
	        showErr('商品已过期，请重新下单。', 0, url("index","index#index") );
	    }
	    
	    // 判断是否使用过红包
	    $evc = $GLOBALS['db']->getOne("select ecv_money from ".DB_PREFIX."deal_order where id = ".$order_info['id']);
	    if ($evc == 0) {
	        // 1. 代金券支付
	        $ecv_data = $data ['ecv_data'];
	        if ($ecv_data) {
	            $ecv_payment_id = $GLOBALS ['db']->getOne ( "select id from " . DB_PREFIX . "payment where class_name = 'Voucher'" );
	            if ($ecv_data ['money'] > $order_info ['total_price'])
	                $ecv_data ['money'] = $order_info ['total_price'];
	            $payment_notice_id = make_payment_notice ( $ecv_data ['money'],'', $order_id, $ecv_payment_id, "", $ecv_data ['id'] );
	            require_once APP_ROOT_PATH . "system/payment/Voucher_payment.php";
	            $voucher_payment = new Voucher_payment ();
	            $voucher_payment->direct_pay ( $ecv_data ['sn'], $ecv_data ['password'], $payment_notice_id );
	        }
	    }
	    
	    // 2. 余额支付
	    $account_money = $data ['account_money'];
	    if (floatval ( $account_money ) > 0) {
	        $account_payment_id = $GLOBALS ['db']->getOne ( "select id from " . DB_PREFIX . "payment where class_name = 'Account'" );
	        $payment_notice_id = make_payment_notice ( $account_money,'', $order_id, $account_payment_id );
	        require_once APP_ROOT_PATH . "system/payment/Account_payment.php";
	        $account_payment = new Account_payment ();
	        $account_payment->get_payment_code ( $payment_notice_id );
	    }
	    
	    //3. 相应的支付接口
	    $payment_info = $data['payment_info'];
	    if($payment_info&&$data['pay_price']>0)
	    {
	        $payment_notice_id = make_payment_notice($data['pay_price'],'',$order_id,$payment_info['id']);
	        //创建支付接口的付款单
	    }
	    
	    $rs = order_paid($order_id);
	    
	    if($rs)
	    {
	        $data = array();
	        $data['info'] = "";
	        $data['jump'] = url("index","payment#done",array("id"=>$order_id));
	        ajax_return($data); //支付成功
	    
	    }
	    else
	    {
	        $data = array();
	        $data['info'] = "";
	        $data['jump'] = url("index","payment#pay",array("id"=>$payment_notice_id));
	        ajax_return($data);
	    }
	}
	
	public function save_consignee()
	{
	    global_run();
	    $user_id = intval($GLOBALS['user_info']['id']);
	     
	    if(check_save_login()!=LOGIN_STATUS_LOGINED)
	    {
	        $result['status'] = 2;
	        ajax_return($result);
	    }
	    $jump_type = strim($_REQUEST['jump_type']);
	
	
	
	    $consignee_id = intval($_REQUEST['consignee_id']);
	    $consignee_count=intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_consignee where user_id = ".$GLOBALS['user_info']['id']));
	
	    if($consignee_count>=5&&$consignee_id ==0){
	        $result['status'] = 3;
	        ajax_return($result);
	    }
	    //所在地区空限制
	    if(!strim($_REQUEST['region_lv2']))
	    {
	        showErr("选择您的省份",1);
	    }
	    if(!strim($_REQUEST['region_lv3']))
	    {
	        showErr($GLOBALS['lang']['SELECT_YOUR_CITY'],1);
	    }
	    if(!strim($_REQUEST['region_lv4']))
	    {
	        showErr("选择您的地区",1);
	    }
	
	    if(strim($_REQUEST['consignee'])=='')
	    {
	        showErr($GLOBALS['lang']['FILL_CORRECT_CONSIGNEE'],1);
	    }
	    if(strim($_REQUEST['address'])=='')
	    {
	        showErr($GLOBALS['lang']['FILL_CORRECT_ADDRESS'],1);
	    }
	
	    if(strim($_REQUEST['mobile'])=='')
	    {
	        showErr($GLOBALS['lang']['FILL_MOBILE_PHONE'],1);
	    }
	    if(!check_mobile($_REQUEST['mobile']))
	    {
	        showErr($GLOBALS['lang']['FILL_CORRECT_MOBILE_PHONE'],1);
	    }
	
	    $consignee_data['user_id'] = $GLOBALS['user_info']['id'];
	    $consignee_data['region_lv1'] = intval($_REQUEST['region_lv1']);
	    $consignee_data['region_lv2'] = intval($_REQUEST['region_lv2']);
	    $consignee_data['region_lv3'] = intval($_REQUEST['region_lv3']);
	    $consignee_data['region_lv4'] = intval($_REQUEST['region_lv4']);
	    $consignee_data['address'] = strim($_REQUEST['address']);
	    $consignee_data['mobile'] = strim($_REQUEST['mobile']);
	    $consignee_data['consignee'] = strim($_REQUEST['consignee']);
	    $consignee_data['zip'] = strim($_REQUEST['zip']);
	    $consignee_data['id_card'] = strim($_REQUEST['id_card']);
	    $consignee_data['is_default'] = intval($_REQUEST['is_default']);
	
	    if($consignee_count==0)
	    {
	        $consignee_data['is_default'] = 1;
	    }
	
	    if($consignee_id == 0)
	    {
	        if($consignee_count>0 && $consignee_data['is_default']==1){
	            $GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",array("is_default"=>0),"UPDATE","user_id=".$GLOBALS['user_info']['id']);
	        }
	        $GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$consignee_data);
	        $consignee_id = $GLOBALS['db']->insert_id();
	    }
	    else
	    {
	         
	        // 如果设置当前为默认地址，其它则设置为非默认
	        if($consignee_data['is_default']==1){
	            $GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",array("is_default"=>0),"UPDATE", "user_id=".$GLOBALS['user_info']['id']);
	        }
	
	        $GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$consignee_data,"UPDATE","id=".$consignee_id." and user_id=".$GLOBALS['user_info']['id']);
	    }
	
	    rm_auto_cache("consignee_info",array("consignee_id"=>intval($consignee_id)));
	    $result['status'] = 1;
	
	    //由幸运记录详情过来的地址保存
	    if($jump_type == 'uc_luck_detail'){
	        $order_item_id = intval($_REQUEST['order_item_id']);
	        $jump = url("index","uc_luck#detail",array("id"=>$order_item_id));
	    }
	
	    if($jump){
	        $result['url'] = $jump;
	    }else
	        $result['url'] = url('index','uc_consignee');
	     
	     
	    //输出所有配送方式
	    $consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id = ".$user_id);
	    foreach($consignee_list as $k=>$v){
	        $consignee_info=load_auto_cache("consignee_info",array("consignee_id"=>$v['id']));
	        $consignee_list[$k]['del_url']=url('index','uc_consignee#del',array('id'=>$v['id']));
	        $consignee_list[$k]['dfurl']=url('index','uc_consignee#set_default',array('id'=>$v['id']));
	        $consignee_list[$k]['region_lv2']=  $consignee_info['consignee_info']['region_lv2_name'];
	        $consignee_list[$k]['region_lv3']=  $consignee_info['consignee_info']['region_lv3_name'];
	        $consignee_list[$k]['region_lv4']=  $consignee_info['consignee_info']['region_lv4_name'];
	    }
	
	    $GLOBALS['tmpl']->assign("consignee_list", $consignee_list);
	    // 插入成功返回地址html
	    $consignee_li = $GLOBALS['tmpl']->fetch("inc/totalbuy_consignee_li.html");
	    $result['consignee_li'] = $consignee_li;
	    ajax_return($result);
	
	}
 
}
?>