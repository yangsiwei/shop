<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class MNavAction extends CommonAction{
	
	public function index()
	{
	    $res = $GLOBALS['db']->getRow("select value from ".DB_PREFIX."conf where name = 'MNAV_SORT' ");
	    $config_list=unserialize($res['value']);
	    $this->assign('list', $config_list);
	    $this->display();
	}
	
	public function edit() {
	    $nav_sort=$_REQUEST['config_name'];
	     
	    $res = $GLOBALS['db']->getRow("select value from ".DB_PREFIX."conf where name = 'MNAV_SORT' ");
	    $config_list=unserialize($res['value']);
	    
	    $vo=$config_list[$nav_sort];
	    $this->assign('vo', $vo);
	    $this->display();
	}
	public function update() {
	    $nav_sort=$_REQUEST['config_name'];
	    
	    $res = $GLOBALS['db']->getRow("select value from ".DB_PREFIX."conf where name = 'MNAV_SORT' ");
	    $config_list=unserialize($res['value']);
	    $vo=$config_list[$nav_sort];
	    $vo['config_name']=$_REQUEST['config_name'];
	    $vo['name']=$_REQUEST['name'];
	    $vo['is_effect']=$_REQUEST['is_effect'];
	    $vo['sort']=$_REQUEST['sort'];
	    
	    foreach ($config_list as $k => $v){
	        if($k==$nav_sort){
	            $config_list[$k]=$vo;
	        }
	    }
	    $value=serialize($config_list);
	    $GLOBALS['db']->query("update ".DB_PREFIX."conf set value = '".$value."' where name = 'MNAV_SORT' ");
	    $this->assign('vo', $vo);
	    $this->success('修改成功');
	    
	}
	
}
?>