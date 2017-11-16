<?php

class annoModule extends MainBaseModule
{
	public function index()
	{
		global_run();
		init_app_page();

	    $param['page']         = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$param['duobao_id'] = intval($_REQUEST['duobao_id']);
	    $data = call_api_core("anno","index", $param);
	   	//用户中奖弹出大喜报数据
	    $user_info = $GLOBALS['user_info']['id'];
	    $sql = "select deal_id , id , name from ".DB_PREFIX."duobao_item where `luck_user_id` = ".$user_info."  ORDER BY id DESC LIMIT 1";
	    $res1 = $GLOBALS['db']->getAll($sql);
	    $res = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal where `id` =".$res1[0]['deal_id']);
		// $res2 = $GLOBALS['db']->getAll( "SELECT * FROM ".DB_PREFIX."duobao_item WHERE name LIKE '%伟蓝%' AND name LIKE '%音响%' AND luck_user_id=".$user_info." ORDER BY id DESC LIMIT 1");
		
		// if ($res2 !=="") {
		// 	require_once APP_ROOT_PATH."system/sms/LK_sms.php";  //发送中奖消息
			
		// 	$LK_sms = new LK_sms();
		// 	echo $LK_sms->sendSMS("15172365702", "测试");
		// 	$mobile_number = $GLOBALS['user_info']['mobile'];
		// 	$content ="您的京东卡密为：*****，请妥善保管";
		// 	if ($mobile_number) {
		// 		$LK_sms->sendSMS("15172365702", $content);
		// 	}
		// 	echo "asa";die();
		// }
		 //五倍开奖
        $duobao = $GLOBALS['db']->getAll("select * from  ".DB_PREFIX."duobao_item where fair_type='five' and has_lottery=1 ");
        foreach ($duobao as $k=>$v){
            $duobao[$k]['lottery_time']=date("Y-m-d H:i",strtotime("+8 hour",$v['lottery_time']));
            $a = explode(",",$v['luck_user_id']);
            $user =array();
            for ($i=0;$i<5;$i++){
                $id = $a[$i];
                $user1= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where id=".$id);
                array_push($user,$user1);


            }
            $duobao[$k]['luck_user_id']=$user[0][0]['user_name'].",".$user[1][0]['user_name'].",".$user[2][0]['user_name'].",".$user[3][0]['user_name'].",".$user[4][0]['user_name'];
        }

		foreach ($data['list'] as $key=>$value ){
		    $data['list'][$key]['lottery_time_show'] = to_date($value['lottery_time'],"H:i");
		    if(to_date($value['lottery_time'],"Y-m-d")==to_date(NOW_TIME,"Y-m-d"))
		    $data['list'][$key]['date'] = "今天";
		    elseif(to_date($value['lottery_time']+24*3600,"Y-m-d")==to_date(NOW_TIME,"Y-m-d"))
		    $data['list'][$key]['date'] = "昨天";
		    else
		    $data['list'][$key]['date'] = to_date($value['lottery_time'],"Y-m-d");
		}

		$page = new Page($data['page']['total'], $data['page']['page_size']); // 初始化分页对象
		$p = $page->show();

		/* 数据 */
		$GLOBALS['tmpl']->assign('pages', $p);
		$GLOBALS['tmpl']->assign("now_time", NOW_TIME);
		$GLOBALS['tmpl']->assign("list", $data['list']);
		$GLOBALS['tmpl']->assign("data", $data);
		$GLOBALS['tmpl']->assign("res", $res[0]);
		//$GLOBALS['tmpl']->assign("res2", $res2[0]);
		$GLOBALS['tmpl']->assign("duobao", $duobao);
		$GLOBALS['tmpl']->assign("res1", $res1[0]);
		$GLOBALS['tmpl']->display("anno.html");
	}
	public function ticket(){
		global_run();
		init_app_page();
		$user_info = $GLOBALS['user_info']['id'];
		$mobile = $GLOBALS['user_info']['mobile'];
	    $sql = "select deal_id , id name from ".DB_PREFIX."duobao_item where `luck_user_id` = ".$user_info." ORDER BY id LIMIT 1";
	    $res1 = $GLOBALS['db']->getAll($sql);
		
		$res2 = $GLOBALS['db']->getAll( "SELECT * FROM ".DB_PREFIX."duobao_item WHERE name LIKE '%伟蓝%' AND name LIKE '%音响%' AND luck_user_id=".$user_info." ORDER BY id DESC LIMIT 1");
		// print_r($res2);die();
		 $data['page_title'].="商品详情";
		$GLOBALS['tmpl']->assign("res2", $res2[0]);
		$GLOBALS['tmpl']->assign("data", $data);
		$GLOBALS['tmpl']->assign("mobile", $mobile);
		$GLOBALS['tmpl']->display("ticket.html");
	}
}
?>
