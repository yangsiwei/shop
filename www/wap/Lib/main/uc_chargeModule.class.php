<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class uc_chargeModule extends MainBaseModule
{

    public function index()
    {
        global_run();
        init_app_page();

        $param=array();
        $data = call_api_core("uc_charge","index",$param);

        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        $payment_class = $GLOBALS['db']->getOne("select payment from ".DB_PREFIX."payment_mode where status = 1");
//        $prev_url = substr($_SERVER['HTTP_REFERER'],0,strpos($_SERVER['HTTP_REFERER'],'?'));
//        if($prev_url == 'https://www.aliduobaodao.com/wap/shukebao/post.php' || $prev_url == 'http://www.aliduobaodao.com/wap/shukebao/post.php'){
//            $is_pay = true;
//        }
//        $GLOBALS['tmpl']->assign("is_pay",$is_pay);
        $GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
        $GLOBALS['tmpl']->assign("payment_class",$payment_class);
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("uc_charge.html");
    }

    public function biejinlai(){
        $res = $GLOBALS['db']->getOne("select sum(pay_amount) money from ".DB_PREFIX."deal_order where type=1 and pay_status=2");
        echo $res;
        $res1 = $GLOBALS['db']->getOne("select sum(money) money from ".DB_PREFIX."withdraw where is_paid=1 and withdraw_method='money'");
        var_dump($res1);
        $res2 = $GLOBALS['db']->getOne("select sum(money) money from ".DB_PREFIX."user where is_effect=1 and is_robot=0 and is_delete=0 and id > 3390");
        echo $res2;
    }


    public function done()
    {
        global_run();
        init_app_page();
        $param=array();
        $param['money'] = floatval($_REQUEST['money']);
        $param['payment_id'] = intval($_REQUEST['payment_id']);
        $data = call_api_core("uc_charge","done",$param);
        $ajaxobj['is_app'] = $data['is_app'];
        if($data['status']==-1)
        {
            $ajaxobj['status'] = 1;
            $ajaxobj['jump'] = wap_url("index","user#login");
            ajax_return($ajaxobj);
        }
        elseif($data['status']==1)
        {
            if($param['payment_id'] == 12){
                $ajaxobj['status'] = 1;
                $ajaxobj['jump'] = "http://www.caizhi998.com/Public/php/shanpay.php?money=".$param['money']."&out_order_no=".$data['out_order_no'];
            }else{
                $ajaxobj['status'] = 1;
                $ajaxobj['jump'] = $data['payment_code']['pay_action'];
            }

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



    public function done_gepi()
    {
        global_run();
        init_app_page();
        $user_data = $GLOBALS['user_info'];
        $user_id = intval($user_data['id']);
        $payment_id = intval($_REQUEST['payment_id']);
        $money = floatval($_REQUEST['money']);
        $bank_code = $_REQUEST['bank_code'];


        if(!$payment_id){
            return output("", 0, "支付接口未开通，请联系客服");
        }

        $now_time = time();
        $create_time = $GLOBALS['db']->getAll("select create_time from ".DB_PREFIX."deal_order where user_id = ".$user_id." and total_price = ".$money." and pay_status = 0");

        foreach($create_time as $vv){
            if($vv['create_time'] == $now_time){
                return output("", 0, "请不要重复提交");
            }
        }

        $rest_recharge = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "rest_recharge where id = 1");
        //限制充值金额
//        if ($money % 10 != 0) {
//            return output("", 0, "充值金额限定为10的倍数");
//        }
//        if ($money < $rest_recharge['lowest_recharge'] || $money > $rest_recharge['highest_recharge']) {
//            return output("", 0, "充值金额的限定范围是{$rest_recharge['lowest_recharge']}~{$rest_recharge['highest_recharge']}");
//        }
        //单日累计充值金额
        $today = date('d');
        $last_recharge_date = date('d', $GLOBALS['user_info']['last_recharge_date']);
        $day_recharge_money = $GLOBALS['user_info']['day_recharge_money'] + $money;
        //控制单日累计充值金额
        if ($rest_recharge['day_recharge_money'] != 0) {
            if ($rest_recharge['day_recharge_money'] < $day_recharge_money) {
                return output("", 0, "累计充值金额超过{$rest_recharge['day_recharge_money']}");
            }
        }

        require_once APP_ROOT_PATH . "system/db/db.php";
        $root['user_login_status'] = 1;
        //开始生成订单
        $now = time();
        $order['type'] = 1; //充值单
        $order['user_id'] = $user_id;
        $order['create_time'] = $now;
        $order['update_time'] = $now;
        $order['total_price'] = $money;
        $order['deal_total_price'] = $money;
        $order['pay_amount'] = 0;
        $order['pay_status'] = 0;
        $order['delivery_status'] = 5;
        $order['order_status'] = 0;
        $order['payment_id'] = $payment_id;
        $order['order_sn'] = to_date(get_gmtime(), "Ymdhis") . rand(100, 999).$user_id;

        $GLOBALS['db']->autoExecute(DB_PREFIX . "deal_order", $order, 'INSERT', '', 'SILENT');
        $order_id = intval($GLOBALS ['db']->insert_id());
        //开始生成支付订单
        $notice_id = $GLOBALS['db']->getOne("select id from " . DB_PREFIX . "payment_notice where is_paid=0 and order_id =" . $order_id . " and payment_id=" . $payment_id . " and (" . NOW_TIME . "-create_time<=30)");

        if (intval($notice_id) == 0) {

            $notice ['create_time'] = NOW_TIME;
            $notice ['order_id'] = $order_id;
            $notice ['user_id'] = $user_id;
            $notice ['payment_id'] = $payment_id;
            $notice ['memo'] = '';
            $notice ['money'] = $money;
            $notice ['coupons'] = '';
            $notice ['ecv_id'] = '';
            $notice ['order_type'] = 3;
            $notice ['create_date_ymd'] = to_date(NOW_TIME, "Y-m-d");
            $notice ['create_date_ym'] = to_date(NOW_TIME, "Y-m");
            $notice ['create_date_y'] = to_date(NOW_TIME, "Y");
            $notice ['create_date_m'] = to_date(NOW_TIME, "m");
            $notice ['create_date_d'] = to_date(NOW_TIME, "d");
            $notice ['notice_sn'] = to_date(NOW_TIME, "Ymdhis") . rand(10, 99).$user_id;
            $GLOBALS ['db']->autoExecute(DB_PREFIX . "payment_notice", $notice, 'INSERT', '', 'SILENT');
            $notice_id = intval($GLOBALS ['db']->insert_id());
        }

        $data['money'] = $money;
        $data['bank_code'] = $bank_code;
        $data['order_no'] = $order['order_sn'];
        $data['order_time'] = $order['create_time'];
        $data['order_userid'] = $user_id;
        $data['payment_id'] = $payment_id;

        ajax_return($data);

    }

}
?>