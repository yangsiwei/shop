<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class IncomeBalanceAction extends CommonAction{
	public function index()
	{

	    $map['begin_time'] = to_timespan($_REQUEST['begin_time']);
	    $map['end_time'] = $_REQUEST['end_time']?$_REQUEST['end_time']:NOW_TIME;
	    $map['end_time'] = to_timespan($map['end_time']);
	    
	    $map['id'] = intval($_REQUEST['id']);
	    $contend='';
	    if($map['id'])
	        $contend = " and t_di.id=".$map['id'];
	    
	    if($map['begin_time'])
	       $contend = " and lottery_time >".$map['begin_time']." and lottery_time<".$map['end_time'];

	        
	    $page = intval($_REQUEST['p'])?intval($_REQUEST['p']):1;
	    $page_size = 50; 
	    $limit = (($page - 1)*$page_size).",".($page_size);
	    //取得满足条件的记录数
		$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."duobao_item t_di where has_lottery = 1 ".$contend);
// echo "select count(*) from ".DB_PREFIX."duobao_item where has_lottery = 1 ".$contend;exit;

		if ($count > 0) {
			$p = new Page ( $count, $page_size );
			//分页查询数据
            $robot_ids = $GLOBALS['db']->getOne("select group_concat(id) from ".DB_PREFIX."user where is_robot=1");
            
            $sql = "select t_di.id,t_di.name,t_di.max_buy,t_d.origin_price,
                robot_buy_count as robot_count,
                t_di.luck_user_name as user_name,
                t_di.lottery_time
                from 
                fanwe_duobao_item t_di 
                left join fanwe_deal t_d on t_d.id=t_di.deal_id
                where t_di.has_lottery = 1 ".$contend." order by t_di.lottery_time desc ,t_di.id desc limit ".$limit;

            $voList = $GLOBALS['db']->getAll($sql);
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
			//分页显示

			$page = $p->show ();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? l("ASC_SORT") : l("DESC_SORT"); //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			$this->assign ( 'list', $voList );
			$this->assign ( "page", $page);
			$this->assign ( "nowPage",$p->nowPage);
		}
		$this->display();
	}
	
	public function export_csv($page = 1)
	{
		set_time_limit(0);
// 		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		$map['begin_time'] = to_timespan($_REQUEST['begin_time']);
		$map['end_time'] = $_REQUEST['end_time']?$_REQUEST['end_time']:NOW_TIME;
		$map['end_time'] = to_timespan($map['end_time']);
		$map['id'] = intval($_REQUEST['id']);
		
		$contend='';
		if($map['id'])
		    $contend = " and t_di.id=".$map['id'];
		
		if($map['begin_time'])
		    $contend = " and lottery_time >".$map['begin_time']." and lottery_time<".$map['end_time'];
		
		$page = intval($_REQUEST['p'])?intval($_REQUEST['p']):$page;
		$page_size = 50;
		$limit = (($page - 1)*$page_size).",".($page_size);
		
		
		$sql = "select t_di.id,t_di.name,t_di.max_buy,t_d.current_price,
                robot_buy_count as robot_count,
                t_di.luck_user_name as user_name,
                t_di.lottery_time
                from 
                fanwe_duobao_item t_di 
                left join fanwe_deal t_d on t_d.id=t_di.deal_id
                where t_di.has_lottery = 1 ".$contend." limit ".$limit;

		$list = $GLOBALS['db']->getAll($sql);
		
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$ecv_value = array(
			    'id'=>'""', 
			    'name'=>'""',
			    'max_buy'=>'""', 
			    'current_price'=>'""', 
			    'robot_count'=>'""',
			    'user_name'=>'""',
			    'lottery_time'=>'""',
			);
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","期号,商品名称,参与人次,成本价格,机器人数量,中奖人姓名,开奖时间");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				
				$ecv_value['id'] = '"' . iconv('utf-8','gbk',$v['id']) . '"';
				$ecv_value['name'] = '"' . iconv('utf-8','gbk',$v['name']) . '"';
				$ecv_value['max_buy'] = '"' . iconv('utf-8','gbk',$v['max_buy']) . '"';
				$ecv_value['current_price'] = '"' . iconv('utf-8','gbk',round($v['current_price'],2)."元") . '"';
				$ecv_value['robot_count'] = '"' . iconv('utf-8','gbk',$v['robot_count']) . '"';
				$ecv_value['user_name'] = '"' . iconv('utf-8','gbk',$v['user_name']) . '"';
				$ecv_value['lottery_time'] = '"' . iconv('utf-8','gbk',to_date($v['lottery_time'])) . '"';			
				
				$content .= implode(",", $ecv_value) . "\n";
			}	

			
			header("Content-Disposition: attachment; filename=voucher_list.csv");
	    	echo $content;  exit;
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}	
		
	}
	
	
	
}
?>