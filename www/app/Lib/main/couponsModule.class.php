<?php
 class couponsModule extends MainBaseModule{
     public function index(){
         global_run();
         init_app_page();
         $page  = intval($_REQUEST['p']);
         $id = intval($_REQUEST['id']);
         $min_buy = intval($_REQUEST['min_buy'])==0?10:intval($_REQUEST['min_buy']);


         $page_size =app_conf("DEAL_PAGE_SIZE");

         if($id>0)
             $cate_item = $GLOBALS['db']->getRow("select id,name from ".DB_PREFIX."deal_cate where id = ".$id);

         $sql_count = "SELECT count(*)
                    FROM
                        ".DB_PREFIX."duobao_item
                    WHERE
                        is_effect = 1 AND
                        success_time = 0 and is_coupons=1 ";
         if($cate_item)
         {
             $sql_count .=" and cate_id = ".$cate_item['id']." ";
         }
         //分页
         require_once APP_ROOT_PATH."app/Lib/page.php";
         if ($page == 0) $page = 1;
         $limit = (($page - 1) * $page_size) . "," . $page_size;
         $count = $GLOBALS['db']->getOne($sql_count);
         $sql = "SELECT id, name,is_coupons,is_topspeed, max_buy,min_buy,unit_price,current_buy, progress,(max_buy-current_buy) as surplus_buy, icon
                FROM ".DB_PREFIX."duobao_item
                WHERE is_effect = 1 AND success_time = 0 and is_coupons=1 ";
         if($cate_item)
         {
             $sql .=" and cate_id = ".$cate_item['id']." ";
         }
         $sql.=" ORDER BY id DESC ";

         $list= $GLOBALS['db']->getAll($sql ." limit " . $limit);
         foreach($list as $k=>$v)
         {
             $list[$k]['icon'] = get_spec_image($v['icon'],200,200,1);
         }

         $data['list']=$list;
         if($cate_item)
             $data['page_title'] = $cate_item['name'];
         else
             $data['page_title'] =$min_buy."夺宝币专区";
         $page = new Page($count, $page_size); // 初始化分页对象
         $p = $page->show();
         $GLOBALS['tmpl']->assign('pages', $p);
         $GLOBALS['tmpl']->assign("list", $data['list']);
         $GLOBALS['tmpl']->assign("data", $data);
         $GLOBALS['tmpl']->display("coupons.html");
     }
 }