<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class annoModule extends MainBaseModule
{
	public function index()
	{		
		global_run();
		
		init_app_page();
		require_once APP_ROOT_PATH."system/model/duobao.php";
		$page =intval($_REQUEST['p']);
		$page_size =app_conf("DEAL_PAGE_SIZE");
		
		$sql_count = "SELECT
                	count(id)
                FROM
                	".DB_PREFIX."duobao_item 
		        WHERE
		            is_effect = 1 AND
		            progress = 100  ORDER BY
                	has_lottery,lottery_time DESC";

		$sql = "SELECT
                	id,name,is_topspeed,is_number_choose,is_pk,progress AS progress,icon, lottery_sn, has_lottery, success_time,
		            lottery_time+10 AS lottery_time,luck_user_id,luck_user_name,max_buy,current_buy,min_buy,luck_user_buy_count
                FROM
                	".DB_PREFIX."duobao_item 
		        WHERE
		            is_effect = 1 AND
		            progress = 100  ORDER BY
                	has_lottery,lottery_time DESC";   //未开奖的

		require_once APP_ROOT_PATH."app/Lib/page.php";
		if ($page == 0) $page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		$count = $GLOBALS['db']->getOne($sql_count);
		$page = new Page($count, $page_size); // 初始化分页对象
		$p = $page->show();
		$lastet_list = $GLOBALS['db']->getAll($sql ." limit " . $limit);
		$GLOBALS['tmpl']->assign("now_time",NOW_TIME);
		$GLOBALS['tmpl']->assign("lastet_list",$lastet_list);  //最新揭晓，已凑满
		
		$sql_unopen = "SELECT
                	DuobaoItem.id AS id,DuobaoItem.is_topspeed AS is_topspeed, DuobaoItem.deal_id AS deal_id, DuobaoItem.duobao_id AS duobao_id, DuobaoItem.name AS duobaoitem_name,DuobaoItem.progress AS progress,
		            DuobaoItem.icon AS icon, DuobaoItem.lottery_sn AS lottery_sn, DuobaoItem.has_lottery AS has_lottery, DuobaoItem.success_time AS success_time,
		            DuobaoItem.lottery_time AS lottery_time, DuobaoItem.fair_sn AS fair_sn, DuobaoItem.luck_user_id AS luck_user_id, DuobaoItem.max_buy, DuobaoItem.current_buy,DuobaoItem.min_buy,(DuobaoItem.max_buy-DuobaoItem.current_buy) as less_buy,
                	USER.user_name AS user_name ,USER.avatar AS avatar
                FROM
                	".DB_PREFIX."duobao_item DuobaoItem
                    LEFT JOIN ".DB_PREFIX."user USER ON USER .id = DuobaoItem.luck_user_id
		        WHERE
		            DuobaoItem.is_effect = 1 AND
		            DuobaoItem.progress < 100 AND
		            is_number_choose=0 AND is_pk=0 AND
                	DuobaoItem.has_lottery = 0 order by DuobaoItem.progress desc limit 6";   //最快揭晓
		
		
		$unopen_list = $GLOBALS['db']->getAll($sql_unopen);
		
		//print_r($unopen_list);exit;
		$GLOBALS['tmpl']->assign("unopen_list",$unopen_list);
		$GLOBALS['tmpl']->assign('pages', $p);

		$GLOBALS['tmpl']->display("anno.html");
	}
	
	
	
}
?>