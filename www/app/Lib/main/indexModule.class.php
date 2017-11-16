<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class indexModule extends MainBaseModule
{
	public function index()
	{
		global_run();
		
		init_app_page();
		require_once APP_ROOT_PATH."system/model/duobao.php";
		$GLOBALS['tmpl']->assign("drop_nav","no_drop"); //首页下拉菜单不输出
		//公告
		
		//print_r($GLOBALS);exit;
		
		$agreement = load_dynamic_cache("agreement"); 
		if($agreement===false)
		{
	        $agreement = $GLOBALS['db']->getRow("SELECT *
	                    FROM
	                        ".DB_PREFIX."agreement
	                    WHERE
	                        is_effect = 1 AND
	                        agreement_cate ='notice'
	                        ORDER BY sort DESC ");
			set_dynamic_cache("agreement", $agreement);
		}
		$GLOBALS['tmpl']->assign("agreement",$agreement);
		
	


		//正在揭晓与最新揭晓
		$sql = "SELECT
                	DuobaoItem.id AS id, DuobaoItem.deal_id AS deal_id, DuobaoItem.duobao_id AS duobao_id, DuobaoItem.name AS duobaoitem_name,
		            DuobaoItem.icon AS icon, DuobaoItem.lottery_sn AS lottery_sn, DuobaoItem.has_lottery AS has_lottery, DuobaoItem.success_time AS success_time,
		            DuobaoItem.lottery_time AS lottery_time, DuobaoItem.fair_sn AS fair_sn, DuobaoItem.luck_user_id AS luck_user_id, DuobaoItem.max_buy, DuobaoItem.current_buy,DuobaoItem.min_buy,
                	DuobaoItem.luck_user_name AS user_name, DuobaoItem.luck_user_buy_count ,u.user_name
                FROM
                	 ".DB_PREFIX."duobao_item as DuobaoItem left join ".DB_PREFIX."user as u on DuobaoItem.luck_user_id = u.id

		        WHERE
		            DuobaoItem.is_effect = 1 AND
		            DuobaoItem.progress = 100  ORDER BY
                	 DuobaoItem.lottery_time desc";   //未开奖的
		$limit=6;
		$lastet_list = $GLOBALS['db']->getAll($sql ." limit " . $limit);
		
		//倒计时多加10秒，等待开奖
		foreach($lastet_list as $k=>$v){
			$lastet_list[$k]['lottery_time']=$v['lottery_time'] +10;
		}
		$k=0;
		for($i=0;$i<$limit/2+1;$i++){
			for($j=0;$j<2;$j++){
	            if($lastet_list[$k]){
					$lastets_list[$i][$j]=$lastet_list[$k];
					$k++;
	            }else{
	            	break;
	            }
		    }
		}
		$GLOBALS['tmpl']->assign("lastet_list",$lastets_list);  //最新揭晓，未开奖的

		
		$recomend_list = load_dynamic_cache("recomend_list");
		if($recomend_list===false)
		{
			$sql_recomend = "SELECT
	                	DuobaoItem.is_coupons,DuobaoItem.id AS id,DuobaoItem.unit_price As unit_price, DuobaoItem.deal_id AS deal_id, DuobaoItem.duobao_id AS duobao_id, DuobaoItem.name AS duobaoitem_name,DuobaoItem.brief AS duobaoitem_brief,
			            DuobaoItem.icon AS icon, DuobaoItem.lottery_sn AS lottery_sn, DuobaoItem.has_lottery AS has_lottery, DuobaoItem.success_time AS success_time,
			            DuobaoItem.lottery_time AS lottery_time, DuobaoItem.fair_sn AS fair_sn, DuobaoItem.luck_user_id AS luck_user_id, DuobaoItem.max_buy, DuobaoItem.current_buy,DuobaoItem.min_buy,DuobaoItem.progress,(DuobaoItem.max_buy-DuobaoItem.current_buy) as surplus_buy
	                FROM
	                	".DB_PREFIX."duobao_item DuobaoItem
	                    LEFT JOIN ".DB_PREFIX."duobao DUOBAO ON DuobaoItem.duobao_id = DUOBAO.id
			        WHERE
			            DuobaoItem.is_effect = 1 AND
			            DuobaoItem.progress < 100 AND
	                    DUOBAO.is_recomend=1  ORDER BY DuobaoItem.create_time DESC limit 6";   //未开奖的
			$recomend_list=$GLOBALS['db']->getAll($sql_recomend);
			

			set_dynamic_cache("recomend_list", $recomend_list);
		}
		
		$GLOBALS['tmpl']->assign("recomend_list",$recomend_list);  //新品推荐列表
		$recomend_one = $recomend_list[rand(0,count($recomend_list)-1)];		
		$GLOBALS['tmpl']->assign("recomend_one",$recomend_one);  //随机推荐夺宝(一个)
	
		 
		//注册送红包弹窗
		if ( es_session::get("is_send_reg_ecv") == 1 ){
		    $GLOBALS['tmpl']->assign('is_send_reg_ecv', 1);
		    $GLOBALS['tmpl']->assign('reg_ecv_money', es_session::get("reg_ecv_money"));
		   
		    es_session::set("is_send_reg_ecv", '');
		    es_session::set("reg_ecv_money", '');
		}
		
		// 获取中奖列表，已开奖的
		$newest_lottery_list = load_dynamic_cache("newest_lottery_list");
		if($newest_lottery_list===false)
		{
			$newest_lottery_list=duobao::get_lottery_list(10);		
			foreach($newest_lottery_list as $k=>$v)
			{
				$newest_lottery_list[$k]['span_time']=duobao::format_lottery_time($v['lottery_time']);
			}
			set_dynamic_cache("newest_lottery_list", $newest_lottery_list);
		}
		$GLOBALS['tmpl']->assign("newest_lottery_list",$newest_lottery_list); 
		

		
		//最热商品
		$hot_sql="select * from ".DB_PREFIX."duobao_item where is_effect=1 and success_time = 0 and is_coupons = 0 and is_pk=0 and is_number_choose=0 and progress < 100 order by click_count desc limit 8";
		$hot_duobao_list = $GLOBALS['db']->getAll($hot_sql);
		foreach($hot_duobao_list as $k=>$v)
		{
			$hot_duobao_list[$k]['less_buy']=$v['max_buy']-$v['current_buy'];
		}
		$GLOBALS['tmpl']->assign("hot_duobao_list",$hot_duobao_list);  //最热商品
		
		
        //广告排序
		$adv = $GLOBALS['db']->getAll("select cate_id,image from ".DB_PREFIX."adv where is_effect = 1 and cate_id > 0");
		foreach ($adv as $k=>$v){
		        $adv_list[$v['cate_id']]=$v;
		}
		
		$cate_sort = $GLOBALS['db']->getAll("select id from ".DB_PREFIX."deal_cate where is_effect = 1 order by sort");
		foreach ($cate_sort as $k=>$v){
		    $cate_sort[$k]=$adv_list[$v['id']]['image'];
		}
		
		$cate_list = $GLOBALS['db']->getAll("select id,name,iconfont,iconcolor,icon from ".DB_PREFIX."deal_cate where is_effect = 1 order by sort");
		$GLOBALS['tmpl']->assign("cate_list",$cate_list);
		
		$cate_list_product=$cate_list;
		//获得活动，按分类分组前4个
		$duobao_item=$GLOBALS['db']->getAll("select *,(max_buy-current_buy) as less_buy from ".DB_PREFIX."duobao_item where is_effect=1 and success_time = 0 and is_coupons = 0 and progress < 100 order by cate_id,create_time desc");
		foreach ($duobao_item as $k=>$v){
			if(count($duobao_item_cate_id[$v['cate_id']])<4)
			$duobao_item_cate_id[$v['cate_id']][]=$v;
		}
		foreach($cate_list as $k=>$v){
			$cate_list_product[$k]['duobao_list']=$duobao_item_cate_id[$v['id']];
			$cate_list_product[$k]['image']=$cate_sort[$k];
		}
		$GLOBALS['tmpl']->assign("cate_list_product",$cate_list_product);  //分类列表
        //最新上架
        $newest_sql="select * from ".DB_PREFIX."duobao_item where is_effect=1 and success_time = 0 and is_coupons = 0 and is_pk=0 and is_number_choose=0 and progress < 100 order by create_time desc limit 20";
        $newest_duobao_list = $GLOBALS['db']->getAll($newest_sql);
        foreach($newest_duobao_list as $k=>$v)
        {
            $newest_duobao_list[$k]['less_buy']=$v['max_buy']-$v['current_buy'];
        }
        $GLOBALS['tmpl']->assign("now_time",NOW_TIME);
        $GLOBALS['tmpl']->assign("newest_duobao_list",$newest_duobao_list);  

        //晒单分享
        $share_sql="select id,image_list,title,content,user_id,user_name,create_time from ".DB_PREFIX."share where is_effect=1 and is_index=1 order by is_top desc,create_time desc limit 16";
        $share = $GLOBALS['db']->getAll($share_sql);
        $i=0;$j=0;
        foreach ($share as $key =>$value) {
			$value['user_id']=$value['user_id'];
        	$value['title']=msubstr($value['title'],0,5);
        	$value['content']=msubstr($value['content'],0,40);
        	$value['image_list']=unserialize($value['image_list']);
        	$value['create_time']=to_date($value['create_time']);
        	if($value){
        		$share_list[$i][$j]=$value;
        		$j++;
        	}
        	if($j==2){
        		$i++;
        		$j=0;
        	}
        }
        //五倍开奖
        $duobao = $GLOBALS['db']->getAll("select * from  ".DB_PREFIX."duobao_item where is_five=1 and has_lottery=1 ");
        $GLOBALS['tmpl']->assign("duobao", $duobao);
        $GLOBALS['tmpl']->assign("share_list",$share_list);
		$GLOBALS['tmpl']->display("index.html");
	}
	 
}
?>