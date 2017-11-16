<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class redsetModule extends MainBaseModule
{
    public function index(){
        global_run();
        init_app_page();
        
        $param=array();
        $param['order_sn']=$_REQUEST['order_sn'];
        $param['limit']=intval($_REQUEST['pid']);
        
        $data = call_api_core("redset","index",$param);
        
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("redset.html");
    }
    
    public function dophlogin()
    {
        global_run();
        $param['mobile'] = strim($_REQUEST['mobile']);
        $param['order_sn']=$_REQUEST['order_sn'];
        $param['limit']=intval($_REQUEST['limit']);
    
        $data = call_api_core("redset", "dophlogin",$param);
        
        ajax_return($data);
    }
}