<?php

class pkApiModule extends MainBaseApiModule
{
    /**
     * @return unknown_type|void
     *
     */
    public function index()
    {
        $root = array();

        $user_data = $GLOBALS['user_info'];

        $page = intval($GLOBALS['request']['page']) ? intval($GLOBALS['request']['page']) : 1; //当前分页

        $user_login_status = check_login();
            $root['user_login_status'] = $user_login_status;
            $root['page_title'] = "PK专区";
            $page_size = PAGE_SIZE;
            $check_login=check_save_login();
            $log_type = intval($GLOBALS['request']['log_type']);
            if ($log_type == 1)
                $log_type_condition = " and di.success_time = 0 ";
            elseif ($log_type == 2)
                $log_type_condition = " and di.has_lottery = 1 ";
            else
                $log_type_condition = "";


            $limit = (($page - 1) * $page_size) . "," . $page_size;
            if($check_login==LOGIN_STATUS_LOGINED){
                $sql = "select di.*,di.unit_price,di.has_password,di.current_buy as number,sum(doi.number) my_has_buy_number from " . DB_PREFIX . "duobao_item as di left join " . DB_PREFIX . "deal_order_item as doi  on doi.duobao_item_id=di.id and doi.user_name='" . $user_data['user_name'] . "'  where di.is_pk=1  and di.is_active=1 and di.is_effect = 1  ";
                $sql_count = "select count(distinct(di.id)) from " . DB_PREFIX . "duobao_item as di  where di.is_pk=1 and di.is_effect = 1 and di.is_active=1";
            }else{
                $sql = "select di.*,di.unit_price,di.has_password,di.current_buy as number,0 as my_has_buy_number from " . DB_PREFIX . "duobao_item as di left join " . DB_PREFIX . "deal_order_item as doi  on doi.duobao_item_id=di.id  where di.is_active=1 and di.is_effect = 1 and di.is_pk=1 ";
                $sql_count = "select count(distinct(di.id)) from " . DB_PREFIX . "duobao_item as di  where di.is_pk=1 and di.is_effect = 1 and di.is_active=1";
            }

            $sql .= $log_type_condition . " group by di.id ";
            $sql_count .= $log_type_condition;

            $sql .= " order by  di.create_time desc limit " . $limit;
            $total = $GLOBALS['db']->getOne($sql_count);
            $page_total = ceil($total / $page_size);
            $root['page'] = array("page" => $page, "page_total" => $page_total, "page_size" => $page_size, "data_total" => $total);
            $res = $GLOBALS['db']->getAll($sql);
            $list = array();
            foreach ($res as $k => $v) {
                $list[$k]['id'] = $v['id'];
                $list[$k]['unit_price']=$v['unit_price'];
                $list[$k]['min_buy'] = $v['min_buy'];
                $list[$k]['name'] = $v['name'];
                $list[$k]['my_has_buy_number'] = $v['my_has_buy_number'] ? $v['my_has_buy_number'] : 0;
                $list[$k]['icon'] = get_abs_img_root(get_spec_image($v['icon'], 200, 200, 1));
                $list[$k]['max_buy'] = $v['max_buy'];
                $list[$k]['less'] = $v['max_buy'] - $v['current_buy'];
                $list[$k]['number'] = $v['current_buy'];
                $list[$k]['success_time'] = $v['success_time'];
                $list[$k]['has_lottery'] = $v['has_lottery'];
                $list[$k]['progress'] = $v['progress'];
                $list[$k]['has_password'] = $v['has_password'];
                if ($v['has_lottery'] == 1) {
                    $list[$k]['luck_user_id'] = $v['luck_user_id'];
                    $list[$k]['luck_user_name'] = $v['luck_user_name'];
                    $list[$k]['luck_user_total'] = $v['luck_user_buy_count'];
                    $list[$k]['lottery_sn'] = $v['lottery_sn'];
                    $list[$k]['lottery_time'] = to_date($v['lottery_time']);
                } else {
                    $list[$k]['luck_user_id'] = 0;
                    $list[$k]['luck_user_name'] = "--";
                    $list[$k]['luck_user_total'] = "--";
                    $list[$k]['lottery_sn'] = "--";
                    $list[$k]['lottery_time'] = "--";
                }
            }
            $root['list'] = $list;

//        }
        return output($root);
    }

    public function record()
    {
        $root = array();

        $user_data = $GLOBALS['user_info'];

        $page = intval($GLOBALS['request']['page']) ? intval($GLOBALS['request']['page']) : 1; //当前分页

        $user_login_status = check_login();
        if ($user_login_status != LOGIN_STATUS_LOGINED) {
            $root['user_login_status'] = $user_login_status;
        } else {
            $root['user_login_status'] = $user_login_status;
            $root['page_title'] = "pk记录";
//            $page_size = PAGE_SIZE;

            $log_type = intval($GLOBALS['request']['log_type']);
            if ($log_type == 1)
                $log_type_condition = " and di.success_time = 0 ";
            elseif ($log_type == 2)
                $log_type_condition = " and di.has_lottery = 1 ";
            else
                $log_type_condition = "";


//            $limit = (($page - 1) * $page_size) . "," . $page_size;
            $sql = "select di.*,di.unit_price,di.has_password,di.current_buy as number,sum(doi.number) my_has_buy_number from " . DB_PREFIX . "duobao_item as di ".
            "left join " . DB_PREFIX . "deal_order_item as doi  on doi.duobao_item_id=di.id and doi.user_name='" . $user_data['user_name'] . "' ".
            "where di.is_pk=1 and di.is_active=1 and di.is_effect = 1 and doi.user_id=".$user_data['id']." and doi.type = 2";
            $sql_count = "select count(distinct(di.id)) from ".DB_PREFIX."deal_order_item as doi  ".
                " left join ".DB_PREFIX."duobao_item as di on doi.duobao_item_id = di.id where di.is_pk=1 and doi.user_id = ".$user_data['id']."  and doi.refund_status = 0  and doi.pay_status = 2 and di.is_effect = 1  and doi.type = 2 ";
            $sql .= $log_type_condition . " group by di.id ";
            $sql_count .= $log_type_condition;
            $sql .= " order by  di.create_time desc  " ;
            $total = $GLOBALS['db']->getOne($sql_count);
            $root['total']=$total;
//            $page_total = ceil($total / $page_size);
//            $root['page'] = array("page" => $page, "page_total" => $page_total, "page_size" => $page_size, "data_total" => $total);
            $res = $GLOBALS['db']->getAll($sql);
            $list = array();
            foreach ($res as $k => $v) {
                $list[$k]['id'] = $v['id'];
                $list[$k]['unit_price']=$v['unit_price'];
                $list[$k]['min_buy'] = $v['min_buy'];
                $list[$k]['name'] = $v['name'];
                $list[$k]['my_has_buy_number'] = $v['my_has_buy_number'] ? $v['my_has_buy_number'] : 0;
                $list[$k]['icon'] = get_abs_img_root(get_spec_image($v['icon'], 200, 200, 1));
                $list[$k]['max_buy'] = $v['max_buy'];
                $list[$k]['less'] = $v['max_buy'] - $v['current_buy'];
                $list[$k]['number'] = $v['current_buy'];
                $list[$k]['success_time'] = $v['success_time'];
                $list[$k]['has_lottery'] = $v['has_lottery'];
                $list[$k]['progress'] = $v['progress'];
                $list[$k]['has_password'] = $v['has_password'];
                if ($v['has_lottery'] == 1) {
                    $list[$k]['luck_user_id'] = $v['luck_user_id'];
                    $list[$k]['luck_user_name'] = $v['luck_user_name'];
                    $list[$k]['luck_user_total'] = $v['luck_user_buy_count'];
                    $list[$k]['lottery_sn'] = $v['lottery_sn'];
                    $list[$k]['lottery_time'] = to_date($v['lottery_time']);
                } else {
                    $list[$k]['luck_user_id'] = 0;
                    $list[$k]['luck_user_name'] = "--";
                    $list[$k]['luck_user_total'] = "--";
                    $list[$k]['lottery_sn'] = "--";
                    $list[$k]['lottery_time'] = "--";
                }
            }

            $root['list'] = $list;
        }
        $big_url = get_user_avatar($user_data['id'],"big").'?x='.mt_rand();
        $root['user_avatar'] =  $big_url ? $big_url : '';
        $root['user_logo'] = $root['user_avatar']?$root['user_avatar']:get_abs_img_root($user_data['user_logo']);
        $sql_all_count="select count(*) from " . DB_PREFIX . "duobao_item as di ".
            "left join " . DB_PREFIX . "deal_order_item as doi  on doi.duobao_item_id=di.id and doi.user_name='" . $user_data['user_name'] . "' ".
            "where di.is_pk=1 and di.is_active=1 and di.is_effect = 1 and doi.user_id=".$user_data['id']." and doi.type = 2";
        $root['all_count']=$GLOBALS['db']->getOne($sql_all_count);
        $root['win_count']=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."duobao_item where is_pk=1 and luck_user_name='".$user_data['user_name']."'");
        return output($root);
    }

    public function choose_pkgoods()
    {
        $root = array();
        $page = intval($GLOBALS['request']['page']) ? intval($GLOBALS['request']['page']) : 1; //当前分页
        $user_login_status = check_login();
            $root['user_login_status'] = $user_login_status;
            $root['page_title'] = "选择PK商品";
            $page_size = PAGE_SIZE;
            $limit = (($page - 1) * $page_size) . "," . $page_size;
            $user_id=$GLOBALS['user_info']['id']?$GLOBALS['user_info']['id']:0;
            $sql = "select do.id,do.name,do.unit_price,do.icon,do.max_buy,do.pk_min_number,(case when ISNULL(doi.create_time) then 1 when doi.create_time<".(NOW_TIME-5*60)." then 1 when doi.pk_user_id=".$user_id." then 1 else 0 end)as status  from " . DB_PREFIX ."duobao as do LEFT JOIN " . DB_PREFIX ."duobao_item as doi on doi.is_active=0 and doi.duobao_id=do.id and doi.is_pk=1 and doi.progress=0 where do.is_pk=1 and do.is_effect = 1 and do.current_schedule + 1 <= do.max_schedule";
            $sql_count = "select count(distinct(do.id)) from " . DB_PREFIX ."duobao as do LEFT JOIN " . DB_PREFIX ."duobao_item as doi on doi.is_active=0 and doi.duobao_id=do.id and doi.is_pk=1 and doi.progress=0 where do.is_pk=1 and do.is_effect = 1 and do.current_schedule + 1 <= do.max_schedule ";
            $sql .= " order by  do.id desc limit " . $limit;
            $total = $GLOBALS['db']->getOne($sql_count);
            $page_total = ceil($total / $page_size);
            $root['page'] = array("page" => $page, "page_total" => $page_total, "page_size" => $page_size, "data_total" => $total);
            $res = $GLOBALS['db']->getAll($sql);
            $list = array();
            foreach ($res as $k => $v) {
                $list[$k]['id'] = $v['id'];
                $list[$k]['name'] = $v['name'];
                $list[$k]['unit_price']=$v['unit_price'];
                $list[$k]['status'] = $v['status'];
                $list[$k]['icon'] = get_abs_img_root(get_spec_image($v['icon'], 200, 200, 1));
                $list[$k]['max_buy'] = $v['max_buy'];
                $list[$k]['pk_min_number'] = $v['pk_min_number'];
            }
            $root['list'] = $list;

        return output($root);
    }

    public function choose_pkgoods_do()
    {
        $config = array();
        $root = array();
        $root['status'] = 0;
        $user_login_status = check_login();
        $user_info = $GLOBALS['user_info'];
        $duobao_id = intval($GLOBALS['request']['duobao_id']);
        $buyer_number = intval($GLOBALS['request']['buyer_number']);
        $pk_password = intval($GLOBALS['request']['pk_password']);
        if ($user_login_status != LOGIN_STATUS_LOGINED) {
            $root['status'] = -1;
            $root['info'] = "未登录";
            return output($root, $root['status'], $root['info']);
        }
        //删除未激活的pk计划中未付款的购物车以及相关订单
        $deal_cart_duobao_item_id = $GLOBALS['db']->getAll("select duobao_item_id as id from " . DB_PREFIX . "deal_cart where user_id=" . $user_info['id'] . " and is_pk=1");
        //删除该用户的pk购物车
        $GLOBALS['db']->query("delete from " . DB_PREFIX . "deal_cart where user_id=" . $user_info['id'] . " and is_pk=1");
        if ($deal_cart_duobao_item_id) {
            $duobao_item_id = array();
            foreach ($deal_cart_duobao_item_id as $val) {
                $duobao_item_id[] = $val['id'];
            }
            if ($duobao_item_id) {
                require_once APP_ROOT_PATH . "system/model/deal_order.php";
                $order_info = $GLOBALS['db']->getAll("select do.id from " . DB_PREFIX . "deal_order as do where do.pay_status=0 and do.type=2 and do.id in (select order_id from " . DB_PREFIX . "deal_order_item where duobao_item_id in(" . implode($duobao_item_id) . ") and user_id=" . $user_info['id'] . ")");
                foreach ($order_info as $val) {
                    cancel_order($val['id']);
                    del_order($val['id']);
                }
            }
        }
        if (!$duobao_id) {
            $root['info'] = '夺宝计划id不存在';
            return output($root, $root['status'], $root['info']);
        }
        $duobao = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "duobao where id=" . $duobao_id);
        if (!$duobao) {
            $root['info'] = '夺宝计划不存在';
            return output($root, $root['status'], $root['info']);
        } else {
            $max_buy = intval($duobao['max_buy']);
            if ($max_buy % $buyer_number != 0) {
                $root['info'] = '参与人数不合法';
                return output($root, $root['status'], $root['info']);
            }
            if ($buyer_number < intval($duobao['pk_min_number'])) {
                $root['info'] = "参与人数大于限制人数";
                return output($root, $root['status'], $root['info']);
            }
        }
        //设置配置pk场参数
        $config['duobao_id'] = $duobao_id;
        $min_buy = intval(intval($duobao['max_buy']) / $buyer_number);
        $config['min_buy'] = $min_buy;
        $config['user_max_buy'] = $min_buy;
        $config['pk_user_id'] = $user_info['id'];
        //pk活动生成，要支付完才能生效
        $config['is_effect'] = 0;
        if (!$pk_password) {
            $config['has_password'] = 0;
            $config['pk_password'] = '';
        } else {
            $config['has_password'] = 1;
            $config['pk_password'] = $pk_password;
        }

        require_once APP_ROOT_PATH . "system/model/duobao.php";
        $duobao_return = duobao::new_pk($config);
        $root['status'] = $duobao_return['status'];
        $root['info'] = $duobao_return['info'];
        if ($root['status']) {
            $duobao_item = $duobao_return['duobao_item']->duobao_item;
            $root['duobao_item_id'] = $duobao_item['id'];
            $root['buy_number'] = $duobao_item['min_buy'];
        }
        return output($root, $root['status'], $root['info']);
    }

    public function addcart()
    {
        $root = array();

        $id = intval($GLOBALS ['request'] ['data_id']);
        $buy_num = intval($GLOBALS ['request'] ['buy_num']);

        // 用户检测
        $user_info = $GLOBALS ['user_info'];
        require_once APP_ROOT_PATH . 'system/model/duobao.php';
        $duobao = new duobao ($id);
        $duobao_info = $duobao->duobao_item;
        if (empty ($duobao_info)) {
            return output($root, 0, "夺宝项目不存在");
        }
        $user_login_status = check_login();
        if ($user_login_status != LOGIN_STATUS_LOGINED) {
            $root ['user_login_status'] = $user_login_status;
            return output($root, -1, "请先登录用户");
        }
        $root ['user_login_status'] = $user_login_status;
        //删除未激活的pk计划中未付款的购物车以及相关订单
        $deal_cart_duobao_item_id = $GLOBALS['db']->getAll("select duobao_item_id as id from " . DB_PREFIX . "deal_cart where user_id=" . $user_info['id'] . " and is_pk=1");
        //删除该用户的pk购物车
        $GLOBALS['db']->query("delete from " . DB_PREFIX . "deal_cart where user_id=" . $user_info['id'] . " and is_pk=1");
        if ($deal_cart_duobao_item_id) {
            $duobao_item_id = array();
            foreach ($deal_cart_duobao_item_id as $val) {
                $duobao_item_id[] = $val['id'];
            }
            if ($duobao_item_id) {
                require_once APP_ROOT_PATH . "system/model/deal_order.php";
                $order_info = $GLOBALS['db']->getAll("select do.id from " . DB_PREFIX . "deal_order as do where do.pay_status=0 and do.type=2 and do.id in (select order_id from " . DB_PREFIX . "deal_order_item where duobao_item_id in(" . implode($duobao_item_id) . ") and user_id=" . $user_info['id'] . ")");
                foreach ($order_info as $val) {
                    cancel_order($val['id']);
                    del_order($val['id']);
                }
            }
        }
        // 购物车业务流程
        $res = duobao::check_pk_duobao_number($id, $buy_num, false);
        if ($res['status'] == 0) {
            return output($root, $res ['status'], $res ['info']);
        }
        $result = $duobao->addcart_pk($user_info ['id'], $buy_num, false);
        $root['deal_cart_id'] = $result['deal_cart_id'];
        $root['buy_number'] = $buy_num;
        $root ['cart_item_num'] = $result ['cart_item_num'] ? $result ['cart_item_num'] : 0;
        return output($root, $result ['status'], $result ['info']);
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
    public function check()
    {
        $root = array();
        if ((check_login() == LOGIN_STATUS_TEMP && $GLOBALS ['user_info'] ['money'] > 0) || check_login() == LOGIN_STATUS_NOLOGIN) {
            return output(array(), -1, "请先登录");
        }
        require_once APP_ROOT_PATH . "system/model/cart.php";
        $cart_result = load_pk_cart_list();
        $total_price = $cart_result ['total_data'] ['total_price'];
        // 处理购物车输出
        $cart_list_o = $cart_result ['cart_list'];
        $cart_list = array();
        
        $total_data_o = $cart_result ['total_data'];
        foreach ($cart_list_o as $k => $v) {
            $bind_data = array();
            $bind_data ['id'] = $v ['id'];

            $bind_data ['unit_price'] = round($v ['unit_price'], 2);
            $bind_data ['total_price'] = round($v ['total_price'], 2);
            $bind_data ['number'] = $v ['number'];
            $bind_data ['duobao_id'] = $v ['duobao_id'];

            $bind_data ['name'] = $v ['name'];
            $bind_data ['is_pk'] = $v ['is_pk'];
            $bind_data ['is_topspeed'] = $v ['is_topspeed'];

            $bind_data ['deal_icon'] = get_abs_img_root(get_spec_image($v ['deal_icon'], 186, 186, 1));
            $cart_list [$v ['id']] = $bind_data;
        }
        $root ['cart_list'] = $cart_list ? $cart_list : null;
        $total_data ['total_price'] = round($total_data_o ['total_price'], 2);
        $root ['total_data'] = $total_data;
        // end购物车输出

        // 统计几个专区 1P K区 2十元区 3百元区 4直购区 5极速区 6选号区 7一元区
        $area = array();
        foreach($cart_list as $k => $v){
            if($v['unit_price']==10){
                $area['range_value2']=2;
            }
            if($v['unit_price']==1){
                $area['range_value7']=7;
            }
            if($v['unit_price']==100){
                $area['range_value3']=3;
            }
            if($v['is_topspeed']==1){
                $area['range_value5']=5;
            }
            if($v['is_pk']==1){
                $area['range_value1']=1;
            }
        }
        $user_type = 0;
        foreach ($cart_list as $k => $v) {

            if ($v ['user_type'] == 1 && $v ['pid'] == 0) {
                $user_type = 1;
                break;
            }
        }

        // 输出支付方式
        global $is_app;
        if (!$is_app) {
            // 支付列表
            $sql = "select id, class_name as code, logo from " . DB_PREFIX . "payment where (online_pay = 2 or online_pay = 4 or online_pay = 5 or online_pay = 6 or online_pay=7) and is_effect = 1";
        } else {
            // 支付列表
            $sql = "select id, class_name as code, logo from " . DB_PREFIX . "payment where (online_pay = 3 or online_pay = 2 or online_pay = 4 or online_pay = 6) and is_effect = 1";
        }
        if (allow_show_api()) {
            $payment_list = $GLOBALS ['db']->getAll($sql);
        }

        // 输出支付方式
        foreach ($payment_list as $k => $v) {
            $directory = APP_ROOT_PATH . "system/payment/";
            $file = $directory . '/' . $v ['code'] . "_payment.php";
            if (file_exists($file)) {
                require_once($file);
                $payment_class = $v ['code'] . "_payment";
                $payment_object = new $payment_class ();
                $payment_list [$k] ['name'] = $payment_object->get_display_code();
            }

            if ($v ['logo'] != "")
                $payment_list [$k] ['logo'] = get_abs_img_root(get_spec_image($v ['logo'], 40, 40, 1));
        }

        sort($payment_list);
        $root ['payment_list'] = $payment_list;

        if ($total_price > 0)
            $show_payment = 1;
        else
            $show_payment = 0;
        $root ['show_payment'] = $show_payment;

        if ($show_payment) {
            $web_payment_list = load_auto_cache("cache_payment");

            foreach ($web_payment_list as $k => $v) {
                if ($v ['class_name'] == "Account" && $GLOBALS ['user_info'] ['money'] > 0) {
                    $root ['has_account'] = 1;
                }
                if ($v ['class_name'] == "Voucher") {
                    $root ['has_ecv'] = 1;
                    $sql = "select e.sn as sn,e.is_all as is_all,e.data as data,t.name as name from " . DB_PREFIX . "ecv as e left join " . DB_PREFIX . "ecv_type as t on e.ecv_type_id = t.id where " . " e.user_id = '" . $GLOBALS ['user_info'] ['id'] . "' and (e.begin_time < " . NOW_TIME . ") and (e.end_time = 0 or e.end_time > " . NOW_TIME . ") and e.meet_amount <= " .$total_price. " and (e.use_limit = 0 or e.use_count<e.use_limit)";
                    $root ['voucher_list'] = $GLOBALS ['db']->getAll($sql);
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
        $root ['account_money'] = round($GLOBALS ['user_info'] ['money'], 2);
        return output($root);
    }

    public function count_buy_total()
    {
        require_once APP_ROOT_PATH . "system/model/cart.php";

        $ecvsn = $GLOBALS ['request'] ['ecvsn'] ? strim($GLOBALS ['request'] ['ecvsn']) : '';
        $ecvpassword = $GLOBALS ['request'] ['ecvpassword'] ? strim($GLOBALS ['request'] ['ecvpassword']) : '';
        $payment = intval($GLOBALS ['request'] ['payment']);
        $all_account_money = intval($GLOBALS ['request'] ['all_account_money']);
        $bank_id = '';

        $cart_result = load_pk_cart_list();
        $goods_list = $cart_result ['cart_list'];

        $result = count_buy_total($payment, 0, $all_account_money, $ecvsn, $ecvpassword, $goods_list, 0, 0, $bank_id);
        $root = array();

        if ($result ['total_price'] > 0) {
            $feeinfo [] = array(
                "name" => "商品总价",
                "value" => round($result ['total_price'], 2) . "元"
            );
        }

        // "value" => round($result['user_discount']*10,1)."折"
        // $result['user_discount']这个算出来是折扣的钱,也就是减了多少钱
        if ($result ['user_discount'] > 0) {
            $feeinfo [] = array(
                "name" => "折扣",
                "value" => round(($result ['total_price'] - $result ['user_discount']) / $result ['total_price'] * 10, 1) . "折"
            );
        }

        if ($result ['payment_info']) {
            $directory = APP_ROOT_PATH . "system/payment/";
            $file = $directory . '/' . $result ['payment_info'] ['class_name'] . "_payment.php";
            if (file_exists($file)) {
                require_once($file);
                $payment_class = $result ['payment_info'] ['class_name'] . "_payment";
                $payment_object = new $payment_class ();
                $payment_name = $payment_object->get_display_code();
            }

            $feeinfo [] = array(
                "name" => "支付方式",
                "value" => $payment_name
            );
        }

        if ($result ['payment_fee'] > 0) {
            $feeinfo [] = array(
                "name" => "手续费",
                "value" => round($result ['payment_fee'], 2) . "元"
            );
        }

        if ($result ['account_money'] > 0) {
            $feeinfo [] = array(
                "name" => "余额支付",
                "value" => round($result ['account_money'], 2)
            );
        }

        if ($result ['ecv_money'] > 0) {
            $feeinfo [] = array(
                "name" => "红包支付",
                "value" => round($result ['ecv_money'], 2)
            );
        }

        if ($result ['buy_type'] == 0) {
            if ($result ['return_total_score']) {
                $feeinfo [] = array(
                    "name" => "返还积分",
                    "value" => round($result ['return_total_score'])
                );
            }
        }

        if ($result ['paid_account_money'] > 0) {
            $feeinfo [] = array(
                "name" => "已付",
                "value" => round($result ['paid_account_money'], 2) . "元"
            );
        }

        if ($result ['paid_ecv_money'] > 0) {
            $feeinfo [] = array(
                "name" => "红包已付",
                "value" => round($result ['paid_ecv_money'], 2) . "元"
            );
        }

        if ($result ['buy_type'] == 0) {
            $feeinfo [] = array(
                "name" => "总计",
                "value" => round($result ['pay_total_price'], 2) . "元"
            );
        }

        if ($result ['pay_price']) {
            $feeinfo [] = array(
                "name" => "应付总额",
                "value" => round($result ['pay_price'], 2) . "元"
            );
        }

        if ($result ['promote_description']) {
            foreach ($result ['promote_description'] as $row) {
                $feeinfo [] = array(
                    "name" => "",
                    "value" => $row
                );
            }
        }
        $root ['feeinfo'] = $feeinfo;
        $root ['pay_price'] = round($result ['pay_price'], 2);

        return output($root);
    }
    public function check_cart() {
        $root = array ();

        $num_req = $GLOBALS ['request'] ['num'];
        $userdata = $GLOBALS['user_info'];
        $num = array ();
        foreach ( $num_req as $k => $v ) {
            $sv = intval ( $v );
            if ($sv)
                $num [$k] = intval ( $sv );
        }
        $user_login_status = check_login ();

        require_once APP_ROOT_PATH . "system/model/cart.php";
        if ($user_login_status == LOGIN_STATUS_NOLOGIN) {
            return output ( $root, - 1, "请先登录" );
        }

        $cart_result = load_pk_cart_list ();
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
            $data = check_pk_cart ( $id, $number );
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
            load_pk_cart_list ( true );
        }
        $root ['user_data'] = $userdata;
        return output ( $root );
    }
    public function done()
    {
        require_once APP_ROOT_PATH . "system/model/cart.php";
        require_once APP_ROOT_PATH . "system/model/deal_order.php";

        $payment = intval($GLOBALS ['request'] ['payment']);
        $all_account_money = intval($GLOBALS ['request'] ['all_account_money']);
        $ecvsn = $GLOBALS ['request'] ['ecvsn'] ? strim($GLOBALS ['request'] ['ecvsn']) : '';
        $ecvpassword = $GLOBALS ['request'] ['ecvpassword'] ? strim($GLOBALS ['request'] ['ecvpassword']) : '';
        $memo = strim($GLOBALS ['request'] ['content']);

        $cart_result = load_pk_cart_list();
        $goods_list = $cart_result ['cart_list'];

        // $data_log['goods_list'] = $goods_list;
        // logger::write(print_r($data_log,1));
        if (!$goods_list) {
            return output(array(), 0, "购物车为空");
        }

        require_once APP_ROOT_PATH . "system/model/duobao.php";
        foreach ($goods_list as $item) {
            $res = duobao::check_pk_duobao_number($item['duobao_item_id'], 0);
            if ($res['status'] == 0)
                return output(array(), 0, $res['info']);
        }

        // 验证购物车
        if ((check_login() == LOGIN_STATUS_TEMP && $GLOBALS ['user_info'] ['money'] > 0) || check_login() == LOGIN_STATUS_NOLOGIN) {
            return output(array(), -1, "请先登录");
        }

        // 开始验证订单接交信息
        $data = count_buy_total($payment, 0, $all_account_money, $ecvsn, $ecvpassword, $goods_list, 0, 0, '');
        if (round($data ['pay_price'], 4) > 0 && !$data ['payment_info']) {
            return output(array(), 0, "请选择支付方式");
        }

        // 结束验证订单接交信息

        $user_id = $GLOBALS ['user_info'] ['id'];

        // 获取用户地址
        $consignee_info = $GLOBALS ['db']->getRow("select * from " . DB_PREFIX . "user_consignee where is_default=1 and user_id=" . $user_id);
        $region_conf = load_auto_cache("delivery_region");

        $region_lv1 = intval($consignee_info ['region_lv1']);
        $region_lv2 = intval($consignee_info ['region_lv2']);
        $region_lv3 = intval($consignee_info ['region_lv3']);
        $region_lv4 = intval($consignee_info ['region_lv4']);
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
        $order ['address'] = strim($consignee_info ['address']);
        $order ['mobile'] = strim($consignee_info ['mobile']);
        $order ['consignee'] = strim($consignee_info ['consignee']);
        $order ['zip'] = strim($consignee_info ['zip']);

        $order ['ecv_money'] = 0;
        $order ['account_money'] = 0;
        $order ['ecv_sn'] = '';
        // $order['delivery_id'] = $data['delivery_info']['id'];

        $order ['payment_id'] = $data ['payment_info'] ['id'];
        $order ['payment_fee'] = $data ['payment_fee'];
        $order ['bank_id'] = "";

        // 更新来路
        $order ['referer'] = $GLOBALS ['referer'];
        $user_info =$GLOBALS['user_info'];
        $order ['user_name'] = $user_info ['user_name'];

        $order ['duobao_ip'] = CLIENT_IP;
        require_once APP_ROOT_PATH . "system/extend/ip.php";
        $ip = new iplocate ();
        $area = $ip->getaddress(CLIENT_IP);
        $order ['duobao_area'] = $area ['area1'];

        $order['create_date_ymd'] = to_date(NOW_TIME, "Y-m-d");
        $order['create_date_ym'] = to_date(NOW_TIME, "Y-m");
        $order['create_date_y'] = to_date(NOW_TIME, "Y");
        $order['create_date_m'] = to_date(NOW_TIME, "m");
        $order['create_date_d'] = to_date(NOW_TIME, "d");

        do {
            $order ['order_sn'] = to_date(NOW_TIME, "Ymdhis") . rand(10, 99);
            $GLOBALS ['db']->autoExecute(DB_PREFIX . "deal_order", $order, 'INSERT', '', 'SILENT');
            $order_id = intval($GLOBALS ['db']->insert_id());
        } while ($order_id == 0);

        // 生成订单商品
        $goods_item = array();
        foreach ($goods_list as $k => $v) {
            $deal_info = load_auto_cache("deal", array(
                "id" => $v ['deal_id']
            ));
//            $duobao_item_id[] = $v['duobao_item_id'];
            $goods_item ['deal_id'] = $v ['deal_id'];
            $goods_item['has_password']=$v['has_password'];
            $goods_item['pk_password']=$v['pk_password'];
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
            $goods_item['create_date_ymd'] = to_date(NOW_TIME, "Y-m-d");
            $goods_item['create_date_ym'] = to_date(NOW_TIME, "Y-m");
            $goods_item['create_date_y'] = to_date(NOW_TIME, "Y");
            $goods_item['create_date_m'] = to_date(NOW_TIME, "m");
            $goods_item['create_date_d'] = to_date(NOW_TIME, "d");

            $GLOBALS ['db']->autoExecute(DB_PREFIX . "deal_order_item", $goods_item, 'INSERT', '', 'SILENT');
        }

        // 开始更新订单表的deal_ids
//		$GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "deal_cart where session_id = '" . es_session::id () . "'" );
        load_pk_cart_list(true);

        // 生成order_id 后
        // 1. 代金券支付
        $ecv_data = $data ['ecv_data'];
        if ($ecv_data) {
            $ecv_payment_id = $GLOBALS ['db']->getOne("select id from " . DB_PREFIX . "payment where class_name = 'Voucher'");
            if ($ecv_data ['money'] > $order ['total_price'])
                $ecv_data ['money'] = $order ['total_price'];
            $payment_notice_id = make_payment_notice($ecv_data ['money'], '', $order_id, $ecv_payment_id, "", $ecv_data ['id']);
            require_once APP_ROOT_PATH . "system/payment/Voucher_payment.php";
            $voucher_payment = new Voucher_payment ();
            $voucher_payment->direct_pay($ecv_data ['sn'], $ecv_data ['password'], $payment_notice_id);
        }

        // 2. 余额支付
        $account_money = $data ['account_money'];
        if (floatval($account_money) > 0) {
            $account_payment_id = $GLOBALS ['db']->getOne("select id from " . DB_PREFIX . "payment where class_name = 'Account'");
            $payment_notice_id = make_payment_notice($account_money, '', $order_id, $account_payment_id);
            require_once APP_ROOT_PATH . "system/payment/Account_payment.php";
            $account_payment = new Account_payment ();
            $account_payment->get_payment_code($payment_notice_id);
        }

        // //3. 相应的支付接口
        // $payment_info = $data['payment_info'];
        // if($payment_info&&$data['pay_price']>0)
        // {
        // $payment_notice_id =
        // make_payment_notice($data['pay_price'],$order_id,$payment_info['id']);
        // //创建支付接口的付款单
        // }
        $rs = order_paid($order_id);
        $duobao_item=$GLOBALS['db']->getRow("select progress from ".DB_PREFIX."duobao_item where id=".$goods_item['duobao_item_id']);
        if ($rs) {
            // 支付成功后的操作
            $root ['pay_status'] = 1;
            $root ['order_id'] = $order_id;
            foreach ($goods_list as $k => $v) {
                $expire_ids[] = $v['id'];
            }
            $GLOBALS['db']->query("update " . DB_PREFIX . "duobao_item set is_active=1 where id=".$goods_item['duobao_item_id']);
            if($duobao_item['progress']!=100)
            $GLOBALS['db']->query("update ".DB_PREFIX."duobao set is_effect=0 where id=".$goods_item['duobao_id']);
            $GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "deal_cart where  is_pk=1 and user_id =".$user_info['id']." and duobao_item_id=".$goods_item['duobao_item_id']);
            if($goods_item['has_password']==1)
                send_msg($user_info['id'], "您开启".$goods_item['name']."的pk活动,pk密码为".$goods_item['pk_password'], "notify");
        } else {
            $payment_info = $data ['payment_info'];
            if ($payment_info ['online_pay'] == 3 && $data ['pay_price'] > 0) // sdk在线支付
            {
                $payment_notice_id = make_payment_notice($data ['pay_price'], '', $order_id, $payment_info ['id']);
                require_once APP_ROOT_PATH . "system/payment/" . $payment_info ['class_name'] . "_payment.php";
                $payment_class = $payment_info ['class_name'] . "_payment";
                $payment_object = new $payment_class ();
                $payment_code = $payment_object->get_payment_code($payment_notice_id);
                $root ['pay_status'] = 0;
                $root ['order_id'] = $order_id;
                $root ['sdk_code'] = $payment_code ['sdk_code'];
                return output($root, 2); // sdk支付
            } else {
                $root ['pay_status'] = 0;
                $root ['order_id'] = $order_id;
            }
            $root ['is_app'] = intval($GLOBALS ['is_app']);
        }
        return output($root);
    }

}