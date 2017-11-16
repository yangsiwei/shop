<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class cateModule extends MainBaseModule
{
	public function index()
	{		
		global_run();
		require_once APP_ROOT_PATH."app/Lib/page.php";
		init_app_page();
		$cate_id = intval($_REQUEST['cate_id']);
		$page= intval($_REQUEST['page']);
		
		if($page==0)
			$page = 1;
		$limit = (($page-1)*app_conf("DEAL_PAGE_SIZE")).",".app_conf("DEAL_PAGE_SIZE");
		
	
		$page = new Page($total,app_conf("DEAL_PAGE_SIZE"));   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		
		require_once APP_ROOT_PATH."system/model/duobao.php";
		
		//$GLOBALS['tmpl']->assign("drop_nav","no_drop"); //首页下拉菜单不输出
		$GLOBALS['tmpl']->assign("wrap_type","1"); //首页宽屏展示

		$cate_list = $GLOBALS['db']->getAll("select id,name,iconfont,iconcolor,icon from ".DB_PREFIX."deal_cate where is_effect = 1 order by sort asc");
		$GLOBALS['tmpl']->assign("cate_list",$cate_list);
		$sql = "SELECT
                	DuobaoItem.id AS id, DuobaoItem.deal_id AS deal_id, DuobaoItem.duobao_id AS duobao_id, DuobaoItem. NAME AS duobaoitem_name,
		            DuobaoItem.icon AS icon, DuobaoItem.lottery_sn AS lottery_sn, DuobaoItem.has_lottery AS has_lottery, DuobaoItem.success_time AS success_time,
		            DuobaoItem.lottery_time AS lottery_time, DuobaoItem.fair_sn AS fair_sn, DuobaoItem.luck_user_id AS luck_user_id, DuobaoItem.max_buy, DuobaoItem.current_buy,DuobaoItem.min_buy,
                	USER.user_name AS user_name
                FROM
                	".DB_PREFIX."duobao_item DuobaoItem
                    LEFT JOIN ".DB_PREFIX."user USER ON USER .id = DuobaoItem.luck_user_id
		        WHERE
		            DuobaoItem.is_effect = 1 AND
		            DuobaoItem.progress = 100  ORDER BY
                	DuobaoItem.has_lottery,DuobaoItem.lottery_time DESC";
		$limit=6;
		$lastet_list = $GLOBALS['db']->getAll($sql ." limit " . $limit);
		
		$GLOBALS['tmpl']->assign("lastet_list",$lastet_list);  //最新揭晓
		
		
		
		// 获取中奖列表
		$newest_lottery_list=duobao::get_lottery_list(10);
		
		foreach($newest_lottery_list as $k=>$v)
		{
			$newest_lottery_list[$k]['span_time']=duobao::format_lottery_time($v['lottery_time']);
		}
		
		//最热商品
		$hot_sql="select * from ".DB_PREFIX."duobao_item where is_effect=1 and success_time = 0 and progress < 100 order by click_count desc limit 8";
		$hot_duobao_list = $GLOBALS['db']->getAll($hot_sql);
		$GLOBALS['tmpl']->assign("hot_duobao_list",$hot_duobao_list);  //最热商品
		
		$cate_list_product=$cate_list;
		foreach($cate_list as $k=>$v){
			$cate_list_product[$k]['duobao_list']=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where is_effect=1 and success_time = 0 and progress < 100 and cate_id=".$v['id']." limit 4");
		}
		
		$GLOBALS['tmpl']->assign("cate_list_product",$cate_list_product);  //分类列表
		$GLOBALS['tmpl']->display("index.html");
	}
	
	
}
?>