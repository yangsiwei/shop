<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
function load_cart_list($reload = false,$type=0) {

	if($type==1){  //$type==1时，查询所有的购物记录，包括无效的
		$cart_list_res = $GLOBALS ['db']->getAll ( "select dc.*,di.is_total_buy,di.is_number_choose,di.is_pk,di.is_topspeed,di.max_buy,di.current_buy,di.min_buy,di.unit_price,di.origin_price,di.user_max_buy from " . DB_PREFIX . "deal_cart as dc left join " . DB_PREFIX . "duobao_item as di on di.id = dc.duobao_item_id  where dc.is_number_choose=0 and dc.is_total_buy = 0 and dc.is_coupons=0 and dc.is_pk=0 and dc.user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
	}else{
		$cart_list_res = $GLOBALS ['db']->getAll ( "select dc.*,di.is_total_buy,di.is_number_choose,di.is_pk,di.is_topspeed,di.max_buy,di.current_buy,di.min_buy,di.unit_price,di.origin_price,di.user_max_buy from " . DB_PREFIX . "deal_cart as dc left join " . DB_PREFIX . "duobao_item as di on di.id = dc.duobao_item_id  where dc.is_number_choose=0 and dc.is_total_buy = 0 and dc.is_coupons=0 and dc.is_pk=0 and dc.user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and dc.is_effect=1" );
	}
	$cart_list = array ();
	foreach ( $cart_list_res as $k => $v ) {
		$v ['url'] = url ( "index", "duobao&id=" . $v ['duobao_item_id'] );
		$v ['residue_count'] = $v ['max_buy'] - $v ['current_buy'];
		$v ['value_price'] =$v ['max_buy']*$v ['unit_price'];
		$cart_list [$v ['id']] = $v;
	}
	if($type==1){  //$type==1时，查询所有的购物记录，包括无效的
		$total_data = $GLOBALS ['db']->getRow ( "select sum(total_price) as total_price,sum(return_total_score) as return_total_score from " . DB_PREFIX . "deal_cart where  is_total_buy = 0 and is_coupons=0 and is_number_choose=0 and is_pk=0  and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
	}else{
		$total_data = $GLOBALS ['db']->getRow ( "select sum(total_price) as total_price,sum(return_total_score) as return_total_score from " . DB_PREFIX . "deal_cart where   is_total_buy = 0 and is_coupons=0 and is_number_choose=0  and is_pk=0 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and is_effect=1" );
	}
	$total_data ['cart_item_number'] = count ( $cart_list );
	$result = array (
			"cart_list" => $cart_list,
			"total_data" => $total_data
	);
	// 有操作程序就更新购物车状态
	$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_cart set update_time=" . NOW_TIME . " where is_total_buy = 0 and is_coupons=0 and is_number_choose=0 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
	return $result;
}

function load_deal_order_list($order_id) {
    $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id." and is_delete = 0 and pay_status <> 2 and order_status <> 1 and is_coupons=0 and user_id =".intval($GLOBALS['user_info']['id']));
    $cart_list_res = $GLOBALS['db']->getAll("select doi.*,di.origin_price from ".DB_PREFIX."deal_order_item as doi left join ".DB_PREFIX."duobao_item as di on di.id = doi.duobao_item_id  where doi.order_id = ".$order_info['id']);
    
    $cart_list = array ();
    foreach ( $cart_list_res as $k => $v ) {
        $v ['url'] = url ( "index", "duobao&id=" . $v ['duobao_item_id'] );
        $v ['residue_count'] = $v ['max_buy'] - $v ['current_buy'];
        $v ['value_price'] =$v ['max_buy']*$v ['unit_price'];
        $cart_list [$v ['id']] = $v;
    }
     
    $total_data['total_price']          = $order_info['total_price']; 
    $total_data['return_total_score']   = $order_info['return_total_score'];

    $total_data ['cart_item_number'] = count ( $cart_list );
    $result = array (
        "cart_list" => $cart_list,
        "total_data" => $total_data
    );
    return $result;
}

/**
 * 获取购物车全价购买的商品
 */
function load_totalbuy_cart_list() {
    $total_data = array();
    $cart_data = $GLOBALS ['db']->getRow( "select id, name, deal_id, duobao_id, duobao_item_id, unit_price, number, total_price, deal_icon, return_score, return_total_score from " . DB_PREFIX . "deal_cart where is_total_buy=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and is_effect=1" );
    if($cart_data['duobao_item_id']){
        $cate_id =  $GLOBALS['db']->getOne("select cate_id from ".DB_PREFIX."duobao_item where id = ".$cart_data['duobao_item_id']);
        $is_fictitious = $GLOBALS['db']->getOne("select is_fictitious from ".DB_PREFIX."deal_cate where id = ".$cate_id);
    }
    
    if ($cart_data) {
        $cart_data['url']                  = url ( "index", "duobao&id=" . $cart_data ['duobao_item_id'] );
        $cart_data['unit_price_format']    = format_sprintf_price($cart_data['unit_price']);
        $cart_data['total_price_format']   = format_sprintf_price($cart_data['total_price']);
        $total_data['total_price_format']  = format_sprintf_price($cart_data['total_price']);
        $cart_data['is_fictitious']   = $is_fictitious;
    }
    
    if ($cart_data) {
        $result = array (
            "status" => 1,
            "cart_data" => $cart_data,
            "total_data" => $total_data,
        );
    }else{
        $result = array (
            "status" => 0,
            "info" => '没有选择全价购买的商品',
        );
    }
    // 有操作程序就更新购物车状态
    $GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_cart set update_time=" . NOW_TIME . " where is_total_buy=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ));
    return $result;
}
function load_pk_cart_list_by_totalbuy() {
    $total_data = array();
    $cart_data = $GLOBALS ['db']->getRow( "select id, name, deal_id, duobao_id, duobao_item_id, unit_price, number, total_price, deal_icon, return_score, return_total_score from " . DB_PREFIX . "deal_cart where is_pk=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and is_effect=1" );
//    if($cart_data['duobao_item_id']){
//        $cate_id =  $GLOBALS['db']->getOne("select cate_id from ".DB_PREFIX."duobao_item where id = ".$cart_data['duobao_item_id']);
//        $is_fictitious = $GLOBALS['db']->getOne("select is_fictitious from ".DB_PREFIX."deal_cate where id = ".$cate_id);
//    }

    if ($cart_data) {
        $cart_data['url']                  = url ( "index", "duobao&id=" . $cart_data ['duobao_item_id'] );
        $cart_data['unit_price_format']    = format_sprintf_price($cart_data['unit_price']);
        $cart_data['total_price_format']   = format_sprintf_price($cart_data['total_price']);
        $total_data['total_price_format']  = format_sprintf_price($cart_data['total_price']);
//        $cart_data['is_fictitious']   = $is_fictitious;
    }

    if ($cart_data) {
        $result = array (
            "status" => 1,
            "cart_data" => $cart_data,
            "total_data" => $total_data,
        );
    }else{
        $result = array (
            "status" => 0,
            "info" => '没有选择pk的商品',
        );
    }
    // 有操作程序就更新购物车状态
    $GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_cart set update_time=" . NOW_TIME . " where is_pk=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ));
    return $result;
}
function load_number_choose_cart_list_by_totalbuy() {
    $total_data = array();
    $cart_data = $GLOBALS ['db']->getRow( "select id, name, deal_id, duobao_id, duobao_item_id, unit_price, number, total_price, deal_icon, return_score, return_total_score from " . DB_PREFIX . "deal_cart where is_number_choose=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and is_effect=1" );
//    if($cart_data['duobao_item_id']){
//        $cate_id =  $GLOBALS['db']->getOne("select cate_id from ".DB_PREFIX."duobao_item where id = ".$cart_data['duobao_item_id']);
////        $is_fictitious = $GLOBALS['db']->getOne("select is_fictitious from ".DB_PREFIX."deal_cate where id = ".$cate_id);
//    }

    if ($cart_data) {
        $cart_data['url']                  = url ( "index", "duobao&id=" . $cart_data ['duobao_item_id'] );
        $cart_data['unit_price_format']    = format_sprintf_price($cart_data['unit_price']);
        $cart_data['total_price_format']   = format_sprintf_price($cart_data['total_price']);
        $total_data['total_price_format']  = format_sprintf_price($cart_data['total_price']);
//        $cart_data['is_fictitious']   = $is_fictitious;
    }

    if ($cart_data) {
        $result = array (
            "status" => 1,
            "cart_data" => $cart_data,
            "total_data" => $total_data,
        );
    }else{
        $result = array (
            "status" => 0,
            "info" => '没有选择选号的商品',
        );
    }
    // 有操作程序就更新购物车状态
    $GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_cart set update_time=" . NOW_TIME . " where is_number_choose=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ));
    return $result;
}
function load_number_choose_cart_list($reload = false,$type=0) {
    if($type==1){  //$type==1时，查询所有的购物记录，包括无效的
        $cart_list_res = $GLOBALS ['db']->getAll ( "select dc.*,di.is_number_choose,di.is_pk,di.is_topspeed,di.max_buy,di.current_buy,di.min_buy,di.unit_price,di.origin_price,di.user_max_buy from " . DB_PREFIX . "deal_cart as dc left join " . DB_PREFIX . "duobao_item as di on di.id = dc.duobao_item_id  where di.is_number_choose=1 and dc.is_total_buy = 0 and dc.is_coupons=0 and dc.user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );

    }else{
        $cart_list_res = $GLOBALS ['db']->getAll ( "select dc.*,di.is_number_choose,di.is_pk,di.is_topspeed,di.max_buy,di.current_buy,di.min_buy,di.unit_price,di.origin_price,di.user_max_buy from " . DB_PREFIX . "deal_cart as dc left join " . DB_PREFIX . "duobao_item as di on di.id = dc.duobao_item_id  where di.is_number_choose=1 and dc.is_total_buy = 0 and dc.is_coupons=0 and dc.user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and dc.is_effect=1" );
    }
    $cart_list = array ();
    foreach ( $cart_list_res as $k => $v ) {
        $v ['url'] = url ( "index", "duobao&id=" . $v ['duobao_item_id'] );
        $v ['residue_count'] = $v ['max_buy'] - $v ['current_buy'];
        $v ['value_price'] =$v ['max_buy']*$v ['unit_price'];
        $cart_list [$v ['id']] = $v;
    }
    if($type==1){  //$type==1时，查询所有的购物记录，包括无效的
        $total_data = $GLOBALS ['db']->getRow ( "select sum(total_price) as total_price,sum(return_total_score) as return_total_score from " . DB_PREFIX . "deal_cart where  is_total_buy = 0 and is_coupons=0 and is_number_choose=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
    }else{
        $total_data = $GLOBALS ['db']->getRow ( "select sum(total_price) as total_price,sum(return_total_score) as return_total_score from " . DB_PREFIX . "deal_cart where   is_total_buy = 0 and is_coupons=0 and is_number_choose=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and is_effect=1" );
    }

    $total_data ['cart_item_number'] = count ( $cart_list );
    $result = array (
        "cart_list" => $cart_list,
        "total_data" => $total_data
    );
    // 有操作程序就更新购物车状态
    $GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_cart set update_time=" . NOW_TIME . " where is_total_buy = 0 and is_coupons=0 and is_number_choose =1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
    return $result;
}
function load_pk_cart_list($reload = false,$type=0) {
    if($type==1){  //$type==1时，查询所有的购物记录，包括无效的
        $cart_list_res = $GLOBALS ['db']->getAll ( "select dc.*,di.is_number_choose,di.pk_password,di.has_password,di.is_pk,di.is_topspeed,di.max_buy,di.current_buy,di.min_buy,di.unit_price,di.origin_price,di.user_max_buy from " . DB_PREFIX . "deal_cart as dc left join " . DB_PREFIX . "duobao_item as di on di.id = dc.duobao_item_id  where di.is_number_choose=0 and di.is_pk=1 and dc.is_total_buy = 0 and dc.is_coupons=0 and dc.user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
    }else{
        $cart_list_res = $GLOBALS ['db']->getAll ( "select dc.*,di.is_number_choose,di.pk_password,di.has_password,di.is_pk,di.is_topspeed,di.max_buy,di.current_buy,di.min_buy,di.unit_price,di.origin_price,di.user_max_buy from " . DB_PREFIX . "deal_cart as dc left join " . DB_PREFIX . "duobao_item as di on di.id = dc.duobao_item_id  where di.is_number_choose=0 and di.is_pk=1 and dc.is_total_buy = 0 and dc.is_coupons=0 and dc.user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and dc.is_effect=1" );
    }
    $cart_list = array ();
    foreach ( $cart_list_res as $k => $v ) {
        $v ['url'] = url ( "index", "duobao&id=" . $v ['duobao_item_id'] );
        $v ['residue_count'] = $v ['max_buy'] - $v ['current_buy'];
        $v ['value_price'] =$v ['max_buy']*$v ['unit_price'];
        $cart_list [$v ['id']] = $v;
    }
    if($type==1){  //$type==1时，查询所有的购物记录，包括无效的
        $total_data = $GLOBALS ['db']->getRow ( "select sum(total_price) as total_price,sum(return_total_score) as return_total_score from " . DB_PREFIX . "deal_cart where  is_total_buy = 0 and is_coupons=0 and is_pk=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
    }else{
        $total_data = $GLOBALS ['db']->getRow ( "select sum(total_price) as total_price,sum(return_total_score) as return_total_score from " . DB_PREFIX . "deal_cart where   is_total_buy = 0 and is_coupons=0 and is_pk=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and is_effect=1" );
    }

    $total_data ['cart_item_number'] = count ( $cart_list );
    $result = array (
        "cart_list" => $cart_list,
        "total_data" => $total_data
    );
    // 有操作程序就更新购物车状态
    $GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_cart set update_time=" . NOW_TIME . " where is_total_buy = 0 and is_coupons=0 and is_pk =1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
    return $result;
}

/**
 * 刷新购物车，过期超时
 */
function refresh_cart_list() {
	$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "deal_cart where " . NOW_TIME . " - update_time > " . SESSION_TIME );
}

// 计算购买价格
/**
 * payment //支付ID
 * account_money //支付余额
 * all_account_money //是否全额支付
 * ecvsn //代金券帐号
 * ecvpassword //代金券密码
 * goods_list //统计的商品列表
 * $paid_account_money 已支付过的余额
 * $paid_ecv_money 已支付过的代金券
 *
 * 返回 array(
 * 'total_price'	=>	$total_price,	商品总价
 * 'pay_price'		=>	$pay_price, 支付费用
 * 'pay_total_price'		=>	$total_price, 应付总费用
 * 'payment_info' =>	$payment_info, 支付方式
 * 'account_money'	=>	$account_money, 余额支付
 * 'ecv_money'		=>	$ecv_money,		代金券金额
 * 'ecv_data'		=>	$ecv_data, 代金券数据
 * 'return_total_score'	=>	$return_total_score, 购买返积分
 */
function count_buy_total($payment, $account_money, $all_account_money, $ecvsn, $ecvpassword, $goods_list, $paid_account_money = 0, $paid_ecv_money = 0, $bank_id = '') {
	// 获取商品总价
	$pay_price = 0; // 支付总价
	$total_price = 0;
	$return_total_score = 0;

	foreach ( $goods_list as $k => $v ) {
		$total_price += $v ['total_price'];
		$return_total_score += $v ['return_total_score'];
	}

	$pay_price = $total_price;

	$pay_price = $pay_price - $paid_account_money - $paid_ecv_money;

	$user_id = intval ( $GLOBALS ['user_info'] ['id'] );

	// 余额支付
	$user_money = $GLOBALS['user_info']['money']+$GLOBALS['user_info']['fx_money']+$GLOBALS['user_info']['can_use_give_money']+$GLOBALS['user_info']['admin_money'];
//	$user_money = $GLOBALS ['db']->getOne ( "select money from " . DB_PREFIX . "user where id = " . $user_id );
	if ($all_account_money == 1) {
		$account_money = $user_money;
	}

	if ($account_money > $user_money)
		$account_money = $user_money; // 余额支付量不能超过帐户余额

	// 开始计算代金券
	$now = NOW_TIME;
	$ecv_sql = "select e.* from " . DB_PREFIX . "ecv as e left join " . DB_PREFIX . "ecv_type as et on e.ecv_type_id = et.id where e.sn = '" . $ecvsn . "' and ((e.begin_time <> 0 and e.begin_time < " . $now . ") or e.begin_time = 0) and " . "((e.end_time <> 0 and e.end_time > " . $now . ") or e.end_time = 0) and ((e.use_limit <> 0 and e.use_limit > e.use_count) or (e.use_limit = 0)) " . "and (e.user_id = " . $user_id . " or e.user_id = 0)";
	$ecv_data = $GLOBALS ['db']->getRow ( $ecv_sql );
	$ecv_money = $ecv_data ['money'];

	// 当余额 + 代金券 > 支付总额时优先用代金券付款 ,代金券不够付，余额为扣除代金券后的余额
	if ($ecv_money + $account_money > $pay_price) {
		if ($ecv_money >= $pay_price) {
			$ecv_use_money = $pay_price;
			$account_money = 0;
		} else {
			$ecv_use_money = $ecv_money;
			$account_money = $pay_price - $ecv_use_money;
		}
	} else {
		$ecv_use_money = $ecv_money;
	}

	// 需要付的钱 = 原本需要付的钱 - 红包支付的钱 - 用户余额；
	$pay_price = $pay_price - $ecv_use_money - $account_money;

	// 支付接口
	if ($payment != 0) {
		if ($pay_price > 0) {
			$payment_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "payment where id = " . $payment );
			$directory = APP_ROOT_PATH . "system/payment/";
			$file = $directory . '/' . $payment_info ['class_name'] . "_payment.php";
			if (file_exists ( $file )) {
				require_once ($file);
				$payment_class = $payment_info ['class_name'] . "_payment";
				$payment_object = new $payment_class ();
				if (method_exists ( $payment_object, "get_name" )) {
					$payment_info ['name'] = $payment_object->get_name ( $bank_id );
				}
			}
		}
	}
	
	$account_money = round($account_money, 2);

	if ($account_money < 0)
		$account_money = 0;

	$result = array (
			'total_price' => $total_price,
			'pay_price' => $pay_price,
			'pay_total_price' => $total_price,
			'payment_info' => $payment_info,
			'account_money' => $account_money,
			'ecv_money' => $ecv_money,
			'ecv_data' => $ecv_data,
			'return_total_score' => $return_total_score,
			'paid_account_money' => $paid_account_money,
			'paid_ecv_money' => $paid_ecv_money
	);

	return $result;
}

function count_buy_totalbuy($payment, $account_money, $all_account_money, $ecvsn, $ecvpassword, $order_info, $paid_account_money = 0, $paid_ecv_money = 0, $bank_id = '') {
    // 获取商品总价
    $pay_price          = 0; // 支付总价
    $total_price        = 0;
    $return_total_score = 0;
    
    
    $total_price        = $order_info ['total_price'];
    $return_total_score = $order_info ['return_total_score'];
    
    
    $pay_price = $total_price;
    
    $pay_price = $pay_price - $paid_account_money - $paid_ecv_money;
    
    $user_id = intval ( $GLOBALS ['user_info'] ['id'] );
    
    // 余额支付
    $user_money = $GLOBALS ['db']->getOne ( "select money from " . DB_PREFIX . "user where id = " . $user_id );
    
    // 如果全额支付
    if ($all_account_money == 1) {
        $account_money = $user_money;
    }
    
    if ($account_money > $user_money){
        $account_money = $user_money; // 余额支付量不能超过帐户余额
    }
    
    // 开始计算代金券
    $now = NOW_TIME;
    $ecv_sql = "select e.* from " . DB_PREFIX . "ecv as e left join " . DB_PREFIX . "ecv_type as et on e.ecv_type_id = et.id where e.sn = '" . $ecvsn . "' and ((e.begin_time <> 0 and e.begin_time < " . $now . ") or e.begin_time = 0) and " . "((e.end_time <> 0 and e.end_time > " . $now . ") or e.end_time = 0) and ((e.use_limit <> 0 and e.use_limit > e.use_count) or (e.use_limit = 0)) " . "and (e.user_id = " . $user_id . " or e.user_id = 0)";
    $ecv_data = $GLOBALS ['db']->getRow ( $ecv_sql );
    $ecv_money = $ecv_data ['money'];

    // 当余额 + 代金券 > 支付总额时优先用代金券付款 ,代金券不够付，余额为扣除代金券后的余额
    if ($ecv_money + $account_money > $pay_price) {
        if ($ecv_money >= $pay_price) {
            $ecv_use_money = $pay_price;
            $account_money = 0;
        } else {
            $ecv_use_money = $ecv_money;
            $account_money = $pay_price - $ecv_use_money;
        }
    } else {
        $ecv_use_money = $ecv_money;
    }

    $pay_price = $pay_price - $ecv_use_money - $account_money;

    // 支付接口
    if ($payment != 0) {
        if ($pay_price > 0) {
            $payment_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "payment where id = " . $payment );
            $directory = APP_ROOT_PATH . "system/payment/";
            $file = $directory . '/' . $payment_info ['class_name'] . "_payment.php";
            if (file_exists ( $file )) {
                require_once ($file);
                $payment_class = $payment_info ['class_name'] . "_payment";
                $payment_object = new $payment_class ();
                if (method_exists ( $payment_object, "get_name" )) {
                    $payment_info ['name'] = $payment_object->get_name ( $bank_id );
                }
            }
        }
    }

    $account_money = round($account_money, 2);
    if ($account_money < 0){
        $account_money = 0;
    }
    
    $result = array (
        'total_price' => $total_price,
        'pay_price' => $pay_price,
        'pay_total_price' => $total_price,
        'payment_info' => $payment_info,
        'account_money' => $account_money,
        'ecv_money' => $ecv_money,
        'ecv_data' => $ecv_data,
        'return_total_score' => $return_total_score,
        'paid_account_money' => $paid_account_money,
        'paid_ecv_money' => $paid_ecv_money
    );

    return $result;
}

/**
 *
 *
 * 创建付款单号
 *
 * @param $money 付款金额
 * @param $coupons_amount 应付优惠币金额
 * @param $order_id 订单ID
 * @param $payment_id 付款方式ID
 * @param $memo 付款单备注
 * @param $ecv_id 如为代金券支付，则指定代金券ID
 *        	return payment_notice_id 付款单ID
 *
 */
function make_payment_notice($money, $coupons_amount, $order_id, $payment_id, $memo = '', $ecv_id = 0,$regen = false) {
	
	$notice_id = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."payment_notice where is_paid=0 and order_id =".$order_id." and payment_id=".$payment_id." and (".NOW_TIME."-create_time<=30)");
	if(intval($notice_id)==0||$regen){
		$notice ['create_time'] = NOW_TIME;
		$notice ['order_id'] = $order_id;
		$notice ['user_id'] = $GLOBALS ['db']->getOne ( "select user_id from " . DB_PREFIX . "deal_order where id = " . $order_id );
		$notice ['payment_id'] = $payment_id;
		$notice ['memo'] = $memo;
		$notice ['money'] = $money;
		$notice ['coupons'] = $coupons_amount;
		$notice ['ecv_id'] = $ecv_id;
	    if(intval($payment_id) == 0){
		    $notice ['order_type'] = 4;//免费购订单
		}else{
		    $notice ['order_type'] = 3;
		}
		$notice ['create_date_ymd'] = to_date ( NOW_TIME, "Y-m-d" );
		$notice ['create_date_ym'] = to_date ( NOW_TIME, "Y-m" );
		$notice ['create_date_y'] = to_date ( NOW_TIME, "Y" );
		$notice ['create_date_m'] = to_date ( NOW_TIME, "m" );
		$notice ['create_date_d'] = to_date ( NOW_TIME, "d" );
		do {
			$notice ['notice_sn'] = to_date ( NOW_TIME, "Ymdhis" ) . rand ( 10, 99 );
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "payment_notice", $notice, 'INSERT', '', 'SILENT' );
			$notice_id = intval ( $GLOBALS ['db']->insert_id () );
		} while ( $notice_id == 0 );
	}
		
	return $notice_id;
}


/**
 * 付款单的支付
 *
 * @param unknown_type $payment_notice_id
 *        	当超额付款时在此进行退款处理
 */
function payment_paid($payment_notice_id) {
	require_once APP_ROOT_PATH . "system/model/user.php";
	$payment_notice_id = intval ( $payment_notice_id );
	$now = NOW_TIME;
	$GLOBALS ['db']->query ( "update " . DB_PREFIX . "payment_notice set pay_time = " . $now . ",is_paid = 1 where id = " . $payment_notice_id . " and is_paid = 0" );
	$rs = $GLOBALS ['db']->affected_rows ();
	if ($rs) {
		$payment_notice = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "payment_notice where id = " . $payment_notice_id );
		$order_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal_order where id = " . $payment_notice ['order_id'] );
		$payment_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "payment where id = " . $payment_notice ['payment_id'] );
		if ($payment_info ['class_name'] == 'Voucher') {
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set pay_amount = pay_amount + " . $payment_notice ['money'] . ",ecv_money = " . $payment_notice ['money'] . ",ecv_id=" . $payment_notice ['ecv_id'] . " where id = " . $payment_notice ['order_id'] . " and is_delete = 0 and order_status = 0 and ((pay_amount + " . $payment_notice ['money'] . " <= total_price) or " . $payment_notice ['money'] . ">=total_price)" );
            $GLOBALS ['db']->query("update ".DB_PREFIX."user set total_use_money = total_use_money +".$payment_notice['money']." where id = ".$order_info['user_id']);
			$order_incharge_rs = $GLOBALS ['db']->affected_rows ();
		} elseif ($payment_info ['class_name'] == 'Account') {
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set pay_amount = pay_amount + " . $payment_notice ['money'] . ",account_money = account_money + " . $payment_notice ['money'] . " where id = " . $payment_notice ['order_id'] . " and is_delete = 0 and order_status = 0 and pay_amount + " . $payment_notice ['money'] . " <= total_price" );
            $GLOBALS ['db']->query("update ".DB_PREFIX."user set total_use_money = total_use_money +".$payment_notice['money']." where id = ".$order_info['user_id']);
			$order_incharge_rs = $GLOBALS ['db']->affected_rows ();
		} else {
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set pay_amount = pay_amount + " . $payment_notice ['money'] . " where id = " . $payment_notice ['order_id'] . " and is_delete = 0 and order_status = 0 and pay_amount + " . $payment_notice ['money'] . " <= total_price" );
            $GLOBALS ['db']->query("update ".DB_PREFIX."user set total_use_money = total_use_money +".$payment_notice['money']." where id = ".$order_info['user_id']);
			$order_incharge_rs = $GLOBALS ['db']->affected_rows ();
		}
		$GLOBALS ['db']->query ( "update " . DB_PREFIX . "payment set total_amount = total_amount + " . $payment_notice ['money'] . " where class_name = '" . $payment_info ['class_name'] . "'" );
		if (! $order_incharge_rs && $payment_notice ['money'] > 0) 		// 订单支付超出
		{
			// 超出充值
			if ($order_info ['is_delete'] == 1 || $order_info ['order_status'] == 1)
				$msg = sprintf ( $GLOBALS ['lang'] ['DELETE_INCHARGE'], $payment_notice ['notice_sn'] );
			else
				$msg = sprintf ( $GLOBALS ['lang'] ['PAYMENT_INCHARGE'], $payment_notice ['notice_sn'] );
			modify_account ( array (
					'money' => $payment_notice ['money'],
					'score' => 0
			), $payment_notice ['user_id'], $msg );
			modify_statements ( $payment_notice ['money'], 2, $order_info ['order_sn'] . "订单超额支付" ); // 订单超额充值
			modify_statements ( $payment_notice ['money'], 0, $order_info ['order_sn'] . "订单超额支付" ); // 订单超额充值

			order_log ( $order_info ['order_sn'] . "订单超额支付，" . format_price ( $payment_notice ['money'] ) . "已退到会员余额", $order_info ['id'] );
			// 更新订单的extra_status为1
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set extra_status = 1 where is_delete = 0 and id = " . intval ( $payment_notice ['order_id'] ) );
		}

		// 在此处开始生成付款的短信及邮件
		send_payment_sms ( $payment_notice_id );
		send_payment_mail ( $payment_notice_id );
	}
	return $rs;
}

// 同步订单支付状态
function order_paid($order_id) {
	$order_id = intval ( $order_id );
	$order = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal_order where id = " . $order_id );
	
	if ($order ['pay_amount'] >= $order ['total_price']) {
		$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set pay_status = 2,create_date_ymd = '" . to_date ( NOW_TIME, "Y-m-d" ) . "',create_date_ym = '" . to_date ( NOW_TIME, "Y-m" ) . "',create_date_y = '" . to_date ( NOW_TIME, "Y" ) . "',create_date_m = '" . to_date ( NOW_TIME, "m" ) . "',create_date_d = '" . to_date ( NOW_TIME, "d" ) . "' where id =" . $order_id . " and pay_status <> 2" );
		$rs = $GLOBALS ['db']->affected_rows ();
		if ($rs) {
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order_item set pay_status = 2 where order_id = " . $order_id . " and pay_status <> 2" );
			order_log ( $order ['order_sn'] . "订单付款完成", $order_id );

			send_wx_msg ( "OPENTM201490080", $order ['user_id'], array (), array (
					"order_id" => $order_id
			) );
			 
			// 支付完成
			order_paid_done ( $order_id );
			$order = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal_order where id = " . $order_id );
			if ($order ['pay_status'] == 2 && $order ['after_sale'] == 0) {
				require_once APP_ROOT_PATH . "system/model/deal_order.php";
				distribute_order ( $order_id );
				$result = true;
			} else{
			    $result = false;
			}
		}
	} elseif ($order ['pay_amount'] < $order ['total_price'] && $order ['pay_amount'] != 0) {
		// by hc 0507
		$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set pay_status = 0 where id =" . $order_id );
		$result = false; // 订单未支付成功
	} elseif ($order ['pay_amount'] == 0) {
		$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set pay_status = 0 where id =" . $order_id );
		$result = false; // 订单未支付成功
	}
	return $result;
}

// 订单付款完毕后执行的操作,充值单也在这处理
function order_paid_done($order_id) {
	// 处理支付成功后的操作
	/**
	 * 生成幸运号
	 */
	require_once APP_ROOT_PATH . "system/model/deal_order.php";
	require_once APP_ROOT_PATH . "system/model/duobao.php";
	require_once APP_ROOT_PATH . "system/model/user.php";
	
	$user_info = $GLOBALS['user_info'];
	$order_id = intval ( $order_id );
	$stock_status = true; // 团购状态
	$order_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "deal_order where id = " . $order_id );

	if ($order_info ['type'] == 2 || $order_info ['type'] == 4) {//夺宝订单或免费购订单
		$order_goods_list = $GLOBALS ['db']->getAll ( "select * from " . DB_PREFIX . "deal_order_item where order_id = " . $order_id );

		/**
		 * 退款情况说明
		 * 一个订单里面可能有多个商品，一个商品有多个号
		 * 循环每个商品 1：生成号个数小于购买的号个数，则相应的商品退款部分钱 2：生成的号个数小于等于0的，商品全部退款 3：生成的号等于购买的则不退
		 *
		 * 这样产生了一个订单里面商品有可能部分退款，或者一个商品全部退款，相对于订单也是部分退款或全部退款
		 *
		 * 而红包支付是想对于订单的，不是相对于订单里面的商品，所以如果要做到退款的时候退红包，必须把商品的所有退款部分的总和，来作为订单整个退款的依据
		 * 订单整个退款的钱 >= 红包的，就可以先退红包，再退到余额
		 *
		 */


		$part_money  = 0; // 部分退款总金额
		$whole_money = 0; // 全部商品退款总金额

		foreach ( $order_goods_list as $k => $v ) {
			$duobao = new duobao ( $v ['duobao_item_id'] );
			$lottery_result = $duobao->make_lottery_sn ( $order_info ['user_id'], $v ['id'] );
			$duobao_number = intval($lottery_result['total']);
			$all_refund = false;
			if($duobao_number!=$v['number']){
				//部份退款
				if($duobao_number<=0){
					$all_refund = true;
				}else{

					$GLOBALS ['db']->query ( "update " . DB_PREFIX . "duobao_item set current_buy = current_buy +" . $duobao_number . ",progress = floor(current_buy/max_buy*100) where id = " . $v ['duobao_item_id']);

					$refund_number 	= $v['number'] - $duobao_number;
					$refund_money 	= $refund_number * $v['unit_price'];
					$part_money	   += $refund_money;

					$min_buy = $GLOBALS ['db']->getOne ( "select min_buy from " . DB_PREFIX . "duobao_item where id = " . $v['duobao_item_id'] );
					$return_total_score = ($refund_number/$min_buy) * $v['return_score'];
					$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order_item set number = ".$duobao_number." where id = " . $v ['id'] );
					$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set extra_status = 2, return_total_score = return_total_score - " . $return_total_score . ", refund_amount = refund_amount + " . $refund_money . " where id = " . intval ( $order_info ['id'] ) );


					$msg = "您参与的夺宝活动" . $v ['name'] . "人数已满! 退回".format_price($refund_money);


					modify_account ( array (
						'money' => $refund_money,
						'score' => 0
					), $order_info ['user_id'], $msg );
					order_log ( $msg .",". format_price ( $refund_money ) . "已退到会员中心", $order_info ['id'] );

					// 增加退款到会员中心的充值记录
					modify_statements ( $refund_money, 2, $msg );

					// 收入
					modify_statements ( $refund_money, 0, $msg ); // 总收入

					// 记录退款的订单日志
					$log ['log_info'] = $msg;
					$log ['log_time'] = NOW_TIME;
					$log ['order_id'] = intval ( $order_info ['id'] );
					$GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order_log", $log );
				}
			}else{
				$GLOBALS ['db']->query ( "update " . DB_PREFIX . "duobao_item set current_buy = current_buy +" . $v ['number'] . ",progress = floor(current_buy/max_buy*100) where id = " . $v ['duobao_item_id']);
				$msg =  $v ['name']."参与的夺宝活动:".format_price( $v ['number']*$v['unit_price'] );

				// 收入 0.收入 1.订单支付收入 2.会员充值收入 3.支出 4.会员提现支出
				modify_statements ( $v ['number']*$v['unit_price'] , 0, $msg ); // 总收入
				modify_statements ( $v ['number']*$v['unit_price'], 1, $msg ); // 订单收入
				if($user_info['pid']){
					$duobao_item=$GLOBALS ['db']->getRow ( "select invite_score,min_buy from " . DB_PREFIX . "duobao_item where id = " . $v ['duobao_item_id'] );
					$p_score+=($v['number']/$duobao_item['min_buy'])*$duobao_item['invite_score'];
				}
			}



			 if($all_refund){
				// 进度已完，退款
				 $GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order_item set refund_status = 2 where order_id = " . $order_info ['id'] );

				 // 超出充值
				 $msg = "您参与的夺宝活动" . $v ['name'] . "人数已凑齐，请关注下一期!";



				 $money = $v['number'] * $v['unit_price'];
				 $whole_money += $money;

				 modify_account (
					array (
						'money' => $money,
						'score' => 0
					),
					$order_info ['user_id'],
					$msg
				 );

				 order_log ( $msg . format_price ( $v ['number']*$v['unit_price'] ) . "已退到会员中心", $order_info ['id'] );

				 // 增加退款到会员中心的充值记录
				 modify_statements ( $v ['number']*$v['unit_price'], 2, $msg );

				 // 收入
				 modify_statements ( $v ['number']*$v['unit_price'], 0, $msg ); // 总收入
				
				 //退款积分
				 $min_buy = $GLOBALS ['db']->getOne ( "select min_buy from " . DB_PREFIX . "duobao_item where id = " . $v['duobao_item_id'] );
				 $return_total_score = ($v['number']/$min_buy) * $v['return_score'];

				 // 将订单的extra_status 状态更新为2，并自动退款
				 $GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set extra_status = 2, return_total_score = return_total_score - " .$return_total_score. ", refund_amount = refund_amount + " . ($v ['number']*$v['unit_price']) . " where id = " . intval ( $order_info ['id'] ) );
				
				 // 记录退款的订单日志
				 $log ['log_info'] = $msg;
				 $log ['log_time'] = NOW_TIME;
				 $log ['order_id'] = intval ( $order_info ['id'] );
				 $GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order_log", $log );
			 }

			 $duobao->check_progress();
		}//end foreach

		// 退款大于红包的，直接退红包，退款金额不能大于非红包支付的总额
		$total_refund_money = $part_money + $whole_money;
		if ($total_refund_money > 0 ) {
			// 退款的金额大于的或等于红包，直接可以退回到红包
			if ($total_refund_money >= $order_info['ecv_money'] && $order_info['ecv_id']) {
				$neg_ecv_money = 0 - $order_info['ecv_money'];
				$set_ecv_money = round($order_info['ecv_money'], 2);
				modify_account(array(
					'money' => $neg_ecv_money,
					'score' => 0
				), $order_info ['user_id'], "订单号为{$order_info['order_sn']}的订单，把原本{$set_ecv_money}元红包部分的余额退到红包");

				$GLOBALS['db']->query("update " . DB_PREFIX . "ecv set use_count = use_count - 1 where id = " . $order_info['ecv_id']);
				order_log("用户取消订单{$order_info['order_sn']}，代金券退回 ", $order_info['id']);
			} else {
				// 如果只选择了红包支付，订单全部退款才会退红包，否则红包不退
				// 总金额-红包金额 = 总的非红包支付金额
				$neg_ecv_money = $order_info['total_price'] - $order_info['ecv_money'] - $total_refund_money;
				if($neg_ecv_money < 0){
					modify_account(array(
						'money' => $neg_ecv_money,
						'score' => 0
					), $order_info ['user_id'], "订单号为{$order_info['order_sn']}的订单，红包属于一次性消费，红包退款部分无效");
				}
			}
		}
		
		//满返红包
		$content_ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 6");
		
		// 有退款，订单满多少发红包判断
		if($content_ecv){
		    if(($order_info['total_price'] - $total_refund_money)>=$content_ecv['minchange_money']){
		        require_once APP_ROOT_PATH."system/libs/voucher.php";
		        $content_ecv_id = send_voucher($content_ecv, $order_info['user_id']);
		        if ( $content_ecv_id > 0 ) {
		            $content_ecv_money = $GLOBALS['db']->getOne("select money from ".DB_PREFIX."ecv where id = {$content_ecv_id}");
		            $content_ecv_money = round($content_ecv_money, 2);
		            send_msg($order_info['user_id'], "订单".$order_info['order_sn']."返还您{$content_ecv_money}元红包", "notify");
		        }
		    }
		}


		//夺宝订单完结  后面更新返利操作
		if($user_info['pid']>0){
			if($p_score>0){
				$arr['user_id']=$user_info['pid'];
				$arr['rel_user_id']=$user_info['id'];
				$arr['create_time']=NOW_TIME;
				$arr['pay_time']=NOW_TIME;
				$arr['order_id']=$order_id;
				$arr['score']=$p_score;
				$GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$arr);
			}
		}

	} 	// end
	elseif ($order_info ['type'] == 1) {
		// 订单充值
		// $GLOBALS['db']->query("update ".DB_PREFIX."deal_order set
		// order_status = 1 where id = ".$order_info['id']); //充值单自动结单
		require_once APP_ROOT_PATH . "system/model/user.php";
		$msg = sprintf ( $GLOBALS ['lang'] ['USER_INCHARGE_DONE'], $order_info ['order_sn'] );
		modify_account ( array (
				'money' => $order_info ['total_price'] - $order_info ['payment_fee'],
				'score' => 0
		), $order_info ['user_id'], $msg );

		// by hc 0507
		// modify_statements("-".($order_info['total_price']), 1,
		// $order_info['order_sn']."会员充值");
		modify_statements ( ($order_info ['total_price']), 2, $order_info ['order_sn'] . "会员充值，含手续费" );

		// 收入, by hc 0507
		modify_statements ( $order_info ['total_price'], 0, $order_info ['order_sn'] . "会员充值，含手续费" ); // 总收入

	    // 充值是否有红包
		$char_ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 5");
		if($char_ecv){
		    require_once APP_ROOT_PATH."system/libs/voucher.php";
		    $chg_ecv_id = send_voucher($char_ecv, $order_info ['user_id'], false, $order_info);
		    if($chg_ecv_id){
		        $chg_ecv_money = $GLOBALS['db']->getOne("select money from ".DB_PREFIX."ecv where id = {$chg_ecv_id}");
		        $chg_ecv_money = round($chg_ecv_money, 2);
		        send_msg($order_info ['user_id'], "充值送{$chg_ecv_money}元红包", "notify");
		    }
		    
		}
		
		send_msg ( $order_info ['user_id'], "成功充值" . format_price ( $order_info ['total_price'] - $order_info ['payment_fee'] ), "notify", $order_id );
	}elseif ($order_info['type'] == 3){
	    // 记录收益
	    modify_statements ( ($order_info ['total_price']), 0, $order_info ['order_sn'] . "直购订单收款金额" );
	    modify_statements ( $order_info ['total_price'], 1, $order_info ['order_sn'] . "直购订单收款金额" ); // 总收入
	}
	auto_over_status ( $order_id ); // 自动结单
}

/**
 * 弃用
 */
function syn_cart() {
}

/**
 * 验证购物车
 */
function check_cart($id, $number) {
    $type = $GLOBALS['request']['type'];
    if ($type == 'free') {
        $cart_result = load_free_list ();
    }
    else{
	   $cart_result = load_cart_list ();
    }

	$cart_item = $cart_result ['cart_list'] [$id];
	if (empty ( $cart_item )) {
		$result ['info'] = "非法的数据";
		$result ['status'] = 0;
		return $result;
	}
	if ($number <= 0) {
		$result ['info'] = "数量不能为0";
		$result ['status'] = 0;
		return $result;
	}

	$add_number = $number - $cart_item ['number'];
	require_once APP_ROOT_PATH . "system/model/duobao.php";

	// 库存的验证
	$check = duobao::check_duobao_number ( $cart_item ['duobao_item_id'], $add_number );

	if ($check ['status'] == 0) {
		$result ['info'] = $check ['info'] . " " . $check ['data'];
		$result ['status'] = 0;
		return $result;
	}

	$result ['status'] = 1;
	return $result;
}

function check_number_choose_cart($id, $number) {
    $cart_result = load_number_choose_cart_list();
    $cart_item = $cart_result ['cart_list'] [$id];
    if (empty ( $cart_item )) {
        $result ['info'] = "非法的数据";
        $result ['status'] = 0;
        return $result;
    }
    if ($number <= 0) {
        $result ['info'] = "数量不能为0";
        $result ['status'] = 0;
        return $result;
    }

    $add_number = $number - $cart_item ['number'];
    require_once APP_ROOT_PATH . "system/model/duobao.php";

    // 库存的验证
    $check = duobao::check_number_choose_duobao_number ( $cart_item ['duobao_item_id'], $add_number );
    if ($check ['status'] == 0) {
        $result ['info'] = $check ['info'] . " " . $check ['data'];
        $result ['status'] = 0;
        return $result;
    }

    $result ['status'] = 1;
    return $result;
}
function check_pk_cart($id, $number) {
    $cart_result = load_pk_cart_list();
    $cart_item = $cart_result ['cart_list'] [$id];
    if (empty ( $cart_item )) {
        $result ['info'] = "非法的数据";
        $result ['status'] = 0;
        return $result;
    }
    if ($number <= 0) {
        $result ['info'] = "数量不能为0";
        $result ['status'] = 0;
        return $result;
    }

    $add_number = $number - $cart_item ['number'];
    require_once APP_ROOT_PATH . "system/model/duobao.php";

    // 库存的验证
    $check = duobao::check_pk_duobao_number ( $cart_item ['duobao_item_id'], $add_number );
    if ($check ['status'] == 0) {
        $result ['info'] = $check ['info'] . " " . $check ['data'];
        $result ['status'] = 0;
        return $result;
    }

    $result ['status'] = 1;
    return $result;
}
//验证免费云购购物车
function check_free_cart($id, $number) {
    $cart_result = load_free_list();
    $cart_item = $cart_result ['cart_list'] [$id];
    if (empty ( $cart_item )) {
        $result ['info'] = "非法的数据";
        $result ['status'] = 0;
        return $result;
    }
    if ($number <= 0) {
        $result ['info'] = "数量不能为0";
        $result ['status'] = 0;
        return $result;
    }

    $result ['status'] = 1;
    return $result;
}

//免费购商品结算列表
function load_free_list($reload = false,$type=0) {

    if($type==1){  //$type==1时，查询所有的购物记录，包括无效的
        $cart_list_res = $GLOBALS ['db']->getAll ( "select dc.*,di.is_topspeed,di.max_buy,di.current_buy,di.min_buy,di.unit_price,di.origin_price,di.user_max_buy from " . DB_PREFIX . "deal_cart as dc left join " . DB_PREFIX . "duobao_item as di on di.id = dc.duobao_item_id  where dc.is_total_buy = 0 and dc.is_coupons=1 and dc.user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
    }else{
        $cart_list_res = $GLOBALS ['db']->getAll ( "select dc.*,di.is_topspeed,di.max_buy,di.current_buy,di.min_buy,di.unit_price,di.origin_price,di.user_max_buy from " . DB_PREFIX . "deal_cart as dc left join " . DB_PREFIX . "duobao_item as di on di.id = dc.duobao_item_id  where dc.is_total_buy = 0 and dc.is_coupons=1 and dc.user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and dc.is_effect=1" );
    }

    $cart_list = array ();
    foreach ( $cart_list_res as $k => $v ) {
        $v ['url'] = url ( "index", "duobao&id=" . $v ['duobao_item_id'] );
        $v ['residue_count'] = $v ['max_buy'] - $v ['current_buy'];
        $v ['value_price'] =$v ['max_buy']*$v ['unit_price'];
        $cart_list [$v ['id']] = $v;
    }
    if($type==1){  //$type==1时，查询所有的购物记录，包括无效的
        $total_data = $GLOBALS ['db']->getRow ( "select sum(total_price) as total_price,sum(return_total_score) as return_total_score from " . DB_PREFIX . "deal_cart where is_total_buy = 0 and is_coupons=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
    }else{
        $total_data = $GLOBALS ['db']->getRow ( "select sum(total_price) as total_price,sum(return_total_score) as return_total_score from " . DB_PREFIX . "deal_cart where is_total_buy = 0 and is_coupons=1 and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] )." and is_effect=1" );
    }
    $total_data ['cart_item_number'] = count ( $cart_list );
    $result = array (
        "cart_list" => $cart_list,
        "total_data" => $total_data
    );
    // 有操作程序就更新购物车状态
    $GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_cart set update_time=" . NOW_TIME . " where is_total_buy = 0 and is_coupons=1" );
    return $result;
} 

?>
