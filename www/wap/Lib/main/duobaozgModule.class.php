<?php

class duobaozgModule extends MainBaseModule
{
	public function index()
	{
	    global_run();
	    init_app_page();
        init_app_page();
        //等级限制
        $consume_level = $GLOBALS['db']->getOne("select max from ".DB_PREFIX."consume_level where id =1");
        $user_level = $GLOBALS['user_info']['level_id'];
        $url = $_SERVER['HTTP_REFERER'];
        if($user_level<$consume_level){
            echo "<script>window.location.href='".$url."'</script>";
            die;
        }

        $param['page']    = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
	    $param['data_id'] = intval($_REQUEST['data_id']);
	    $param['keyword'] = strim($_REQUEST['keyword']);
	    $data = call_api_core("totalbuy","lists", $param);
	     
	    $page = new Page($data['page']['total'], $data['page']['page_size']); // 初始化分页对象
	    $p = $page->show();
	    
	    /* 数据 */
	    $GLOBALS['tmpl']->assign('pages', $p);
	    $GLOBALS['tmpl']->assign("list", $data['list']);
	    $GLOBALS['tmpl']->assign("data", $data);
	    
	    $GLOBALS['tmpl']->display("totalbuy_lists.html");
		 
	}
}
?>
