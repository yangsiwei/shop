<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
class serviceModule extends MainBaseModule
{
    public function index(){
        global_run();
        if(!isset($_COOKIE['service'])){
            echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service&act=login'</script>";
            die;
        }
        $root['user_name'] = $_REQUEST['user_name'];
        $root['order_sn'] = $_REQUEST['order_sn'];
        $root['order_status'] = $_REQUEST['order_status'];
        $root['money'] = $_REQUEST['money'];
        if(!empty($root['money'])){
            $money_sql = "and pay_amount = ".$root['money'];
        }
        if($root['order_status'] == 1){
            $sql = "and order_status = 1";
        }else{
            $sql = '';
        }
        if($root['user_name']){
            $user_info = $GLOBALS['db']->getAll("select id from ".DB_PREFIX."user where user_name like '".$root['user_name']."'");
            foreach($user_info as $ui){
                $user_id[] = $ui['id'];
            }
            $user_id = join(',',$user_id);
            $charge_order = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order where order_sn like '%".$root['order_sn']."%' and  type=1 and pay_status=2 and pay_amount>0 and user_id in ({$user_id}) ".$sql."".$money_sql." order by create_time desc");
        }else{
            $charge_order = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order where order_sn like '%".$root['order_sn']."%'".$money_sql." and  type=1 and pay_status=2 and pay_amount>0 ".$sql." order by create_time desc");

        }
        $user = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where is_effect = 1");
        foreach($charge_order as &$co){
            foreach($user as $u){
                if($co['user_id'] == $u['id']){
                    $co['user_name'] = $u['user_name'];
                }
            }
            $co['create_time'] = date('Y年m月d日 H:i:s',$co['create_time']);
        }
        $GLOBALS['tmpl']->assign("page_title",'充值列表');
        $GLOBALS['tmpl']->assign("root",$root);
        $GLOBALS['tmpl']->assign("charge_order",$charge_order);
        $GLOBALS['tmpl']->display("service.html");
    }



    public function login(){
        $pwd = $_REQUEST['pwd'];
        $name = $_REQUEST['user'];
        if($pwd||$name){
            $admin = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."admin where is_effect=1 and is_delete=0");
            $pass='';
            foreach($admin as $v){
                if($name == $v['adm_name']){
                    $pass = $v['adm_password'];
                }
            }
            $pwd = md5($pwd);
            if($pwd == $pass){
                $login = true;
                //保存cookie
                es_cookie::set("service", $login, 3600 * 24 * 30);
                echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service'</script>";
            }else{
                echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service&act=login'</script>";
            }
        }else{
            $dologin = true;
            $GLOBALS['tmpl']->assign("dologin",$dologin);
            $GLOBALS['tmpl']->display("service.html");
        }
    }

    public function logout(){
        es_cookie::delete("service");
        echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service'</script>";
    }

    public function order_done(){
        if(!isset($_COOKIE['service'])){
            echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service&act=login'</script>";
            die;
        }
        $id = $_REQUEST['id'];
        $res = $GLOBALS['db']->query("update ".DB_PREFIX."deal_order set order_status = 1 where id = ".$id);
        if($res){
            $data['status'] = 1;
            $data['info'] = '状态修改成功';
        }else{
            $data['stauts'] = 0;
            $data['info'] = '状态修改失败';
        }
        ajax_return($data);
    }

    public function withdraw(){
        global_run();
        if(!isset($_COOKIE['service'])){
            echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service&act=login'</script>";
            die;
        }
        if(!isset($_COOKIE['service'])){
            $dologin = true;
        }
        $info = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."withdraw order by create_time desc");
        $user = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where is_effect = 1");
        foreach($info as &$co){
            foreach($user as $u){
                if($co['user_id'] == $u['id']){
                    $co['user_name'] = $u['user_name'];
                }
                if($u['first_pay_date'] != $u['last_recharge_date'] && $co['user_id'] == $u['id']){
                    $co['repeat'] = 1;
                }
            }
            foreach($info as $in){
                if($co['bank_user'] == $in['bank_user']){
                    if($co['user_id'] != $in['user_id']){
                        $co['cf']++;
                    }
                    if($co['withdraw_method']=='money'){
                        $co['cft']++;
                    }
                }
            }
            if($co['cf']>=1){
                $co['bank_user'] = "<p style='color:red;'>".$co['bank_user']."</p>";
            }
            if($co['cft']>1){
                $co['bank_user'] = "<p style='color:blue;'>".$co['bank_user']."</p>";
            }

            switch ($co['withdraw_method'])
            {
                case fx_money:
                $co['withdraw_method'] = "<p style='color:#E7400D'>推广奖</p>";
                break;
                case admin_money:
                $co['withdraw_method'] = "<p style='color:#C7E70D'>管理奖</p>";
                break;
                case give_money:
                $co['withdraw_method'] = "<p style='color:#1AEA1E'>赠送金额</p>";
                break;
                default:
                $co['withdraw_method'] = "<p style='color:#521AEA'>本金</p>";
            }
            $co['money'] = intval($co['money']);
            $co['create_time'] = date('Y年m月d日 H:i:s',$co['create_time']);
        }

        $GLOBALS['tmpl']->assign("page_title",'提现列表');
        $GLOBALS['tmpl']->assign("withdraw_order",$info);
        $GLOBALS['tmpl']->assign("dologin",$dologin);
        $GLOBALS['tmpl']->display("service.html");
    }

    public function with_done(){
        if(!isset($_COOKIE['service'])){
            echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service&act=login'</script>";
            die;
        }
        $id = $_REQUEST['id'];
        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."withdraw where id = ".$id);
        if($order_info['is_delete'] == 1){
            $data['status'] = 0;
            $data['info'] = '用户已删除';
            ajax_return($data);
        }
        $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$order_info['user_id']);
        if($order_info['withdraw_method'] == 'money'){
            if($user_info['money']<$order_info['money']){
                $data['status'] = 0;
                $data['info'] = '用户余额不足,转账失败';
                ajax_return($data);
            }
        }

        if($order_info['is_paid'] != 1 && $order_info['is_delete'] != 1){
            $res = $GLOBALS['db']->query("update ".DB_PREFIX."withdraw set is_paid = 1,pay_time=".time()." where id = ".$id);


        }else{
            $data['status'] = 0;
            $data['info'] = '订单已付款或用户已删除';
        }

        if($res){
            $money = $user_info['money']-$order_info['money'];
            if($order_info['withdraw_method'] == 'money'){
                $GLOBALS['db']->query("update ".DB_PREFIX."user set money = ".$money." where id = ".$user_info['id']);
            }
            //添加用户提现记录
            $give_log['log_info'] = '提现'.$order_info['money'].'申请通过';
            $give_log['log_time'] = time();
            $give_log['log_user_id'] = $order_info['user_id'];
            $give_log['money'] = -$order_info['money'];
            $give_log['user_id'] = $order_info['user_id'];
            $GLOBALS['db']->autoExecute(DB_PREFIX."user_log", $give_log);

            $data['status'] = 1;
            $data['info'] = '确认付款成功';
            send_user_withdraw_sms($user_info['id'],$order_info['money']);
            send_user_withdraw_mail($user_info['id'],$order_info['money']);
            modify_account(array('money'=>"-".$order_info['money']),$order_info['user_id'],$user_info['user_name']."提现".format_price($order_info['money'])."元审核通过。");
            modify_statements($order_info['money'],3,$user_info['user_name']."提现".format_price($order_info['money'])."元审核通过。");
            modify_statements($order_info['money'],4,$user_info['user_name']."提现".format_price($order_info['money'])."元审核通过。");
        }

        ajax_return($data);

    }

    public function with_del(){
        if(!isset($_COOKIE['service'])){
            echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service&act=login'</script>";
            die;
        }
        $id = $_REQUEST['id'];
        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."withdraw where id = ".$id);
        $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$order_info['user_id']." and is_effect = 0");
        if(!$order_info){
            $data['status'] = 0;
            $data['info'] = '订单不存在';
        }
        if($order_info['withdraw_method'] != 'money'){
            if($order_info['is_paid'] != 1 && $order_info['is_delete'] != 1){
                $money = $user_info[$order_info['withdraw_method']]+$order_info['money'];
                $res = $GLOBALS['db']->query("update ".DB_PREFIX."user set ".$order_info['withdraw_method']." = ".$money." where id = ".$order_info['user_id']);
                if($res){
                    $data['status'] = 1;
                    $data['info'] = '用户'.$order_info['withdraw_method'].'已增加';
                }else{
                    $data['status'] = 0;
                    $data['info'] = '用户'.$order_info['withdraw_method'].'修改失败';
                }
            }else{
                $data['status'] = 0;
                $data['info'] = '订单已付款或用户已删除';
            }
        }else{
            if($order_info['is_paid'] != 1 && $order_info['is_delete'] != 1){
                $data['status'] = 1;
                $data['info'] = '用户余额提现审批未通过';
            }else{
                $data['status'] = 0;
                $data['info'] = '订单已付款或用户已删除';
            }
        }
        $GLOBALS['db']->query("delete from ".DB_PREFIX."withdraw where id = ".$id);
        ajax_return($data);
    }

    public function user_log(){
        $id = $_REQUEST['id'];
        $user_log = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_log where user_id = ".$id);
        $admin = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."admin");
        $user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".$id);
        foreach($user_log as &$v){
            $v['log_time'] = date("Y年m月d日 H:i:s",$v['log_time']);
            foreach($admin as $vv){
                if($v['log_admin_id'] == $vv['id']){
                    $v['log_admin'] = $vv['adm_name'];
                }
            }
        }
        $url=$_SERVER['HTTP_REFERER'];
        $GLOBALS['tmpl']->assign("url_user_list",$url);
        $GLOBALS['tmpl']->assign("user_log",$user_log);
        $GLOBALS['tmpl']->assign("page_title",$user_name.'的账户明细');
        $GLOBALS['tmpl']->display("service.html");
    }

    public function user_list(){
        if(!isset($_COOKIE['service'])){
            echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service&act=login'</script>";
            die;
        }
        $id = $_REQUEST['id'];
        $user_info_list = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id);
        $url=$_SERVER['HTTP_REFERER'];
        $GLOBALS['tmpl']->assign("url_user_list",$url);
        $GLOBALS['tmpl']->assign("user_info_list",$user_info_list);
        $GLOBALS['tmpl']->assign("page_title",'用户详情');
        $GLOBALS['tmpl']->display("service.html");
    }

//    public function user_list_edit(){
//        $post = $_REQUEST;
//        $user['mobile'] = $_REQUEST['mobile'];
//        $user['fx_money'] = $_REQUEST['fx_money'];
//        $user['admin_money'] = $_REQUEST['admin_money'];
//        $user['give_money'] = $_REQUEST['give_money'];
//        $user['money'] = $_REQUEST['money'];
//        $user['fx_level'] = $_REQUEST['fx_level'];
//
//    }

    public function user(){
        if(!isset($_COOKIE['service'])){
            echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service&act=login'</script>";
            die;
        }
        $data['user_name'] = $_REQUEST['user_name'];
        $data['id'] = $_REQUEST['id'];
        $data['email'] = $_REQUEST['email'];
        $data['mobile'] = $_REQUEST['mobile'];
        $data['pid'] = $_REQUEST['pid'];
        $data['money'] = $_REQUEST['money'];
        $data['fx_level'] = $_REQUEST['fx_level'];
        if(!empty($data['user_name'])){
            $sql_user_name = "and user_name like '%".$data['user_name']."%'";
        }
        if(!empty($data['id'])){
            $sql_id = "and id = ".$data['id'];
        }
        if(!empty($data['email'])){
            $sql_email = "and email like '%".$data['email']."%'";
        }
        if(!empty($data['mobile'])){
            $sql_mobile = "and mobile like '%".$data['mobile']."%'";
        }
        if(!empty($data['pid'])){
            $user_pid = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where user_name = '".$data['pid']."'");
            $sql_pid = "and pid = ".$user_pid;
        }
        if(!empty($data['money'])){
            $sql_money = "and money >= ".$data['money'];
        }
        if(!empty($data['fx_level'])){
            $sql_fx_level = "and fx_level = ".$data['fx_level'];
        }
        $user = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where is_effect=1 and is_robot=0 and is_delete=0 ".$sql_user_name."".$sql_id."".$sql_email."".$sql_mobile."".$sql_pid."".$sql_money."".$sql_fx_level);
        $user_s = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where is_effect=1 and is_robot=0 and is_delete=0");
        foreach($user as &$v){
            $v['first_pay_date'] = date("Y年m月d日 H:i:s",$v['first_pay_date']);
            foreach($user_s as $vv){
                if($v['pid'] == $vv['id']){
                    $v['pid_name'] = $vv['user_name'];
                }
            }
        }
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("user",$user);
        $GLOBALS['tmpl']->assign("page_title",'会员列表');
        $GLOBALS['tmpl']->display("service.html");
    }

    public function fx(){
        $id = $_REQUEST['id'];
        $user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".$id);
        $fx_user = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where pid = ".$id." and is_effect=1 and is_delete=0 and is_robot=0");
        $all_user = $GLOBALS['db']->getAll("select id,pid,user_name from ".DB_PREFIX."user where is_effect=1 and is_delete=0 and is_robot=0");
        foreach($fx_user as &$v){
            $v['create_time'] = date("Y年m月d日 H:i:s",$v['create_time']);
            foreach($all_user as $vv){
                if($v['id'] == $vv['pid']){
                    $v['sid']++;
                }
                if($v['pid'] == $vv['id']){
                    $v['p_user'] = $vv['user_name'];
                }
            }
            $first_uid[] = $v['id'];
        }

        $first_uid = join(',',$first_uid);
        $second_user=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where pid in ({$first_uid}) and is_effect=1 and is_delete=0 and is_robot=0");
        foreach($second_user as &$se){
            $se['create_time'] = date("Y年m月d日 H:i:s",$se['create_time']);
            $second_uid[] = $se['id'];
            foreach($all_user as $vv){
                if($se['id'] == $vv['pid']){
                    $se['sid']++;
                }
                if($se['pid'] == $vv['id']){
                    $se['p_user'] = $vv['user_name'];
                }
            }
        }
        $second_uid = join(',',$second_uid);
        $three_user = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where pid in ({$second_uid}) and is_effect=1 and is_delete=0 and is_robot=0");
        foreach($three_user as &$th){
            $th['create_time'] = date("Y年m月d日 H:i:s",$th['create_time']);
            $three_uid[] = $th['id'];
            foreach($all_user as $vv){
                if($th['id'] == $vv['pid']){
                    $th['sid']++;
                }
                if($th['pid'] == $vv['id']){
                    $th['p_user'] = $vv['user_name'];
                }
            }
        }
        $three_uid = join(',',$three_uid);
        $four_user = $this->four_user_msg($three_uid);
        foreach($four_user as &$fo){
            $fo['create_time'] = date("Y年m月d日 H:i:s",$fo['create_time']);
            foreach($all_user as $vv){
                if($fo['id'] == $vv['pid']){
                    $fo['sid']++;
                }
                if($fo['pid'] == $vv['id']){
                    $fo['p_user'] = $vv['user_name'];
                }
            }
        }
        $GLOBALS['tmpl']->assign("four_user",$four_user);
        $GLOBALS['tmpl']->assign("three_user",$three_user);
        $GLOBALS['tmpl']->assign("second_user",$second_user);
        $GLOBALS['tmpl']->assign("fx_user",$fx_user);
        $GLOBALS['tmpl']->assign("page_title",$user_name."的线下会员");
        $GLOBALS['tmpl']->display("service.html");
    }

    public function four_user_msg($three_uid,$data=array()){
        $user_info = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where pid in ({$three_uid}) and is_effect=1 and is_delete=0 and is_robot=0");
        foreach($user_info as $k=>$v){
            $user_id[] = $v['id'];
            $data[] = $user_info[$k];
        }
        $user_id = join(',',$user_id);
        if($user_info){
            $this->four_user_msg($user_id);
        }
        return $data;
    }

    public function prize(){
       if(!isset($_COOKIE['service'])){
            echo "<script>window.location.href='http://www.gagoods.cn/index.php?ctl=service&act=login'</script>";
            die;
        }
        $prize_order = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."prize order by addtime desc");
        $user = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where is_effect=1");

           foreach($prize_order as &$v){
            foreach($user as $u){
                if($v['uid'] == $u['id']){
                    $v['user_name'] = $u['user_name'];
                }
            }
            $v['addtime'] = date('Y年m月d日 H:i:s',$v['addtime']);
        }

        $GLOBALS['tmpl']->assign("prize_order",$prize_order);
        $GLOBALS['tmpl']->assign("page_title",'抽奖列表');
        $GLOBALS['tmpl']->display("service.html");
    }
}