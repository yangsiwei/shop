<?php

class anno_user_centerModule extends MainBaseModule
{

	public function index()
	{
		global_run();
		init_app_page();

		$log_type = $param['log_type'] = intval($_REQUEST['log_type']);
		$param['page']  = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$param['uid']=intval($_REQUEST['lucky_user_id']);
		$data = call_api_core("anno_user_center","index", $param);
	    // print_r($data);die();

		if(isset($data['page']) && is_array($data['page'])){
			$page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
			$p  =  $page->show();
			$GLOBALS['tmpl']->assign('pages',$p);
		}

		//查询充值记录
        $charge_log = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_order where type = 1 and pay_status = 2 and user_id = ".$param['uid']);
        foreach($charge_log as &$v){
            $v['create_time'] = date("YmdHi",$v['create_time']);
        }
         //五倍开奖
        foreach ($data['list'] as $k=>$v){
            if (is_array($v['luck_user_id'])) {
                $a = explode(",",$v['luck_user_id']);
                $user =array();
                for ($i=0;$i<5;$i++){
                    $id = $a[$i];
                    $user1= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where id=".$id);
                    array_push($user,$user1);
                }
                $data['list'][$k]['luck_user_name']=$user[0][0]['user_name'].",".$user[1][0]['user_name'].",".$user[2][0]['user_name'].",".$user[3][0]['user_name'].",".$user[4][0]['user_name'];
      
            }
        }
		$GLOBALS['tmpl']->assign("charge_log",$charge_log);
		$GLOBALS['tmpl']->assign("data",$data);
		$GLOBALS['tmpl']->assign("user_info",$data['user_info']);
		$GLOBALS['tmpl']->assign("list",$data['list']);
		$GLOBALS['tmpl']->assign("share_list",$data['share_list']);
		$GLOBALS['tmpl']->assign("log_type",$log_type); 
		$GLOBALS['tmpl']->display("anno_user_center_index.html");
		
	}
	    

    public function my_no(){
        global_run();
        init_app_page();

        $param['id'] = intval($_REQUEST['id']);
        $param['uid'] = intval($_REQUEST['uid']);
        $data = call_api_core("anno_user_center","my_no", $param);



        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("list",$data['list']);
        $GLOBALS['tmpl']->assign("user_info",$data['user_info']);
        $GLOBALS['tmpl']->display("anno_user_center_my_no.html");
    }

    public function my_no_all(){

        global_run();
        init_app_page();

        $param['uid'] = intval($_REQUEST['uid']);
        $param['id'] = intval($_REQUEST['id']);
        $data = call_api_core("anno_user_center","my_no_all", $param);



        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->assign("list",$data['list']);
        $GLOBALS['tmpl']->assign("user_info",$data['user_info']);
        $GLOBALS['tmpl']->display("anno_user_center_my_no_all.html");
    }

}
?>