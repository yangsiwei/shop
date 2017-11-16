<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

$payment_lang = array(
    'name'	=>	'余额支付',
    'account_credit'	=>	'帐户余额',
    'use_user_money' =>	'使用余额支付',
    'use_all_money'	=>	'全额支付',
    'USER_ORDER_PAID'	=>	'%s订单付款,付款单号%s'
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Account';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '0';

    /* 配送 */
    $module['config'] = $config;

    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

// 余额支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Account_payment implements payment {
    public function get_payment_code($payment_notice_id)
    {
        global_run();
        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
        $money          = $payment_notice['money'];

        $order_sn       = $GLOBALS['db']->getOne("select order_sn from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);

        //双十一活动时间
        $date11_start = strtotime('2017-11-10');
        $date11_end = strtotime('2017-11-20');
        $date11_register = strtotime('2017-11-10');
        $date = time();
        $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$payment_notice['user_id']);


        if($payment_notice&&$money>0)
        {
            if($date>=$date11_start && $date<=$date11_end){
                if($user_info['first_pay_date']<=$date11_register){
                    if($user_info['give11']>=100){
                        if($money<=10){
                            $give_money = $money*10;
                        }else{
                            $give_money = 100;
                        }
                        $give11 = $user_info['give11']+$give_money;
                        if($give11>200){
                            $give_money = 200-$user_info['give11'];
                        }
                        $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money = give_money + ".$give_money." where id = ".$payment_notice['user_id']);
                        $GLOBALS['db']->query("update ".DB_PREFIX."user set give11 = give11 + ".$give_money." where id = ".$payment_notice['user_id']);

                        //添加用户日志
                        $user_log['id'] = 0;
                        $user_log['log_info'] = '消费'.$money."赠送".$give_money;
                        $user_log['log_time'] = $date;
                        $user_log['log_user_id'] = $payment_notice['user_id'];
                        $user_log['money'] = $give_money;
                        $user_log['user_id'] = $payment_notice['user_id'];
                        $GLOBALS['db']->autoExecute(DB_PREFIX."user_log", $user_log);

                        $give_date['create_time'] = $date+60*60*24*7;
                        $give_date['user_id'] = $payment_notice['user_id'];
                        $give_date['is_delete'] = 0;
                        $give_date['money'] = $give_money;
                        $GLOBALS['db']->autoExecute(DB_PREFIX."give_money", $give_date);

                    }
                }
            }
            //判断管理奖是否充足
            if($money<=$GLOBALS['user_info']['admin_money']){
                $GLOBALS['db']->query("update ".DB_PREFIX."user set admin_money = admin_money - ".$money." where admin_money - ".$money." >=0 and id = ".$payment_notice['user_id']);
            }else{
                //判断充值赠送金额是否充足
                if($money<=$GLOBALS['user_info']['give_money']){
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set can_use_give_money = can_use_give_money - ".$money." where id = ".$payment_notice['user_id']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money = give_money - ".$money." where give_money - ".$money." >=0 and id = ".$payment_notice['user_id']);
                }else{
                    //判断推广奖是否充足
                    if($money<=$GLOBALS['user_info']['fx_money']){
                        $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_money = fx_money - ".$money." where fx_money - ".$money." >=0 and id = ".$payment_notice['user_id']);
                    }else{
                        //判端余额是否充足
                        if($money<=$GLOBALS['user_info']['money']){
                            $GLOBALS['db']->query("update ".DB_PREFIX."user set money = money - ".$money." where money - ".$money." >=0 and id = ".$payment_notice['user_id']);
                        }else{
                            $data['status'] = 0;
                            $data['info'] = '用户余额不足，支付失败';
                        }
                    }
                }
            }

            $rs = payment_paid($payment_notice_id);
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
                    $log_info['money'] = '-'.floatval($money);
                    $log_info['score'] = 0;
                    $log_info['point'] = 0;
                    $log_info['user_id'] = $user_id;
                    $GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);

                    load_user($user_id,true);
                }
            }
            // }


        }


    }

    /**
     * 直接处理付款单
     * @param unknown_type $payment_notice
     */
    public function response($payment_notice)
    {
        return false;
    }

    public function notify($request)
    {
        return false;
    }

    public function get_display_code()
    {
        $user_id = intval($GLOBALS['user_info']['id']);
        $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id." and is_effect = 1 and is_delete = 0");
        if($user_info&&$user_info['money']+$user_info['can_use_give_money']+$user_info['fx_money']+$user_info['admin_money']>0)
        {
            $is_default=$GLOBALS['db']->getOne("select is_default_pc from ".DB_PREFIX."payment where is_effect = 1 and class_name = 'Account'");
            if($is_default=='1'){
                $html = "<p>帐户余额+可用赠送金额+推广奖+管理奖：<b>".format_price($user_info['money']+$user_info['can_use_give_money']+$user_info['fx_money']+$user_info['admin_money'])."</b>，使用余额支付".
                    " <input type='text' style='width: 50px; margin-bottom:-5px;' value='' name='account_money' class='ui-textbox' id='account_money'>，".
                    "<label class='ui-checkbox' rel='common_cbo'><input type='checkbox' checked='checked' id='check-all-money' name='all_account_money'>全额支付</label></p>";
            }else{
                $html = "<p>帐户余额+可用赠送金额+推广奖+管理奖：<b>".format_price($user_info['money']+$user_info['can_use_give_money']+$user_info['fx_money']+$user_info['admin_money'])."</b>，使用余额支付".
                    " <input type='text' style='width: 50px; margin-bottom:-5px;' value='' name='account_money' class='ui-textbox' id='account_money'>，".
                    "<label class='ui-checkbox' rel='common_cbo'><input type='checkbox'  id='check-all-money' name='all_account_money'>全额支付</label></p>";
            }
            return $html;
        }
        else
        {
            return '';
        }
    }
}
?>