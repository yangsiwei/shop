<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class helpModule extends MainBaseModule
{
    public function index()
    {       
        global_run();
        init_app_page();
        $web_article_id = intval($_REQUEST['id']);
        $is_agreement = strim($_REQUEST['t']);
        $shptel = app_conf("SHOP_TEL");
        $page =intval($_REQUEST['p']);
        $page_size =app_conf("PAGE_SIZE");
        
        $GLOBALS['tmpl']->assign("is_agreement",$is_agreement);
        $GLOBALS['tmpl']->assign("web_article_id",$web_article_id);
        $GLOBALS['tmpl']->assign("shptel",$shptel);


        if(!$is_agreement){
            //输出文章
			if($web_article_id)
            $content = $GLOBALS['db']->getRow("select title,content,cate_id from ".DB_PREFIX."web_article where is_effect = 1 and id=".$web_article_id." ");
			else{
				$content = $GLOBALS['db']->getRow("select id,title,content,cate_id from ".DB_PREFIX."web_article where is_effect = 1 ORDER BY cate_id,sort ");
				$GLOBALS['tmpl']->assign("web_article_id",$content['id']);
			}
			$title = $GLOBALS['db']->getOne("select title from ".DB_PREFIX."web_article_cate where is_effect = 1 and id='".$content['cate_id']."' ");
			$GLOBALS['tmpl']->assign("title",$title);
            $GLOBALS['tmpl']->assign("content",$content);  //输出文章
        }else{
            //输出公告与服务协议
            if(!$web_article_id){
                require_once APP_ROOT_PATH."app/Lib/page.php";
                if ($page == 0) $page = 1;
                $limit = (($page - 1) * $page_size) . "," . $page_size;
                $count =$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."agreement where is_effect = 1 and agreement_cate='".$is_agreement."' order by create_time desc");
                
                $agreement_list = $GLOBALS['db']->getAll("select id,create_time,agreement_name from ".DB_PREFIX."agreement where is_effect = 1 and agreement_cate='".$is_agreement."' order by create_time desc" ." limit " . $limit);

                $page = new Page($count, $page_size); // 初始化分页对象
                $p = $page->show();
                $GLOBALS['tmpl']->assign('pages', $p);
                $GLOBALS['tmpl']->assign("agreement_list",$agreement_list);  
            }else{
                $agreement_one = $GLOBALS['db']->getRow("select create_time,agreement_name,agreement from ".DB_PREFIX."agreement where is_effect = 1 and id='".$web_article_id."' order by create_time desc");
       
                $GLOBALS['tmpl']->assign("agreement_one",$agreement_one);  
            }
        }

        $GLOBALS['tmpl']->display("helpcenter.html");
    }
    

    
}
?>