<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class skb_notifyModule extends MainBaseModule
{

    public function index()
    {
        global_run();

        /**参数列表 **/
        $merchant_code = $_REQUEST['merchant_code'];//商户号
        $interface_version= $_REQUEST['interface_version'];//接口版本
        $order_no= $_REQUEST['order_no'];//商家订单号
        $trade_no= $_REQUEST['trade_no'];//平台的支付订单号
        $order_amount= $_REQUEST['order_amount'];//商家订单金额
        $product_number= $_REQUEST['product_number'];//商家商品数量
        $order_success_time= $_REQUEST['order_success_time'];//订单支付成功时间
        $order_time= $_REQUEST['order_time'];//商家订单时间
        $order_status= $_REQUEST['order_status'];//订单支付状态
        $bank_code= $_REQUEST['bank_code'];//支付渠道编码
        $sign_type= $_REQUEST['sign_type'];//签名类型
        $bank_name= $_REQUEST['bank_name'];//消费者支付渠道名称
        $product_name= $_REQUEST['product_name'];//商品名称
        $order_userid= $_REQUEST['order_userid'];//商户平台支付会员账号
        $order_info= $_REQUEST['order_info'];//商户平台支付会员账号
        $sign= $_REQUEST['sign'];//MD5或RSA签名,根据您请求签名模型生成的
        /**参数列表end **/

//        $order_userid = 270;
//        $order_status = 'success';
//        $order_amount = 1000;
//        $order_success_time = "2017-10-09 12:13:53";
//        $order_no = '20171009020836710270';


        /**-------------签名--------------- **/
        $MARK = "~|~";
        //商户签名秘钥
        $key = "";
        //拼成原始签名串
        $initSign = $merchant_code.$MARK.$interface_version.$MARK.$order_no.$MARK.$trade_no.$MARK.$order_amount.$MARK
            .$product_number.$MARK.$order_success_time.$MARK.$order_time.$MARK.$order_status.$MARK.$bank_code;
        //验证签名串是否正确
        $valiSign = false;

        //根据您商户设置的签名模型自行选择对应的签名方式
        if($sign_type == 'MD5') {
            //MD5商户签名秘钥
            $key="w5iHGtXB1DC4vyvYa885vAmoInpx7pVg";
            //生成MD5签名串和sign进行比较
            $notitysign = md5($initSign.$MARK.$key);
            if($notitysign == $sign){
                $valiSign = true;//验证签名通过
            }
        }else if($sign_type == 'RSA'){//RSA签名模型
            $sign = base64_decode($_POST["sign"]);//RSA编码转换
            /** PHP语言平台公钥 key说明：
            1)这里是<平台RSA公钥>，每个商家对应一个固定的平台RSA公钥（不是使用工具生成的密钥merchant_public_key.txt，不要混淆），
            平台字符串公钥内容,请复制出来之后调成4行（换行位置任意，前面三行一定要<对齐>，第四行短一些）,如下demo平台公钥格式，
            前后并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----" <注释后面必须换行>
            2）使用平台的公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
             */
            $key='-----BEGIN PUBLIC KEY-----
            MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCyt4xRBmwlYPK4SeYqc/hslRwA
            9Q/tqMCsPm9xvXmPtmS4C8S5xCeliSPRIU34C27yNJczqRTDhsHdNChc1CHR+YpM
            wZTgYk5H8nt+YyfmSwExEez4aL8KD6quHPu4fH522Drzg856FQFZuwzyZWcfG/L4
            9/pboNX7kIQ2iWIofwIDAQAB
            -----END PUBLIC KEY-----';//平台公钥
            $key = openssl_get_publickey($key);
            $valiSign = openssl_verify($initSign,$sign,$key,OPENSSL_ALGO_MD5);
        }
        /**-------------签名end--------------- **/

//        if($valiSign){
        //------------订单支付成功，签名验证正确，开始处理你的平台逻辑,注意多次重复通知----------------

        if($order_status == 'success'){
            $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$order_userid);



            $order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where order_sn = ".$order_no);
            $order_id = $order['id'];
            if($order['pay_status'] == 2){
                echo 'OK';//处理完成，必须返回OK,页面除OK外不能有任何标签文字之类的
                die;
            }


            // var_dump($order_id);

            $GLOBALS ['db']->query ( "update ".DB_PREFIX."deal_order set pay_status = 2 , pay_amount = ".$order_amount.",create_date_ymd = '" . to_date ( NOW_TIME, "Y-m-d" ) . "',create_date_ym = '" . to_date ( NOW_TIME, "Y-m" ) . "',create_date_y = '" . to_date ( NOW_TIME, "Y" ) . "',create_date_m = '" . to_date ( NOW_TIME, "m" ) . "',create_date_d = '" . to_date ( NOW_TIME, "d" ) . "' where order_sn =" . $order_no . " and pay_status <> 2" );


            //双11活动开启时间
            $date11_start = strtotime('2017-11-10');
            $date11_end = strtotime('2017-11-20');
            $date11_register = strtotime('2017-11-10');
            $date = time();
            $order_success_time = strtotime($order_success_time);
            //修改付款订单列表
            $GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set pay_time = ".$order_success_time.",is_paid = 1 where order_id = ".$order_id);
            if($date>=$date11_end){
                //大转盘
            if($user_info['total_money']>=1000 && $order_amount>=1000){
                $lucky_draw = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."lucky_draw where user_id = ".$user_info['id']);
                if($lucky_draw == false){
                    $lucky['id'] = '';
                    $lucky['user_id'] = $user_info['id'];
                    $lucky['number'] = 1;
                    $lucky['create_time'] = $order_success_time;
                    $lucky['user_name'] = $user_info['user_name'];
                    $lucky['is_effect'] = 0;
                    $GLOBALS['db']->autoExecute(DB_PREFIX."lucky_draw", $lucky);
                }else{
                    $GLOBALS['db']->query("update ".DB_PREFIX."lucky_draw set number = number+1,create_time=".$order_success_time." where user_id = ".$user_info['id']);
                }
            }
            }

            //添加用户余额 添加充值单号验证码
            $GLOBALS['db']->query("update ".DB_PREFIX."user set money = money+".$order_amount." where id = ".$order_userid);

            $pid = $GLOBALS['db']->getOne("select pid from ".DB_PREFIX."user where id = ".$order_userid);
            $user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".$order_userid);


            //首冲时间设置
            if($user_info['first_pay_date']==''){
                $GLOBALS['db']->query("update ".DB_PREFIX."user set first_pay_date = ".$date." where id = ".$order_userid);
            }

            //添加订单日志
            $order_log['id'] = 0;
            $order_log['log_info'] = $order_no.'付款完成';
            $order_log['log_time'] = $order_success_time;
            $order_log['order_id'] = $order_id;
            $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_log", $order_log);

            $payment_notice['notice_sn'] = $GLOBALS['db']->getOne("select notice_sn from ".DB_PREFIX."payment_notice where order_id = ".$order_id);


            //添加用户日志
            $user_log['id'] = 0;
            $user_log['log_info'] = '充值：订单号'.$order_no.'付款单号：'.$payment_notice['notice_sn'];
            $user_log['log_time'] = $order_success_time;
            $user_log['log_user_id'] = $order_userid;
            $user_log['money'] = $order_amount;
            $user_log['user_id'] = $order_userid;
            $user_log['payment'] = $bank_name;
            $GLOBALS['db']->autoExecute(DB_PREFIX."user_log", $user_log);

            $today = date('d',$date);

            //最后充值时间
            $last_recharge_date = date('d',$user_info['last_recharge_date']);
            //单日累计充值金额
            $day_recharge_money = $user_info['day_recharge_money']+$order_amount;
            if($today == $last_recharge_date){
                $GLOBALS['db']->query("update ".DB_PREFIX."user set day_recharge_money = ".$day_recharge_money." where id =".$order_userid);
            }else{
                $GLOBALS['db']->query("update ".DB_PREFIX."user set day_recharge_money = ".$order_amount." where id =".$order_userid);
            }
            //记录最后充值时间
            $GLOBALS['db']->query("update ".DB_PREFIX."user set last_recharge_date=".$date." where id =".$order_userid);
            $total_money = $user_info['total_money'];




            //充值成功，开始给邀请人添加推广奖
            if($order_amount >= 100){
                $this->fx_money($pid,$order_amount,$order_success_time,$user_name);
            }
            if($total_money==0 && $order_amount>=1000){
                $first_give_money = $order_amount*0.2;
                $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money = ".$first_give_money." where id = ".$order_userid);
                //添加赠送金额日志
                $give_log['log_info'] = '充值：订单号'.$order_no.'获得赠送金额'.$first_give_money."7日后可提现";
                $give_log['log_time'] = $order_success_time;
                $give_log['log_user_id'] = $order_userid;
                $give_log['money'] = $first_give_money;
                $give_log['user_id'] = $order_userid;
                $GLOBALS['db']->autoExecute(DB_PREFIX."user_log", $give_log);

                //添加赠送金额提现表
                $give_date['create_time'] = $date+60*60*24*7;
                $give_date['user_id'] = $user_info['id'];
                $give_date['is_delete'] = 0;
                $give_date['money'] = $first_give_money;
                $GLOBALS['db']->autoExecute(DB_PREFIX."give_money", $give_date);
            }

            if($total_money>0 && $order_amount>=100){
                //不是首冲，满100以上送金额
                $level = $user_info['level_id'];
                if($level>=0 && $level<=4){
                    $give_money = $order_amount*0.06;
                }elseif($level>=5 && $level<=7){
                    $give_money = $order_amount*0.07;
                }elseif($level>=8){
                    $give_money = $order_amount*0.08;
                }

                if($date>=$date11_start && $date<=$date11_end){
                    if($total_money==1000 && $user_info['first_pay_date']<=$date11_register && $order_amount==1000){
                        $give_money = 100;
                        $GLOBALS['db']->query("update ".DB_PREFIX."user set give11 = 100 where id = ".$order_userid);
                    }
                }

                $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money = give_money + ".$give_money." where id = ".$order_userid);

                //添加赠送金额日志
                $give_log['log_info'] = '充值：订单号'.$order_no.'获得赠送金额'.$give_money;
                $give_log['log_time'] = $order_success_time;
                $give_log['log_user_id'] = $order_userid;
                $give_log['money'] = $give_money;
                $give_log['user_id'] = $order_userid;
                $GLOBALS['db']->autoExecute(DB_PREFIX."user_log", $give_log);


                //添加赠送金额提现表
                $give_date['create_time'] = $date+60*60*24*7;
                $give_date['user_id'] = $user_info['id'];
                $give_date['is_delete'] = 0;
                $give_date['money'] = $give_money;
                $GLOBALS['db']->autoExecute(DB_PREFIX."give_money", $give_date);
            }

            $GLOBALS['db']->query("update ".DB_PREFIX."user set total_money = total_money+".$order_amount." where id =".$order_userid);

            echo 'OK';//处理完成，必须返回OK,页面除OK外不能有任何标签文字之类的
//            }


        }else{
            echo 'FAIL';//签名验证失败了，请检查签名或参数信息的正确性
        }

    }

    public function date11_fx_user_count($pid){
        $date11_start = strtotime('2017-11-10');
        $date11_end = strtotime('2017-11-20');
        $user_info = $GLOBALS['db']->getAll("select count(id) num from ".DB_PREFIX."user where is_delete=0 and is_effect=1 and create_time>".$date11_start." and create_time<".$date11_end." and pid = ".$pid);
        return $user_info[0]['num'];
    }
    public function date11_ago_fx_user_count($pid){
        $date11_register = strtotime('2017-11-9');
        $user_info = $GLOBALS['db']->getAll("select count(id) num from ".DB_PREFIX."user where is_delete=0 and is_effect=1 and create_time<".$date11_register." and pid = ".$pid);
        return $user_info[0]['num'];
    }

    public function fx_money($pid,$money,$order_success_time,$user_name,$fx_lv=1){
        if($fx_lv>4){
            $fx_lv = 4;
        }
        $date = time();

        $user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$pid);
        //双十一活动时间
        $date11_start = strtotime('2017-11-10');
        $date11_end = strtotime('2017-11-20');
        $date11_register = strtotime('2017-11-10');

        if($user['fx_level'] >= $fx_lv){
            if($fx_lv == 4 && $user['fx_level'] == 4){
                $fx_salary = 0.001;
                $admin_money_befor = $GLOBALS['db']->getOne("select admin_money from ".DB_PREFIX."user  where id =".$pid);
                $admin_money_now = $fx_salary*$money;
                $admin_money = $admin_money_befor+$admin_money_now;
                $GLOBALS['db']->query("update ".DB_PREFIX."user set admin_money=".$admin_money." where id = ".$pid);
                //添加管理奖提现表
                $admin_date['create_time'] = $date+60*60*24*7;
                $admin_date['user_id'] = $pid;
                $admin_date['is_delete'] = 0;
                $admin_date['money'] = $admin_money_now;
                $admin_date['method'] = 'admin_money';
                $GLOBALS['db']->autoExecute(DB_PREFIX."fx_money", $admin_date);
            }else{
                $fx_salary = $GLOBALS['db']->getOne("select fx_salary from ".DB_PREFIX."fx_salary  where fx_level =".$fx_lv);

                if($date>=$date11_start && $date<=$date11_end && $fx_lv==1){
                    $user_create_time = intval($user['first_pay_date']);
                    if($user_create_time<=$date11_register){
                        $date11_ago_fx_user_count = $this->date11_ago_fx_user_count($user['id']);
                        $date11_ago_fx_user_count = intval($date11_ago_fx_user_count);
                        if($date11_ago_fx_user_count<=2){
                            $date11_fx_user_count = $this->date11_fx_user_count($user['id']);
                            $fx_salary = (intval($date11_fx_user_count)+4)/100;
                            if($fx_salary>=0.09){
                                $fx_salary = 0.09;
                            }
                        }
                    }
                }

                $fx_money_befor = $GLOBALS['db']->getOne("select fx_money from ".DB_PREFIX."user  where id =".$pid);
                $fx_money_now = $fx_salary*$money;
                $fx_money = $fx_money_befor+$fx_money_now;
                $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_money=".$fx_money.",fx_total_balance=fx_total_balance+".$fx_money_now." where id = ".$pid);
                //添加推广奖体现表
                $fx_date['create_time'] = $date+60*60*24*7;
                $fx_date['user_id'] = $pid;
                $fx_date['is_delete'] = 0;
                $fx_date['money'] = $fx_money_now;
                $fx_date['method'] = 'fx_money';
                $GLOBALS['db']->autoExecute(DB_PREFIX."fx_money", $fx_date);
            }

            //添加用户日志
            $user_log['id'] = 0;
            if($fx_lv == 4){
                $user_log['log_info'] = '线下'.$fx_lv.'级会员'.$user_name.'充值'.$money.'夺宝币获得管理奖'.$fx_money_now.'夺宝币';
            }else{
                $user_log['log_info'] = '线下'.$fx_lv.'级会员'.$user_name.'充值'.$money.'夺宝币获得推广奖'.$fx_money_now.'夺宝币';
            }
            $user_log['log_time'] = $order_success_time;
            $user_log['log_user_id'] = $pid;
            $user_log['money'] = $fx_money_now;
            $user_log['user_id'] = $pid;
            $GLOBALS['db']->autoExecute(DB_PREFIX."user_log", $user_log);
        }

        $fx_lv++;
        if($user['pid']){
            $this->fx_money($user['pid'],$money,$order_success_time,$user_name,$fx_lv);
        }
    }

}