<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

$lang = array (
		'DEAL_ERROR_1' => '团购进行中',
		'DEAL_ERROR_2' => '已过期',
		'DEAL_ERROR_3' => '未开始',
		'DEAL_ERROR_4' => '产品剩余库存不足',
		'DEAL_ERROR_5' => '用户最小购买数不足',
		'DEAL_ERROR_6' => '用户最大购买数超出' 
);
class
cartApiModule extends MainBaseApiModule {
	
	/**
	 * 获取购物车列表
	 *
	 * 输入:
	 * 无
	 *
	 * 输出:
	 */
	public function index() {
	    require_once APP_ROOT_PATH . "system/model/cart.php";
	    
	    if ($GLOBALS ['request']['type'] == 'free') {
			$cart_result = load_free_list();
		} else {
			$cart_result = load_cart_list();
		}
		// 检测购物车中的商品是否过期
		$duobao_ids = array_keys ( $cart_result ['cart_list'] );
		if($duobao_ids){
		    $duobao_items = $GLOBALS ['db']->getAll ( "select dc.id,di.name,di.progress,di.current_buy,di.max_buy from " . DB_PREFIX . "deal_cart as dc left join " . DB_PREFIX . "duobao_item as di on di.id = dc.duobao_item_id where di.is_number_choose=0 and dc.id in(" . implode ( ",", $duobao_ids ) . ")" );
		    
		    foreach ( $duobao_items as $k => $v ) {
		        if ($v ['progress'] == 100 && ($v ['max_buy'] == $v ['current_buy'])) {
		            $expire_ids [] = $v ['id'];
		        }
		    }
		    $GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "deal_cart where id in(" . implode ( ",", $expire_ids ) . ") and user_id = " . intval ( $GLOBALS ['user_info'] ['id'] ) );
		}
		
		$cart_list = array ();
		foreach ( $cart_result ['cart_list'] as $k => $v ) {
			$v ['deal_icon'] = get_abs_img_root ( get_spec_image ( $v ['deal_icon'], 186, 186, 1 ) );
			$cart_list [] = $v;
		}
		$root ['cart_list'] = $cart_list;
		
		$cart_result ['total_data'] ['total_price'] = round ( $cart_result ['total_data'] ['total_price'], 2 );
		$root ['total_data'] = $cart_result ['total_data'];
		
		$user_login_status = check_login ();
		if ($GLOBALS ['user_info'] ['mobile'] == "")
			$root ['has_mobile'] = 0;
		else
			$root ['has_mobile'] = 1;
		
		if ($user_login_status == LOGIN_STATUS_TEMP) {
			$user_login_status = LOGIN_STATUS_LOGINED; // 购物车页不存在临时状态
		}
		$root ['user_login_status'] = $user_login_status;
		// 购物车
		$root ['cart_info'] ['cart_item_num'] = $cart_result ['total_data'] ['cart_item_number'];
		
		$root ['page_title'] = "购物车";
		$root ['type'] = $GLOBALS ['request']['type'];

		//推荐商品
        require_once APP_ROOT_PATH."system/model/duobao.php";

        $index_duobao = duobao::get_list(0,$order,false,$page,$order_dir);
        $index_duobao_list=$index_duobao['list'];
        $total=$index_duobao['count'];
        foreach($index_duobao_list as $k=>$v)
        {
            $index_duobao_list[$k]['icon']=get_abs_img_root(get_spec_image($v['icon'],280,280,1));
            $index_duobao_list[$k]['progress']=round($v['current_buy']/$v['max_buy'],2)*100;
        }

        $root['index_duobao_list'] = $index_duobao_list?$index_duobao_list:array();


		return output ( $root );
	}
	
	/**
	 * 加入购物车接口
	 *
	 * 输入：
	 *
	 * id:int 商品id（配件合并购买时不存在）
	 *
	 * 输出：
	 * [user_login_status] => 1
	 * 用户登录后返回
	 * [cart_data] => Array 购物车列表数据
	 * (
	 * [cart_list] => Array
	 * (
	 * [0] => Array
	 * (
	 * [id] => 10000337 int 夺宝期数 ID
	 * [name] => TEMIX迷你指甲油 5瓶套装包邮 string 夺宝名称
	 * [number] => 3 int 购物车中已经添加的数量
	 * [unit_price] => 1.0000 float 单价
	 * )
	 * )
	 *
	 * [cart_item_num] => 1 int 购物车中一共多少不同的夺宝商品
	 * )
	 * 错误消息及成功消息
	 * [data] => Array
	 * (
	 * [status] => 1
	 * [info] => 已加入清单 string 成功或者错误消息
	 * )
	 */
	public function addcart() {
		$root = array ();
		
		$id = intval ( $GLOBALS ['request'] ['data_id'] );
		$buy_num = intval ( $GLOBALS ['request'] ['buy_num'] );
		
		// 用户检测
		$user_info = $GLOBALS ['user_info'];
		require_once APP_ROOT_PATH . 'system/model/duobao.php';
		$duobao = new duobao ( $id );
		$duobao_info = $duobao->duobao_item;
		if (empty ( $duobao_info )) {
			return output ( $root, 0, "夺宝项目不存在" );
		}
		$user_login_status = check_login ();
		if ($user_login_status != LOGIN_STATUS_LOGINED) {
			$root ['user_login_status'] = $user_login_status;
			return output ( $root, - 1, "请先登录用户" );
		}
		$root ['user_login_status'] = $user_login_status;
		
		// 购物车业务流程
        if($duobao_info['is_number_choose']){
            $res = duobao::check_number_choose_duobao_number($id, $buy_num, false);
        }else{
            $res = duobao::check_duobao_number($id, $buy_num, false);
        }
		if($res['status']==0)
		{
			return output ( $root, $res ['status'], $res ['info'] );
		}
        $choose_number=$GLOBALS['request']['choose_number'];
		if($duobao_info['is_number_choose']){
            $result = $duobao->addcart_choose_number ( $user_info ['id'], $buy_num, false,$choose_number);
            $root['deal_cart_id']=$result['deal_cart_id'];
            $root['is_number_choose']=1;
        }else{
            $result = $duobao->addcart ( $user_info ['id'], $buy_num, false);
        }
        $root['buy_number']=$buy_num;
		$root ['cart_item_num'] = $result ['cart_item_num'] ? $result ['cart_item_num'] : 0;
		return output ( $root, $result ['status'], $result ['info'] );
	}
	
	/**
	 * 获取购物车列表接口
	 *
	 * 输入：
	 *
	 * id:int 商品id（配件合并购买时不存在）
	 *
	 * 输出：
	 * [user_login_status] => 1
	 * 用户登录后返回
	 * [data] => Array
	 * (
	 * [cart_data] => Array 购物车列表数据
	 * (
	 * [cart_list] => Array
	 * (
	 * [0] => Array
	 * (
	 * [id] => 10000337 int 夺宝期数 ID
	 * [name] => TEMIX迷你指甲油 5瓶套装包邮 string 夺宝名称
	 * [number] => 3 int 购物车中已经添加的数量
	 * [unit_price] => 1.0000 float 单价
	 * )
	 * )
	 *
	 * [cart_item_num] => 1 int 购物车中一共多少不同的夺宝商品
	 * )
	 * )
	 */
	public function getcart() {
		// 用户检测
		$user_info = $GLOBALS ['user_info'];
		$user_login_status = check_login ();
		if ($user_login_status != LOGIN_STATUS_LOGINED) {
			$root ['user_login_status'] = $user_login_status;
			return output ( $root, 0, "请先登录用户" );
		} else {
			$root ['user_login_status'] = $user_login_status;
			require_once APP_ROOT_PATH . "system/model/duobao.php";
			$root ['data'] = duobao::getcart ( $user_info ['id'] );
		}
		
		return output ( $root );
	}
	
	/**
	 * 提交修改购物车，并生成会员接口
	 *
	 * 输入
	 * num: 购物车列表的数量修改 array
	 * 结构如下
	 *
	 * [duobao_id] => Array
	 * (
	 * [553] => 19 key[int] 购物车主键 value时段id
	 * )
	 * mobile string 手机号
	 * sms_verify string 手机验证码
	 *
	 * 输出
	 * status: int 状态 0失败 1成功
	 * info: string 消息
	 * user_data: 当前的会员信息，用于同步本地信息 array
	 * Array(
	 * id:int 会员ID
	 * user_name:string 会员名
	 * user_pwd:string 加密过的密码
	 * email:string 邮箱
	 * mobile:string 手机号
	 * is_tmp: int 是否为临时会员 0:否 1:是
	 * )
	 */
	public function check_cart() {
		$root = array ();
		
		$num_req = $GLOBALS ['request'] ['num'];
		$num = array ();
		foreach ( $num_req as $k => $v ) {
			$sv = intval ( $v );
			if ($sv)
				$num [$k] = intval ( $sv );
		}
		
		$user_mobile = strim ( $GLOBALS ['request'] ['mobile'] );
		$sms_verify = strim ( $GLOBALS ['request'] ['sms_verify'] );
		$user_data = $GLOBALS['user_info'];
		$user_login_status = check_login ();
		
		require_once APP_ROOT_PATH . "system/model/cart.php";
		if ($user_login_status == LOGIN_STATUS_NOLOGIN) {
			return output ( $root, - 1, "请先登录" );
		}
		
		
		$type=$GLOBALS ['request']['type'];
		
		if ($type == 'free') {
		    $cart_result = load_free_list ();
		}
		else{
		    $cart_result = load_cart_list ();
		}
		
		
		$cart_list = $cart_result ['cart_list'];
		
		// 检测购物车中的商品是否过期
		$duobao_ids = array_keys ( $num );
		$duobao_items = $GLOBALS ['db']->getAll ( "select dc.id,di.name,di.progress,di.current_buy,di.max_buy from " . DB_PREFIX . "deal_cart as dc left join " . DB_PREFIX . "duobao_item as di on di.id = dc.duobao_item_id where dc.id in(" . implode ( ",", $duobao_ids ) . ")" );
		
		foreach ( $duobao_items as $k => $v ) {
			if ($v ['progress'] == 100 && ($v ['max_buy'] == $v ['current_buy'])) {
				$expire_ids [] = $v ['id'];
			}
		}
		
		if (count ( $expire_ids ) > 0) {
			$root ['expire_ids'] = $expire_ids;
			return output ( $root, 0, "购物车存在已结束活动" );
		}
		
		$total_money = 0;
		foreach ( $num as $k => $v ) {
			$id = intval ( $k );
			$number = $v;
			$total_money += $cart_list [$id] ['return_money'] * $number;
		}
		
		// 关于现金的验证
		// $total_money = $cart_result['total_data']['return_total_money'];
		if ($GLOBALS ['user_info'] ['money'] + $total_money < 0) {
			return output ( $root, 0, "余额不足" );
		}
		// 关于现金的验证
		foreach ( $num as $k => $v ) {
			$id = intval ( $k );
			$number = intval ( $v );
			$data = check_cart ( $id, $number );
			if (! $data ['status']) {
				return output ( $root, 0, $data ['info'] );
			}
		}
		// print_r($num);exit;
		require_once APP_ROOT_PATH . 'system/model/duobao.php';
		$duobao = new duobao ( 0 );
		foreach ( $num as $k => $v ) {
			$id = intval ( $k );
			$number = intval ( $v );
			
			$GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_cart set number =" . $number . ", total_price = " . $number . "* unit_price, return_total_score = " . $number . "* return_score where id =" . $id . " and session_id = '" . es_session::id () . "'" );
			load_cart_list ( true );
		}
		$root ['user_data'] = $user_data;

		return output ( $root );
	}
	
	/**
	 * 删除购物车
	 *
	 * 输入
	 * id:int 购物车中的商品ID，该参数不传时表示为清空所有购物车内容
	 *
	 * 输出
	 * 无
	 */
    public function del() {
		$root = array ();
		  
		$user_info = $GLOBALS ['user_info'];
		$user_id = intval($user_info ['id']);
		if (isset ( $GLOBALS ['request'] ['id'] )) {
		    $id = intval ( $GLOBALS ['request'] ['id'] );
		    $sql = "delete from " . DB_PREFIX . "deal_cart  where user_id={$user_id}  and id = {$id}";
		} else {
		    $sql = "delete from " . DB_PREFIX . "deal_cart  where user_id={$user_id}";
		}
		
		$op_result = $GLOBALS ['db']->query ( $sql );
		
		require_once APP_ROOT_PATH . "system/model/cart.php";
		
		if ($op_result > 0) {
			load_cart_list ( true ); // 重新刷新购物车
		}
		require_once APP_ROOT_PATH . 'system/model/duobao.php';
		// 购物车
		$root ['cart_info'] = duobao::getcart ( $GLOBALS ['user_info'] ['id'] );
		return output ( $root );
	}
	
	/* 免费购购物车提交 */
	public function free_check() {
	    $root = array ();
	    if ((check_login () == LOGIN_STATUS_TEMP && $GLOBALS ['user_info'] ['money'] > 0) || check_login () == LOGIN_STATUS_NOLOGIN) {
	        return output ( array (), - 1, "请先登录" );
	    }
	    require_once APP_ROOT_PATH . "system/model/cart.php";
	    
	    $cart_result = load_free_list ();
	    
	    $total_price = $cart_result ['total_data'] ['total_price'];
	    // 处理购物车输出
	    $cart_list_o = $cart_result ['cart_list'];
	    $cart_list = array ();
	    
	    $total_data_o = $cart_result ['total_data'];
	    foreach ( $cart_list_o as $k => $v ) {
	        $bind_data = array ();
	        $bind_data ['id'] = $v ['id'];
	        	
	        $bind_data ['unit_price'] = round ( $v ['unit_price'], 2 );
	        $bind_data ['total_price'] = round ( $v ['total_price'], 2 );
	        $bind_data ['number'] = $v ['number'];
	        $bind_data ['duobao_id'] = $v ['duobao_id'];
	        	
	        $bind_data ['name'] = $v ['name'];
	        	
	        $bind_data ['deal_icon'] = get_abs_img_root ( get_spec_image ( $v ['deal_icon'], 186, 186, 1 ) );
	        $cart_list [$v ['id']] = $bind_data;
	    }
	    $root ['cart_list'] = $cart_list ? $cart_list : null;
	    $total_data ['total_price'] = round ( $total_data_o ['total_price'], 2 );
	    $root ['total_data'] = $total_data;
	    // end购物车输出
	    
	    $user_type = 0;
	    foreach ( $cart_list as $k => $v ) {
	        	
	        if ($v ['user_type'] == 1 && $v ['pid'] == 0) {
	            $user_type = 1;
	            break;
	        }
	    }
	    
	    
	    $root ['page_title'] = "提交订单";
	    $root ['account_money'] = round ( $GLOBALS ['user_info'] ['coupons'], 2 );
	    
	    return output ( $root );
	}
	
	
	/**
	 * 购物车的提交页
	 * 输入:
	 * 无
	 *
	 * 输出:
	 * status: int 状态 1:正常 -1未登录需要登录
	 * info:string 信息
	 * cart_list: object 购物车列表，如该列表为空数组则跳回首页,结构如下
	 * Array
	 */
	public function check() {
		$root = array ();
		if ((check_login () == LOGIN_STATUS_TEMP && $GLOBALS ['user_info'] ['money'] > 0) || check_login () == LOGIN_STATUS_NOLOGIN) {
			return output ( array (), - 1, "请先登录" );
		}
		require_once APP_ROOT_PATH . "system/model/cart.php";
		$cart_result = load_cart_list ();
		$total_price = $cart_result ['total_data'] ['total_price'];
		// 处理购物车输出
		$cart_list_o = $cart_result ['cart_list'];
		$cart_list = array ();
		
		$total_data_o = $cart_result ['total_data'];
		foreach ( $cart_list_o as $k => $v ) {
			$bind_data = array ();
			$bind_data ['id'] = $v ['id'];
			
			$bind_data ['unit_price'] = round ( $v ['unit_price'], 2 );
			$bind_data ['total_price'] = round ( $v ['total_price'], 2 );
			$bind_data ['number'] = $v ['number'];
			$bind_data ['duobao_id'] = $v ['duobao_id'];
			
			$bind_data ['name'] = $v ['name'];
			$bind_data ['is_total_buy'] = $v ['is_total_buy'];
			$bind_data ['is_pk'] = $v ['is_pk'];
			$bind_data ['is_number_choose'] = $v ['is_number_choose'];
			$bind_data ['is_topspeed'] = $v ['is_topspeed'];
			$bind_data ['min_buy'] = $v ['min_buy'];
			
			$bind_data ['deal_icon'] = get_abs_img_root ( get_spec_image ( $v ['deal_icon'], 186, 186, 1 ) );
			$cart_list [$v ['id']] = $bind_data;
		}
		$root ['cart_list'] = $cart_list ? $cart_list : null;
		$total_data ['total_price'] = round ( $total_data_o ['total_price'], 2 );
		$root ['total_data'] = $total_data;
		// end购物车输出
		
		$user_type = 0;
		foreach ( $cart_list as $k => $v ) {
			
			if ($v ['user_type'] == 1 && $v ['pid'] == 0) {
				$user_type = 1;
				break;
			}
		}
		
		// 输出支付方式
		global $is_app;
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
		
		if ($total_price > 0)
			$show_payment = 1;
		else
			$show_payment = 0;
		$root ['show_payment'] = $show_payment;
		
		// 统计几个专区 1P K区 2十元区 3百元区 4直购区 5极速区 6选号区 7一元区
		$area = array();
		foreach($cart_list as $k => $v){
		    if($v['min_buy']==10||$v['unit_price']==10){
		        $area['range_value2']=2;
		    }
		    if($v['min_buy']==1&&$v['is_topspeed']!=1&&$v['unit_price']==1){
		        $area['range_value7']=7;
		    }
		    if($v['unit_price']==100){
		        $area['range_value3']=3;
		    }
		    if($v['is_topspeed']==1){
		        $area['range_value5']=5;
		    }
		}
		
		if ($show_payment) {
			$web_payment_list = load_auto_cache ( "cache_payment" );
			
			foreach ( $web_payment_list as $k => $v ) {
				if ($v ['class_name'] == "Account" && $GLOBALS ['user_info'] ['money']+$GLOBALS['user_info']['can_use_give_money']+$GLOBALS['user_info']['fx_money']+$GLOBALS['user_info']['admin_money'] > 0) {
					$root ['has_account'] = 1;
				}
				if ($v ['class_name'] == "Voucher") {
					$root ['has_ecv'] = 1;
					$sql = "select e.sn as sn,e.is_all as is_all,e.data as data,t.name as name from " . DB_PREFIX . "ecv as e left join " . DB_PREFIX . "ecv_type as t on e.ecv_type_id = t.id where " . " e.user_id = '" . $GLOBALS ['user_info'] ['id'] . "' and (e.begin_time < " . NOW_TIME . ") and (e.end_time = 0 or e.end_time > " . NOW_TIME . ") and e.meet_amount <= " .$total_price." and (e.use_limit = 0 or e.use_count<e.use_limit)";
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
		$root ['page_title'] = "提交订单";
		$root ['account_money'] = round ( $GLOBALS ['user_info'] ['money'], 2 );
		return output ( $root );
	}
	
	
	public function order() {
	    $root = array ();
	    if ((check_login () == LOGIN_STATUS_TEMP && $GLOBALS ['user_info'] ['money'] > 0) || check_login () == LOGIN_STATUS_NOLOGIN) {
	        return output ( array (), - 1, "请先登录" );
	    }
	    
	    $id = intval($GLOBALS['request']['id']);
	    $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$id." and is_delete = 0 and pay_status <> 2 and order_status <> 1 and user_id =".intval($GLOBALS['user_info']['id']));
	     
	    
	    $cart_list = $GLOBALS['db']->getAll("select doi.*,di.origin_price from ".DB_PREFIX."deal_order_item as doi left join ".DB_PREFIX."duobao_item as di on di.id = doi.duobao_item_id  where doi.order_id = ".$order_info['id']);
	     
	    if(!$cart_list)
	    {
	        app_redirect(url("index"));
	    }
	    else
	    {
	        foreach($cart_list as $k=>$v)
	        {
	            $cart_list[$k]['unit_price'] = round($v['unit_price']);
	            $cart_list[$k]['total_price'] = round($v['total_price']);
	            $cart_list[$k]['origin_price'] = round($v['origin_price']);
	            $total_price +=$v['total_price'];
	        }
	    }
	    
	    $root ['cart_list'] = $cart_list ? $cart_list : null;
	    $total_price = round($total_price,2);
	    $total_data ['total_price'] = $total_price;
	    $root ['total_data'] = $total_data;
	    // end购物车输出
	    
	     
	    $user_type = 0;
	    foreach ( $cart_list as $k => $v ) {
	        	
	        if ($v ['user_type'] == 1 && $v ['pid'] == 0) {
	            $user_type = 1;
	            break;
	        }
	    }
	    
	    // 输出支付方式
	    global $is_app;
	    if (! $is_app) {
	        // 支付列表
	        $sql = "select id, class_name as code, logo from " . DB_PREFIX . "payment where (online_pay = 2 or online_pay = 4 or online_pay = 5 or online_pay = 6 or online_pay = 7) and is_effect = 1";
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
	    
	    if ($total_price > 0)
	        $show_payment = 1;
	    else
	        $show_payment = 0;
	    $root ['show_payment'] = $show_payment;
	    
	    if ($show_payment) {
	        $web_payment_list = load_auto_cache ( "cache_payment" );
	        	
	        foreach ( $web_payment_list as $k => $v ) {
	            if ($v ['class_name'] == "Account" && $GLOBALS ['user_info'] ['money']+$GLOBALS ['user_info'] ['money']+$GLOBALS['user_info']['can_use_give_money']+$GLOBALS['user_info']['fx_money']+$GLOBALS['user_info']['admin_money'] > 0) {
	                $root ['has_account'] = 1;
	            }
	            if ($v ['class_name'] == "Voucher") {
	                $root ['has_ecv'] = 1;
	                $sql = "select e.sn as sn,t.name as name from " . DB_PREFIX . "ecv as e left join " . DB_PREFIX . "ecv_type as t on e.ecv_type_id = t.id where " . " e.user_id = '" . $GLOBALS ['user_info'] ['id'] . "' and (e.begin_time < " . NOW_TIME . ") and (e.end_time = 0 or e.end_time > " . NOW_TIME . ") " . " and (e.use_limit = 0 or e.use_count<e.use_limit)";
	                $root ['voucher_list'] = $GLOBALS ['db']->getAll ( $sql );
	            }
	        }
	    } else {
	        $root ['has_account'] = 0;
	        $root ['has_ecv'] = 0;
	    }
	    
	    $root ['page_title'] = "提交订单";
	    $root ['account_money'] = round ( $GLOBALS ['user_info'] ['money'], 2 );
	    return output ( $root );
	}
	
	
	/**
	 * 计算直购商品总价
	 *
	 * 输入:
	 * delivery_id: int 配送方式主键
	 * ecvsn:string 代金券序列号
	 * ecvpassword: string 代金券密码
	 * payment:int 支付方式ID
	 * all_account_money:int 是否使用余额支付 0否 1是
	 *
	 * 输出:
	 * pay_price:float 当前要付的余额，如为0表示不需要使用在线支付，则支付方式不让选中
	 * delivery_fee_supplier:商家的运费费用Array
	 * array(
	 * array(supplier_id=>delivery_fee)
	 * )
	 * feeinfo: array 费用清单，结构如下
	 * Array(
	 * Array(
	 * "name" => "折扣", string 费用清单项名称
	 * "value" => "7折" string 费用清单项内容
	 * ),
	 * )
	 */
	public function count_buy_totalbuy() {
	    require_once APP_ROOT_PATH . "system/model/cart.php";
	   
	    $order_id          = intval ( $GLOBALS['request']['order_id'] );
	    $ecvsn             = $GLOBALS ['request'] ['ecvsn'] ? strim ( $GLOBALS ['request'] ['ecvsn'] ) : '';
	    $ecvpassword       = $GLOBALS ['request'] ['ecvpassword'] ? strim ( $GLOBALS ['request'] ['ecvpassword'] ) : '';
	    $payment           = intval ( $GLOBALS ['request'] ['payment'] );
	    $all_account_money = intval ( $GLOBALS ['request'] ['all_account_money'] );
	    $bank_id = '';
	    
	   
	    
	    
	
	    
	    $order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where type=3 and id = ".$order_id);
	    $result = count_buy_totalbuy( $payment, 0, $all_account_money, $ecvsn, $ecvpassword, $order, $order['account_money'], $order['ecv_money'], $bank_id );
	    $root = array ();
	
	    if ($result ['total_price'] > 0) {
	        $feeinfo [] = array (
	            "name" => "商品总价",
	            "value" => round ( $result ['total_price'], 2 ) . "元"
	        );
	    }
	
	    // "value" => round($result['user_discount']*10,1)."折"
	    // $result['user_discount']这个算出来是折扣的钱,也就是减了多少钱
	    if ($result ['user_discount'] > 0) {
	        $feeinfo [] = array (
	            "name" => "折扣",
	            "value" => round ( ($result ['total_price'] - $result ['user_discount']) / $result ['total_price'] * 10, 1 ) . "折"
	        );
	    }
	
	    if ($result ['payment_info']) {
	        $directory = APP_ROOT_PATH . "system/payment/";
	        $file = $directory . '/' . $result ['payment_info'] ['class_name'] . "_payment.php";
	        if (file_exists ( $file )) {
	            require_once ($file);
	            $payment_class = $result ['payment_info'] ['class_name'] . "_payment";
	            $payment_object = new $payment_class ();
	            $payment_name = $payment_object->get_display_code ();
	        }
	        	
	        $feeinfo [] = array (
	            "name" => "支付方式",
	            "value" => $payment_name
	        );
	    }
	
	    if ($result ['payment_fee'] > 0) {
	        $feeinfo [] = array (
	            "name" => "手续费",
	            "value" => round ( $result ['payment_fee'], 2 ) . "元"
	        );
	    }
	
	    if ($result ['account_money'] > 0) {
	        $feeinfo [] = array (
	            "name" => "余额支付",
	            "value" => round ( $result ['account_money'], 2 )
	        );
	    }
	
	    if ($result ['ecv_money'] > 0) {
	        $feeinfo [] = array (
	            "name" => "红包支付",
	            "value" => round ( $result ['ecv_money'], 2 )
	        );
	    }
	
	    if ($result ['buy_type'] == 0) {
	        if ($result ['return_total_score']) {
	            $feeinfo [] = array (
	                "name" => "返还积分",
	                "value" => round ( $result ['return_total_score'] )
	            );
	        }
	    }
	
	    if ($result ['paid_account_money'] > 0) {
	        $feeinfo [] = array (
	            "name" => "已付",
	            "value" => round ( $result ['paid_account_money'], 2 ) . "元"
	        );
	    }
	
	    if ($result ['paid_ecv_money'] > 0) {
	        $feeinfo [] = array (
	            "name" => "红包已付",
	            "value" => round ( $result ['paid_ecv_money'], 2 ) . "元"
	        );
	    }
	
	    if ($result ['buy_type'] == 0) {
	        $feeinfo [] = array (
	            "name" => "总计",
	            "value" => round ( $result ['pay_total_price'], 2 ) . "元"
	        );
	    }
	
	    if ($result ['pay_price']) {
	        $feeinfo [] = array (
	            "name" => "应付总额",
	            "value" => round ( $result ['pay_price'], 2 ) . "元"
	        );
	    }
	
	    if ($result ['promote_description']) {
	        foreach ( $result ['promote_description'] as $row ) {
	            $feeinfo [] = array (
	                "name" => "",
	                "value" => $row
	            );
	        }
	    }
	    $root ['feeinfo'] = $feeinfo;
	    $root ['pay_price'] = round ( $result ['pay_price'], 2 );
	
	    return output ( $root );
	}
	
	 
	
	/**
	 * 计算购物车总价
	 *
	 * 输入:
	 * delivery_id: int 配送方式主键
	 * ecvsn:string 代金券序列号
	 * ecvpassword: string 代金券密码
	 * payment:int 支付方式ID
	 * all_account_money:int 是否使用余额支付 0否 1是
	 *
	 * 输出:
	 * pay_price:float 当前要付的余额，如为0表示不需要使用在线支付，则支付方式不让选中
	 * delivery_fee_supplier:商家的运费费用Array
	 * array(
	 * array(supplier_id=>delivery_fee)
	 * )
	 * feeinfo: array 费用清单，结构如下
	 * Array(
	 * Array(
	 * "name" => "折扣", string 费用清单项名称
	 * "value" => "7折" string 费用清单项内容
	 * ),
	 * )
	 */
	public function count_buy_total() {
		require_once APP_ROOT_PATH . "system/model/cart.php";
		
		$ecvsn = $GLOBALS ['request'] ['ecvsn'] ? strim ( $GLOBALS ['request'] ['ecvsn'] ) : '';
		$ecvpassword = $GLOBALS ['request'] ['ecvpassword'] ? strim ( $GLOBALS ['request'] ['ecvpassword'] ) : '';
		$payment = intval ( $GLOBALS ['request'] ['payment'] );
		$all_account_money = intval ( $GLOBALS ['request'] ['all_account_money'] );
		$bank_id = '';
		
		$cart_result = load_cart_list ();
		$goods_list = $cart_result ['cart_list'];
		
		$result = count_buy_total ( $payment, 0, $all_account_money, $ecvsn, $ecvpassword, $goods_list, 0, 0, $bank_id );
		$root = array ();
		
		if ($result ['total_price'] > 0) {
			$feeinfo [] = array (
					"name" => "商品总价",
					"value" => round ( $result ['total_price'], 2 ) . "元" 
			);
		}
		
		// "value" => round($result['user_discount']*10,1)."折"
		// $result['user_discount']这个算出来是折扣的钱,也就是减了多少钱
		if ($result ['user_discount'] > 0) {
			$feeinfo [] = array (
					"name" => "折扣",
					"value" => round ( ($result ['total_price'] - $result ['user_discount']) / $result ['total_price'] * 10, 1 ) . "折" 
			);
		}
		
		if ($result ['payment_info']) {
			$directory = APP_ROOT_PATH . "system/payment/";
			$file = $directory . '/' . $result ['payment_info'] ['class_name'] . "_payment.php";
			if (file_exists ( $file )) {
				require_once ($file);
				$payment_class = $result ['payment_info'] ['class_name'] . "_payment";
				$payment_object = new $payment_class ();
				$payment_name = $payment_object->get_display_code ();
			}
			
			$feeinfo [] = array (
					"name" => "支付方式",
					"value" => $payment_name 
			);
		}
		
		if ($result ['payment_fee'] > 0) {
			$feeinfo [] = array (
					"name" => "手续费",
					"value" => round ( $result ['payment_fee'], 2 ) . "元" 
			);
		}
		
		if ($result ['account_money'] > 0) {
			$feeinfo [] = array (
					"name" => "余额支付",
					"value" => round ( $result ['account_money'], 2 ) 
			);
		}
		
		if ($result ['ecv_money'] > 0) {
			$feeinfo [] = array (
					"name" => "红包支付",
					"value" => round ( $result ['ecv_money'], 2 ) 
			);
		}
		
		if ($result ['buy_type'] == 0) {
			if ($result ['return_total_score']) {
				$feeinfo [] = array (
						"name" => "返还积分",
						"value" => round ( $result ['return_total_score'] ) 
				);
			}
		}
		
		if ($result ['paid_account_money'] > 0) {
			$feeinfo [] = array (
					"name" => "已付",
					"value" => round ( $result ['paid_account_money'], 2 ) . "元" 
			);
		}
		
		if ($result ['paid_ecv_money'] > 0) {
			$feeinfo [] = array (
					"name" => "红包已付",
					"value" => round ( $result ['paid_ecv_money'], 2 ) . "元" 
			);
		}
		
		if ($result ['buy_type'] == 0) {
			$feeinfo [] = array (
					"name" => "总计",
					"value" => round ( $result ['pay_total_price'], 2 ) . "元" 
			);
		}
		
		if ($result ['pay_price']) {
			$feeinfo [] = array (
					"name" => "应付总额",
					"value" => round ( $result ['pay_price'], 2 ) . "元" 
			);
		}
		
		if ($result ['promote_description']) {
			foreach ( $result ['promote_description'] as $row ) {
				$feeinfo [] = array (
						"name" => "",
						"value" => $row 
				);
			}
		}
		$root ['feeinfo'] = $feeinfo;
		$root ['pay_price'] = round ( $result ['pay_price'], 2 );
		
		return output ( $root );
	}
	
	public function count_buy_order_total() {
	    require_once APP_ROOT_PATH . "system/model/cart.php";
	
	    $ecvsn = $GLOBALS ['request'] ['ecvsn'] ? strim ( $GLOBALS ['request'] ['ecvsn'] ) : '';
	    $ecvpassword = $GLOBALS ['request'] ['ecvpassword'] ? strim ( $GLOBALS ['request'] ['ecvpassword'] ) : '';
	    $payment = intval ( $GLOBALS ['request'] ['payment'] );
	    $all_account_money = intval ( $GLOBALS ['request'] ['all_account_money'] );
	    $bank_id = '';
	    $order_id = intval ( $GLOBALS ['request'] ['order_id'] );
	
	    $cart_result = load_deal_order_list ($order_id);
	    $goods_list = $cart_result ['cart_list'];
	
	    $result = count_buy_total ( $payment, 0, $all_account_money, $ecvsn, $ecvpassword, $goods_list, 0, 0, $bank_id );
	    $root = array ();
	
	    if ($result ['total_price'] > 0) {
	        $feeinfo [] = array (
	            "name" => "商品总价",
	            "value" => round ( $result ['total_price'], 2 ) . "元"
	        );
	    }
	
	    // "value" => round($result['user_discount']*10,1)."折"
	    // $result['user_discount']这个算出来是折扣的钱,也就是减了多少钱
	    if ($result ['user_discount'] > 0) {
	        $feeinfo [] = array (
	            "name" => "折扣",
	            "value" => round ( ($result ['total_price'] - $result ['user_discount']) / $result ['total_price'] * 10, 1 ) . "折"
	        );
	    }
	
	    if ($result ['payment_info']) {
	        $directory = APP_ROOT_PATH . "system/payment/";
	        $file = $directory . '/' . $result ['payment_info'] ['class_name'] . "_payment.php";
	        if (file_exists ( $file )) {
	            require_once ($file);
	            $payment_class = $result ['payment_info'] ['class_name'] . "_payment";
	            $payment_object = new $payment_class ();
	            $payment_name = $payment_object->get_display_code ();
	        }
	        	
	        $feeinfo [] = array (
	            "name" => "支付方式",
	            "value" => $payment_name
	        );
	    }
	
	    if ($result ['payment_fee'] > 0) {
	        $feeinfo [] = array (
	            "name" => "手续费",
	            "value" => round ( $result ['payment_fee'], 2 ) . "元"
	        );
	    }
	
	    if ($result ['account_money'] > 0) {
	        $feeinfo [] = array (
	            "name" => "余额支付",
	            "value" => round ( $result ['account_money'], 2 )
	        );
	    }
	
	    if ($result ['ecv_money'] > 0) {
	        $feeinfo [] = array (
	            "name" => "红包支付",
	            "value" => round ( $result ['ecv_money'], 2 )
	        );
	    }
	
	    if ($result ['buy_type'] == 0) {
	        if ($result ['return_total_score']) {
	            $feeinfo [] = array (
	                "name" => "返还积分",
	                "value" => round ( $result ['return_total_score'] )
	            );
	        }
	    }
	
	    if ($result ['paid_account_money'] > 0) {
	        $feeinfo [] = array (
	            "name" => "已付",
	            "value" => round ( $result ['paid_account_money'], 2 ) . "元"
	        );
	    }
	
	    if ($result ['paid_ecv_money'] > 0) {
	        $feeinfo [] = array (
	            "name" => "红包已付",
	            "value" => round ( $result ['paid_ecv_money'], 2 ) . "元"
	        );
	    }
	
	    if ($result ['buy_type'] == 0) {
	        $feeinfo [] = array (
	            "name" => "总计",
	            "value" => round ( $result ['pay_total_price'], 2 ) . "元"
	        );
	    }
	
	    if ($result ['pay_price']) {
	        $feeinfo [] = array (
	            "name" => "应付总额",
	            "value" => round ( $result ['pay_price'], 2 ) . "元"
	        );
	    }
	
	    if ($result ['promote_description']) {
	        foreach ( $result ['promote_description'] as $row ) {
	            $feeinfo [] = array (
	                "name" => "",
	                "value" => $row
	            );
	        }
	    }
	    $root ['feeinfo'] = $feeinfo;
	    $root ['pay_price'] = round ( $result ['pay_price'], 2 );
	
	    return output ( $root );
	}
	
	/**
	 * 购物车提交订单接口
	 * 输入：
	 * ecvsn:string 代金券序列号
	 * ecvpassword: string 代金券密码
	 * payment:int 支付方式ID
	 * all_account_money:int 是否使用余额支付 0否 1是
	 * content:string 订单备注
	 *
	 * 输出：
	 * status: int 状态 0:失败 1:成功 -1:未登录
	 * info: string 失败时返回的错误信息，用于提示
	 * 以下参数为status为1时返回
	 * pay_status:int 0未支付成功 1全部支付
	 * order_id:int 订单ID
	 */
	public function done() {
		require_once APP_ROOT_PATH . "system/model/cart.php";
		require_once APP_ROOT_PATH . "system/model/deal_order.php";
		
		$payment = intval ( $GLOBALS ['request'] ['payment'] );
		$all_account_money = intval ( $GLOBALS ['request'] ['all_account_money'] );
		$ecvsn = $GLOBALS ['request'] ['ecvsn'] ? strim ( $GLOBALS ['request'] ['ecvsn'] ) : '';
		$ecvpassword = $GLOBALS ['request'] ['ecvpassword'] ? strim ( $GLOBALS ['request'] ['ecvpassword'] ) : '';
		$memo = strim ( $GLOBALS ['request'] ['content'] );
		
		// $data_log['payment'] = $payment;
		// $data_log['all_account_money'] = $all_account_money;
		// $data_log['ecvsn'] = $ecvsn;
		// $data_log['ecvpassword'] = $ecvpassword;
		// $data_log['memo'] = $memo;
		
		$cart_result = load_cart_list ();
		$goods_list = $cart_result ['cart_list'];
		
		// $data_log['goods_list'] = $goods_list;
		if (! $goods_list) {
			return output ( array (), 0, "购物车为空" );
		}
		
		require_once APP_ROOT_PATH . "system/model/duobao.php";
		foreach($goods_list as $item)
		{
			$res = duobao::check_duobao_number($item['duobao_item_id'], 0);
			if($res['status']==0)
				return output ( array (), 0, $res['info']);
		}
		
		// 验证购物车
		if ((check_login () == LOGIN_STATUS_TEMP && $GLOBALS ['user_info'] ['money'] > 0) || check_login () == LOGIN_STATUS_NOLOGIN) {
			return output ( array (), - 1, "请先登录" );
		}
		
		// 开始验证订单接交信息
		$data = count_buy_total ( $payment, 0, $all_account_money, $ecvsn, $ecvpassword, $goods_list, 0, 0, '' );
		 
		if (round ( $data ['pay_price'], 4 ) > 0 && !$data ['payment_info']) {
		    return output ( array (), 0, "请选择支付方式" );
		}

		// 结束验证订单接交信息
		
		$user_id = $GLOBALS ['user_info'] ['id'];
		
		// 获取用户地址
		$consignee_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user_consignee where is_default=1 and user_id=" . $user_id );
		$region_conf = load_auto_cache ( "delivery_region" );
		
		$region_lv1 = intval ( $consignee_info ['region_lv1'] );
		$region_lv2 = intval ( $consignee_info ['region_lv2'] );
		$region_lv3 = intval ( $consignee_info ['region_lv3'] );
		$region_lv4 = intval ( $consignee_info ['region_lv4'] );
		$region_info = $region_conf [$region_lv1] ['name'] . " " . $region_conf [$region_lv2] ['name'] . " " . $region_conf [$region_lv3] ['name'] . " " . $region_conf [$region_lv4] ['name'];
		
		// 开始生成订单
		$now = NOW_TIME;
		$order ['type'] = 2; // 一元购订单
		$order ['user_id'] = $user_id;
		$order ['create_time'] = $now;
		$order ['update_time'] = $now;
		$order ['total_price'] = $data ['pay_total_price']; // 应付总额 商品价 - 会员折扣 + 运费
		                                                  // + 支付手续费
		$order ['pay_amount'] = 0;
		$order ['pay_status'] = 0; // 新单都为零， 等下面的流程同步订单状态
		$order ['delivery_status'] = $data ['is_delivery'] == 0 ? 5 : 0;
		$order ['order_status'] = 0; // 新单都为零， 等下面的流程同步订单状态
		$order ['return_total_score'] = $data ['return_total_score']; // 结单后送的积分
		$order ['memo'] = $memo;
		// 地址待定
		$order ['region_info'] = $region_info;
		$order ['address'] = strim ( $consignee_info ['address'] );
		$order ['mobile'] = strim ( $consignee_info ['mobile'] );
		$order ['consignee'] = strim ( $consignee_info ['consignee'] );
		$order ['zip'] = strim ( $consignee_info ['zip'] );
		
		$order ['ecv_money'] = 0;
		$order ['account_money'] = 0;
		$order ['ecv_sn'] = '';
		// $order['delivery_id'] = $data['delivery_info']['id'];
		
		$order ['payment_id'] = $data ['payment_info'] ['id'];
		$order ['payment_fee'] = $data ['payment_fee'];
		$order ['bank_id'] = "";
		
		// 更新来路
		$order ['referer'] = $GLOBALS ['referer'];
		$user_info = es_session::get ( "user_info" );
		$order ['user_name'] = $user_info ['user_name'];
		
		$order ['duobao_ip'] = CLIENT_IP;
		require_once APP_ROOT_PATH . "system/extend/ip.php";
		$ip = new iplocate ();
		$area = $ip->getaddress ( CLIENT_IP );
		$order ['duobao_area'] = $area ['area1'];
		
		$order['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
		$order['create_date_ym'] = to_date(NOW_TIME,"Y-m");
		$order['create_date_y'] = to_date(NOW_TIME,"Y");
		$order['create_date_m'] = to_date(NOW_TIME,"m");
		$order['create_date_d'] = to_date(NOW_TIME,"d");
		
		do {
			$order ['order_sn'] = to_date ( NOW_TIME, "Ymdhis" ) . rand ( 10, 99 );
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order", $order, 'INSERT', '', 'SILENT' );
			$order_id = intval ( $GLOBALS ['db']->insert_id () );
		} while ( $order_id == 0 );
		
		// 生成订单商品
		foreach ( $goods_list as $k => $v ) {
            $cart_ids[] = $v['id'];

            $deal_info = load_auto_cache ( "deal", array (
					"id" => $v ['deal_id'] 
			) );
			$goods_item = array ();
			
			$goods_item ['deal_id'] = $v ['deal_id'];
			$goods_item ['duobao_id'] = $v ['duobao_id'];
			$goods_item ['duobao_item_id'] = $v ['duobao_item_id'];
			$goods_item ['number'] = $v ['number'];
			$goods_item ['unit_price'] = $v ['unit_price'];
			$goods_item ['total_price'] = $v ['total_price'];
			$goods_item ['name'] = $v ['name'];
			$goods_item ['delivery_status'] = 0;
			$goods_item ['return_score'] = $v ['return_score'];
			$goods_item ['return_total_score'] = $v ['return_total_score'];
			$goods_item ['verify_code'] = $v ['verify_code'];
			$goods_item ['order_sn'] = $order ['order_sn'];
			$goods_item ['order_id'] = $order_id;
			$goods_item ['is_arrival'] = 0;
			$goods_item ['deal_icon'] = $v ['deal_icon'];
			$goods_item ['user_id'] = $user_id;
			$goods_item ['duobao_ip'] = $order ['duobao_ip'];
			$goods_item ['duobao_area'] = $order ['duobao_area'];
			$goods_item ['type'] = $order ['type'];
			$goods_item['create_time'] = NOW_TIME;
			$goods_item['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
			$goods_item['create_date_ym'] = to_date(NOW_TIME,"Y-m");
			$goods_item['create_date_y'] = to_date(NOW_TIME,"Y");
			$goods_item['create_date_m'] = to_date(NOW_TIME,"m");
			$goods_item['create_date_d'] = to_date(NOW_TIME,"d");
			
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order_item", $goods_item, 'INSERT', '', 'SILENT' );
		}

        $cart_ids = implode(',', $cart_ids);
        $GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "deal_cart where  user_id={$user_id} and id in ({$cart_ids})" );

		// 开始更新订单表的deal_ids
		load_cart_list ( true );
		
		// 生成order_id 后
		// 1. 代金券支付
		$ecv_data = $data ['ecv_data'];
		if ($ecv_data) {
			$ecv_payment_id = $GLOBALS ['db']->getOne ( "select id from " . DB_PREFIX . "payment where class_name = 'Voucher'" );
			if ($ecv_data ['money'] > $order ['total_price'])
				$ecv_data ['money'] = $order ['total_price'];
			$payment_notice_id = make_payment_notice ( $ecv_data ['money'],'', $order_id, $ecv_payment_id, "", $ecv_data ['id'] );
			require_once APP_ROOT_PATH . "system/payment/Voucher_payment.php";
			$voucher_payment = new Voucher_payment ();
			$voucher_payment->direct_pay ( $ecv_data ['sn'], $ecv_data ['password'], $payment_notice_id );
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
            foreach($goods_list as $k=>$v){
                $expire_ids[] = $v['id'];
            }
            $GLOBALS['db']->query("delete from ".DB_PREFIX."deal_cart where id in(".  implode(",", $expire_ids).") and user_id = ".intval($GLOBALS['user_info']['id']));

        } else {
			$payment_info = $data ['payment_info'];
			if ($payment_info ['online_pay'] == 3 && $data ['pay_price'] > 0) 			// sdk在线支付
			{
				$payment_notice_id = make_payment_notice ( $data ['pay_price'],'', $order_id, $payment_info ['id'] );
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
	 * 购物车提交订单接口
	 * 输入：
	 * ecvsn:string 代金券序列号
	 * ecvpassword: string 代金券密码
	 * payment:int 支付方式ID
	 * all_account_money:int 是否使用余额支付 0否 1是
	 * content:string 订单备注
	 *
	 * 输出：
	 * status: int 状态 0:失败 1:成功 -1:未登录
	 * info: string 失败时返回的错误信息，用于提示
	 * 以下参数为status为1时返回
	 * pay_status:int 0未支付成功 1全部支付
	 * order_id:int 订单ID
	 */
	public function order_done() {
	    require_once APP_ROOT_PATH . "system/model/cart.php";
	    require_once APP_ROOT_PATH . "system/model/deal_order.php";
	
	    $payment = intval ( $GLOBALS ['request'] ['payment'] );
	    $all_account_money = intval ( $GLOBALS ['request'] ['all_account_money'] );
	    $ecvsn = $GLOBALS ['request'] ['ecvsn'] ? strim ( $GLOBALS ['request'] ['ecvsn'] ) : '';
	    $ecvpassword = $GLOBALS ['request'] ['ecvpassword'] ? strim ( $GLOBALS ['request'] ['ecvpassword'] ) : '';
	    $memo = strim ( $GLOBALS ['request'] ['content'] );
	    $order_id = intval ( $GLOBALS ['request'] ['order_id'] );

	    $cart_result = load_deal_order_list ($order_id);
	    $goods_list = $cart_result ['cart_list'];
	
	    // $data_log['goods_list'] = $goods_list;
	    if (! $goods_list) {
	        return output ( array (), 0, "订单为空" );
	    }
	
	    require_once APP_ROOT_PATH . "system/model/duobao.php";
	    foreach($goods_list as $item)
	    {
	        $res = duobao::check_order_duobao_number($item['duobao_item_id'], 0, $order_id);
	        if($res['status']==0)
	            return output ( array (), 0, $res['info']);
	    }
	
	    // 验证登录
	    if ((check_login () == LOGIN_STATUS_TEMP && $GLOBALS ['user_info'] ['money'] > 0) || check_login () == LOGIN_STATUS_NOLOGIN) {
	        return output ( array (), - 1, "请先登录" );
	    }
	
	    // 开始验证订单接交信息
	    $data = count_buy_total ( $payment, 0, $all_account_money, $ecvsn, $ecvpassword, $goods_list, 0, 0, '' );
	    
	    // 每次提交也要修改订单的支付方式
	    $GLOBALS ['db']->query ( "update " . DB_PREFIX . "deal_order set payment_id =".$data['payment_info']['id']." where id =" . $order_id);
	    
	    
	    if ( !$data ['payment_info'] && $all_account_money != 1 ) {
	        return output ( array (), 0, "请选择支付方式" );
	    }
	    
	    if (round ( $data ['pay_price'], 4 ) > 0 && !$data ['payment_info']) {
	        return output ( array (), 0, "余额不足，请选择支付方式" );
	    }
	    
	    
	    
	    
	    // 查询订单信息
	    $order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id." and is_delete = 0 and pay_status <> 2 and order_status <> 1 and user_id =".intval($GLOBALS['user_info']['id']));
	    
	    // 1. 代金券支付
	    $ecv_data = $data ['ecv_data'];
	    if ($ecv_data) {
	        $ecv_payment_id = $GLOBALS ['db']->getOne ( "select id from " . DB_PREFIX . "payment where class_name = 'Voucher'" );
	        if ($ecv_data ['money'] > $order ['total_price'])
	            $ecv_data ['money'] = $order ['total_price'];
	        $payment_notice_id = make_payment_notice ( $ecv_data ['money'], '', $order_id, $ecv_payment_id, "", $ecv_data ['id'] );
	        require_once APP_ROOT_PATH . "system/payment/Voucher_payment.php";
	        $voucher_payment = new Voucher_payment ();
	        $voucher_payment->direct_pay ( $ecv_data ['sn'], $ecv_data ['password'], $payment_notice_id );
	    }
	
	    // 2. 余额支付
	    $account_money = $data ['account_money'];
	    if (floatval ( $account_money ) > 0) {
	        $account_payment_id = $GLOBALS ['db']->getOne ( "select id from " . DB_PREFIX . "payment where class_name = 'Account'" );
	        $payment_notice_id = make_payment_notice ( $account_money, '', $order_id, $account_payment_id );
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
	
    public function free_done(){
	    global_run();
	    require_once APP_ROOT_PATH . "system/model/cart.php";
	    require_once APP_ROOT_PATH . "system/model/deal_order.php";
	    require_once APP_ROOT_PATH . "system/model/duobao.php";
	    
	    
	    $ajax = 1;
	    
	    $memo = strim ( $_REQUEST['content'] );
	    
	    $cart_result = load_free_list();
	    $goods_list = $cart_result['cart_list'];
	    
	    
	    if (! $goods_list) {
	        showErr($GLOBALS['lang']['CART_EMPTY_TIP'],$ajax,url("index"));
	    }
	    
	    // 验证购物车
	    if((check_save_login()!=LOGIN_STATUS_LOGINED&&$GLOBALS['user_info']['coupons']>0)||check_save_login()==LOGIN_STATUS_NOLOGIN)
	    {
	        showErr($GLOBALS['lang']['PLEASE_LOGIN_FIRST'],$ajax,url("index","user#login"));
	    }
	    // 开始验证订单接交信息
	    //$data = count_buy_total ( $payment, $account_money, $all_account_money, $ecvsn, $ecvpassword, $goods_list, 0, 0, '' );
	    // 开始验证订单接交信息
	    
	    foreach ( $goods_list as $k => $v ) {
	        $total_price += $v ['total_price'];
	        $return_total_score += $v ['return_total_score'];//购买返积分
	    }
	    
	    
	    $user_id = intval ( $GLOBALS['user_info'] ['id'] );
	    
	    $account_money = $total_price;//需要付款的优惠币数量
	    //用户总优惠币
	    $user_coupons = $GLOBALS['db']->getOne ( "select coupons from " . DB_PREFIX . "user where id = " . $user_id );
	    
	    // 开始生成订单
	    $now = NOW_TIME;
	    $order['type'] = 4; // 免费购订单
	    $order['user_id'] = $user_id;
	    $order['create_time'] = $now;
	    $order['update_time'] = $now;
	    
	    
	    // + 支付手续费
	    //$order ['pay_amount'] = 0;
	    $order ['pay_status'] = 0; // 新单都为零， 等下面的流程同步订单状态
	    $order ['order_status'] = 0; // 新单都为零， 等下面的流程同步订单状态
	    $order ['return_total_score'] = $return_total_score; // 结单后送的积分
	    $order ['memo'] = $memo;
	    
	    $order ['ecv_money'] = 0;
	    $order ['account_money'] = 0;
	    $order ['ecv_sn'] = '';	   
	    $order ['bank_id'] = "";
	    
	    
	    // 更新来路
	    $order['referer'] = $GLOBALS['referer'];
	    $user_info = es_session::get ( "user_info" );
	    $order['user_name'] = $user_info['user_name'];
	    $order['coupons'] = $account_money;
	    
	    $order['duobao_ip'] = CLIENT_IP;
	    require_once APP_ROOT_PATH . "system/extend/ip.php";
	    $ip = new iplocate ();
	    $area = $ip->getaddress ( CLIENT_IP );
	    $order ['duobao_area'] = $area['area1'];
	    
	    $order['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
	    $order['create_date_ym'] = to_date(NOW_TIME,"Y-m");
	    $order['create_date_y'] = to_date(NOW_TIME,"Y");
	    $order['create_date_m'] = to_date(NOW_TIME,"m");
	    $order['create_date_d'] = to_date(NOW_TIME,"d");
	    
	    do {
	        $order['order_sn'] = to_date ( NOW_TIME, "Ymdhis" ) . rand ( 10, 99 );
	        $GLOBALS['db']->autoExecute ( DB_PREFIX . "deal_order", $order, 'INSERT', '', 'SILENT' );
	        $order_id = intval ( $GLOBALS['db']->insert_id () );
	    } while ( $order_id == 0 );
	    
	    // 生成订单商品
	    foreach ( $goods_list as $k => $v ) {
	        $deal_info = load_auto_cache ( "deal", array (
	            "id" => $v ['deal_id']
	        ) );
	        $goods_item = array ();
	    
	        $goods_item ['deal_id'] = $v ['deal_id'];
	        $goods_item ['duobao_id'] = $v ['duobao_id'];
	        $goods_item ['duobao_item_id'] = $v ['duobao_item_id'];
	        $goods_item ['number'] = $v ['number'];
	        $goods_item ['unit_price'] = $v ['unit_price'];
	        $goods_item ['total_price'] = $v ['total_price'];
	        $goods_item ['name'] = $v ['name'];
	        $goods_item ['delivery_status'] = 0;
	        $goods_item ['return_score'] = $v ['return_score'];
	        $goods_item ['return_total_score'] = $v ['return_total_score'];
	        $goods_item ['verify_code'] = $v ['verify_code'];
	        $goods_item ['order_sn'] = $order ['order_sn'];
	        $goods_item ['order_id'] = $order_id;
	        $goods_item ['is_arrival'] = 0;
	        $goods_item ['deal_icon'] = $v ['deal_icon'];
	        $goods_item ['user_id'] = $user_id;
	        $goods_item ['duobao_ip'] = $order ['duobao_ip'];
	        $goods_item ['duobao_area'] = $order ['duobao_area'];
	        $goods_item ['coupons'] = $order['coupons'];
	        $goods_item ['type'] = $order ['type'];
	        $goods_item['create_time'] = NOW_TIME;
	        $goods_item['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
	        $goods_item['create_date_ym'] = to_date(NOW_TIME,"Y-m");
	        $goods_item['create_date_y'] = to_date(NOW_TIME,"Y");
	        $goods_item['create_date_m'] = to_date(NOW_TIME,"m");
	        $goods_item['create_date_d'] = to_date(NOW_TIME,"d");
	    
	        $GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order_item", $goods_item, 'INSERT', '', 'SILENT' );
	    }
	    
	    
	    load_free_list ( true );
	    
	    if($GLOBALS ['user_info']['is_cart_agreement']==0){
	        $GLOBALS ['db']->query ( "update " . DB_PREFIX . "user set is_cart_agreement=1  where id=".$GLOBALS ['user_info']['id'] );
	    }
	    
    	    $money=0;
    	    $is_coupons=1;
    	    $payment_id=0;//优惠币支付
	    if ($account_money > 0) {
	        $payment_notice_id = make_payment_notice ( $money,$account_money, $order_id, $payment_id );
	        
            $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
	       
            $account_money  = $payment_notice['coupons'];
             
            $order_sn       = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
            
            if($payment_notice && $account_money>0)
            {
                $GLOBALS['db']->query("update ".DB_PREFIX."user set coupons = coupons - ".$account_money." where coupons - ".$account_money." >=0 and id = ".$payment_notice['user_id']);
                if($GLOBALS['db']->affected_rows()>0)
                {
                    $rs = payment_paid($payment_notice_id,$is_coupons);
                    
                    $msg = sprintf('付款：订单号%s,付款单号%s',$order_sn,$payment_notice['notice_sn']);
                    if($rs)
                    {
                        $user_id = $payment_notice['user_id'];
                        $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where is_delete = 0 and is_effect = 1 and id = ".$user_id);
                       
                        if($user_info['is_robot']==0)// by hc4.18 机器人不产生日志
                        {
                            $log_info['log_info'] = $msg;
                            $log_info['log_time'] = NOW_TIME;
                            $adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
                             
                            $adm_id = intval($adm_session['adm_id']);
                            if($adm_id!=0)
                            {
                                $log_info['log_admin_id'] = $adm_id;
                            }
                            else
                            {
                                $log_info['log_user_id'] = intval($user_info['id']);
                            }
                            $log_info['coupons'] = '-'.intval($account_money);
                            $log_info['score'] = 0;
                            $log_info['point'] = 0;
                            $log_info['user_id'] = $user_id;
                            $GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
                            
                            load_user($user_id,true);
                        } 
                    }
                }
            }
	    }

	     
	    $os = order_paid($order_id); 
	    
	    if($os)
	    {
	        $data = array();
	        $data['info'] = "";
	        $data['jump'] = wap_url("index","payment#done",array("id"=>$order_id));
	        
	        foreach($goods_list as $k=>$v){
	            $expire_ids[] = $v['id'];
	        }
	        
	        $GLOBALS['db']->query("delete from ".DB_PREFIX."deal_cart where id in(".  implode(",", $expire_ids).") and user_id = ".intval($GLOBALS['user_info']['id']));
	        ajax_return($data); //支付成功
	    
	    }
	    else
	    {
	        $data = array();
	        $data['info'] = "";
	        $data['jump'] = wap_url("index","payment#pay",array("id"=>$payment_notice_id));
	        ajax_return($data);
	    } 
	}
	
	
}
?>