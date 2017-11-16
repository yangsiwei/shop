<?php
class couponsApiModule extends MainBaseApiModule{
    
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
    		            success_time = 0 and is_coupons=1 and is_pk=0 ";
        if($cate_item)
        {
            $sql_count .=" and cate_id = ".$cate_item['id']." ";
        }

        if ($page == 0) $page = 1;
        $limit = (($page - 1) * $page_size) . "," . $page_size;
        $total = $GLOBALS['db']->getOne($sql_count);
        $page_data['total'] = $total;
        $page_data['page_size'] = $page_size;

        $sql = "SELECT id, name,is_topspeed,unit_price, max_buy,min_buy, current_buy, (max_buy-current_buy) as surplus_buy, icon
                    FROM
                    	".DB_PREFIX."duobao_item
    		        WHERE
    		            is_effect = 1 AND success_time = 0 and is_coupons=1 and is_pk=0";
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
            $data['page_title'] ="免费购专区";
        $data['type']='free';
        return output($data);


    }
}
