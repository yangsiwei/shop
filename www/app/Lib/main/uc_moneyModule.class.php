<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


require_once APP_ROOT_PATH.'system/model/user.php';
class uc_moneyModule extends MainBaseModule
{
	public function index()
	{
		 app_redirect(url("index","uc_money#incharge"));
	}
	
	/**
	 * 提现
	 */
	public function withdraw()
	{
		global_run();
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}
		init_app_page();
		$user_info = $GLOBALS['user_info'];
		//取出等级信息
		$level_data = load_auto_cache("cache_user_level");
		$cur_level = $level_data[$user_info['level_id']];
		 
		//游标移动获取下一个等级
		reset($level_data);
		do{
			$current_data = current($level_data);
			 
			if($current_data['id']==$cur_level['id'])
			{
			  
				$next_data = next($level_data);
				break;
			}
		}while(next($level_data));
		$uc_query_data = array();
		$uc_query_data['cur_level'] = $cur_level['level']; //当前等级
		$uc_query_data['cur_point'] = $user_info['point'];
		$uc_query_data['cur_level_name'] = $cur_level['name'];
		if($next_data){
			$uc_query_data['next_level'] = $next_data['id'];
			$uc_query_data['next_point'] =$next_data['point'] - $user_info['point']; //我再增加：100 经验值，就可以升级为：青铜五
			$uc_query_data['next_level_name'] = $next_data['name'];
		}
		 
		 
		$uc_query_data['cur_score'] = $user_info['score'];

		$uc_query_data['cur_gourp'] = $cur_group['id'];
		$uc_query_data['cur_gourp_name'] = $cur_group['name'];
		$uc_query_data['cur_discount'] = floatval(sprintf('%.2f', $cur_group['discount']*10));
		
		$GLOBALS['tmpl']->assign("uc_query_data",$uc_query_data);
		
		$GLOBALS['tmpl']->assign("sms_lesstime",load_sms_lesstime());
		$GLOBALS['tmpl']->assign("sms_ipcount",load_sms_ipcount());
                
		//取出已绑定的银行卡
		$user_bank_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bank where user_id = ".$user_info['id']);
                $f_bank_list = array();

                if($user_bank_list){
                    foreach ($user_bank_list as $k=>$v){
                        $tmp_bank_name = strripos($v['bank_name'], "银行")?substr($v['bank_name'],  0,strripos($v['bank_name'], "银行")+6):$v['bank_name'];
                        $user_bank_list[$k]['show_bank_name'] = $tmp_bank_name." （...".  substr($v['bank_account'], -4)."）";
                        $user_bank_list[$k]['bank_account'] = substr($v['bank_account'], 0,4)."****".substr($v['bank_account'], -4);
                    }
                }
                $GLOBALS['tmpl']->assign("user_bank_list",$user_bank_list);
                //用户真实名称
                $GLOBALS['tmpl']->assign("real_name",$user_info['real_name']);
                
                
		require_once APP_ROOT_PATH."system/model/user_center.php";
		require_once APP_ROOT_PATH."app/Lib/page.php";
		//输出充值订单
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*app_conf("PAGE_SIZE")).",".app_conf("PAGE_SIZE");
		
		$result = get_user_withdraw($limit,$GLOBALS['user_info']['id']);
		
		$GLOBALS['tmpl']->assign("list",$result['list']);
		$page = new Page($result['count'],app_conf("PAGE_SIZE"));   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		//通用模版参数定义
		assign_uc_nav_list();//左侧导航菜单
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		$GLOBALS['tmpl']->assign("page_title","会员提现"); //title
		$GLOBALS['tmpl']->display("uc/uc_money_withdraw.html"); //title
	} 
	
	public function del_withdraw()
	{
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
	
	
	public function withdraw_done()
	{
		global_run();
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			$data['status'] = 1000;
			ajax_return($data);
		}
		
		$is_bind = intval($_REQUEST['is_bind']);
		$money = floatval($_REQUEST['money']);
		$mobile = $GLOBALS['user_info']['mobile'];
        $user_bank_id = intval($_REQUEST['user_bank_id']);

		
		$sms_verify = strim($_REQUEST['sms_verify']);
 
                if($user_bank_id){//数据库中查询银行信息
                    $bank_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_bank where id=".$user_bank_id." and user_id =".$GLOBALS['user_info']['id']);
                    if($bank_info){
                        $bank_name = $bank_info['bank_name'];
                        $bank_account = $bank_info['bank_account'];;
                        $bank_user = $bank_info['bank_user'];
                    }else{
                        $data['status'] = 0;
                        $data['info'] = "银行数据错误，请选择其他银行或新卡提现";
                        ajax_return($data);
                    }
                    
                }else{//表单数据
                    $bank_name = strim($_REQUEST['bank_name']);
                    $bank_account = strim($_REQUEST['bank_account']);
                    $bank_user = strim($_REQUEST['bank_user']);
                    
                    if($bank_name=="")
                    {
                            $data['status'] = 0;
                            $data['info'] = "请输入开户行全称";
                            ajax_return($data);
                    }
                    if($bank_account=="")
                    {
                            $data['status'] = 0;
                            $data['info'] = "请输入开户行账号";
                            ajax_return($data);
                    }
                    if($bank_user=="")
                    {
                            $data['status'] = 0;
                            $data['info'] = "请输入开户人真实姓名";
                            ajax_return($data);
                    }
                }
		
		if($money<=0)
		{
			$data['status'] = 0;
			$data['info'] = "请输入正确的提现金额";
			ajax_return($data);
		}
		
		if(app_conf("SMS_ON")==1)
		{
			if($mobile=="")
			{
				$data['status'] = 0;
				$data['info'] = "请先完善会员的手机号码";
				$data['jump'] = url("index","uc_account");
				ajax_return($data);
			}
			
			
			
		
			if($sms_verify=="")
			{
				$data['status'] = 0;
				$data['info']	=	"请输入收到的验证码";
				ajax_return($data);
			}
		
			//短信码验证
			$sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
			$GLOBALS['db']->query($sql);
		
			$mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$mobile."'");
		
			if($mobile_data['code']!=$sms_verify)
			{
				$data['status'] = 1;
				$data['info']	=  "验证码错误";
				ajax_return($data);
			}
		
			
		}
		
		$submitted_money = floatval($GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."withdraw where user_id = ".$GLOBALS['user_info']['id']." and is_delete = 0 and is_paid = 0"));
		if($submitted_money+$money>$GLOBALS['user_info']['money'])
		{
			$data['status'] = 0;
			$data['info'] = "提现超额";
			ajax_return($data);
		}
		
		$withdraw_data = array();
		$withdraw_data['user_id'] = $GLOBALS['user_info']['id'];
		$withdraw_data['money'] = $money;
		$withdraw_data['create_time'] = NOW_TIME;
		$withdraw_data['bank_name'] = $bank_name;
		$withdraw_data['bank_account'] = $bank_account;
		$withdraw_data['bank_user'] = $bank_user;
                $withdraw_data['is_bind'] = $is_bind;
                $withdraw_data['bank_mobile'] = $mobile;
		$GLOBALS['db']->autoExecute(DB_PREFIX."withdraw",$withdraw_data);
		
		$GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$mobile."'");
		$data['status'] = 1;
		$data['info'] = "提现申请提交成功，请等待审核";
		ajax_return($data);
	}
    
    /**
     * 充值
     */
	public function incharge()
	{
	    global_run();
	    if(check_save_login()!=LOGIN_STATUS_LOGINED)
	    {
	        app_redirect(url("index","user#login"));
	    }
	    init_app_page();

	    //删除未支付的充值订单
        $GLOBALS['db']->query("delete from ".DB_PREFIX."deal_order where pay_status = 0 and type = 1");
        $GLOBALS['db']->query("delete from ".DB_PREFIX."payment_notice where is_paid = 0 and pay_time = 0");

	    $user_info = $GLOBALS['user_info'];
	    //取出等级信息
	    $level_data = load_auto_cache("cache_user_level");
	    $cur_level = $level_data[$user_info['level_id']];
		
	    //游标移动获取下一个等级
	    reset($level_data);
	    do{
	    	$current_data = current($level_data);
	    	 
	    	if($current_data['id']==$cur_level['id'])
	    	{
	    
	    		$next_data = next($level_data);
	    		break;
	    	}
	    }while(next($level_data));
	    
	    $uc_query_data = array();
	    $uc_query_data['cur_level'] = $cur_level['level']; //当前等级
	    $uc_query_data['cur_point'] = $user_info['point'];
	    $uc_query_data['cur_level_name'] = $cur_level['name'];
	    if($next_data){
	    	$uc_query_data['next_level'] = $next_data['id'];
	    	$uc_query_data['next_point'] =$next_data['point'] - $user_info['point']; //我再增加：100 经验值，就可以升级为：青铜五
	    	$uc_query_data['next_level_name'] = $next_data['name'];
	    }
	    
	    
	    $uc_query_data['cur_score'] = $user_info['score'];

	    $uc_query_data['cur_gourp'] = $cur_group['id'];
	    $uc_query_data['cur_gourp_name'] = $cur_group['name'];
	    $uc_query_data['cur_discount'] = floatval(sprintf('%.2f', $cur_group['discount']*10));

	    $GLOBALS['tmpl']->assign("uc_query_data",$uc_query_data);
	    
	    
	    //输出支付方式
		$payment_list = load_auto_cache("cache_payment");
		$icon_paylist = array(); //用图标展示的支付方式
		//$disp_paylist = array(); //特殊的支付方式(Voucher,Account,Otherpay)
		$bank_paylist = array(); //网银直连
		
		
		foreach($payment_list as $k=>$v)
		{
			if($v['class_name']=="Voucher"||$v['class_name']=="Account"||$v['class_name']=="Otherpay"||$v['class_name']=="tenpayc2c")
			{
				//$disp_paylist[] = $v;
			}
			else
			{
				if($v['class_name']=="Alipay")
				{
					$cfg = unserialize($v['config']);
					if($cfg['alipay_service']==2)
					{
						if($v['is_bank']==1)
							$bank_paylist[] = $v;
						else
							$icon_paylist[] = $v;
					}
				}
				else
				{
					if($v['is_bank']==1)
					$bank_paylist[] = $v;
					else
					$icon_paylist[] = $v;
				}
			}
		}

		
		
		
	
		$GLOBALS['tmpl']->assign("icon_paylist",$icon_paylist);
		//$GLOBALS['tmpl']->assign("disp_paylist",$disp_paylist);
		$GLOBALS['tmpl']->assign("bank_paylist",$bank_paylist);
		
		require_once APP_ROOT_PATH."system/model/user_center.php";
		require_once APP_ROOT_PATH."app/Lib/page.php";
		//输出充值订单
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*app_conf("PAGE_SIZE")).",".app_conf("PAGE_SIZE");


		//判断是否首冲
		$total_money = $GLOBALS['user_info']['total_money'];
		if($total_money<=0){
			$GLOBALS['tmpl']->assign("first_pay",true);
		}


		$result = get_user_incharge($limit,$GLOBALS['user_info']['id']);
		$GLOBALS['tmpl']->assign("list",$result['list']);
		$page = new Page($result['count'],app_conf("PAGE_SIZE"));   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);

		$user_info = $GLOBALS['user_info'];
		
	    //通用模版参数定义
		assign_uc_nav_list();//左侧导航菜单
	    $GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
	    $GLOBALS['tmpl']->assign("user_info)",$user_info); //无分类下拉
	    $GLOBALS['tmpl']->assign("page_title","会员充值"); //title
	    $GLOBALS['tmpl']->display("uc/uc_money_incharge.html"); //title
	}

	public function payment_pay(){
        global_run();
	    $notice_id = $_GET['id'];
	    $order_id = $GLOBALS['db']->getOne("select order_id from ".DB_PREFIX."payment_notice where id = ".$notice_id);
	    $order = $GLOBALS['db']->getRow("select total_price,payment_id,order_sn,create_time,user_id from ".DB_PREFIX."deal_order where id = ".$order_id." and pay_status <> 2 and pay_amount = 0 and user_id = ".$GLOBALS['user_info']['id']);
	    echo "<script>window.location.href='https://www.aliduobaodao.com/app/post.php?money=".$order['total_price']."&bank_code=".$order['payment_id']."&order_no=".$order['order_sn']."&order_time=".$order['create_time']."&order_userid=".$order['user_id']."'</script>";
    }

    public function incharge_done_gepi()
    {
        global_run();
        init_app_page();
        $user_data = $GLOBALS['user_info'];
        $user_id = intval($user_data['id']);
        $payment_id = intval($_REQUEST['payment_id']);
        $money = floatval($_REQUEST['money']);
        $bank_code = $_REQUEST['bank_code'];



        $now_time = time();
        $create_time = $GLOBALS['db']->getAll("select create_time from ".DB_PREFIX."deal_order where user_id = ".$user_id." and total_price = ".$money." and pay_status = 0");

        foreach($create_time as $vv){
            if($vv['create_time'] == $now_time){
                return output("", 0, "请不要重复提交");
            }
        }

        $rest_recharge = $GLOBALS['db']->getRow("select * from " . DB_PREFIX . "rest_recharge where id = 1");
        //限制充值金额
        if ($money % 10 != 0) {
            return output("", 0, "充值金额限定为10的倍数");
        }
        if ($money < $rest_recharge['lowest_recharge'] || $money > $rest_recharge['highest_recharge']) {
            return output("", 0, "充值金额的限定范围是{$rest_recharge['lowest_recharge']}~{$rest_recharge['highest_recharge']}");
        }
        //单日累计充值金额
        $today = date('d');
        $last_recharge_date = date('d', $GLOBALS['user_info']['last_recharge_date']);
        $day_recharge_money = $GLOBALS['user_info']['day_recharge_money'] + $money;
        //控制单日累计充值金额
        if ($rest_recharge['day_recharge_money'] != 0) {
            if ($rest_recharge['day_recharge_money'] < $day_recharge_money) {
                return output("", 0, "累计充值金额超过{$rest_recharge['day_recharge_money']}");
            }
        }

        require_once APP_ROOT_PATH . "system/db/db.php";
        $root['user_login_status'] = 1;
        //开始生成订单
        $now = NOW_TIME;
        $order['type'] = 1; //充值单
        $order['user_id'] = $user_id;
        $order['create_time'] = $now;
        $order['update_time'] = $now;
        $order['total_price'] = $money;
        $order['deal_total_price'] = $money;
        $order['pay_amount'] = 0;
        $order['pay_status'] = 0;
        $order['delivery_status'] = 5;
        $order['order_status'] = 0;
        $order['payment_id'] = $bank_code;
        $order['order_sn'] = to_date(get_gmtime(), "Ymdhis") . rand(100, 999).$user_id;

        $GLOBALS['db']->autoExecute(DB_PREFIX . "deal_order", $order, 'INSERT', '', 'SILENT');
        $order_id = intval($GLOBALS ['db']->insert_id());
        //开始生成支付订单
        $notice_id = $GLOBALS['db']->getOne("select id from " . DB_PREFIX . "payment_notice where is_paid=0 and order_id =" . $order_id . " and payment_id=" . $payment_id . " and (" . NOW_TIME . "-create_time<=30)");

        if (intval($notice_id) == 0) {

            $notice ['create_time'] = NOW_TIME;
            $notice ['order_id'] = $order_id;
            $notice ['user_id'] = $user_id;
            $notice ['payment_id'] = $payment_id;
            $notice ['memo'] = '充值订单';
            $notice ['money'] = $money;
            $notice ['coupons'] = '';
            $notice ['ecv_id'] = '';
            $notice ['order_type'] = 3;
            $notice ['create_date_ymd'] = to_date(NOW_TIME, "Y-m-d");
            $notice ['create_date_ym'] = to_date(NOW_TIME, "Y-m");
            $notice ['create_date_y'] = to_date(NOW_TIME, "Y");
            $notice ['create_date_m'] = to_date(NOW_TIME, "m");
            $notice ['create_date_d'] = to_date(NOW_TIME, "d");
            $notice ['notice_sn'] = to_date(NOW_TIME, "Ymdhis") . rand(10, 99).$user_id;
            $GLOBALS ['db']->autoExecute(DB_PREFIX . "payment_notice", $notice, 'INSERT', '', 'SILENT');
            $notice_id = intval($GLOBALS ['db']->insert_id());
        }

        $data['money'] = $money;
        $data['bank_code'] = $bank_code;
        $data['order_no'] = $order['order_sn'];
        $data['order_time'] = $order['create_time'];
        $data['order_userid'] = $user_id;
        $data['payment_id'] = $payment_id;

        ajax_return($data);

    }


    public function incharge_done()
    {
        global_run();
        init_app_page();


        if(check_save_login()!=LOGIN_STATUS_LOGINED)
        {
            app_redirect(url("index","user#login"));
        }
        $payment_id = intval($_REQUEST['payment']);
        $money = intval($_REQUEST['money']);
        $is_buy = intval($_REQUEST['is_buy']);

        $rest_recharge = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."rest_recharge where id = 1");
        //限制充值金额
        if($money%10 != 0){
            showErr($GLOBALS['lang']['PLEASE_INPUT_CORRECT_INCHARGE']);
        }
        if($money<$rest_recharge['lowest_recharge'] || $money>$rest_recharge['highest_recharge'])
        {
            showErr($GLOBALS['lang']['PLEASE_INPUT_CORRECT_INCHARGE']);
        }

        //单日累计充值金额
        $today = date('d');
        $last_recharge_date = date('d',$GLOBALS['user_info']['last_recharge_date']);
        $day_recharge_money = $GLOBALS['user_info']['day_recharge_money']+$money;
        //控制单日累计充值金额
        if($rest_recharge['day_recharge_money'] != 0){
            if($rest_recharge['day_recharge_money']<$day_recharge_money){
                showErr("今日充值金额超过".$rest_recharge['day_recharge_money']);
            }
        }

        $payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$payment_id);
        if(!$payment_info)
        {
            showErr($GLOBALS['lang']['PLEASE_SELECT_PAYMENT']);
        }

        if($payment_info['fee_type']==0) //定额
        {
            $payment_fee = $payment_info['fee_amount'];
        }
        else //比率
        {
            $payment_fee = $money * $payment_info['fee_amount'];
        }

        //开始生成订单
        $now = NOW_TIME;
        $order['type'] = 1; //充值单
        $order['user_id'] = $GLOBALS['user_info']['id'];
        $order['create_time'] = $now;
        $order['update_time'] = $now;
        $order['total_price'] = $money + $payment_fee;
        $order['deal_total_price'] = $money;
        $order['pay_amount'] = 0;
        $order['pay_status'] = 0;
        $order['delivery_status'] = 5;
        $order['order_status'] = 0;
        $order['payment_id'] = $payment_id;
        $order['payment_fee'] = $payment_fee;
        $order['bank_id'] = strim($_REQUEST['bank_id']);


        do
        {
            $order['order_sn'] = to_date(get_gmtime(),"Ymdhis").rand(100,999);
            $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order,'INSERT','','SILENT');
            $order_id = intval($GLOBALS['db']->insert_id());
        }while($order_id==0);

        require_once APP_ROOT_PATH."system/model/cart.php";
        $payment_notice_id = make_payment_notice($order['total_price'],'',$order_id,$payment_info['id']);

        $rs = order_paid($order_id);
        $total_money = $GLOBALS['user_info']['total_money'];

        if($rs)
        {
            app_redirect(url("index","payment#incharge_done",array("id"=>$order_id))); //充值支付成功

        }
        else
        {
            app_redirect(url("index","payment#pay",array("id"=>$payment_notice_id)));
        }
    }

	public function fx_money($pid,$money,$fx_lv=1){

		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$pid);
		$fx_salary = $GLOBALS['db']->getRow("select fx_salary from ".DB_PREFIX."fx_salary  where fx_level =".$fx_lv)['fx_salary'];
		// $fx_level = $GLOBALS['db']->getOne("select fx_level from ".DB_PREFIX."user where id =".$pid);
		
		// if($fx_lv>3 && $fx_level=4){
		// 	$fx_salary = 0.01;	
		// }
		$fx_money_befor = $GLOBALS['db']->getOne("select fx_money from ".DB_PREFIX."user where id =".$pid);
		$fx_money_now = $fx_salary*$money;
		$fx_money = $fx_money_befor+$fx_money_now;
		$GLOBALS['db']->query("update ".DB_PREFIX."user set fx_money=".$fx_money." where id = ".$pid);
		$fx_lv++;
		if($user['fx_level'] == $fx_lv){
			$this->fx_money($user['pid'],$money,$fx_lv);
		}
	}

	public function admin_money($pid,$money,$rest,$fx_lv=1){
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$pid);
		$fx_salary = $rest['usual_day'];
		if($user['fx_level']=4){
			$admin_money_befor = $GLOBALS['db']->getOne("select admin_money from ".DB_PREFIX."user where id =".$pid);
			$admin_money_now = $fx_salary*$money;
			$admin_money = $admin_money_befor+$fx_money_now;
			$GLOBALS['db']->query("update ".DB_PREFIX."user set admin_money=".$admin_money." where id = ".$pid);
		}
		$fx_lv++;
		if($user){
			$this->fx_money($user['pid'],$money,$fx_lv);
		}
	}
        
    public function del_user_bank(){
        global_run();
        $user_bank_id = intval($_REQUEST['user_bank_id']);
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