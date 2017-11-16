<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class duobaosModule extends MainBaseModule
{
    public function index()
    {   
        global_run();
        init_app_page();
        //分类
        
        $cate_list = load_cate_list(8);
        $GLOBALS['tmpl']->assign("cate_list",$cate_list);
        
        
        $id = intval($_REQUEST['id']);
        $keyword = strim($_REQUEST['keyword']);
        $order= strim($_REQUEST['t']);
        $order_dir= intval($_REQUEST['d']);
        $page =intval($_REQUEST['p']);
        $page_size =app_conf("DEAL_PAGE_SIZE");

        if($id>0)
            $cate_info = $GLOBALS['db']->getRow("select id,name from ".DB_PREFIX."deal_cate where id = ".$id);
        
        $sql_count = "SELECT count(*)
                    FROM
                        ".DB_PREFIX."duobao_item
                    WHERE
                        is_effect = 1 AND
                        success_time = 0 AND  is_coupons=0 and is_pk=0 and is_number_choose=0 and
                        progress < 100 ";
        if($id > 0)
        {
            $sql_count .=" and cate_id = ".$id." ";
        }
        //关键词搜索
        if($keyword)
        {
        	$sql_count .=" and name like '%".$keyword."%'";
        }
        //分页
        require_once APP_ROOT_PATH."app/Lib/page.php";
        if ($page == 0) $page = 1;
        $limit = (($page - 1) * $page_size) . "," . $page_size;
        $count = $GLOBALS['db']->getOne($sql_count);
        $sql = "SELECT di.id, di.name,di.is_topspeed, di.max_buy,di.min_buy, di.unit_price,di.current_buy,di.click_count, di.progress, (di.max_buy-di.current_buy) as surplus_buy, di.icon
                FROM ".DB_PREFIX."duobao_item as di 
                WHERE di.is_effect = 1 AND di.success_time = 0 and is_pk=0 and is_coupons=0 and is_number_choose=0  and di.progress < 100 ";
        
        //关键词搜索 
        if($keyword)
        {
            $sql .=" and di.name like '%".$keyword."%'";
        }

        //分类
        if($id>0)
        {
            $sql .=" and di.cate_id = ".$id." ";
        }
        
        if($order=="hot")
        {
        	$order_field = "click_count";
        }
        elseif($order=="sort")
        {
            $order_field = "sort";
        }
        elseif($order=="new")
        {
        	$order_field = "create_time";
        }
        else
        {
        	$order_field = $order;
        }
        
        //排序
        if(!$order_field)
        $sql.=" ORDER BY click_count DESC ";
        elseif($order_field=="sort")
        $sql.=" ORDER BY sort DESC";
        elseif($order_field=="max_buy"&&$order_dir)
        $sql.=" ORDER BY max_buy ASC ";
        elseif($order_field=="max_buy"&&!$order_dir)
        $sql.=" ORDER BY max_buy DESC ";
        elseif($order_field=="less")
        $sql.=" ORDER BY max_buy-current_buy ASC ";    
        else
        $sql.=" ORDER BY ".$order_field." DESC ";    
        
        //获得数据源
        $list= $GLOBALS['db']->getAll($sql ." limit " . $limit);
		if($page==floor(($count+$page_size-1)/$page_size)){
			if($count%4!=0){
				for($i=1;$i<=(4-($count%4));$i++){
					$list[]=array();
				}
			}
		}
		
		
        $data['list']=$list;
        $data['count']=$count;
        $data['id']=$id;
        $data['order']=$order;
        $data['dir']=$order_dir;
        $data['keyword']=$keyword;
       
        if($list)
            $data['page_title'] = $list[0]['name'];
        else
            $data['page_title'] ="夺宝活动";

        $page = new Page($count, $page_size); // 初始化分页对象
        $p = $page->show();
          
        /* 数据 */
        $GLOBALS['tmpl']->assign('pages', $p);
        $GLOBALS['tmpl']->assign("list", $data['list']);
        $GLOBALS['tmpl']->assign("data", $data);
        $GLOBALS['tmpl']->assign("cate_info", $cate_info);
        $GLOBALS['tmpl']->display("duobaos.html");
    }
    
}
?>