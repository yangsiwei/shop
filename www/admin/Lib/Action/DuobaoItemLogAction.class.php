<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

class DuobaoItemLogAction extends CommonAction{
    public function index(){
    	
        //列表过滤器，生成查询Map对象
        $duobao_item = M("DuobaoItem")->where("id=".intval($_REQUEST['duobao_item_id']))->find();
    	
        if($duobao_item['log_moved']==1)
        $model = D("DuobaoItemLogHistory");
        else
        $model = D('DuobaoItemLog');
        $map = $this->_search ($model);
        
        if(strim($_REQUEST['user_name']))
        {
        	$map['user_id'] = intval(M("User")->where("user_name='".strim($_REQUEST['user_name'])."'")->getField("id"));
        }
        else
        {
        	$map['user_id'] = array("gt",0);
        }
        
        $count = $model->where($map)->count();
        $this->_list ( $model,$map,"",false,$count);
        
        
        $duobao_name = $duobao_item['name'];
        
        $this->assign("duobao_name",$duobao_name);
      
        
        $this->display ();
    }
    
    
   
}