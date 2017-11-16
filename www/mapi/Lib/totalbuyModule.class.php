<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class totalbuyApiModule extends MainBaseApiModule
{

	public function index()
	{
	    $root = array ();
	    $root['page_title']        = '确认订单';
	    // 用户检测
	    $user_info = $GLOBALS['user_info'];
	    
	    
	    
	    // 验证登录状态
	    $user_login_status = check_login ();
	    $root ['user_login_status'] = 1;
	    if ($user_login_status != LOGIN_STATUS_LOGINED) {
	        $root ['user_login_status'] = $user_login_status;
	        return output ( $root, - 1, "请先登录用户" );
	    }

	    $user_id    = intval( $user_info['id'] );
	    $user_money = $user_info['money'];
	    
	    require_once APP_ROOT_PATH."system/model/cart.php";
	    $cart_result = load_totalbuy_cart_list();
	    
	    if ($cart_result['status'] == 0) {
	        return output( $root, 0, $cart_result['info'] );
	    }
	    
	     
	    $cart_result['cart_data']['deal_icon'] = get_abs_img_root ( get_spec_image ( $cart_result['cart_data']['deal_icon'], 186, 186, 1 ) );
	   
	    
	    //输出所有配送方式
	    $consignee_id = intval($GLOBALS['request']['consignee_id']);
	    if ($consignee_id > 0) {
	        $consignee = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where id=".$consignee_id." and user_id = ".$user_id);
	    }else{
	        $consignee = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where is_default=1 and user_id = ".$user_id);
	    }
	    
	    if($consignee){
	        $consignee_info            = load_auto_cache("consignee_info", array("consignee_id"=>$consignee['id']));
	        $consignee['del_url']      = url('index','uc_consignee#del', array('id'=>$consignee['id']));
	        $consignee['dfurl']        = url('index','uc_consignee#set_default', array('id'=>$consignee['id']));
	        $consignee['region_lv2']   = $consignee_info['consignee_info']['region_lv2_name'];
	        $consignee['region_lv3']   = $consignee_info['consignee_info']['region_lv3_name'];
	        $consignee['region_lv4']   = $consignee_info['consignee_info']['region_lv4_name'];
	    }
	    
	    $root['consignee']         = $consignee;
	    $root['cart_result']       = $cart_result;
	    $root['user_money']        = $user_money;
		return output($root);
	}
	
	/**
	 * 订单提交
	 */
	public function done(){
	    require_once APP_ROOT_PATH . "system/model/cart.php";
	    require_once APP_ROOT_PATH . "system/model/deal_order.php";
	    
	    // 验证购物车
	    $cart_result = load_totalbuy_cart_list();
	    $goods_item = $cart_result['cart_data'];
	    
	    $root = array();
	    $ajax = 1;
	    
	    if($cart_result['cart_data']['is_fictitious'] != 1){
	        $consignee_id = intval($GLOBALS['request']['consignee_id']);
    	    if ($consignee_id <= 0) {
    	        return output( $root, - 1, "请选择收货地址" );
    	    }
	    }
	    
	    // 验证登录
	    $user_login_status = check_login ();
	    $root ['user_login_status'] = 1;
	    if ($user_login_status != LOGIN_STATUS_LOGINED) {
	        $root ['user_login_status'] = $user_login_status;
	        return output( $root, - 1, "请先登录用户" );
	    }
	    
	    $user_info = $GLOBALS['user_info'];
	    $user_id   = $user_info['id'];
	    
	     
	    if (! $goods_item) {
	        return output( $root,  0,  $GLOBALS['lang']['CART_EMPTY_TIP'] );
	    }
	    
	    require_once APP_ROOT_PATH . "system/model/duobao.php";
	    // 检测库存
	    $res = duobao::check_totalbuy_number($goods_item['duobao_item_id'], $goods_item['number']);
	    if($res['status']==0){
	        return output( $root, 0, $res['info'] );
	    }
	      
	     
	    if($cart_result['cart_data']['is_fictitious'] != 1){
    	    // 获取用户地址
    	    $consignee_info    = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user_consignee where id=".$consignee_id." and user_id=" . $user_id );
    	    if (!$consignee_info) {
    	        return output( $root, 0, '收货地址不存在');
    	    }
	    }
	    
	    $region_conf       = load_auto_cache ( "delivery_region" );
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
	        return output( $root, 0, '库存不足,订单提交失败。');
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
	    
	    
	    $goods_item ['id']                     = '';
	    $goods_item ['deal_id']                = $goods_item ['deal_id'];
	    $goods_item ['duobao_id']              = $goods_item ['duobao_id'];
	    $goods_item ['duobao_item_id']         = $goods_item ['duobao_item_id'];
	    $goods_item ['number']                 = $goods_item ['number'];
	    $goods_item ['unit_price']             = $goods_item ['unit_price'];
	    $goods_item ['total_price']            = $goods_item ['total_price'];
	    $goods_item ['name']                   = $goods_item ['name'];
	    $goods_item ['delivery_status']        = 0;
	    $goods_item ['return_score']           = $goods_item ['return_score'];
	    $goods_item ['return_total_score']     = $goods_item ['return_total_score'];
	    $goods_item ['verify_code']            = $goods_item ['verify_code'];
	    $goods_item ['order_sn']               = $order ['order_sn'];
	    $goods_item ['order_id']               = $order_id;
	    $goods_item ['is_arrival']             = 0;
	    $goods_item ['deal_icon']              = $goods_item ['deal_icon'];
	    $goods_item ['user_id']                = $user_id;
	    $goods_item ['duobao_ip']              = $order ['duobao_ip'];
	    $goods_item ['duobao_area']            = $order ['duobao_area'];
	    $goods_item ['type']                   = $order ['type'];
	    $goods_item['create_time']             = NOW_TIME;
	    $goods_item['create_date_ymd']         = to_date(NOW_TIME,"Y-m-d");
	    $goods_item['create_date_ym']          = to_date(NOW_TIME,"Y-m");
	    $goods_item['create_date_y']           = to_date(NOW_TIME,"Y");
	    $goods_item['create_date_m']           = to_date(NOW_TIME,"m");
	    $goods_item['create_date_d']           = to_date(NOW_TIME,"d");
	    
	    $goods_item['consignee']                = strim ( $consignee_info ['consignee'] );
	    $goods_item['mobile']                   = strim ( $consignee_info ['mobile'] );
	    $goods_item['region_info']              = $region_info;
	    $goods_item['address']                  = strim ( $consignee_info ['address'] );
	    $goods_item['is_set_consignee']         = 1;
	    
	    	
	    $GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order_item", $goods_item, 'INSERT', '', 'SILENT' );
	    
	    
	    
	    // 删除购物车直购商品
	    $GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "deal_cart where is_total_buy=1 and session_id = '" . es_session::id () . "'" );
	     
	    $root['status'] = 1;
	    $root['jump'] = wap_url( "index","totalbuy#pay_check", array("id"=>$order_id) );
	    return output( $root); 
	    
	}

	
	public function pay_check(){
	    
	    $root = array();
	    // 验证登录状态
	    $user_login_status = check_login ();
	    $root ['user_login_status'] = 1;
	    if ($user_login_status != LOGIN_STATUS_LOGINED) {
	        $root ['user_login_status'] = $user_login_status;
	        return output ( $root, - 1, "请先登录用户" );
	    }
	 
	    $user_info = $GLOBALS['user_info'];
	    $user_id   = $user_info['id'];
	    
	    // 获取订单信息
	    $order_id = intval($GLOBALS['request']['id']);
	    $order_info = $GLOBALS['db']->getRow("select do.*, doi.name deal_name, doi.number  from ".DB_PREFIX."deal_order do, ".DB_PREFIX."deal_order_item doi  where doi.order_id=do.id and do.order_status=0 and do.user_id = ".$user_id." and do.id=".$order_id);
	    
	    
	    // 判断失效，20分钟后失效
	    $expir_time = 20 * 60 + $order_info['create_time'];
	    if (NOW_TIME > $expir_time) {
	        require_once APP_ROOT_PATH . "system/model/deal_order.php";
	        // 过期的订单，修改状态为关闭 返还库存
	        cancel_totalbuy_order($order_info);
	        return output( $root, 0, '商品已过期，请重新下单。');
	    }
	    
	    // 统计几个专区 1P K区 2十元区 3百元区 4直购区 5极速区 6选号区 7一元区
	    $area = array();
	    $area['range_value5']=4;
	    
	    $root ['order_info'] = $order_info ? $order_info : null;
	    // 输出支付方式
	    $is_app = $GLOBALS ['is_app'];
	    if (! $is_app) {
	        // 支付列表
	        $sql = "select id, class_name as code, logo from " . DB_PREFIX . "payment where (online_pay = 2 or online_pay = 4 or online_pay = 5 or online_pay = 6 or online_pay=7) and is_effect = 1";
	    } else {
	        // 支付列表
	        $sql = "select id, class_name as code, logo from " . DB_PREFIX . "payment where (online_pay = 3 or online_pay = 2 or online_pay = 4 or online_pay = 6) and is_effect = 1";
	    }
	    if (allow_show_api ()) {
	        $payment_list = $GLOBALS ['db']->getAll ( $sql );
	    }
	    
	    // 输出支付方式
	    foreach ( $payment_list as $k => $v ) {
	        $directory = APP_ROOT_PATH . "system/payment/";
	        $file = $directory . '/' . $v ['code'] . "_payment.php";
	        if (file_exists ( $file )) {
	            require_once ($file);
	            $payment_class = $v ['code'] . "_payment";
	            $payment_object = new $payment_class ();
	            $payment_list [$k] ['name'] = $payment_object->get_display_code ();
	        }
	        	
	        if ($v ['logo'] != "")
	            $payment_list [$k] ['logo'] = get_abs_img_root ( get_spec_image ( $v ['logo'], 40, 40, 1 ) );
	    }
	    
	    sort ( $payment_list );
	    $root ['payment_list'] = $payment_list;
	    
	    if ($order_info['total_price'] > 0)
	        $show_payment = 1;
	    else
	        $show_payment = 0;
	    $root ['show_payment'] = $show_payment;
	    
	    if ($show_payment) {
	        $web_payment_list = load_auto_cache ( "cache_payment" );
	        	
	        foreach ( $web_payment_list as $k => $v ) {
	            if ($v ['class_name'] == "Account" && $GLOBALS ['user_info'] ['money'] > 0) {
	                $root ['has_account'] = 1;
	            }
	            if ($v ['class_name'] == "Voucher") {
	                
	                // 判断是否使用过红包
	                $evc = $GLOBALS['db']->getOne("select ecv_money from ".DB_PREFIX."deal_order where id = ".$order_info['id']);
	                if ($evc > 0) {
	                    unset($payment_list[$k]);
	                    continue;
	                }
	                $root ['has_ecv'] = 1;
	                $sql = "select e.sn as sn,e.is_all as is_all,e.data as data,t.name as name from " . DB_PREFIX . "ecv as e left join " . DB_PREFIX . "ecv_type as t on e.ecv_type_id = t.id where " . " e.user_id = '" . $GLOBALS ['user_info'] ['id'] . "' and (e.begin_time < " . NOW_TIME . ") and (e.end_time = 0 or e.end_time > " . NOW_TIME . ") and e.meet_amount <= " .$order_info['total_price']. " and (e.use_limit = 0 or e.use_count<e.use_limit)";
	                $root ['voucher_list'] = $GLOBALS ['db']->getAll ( $sql );
	            }
	        }
	    } else {
	        $root ['has_account'] = 0;
	        $root ['has_ecv'] = 0;
	    }
	    $use_all=array();
	    // 统计哪些红包可以用 1	P K区 2十元区 3百元区 4直购区 5极速区 6选号区 7一元区
	    foreach ($root ['voucher_list'] as $key=>$value){
	        // 判断data 里面的数据，在订单专区里面是否有
	        if($value['is_all']==1){
	            $use_all[$key] =  $value;
	        }else{
	            if($value['data']){
	                $json_data = json_decode($value['data'], 1);
	                foreach($area as $k => $v){
	                    if(in_array($v,$json_data['domain'])){
	                        $use_all[$key] =  $value;
	                    }
	                }
	            }
	    
	        }
	    }
	    $root ['voucher_list']=$use_all;
	    $root ['page_title'] = "支付订单";
	    $root ['account_money'] = round ( $GLOBALS ['user_info'] ['money'], 2 );
	    return output ( $root );
	    
	}
	
	public function pay_done(){
	    
	    $root = array();
	    // 验证登录状态
	    $user_login_status = check_login ();
	    $root ['user_login_status'] = 1;
	    if ($user_login_status != LOGIN_STATUS_LOGINED) {
	        $root ['user_login_status'] = $user_login_status;
	        return output ( $root, - 1, "请先登录用户" );
	    }
	    
	    $user_info = $GLOBALS['user_info'];
	    $user_id   = $user_info['id'];
	    
	    
	    $order_id              = intval($GLOBALS['request']['order_id']);
	    $payment               = intval ( $GLOBALS ['request'] ['payment'] );
	    $all_account_money     = intval ( $GLOBALS ['request'] ['all_account_money'] );
	    $ecvsn                 = $GLOBALS ['request'] ['ecvsn'] ? strim ( $GLOBALS ['request'] ['ecvsn'] ) : '';
	    $ecvpassword           = $GLOBALS ['request'] ['ecvpassword'] ? strim ( $GLOBALS ['request'] ['ecvpassword'] ) : '';
	    $memo                  = strim ( $GLOBALS ['request'] ['content'] );
	   
	    $GLOBALS['db']->query("UPDATE `".DB_PREFIX."deal_order` SET `payment_id`={$payment} WHERE id={$order_id}");
	    
	    $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where order_status=0 and user_id = ".$user_id." and id=".$order_id);
	    if (!$order_info) {
	        return output( $root, 0, '支付订单不存，请重新下单。');
	    }
	    // 开始验证订单接交信息
	    $data = count_buy_totalbuy( $payment, 0, $all_account_money, $ecvsn, $ecvpassword, $order_info, $order_info['account_money'], $order_info['ecv_money'], '' );

	    if (round ( $data ['pay_price'], 4 ) > 0 && !$data ['payment_info']) {
	        return output ( array (), 0, "请选择支付方式" );
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
	    
	    // //3. 相应的支付接口
	    // $payment_info = $data['payment_info'];
	    // if($payment_info&&$data['pay_price']>0)
	    // {
	    // $payment_notice_id =
	    // make_payment_notice($data['pay_price'],'',$order_id,$payment_info['id']);
	    // //创建支付接口的付款单
	    // }
	    $rs = order_paid ( $order_id );
	    if ($rs) {
	        // 支付成功后的操作
	        $root ['pay_status'] = 1;
	        $root ['order_id'] = $order_id;
	    } else {
	        $payment_info = $data ['payment_info'];
	        if ($payment_info ['online_pay'] == 3 && $data ['pay_price'] > 0) 			// sdk在线支付
	        {
	            $payment_notice_id = make_payment_notice ( $data ['pay_price'], '', $order_id, $payment_info ['id'] );
	            require_once APP_ROOT_PATH . "system/payment/" . $payment_info ['class_name'] . "_payment.php";
	            $payment_class = $payment_info ['class_name'] . "_payment";
	            $payment_object = new $payment_class ();
	            $payment_code = $payment_object->get_payment_code ( $payment_notice_id );
	            $root ['pay_status'] = 0;
	            $root ['order_id'] = $order_id;
	            $root ['sdk_code'] = $payment_code ['sdk_code'];
	            return output ( $root, 2 ); // sdk支付
	        } else {
	            $root ['pay_status'] = 0;
	            $root ['order_id'] = $order_id;
	        }
	        $root ['is_app'] = intval ( $GLOBALS ['is_app'] );
	    }
	    return output ( $root );
	    
	}
	
	/**
	 * 全部商品列表接口
	 * 输入：
	 * data_id: int 分页ID
	 * page:int 当前的页数
	 * keyword:string
	
	 *
	 * 输出：
	 array (
	 'page' =>
	 array (
	 'total' => '7',  分页总数
	 'page_size' => 20, 分页大小
	 ),
	 'list' =>
	 array (
	 0 =>
	 array (
	 'id' => '10000342',   夺宝id
	 'name' => '初体验3小时家务保洁！提前1天预约！',  夺宝商品名称
	 'max_buy' => '1000',    总需要次数
	 'current_buy' => '90',    当前购买次数
	 'surplus_buy' => '910',   剩余次数
	 'icon' => './public/attachment/201509/18/16/55fbcc815d651.jpg',  夺宝商品
	 )
	 )
	 */
	public function lists(){
	    $page_size = PAGE_SIZE;
	    $data_id = intval($GLOBALS['request']['data_id']);
	    $page      = intval($GLOBALS['request']['page']);
	    $keyword = strim($GLOBALS['request']['keyword']);
	
	
	    if($data_id>0)
	        $cate_item = $GLOBALS['db']->getRow("select id,name from ".DB_PREFIX."deal_cate where id = ".$data_id);
	
	        $sql_count = "SELECT count(*)
                    FROM
                    	".DB_PREFIX."duobao_item di,
                        ".DB_PREFIX."deal d
    		        WHERE
                        d.total_buy_stock > 0 AND
                        d.id = di.deal_id AND
    		            di.is_effect = 1 AND
                    	di.is_total_buy = 1 AND
    		            di.success_time = 0 ";
	        if($cate_item)
	        {
	            $sql_count .=" and cate_id = ".$cate_item['id']." ";
	        }
	
	        if($keyword)
	        {
	            $sql_count .=" and name like '%".$keyword."%' ";
	        }
	
	        if ($page == 0) $page = 1;
	        $limit = (($page - 1) * $page_size) . "," . $page_size;
	        $total = $GLOBALS['db']->getOne($sql_count);
	        $page_data['total'] = $total;
	        $page_data['page_size'] = $page_size;
	
	        $sql = "SELECT di.id, di.unit_price, di.name, di.max_buy, di.min_buy, di.current_buy, (di.max_buy-di.current_buy) as surplus_buy, di.icon, d.total_buy_stock
                    FROM
                    	".DB_PREFIX."duobao_item di,
                    	".DB_PREFIX."deal d
    		        WHERE
    		            d.total_buy_stock > 0 AND d.id = di.deal_id AND di.is_effect = 1 AND di.is_total_buy=1 AND di.success_time = 0 ";
	        if($cate_item)
	        {
	            $sql .=" and di.cate_id = ".$cate_item['id']." ";
	        }
	        if($keyword)
	        {
	            $sql .=" and di.name like '%".$keyword."%' ";
	        }
	
	        $sql.=" ORDER BY di.id DESC ";
	        $list = $GLOBALS['db']->getAll($sql ." limit " . $limit);
	        foreach($list as $k=>$v)
	        {
	            $list[$k]['icon'] = get_abs_img_root(get_spec_image($v['icon'],280,280,1));
	        }
	
	        require_once APP_ROOT_PATH."system/model/duobao.php";
	        $cart_info=duobao::getcart($GLOBALS['user_info']['id']);
	        $data['cart_info']=$cart_info;
	
	        /* 分页 */
	        $data['page'] = $page_data;
	        $data['list'] = $list;
	        if($cate_item)
	            $data['page_title'] = $cate_item['name'];
	            elseif($keyword)
	            $data['page_title'] = $keyword."搜索结果";
	            else
	                $data['page_title'] ="全价购";
	                return output($data);
	                 
	
	}
 

}
?>
