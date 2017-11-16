<?php

/**
 * 手机端提现接口
 *
 * @author jobinlin
 */
class uc_moneyApiModule extends MainBaseApiModule{

	
        /**
         * 用户资金日志
         * 输入：
         * 输出：
         *  money ：float 余额
         
         */
        public function setting()
		{
			$root = array();		
			/*参数初始化*/
			
			//检查用户,用户密码
			$user = $GLOBALS['user_info'];
			$user_login_status = check_login();
			if($user_login_status!=LOGIN_STATUS_LOGINED){
			    $root['user_login_status'] = $user_login_status;
			}
			else
             {
                    $root['user_login_status'] = $user_login_status;
                    $root['money'] = round($GLOBALS['user_info']['money'],2);
             }

			$root['page_title'].="我的账户";
			return output($root);
		}	
	
	
	
        /**
         * 资金日志页
         * 输入：
         *  page int 分页
         *  
         * 输出： 
         * list 资金日志
			Array
        (
            [0] => Array
                (
                    [id] => 320 日志id
                    [money] => -0.3 夺宝币 日志金额
                    [log_time] => 2016-01-24 17:21:50 操作时间
                    [log_info] => fanweyydb提现0.3元元审核通过。   日志内容
                )          

        )

          page:array 分页信息 array("page"=>当前页数,"page_total"=>总页数,"page_size"=>分页量,"data_total"=>数据总量);
         * 
         */
        public function index()
		{
			$root = array();		
			/*参数初始化*/
			$page = intval($GLOBALS['request']['page'])?intval($GLOBALS['request']['page']):1; //当前分页
			//检查用户,用户密码
			$user = $GLOBALS['user_info'];
			$user_login_status = check_login();
			if($user_login_status!=LOGIN_STATUS_LOGINED){
			    $root['user_login_status'] = $user_login_status;
			}else{
                    $root['user_login_status'] = $user_login_status;                    
			
					require_once APP_ROOT_PATH.'system/model/user_center.php';
					$page_size = PAGE_SIZE;
					$limit = (($page-1)*$page_size).",".$page_size;			
					$result = get_user_log($limit,$GLOBALS['user_info']['id'],'money');
					$page_total = ceil($result['count']/$page_size);
					
					foreach($result['list'] as $k=>$v)
					{	
						$list[$k]['id']=$v['id'];						
						$list[$k]['money'] = format_duobao_price($v['money']);
						$list[$k]['log_time']=date("Y-m-d H:i:s",$v['log_time']);
						$list[$k]['log_info']=$v['log_info'];		
						//$list[$k]['type']=$v['type'];
					}					
					
					$root['list'] = $list?$list:array();
					
					$root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$result['count']);

					$root['page_title'].="我的资金日志";
             }			
			return output($root);
		}
        
        /**
         * 银行卡列表
         * 输入：无
         * 输出：
         * bank_list | array 绑定的银行列表
         * array(
         *      id
         *      bank_name
         * )
         * money | float 余额
         * real_name |string  会员真实姓名(用于再次绑定银行卡)
         */
        public function withdraw_bank_list(){
            $root = array();		
			/*参数初始化*/
			
			//检查用户,用户密码
			$user = $GLOBALS['user_info'];
			$user_login_status = check_login();
			if($user_login_status!=LOGIN_STATUS_LOGINED){
			    	$root['user_login_status'] = $user_login_status;
			}else{
                    $root['user_login_status'] = $user_login_status;
                    $root['real_name'] = $user['real_name'];
                    $root['mobile'] = $user['mobile'];
                    $root['money'] = round($GLOBALS['db']->getOne("select money from ".DB_PREFIX."user where id = ".$user['id']),2);
                    $bank_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bank where user_id = ".$user['id']);
                    $f_bank_list = array();

                    if($bank_list){
                        foreach ($bank_list as $k=>$v){
                            $temp_arr = array();
                            $temp_arr['id'] = $v['id'];
                            $tmp_bank_name = strripos($v['bank_name'], "银行")?substr($v['bank_name'],  0,strripos($v['bank_name'], "银行")+8):$v['bank_name'];
                            $temp_arr['bank_name'] = $tmp_bank_name." 尾号".  substr($v['bank_account'], -4);
                            $f_bank_list[] = $temp_arr;
                        }
                    }
                    $root['bank_list'] = $bank_list?$f_bank_list:array();
                    $root['step']=1;
	                    
	           }
		
				$root['page_title'].="提现";
				return output($root);
        }
        
        /**
         * 提现明细日志
         * * 输入：
         * page int 分页
         * 
         * 输出         
         * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
         * page:array 分页信息 array("page"=>当前页数,"page_total"=>总页数,"page_size"=>分页量,"data_total"=>数据总量);
         * data:提现日志 
         * array(
				[0] => Array
                (
                    [id] => 4 记录id
                    [user_id] => 240
                    [money] => 0.3 提现金额
                    [create_time] => 2016-01-24 17:04:59 提现时间
                    [is_paid] => 0  提现状态  0表示申请中 1表示已打款
                    [pay_time] => 0
                    [bank_name] => 支付宝 (...9999)  提现日志标题
                    [bank_account] => *******9999
                    [bank_user] => **耗
                    [is_delete] => 0
                    [bank_mobile] => 13899999999
                    [is_bind] => 0
                )
             )
         */
        public function withdraw_log(){
            $root = array();		
            /*参数初始化*/

            //检查用户,用户密码
            $user = $GLOBALS['user_info'];
            $user_login_status = check_login();
            if($user_login_status!=LOGIN_STATUS_LOGINED){
                $root['user_login_status'] = $user_login_status;
            }
            else
            {
              $root['user_login_status'] = $user_login_status;
                require_once APP_ROOT_PATH."system/model/user_center.php";
                //分页
                $page = intval($GLOBALS['request']['page']);
                $page=$page==0?1:$page;

                $page_size = PAGE_SIZE;
                $limit = (($page-1)*$page_size).",".$page_size;

                $result = get_user_withdraw($limit,$user['id']);
                foreach ($result['list'] as $k => $v) {
                    $result['list'][$k]['create_time'] = to_date($v['create_time']);
                    $result['list'][$k]['money'] = round($v['money'],2);
                    $tmp_bank_name = strripos($v['bank_name'], "银行")?substr($v['bank_name'],  0,strripos($v['bank_name'], "银行")+6):$v['bank_name'];
                    $result['list'][$k]['bank_name'] = $tmp_bank_name." (...".  substr($v['bank_account'], -4).")";
                }
                $root['data'] = $result['list'];
                $count = $result['count'];
                //分页
                $page_total = ceil($count/$page_size);
                $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
             }
            $root['page_title'].="提现明细";
            return output($root);
        }
        

        
        /**
         * 提现申请提交
         * 输入：

         * money：提现的金额
         * check_pwd:会员的密码
         * 
         * 输出：
         * 已有银行卡操作：
         * status: 0 失败 1 成功
         * info ：错误或者成功的消息
         * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
         * 

         */
        public function do_withdraw(){
            $user = $GLOBALS['user_info'];
            //获取参数
            $user_bank_id = intval($GLOBALS['request']['bank_id']);
            $money = floatval($GLOBALS['request']['money']);
            $pwd_check = strim($GLOBALS['request']['pwd_check']);

            
            $user_login_status = check_login();
            if($user_login_status!=LOGIN_STATUS_LOGINED){
                $root['user_login_status'] = $user_login_status;
            }
            else
            {
	                $root['user_login_status'] = $user_login_status;
	
	
	                //验证金额
	                if($money <=0){
	                    return output($root,0,"提现金额必须大于0");
	                }
                
                     //密码验证
                    if(md5($pwd_check)!=$user['user_pwd']){
                        return output($root,0,"密码验证失败");
                    }
                    if($user_bank_id<=0){
                        return output($root,0,"提交数据不正确");
                    }                
                    $submitted_money = floatval($GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."withdraw where user_id = ".$user['id']." and is_delete = 0 and is_paid = 0"));
                    if($submitted_money+$money>$user['money'])
                    {
                            return output($root,0,"提现超额");
                    }
               


                    $user_bank_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_bank where id = ".$user_bank_id." and user_id=".$user['id']);
                    
                    if(!$user_bank_info){
                        return output($root,0,"提交数据不正确");
                    }
                    $withdraw_data = array();
                    $withdraw_data['user_id'] = $user['id'];                    
                    $withdraw_data['money'] = $money;
                    $withdraw_data['create_time'] = NOW_TIME;
                    $withdraw_data['bank_name'] = $user_bank_info['bank_name'];
                    $withdraw_data['bank_account'] = $user_bank_info['bank_account'];
                    $withdraw_data['bank_user'] = $user_bank_info['bank_user'];
                    $withdraw_data['bank_mobile'] = $user_bank_info['bank_mobile'];
                    $GLOBALS['db']->autoExecute(DB_PREFIX."withdraw",$withdraw_data);
                    
                }
                $tmp_bank_name = strripos($withdraw_data['bank_name'], "银行")?substr($withdraw_data['bank_name'],  0,strripos($withdraw_data['bank_name'], "银行")+6):$withdraw_data['bank_name'];
                $root['bank_name'] = $tmp_bank_name." 尾号".  substr($withdraw_data['bank_account'], -4);
                return output($root,1,"提现申请提交成功，请等待审核");
                
                

            return output($root);
        }

         /**
         * 绑定银行卡界面接口
         * 
         * 输入
         * 
          输出：
         *user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)

         */       
        public function add_card(){
	  		$root = array();		
				
			$user_data = $GLOBALS['user_info'];				
			$user_login_status = check_login();
			if($user_login_status!=LOGIN_STATUS_LOGINED){			
				$root['user_login_status'] = $user_login_status;	
			}else{
				$root['user_login_status'] = 1;
		
			}	
			return output($root);	      
        }  
        
        /**
         * 绑定银行卡接口
         * 
         * 输入
         * sms_verify:短信验证码
         * bank_name    开户行名称
         * bank_account 开户行账号
         * bank_user    开会真实姓名

         * 
          输出：
         *user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
         * status: 0 失败 1 成功
         * info ：错误或者成功的消息
         */
        public function do_bind_bank(){
            $user = $GLOBALS['user_info'];
            
            $user_login_status = check_login();
            if($user_login_status!=LOGIN_STATUS_LOGINED){
                $root['user_login_status'] = $user_login_status;
            }
            else
            {
               		 $root['user_login_status'] = $user_login_status;
                     $bank_name = strim($GLOBALS['request']['bank_name']);
                    $bank_account = strim($GLOBALS['request']['bank_account']);
                    $bank_user = strim($GLOBALS['request']['bank_user']);
                    $sms_verify = strim($GLOBALS['request']['sms_verify']);
                    if($bank_name=="")
                    {
                            return output($root,0,"请输入开户行全称");
                    }
                    if($bank_account=="")
                    {
                            return output($root,0,"请输入开户行账号");
                    }
                    if($bank_user=="")
                    {
                            return output($root,0,"请输入开户人真实姓名");
                    }                    
                    
                    //短信码验证
                    if($sms_verify == ''){
                        return output($root,0,"请输入手机验证码");
                    }
                    $sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
                    $GLOBALS['db']->query($sql);
                    $mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$GLOBALS['user_info']['mobile']."'");

                    if($mobile_data['code']!=$sms_verify)
                    {
                        return output($root,0,"手机验证码错误");
                    }
                   $data['user_id'] = $GLOBALS['user_info']['id'];
                    $data['bank_name'] = $bank_name;
                    $data['bank_account'] = $bank_account;
                    $data['bank_user'] = $bank_user;
                    $data['bank_moblie'] = $GLOBALS['user_info']['mobile'];
                    $GLOBALS['db']->autoExecute(DB_PREFIX."user_bank",$data);
            }
            return output($root);
        }

}
