<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class uc_orderApiModule extends MainBaseApiModule
{
	
	
	/**
	 * 取消删除订单接口
	 * 
	 * 输入
	 * id: int 订单ID
	 * 
	 * 输出
	 * user_login_status:int 用户登录状态(1 已经登录/0 用户未登录/2临时用户)
	 * status: int 0失败 1成功
	 * info: string 消息
	 */
	public function cancel()
	{
		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED)
		{
			 $root['user_login_status'] = $user_login_status;
			 return output($root,0,"请先登录");
		}
		else
		{
			$root['user_login_status'] = $user_login_status;
			$id = intval($GLOBALS['request']['id']);
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$id." and is_delete = 0 and user_id = ".$GLOBALS['user_info']['id']);
			if($order_info)
			{
				require_once APP_ROOT_PATH."system/model/deal_order.php";				
				cancel_order($order_info['id']);				
			}
			else
			{
				return output($root,0,"订单不存在");
			}
		}
	}
	

	
	/**
	 * 确认收货接口(实体商品)
	 * 输入:
	 * item_id: int 订单商品表中的商品ID（order_item_id，非商品的ID）
	 *
	 * 输出
	 * user_login_status:int 用户登录状态(1 已经登录/0 用户未登录/2临时用户)
	 * status: int 0失败 1成功
	 * info: string 消息
	 *
	 */
	public function verify_delivery()
	{
		$user_login_status = check_login();
		if($user_login_status!=LOGIN_STATUS_LOGINED)
		{
			$root['user_login_status'] = $user_login_status;
			return output($root,0,"请先登录");
		}
		else
		{

			$root['user_login_status'] = $user_login_status;
			
			$id = intval($GLOBALS['request']['item_id']);
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
					$root['duobao_item_id']=$GLOBALS['db']->getOne("select duobao_item_id from ".DB_PREFIX."deal_order_item where id =".$id);
					return output($root,1,"确认收货成功,是否前往晒单！");
				}
				else
				{
					return output($root,0,"确认收货失败");
				}
			}
			else
			{
				return output($root,0,"订单未发货");
			}
		}
	}
	
	
	/**
	 * 快递查询接口
	 * 输入:
	 * item_id: int 订单商品表中的商品ID（order_item_id，非商品的ID）
	 *
	 * 输出
	 * status: int 0失败 1成功
	 * info: string 消息
	 * url: 快递查询的手机端接口地址(仅status为1返回)
	 */
	public function check_delivery()
	{
		$id = intval($GLOBALS['request']['item_id']);
		$user_id = intval($GLOBALS['user_info']['id']);
		require_once APP_ROOT_PATH."system/model/deal_order.php";
		$order_table_name = get_user_order_table_name($user_id);
		
		$delivery_notice = $GLOBALS['db']->getRow("select n.* from ".DB_PREFIX."delivery_notice as n left join ".$order_table_name." as o on n.order_id = o.id where n.order_item_id = ".$id." and o.user_id = ".$user_id." order by delivery_time desc");
		if($delivery_notice)
		{
			$express_id = intval($delivery_notice['express_id']);
			$typeNu = strim($delivery_notice["notice_sn"]);
			$express_list = require_once APP_ROOT_PATH."system/express_cfg.php";			
			$express_info = $express_list[$express_id];
			$typeCom = $express_info['code'];
			if(isset($typeCom)&&isset($typeNu))
			{
				$root['url'] = "http://m.kuaidi100.com/index_all.html?type=".$typeCom."&postid=".$typeNu;
				$root['page_title']="快递查询";
				return output($root);
			}
			else
			{
				return output("",0,"无效的快递查询");
			}
		}
		else
		{
			return output("",0,"非法操作");
		}
	}
	
}
?>