<?php
class cateApiModule extends MainBaseApiModule{

	/**
	 * 所有分类
	 * array(
	 * array(
	 * 	id,name,iconfont,iconcolor
	 * )
	 * )
	 */
    public function index(){
    	
    	
    	
    	
    	$root = array();
    	$cate_list = $GLOBALS['db']->getAll("select id,name,iconfont,iconcolor from ".DB_PREFIX."deal_cate where is_effect = 1 order by sort asc");
    	
    	$root['list'] = $cate_list;
    		
    			
    	$root['page_title']="商品分类";
    	
    	
    	
    	return output($root);
    	
    	
    }
    
    
    
}