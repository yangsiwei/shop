<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

class DealOrderAction extends CommonEnhanceAction{
	
	public function index()
	{
	    if(!isset($_REQUEST['delivery_status_item']))$_REQUEST['delivery_status_item'] = -1;
	    
		//列表过滤器，生成查询Map对象
		$model = D ('DealOrderItem');
		$map = $this->_search ($model);
		$map['type'] = 0;
		if(strim($_REQUEST['order_sn']))
		$map['order_sn'] = strim($_REQUEST['order_sn']);
		if(strim($_REQUEST['duobao_item_id']))
		$map['duobao_item_id'] = strim($_REQUEST['duobao_item_id']);
		if(strim($_REQUEST['user_name']))
		    $map['user_name'] = strim($_REQUEST['user_name']);
		
// 		$is_robot = intval($_REQUEST['is_robot']);
// 		$order_status=intval($_REQUEST['order_status']);
// 		if($order_status==5)	$map['order_status'] = 0;
// 		if($is_robot==0)
// 			unset($map['is_robot']);
// 		elseif($is_robot==2)
// 			$map['is_robot'] = 0;
// 		else
// 			$map['is_robot'] = 1;
		// 		$map['is_robot'] = 0;
		$delivery_status_item = intval($_REQUEST['delivery_status_item']);
		if(delivery_status_item==-1)
		{
		    unset($map['delivery_status_item']);
		}
		
		
		$condition = ' where t_doi.type=0 ';
		
		if(strim($_REQUEST['order_sn']))
		    $condition .= " and t_doi.order_sn='".strim($_REQUEST['order_sn'])."'";
		if(strim($_REQUEST['duobao_item_id']))
		    $condition .= " and t_doi.duobao_item_id='".intval($_REQUEST['duobao_item_id'])."'";
		if(strim($_REQUEST['user_name']))
		    $condition .= " and t_doi.user_name='".strim($_REQUEST['user_name'])."'";
		if(isset($_REQUEST['delivery_status_item'])&&$_REQUEST['delivery_status_item']!=-1)
		    $condition .=" and t_doi.delivery_status=".intval($_REQUEST['delivery_status_item']);
        if(isset($_REQUEST['is_set_consignee'])&&$_REQUEST['is_set_consignee']!=-1)
            $condition.=" and t_doi.is_set_consignee=".intval($_REQUEST['is_set_consignee']);
            //取得满足条件的记录数
		
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
		    $order = $_REQUEST ['_order'];
		} else {
		    $order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST ['_sort'] )) {
		    $sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
		    $sort = $asc ? 'asc' : 'desc';
		}
		
		$order_by = $order . " " . $sort;
		$count_sql = "select count(*) from ".DB_PREFIX."deal_order_item t_doi ".$condition;
		$count = $GLOBALS['db']->getOne($count_sql);
// 		echo($count_sql);
		if ($count > 0 ) {
		    //创建分页对象
		    if (! empty ( $_REQUEST ['listRows'] )) {
		        $listRows = $_REQUEST ['listRows'];
		    } else {
		        $listRows = '30';
		    }
		    $p = new Page ( $count, $listRows );
		    //分页查询数据
		

		    $limit = $p->firstRow . ',' . $p->listRows;
		    $sql = "select t_doi.* from ".DB_PREFIX."deal_order_item t_doi ".$condition." order by ".$order_by." limit ".$limit;
		    $voList = $GLOBALS['db']->getAll($sql);

		    //分页跳转的时候保证查询条件
		    foreach ( $map as $key => $val ) {
		        if (! is_array ( $val )) {
		            $p->parameter .= "$key=" . urlencode ( $val ) . "&";
		        }
		    }
		    //分页显示
		
		    $page = $p->show ();
		    //列表排序显示
		    $sortImg = $sort; //排序图标
		    $sortAlt = $sort == 'desc' ? l("ASC_SORT") : l("DESC_SORT"); //排序提示
		    $sort = $sort == 'desc' ? 1 : 0; //排序方式
		    //模板赋值显示
		    $this->assign ( 'list', $voList );
		    $this->assign ( 'sort', $sort );
		    $this->assign ( 'order', $order );
		    $this->assign ( 'sortImg', $sortImg );
		    $this->assign ( 'sortType', $sortAlt );
		    $this->assign ( "page", $page);
		    $this->assign ( "nowPage",$p->nowPage);
		}
		    //五倍开奖
        $five = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where fair_type='five' and has_lottery=1");
        foreach ($five as $k=>$v){
            $five[$k]['create_time']=to_date($v['lottery_time']);
            $a = explode(",",$v['luck_user_id']);
            $user =array();
            $dq=array();
            $ss=array();
            for ($i=0;$i<5;$i++){
                $id = $a[$i];
                $user1= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where id=".$id);
                $dq1 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id=".$id." and is_default=1");

//                foreach ($dq1 as $k=>$v){
//                    $region_lv2 = $v['region_lv2'];
//                    $region_lv3 = $v['region_lv3'];
//                    $dq2 = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."delivery_region where id=".$region_lv2);
//                    $dq3 = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."delivery_region where id=".$region_lv3);
//                    $dq1[$k]['dy1']= "中国"." ".$dq2 ." ".$dq3;
//                }
                array_push($user,$user1);
                array_push($dq,$dq1[0]);

            }
            $five[$k]['dy']= $dq;
            $five[$k]['luck_user_id']=$user[0][0]['user_name'].",".$user[1][0]['user_name'].",".$user[2][0]['user_name'].",".$user[3][0]['user_name'].",".$user[4][0]['user_name'];
        }
       // print_r($five);die;
		$this ->assign('five',$five);
		
	
		$this->display ();
	}
	
	public function trash()
	{
		//列表过滤器，生成查询Map对象
		$model = D ('DealOrderHistoryView');
		$map = $this->_search ($model);
		$map['type'] = 0;
		// 	$map['is_robot'] = 0;
		
		$this->_list( $model, $map ,"id");
		$this->display ();
	}
	
	
	
	
	
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("DealOrderHistory")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['order_sn'];
				}
				if($info) $info = implode(",",$info);
				$list = M("DealOrderHistory")->where ( $condition )->delete();	
		
				if ($list!==false) {
					//删除关联数据
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function view_order()
	{

		$id = intval($_REQUEST['id']);
		$order_info = M("DealOrder")->where("id=".$id." and type = 0")->find();
		$duobao_id = M("DealOrderItem")->where("order_id=".$id)->getField('duobao_id');
		$cate_id = M("DuobaoItem")->where("duobao_id=".$duobao_id)->getField('cate_id');
		$order_info['is_fictitious'] = M("DealCate")->where("id=".$cate_id)->getField('is_fictitious');
		$order_info['fictitious_info'] = M("DeliveryNotice")->where("order_id=".$id)->getField('fictitious_info');
		if(!$order_info)
		{
			$this->error(l("INVALID_ORDER"));
		}
		$order_deal_items = M("DealOrderItem")->where("order_id=".$order_info['id'])->findAll();
	
// 		var_dump($order_deal_items['is_fictitious']);exit;
		
		
		//夺宝订单里面没有地址信息，取用户的默认地址信息
		/* if($order_info['mobile']==''){
		    $user_consignee = D("UserConsignee")->where("user_id=".$order_info['user_id']." and is_default=1")->find();
		
		    $consignee_data = load_auto_cache("consignee_info",array("consignee_id"=>$user_consignee['id']));
		    $consignee_info = $consignee_data['consignee_info'];
		    if($consignee_info){
		        $order_info['region_info'] = $consignee_info['region_lv1_name']." ".$consignee_info['region_lv2_name']." ".$consignee_info['region_lv3_name']." ".$consignee_info['region_lv4_name'];
		        $order_info['address'] = $consignee_info['address'];
		        $order_info['mobile'] = $consignee_info['mobile'];
		        $order_info['zip'] = $consignee_info['zip'];
		        $order_info['consignee'] = $consignee_info['consignee'];
		    }
		} */
		//不符合发货流程，暂时舍弃
		
		$this->assign("order_deals",$order_deal_items);
		$this->assign("order_info",$order_info);
        
		
		$payment_notice = M("PaymentNotice")->where("order_id = ".$order_info['id']." and is_paid = 1")->order("pay_time desc")->findAll();
		$this->assign("payment_notice",$payment_notice);
		
		
		//输出订单日志
		$log_list = M("DealOrderLog")->where("order_id=".$order_info['id'])->order("log_time desc")->findAll();
		$this->assign("log_list",$log_list);

		//五倍开奖
        $zq = $id;
       if($zq){
           $id = intval($_REQUEST['id']);
           $five = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where fair_type='five' and has_lottery=1 and id=".$id);
//            print_r($five);die;

           foreach ($five as $k=>$v){
               $five[$k]['create_time']=to_date($v['lottery_time']);
               $a = explode(",",$v['luck_user_id']);
               $user =array();
               $dq=array();
               for ($i=0;$i<5;$i++){
                   $id = $a[$i];
                   $user1= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where id=".$id);
                   $dq1 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id=".$id." and is_default=1");

                foreach ($dq1 as $k=>$v){
                    $region_lv2 = $v['region_lv2'];
                    $region_lv3 = $v['region_lv3'];
                    $dq2 = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."delivery_region where id=".$region_lv2);
                    $dq3 = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."delivery_region where id=".$region_lv3);
                    $dq1[$k]['dy1']= "中国"." ".$dq2 ." ".$dq3;
                }
                   array_push($user,$user1);
                   array_push($dq,$dq1[0]);

               }
               $five[$k]['dy']= $dq;
               $five[$k]['luck_user_id']=$user[0][0]['user_name'].",".$user[1][0]['user_name'].",".$user[2][0]['user_name'].",".$user[3][0]['user_name'].",".$user[4][0]['user_name'];
           }


       }
   
        $this->assign("five",$five[0]);
        $this->assign("five1",$five[0]['dy']);
		
		$this->display();
	}

		//五倍开奖发货通知
	public function fa_huo(){
        $id = intval($_REQUEST['id']);
        $five = $GLOBALS['db']->getOne("select take_effect from ".DB_PREFIX."duobao_item where fair_type='five' and has_lottery=1 and id=".$id);
       // print_r($five);die;
        if($five ==0){
            $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set take_effect=1 where id=".$id);
            $user_id1= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log where duobao_item_id=".$id." and is_luck=1 ");

            $duobao1 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where id = ".$id. " and has_lottery=1");
            for($i=0;$i<5;$i++){
                $name =$duobao1[0]['name'];
                $lottery_sn =  $user_id1[$i]['lottery_sn'];
                $fh = $GLOBALS['db']->query("insert into ".DB_PREFIX."msg_box (content,user_id,create_time,is_read,is_delete,type,data,data_id) values ('您参与的 $name 夺宝活动，奖品已发货了！', ".$user_id1[$i]['user_id'].",".time().",0,0,'orderitem',0,'$lottery_sn')" );
            }
            $this->success ("发货成功");
        }
    }
	
	
	public function view_order_history()
	{
		$id = intval($_REQUEST['id']);
		$order_info = M("DealOrderHistory")->where("id=".$id." and type = 0")->find();
		if(!$order_info)
		{
			$this->error(l("INVALID_ORDER"));
		}
		$order_deal_items = unserialize($order_info['history_deal_order_item']);
		
		$this->assign("order_deals",$order_deal_items);
		$this->assign("order_info",$order_info);
		

		
		//输出订单日志
		
		$log_list = unserialize($order_info['history_deal_order_log']);
		$this->assign("log_list",$log_list);
		
		$this->display();
	}
	
	public function delivery()
	{
		$id = intval($_REQUEST['id']);
		$order_info = M("DealOrder")->where("id=".$id." and is_delete = 0 and type = 0")->find();
		if(!$order_info)
		{
			$this->error(l("INVALID_ORDER"));
		}
		

		$order_deal_items = M("DealOrderItem")->where("order_id=".$order_info['id'])->findAll();
		foreach($order_deal_items as $k=>$v)
		{
		    $cate_id = M("DuobaoItem")->where("deal_id=".$v['deal_id'])->order("id desc")->find();
		    $is_fictitious = M("DealCate")->where("id=".$cate_id['cate_id'])->find();
		}

		//输出快递接口
		$express_list = require_once APP_ROOT_PATH."system/express_cfg.php";
		$this->assign("express_list",$express_list);
		$this->assign("order_deals",$order_deal_items);
		$this->assign("order_info",$order_info);
		$this->assign("is_fictitious",$is_fictitious);
		$this->display();
	}
	
	
	public function do_delivery()
	{
		require_once APP_ROOT_PATH."system/model/deal_order.php";
		$silent = intval($_REQUEST['silent']);
		$order_id = intval($_REQUEST['order_id']);
		$order_deals = $_REQUEST['order_deals'];
		$delivery_sn = $_REQUEST['delivery_sn'];
		$fictitious_info = $_REQUEST['fictitious_info'];
		$express_id = intval($_REQUEST['express_id']);
		$memo = $_REQUEST['memo'];
		$order_info = M("DealOrder")->getById($order_id);
		
		
		if(!$order_deals)
		{
			if($silent==0)
			$this->error(l("PLEASE_SELECT_DELIVERY_ITEM"));
		}
		else
		{
		    $order_deal_items = M("DealOrderItem")->where("order_id=".$order_info['id'])->findAll();
		    foreach($order_deal_items as $k=>$v)
		    {
		        $cate_id = M("DuobaoItem")->where("deal_id=".$v['deal_id'])->order("id desc")->find();
		        $is_fictitious = M("DealCate")->where("id=".$cate_id['cate_id'])->find();
		    }
			if($is_fictitious['is_fictitious']!=1){
			    $user_consignee=M("UserConsignee")->where("user_id=".$order_info['user_id'])->findAll();
			    if(!$user_consignee)
			        $this->error("用户未填写收货地址");
			}
			
			
			$deal_names = array();
			foreach($order_deals as $order_deal_id)
			{
				$deal_info =$GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$order_deal_id);
				$deal_name = $deal_info['name'];
				array_push($deal_names,$deal_name);
				$rs = make_delivery_notice($order_id,$order_deal_id,$delivery_sn,$memo,$express_id);
				$resss = $GLOBALS['db']->query("update ".DB_PREFIX."delivery_notice set fictitious_info='".$fictitious_info."' where id=".$rs);
				if($rs)
				{
				    $up_sql = "update ".DB_PREFIX."deal_order_item set 
				        admin_memo = '".$order_info['admin_memo']."', 
                        memo = '".$order_info['memo']."' ,
                        region_info = '".$order_info['region_info']."' , 
                        address = '".$order_info['address']."',
                        mobile = '".$order_info['mobile']."' ,
                        zip = '".$order_info['zip']."' ,
                        consignee = '".$order_info['consignee']."',
				        delivery_status = 1,
				        is_arrival = 0,
				        create_date_ymd = '".to_date(NOW_TIME,"Y-m-d")."',
				        create_date_ym = '".to_date(NOW_TIME,"Y-m")."',
				        create_date_y = '".to_date(NOW_TIME,"Y")."',
				        create_date_m = '".to_date(NOW_TIME,"m")."',
				        create_date_d = '".to_date(NOW_TIME,"d")."' 
				        where id = ".$order_deal_id;

					$GLOBALS['db']->query($up_sql);			
				}
			}
			$deal_names = implode(",",$deal_names);
			
			send_delivery_mail($delivery_sn,$deal_names,$order_id);
			send_delivery_sms($delivery_sn,$deal_names,$order_id);
			//开始同步订单的发货状态
	
			$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set delivery_status = 2 where id = ".$order_id); //全部发
					
			M("DealOrder")->where("id=".$order_id)->setField("update_time",NOW_TIME);
						
			
			$msg = l("DELIVERY_SUCCESS");
									
			$this->assign("jumpUrl",U("DealOrder/view_order",array("id"=>$order_id)));		

			//查询快递名
			$express_name = M("Express")->where("id=".$express_id)->getField("name");
			
			
			order_log(l("DELIVERY_SUCCESS").$express_name.$delivery_sn.$_REQUEST['memo'],$order_id);
			
			
			
			distribute_order($order_id);
				
			if($is_fictitious['is_fictitious']==1){
			    send_msg($order_info['user_id'], $deal_info['name']."发货了，请注意查收", "orderitem", $deal_info['id']);
			}else{
			    send_msg($order_info['user_id'], $deal_info['name']."发货了，发货单号：".$delivery_sn, "orderitem", $deal_info['id']);
			}
			
			$wx_account = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_account where user_id = 0");
			send_wx_msg("OPENTM200565259", $order_info['user_id'], $wx_account,array("order_id"=>$order_id,"order_sn"=>$order_info['order_sn'],"company_name"=>$express_name,"delivery_sn"=>$delivery_sn,"order_item_id"=>$order_deal_id));
			
			if($silent==0)
			$this->success($msg);
		}
	}
	
	//查看快递
	public function check_delivery()
	{
		$express_id = intval($_REQUEST['express_id']);
		$typeNu = addslashes(trim($_REQUEST["express_sn"]));
		$express_list = require_once APP_ROOT_PATH."system/express_cfg.php";			
		$express_info = $express_list[$express_id];
		$typeCom = $express_info['code'];
		
		if(isset($typeCom)&&isset($typeNu)){
		
			$AppKey = app_conf("KUAIDI_APP_KEY");//请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY

			$data['msg'] = "http://www.kuaidi100.com/chaxun?com=".$typeCom."&nu=".$typeNu;
			$data['status'] = 1;   //页面查询
			ajax_return($data);
		}else{
			$data['msg'] = '查询失败，请重试';
			$data['status'] = 0;   //查询失败
			ajax_return($data);
		}
		exit();
	}	
	
	
	
	
	public function do_verify()
	{
		$order_item_id = intval($_REQUEST['order_item_id']);
		$coupon_id = intval($_REQUEST['coupon_id']);
		
		if($order_item_id)
		{
			$oi = $order_item_id;
			$data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$order_item_id);
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$data['order_id']);
			$delivery_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_notice where order_item_id = ".$order_item_id." and order_id = ".$data['order_id']." and is_arrival <> 1 order by delivery_time desc");
				
			if($delivery_notice)
			{
				require_once APP_ROOT_PATH."system/model/deal_order.php";
				$res = confirm_delivery($delivery_notice['notice_sn'],$order_item_id);
				if($res)
				{
					send_msg($order_info['user_id'], "订单经管理员审核，确认收货", "orderitem", $oi);
					$data['status'] = true;
					$data['info'] = "操作收货成功";
					ajax_return($data);
				}
				else
				{
					$data['status'] = 0;
					$data['info'] = "操作收货失败";
					ajax_return($data);
				}
			}
			else
			{
				$data['status'] = 0;
				$data['info'] = "订单已收货";
				ajax_return($data);
			}
		}
		
		
		
	}
	
}
?>