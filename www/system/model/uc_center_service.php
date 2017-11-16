<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

//查询会员邀请及返利列表
function get_invite_list($limit,$user_id)
{
	$user_id = intval($user_id);
	$sql = "SELECT
        u.user_name AS i_user_name,
        u.referral_count AS i_referral_count,
        u.create_time AS i_reg_time,
        o.order_sn AS i_order_sn,
        r.create_time AS i_referral_time,
        r.pay_time AS i_pay_time,
        r.money AS i_money,
        r.score AS i_score,
        r.coupons AS i_coupons,
        a.number,
        b.min_buy,
        b.invite_score,
        a.name,
        (b.invite_score*a.number/b.min_buy) as score
        FROM
        	".DB_PREFIX."user AS u
        LEFT JOIN ".DB_PREFIX."referrals AS r ON u.id = r.rel_user_id
        AND u.pid = r.user_id
        LEFT JOIN ".DB_PREFIX."deal_order AS o ON r.order_id = o.id
        LEFT JOIN ".DB_PREFIX."deal_order_item AS a ON a.order_id = o.id
        LEFT JOIN ".DB_PREFIX."duobao_item AS b ON a.duobao_item_id = b.id
        WHERE
        	u.pid = ".$user_id." and (o.order_sn is null or b.invite_score>0 )
        ORDER BY
        	i_referral_time DESC limit ".$limit;
	
    $sql_count = "SELECT count(*) FROM 
    	".DB_PREFIX."user AS u
        LEFT JOIN ".DB_PREFIX."referrals AS r ON u.id = r.rel_user_id
        AND u.pid = r.user_id
        LEFT JOIN ".DB_PREFIX."deal_order AS o ON r.order_id = o.id
        LEFT JOIN ".DB_PREFIX."deal_order_item AS a ON a.order_id = o.id
        LEFT JOIN ".DB_PREFIX."duobao_item AS b ON a.duobao_item_id = b.id
        WHERE
        	u.pid = ".$user_id." and (o.order_sn is null or b.invite_score>0 )
        ORDER BY
        	r.create_time DESC";
	$list = $GLOBALS['db']->getAll($sql);
	$count = $GLOBALS['db']->getOne($sql_count);
	return array("list"=>$list,'count'=>$count);
}

//查询会员邀请及总返利列表
function get_total_invite_list($limit,$user_id)
{
    $user_id = intval($user_id);
    $inte_sql="SELECT u.user_name, SUM(r.money) AS money, SUM(r.score) AS score, SUM(r.coupons) AS coupons FROM ".DB_PREFIX."user AS u LEFT JOIN ".DB_PREFIX."referrals AS r ON u.id = r.rel_user_id WHERE u.pid = ".$user_id.".AND u.pid = r.user_id GROUP BY r.rel_user_id limit ".$limit;
    $total_list=$GLOBALS['db']->getAll($inte_sql);

    $count_sql = "SELECT u.user_name FROM ".DB_PREFIX."user AS u LEFT JOIN ".DB_PREFIX."referrals AS r ON u.id = r.rel_user_id WHERE u.pid = ".$user_id." AND u.pid = r.user_id GROUP BY r.rel_user_id";
    $count_list = $GLOBALS['db']->getAll($count_sql);
    $count = count($count_list);
    return array('total_list'=>$total_list,'count'=>$count);
}

function get_collect_list($limit,$user_id,$channel_id=0)
{
	$user_id = intval($user_id);
	$condition=" and 1=1 ";
	if($channel_id>0)$condition=" and d.channel_id=".$channel_id." ";
	$sql = "select d.id,d.name,d.sub_name,d.origin_price,d.current_price,d.buy_count,d.brief,d.icon,c.create_time as add_time ,c.id as cid from ".DB_PREFIX."deal_collect as c left join ".DB_PREFIX."deal as d on d.id = c.deal_id where c.user_id = ".$user_id.$condition." order by c.create_time desc limit ".$limit;
	$sql_count="select count(*) from ".DB_PREFIX."deal_collect as c left join ".DB_PREFIX."deal as d on d.id = c.deal_id where c.user_id = ".$user_id.$condition;
	$list = $GLOBALS['db']->getAll($sql);
	$count = $GLOBALS['db']->getOne($sql_count);
	return array("list"=>$list,'count'=>$count);
}



//查询代金券列表
function get_voucher_list($limit,$user_id,$type=0)
{
	$user_id = intval($user_id);
	if($type==0){//全部
		$sql_where='';
		$sql_count_where='';
	}elseif($type==1){//可使用
		$sql_where=' and (e.use_limit=0 or e.use_limit>e.use_count) and (e.end_time>'.NOW_TIME.' or e.end_time=0)';
		$sql_count_where=' and (use_limit=0 or use_limit!=use_count) and (end_time>'.NOW_TIME.' or end_time=0)';
	}elseif($type==2){//已使用或已过期
		$sql_where=' and ((e.use_limit=e.use_count and e.use_limit>0) or (e.end_time<'.NOW_TIME.' and e.end_time!=0))';
		$sql_count_where=' and ((use_limit=use_count and use_limit!=0) or (end_time<'.NOW_TIME.' and end_time!=0))';
	}
	$sql = "select e.*,
			et.name,
			et.money as type_money,
			et.use_limit as type_use_limit,et.begin_time as type_begin_time,et.end_time as type_end_time
			,et.gen_count,et.send_type,et.exchange_score,et.exchange_limit,et.exchange_sn,et.share_url,et.memo,et.tpl,et.total_limit
			from ".DB_PREFIX."ecv as e left join ".DB_PREFIX."ecv_type as et on e.ecv_type_id = et.id where e.user_id = ".$user_id.$sql_where." order by e.id desc limit ".$limit;
	$sql_count = "select count(*) from ".DB_PREFIX."ecv where user_id = ".$user_id.$sql_count_where;
	$list = $GLOBALS['db']->getAll($sql);
	$count = $GLOBALS['db']->getOne($sql_count);
	return array("list"=>$list,'count'=>$count);
}

//查询可兑换代金券列表
function get_exchange_voucher_list($limit)
{
	$sql = "select * from ".DB_PREFIX."ecv_type where (end_time>".NOW_TIME." or end_time=0) and send_type = 1 order by id desc limit ".$limit;
	$sql_count = "select count(*) from ".DB_PREFIX."ecv_type where send_type = 1";
	
	$list = $GLOBALS['db']->getAll($sql);
	$count = $GLOBALS['db']->getOne($sql_count);
	return array("list"=>$list,'count'=>$count);
}

?>