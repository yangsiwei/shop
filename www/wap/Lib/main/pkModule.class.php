<?php
class pkModule extends MainBaseModule
{
    /**
     * pk场首页
     */
    public function index(){
        global_run();
        init_app_page();
        $per = $this->check_pknum();
        if(!$per){
            $GLOBALS['tmpl']->assign("per",1);
        }
        //等级限制
        $consume_level = $GLOBALS['db']->getOne("select pk from ".DB_PREFIX."consume_level where id =1");
        $user_level = $GLOBALS['user_info']['level_id'];
        $url = $_SERVER['HTTP_REFERER'];
        if($user_level<$consume_level){
            echo "<script>window.location.href='".$url."'</script>";
            die;
        }

        $log_type = $param['log_type'] = intval($_REQUEST['log_type'])?intval($_REQUEST['log_type']):1;
        $param['page']  = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;


        $data = call_api_core("pk","index", $param);

//        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
//            app_redirect(wap_url("index","user#login"));
//        }

        if(isset($data['page']) && is_array($data['page'])){
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();
            $GLOBALS['tmpl']->assign('pages',$p);
        }

        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("list",$data['list']);
        $GLOBALS['tmpl']->assign("log_type",$log_type);
        $GLOBALS['tmpl']->display("pk.html");
    }


   /**
     * 检查PK入场次数
     */
    public function check_pknum(){
        global_run();
        $user_login_status = check_save_login();
        if ($user_login_status != LOGIN_STATUS_LOGINED) {
            echo "<SCRIPT LANGUAGE='javascript'>alert('您还没有登录！');</SCRIPT><meta http-equiv='refresh' content='0;url=index.php'>";exit;
        }
        $sql = 'select num from '.DB_PREFIX.'pkapply WHERE uid='.$GLOBALS['user_info']['id'].' and num>0';
        $res = $GLOBALS['db']->getOne($sql);
        return $res;
    }



/**
     * pk场申请卡密
     */
    public  function getcamilo(){
        global_run();
        init_app_page();
        $uid = $GLOBALS['user_info']['id'];
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $sql = 'select camilo,num from '.DB_PREFIX.'pkapply WHERE uid='.$GLOBALS['user_info']['id'].' and num>0';
            $res = $GLOBALS['db']->getRow($sql);
            if($res){
                $data['info'] = '您还有次数没用完！卡密为：'.$res['camilo'];
                $data['status'] = 0;
                ajax_return($data);
            }
            $a= $this->check_time();
            if($a==1 && $result==1){
                $data['info'] = '抱歉,一天只能请求一次';
                $data['status'] = 0;
                ajax_return($data);
            }
            $res = $GLOBALS['db']->getRow('select camilo,num from '.DB_PREFIX.'camilo where is_delete=0');
            $map = array('camilo'=>$res['camilo'],'uid'=>$uid,'create_time'=>time(),'num'=>$res['num']);
            $result = $GLOBALS['db']->autoExecute(DB_PREFIX."pkapply",$map,'INSERT');
            if ($result){
                $data['info'] = $res;
                $data['status'] = 1;
                $result ==1;
            }else{
                $data['info'] = '对不起，您的等级不够';
                $data['status'] =0;
            }
            ajax_return($data);
        }

    }


  /**
     * pk场每天申请卡密次数限制
     */
     public function check_time(){
        $allowTime = 24*3600;
        if (empty($_COOKIE['stime'])){
            setcookie("stime",time(),time()+24*3600);
        }elseif (time() - $_COOKIE['stime']>$allowTime ||date('H:i:s',time()) =='24:00:00'){
            $_COOKIE['stime'] = time();
        }else{
            return 1;
        }
    }

    /**
     * 展示场次状态进行中和最新揭晓
     */
    public function record(){
        global_run();
        init_app_page();
        $log_type = $param['log_type'] = intval($_REQUEST['log_type'])?intval($_REQUEST['log_type']):1;
        $param['page']  = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;


        $data = call_api_core("pk","record", $param);

        if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
            app_redirect(wap_url("index","user#login"));
        }

        if(isset($data['page']) && is_array($data['page'])){
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();
            $GLOBALS['tmpl']->assign('pages',$p);
        }

        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("list",$data['list']);
        $GLOBALS['tmpl']->assign("log_type",$log_type);
        $GLOBALS['tmpl']->display("pk_record.html");
    }

    /**
     *     发起pk
     */
    public function choose_pkgoods(){
        global_run();
        init_app_page();
       $per = $this->check_pknum();
        if(!$per){
            app_redirect(wap_url("index","pk"));
        }
        $param['page']  = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;


        $data = call_api_core("pk","choose_pkgoods", $param);

        if(isset($data['page']) && is_array($data['page'])){
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();
            $GLOBALS['tmpl']->assign('pages',$p);
        }

        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("list",$data['list']);
        $GLOBALS['tmpl']->display("pk_choose_pkgoods.html");
    }

    /**
     * 处理发起pk操作
     */
    public function choose_pkgoods_do(){
        global_run();
        $param=array();
        $user_login_status=check_save_login();
        if ($user_login_status != LOGIN_STATUS_LOGINED) {
            $root['status'] = -1;
            $root['info'] = "未登录";
            return $root;
        }
        $param['pk_password']=$_REQUEST['pk_password'];
        $param['duobao_id']=$_REQUEST['duobao_id'];
        $param['buyer_number']=$_REQUEST['buyer_number'];
        if(!$_REQUEST['duobao_id']){
            ajax_return(array("status"=>0,"info"=>"pk计划的id不存在"));
        }
        $ajax_data = call_api_core("pk","choose_pkgoods_do", $param);
        if($ajax_data['status']==-1){
            $ajax_data['jump']=wap_url("index","user#login");
        }elseif($ajax_data['status']==1){
            if(!$ajax_data['duobao_item_id']){
                $ajax_data['info']="duobao_item_id不存在";
            }else{
                $data=call_api_core("pk","addcart",array("data_id"=>$ajax_data['duobao_item_id'],"buy_num"=>$ajax_data['buy_number']));
                if($data['status']==1){
                    $this->check_cart($data['deal_cart_id'],$data['buy_number']);
                }elseif($data['status']==-1){
                    $ajax_data['status']=$data['status'];
                    $ajax_data['jump']=wap_url("index","user#login");
                }else{
                    $ajax_data['status']=0;
                    $ajax_data['info']=$data['info']?$data['info']:$ajax_data['info'];
                    $ajax_data['jump']=$data['jump']?$data['jump']:$ajax_data['jump'];
                }
            }
        }
        ajax_return($ajax_data);
    }

    /**
     * 添加购物车直接进行支付
     */
    public function add_cart($check_password){
        if(!$check_password){
            ajax_return(array("status"=>0,"info"=>"密码错误"));
        }
        $duobao_item_id=$_REQUEST['data_id'];
        $buy_num=$GLOBALS['db']->getOne("select min_buy from ".DB_PREFIX."duobao_item where id=".$duobao_item_id);
        $data=call_api_core("pk","addcart",array("data_id"=>$duobao_item_id,"buy_num"=>$buy_num));
        if($data['status']==1){
            $this->check_cart($data['deal_cart_id'],$data['buy_number']);
        }elseif($data['status']==-1){
            $ajax_data['status']=$data['status'];
            $ajax_data['jump']=wap_url("index","user#login");
        }else{
            $ajax_data['status']=0;
            $ajax_data['info']=$data['info']?$data['info']:$ajax_data['info'];
            $ajax_data['jump']=$data['jump']?$data['jump']:$ajax_data['jump'];
        }
        ajax_return($ajax_data);
    }

    /**
     * 检查密码
     * 检查完密码进行购物车购买
     */
    public function check_password(){
    	$per = $this->check_pknum();
        if(!$per){
            app_redirect(wap_url("index","pk"));
        }
        $pk_password=$_REQUEST['pk_password'];
        $is_md5=$_REQUEST['is_md5']?$_REQUEST['is_md5']:0;
        $duobao_item_id=$_REQUEST['data_id'];
        if(!$duobao_item_id){
            ajax_return(array('status'=>0,'info'=>'夺宝活动id不存在'));
        }
        $data=array('status'=>0);
        if(!$is_md5){
            $pk_password=$pk_password;
        }
        if($duobao_item_id){
            $duobao_item=$GLOBALS['db']->getRow("select pk_password,has_password from ".DB_PREFIX."duobao_item where id=".$duobao_item_id);
            if($duobao_item){
                if($duobao_item['has_password']){
                    if($duobao_item['pk_password']==$pk_password){
                       $this->add_cart(true);
                    }else{
                        $data['info']="注意密码";
                    }
                }else{
                    $this->add_cart(true);
                }
            }else{
                $data['info']="不存在该pk活动";
            }
        }else{
            $data['info']="pk活动id不存在";
        }
        ajax_return($data);

    }
    public function check_cart($deal_cart_id,$buy_number)
    {
        global_run();

        $num = array();
        $deal_cart_id=$deal_cart_id;
        $num[$deal_cart_id]=$buy_number;

        $mobile = strim($_REQUEST['mobile']);
        $sms_verify = strim($_REQUEST['sms_verify']);

        $data = call_api_core("pk","check_cart",array("num"=>$num, "mobile"=>$mobile,"sms_verify"=>$sms_verify));

        if($data['status'])
        {
            $ajaxdata['jump'] = wap_url("index","pk#check",array("deal_cart_id"=>$deal_cart_id));
            $ajaxdata['status'] = 1;
            ajax_return($ajaxdata);
        }
        elseif($data['status']==-1)
        {
            $ajaxdata['status'] = -1;
            $ajaxdata['info'] = $data['info'];
            $ajaxdata['jump'] = wap_url("index","user#login");
            ajax_return($ajaxdata);
        }
        else
        {
            $ajaxdata['status'] = 0;
            $ajaxdata['info'] = $data['info'];
            $ajaxdata['expire_ids'] = $data['expire_ids']?$data['expire_ids']:array();
            ajax_return($ajaxdata);
        }
    }
    public function check()
    {
        global_run();
        init_app_page();
        //避免重复提交
        //assign_form_verify();
        $deal_cart_id=$_REQUEST['deal_cart_id'];
        $data = call_api_core("pk","check",array('deal_cart_id'=>$deal_cart_id));
        $data['cencel_url'] = wap_url("index");
        if(!$GLOBALS['is_weixin'])
        {
            foreach($data['payment_list'] as $k=>$v)
            {
                if($v['code']=="Wwxjspay")
                {
                    unset($data['payment_list'][$k]);
                }
                if (!empty($v['logo'])){
                    $data['payment_list'][$k]['logo']=$v['logo'];
                }
            }
        }
        if($data['status']==-1)
        {
            app_redirect(wap_url("index","user#login"));
        }

        if(empty($data['cart_list']))
        {
            app_redirect(wap_url("index"));
        }
        //wap端默认支付id
        $value=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."payment where is_default_wap = 1");
        if($value){
            $GLOBALS['tmpl']->assign("payment_id",$value);
        }
        $account_id=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."payment where is_default_wap = 1 and is_effect = 1 and class_name = 'Account'");
        $GLOBALS['tmpl']->assign("account_id",$account_id);
        $data['is_pk']=1;
        $account_amount = round($GLOBALS['user_info']['money'],2);
        $GLOBALS['tmpl']->assign("account_amount",$account_amount);
        $GLOBALS['tmpl']->assign("data",$data);

        $GLOBALS['tmpl']->display("cart_check.html");
    }
    /**
     * pk商品的详细状态
     */
    public function show_pkgoods_status(){
        global_run();
        init_app_page();

        //获取参数
        $data_id = intval($_REQUEST['data_id']);
        $dbid = intval($_REQUEST['dbid']);
        $page = intval($_REQUEST['page']);
        //请求接口
        $data = call_api_core("duobao","index",array("data_id"=>$data_id, "dbid"=>$dbid, "page"=>$page));

        if(empty($data['item_data']))
        {
            showErr($data['info']);
        }

        $cart_conf_json = array(
            "max_buy"=>$data['item_data']['max_buy'],
            "min_buy"=>$data['item_data']['min_buy'],
            "current_buy"=>$data['item_data']['current_buy'],
            "residue_count"=>($data['item_data']['max_buy']-$data['item_data']['current_buy'])
        );

        $GLOBALS['tmpl']->assign("cart_data_json",  json_encode($data['cart_data']));
        $GLOBALS['tmpl']->assign("cart_conf_json",  json_encode($cart_conf_json));
        $GLOBALS['tmpl']->assign("item_data",$data['item_data']);
        $GLOBALS['tmpl']->assign("data",$data);

        if(isset($data['page']) && is_array($data['page'])){
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();
            $GLOBALS['tmpl']->assign('pages',$p);
        }
        $GLOBALS['tmpl']->display("pk_show_pkgoods_status.html");
    }


public function update_num(){
        global_run();
        $uid= $GLOBALS['user_info']['id'];
        $sql='update '.DB_PREFIX.'pkapply set num=num-1 where uid='.$uid.' and num>0';
        $res = $GLOBALS['db']->query($sql);
        return $res;
    }
    
    public function done()
    {
        global_run();

        $param['ecvsn'] = $_REQUEST['ecvsn']?addslashes(trim($_REQUEST['ecvsn'])):'';
        $param['ecvpassword'] = $_REQUEST['ecvpassword']?addslashes(trim($_REQUEST['ecvpassword'])):'';
        $param['payment'] = intval($_REQUEST['payment']);
        $param['all_account_money'] = intval($_REQUEST['all_account_money']);
        $param['content'] = strim($_REQUEST['content']);
        //check_form_verify();
        $data = call_api_core("pk","done",$param);
        $ajaxobj['is_app'] = $data['is_app'];
        $ajaxobj['order_id'] = $data['order_id'];
        // $ajaxobj['reload_url'] = SITE_DOMAIN.wap_url("index","cart#order",array("id"=>$data['order_id']));
        $ajaxobj['reload_url'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id']));
        $ajaxobj['success_url'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id'], 'is_done'=>1));
        
       if($data['status']==1){
        $res = $this->update_num();
        if(!$res){
            $ajaxobj['status'] = 0;
            $ajaxobj['info'] = '卡密次数使用失败！';
            ajax_return($ajaxobj);
        }}
        if($data['status']==-1)
        {
            $ajaxobj['status'] = 1;
            $ajaxobj['jump'] = wap_url("index","user#login");
            ajax_return($ajaxobj);
        }
        elseif($data['status']==1)
        {
            $ajaxobj['status'] = 1;
            $ajaxobj['jump'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id']));

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
}