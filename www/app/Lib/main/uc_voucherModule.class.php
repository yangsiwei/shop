<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


require APP_ROOT_PATH.'app/Lib/page.php';
require APP_ROOT_PATH."system/model/uc_center_service.php";
class uc_voucherModule extends MainBaseModule
{
	public function index()
	{		
		global_run();
		init_app_page();
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}	
		
		$type=intval($_REQUEST['type']);
		if($type==0)$type=1;
		$page = intval($_REQUEST['p']);
		$page_size =app_conf("PAGE_SIZE");
		if($page<=0)	$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;
		$user_id = intval($GLOBALS['user_info']['id']);
		$result = get_voucher_list($limit,$user_id,$type);
		 
 
		//当前分类的数量
		$count[$type]=$result['count'];
		//获得另一个分类的数量
		if($type=='1'){
			$another='2';
		}elseif($type=='2'){
			$another='1';
		}
		$another_data=get_voucher_list($limit,$user_id,$another);
		$count[$another]=$another_data['count'];
		
		foreach($result['list'] as $k => $v){
		     $json_data=json_decode($v['data'],1);
		     if ($json_data) {
		         if($json_data['domain']['range_value1']==1){
		             $result['list'][$k]['range_value1']="pk区"."&nbsp;";
		         }
		         if($json_data['domain']['range_value2']==2){
		             $result['list'][$k]['range_value2']="十夺宝币区"."&nbsp;";
		         }
		         if($json_data['domain']['range_value3']==3){
		             $result['list'][$k]['range_value3']="百夺宝币区"."&nbsp;";
		         }
		         if($json_data['domain']['range_value4']==4){
		             $result['list'][$k]['range_value4']="直购区"."&nbsp;";
		         }
		         if($json_data['domain']['range_value5']==5){
		             $result['list'][$k]['range_value5']="极速区"."&nbsp;";
		         }
		         if($json_data['domain']['range_value6']==6){
		             $result['list'][$k]['range_value6']="选号区"."&nbsp;";
		         }
		         if($json_data['domain']['range_value7']==7){
		             $result['list'][$k]['range_value7']="一夺宝币区"."&nbsp;";
		         }
		     }
		}
		$GLOBALS['tmpl']->assign("count",$count);
		$GLOBALS['tmpl']->assign("new_time",NOW_TIME);
		$GLOBALS['tmpl']->assign("type",$type);
		$GLOBALS['tmpl']->assign("list",$result['list']);

		$page = new Page($result['count'],$page_size);   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);		

			
		
		$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['UC_VOUCHER']);
		assign_uc_nav_list();//左侧导航菜单	
		$GLOBALS['tmpl']->display("uc/uc_voucher_index.html");
		
	}

	public function exchange()
	{
		global_run();
		init_app_page();
		
		//assign_form_verify();
		$GLOBALS['tmpl']->assign("no_nav",true); //无分类下拉
		
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			app_redirect(url("index","user#login"));
		}			 
		$page = intval($_REQUEST['p']);
		if($page==0)	$page = 1;
		$limit = (($page-1)*app_conf("PAGE_SIZE")).",".app_conf("PAGE_SIZE");
		$result = get_exchange_voucher_list($limit);
		$exchange_count = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."ecv where user_id = ".intval($GLOBALS['user_info']['id']));
		$i=array();
		foreach($result['list'] as $k => $v){
		    foreach($exchange_count as $kk => $vv){
		        if($vv['ecv_type_id']==$v['id']){
		            $i[$k]=$i[$k]+1;
		            if($i[$k]>=$v['exchange_limit']&&$v['exchange_limit']!=0){
		                $result['list'][$k]['receive']=1;
		            }
		        }
		    }
		}
		
		$GLOBALS['tmpl']->assign("list",$result['list']);
		$page = new Page($result['count'],app_conf("PAGE_SIZE"));   //初始化分页对象 		
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
	
		
		$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['UC_VOUCHER']);
		assign_uc_nav_list();//左侧导航菜单	
		$GLOBALS['tmpl']->display("uc/uc_voucher_exchange.html");
	}	
	
	public function do_exchange()
	{
		global_run();
		//check_form_verify();
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			$result['status'] = 2;
			ajax_return($result);	
		}
		$id = intval($_REQUEST['id']);
		$ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where id = ".$id);
		if(!$ecv_type)
		{
			showErr($GLOBALS['lang']['INVALID_VOUCHER'],1);
		}
		else
		{
			if($ecv_type['end_time']<NOW_TIME&&$ecv_type['end_time']!=0){
				$msg = "红包已过期";
				showErr($msg,1);
			}
			$exchange_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."ecv where ecv_type_id = ".$id." and user_id = ".intval($GLOBALS['user_info']['id']));
			if($ecv_type['exchange_limit']>0&&$exchange_count>=$ecv_type['exchange_limit'])
			{
				$msg = sprintf($GLOBALS['lang']['EXCHANGE_VOUCHER_LIMIT'],$ecv_type['exchange_limit']);
				showErr($msg,1);
			}
			elseif($ecv_type['exchange_score']>intval($GLOBALS['db']->getOne("select score from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']))))
			{
				showErr($GLOBALS['lang']['INSUFFCIENT_SCORE'],1);
			}
			else
			{
			    //先更新用户的积分
			    $GLOBALS['db']->query("update ".DB_PREFIX."user set score = score - ".intval($ecv_type['exchange_score'])." where id =".$GLOBALS['user_info']['id']." and score - ".intval($ecv_type['exchange_score']).">=0");
                if($GLOBALS['db']->affected_rows()){
    				require_once APP_ROOT_PATH."system/libs/voucher.php";
    				$rs = send_voucher($ecv_type['id'],$GLOBALS['user_info']['id'],1);
    				if($rs>0)
    				{
    					require_once APP_ROOT_PATH."system/model/user.php";
    					$msg = sprintf($GLOBALS['lang']['EXCHANGE_VOUCHER_USE_SCORE'],$ecv_type['name'],$ecv_type['exchange_score']);
    					
    					//更新用户日志
    					//$GLOBALS['db']->query("update ".DB_PREFIX."user set total_score = total_score - ".intval($ecv_type['exchange_score'])." where id =".$GLOBALS['user_info']['id']);
    					$user_info = $GLOBALS['user_info'];
    					if($user_info['is_robot']==0)// by hc4.18 机器人不产生日志
    					{
    					    $log_info['log_info'] = $msg;
    					    $log_info['log_time'] = NOW_TIME;
    					
    					    $log_info['log_user_id'] = intval($user_info['id']);
    					    $log_info['score'] = "-".intval($ecv_type['exchange_score']);
    					    $log_info['money'] = 0;
    					    $log_info['point'] = 0;
    					    $log_info['user_id'] = intval($user_info['id']);
    					    $GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);
    					
    					    load_user(intval($user_info['id']),true);
    					}
    					
    					showSuccess($GLOBALS['lang']['EXCHANGE_SUCCESS'],1,url('index','uc_voucher'));
    				}
    				$GLOBALS['db']->query("update ".DB_PREFIX."user set score = score + ".intval($ecv_type['exchange_score'])." where id =".$GLOBALS['user_info']['id']);
    				if($rs==-1)
    				{
    					showErr("您来晚了，已兑换光了",1);
    				}
    				else
    				{
    					showErr($GLOBALS['lang']['EXCHANGE_FAILED'],1);
    				}
    				
    			}else{    			    
    			    showErr($GLOBALS['lang']['EXCHANGE_FAILED'],1);
    			}
			}
		}
	}
	
	
	public function do_snexchange()
	{
		global_run();
		//check_form_verify();
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
			$result['status'] = 2;
			ajax_return($result);
		}
		$sn = strim($_REQUEST['sn']);
		$ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where exchange_sn = '".$sn."'");
		if(!$ecv_type)
		{
			$ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv where sn = '".$sn."'");
			if($ecv['end_time']<NOW_TIME&&$ecv['end_time']!=0){
				$msg = "红包已过期";
				showErr($msg,1);
			}
			$GLOBALS['db']->query("update ".DB_PREFIX."ecv set user_id = '".$GLOBALS['user_info']['id']."' where sn = '".$sn."' and user_id = 0");
			if($GLOBALS['db']->affected_rows())
			{
				showSuccess($GLOBALS['lang']['EXCHANGE_SUCCESS'],1,url('index','uc_voucher'));
			}
			else
			showErr($GLOBALS['lang']['INVALID_VOUCHER'],1);
		}
		else
		{
			if($ecv_type['end_time']<NOW_TIME&&$ecv_type['end_time']!=0){
				$msg = "红包已过期";
				showErr($msg,1);
			}
			$exchange_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."ecv where ecv_type_id = ".$ecv_type['id']." and user_id = ".intval($GLOBALS['user_info']['id']));
			if($ecv_type['exchange_limit']>0&&$exchange_count>=$ecv_type['exchange_limit'])
			{
				$msg = sprintf($GLOBALS['lang']['EXCHANGE_VOUCHER_LIMIT'],$ecv_type['exchange_limit']);
				showErr($msg,1);
			}
			else
			{
				require_once APP_ROOT_PATH."system/libs/voucher.php";
				$rs = send_voucher($ecv_type['id'],$GLOBALS['user_info']['id'],1);
				if($rs>0)
				{
					showSuccess($GLOBALS['lang']['EXCHANGE_SUCCESS'],1,url('index','uc_voucher'));
				}
				else if($rs==-1)
				{
					showErr("您来晚了，已兑换光了",1);
				}
				else
				{
					showErr($GLOBALS['lang']['EXCHANGE_FAILED'],1);
				}
			}
		}
	}
	
}
?>