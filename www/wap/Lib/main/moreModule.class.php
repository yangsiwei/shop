<?php
class moreModule extends MainBaseModule
{
    public function index()
    {
        global_run();
        init_app_page();
        $data = call_api_core("more", "index");

        foreach($data['indexs'] as $k=>$v)
        {
            $data['indexs'][$k]['url'] =  getWebAdsUrl($v);
        }

        $data['page_title'] = '竞技场';
        require_once APP_ROOT_PATH."system/model/duobao.php";
        $data['cart_info']=duobao::getcart($GLOBALS['user_info']['id']);

        $GLOBALS['tmpl']->assign('data',$data);
        $GLOBALS['tmpl']->display("more_index.html");
    }
}