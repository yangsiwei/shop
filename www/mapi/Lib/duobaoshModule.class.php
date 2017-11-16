<?php
class duobaoshApiModule extends MainBaseApiModule{
    /**
     * 百元区全部商品列表接口
     * 输入：
     * data_id: int 分页ID
     * page:int 当前的页数
    
     *
     * 输出：
        array (
          'page' => 
              array (
                'total' => '7',  分页总数
                'page_size' => 20, 分页大小
              ),
          'list' => 
              array (
                0 => 
                array (
                  'id' => '10000342',   夺宝id
                  'name' => '初体验3小时家务保洁！提前1天预约！',  夺宝商品名称
                  'max_buy' => '1000',    总需要次数
                  'current_buy' => '90',    当前购买次数
                  'surplus_buy' => '910',   剩余次数
                  'icon' => './public/attachment/201509/18/16/55fbcc815d651.jpg',  夺宝商品
                )
         )
     */
    public function index(){
    	$min_buy=1;
        $page_size = PAGE_SIZE;
        $data_id = intval($GLOBALS['request']['data_id']);
        $page      = intval($GLOBALS['request']['page']);
    
		if($data_id>0)
			$cate_item = $GLOBALS['db']->getRow("select id,name from ".DB_PREFIX."deal_cate where id = ".$data_id);
        
        $sql_count = "SELECT count(*)
                    FROM
                    	".DB_PREFIX."duobao_item
    		        WHERE
    		            is_effect = 1 AND
    		            success_time = 0 and is_pk=0 and is_topspeed=0 and is_coupons=0 and is_number_choose=0 and min_buy = ".$min_buy." and unit_price = 100 ";
        if($cate_item)
        {
        	$sql_count .=" and cate_id = ".$cate_item['id']." ";
        }
    
        if ($page == 0) $page = 1;
        $limit = (($page - 1) * $page_size) . "," . $page_size;
        $total = $GLOBALS['db']->getOne($sql_count);
        $page_data['total'] = $total;
        $page_data['page_size'] = $page_size;
    
        $sql = "SELECT id, name,unit_price, max_buy,min_buy, current_buy, (max_buy-current_buy) as surplus_buy, icon
                    FROM
                    	".DB_PREFIX."duobao_item
    		        WHERE
    		            is_effect = 1 AND is_pk=0 and is_topspeed=0 and is_coupons=0 and is_number_choose=0 and success_time = 0 and min_buy = ".$min_buy." and unit_price = 100 ";
        if($cate_item)
        {
        	$sql .=" and cate_id = ".$cate_item['id']." ";
        }
        
        $sql.=" ORDER BY id DESC ";
        $list = $GLOBALS['db']->getAll($sql ." limit " . $limit);
        foreach($list as $k=>$v)
        {
        	$list[$k]['icon'] = get_abs_img_root(get_spec_image($v['icon'],200,200,1));
        }
    
        require_once APP_ROOT_PATH."system/model/duobao.php";
        $cart_info=duobao::getcart($GLOBALS['user_info']['id']);
        $data['cart_info']=$cart_info;
        
        /* 分页 */
        $data['page'] = $page_data;
        $data['list'] = $list;
        if($cate_item)
       	  	$data['page_title'] = $cate_item['name'];
        else
        	$data['page_title'] ="百元专区";
        return output($data);
         
    
    }
}
 