<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class uc_winlogApiModule extends MainBaseApiModule
{
	
	/**
	 * 	 会员中心中奖记录接口
	 * 
	 * 	  输入：
	 *  page:int 当前的页数
	 *  
	 *  输出：
	 * user_login_status:int   0表示未登录   1表示已登录  2表示临时登录
	 * login_info:string 未登录状态的提示信息，已登录时无此项
	 * page_title:string 页面标题
	 * page:array 分页信息 array("page"=>当前页数,"page_total"=>总页数,"page_size"=>分页量,"data_total"=>数据总量);
	 * list:array:array 消息列表，结构如下
 		Array
        (
            [0] => Array
                (
                    [id] => 58[int] 订单id
                    
                    [create_time] => 2015-12-03 11:43:07 [string] 下单时间
                    [order_status] => 1 订单状态[int]0 开放状态，不可删除  1结单状态，可删除
                    [region_info] => 福建福州鼓楼 [string] 地区信息 
                    [address] => 福大怡山文传园23号 详细地址
                    [mobile] => 13333333333 联系人shji
                    [zip] => 350000 邮编
                    [consignee] => 张三 收货人
                    [duobao_item_id] => 10000392  夺宝期数
                    [delivery_status] => 0  发货状态 0未发货   1已发货 5无需发货
                    [name] => Apple Watch Sport 38毫米 铝金属表壳 运动表带  [string]奖品名称
                    [is_arrival] => 0  是否收货 0未收货  1已收货  2没收到货
                    [deal_icon] => http://192.168.1.13/yydb/public/attachment/201601/23/14/56a31727745f6_50x50.jpg  奖品图片
                    [lottery_sn] => 0  中奖号码
                )
           )
	 */
	public function index()
	{
		$root = array();		
			
		$user_data = $GLOBALS['user_info'];		

		$page = intval($GLOBALS['request']['page'])?intval($GLOBALS['request']['page']):1; //当前分页
		$data_id = intval($GLOBALS['request']['data_id']);
		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED){			
			$root['user_login_status'] = $user_login_status;
		
		}else{
			$root['user_login_status'] = 1;
			
			//获取用户默认收货地址
			//$region_status = 0;
			/* $user_consignee = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where user_id=".intval($user_data['id'])." and is_default=1");			
			if($user_consignee){
			    $consignee_data = load_auto_cache("consignee_info",array("consignee_id"=>intval($user_consignee['id'])));
			    $consignee_info = $consignee_data['consignee_info'];
			    
			    if($consignee_info){
			        $region_status = 1;
			    }
			} */
			
			
			$page_size = PAGE_SIZE;
			$limit = (($page-1)*$page_size).",".$page_size;

			$condition=" 1=1 and ";
			if($data_id>0) $condition=" i.id=".$data_id." and ";
			$sql = "select i.id,i.duobao_item_id,o.create_time,o.order_status,o.region_info,o.address,o.mobile,o.zip,o.consignee,i.is_set_consignee,i.delivery_status,i.name,i.is_arrival,i.deal_icon,i.lottery_sn,i.is_send_share from ".DB_PREFIX."deal_order as o left join ".DB_PREFIX."deal_order_item as i on o.id=i.order_id where ".$condition." o.user_id = ".$GLOBALS['user_info']['id']." and o.type=0 order by o.create_time desc limit ".$limit;
			$sql_count = "select count(*) from ".DB_PREFIX."deal_order as o left join ".DB_PREFIX."deal_order_item as i on o.id=i.order_id where ".$condition." o.user_id = ".$GLOBALS['user_info']['id']." and o.type=0";
			$list = $GLOBALS['db']->getAll($sql);
			$sql_id = "select id from fanwe_deal_cate  c where  c.is_fictitious=1";
			$id = $GLOBALS['db']->getAll($sql_id);
			foreach ($id as $k=>$v){
			    $id[$k]=$v['id'];
			}

            
			foreach($list as $k=>$v)
			{

			    if (!$v['id']) {
			        continue;
			    }
			    
				$list[$k]['create_time'] = to_date($v['create_time']);
				$list[$k]['deal_icon']=get_abs_img_root(get_spec_image($list[$k]['deal_icon'],200,200,1));
                $list[$k]['region_status'] = $region_status;
                $duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$v['duobao_item_id']);
                if(in_array($duobao_item['cate_id'], $id)){
                    $delivery_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_notice where order_item_id = ".$v['id']);
                    $list[$k]['fictitious_info'] = $delivery_notice['fictitious_info'];
                    $list[$k]['cate_id'] = $duobao_item['cate_id'];
                     
                }
              
                
			}
			 
			$user_id = $GLOBALS['user_info']['id'];
			
			// 更新中奖提醒为1
			$GLOBALS['db']->query( "UPDATE `".DB_PREFIX."deal_order_item` SET `is_read`='1' WHERE (`type`='0') AND (`is_read`='0') AND (`user_id`='".$user_id."')" );
			
			$count = $GLOBALS['db']->getOne($sql_count);
			$page_total = ceil($count/$page_size);

			$root['user_avatar'] = get_abs_img_root(get_muser_avatar($user_data['id'],"big"))?get_abs_img_root(get_muser_avatar($user_data['id'],"big")):"";
			$root['user_logo'] = $user_data['user_logo']?get_abs_img_root($user_data['user_logo']):$root['user_avatar'];
			$root['list'] = $list?$list:array();
						

			$root['user_name']=$GLOBALS['user_info']['user_name'];
			$root['user_id']=$GLOBALS['user_info']['id'];
			$root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
			
			$root['is_app']   = $GLOBALS['is_app'] ? 1:0;
			$root['page_title'].="中奖记录";

		}
		
		return output($root);

	}

	
	/**
	 * 	 会员中心删除记录接口
	 * 
	 * 	  输入：
	 *  id:int 消息id

	 *  
	 *  输出：
	 * user_login_status:[int]   0表示未登录   1表示已登录
	 * login_info:[string] 未登录状态的提示信息，已登录时无此项
	 * del_status:[int] 删除的结果  1表示成功   0表示失败

   
	 */		
	
	public function remove_log()
	{
//		$root = array();		
//			
//		$user_data = $GLOBALS['user_info'];		
//		$user_id = intval($user_data['id']);
//		$id = intval($GLOBALS['request']['id']);
//		
//		$user_login_status = check_login();
//		if($user_login_status!=LOGIN_STATUS_LOGINED){			
//			$root['user_login_status'] = $user_login_status;	
//		}else{
//			$root['user_login_status'] = 1;
//			$root['del_status']=0;
//			$GLOBALS['db']->query("update ".DB_PREFIX."msg_box set is_delete = 1 where id = ".$id." and user_id = ".$GLOBALS['user_info']['id']);
//			if($GLOBALS['db']->affected_rows())
//			{
//				$root['del_status']=1;
//			}			
//		}	
//		return output($root);				
	}	
	
	public function winlog_address()
	{
		$root = array();		
			
		$user_data = $GLOBALS['user_info'];		
		$user_id = intval($user_data['id']);
		
		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED){			
			$root['user_login_status'] = $user_login_status;	
		}else{
			$root['user_login_status'] = 1;
			
			$root['order_item_id'] = intval($GLOBALS['request']['order_item_id']);
			$list = $GLOBALS['db']->getAll("select id from ".DB_PREFIX."user_consignee where user_id = ".$user_id." limit 5");			
			foreach($list as $k=>$v){
				$consignee_info=load_auto_cache("consignee_info",array("consignee_id"=>$v['id']));				
				$list[$k] =	$consignee_info['consignee_info'];		
			}			
			$root['consignee_list'] = $list?$list:array();
			

			$root['page_title'].="订单地址选择";

		}
		
		return output($root);
	}	
	public function uc_luck_confirm_address()
	{
		$consignee_id = intval($GLOBALS['request']['consignee_id']);
	    $order_item_id = intval($GLOBALS['request']['order_item_id']);
		
		$user_login_status = check_login();
		
		if($user_login_status!=LOGIN_STATUS_LOGINED){			
			$root['user_login_status'] = $user_login_status;
		}else{
	    //验证地址是否存在
			$consignee_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_consignee where id=".$consignee_id." and user_id=".$GLOBALS['user_info']['id']);
			if(!$consignee_info){
				$root['status'] = 1;
				$root['info'] = "数据错误请重新选择";
				return output ( $root, 0, $root['info'] );
			}
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_item",array("is_set_consignee"=>1),"UPDATE"," id =".$order_item_id." and user_id = ".$GLOBALS['user_info']['id']);
			if ($GLOBALS['db']->affected_rows()){
				//更新订单中的地址
				$order_id = $GLOBALS['db']->getOne("select order_id from ".DB_PREFIX."deal_order_item where id =".$order_item_id);
				
				$region_conf = load_auto_cache ( "delivery_region" );
				$region_lv1 = intval ( $consignee_info ['region_lv1'] );
				$region_lv2 = intval ( $consignee_info ['region_lv2'] );
				$region_lv3 = intval ( $consignee_info ['region_lv3'] );
				$region_lv4 = intval ( $consignee_info ['region_lv4'] );
				$region_info = $region_conf [$region_lv1] ['name'] . " " . $region_conf [$region_lv2] ['name'] . " " . $region_conf [$region_lv3] ['name'] . " " . $region_conf [$region_lv4] ['name'];
				
				$order ['region_info'] = $region_info;
				$order ['address'] = strim ( $consignee_info ['address'] );
				$order ['mobile'] = strim ( $consignee_info ['mobile'] );
				$order ['consignee'] = strim ( $consignee_info ['consignee'] );
				$order ['zip'] = strim ( $consignee_info ['zip'] );

				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order,"UPDATE"," id = ".$order_id);
				//update_order_consignee($order_id,$consignee_info);
				$root['status'] = 1;
			}else{
				$root['status'] = 0;
				$root['info'] ="数据错误请重新选择";
				return output ($root,0,$root['info'] );
			}
		}
	    return output($root);
	}
	
	
}
?>