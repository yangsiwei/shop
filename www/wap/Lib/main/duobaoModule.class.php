<?php

class duobaoModule extends MainBaseModule
{
	public function index()
	{
		global_run();
		init_app_page();

        //获取参数
        $data_id = intval($_REQUEST['data_id']);
        $dbid = intval($_REQUEST['dbid']);
        $page = intval($_REQUEST['page']);
        //请求接口
        $data = call_api_core("duobao","index",array("data_id"=>$data_id, "dbid"=>$dbid, "page"=>$page));
        
        if(empty($data['item_data']))
        {
        	showErr($data['info']);
        }


         //五倍开奖
         $duobao = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where id = ".$data['item_data']['id']. " and has_lottery=0 ");
        if($data['item_data']['progress']==100  && $duobao[0]['fair_type']=='five') {//判断这个商品是否卖完，卖完就开奖
            $lottery_sn = $GLOBALS['db']->getAll("select * from " . DB_PREFIX . "duobao_item_log where duobao_item_id = " . $duobao[0]['id'] . " order by lottery_sn desc limit 1");
            $now = to_date($duobao[0]['lottery_time']);
//            $now2 = to_date(strtotime(" +1 minute",$duobao[0]['lottery_time']));
            $now3 = to_date(NOW_TIME);
//            echo $now;echo $now3;die;

            // if ($now <= $now3) {
                for ($i = 0; $i < 5; $i++) {//随机五个中奖码，修改对应表数据
                    $rand_array = range(100000001, $lottery_sn[0]['lottery_sn']);
                    $key = rand(0, 4);//获取不超过数组长度的随机数
                    $value = $rand_array[$key];//获得一个随机夺宝号码
                    $rand[] = mt_rand(100000001, $lottery_sn[0]['lottery_sn']);
                    //修改duobao_item_log中的is_luck为1
                    $GLOBALS['db']->query("update " . duobao_item_log_table($duobao[0]) . " set is_luck = 1 where lottery_sn = " . $rand[$i] . " and duobao_item_id = " . $duobao[0]['id'] . " and is_luck = 0");
                    //duobao_item中的has_lottery为1是开奖状态 展示中奖人信息
                    $GLOBALS['db']->query("update " . DB_PREFIX . "duobao_item set has_lottery = 1 where id=" . $duobao[0]['id']);
                    //修改deal__order_item为duobao_status 2 开奖
                    $GLOBALS['db']->query("update " . DB_PREFIX . "deal_order_item set duobao_status = 2 where duobao_item_id = " . $duobao[0]['id']);
                    //                $user_id= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log where duobao_item_id=".$duobao[0]['id']." and is_luck=1 ");
                    //              $dq =$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where id=".$user_id[$i]['user_id']);

                }

                //五倍开奖中奖人存到duobao_item表
                $duobao1 = $GLOBALS['db']->getAll("select * from " . DB_PREFIX . "duobao_item where id = " . $data['item_data']['id'] . " and has_lottery=1");
                $user_id1 = $GLOBALS['db']->getAll("select * from " . DB_PREFIX . "duobao_item_log where duobao_item_id=" . $duobao1[0]['id'] . " and is_luck=1 ");
                $arr = array($user_id1[0]['lottery_sn'], $user_id1[1]['lottery_sn'], $user_id1[2]['lottery_sn'], $user_id1[3]['lottery_sn'], $user_id1[4]['lottery_sn']);
                $lottery_sn = implode(" ,", $arr);
                $arr1 = array($user_id1[0]['user_id'], $user_id1[1]['user_id'], $user_id1[2]['user_id'], $user_id1[3]['user_id'], $user_id1[4]['user_id']);
                $username = implode(" ,", $arr1);

                $name = array();
                for ($i = 0; $i < 5; $i++) {
                    $name1 = $GLOBALS['db']->getOne("select user_name from " . DB_PREFIX . "user where id=" . $arr1[$i]);
                    array_push($name, $name1);
                }
                $arr2 = array($name[0], $name[1], $name[2], $name[3], $name[4]);
                $user_name1 = implode(" ,", $arr2);
                $GLOBALS['db']->query("update " . DB_PREFIX . "duobao_item set lottery_sn =concat_ws(',',{$lottery_sn}) ,luck_user_id=concat_ws(',',$username) where id=" . $duobao1[0]['id']);
                //中奖消息提示msg_box
                for ($i = 0; $i < 5; $i++) {
                    $name = $duobao1[0]['name'];
                    $lottery_sn = $user_id1[$i]['lottery_sn'];
                    $GLOBALS['db']->query("insert into " . DB_PREFIX . "msg_box (content,user_id,create_time,is_read,is_delete,type,data,data_id) values ('恭喜您，您参与的 $name 夺宝活动中奖了！', " . $user_id1[$i]['user_id'] . "," . NOW_TIME . ",0,0,'orderitem',0,'$lottery_sn')");
                }
            }
        // }
        $duobao1 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where id = ".$data['item_data']['id']. " and has_lottery=1 and fair_type='five' ");
        $lott = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log where duobao_item_id = ".$duobao1[0]['id']." and is_luck=1");

        foreach ($lott as $k=>$v){
            $username = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id=".$lott[$k]['user_id']);

            $lott[$k]['user_id']=$username;
        }
        //是否是五倍开奖
        if($lott[4]){
            $GLOBALS['tmpl']->assign("lott",$lott);
            $GLOBALS['tmpl']->assign("duobao1",$duobao1[0]);
        }
        
        $cart_conf_json = array(
            "max_buy"=>$data['item_data']['max_buy'],
            "min_buy"=>$data['item_data']['min_buy'],
            "current_buy"=>$data['item_data']['current_buy'],
            "residue_count"=>($data['item_data']['max_buy']-$data['item_data']['current_buy'])
        );
        
        $GLOBALS['tmpl']->assign("cart_data_json",  json_encode($data['cart_data']));
        $GLOBALS['tmpl']->assign("cart_conf_json",  json_encode($cart_conf_json));
        $GLOBALS['tmpl']->assign("item_data",$data['item_data']);
        $GLOBALS['tmpl']->assign("data",$data);
        
        if(isset($data['page']) && is_array($data['page'])){
        	$page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
        	$p  =  $page->show();
        	$GLOBALS['tmpl']->assign('pages',$p);
        }

        $GLOBALS['tmpl']->display("duobao.html");
	}


    public function helper(){
        global_run();
        init_app_page();
        $data_id = $_REQUEST['goods_id'];
        $dbid = $_REQUEST['data_id'];
        $goods_id = $_REQUEST['goods_id'];
        $goods = $GLOBALS['db']->getRow("select icon,name,max_buy from ".DB_PREFIX."duobao_item where id = ".$goods_id);
        $page = intval($_REQUEST['page']);


        $data = call_api_core("duobao","index",array("data_id"=>$data_id, "dbid"=>$dbid, "page"=>$page));
        $cart_conf_json = array(
            "max_buy"=>$data['item_data']['max_buy'],
            "min_buy"=>$data['item_data']['min_buy'],
            "current_buy"=>$data['item_data']['current_buy'],
            "residue_count"=>($data['item_data']['max_buy']-$data['item_data']['current_buy'])
        );
        if(isset($data['page']) && is_array($data['page'])){
            $page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
            $p  =  $page->show();
            $GLOBALS['tmpl']->assign('pages',$p);
        }

        $luck_user = $GLOBALS['db']->getRow("select luck_user_name,luck_user_buy_count,id from ".DB_PREFIX."duobao_item where is_effect = 1 and has_lottery = 1 and duobao_id = ".$dbid." order by create_time desc");

        $info = $GLOBALS['db']->getAll("select lottery_sn,duobao_item_id,user_name,buy_number  from ".DB_PREFIX."deal_order_item where duobao_id = ".$dbid." and lottery_sn != 0 order by order_sn desc");

        $GLOBALS['tmpl']->assign("cart_data_json",  json_encode($data['cart_data']));
        $GLOBALS['tmpl']->assign("cart_conf_json",  json_encode($cart_conf_json));
        $data['page_title'] = '夺宝助手';
        $GLOBALS['tmpl']->assign("luck_user",$luck_user);
        $GLOBALS['tmpl']->assign("info",$info);
        $GLOBALS['tmpl']->assign("item_data",$data['item_data']);
        $GLOBALS['tmpl']->assign('data',$data);
        $GLOBALS['tmpl']->assign('good',$goods);
        $GLOBALS['tmpl']->display("helper.html");
    }

    public function zst(){
        global_run();
        init_app_page();
        $id = $_POST['id'];
        $lenth = $GLOBALS['db']->getOne("select max_buy from ".DB_PREFIX."duobao_item where duobao_id = ".$id);
        $info = $GLOBALS['db']->getAll("select lottery_sn,duobao_item_id from ".DB_PREFIX."deal_order_item where duobao_id = ".$id." and lottery_sn != 0 order by order_sn desc limit 10");

        $zst['code'] = array();
        $zst['num'] = array();
        foreach($info as $v){
            $zst['code'][] = $v['lottery_sn']-100000000;
            $zst['num'][] = $v['duobao_item_id']-100000000;
        }
        $zst['num'] = array_reverse($zst['num']);
        if($info){
            $data['status'] = 'success';
            $data['info'] = $zst;
        }
        ajax_return($data);
    }

    public function jdt(){
        global_run();
        init_app_page();
        $id = $_POST['id'];
        $duobao_id = $_POST['duobao_id'];
        $data['progress'] = $GLOBALS['db']->getOne("select progress from ".DB_PREFIX."duobao_item where id = ".$id);
        $data['code'] = $GLOBALS['db']->getAll("select lottery_sn code from ".DB_PREFIX."duobao_item_log where duobao_item_id = ".$id." and user_id != 0 order by create_time desc");
        $data['code'][0]['code'] = $data['code'][0]['code']-100000000;
        if($data['code'][0]['code']<0){
            $data['code'][0]['code'] = '';
        }
        if($data['progress']&&$data['code']){
            $data['status'] = 'success';
        }

        ajax_return($data);

    }

    public function surplus(){
        global_run();
        init_app_page();
        $id = $_POST['id'];
        $surplus = $GLOBALS['db']->getAll("select lottery_sn code from ".DB_PREFIX."duobao_item_log where duobao_item_id = ".$id." and user_id = 0");

        if($surplus){
            $data['status'] = 'success';
            $data['info'] = $surplus;
        }
        ajax_return($data);
    }




    public function get_duobao_status(){
	    $data = call_api_core("duobao","get_duobao_status",array("data_id"=>  intval($_REQUEST['data_id'])));
	    $GLOBALS['tmpl']->assign("item_data",$data['item_data']);
	    $html = $GLOBALS['tmpl']->fetch("inc/is_luck_lottery_header.html");
	    $result['html'] = $html;
	    $result['status'] = 0;
	    if($data['item_data']['duobao_status'] == 2){
	        $result['status'] = 1;
	    }
	    ajax_return($result);
	}
	
	public function more()
	{
		global_run();
		init_app_page();
		
		//获取参数
		$data_id = intval($_REQUEST['data_id']);
		$data = call_api_core("duobao","more",array("data_id"=>$data_id));
		$GLOBALS['tmpl']->assign("data",$data);
		$GLOBALS['tmpl']->display("duobao_more.html");
	}
        
    public function detail(){
        global_run();
        init_app_page();

        //获取参数
        $data_id = intval($_REQUEST['data_id']);
        $data = call_api_core("duobao","detail",array("data_id"=>$data_id));
        $GLOBALS['tmpl']->assign("is_app",$GLOBALS['is_app']);
        $GLOBALS['tmpl']->assign("data",$data);
        $GLOBALS['tmpl']->display("duobao_detail.html");
    }
    public function duobao_record(){
      		
    	global_run();
    	init_app_page();
    	
    	$param['page'] = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    	$param['data_id'] = intval($_REQUEST['data_id']);
    	$data = call_api_core("duobao","duobao_record", $param);
    		
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
        $GLOBALS['tmpl']->display("duobao_record.html");
    }
}
?>
