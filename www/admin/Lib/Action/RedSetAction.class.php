<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

Class RedSetAction extends CommonAction{
    public function index()
    {
        $conf_res = M("Conf")->where("is_effect = 1 and name = 'SPLIT_RED_MONEY'")->find();
        $ecvtype_res = M("EcvType")->where("send_type = 3")->find();
        $json_data = json_decode($ecvtype_res['data'], 1);
        	
        if ($json_data) {
            $ecvtype_res = array_merge($ecvtype_res, $json_data);
        }
        $ecvtype_res['money'] = number_format($ecvtype_res['money'],2);
        $this->assign("conf_res",$conf_res);
        $this->assign("ecvtype_res",$ecvtype_res);
        $this->display();
    }
    
    public function update()
    {
        $is_effect = intval($_REQUEST['is_effect']);
        $GLOBALS['db']->query("update ".DB_PREFIX."conf set value='".$is_effect."' where name = 'SPLIT_RED_MONEY'");
        $ecvtype['name'] = '拆分红包';
        $ecvtype['sm_way'] = intval($_REQUEST['sm_way']);//方式
        
        $ecvtype['money'] = intval($_REQUEST['money']); //红包总额
        
        $ecvtype['total_limit'] = intval($_REQUEST['total_limit']); //红包个数
        
        $ecvtype['draw_count'] = intval($_REQUEST['draw_count']); //每天能领取的个数
        
        $ecvtype['minchange_money'] = intval($_REQUEST['minchange_money']); //最小购买金额
        
        $ecvtype['is_all'] = intval($_REQUEST['is_all']); //是否为全部区可用
        
        $ecvtype['meet_amount'] = intval($_REQUEST['meet_amount']); //订单金额满多少可以该红包用
        
        $range_value['range_value1'] = intval($_REQUEST['range_value1']); //使用范围
        $range_value['range_value2'] = intval($_REQUEST['range_value2']);
        $range_value['range_value3'] = intval($_REQUEST['range_value3']);
        $range_value['range_value4'] = intval($_REQUEST['range_value4']);
        $range_value['range_value5'] = intval($_REQUEST['range_value5']);
        $range_value['range_value6'] = intval($_REQUEST['range_value6']);
        $range_value['range_value7'] = intval($_REQUEST['range_value7']);
        
        if($range_value['range_value1']==0&&$range_value['range_value2']==0&&$range_value['range_value3']==0&&$range_value['range_value4']==0&&$range_value['range_value5']==0&&$range_value['range_value6']==0&&$range_value['range_value7']==0&&$data['is_all']==0)
        {
            $this->error("请最少选择一个使用区域");
        }
        
        $json_data['domain']=$range_value;
        $ecvtype['data'] = json_encode($json_data);
        
        $ecvtype['begin_time'] = strim($_REQUEST['begin_time'])==''?0:to_timespan($_REQUEST['begin_time']);
        $ecvtype['end_time'] = strim($_REQUEST['end_time'])==''?0:to_timespan($_REQUEST['end_time']);
        $send_type= M("EcvType")->where("send_type = 3")->find();
        if($send_type){
            M("EcvType")->where("send_type = 3")->save($ecvtype);
            $this->success(L("UPDATE_SUCCESS"));
        }else{
            $ecvtype['send_type'] = 3;
            M("EcvType")->add($ecvtype);
            $this->success(L("INSERT_SUCCESS"));
        }
    }
}