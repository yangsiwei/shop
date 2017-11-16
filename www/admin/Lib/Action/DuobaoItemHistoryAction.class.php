<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

class DuobaoItemHistoryAction extends CommonEnhanceAction{
    public function index(){
        //列表过滤器，生成查询Map对象
        $model = D ('DuobaoItemHistoryView');
        $map = $this->_search ($model);
        
        foreach ($map as $key=>$value){
            if(stripos($key, 'create_time')){
                $k = str_replace('create_time', 'lottery_time', $key);
                $map[$k] = $value;
                unset($map[$key]);
            }
            
        }
        
        $this->_list ( $model, $map );
        $this->display ();
    }
    
    
   
}