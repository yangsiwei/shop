<?php
 class number_chooseModule extends MainBaseModule{
     public function index(){
         global_run();
         init_app_page();

         $consume_level = $GLOBALS['db']->getOne("select choose from ".DB_PREFIX."consume_level where id =1");
         $user_level = $GLOBALS['user_info']['level_id'];
         $url = $_SERVER['HTTP_REFERER'];
         if($user_level<$consume_level){
             echo "<script>window.location.href='".$url."'</script>";
             die;
         }

         $log_type = $param['log_type'] = intval($_REQUEST['log_type'])?intval($_REQUEST['log_type']):1;
         $param['page']  = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;


         $data = call_api_core("number_choose","index", $param);
         if(isset($data['page']) && is_array($data['page'])){
             $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
             $p  =  $page->show();
             $GLOBALS['tmpl']->assign('pages',$p);
         }

         $GLOBALS['tmpl']->assign("data",$data);
         $GLOBALS['tmpl']->assign("list",$data['list']);
         $GLOBALS['tmpl']->assign("log_type",$log_type);
         $GLOBALS['tmpl']->display("number_choose_index.html");
     }
     public function select(){
         global_run();
         init_app_page();

         $consume_level = $GLOBALS['db']->getOne("select choose from ".DB_PREFIX."consume_level where id =1");
         $user_level = $GLOBALS['user_info']['level_id'];
         $url = $_SERVER['HTTP_REFERER'];
         if($user_level<$consume_level){
             echo "<script>window.location.href='".$url."'</script>";
             die;
         }

         $param['id']=intval($_REQUEST['data_id']);//duobao_item的id
         $param['page']  = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
         $data = call_api_core("number_choose","select", $param);

         if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
             app_redirect(wap_url("index","user#login"));
         }
         if(isset($data['page']) && is_array($data['page'])){
             $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
             $p  =  $page->show();
             $GLOBALS['tmpl']->assign('pages',$p);
         }
         $GLOBALS['tmpl']->assign("data",$data);
         $GLOBALS['tmpl']->display("number_choose_select.html");
     }

     public function check_cart($deal_cart_id,$buy_number)
     {
         global_run();

         $num = array();
         $deal_cart_id=$deal_cart_id;
         $num[$deal_cart_id]=$buy_number;

         $mobile = strim($_REQUEST['mobile']);
         $sms_verify = strim($_REQUEST['sms_verify']);

         $data = call_api_core("number_choose","check_cart",array("num"=>$num, "mobile"=>$mobile,"sms_verify"=>$sms_verify));
         if($data['status'])
         {
             $ajaxdata['jump'] = wap_url("index","number_choose#check",array("deal_cart_id"=>$deal_cart_id));
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
         $data = call_api_core("number_choose","check",array('deal_cart_id'=>$deal_cart_id));
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
         $data['is_number_choose']=1;
         $account_amount = round($GLOBALS['user_info']['money'],2);
         $GLOBALS['tmpl']->assign("account_amount",$account_amount);
         $GLOBALS['tmpl']->assign("data",$data);

         $GLOBALS['tmpl']->display("cart_check.html");
     }
     public function add_cart(){
         global_run();
         $user_info=$GLOBALS['user_info'];
         $data_id = intval($_REQUEST['data_id']);
         $buy_num = intval($_REQUEST['buy_num']);
         $choose_num=$_REQUEST['choose_number'];
         if($_REQUEST['is_all_choose']==1){
             $un_choose_number=$_REQUEST['un_choose_number'];
             if(!$un_choose_number){
                 $un_choose_number=0;
             }
             $choose_number=$GLOBALS['db']->getCol("select lottery_sn from ".DB_PREFIX."duobao_item_log where duobao_item_id=".$data_id." and user_id=0 and lottery_sn not in (".$un_choose_number.") order by lottery_sn ");
             $buy_num=count($choose_number);
             $choose_num=implode(",",$choose_number);
         }
         if(!$choose_num){
             ajax_return(array('status'=>0,"info"=>'未选择号码'));
         }
         //删除未付款的购物车以及相关订单
         $deal_cart_duobao_item_id = $GLOBALS['db']->getAll("select duobao_item_id as id from " . DB_PREFIX . "deal_cart where user_id=" . $user_info['id'] . " and is_number_choose=1");
         //删除该用户的pk购物车
         $GLOBALS['db']->query("delete from " . DB_PREFIX . "deal_cart where user_id=" . $user_info['id'] . " and is_number_choose=1");
         if ($deal_cart_duobao_item_id) {
             $duobao_item_id = array();
             foreach ($deal_cart_duobao_item_id as $val) {
                 $duobao_item_id[] = $val['id'];
             }
             if ($duobao_item_id) {
                 require_once APP_ROOT_PATH . "system/model/deal_order.php";
                 $order_info = $GLOBALS['db']->getAll("select do.id from " . DB_PREFIX . "deal_order as do where do.pay_status=0 and do.type=2 and do.id in (select order_id from " . DB_PREFIX . "deal_order_item where duobao_item_id in(" . implode($duobao_item_id) . ") and user_id=" . $user_info['id'] . ")");
                 foreach ($order_info as $val) {
                     cancel_order($val['id']);
                     del_order($val['id']);
                 }
             }
         }
         $data = call_api_core("cart","addcart",array("data_id"=>$data_id,"buy_num"=>$buy_num,"choose_number"=>$choose_num));

         $ajax_data['status'] = $data['status'];
         $ajax_data['info'] = $data['info'];
         $ajax_data['cart_item_num'] = $data['cart_item_num'];
         if($data['status']==1&&!$data['is_number_choose'])
         {
             $ajax_data['jump'] = wap_url("index","cart");
         }
         elseif($data['status']==-1&&!$data['is_number_choose'])
         {
             $ajax_data['jump'] = wap_url("index","user#login");
         }
         elseif($data['is_number_choose']){
             $this->check_cart($data['deal_cart_id'],$data['buy_number']);
         }
         ajax_return($ajax_data);
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
         $data = call_api_core("number_choose","done",$param);

         $ajaxobj['is_app'] = $data['is_app'];
         $ajaxobj['order_id'] = $data['order_id'];
         // $ajaxobj['reload_url'] = SITE_DOMAIN.wap_url("index","cart#order",array("id"=>$data['order_id']));
         $ajaxobj['reload_url'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id']));
         $ajaxobj['success_url'] = SITE_DOMAIN.wap_url("index","payment#done",array("id"=>$data['order_id'], 'is_done'=>1));
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