<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class uc_settingModule extends MainBaseModule
{

    public function index()
    {
        global_run();
        init_app_page();
        $this->level();
        $param=array();
        $data = call_api_core("uc_setting","index",$param);
        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }
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
        if($_SERVER['REQUEST_URI'] == '/wap/index.php?ctl=uc_setting&show_prog=1'){
            $fanhui = true;
        }
        $data['page_title'] = '用户设置';
        $data['level_id'] = $GLOBALS['user_info']['level_id'];
        $data['total_use_money']  = $GLOBALS['user_info']['total_use_money'];
        $GLOBALS['tmpl']->assign("fanhui",$fanhui);
        $GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);
        $GLOBALS['tmpl']->assign("fx_level",$fx_level);
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("uc_setting.html");
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
