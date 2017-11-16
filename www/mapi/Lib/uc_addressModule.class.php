<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class uc_addressApiModule extends MainBaseApiModule
{
	
	/**
	 * 	 会员中心配送地址列表接口
	 * 
	 * 	  输入：
	 *  
	 *  输出：
	 * user_login_status:int   0表示未登录   1表示已登录
	 * login_info:string 未登录状态的提示信息，已登录时无此项
	 * page_title:string 页面标题	 
	 * consignee_list:array:array 配送地址列表，结构如下
	 *  Array
		(
		    [0] => Array
		        (
		                [id] => 19 int 配送方式的主键
			            [user_id] => 71 int 当前会员ID
			            [region_lv1] => 1 int 国ID
			            [region_lv2] => 4 int 省ID
			            [region_lv3] => 53 int 市ID
			            [region_lv4] => 519 int 区ID
			            [address] => 群升国际 string 详细地址
			            [mobile] => 13555566666 string 手机号
			            [zip] => 350001 string 邮编
			            [consignee] => 李四 string 收货人姓名
			            [is_default] => 1
			            [region_lv1_name] => 中国 string 国名
			            [region_lv2_name] => 福建 string 省名
			            [region_lv3_name] => 福州 string 市名
			            [region_lv4_name] => 台江区 string 区名
		        )
		)
	 */
	public function index()
	{
		$root = array();		
			
		$user_data = $GLOBALS['user_info'];		
		$user_id = intval($user_data['id']);
		
		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED){			
			$root['user_login_status'] = $user_login_status;	
		}else{
			$root['user_login_status'] = 1;
			
	
			$list = $GLOBALS['db']->getAll("select id from ".DB_PREFIX."user_consignee where user_id = ".$user_id." limit 5");			
			foreach($list as $k=>$v){
				$consignee_info=load_auto_cache("consignee_info",array("consignee_id"=>$v['id']));				
				$list[$k] =	$consignee_info['consignee_info'];		
			}			
			$root['consignee_list'] = $list?$list:array();
			

			$root['page_title'].="配送地址列表";

		}
		
		return output($root);

	}

	
	
	
	
	/**
	 * 	 会员中心添加和修改地址接口
	 * 
	 * 	  输入：
	 *  id:5[int]  会员地址ID,修改地址时传入，新增地址时不传入
	 *  
	 *  输出：
	 * user_login_status:int   0表示未登录   1表示已登录
	 * login_info:string 未登录状态的提示信息，已登录时无此项
	 * page_title:string 页面标题	
	 * consignee_info:object 会员地址信息，修改地址时有值，新增地址时为空数组
	 *  Array
        (
                [id] => 19 int 配送方式的主键
	            [user_id] => 71 int 当前会员ID
	            [region_lv1] => 1 int 国ID
	            [region_lv2] => 4 int 省ID
	            [region_lv3] => 53 int 市ID
	            [region_lv4] => 519 int 区ID
	            [address] => 群升国际 string 详细地址
	            [mobile] => 13555566666 string 手机号
	            [zip] => 350001 string 邮编
	            [consignee] => 李四 string 收货人姓名
	            [is_default] => 1
	            [region_lv1_name] => 中国 string 国名
	            [region_lv2_name] => 福建 string 省名
	            [region_lv3_name] => 福州 string 市名
	            [region_lv4_name] => 台江区 string 区名
         )
	    region_lv1: array:array 一级地区列表
	     Array
	     (
	            [0] => Array
	                (
	                    [id] => 1 [int]地区id
	                    [pid] => 0 [int]父级地区id
	                    [name] => 中国 [string]  地区名称
	                    [region_level] => 1[int] 地区等级
	                    [selected] => 1 [int] 是否选中，未选中时没有此项
	                )
	
	      )
		 region_lv2: array:array 二级地区列表
		 Array
         (
            [0] => Array
                (
                    [id] => 2 [int]地区id
                    [pid] => 1 [int]父级地区id
                    [name] => 北京 [string]  地区名称
                    [region_level] => 2 [int] 地区等级
                    [selected] => 1 [int] 是否选中，未选中时没有此项
                )

            [1] => Array
                (
                    [id] => 3
                    [pid] => 1 
                    [name] => 安徽 
                    [region_level] => 2 
                )  
           ) 
 		region_lv3: array:array 三级地区列表
 		Array
        (
            [0] => Array
                (
                    [id] => 53 [int]地区id
                    [pid] => 4 [int]父级地区id
                    [name] => 福州 [string]  地区名称
                    [region_level] => 3 [int] 地区等级
                    [selected] => 1 [int] 是否选中，未选中时没有此项
                )

            [1] => Array
                (
                    [id] => 54
                    [pid] => 4
                    [name] => 龙岩
                    [region_level] => 3
                ) 
          )
 		region_lv4: array:array 四级地区列表
		Array
        (
            [0] => Array
                (
                    [id] => 518 [int]地区id
                    [pid] => 53 [int]父级地区id
                    [name] => 鼓楼区 [string]  地区名称
                    [region_level] => 4 [int] 地区等级
                    [selected] => 1 [int] 是否选中，未选中时没有此项
                )

            [1] => Array
                (
                    [id] => 519
                    [pid] => 53
                    [name] => 台江区
                    [region_level] => 4                    
                )
         ) 
	 */
	public function add()
	{
		$root = array();		
			
		$user_data = $GLOBALS['user_info'];		
		$user_id = intval($user_data['id']);
		
		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED){			
				$root['user_login_status'] = $user_login_status;	
		}else{
				$root['user_login_status'] = 1;
				$consignee_id=$GLOBALS['request']['id'];
				if($consignee_id>0)
				{	
					$consignee_data = load_auto_cache("consignee_info",array("consignee_id"=>$consignee_id));					
					$consignee_info = $consignee_data['consignee_info'];
					if($consignee_info['user_id']!=$user_id) exit;
					$region_lv1 = $consignee_data['region_lv1'];
					$region_lv2 = $consignee_data['region_lv2'];
					$region_lv3 = $consignee_data['region_lv3'];
					$region_lv4 = $consignee_data['region_lv4'];
				}else{
					$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."delivery_region where pid = 1");  //一级地址
				}
			
				$root['consignee_info'] = $consignee_info?$consignee_info:null;
				$root['region_lv1']=$region_lv1?$region_lv1:"";
				$root['region_lv2']=$region_lv2?$region_lv2:"";		
				$root['region_lv3']=$region_lv3?$region_lv3:"";		
				$root['region_lv4']=$region_lv4?$region_lv4:"";		
				$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
					
				$root['page_title'].="地址管理";
				//订单选择地址时传递的参数,用于返回订单选择地址页ctl=uc_winlog&act=winlog_address
				$root['order_item_id']=intval($GLOBALS['request']['order_item_id']);

		}
		
		return output($root);

	}	
	
	

	
	/**
	 * 	 会员中心配送地址保存接口
	 * 
	 * 	  输入：
	 *  id:int 配送地址id，新增时无此项
	 *  region_lv1:1[int] 一级地区id
	 *  region_lv2:8[int] 二级地区id
	 *  region_lv3:136[int] 三级地区id
	 *  region_lv4:1235[int] 四级地区id
	 *  consignee:陈新国 [string] 收件人
	 *  address:八一七中路5号[string] 详细地址
	 *  mobile:13500000000[string]手机
	 *  zip：350000[string]邮编
	 *   [xpoint] => 119.298042  x坐标
   		 [ypoint] => 26.085253  y坐标
	 *  输出：
	 * user_login_status:[int]   0表示未登录   1表示已登录
	 * login_info:[string] 未登录状态的提示信息，已登录时无此项
	 * add_status:[int] 新增修改地址的保存结果  1表示成功   0表示失败
	 * infos:收件人不能为空[string]  保存失败时返回的信息，成功时返回空

   
	 */
	public function save()
	{		
		$root = array();		

		$user_data = $GLOBALS['user_info'];		
		$user_id = intval($user_data['id']);
		$id = intval($GLOBALS['request']['id']);
		
		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED){			
			$root['user_login_status'] = $user_login_status;	
		}else{
			
			
			$root['user_login_status'] = 1;
			$root['add_status']=0;
			$root['infos']="";
			$root['order_item_id']=intval($GLOBALS['request']['order_item_id']);
			$root['is_total_buy']=intval($GLOBALS['request']['is_total_buy']);
			
			$consignee_data['user_id'] = $user_id;
			$consignee_data['region_lv1'] = intval($GLOBALS['request']['region_lv1'])?intval($GLOBALS['request']['region_lv1']):1;
			$consignee_data['region_lv2'] = intval($GLOBALS['request']['region_lv2']);
			$consignee_data['region_lv3'] = intval($GLOBALS['request']['region_lv3']);
			$consignee_data['region_lv4'] = intval($GLOBALS['request']['region_lv4']);
			$consignee_data['address'] = strim($GLOBALS['request']['address']);
			$consignee_data['mobile'] = strim($GLOBALS['request']['mobile']);
			$consignee_data['consignee'] = strim($GLOBALS['request']['consignee']);
			$consignee_data['zip'] = strim($GLOBALS['request']['zip']);
			$consignee_count = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_consignee where user_id = ".$user_id));
			
			if($consignee_data['region_lv2']==0||$consignee_data['region_lv3']==0||$consignee_data['region_lv4']==0)
			{
				$root['infos']="请选择完整的地区信息";
			}
			elseif($consignee_data['consignee']==''){
				$root['infos']="收件人不能为空";
			}elseif($consignee_data['address']==''){
				$root['infos']="详细地址不能为空";
			}elseif($consignee_data['mobile']==''){
				$root['infos']="手机不能为空";
			}elseif(!check_mobile($consignee_data['mobile'])){
				$root['infos']="手机格式不正确";
			}elseif($consignee_count>=5&&$id ==0){
				$root['infos']="配送地址最多5个";		
			}else{
				if($consignee_count==0){
					$consignee_data['is_default'] = 1;
					$empty_order=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where (region_info='' or address='' or consignee='' or mobile='') and user_id = ".$user_id);
					if($empty_order>0){
						$order_address['address']=$consignee_data['address'];
						$order_address['mobile']=$consignee_data['mobile'];
						$order_address['zip']=$consignee_data['zip'];
						$order_address['consignee']=$consignee_data['consignee'];
						$region_2=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."delivery_region where id =".$consignee_data['region_lv2']);
						$region_3=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."delivery_region where id =".$consignee_data['region_lv3']);
						$region_4=$GLOBALS['db']->getOne("select name from ".DB_PREFIX."delivery_region where id =".$consignee_data['region_lv4']);
						$order_address['region_info']=$region_2.$region_3.$region_4;
						$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$order_address,"UPDATE","(region_info='' or address='' or consignee='' or mobile='') and user_id=".$user_id);
						
					}
					
				}
								
				if($id == 0)	{
//					$GLOBALS['db']->query("update ".DB_PREFIX."user_consignee set is_default=0 where user_id=".$user_id);
//					$consignee_data['is_default'] = 1;
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$consignee_data);
					$root['consignee_id'] = intval( $GLOBALS['db']->insert_id() );
					
				}else{			
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_consignee",$consignee_data,"UPDATE","id=".$id." and user_id=".$user_id);
					rm_auto_cache("consignee_info",array("consignee_id"=>intval($id)));
					$root['consignee_id'] = $id;
				}
				
				$root['add_status'] = 1;					
				
			}

		}
		
		return output($root);

	}		
	
	
	/**
	 * 	 会员中心配送地址设为默认接口
	 * 
	 * 	  输入：
	 *  id:int 配送地址id

	 *  
	 *  输出：
	 * user_login_status:[int]   0表示未登录   1表示已登录
	 * login_info:[string] 未登录状态的提示信息，已登录时无此项
	 * set_status:[int] 设为默认的结果  1表示成功   0表示失败

   
	 */	
	public function set_default()
	{
		$root = array();		
			
		$user_data = $GLOBALS['user_info'];		
		$user_id = intval($user_data['id']);
		$id = intval($GLOBALS['request']['id']);
		
		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED){			
			$root['user_login_status'] = $user_login_status;	
		}else{
			$root['user_login_status'] = 1;
			$root['set_status']=0;
			$GLOBALS['db']->query("update ".DB_PREFIX."user_consignee set is_default=0 where user_id=".$user_id);
			$GLOBALS['db']->query("update ".DB_PREFIX."user_consignee set is_default=1 where id=".$id." and user_id=".$user_id);	
			if($GLOBALS['db']->affected_rows())
			{	
				$ids=$GLOBALS['db']->getAll("select id from ".DB_PREFIX."user_consignee where user_id=".$user_id);
				foreach($ids as $k=>$v){
					rm_auto_cache("consignee_info",array("consignee_id"=>intval($v['id'])));
				}
				
				$root['set_status']=1;
			}			
		}	
		return output($root);		
	}
	

	
	/**
	 * 	 会员中心删除地址接口
	 * 
	 * 	  输入：
	 *  id:int 配送地址id

	 *  
	 *  输出：
	 * user_login_status:[int]   0表示未登录   1表示已登录
	 * login_info:[string] 未登录状态的提示信息，已登录时无此项
	 * del_status:[int] 删除的结果  1表示成功   0表示失败

   
	 */	
	public function del()
	{
		$root = array();		
			
		$user_data = $GLOBALS['user_info'];		
		$user_id = intval($user_data['id']);
		$id = intval($GLOBALS['request']['id']);
		
		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED){			
			$root['user_login_status'] = $user_login_status;	
		}else{
			$root['user_login_status'] = 1;
			$root['del_status']=0;
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_consignee where id=".$id." and user_id=".$user_id);
			if($GLOBALS['db']->affected_rows())
			{
				$root['del_status']=1;
			}			
		}	
		return output($root);		
	}	
	
}
?>