<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class uc_money_cashModule extends MainBaseModule
{
    /**
     * 资金记录
     */
    public function index(){
        global_run();
        init_app_page();
        $param['page'] = intval($_REQUEST['page']);
        $data = call_api_core("uc_money_cash","index",$param);
//				print_r($data);die();
        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        if(isset($data['page']) && is_array($data['page'])){
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();
            $GLOBALS['tmpl']->assign('pages',$p);
        }
        if(!$data['give_money']){
            $data['give_money'] = '0';
        }
        if(!$data['money']){
            $data['money'] = '0';
        }
        if(!$data['fx_money']){
            $data['fx_money'] = '0';
        }
        if(!$data['admin_money']){
            $data['admin_money'] = '0';
        }
        $date = time();
        $first_pay_date = $GLOBALS['user_info']['first_pay_date'];
        $seven_day_ago = time()-60*60*24*7;
        if($first_pay_date<=$seven_day_ago && $GLOBALS['user_info']['total_money']==1000  && $GLOBALS['user_info']['money']==$GLOBALS['user_info']['total_money']){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set can_use_give_money = ".$GLOBALS['user_info']['give_money']." where id = ".$GLOBALS['user_info']['id']);
        }


        $GLOBALS['tmpl']->assign("data",$data);

       $this->withdraw_bank_list();
    }
    public function withdraw_bank_list(){
        global_run();
        init_app_page();
        $param=array();

        $data = call_api_core("uc_money_cash","withdraw_bank_list",$param);
        $rest = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."rest_withdraw where id = 1");
//    print_r($rest);die();
        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        $GLOBALS['tmpl']->assign("rest",$rest);
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("uc_money_withdraw.html");
    }

    public function add_card(){
        global_run();
        init_app_page();
        $param=array();

        $data = call_api_core("uc_money_cash","add_card",$param);
        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        $data['step']=2;
        $data['page_title']="添加提现账户";
        $GLOBALS['tmpl']->assign("sms_lesstime",load_sms_lesstime());
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("uc_money_withdraw.html");
    }

    public function do_bind_bank(){
        global_run();
        $param=array();
        $param['bank_name'] = strim($_REQUEST['bank_name']);
        $param['bank_account']= strim($_REQUEST['bank_account']);
        $param['bank_user'] = strim($_REQUEST['bank_user']);
        $param['sms_verify'] = strim($_REQUEST['sms_verify']);
        $bank_user = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bank where user_id = ".$GLOBALS['user_info']['id']);
        foreach($bank_user as $v){
            if($param['bank_user'] != $v['bank_user']){
                $data['status'] = 0;
                $data['info'] = '开户行真实姓名不一致';
                ajax_return($data);
                die;
            }
        }
        $data = call_api_core("uc_money_cash","do_bind_bank",$param);

        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        if($data['status']==1){
            $result['status'] = 1;
            $result['url'] = wap_url("index","uc_money_cash#withdraw_bank_list");
            ajax_return($result);
        }else{
            $result['status'] =0;
            $result['info'] =$data['info'];
            ajax_return($result);
        }
    }
    public function method_ajax(){
        global_run();
        $method = $_REQUEST['method'];
        $now = date('Y-m-d',time());
        // $money = $GLOBALS['user_info'][$method];
        //查询提现限制
        $rest_withdraw = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."rest_withdraw where id = 1");
//        print_r($rest_withdraw);die();

        if($method == 'money'){
            //查询今日是否有提现
            $withdraw = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."withdraw where user_id = ".$GLOBALS['user_info']['id']."and withdraw_method = money");
                //判断余额与提现限制之间的关系
                if($GLOBALS['user_info']['money']<$rest_withdraw['money_low']){
                    $money = '0';
                    $money_no = $GLOBALS['user_info']['money'];
                }elseif($GLOBALS['user_info']['money']>$rest_withdraw['money_high']){
                    $money = $rest_withdraw['money_high'];
                    $money_no = $GLOBALS['user_info']['money']-$rest_withdraw['money_high'];
                }else{
                    $money = $GLOBALS['user_info']['money'];
                    $money_no = '0';
                }
                //如果今日有提现余额，则今天不能再提现
                foreach($withdraw as $v){
                    $date = date('Y-m-d',$v['create_time']);
                    if($now == $date){
                        $money = '0';
                        $money_no = $GLOBALS['user_info']['money'];
                 }
           }
     }

    if($method == 'give_money'){
        //查询今日是否有提现
        $dongjie = $GLOBALS['db']->getAll("select sum(money) money from ".DB_PREFIX."give_money where user_id = ".$GLOBALS['user_info']['id']." and is_delete = 0");
        $dj_money = $dongjie[0]['money'];
        $withdraw = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."withdraw where user_id = ".$GLOBALS['user_info']['id']."and withdraw_method = give_money");
            //判断余额与提现限制之间的关系
            if($GLOBALS['user_info']['can_use_give_money']<$rest_withdraw['give_money_low']){
                $money = '0';
                $money_no = $GLOBALS['user_info']['can_use_give_money']+$dj_money;
            }elseif($GLOBALS['user_info']['can_use_give_money']>$rest_withdraw['give_money_high']){
                $money = $rest_withdraw['give_money_high'];
                $money_no = $GLOBALS['user_info']['can_use_give_money']+$dj_money-$rest_withdraw['give_money_high'];
            }else{
                $money = $GLOBALS['user_info']['can_use_give_money']-$GLOBALS['user_info']['can_use_give_money']%50;
                $money_no = $dj_money;
            }
            //如果今日有提现赠送金额，则今天不能再提现
            foreach($withdraw as $v){
                $date = date('Y-m-d',$v['create_time']);
                if($now == $date){
                    $money = '0';
                    $money_no = $GLOBALS['user_info']['can_use_give_money'];
                }
            }
        }

    if($method == 'fx_money'){
        //查询今日是否有提现推广奖
        $dongjie = $GLOBALS['db']->getAll("select sum(money) money from ".DB_PREFIX."fx_money where user_id = ".$GLOBALS['user_info']['id']." and is_delete = 0");
        $dj_money = $dongjie[0]['money'];
        $withdraw = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."withdraw where user_id = ".$GLOBALS['user_info']['id']."and withdraw_method = fx_money");
            //判断余额与提现限制之间的关系
            if($GLOBALS['user_info']['fx_money']<$rest_withdraw['fx_money_low']){
                $money = '0';
                $money_no = $GLOBALS['user_info']['fx_money']+$dj_money;
            }elseif($GLOBALS['user_info']['fx_money']>$rest_withdraw['fx_money_high']){
                $money = $rest_withdraw['fx_money_high'];
                $money_no = $GLOBALS['user_info']['fx_money']+$dj_money-$rest_withdraw['fx_money_high'];
            }else{
                $money = $GLOBALS['user_info']['fx_money']-$GLOBALS['user_info']['fx_money']%50;
                $money_no = $dj_money;
            }
            //如果今日有提现推广奖，则今天不能再提现
            $now_week = date('Y-m-w',time());
            foreach($withdraw as $v){
                $date_week = date('Y-m-w',$v['create_time']);
                if($now_week == $date_week){
                    $money = '0';
                    $money_no = $GLOBALS['user_info']['fx_money'];
                }
            }
        }


    if($method == 'admin_money'){
        //查询今日是否有提现推广奖
        $withdraw = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."withdraw where user_id = ".$GLOBALS['user_info']['id']."and withdraw_method = admin_money");
            //判断余额与提现限制之间的关系
            if($GLOBALS['user_info']['admin_money']<$rest_withdraw['admin_money_low']){
                $money = '0';
                $money_no = $GLOBALS['user_info']['admin_money'];
            }elseif($GLOBALS['user_info']['admin_money']>$rest_withdraw['admin_money_high']){
                $money = $rest_withdraw['admin_money_high'];
                $money_no = $GLOBALS['user_info']['admin_money']-$rest_withdraw['admin_money_high'];
            }else{
                $money = $GLOBALS['user_info']['admin_money'];
                $money_no = '0';
            }
            //如果今日有提现管理奖，则今天不能再提现
            $now_week = date('Y-m',time());
            foreach($withdraw as $v){
                $date_week = date('Y-m',$v['create_time']);
                if($now_week == $date_week){
                    $money = '0';
                    $money_no = $GLOBALS['user_info']['admin_money'];
                }
            }
        }



    $data['money'] = $money;
    $data['money_no'] = $money_no;
    $data['status'] = 1;
    ajax_return($data);
}


    public function do_withdraw(){
        global_run();
        //双十一活动时间
        $date11_start = strtotime('2017-11-10');
        $date11_end = strtotime('2017-11-13');
        $date11_register = strtotime('2017-11-9');
        $date = time();
        $date_d = date("N",time());
        $date_h = date("H",time());
        if($date_d<1 || $date_d >5){
            $data['status'] = 0;
            $data['info'] = '提现时间为周一至周五';
            ajax_return($data);
        }
        if($date_h<9 || $date_h >17){
            $data['status'] = 0;
            $data['info'] = '提现时间为9:00~17:00';
            ajax_return($data);
        }
        if($date>=$date11_register && $date<=$date11_end){
            $data['status'] = 0;
            $data['info'] = '亲，11月9日~11月12日双十一活动期间暂时停止提现';
            ajax_return($data);
        }


        $param=array();
        $param['user_bank_id'] = intval($_REQUEST['bank_id']);
        $param['money']= floatval($_REQUEST['money']);
        $param['check_pwd'] = strim($_REQUEST['pwd']);
        $param['withdraw_method'] = $_REQUEST['withdraw_method'];
        if($param['money']%50 != 0){
            $data['status'] = 0;
            $data['info'] = '请输入50的倍数';
            ajax_return($data);
        }
        if(!$param['withdraw_method']){
            $data['status'] = 0;
            $data['info'] = '请选择提现款项';
            ajax_return($data);
        }
        //查询用户提现信息
        $withdraw_money = $GLOBALS['db']->getOne("select money from ".DB_PREFIX."withdraw where user_id = ".$GLOBALS['user_info']['id']." and is_paid != 1 and withdraw_method = ".$param['withdraw_method']);
        if($withdraw_money+$param['money']>$GLOBALS['user_info'][$param['withdraw_method']]){
            $data['status'] = 0;
            $data['info'] = '提现超额';
            ajax_return($data);
        }
        $date = time();
        //提现金额限制
        $rest_withdraw = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."rest_withdraw where id = 1");

        switch ($param['withdraw_method']) {
            case 'money':
                $this->money($rest_withdraw ,$param['money']);
                $first_pay_date = $GLOBALS['user_info']['first_pay_date'];
                if($first_pay_date<=0||$first_pay_date<=''||$first_pay_date<=null){
                    $first_pay_date = $GLOBALS['user_info']['last_recharge_date'];
                }
                $one_day = 60*60*24*7;
                $submitted_date_one = $first_pay_date+$one_day;
                if($date<$submitted_date_one){
                    $data['status'] = 0;
                    $data['info'] = "首次充值时间未超过7天，不能提现";
                    ajax_return($data);
                }
                break;
            case 'give_money':
                $this->give_money($rest_withdraw,$param['money']);
                break;
            case 'fx_money':
                $this->fx_money($rest_withdraw,$param['money']);
                break;
            default:
                $this->admin_money($rest_withdraw,$param['money']);
                break;
        }
        //提现时间限制
        $first_pay_date = $GLOBALS['user_info']['first_pay_date'];
        $ten_day = 60*60*24*7;
        $submitted_date_one = $first_pay_date+$ten_day;
        if($date<$submitted_date_one){
            $data['status'] = 0;
            $data['info'] = "首次充值时间未超过1周，不能提现";
            ajax_return($data);
        }

        $data = call_api_core("uc_money_cash","do_withdraw",$param);
        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }

        if($data['status']==1){
            if($param['withdraw_method'] != 'money'){
                $with_befor = $GLOBALS['user_info'][$param['withdraw_method']];
                $with_monty = $with_befor-$param['money'];
                $GLOBALS['db']->query("update ".DB_PREFIX."user set ".$param['withdraw_method']." = ".$with_monty." where id = ".$GLOBALS['user_info']['id']);
                if($param['withdraw_method'] == 'give_money'){
                    $with_give = $GLOBALS['user_info']['can_use_give_money']-$param['money'];
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set can_use_give_money = ".$with_give." where id = ".$GLOBALS['user_info']['id']);
                }
            }

            $result['status'] = 1;
            $result['url'] = wap_url("index","uc_money_cash#withdraw_log");
            ajax_return($result);
        }else{
            $result['status'] =0;
            $result['info'] =$data['info'];
            ajax_return($result);
        }
    }
    public function withdraw_log(){
        global_run();
        init_app_page();
        $param=array();
        $param['page'] = intval($_REQUEST['page']);
        $data = call_api_core("uc_money_cash","withdraw_log",$param);
        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        if(isset($data['page']) && is_array($data['page'])){
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();

            $GLOBALS['tmpl']->assign('pages',$p);
        }
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("uc_withdraw_log.html");
    }
    public function del_withdraw(){
        global_run();
        if(check_save_login()!=LOGIN_STATUS_LOGINED)
        {
            $data['status'] = 1000;
            ajax_return($data);
        }
        else
        {
            $id = intval($_REQUEST['id']);
            $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."withdraw where id = ".$id." and is_delete = 0 and user_id = ".$GLOBALS['user_info']['id']);
            if($order_info)
            {
                $GLOBALS['db']->query("update ".DB_PREFIX."withdraw set is_delete = 1 where is_delete = 0 and user_id = ".$GLOBALS['user_info']['id']." and id = ".$id);
                if($GLOBALS['db']->affected_rows())
                {
                    if($order_info['withdraw_method'] != 'money'){
                        $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$order_info['user_id']);
                        $money = $user_info[$order_info['withdraw_method']]+$order_info['money'];
                        $GLOBALS['db']->query("update ".DB_PREFIX."user set ".$order_info['withdraw_method']." = ".$money." where id = ".$order_info['user_id']);
                    }
                    $data['status'] = 1;
                    $data['info'] = "删除成功";
                    ajax_return($data);
                }
                else
                {
                    $data['status'] = 0;
                    $data['info'] = "删除失败";
                    ajax_return($data);
                }
            }
            else
            {
                $data['status'] = 0;
                $data['info'] = "提现单不存在";
                ajax_return($data);
            }
        }
    }

    public function admin_money($rest_withdraw,$money){

        $withdraw_date = $GLOBALS['db']->getOne("select create_time from ".DB_PREFIX."withdraw where user_id = ".$GLOBALS['user_info']['id']." and withdraw_method = 'admin_money' and is_delete=0 order by create_time desc");
        $withdraw_date_month = date('Y-m',$withdraw_date);
        $this_month = date('Y-m',time());
        //提现金额限制
        if($rest_withdraw['admin_money_low'] != 0){
            if($money<$rest_withdraw['admin_money_low']){
                $data['status'] = 0;
                $data['info'] = "金额低于".$rest_withdraw['admin_money_low']."，请重新输入";
                ajax_return($data);
            }
        }elseif($rest_withdraw['admin_money_high'] != 0){
            if($money>$rest_withdraw['admin_money_high']){
                $data['status'] = 0;
                $data['info'] = "金额高于".$rest_withdraw['admin_money_high']."，请重新输入";
                ajax_return($data);
            }
        }
        //提现时间限制
        if($this_month == $withdraw_date_month){
            $data['status'] = 0;
            $data['info'] = "本月已提现管理奖，不可再次提现";
            ajax_return($data);
        }
    }

    public function fx_money($rest_withdraw,$money){
        $date = time();
        $withdraw_date = $GLOBALS['db']->getOne("select create_time from ".DB_PREFIX."withdraw where withdraw_method  = 'fx_money' and is_delete=0 and user_id = ".$GLOBALS['user_info']['id']." order by create_time desc");
        $withdraw_date_befor = date('Y-W',$withdraw_date);
        $week = date("Y-W",time());
        //提现金额限制
        if($rest_withdraw['fx_money_low'] != 0){
            if($money<$rest_withdraw['fx_money_low']){
                $data['status'] = 0;
                $data['info'] = "金额低于".$rest_withdraw['fx_money_low']."，请重新输入";
                ajax_return($data);
            }
        }elseif($rest_withdraw['fx_money_high'] != 0){
            if($money>$rest_withdraw['fx_money_high']){
                $data['status'] = 0;
                $data['info'] = "金额高于".$rest_withdraw['fx_money_high']."，请重新输入";
                ajax_return($data);
            }
        }
        //提现时间限制
        if($week == $withdraw_date_befor){
            $data['status'] = 0;
            $data['info'] = "本周已提现推广奖，不可再次提现";
            ajax_return($data);
        }else{
            $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_withdraw_date=".$date." where id =".$GLOBALS['user_info']['id']);
        }
    }

    public function money($rest_withdraw,$money){
        $date = time();
        $last_withdraw_date = $GLOBALS['db']->getOne("select create_time from ".DB_PREFIX."withdraw where withdraw_method  = 'money' and is_delete=0 and user_id = ".$GLOBALS['user_info']['id']." order by create_time desc");
        if($last_withdraw_date != false){
            $last_withdraw_date_day = date('Y-m-d',$last_withdraw_date);
        }else{
            $last_withdraw_date_day = '';
        }
        $seven_day_ago = time()-60*60*24*7;
        $incharge_info = $GLOBALS['db']->getAll("select sum(pay_amount) money from ".DB_PREFIX."deal_order where type=1 and pay_status=2 and create_time<".$seven_day_ago." and user_id = ".$GLOBALS['user_info']['id']);
        $withdraw_info = $GLOBALS['db']->getAll("select sum(money) money from ".DB_PREFIX."withdraw where is_paid=1 and withdraw_method=money and user_id = ".$GLOBALS['user_info']['id']);
        foreach($incharge_info as $i){
            foreach($withdraw_info as $w){
                $kt_money = $i['money']-$w['money'];
            }
        }
        if($kt_money<$money){
            $data['status'] = 0;
            $data['info'] = '充值时间未满7天不可提现';
            ajax_return($data);
        }
        $today = date('Y-m-d',time());
        $first_pay_date = $GLOBALS['user_info']['first_pay_date'];
        $ten_day = 60*60*24*7;
        $submitted_date1 = $first_pay_date+$ten_day;
        $submitted_date = date('Y-m-d',$submitted_date1);
        $total_use_money = $GLOBALS['user_info']['total_use_money'];
        //提现金额限制
        if($rest_withdraw['money_low'] != 0){
            if($money<$rest_withdraw['money_low']){
                $data['status'] = 0;
                $data['info'] = "金额低于".$rest_withdraw['money_low']."，请重新输入";
                ajax_return($data);
            }
        }elseif($rest_withdraw['money_high'] != 0){
            if($money>$rest_withdraw['money_high']){
                $data['status'] = 0;
                $data['info'] = "金额高于".$rest_withdraw['money_high']."，请重新输入";
                ajax_return($data);
            }
        }
        //提现时间限制
        if($today == $last_withdraw_date_day){
            $data['status'] = 0;
            $data['info'] = "今天提现次数已用完，请明天再来";
            ajax_return($data);
        }elseif($date<$submitted_date){
            if($total_use_money==0){
                $GLOBALS['db']->query("update ".DB_PREFIX."user set can_use_give_money=0 where id =".$GLOBALS['user_info']['id']);
            }
        }
    }

    public function give_money($rest_withdraw,$money){
        $first_pay_date = $GLOBALS['user_info']['first_pay_date'];
        $ten_day = 60*60*24*7;
        $submitted_date = $first_pay_date+$ten_day;
        $date = time();
        $first_give_money = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."withdraw where user_id=".$GLOBALS['user_info']['id']." and withdraw_method = give_money");
        if($first_give_money==false){
            if($money<100){
                $data['status'] = 0;
                $data['info'] = "金额低于100，请重新输入";
                ajax_return($data);
            }
        }else{
            //提现金额限制
            if($rest_withdraw['give_money_low'] != 0){
                if($money<$rest_withdraw['give_money_low']){
                    $data['status'] = 0;
                    $data['info'] = "金额低于".$rest_withdraw['give_money_low']."，请重新输入";
                    ajax_return($data);
                }
            }elseif($rest_withdraw['give_money_high'] != 0){
                if($money>$rest_withdraw['give_money_high']){
                    $data['status'] = 0;
                    $data['info'] = "金额高于".$rest_withdraw['give_money_high']."，请重新输入";
                    ajax_return($data);
                }
            }
        }

        if(is_int($money/50)){
            $data['status'] = 0;
            $data['info'] = "提现要是50的倍数";
            ajax_return($data);
        }
        //提现时间限制

        if($date<$submitted_date){
            $data['status'] = 0;
            $data['info'] = "首次充值时间未超过7天，赠送金额不可提现";
            ajax_return($data);
        }else{
            $can_use_give_money = intval($GLOBALS['user_info']['can_use_give_money']);
            if($money>$can_use_give_money){
                $data['status'] = 0;
                $data['info'] = "提现金额超过可提现金额";
                ajax_return($data);
            }
        }
    }

    public function biejinlai(){
        $user_info = $GLOBALS['db']->getAll("select id,first_pay_date,give_money from ".DB_PREFIX."user where total_money=money and money=1000 and give_money=200");
        //添加赠送金额提现表

        $give_info = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."give_money where money=200");

    }

    public function del_user_bank(){
        global_run();
        $user_bank_id = intval($_REQUEST['user_bank_id']);
        $user_bank = $GLOBALS['db']->getAll("select id from ".DB_PREFIX."user_bank where user_id = ".$GLOBALS['user_info']['id']);
        $count = 0;
        foreach($user_bank as $v){
            $count++;
        }
        if($count<=1){
            $data['status'] = 0;
            $data['info'] = '用户仅剩最后一张银行卡，无法删除';
            ajax_return($data);
            die;
        }
        $GLOBALS['db']->query("delete from ".DB_PREFIX."user_bank where id = ".$user_bank_id." and user_id = ".$GLOBALS['user_info']['id']);

        if($GLOBALS['db']->affected_rows()){
            $data['status'] = 1;
            $data['info'] = "删除成功";
        }else{
            $data['status'] = 0;
            $data['info'] = "删除失败";
        }
        ajax_return($data);
    }
}
?>
