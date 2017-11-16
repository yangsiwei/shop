<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jobinlin
// +----------------------------------------------------------------------
class Payment_shanAction extends CommonAction{
    public function index()
    {
        $payment_mode = M('payment_mode');
        $shan = $payment_mode->where(array('id'=>1))->find()['status'];
        $fql = $payment_mode->where(array('id'=>4))->find()['status'];
        $skb = $payment_mode->where(array('id'=>3))->find()['status'];
        $this->assign("title_name","支付方式设置");
        $this->assign("shan",$shan);
        $this->assign("fql",$fql);
        $this->assign("skb",$skb);
        $this->display ();
    }

    public function shan_open(){
        $shan = M('payment_mode');
        $res = $shan->where(array('id'=>1))->setField('status',1);
        if($res){
            $data['status'] = 1;
            $data['info'] = '爱贝开启成功';
        }else{
            $data['status'] = 0;
            $data['info'] = '爱贝开启失败';
        }
        ajax_return($data);
    }

    public function shan_close(){
        $shan = M('payment_mode');
        $res = $shan->where(array('id'=>1))->setField('status',0);
        if($res){
            $data['status'] = 1;
            $data['info'] = '爱贝关闭成功';
        }else{
            $data['status'] = 0;
            $data['info'] = '爱贝关闭失败';
        }
        ajax_return($data);
    }

    public function fql_open(){
        $fql = M('payment_mode');
        $res = $fql->where(array('id'=>4))->setField('status',1);
        if($res){
            $data['status'] = 1;
            $data['info'] = '付钱啦支付开启成功';
        }else{
            $data['status'] = 0;
            $data['info'] = '付钱啦支付开启失败';
        }
        ajax_return($data);
    }

    public function fql_close(){
        $fql = M('payment_mode');
        $res = $fql->where(array('id'=>4))->setField('status',0);
        if($res){
            $data['status'] = 1;
            $data['info'] = '付钱啦支付关闭成功';
        }else{
            $data['status'] = 0;
            $data['info'] = '付钱啦支付关闭失败';
        }
        ajax_return($data);
    }

    public function skb_open(){
        $skb = M('payment_mode');
        $res = $skb->where(array('id'=>3))->setField('status',1);
        if($res){
            $data['status'] = 1;
            $data['info'] = '数科宝支付开启成功';
        }else{
            $data['status'] = 0;
            $data['info'] = '数科宝支付开启失败';
        }
        ajax_return($data);
    }

    public function skb_close(){
        $skb = M('payment_mode');
        $res = $skb->where(array('id'=>3))->setField('status',0);
        if($res){
            $data['status'] = 1;
            $data['info'] = '数科宝支付关闭成功';
        }else{
            $data['status'] = 0;
            $data['info'] = '数科宝支付关闭失败';
        }
        ajax_return($data);
    }
}