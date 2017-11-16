<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class uc_totalbuyApiModule extends MainBaseApiModule
{
	public function index()
	{
		$root = array();		
			
		$user_data = $GLOBALS['user_info'];		

		$page = intval($GLOBALS['request']['page'])?intval($GLOBALS['request']['page']):1; //当前分页
		$data_id = intval($GLOBALS['request']['data_id']);
		$log_type = intval($GLOBALS['request']['log_type']);
		
		$log_type_condition = " ";
		if($log_type==1){
		    $log_type_condition = " and o.pay_status = 0 and o.order_status = 0";
		}elseif($log_type==2){
		    $log_type_condition = " and i.delivery_status = 0 and i.is_arrival = 0 and o.pay_status = 2 ";
		}elseif($log_type==3){
		    $log_type_condition = " and i.is_arrival = 0 and i.delivery_status = 1 and o.pay_status = 2 ";
		}
		
		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED){			
			$root['user_login_status'] = $user_login_status;
		
		}else{
			$root['user_login_status'] = 1;
			
			$page_size = PAGE_SIZE;
			$limit = (($page-1)*$page_size).",".$page_size;
			
			$sql_count = "select count(*) from ".DB_PREFIX."deal_order_item as i left join ".DB_PREFIX."deal_order as o on o.id = i.order_id where o.user_id = ".$user_data['id']." and o.type = 3 ".$log_type_condition;

			$sql = "select i.id,i.deal_id,i.duobao_id,i.duobao_item_id,i.number,i.delivery_status,i.name,i.order_sn,i.unit_price,i.total_price,i.order_id,i.is_arrival,i.refund_status,i.deal_icon,i.user_id,i.create_time,i.user_name,o.pay_status,o.create_time,o.order_sn,o.order_status
		    from ".DB_PREFIX."deal_order_item as i left join ".DB_PREFIX."deal_order as o on o.id = i.order_id where o.user_id = ".$user_data['id']." and o.type = 3 ".$log_type_condition." order by i.create_time desc limit ".$limit;
		
		    $list = $GLOBALS['db']->getAll($sql);
			
			$sql_id = "select id from fanwe_deal_cate  c where  c.is_fictitious=1";
			$is_fictitious_id = $GLOBALS['db']->getAll($sql_id);
			foreach ($is_fictitious_id as $k=>$v){
			    $is_fictitious_id[$k]=$v['id'];
			}
            
			foreach($list as $k=>$v)
			{

			    if (!$v['id']) {
			        continue;
			    }
			    
			    $list[$k]['unit_price'] = format_sprintf_price($v['unit_price']);
			    $list[$k]['total_price'] = format_sprintf_price($v['total_price']);
				$list[$k]['create_time'] = to_date($v['create_time']);
				$list[$k]['deal_icon']=get_abs_img_root(get_spec_image($list[$k]['deal_icon'],200,200,1));
                $list[$k]['region_status'] = $region_status;
                $duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$v['duobao_item_id']);
                if(in_array($duobao_item['cate_id'], $is_fictitious_id)){
                    $delivery_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_notice where order_item_id = ".$v['id']);
                    $list[$k]['fictitious_info'] = $delivery_notice['fictitious_info'];
                    $list[$k]['cate_id'] = $duobao_item['cate_id'];
                     
                }
                
			}
			$count = $GLOBALS['db']->getOne($sql_count);
			$page_total = ceil($count/$page_size);

			$root['user_avatar'] = get_abs_img_root(get_muser_avatar($user_data['id'],"big"))?get_abs_img_root(get_muser_avatar($user_data['id'],"big")):"";
			$root['user_logo'] = $user_data['user_logo']?get_abs_img_root($user_data['user_logo']):$root['user_avatar'];
			$root['list'] = $list?$list:array();
			$root['log_type'] = $log_type;
						

			$root['user_name']=$GLOBALS['user_info']['user_name'];
			$root['user_id']=$GLOBALS['user_info']['id'];
			$root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
			
			$root['is_app']   = $GLOBALS['is_app'] ? 1:0;
			$root['page_title'].="购买记录";

		}
		
		return output($root);
	}
	
	
	public function detail(){
	    global_run();
	
	    $id = intval($GLOBALS['request']['id']);
	    $user_id=intval($GLOBALS['user_info']['id']);
	
	    //查询是否存在
	    $sql = "select
                    i.id,
                    i.is_set_consignee,
                    i.id as order_item_id,
                    i.duobao_item_id,
	                i.duobao_id,
	                i.number as buynumber,
	                i.unit_price,
	                i.total_price,
                    i.delivery_status,
                    i.name,
	                i.order_sn,
	                i.order_id,
                    i.is_arrival,
                    i.deal_icon,
	                i.create_time,
                    i.lottery_sn,
                    i.buy_number as number,
                    i.consignee,
	                i.address,
	                i.pay_status,
                    i.mobile,
                    i.region_info,
                    i.zip,
	                o.order_status
                 from  ".DB_PREFIX."deal_order_item as i left join ".DB_PREFIX."deal_order as o on o.id = i.order_id
	
                     where i.id=".$id." and i.user_id = ".$GLOBALS['user_info']['id']."
                         and i.type=3";
	
	    $order = $GLOBALS['db']->getRow($sql);
	    $order['create_time'] = to_date($order['create_time']);
	
	    if (!$order){
	        showErr("数据不存在",0,url("index","uc_totalbuy"));
	    }
	
	    $duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$order['duobao_item_id']);
	    $sql_id = "select id from fanwe_deal_cate  c where  c.is_fictitious=1";
	    $is_fictitious_id = $GLOBALS['db']->getAll($sql_id);
	    foreach ($is_fictitious_id as $k=>$v){
	        $is_fictitious_id[$k]=$v['id'];
	    }
	    if(in_array($duobao_item['cate_id'], $is_fictitious_id)){
	        $delivery_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_notice where order_item_id = ".$order['id']);
	        $order['fictitious_info'] = $delivery_notice['fictitious_info'];
	        $order['cate_id'] = $duobao_item['cate_id'];
	
	    }
	    $order['max_buy'] = $duobao_item['max_buy'];
	    $order['price']   = $duobao_item['max_buy'] * $duobao_item['unit_price'];
	    $order['lottery_time'] = $duobao_item['lottery_time'];
	    $order['is_send_share'] = $duobao_item['is_send_share'];
	    $order['share_id'] = $duobao_item['share_id'];
	    $order['unit_price'] = format_sprintf_price($order['unit_price']);
	    $order['total_price'] = format_sprintf_price($order['total_price']);
	
	    //输出所有配送方式
	    $consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id = ".$user_id);
	    foreach($consignee_list as $k=>$v){
	        $consignee_info=load_auto_cache("consignee_info",array("consignee_id"=>$v['id']));
	        $consignee_list[$k]['del_url']=url('index','uc_consignee#del',array('id'=>$v['id']));
	        $consignee_list[$k]['dfurl']=url('index','uc_consignee#set_default',array('id'=>$v['id']));
	        $consignee_list[$k]['region_lv2']=  $consignee_info['consignee_info']['region_lv2_name'];
	        $consignee_list[$k]['region_lv3']=  $consignee_info['consignee_info']['region_lv3_name'];
	        $consignee_list[$k]['region_lv4']=  $consignee_info['consignee_info']['region_lv4_name'];
	    }
	
	    //夺宝商品信息
	    $duobao_item['value_price'] = $duobao_item['max_buy']*$duobao_item['unit_price'];
	    $duobao_item['origin_price'] = round($duobao_item['origin_price'],2);
	
	    //快递信息和虚拟商品信息  fictitious_info
	    $delivery_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_notice where order_item_id = ".$order['id']);
	    
	    if($delivery_notice){
	        $express_list = require_once APP_ROOT_PATH."system/express_cfg.php";
	        $express_info = $express_list[$delivery_notice['express_id']];
	        $delivery_notice['express_name'] = $express_info['name'];
	    }
	    $count_consignee = count($consignee_list);
	    $root['order'] = $order;
	    $root['duobao_item'] = $duobao_item;
	    $root['delivery_notice'] = $delivery_notice;
	    $root['consignee_list'] = $consignee_list;
	    $root['count_consignee'] = $count_consignee;
	    
	    return output($root);
	}
	
	public function verify_delivery()
	{
	    global_run();
	    $user_login_status = check_login();
	    if($user_login_status!=LOGIN_STATUS_LOGINED){
	        $root['user_login_status'] = $user_login_status;
	    }else{
	        $root['user_login_status'] = 1;
	        $id = intval($GLOBALS['request']['id']);
	        $user_id = intval($GLOBALS['user_info']['id']);
	        require_once APP_ROOT_PATH."system/model/deal_order.php";
	        $order_table_name = get_user_order_table_name($user_id);
	
	        $delivery_notice = $GLOBALS['db']->getRow("select n.* from ".DB_PREFIX."delivery_notice as n left join ".$order_table_name." as o on n.order_id = o.id where n.order_item_id = ".$id." and o.user_id = ".$user_id." and is_arrival = 0 order by delivery_time desc");
	        if($delivery_notice)
	        {
	            require_once APP_ROOT_PATH."system/model/deal_order.php";
	            $res = confirm_delivery($delivery_notice['notice_sn'],$id);
	            if($res)
	            {
	                $data['status'] = 1;
	                $data['share_url'] = url("index","uc_share#add",array("id"=>$id));
	                $order_id = $GLOBALS['db']->getOne("select order_id from ".DB_PREFIX."deal_order_item where id = '".$id."'");
	                $order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".intval($order_id));
	                
	                if( $order_info['pay_status'] != 2 ){
	                    return output($root, 0, '订单付款异常，无法确认收货');
	                }
	                
	                $GLOBALS['db']->query("update ".DB_PREFIX."deal_order set order_status = 1,update_time = ".NOW_TIME." where order_status = 0 and id = ".$order_info['id']);
	                if(!$GLOBALS['db']->affected_rows())
	                {
	                    return output($root, 0, '结单失败');  //结单失败
	                }else{
	                    
//	                    //直购订单完结后送满返红包
//	                    $content_ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 6");
//
//	                    // 订单满多少发红包判断
//	                    if($content_ecv){
//	                        if($order_info['total_price']>=$content_ecv['minchange_money']){
//	                            require_once APP_ROOT_PATH."system/libs/voucher.php";
//	                            $content_ecv_id = send_voucher($content_ecv, $order_info['user_id']);
//	                            if ( $content_ecv_id > 0 ) {
//	                                $content_ecv_money = $GLOBALS['db']->getOne("select money from ".DB_PREFIX."ecv where id = {$content_ecv_id}");
//	                                $content_ecv_money = round($content_ecv_money, 2);
//	                                send_msg($order_info['user_id'], "订单".$order_info['order_sn']."返还您{$content_ecv_money}元红包", "notify");
//	                            }
//	                        }
//	                    }
	                    
//	                    //订单结单成功后，开始为订单商品进行分销推广奖利润计算
//	                    if( defined("FX_LEVEL") )
//	                    {
//	                        require_once APP_ROOT_PATH."system/model/fx.php";
//	                        send_fx_order_salary($order_info);
//	                    }
	                    order_log("订单完结", $order_info['id']);
	                }
	                
	            }
	            else
	            {
	                return output($root, 0, '收货失败');
	            }
	        }
	        else
	        {
	            return output($root, 0, "订单未发货");
	        }
	    }
	    return output($root);
	}
	
	public function close()
	{
	    global_run();
	    require_once APP_ROOT_PATH . "system/model/deal_order.php";

	    $root = array();
	    $user_login_status = check_login();
	    if($user_login_status!=LOGIN_STATUS_LOGINED){
	        $root['user_login_status'] = $user_login_status;
	    }else{
	        $root['user_login_status'] = 1;
	    
    	    $order_id = intval($GLOBALS['request']['id']);
    	   
    	    // 关闭订单，退库存，退金额
    	    $status = cancel_totalbuy_order($order_id, true);
    	    
    	    if ($status) {
    	        return output($root, 1, '订单已关闭');
    	       
    	    }else{
    	        return output($root, 0, '订单关闭出错');
    	        
    	    }
	    }
	    return output($root);
	}
	
	
}
?>