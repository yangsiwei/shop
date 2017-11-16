<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class user_centerModule extends MainBaseModule
{

    public function index()
    {
        global_run();
        init_app_page();

        $param=array();
        $data = call_api_core("user_center","index",$param);
        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        $dealers = $GLOBALS['user_info']['dealers'];
        if($dealers <= 1){
            $GLOBALS['tmpl']->assign("dealers",$dealers);
        }
        if($GLOBALS['user_info']['dealers'] == 2){
            $GLOBALS['tmpl']->assign("dealers",$GLOBALS['user_info']['dealers']);
        }
        $data['total_use_money'] = $GLOBALS['user_info']['total_use_money'];
        if($GLOBALS['user_info']['dealers'] != 2){
            $jingxiaoshang = 1;
        }

        $lucky_draw = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."lucky_draw where user_id = ".$GLOBALS['user_info']['id']);

        $fx_level = $GLOBALS['user_info']['fx_level'];
        switch ($fx_level){
            case 1:
                $fx_level = '白银';
                break;
            case 2:
                $fx_level = '黄金';
                break;
            case 3:
                $fx_level = '钻石';
                break;
            case 4:
                $fx_level = '钻石';
                break;
            default:
                $fx_level = '小白';

        }

        $date = time();

        //添加赠送金额
        $give_money = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."give_money where user_id = ".$GLOBALS['user_info']['id']." and create_time<".$date." and is_delete=0");
        foreach($give_money as $gi){
            $GLOBALS['db']->query("update ".DB_PREFIX."give_money set is_delete=1 where id = ".$gi['id']);
            $GLOBALS['db']->query("update ".DB_PREFIX."user set can_use_give_money = can_use_give_money+".$gi['money']." where id = ".$GLOBALS['user_info']['id']);
        }

        //添加推广奖
        $fx_money = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."fx_money where user_id = ".$GLOBALS['user_info']['id']." and create_time<".$date." and is_delete=0 and method = fx_money");
        foreach($fx_money as $fx){
            $GLOBALS['db']->query("update ".DB_PREFIX."fx_money set is_delete=1 where id = ".$fx['id']);
            $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_money = fx_money+".$fx['money']." where id = ".$GLOBALS['user_info']['id']);
        }

        //添加管理奖
        $admin_money = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."fx_money where user_id = ".$GLOBALS['user_info']['id']." and create_time<".$date." and is_delete=0 and method = admin_money");

        foreach($admin_money as $ad){
            $GLOBALS['db']->query("update ".DB_PREFIX."fx_money set is_delete=1 where id = ".$fx['id']);
            $GLOBALS['db']->query("update ".DB_PREFIX."user set admin_money = admin_money+".$ad['money']." where id = ".$GLOBALS['user_info']['id']);
        }
        $jjdz = $GLOBALS['db']->getAll("select sum(money) money from ".DB_PREFIX."fx_money where user_id=".$GLOBALS['user_info']['id']." and is_delete = 0");
        $this->level();
        $GLOBALS['tmpl']->assign('fx_level',$fx_level);
        $GLOBALS['tmpl']->assign('lucky_draw',$lucky_draw);
        $GLOBALS['tmpl']->assign('jingxiaoshang',$jingxiaoshang);
        $GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("jjdz",intval($jjdz[0]['money']));
        $GLOBALS['tmpl']->display("user_center.html");
    }

    public function more_use(){
        $data['page_title'] = '更多功能';
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("more_use.html");
    }

    public function qrcode(){
        global_run();
        init_app_page();
        $data['page_title'] ="渠道二维码";
        $data['user_id'] = intval($_REQUEST['data_id']);

        $user_id = $data['user_id'];
        include_once APP_ROOT_PATH."system/model/weixin_jssdk.php";
        $img_url = getQrCode($user_id);

        $share_url = $img_url;
        $GLOBALS['tmpl']->assign("wx_share_url", $share_url);
        $GLOBALS['tmpl']->assign("img_url",$img_url);
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("qrcode.html");
    }

    public function tobe_dealers(){
        global_run();
        $total_money = $GLOBALS['user_info']['total_money'];
        $money = 10-$total_money;
        if($total_money>=10){
            $res = $GLOBALS['db']->query("update ".DB_PREFIX."user set dealers = 2,fx_level = 1 where id = ".$GLOBALS['user_info']['id']);
            if($res){
                $data['status'] = 1;
                $data['info'] = '恭喜您已经成为经销商，可以坐享佣金啦！！！';
            }
        }else{
            $data['status'] = 0;
            $data['info'] = '你还需充值'.$money.'夺宝币可以成为经销商';
        }
        ajax_return($data);
    }

    public function level(){
        global_run();
        $point = $GLOBALS['user_info']['total_use_money'];
        if($point<10&&$point>0){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set level_id = 1 where id = ".$GLOBALS['user_info']['id']);
        }elseif($point>=10&&$point<50){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set level_id = 2 where id = ".$GLOBALS['user_info']['id']);
        }elseif($point>=50&&$point<100){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set level_id = 3 where id = ".$GLOBALS['user_info']['id']);
        }elseif($point>=100&&$point<500){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set level_id = 4 where id = ".$GLOBALS['user_info']['id']);
        }elseif($point>=500&&$point<1000){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set level_id = 5 where id = ".$GLOBALS['user_info']['id']);
        }elseif($point>=1000&&$point<5000){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set level_id = 6 where id = ".$GLOBALS['user_info']['id']);
        }elseif($point>=5000&&$point<10000){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set level_id = 7 where id = ".$GLOBALS['user_info']['id']);
        }elseif($point>=10000&&$point<50000){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set level_id = 8 where id = ".$GLOBALS['user_info']['id']);
        }elseif($point>=50000&&$point<10000){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set level_id = 9 where id = ".$GLOBALS['user_info']['id']);
        }elseif($point>=100000){
            $GLOBALS['db']->query("update ".DB_PREFIX."user set level_id = 10 where id = ".$GLOBALS['user_info']['id']);
        }
    }


}
?>
