<?php

/**
 * 手机端提现接口
 *
 * @author jobinlin
 */
class uc_money_cashApiModule extends MainBaseApiModule{
    
	    /**
	     * 用户提现首页
	     * 输入：
	     * 输出： money ：float 用余额
	     */
	    public function index()
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
	                    $user_money = $GLOBALS['db']->getOne("select money from ".DB_PREFIX."user where id = ".$user['id']);
	                    $withdraw_money = $GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."withdraw where is_delete = 0 and is_paid = 0 and user_id = ".$user['id']);
	                    $root['user_money'] = round($user_money,2);
	                    $root['money'] = round(($user_money-$withdraw_money),2);
	                    $root['give_money'] = $user['give_money'];
	                    $root['can_use_give_money'] = $user['can_use_give_money'];
	                    $root['fx_money'] = $user['fx_money'];
	                    $root['admin_money'] = $user['admin_money'];
	                }
			
			$root['page_title'].="提现";
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
		}
		else
                {
                    $root['user_login_status'] = $user_login_status;
                    $root['real_name'] = $user['real_name'];
                    $root['mobile'] = $user['mobile'];
                    $user_money = $GLOBALS['db']->getOne("select money from ".DB_PREFIX."user where id = ".$user['id']);
                    $withdraw_money = $GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."withdraw where is_delete = 0 and is_paid = 0 and user_id = ".$user['id']);
                    $root['money'] = round(($user_money-$withdraw_money),2);
                    $bank_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_bank where user_id = ".$user['id']);
                    $f_bank_list = array();

                    if($bank_list){
                        foreach ($bank_list as $k=>$v){
                            $temp_arr = array();
                            $temp_arr['id'] = $v['id'];
                            $tmp_bank_name = strripos($v['bank_name'], "银行")?$v['bank_name']:substr($v['bank_name'],  0,strripos($v['bank_name'], "银行")+8);
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
         * 提现明细
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
         * 登陆密码验证
         */
        public function password_check(){
            $root = array();		
            /*参数初始化*/
            $check_pwd = strim($GLOBALS['request']['check_pwd']);
            //检查用户,用户密码
            $user = $GLOBALS['user_info'];
            $user_login_status = check_login();
            if($user_login_status!=LOGIN_STATUS_LOGINED){
                $root['user_login_status'] = $user_login_status;
            }
            else
            {
                $root['user_login_status'] = $user_login_status;
                $pwd = $GLOBALS['db']->getOne("select user_pwd from ".DB_PREFIX."user where id = ".$user['id']);

                if(md5($check_pwd) == $pwd){
                    return output($root,1,"密码验证通过");
                }else{
                    return output($root,0,"密码验证失败");
                }
            }
            return output($root);
        }
        
        /**
         * 提现申请提交
         * 输入：
         * user_bank_id :int 绑定的银行卡ID
         * money：提现的金额
         * check_pwd:验证成功的密码
         * 
         * 以下信息可为空
         * sms_verify:短信验证码
         * 
         * 银行信息 | string
         * bank_name    开户行名称
         * bank_account 开户行账号
         * bank_user    开会真实姓名
         * bank_mobile  银行预留的手机号
         * 
         * 输出：
         * 已有银行卡操作：
         * status: 0 失败 1 成功
         * info ：错误或者成功的消息
         * 
         * 新银行卡
         * 多一个 withdraw_id：int 新卡的数据库ID ，下一步操作使用
         */
        public function do_withdraw(){
        
        	$root=array();
            $user = $GLOBALS['user_info'];
      
            //获取参数
            $user_bank_id = intval($GLOBALS['request']['user_bank_id']);
            $money = floatval($GLOBALS['request']['money']);
            $check_pwd = strim($GLOBALS['request']['check_pwd']);
            $withdraw_method = $GLOBALS['request']['withdraw_method'];
            
            $sms_verify = strim($GLOBALS['request']['sms_verify']);
            
            $user_login_status = check_login();
            if($user_login_status!=LOGIN_STATUS_LOGINED){
                $root['user_login_status'] = $user_login_status;
            }
            else
            {
                $root['user_login_status'] = $user_login_status;
                //有银行卡信息的~
                if($GLOBALS['request']['bank_name']){
                    $bank_name = strim($GLOBALS['request']['bank_name']);
                    $bank_account = strim($GLOBALS['request']['bank_account']);
                    $bank_user = strim($GLOBALS['request']['bank_user']);
                    $bank_mobile = strim($GLOBALS['request']['bank_mobile']);
                    
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
                    if($bank_mobile=="")
                    {
                            return output($root,0,"请输入银行预留手机号");
                    }
                    
                    
                    //短信码验证
                    if($sms_verify == ''){
                        return output($root,0,"请输入手机验证码");
                    }
                    $sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
                    $GLOBALS['db']->query($sql);

                    $mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$bank_mobile."'");

                    if($mobile_data['code']!=$sms_verify)
                    {
                        return output($root,0,"手机验证码错误");
                    }
                }

                //验证金额
                if($money <=0){
                    return output($root,0,"提现金额必须大于0");
                }
                
                if($GLOBALS['request']['bank_name']){   //银行卡表单提交并且提现
                	
                    $submitted_money = floatval($GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."withdraw where user_id = ".$user['id']." and is_delete = 0 and is_paid = 0 and withdraw_method  = ".$withdraw_method));
                    if($submitted_money+$money>$user[$withdraw_method])
                    {
                            return output($root,0,"提现超额");
                    }
                    $withdraw_data = array();
                    $withdraw_data['user_id'] = $user['id'];
                    $withdraw_data['create_time'] = NOW_TIME;
                    $withdraw_data['withdraw_method'] = $withdraw_method;
                    $withdraw_data['money'] = $money;
                    $withdraw_data['create_time'] = NOW_TIME;
                    $withdraw_data['bank_name'] = $bank_name;
                    $withdraw_data['bank_account'] = $bank_account;
                    $withdraw_data['bank_user'] = $bank_user;
                    $withdraw_data['bank_mobile'] = $bank_mobile;

                    $GLOBALS['db']->autoExecute(DB_PREFIX."withdraw",$withdraw_data);
                    $root['withdraw_id'] = $GLOBALS['db']->insert_id();
                    
                    $GLOBALS['db']->query("delete from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$bank_mobile."'");
                    
                    
                    
                }else{
                    //密码验证
                  
                    if(md5($check_pwd)!==$user['user_pwd']){
                        return output($root,0,"密码验证失败");
                    }
                    if($user_bank_id<=0){
                        return output($root,0,"提交数据不正确");
                    }
                    $user_bank_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_bank where id = ".$user_bank_id);
                    if(!$user_bank_info){
                        return output($root,0,"提交数据不正确");
                    }
                    $submitted_money = floatval($GLOBALS['db']->getOne("select sum(money) from ".DB_PREFIX."withdraw where user_id = ".$user['id']." and is_delete = 0 and is_paid = 0 and withdraw_method  = ".$withdraw_method));
                    if($submitted_money+$money>$user[$withdraw_method])
                    {
                        return output($root,0,"提现超额");
                    }
                    
                    $withdraw_data = array();
                    $withdraw_data['user_id'] = $user['id'];
                    $withdraw_data['create_time'] = NOW_TIME;
                    $withdraw_data['withdraw_method'] = $withdraw_method;
                    $withdraw_data['money'] = $money;
                    $withdraw_data['create_time'] = NOW_TIME;
                    $withdraw_data['bank_name'] = $user_bank_info['bank_name'];
                    $withdraw_data['bank_account'] = $user_bank_info['bank_account'];
                    $withdraw_data['bank_user'] = $user_bank_info['bank_user'];
                    $withdraw_data['bank_moblie'] = $user_bank_info['bank_moblie'];
                    $GLOBALS['db']->autoExecute(DB_PREFIX."withdraw",$withdraw_data);
                    
                }
                $tmp_bank_name = strripos($withdraw_data['bank_name'], "银行")?substr($withdraw_data['bank_name'],  0,strripos($withdraw_data['bank_name'], "银行")+6):$withdraw_data['bank_name'];
                $root['bank_name'] = $tmp_bank_name." 尾号".  substr($withdraw_data['bank_account'], -4);
                return output($root,1,"提现申请提交成功，请等待审核");
                
                
            }
            return output($root);
        }
        
        /**
         * 绑定
         * withdraw_id：int 银行卡ID
         * is_bind：int 是否绑定
         * bank_name | string 格式化好的银行名称+卡号尾数
         */
        public function do_bind_bank(){
            $user = $GLOBALS['user_info'];
            
            //获取参数
            $withdraw_id = intval($GLOBALS['request']['withdraw_id']);
            $is_bind = intval($GLOBALS['request']['is_bind']);
            
            $user_login_status = check_login();
            if($user_login_status!=LOGIN_STATUS_LOGINED){
                $root['user_login_status'] = $user_login_status;
            }
            else
            {
                $root['user_login_status'] = $user_login_status;
                if($is_bind>0 && $withdraw_id>0){
                    $withdraw_info = $GLOBALS['db']->getRow("select count(*) from ".DB_PREFIX."withdraw where user_id= ".$user['id']." and id=".$withdraw_id);
                    if($withdraw_info){
                        $GLOBALS['db']->autoExecute(DB_PREFIX."withdraw",array("is_bind"=>1),"UPDATE"," id = ".$withdraw_id);
                        if($GLOBALS['db']->affected_rows()){
                            output ($root,1,"操作成功");
                        }
                            
                    }
                }
                //提现时新增到数据库
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
//                if($sms_verify == ''){
//                	return output($root,0,"请输入手机验证码");
//                }
                $sql = "DELETE FROM ".DB_PREFIX."sms_mobile_verify WHERE add_time <=".(NOW_TIME-SMS_EXPIRESPAN);
//                $GLOBALS['db']->query($sql);
//                $mobile_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sms_mobile_verify where mobile_phone = '".$GLOBALS['user_info']['mobile']."'");
//
//                if($mobile_data['code']!=$sms_verify)
//                {
//                	return output($root,0,"手机验证码错误");
//                }
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
