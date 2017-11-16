<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



class homeModule extends MainBaseModule
{
	public function index()
	{
	    //require APP_ROOT_PATH."system/model/uc_center_service.php";
		global_run();
		init_app_page();
		
		require_once APP_ROOT_PATH.'system/model/user.php';
		//点用户查看其他用户
		$id = intval($_REQUEST['id']);
		 
		//已登录的用户自己
		$my_user = $GLOBALS['user_info'];
		if($id==$my_user['id']){//为用户自己跳回个人主页
		    app_redirect(url("index","uc_duobao"));
		}else{
		    //其他用户
		    $home_user = load_user($id);
		}
		
		if(empty($home_user))
		{
			app_redirect(url("index"));
		}
		
		$sql_total = "select count(distinct(duobao_item_id)) from ".DB_PREFIX."deal_order_item where type = 2 and pay_status = 2 and refund_status=0 and user_id = ".$id;
		$total = $GLOBALS['db']->getOne($sql_total);
		
		//分页	
		require_once APP_ROOT_PATH."app/Lib/page.php";
		$page =intval($_REQUEST['p']);
		$page_size =app_conf("PAGE_SIZE");
		if ($page == 0) $page = 1;
		$limit = (($page - 1) * $page_size) . "," . $page_size;
		
		$sql = "select *,sum(number) as number from ".DB_PREFIX."deal_order_item where type = 2 and pay_status = 2 and refund_status=0 and user_id = ".$id." group by duobao_item_id order by create_time desc limit ".$limit;
		$list = $GLOBALS['db']->getAll($sql);
		foreach($list as $k=>$v)
		{
			$list[$k]['duobao_item'] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$v['duobao_item_id']);
			$list[$k]['duobao_item']['less'] = $list[$k]['duobao_item']['max_buy'] - $list[$k]['duobao_item']['current_buy'];
			if($list[$k]['duobao_item']['progress']==100 && $list[$k]['duobao_item']['success_time'] > 0){
				//最新一期
				$new_item_data= $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where duobao_id=".$list[$k]['duobao_item']['duobao_id']." and progress <100 order by create_time desc limit 0,1");
				if($new_item_data){
					$list[$k]['duobao_item']['new_duobao_item_id']=$new_item_data['id'];
				}
				
			}
			
		}
		
		$page = new Page($total, $page_size); // 初始化分页对象
		$p = $page->show();
		
		$GLOBALS['tmpl']->assign("list",$list);
		$GLOBALS['tmpl']->assign("pages",$p);
		$GLOBALS['tmpl']->assign("home_user",$home_user);
		$GLOBALS['tmpl']->display("home/duobao.html");
	    
	}
	

	
}
?>