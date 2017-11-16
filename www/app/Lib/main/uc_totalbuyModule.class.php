<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


require APP_ROOT_PATH.'app/Lib/page.php';
class uc_totalbuyModule extends MainBaseModule
{
	public function index()
	{
	   
		require APP_ROOT_PATH."system/model/uc_center_service.php";
		global_run();
		init_app_page();
		assign_uc_nav_list();//左侧导航菜单
		
		if(empty($GLOBALS['user_info']))
		{
		    app_redirect(url("index","user#login"));
		}
		
		
		$id = $GLOBALS['user_info']['id'];
		
		$log_type=intval($_REQUEST['log_type']); //1未付款 2待发货 3待收货 4已收货 0所有
		
		$log_type_condition = " ";
		if($log_type==1){
		    $log_type_condition = " and o.pay_status = 0 and order_status =0 ";
		}elseif($log_type==2){
		    $log_type_condition = " and i.delivery_status = 0 and o.pay_status = 2 ";
		}elseif($log_type==3){
		    $log_type_condition = " and i.is_arrival = 0 and i.delivery_status = 1 and o.pay_status = 2 ";
		}elseif($log_type==4){
		    $log_type_condition = " and i.is_arrival = 1 and o.pay_status = 2 ";
		}
		
		//时间区间
		$log_time_type=intval($_REQUEST['log_time_type']); //1今天 2最近七天 3最近30天 5一年内 默认3个月内
		if($log_time_type==0)$log_time_type=4;
		
		//时间
		if($log_time_type==1)  //今天
		    $log_time_type_condition = " and i.create_date_ymd = '".to_date(NOW_TIME,"Y-m-d")."' ";//今天
		elseif($log_time_type==2)  //最近7天
		{
		    for($i=0;$i<7;$i++)
		    {
		        $week_day[] = "'".to_date((NOW_TIME-$i*24*3600),"Y-m-d")."'";
		    }
		     
		    $log_time_type_condition = " and  i.create_date_ymd in (".implode(",", $week_day).") ";//7天内
		}
		elseif($log_time_type==3)  //最近30天
		{
		    for($i=0;$i<30;$i++)
		    {
		        $month_day[] = "'".to_date((NOW_TIME-$i*24*3600),"Y-m-d")."'";
		    }
		     
		    $log_time_type_condition = " and  i.create_date_ymd in (".implode(",", $month_day).") ";//30天内
		}
		elseif($log_time_type==5)  //1年内
		$log_time_type_condition = " and create_date_y = '".to_date(NOW_TIME,"Y")."' ";//1年内
		else   //默认，最近三个月
		{
		    $month_day[] = "'".date('Y-m',time())."'";
		    for($i=1;$i<=2;$i++)
		    {
		        $month_day[] = "'".date('Y-m',strtotime('-'.$i.' month'))."'";
		    }
		     
		    $log_time_type_condition = " and  i.create_date_ym in (".implode(",", $month_day).") ";
		     
		}
		
		
		$page =intval($_REQUEST['p']);
		$page_size =app_conf("PAGE_SIZE");
		
		
		$sql_total = "select count(*) from ".DB_PREFIX."deal_order_item as i left join ".DB_PREFIX."deal_order as o on o.id = i.order_id where o.user_id = ".$id." and o.type = 3 ".$log_type_condition." ".$log_time_type_condition;
		$total = $GLOBALS['db']->getOne($sql_total);
		
		//分页
		require_once APP_ROOT_PATH."app/Lib/page.php";
		$page =intval($_REQUEST['p']);
		$page_size =app_conf("PAGE_SIZE");
		if ($page == 0) $page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$sql = "select i.id,i.deal_id,i.duobao_id,i.duobao_item_id,i.number,i.delivery_status,i.name,i.order_sn,i.order_id,i.is_arrival,i.refund_status,i.user_id,i.create_time,i.user_name,o.pay_status,o.create_time,o.order_sn,o.order_status
		    from ".DB_PREFIX."deal_order_item as i left join ".DB_PREFIX."deal_order as o on o.id = i.order_id where o.user_id = ".$id." and o.type = 3 ".$log_type_condition." ".$log_time_type_condition." order by i.create_time desc limit ".$limit;
		
		$sql_id = "select id from ".DB_PREFIX."deal_cate  c where  c.is_fictitious=1";
		$is_fictitious_id = $GLOBALS['db']->getAll($sql_id);
		foreach ($is_fictitious_id as $k=>$v){
		    $is_fictitious_id[$k]=$v['id'];
		}
		$list = $GLOBALS['db']->getAll($sql);
		
		foreach($list as $k=>$v)
		{
		    $list[$k]['duobao_item'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$v['duobao_item_id']);
		    $list[$k]['duobao_item']['less'] = $list[$k]['duobao_item']['max_buy'] - $list[$k]['duobao_item']['current_buy'];
		    $list[$k]['create_time'] = to_date($list[$k]['create_time'],"Y-m-d H:i:s");
		    if(in_array($list[$k]['duobao_item']['cate_id'], $is_fictitious_id)){
		        $list[$k]['duobao_item']['is_fictitious_id'] = $list[$k]['duobao_item']['cate_id'];
		         
		    }
		
		}
		
		$page = new Page($total, $page_size); // 初始化分页对象
		$p = $page->show();
		
		$GLOBALS['tmpl']->assign("list",$list);
		$GLOBALS['tmpl']->assign("pages",$p);
		
		$sql_total = "select count(*) from ".DB_PREFIX."deal_order_item as i left join ".DB_PREFIX."deal_order as o on o.id = i.order_id where o.user_id = ".$id." and o.type = 3 ".$log_time_type_condition;
		$data['success_count']=$GLOBALS['db']->getOne($sql_total);
		
		$sql_total = "select count(*) from ".DB_PREFIX."deal_order_item as i left join ".DB_PREFIX."deal_order as o on o.id = i.order_id where o.user_id = ".$id." and o.type = 3 and o.pay_status = 0 and order_status =0 ".$log_time_type_condition;
		$data['soon_count']=$GLOBALS['db']->getOne($sql_total);
		
		$sql_total = "select count(*) from ".DB_PREFIX."deal_order_item as i left join ".DB_PREFIX."deal_order as o on o.id = i.order_id where o.user_id = ".$id." and o.type = 3 and i.delivery_status = 0 and o.pay_status = 2 ".$log_time_type_condition;
		$data['in_count']=$GLOBALS['db']->getOne($sql_total);
		
		$sql_total = "select count(*) from ".DB_PREFIX."deal_order_item as i left join ".DB_PREFIX."deal_order as o on o.id = i.order_id where o.user_id = ".$id." and o.type = 3 and i.is_arrival = 0 and i.delivery_status = 1 and o.pay_status = 2 ".$log_time_type_condition;
		$data['complete_count']=$GLOBALS['db']->getOne($sql_total);;
		
		$sql_total = "select count(*) from ".DB_PREFIX."deal_order_item as i left join ".DB_PREFIX."deal_order as o on o.id = i.order_id where o.user_id = ".$id." and o.type = 3 and i.is_arrival = 1 and o.pay_status = 2 ".$log_time_type_condition;
		$data['is_arrival']=$GLOBALS['db']->getOne($sql_total);;
		$data['log_type']=$log_type;
		$data['log_time_type']=$log_time_type;
		$GLOBALS['tmpl']->assign("data",$data);
		

// 		//初始化参数
// 		$user_id = intval($GLOBALS['user_info']['id']);
		
		
// 		if(check_save_login()!=LOGIN_STATUS_LOGINED)
// 		{
// 			app_redirect(url("index","user#login"));
// 		}	
		 
// 		$user_data['id'] = $user_id;
		  
		$GLOBALS['tmpl']->assign("page_title","购买记录");
		$GLOBALS['tmpl']->display("uc/uc_totalbuy.html");
	}
	public function detail(){
	    global_run();
	    init_app_page();
	     
	    $id = intval($_REQUEST['id']);
	    $user_id=intval($GLOBALS['user_info']['id']);
	
	    //查询是否存在
	    $sql = "select
                    i.id,
                    i.is_set_consignee,
                    i.id as order_item_id,
                    i.duobao_item_id,
	                i.number as buynumber,
	                i.total_price,
                    i.delivery_status,
                    i.name,
	                i.order_id,
                    i.is_arrival,
                    i.deal_icon,
	                i.create_time,
                    i.lottery_sn,
                    i.buy_number as number,
                    i.consignee,
	                i.pay_status,
                    i.mobile,
                    i.region_info,
                    i.zip
                 from  ".DB_PREFIX."deal_order_item as i
	
                     where i.id=".$id." and i.user_id = ".$GLOBALS['user_info']['id']."
                         and i.type=3";
	
	    $order = $GLOBALS['db']->getRow($sql);
	    $order['total_price'] = format_sprintf_price($order['total_price']);
	    $order['create_time'] = to_date($order['create_time'],"Y-m-d H:i:m");
	     
	    if (!$order){
	        showErr("数据不存在",0,url("index","uc_totalbuy"));
	    }
	
	    $duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$order['duobao_item_id']);
	    $sql_id = "select id from fanwe_deal_cate  c where  c.is_fictitious=1";
	    $cate_id = $GLOBALS['db']->getAll($sql_id);
	    foreach ($cate_id as $k=>$v){
	        $cate_id[$k]=$v['id'];
	    }
	    if(in_array($duobao_item['cate_id'], $cate_id)){
	        $order['cate_id'] = $duobao_item['cate_id'];
	         
	    }
	    $order['max_buy'] = $duobao_item['max_buy'];
	    $order['price']   = $duobao_item['max_buy'] * $duobao_item['unit_price'];
	    $order['lottery_time'] = $duobao_item['lottery_time'];
	    $order['is_send_share'] = $duobao_item['is_send_share'];
	    $order['share_id'] = $duobao_item['share_id'];
	
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
	    //         var_dump($order['id']);exit;
	    if($delivery_notice){
	        $express_list = require_once APP_ROOT_PATH."system/express_cfg.php";
	        $express_info = $express_list[$delivery_notice['express_id']];
	        $delivery_notice['express_name'] = $express_info['name'];
	    }
	
	    $GLOBALS['tmpl']->assign("order",$order);
	    $GLOBALS['tmpl']->assign("duobao_item",$duobao_item);
	    $GLOBALS['tmpl']->assign("delivery_notice",$delivery_notice);
	    $GLOBALS['tmpl']->assign("consignee_list",$consignee_list);
	    $GLOBALS['tmpl']->assign("count_consignee",count($consignee_list));
	    $GLOBALS['tmpl']->display("uc/uc_totalbuy_detail.html");
	}
	public function verify_delivery()
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
	                    $data['status'] = 0;
	                    $data['info'] = "订单付款异常，无法确认收货";
	                    ajax_return($data);
	                }
	                
	                $GLOBALS['db']->query("update ".DB_PREFIX."deal_order set order_status = 1,update_time = ".NOW_TIME." where order_status = 0 and id = ".$order_info['id']);
	                if(!$GLOBALS['db']->affected_rows())
	                {
	                    $data['status'] = 0;
	                    $data['info'] = "结单失败";
	                    ajax_return($data);
	                }else{
//
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
//	                                send_msg($order_info['user_id'], "订单".$order_info['order_sn']."返还您{$content_ecv_money}夺宝币红包", "notify");
//	                            }
//	                        }
	                    }
	                    
//	                    //订单结单成功后，开始为订单商品进行分销推广奖利润计算
//	                    if( defined("FX_LEVEL") )
//	                    {
//	                        require_once APP_ROOT_PATH."system/model/fx.php";
//	                        send_fx_order_salary($order_info);
//	                    }
	                    order_log("订单完结", $order_info['id']);
	                    ajax_return($data);
	                }
	               
	            }
	            else
	            {
	                $data['status'] = 0;
	                $data['info'] = "收货失败";
	                ajax_return($data);
	            }
	        }
	        else
	        {
	            $data['status'] = 0;
	            $data['info'] = "订单未发货";
	            ajax_return($data);
	        }
	    }
	}
 	public function close()
	{
	    global_run();
	    require_once APP_ROOT_PATH . "system/model/deal_order.php";
	    
	    $order_id = intval($_REQUEST['order_id']);
	    // 关闭订单，退库存，退金额
	    $status = cancel_totalbuy_order($order_id, true);
	    
	    if ($status) {
	       $data['status'] = 1;
	       $data['info']   = '订单已关闭';
	       ajax_return($data);
	    }
	    
	    
	    
	} 

}
?>