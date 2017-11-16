<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class ajaxModule extends MainBaseModule
{
	/**
	 * 发送手机验证码
	 */
	public function send_sms_code()
	{
		$verify_code = strim($_REQUEST['verify_code']);
		$mobile_phone = strim($_REQUEST['mobile']);
		$account = intval($_REQUEST['account']);
		$no_verify = intval($_REQUEST['no_verify']); //是否图形验证
		$no_verify = 0;
		$get_password = intval($_REQUEST['get_password']); //取回密码用
		if($account==1)
		{
			global_run();
			$mobile_phone = $GLOBALS['user_info']['mobile'];
			if($mobile_phone=="")
			{
				$data['status'] = false;
				$data['info'] = "请先绑定手机号";
				$data['jump'] = url("index","uc_account");
				$data['field'] = "user_mobile";
				ajax_return($data);
			}
		}
		if($mobile_phone=="")
		{
			$data['status'] = false;
			$data['info'] = "请输入手机号";
			$data['field'] = "user_mobile";
			ajax_return($data);
		}
		if(!check_mobile($mobile_phone))
		{
			$data['status'] = false;
			$data['info'] = "手机号格式不正确";
			$data['field'] = "user_mobile";
			ajax_return($data);
		}

		if(intval($_REQUEST['unique'])==1)
		{
			if(intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile = '".$mobile_phone."'"))>0)
			{
				$data['status'] = false;
				$data['info'] = "手机号已被注册";
				$data['field'] = "user_mobile";
				ajax_return($data);
			}
		}

		if($get_password==1)
		{
			$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where mobile = '".$mobile_phone."'");
			if(!$user_data)
			{
				$data['status'] = false;
				$data['info'] = "手机号未在本站注册过";
				$data['field'] = "user_mobile";
				ajax_return($data);
			}
		}


		$sms_ipcount = load_sms_ipcount();
		if($sms_ipcount>1&&$no_verify==0)
		{
			//需要图形验证码
			if(es_session::get("verify")!=md5($verify_code))
			{
				$data['status'] = false;
				$data['info'] = "图形验证码错误";
				$data['field'] = "verify_code";
				es_session::delete("verify");
				ajax_return($data);
			}
			es_session::delete("verify");
		}

		if(!check_ipop_limit(CLIENT_IP, "send_sms_code",SMS_TIMESPAN))
		{
			showErr("请勿频繁发送短信",1);
		}



		//删除失效验证码
		$sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
		$GLOBALS['db']->query($sql);

		$mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$mobile_phone."'");
		if($mobile_data)
		{
			//重新发送未失效的验证码
			$code = $mobile_data['code'];
			$mobile_data['add_time'] = NOW_TIME;
			$GLOBALS['db']->query("update ".DB_PREFIX."sms_mobile_verify set add_time = '".$mobile_data['add_time']."',send_count = send_count + 1 where mobile_phone = '".$mobile_phone."'");
		}
		else
		{
			$code = rand(100000,999999);
			$mobile_data['mobile_phone'] = $mobile_phone;
			$mobile_data['add_time'] = NOW_TIME;
			$mobile_data['code'] = $code;
			$mobile_data['ip'] = CLIENT_IP;
			$GLOBALS['db']->autoExecute(DB_PREFIX."sms_mobile_verify",$mobile_data,"INSERT","","SILENT");

		}
		if($get_password==1)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."user set password_verify = '".$code."' where id = ".$user_data['id']);
		}
		send_verify_sms($mobile_phone,$code);
		es_session::delete("verify"); //删除图形验证码
		$data['status'] = true;
		$data['info'] = "发送成功";
		$data['lesstime'] = SMS_TIMESPAN -(NOW_TIME - $mobile_data['add_time']);  //剩余时间
		$data['sms_ipcount'] = load_sms_ipcount();
		ajax_return($data);


	}


	/**
	 * 验证会员字段
	 */
	public function check_field()
	{
		$field = strim($_REQUEST['field']);
		$value = strim($_REQUEST['value']);
		$user_id = intval($_REQUEST['user_id']);

		$data = check_field($field, $value, $user_id);
		ajax_return($data);

	}



	public function check_login_status()
	{
		global_run();
		if(check_save_login()==LOGIN_STATUS_NOLOGIN)
			$result['status'] = 0;
		else
			$result['status'] = 1;
		ajax_return($result);
	}



	/**
	 * 加载购物车中的配送地区
	 */
	public function load_consignee()
	{
		global_run();
		$consignee_id = intval($_REQUEST['id']);
		$order_id = intval($_REQUEST['order_id']);
		if($order_id)
		  $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id." and is_delete = 0 and user_id =".intval($GLOBALS['user_info']['id']));

		if($consignee_id>0)
		{
			$consignee_data = load_auto_cache("consignee_info",array("consignee_id"=>$consignee_id));
			$consignee_info = $consignee_data['consignee_info'];
			$region_lv1 = $consignee_data['region_lv1'];
			$region_lv2 = $consignee_data['region_lv2'];
			$region_lv3 = $consignee_data['region_lv3'];
			$region_lv4 = $consignee_data['region_lv4'];
			$GLOBALS['tmpl']->assign("region_lv1",$region_lv1);
			$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
			$GLOBALS['tmpl']->assign("region_lv3",$region_lv3);
			$GLOBALS['tmpl']->assign("region_lv4",$region_lv4);
			$GLOBALS['tmpl']->assign("consignee_info",$consignee_info);
		}
		elseif($order_info)
		{
			//关于订单的地区输出

			$consignee_data['consignee_info'] = $order_info;
			$region_lv1 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."delivery_region where pid = 0");  //一级地址
			foreach($region_lv1 as $k=>$v)
			{
				if($v['id'] == $order_info['region_lv1'])
				{
					$region_lv1[$k]['selected'] = 1;
					break;
				}
			}
			$consignee_data['region_lv1'] = $region_lv1;

			$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."delivery_region where pid = ".$order_info['region_lv1']);  //二级地址
			foreach($region_lv2 as $k=>$v)
			{
				if($v['id'] == $order_info['region_lv2'])
				{
					$region_lv2[$k]['selected'] = 1;
					break;
				}
			}
			$consignee_data['region_lv2'] = $region_lv2;

			$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."delivery_region where pid = ".$order_info['region_lv2']);  //三级地址
			foreach($region_lv3 as $k=>$v)
			{
				if($v['id'] == $order_info['region_lv3'])
				{
					$region_lv3[$k]['selected'] = 1;
					break;
				}
			}
			$consignee_data['region_lv3'] = $region_lv3;

			$region_lv4 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."delivery_region where pid = ".$order_info['region_lv3']);  //四级地址
			foreach($region_lv4 as $k=>$v)
			{
				if($v['id'] == $order_info['region_lv4'])
				{
					$region_lv4[$k]['selected'] = 1;
					break;
				}
			}
			$consignee_data['region_lv4'] = $region_lv4;

			$region_lv1 = $consignee_data['region_lv1'];
			$region_lv2 = $consignee_data['region_lv2'];
			$region_lv3 = $consignee_data['region_lv3'];
			$region_lv4 = $consignee_data['region_lv4'];
			$GLOBALS['tmpl']->assign("region_lv1",$region_lv1);
			$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
			$GLOBALS['tmpl']->assign("region_lv3",$region_lv3);
			$GLOBALS['tmpl']->assign("region_lv4",$region_lv4);
			unset($order_info['id']);
			$GLOBALS['tmpl']->assign("consignee_info",$order_info);
		}
		else
		{
			$region_lv1 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."delivery_region where pid = 0");  //一级地址
			$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."delivery_region where pid = 1");  //二级地址
			$GLOBALS['tmpl']->assign("region_lv1",$region_lv1);
			$GLOBALS['tmpl']->assign("region_lv2",$region_lv2);
		}

		$data['html'] = $GLOBALS['tmpl']->fetch("inc/cart_consignee.html");
		ajax_return($data);
	}


	public function load_cart_count()
	{
		global_run();
		require_once APP_ROOT_PATH."system/model/cart.php";
		$result = load_cart_list();
		$data['cart_count'] = $result['total_data']['cart_item_number'];
		ajax_return($data);
	}

	public function load_cart_list()
	{
		global_run();
		require_once APP_ROOT_PATH."system/model/cart.php";
		$result = load_cart_list();
		$GLOBALS['tmpl']->assign("result_list",$result);
		$data['html'] = $GLOBALS['tmpl']->fetch("inc/cart_tip_list.html");
		ajax_return($data);
	}

	//删除指定的购物车项
	public function del_cart()
	{
		global_run();
		if(isset($_REQUEST['id']))
		{
			$id = intval($_REQUEST['id']);
			$cart_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_cart where id = ".$id);
			$sql = "delete from ".DB_PREFIX."deal_cart  where  id = ".$id;
		}
		else
		{
			$sql = "delete from ".DB_PREFIX."deal_cart  where user_id=".intval($GLOBALS['user_info']['id']);
		}
		$GLOBALS['db']->query($sql);
		$op_result = $GLOBALS['db']->affected_rows();
		if($cart_item)
		{
			$GLOBALS['db']->query("delete from ".DB_PREFIX."deal_cart  where pid = '".$cart_item['deal_id']."'");
		}

		require_once APP_ROOT_PATH."system/model/cart.php";

		if($op_result>0)
		{
			$result = load_cart_list(true);  //重新刷新购物车

			$GLOBALS['tmpl']->assign("result_list",$result);
			$data['html'] = $GLOBALS['tmpl']->fetch("inc/cart_tip_list.html");

			$data['cart_count'] = $result['total_data']['cart_item_number'];

			ajax_return(array("status"=>1,"data"=>$data));
		}
		else
		{
			ajax_return(array("status"=>0));
		}

	}

	public function get_lottery_info()
	{

		$id = intval($_REQUEST['id']);
		$sql = "SELECT
                	DuobaoItem.id AS id, DuobaoItem.current_buy AS current_buy, DuobaoItem.lottery_sn AS lottery_sn,DuobaoItem.lottery_time AS lottery_time, DuobaoItem.luck_user_id,
                	USER.user_name AS user_name ,USER.avatar AS avatar
                FROM
                	".DB_PREFIX."duobao_item DuobaoItem
                    LEFT JOIN ".DB_PREFIX."user USER ON USER .id = DuobaoItem.luck_user_id
		            WHERE
		            DuobaoItem.id =". $id;
		$lottery_info=$GLOBALS['db']->getRow($sql);
		$GLOBALS['tmpl']->assign("lottery_info",$lottery_info);
		$GLOBALS['tmpl']->assign("now_time",NOW_TIME);

		$html=$GLOBALS['tmpl']->fetch("inc/lottery_info.html");
		$result['status']=1;
		$result['html']=$html;

		ajax_return($result);

	}
	public function get_lottery_info_anno()
	{

		$id = intval($_REQUEST['id']);
		$sql = "SELECT
                	DuobaoItem.id AS id, DuobaoItem.current_buy AS current_buy, DuobaoItem.lottery_sn AS lottery_sn,DuobaoItem.lottery_time AS lottery_time, DuobaoItem.luck_user_id,
                	USER.user_name AS user_name ,USER.avatar AS avatar
                FROM
                	".DB_PREFIX."duobao_item DuobaoItem
                    LEFT JOIN ".DB_PREFIX."user USER ON USER .id = DuobaoItem.luck_user_id
		            WHERE
		            DuobaoItem.id =". $id;
		$lottery_info_anno=$GLOBALS['db']->getRow($sql);
		$GLOBALS['tmpl']->assign("lottery_info_anno",$lottery_info_anno);
		$GLOBALS['tmpl']->assign("now_time",NOW_TIME);

		$html=$GLOBALS['tmpl']->fetch("inc/lottery_info_anno.html");
		$result['status']=1;
		$result['html']=$html;
		ajax_return($result);

	}
	
	// 直购加入购物车
	public function add_total_cart()
	{
	
	    global_run();
	    $id = intval($_REQUEST['data_id']);
	    $buy_num = intval($_REQUEST['buy_num']);
	    //用户检测
	    $user_info = $GLOBALS['user_info'];
	
	    require_once APP_ROOT_PATH.'system/model/duobao.php';
	    $duobao = new duobao($id);
	    $duobao_info = $duobao->duobao_item;
	
	    if(empty($duobao_info)){
	        $result['status']=0;
	        $result['info']="夺宝项目不存在";
	        ajax_return($result);
	    }
	
	    if(!$user_info){
	
	        $result['status']=-1;
	        $result['info']="请先登录用户";
	        ajax_return($result);
	    }
	
	    
	    //购物车业务流程
	    if ($_REQUEST['update'] == 1) {
	        $cart_list = $duobao->add_cart_total_buy($user_info['id'], $buy_num, 1);
	    }else{
	        $cart_list = $duobao->add_cart_total_buy($user_info['id'], $buy_num);
	    }
	    
	    if ($cart_list['status'] == 0) {
	        $result['status'] = 0;
	        $result['info']   = $cart_list['info'];
	    }else{
	        $result['cart_item'] = $cart_list['cart_item']?$cart_list['cart_item']:0;
	        $result['status']=1;
	        $result['info']="添加成功";
	    }
	    
	    
	    ajax_return($result);
	
	
	}

	public function addcart()
	{

		global_run();
		$id = intval($_REQUEST['data_id']);
		$buy_num = intval($_REQUEST['buy_num']);

		//用户检测
		$user_info = $GLOBALS['user_info'];

		require_once APP_ROOT_PATH.'system/model/duobao.php';
		$duobao = new duobao($id);
		$duobao_info = $duobao->duobao_item;

		if(empty($duobao_info)){

			$result['status']=0;
			$result['info']="夺宝项目不存在";
			ajax_return($result);
		}

		if(!$user_info){

			$result['status']=-1;
			$result['info']="请先登录用户";
			ajax_return($result);
		}

		$res = duobao::check_duobao_number($id, $buy_num, false);
		if($res['status']==0)
		{
			ajax_return($res);
		}
		//购物车业务流程

		$cart_list=$duobao->addcart($user_info['id'], $buy_num,false);
		$result['cart_item_num'] = $cart_list['cart_item_num']?$cart_list['cart_item_num']:0;
		if($duobao_info['is_coupons']==1){
		    
		    $result['free_url']=url("index","cart#coupons_cart");
		}		
		
		$result['status']=1;
		$result['info']="添加成功";
		ajax_return($result);


	}
	public function count_buy_total(){
	    global_run();
	    require_once APP_ROOT_PATH."system/model/cart.php";

	    $account_money =  floatval($_REQUEST['account_money']); //余额
	    $ecvsn = $_REQUEST['ecvsn']?addslashes(trim($_REQUEST['ecvsn'])):'';
	    $ecvpassword = $_REQUEST['ecvpassword']?addslashes(trim($_REQUEST['ecvpassword'])):'';
	    $payment = intval($_REQUEST['payment']);
	    $all_account_money = intval($_REQUEST['all_account_money']);
	    $bank_id = strim(trim($_REQUEST['bank_id']));
	    $order_id = intval($_REQUEST['id']);
	    $paid_account_money = 0;
	    $user_id = intval($GLOBALS['user_info']['id']);
	    $session_id = es_session::id();
	    if($order_id){
	        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
	        $goods_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order_item where order_id = ".$order_id);
	        $paid_account_money = $order_info['pay_amount'];
	    }else{
	        $cart_result = load_cart_list();
	        $goods_list = $cart_result['cart_list'];
	    }


	    $result = count_buy_total($payment,$account_money,$all_account_money,$ecvsn,$ecvpassword,$goods_list,$paid_account_money,0,$bank_id);

	    $GLOBALS['tmpl']->assign("result",$result);
	    $html = $GLOBALS['tmpl']->fetch("inc/cart_total.html");
	    $data = $result;
	    $data['html'] = $html;
	    $data['expire'] = empty($goods_list)?true:false;
	    if($data['expire'])$data['jump'] = url("index","cart");
	    ajax_return($data);
	}
	
	public function count_buy_totalbuy(){
	    global_run();
	    require_once APP_ROOT_PATH."system/model/cart.php";
	
	    $account_money         =  floatval($_REQUEST['account_money']); //余额
	    $ecvsn                 = $_REQUEST['ecvsn']?addslashes(trim($_REQUEST['ecvsn'])):'';
	    $ecvpassword           = $_REQUEST['ecvpassword']?addslashes(trim($_REQUEST['ecvpassword'])):'';
	    $payment               = intval($_REQUEST['payment']);
	    $all_account_money     = intval($_REQUEST['all_account_money']);
	    $bank_id               = strim(trim($_REQUEST['bank_id']));
	    $order_id              = intval($_REQUEST['id']);
	    $paid_account_money    = 0;
	    $user_id               = intval($GLOBALS['user_info']['id']);
	    $session_id            = es_session::id();
	    
        $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
       
        $paid_account_money = $order_info['pay_amount'];
	    
	
		
	    $result = count_buy_totalbuy($payment,$account_money,$all_account_money,$ecvsn,$ecvpassword,$order_info,$paid_account_money,0,$bank_id);
	
	    $GLOBALS['tmpl']->assign("result",$result);
	    $html = $GLOBALS['tmpl']->fetch("inc/cart_total.html");
	    $data = $result;
	    $data['html'] = $html;
	    $data['expire'] = empty($goods_list)?true:false;
	    if($data['expire'])$data['jump'] = url("index","cart");
	    ajax_return($data);
	}

	public function my_no_all()
	{

		global_run();

		$user_data = $GLOBALS['user_info'];
		$user_id = intval($_REQUEST['user_id']);
		$order_item_id = intval($_REQUEST['order_item_id']);
		if($user_id==0)
		{
			$user_id   = intval($GLOBALS['user_info']['id']);
		}

		$duobao_item_id = intval($_REQUEST['id']);

		$data['duobao_item'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$duobao_item_id);

		require_once APP_ROOT_PATH."system/model/duobao.php";
		$list = duobao::get_user_no_all(array("user_id"=>$user_id,"duobao_item"=>$data['duobao_item'],"order_item_id"=>$order_item_id));
		$data['duobao_count']=0;
		foreach ($list as $key => $value) {
			$data['duobao_count']+=count($value['list']);
		}

		$data['list'] = $list;

		$GLOBALS['tmpl']->assign("data",$data);
		$GLOBALS['tmpl']->assign("list",$data['list']);

		$html=$GLOBALS['tmpl']->fetch("inc/my_no_all.html");
		$data['status']=1;
		$data['html']=$html;
		ajax_return($data);

	}

	public function uc_luck_confirm_address(){
	    global_run();
	    if(check_save_login()==LOGIN_STATUS_NOLOGIN){
	        $result['status'] = 1000;
	        ajax_return($result);
	    }

	    $consignee_id = intval($_REQUEST['consignee_id']);
	    $order_item_id = intval($_REQUEST['order_item_id']);
	    //验证地址是否存在
	    $consignee_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where id=".$consignee_id." and user_id=".$GLOBALS['user_info']['id']);
	    if(!$consignee_info){
	        $result['status'] = 0;
	        $result['info'] = "数据错误请重新选择";
	        ajax_return($result);
	    }

	    $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_item",array("is_set_consignee"=>1),"UPDATE"," id =".$order_item_id." and user_id = ".$GLOBALS['user_info']['id']);
	    if ($GLOBALS['db']->affected_rows()){
	        //更新订单中的地址
	        $order_id = $GLOBALS['db']->getOne("select order_id from ".DB_PREFIX."deal_order_item where id =".$order_item_id);
	        update_order_consignee($order_id,$consignee_info);
	        $result['status'] = 1;
	    }else{
	        $result['status'] = 0;
	        $result['info'] ="数据错误请重新选择";
	        ajax_return($result);
	    }

	    ajax_return($result);
	}

	public function uc_share(){
	    global_run();
	    require_once APP_ROOT_PATH.'app/Lib/page.php';
		require_once APP_ROOT_PATH."system/model/share.php";

		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
		    //超出
		    $result['status'] = 1000;
		    ajax_return($result);
		}


		$page = intval($_REQUEST['page']);
		if($page==0)$page = 1;
		$step = intval($_REQUEST['step']);
		$step_size = intval($_REQUEST['step_size']);
		$limit = (($page - 1)*PIN_PAGE_SIZE + ($step - 1)*PIN_SECTOR).",".PIN_SECTOR;
		if($step==0||$step>$step_size)
		{
			//超出
			$result['doms'] = array();
			$result['step'] = 0;
			$result['status'] = 0;
			$result['info'] = 'end';
			ajax_return($result);
		}

		$excondition = " user_id= ".$GLOBALS['user_info']['id'];
		$share = new share();
		$result_list = $share->get_share_list($limit,$excondition);

		$result_list = $result_list['list'];
		if($result_list)
		{
			$result['doms'] = array();
			foreach($result_list as $k=>$v)
			{
				$GLOBALS['tmpl']->assign("row",$v);
				$result['doms'][] = $GLOBALS['tmpl']->fetch("inc/share_pin_box.html");
			}

			if($step==0||$step>=$step_size)
			{
				//超出
				$result['step'] = 0;
				$result['status'] = 0;
				$result['info'] = 'end';
				ajax_return($result);
			}
			else
			{
				$result['status'] = 1;
				$result['step'] = $step + 1;
				$result['info'] = 'next';
				ajax_return($result);
			}

		}
		else
		{
			$result['doms'] = array();
			$result['step'] = 0;
			$result['status'] = 0;
			$result['info'] = 'end';
			//			$result['sql'] = $sql;
			ajax_return($result);
		}
	}

	public function home_share(){
	    global_run();
	    require_once APP_ROOT_PATH.'app/Lib/page.php';
	    require_once APP_ROOT_PATH."system/model/share.php";

	    $id = intval($_REQUEST['id']);


	    $page = intval($_REQUEST['page']);
	    if($page==0)$page = 1;
	    $step = intval($_REQUEST['step']);
	    $step_size = intval($_REQUEST['step_size']);
	    $limit = (($page - 1)*PIN_PAGE_SIZE + ($step - 1)*PIN_SECTOR).",".PIN_SECTOR;
	    if($step==0||$step>$step_size)
	    {
	        //超出
	        $result['doms'] = array();
	        $result['step'] = 0;
	        $result['status'] = 0;
	        $result['info'] = 'end';
	        ajax_return($result);
	    }

	    $excondition = " is_effect=1 and user_id= ".$id;
	    $share = new share();
	    $result_list = $share->get_share_list($limit,$excondition);

	    $result_list = $result_list['list'];
	    if($result_list)
	    {
	        $result['doms'] = array();
	        foreach($result_list as $k=>$v)
	        {
	            $GLOBALS['tmpl']->assign("row",$v);
	            $result['doms'][] = $GLOBALS['tmpl']->fetch("inc/share_pin_box.html");
	        }

	        if($step==0||$step>=$step_size)
	        {
	            //超出
	            $result['step'] = 0;
	            $result['status'] = 0;
	            $result['info'] = 'end';
	            ajax_return($result);
	        }
	        else
	        {
	            $result['status'] = 1;
	            $result['step'] = $step + 1;
	            $result['info'] = 'next';
	            ajax_return($result);
	        }

	    }
	    else
	    {
	        $result['doms'] = array();
	        $result['step'] = 0;
	        $result['status'] = 0;
	        $result['info'] = 'end';
	        //			$result['sql'] = $sql;
	        ajax_return($result);
	    }
	}

	public function share(){

	    global_run();
	    require_once APP_ROOT_PATH.'app/Lib/page.php';
	    require_once APP_ROOT_PATH."system/model/share.php";

	    $page = intval($_REQUEST['page']);
	    if($page==0)$page = 1;
	    $step = intval($_REQUEST['step']);
	    $step_size = intval($_REQUEST['step_size']);
	    $limit = (($page - 1)*PIN_PAGE_SIZE + ($step - 1)*PIN_SECTOR).",".PIN_SECTOR;

	    if($step==0||$step>$step_size)
	    {
	        //超出
	        $result['doms'] = array();
	        $result['step'] = 0;
	        $result['status'] = 0;
	        $result['info'] = 'end';
	        ajax_return($result);
	    }

	    $excondition = ' is_effect=1 ';
	    $share = new share();
	    $result_list = $share->get_share_list($limit,$excondition);

	    $result_list = $result_list['list'];
	    if($result_list)
	    {
	        $result['doms'] = array();
	        foreach($result_list as $k=>$v)
	        {
	            $GLOBALS['tmpl']->assign("row",$v);
	            $result['doms'][] = $GLOBALS['tmpl']->fetch("inc/pin_box.html");
	        }

	        if($step==0||$step>=$step_size)
	        {
	            //超出
	            $result['step'] = 0;
	            $result['status'] = 0;
	            $result['info'] = 'end';
	            ajax_return($result);
	        }
	        else
	        {

	            $result['status'] = 1;
	            $result['step'] = $step + 1;
	            $result['info'] = 'next';
	            ajax_return($result);
	        }

	    }
	    else
	    {
	        $result['doms'] = array();
	        $result['step'] = 0;
	        $result['status'] = 0;
	        $result['info'] = 'end';
	        //			$result['sql'] = $sql;
	        ajax_return($result);
	    }
	}
	
	public function check_payment_notice()
	{
	    $id = intval($_REQUEST['notice_id']);
	    $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$id);
	    if($payment_notice['is_paid']==1)
	    {
	        $data['status'] = 1;
	    }
	    else
	    {
	        $data['status'] = 0;
	    }
	    ajax_return($data);
	}

}
?>
