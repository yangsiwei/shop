<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class uc_ecvModule extends MainBaseModule
{
	public function index()
	{		
		global_run();
		init_app_page();
		$data['page_title'] = '我的红包';

		$GLOBALS['tmpl']->display("uc_ecv_index.html");
	}
	
	public function exchange(){
	    global_run();
	    init_app_page();
	    $data['page_title'] = '红包兑换';
	    
	    $GLOBALS['tmpl']->display("uc_ecv_exchange.html");
	}
	
	
}
?>