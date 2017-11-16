<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class duobaoModule extends MainBaseModule
{
    public function index()
    {
        global_run();
        init_app_page();
        $id = intval($_REQUEST['id']);
        $dbid = intval($_REQUEST['dbid']);
        if($dbid)
        {
        	$item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where duobao_id=".$dbid." and progress < 100 order by create_time desc");
        }
        else
        $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$id);

        $user_info = $GLOBALS['user_info'];
        require_once APP_ROOT_PATH.'system/model/duobao.php';

        if(empty($item_data))
        {
        	app_redirect(url("index"));
        }
    
        // 和夺宝计划共用库存
        $item_data['total_buy_stock'] = $GLOBALS['db']->getOne("select total_buy_stock from ".DB_PREFIX."deal where id=".$item_data['deal_id']);
       
        
        set_view_history("duobao", $item_data['id']);

        $item_data['deal_gallery'] = unserialize($item_data['deal_gallery']);

        $item_pic=array();
        foreach($item_data['deal_gallery'] as $k=>$v)
        {
        	if($k<4){

        		$item_pic[$k] = get_spec_image($v,400,400,1);
        	}

        }
        $item_data['deal_gallery'] =$item_pic;
        //商品状态
        $duobao_status = 0;  //0 进行中  1 倒计时  2已揭晓
        if($item_data['has_lottery']==0){//未开奖
        	if($item_data['success_time']==0){//未成功
        		$duobao_status = 0;
        	}else{
        		$duobao_status=1;
        	}
        }else{
        	$duobao_status = 2;
        }
        $item_data['fair_sn']               = sprintf("%05d", $item_data['fair_sn']);
        $item_data['fair_sn_s']             = str_split( strim($item_data['fair_sn']) ,1);

        $item_data['duobao_status']         = $duobao_status;
        $item_data['surplus_count']         = $item_data['max_buy'] - $item_data['current_buy'];
        $item_data['lottery_time']          = $item_data['lottery_time']+10;
        $item_data['lottery_time_format']   = to_date($item_data['lottery_time']);
        $item_data['create_time_format']    = to_date($item_data['create_time']);
        $item_data['total_buy_price']       = format_sprintf_price($item_data['total_buy_price']);
        
        
        $item_data['click_count']++;
    	$sql="update ".DB_PREFIX."duobao_item set click_count =".$item_data['click_count']." where id=".$id;
    	$GLOBALS['db']->query($sql);
    	
        if($GLOBALS['user_info']){ //登录的情况下，查看我的号码

        	$duobao_item_log_user_cache = load_dynamic_cache("duobao_item_log_".$item_data['id']."_".$GLOBALS['user_info']['id']);
        	if($duobao_item_log_user_cache===false)
        	{
        		$list=duobao::get_user_no_all(array("user_id"=>$GLOBALS['user_info']['id'],"duobao_item"=>$item_data));
        		$total_sql = "select sum(doi.number) from ".DB_PREFIX."deal_order_item as doi left join ".DB_PREFIX."deal_order as do on doi.order_id = do.id where do.user_id = ".$GLOBALS['user_info']['id']." and doi.duobao_item_id = ".$item_data['id']." and doi.refund_status = 0 and do.pay_status = 2 and do.type = 2 ";
				$duobao_recode_count=$GLOBALS['db']->getOne($total_sql);
				$duobao_item_log_user_arr=array();
				$duobao_item_log_user_arr['duobao_recode_count'] = $root['duobao_recode_count'] = $duobao_recode_count;
				$duobao_item_log_user_arr['duobao_recode_list']= $root['duobao_recode_list']=$list;

				set_dynamic_cache("duobao_item_log_".$item_data['id']."_".$GLOBALS['user_info']['id'], $duobao_item_log_user_arr);
        	}else{

        		$root['duobao_recode_count'] = $duobao_item_log_user_cache['duobao_recode_count'];
        		$root['duobao_recode_list']=$duobao_item_log_user_cache['duobao_recode_list'];
        	}

			 $root['is_login']=1;

        }
        
         if($duobao_status==2)
        {
        	$duobao_item_log = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($item_data)." where lottery_sn = '".$item_data['lottery_sn']."' and duobao_item_id = ".$item_data['id']);
        	$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$duobao_item_log['user_id']);

        	//查看夺宝记录

        	if(!$GLOBALS['user_info']){ //没登录的情况下，查看他的号码，即中奖人的号码
        			$root['is_login']=0;
        	}else{

        		if($GLOBALS['user_info']['id']!=$user_info['id']){//用户非中奖者，查看中奖者号码
					$root['is_login']=0;
        		}
        	}

        	$duobao_item_log_cache = load_dynamic_cache("duobao_item_log_".$item_data['id']);
        	if($duobao_item_log_cache===false)
        	{
        		$ta_list=duobao::get_user_no_all(array("user_id"=>$user_info['id'],"duobao_item"=>$item_data));
                $duobao_recode_count=$item_data['luck_user_buy_count'];
        		$duobao_item_log_arr=array();
        		$duobao_item_log_arr['duobao_list']=$ta_list;
        		$duobao_item_log_arr['duobao_recode_count']=$duobao_recode_count;
        		set_dynamic_cache("duobao_item_log_".$item_data['id'], $duobao_item_log_arr);
        		$root['ta_duobao_recode_count'] = $duobao_recode_count;
        		$root['ta_duobao_recode_list']=$ta_list;

        	}else{
        		$root['ta_duobao_recode_count'] = $duobao_item_log_cache['duobao_recode_count'];
        		$root['ta_duobao_recode_list']=$duobao_item_log_cache['duobao_list'];

        	}


        	$duobao_item_log['fixed_value'] = $duobao_item_log['lottery_sn']-100000001;

            $duobao_item_log['lottery_sns']=str_split( strim($duobao_item_log['lottery_sn']) ,1);
            $duobao_item_log['fixed_values']=str_split( strim($duobao_item_log['fixed_value']) ,1);

        	$duobao_item_log['create_time'] = to_date($duobao_item_log['create_time']);
        	$duobao_item_log['user_name'] = $user_info['user_name'];
        	$duobao_item_log['user_logo'] = $user_info['user_logo'];

        	$duobao_item_log['user_duobao_count']=$root['ta_duobao_recode_count'];
            foreach ($GLOBALS['db']->getALl("select id,name from ".DB_PREFIX."delivery_region where id in(".$user_info['province_id'].",".$user_info['city_id'].")") as $key => $value) {
                $pc[$value['id']]=$value;
            }
            $duobao_item_log['province_name'] =$pc[$user_info['province_id']]['name'];
            $duobao_item_log['city_name'] =$pc[$user_info['city_id']]['name'];
            $item_data['luck_lottery'] = $duobao_item_log;


        }


        $item_data['now_time'] = NOW_TIME;
        $item_data['f_success_time_50'] = to_date($item_data['success_time_50'],"Y-m-d H:i:s").substr($item_data['success_time_50'], stripos($item_data['success_time_50'], "."),4);

		$root['item_data'] = $item_data;

		if($item_data['progress']==100 && $item_data['success_time'] > 0){
			//最新一期
			$new_item_data= $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where duobao_id=".$item_data['duobao_id']." and progress <100 order by id desc limit 0,1");
			if($new_item_data){
				$new_item_data['surplus_count'] = $new_item_data['max_buy'] - $new_item_data['current_buy'];
				$root['new_item_data']=$new_item_data;
			}

		}

		//夺宝50条参与记录 //2016-05-21 14:23:52
		$duobao_item_log_55_cache = load_dynamic_cache("duobao_item_log_55_".$item_data['success_time']);
        if($duobao_item_log_55_cache===false)
		{
			$sql = "select * from ".DB_PREFIX."deal_order_item where lottery_sn=0 and create_time <=".$item_data['success_time_50']." order by create_time desc limit 55";
			$duobao_order_log = $GLOBALS['db']->getAll($sql);
			set_dynamic_cache("duobao_item_log_55_".$item_data['success_time'], $duobao_order_log);
		}else{
			$duobao_order_log=$duobao_item_log_55_cache;
		}


        foreach($duobao_order_log as $k=>$v)
        {

            $create_time = $v['create_time'];
            $data_arr = explode(".", $create_time);
            $date_str = to_date(intval($data_arr[0]),"H:i:s");
            $full_date_str = to_date(intval($data_arr[0]));
            $mmtime = trim($data_arr[1]);

            $res = intval(str_replace(":", "", $date_str).$mmtime);
            $fair_sn_local=$res;

            $duobao_order_log[$k]['create_time_format'] = $full_date_str.".".$mmtime;
            $duobao_order_log[$k]['fair_sn_local'] = $fair_sn_local;
        }

         //五倍开奖
        $duobao1 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where id = ".$id. " and has_lottery=1 and is_five=1 ");
        $lott = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log where duobao_item_id = ".$duobao1[0]['id']." and is_luck=1");

        foreach ($lott as $k=>$v){
            $username = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id=".$lott[$k]['user_id']);

            $lott[$k]['user_id']=$username;
        }
        //是否是五倍开奖
        if($lott[4]){
            $GLOBALS['tmpl']->assign("lott",$lott);
            $GLOBALS['tmpl']->assign("duobao1",$duobao1[0]);
//            print_r($lott);die;

        }

        $duobao_order_log=array_chunk($duobao_order_log, 50);
        $root['duobao_order_log']=$duobao_order_log['0'];

        $root['duobao_order_logs']=$duobao_order_log['1'];

        $root['page_title'].="奖品详情";
		//分享url
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$root['share_url'] = $url.'&r='.base64_encode($GLOBALS['user_info']['id']);
        $GLOBALS['tmpl']->assign('user',$GLOBALS['user_info']);
        $GLOBALS['tmpl']->assign("share_title",app_conf('SHOP_TITLE')."-".$item_data['name']);
        $share_url = get_domain().get_current_url();
        $GLOBALS['tmpl']->assign("share_url",$share_url);
        $GLOBALS['tmpl']->assign('page_title',$item_data['name']);
        $GLOBALS['tmpl']->assign('root',$root);
        $GLOBALS['tmpl']->assign('default_lottery',DEFAULT_LOTTERY);
        $GLOBALS['tmpl']->display("duobao.html");


    }
    public function used_item_data_page()
    {//往期夺宝翻页
        global_run();
        init_app_page();

        $data_id=intval($_REQUEST['data_id']);
        $page=intval($_REQUEST['p']);
        $_GET['p']=intval($_REQUEST['p']);
        $page_size =app_conf("PAGE_SIZE");

        require APP_ROOT_PATH.'app/Lib/page.php';
        if($page<=0)$page = 1;
        $limit = (($page - 1) * $page_size) . ",".$page_size;
        $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$data_id);

        $used_item_count=$GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."duobao_item where duobao_id=".$item_data['duobao_id']." and success_time !=0 order by id desc");
        $used_item_data =$GLOBALS['db']->getAll("select  a.id,a.lottery_sn,a.has_lottery,a.lottery_time,a.luck_user_id,a.luck_user_name,a.luck_user_buy_count as user_duobao_count,b.duobao_area,b.create_time from ".DB_PREFIX."duobao_item as a left join ".DB_PREFIX."duobao_item_log_history as b on a.id=b.duobao_item_id and a.lottery_sn=b.lottery_sn where a.duobao_id=".$item_data['duobao_id']." and a.success_time !=0 order by a.id desc limit ".$limit);
        if($used_item_data){
            foreach ($used_item_data as $key => $value) {
                $used_item_data[$key]['lottery_time'] = $value['lottery_time']+10;
                $used_item_data[$key]['lottery_time_data'] = to_date($value['lottery_time']);
                $used_item_data[$key]['create_time']=to_date($used_item_data[$key]['create_time']);
            }
            $max_page=floor(($used_item_count+$page_size-1)/$page_size);
            $GLOBALS['tmpl']->assign("max_page",$max_page);
            $GLOBALS['tmpl']->assign("p",$_GET['p']);
            $GLOBALS['tmpl']->assign("now_time",NOW_TIME);
            $GLOBALS['tmpl']->assign("data_id",$data_id);


            $page = new Page($used_item_count,$page_size);   //初始化分页对象
            $p  =  $page->show();
            $GLOBALS['tmpl']->assign('pages',$p);

        }
        $GLOBALS['tmpl']->assign("used_item_data_page",$used_item_data);

        $html=$GLOBALS['tmpl']->fetch("inc/duobao_used_item_data_page.html");
        $result['status']=1;
        $result['html']=$html;
        ajax_return($result);
    }
    public function used_item_data_countdown()
    {//往期夺宝倒计时
        $data_id=intval($_REQUEST['data_id']);
        $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$data_id);

        $used_item_data =$GLOBALS['db']->getRow("select a.id,a.lottery_sn,a.has_lottery,a.lottery_time,a.luck_user_id,a.luck_user_name,a.luck_user_buy_count as user_duobao_count,b.create_time,b.duobao_area from ".DB_PREFIX."duobao_item as a left join ".duobao_item_log_table($item_data)." as b on a.id=b.duobao_item_id and a.lottery_sn=b.lottery_sn where a.id=".$data_id." and a.success_time !=0 ");
        $used_item_data['lottery_time'] = $used_item_data['lottery_time']+10;
        $used_item_data['lottery_time_data'] = to_date($used_item_data['lottery_time']);
        $used_item_data['create_time']=to_date($used_item_data['create_time']);

        $GLOBALS['tmpl']->assign("now_time",NOW_TIME);
        $GLOBALS['tmpl']->assign("data_id",$data_id);
        $GLOBALS['tmpl']->assign("used_item_data",$used_item_data);

        $html=$GLOBALS['tmpl']->fetch("inc/duobao_used_item_data_countdown.html");
        $result['status']=1;
        $result['html']=$html;
        ajax_return($result);
    }
    public function duobao_share_page()
    {//晒单分享翻页
        global_run();
        init_app_page();
        $data_id=intval($_REQUEST['data_id']);
        $page=intval($_REQUEST['p']);
        $_GET['p']=intval($_REQUEST['p']);
        $page_size =app_conf("PAGE_SIZE");
        require APP_ROOT_PATH.'app/Lib/page.php';
        if($page<=0)$page = 1;
        $limit = (($page - 1) * $page_size) . ",".$page_size;
        $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$data_id);
        $duobao_share_count=$GLOBALS['db']->getOne("select count(a.id) from ".DB_PREFIX."duobao_item as a join ".DB_PREFIX."share as b on a.id=b.duobao_item_id  where a.duobao_id=".$item_data['duobao_id']." and b.is_effect=1");
        $duobao_share_data =$GLOBALS['db']->getAll("select b.id,b.user_id,b.user_name,b.title,b.content,b.create_time,b.image_list from ".DB_PREFIX."duobao_item as a join ".DB_PREFIX."share as b on a.id=b.duobao_item_id where a.duobao_id=".$item_data['duobao_id']." and b.is_effect=1 order by a.id desc limit ".$limit);
        foreach ($duobao_share_data as $key => $value) {
            $duobao_share_data[$key]['image_list'] = unserialize($value['image_list']);
            $duobao_share_data[$key]['create_time'] = to_date($value['create_time']);
        }
        $max_page=floor(($duobao_share_count+$page_size-1)/$page_size);
        $GLOBALS['tmpl']->assign("max_page",$max_page);
        $GLOBALS['tmpl']->assign("p",$_GET['p']);
        $GLOBALS['tmpl']->assign("data_id",$data_id);

        $page = new Page($duobao_share_count,$page_size);   //初始化分页对象
        $p  =  $page->show();
        $GLOBALS['tmpl']->assign('pages',$p);

        $GLOBALS['tmpl']->assign("duobao_share_data",$duobao_share_data);
        $html=$GLOBALS['tmpl']->fetch("inc/duobao_share_page.html");
        $result['status']=1;
        $result['html']=$html;
        ajax_return($result);
    }
    public function duobao_record_page()
    {//夺宝记录翻页
        global_run();
        init_app_page();
        $data_id=intval($_REQUEST['data_id']);
        $page=intval($_REQUEST['p']);
        $_GET['p']=intval($_REQUEST['p']);
        $page_size =50;//app_conf("PAGE_SIZE");

        require APP_ROOT_PATH.'app/Lib/page.php';
        if($page<=0)$page = 1;
        $limit = (($page - 1) * $page_size) . ",".$page_size;

        $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$data_id);
        //夺宝参与记录
        //查询全部夺宝号码
        $record_duobao_item_log=$GLOBALS['db']->getAll("select order_item_id,lottery_sn from ".duobao_item_log_table($item_data)." where order_item_id !=0 and order_id !=0 and duobao_item_id=".$data_id." order by order_item_id desc");
        //夺宝号码根据order_item_id为键名，构成数组
        $record_duobao_item_log_order_item_id=array();
        foreach($record_duobao_item_log as $k=>$v){
            $record_duobao_item_log_order_item_id[$v['order_item_id']][]    =   $v;
        }
        $duobao_record_count=$GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."deal_order_item where lottery_sn=0 and duobao_item_id=".$item_data['id']." and refund_status=0 and lottery_sn_send=1 order by id desc");
        $duobao_record=$GLOBALS['db']->getAll("select id,number,user_id,create_time,duobao_ip,duobao_area,user_name from ".DB_PREFIX."deal_order_item where lottery_sn=0 and duobao_item_id=".$item_data['id']."  and refund_status=0 and lottery_sn_send=1  order by id desc limit ".$limit);
        foreach ($duobao_record as $key => $value) {
            $duobao_record[$key]['create_time_date']=to_date($value['create_time'],'H:i:s').".".substr($value['create_time'],-3);
            $duobao_record[$key]['duobao_item_log']=$record_duobao_item_log_order_item_id[$value['id']];
        }
        foreach($duobao_record as $k=>$v){
            $v['duobao_ip'] = substr($v['duobao_ip'], 0,(stripos($v['duobao_ip'],".")+1))."****".substr($v['duobao_ip'], strripos($v['duobao_ip'],"."));
            $duobao_record_create_time[to_date($v['create_time'],'Y-m-d')][]    =   $v;
        }
        $max_page=floor(($duobao_record_count+$page_size-1)/$page_size);

        $GLOBALS['tmpl']->assign("now_time",NOW_TIME);
        $GLOBALS['tmpl']->assign("data_id",$data_id);
        $GLOBALS['tmpl']->assign("max_page",$max_page);
        $GLOBALS['tmpl']->assign("new_page",intval($_REQUEST['p']));
        $page = new Page($duobao_record_count,$page_size);   //初始化分页对象
        $p  =  $page->show();
        $GLOBALS['tmpl']->assign('pages',$p);

        $GLOBALS['tmpl']->assign("duobao_records",$duobao_record_create_time);

        $html=$GLOBALS['tmpl']->fetch("inc/duobao_duobao_record_page.html");
        $result['status']=1;
        $result['html']=$html;
        ajax_return($result);
    }
    public function init_count_dow()
    {
        //倒计时刷新
        global_run();
        init_app_page();
        $id = intval($_REQUEST['id']);
        
        $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$id);
        
        $user_info = $GLOBALS['user_info'];
        require_once APP_ROOT_PATH.'system/model/duobao.php';
        
        if(empty($item_data))
        {
            showErr('夺宝活动不存在', 1, url("index"));
        }
         
        set_view_history("duobao", $item_data['id']);
        
        
         
        //商品状态
        $duobao_status = 0;  //0 进行中  1 倒计时  2已揭晓
        if($item_data['has_lottery']==0){//未开奖
            if($item_data['success_time']==0){//未成功
                $duobao_status = 0;
            }else{
                $duobao_status=1;
            }
        }else{
            $duobao_status = 2;
        }
        $item_data['fair_sn']               = sprintf("%05d", $item_data['fair_sn']);
        $item_data['fair_sn_s']             = str_split( strim($item_data['fair_sn']) ,1);
        
        $item_data['duobao_status']         = $duobao_status;
        $item_data['surplus_count']         = $item_data['max_buy'] - $item_data['current_buy'];
        $item_data['lottery_time']          = $item_data['lottery_time']+10;
        $item_data['lottery_time_format']   = to_date($item_data['lottery_time']);
        $item_data['create_time_format']    = to_date($item_data['create_time']);
        $item_data['total_buy_price']       = format_sprintf_price($item_data['total_buy_price']);
        
        
        $item_data['click_count']++;
        $sql="update ".DB_PREFIX."duobao_item set click_count =".$item_data['click_count']." where id=".$id;
        $GLOBALS['db']->query($sql);
         
        if($GLOBALS['user_info']){ //登录的情况下，查看我的号码
        
            $duobao_item_log_user_cache = load_dynamic_cache("duobao_item_log_".$item_data['id']."_".$GLOBALS['user_info']['id']);
            if($duobao_item_log_user_cache===false)
            {
                $list=duobao::get_user_no_all(array("user_id"=>$GLOBALS['user_info']['id'],"duobao_item"=>$item_data));
                $total_sql = "select sum(doi.number) from ".DB_PREFIX."deal_order_item as doi left join ".DB_PREFIX."deal_order as do on doi.order_id = do.id where do.user_id = ".$GLOBALS['user_info']['id']." and doi.duobao_item_id = ".$item_data['id']." and doi.refund_status = 0 and do.pay_status = 2 and do.type = 2 ";
                $duobao_recode_count=$GLOBALS['db']->getOne($total_sql);
                $duobao_item_log_user_arr=array();
                $duobao_item_log_user_arr['duobao_recode_count'] = $root['duobao_recode_count'] = $duobao_recode_count;
                $duobao_item_log_user_arr['duobao_recode_list']= $root['duobao_recode_list']=$list;
        
                set_dynamic_cache("duobao_item_log_".$item_data['id']."_".$GLOBALS['user_info']['id'], $duobao_item_log_user_arr);
            }else{
        
                $root['duobao_recode_count'] = $duobao_item_log_user_cache['duobao_recode_count'];
                $root['duobao_recode_list']=$duobao_item_log_user_cache['duobao_recode_list'];
            }
        
            $root['is_login']=1;
        
        }
        
        if($duobao_status==2)
        {
            $duobao_item_log = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($item_data)." where lottery_sn = '".$item_data['lottery_sn']."' and duobao_item_id = ".$item_data['id']);
        
            $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$duobao_item_log['user_id']);
        
            //查看夺宝记录
        
            if(!$GLOBALS['user_info']){ //没登录的情况下，查看他的号码，即中奖人的号码
                $root['is_login']=0;
            }else{
        
                if($GLOBALS['user_info']['id']!=$user_info['id']){//用户非中奖者，查看中奖者号码
                    $root['is_login']=0;
                }
            }
        
            $duobao_item_log_cache = load_dynamic_cache("duobao_item_log_".$item_data['id']);
            if($duobao_item_log_cache===false)
            {
                $ta_list=duobao::get_user_no_all(array("user_id"=>$user_info['id'],"duobao_item"=>$item_data));
                $duobao_recode_count=$item_data['luck_user_buy_count'];
                $duobao_item_log_arr=array();
                $duobao_item_log_arr['duobao_list']=$ta_list;
                $duobao_item_log_arr['duobao_recode_count']=$duobao_recode_count;
                set_dynamic_cache("duobao_item_log_".$item_data['id'], $duobao_item_log_arr);
                $root['ta_duobao_recode_count'] = $duobao_recode_count;
                $root['ta_duobao_recode_list']=$ta_list;
        
            }else{
                $root['ta_duobao_recode_count'] = $duobao_item_log_cache['duobao_recode_count'];
                $root['ta_duobao_recode_list']=$duobao_item_log_cache['duobao_list'];
        
            }
        
        
            $duobao_item_log['fixed_value'] = $duobao_item_log['lottery_sn']-100000001;
        
            $duobao_item_log['lottery_sns']=str_split( strim($duobao_item_log['lottery_sn']) ,1);
            $duobao_item_log['fixed_values']=str_split( strim($duobao_item_log['fixed_value']) ,1);
        
            $duobao_item_log['create_time'] = to_date($duobao_item_log['create_time']);
            $duobao_item_log['user_name'] = $user_info['user_name'];
            $duobao_item_log['user_logo'] = $user_info['user_logo'];
        
            $duobao_item_log['user_duobao_count']=$root['ta_duobao_recode_count'];
            foreach ($GLOBALS['db']->getALl("select id,name from ".DB_PREFIX."delivery_region where id in(".$user_info['province_id'].",".$user_info['city_id'].")") as $key => $value) {
                $pc[$value['id']]=$value;
            }
            $duobao_item_log['province_name'] =$pc[$user_info['province_id']]['name'];
            $duobao_item_log['city_name'] =$pc[$user_info['city_id']]['name'];
            $item_data['luck_lottery'] = $duobao_item_log;
        
        
        }
        
        
        $item_data['now_time'] = NOW_TIME;
        $item_data['f_success_time_50'] = to_date($item_data['success_time_50'],"Y-m-d H:i:s").substr($item_data['success_time_50'], stripos($item_data['success_time_50'], "."),4);
        
        $root['item_data'] = $item_data;
        
        if($item_data['progress']==100 && $item_data['success_time'] > 0){
            //最新一期
            $new_item_data= $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where duobao_id=".$item_data['duobao_id']." and progress <100 order by id desc limit 0,1");
            if($new_item_data){
                $new_item_data['surplus_count'] = $new_item_data['max_buy'] - $new_item_data['current_buy'];
                $root['new_item_data']=$new_item_data;
            }
        
        }
        
        //夺宝50条参与记录 //2016-05-21 14:23:52
        $duobao_item_log_55_cache = load_dynamic_cache("duobao_item_log_55_".$item_data['success_time']);
        if($duobao_item_log_55_cache===false)
        {
            $sql = "select * from ".DB_PREFIX."deal_order_item where lottery_sn=0 and create_time <=".$item_data['success_time_50']." order by create_time desc limit 55";
            $duobao_order_log = $GLOBALS['db']->getAll($sql);
            set_dynamic_cache("duobao_item_log_55_".$item_data['success_time'], $duobao_order_log);
        }else{
            $duobao_order_log=$duobao_item_log_55_cache;
        }
        
        
        foreach($duobao_order_log as $k=>$v)
        {
        
            $create_time = $v['create_time'];
            $data_arr = explode(".", $create_time);
            $date_str = to_date(intval($data_arr[0]),"H:i:s");
            $full_date_str = to_date(intval($data_arr[0]));
            $mmtime = trim($data_arr[1]);
        
            $res = intval(str_replace(":", "", $date_str).$mmtime);
            $fair_sn_local=$res;
        
            $duobao_order_log[$k]['create_time_format'] = $full_date_str.".".$mmtime;
            $duobao_order_log[$k]['fair_sn_local'] = $fair_sn_local;
        }
        
        
        $GLOBALS['tmpl']->assign('root',$root);
        $html=$GLOBALS['tmpl']->fetch("inc/duobao_countdown.html");
        $result['status']=2;
        $result['html']=$html;


        $countdown_tip=$GLOBALS['tmpl']->fetch("inc/duobao_countdown_tip.html");
        $result['countdown_tip']=$countdown_tip;

        $layer=$GLOBALS['tmpl']->fetch("inc/layer.html");
        $result['layer']=$layer;

        ajax_return($result);
    }

}
