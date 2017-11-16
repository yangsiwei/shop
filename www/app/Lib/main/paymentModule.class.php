<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class paymentModule extends MainBaseModule
{
	//订单支付页
	public function pay()
	{
		global_run();
		init_app_page();

		$id = intval($_REQUEST['id']);
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$id);
		if($payment_notice)
		{
			if($payment_notice['is_paid'] == 0)
			{
				$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$payment_notice['payment_id']);
				if(empty($payment_info))
				{
					app_redirect(url("index"));
				}
				$order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']." and is_delete = 0");
				if(empty($order))
				{
					app_redirect(url("index"));
				}
				//判断支付状态
				if($order['pay_status']==2)
				{
					app_redirect(url("index","payment#done",array("id"=>$order['id'])));
				}
				require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
				$payment_class = $payment_info['class_name']."_payment";
				$payment_object = new $payment_class();
				$payment_code = $payment_object->get_payment_code($payment_notice['id']);
				$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['PAY_NOW']);
				$GLOBALS['tmpl']->assign("payment_code",$payment_code);

				$GLOBALS['tmpl']->assign("order",$order);
				$GLOBALS['tmpl']->assign("payment_notice",$payment_notice);
				if(intval($_REQUEST['check'])==1)
				{
					showErr($GLOBALS['lang']['PAYMENT_NOT_PAID_RENOTICE'],0,url("index","payment#pay",array("id"=>$id)));
				}
				$GLOBALS['tmpl']->display("payment_pay.html");
			}
			else
			{
				$order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
			
				if($order['pay_status']==2)
				{
					app_redirect(url("index","payment#done",array("id"=>$order['id'])));
				}
				else
					showSuccess($GLOBALS['lang']['NOTICE_PAY_SUCCESS'],0,url("index"),1);
			}
		}
		else
		{
			showErr($GLOBALS['lang']['NOTICE_SN_NOT_EXIST'],0,url("index"),1);
		}
	}
	
	
	public function tip()
	{
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".intval($_REQUEST['id']));
		$GLOBALS['tmpl']->assign("payment_notice",$payment_notice);
		$GLOBALS['tmpl']->display("payment_tip.html");
	}
	
	
	public function response()
	{
		//支付跳转返回页
		if($GLOBALS['pay_req']['class_name'])
			$_REQUEST['class_name'] = $GLOBALS['pay_req']['class_name'];
			
		$class_name = strim($_REQUEST['class_name']);
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name = '".$class_name."'");
		if($payment_info)
		{
			require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
			$payment_class = $payment_info['class_name']."_payment";
			$payment_object = new $payment_class();
			adddeepslashes($_REQUEST);
			$payment_code = $payment_object->response($_REQUEST);
		}
		else
		{
			showErr($GLOBALS['lang']['PAYMENT_NOT_EXIST']);
		}
	}
	
	public function notify()
	{
		//支付跳转返回页
		if($GLOBALS['pay_req']['class_name'])
			$_REQUEST['class_name'] = $GLOBALS['pay_req']['class_name'];
			
		$class_name = strim($_REQUEST['class_name']);
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name = '".$class_name."'");
		if($payment_info)
		{
			require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
			$payment_class = $payment_info['class_name']."_payment";
			$payment_object = new $payment_class();
			adddeepslashes($_REQUEST);
			$payment_code = $payment_object->notify($_REQUEST);
		}
		else
		{
			showErr($GLOBALS['lang']['PAYMENT_NOT_EXIST']);
		}
	}
	
	
	
	public function done()
	{
		global_run();
	    init_app_page();
	    $order_id = intval($_REQUEST['id']);
	    
	    $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
	    //送拆分红包
	    $split_red_money = $GLOBALS['db']->getOne("select value from ".DB_PREFIX."conf where name = 'SPLIT_RED_MONEY'");
	    
	    if(empty($order_info))
	    {
	        showErr("订单不存在",0,url("index"));
	    }

	    //判断支付状态
	    if($order_info['pay_status']==2){
	    	//增加累计消费金额
	        $total_use_money_befor = $GLOBALS['user_info']['total_use_money'];
	        $total_use_money = $total_use_money_befor+$order_info['pay_amount'];
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set total_use_money=".$total_use_money." where id =".$order_info['user_id']);

	        //判断是否有成为经销商的资格
	        if($total_use_money>=100){
	        	$dealers = $GLOBALS['db']->getOne("select dealers from ".DB_PREFIX."user where id =".$order_info['user_id']);
	        	if($dealers == null){
	        		$res = $GLOBALS['db']->query("update ".DB_PREFIX."user set dealers=1 where id =".$order_info['user_id']);
	        	}
	        }

	        if($order_info['type']==2 || $order_info['type']=='0'){ //商品订单和夺宝订单
	            $order_item = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order_item where order_id = ".$order_info['id']);
	            $duobao_item_ids = array();
	            foreach ($order_item as $k=>$v){
	                $duobao_item_ids[] = $v['duobao_item_id'];
	                $order_item_ids[] = $v['id'];
	            }
	            //查询夺宝号
	            $duobao_item_log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log where order_item_id in (".implode(",", $order_item_ids).") and duobao_item_id in (".implode(",", $duobao_item_ids).") and user_id = ".$order_info['user_id']);
                //极速专区会移到duobao_item_log_list_history表
                $duobao_item_log_list_history = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log_history where order_item_id in (".implode(",", $order_item_ids).") and duobao_item_id in (".implode(",", $duobao_item_ids).") and user_id = ".$order_info['user_id']);
                $duobao_item_log_list=array_merge($duobao_item_log_list,$duobao_item_log_list_history);
                foreach ($order_item as $k=>$v){
	                $temp_arr = array();
	                foreach ($duobao_item_log_list as $sub_k=>$sub_v){
	                    if($v['duobao_item_id']==$sub_v['duobao_item_id']){
	                        $temp_arr[] = $sub_v['lottery_sn'];
	                    }
	                }
	                $create_time = $v['create_time'];
	                $data_arr = explode(".", $create_time);
	                $date_str = to_date(intval($data_arr[0]),"H:i:s");
	                $full_date_str = to_date(intval($data_arr[0]));
	                $mmtime = trim($data_arr[1]);
	                
	                $res = intval(str_replace(":", "", $date_str).$mmtime);
	                $fair_sn_local=$res;
	                
	                $order_item[$k]['create_time_format'] = $full_date_str.".".$mmtime;
	                $order_item[$k]['lottery_sn_list'] = $temp_arr;
	                $total_number+=intval($v['number']);
	            }
	            if($split_red_money['value']==1){
	                $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 3");
	                if($order_info['pay_amount']>=$ecv_type['minchange_money']){
	                    $sendmoney['split_red_money'] = 1;
	                    $sendmoney['url']= SITE_DOMAIN.url("index","redset",array("order_sn"=>$order_info['order_sn']));
	                    $bonus_items=$this->sendrandbonus($ecv_type['money'],$ecv_type['total_limit'],$ecv_type['sm_way']);
	                    $bonus_items=serialize($bonus_items);
	                    $sendrandbonus = $GLOBALS['db']->getOne("select sendrandbonus from ".DB_PREFIX."deal_order where order_sn =".$order_info['order_sn']);
	                    if($sendrandbonus==''){
	                        $GLOBALS['db']->query("update ".DB_PREFIX."deal_order set sendrandbonus = '".$bonus_items."',send_limit = ".$ecv_type['total_limit']." where order_sn =".$order_info['order_sn']);
	                    }
	                    $GLOBALS['tmpl']->assign("sendmoney",$sendmoney);
	                }
	            }
	            $GLOBALS['tmpl']->assign("total_number",$total_number);
	            $GLOBALS['tmpl']->assign("order_item",$order_item);
	        
	       }elseif($order_info['type']==1){//充值订单
	           $GLOBALS['tmpl']->assign("info",round($order_info['pay_amount'],2)." 夺宝币 充值成功");
           }elseif($order_info['type']==3){
	            $order_info['create_time_format']  = to_date($order_info['create_time'], 'Y-m-d H:i:s');
	            $deal_order_item                   = $GLOBALS['db']->getRow("select duobao_item_id, name, number from ".DB_PREFIX."deal_order_item where order_id=".$order_id);
	            $order_info['deal_name']           = $deal_order_item['name'];
	            $order_info['number']              = $deal_order_item['number'];
	            $order_info['duobao_item_id']      = $deal_order_item['duobao_item_id'];
	            if($split_red_money['value']==1){
	                $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 3");
	                if($order_info['pay_amount']>=$ecv_type['minchange_money']){
	                    $sendmoney['split_red_money'] = 1;
	                    $sendmoney['url']= SITE_DOMAIN.url("index","redset",array("order_sn"=>$order_info['order_sn']));
	                    $bonus_items=$this->sendrandbonus($ecv_type['money'],$ecv_type['total_limit'],$ecv_type['sm_way']);
	                    $bonus_items=serialize($bonus_items);
	                    $sendrandbonus = $GLOBALS['db']->getOne("select sendrandbonus from ".DB_PREFIX."deal_order where order_sn =".$order_info['order_sn']);
	                    if($sendrandbonus==''){
	                        $GLOBALS['db']->query("update ".DB_PREFIX."deal_order set sendrandbonus = '".$bonus_items."',send_limit = ".$ecv_type['total_limit']." where order_sn =".$order_info['order_sn']);
	                    }
	                    $GLOBALS['tmpl']->assign("sendmoney",$sendmoney);
	                }
	            }
	            
	       }elseif($order_info['type']==4){//免费购订单
	           $order_item = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order_item where order_id = ".$order_info['id']);
	           $duobao_item_ids = array();
	           foreach ($order_item as $k=>$v){
	               $duobao_item_ids[] = $v['duobao_item_id'];
	               $order_item_ids[] = $v['id'];
	           }
	           //查询夺宝号
	           $duobao_item_log_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log where order_item_id in (".implode(",", $order_item_ids).") and duobao_item_id in (".implode(",", $duobao_item_ids).") and user_id = ".$order_info['user_id']);
	           //极速专区会移到duobao_item_log_list_history表
	           $duobao_item_log_list_history = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log_history where order_item_id in (".implode(",", $order_item_ids).") and duobao_item_id in (".implode(",", $duobao_item_ids).") and user_id = ".$order_info['user_id']);
	           $duobao_item_log_list=array_merge($duobao_item_log_list,$duobao_item_log_list_history);
	           foreach ($order_item as $k=>$v){
	               $temp_arr = array();
	               foreach ($duobao_item_log_list as $sub_k=>$sub_v){
	                   if($v['duobao_item_id']==$sub_v['duobao_item_id']){
	                       $temp_arr[] = $sub_v['lottery_sn'];
	                   }
	               }
	               $create_time = $v['create_time'];
	               $data_arr = explode(".", $create_time);
	               $date_str = to_date(intval($data_arr[0]),"H:i:s");
	               $full_date_str = to_date(intval($data_arr[0]));
	               $mmtime = trim($data_arr[1]);
	                
	               $res = intval(str_replace(":", "", $date_str).$mmtime);
	               $fair_sn_local=$res;
	                
	               $order_item[$k]['create_time_format'] = $full_date_str.".".$mmtime;
	               $order_item[$k]['lottery_sn_list'] = $temp_arr;
	               $total_number+=intval($v['number']);
	           }
	           $GLOBALS['tmpl']->assign("total_number",$total_number);
	           $GLOBALS['tmpl']->assign("order_item",$order_item);
	       }
	       
	       $GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['PAY_SUCCESS']);
	    }else{
           $GLOBALS['tmpl']->assign("page_title", '支付失败');
	    }
	    $GLOBALS['tmpl']->assign("order_info",$order_info);
		
		if ($order_info['type'] == 3) {
		    $GLOBALS['tmpl']->display("totalbuy_payment_done.html");
		}else{
		    $GLOBALS['tmpl']->display("payment_done.html");
		}
		
	}
	
	public function incharge_done()
	{
		global_run();
		init_app_page();
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		$order_id = intval($_REQUEST['id']);
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
		//$order_deals = $GLOBALS['db']->getAll("select d.* from ".DB_PREFIX."deal as d where id in (select distinct deal_id from ".DB_PREFIX."deal_order_item where order_id = ".$order_id.")");
		$GLOBALS['tmpl']->assign("order_info",$order_info);
		//$GLOBALS['tmpl']->assign("order_deals",$order_deals);
		
		if($order_info['user_id']==$GLOBALS['user_info']['id'])
		{
			showSuccess(round($order_info['pay_amount'],2)." 夺宝币 充值成功",0,url("index","uc_money"));
		}
		else
		{
			showSuccess(round($order_info['pay_amount'],2)." 夺宝币 充值成功",0);
		}
	
		$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['PAY_SUCCESS']);
		$GLOBALS['tmpl']->display("payment_done.html");
	}
	
	//生成红包函数 $ecv_type:总金额; $count:总个数; $type:0为等额,1为随机
	public function sendrandbonus($total=0, $count=3, $type=1){
	    if($type==1){
	        $input     = range(0.01, $total, 0.01);
	        if($count>1){
	            $rand_keys = (array) array_rand($input, $count-1);
	            $last    = 0;
	            foreach($rand_keys as $i=>$key){
	                $current  = $input[$key]-$last;
	                $items[]  = number_format($current,2);
	                $last    = $input[$key];
	            }
	        }
	        $items[]    = number_format($total-array_sum($items),2);
	    }else{
	        $avg      = number_format($total/$count, 2);
	        $i       = 0;
	        while($i<$count){
	            $items[]  = $i<$count-1?number_format($avg,2):(number_format($total-array_sum($items),2));
	            $i++;
	        }
	    }
	    return $items;
	}
}
?>