<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class uc_fxinviteModule extends MainBaseModule
{

    public function index(){
        global_run();
        init_app_page();

        $param=array();
        $param['page'] = intval($_REQUEST['page']);

        $data = call_api_core("uc_fxinvite","index",$param);

        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }

        if(isset($data['page']) && is_array($data['page'])){
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();

            $GLOBALS['tmpl']->assign('pages',$p);
        }


        $host = $_SERVER['HTTP_HOST'];
        $fx_url = SITE_DOMAIN.wap_url("index","user#register")."&r=".base64_encode($GLOBALS['user_info']['id']);
        $GLOBALS['tmpl']->assign("fx_url",$fx_url);
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("host",$host);
        $GLOBALS['tmpl']->display("uc_fxinvite.html");
    }

    public function index1(){
        global_run();
        init_app_page();

        $data = call_api_core("uc_fxinvite","index1");
        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        $data['first_user_count'] = intval($data['first_user_count']);
        $data['second_user_count'] = intval($data['second_user_count']);
        $data['three_user_count'] = intval($data['three_user_count']);

        $data['fx_name'] = app_conf('FX_SET_NAME');
        $data['fx_user1_url'] = wap_url("index","uc_fxinvite#uc_fxinvite_user1");
        $data['fx_user2_url'] = wap_url("index","uc_fxinvite#uc_fxinvite_user2");
        $data['fx_user3_url'] = wap_url("index","uc_fxinvite#uc_fxinvite_user3");
        $data['page_title'] = "我的团队";

        $fx_level = $GLOBALS['db']->getOne("select fx_level from ".DB_PREFIX."user where id = ".$GLOBALS['user_info']['id']);
        switch ($fx_level) {
            case 4:
                $level = '管理员';
                break;
            case 3:
                $level = '钻石会员';
                break;
            case 2:
                $level = '黄金会员';
                break;
            default:
                $level = '白银会员';
                break;
        }
        $GLOBALS['tmpl']->assign("level",$level);
        $dealers = $GLOBALS['user_info']['dealers'];
//        $need_money = 100-$GLOBALS['user_info']['total_money'];
        $GLOBALS['tmpl']->assign('dealers',$dealers);
//        $GLOBALS['tmpl']->assign('need_money',$need_money);
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("uc_fxinvite1.html");
    }

    public function to_be_dealers(){
        global_run();

        if(!$GLOBALS['user_info']){
            app_redirect(wap_url("index","user#login"));
            die;
        }

        if($GLOBALS['user_info']['total_money']<100){
            app_redirect(wap_url("index","user#login"));
            die;
        }

        $res = $GLOBALS['db']->query("update ".DB_PREFIX."user set dealers = 2 where id = ".$GLOBALS['user_info']['id']);
        if($res){
            app_redirect(wap_url("index","uc_fxinvite#index1"));
        }
    }


    public function uc_fxinvite_user1(){
        global_run();
        init_app_page();
        $data = call_api_core("uc_fxinvite","uc_fxinvite_user1");

        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        $data['page_title'] = "一级邀请用户";
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("uc_fxinvite_user.html");
    }

    public function uc_fxinvite_user2(){
        global_run();
        init_app_page();
        $data = call_api_core("uc_fxinvite","uc_fxinvite_user2");

        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        $fx_name = app_conf('FX_SET_NAME');
        $data['page_title'] = "二级邀请用户";
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("uc_fxinvite_user.html");
    }

    public function uc_fxinvite_user3(){
        global_run();
        init_app_page();
        $data = call_api_core("uc_fxinvite","uc_fxinvite_user3");

        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
        $fx_name = app_conf('FX_SET_NAME');
        $data['page_title'] = "三级邀请用户";
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("uc_fxinvite_user.html");
    }

}
?>