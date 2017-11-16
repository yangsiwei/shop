<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class duobaozgModule extends MainBaseModule
{
    public function index()
    {   //输出直购夺宝币专区
        global_run();
        init_app_page();
     
        $id = intval($_REQUEST['id']);
        $page  = intval($_REQUEST['p']);
        $page_size =app_conf("DEAL_PAGE_SIZE");
       
        //分页
        require_once APP_ROOT_PATH."app/Lib/page.php";
        if ($page == 0) {
            $page = 1;
        }
        $limit = (($page - 1) * $page_size) . "," . $page_size;
        
        $sql_count = "select count(*) 
                    from ".DB_PREFIX."duobao_item dbi 
                    left join ".DB_PREFIX."deal deal on dbi.deal_id=deal.id 
                    where is_total_buy='1' and total_buy_stock > 0 and dbi.is_effect = 1 and success_time = 0";
        $count = $GLOBALS['db']->getOne($sql_count);
        
        $sql = "select * 
                from ".DB_PREFIX."deal deal 
                left join ".DB_PREFIX."duobao_item dbi on dbi.deal_id=deal.id 
                where is_total_buy='1' and total_buy_stock > 0 and  dbi.is_effect = 1 and success_time = 0 order by dbi.create_time desc";
        $data = $GLOBALS['db']->getAll($sql ." limit " . $limit);  
        $duobaozg_adv = $GLOBALS['db']->getOne("select image from ".DB_PREFIX."adv where page_module='index|duobaozg#index'");

        $page = new Page($count, $page_size); // 初始化分页对象
        $p = $page->show();
        
        /* 数据 */
        $GLOBALS['tmpl']->assign('pages', $p);
        $GLOBALS['tmpl']->assign("duobaozg_adv", $duobaozg_adv);
        $GLOBALS['tmpl']->assign("data", $data);
        $GLOBALS['tmpl']->display("duobaozg.html");
    }
    
}
?>