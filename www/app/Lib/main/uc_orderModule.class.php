<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



class uc_orderModule extends MainBaseModule
{
	public function index()
	{
		global_run();
		init_app_page();
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}	
		
		require_once APP_ROOT_PATH."app/Lib/page.php";
		$page_size = 10;
		$page = intval($_REQUEST['p']);
		if($page==0)
			$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;

		$user_id = $GLOBALS['user_info']['id'];
		
		require_once APP_ROOT_PATH."system/model/deal_order.php";
		$order_table_name = get_user_order_table_name($user_id);
		
		$sql = "select do.* from ".$order_table_name." as do where do.is_delete = 0 and ".
		" do.user_id = ".$user_id." and do.type = 0  order by do.create_time desc limit ".$limit;		
		$sql_count = "select count(*) from ".$order_table_name." as do where do.is_delete = 0 and ".
		" do.user_id = ".$user_id." and do.type = 0 ";
		
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $k=>$v)
		{
			$list[$k]['create_time'] = to_date($v['create_time']);
			$list[$k]['pay_amount'] = format_price($v['pay_amount']);
			$list[$k]['total_price'] = format_price($v['total_price']);
			if($v['deal_order_item'])
			{
				$list[$k]['deal_order_item'] = unserialize($v['deal_order_item']);				
			}
			else
			{
				$order_id = $v['id'];
				update_order_cache($order_id);
				$list[$k]['deal_order_item'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order_item where order_id = ".$order_id);
			}
			$list[$k]['c'] = count($list[$k]['deal_order_item']);
			foreach($list[$k]['deal_order_item'] as $kk=>$vv)
			{
				$list[$k]['deal_order_item'][$kk]['total_price'] = format_price($vv['total_price']);
				$deal_info = load_auto_cache("deal",array("id"=>$vv['deal_id']));
				$list[$k]['deal_order_item'][$kk]['url'] = $deal_info['url'];
			}
		}
		
		$count = $GLOBALS['db']->getOne($sql_count);

		$page = new Page($count,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		$GLOBALS['tmpl']->assign("list",$list);
		$GLOBALS['tmpl']->assign("page_title","我的订单");
		assign_uc_nav_list();
		$GLOBALS['tmpl']->display("uc/uc_order_index.html");
	}
	
	/**
	 * 快递查询
	 */
	public function check_delivery(){
	    global_run();
	    init_app_page();
	     
	    $item_id = intval($_REQUEST['item_id']);
	    $user_id = intval($GLOBALS['user_info']['id']);
	    require_once APP_ROOT_PATH."system/model/deal_order.php";
	    $order_table_name = get_user_order_table_name($user_id);
	
	    $delivery_notice = $GLOBALS['db']->getRow("select n.* from ".DB_PREFIX."delivery_notice as n left join ".$order_table_name." as o on n.order_id = o.id where n.order_item_id = ".$item_id." and o.user_id = ".$user_id." order by delivery_time desc");
	    if($delivery_notice)
	    {
	        $data['status'] = true;
	         
	        $express_id = intval($delivery_notice['express_id']);
	        $typeNu = strim($delivery_notice["notice_sn"]);
	        $express_list = require_once APP_ROOT_PATH."system/express_cfg.php";
	        $express_info = $express_list[$express_id];
	        $typeCom = $express_info['code'];
	        if(isset($typeCom)&&isset($typeNu)){
	
	            $AppKey = app_conf("KUAIDI_APP_KEY");//请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY
	            $url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$typeCom.'&nu='.$typeNu.'&show=0&muti=1&order=asc';
	
	             
	            //优先使用curl模式发送数据
	            //KUAIDI_TYPE : 1. API查询 2.页面查询
	            if (app_conf("KUAIDI_TYPE")==''){
	                $data = es_session::get(md5($url));
	                if(empty($data)||(NOW_TIME - $data['time'])>600)
	                {
	                    $api_result = get_delivery_api_content($url);
	                    $api_result_status = $api_result['status'];
	                    $get_content = $api_result['html'];
	
	                    //请勿删除变量$powered 的信息，否者本站将不再为你提供快递接口服务。
	                    $powered = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com （快递100）</a> 网站提供 ';
	                    $data['html'] = $get_content . '<br/>' . $powered;
	                    $data['status'] = true;   //API查询
	                    $data['time'] = NOW_TIME;
	                    if($api_result_status){
	                        es_session::set(md5($url),$data);
	                    }
	                }
	                ajax_return($data);
	            }elseif (app_conf("KUAIDI_TYPE")==2){
	                if($typeCom && $typeNu){
	                    $url = "http://www.kuaidi100.com/applyurl?key=".$AppKey."&com=".$typeCom."&nu=".$typeNu;
	                    $api_url = trim(file_get_contents($url));
	                    $html = '<iframe name="kuaidi100" src="'.$api_url.'" width="600" height="380" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no"></iframe>';
	                    $data['html'] = $html;
	                    $data['status'] = true;   //API查询
	                    $data['time'] = NOW_TIME;
	                    ajax_return($data);
	                }else{
	                    $data['status'] = false;
	                    $data['info'] = "无效的快递查询";
	                    ajax_return($data);
	                }
	                 
	            }else{
	                $data['url'] = "http://m.kuaidi100.com/index_all.html?type=".$typeCom."&postid=".$typeNu;
	                app_redirect($data['url']);
	            }
	
	        }else{
	            if(app_conf("KUAIDI_TYPE")==1)
	            {
	                $data['status'] = false;
	                $data['info'] = "无效的快递查询";
	                ajax_return($data);
	            }else{
	                init_app_page();
	                showErr("非法的快递查询");
	            }
	        }
	    }else{
	        if(app_conf("KUAIDI_TYPE")==1){
	            $data['status'] = false;
	            ajax_return($data);
	        }else{
	            init_app_page();
	            showErr("非法的快递查询");
	        }
	    }
	}
	
	/**
	 * 确认收货
	 */
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
					ajax_return($data);
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
	
	
	
	public function refuse_delivery()
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
			$content = strim($_REQUEST['content']);

			if($content=="")
			{
				$data['status'] = 0;
				$data['info'] = "请输入具体说明";
				ajax_return($data);
			}
			
			$delivery_notice = $GLOBALS['db']->getRow("select n.* from ".DB_PREFIX."delivery_notice as n left join ".$order_table_name." as o on n.order_id = o.id where n.order_item_id = ".$id." and o.user_id = ".$user_id." and is_arrival = 0 order by delivery_time desc");
			if($delivery_notice)
			{
				require_once APP_ROOT_PATH."system/model/deal_order.php";
				$res = refuse_delivery($delivery_notice['notice_sn'],$id);
				if($res)
				{
					
					$msg = array();
					$msg['rel_table'] = "deal_order";
					$msg['rel_id'] = $delivery_notice['order_id'];
					$msg['title'] = "订单维权";
					$msg['content'] = "订单维权：".$content;
					$msg['create_time'] = NOW_TIME;
					$msg['user_id'] = $GLOBALS['user_info']['id'];
					$GLOBALS['db']->autoExecute(DB_PREFIX."message",$msg);
					
					$data['status'] = true;
					$data['info'] = "维权提交成功";
					ajax_return($data);
				}
				else
				{
					$data['status'] = 0;
					$data['info'] = "维权提交失败";
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
	
	/**
	 * 删除订单
	 */
	public function cancel()
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
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$id." and is_delete = 0 and user_id = ".$GLOBALS['user_info']['id']);
			if($order_info)
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_delete = 1 where (order_status = 1 or pay_status = 0) and is_delete = 0 and user_id = ".$GLOBALS['user_info']['id']." and id = ".$id);
				if($GLOBALS['db']->affected_rows())
				{
					require_once APP_ROOT_PATH."system/model/deal_order.php";
					//开始退已付的款
					if($order_info['pay_status']==0&&$order_info['pay_amount']>0)
					{
						$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set pay_amount = 0,ecv_id = 0,ecv_money=0,account_money = 0 where id = ".$order_info['id']);	
						require_once APP_ROOT_PATH."system/model/user.php";
						if($order_info['account_money']>0)
						{
							modify_account(array("money"=>$order_info['account_money']), $order_info['user_id'],"退款：取消订单号为{$order_info['order_sn']}的订单，退款到余额。");
							order_log("用户取消订单，退回余额支付 ".$order_info['account_money']." 夺宝币", $order_info['id']);
						}
						if($order_info['ecv_id'])
						{
							$GLOBALS['db']->query("update ".DB_PREFIX."ecv set use_count = use_count - 1 where id = ".$order_info['ecv_id']);
							order_log("用户取消订单，代金券退回 ", $order_info['id']);
						}
						
					}
					over_order($order_info['id']);
					$data['status'] = 1;
					$data['info'] = "订单删除成功";
					ajax_return($data);
				}
				else
				{
					$data['status'] = 0;
					$data['info'] = "订单删除失败";
					ajax_return($data);
				}
			}
			else
			{
				$data['status'] = 0;
				$data['info'] = "订单不存在";
				ajax_return($data);
			}
		}
	}
	
	
	/**
	 * 查看订单内容
	 */
	public function view()
	{
		global_run();
		init_app_page();
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}
		
		$GLOBALS['tmpl']->assign("page_title","我的订单");
		assign_uc_nav_list();
		
		$id = intval($_REQUEST['id']);
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where is_delete = 0 and user_id = ".$GLOBALS['user_info']['id']." and id = ".$id);
		if($order_info)
		{
			if($order_info['deal_order_item'])
			{
				$order_info['deal_order_item'] = unserialize($order_info['deal_order_item']);
			}
			else
			{
				update_order_cache($order_info['id']);
				$order_info['deal_order_item'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order_item where order_id = ".$order_info['id']);
			}
			
			$order_info['create_time'] = to_date($order_info['create_time']);
			$order_info['pay_amount_format'] = format_price($order_info['pay_amount']);
			$order_info['total_price_format'] = format_price($order_info['total_price']);
			$order_info['delivery_fee_format'] = format_price($order_info['delivery_fee']);
			
			$order_info['region_lv1'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_region where id = ".$order_info['region_lv1']);
			$order_info['region_lv2'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_region where id = ".$order_info['region_lv2']);
			$order_info['region_lv3'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_region where id = ".$order_info['region_lv3']);
			$order_info['region_lv4'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_region where id = ".$order_info['region_lv4']);
			$order_info['c'] = count($order_info['deal_order_item']);
			
			foreach($order_info['deal_order_item'] as $kk=>$vv)
			{
				$order_info['deal_order_item'][$kk]['total_price'] = format_price($vv['total_price']);
				$deal_info = load_auto_cache("deal",array("id"=>$vv['deal_id']));
				$order_info['deal_order_item'][$kk]['url'] = $deal_info['url'];
				$order_info['deal_order_item'][$kk]['forbid_sms'] = $deal_info['forbid_sms'];
				$order_info['deal_order_item'][$kk]['coupon'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_coupon where is_valid > 0 and user_id = ".$GLOBALS['user_info']['id']." and order_deal_id = ".$vv['id']);
			}
			$order_info['payment'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where id = ".$order_info['payment_id']);
			$GLOBALS['tmpl']->assign("order_info",$order_info);
			
			//输出收款单日志
			$payment_list_res = load_auto_cache("cache_payment");
			foreach($payment_list_res as $k=>$v)
			{
				$payment_list[$v['id']] = $v;
			}
			$payment_notice_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment_notice where order_id = ".$order_info['id']." and is_paid = 1 order by create_time desc");
			foreach($payment_notice_list as $k=>$v)
			{
				$payment_notice_list[$k]['payment'] = $payment_list[$v['payment_id']];
			}
			$GLOBALS['tmpl']->assign("payment_notice_list",$payment_notice_list);
			
			
			//订单日志
			$order_logs = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order_log where order_id = ".$order_info['id']." order by id desc");
			$GLOBALS['tmpl']->assign("order_logs",$order_logs);
			
			$GLOBALS['tmpl']->assign("NOW_TIME",NOW_TIME);
			$GLOBALS['tmpl']->display("uc/uc_order_view.html");
		}
		else
		{
			showErr("订单不存在");
		}
	}
	
	/**
	 * 退款申请
	 */
	public function refund()
	{
		global_run();
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			$data['status'] = 1000;
			ajax_return($data);
		}
		else
		{
			$did = intval($_REQUEST['did']);
			$cid = intval($_REQUEST['cid']);
			
			if($did)
			{
				//退单
				$deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$did);		
				$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = '".$deal_order_item['order_id']."' and order_status = 0 and user_id = ".$GLOBALS['user_info']['id']);
				if($order_info)
				{										
					if($deal_order_item['delivery_status']==0&&$order_info['pay_status']==2&&$deal_order_item['is_refund']==1)
					{
						if($deal_order_item['refund_status']==0)
						{
							$data['status'] = true;
							$GLOBALS['tmpl']->assign("did",$did);
							$data['html'] = $GLOBALS['tmpl']->fetch("inc/refund_form.html");
							ajax_return($data);
						}
						else
						{
							$data['status'] = 0;
							$data['info'] = "不允许退款";
							ajax_return($data);
						}
					}
					else
					{
						$data['status'] = 0;
						$data['info'] = "非法操作";
						ajax_return($data);
					}
				}
				else
				{
					$data['status'] = 0;
					$data['info'] = "非法操作";
					ajax_return($data);
				}
			}
			elseif($cid)
			{
				//退券
				$coupon = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_coupon where user_id = ".$GLOBALS['user_info']['id']." and id = ".$cid);
				if($coupon)
				{
					if($coupon['refund_status']==0&&$coupon['confirm_time']==0)//从未退过款可以退款，且未使用过
					{
						if($coupon['any_refund']==1||($coupon['expire_refund']==1&&$coupon['end_time']>0&&$coupon['end_time']<NOW_TIME))//随时退或过期退已过期
						{
							$data['status'] = true;
							$GLOBALS['tmpl']->assign("cid",$cid);
							$data['html'] = $GLOBALS['tmpl']->fetch("inc/refund_form.html");
							ajax_return($data);
						}
						else
						{
							$data['status'] = 0;
							$data['info'] = "不允许退款";
							ajax_return($data);
						}
					}
					else
					{
						$data['status'] = 0;
						$data['info'] = "非法操作";
						ajax_return($data);
					}
				}
				else
				{
					$data['status'] = 0;
					$data['info'] = "非法操作";
					ajax_return($data);
				}
			}
			else
			{
				$data['status'] = 0;
				$data['info'] = "非法操作";
				ajax_return($data);
			}
			
		}
	}
	
	
	
	/**
	 * 退款申请
	 */
	public function do_refund()
	{
		global_run();
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			$data['status'] = 1000;
			ajax_return($data);
		}
		else
		{
			$did = intval($_REQUEST['did']);
			$cid = intval($_REQUEST['cid']);
			$content = strim($_REQUEST['content']);
			if(empty($content))
			{
				$data['status'] = 0;
				$data['info'] = "请填写退款原因";
				ajax_return($data);
			}
			if($did)
			{
				//退单
				$deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$did);
				$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = '".$deal_order_item['order_id']."' and order_status = 0 and user_id = ".$GLOBALS['user_info']['id']);
				if($order_info)
				{							
					if($deal_order_item['delivery_status']==0&&$order_info['pay_status']==2&&$deal_order_item['is_refund']==1)
					{
						if($deal_order_item['refund_status']==0)
						{
							//执行退单,标记：deal_order_item表与deal_order表，
							$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set refund_status = 1 where id = ".$deal_order_item['id']);
							$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set refund_status = 1 where id = ".$deal_order_item['order_id']);
							
							$msg = array();
							$msg['rel_table'] = "deal_order";
							$msg['rel_id'] = $deal_order_item['order_id'];
							$msg['title'] = "退款申请";
							$msg['content'] = "退款申请：".$content;
							$msg['create_time'] = NOW_TIME;
							$msg['user_id'] = $GLOBALS['user_info']['id'];
							$GLOBALS['db']->autoExecute(DB_PREFIX."message",$msg);
							
							update_order_cache($deal_order_item['order_id']);
							
							order_log($deal_order_item['sub_name']."申请退款，等待审核", $deal_order_item['order_id']);
							
							require_once APP_ROOT_PATH."system/model/deal_order.php";
							distribute_order($order_info['id']);
							
							$data['status'] = true;
							$data['info'] = "退款申请已提交，请等待审核";
							ajax_return($data);
						}
						else
						{
							$data['status'] = 0;
							$data['info'] = "不允许退款";
							ajax_return($data);
						}
					}
					else
					{
						$data['status'] = 0;
						$data['info'] = "非法操作";
						ajax_return($data);
					}
				}
				else
				{
					$data['status'] = 0;
					$data['info'] = "非法操作";
					ajax_return($data);
				}
			}
			elseif($cid)
			{
				//退券
				$coupon = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_coupon where user_id = ".$GLOBALS['user_info']['id']." and id = ".$cid);
				if($coupon)
				{
					if($coupon['refund_status']==0&&$coupon['confirm_time']==0)//从未退过款可以退款，且未使用过
					{
						if($coupon['any_refund']==1||($coupon['expire_refund']==1&&$coupon['end_time']>0&&$coupon['end_time']<NOW_TIME))//随时退或过期退已过期
						{
							//执行退券
							$GLOBALS['db']->query("update ".DB_PREFIX."deal_coupon set refund_status = 1 where id = ".$coupon['id']);
							$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set refund_status = 1 where id = ".$coupon['order_deal_id']);
							$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set refund_status = 1 where id = ".$coupon['order_id']);
							
							$deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$coupon['order_deal_id']);
							
							$msg = array();
							$msg['rel_table'] = "deal_order";
							$msg['rel_id'] = $coupon['order_id'];
							$msg['title'] = "退款申请";
							$msg['content'] = $content;
							$msg['create_time'] = NOW_TIME;
							$msg['user_id'] = $GLOBALS['user_info']['id'];
							$GLOBALS['db']->autoExecute(DB_PREFIX."message",$msg);
							update_order_cache($coupon['order_id']);
							
							order_log($deal_order_item['sub_name']."申请退一张消费券，等待审核", $coupon['order_id']);
							
							require_once APP_ROOT_PATH."system/model/deal_order.php";
							distribute_order($coupon['order_id']);
							$data['status'] = true;
							$data['info'] = "退款申请已提交，请等待审核";
							ajax_return($data);
						}
						else
						{
							$data['status'] = 0;
							$data['info'] = "不允许退款";
							ajax_return($data);
						}
					}
					else
					{
						$data['status'] = 0;
						$data['info'] = "非法操作";
						ajax_return($data);
					}
				}
				else
				{
					$data['status'] = 0;
					$data['info'] = "非法操作";
					ajax_return($data);
				}
			}
			else
			{
				$data['status'] = 0;
				$data['info'] = "非法操作";
				ajax_return($data);
			}
				
		}
	}
}
?>