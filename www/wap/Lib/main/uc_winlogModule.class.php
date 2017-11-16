<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class uc_winlogModule extends MainBaseModule
{

	public function index()
	{
		global_run();		
		init_app_page();

		
		$param=array();	
		$param['page'] = intval($_REQUEST['page']);	
		$param['data_id'] = intval($_REQUEST['data_id']);	
		$data = call_api_core("uc_winlog","index",$param);
		
		if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
			app_redirect(wap_url("index","user#login"));
		}
		
		foreach($data['list'] as $k=>$v){
			
			$data['list'][$k]['url']=wap_url("index","duobao",array("data_id"=>$v['duobao_item_id']));
		}
		
		if(isset($data['page']) && is_array($data['page'])){
			$page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
			$p  =  $page->show();
			$GLOBALS['tmpl']->assign('pages',$p);
		}

		 //五倍开奖
        $luck_user_id= $GLOBALS['user_info']['id'];
        $duobao = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where  luck_user_id LIKE '%{$luck_user_id}%' and has_lottery=1 and fair_type='five' order by success_time desc  ");
        $duobao1 = $GLOBALS['db']->getAll("select count(*) from ".DB_PREFIX."duobao_item where  luck_user_id LIKE '%{$luck_user_id}%' and has_lottery=1 and fair_type='five' order by success_time desc  ");
        foreach ($duobao as $k=>$v){
        	$duobao[$k]['create_time']=to_date($v['create_time']);
            $a = explode(",",$v['lottery_sn']);
            $lottery_sn =array();
            $c = array();
            $b = explode(",",$v['luck_user_id']);
             if( $b[0] ==$luck_user_id){
                $c[] = $a[0];
             }
            if($b[1] == $luck_user_id){
                $c[] = $a[1];
            }
            if($b[2] == $luck_user_id){
                $c[] = $a[2];
            }
            if($b[3] == $luck_user_id){
                $c[] = $a[3];
            }
            if($b[4] == $luck_user_id){
                $c[] = $a[4];
            }
            $d = implode(",",$c);
            $duobao[$k]['lottery_sn1']= $a;
            $duobao[$k]['luck_user_id1']= $b;
            $duobao[$k]['lottery_sn2']= $d;
        }
        $data['is_share'] = intval($_REQUEST['is_share']);
        $GLOBALS['tmpl']->assign("duobao",$duobao);
        $GLOBALS['tmpl']->assign("data",$data);
		$GLOBALS['tmpl']->display("uc_winlog.html");
	}
	
	public function remove_log()
	{
//		global_run();
//		init_app_page();
//
//		$param=array();
//		$param['id'] = intval($_REQUEST['id']);		
//		$data = call_api_core("uc_msg","remove_msg",$param);
//		if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
//			app_redirect(wap_url("index","user#login"));
//		}
//		if($data['del_status']==1){
//					$result['status'] = 1;
//					$result['url'] = wap_url("index","uc_msg");
//					ajax_return($result);			
//		}else{
//					$result['status'] =0;					
//					ajax_return($result);		
//		}
	}
	
	public function winlog_address()
	{
		global_run();		
		init_app_page();
		
		$cart = intval($_REQUEST['cart']);
		$order_id = intval($_REQUEST['order_id']);
		$order_item_id = intval($_REQUEST['order_item_id']);
		if($cart)
		{
			if($order_id)
				es_session::set("wap_cart_set_address_url",wap_url("index","cart#order",array("id"=>$order_id)));
			else
				es_session::set("wap_cart_set_address_url",wap_url("index","cart#check"));
		}
		else
		{
			es_session::set("wap_cart_set_address_url","");
		}
		
		$param=array();
		$param['order_item_id']=$order_item_id;				
		$data = call_api_core("uc_winlog","winlog_address",$param);
		
		if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
			app_redirect(wap_url("index","user#login"));
		}
		
		foreach($data['consignee_list'] as $k=>$v){
			$data['consignee_list'][$k]['url']= wap_url("index","uc_address#add",array("id"=>$v['id']));
			$data['consignee_list'][$k]['del_url']=wap_url('index','uc_address#del',array('id'=>$v['id']));
			$data['consignee_list'][$k]['dfurl']=wap_url('index','uc_address#set_default',array('id'=>$v['id']));			
		}

		$GLOBALS['tmpl']->assign("order_item_id",$data['order_item_id']);	
		$GLOBALS['tmpl']->assign("data",$data);	
		$GLOBALS['tmpl']->display("uc_winlog_address.html");
	}
	public function uc_luck_confirm_address()
	{
		global_run();
	    
	    $param['consignee_id'] = intval($_REQUEST['consignee_id']);
	    $param['order_item_id'] = intval($_REQUEST['order_item_id']);
		
		$data = call_api_core("uc_winlog","uc_luck_confirm_address",$param);
		
	    if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
			$data['jump'] = wap_url("index","user#login");
			ajax_return($data);
		}
		
	    ajax_return($data);
	}
	


}
?>