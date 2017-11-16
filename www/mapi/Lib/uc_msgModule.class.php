<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class uc_msgApiModule extends MainBaseApiModule
{
	
	/**
	 * 	 会员中心我的消息列表接口
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
                    [id] => 58[int] 消息id
                    [content] => 您的点评“店呵呵呵”被回复了 [string]消息内容
                    [create_time] => 2015-12-03 11:43:07 [string] 发送时间
                    [is_read] => 1 [int]是否已读
                    [type] => dp [string] 消息类型  
                    (消息一共5种类型  1、dp点评  2、notify通知  3、system系统 4、订单 orderitem  5、topic主题， 其中类型为订单和点评的需要点击跳转，订单消息的跳转地址是订单详细页，点评的跳转地址为相应的商品或门店详细页)
                    [data_id] => 11
                    [icon] => ./public/attachment/201509/19/09/55fcc17130048.jpg [string] 消息图片地址，可有可无，没有的时候统一显示消息图标
                    [short_title] => 对 [方维商家] 的点评 [string]消息短标题
                    [order_id] => 31 [int] 订单id(只有消息类型为订单的时候才有，用来跳转到相应的订单页)
                    [deal_id] => 1 [int]商品id  (只有消息类型为点评的时候才有，用来跳转到相应的商品详细页，商品id大于0时，门店id一定是0，门店id大于0时，商品id一定是0)
                    [location_id] => 0 [int]门店id (只有消息类型为点评的时候才有，用来跳转到相应的门店详细页，只有当商品id为0时，才跳转到相应门店)
                )
           )
	 */
	public function index()
	{
		$root = array();		
			
		$user_data = $GLOBALS['user_info'];		

		$page = intval($GLOBALS['request']['page'])?intval($GLOBALS['request']['page']):1; //当前分页

		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED){			
			$root['user_login_status'] = $user_login_status;
		
		}else{
			$root['user_login_status'] = 1;
			
			$page_size = PAGE_SIZE;
			$limit = (($page-1)*$page_size).",".$page_size;

			
			$sql = "select * from ".DB_PREFIX."msg_box where user_id = ".$GLOBALS['user_info']['id']." and is_delete = 0 order by is_read asc,create_time desc limit ".$limit;
			$sql_count = "select count(*) from ".DB_PREFIX."msg_box where user_id = ".$GLOBALS['user_info']['id']." and is_delete = 0 ";
			$list = $GLOBALS['db']->getAll($sql);
			$ids[] = 0;
			foreach($list as $k=>$v)
			{
				$list[$k] = load_msg($v['type'], $v);
				$list[$k]['create_time'] = to_date($v['create_time']);
				$ids[] = $v['id'];
				$list[$k]['icon']=get_abs_img_root(get_spec_image($list[$k]['icon'],50,50,1));
				unset($list[$k]['data']);
				//unset($list[$k]['link']);
				unset($list[$k]['user_id']);
				unset($list[$k]['is_delete']);
				unset($list[$k]['title']);
			}
			
			$count = $GLOBALS['db']->getOne($sql_count);
			$page_total = ceil($count/$page_size);

			
			$root['list'] = $list?$list:array();
						
			$ids_str = implode(",", $ids);
	 		$GLOBALS['db']->query("update ".DB_PREFIX."msg_box set is_read = 1 where user_id = ".$GLOBALS['user_info']['id']." and id in (".$ids_str.")");
			
	 		require_once APP_ROOT_PATH."system/model/user.php";
	 		load_user($GLOBALS['user_info']['id'],true);
			
			$root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
			
			
			$root['page_title'].="我的消息";

		}
		
		return output($root);

	}

	
	/**
	 * 	 会员中心删除消息接口
	 * 
	 * 	  输入：
	 *  id:int 消息id

	 *  
	 *  输出：
	 * user_login_status:[int]   0表示未登录   1表示已登录
	 * login_info:[string] 未登录状态的提示信息，已登录时无此项
	 * del_status:[int] 删除的结果  1表示成功   0表示失败

   
	 */		
	
	public function remove_msg()
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
			$GLOBALS['db']->query("update ".DB_PREFIX."msg_box set is_delete = 1 where id = ".$id." and user_id = ".$GLOBALS['user_info']['id']);
			if($GLOBALS['db']->affected_rows())
			{
				$root['del_status']=1;
			}			
		}	
		return output($root);				
	}	
	
	
}
?>