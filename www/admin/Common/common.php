<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

if (!defined('THINK_PATH')) exit();

//过滤请求
filter_request($_REQUEST);
filter_request($_GET);
filter_request($_POST);
define("AUTH_NOT_LOGIN", 1); //未登录的常量
define("AUTH_NOT_AUTH", 2);  //未授权常量

// 全站公共函数库
// 更改系统配置, 当更改数据库配置时为永久性修改， 修改配置文档中配置为临时修改
function conf($name,$value = false)
{
	if($value === false)
	{
		return C($name);
	}
	else
	{
		if(M("Conf")->where("is_effect=1 and name='".$name."'")->count()>0)
		{
			if(in_array($name,array('EXPIRED_TIME','SUBMIT_DELAY','SEND_SPAN','WATER_ALPHA','MAX_IMAGE_SIZE','INDEX_LEFT_STORE','INDEX_LEFT_TUAN','INDEX_LEFT_YOUHUI','INDEX_LEFT_DAIJIN','INDEX_LEFT_EVENT','INDEX_RIGHT_STORE','INDEX_RIGHT_TUAN','INDEX_RIGHT_YOUHUI','INDEX_RIGHT_DAIJIN','INDEX_RIGHT_EVENT','SIDE_DEAL_COUNT','DEAL_PAGE_SIZE','PAGE_SIZE','BATCH_PAGE_SIZE','HELP_CATE_LIMIT','HELP_ITEM_LIMIT','REC_HOT_LIMIT','REC_NEW_LIMIT','REC_BEST_LIMIT','REC_CATE_GOODS_LIMIT','SALE_LIST','INDEX_NOTICE_COUNT','RELATE_GOODS_LIMIT')))
			{
				$value = intval($value);
			}
			M("Conf")->where("is_effect=1 and name='".$name."'")->setField("value",$value);
		}
		C($name,$value);
	}
}



function write_timezone($zone='')
{
	if($zone=='')
	$zone = conf('TIME_ZONE');
		$var = array(
			'0'	=>	'UTC',
			'8'	=>	'PRC',
		);
		
		//开始将$db_config写入配置
	    $timezone_config_str 	 = 	"<?php\r\n";
	    $timezone_config_str	.=	"return array(\r\n";
	    $timezone_config_str.="'DEFAULT_TIMEZONE'=>'".$var[$zone]."',\r\n";
	    
	    $timezone_config_str.=");\r\n";
	    $timezone_config_str.="?>";
	   
	    @file_put_contents(get_real_path()."public/timezone_config.php",$timezone_config_str);
}



//后台日志记录
function save_log($msg,$status)
{
	if(conf("ADMIN_LOG")==1)
	{
		$adm_session = es_session::get(md5(conf("AUTH_KEY")));
		$log_data['log_info'] = $msg;
		$log_data['log_time'] = NOW_TIME;
		$log_data['log_admin'] = intval($adm_session['adm_id']);
		$log_data['log_ip']	= CLIENT_IP;
		$log_data['log_status'] = $status;	
		$log_data['module']	=	MODULE_NAME;
		$log_data['action'] = 	ACTION_NAME;
		M("Log")->add($log_data);
	}
}


//状态的显示
function get_toogle_status($tag,$id,$field)
{
	if($tag)
	{
		return "<span class='is_effect' onclick=\"toogle_status(".$id.",this,'".$field."');\">".l("YES")."</span>";
	}
	else
	{
		return "<span class='is_effect' onclick=\"toogle_status(".$id.",this,'".$field."');\">".l("NO")."</span>";
	}
}

//状态的显示
function get_is_effect($tag,$id)
{
	if($tag)
	{
		return "<span class='is_effect' onclick='set_effect(".$id.",this);'>".l("IS_EFFECT_1")."</span>";
	}
	else
	{
		return "<span class='is_effect' onclick='set_effect(".$id.",this);'>".l("IS_EFFECT_0")."</span>";
	}
}

//虚拟的显示
function get_is_fictitious($tag,$id)
{
    if($tag)
    {
        return "<span class='is_fictitious' onclick='set_fictitious(".$id.",this);'>".l("IS_FICTITIOUS_1")."</span>";
    }
    else
    {
        return "<span class='is_fictitious' onclick='set_fictitious(".$id.",this);'>".l("IS_FICTITIOUS_0")."</span>";
    }
}

//状态审核的显示
function get_is_verify($tag,$id)
{
	if($tag)
	{
		return "<span class='is_effect' onclick='set_verify(".$id.",this);'>".l("IS_EFFECT_1")."</span>";
	}
	else
	{
		return "<span class='is_effect' onclick='set_verify(".$id.",this);'>".l("IS_EFFECT_0")."</span>";
	}
}


//排序显示
function get_sort($sort,$id)
{
	if($tag)
	{
		return "<span class='sort_span' onclick='set_sort(".$id.",".$sort.",this);'>".$sort."</span>";
	}
	else
	{
		return "<span class='sort_span' onclick='set_sort(".$id.",".$sort.",this);'>".$sort."</span>";
	}
}
function get_nav($nav_id)
{
	return M("RoleNav")->where("id=".$nav_id)->getField("name");	
}
function get_module($module_id)
{
	return M("RoleModule")->where("id=".$module_id)->getField("module");
}
function get_group($group_id)
{
	if($group_data = M("RoleGroup")->where("id=".$group_id)->find())
	$group_name = $group_data['name'];
	else
	$group_name = L("SYSTEM_NODE");
	return $group_name;
}
function get_role_name($role_id)
{
	return M("Role")->where("id=".$role_id)->getField("name");
}
function get_admin_name($admin_id)
{
	$adm_name = M("Admin")->where("id=".$admin_id)->getField("adm_name");
	if($adm_name)
	return $adm_name;
	else
	return l("NONE_ADMIN_NAME");
}
function get_log_status($status)
{
	return l("LOG_STATUS_".$status);
}
//验证相关的函数
//验证排序字段
function check_sort($sort)
{
	if(!is_numeric($sort))
	{
		return false;
	}
	return true;
}
function check_empty($data)
{
	if(strim($data)=='')
	{
		return false;
	}
	return true;
}

function set_default($null,$adm_id)
{

	$admin_name = M("Admin")->where("id=".$adm_id)->getField("adm_name");
	if($admin_name == conf("DEFAULT_ADMIN"))
	{
		return "<span style='color:#f30;'>".l("DEFAULT_ADMIN")."</span>";
	}
	else
	{
		return "<a href='".u("Admin/set_default",array("id"=>$adm_id))."'>".l("SET_DEFAULT_ADMIN")."</a>";
	}
}
function get_order_sn($order_id)
{
	return M("DealOrder")->where("id=".$order_id)->getField("order_sn");
}
function get_order_sn_with_link($order_id)
{
	$order_info = M("DealOrder")->where("id=".$order_id)->find();
	
	if($order_info['type']==2){
	    $str = "夺宝订单：<a href='".u("DuobaoOrder/index",array("order_sn"=>$order_info['order_sn']))."'>".$order_info['order_sn']."</a>";
	}elseif($order_info['type']==1){
	    $str = "充值订单：<a href='".u("DealOrder/InchargeOrder",array("order_sn"=>$order_info['order_sn']))."'>".$order_info['order_sn']."</a>";
	}elseif ($order_info['type']==3){
	    $str = "直购订单：<a href='".u("TotalbuyOrder/index",array("order_sn"=>$order_info['order_sn']))."'>".$order_info['order_sn']."</a>";
	}elseif ($order_info['type']==4){
	    $str = "免费购订单：<a href='".u("FreebuyOrder/index",array("order_sn"=>$order_info['order_sn']))."'>".$order_info['order_sn']."</a>";
	}
	
	
	if($order_info['is_delete']==1)
	$str ="<span style='text-decoration:line-through;'>".$str."</span>";
	return $str;
}

function get_user_name($user_id)
{
	$user_name =  M("User")->where("id=".$user_id." and is_delete = 0")->getField("user_name");
	
	if(!$user_name)
	return l("NO_USER");
	else
	return "<a href='".u("User/index",array("user_name"=>$user_name))."'>".$user_name."</a>";
	
	
}
function get_user_name_js($user_id)
{
	$user_name =  M("User")->where("id=".$user_id." and is_delete = 0")->getField("user_name");
	
	if(!$user_name)
	return l("NO_USER");
	else
	return "<a href='javascript:void(0);' onclick='account(".$user_id.")'>".$user_name."</a>";
	
	
}
function get_pay_status($status)
{
	return L("PAY_STATUS_".$status);
}

function get_order_status($s,$order_info)
{
	if($order_info['extra_status'])
		$extra_status = l("EXTRA_STATUS_".$order_info['extra_status']);
	if($order_info['after_sale'])
		$after_sale = l("AFTER_SALE_".$order_info['after_sale']);
	if($s==1){
	    $msg = "订单完结";
	}elseif($s==2){
	    $msg = "订单关闭";
	}elseif($s==3){
	    $msg = "已过期";
	}else{
	    $msg = "待处理";
	}
	if($after_sale)
		$msg.="<br />".$after_sale;

	if($extra_status)
		$msg.="<br />".$extra_status;
	
	if($order_info['is_delete']==1)
		$msg.="<br />交易关闭";
	return "<div style='text-align:center; '>".$msg."</div>";
}
function get_order_status_csv($s,$order_info)
{
	if($order_info['extra_status'])
		$extra_status = l("EXTRA_STATUS_".$order_info['extra_status']);
	if($order_info['after_sale'])
		$after_sale = l("AFTER_SALE_".$order_info['after_sale']);
	if($s)
		$msg = "订单完结";
	else
		$msg = "待处理";

	if($after_sale)
		$msg.="\n".$after_sale;

	if($extra_status)
		$msg.="\n".$extra_status;
	
	if($order_info['is_delete']==1)
		$msg.="\n用户删除";
	return $msg;
}
function get_notice_info($sn,$notice_id)
{
		$express_name = M()->query("select e.name as ename from ".DB_PREFIX."express as e left join ".DB_PREFIX."delivery_notice as dn on dn.express_id = e.id where dn.id = ".$notice_id);
		$express_name = $express_name[0]['ename'];
		if($express_name)
		$str = $express_name."<br/>".$sn;
		else 
		$str = $sn;
		return $str;
}
function get_payment_name($payment_id)
{
	if($payment_id == '0') {
	    return "优惠币支付";
	}
	else {
	    return M("Payment")->where("id=".$payment_id)->getField("name");
	}
}
function get_delivery_name($delivery_id)
{
	return M("Delivery")->where("id=".$delivery_id)->getField("name");
}
function get_lbs_delivery_name($delivery_id)
{
	return M("DeliveryLbs")->where("id=".$delivery_id)->getField("name");
}
function get_region_name($region_id)
{
	return M("DeliveryRegion")->where("id=".$region_id)->getField("name");
}
function get_city_name($id)
{
	return M("DealCity")->where("id=".$id)->getField("name");
}
function get_message_is_effect($status)
{
	return $status==1?l("YES"):l("NO");
}
function get_message_type($type_name,$rel_id)
{
	$show_name = M("MessageType")->where("type_name='".$type_name."'")->getField("show_name");
	if($type_name=='deal_order')
	{
		$order_sn = M("DealOrder")->where("id=".$rel_id)->getField("order_sn");
		if($order_sn)
		return "[".$order_sn."] <a href='".u("DealOrder/deal_index",array("id"=>$rel_id))."'>".$show_name."</a>";
		else
		return $show_name;
	}
	elseif($type_name=='deal')
	{
		$sub_name = M("Deal")->where("id=".$rel_id)->getField("sub_name");
		if($sub_name)
		return "[".$sub_name."]" .$show_name;
		else
		return $show_name;
	}
	elseif($type_name=='supplier')
	{
		$name = M("Supplier")->where("id=".$rel_id)->getField("name");
		if($name)
		return "[".$name."] <a href='".u("Supplier/index",array("id"=>$rel_id))."'>".$show_name."</a>";
		else
		return $show_name;
	}
	else
	{
		if($show_name)
		return $show_name;
		else
		return $type_name;
	}
}

function get_send_status($status)
{
	return L("SEND_STATUS_".$status);
}
function get_send_mail_type($deal_id)
{
	if($deal_id>0)
	return l("DEAL_NOTICE");
	else 
	return l("COMMON_NOTICE");
}
function get_send_type($send_type)
{
	return l("SEND_TYPE_".$send_type);
}

function get_all_files( $path )
{
		$list = array();
		$dir = @opendir($path);
	    while (false !== ($file = @readdir($dir)))
	    {
	    	if($file!='.'&&$file!='..')
	    	if( is_dir( $path.$file."/" ) ){
	         	$list = array_merge( $list , get_all_files( $path.$file."/" ) );
	        }
	        else 
	        {
	        	$list[] = $path.$file;
	        }
	    }
	    @closedir($dir);
	    return $list;
}

function get_order_verify($type)
{
	if($type==0)return "自动确认";
	if($type==1)return "人工确认";
}

function get_time_status($end_time,$deal)
{
	if($deal['begin_time']>NOW_TIME)return "未开始";
	if($deal['end_time']==0)return "不限时";
	if($deal['end_time']<NOW_TIME)return "已过期";
	return "进行中";
}

function get_order_item_name($id)
{
	return M("DealOrderItem")->where("id=".$id)->getField("name");
}


function get_location_name($id){
		return M('SupplierLocation')->where('id='.$id)->getField('name');
	
}
	
function get_send_type_msg($status)
{
	//发送类型 0:短信 1:邮件;2:微信;3:andoird;4:ios
	if($status==0)
	{
		return l("SMS_SEND");
	}
	elseif($status==2)
	{
		return '微信';
	}
	elseif($status==3)
	{
		return 'andorid';
	}
	elseif($status==4)
	{
		return 'ios';
	}		
	else 
	{
		return l("MAIL_SEND");
	}
}

function show_content($content,$id)
{
	return "<a title='".l("VIEW")."' href='javascript:void(0);' onclick='show_content(".$id.")'>".l("VIEW")."</a>";
}



function get_is_send($is_send)
{
	if($is_send==0)
	return L("NO");
	else
	return L("YES");
}
function get_send_result($result)
{
	if($result==0)
	{
		return L("FAILED");
	}
	else
	{
		return L("SUCCESS");
	}
}

function get_is_buy($is_buy)
{
	return l("IS_BUY_".$is_buy);	
}

function get_point($point)
{
	return l("MESSAGE_POINT_".$point);
}

function get_status($status)
{
	if($status)
	{
		return l("YES");
	}
	else
	return l("NO");
}


function getMPageName($page)
{
	return L('MPAGE_'.strtoupper($page));
}

function getMTypeName($type,$item)
{	
	$cfg = $GLOBALS['mobile_cfg'];
	$navs = null;
	foreach($cfg as $k=>$v)
	{
		if($v['mobile_type']==$item['mobile_type'])
		{
			$navs = $v['nav'];
			break;
		}
	}
	
	
	foreach($navs as $k=>$v)
	{
		if($v['type']==$type)
		{
			return $v['name'];
		}
	}
	
}

function getWebTypeName($type,$item)
{	
	$cfg = $GLOBALS['web_zt_cfg'];
	$navs = $cfg['web']['nav'];

	foreach($navs as $k=>$v)
	{
		if($v['type']==$type)
		{
			return $v['name'];
		}
	}
	
}

function get_submit_user($uid)
{
		if($uid==0)
		return "管理员发布";
		else
		{
			$uname = M("SupplierAccount")->where("id=".$uid)->getField("account_name");
			return $uname?$uname:"商家不存在";
		}
		
}
function get_event_cate_name($id)
	{
		return M("EventCate")->where("id=".$id)->getField("name");
	}
	
function show_table_substr($word,$cut=20)
{
	return "<span title='".$word."'>".msubstr($word,0,$cut)."</span>";
}

function get_balance_status($status)
{
	return l("BALANCE_".$status);
}

/**
 * 结算
 * @param unknown_type $rel_ids 结算的数据ID数组
 * @param unknown_type $deal_id 项目编号
 * @param memo 备注 
 */
function do_balance($rel_ids,$deal_id,$memo="")
{
	$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
	$now = NOW_TIME;
	if($deal_info['is_coupon']==1)
	{
		$sql = "update ".DB_PREFIX."deal_coupon set is_balance = 2,balance_time = ".$now.",balance_memo = '".$memo."' where id in (".implode(",",$rel_ids).") and is_balance <> 2";
		$sql_amount = "select sum(balance_price)+sum(add_balance_price) from ".DB_PREFIX."deal_coupon where id in (".implode(",",$rel_ids).") and is_balance <> 2";
		$amount = $GLOBALS['db']->getOne($sql_amount);
		$GLOBALS['db']->query($sql);	
		
		//同步更新订单商品
		$sql_item = "select doi.* from ".DB_PREFIX."deal_order_item as doi where doi.id in(select distinct(dc.order_deal_id) as item_id from ".DB_PREFIX."deal_coupon as dc where dc.id in (".implode(",",$rel_ids)."))";
		$item_list = $GLOBALS['db']->getAll($sql_item);
		foreach($item_list as $k=>$v)
		{
			if($deal_info['deal_type']==1)
			{
				//按单
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set is_balance = 2,balance_time = ".$now.",balance_memo = '".$memo."' where id = ".$v['id']." and is_balance <> 2");
			}
			else
			{
				if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_coupon where order_deal_id = ".$v['id']." and is_balance = 2")==$v['number'])
				{
					//全部	
					$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set is_balance = 2,balance_time = ".$now.",balance_memo = '".$memo."' where id = ".$v['id']." and is_balance <> 2");			
				}
				else
				{
					//部份
					$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set is_balance = 3,balance_time = ".$now.",balance_memo = '".$memo."' where id = ".$v['id']." and is_balance <> 2");			
				}
			}
		}		
	}
	else
	{
		$sql_amount = "select sum(balance_total_price)+sum(add_balance_price_total) from ".DB_PREFIX."deal_order_item where id in (".implode(",",$rel_ids).") and is_balance <> 2";
		$amount = $GLOBALS['db']->getOne($sql_amount);
		$sql = "update ".DB_PREFIX."deal_order_item set is_balance = 2,balance_time = ".$now.",balance_memo = '".$memo."' where id in (".implode(",",$rel_ids).") and is_balance <> 2";
		
		$GLOBALS['db']->query($sql);
		
	}
	supplier_money_log($deal_info['supplier_id'],$amount, $deal_info['sub_name']."结算 ".$memo);
}



function getMobileTypeName($type)
{
	$cfg = $GLOBALS['mobile_cfg'];
	foreach($cfg as $k=>$v)
	{
		if($v['mobile_type']==$type)
		{
			return $v['name'];
		}
	}
}

function msubstr_name($n)
{
	return msubstr($n,0,40);
	
}

/**
 * 分页处理
 * @param string $type 所在页面
 * @param array  $args 参数
 * @param int $total_count 总数
 * @param int $page 当前页
 * @param int $page_size 分页大小
 * @param string $url 自定义路径
 * @param int $offset 偏移量
 * @return array
 */
function buildPage($type,$args,$total_count,$page = 1,$page_size = 0,$url='',$offset = 5){
	$pager['total_count'] = intval($total_count);
	$pager['page'] = $page;
	$pager['page_size'] = ($page_size == 0) ? 20 : $page_size;
	/* page 总数 */
	$pager['page_count'] = ($pager['total_count'] > 0) ? ceil($pager['total_count'] / $pager['page_size']) : 1;

	/* 边界处理 */
	if ($pager['page'] > $pager['page_count'])
		$pager['page'] = $pager['page_count'];

	$pager['limit'] = ($pager['page'] - 1) * $pager['page_size'] . "," . $pager['page_size'];
	$page_prev  = ($pager['page'] > 1) ? $pager['page'] - 1 : 1;
	$page_next  = ($pager['page'] < $pager['page_count']) ? $pager['page'] + 1 : $pager['page_count'];
	$pager['prev_page'] = $page_prev;
	$pager['next_page'] = $page_next;

	if (!empty($url)){
		$pager['page_first'] = $url . 1;
		$pager['page_prev']  = $url . $page_prev;
		$pager['page_next']  = $url . $page_next;
		$pager['page_last']  = $url . $pager['page_count'];
	}
	else{
		$args['page'] = '_page_';
		if(!empty($type)){
			if(strpos($type,'javascript:') === false){
				//$page_url = JKU($type,$args);
			}else{
				$page_url = $type;
			}
		}else{
			$page_url = 'javascript:;';
		}
		$pager['page_first'] = str_replace('_page_',1,$page_url);
		$pager['page_prev']  = str_replace('_page_',$page_prev,$page_url);
		$pager['page_next']  = str_replace('_page_',$page_next,$page_url);
		$pager['page_last']  = str_replace('_page_',$pager['page_count'],$page_url);
	}
	$pager['page_nums'] = array();
	if($pager['page_count'] <= $offset * 2){
		for ($i=1; $i <= $pager['page_count']; $i++){
			$pager['page_nums'][] = array('name' => $i,'url' => empty($url) ? str_replace('_page_',$i,$page_url) : $url . $i);
		}
	}else{
		if($pager['page'] - $offset < 2){
			$temp = $offset * 2;
			for ($i=1; $i<=$temp; $i++){
				$pager['page_nums'][] = array('name' => $i,'url' => empty($url) ? str_replace('_page_',$i,$page_url) : $url . $i);
			}
			$pager['page_nums'][] = array('name'=>'...');
			$pager['page_nums'][] = array('name' => $pager['page_count'],'url' => empty($url) ? str_replace('_page_',$pager['page_count'],$page_url) : $url . $pager['page_count']);
		}else{
			$pager['page_nums'][] = array('name' => 1,'url' => empty($url) ? str_replace('_page_',1,$page_url) : $url . 1);
			$pager['page_nums'][] = array('name'=>'...');
			$start = $pager['page'] - $offset + 1;
			$end = $pager['page'] + $offset - 1;
			if($pager['page_count'] - $end > 1){
				for ($i=$start;$i<=$end;$i++){
					$pager['page_nums'][] = array('name' => $i,'url' => empty($url) ? str_replace('_page_',$i,$page_url) : $url . $i);
				}

				$pager['page_nums'][] = array('name'=>'...');
				$pager['page_nums'][] = array('name' => $pager['page_count'],'url' => empty($url) ? str_replace('_page_',$pager['page_count'],$page_url) : $url . $pager['page_count']);
			}else{
				$start = $pager['page_count'] - $offset * 2 + 1;
				$end = $pager['page_count'];
				for ($i=$start;$i<=$end;$i++){
					$pager['page_nums'][] = array('name' => $i,'url' => empty($url) ? str_replace('_page_',$i,$page_url) : $url . $i);
				}
			}
		}
	}
	return $pager;
}

function get_channel_name($channel_id)
{
	static $channel_names;
	if(!$channel_names[$channel_id])
	{
		$name = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."channel where id = ".$channel_id);
		if(!$name)$name="无频道归属";
		$channel_names[$channel_id] = $name;
	}
	else
	{
		$name = $channel_names[$channel_id];
	}
	return $name;
}

function get_buy_count($buy_count,$deal)
{
	$real_buy_count = $GLOBALS['db']->getOne("select buy_count from ".DB_PREFIX."deal_stock where deal_id = ".$deal['id']);
	if($real_buy_count==$buy_count)
		return $buy_count;
	else
		return "虚拟".$buy_count.",真实".$real_buy_count;
}



function show_deal_order_delivery_status($deal_item,$order_info)
{
    
	if($deal_item['pid']>0)
	{
		echo '--';
	}
	else
	{
	    
	    if($order_info['type']==3){
	        if($order_info['pay_status']==2){
	            if($deal_item['delivery_status']==0){
	                if($deal_item['is_set_consignee']==1||$order_info['is_fictitious']==1){
	                    echo '<a href="'.u("TotalbuyOrder/delivery",array("id"=>$order_info['id'])).'">发货</a>';
	                }
	            }else{
	                echo '已发货<br />';
	                echo '发货单号：'.get_delivery_sn($deal_item['id']).'<br /><br />发货单状态：'.get_delivery_arrival($deal_item['id'])."<br />";
	                 
	                if($deal_item['is_arrival']==0){
	                    echo '<a href="javascript:void(0);" class="do_verify" action="'.u("DealOrder/do_verify",array("order_item_id"=>$deal_item['id'])).'">长期不收货，强制收货</a>';
	                }else{
	                    echo "订单状态：已收货";
	                }
	            }
	        }else{
	            echo '付款未完成';
	        }
	        
	    }else{
	        if($deal_item['delivery_status']==0)
	        {
	            if($deal_item['is_set_consignee']==1||$order_info['is_fictitious']==1){
	                echo '<a href="'.u("DealOrder/delivery",array("id"=>$order_info['id'])).'">发货</a>';
	            }else{
	                echo '用户未选择地址';
	            }
	        }else{
	            echo '已发货<br />';
	            echo '发货单号：'.get_delivery_sn($deal_item['id']).'<br /><br />发货单状态：'.get_delivery_arrival($deal_item['id'])."<br />";
	        
	            if($deal_item['is_arrival']==0){
	                echo '<a href="javascript:void(0);" class="do_verify" action="'.u("DealOrder/do_verify",array("order_item_id"=>$deal_item['id'])).'">长期不收货，强制收货</a>';
	            }else{
	                echo "订单状态：已收货";
	            }
	        }
	    }
			
			
// 			if($deal_item['delivery_status']==0)
// 			{
			    
// 				if($deal_item['is_set_consignee']==1||$order_info['is_fictitious']==1){
// 				    if($order_info['type']==3){
// 				        if($order_info['pay_status']==2){
// 				            echo '<a href="'.u("TotalbuyOrder/delivery",array("id"=>$order_info['id'])).'">发货</a>';
// 				        }else{
// 				            echo '付款未完成';
// 				        }
// 				    }else{
// 				        echo '<a href="'.u("DealOrder/delivery",array("id"=>$order_info['id'])).'">发货</a>';
// 				    }
// 				}else{
// 					echo '用户未选择地址';
// 				}
// 			}
// 			else
// 			{
// 				echo '已发货<br />';
// 				echo '发货单号：'.get_delivery_sn($deal_item['id']).'<br /><br />发货单状态：'.get_delivery_arrival($deal_item['id'])."<br />";
				
		
// 				if($deal_item['is_arrival']==0)
// 				{
// 					echo '<a href="javascript:void(0);" class="do_verify" action="'.u("DealOrder/do_verify",array("order_item_id"=>$deal_item['id'])).'">长期不收货，强制收货</a>';
// 				}
// 				else
// 				{
// 					echo "订单状态：已收货";
// 				}
// 			}
		
	}
	
}



function get_delivery_sn($deal_order_item_id)
{
	$delivery_notice = M("DeliveryNotice")->where("order_item_id=".$deal_order_item_id)->order("delivery_time desc")->find();
	$order_id = M("DealOrderItem")->where("id=".$delivery_notice['order_item_id'])->getField("order_id");
	$res = $delivery_notice['notice_sn'];
	if($delivery_notice['express_id']!=0)
	{
		$res.=" <br /><a href='javascript:void(0);' onclick='track_express(\"".$delivery_notice['notice_sn']."\",\"".$delivery_notice['express_id']."\");'>".l("TRACK_EXPRESS")."</a>";
	}
	return $res;
}
function get_delivery_arrival($deal_order_item_id)
{
	$delivery_notice =  M("DeliveryNotice")->where("order_item_id=".$deal_order_item_id)->order("delivery_time desc")->find();
	if($delivery_notice['is_arrival']==1)
	{
		return l("USER_CONFIRM_DELIVERY");
	}
	elseif($delivery_notice['is_arrival']==2)
	{
		return "<span style='color:#f30;'>用户未收到货，维权</span>";
	}
	else
	{
		return l("USER_NOT_CONFIRM_DELIVERY");
	}
}
function get_delivery_memo($deal_order_item_id)
{
	return M("DeliveryNotice")->where("order_item_id=".$deal_order_item_id)->getField("memo");
}

?>