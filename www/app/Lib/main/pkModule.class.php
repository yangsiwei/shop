<?php
/**
 * @desc      
 * @author    wuqingxiang
 * @since     2016-11-07 17:27 
 */
class pkModule extends MainBaseModule
{
    /**
     * pk场首页
     */
    public function index(){
        global_run();
        init_app_page();

        $log_type = 1;
        $param['page']  = intval($_REQUEST['p']) ? intval($_REQUEST['p']) : 1;
        $param['log_type']=1;
        $data =$this->indexApi($param);
        if(isset($data['page']) && is_array($data['page'])){
            require_once APP_ROOT_PATH."app/Lib/page.php";
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();
            $GLOBALS['tmpl']->assign('pages',$p);
        }

        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("list",$data['list']);
        $GLOBALS['tmpl']->assign("log_type",$log_type);
        $GLOBALS['tmpl']->display("pk.html");
    }
    public function indexApi($config)
    {
        $root = array();

        $user_data = $GLOBALS['user_info'];
        $check_login=check_save_login();
        $page = intval($config['page']) ? intval($config['page']) : 1; //当前分页

        $page_size = app_conf("DEAL_PAGE_SIZE");

        $log_type = intval($config['log_type']);
        if ($log_type == 1)
            $log_type_condition = " and di.success_time = 0 ";
        elseif ($log_type == 2)
            $log_type_condition = " and di.has_lottery = 1 ";
        else
            $log_type_condition = "";


        $limit = (($page - 1) * $page_size) . "," . $page_size;
        if($check_login==LOGIN_STATUS_LOGINED){
            $sql = "select di.*,di.unit_price,di.has_password,di.current_buy as number,sum(doi.number) my_has_buy_number from " . DB_PREFIX . "duobao_item as di left join " . DB_PREFIX . "deal_order_item as doi  on doi.duobao_item_id=di.id and doi.user_name='" . $user_data['user_name'] . "' where di.is_pk=1 and di.is_active=1  and di.is_effect = 1 and has_lottery=0 ";
            $sql_count = "select count(distinct(di.id)) from " . DB_PREFIX . "duobao_item as di left join " . DB_PREFIX . "deal_order_item as doi  on doi.duobao_item_id=di.id and doi.user_name='" . $user_data['user_name'] . "' where di.is_pk=1 and di.is_active=1 and di.is_effect = 1 and has_lottery=0";
        }else{
            $sql = "select di.*,di.unit_price,di.has_password,di.current_buy as number,0 as my_has_buy_number from " . DB_PREFIX . "duobao_item as di left join " . DB_PREFIX . "deal_order_item as doi  on doi.duobao_item_id=di.id  where di.is_pk=1 and di.is_active=1 and di.is_effect = 1 and has_lottery=0 ";
            $sql_count = "select count(distinct(di.id)) from " . DB_PREFIX . "duobao_item as di left join " . DB_PREFIX . "deal_order_item as doi  on doi.duobao_item_id=di.id  where di.is_pk=1 and di.is_active=1 and di.is_effect = 1 and has_lottery=0";
        }
        $sql .= $log_type_condition . " group by di.id ";
        $sql_count .= $log_type_condition;

        $sql .= " order by  di.create_time desc limit " . $limit;


        $total = $GLOBALS['db']->getOne($sql_count);
        $page_total = ceil($total / $page_size);
        $root['page'] = array("page" => $page, "page_total" => $page_total, "page_size" => $page_size, "data_total" => $total);

        $res = $GLOBALS['db']->getAll($sql);
        $list = array();
        foreach ($res as $k => $v) {
            $list[$k]['id'] = $v['id'];
            $list[$k]['min_buy'] = $v['min_buy'];
            $list[$k]['unit_price']=$list['unit_price'];
            $list[$k]['name'] = $v['name'];
            $list[$k]['my_has_buy_number'] = $v['my_has_buy_number'] ? $v['my_has_buy_number'] : 0;
            $list[$k]['icon'] = get_spec_image($v['icon'], 200, 200, 1);
            $list[$k]['max_buy'] = $v['max_buy'];
            $list[$k]['less'] = $v['max_buy'] - $v['current_buy'];
            $list[$k]['number'] = $v['current_buy'];
            $list[$k]['success_time'] = $v['success_time'];
            $list[$k]['has_lottery'] = $v['has_lottery'];
            $list[$k]['progress'] = $v['progress'];
            $list[$k]['has_password'] = $v['has_password'];
            if ($v['has_lottery'] == 1) {
                $list[$k]['luck_user_id'] = $v['luck_user_id'];
                $list[$k]['luck_user_name'] = $v['luck_user_name'];
                $list[$k]['luck_user_total'] = $v['luck_user_buy_count'];
                $list[$k]['lottery_sn'] = $v['lottery_sn'];
                $list[$k]['lottery_time'] = to_date($v['lottery_time']);
            } else {
                $list[$k]['luck_user_id'] = 0;
                $list[$k]['luck_user_name'] = "--";
                $list[$k]['luck_user_total'] = "--";
                $list[$k]['lottery_sn'] = "--";
                $list[$k]['lottery_time'] = "--";
            }
        }
        $root['list'] = $list;
//        }
        return $root;
    }
    /**
     *     发起pk
     */
    public function choose_pkgoods(){
        global_run();
        init_app_page();

        $param['page']  = intval($_REQUEST['p']) ? intval($_REQUEST['p']) : 1;

         $data=$this->choose_pkgoodsApi($param);
        if(isset($data['page']) && is_array($data['page'])){
            require_once APP_ROOT_PATH."app/Lib/page.php";
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();
            $GLOBALS['tmpl']->assign('pages',$p);
        }

        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("list",$data['list']);
        $GLOBALS['tmpl']->display("pk_choose_pkgoods.html");
    }
    public function choose_pkgoodsApi($config)
    {
        $root = array();
        $page = intval($config['page']) ? intval($config['page']) : 1; //当前分页
            $page_size = app_conf("DEAL_PAGE_SIZE");
            $limit = (($page - 1) * $page_size) . "," . $page_size;
            $user_id=$GLOBALS['user_info']['id']?$GLOBALS['user_info']['id']:0;
            //查找未开启活动或者开启活动五分钟后未付款的pk计划
            $sql = "select do.id,do.name,do.unit_price,do.icon,do.max_buy,do.pk_min_number,(case when ISNULL(doi.create_time) then 1 when doi.create_time<".(NOW_TIME-5*60)." then 1 when doi.pk_user_id=".$user_id." then 1 else 0 end)as status  from " . DB_PREFIX . "duobao as do LEFT JOIN " . DB_PREFIX . "duobao_item as doi on doi.is_active=0 and doi.duobao_id=do.id and doi.progress=0 and doi.is_pk=1 where do.is_pk=1 and do.is_effect = 1 and do.current_schedule + 1 <= do.max_schedule ";
            $sql_count = "select count(distinct(do.id)) from " . DB_PREFIX . "duobao as do LEFT JOIN " . DB_PREFIX . "duobao_item as doi on doi.is_active=0 and doi.duobao_id=do.id and doi.progress=0 and doi.is_pk=1 where do.is_pk=1 and do.is_effect = 1 and do.current_schedule + 1 <= do.max_schedule ";
            $sql .= " order by  do.id desc limit " . $limit;
            $total = $GLOBALS['db']->getOne($sql_count);
            $page_total = ceil($total / $page_size);
            $root['page'] = array("page" => $page, "page_total" => $page_total, "page_size" => $page_size, "data_total" => $total);
            $res = $GLOBALS['db']->getAll($sql);
            $list = array();
            foreach ($res as $k => $v) {
                $list[$k]['id'] = $v['id'];
                $list[$k]['name'] = $v['name'];
                $list[$k]['unit_price']=$v['unit_price'];
                $list[$k]['status'] = $v['status'];
                $list[$k]['icon'] = get_spec_image($v['icon'], 200, 200, 1);
                $list[$k]['max_buy'] = $v['max_buy'];
                $list[$k]['pk_min_number'] = $v['pk_min_number'];
            }
            $root['list'] = $list;
        
        return $root;
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
        $ajax_data = $this->choose_pkgoods_doApi($param);
        if($ajax_data['status']==-1){
            $ajax_data['jump']=url("index","user#login");
        }elseif($ajax_data['status']==1){
            if(!$ajax_data['duobao_item_id']){
                $ajax_data['info']="duobao_item_id不存在";
            }else{
                $_REQUEST['data_id']=$ajax_data['duobao_item_id'];
                $this->add_cart();
            }
        }
        ajax_return($ajax_data);
    }
    public function choose_pkgoods_doApi($_config)
    {
        $config = array();
        $root = array();
        $root['status'] = 0;
        $user_login_status = check_save_login();
        $user_info = $GLOBALS['user_info'];
        $duobao_id = intval($_config['duobao_id']);
        $buyer_number = intval($_config['buyer_number']);
        $pk_password = intval($_config['pk_password']);
        if ($user_login_status != LOGIN_STATUS_LOGINED) {
            $root['status'] = -1;
            $root['info'] = "未登录";
            return $root;
        }
        //删除未激活的pk计划中未付款的购物车以及相关订单
        $deal_cart_duobao_item_id = $GLOBALS['db']->getAll("select id  from " . DB_PREFIX . "duobao_item where duobao_id = " . $duobao_id . " and  has_lottery=0 and is_active=0 and progress=0");
        //删除该用户的pk购物车
        $GLOBALS['db']->query("delete from " . DB_PREFIX . "deal_cart where user_id=" . $user_info['id'] . " and is_pk=1");
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
        if (!$duobao_id) {
            $root['info'] = 'pk计划id不存在';
            return $root;
        }
        $duobao = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "duobao where id=" . $duobao_id);
        if (!$duobao) {
            $root['info'] = 'pk计划不存在';
            return $root;
        } else {
            $max_buy = intval($duobao['max_buy']);
            if ($max_buy % $buyer_number != 0) {
                $root['info'] = '参与人数不合法';
                return $root;
            }
            if ($buyer_number < intval($duobao['pk_min_number'])) {
                $root['info'] = "参与人数大于限制人数";
                return $root;
            }
        }
        //设置配置pk场参数
        $config['duobao_id'] = $duobao_id;
        $min_buy = intval(intval($duobao['max_buy']) / $buyer_number);
        $config['min_buy'] = $min_buy;
        $config['user_max_buy'] = $min_buy;
        $config['pk_user_id'] = $user_info['id'];
        //pk活动生成，要支付完才能生效
        $config['is_effect'] = 0;
        if (!$pk_password) {
            $config['has_password'] = 0;
            $config['pk_password'] = '';
        } else {
            $config['has_password'] = 1;
            $config['pk_password'] = $pk_password;
        }

        require_once APP_ROOT_PATH . "system/model/duobao.php";
        $duobao_return = duobao::new_pk($config);
        $root['status'] = $duobao_return['status'];
        $root['info'] = $duobao_return['info'];
        if ($root['status']) {
            $duobao_item = $duobao_return['duobao_item']->duobao_item;
            $root['duobao_item_id'] = $duobao_item['id'];
            $root['buy_number'] = $duobao_item['min_buy'];
        }
        return $root;
    }

    /**
     * 添加购物车直接进行支付
     */
    public function add_cart(){
        global_run();
        $id=$_REQUEST['data_id'];
        $user_info=$GLOBALS['user_info'];
        $buy_num=$GLOBALS['db']->getOne("select min_buy from ".DB_PREFIX."duobao_item where id=".$id);
        //删除未付款的购物车以及相关订单
        $deal_cart_duobao_item_id = $GLOBALS['db']->getAll("select duobao_item_id as id from " . DB_PREFIX . "deal_cart where user_id=" . $user_info['id'] . " and is_pk=1");
        //删除该用户的pk购物车
        $GLOBALS['db']->query("delete from " . DB_PREFIX . "deal_cart where user_id=" . $user_info['id'] . " and is_pk=1");
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
        $data=$this->addcartApi(array("data_id"=>$id,"buy_num"=>$buy_num));
        if($data['status']==1){
            $ajax_data['status']=1;
            $ajax_data['jump']=url("index","pk#totalbuy_index");
        }elseif($data['status']==-1){
            $ajax_data['status']=$data['status'];
            $ajax_data['jump']=url("index","user#login");
        }else{
            $ajax_data['status']=0;
            $ajax_data['info']=$data['info']?$data['info']:$ajax_data['info'];
            $ajax_data['jump']=$data['jump']?$data['jump']:$ajax_data['jump'];
        }
        ajax_return($ajax_data);
    }
    public function addcartApi($config)
    {
        global_run();
        $root = array();
        $root['status']=0;
        $id = $config['data_id'];
        $buy_num = intval($config ['buy_num']);

        // 用户检测
        $user_info = $GLOBALS ['user_info'];
        require_once APP_ROOT_PATH . 'system/model/duobao.php';
        $duobao = new duobao ($id);
        $duobao_info = $duobao->duobao_item;
        if (empty ($duobao_info)) {
            $root['info']="夺宝项目不存在";
            return $root;
        }
        $user_login_status = check_save_login();
        if ($user_login_status != LOGIN_STATUS_LOGINED) {
            $root ['user_login_status'] = $user_login_status;
            $root['status']=-1;
            $root['info']="请先登录用户";
            return $root;
        }
        $root ['user_login_status'] = $user_login_status;

        //删除该用户的pk购物车
        $GLOBALS['db']->query("delete from " . DB_PREFIX . "deal_cart where user_id=" . $user_info['id'] . " and is_pk=1");

        // 购物车业务流程
        $res = duobao::check_pk_duobao_number($id, $buy_num, false);
        if ($res['status'] == 0) {
            $root['status']=$res['status'];
            $root['info']=$res['info'];
            return $root;
        }
        $result = $duobao->addcart_pk($user_info ['id'], $buy_num, false);
        $root['deal_cart_id'] = $result['deal_cart_id'];
        $root['buy_number'] = $buy_num;
        $root ['cart_item_num'] = $result ['cart_item_num'] ? $result ['cart_item_num'] : 0;
        $root['status']=$result['status'];
        $root['info']=$root['info'];
        return $root;
    }
    /**
     * 检查密码
     * 检查完密码进行购物车购买
     */
    public function check_password(){
        $pk_password=$_REQUEST['pk_password'];
        $is_md5=$_REQUEST['is_md5']?$_REQUEST['is_md5']:0;
        $duobao_item_id=$_REQUEST['data_id'];
        $data=array('status'=>0);
        if(!$is_md5){
            $pk_password=$pk_password;
        }
        if($duobao_item_id){
            $duobao_item=$GLOBALS['db']->getRow("select pk_password,has_password from ".DB_PREFIX."duobao_item where id=".$duobao_item_id);
            if($duobao_item){
                if($duobao_item['has_password']){
                    if($duobao_item['pk_password']==$pk_password){
                        $this->add_cart();
                    }else{
                        $data['info']="密码错误";
                    }
                }else{
                    $this->add_cart();
                }
            }else{
                $data['info']="不存在该pk活动";
            }
        }else{
            $data['info']="pk活动id不存在";
        }
        ajax_return($data);

    }
    public function pay_check()
    {
        global_run();
        init_app_page();
        require_once APP_ROOT_PATH . "system/model/deal_order.php";
        //避免重复提交
        //assign_form_verify();
        if( check_save_login() != LOGIN_STATUS_LOGINED ){
            app_redirect(url("index","user#login"));
        }

        $user_id = intval($GLOBALS['user_info']['id']);
        $user_money = $GLOBALS['user_info']['money'];

        // 获取订单信息
        $order_id = intval($_REQUEST['id']);
        $order_info = $GLOBALS['db']->getRow("select do.*, doi.duobao_item_id,doi.deal_id, doi.name deal_name, doi.number  from ".DB_PREFIX."deal_order do, ".DB_PREFIX."deal_order_item doi  where doi.order_id=do.id and do.order_status=0 and do.pay_status=0 and do.user_id = ".$user_id." and do.id=".$order_id);
        
        $range_item=$GLOBALS['db']->getRow("select unit_price,is_topspeed,is_pk,min_buy from ".DB_PREFIX."duobao_item where id =".$order_info['duobao_item_id']);
        
        // 统计几个专区 1P K区 2十夺宝币区 3百夺宝币区 4直购区 5极速区 6选号区 7一夺宝币区
        $area = array();
        if($range_item['unit_price']==10){
            $area['range_value2']=2;
        }
        if($range_item['unit_price']==1){
            $area['range_value7']=7;
        }
        if($range_item['unit_price']==100){
            $area['range_value3']=3;
        }
        if($range_item['is_topspeed']==1){
            $area['range_value5']=5;
        }
        if($range_item['is_pk']==1){
            $area['range_value1']=1;
        }
        
        // 判断失效，20分钟后失效
        $expir_time = 20 * 60 + $order_info['create_time'];
        if (NOW_TIME > $expir_time) {
            // 过期的订单，修改状态为关闭 返还库存
            cancel_order($order_info['id']);
            showErr('商品已过期，请重新下单。', 0, url("index","index#index") );
        }

        // 有效订单操作
        if ($order_info) {
            $GLOBALS['tmpl']->assign("order_info", $order_info);
            //输出支付方式
            $payment_list = load_auto_cache("cache_payment");



            $icon_paylist = array(); //用图标展示的支付方式
            $disp_paylist = array(); //特殊的支付方式(Voucher,Account,Otherpay)
            $bank_paylist = array(); //网银直连

            $wx_payment = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name = 'Wwxjspay'");
            if($wx_payment)
            {
                $wx_payment['config'] = unserialize($wx_payment['config']);
                if($wx_payment['config']['scan']==1)
                {
                    $directory = APP_ROOT_PATH."system/payment/";
                    $file = $directory. '/' .$wx_payment['class_name']."_payment.php";
                    if(file_exists($file))
                    {
                        require_once($file);
                        $payment_class = $wx_payment['class_name']."_payment";
                        $payment_object = new $payment_class();
                        $wx_payment['display_code'] = $payment_object->get_web_display_code();
                        $disp_paylist[] = $wx_payment;
                    }
                }
            }

            foreach($payment_list as $k=>$v)
            {
                if($v['class_name']=="Voucher"||$v['class_name']=="Account"||$v['class_name']=="Otherpay")
                {
                    if($v['class_name']=="Account")
                    {
                        $directory = APP_ROOT_PATH."system/payment/";
                        $file = $directory. '/' .$v['class_name']."_payment.php";
                        if(file_exists($file))
                        {
                            require_once($file);
                            $payment_class = $v['class_name']."_payment";
                            $payment_object = new $payment_class();
                            $v['display_code'] = $payment_object->get_display_code();
                        }
                    }
                    if($v['class_name']=="Voucher")
                    {
                        // 判断是否使用过红包
                        $evc = $GLOBALS['db']->getOne("select ecv_money from ".DB_PREFIX."deal_order where id = ".$order_info['id']);
                        if ($evc > 0) {
                            unset($payment_list[$k]);
                            continue;
                        }


                        $directory = APP_ROOT_PATH."system/payment/";
                        $file = $directory. '/' .$v['class_name']."_payment.php";
                        if(file_exists($file))
                        {
                            require_once($file);
                            $payment_class = $v['class_name']."_payment";
                            $payment_object = new $payment_class();
                            $v['display_code'] = $payment_object->get_display_code($area,$order_info['total_price']);
                        }

                    }

                    $disp_paylist[] = $v;
                }
                else
                {
                    if($v['is_bank']==1)
                        $bank_paylist[] = $v;
                    else
                        $icon_paylist[] = $v;
                }
            }

            //pc端支付方式后台设置默认id
            $value=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."payment where is_effect = 1 and is_default_pc = 1");
            if($value){
                $GLOBALS['tmpl']->assign("payment_id",$value);
            }

            //支付方式显示
            $GLOBALS['tmpl']->assign("icon_paylist",$icon_paylist);
            $GLOBALS['tmpl']->assign("disp_paylist",$disp_paylist);
            $GLOBALS['tmpl']->assign("bank_paylist",$bank_paylist);

            //用户信息显示
            $GLOBALS['tmpl']->assign("user_info",$GLOBALS['user_info']);


            //支付方式显示
            $GLOBALS['tmpl']->assign("show_payment",true);



            //关于短信发送的条件
            $GLOBALS['tmpl']->assign("sms_lesstime",load_sms_lesstime());
            $GLOBALS['tmpl']->assign("sms_ipcount",load_sms_ipcount());
        }else{
            $order_info['order_status'] = -1;
        }
        $GLOBALS['tmpl']->assign("is_pk",1);
        $GLOBALS['tmpl']->assign("order_info", $order_info);
        $GLOBALS['tmpl']->display("totalbuy_check.html");
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
        $data = $this->duobao_indexApi(array("data_id"=>$data_id, "dbid"=>$dbid, "page"=>$page));

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
            require_once APP_ROOT_PATH."app/Lib/page.php";
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();
            $GLOBALS['tmpl']->assign('pages',$p);
        }
        $GLOBALS['tmpl']->display("pk_show_pkgoods_status.html");
    }
    public function duobao_indexApi($config)
    {
        /*参数列表*/
        $id = intval($config['data_id']);
        $dbid = intval($config['dbid']);
        $page = intval($config['page'])?intval($config['page']):1; //当前分页

        $root = array();
        $root['status']=0;
        if($dbid)
        {
            $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where duobao_id=".$dbid." and progress < 100 order by create_time desc");
        }else{
            $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$id." and is_effect = 1");
        }
        if(empty($item_data))
        {
            $root['info']="夺宝活动已过期";
            return root;
        }
        // 和夺宝计划共用库存
        $item_data['total_buy_stock'] = $GLOBALS['db']->getOne("select total_buy_stock from ".DB_PREFIX."deal where id=".$item_data['deal_id']);

        $item_data['deal_gallery'] = unserialize($item_data['deal_gallery']);
        foreach($item_data['deal_gallery'] as $k=>$v)
        {
            $item_data['deal_gallery'][$k] = get_abs_img_root(get_spec_image($v,400,400,1));
        }
        //用户状态
        $user_login_status = check_save_login();
        if($user_login_status == LOGIN_STATUS_LOGINED){
            $user_info = $GLOBALS['user_info'];
            //用户参与的数据
            $root['user_login_status'] = $user_login_status;
        }


        //商品状态
        $duobao_status = 0;  //0 进行中  1 倒计时  2已揭晓
        if($item_data['has_lottery']==0){//未开奖
            if($item_data['success_time']==0){//未成功
                $duobao_status = 0;
            }else{
                $duobao_status=1;
            }
        }else{
            $duobao_status = 2;
        }
        $item_data['duobao_status']         = $duobao_status;
        $item_data['icon']                  = get_abs_img_root($item_data['icon']);
        $item_data['surplus_count']         = $item_data['max_buy'] - $item_data['current_buy'];
        $item_data['lottery_time_format']   = to_date($item_data['lottery_time']);
        $item_data['create_time_format']    = to_date($item_data['create_time']);
        $item_data['total_buy_price']       = format_sprintf_price($item_data['total_buy_price']);

        $item_data['click_count']++;
        $sql="update ".DB_PREFIX."duobao_item set click_count =".$item_data['click_count']." where id=".$id;
        $GLOBALS['db']->query($sql);

        //输出揭晓的相关数据
        if($duobao_status==2)
        {
            require_once APP_ROOT_PATH."system/model/user.php";
            $duobao_item_log = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($item_data['id'])." where lottery_sn = '".$item_data['lottery_sn']."' and duobao_item_id = ".$item_data['id']);
            $luck_user_info = load_user($item_data['luck_user_id']);

            $duobao_item_log['user_name'] = $luck_user_info['user_name'];
            $duobao_item_log['user_logo'] = $luck_user_info['user_logo'];
            $duobao_item_log['avatar'] = get_abs_img_root(get_muser_avatar($luck_user_info['user_id'],"big"))?get_abs_img_root(get_muser_avatar($luck_user_info['user_id'],"big")):"";

            $duobao_item_log['user_total'] = $item_data['luck_user_buy_count'];
            $item_data['luck_lottery'] = $duobao_item_log;
        }

        //倒计时
        if($duobao_status==1)
        {
            $item_data['now_time'] = NOW_TIME;
        }

        if($duobao_status!=0)
        {
            $root['next_id'] = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."duobao_item where duobao_id = ".$item_data['duobao_id']." and success_time = 0");
        }

        //我的参与
        $root['my_duobao_log'] = $GLOBALS['db']->getAll("select lottery_sn from ".duobao_item_log_table($item_data)." where user_id = ".intval($GLOBALS['user_info']['id'])." and duobao_item_id = ".$item_data['id']);
        $root['my_duobao_count'] = count($root['my_duobao_log']);

        //所有参与记录
        $page_size = PAGE_SIZE;
        $limit = (($page - 1) * $page_size) . "," . $page_size;
        $order_list_sql = "select doi.*,u.user_name,u.avatar,u.user_logo from ".DB_PREFIX."deal_order_item as doi left join ".DB_PREFIX."user as u on u.id = doi.user_id where doi.duobao_item_id = ".$item_data['id']." and doi.lottery_sn=0 and doi.refund_status = 0 and doi.pay_status = 2  and doi.lottery_sn_send=1 order by doi.create_time desc limit ".$limit;

        $root['duobao_order_list'] = $GLOBALS['db']->getAll($order_list_sql);


        $total = $GLOBALS['db']->getOne("select count(doi.id) from ".DB_PREFIX."deal_order_item as doi where doi.duobao_item_id = ".$item_data['id']." and doi.refund_status = 0 and doi.pay_status = 2 and doi.lottery_sn_send=1");

        $page_total = ceil($total/$page_size);
        $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$total);

        foreach($root['duobao_order_list'] as $k=>$v)
        {
            $root['duobao_order_list'][$k]['f_create_time'] = to_date($v['create_time']);
            $root['duobao_order_list'][$k]['avatar'] = get_abs_img_root(get_user_avatar($v['user_id'], "small"));
        }
        //购物车数据
        $root['cart_data'] = array();
        if($user_info){
            require_once APP_ROOT_PATH.'system/model/duobao.php';
            $root['cart_data'] = duobao::getcart($user_info['id']);
        }

        $root['item_data'] = $item_data;
        $root['page_title'].="奖品详情";

        return $root;
    }
    public function done(){
        global_run();
        require_once APP_ROOT_PATH . "system/model/cart.php";
        require_once APP_ROOT_PATH . "system/model/deal_order.php";
        $cart_result = load_pk_cart_list_by_totalbuy();

        // 验证购物车
        $goods_item = $cart_result['cart_data'];
        $ajax = 1;
        //check_form_verify();

//        if($cart_result['cart_data']['is_fictitious'] != 1){
//            $consignee_id = intval($_REQUEST['consignee_id']);
//            if ($consignee_id <= 0) {
//                showErr('请选择收货地址', $ajax);
//            }
//        }

        // 验证登录
        if( check_save_login() != LOGIN_STATUS_LOGINED )
        {
            showErr($GLOBALS['lang']['PLEASE_LOGIN_FIRST'], $ajax,url("index","user#login"));
        }

        if (! $goods_item) {
            showErr($GLOBALS['lang']['CART_EMPTY_TIP'],$ajax,url("index"));
        }

        require_once APP_ROOT_PATH . "system/model/duobao.php";
        // 检测库存
        $res = duobao::check_pk_duobao_number($goods_item['duobao_item_id'],0,true);
        if($res['status']==0){
            showErr($res['info'],$ajax);
        }


        $user_id = $GLOBALS ['user_info'] ['id'];

//        if($cart_result['cart_data']['is_fictitious'] != 1){
//            // 获取用户地址
//            $consignee_info = $GLOBALS ['db']->getRow ( "select * from " . DB_PREFIX . "user_consignee where id=".$consignee_id." and user_id=" . $user_id );
//            if (!$consignee_info) {
//                showErr('收货地址不存在', $ajax);
//            }
//        }

//        $region_conf   = load_auto_cache ( "delivery_region" );
//        $region_lv1    = intval ( $consignee_info ['region_lv1'] );
//        $region_lv2    = intval ( $consignee_info ['region_lv2'] );
//        $region_lv3    = intval ( $consignee_info ['region_lv3'] );
//        $region_lv4    = intval ( $consignee_info ['region_lv4'] );
//        $region_info   = $region_conf [$region_lv1] ['name'] . " " . $region_conf [$region_lv2] ['name'] . " " . $region_conf [$region_lv3] ['name'] . " " . $region_conf [$region_lv4] ['name'];

        // 开始生成订单
        $now                       = NOW_TIME;
        $order ['type']            = 2; // 夺宝订单
        $order ['user_id']         = $user_id;
        $order ['create_time']     = $now;
        $order ['update_time']     = $now;
        $order ['total_price']     = $goods_item['total_price']; // 应付总额 商品价 - 会员折扣 + 运费

        // + 支付手续费
        $order ['pay_amount']          = 0;
        $order ['pay_status']          = 0; // 新单都为零， 等下面的流程同步订单状态
        $order ['delivery_status']     = 0;
        $order ['order_status']        = 0; // 新单都为零， 等下面的流程同步订单状态
        $order ['return_total_score']  = $goods_item ['return_total_score']; // 结单后送的积分
        $order ['memo']                = '';

        // 地址待定
//        $order ['region_info']     = $region_info;
//        $order ['address']         = strim ( $consignee_info ['address'] );
//        $order ['mobile']          = strim ( $consignee_info ['mobile'] );
//        $order ['consignee']       = strim ( $consignee_info ['consignee'] );
//        $order ['zip']             = strim ( $consignee_info ['zip'] );

        $order ['ecv_money']       = 0;
        $order ['account_money']   = 0;
        $order ['ecv_sn']          = '';

        $order ['payment_id']  = 0;
        $order ['bank_id']     = "";

        // 更新来路
        $order ['referer']     = $GLOBALS ['referer'];
        $user_info             = es_session::get ( "user_info" );
        $order ['user_name']   = $user_info ['user_name'];

        $order ['duobao_ip']   = CLIENT_IP;

        require_once APP_ROOT_PATH . "system/extend/ip.php";
        $ip                    = new iplocate ();
        $area                  = $ip->getaddress ( CLIENT_IP );
        $order ['duobao_area'] = $area ['area1'];

        $order['create_date_ymd']  = to_date(NOW_TIME,"Y-m-d");
        $order['create_date_ym']   = to_date(NOW_TIME,"Y-m");
        $order['create_date_y']    = to_date(NOW_TIME,"Y");
        $order['create_date_m']    = to_date(NOW_TIME,"m");
        $order['create_date_d']    = to_date(NOW_TIME,"d");

        do {
            $order ['order_sn'] = to_date ( NOW_TIME, "Ymdhis" ) . rand ( 10, 99 );
            $GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order", $order, 'INSERT', '', 'SILENT' );
            $order_id = intval ( $GLOBALS ['db']->insert_id () );
        } while ( $order_id == 0 );

        // 生成订单商品
        $goods_item['id']                       = '';
        $goods_item['delivery_status']          = 0;
        $goods_item['order_sn']                 = $order['order_sn'];
        $goods_item['order_id']                 = $order_id;
        $goods_item['is_arrival']               = 0;
        $goods_item['user_id']                  = $user_id;
        $goods_item['duobao_ip']                = $order['duobao_ip'];
        $goods_item['duobao_area']              = $order['duobao_area'];
        $goods_item['type']                     = $order['type'];
        $goods_item['create_time']              = NOW_TIME;
        $goods_item['create_date_ymd']          = to_date(NOW_TIME,"Y-m-d");
        $goods_item['create_date_ym']           = to_date(NOW_TIME,"Y-m");
        $goods_item['create_date_y']            = to_date(NOW_TIME,"Y");
        $goods_item['create_date_m']            = to_date(NOW_TIME,"m");
        $goods_item['create_date_d']            = to_date(NOW_TIME,"d");

//        $goods_item['consignee']                = strim ( $consignee_info ['consignee'] );
//        $goods_item['mobile']                   = strim ( $consignee_info ['mobile'] );
//        $goods_item['region_info']              = $region_info;
//        $goods_item['address']                  = strim ( $consignee_info ['address'] );
//        $goods_item['is_set_consignee']         = 0;


        $GLOBALS ['db']->autoExecute ( DB_PREFIX . "deal_order_item", $goods_item, 'INSERT', '', 'SILENT' );

        // 删除pk商品
//        $GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "deal_cart where  is_pk=1 and session_id = '" . es_session::id () . "'" );

        $return_data['status'] = 1;
        $return_data['is_pk']=1;
        $return_data['jump'] = url("index","pk#pay_check",array("id"=>$order_id));
        ajax_return($return_data);

    }
    public function totalbuy_index()
    {
        global_run();
        init_app_page();
        //避免重复提交
        //assign_form_verify();

        if( check_save_login() != LOGIN_STATUS_LOGINED )
        {
            app_redirect(url("index","user#login"));
        }

        $user_id = intval($GLOBALS['user_info']['id']);
        $user_money = $GLOBALS['user_info']['money'];

        require_once APP_ROOT_PATH."system/model/cart.php";
        $cart_result = load_pk_cart_list_by_totalbuy();
        //输出所有配送方式
        $consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id = ".$user_id);
        foreach($consignee_list as $k=>$v){
            $consignee_info=load_auto_cache("consignee_info",array("consignee_id"=>$v['id']));
            $consignee_list[$k]['del_url']    = url('index','uc_consignee#del',array('id'=>$v['id']));
            $consignee_list[$k]['dfurl']      = url('index','uc_consignee#set_default',array('id'=>$v['id']));
            $consignee_list[$k]['region_lv2'] = $consignee_info['consignee_info']['region_lv2_name'];
            $consignee_list[$k]['region_lv3'] = $consignee_info['consignee_info']['region_lv3_name'];
            $consignee_list[$k]['region_lv4'] = $consignee_info['consignee_info']['region_lv4_name'];
        }

        $GLOBALS['tmpl']->assign("consignee_list", $consignee_list);
        $GLOBALS['tmpl']->assign("is_pk",1);
        $GLOBALS['tmpl']->assign("count_consignee", count($consignee_list));
        $GLOBALS['tmpl']->assign("cart_result", $cart_result);
        $GLOBALS['tmpl']->assign("user_money",$user_money);
        $GLOBALS['tmpl']->display("totalbuy.html");
    }
    public function pay_done(){
        global_run();
        init_app_page();
        require_once APP_ROOT_PATH . "system/model/deal_order.php";

        $ajax = 1;
        if( check_save_login() != LOGIN_STATUS_LOGINED ){
            app_redirect(url("index","user#login"));
        }
        $user_info         = $GLOBALS['user_info'];
        $order_id          = intval($_REQUEST['id']);
        $payment           = intval ( $_REQUEST['payment'] );
        $account_money     = floatval($_REQUEST['account_money']);
        $all_account_money = intval($_REQUEST['all_account_money']);

        $ecvsn         = $_REQUEST['ecvsn'] ? strim ( $_REQUEST['ecvsn'] ) : '';
        $ecvpassword   = $_REQUEST['ecvpassword'] ? strim ( $_REQUEST['ecvpassword'] ) : '';
        $memo          = strim ( $_REQUEST['content'] );

        $GLOBALS['db']->query("UPDATE `".DB_PREFIX."deal_order` SET `payment_id`={$payment} WHERE id={$order_id}");
        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where type=2 and id = ".$order_id);

        if (!$order_info) {
            showErr('支付订单不存，请重新下单。', $ajax, url("index","index#index") );
        }

        // 更新订单表
        // 开始验证订单接交信息
        require_once APP_ROOT_PATH."system/model/cart.php";
        $data = count_buy_totalbuy ( $payment, $account_money, $all_account_money, $ecvsn, $ecvpassword, $order_info, $order_info['account_money'], $order_info['ecv_money'], '' );

        if( round($data['pay_price'],4)>0&&!$data['payment_info'] )
        {
            showErr($GLOBALS['lang']['PLEASE_SELECT_PAYMENT'], $ajax);
        }

        // 检查避免重复提交
        //check_form_verify();

        $deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where order_id = ".$order_id);

        // 判断失效，20分钟后失效
        $expir_time = 20 * 60 + $order_info['create_time'];
        if (NOW_TIME > $expir_time) {
            // 过期的订单，修改状态为关闭 返还库存
            cancel_totalbuy_order($order_info);
            showErr('商品已过期，请重新下单。', 0, url("index","index#index") );
        }

        // 判断是否使用过红包
        $evc = $GLOBALS['db']->getOne("select ecv_money from ".DB_PREFIX."deal_order where id = ".$order_info['id']);
        if ($evc == 0) {
            // 1. 代金券支付
            $ecv_data = $data ['ecv_data'];
            if ($ecv_data) {
                $ecv_payment_id = $GLOBALS ['db']->getOne ( "select id from " . DB_PREFIX . "payment where class_name = 'Voucher'" );
                if ($ecv_data ['money'] > $order_info ['total_price'])
                    $ecv_data ['money'] = $order_info ['total_price'];
                $payment_notice_id = make_payment_notice ( $ecv_data ['money'],'', $order_id, $ecv_payment_id, "", $ecv_data ['id'] );
                require_once APP_ROOT_PATH . "system/payment/Voucher_payment.php";
                $voucher_payment = new Voucher_payment ();
                $voucher_payment->direct_pay ( $ecv_data ['sn'], $ecv_data ['password'], $payment_notice_id );
            }
        }

        // 2. 余额支付
        $account_money = $data ['account_money'];
        if (floatval ( $account_money ) > 0) {
            $account_payment_id = $GLOBALS ['db']->getOne ( "select id from " . DB_PREFIX . "payment where class_name = 'Account'" );
            $payment_notice_id = make_payment_notice ( $account_money,'', $order_id, $account_payment_id );
            require_once APP_ROOT_PATH . "system/payment/Account_payment.php";
            $account_payment = new Account_payment ();
            $account_payment->get_payment_code ( $payment_notice_id );
        }

        //3. 相应的支付接口
        $payment_info = $data['payment_info'];
        if($payment_info&&$data['pay_price']>0)
        {
            $payment_notice_id = make_payment_notice($data['pay_price'],'',$order_id,$payment_info['id']);
            //创建支付接口的付款单
        }

        $rs = order_paid($order_id);
        $duobao_item=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$deal_order_item['duobao_item_id']);
        $duobao_item_id=$deal_order_item['duobao_item_id'];
        if($rs)
        {
            $data = array();
            $data['info'] = "";
            $data['status']=1;
            $data['jump'] = url("index","payment#done",array("id"=>$order_id));
            $GLOBALS['db']->query("update " . DB_PREFIX . "duobao_item set is_active=1 where id=".$duobao_item_id);
            if($duobao_item['progress']!=100)
            $GLOBALS['db']->query("update ".DB_PREFIX."duobao set is_effect=0 where id=".$deal_order_item['duobao_id']);
            $GLOBALS ['db']->query ( "delete from " . DB_PREFIX . "deal_cart where  is_pk=1 and user_id =".$user_info['id']." and duobao_item_id=".$duobao_item_id);
            if($duobao_item['has_password']==1)
                send_msg($user_info['id'], "您开启".$duobao_item['name']."的pk活动,pk密码为".$duobao_item['pk_password'], "notify");
            ajax_return($data); //支付成功
        }
        else
        {
            $data = array();
            $data['info'] = "";
            $data['jump'] = url("index","payment#pay",array("id"=>$payment_notice_id));
            ajax_return($data);
        }
    }

}