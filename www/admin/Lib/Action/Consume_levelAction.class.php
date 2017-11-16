<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jobinlin
// +----------------------------------------------------------------------
class Consume_levelAction extends CommonEnhanceAction{
    public function index()
    {
        $consume = M("consume_level");
        $info = $consume->where(array("id"=>1))->find();
        $title_name = "消费级别场";
        $this->assign('info',$info);
        $this->assign('title_name',$title_name);
        $this->display();
    }
    public function edit(){
        $post = $_REQUEST;
        $consume = M("consume_level");
        $create = $consume->create($post);
        if($create){
            $res = $consume->where(array("id"=>1))->save();
            if($res){
                $this->success("修改成功",U('index'));
            }else{
                $this->error($consume->getError());
            }
        }
    }
}