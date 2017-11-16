<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class uc_chargeApiModule extends MainBaseApiModule
{

    /**
     * 	 会员中心充值页面接口
     *
     * 	  输入：
     *
     *
     *  输出：
     * user_login_status:int   0表示未登录   1表示已登录  2表示临时登录
     * login_info:string 未登录状态的提示信息，已登录时无此项
     * page_title:string 页面标题

     */
    public function index()
    {
        $root = array();

        $user_data = $GLOBALS['user_info'];
        $user_id = intval($user_data['id']);


        $user_login_status = check_login();
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = 1;
            //输出支付方式

            //输出支付方式
            global $is_app;
            if (!$is_app)
            {
                //支付列表
                $sql = "select id, class_name as code, logo from ".DB_PREFIX."payment where (online_pay = 2 or online_pay = 4 or online_pay = 6 or online_pay = 7) and is_effect = 1";

            }
            else
            {
                //支付列表
                $sql = "select id, class_name as code, logo from ".DB_PREFIX."payment where (online_pay = 3 or online_pay = 4 or online_pay = 6) and is_effect = 1";
            }
            if(allow_show_api())
            {
                $payment_list = $GLOBALS['db']->getAll($sql);
            }
            //输出支付方式
            foreach($payment_list as $k=>$v)
            {
                $directory = APP_ROOT_PATH."system/payment/";
                $file = $directory. '/' .$v['code']."_payment.php";
                if(file_exists($file))
                {
                    require_once($file);
                    $payment_class = $v['code']."_payment";
                    $payment_object = new $payment_class();
                    $payment_list[$k]['name'] = $payment_object->get_display_code();
                }

                if($v['logo']!="")
                    $payment_list[$k]['logo'] = get_abs_img_root(get_spec_image($v['logo'],40,40,1));
            }

            sort($payment_list);

            $root['payment_list']=$payment_list?$payment_list:array();

            $root['page_title'].="会员充值";
        }
        return output($root);

    }




    /**
     * 	 会员中心充值操作接口
     *
     * 	  输入：
     *  payment_id:int 支付方式id
     *  money: float  支付金额
     *
     *  输出：
     * user_login_status:int   0表示未登录   1表示已登录  2表示临时登录
     * login_info:string 未登录状态的提示信息，已登录时无此项
     * page_title:string 页面标题

     */
    public function done()
    {
        $root = array();

        $user_data = $GLOBALS['user_info'];
        $user_id = intval($user_data['id']);
        $payment_id = intval($GLOBALS['request']['payment_id']);
        $money = floatval($GLOBALS['request']['money']);

        $rest_recharge = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."rest_recharge where id = 1");
        //限制充值金额
        if($money%10 != 0){
            return output("",0,"充值金额限定为10的倍数");
        }
        if($money<$rest_recharge['lowest_recharge'] || $money>$rest_recharge['highest_recharge'])
        {
            return output("",0,"充值金额的限定范围是{$rest_recharge['lowest_recharge']}~{$rest_recharge['highest_recharge']}");
        }

        //单日累计充值金额
        $today = date('d');
        $last_recharge_date = date('d',$GLOBALS['user_info']['last_recharge_date']);
        $day_recharge_money = $GLOBALS['user_info']['day_recharge_money']+$money;
        //控制单日累计充值金额
        if($rest_recharge['day_recharge_money'] != 0){
            if($rest_recharge['day_recharge_money']<$day_recharge_money){
                return output("",0,"累计充值金额超过{$rest_recharge['day_recharge_money']}");
            }
        }

        $user_login_status = check_login();
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = 1;

            if($money<=0)
            {
                return output("",0,"请输入正确的金额");
            }

            $payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$payment_id);
            if(!$payment_info)
            {
                return output("",0,"支付方式不存在");
            }





            //开始生成订单
            $now = NOW_TIME;
            $order['type'] = 1; //充值单
            $order['user_id'] = $GLOBALS['user_info']['id'];
            $order['create_time'] = $now;
            $order['update_time'] = $now;
            $order['total_price'] = $money;
            $order['deal_total_price'] = $money;
            $order['pay_amount'] = 0;
            $order['pay_status'] = 0;
            $order['delivery_status'] = 5;
            $order['order_status'] = 0;
            $order['payment_id'] = $payment_id;

            do
            {
                $order['order_sn'] = to_date(get_gmtime(),"Ymdhis").rand(100,999);
                $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order,'INSERT','','SILENT');
                $order_id = intval($GLOBALS['db']->insert_id());
            }while($order_id==0);

            require_once APP_ROOT_PATH."system/model/cart.php";
            $payment_notice_id = make_payment_notice($order['total_price'],'',$order_id,$payment_info['id']);
            //创建支付接口的付款单

            $rs = order_paid($order_id);
            if($rs)
            {

                //添加累计充值金额
                $total_money_befor = $GLOBALS['user_info']['total_money'];
                $total_money = $total_money_befor+$money;
                $GLOBALS['db']->query("update ".DB_PREFIX."user set tatal_money=".$total_money."where id =".$GLOBALS['user_info']['id']);

                // //查询充值限制数据
                // $rest = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."rest where id = 1");
                //充值成功，开始给邀请人添加推广奖
                $this->fx_money($GLOBALS['user_info']['id'],$money);
                //给符合领取管理奖条件的人添加管理奖
                $this->admin_money($GLOBALS['user_info']['id'],$money,$rest);

                $date = time();

                if($today == $last_recharge_date){
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set day_recharge_money = ".$day_recharge_money." where id =".$GLOBALS['user_info']['id']);
                }else{
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set day_recharge_money = ".$money." where id =".$GLOBALS['user_info']['id']);
                }

                //记录最后充值时间
                $GLOBALS['db']->query("update ".DB_PREFIX."user set last_recharge_date=".$date." where id =".$GLOBALS['user_info']['id']);



                //首冲功能
                if($total_money<=0 && $money<100){
                    //第一次充值金额不满100夺宝币，

                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay=1 where id = ".$GLOBALS['user_info']['id']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay_date=".$date." where id =".$GLOBALS['user_info']['id']);



                }elseif($total_money<=0 && 1000>=$money && $money>=100){
                    //判断是否是首冲，充值金额在100到1000之间，赠送20夺宝币
                    echo 333;
                    $give_money = $rest['hundred'];
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money=".$give_money." where id = ".$GLOBALS['user_info']['id']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay=1 where id = ".$GLOBALS['user_info']['id']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay_date=".$date." where id =".$GLOBALS['user_info']['id']);
                }elseif($total_money<=0 && $money>=1000){
                    //判断是否是首冲，且充值金额大于1000，赠送200夺宝币
                    $give_money = $rest['thousand'];
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money=".$give_money." where id = ".$GLOBALS['user_info']['id']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay=1 where id = ".$GLOBALS['user_info']['id']);
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay_date=".$date." where id =".$GLOBALS['user_info']['id']);
                }elseif($total_money>0 && $money>=100){
                    //不是首冲，满100以上送金额
                    $give_money_befor = $GLOBALS['user_info']['give_money'];
                    $give_money_now = $money*$rest['usual_day'];
                    $give_money = $give_money_befor+$give_money_now;
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money=".$give_money." where id = ".$GLOBALS['user_info']['id']);
                }

                $root['pay_status'] = 1;
                $root['order_id'] = $order_id;
                $root['out_order_no'] = $order['order_sn'];
            }
            else
            {
                if($payment_info['online_pay']==3) //sdk在线支付
                {
                    require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
                    $payment_class = $payment_info['class_name']."_payment";
                    $payment_object = new $payment_class();
                    $payment_code = $payment_object->get_payment_code($payment_notice_id);
                    $root['pay_status'] = 0;
                    $root['order_id'] = $order_id;
                    $root['sdk_code'] = $payment_code['sdk_code'];
                    return output($root,2); //sdk支付
                }
                else
                {

                    require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
                    $payment_class = $payment_info['class_name']."_payment";
                    $payment_object = new $payment_class();
                    $payment_code = $payment_object->get_payment_code($payment_notice_id);

                    $root['pay_status'] = 0;
                    $root['payment_code'] = $payment_code;

                    $root['page_title'].="充值中……";
                    $root['order_id'] = $order_id;
                    $root['out_order_no'] = $order['order_sn'];
                }
            }




        }
        $root ['is_app'] = intval ( $GLOBALS ['is_app'] );
        return output($root);

    }


    public function fx_money($pid,$money,$fx_lv=1){

        $user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$pid);
        $fx_salary = $GLOBALS['db']->getRow("select fx_salary from ".DB_PREFIX."fx_salary  where fx_level =".$fx_lv)['fx_salary'];
        // $fx_level = $GLOBALS['db']->getOne("select fx_level from ".DB_PREFIX."user where id =".$pid);

        // if($fx_lv>3 && $fx_level=4){
        // 	$fx_salary = 0.01;
        // }
        $fx_money_befor = $GLOBALS['db']->getOne("select fx_money from ".DB_PREFIX."user where id =".$pid);
        $fx_money_now = $fx_salary*$money;
        $fx_money = $fx_money_befor+$fx_money_now;
        $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_money=".$fx_money." where id = ".$pid);
        $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_total_balance=fx_total_balance+".$fx_money_now." where id = ".$pid);
        $fx_lv++;
        if($user['fx_level'] == $fx_lv){
            $this->fx_money($user['pid'],$money,$fx_lv);
        }
    }

    public function admin_money($pid,$money,$rest,$fx_lv=1){
        $user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$pid);
        $fx_salary = $rest['usual_day'];
        if($user['fx_level']=4){
            $admin_money_befor = $GLOBALS['db']->getOne("select admin_money from ".DB_PREFIX."user where id =".$pid);
            $admin_money_now = $fx_salary*$money;
            $admin_money = $admin_money_befor+$fx_money_now;
            $GLOBALS['db']->query("update ".DB_PREFIX."user set admin_money=".$admin_money." where id = ".$pid);
        }
        $fx_lv++;
        if($user){
            $this->fx_money($user['pid'],$money,$fx_lv);
        }
    }




}
?>