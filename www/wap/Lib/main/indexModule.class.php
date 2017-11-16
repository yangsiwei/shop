<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class indexModule extends MainBaseModule
{
	public function index()
	{
		global_run();

		init_app_page();

		$param['page'] = intval($_REQUEST['page']);
		$param['order'] = strim($_REQUEST['order']);
		$param['order_dir']=intval($_REQUEST['order_dir']);

		$data = call_api_core("index","wap",$param);
 
		foreach($data['advs'] as $k=>$v)
		{

			$data['advs'][$k]['url'] =  getWebAdsUrl($v);
		}

		foreach($data['indexs'] as $k=>$v)
		{
			foreach($data['indexs'][$k] as $kk=>$vv){
				$data['indexs'][$k][$kk]['url'] =  getWebAdsUrl($vv);
			}
		}

		foreach($data['index_duobao_list'] as $k=>$v)
		{
			$data['index_duobao_list'][$k]['url'] =  wap_url("index","duobao",array("data_id"=>$v['id']));

		}

		if(isset($data['page']) && is_array($data['page'])){

			//感觉这个分页有问题,查询条件处理;分页数10,需要与sjmpai同步,是否要将分页处理移到sjmapi中?或换成下拉加载的方式,这样就不要用到分页了
			$page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
			//$page->parameter
			$p  =  $page->show();
			//print_r($p);exit;
			$GLOBALS['tmpl']->assign('pages',$p);
		}
		
		 
		
	    //注册送红包弹窗
		if ( es_session::get("is_send_reg_ecv") == 1 ){
		    $GLOBALS['tmpl']->assign('reg_ecv_money', es_session::get("reg_ecv_money"));
		    $GLOBALS['tmpl']->assign('is_send_reg_ecv', 1);
		    
		    es_session::set("is_send_reg_ecv", '');
		    es_session::set("reg_ecv_money", '');
		}
		 
		$GLOBALS['tmpl']->assign("data",$data);
		
		$m_config = getMConfig();//初始化手机端配置
		
		if (es_cookie::get('is_app_down')||(!$m_config['ios_down_url']&&!$m_config['android_filename'])){
			$GLOBALS['tmpl']->assign('is_show_down',0);//用户已下载
		}else{
			$GLOBALS['tmpl']->assign('is_show_down',1);//用户未下载
		}
		$res = $GLOBALS['db']->getAll("select id from ".DB_PREFIX."duobao where place_id > 0");
		foreach($res as $v){
		    $duo_id[] = $v['id'];
        }
        $duo_id = join(',',$duo_id);
        $deal = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where duobao_id in ({$duo_id}) and has_lottery=0");
        foreach($deal as &$vv){
            $vv['surplus_buy'] = $vv['max_buy']-$vv['current_buy'];
        }

        $daren = $GLOBALS['user_info']['is_daren'];
        if($daren == 0){
            $msg_system = true;
        }
        $GLOBALS['tmpl']->assign('msg_system',$msg_system);
        $GLOBALS['tmpl']->assign('res',$deal);
        $GLOBALS['tmpl']->display("index.html");
	}

	public function xiaoxi(){
        global_run();
        $res = $GLOBALS['db']->query("update ".DB_PREFIX."user set is_daren=1 where id = ".$GLOBALS['user_info']['id']);
        if($res){
            $data['status'] = 1;
            $data['info'] = true;
        }
	    ajax_return($data);
    }
	//每日签到
	public function qid(){
        global_run();

        init_app_page();

		if ($GLOBALS['user_info']){
            if ($_POST['do']=="sign"){
                $uid = $GLOBALS['user_info']['id'];
                $time = time();
                $sign_days = $GLOBALS['user_info']['sign_days'];
                $todayBegin=strtotime(date('Y-m-d')." 00:00:00");
                $todayEnd= strtotime(date('Y-m-d')." 23:59:59");
                $checkSignSql="SELECT * FROM ".DB_PREFIX."sign WHERE `uid` = ".$uid." AND `dateline` < ".$todayEnd." AND `dateline` > ".$todayBegin;
                $checkContinuYesterday = $GLOBALS['db']->getAll($checkSignSql);//查询今天有没有签到
                $yesterdayBegin= strtotime(date("Y-m-d",strtotime("-1 day"))." 00:00:00");
                $yesterdayEnd= strtotime(date("Y-m-d",strtotime("-1 day"))." 23:59:59");
                $checkContinuSql1="SELECT * FROM ".DB_PREFIX."sign WHERE `uid` = {$uid} AND `dateline` < {$yesterdayEnd} AND `dateline` > {$yesterdayBegin}";
                $checkContinuYesterday1 = $GLOBALS['db']->getAll($checkContinuSql1);//查询昨天有没有签到

				//判断今天有没有签到
                if(empty($checkContinuYesterday)){
                    $frist_sql="SELECT * FROM ".DB_PREFIX."sign WHERE `uid` = {$uid}  ";//查询是否签到过
                    $frist_data = $GLOBALS['db']->getAll($frist_sql);
                    if (empty($frist_data)){
                        $info= $GLOBALS['db']->query("INSERT INTO ".DB_PREFIX."sign (`uid`,`dateline`,`red_packet`,`frequency`) VALUES ( ".$uid.",".$time.",'0.1','1')" );
                        $GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET `sign_days` = 1 WHERE `id` =".$uid);
                        $checkSignSq="SELECT * FROM ".DB_PREFIX."sign WHERE `uid` = ".$uid." AND `dateline` < ".$todayEnd." AND `dateline` > ".$todayBegin;
                        $checkContinuYesterda = $GLOBALS['db']->getAll($checkSignSq);//查询今天有没有签到
                        if($info){
                            $GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET `red_packet_total` = {$checkContinuYesterday1[0]['red_packet']} WHERE `id` =".$uid);
                            $data['status'] = 'success';
                            $data['info'] = "+{$checkContinuYesterda[0]['red_packet']}红包";
                                $data['msg'] ="已经连续签到1天了，继续努力!+{$checkContinuYesterday[0]['red_packet']}红包";
                        }
                        echo json_encode($data);
                    }else{//是否连续签到
						if(!empty($checkContinuYesterday1)){//如果昨天签到过则今天签到的天数加一
                            $rand = rand(1,10)/10;
                            $info=$GLOBALS['db']->query("INSERT INTO ".DB_PREFIX."sign (`uid`,`dateline`,`red_packet`,`frequency`) VALUES ( ".$uid.",".$time.",'$rand'," .$GLOBALS['user_info']['sign_days']."+1)" );
                            $GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET `sign_days` = `sign_days` + 1 WHERE `id` =".$uid);
                            $checkSignSql2="SELECT * FROM ".DB_PREFIX."sign WHERE `uid` = ".$uid." AND `dateline` < ".$todayEnd." AND `dateline` > ".$todayBegin;
                            $checkContinuYesterday2 = $GLOBALS['db']->getAll($checkSignSql);//查询今天有没有签到
                            if($info){
                                $GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET `red_packet_total` = `red_packet_total`+{$checkContinuYesterday2[0]['red_packet']} WHERE `id` =".$uid);
                                $data['status'] = 'success';
                                $data['info'] = "+{$checkContinuYesterday2[0]['red_packet']}红包";
                                $sign_days =$GLOBALS['user_info']['sign_days']+1;
                                $data['msg'] ="已经连续签到{$sign_days}天了，继续努力!+{$checkContinuYesterday2[0]['red_packet']}红包";
                            }
							echo json_encode($data);

                        }else{//如果昨天没签到过则今天签到的天数为1 增加今天一条签到数据
                            $info = $GLOBALS['db']->query("INSERT INTO ".DB_PREFIX."sign (`uid`,`dateline`,`red_packet`,`frequency`) VALUES ( ".$uid.",".$time.",'0.1','1')" );
                            $GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET `sign_days` = 1  WHERE `id` ={$uid}");
                            $checkSignSql3="SELECT * FROM ".DB_PREFIX."sign WHERE `uid` = ".$uid." AND `dateline` < ".$todayEnd." AND `dateline` > ".$todayBegin;
                            $checkContinuYesterday3 = $GLOBALS['db']->getAll($checkSignSql);//查询今天有没有签到
                            if($info){
                                $GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET `red_packet_total` = `red_packet_total`+{$checkContinuYesterday3[0]['red_packet']} WHERE `id` =".$uid);
                                $data['status'] = 'success';
                                $data['info'] = "+{$checkContinuYesterday3[0]['red_packet']}红包";
                                $data['msg'] ="已经连续签到1天了，继续努力!+{$checkContinuYesterday3[0]['red_packet']}红包";
                            }
                            echo json_encode($data);
                        }
                    }

				}else{
                    $data['status'] = 'success';
                    $data['info'] = "";
                    $data['msg'] ="今天已经签到，明日再签";
                    echo json_encode($data);
				}
            }
		}else{
            $data['status'] = 'success';
            $data['info'] = "";
            $data['msg'] ="请先登录,再签到";
            echo json_encode($data);

		}

	}
        //  上个月查询签到
    public function monthn(){
        global_run();

        init_app_page();
        $year= $_POST['showYear'];
        $month = $_POST['showMonth'];
        $time0 = "{$year}-{$month}-01 00:00:00";
        $time1 = date('t', strtotime(".$time0."));
        $time2= "{$year}-{$month}-{$time1} 00:00:00";
        $time3= strtotime("$time0");
        $time4= strtotime("$time2");
//       echo $time3.",".$time4;die;
        $id = $GLOBALS['user_info']['id'];
        $data = $GLOBALS['db']->getAll("select dateline from  ".DB_PREFIX."sign where uid=".$id." and  dateline>=".$time3." and dateline<=".$time4);
        if ($data){
            foreach ($data as $k=>$v){
                $data[$k]['dateline'] = date("d",$v['dateline']);
            }

            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }
    //下个月签到
    public function months(){
        global_run();

        init_app_page();
        $year= $_POST['showYear'];
        $month = $_POST['showMonth'];
        $time0 = "{$year}-{$month}-01 00:00:00";
        $time1 = date('t', strtotime(".$time0."));
        $time2= "{$year}-{$month}-{$time1} 00:00:00";
        $time3= strtotime("$time0");
        $time4= strtotime("$time2");
        $id = $GLOBALS['user_info']['id'];
        $data = $GLOBALS['db']->getAll("select dateline from  ".DB_PREFIX."sign where uid=".$id." and  dateline>=".$time3." and dateline<=".$time4);
        if ($data){
            foreach ($data as $k=>$v){
                $data[$k]['dateline'] = date("d",$v['dateline']);
            }

            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }
    //显示日历
    public function monthd(){
        global_run();

        init_app_page();
        $year= $_POST['showYear'];
        $month = $_POST['showMonth'];
        $time0 = "{$year}-{$month}-01 00:00:00";
        $time1 = date('t', strtotime(".$time0."));
        $time2= "{$year}-{$month}-{$time1} 00:00:00";
        $time3= strtotime("$time0");
        $time4= strtotime("$time2");
        $id = $GLOBALS['user_info']['id'];
        $data = $GLOBALS['db']->getAll("select dateline from  ".DB_PREFIX."sign where uid=".$id." and  dateline>=".$time3." and dateline<=".$time4);
        if ($data){
            foreach ($data as $k=>$v){
                $data[$k]['dateline'] = date("d",$v['dateline']);
            }

            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }
 
}
?>
