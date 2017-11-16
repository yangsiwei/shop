<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

/**
 * 我的红包接口
 * @author jobin.lin
 *
 */
class uc_ecvApiModule extends MainBaseApiModule
{
    
    /**
     * 我的红包
     * 输入：
     * n_valid: int 是否失效， 0未失效，1已失效
     * 输出：
     * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
     * [data] => Array
        (
            [0] => Array
                (
                    [id] => 7   ：int 红包ID
                    [use_status] => 0   ：int 红包是否已经领取的状态
                    [datetime] =>  不限时      ：string 红包到期时间显示内容
                    [name] => 简单换       ：string 红包名称 
                    [money] => 15   ：红包金额
                )
         )
        [user_avatar] => http://localhost/o2onew/public/avatar/000/00/00/71virtual_avatar_big.jpg  ：string 用户头像
        [ecv_count] => 22   ：int 红包个数
        [ecv_total] => 1482 ：int 红包总金额

        [page] => Array
        (
            [page] => 1
            [page_total] => 3
            [page_size] => 10
            [data_total] => 22
        )

     *
     * */
    public function index(){
        $root = array();
        /*参数列表*/
        $n_valid = intval($GLOBALS['request']['n_valid']);
        
        /*业务逻辑*/
        //检查用户,用户密码
        $user = $GLOBALS['user_info'];
        $user_id  = intval($user['id']);

        $user_login_status = check_login();
        
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = $user_login_status;
            //分页
            $page = intval($GLOBALS['request']['page']);
            $page=$page==0?1:$page;
            
            $page_size = PAGE_SIZE;
            $limit = (($page-1)*$page_size).",".$page_size;
            

            $condtion = '';
            if($n_valid){
                $condtion = ' and ((e.use_limit=e.use_count and e.use_limit>0) or (e.end_time<'.NOW_TIME.' and e.end_time>0))';
               //(e.use_limit>0 and (e.use_limit = e.use_count || (e.end_time<'.NOW_TIME.' and e.end_time>0))) ';
            }else{
                $condtion = ' and (e.use_limit=0 or e.use_limit>e.use_count) and (e.end_time>'.NOW_TIME.' or e.end_time=0)';
                //and (e.use_limit=0 or e.use_limit > e.use_count) ';
            }
            
            $sql = "select e.*,
                    et.name,
                    et.money as type_money,
                    et.use_limit as type_use_limit,et.begin_time as type_begin_time,et.end_time as type_end_time
                    ,et.gen_count,et.send_type,et.exchange_score,et.exchange_limit,et.exchange_sn,et.share_url,et.memo,et.tpl,et.total_limit
                    from ".DB_PREFIX."ecv as e left join ".DB_PREFIX."ecv_type as et on e.ecv_type_id = et.id where e.user_id = ".$user_id.$condtion." order by e.id desc limit ".$limit;

            $sql_count = "select count(*) from ".DB_PREFIX."ecv e where user_id = ".$user_id.$condtion;
            $list = $GLOBALS['db']->getAll($sql);
            $count = $GLOBALS['db']->getOne($sql_count);
            $money = 0;
            foreach ($list as $k=>$v){
                $temp_arr = array();
                $temp_arr['id'] = $v['id'];
                if($v['use_limit'] == $v['use_count'] && $v['use_limit']>0)
                    $temp_arr['use_status'] = 1;    //0为使用或者还可以,1不可使用，或者已经使用光
                else{ 
                	if($v['end_time']<NOW_TIME && $v['end_time'] <> 0)
                		$temp_arr['use_status'] = 1;
                	else	
                    	$temp_arr['use_status'] = 0;
                }
                $time_str = '';
                if($v['begin_time']>0 || $v['end_time']>0){
                    $begin_time = $v['begin_time']>0?to_date($v['begin_time'],'Y-m-d H:i'):'';
                    $end_time = $v['end_time']>0?to_date($v['end_time'],'Y-m-d H:i'):'';
                    if($v['begin_time']>NOW_TIME){
                        $time_str = $begin_time.' 可以使用 ';
                    }else{
                        if($v['end_time']>0)
                            $time_str = $begin_time.' 至 '.$end_time;
                        else
                            $time_str = ' 不限时 ';
                    }
                        
                }else{
                    $time_str = ' 不限时 ';
                }
                $temp_arr['datetime']=$time_str;
                if ($temp_arr['use_status'] == 0){
                    if($v['use_limit']>0)
                        $money+=($v['use_limit']-$v['use_count'])*$v['money'];
                    else 
                        $money+=$v['money'];
                }
                $temp_arr['name'] = $v['name'];
                $temp_arr['is_all'] = $v['is_all'];
      
               	$temp_arr['use_limit'] = $v['use_limit'];
                $temp_arr['money'] = round($v['money'],2);;
                $temp_arr['memo'] = $v['memo'];
                $temp_arr['meet_amount'] = $v['meet_amount'];
                $json_data=json_decode($v['data'],1);
                if ($json_data) {
                    if($json_data['domain']['range_value1']==1){
                        $temp_arr['range_value1']="pk区"."&nbsp;";
                    }
                    if($json_data['domain']['range_value2']==2){
                        $temp_arr['range_value2']="十元区"."&nbsp;";
                    }
                    if($json_data['domain']['range_value3']==3){
                        $temp_arr['range_value3']="百元区"."&nbsp;";
                    }
                    if($json_data['domain']['range_value4']==4){
                        $temp_arr['range_value4']="直购区"."&nbsp;";
                    }
                    if($json_data['domain']['range_value5']==5){
                        $temp_arr['range_value5']="极速区"."&nbsp;";
                    }
                    if($json_data['domain']['range_value6']==6){
                        $temp_arr['range_value6']="选号区"."&nbsp;";
                    }
                    if($json_data['domain']['range_value7']==7){
                        $temp_arr['range_value7']="一元区"."&nbsp;";
                    }
                }
                $data_list[] =$temp_arr;
            }
            $root['data']=$data_list;
            $root['user_avatar'] = get_abs_img_root(get_muser_avatar($user_id,"big"));
            $root['ecv_count'] = $count;
            $root['ecv_total'] = round($money,2);
            
            $page_total = ceil($count/$page_size);
            $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
        };

        $root['page_title'].="我的红包";
        
        return output($root);
    }
    
    
    /**
     * 我的红包分页载入数据
     * 输入：
     * page: int 分页
     * n_valid: int 是否失效， 0未失效，1已失效
     *
     * 输出：
     * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
     * [data] => Array
     (
         [0] => Array
         (
             [id] => 7   ：int 红包ID
             [use_status] => 0   ：int 红包是否已经领取的状态
             [datetime] =>  不限时      ：string 红包到期时间显示内容
             [name] => 简单换       ：string 红包名称
             [money] => 15   ：float 红包金额
        )
     )
    
     [page] => Array
     (
         [page] => 1
         [page_total] => 3
         [page_size] => 10
         [data_total] => 22
     )
    
     *
     * */
    public function load_ecv_list(){
        $root = array();
        /*参数列表*/
        $n_valid = intval($GLOBALS['request']['n_valid']);
        
        /*业务逻辑*/
        //检查用户,用户密码
        $user = $GLOBALS['user_info'];
        $user_id  = intval($user['id']);
        
        $user_login_status = check_login();
        
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = $user_login_status;
            //分页
            $page = intval($GLOBALS['request']['page']);
            $page=$page==0?1:$page;
            
            $page_size = PAGE_SIZE;
            $limit = (($page-1)*$page_size).",".$page_size;
            
            $condtion = '';
            if($n_valid){
                $condtion = ' and (e.use_limit>0 and (e.use_limit = e.use_count || (et.end_time<'.NOW_TIME.' and et.end_time>0))) ';
            }else{
                $condtion = ' and (e.use_limit=0 or e.use_limit > e.use_count) ';
            }
            
            $sql = "select * from ".DB_PREFIX."ecv as e left join ".DB_PREFIX."ecv_type as et on e.ecv_type_id = et.id where e.user_id = ".$user_id.$condtion." order by e.id desc limit ".$limit;
            $sql_count = "select count(*) from ".DB_PREFIX."ecv e where user_id = ".$user_id.$condtion;
            
            $list = $GLOBALS['db']->getAll($sql);
            $count = $GLOBALS['db']->getOne($sql_count);
            $money = 0;
            foreach ($list as $k=>$v){
                $temp_arr = array();
                $temp_arr['id'] = $v['id'];
                if($v['use_limit'] == $v['use_count'] && $v['use_limit']>0)
                    $temp_arr['use_status'] = 1;    //0为使用或者还可以,1不可使用，或者已经使用光
                else{ 
                	if($v['end_time']<NOW_TIME && $v['end_time'] <> 0)
                		$temp_arr['use_status'] = 1;
                	else	
                    	$temp_arr['use_status'] = 0;
                }
                
                $time_str = '';
                if($v['begin_time']>0 || $v['end_time']>0){
                    $begin_time = $v['begin_time']>0?to_date($v['begin_time'],'Y-m-d H:i'):'';
                    $end_time = $v['end_time']>0?to_date($v['end_time'],'Y-m-d H:i'):'';
                    if($v['begin_time']>NOW_TIME){
                        $time_str = $begin_time.' 可以使用 ';
                    }else{
                        if($v['end_time']>0)
                            $time_str = $begin_time.' 至 '.$end_time;
                        else
                            $time_str = ' 不限时 ';
                    }
                        
                }
                $temp_arr['datetime']=$time_str;
                if ($temp_arr['use_status'] == 0){
                    if($v['use_limit']>0)
                        $money+=($v['use_limit']-$v['use_count'])*$v['money'];
                    else 
                        $money+=$v['money'];
                }
                $temp_arr['name'] = $v['name'];
                $temp_arr['money'] = round($v['money'],2);;
                $data_list[] =$temp_arr;
            }
            
            $root['data']=$data_list;
            
            $page_total = ceil($count/$page_size);
            $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
        };
        return output($root);
    }
    
    /**
     * 我的红包兑换页面
     * 输入：
     * 
     *
     * 输出：
     * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
     * [data] => Array
     (
         [0] => Array
         (
             [id] => 7   ：int 红包ID
             [name] => 简单换       ：string 红包名称
             [money] => 15   ：float 红包金额
             [exchange_score] => 10     :int 兑换所需的积分
         )
     )
    
     [page] => Array
     (
         [page] => 1
         [page_total] => 3
         [page_size] => 10
         [data_total] => 22
     )
    
     *
     * */
    public function exchange(){
        $root = array();
        /*参数列表*/
        
        /*业务逻辑*/
        //检查用户,用户密码
        $user = $GLOBALS['user_info'];
        $user_id  = intval($user['id']);
        
        $user_login_status = check_login();
        
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = $user_login_status;
            $root['score'] = $user['score'];
            //分页
            $page = intval($GLOBALS['request']['page']);
            $page=$page==0?1:$page;
        
            $page_size = PAGE_SIZE;
            $limit = (($page-1)*$page_size).",".$page_size;
            $sql = "select * from ".DB_PREFIX."ecv_type where send_type = 1 and (end_time>".NOW_TIME." or end_time = '') order by id desc limit ".$limit;
            $sql_count = "select count(*) from ".DB_PREFIX."ecv_type where send_type = 1 and (end_time>".NOW_TIME." or end_time = '')";
            
            $list = $GLOBALS['db']->getAll($sql);
            $exchange_count = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."ecv where user_id = ".intval($GLOBALS['user_info']['id']));
            $count = $GLOBALS['db']->getOne($sql_count);
            $data_list = array();
            foreach($list as $k=>$v){
                $temp_arr = array();
                $temp_arr['id'] =  $v['id'];
                $temp_arr['name'] =  $v['name'];
                $temp_arr['money'] =  round($v['money'],2);
                $temp_arr['exchange_score'] =  $v['exchange_score'];
                $temp_arr['id'] =  $v['id'];
                foreach($exchange_count as $kk => $vv){
                    if($vv['ecv_type_id']==$v['id']){
                        $i[$k]=$i[$k]+1;
                        if($i[$k]>=$v['exchange_limit']&&$v['exchange_limit']!=0){
                            $temp_arr['receive']=1;
                        }
                    }
                }
                $data_list[] = $temp_arr;
            }
            
            $root['data'] = $data_list;
            $page_total = ceil($count/$page_size);
            $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
            
        }

        $root['page_title'].="红包兑换";
        return output($root);
    }
    
    /**
     * SN红包兑换
     * 输入：
     * sn:string 红包SN
     *
     * 输出：
     * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
     * status   :int 状态 0失败 1成功
     * info     ：string  消息
     *
     * */
    public function do_snexchange(){
        $root = array();
        /*参数列表*/
        
        /*业务逻辑*/
        //检查用户,用户密码
        $user = $GLOBALS['user_info'];
        $user_id  = intval($user['id']);
        
        $user_login_status = check_login();
       
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
            return output($root);
        }else{
            $root['user_login_status'] = $user_login_status;
            
            $sn = strim($GLOBALS['request']['sn']);
            if (empty($sn)){
                return output($root,0,'口令不能为空');
            }
            $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where exchange_sn = '".$sn."'");
            $root['ecv_type'] = $ecv_type;
            if(!$ecv_type)
            {
            	$ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv where sn = '".$sn."'");
                if($ecv['end_time']<NOW_TIME&&$ecv['end_time']!=0){
                    return output($root,0,"红包已过期");
                }
                $GLOBALS['db']->query("update ".DB_PREFIX."ecv set user_id = '".$user_id."' where sn = '".$sn."' and user_id = 0");
            	if($GLOBALS['db']->affected_rows())
            	{
            		return output($root,1,"兑换成功");
            	}
            	else
                return output($root,0,"红包序列号不存在");
            }
            else
            {
                if($ecv_type['end_time']<NOW_TIME&&$ecv_type['end_time']!=0){
                    return output($root,0,"红包已过期");
                }
                $exchange_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."ecv where ecv_type_id = ".$ecv_type['id']." and user_id = ".intval($GLOBALS['user_info']['id']));
                if($ecv_type['exchange_limit']>0&&$exchange_count>=$ecv_type['exchange_limit'])
                {
                    $msg = sprintf("每个会员只能兑换%s张",$ecv_type['exchange_limit']);
                    return output($root,0,$msg);
                }
                else
                {
                    require_once APP_ROOT_PATH."system/libs/voucher.php";
                    $rs = send_voucher($ecv_type['id'],$user_id,1);
               		if($rs>0)
                    {
                        return output($root,1,"兑换成功");
                    }
                    else if($rs==-1)
                    {
                    	return output($root,0,"您来晚了，红包已领光了!");
                    }
                    else
                    {
                        return output($root,0,"兑换失败");
                    }
                }
            }
        }
        
        return output($root);
    }
    
    /**
     * 积分红包兑换
     * 输入：
     * id ：int 红包id
     *
     * 输出：
     * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
     * status   :int 状态 0失败 1成功
     * info     ：string  消息
     *
     * */
    public function do_exchange(){
        $root = array();
        /*参数列表*/
    
        /*业务逻辑*/
        //检查用户,用户密码
        $user = $GLOBALS['user_info'];
        $user_id  = intval($user['id']);
    
        $user_login_status = check_login();
         
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
            return output($root);
        }else{
            $root['user_login_status'] = $user_login_status;
            
            $id = intval($GLOBALS['request']['id']);
		    $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where id = ".$id);

            if(!$ecv_type)
            {
                return output($root,0,"红包序列号不存在");
            }
            else
            {
                if($ecv_type['end_time']<NOW_TIME&&$ecv_type['end_time']!=0){
                    $msg = "红包已过期";
                    showErr($msg,1);
                }
                
                $exchange_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."ecv where ecv_type_id = ".$id." and user_id = ".$user_id);
                if($ecv_type['exchange_limit']>0&&$exchange_count>=$ecv_type['exchange_limit'])
    			{
    				$msg = sprintf("每个会员只能兑换%s张",$ecv_type['exchange_limit']);
    				return output($root,0,$msg);
    			}
    			elseif($ecv_type['exchange_score']>intval($GLOBALS['db']->getOne("select score from ".DB_PREFIX."user where id = ".$user_id)))
    			{
    				return output($root,0,"积分不足");
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
    				        $msg = sprintf("兑换[%s]代金券消耗%s积分",$ecv_type['name'],$ecv_type['exchange_score']);
    				        	
    				        //更新用户日志
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
    				        	
    				        return output($root,1,"兑换成功");
    				    }
    				    $GLOBALS['db']->query("update ".DB_PREFIX."user set score = score + ".intval($ecv_type['exchange_score'])." where id =".$GLOBALS['user_info']['id']);
    				    if($rs==-1)
    				    {
    				        return output($root,0,"您来晚了，红包已领光了!");
    				    }
    				    else
    				    {
    				        return output($root,0,"兑换失败");
    				    }
    				
    				}else{
    				    return output($root,0,"兑换失败");
    				}
    			}
            }
        }
    
        return output($root);
    }
    
    /**
     * 我的红包兑换页面分页数
     * 输入：
     *
     *
     * 输出：
     * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
     * [data] => Array
     (
     [0] => Array
             (
                 [id] => 7   ：int 红包ID
                 [name] => 简单换       ：string 红包名称
                 [money] => 15   ：float 红包金额
                 [exchange_score] => 10     :int 兑换所需的积分
             )
     )
    
     [page] => Array
     (
     [page] => 1
     [page_total] => 3
     [page_size] => 10
     [data_total] => 22
     )
    
     *
     * */
    public function load_ecv_exchange_list(){
        $root = array();
        /*参数列表*/
    
        /*业务逻辑*/
        //检查用户,用户密码
        $user = $GLOBALS['user_info'];
        $user_id  = intval($user['id']);
    
        $user_login_status = check_login();
    
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = $user_login_status;
            $root['score'] = $user['score'];
            //分页
            $page = intval($GLOBALS['request']['page']);
            $page=$page==0?1:$page;
        
            $page_size = PAGE_SIZE;
            $limit = (($page-1)*$page_size).",".$page_size;
            $sql = "select * from ".DB_PREFIX."ecv_type where send_type = 1 and (end_time>".NOW_TIME." or end_time = '') order by id desc limit ".$limit;
            $sql_count = "select count(*) from ".DB_PREFIX."ecv_type where send_type = 1 and (end_time>".NOW_TIME." or end_time = '')";
            
            $list = $GLOBALS['db']->getAll($sql);
            $count = $GLOBALS['db']->getOne($sql_count);
            
            foreach($list as $k=>$v){
                $list[$k]['money'] = round($v['money'],2);
            }
            
            $root['data'] = $list;
            $page_total = ceil($count/$page_size);
            $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
        }
        return output($root);
    }

}

