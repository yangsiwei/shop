<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

define("SALE_COLOR","#404d60");
define("REFUND_COLOR","#10b9a5");
define("VERIFY_COLOR","#ff6600");
class OfcAction extends CommonAction{
	
	
	/**
	 * 订单来路的图饼展示
	 */
	
	
	
	public function sale_line()
	{

		
		//定义天数最近30天
		$begin_time = NOW_TIME - 30*24*3600;
		$end_time = NOW_TIME;
		$begin_time_date = to_date($begin_time,"Y-m-d");
		$end_time_date = to_date($end_time,"Y-m-d");
		
		$x_labels = array();  //x轴的标题
		for($i=0;$i<30;$i++)
		{
			$x_labels[] = to_date($begin_time+$i*24*3600,"d");
		}		
		$result['x_axis'] = array("labels"=>array("labels"=>$x_labels));
		
		$sql = "select income_order,stat_time from ".DB_PREFIX."statements where stat_time > '".$begin_time_date."' and stat_time <= '".$end_time_date."'";		
		$stat_result = $GLOBALS['db']->getAll($sql);

		//开始定义每个数据的线条元素
		$max_value = 0;
		
		//销售额线条元素
		$sale_line_values = array();
		for($i=0;$i<=30;$i++)
		{
			$stat_time = to_date($begin_time+$i*24*3600,"Y-m-d");
			$data_row = array("value"=>0,"tip"=>$stat_time."营业额0元");
			foreach($stat_result as $row)
			{				
				if($row['stat_time']==$stat_time)
				{				
					if($row['income_order']>$max_value)$max_value = $row['income_order'];
					$data_row = array("value"=>floatval($row['income_order']),"tip"=>$stat_time."营业额".round($row['income_order'],2)."元");
				}				
			}
			$sale_line_values[] = $data_row;
		}
		$sale_line_element = array("type"=>"line","colour"=>SALE_COLOR,"text"=>"营业额","width"=>2,"values"=>$sale_line_values);
		
		
		
	
		$max_value = ofc_max($max_value);
		
		$result['y_axis'] = array("max"=>floatval($max_value));
		$result['elements'] = array($sale_line_element);
		$result['bg_colour']	= "#ffffff";
		
		
		ajax_return($result);
	}
	
	
	
	
	
	public function sale_month_line()
	{
	
	
		$year = intval($_REQUEST['year']);
		$month = intval($_REQUEST['month']);
		
		$current_year = intval(to_date(NOW_TIME,"Y"));
		$current_month = intval(to_date(NOW_TIME,"m"));
		
		if($year==0)$year = $current_year;
		if($month==0)$month = $current_month;
		
		$days_list = array(31,28,31,30,31,30,31,31,30,31,30,31);
		$days = $days_list[$month-1];
		if($days==28&&$year%4==0&&($year%100!=0||$year%400==0))
		{
			$days = 29;
		}
				
		$stat_month = $year."-".str_pad($month,2,"0",STR_PAD_LEFT);
		
		//月数据	
		$x_labels = array();  //x轴的标题
		for($i=1;$i<=$days;$i++)
		{
			$x_labels[] = $i."日";
		}
		$result['x_axis'] = array("labels"=>array("labels"=>$x_labels));
	
		$sql = "select income_order,income_incharge,out_uwd_money,stat_time from ".DB_PREFIX."statements where stat_month = '".$stat_month."'";
		$stat_result = $GLOBALS['db']->getAll($sql);
	
		//开始定义每个数据的线条元素
		$max_value = 0;
	
		//销售额线条元素
		$sale_line_values = array();
		for($i=1;$i<=$days;$i++)
		{
			$stat_time = $stat_month."-".str_pad($i,2,"0",STR_PAD_LEFT);
			$data_row = array("value"=>0,"tip"=>$stat_time."营业额0元");
			foreach($stat_result as $row)
			{
				if($row['stat_time']==$stat_time)
				{
					if($row['income_order']>$max_value)$max_value = $row['income_order'];
					$data_row = array("value"=>floatval($row['income_order']),"tip"=>$stat_time."营业额".round($row['income_order'],2)."元");
				}
			}
			$sale_line_values[] = $data_row;
		}
		$sale_line_element = array("type"=>"line","colour"=>SALE_COLOR,"text"=>"营业额","width"=>2,"values"=>$sale_line_values);
	
	
	
		//会员充值线条元素
		$income_incharge_line_values = array();
		for($i=1;$i<=$days;$i++)
		{
			$stat_time = $stat_month."-".str_pad($i,2,"0",STR_PAD_LEFT);
			$data_row = array("value"=>0,"tip"=>$stat_time."充值0元");
			foreach($stat_result as $row)
			{
				if($row['stat_time']==$stat_time)
				{
					if($row['income_incharge']>$max_value)$max_value = $row['income_incharge'];
					$data_row = array("value"=>floatval($row['income_incharge']),"tip"=>$stat_time."充值".round($row['income_incharge'],2)."元");
				}
			}
			$income_incharge_line_values[] = $data_row;
		}
		$income_incharge_line_element = array("type"=>"line","colour"=>REFUND_COLOR,"text"=>"充值额","width"=>2,"values"=>$income_incharge_line_values);
	
		//提现额线条元素
		$out_uwd_money_line_values = array();
		for($i=1;$i<=$days;$i++)
		{
			$stat_time = $stat_month."-".str_pad($i,2,"0",STR_PAD_LEFT);
			$data_row = array("value"=>0,"tip"=>$stat_time."提现额0元");
			foreach($stat_result as $row)
			{
				if($row['stat_time']==$stat_time)
				{
					if($row['out_uwd_money']>$max_value)$max_value = $row['out_uwd_money'];
					$data_row = array("value"=>floatval($row['out_uwd_money']),"tip"=>$stat_time."提现额".round($row['out_uwd_money'],2)."元");
				}
			}
			$out_uwd_money_line_values[] = $data_row;
		}
		$out_uwd_money_line_element = array("type"=>"line","colour"=>VERIFY_COLOR,"text"=>"提现额","width"=>2,"values"=>$out_uwd_money_line_values);
	
		$max_value = ofc_max($max_value);

		$result['y_axis'] = array("max"=>floatval($max_value));
		$result['elements'] = array($sale_line_element,$income_incharge_line_element,$out_uwd_money_line_element);
		$result['bg_colour']	= "#ffffff";


		ajax_return($result);
	}
	
	
	
}
?>