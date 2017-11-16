<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class userModule extends MainBaseModule
{
    /**
     *
     * 登录页跳转进入条件
     * 1.未登录状态 login_status 0
     * 2.需要验证安全登录状态的临时登录 login_status:2
     * 3.下单时账户无手机号（进入绑定）
     * 4.下单时有账户余额并且为临时登录状态
     *
     * 登录页的展示：
     * 1.无登录时，显示账号登录与手机短信登录（无需验证唯一）
     * 2.临时登录时，显示验证登录，账号名锁死，如有手机号，手机号锁死
     * 3.会员为临时会员，并且无手机号时，显示绑定页
     */
    public function login()
    {
        global_run();
        init_app_page();
        //已登录跳到首页
        require_once APP_ROOT_PATH . "system/model/user.php";
        $user_login_status = check_save_login();
        if ($user_login_status == LOGIN_STATUS_LOGINED) {
            app_redirect(wap_url("index", "index#index"));
        }


        $GLOBALS['tmpl']->assign("user_info", $GLOBALS['user_info']);
        $data['page_title'] = $GLOBALS['m_config']['program_title'] ? $GLOBALS['m_config']['program_title'] . " - " : "";
        $data['page_title'] .= "登录";
        $GLOBALS['tmpl']->assign("sms_lesstime", load_sms_lesstime());

        $GLOBALS['tmpl']->assign("data", $data);
        $GLOBALS['tmpl']->display("user_login.html");
    }
    /**
     * 注册条款 2017.9.19
     */
    public function register_agree(){
        global_run();
        $GLOBALS['tmpl']->display("register_agree.html");
    }
    /**
     * 隐私条款 2017.9.19
     */
    public function register_privacy(){
        global_run();
        $GLOBALS['tmpl']->display("register_privacy.html");
    }

    /**
     * 微博登录
     */
    public function wb_login()
    {
        global_run();
        init_app_page();
        //已登录跳到首页
        require APP_ROOT_PATH."system/weibo/saetv2.ex.class.php";
        $sae  = new SaeTOAuthV2( app_conf('WB_APP_KEY'), app_conf('WB_APP_SECRET') );

        $aUrl = get_domain().wap_url("index", "index#index", array('wb_login'=>1));
        $code_url = $sae->getAuthorizeURL($aUrl,  $response_type = 'code', $state = NULL, 'mobile');

        // 直接跳转到授权页面
        app_redirect($code_url);
    }

    /**
     * QQ登录
     */
    public function qq_login()
    {
        global_run();
        init_app_page();
        require APP_ROOT_PATH."system/qqconnect/API/qqConnectAPI.php";
        $qc = new QC();
        $qc->qq_login();
    }

    public function dologin()
    {
        global_run();
        $user_name = strim($_REQUEST['user_key']);
        $password = strim($_REQUEST['user_pwd']);

        $data = call_api_core("user", "dologin", array("user_key" => $user_name, "user_pwd" => $password));
        if ($data['status']) {
            $data['jump'] = get_gopreview();
            //保存cookie
            es_cookie::set("user_name", $data['user_name'], 3600 * 24 * 30);
            es_cookie::set("user_pwd", md5($data['user_pwd'] . "_EASE_COOKIE"), 3600 * 24 * 30);
        }
        ajax_return($data);
    }


    public function dophlogin()
    {
        global_run();
        $mobile = strim($_REQUEST['mobile']);
        $sms_verify = strim($_REQUEST['sms_verify']);

        $data = call_api_core("user", "dophlogin", array("mobile" => $mobile, "sms_verify" => $sms_verify));
        if ($data['status']&&$data['is_new']) {
//            $data['jump'] = get_gopreview();
        $data['jump'] =wap_url("index","user#user_register_next");
        //保存cookie
        es_cookie::set("fanwe_mobile", $mobile, 3600 * 24 * 7);
        es_cookie::set("user_name", $data['user_name'], 3600 * 24 * 30);
        es_cookie::set("user_pwd", md5($data['user_pwd'] . "_EASE_COOKIE"), 3600 * 24 * 30);
        }else if($data['status']&&!$data['is_new']){
            $data['jump'] = get_gopreview();
            //保存cookie
            es_cookie::set("fanwe_mobile", $mobile, 3600 * 24 * 7);
            es_cookie::set("user_name", $data['user_name'], 3600 * 24 * 30);
            es_cookie::set("user_pwd", md5($data['user_pwd'] . "_EASE_COOKIE"), 3600 * 24 * 30);
        }
        ajax_return($data);
    }

    public function dophbind()
    {
        global_run();
        $mobile = strim($_REQUEST['mobile']);
        $sms_verify = strim($_REQUEST['sms_verify']);

        $data = call_api_core("user", "dophbind", array("mobile" => $mobile, "sms_verify" => $sms_verify));
        if ($data['status']) {
            $data['jump'] = get_gopreview();

            //保存cookie
            es_cookie::set("fanwe_mobile", $mobile, 3600 * 24 * 7);
            es_cookie::set("user_name", $data['user_name'], 3600 * 24 * 30);
            es_cookie::set("user_pwd", md5($data['user_pwd'] . "_EASE_COOKIE"), 3600 * 24 * 30);
        }
        ajax_return($data);
    }


    public function loginout()
    {
        $data = call_api_core("user", "loginout");
        es_cookie::delete("user_name");
        es_cookie::delete("user_pwd");
        es_session::delete("wx_info");
        es_cookie::set("deny_weixin_" . intval($GLOBALS['supplier_info']['id']), 1); //人工退出禁止微信登录
        $url = wap_url("index");
        $url = preg_replace("/[&|?]redirect=[^&]*/i", "", $url);
        $url = preg_replace("/[&|?]weixin_login=[^&]*/i", "", $url);
        app_redirect($url);
    }


    public function getpassword()
    {
        global_run();
        init_app_page();

        $data['page_title'] = $GLOBALS['m_config']['program_title'] ? $GLOBALS['m_config']['program_title'] . " - " : "";

        $GLOBALS['tmpl']->assign("sms_lesstime", load_sms_lesstime());

        if ($GLOBALS['user_info'] && $GLOBALS['user_info']['mobile'] == '') {
            $data['page_title'] .= "绑定手机号";
            $GLOBALS['tmpl']->assign("data", $data);
            $user_info = $GLOBALS['user_info'];
            $user_info['is_tmp'] = 1;
            $GLOBALS['tmpl']->assign("user_info", $user_info);
            $GLOBALS['tmpl']->display("user_login.html");
        } else {
            $data['page_title'] .= "重置密码";
            $GLOBALS['tmpl']->assign("data", $data);
            $GLOBALS['tmpl']->display("user_getpassword.html");
        }
    }
    public function user_change_name(){
        global_run();
        init_app_page();
        $data['page_title']="修改昵称";
            $GLOBALS['tmpl']->assign("data",$data);
            $GLOBALS['tmpl']->display("user_change_name.html");
    }
    public function user_change_name_do(){
        global_run();
        $user_name = strim($_REQUEST["user_name"]);
        $data = call_api_core("user", "update_user_name", array("user_name" => $user_name));
        ajax_return($data);
    }
    public function phmodifypassword()
    {
        global_run();
        $mobile = strim($_REQUEST['mobile']);
        $sms_verify = strim($_REQUEST['sms_verify']);
        $new_pwd = strim($_REQUEST['new_pwd']);

        $data = call_api_core("user", "phmodifypassword", array("mobile" => $mobile, "sms_verify" => $sms_verify, "new_pwd" => $new_pwd));
        if ($data['status']) {
            $data['jump'] = get_gopreview();

            //保存cookie
            es_cookie::set("fanwe_mobile", $mobile, 3600 * 24 * 7);
            es_cookie::set("user_name", $data['user_name'], 3600 * 24 * 30);
            es_cookie::set("user_pwd", md5($data['user_pwd'] . "_EASE_COOKIE"), 3600 * 24 * 30);
        }

        ajax_return($data);

    }


    public function register()
    {
        global_run();
        init_app_page();

        $data['page_title'] = $GLOBALS['m_config']['program_title'] ? $GLOBALS['m_config']['program_title'] . " - " : "";
        $data['page_title'] .= "注册";

        $GLOBALS['tmpl']->assign("sms_lesstime", load_sms_lesstime());
        $GLOBALS['tmpl']->assign("data", $data);
        $GLOBALS['tmpl']->display("user_register.html");
    }

    public function doregister()
    {
        global_run();
        $user_name = strim($_REQUEST['user_name']);
        $email = strim($_REQUEST['email']);
        $user_pwd = strim($_REQUEST['user_pwd']);

        $data = call_api_core("user", "doregister", array("user_name" => $user_name, "user_email" => $email, "user_pwd" => $user_pwd, "ref_uid" => $GLOBALS['ref_uid']));
        if ($data['status']) {
            $data['jump'] = get_gopreview();

            //保存cookie
            es_cookie::set("user_name", $data['user_name'], 3600 * 24 * 30);
            es_cookie::set("user_pwd", md5($data['user_pwd'] . "_EASE_COOKIE"), 3600 * 24 * 30);
        }
        ajax_return($data);
    }

    public function dophregister()
    {
        global_run();
        $mobile = strim($_REQUEST['mobile']);
        $sms_verify = strim($_REQUEST['sms_verify']);
        $user_pwd = strim($_REQUEST['user_pwd']);

        $data = call_api_core("user", "dophregister", array("mobile" => $mobile, "sms_verify" => $sms_verify, "user_pwd" => $user_pwd, "ref_uid" => $GLOBALS['ref_uid']));
        if ($data['status']) {

            $data['jump'] = wap_url("index","user#user_register_next");

            //保存cookie
            es_cookie::set("user_name", $data['user_name'], 3600 * 24 * 30);
            es_cookie::set("user_pwd", md5($data['user_pwd'] . "_EASE_COOKIE"), 3600 * 24 * 30);

        }
        ajax_return($data);
    }

    public function user_register_next()
    {
        global_run();
        init_app_page();
        $GLOBALS['tmpl']->display("user_register_next.html");
    }

    public function user_register_next_do()
    {
        global_run();
        $user_name = strim($_REQUEST["user_name"]);
        $dir_data=$this->change_user_image();
        $data = call_api_core("user", "update_user_name", array("user_name" => $user_name));
        if($dir_data){
            $GLOBALS['db']->query("update " . DB_PREFIX . "user set avatar = '" . $dir_data['dir'] . "' where id = " . $dir_data['user_id']);
        }
        ajax_return($data);
    }

    public function change_user_image()
    {
        global_run();
        $img_data = $_REQUEST['img_data'];
        $user_data = $GLOBALS['user_info'];
//        $file_path = array();

        //上传处理
        //创建avatar临时目录
        $user_id = $user_data['id'];

        //开始移动图片到相应位置

        $uid = sprintf("%09d", $user_id);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $path = $dir1 . '/' . $dir2 . '/' . $dir3;

        //创建相应的目录
        if (!is_dir(APP_ROOT_PATH . "public/avatar/" . $dir1)) {
            @mkdir(APP_ROOT_PATH . "public/avatar/" . $dir1);
            @chmod(APP_ROOT_PATH . "public/avatar/" . $dir1, 0777);
        }
        if (!is_dir(APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2)) {
            @mkdir(APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2);
            @chmod(APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2, 0777);
        }
        if (!is_dir(APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 . '/' . $dir3)) {
            @mkdir(APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 . '/' . $dir3);
            @chmod(APP_ROOT_PATH . "public/avatar/" . $dir1 . '/' . $dir2 . '/' . $dir3, 0777);
        }

        $id = str_pad($user_id, 2, "0", STR_PAD_LEFT);
        $id = substr($user_id, -2);
        $dir = APP_ROOT_PATH . "public/avatar/" . $path . "/" . $id . "avatar.jpg";

//        $main = "./public/avatar/temp/";

        $max_image_size = app_conf("MAX_IMAGE_SIZE");
        $f_img_data = array();
        $temp_arr = array();
//        $json_arr = array();
        $json_arr = (array)json_decode($img_data);
        if ($json_arr['size'] <= $max_image_size) {
            preg_match("/data:image\/(jpg|jpeg|png|gif);base64,/i", $json_arr['base64'], $res);
            $temp_arr['ext'] = $res[1];
            if (!in_array($temp_arr['ext'], array("jpg", "jpeg", "png", "gif"))) {
                $result['status'] = 0;
                $result['info'] = '上传文件格式有误';
                ajax_return($result);
            }
            $temp_arr['size'] = $json_arr['size'];
            $temp_arr['img_data'] = preg_replace("/data:image\/(jpg|jpeg|png|gif);base64,/i", "", $json_arr['base64']);
            $temp_arr['file_name'] = time() . md5(rand(0, 100)) . '.' . $temp_arr['ext'];
            $f_img_data[] = $temp_arr;
        }
        foreach ($f_img_data as $k => $v) {
            delete_avatar($user_id);
            if (file_put_contents($dir, base64_decode($v['img_data'])) === false) {
                $result['status'] = 0;
                $result['info'] = '上传文件失败';
                ajax_return($result);
            } else {
                $data['dir'] = "./public/avatar/" . $path . "/" . $id . "avatar.jpg";
                $data['user_id']=$user_id;
//                $GLOBALS['db']->query("update " . DB_PREFIX . "user set avatar = '" . $dir . "' where id = " . $user_id);
//                $data['small_url'] = get_user_avatar($user_id, "small");
//                $data['middle_url'] = get_user_avatar($user_id, "middle");
//                $data['big_url'] = get_user_avatar($user_id, "big");
            }
        }
//        $data['status'] = 1;
//        ajax_return($data);
        return $data;
    }
    function check_login(){
        global_run();
        $is_login=check_save_login();
        $data['status']=$is_login;
        if($is_login==LOGIN_STATUS_NOLOGIN){
            $data['info']="未登录";
            $data['jump']=wap_url("index","user#login");
        }
        ajax_return($data);
    }
}
