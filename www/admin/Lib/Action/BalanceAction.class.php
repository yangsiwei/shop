<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class BalanceAction extends CommonAction
{

    public function index()
    {
// 		1营业额 2充值 4提现

        $type = intval($_REQUEST['type']);


        $this->assign("type", $type);

        $balance_title = "销售明细";
        if ($type == 2)
            $balance_title = "会员充值明细";
        if ($type == 4)
            $balance_title = "会员提现明细";


        //
        $year = intval($_REQUEST['year']);
        $month = intval($_REQUEST['month']);

        $current_year = intval(to_date(NOW_TIME, "Y"));
        $current_month = intval(to_date(NOW_TIME, "m"));

        if ($year == 0) $year = $current_year;
        if ($month == 0) $month = $current_month;

        $year_list = array();
        for ($i = $current_year - 10; $i <= $current_year + 10; $i++) {
            $current = $year == $i ? true : false;
            $year_list[] = array("year" => $i, "current" => $current);
        }

        $month_list = array();
        for ($i = 1; $i <= 12; $i++) {
            $current = $month == $i ? true : false;
            $month_list[] = array("month" => $i, "current" => $current);
        }


        $this->assign("year_list", $year_list);
        $this->assign("month_list", $month_list);

        $this->assign("cyear", $year);
        $this->assign("cmonth", $month);


        $begin_time = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01";
        $begin_time_s = to_timespan($begin_time, "Y-m-d H:i:s");

        $next_month = $month + 1;
        $next_year = $year;
        if ($next_month > 12) {
            $next_month = 1;
            $next_year = $next_year + 1;
        }
        $end_time = $next_year . "-" . str_pad($next_month, 2, "0", STR_PAD_LEFT) . "-01";
        $end_time_s = to_timespan($end_time, "Y-m-d H:i:s");

        $this->assign("balance_title", $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . " " . $balance_title);
        $this->assign("month_title", $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT));
        //

        $map['type'] = $type;
        $map['money'] = array("gt", 0);
        if ($begin_time_s && $end_time_s) {
            $map['create_time'] = array("between", array($begin_time_s, $end_time_s));
        } elseif ($begin_time_s) {
            $map['create_time'] = array("gt", $begin_time_s);
        } elseif ($end_time_s) {
            $map['create_time'] = array("lt", $end_time_s);
        }

        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }

        $model = D("StatementsLog");
        if (!empty ($model)) {
            $this->_list($model, $map);
        }

        $sum_money = $model->where($map)->sum("money");
        $this->assign("sum_money", $sum_money);

        $voList = $this->get("list");
        $page_sum_money = 0;
        foreach ($voList as $row) {
            $page_sum_money += floatval($row['money']);
        }
        $this->assign("page_sum_money", $page_sum_money);


        //开始计算利润率
        $stat_month = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT);
        $sql = "select sum(income_money) as income_money,
				sum(out_money) as out_money   from " . DB_PREFIX . "statements where stat_month = '" . $stat_month . "'";
        $stat_result = $GLOBALS['db']->getRow($sql);


        $accout_money = floatval($stat_result['income_money']) - floatval($stat_result['out_money']);


        $this->assign("stat_result", $stat_result);
        $this->assign("accout_money", $accout_money);

        $this->display();
        return;
    }

    public function foreverdelete()
    {
        $year = intval($_REQUEST['year']);
        $month = intval($_REQUEST['month']);

        if ($year == 0 || $month == 0) {
            $this->error("请选择日期");
        }


        $begin_time = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01";
        $begin_time_s = to_timespan($begin_time, "Y-m-d H:i:s");

        $next_month = $month + 1;
        $next_year = $year;
        if ($next_month > 12) {
            $next_month = 1;
            $next_year = $next_year + 1;
        }
        $end_time = $next_year . "-" . str_pad($next_month, 2, "0", STR_PAD_LEFT) . "-01";
        $end_time_s = to_timespan($end_time, "Y-m-d H:i:s");

        $stat_month = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT);

        $GLOBALS['db']->query("delete from " . DB_PREFIX . "statements_log where create_time between $begin_time_s and $end_time_s");
        $GLOBALS['db']->query("delete from " . DB_PREFIX . "statements where stat_month = '" . $stat_month . "'");

        $this->error("清空成功");

    }

    public function export_excel()
    {
//        $type = intval($_REQUEST['type']);


//        $this->assign("type", $type);

//        $balance_title = "销售明细";
//        if ($type == 2)
//            $balance_title = "会员充值明细";
//        if ($type == 4)
//            $balance_title = "会员提现明细";

        $year = intval($_REQUEST['year']);
        $month = intval($_REQUEST['month']);

        $current_year = intval(to_date(NOW_TIME, "Y"));
        $current_month = intval(to_date(NOW_TIME, "m"));

        if ($year == 0) $year = $current_year;
        if ($month == 0) $month = $current_month;

        $begin_time = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01";
        $begin_time_s = to_timespan($begin_time, "Y-m-d H:i:s");

        $next_month = $month + 1;
        $next_year = $year;
        if ($next_month > 12) {
            $next_month = 1;
            $next_year = $next_year + 1;
        }
        $end_time = $next_year . "-" . str_pad($next_month, 2, "0", STR_PAD_LEFT) . "-01";
        $end_time_s = to_timespan($end_time, "Y-m-d H:i:s");

        $balance_title= $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT)."统计报表" ;


        $map['type'] = array("in",array(1,2,4));
        $map['money'] = array("gt", 0);
        if ($begin_time_s && $end_time_s) {
            $map['create_time'] = array("between", array($begin_time_s, $end_time_s));
        } elseif ($begin_time_s) {
            $map['create_time'] = array("gt", $begin_time_s);
        } elseif ($end_time_s) {
            $map['create_time'] = array("lt", $end_time_s);
        }

        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }

        $model = D("StatementsLog");
//        if (!empty ($model)) {
//            $this->_list($model, $map);
//        }


//        $sum_money = $model->where($map)->sum("money");
            $voList=$model->where($map)->select();
//        $voList = $this->get("list");
//        $page_sum_money = 0;
//        foreach ($voList as $row) {
//            $page_sum_money += floatval($row['money']);
//        }
//        $this->assign("page_sum_money", $page_sum_money);


        //开始计算利润率
        $stat_month = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT);
        $sql = "select sum(income_money) as income_money,sum(out_money) as out_money   from " . DB_PREFIX . "statements where stat_month = '" . $stat_month . "'";
        $stat_result = $GLOBALS['db']->getRow($sql);
        $income_money=$stat_result['income_money']?$stat_result['income_money']:0;
        $out_money=$stat_result['out_money']?$stat_result['out_money']:0;
        vendor('Classes.PHPExcel');
        $Excel = new \PHPExcel();
        $start=1;
        $Excel ->setActiveSheetIndex(0)
            ->setCellValue("A".$start,$balance_title.","."月总收入为:".$income_money.","."月支出为:".$out_money)
            ->mergeCells("A$start:W$start");

        $start=2;
        $Excel ->setActiveSheetIndex(0)
            ->setCellValue("A".$start,"ID")
            ->setCellValue("B".$start,"创建时间")
            ->setCellValue("C".$start,"类型")
            ->setCellValue("D".$start,"金额")
            ->setCellValue("E".$start,"日志");
        foreach($voList as $key =>$val){
            $key=$key+$start+1;
            $Excel ->setActiveSheetIndex(0)
                   ->setCellValue("A".$key,$val["id"])
                   ->setCellValue("B".$key,to_date($val["create_time"], "Y-m-d H:i:s"))
                   ->setCellValue("C".$key,$this->get_type_name($val['type']))
                   ->setCellValue("D".$key,$val['money'])
                   ->setCellValue("E".$key,$val["log_info"]);
        }
        $Excel->getActiveSheet()->setTitle('export');
        $Excel->setActiveSheetIndex(0);
        $name='报表统计.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$name);
        header('Cache-Control: max-age=0');

        $ExcelWriter = \PHPExcel_IOFactory::createWriter($Excel, 'Excel2007');
        $ExcelWriter->save('php://output');
        exit;
    }

    public function get_type_name($type){
        $balance_title = "销售明细";
        if ($type == 2)
            $balance_title = "会员充值明细";
        if ($type == 4)
            $balance_title = "会员提现明细";
        return $balance_title;
    }
}
