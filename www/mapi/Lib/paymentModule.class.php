<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class paymentApiModule extends MainBaseApiModule
{
	
	/**
	 * 订单支付页，包含检测状态，获取支付代码与消费券
	 * 
	 * 输入:
	 * id: int 订单ID
	 * 
	 * 输出:
	 * status:int 状态 0:失败 1:成功
	 * info: string 失败的原因
	 * 以下参数为成功时返回
	 * pay_status: int 支付状态 0:未支付 1:已支付 
	 * order_id: int 订单ID
	 * order_sn: string 订单号
	 * 
	 * pay_info: string 显示的信息
	 * 
	 * 当pay_status 为1时
	 * couponlist: array 消费券列表
	 * Array
	 * (
	 * 		Array(
	 * 			"password" => string 验证码
	 * 			"qrcode"  => string 二维码地址
	 * 		)
	 * )
	 * 
	 * 当pay_status 为0时
	 * payment_code: Array() 相关支付接口返回的支付数据
	 */
	public function done()
	{
		global_run();
		$root = array();
		$order_id = intval($GLOBALS['request']['id']);
		
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$order_id);
		
		//送拆分红包
		$split_red_money = $GLOBALS['db']->getOne("select value from ".DB_PREFIX."conf where name = 'SPLIT_RED_MONEY'");
		
		if(empty($order_info))
		{
			return output(array(),0,"订单不存在");
		}
        $root['duobao_item']  = $GLOBALS['db']->getRow("select DISTINCT(di.id),di.is_pk,di.is_number_choose from ".DB_PREFIX."duobao_item as di LEFT JOIN ".DB_PREFIX."deal_order_item as doi on di.id=doi.duobao_item_id where doi.order_id=".$order_id);
		$root['order_sn'] = $order_info['order_sn'];
		$root['order_id'] = $order_id;
		$root['is_app']   = $GLOBALS['is_app'] ? 1:0;
		$root['pay_amount'] = $order_info['pay_amount'];
		if($order_info['pay_status']==2)
		{
			if($order_info['type']==0||$order_info['type']==2)//商品订单和夺宝订单
			{
				$refund_item = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order_item where order_id = ".$order_info['id']." and refund_status = 2");
				if($refund_item)
				{
					$root['pay_status'] = 1;
					if(count($refund_item)>1)
						$root['pay_info'] = $refund_item[0]['name'].'等已失效，已退款';
					else
						$root['pay_info'] = $refund_item[0]['name'].'已失效，已退款';
				}
				else
				{ 
					$root['pay_status'] = 1;					
					$root['pay_info'] = '订单已经收款';
					if($split_red_money['value']==1){
					    $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 3");
					    if($order_info['pay_amount']>=$ecv_type['minchange_money']){
					        $root['split_red_money'] = 1;
					        $root['url']= SITE_DOMAIN.wap_url("index","redset",array("order_sn"=>$order_info['order_sn']));
					        $bonus_items=$this->sendrandbonus($ecv_type['money'],$ecv_type['total_limit'],$ecv_type['sm_way']);
					        $bonus_items=serialize($bonus_items);
					        $sendrandbonus = $GLOBALS['db']->getOne("select sendrandbonus from ".DB_PREFIX."deal_order where order_sn =".$order_info['order_sn']);
					        if($sendrandbonus==''){
					            $GLOBALS['db']->query("update ".DB_PREFIX."deal_order set sendrandbonus = '".$bonus_items."',send_limit = ".$ecv_type['total_limit']." where order_sn =".$order_info['order_sn']);
					        }
					    }
					}
				}
				return output($root);
			}elseif ($order_info['type']==3){
			    $root['pay_status']  = 1;
			    $root['pay_type']    = 3; // 直购订单
			    $deal_order_item         = $GLOBALS['db']->getRow("select duobao_item_id, name from ".DB_PREFIX."deal_order_item where order_id=".$order_id);
			    $root['pay_info']        = $deal_order_item['name'];
			    $root['duobao_item_id']  = $deal_order_item['duobao_item_id'];
			    if($split_red_money['value']==1){
			        $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 3");
			        if($order_info['pay_amount']>=$ecv_type['minchange_money']){
			            $root['split_red_money'] = 1;
			            $root['url']= SITE_DOMAIN.wap_url("index","redset",array("order_sn"=>$order_info['order_sn']));
			            $bonus_items=$this->sendRandBonus($ecv_type['money'],$ecv_type['total_limit'],$ecv_type['sm_way']);
			            $bonus_items=serialize($bonus_items);
			            $sendrandbonus = $GLOBALS['db']->getOne("select sendrandbonus from ".DB_PREFIX."deal_order where order_sn =".$order_info['order_sn']);
			            if($sendrandbonus==''){
			                $GLOBALS['db']->query("update ".DB_PREFIX."deal_order set sendrandbonus = '".$bonus_items."',send_limit = ".$ecv_type['total_limit']." where order_sn =".$order_info['order_sn']);
			            }
			        }
			    }
			    
			    return output($root);
			}elseif($order_info['type']==4) //免费购订单
			{
			    $refund_item = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order_item where order_id = ".$order_info['id']." and refund_status = 2");
			    if($refund_item)
			    {
			        $root['pay_status'] = 1;
			        if(count($refund_item)>1)
			            $root['pay_info'] = $refund_item[0]['name'].'等已失效，已退款';
			        else
			            $root['pay_info'] = $refund_item[0]['name'].'已失效，已退款';
			    }
			    else
			    {
			        $root['pay_status'] = 1;
			        $root['pay_info'] = '订单已经收款';
			    }
			    return output($root);
			    
			}else{
				$root['pay_status'] = 1;
				$root['pay_type'] = $order_info['type'];//判断会员充值夺宝币
				$root['pay_info'] = round($order_info['pay_amount'],2)." 元 充值成功";
				return output($root);
			}
		}
		else
		{
			require_once APP_ROOT_PATH."system/model/cart.php";
			
			
			$payment = $order_info['payment_id'];
			if($order_info['type']==1)
			{
				$pay_price = $order_info['total_price'];
			}
			else {
				$goods_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order_item where order_id = ".$order_info['id']);
				
				$data = count_buy_total($payment,0,0,'','',$goods_list,$order_info['account_money'],$order_info['ecv_money']);
				
				$pay_price = $data['pay_price'];
			}
			$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$order_info['payment_id']);
			if(empty($payment_info))
			{
				return output(array(),0,"支付方式不存在");
			}
			if($pay_price<=0)
			{
				return output(array(),0,"无效的支付方式");
			}
			
			global $is_app;
			if(!$is_app)
			{
				if ( $payment_info['online_pay'] !=2 && $payment_info['online_pay'] !=4 && $payment_info['online_pay'] !=5 && $payment_info['online_pay'] !=6 && $payment_info['online_pay'] != 7 )
				{
					return output(array(),0,"该支付方式不支持wap支付");
				}
			}
			else
			{
				if ($payment_info['online_pay']!=3&&$payment_info['online_pay']!=4&&$payment_info['online_pay']!=5&&$payment_info['online_pay']!=6)
				{
					return output(array(),0,"该支付方式不支持手机支付");
				}
			}
			
			$payment_notice_id = make_payment_notice($pay_price,'',$order_id,$order_info['payment_id']);
			require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
			$payment_class = $payment_info['class_name']."_payment";
			$payment_object = new $payment_class();
			$payment_code = $payment_object->get_payment_code($payment_notice_id);
			

			$root['pay_status']   = 0;
			$root['pay_info']     = '支付失败';
			$root['payment_code'] = $payment_code;
			return output($root);
		}		
	}
	
	public function order_share(){
	    global_run();
	    $root = array();
	    $order_id = intval($GLOBALS['request']['id']);

	    //检查用户,用户密码
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    
	    $user_login_status = check_login();
	    if($user_login_status!=LOGIN_STATUS_LOGINED){
	        $root['user_login_status'] = $user_login_status;
	    }
	    else
	    {
	        $root['user_login_status'] = $user_login_status;
	        require_once APP_ROOT_PATH.'system/model/topic.php';
	        order_share($order_id);
	    }
	    return output($root);
	    
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

