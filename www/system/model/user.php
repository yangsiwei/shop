<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

define("EMPTY_ERROR",1);  //未填写的错误
define("FORMAT_ERROR",2); //格式错误
define("EXIST_ERROR",3); //已存在的错误

define("ACCOUNT_NO_EXIST_ERROR",1); //帐户不存在
define("ACCOUNT_PASSWORD_ERROR",2); //帐户密码错误
define("ACCOUNT_NO_VERIFY_ERROR",3); //帐户未激活

define("LOGIN_STATUS_LOGINED",1); //成功登录
define("LOGIN_STATUS_NOLOGIN",0); //未登录
define("LOGIN_STATUS_TEMP",2); //临时登录


	/**
	 * 自动创建会员
	 * @param unknown_type $user_data 自动创建的会员基本数据(只需要user_name/mobile/email任一，以及第三方登录的一些特殊字段，如新浪ID等)
	 * @param unknown_type $type 0:账号 1:手机号 2:邮箱 (只有0账号类型可以不报错创建用户)
	 * $allow_err : 是否报错
	 */
	function auto_create($user_data,$type,$allow_err=false,$user_logo="")
	{
		if(isset($user_data['user_name'])){
		    $user_data['user_name'] = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '[表情]', $user_data['user_name']);
		}
		if($type<0||$type>2){
		    $type=0;
		}
        if($type==1){
            $user_data['user_name'] = substr($user_data['mobile'], -4)."_".substr(md5(rand(100000,999999)), 0,3);
            $user_data['is_phone_register'] = 1;
        }
		if($user_data['user_pwd']=="")
		$user_data['user_pwd'] = md5(rand(100000,999999));
		$user_data['create_time'] = NOW_TIME;
		$user_data['update_time'] = NOW_TIME;
		$user_data['login_ip'] = CLIENT_IP;
		$user_data['login_time'] = NOW_TIME;
		$user_data['is_tmp'] = 1;
		
		// 判断默认新增用户是否开启渠道二维码
		$fx_default_status = $GLOBALS['db']->getOne("select fx_default_status from ".DB_PREFIX."fx_salary");
		if ($fx_default_status == 1) {
		    $user_data['is_open_scan'] = 1;
		}else{
		    $user_data['is_open_scan'] = 0;
		}
		
		
		// 判断是否有pid
		if (!$user_data['pid']) {
		    $ref_uid = intval( es_cookie::get("ref_uid") );
			$user_data['pid'] = $ref_uid;
		}
		$user_data['is_effect'] = 1;
		if ($user_logo) {
			$user_data['user_logo'] = $user_logo;
		}
        
		$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user_data,"INSERT","","SILENT");
		$user_id = intval($GLOBALS['db']->insert_id());



		if($user_id)
		{
			$user_data['id'] = $user_id;

			$user_name = $user_data['user_name']?$user_data['user_name']:"游客";

			if(empty($user_data['user_name']))
			{
				$count = 0;
				do{
					if($count==0)
						$user_data['user_name'] = $user_name."_".$user_id;
					else
						$user_data['user_name'] = $user_name."_".$user_id.$count;
					$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user_data,"UPDATE","id=".$user_id,"SILENT");
					$affected_rows = intval($GLOBALS['db']->affected_rows());
					$count++;
				}while($affected_rows<=0);
			}

			$register_money = 0;
			$register_score = intval(app_conf("USER_REGISTER_SCORE"));
			$register_coupons = intval(app_conf("USER_REGISTER_COUPONS_ACCOUNT"));
			$register_point = 0;
			if($register_money>0||$register_score>0 || $register_point>0 || $register_coupons>0 )
			{
				$user_get['score'] = $register_score;
				$user_get['money'] = $register_money;
				$user_get['point'] = $register_point;
				$user_get['coupons'] = $register_coupons;
				$user_get['send_money']=1;
				modify_account($user_get,intval($user_id),"在".to_date(NOW_TIME)."注册成功");
			}
			
			$arr['user_id']=$user_data['pid'];
			$arr['rel_user_id']=$user_id;
			$arr['create_time']=NOW_TIME;
			$arr['pay_time']=NOW_TIME;
			$arr['order_id']=0;
			if($user_data['pid']!=0){
			    if(app_conf("USER_INVITE_SCORE")>0 && app_conf("USER_INVITE_COUPONS_ACCOUNT")>0){
			        $arr['score']=app_conf("USER_INVITE_SCORE");
			        $arr['coupons']=app_conf("USER_INVITE_COUPONS_ACCOUNT");
			        $GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$arr);
			    }
			    else if(app_conf("USER_INVITE_SCORE")>0){
			        $arr['score']=app_conf("USER_INVITE_SCORE");
			        $GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$arr);
			    }
			    else if(app_conf("USER_INVITE_COUPONS_ACCOUNT")>0){
			        $arr['coupons']=app_conf("USER_INVITE_COUPONS_ACCOUNT");
			        $GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$arr);
			    }
			    $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 7");
			    if($ecv_type){
			        if($ecv_type['sm_way']==1){
			            $ecv_type['data']=json_decode($ecv_type['data'],1);
			            if($ecv_type['data']['rand_value1']>0){
			                $money=number_format(rand(($ecv_type['data']['rand_value1']*100),($ecv_type['data']['rand_value2']*100))/100,2);
			                send_vouchers($user_data['pid'],$money,1);
			                send_msg($user_data['pid'], "有人通过你分享的链接注册成功，送您".$money."元红包啦", "notify");
			            }
			        }elseif($ecv_type['sm_way']==0){
			            if($ecv_type['money']>0){
			                $money=number_format($ecv_type['money'],2);
			                send_vouchers($user_data['pid'],$money,1);
			                send_msg($user_data['pid'], "有人通过你分享的链接注册成功，送您".$money."元红包啦", "notify");
			            }
			        }
			    
			    }
			}
			if($GLOBALS['ref_uid']>0){
				$user_log['score'] = intval(app_conf("USER_INVITE_SCORE"));
				$user_log['coupons'] = intval(app_conf("USER_INVITE_COUPONS_ACCOUNT"));
				
				if ($user_log['score']>0 && $user_log['coupons']>0){
				    $log_msg="在".to_date(NOW_TIME)."注册成功邀请返积分和优惠币";
				}
				if($user_log['score']>0){
				    $log_msg="在".to_date(NOW_TIME)."注册成功邀请返积分";
				}
				if($user_log['coupons']>0){
				    $log_msg="在".to_date(NOW_TIME)."注册成功邀请返优惠币";
				}
				
				modify_account($user_log,intval($GLOBALS['ref_uid']),$log_msg);
				
			}
			
		 
			
			//新用户送红包是否开启
			$reg_ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 4");
			if($reg_ecv){
			    require_once APP_ROOT_PATH."system/libs/voucher.php";
			    $reg_ecv_id = send_voucher($reg_ecv, $user_id);
			    if ( $reg_ecv_id > 0 ) {
			        $reg_ecv_money = $GLOBALS['db']->getOne("select money from ".DB_PREFIX."ecv where id = {$reg_ecv_id}");
			        $reg_ecv_money = round($reg_ecv_money, 2);
			        es_session::set("is_send_reg_ecv", "1");
			        es_session::set("reg_ecv_money", $reg_ecv_money);
                    send_msg($user_id, "新用户注册，送{$reg_ecv_money}元红包", "notify");
			    }
			}
			
			
			// 新用户是否开启注册送夺宝币
			$reg_money = app_conf('USER_REGISTER_MONEY');
			if($reg_money){
			    $account_data['money'] = $reg_money;
			    $log_msg="新用户注册，送{$reg_money}个夺宝币";
			    modify_account($account_data, $user_data['id'], $log_msg);
			    send_msg($user_data['id'], $log_msg, "notify");
			}
			
			
 
		 
			//大于0即开启新用户送优惠币
			if(intval(app_conf("USER_INVITE_COUPONS_ACCOUNT"))>0){
			    es_cookie::set("send_coupons","1");
			}
			
			return array("status"=>true,"info"=>"注册成功","user_data"=>$user_data);
		}
		else
		{
			if($allow_err||$type!=0)
			{
				if($type==0)
				{
					$info = "用户名已存在";
				}
				elseif($type==1)
				{
					$info = "手机号已被抢占";
				}
				else
				{
					$info = "邮箱已注册";
				}
				return array("status"=>false,"info"=>$info,"user_data"=>$user_data);
			}
			else
			{
				$user_name = $user_data['user_name'];
				$count = 1;
				do{
					$user_data['user_name'] = $user_name."_".$count;
					$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user_data,"INSERT","","SILENT");
					$user_id = intval($GLOBALS['db']->insert_id());
					$count++;
				}while($user_id==0);
				$user_data['id'] = $user_id;


				$register_money = 0;
				$register_score = intval(app_conf("USER_REGISTER_SCORE"));
				$register_coupons = intval(app_conf("USER_INVITE_COUPONS_ACCOUNT"));
				
				$register_point = 0;
				if($register_money>0||$register_score>0 || $register_point>0 || $register_coupons>0)
				{
					$user_get['score'] = $register_score;
					$user_get['money'] = $register_money;
					$user_get['point'] = $register_point;
					$user_get['coupons'] = $register_coupons;
					modify_account($user_get,intval($user_id),"在".to_date(NOW_TIME)."注册成功");
				}
				if($user_data['pid']!=0){
				    $arr['user_id']=$user_data['pid'];
				    $arr['rel_user_id']=$user_id;
				    $arr['create_time']=NOW_TIME;
				    $arr['pay_time']=NOW_TIME;
				    $arr['order_id']=0;
				    if(app_conf("USER_INVITE_SCORE")>0 && app_conf("USER_INVITE_COUPONS_ACCOUNT")>0){
				        $arr['coupons']=app_conf("USER_INVITE_COUPONS_ACCOUNT");
				        $arr['score']=app_conf("USER_INVITE_SCORE");
				        
				        $GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$arr);
				    }
					else if(app_conf("USER_INVITE_SCORE")>0){
						$arr['score']=app_conf("USER_INVITE_SCORE");
						
						$GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$arr);
					}
					else if(app_conf("USER_INVITE_COUPONS_ACCOUNT")>0){
					    $arr['coupons']=app_conf("USER_INVITE_COUPONS_ACCOUNT");
					    
					    $GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$arr);
					}
					$ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 7");
					if($ecv_type){
					    if($ecv_type['sm_way']==1){
					        $ecv_type['data']=json_decode($ecv_type['data'],1);
					        if($ecv_type['data']['rand_value1']>0){
					            $money=number_format(rand(($ecv_type['data']['rand_value1']*100),($ecv_type['data']['rand_value2']*100))/100,2);
					            send_vouchers($user_data['pid'],$money,1);
					            send_msg($user_data['pid'], "有人通过你分享的链接注册成功，送您".$money."元红包啦", "notify");
					        }
					    }elseif($ecv_type['sm_way']==0){
					        if($ecv_type['money']>0){
					            $money=number_format($ecv_type['money'],2);
					            send_vouchers($user_data['pid'],$money,1);
					            send_msg($user_data['pid'], "有人通过你分享的链接注册成功，送您".$money."元红包啦", "notify");
					        }
					    }
					     
					}
					
				}
				
				if($GLOBALS['ref_uid']>0){
				    $user_log['score'] = intval(app_conf("USER_INVITE_SCORE"));
				    $user_log['coupons'] = intval(app_conf("USER_INVITE_COUPONS_ACCOUNT"));
				    
				    if ($user_log['score']>0 && $user_log['coupons']>0){
				        $log_msg="在".to_date(NOW_TIME)."注册成功邀请返积分和优惠币";
				    }
				    if($user_log['score']>0){
				        $log_msg="在".to_date(NOW_TIME)."注册成功邀请返积分";
				    }
				    if($user_log['coupons']>0){
				        $log_msg="在".to_date(NOW_TIME)."注册成功邀请返优惠币";
				    }
				    
				    modify_account($user_log,intval($GLOBALS['ref_uid']),$log_msg);
				}
				//新用户送红包是否开启
				$conf_red_packet=$GLOBALS['db']->getOne(" select value from ".DB_PREFIX."conf where name = 'USER_REGISTER_BRIBERY_MONEY' and is_effect = 1");
				if($conf_red_packet==1){
				    es_cookie::set("send_draw","1");
				}
				
				//大于0即开启新用户送优惠币
				if(intval(app_conf("USER_INVITE_COUPONS_ACCOUNT"))>0){
				    es_cookie::set("send_coupons","1");
				}
				
				return array("status"=>true,"info"=>"注册成功","user_data"=>$user_data);
			}

		}

	}

	/**
	 * 生成会员数据
	 * @param $user_data  提交[post或get]的会员数据
	 * @param $mode  处理的方式，注册或保存
	 * 返回：data中返回出错的字段信息，包括field_name, 可能存在的field_show_name 以及 error 错误常量
	 * 不会更新保存的字段为：score,money,verify,pid
	 */
	function save_user($user_data,$mode='INSERT')
	{
		//开始数据验证
		$res = array('status'=>1,'info'=>'','data'=>''); //用于返回的数据

		if(trim($user_data['user_pwd'])!='')
		{
			if(strlen($user_data['user_pwd'])<4||strlen($user_data['user_pwd'])>30)
			{
				$field_item['field_name'] = 'user_pwd';
				$field_item['error']	=	FORMAT_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}

		}


        if(trim($user_data['user_name'])=='')
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EMPTY_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if(!check_username($user_data['user_name']))
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where (user_name = '".trim($user_data['user_name'])."' or mobile = '".trim($user_data['user_name'])."' or email = '".trim($user_data['user_name'])."') and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}

// 		if(trim($user_data['email'])=='')
// 		{
// 			$field_item['field_name'] = 'email';
// 			$field_item['error']	=	EMPTY_ERROR;
// 			$res['status'] = 0;
// 			$res['data'] = $field_item;
// 			return $res;
// 		}
		if(!check_email(trim($user_data['email'])))
		{
			$field_item['field_name'] = 'email';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if(trim($user_data['email']) && $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where (user_name = '".trim($user_data['email'])."' or mobile = '".trim($user_data['email'])."' or email = '".trim($user_data['email'])."') and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'email';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}



		if(!check_mobile(trim($user_data['mobile'])))
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($user_data['mobile']!=''&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where (user_name = '".trim($user_data['mobile'])."' or mobile = '".trim($user_data['mobile'])."' or email = '".trim($user_data['mobile'])."') and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}


		//验证结束开始插入数据
		$user['user_name'] = $user_data['user_name'];
		$user['create_time'] = NOW_TIME;
		$user['update_time'] = NOW_TIME;
		$user['pid'] = $user_data['pid'];
		if(isset($user_data['province_id']))
		$user['province_id'] = intval($user_data['province_id']);
		if(isset($user_data['city_id']))
		$user['city_id'] = intval($user_data['city_id']);
		if(isset($user_data['sex']))
		$user['sex'] = intval($user_data['sex']);
		$user['my_intro'] = strim($user_data['my_intro']);
		if(isset($user_data['byear']))
		$user['byear'] = intval($user_data['byear']);
		if(isset($user_data['bmonth']))
		$user['bmonth'] = intval($user_data['bmonth']);
		if(isset($user_data['bday']))
		$user['bday'] = intval($user_data['bday']);
		if(isset($user_data['user_logo']))
		    $user['user_logo'] = strim($user_data['user_logo']);

		if(isset($user_data['is_merchant']))
		{
			$user['is_merchant'] = intval($user_data['is_merchant']);
			$user['merchant_name'] = $user_data['merchant_name'];
		}
		if(isset($user_data['is_daren']))
		{
			$user['is_daren'] = intval($user_data['is_daren']);
			$user['daren_title'] = $user_data['daren_title'];
		}
		
		// 判断默认新增用户是否开启渠道二维码
		$fx_default_status = $GLOBALS['db']->getOne("select fx_default_status from ".DB_PREFIX."fx_salary");
		if ($fx_default_status == 1) {
		    $user['is_open_scan'] = 1;
		}else{
		    $user['is_open_scan'] = 0;
		}



		//会员状态
		if(intval($user_data['is_effect'])!=0)
		{
			$user['is_effect'] = $user_data['is_effect'];
		}
		else
		{
			if($mode == 'INSERT')
			{
				$user['is_effect'] = 1;
			}
		}

		$user['email'] = $user_data['email'];
		$user['mobile'] = $user_data['mobile'];
		if($mode == 'INSERT')
		{
			$user['code'] = ''; //默认不使用code, 该值用于其他系统导入时的初次认证
		}
		else
		{
			$db_user = $GLOBALS['db']->getRow("select user_name,is_tmp,code from ".DB_PREFIX."user where id =".$user_data['id']);
			$user['code'] = $db_user['code'];
//			if($db_user['is_tmp']==0) //非临时用户不能改名
//			{
            if($db_user['user_name']!=$user_data['user_name'])
				$user['user_name'] = $user['user_name'];
//			}
//			else
//			{
//				$user['is_tmp'] = 0;
//			}
		}
		if(isset($user_data['user_pwd'])&&$user_data['user_pwd']!='')
		$user['user_pwd'] = md5($user_data['user_pwd'].$user['code']);
		$res['user_pwd'] = $user['user_pwd'];
		//载入会员整合
		$integrate_code = strim(app_conf("INTEGRATE_CODE"));
		if($integrate_code!='')
		{
			$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
			if(file_exists($integrate_file))
			{
				require_once $integrate_file;
				$integrate_class = $integrate_code."_integrate";
				$integrate_obj = new $integrate_class;
			}
		}
		//同步整合
		if($integrate_obj)
		{
			if($mode == 'INSERT')
			{
				$res = $integrate_obj->add_user($user_data['user_name'],$user_data['user_pwd'],$user_data['email']);
				$user['integrate_id'] = intval($res['data']);
			}
			else
			{
				$add_res = $integrate_obj->add_user($user_data['user_name'],$user_data['user_pwd'],$user_data['email']);
				if(intval($add_res['status']))
				{
					$GLOBALS['db']->query("update ".DB_PREFIX."user set integrate_id = ".intval($add_res['data'])." where id = ".intval($user_data['id']));
				}
				else
				{
					if(isset($user_data['user_pwd'])&&$user_data['user_pwd']!='') //有新密码
					{
						$status = $integrate_obj->edit_user($user,$user_data['user_pwd']);
						if($status<=0)
						{
							//修改密码失败
							$res['status'] = 0;
						}
					}
				}
			}
			if(intval($res['status'])==0) //整合注册失败
			{
				return $res;
			}
		}


		if($user['email']=="")unset($user['email']);
		if($user['mobile']=="")unset($user['mobile']);
		if($mode == 'INSERT')
		{
			$s_api_user_info = es_session::get("api_user_info");
			$user[$s_api_user_info['field']] = $s_api_user_info['id'];
			es_session::delete("api_user_info");
			$where = '';
		}
		else
		{
			unset($user['pid']);
			$where = "id=".intval($user_data['id']);
		}
		if($GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,$mode,$where))
		{

			if($mode == 'INSERT')
			{
				$user_id = $GLOBALS['db']->insert_id();
				$register_money = 0;
				$register_score = intval(app_conf("USER_REGISTER_SCORE"));
				$register_point = 0;
				$register_coupons = intval(app_conf("USER_REGISTER_COUPONS_ACCOUNT"));
				if($register_money>0||$register_score>0 || $register_point>0 || $register_coupons>0)
				{
					$user_get['score'] = $register_score;
					$user_get['money'] = $register_money;
					$user_get['point'] = $register_point;
					$user_get['coupons'] = $register_coupons;
					modify_account($user_get,intval($user_id),"在".to_date(NOW_TIME)."注册成功");
				}
				 
				if($user_data['pid']!=0){
				    $arr['user_id']=$user_data['pid'];
				    $arr['rel_user_id']=$user_id;
				    $arr['create_time']=NOW_TIME;
				    $arr['pay_time']=NOW_TIME;
				    $arr['order_id']=0;
				    if(app_conf("USER_INVITE_SCORE")>0 && app_conf("USER_INVITE_COUPONS_ACCOUNT")>0){
				        $arr['score']=app_conf("USER_INVITE_SCORE");
				        $arr['coupons']=app_conf("USER_INVITE_COUPONS_ACCOUNT");
				        $GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$arr);
				    }
				    else if(app_conf("USER_INVITE_COUPONS_ACCOUNT")>0){
				        $arr['coupons']=app_conf("USER_INVITE_COUPONS_ACCOUNT");
				        $GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$arr);
				    }
				    else if(app_conf("USER_INVITE_SCORE")>0){
				        $arr['score']=app_conf("USER_INVITE_SCORE");
				        $GLOBALS['db']->autoExecute(DB_PREFIX."referrals",$arr);
				    }
				    
				    if($GLOBALS['ref_uid']>0){
    				    $data['score']=app_conf("USER_INVITE_SCORE");
    				    $data['coupons']=app_conf("USER_INVITE_COUPONS_ACCOUNT");
						
    				    if ($data['score']>0 && $data['coupons']>0){
    				        $log_msg="在".to_date(NOW_TIME)."注册成功邀请返积分和优惠币";
    				    }
    				    if($data['score']>0){
    				        $log_msg="在".to_date(NOW_TIME)."注册成功邀请返积分";
    				    }
    				    if($data['coupons']>0){
    				        $log_msg="在".to_date(NOW_TIME)."注册成功邀请返优惠币";
    				    }
    				    
    				    modify_account($data,intval($GLOBALS['ref_uid']),$log_msg);
				    }
				}
				 
			}
			else
			{
				$user_id = $user_data['id'];
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set user_name = '".$user_data['user_name']."' where user_id = ".$user_id);
				require_once APP_ROOT_PATH."system/model/deal_order.php";
				$user_order_table_name = get_user_order_table_name($user_id);
				$GLOBALS['db']->query("update ".$user_order_table_name." set user_name = '".$user_data['user_name']."' where user_id = ".$user_id);

				$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set luck_user_name = '".$user_data['user_name']."' where luck_user_id=".$user_id);
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set user_name = '".$user_data['user_name']."' where user_id=".$user_id);
			}
			//新用户送夺宝币是否开启
			$conf_red_packet=$GLOBALS['db']->getOne(" select value from ".DB_PREFIX."conf where name = 'USER_REGISTER_BRIBERY_MONEY' and is_effect = 1");
			if($conf_red_packet==1){
			    es_cookie::set("send_draw","1");
			}
			//新用户送优惠币是否开启
			$conf_register_coupons=$GLOBALS['db']->getOne(" select value from ".DB_PREFIX."conf where name = 'USER_REGISTER_COUPONS' and is_effect = 1");
			if($conf_register_coupons==1){
			    es_cookie::set("send_coupons","1");
			}
		}
		$res['data'] = $user_id;

		//开始更新处理扩展字段
		if($mode == 'INSERT')
		{
			foreach($user_field as $field_item)
			{
				$extend = array();
				$extend['user_id'] = $user_id;
				$extend['field_id'] = $field_item['id'];
				$extend['value'] = $user_data[$field_item['field_name']];
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_extend",$extend,$mode);
			}

		}
		else
		{
			foreach($user_field as $field_item)
			{
				$extend = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_extend where user_id=".$user_id." and field_id =".$field_item['id']);
				if($extend)
				{
					$extend['value'] = $user_data[$field_item['field_name']];
					$where = 'id='.$extend['id'];
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_extend",$extend,$mode,$where);
				}
				else
				{
					$extend = array();
					$extend['user_id'] = $user_id;
					$extend['field_id'] = $field_item['id'];
					$extend['value'] = $user_data[$field_item['field_name']];
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_extend",$extend,"INSERT");
				}

			}
		}
		load_user($user_id,true);
		return $res;
	}
	
	/**
	 * 代金券发放
	 * @param $money 代金券金额
	 * @param $order_sn 订单id
	 * @param $user_id  发放给的会员。0为线下模式的发放
	 * @param 分享红包，拆分红包专用
	 */
    function send_vouchers($user_id=0,$money,$is_password=false)
	{
	    if(!$GLOBALS['db']->affected_rows())
	    {
	        return -1;
	    }
	    $ecv_type_id=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 7");
	    if($is_password)$ecv_data['password'] = rand(10000000,99999999);
	    $ecv_data['use_limit']         = 1;
	    $ecv_data['begin_time']        = $ecv_type_id['begin_time'];
	    $ecv_data['end_time']          = $ecv_type_id['end_time'];
	    $ecv_data['money']             = $money;
	    $ecv_data['ecv_type_id']       = $ecv_type_id['id'];
	    $ecv_data['user_id']           = $user_id;
	    $ecv_data['data']              = $ecv_type_id['data'];
	    $ecv_data['is_all']            = $ecv_type_id['is_all'];
	    $ecv_data['meet_amount']       = $ecv_type_id['meet_amount'];
	
	    do{
	        $sn = unpack('H12',str_shuffle(md5(uniqid())));
	        $sn = $sn[1];
	        $ecv_data['sn'] = $sn;
	        //$ecv_data['sn'] = md5(NOW_TIME);
	        $GLOBALS['db']->autoExecute(DB_PREFIX."ecv",$ecv_data,'INSERT','','SILENT');
	        $insert_id = $GLOBALS['db']->insert_id();
	    }while(intval($insert_id) == 0);
	}

	/**
	 * 删除会员以及相关数据
	 * @param integer $id
	 */
	function delete_user($id)
	{

		$result = 1;
		//载入会员整合
		$integrate_code = strim(app_conf("INTEGRATE_CODE"));
		if($integrate_code!='')
		{
			$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
			if(file_exists($integrate_file))
			{
				require_once $integrate_file;
				$integrate_class = $integrate_code."_integrate";
				$integrate_obj = new $integrate_class;
			}
		}
		if($integrate_obj)
		{
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$id);
			$result = $integrate_obj->delete_user($user_info);
		}

		if($result>0)
		{
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user where id =".$id); //删除会员

			//以上数据不删除，只更新字段内容
			$GLOBALS['db']->query("update ".DB_PREFIX."user set pid = 0 where pid = ".$id); //更新推荐人数据为0
			$GLOBALS['db']->query("update ".DB_PREFIX."referrals set rel_user_id = 0 where rel_user_id=".$id);  //更新返利记录的推荐人为0
			$GLOBALS['db']->query("update ".DB_PREFIX."user_log set log_user_id = 0 where log_user_id=".$id);  //更新记录会员ID为0
			$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set user_id = 0 where user_id=".$id);    //收款单
			$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set user_id= 0 where user_id=".$id);  //订单
			$GLOBALS['db']->query("update ".DB_PREFIX."delivery_notice set user_id = 0 where user_id=".$id);


			//开始删除关联数据
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_auth where user_id=".$id);  //权限
			$GLOBALS['db']->query("delete from ".DB_PREFIX."user_log where user_id=".$id);  //会员日志
            $GLOBALS['db']->query("delete from ".DB_PREFIX."user_consignee where user_id=".$id);  //配送地址


		}
	}

	/**
	 * 会员资金积分变化操作函数
	 * @param array $data 包括 score,money,point
	 * @param integer $user_id
	 * @param string $log_msg 日志内容
	 */
	function modify_account($data,$user_id,$log_msg='')
	{

		if(intval($data['score'])!=0)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."user set score = score + ".intval($data['score'])." where id =".$user_id);
			if($data['score']>0)
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."user set total_score = total_score + ".intval($data['score'])." where id =".$user_id);
			}
		}
		if(intval($data['point'])!=0)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."user set point = point + ".intval($data['point'])." where id =".$user_id);

		}
		if(intval($data['coupons'])!=0)
		{
		    if($data['send_coupons'] == 1){
		        $GLOBALS['db']->query("update ".DB_PREFIX."user set has_coupons = 1,coupons = coupons + ".intval($data['coupons'])." where has_coupons = 0 and id =".$user_id);
		        $affected_rows = intval( $GLOBALS['db']->affected_rows() );
		        if($affected_rows <= 0){
		            return false;
		        }
		    }else{
		        $GLOBALS['db']->query("update ".DB_PREFIX."user set coupons = coupons + ".intval($data['coupons'])." where id =".$user_id);
		    }
		}
		if(floatval($data['money'])!=0)
		{
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set money = money + ".floatval($data['money'])." where id =".$user_id);
		}

		if(intval($data['score'])!=0||floatval($data['money'])!=0||intval($data['point'])!=0 || intval($data['coupons'])!=0)
		{
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where is_delete = 0 and is_effect = 1 and id = ".$user_id);
			 

			if($user_info['is_robot']==0)// by hc4.18 机器人不产生日志
			{
				$log_info['log_info'] = $log_msg;
				$log_info['log_time'] = NOW_TIME;
				$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));

				$adm_id = intval($adm_session['adm_id']);
				if($adm_id!=0)
				{
					$log_info['log_admin_id'] = $adm_id;
				}
				else
				{
					$log_info['log_user_id'] = intval($user_info['id']);
				}
				$log_info['money'] = floatval($data['money']);
				$log_info['score'] = intval($data['score']);
				$log_info['point'] = intval($data['point']);
				$log_info['coupons'] = intval($data['coupons']);
				$log_info['user_id'] = $user_id;
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$log_info);

				load_user($user_id,true);
			}
		}
	}

	/**
	 * 处理cookie的自动登录
	 * @param $user_name_or_email  用户名或邮箱
	 * @param $user_md5_pwd  md5加密过的密码
	 * $from_cookie  为true时表示密码加密需要后缀
	 */
	function auto_do_login_user($user_name_or_email,$user_md5_pwd,$from_cookie = true)
	{
		$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where (user_name=\"".$user_name_or_email."\" or email = \"".$user_name_or_email."\" or mobile = \"".$user_name_or_email."\") and is_delete = 0");
		if($user_data)
		{
			$pwdOK = false;
			if($from_cookie)
			{
				$pwdOK = md5($user_data['user_pwd']."_EASE_COOKIE")==$user_md5_pwd;
			}
			else
			{
				$pwdOK = $user_data['user_pwd']==$user_md5_pwd;
			}
			if($pwdOK)
			{

				send_system_msg($user_data['id']);
				$user_data = load_user($user_data['id'],true);
				es_session::set("user_info",$user_data);
				$GLOBALS['user_info'] = $user_data;



				//签到
				$signin_result = signin($GLOBALS['user_info']['id']);
				if($signin_result['status'])
				{
					es_session::set("signin_result", $signin_result);
				}


				$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".CLIENT_IP."',login_time= ".NOW_TIME.",group_id=".intval($user_data['group_id'])." where id =".$user_data['id']);
				require_once APP_ROOT_PATH."system/model/cart.php";
				load_cart_list(true);
			}
		}
	}
	/**
	 * 处理会员登录
	 * @param $user_name_or_email 用户名或邮箱地址
	 * @param $user_pwd 密码
	 *
	 */
	function do_login_user($user_name_or_email,$user_pwd)
	{
		$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where (user_name='".$user_name_or_email."' or email = '".$user_name_or_email."' or mobile = '".$user_name_or_email."') and is_delete = 0");

		//载入会员整合
		$integrate_code = strim(app_conf("INTEGRATE_CODE"));
		if($integrate_code!='' && $GLOBALS['request']['from'] != 'wap') //&& $GLOBALS['request']['from'] != 'wap' chenfq by add wap版本时,不做整合登陆
		{
			$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
			if(file_exists($integrate_file))
			{
				require_once $integrate_file;
				$integrate_class = $integrate_code."_integrate";
				$integrate_obj = new $integrate_class;
			}
		}
		if($integrate_obj)
		{
			$result = $integrate_obj->login($user_name_or_email,$user_pwd);

		}

		$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where (user_name='".$user_name_or_email."' or email = '".$user_name_or_email."' or mobile = '".$user_name_or_email."') and is_delete = 0");
		if(!$user_data)
		{
			$result['status'] = 0;
			$result['data'] = ACCOUNT_NO_EXIST_ERROR;
			return $result;
		}
		else
		{
			$result['user'] = $user_data;
			if($user_data['user_pwd'] != md5($user_pwd.$user_data['code'])&&$user_data['user_pwd']!=$user_pwd)
			{
				$result['status'] = 0;
				$result['data'] = ACCOUNT_PASSWORD_ERROR;
				return $result;
			}
			elseif($user_data['is_effect'] != 1)
			{
				$result['status'] = 0;
				$result['data'] = ACCOUNT_NO_VERIFY_ERROR;
				return $result;
			}
			else
			{

				if(intval($result['status'])==0) //未整合，则直接成功
				{
					$result['status'] = 1;
				}




				send_system_msg($user_data['id']);
				$user_data = load_user($user_data['id'],true);
				es_session::set("user_info",$user_data);
				$GLOBALS['user_info'] = $user_data;
				es_session::set("user_logined", true);
				$GLOBALS['user_logined'] = true;
				es_session::set("user_logined_time", NOW_TIME);

				require_once APP_ROOT_PATH."system/model/cart.php";
				load_cart_list(true);



				//签到
				$signin_result = signin($GLOBALS['user_info']['id']);
				if($signin_result['status'])
				{
					es_session::set("signin_result", $signin_result);
				}

				$GLOBALS['db']->query("update ".DB_PREFIX."user set login_ip = '".CLIENT_IP."',login_time= ".NOW_TIME.",group_id=".intval($user_data['group_id'])." where id =".$user_data['id']);

				$s_api_user_info = es_session::get("api_user_info");

				if($s_api_user_info)
				{
					$GLOBALS['db']->query("update ".DB_PREFIX."user set ".$s_api_user_info['field']." = '".$s_api_user_info['id']."' where id = ".$user_data['id']." and (".$s_api_user_info['field']." = 0 or ".$s_api_user_info['field']."='')");
					es_session::delete("api_user_info");
				}

				$result['step'] = intval($user_data["step"]);

				return $result;
			}
		}
	}
	/**
	 * 登出,返回 array('status'=>'',data=>'',msg=>'') msg存放整合接口返回的字符串
	 */
	function loginout_user()
	{
		$user_info = es_session::get("user_info");
		if(!$user_info)
		{
			return false;
		}
		else
		{
			//载入会员整合
			$integrate_code = strim(app_conf("INTEGRATE_CODE"));
			if($integrate_code!='')
			{
				$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
				if(file_exists($integrate_file))
				{
					require_once $integrate_file;
					$integrate_class = $integrate_code."_integrate";
					$integrate_obj = new $integrate_class;
				}
			}
			if($integrate_obj)
			{
				$result = $integrate_obj->logout();
			}
			if(intval($result['status'])==0)
			{
				$result['status'] = 1;
			}



			es_session::delete("user_info");
			es_session::delete("user_logined");
			es_session::delete("user_logined_time");
			return $result;
		}
	}





	/**
	 * 验证会员数据
	 */
	function check_user($field_name,$field_data,$user_data=array())
	{
		//开始数据验证
		$user_data[$field_name] = $field_data;
		$res = array('status'=>1,'info'=>'','data'=>''); //用于返回的数据
		if(trim($user_data['user_name'])==''&&$field_name=='user_name')
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	EMPTY_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}

		if(!check_username($user_data['user_name'])&&$field_name=='user_name')
		{
			$field_item['field_name'] = 'user_name';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}

		if($field_name=='user_name')
		{
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".strim($user_data['user_name'])."' and id <> ".intval($user_data['id']))==0){
				//载入会员整合
				$integrate_code = strim(app_conf("INTEGRATE_CODE"));
				if($integrate_code!='')
				{
					$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
					if(file_exists($integrate_file))
					{
						require_once $integrate_file;
						$integrate_class = $integrate_code."_integrate";
						$integrate_obj = new $integrate_class;
					}
				}
				//同步整合
				if($integrate_obj)
				{
					if ($integrate_obj->check_user(strim($user_data['user_name']))) //如存在
			        {
						$field_item['field_name'] = 'user_name';
						$field_item['error']	=	EXIST_ERROR;
						$res['status'] = 0;
						$res['data'] = $field_item;
						return $res;
			        }
				}
			}
			else{
				$field_item['field_name'] = 'user_name';
				$field_item['error']	=	EXIST_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
			}
			return $res;
		}


		if($field_name=='email')
		{
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email = '".trim($user_data['email'])."' and id <> ".intval($user_data['id']))==0){
				//载入会员整合
				$integrate_code = strim(app_conf("INTEGRATE_CODE"));
				if($integrate_code!='')
				{
					$integrate_file = APP_ROOT_PATH."system/integrate/".$integrate_code."_integrate.php";
					if(file_exists($integrate_file))
					{
						require_once $integrate_file;
						$integrate_class = $integrate_code."_integrate";
						$integrate_obj = new $integrate_class;
					}
				}
				//同步整合
				if($integrate_obj)
				{
					if ($integrate_obj->check_email(strim($user_data['email']))) //如存在
			        {
						$field_item['field_name'] = 'email';
						$field_item['error']	=	EXIST_ERROR;
						$res['status'] = 0;
						$res['data'] = $field_item;
						return $res;
			        }
				}
			}
			else{
				$field_item['field_name'] = 'email';
				$field_item['error']	=	EXIST_ERROR;
				$res['status'] = 0;
				$res['data'] = $field_item;
				return $res;
			}

		}
		if($field_name=='email'&&trim($user_data['email'])=='')
		{
			$field_item['field_name'] = 'email';
			$field_item['error']	=	EMPTY_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($field_name=='email'&&!check_email(strim($user_data['email'])))
		{
			$field_item['field_name'] = 'email';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}


		if($field_name=='mobile'&&!check_mobile(strim($user_data['mobile'])))
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	FORMAT_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}
		if($field_name=='mobile'&&$user_data['mobile']!=''&&$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where mobile = '".trim($user_data['mobile'])."' and id <> ".intval($user_data['id']))>0)
		{
			$field_item['field_name'] = 'mobile';
			$field_item['error']	=	EXIST_ERROR;
			$res['status'] = 0;
			$res['data'] = $field_item;
			return $res;
		}

		return $res;
	}




	/**
	 * 检测安全登录状态
	 * LOGIN_STATUS_TEMP: 临时登录：来源自记住密码，第三方登录
	 * LOGIN_STATUS_LOGINED：正常登录，即手机验证登录或密码登录
	 * LOGIN_STATUS_NOLOGIN：未登录，无user_info
	 *
	 * 普通业务不涉及金钱的登录状态可以凭!=LOGIN_STATUS_NOLOGIN为准
	 * 重要业务及会员中心相关页面，任==LOGIN_STATUS_LOGINED为准
	 */
	function check_save_login()
	{
		if($GLOBALS['user_info'])
		{
			if(APP_INDEX!="index")
			{
				return LOGIN_STATUS_LOGINED;
			}
			if(!$GLOBALS['user_logined'])
			{
// 				return LOGIN_STATUS_TEMP;
				return LOGIN_STATUS_LOGINED;
			}
			else
			{
				return LOGIN_STATUS_LOGINED;
			}
		}
		else
		{
			return LOGIN_STATUS_NOLOGIN;
		}
	}


	/**
	 * 为指定会员签到
	 * @param unknown_type $user_id
	 */
	function signin($user_id)
	{
		$msg = '';
		$t_begin_time = to_timespan(to_date(NOW_TIME,"Y-m-d"));  //今天开始
		$t_end_time = to_timespan(to_date(NOW_TIME,"Y-m-d"))+ (24*3600 - 1);  //今天结束
		$y_begin_time = $t_begin_time - (24*3600); //昨天开始
		$y_end_time = $t_end_time - (24*3600);  //昨天结束

		$t_sign_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_sign_log where user_id = ".$user_id." and sign_date between ".$t_begin_time." and ".$t_end_time);
		if($t_sign_data)   //已经签到过了
		{
			$result['status'] = 0;
			$result['info'] = "您已经签到过了";
		}
		else
		{
			if($GLOBALS['user_info'])
				$user_info = $GLOBALS['user_info'];
			else
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id." and is_effect = 1 and is_delete = 0");
			$y_sign_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_sign_log where user_id = ".$user_id." and sign_date between ".$y_begin_time." and ".$y_end_time);
			$total_signcount = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_sign_log where user_id = ".$user_id);
			if($y_sign_data&&$total_signcount>=2)
			{
				$point = 0;
				$score = intval(app_conf("USER_LOGIN_KEEP_SCORE"));
				$money = 0;
				$msg = ",已经连续签到".($total_signcount+1)."天了，继续努力!";
			}
			else
			{
				if(!$y_sign_data) //签到中断清空记录，未中断不清空
					$GLOBALS['db']->query("delete from ".DB_PREFIX."user_sign_log where user_id = ".$user_id);
				$point = 0;
				$score = intval(app_conf("USER_LOGIN_SCORE"));
				$money = 0;
			}
			if($point>0||$score>0||$money>0)
			{
				require_once APP_ROOT_PATH."system/model/user.php";
				$data = array("money"=>$money,"score"=>$score,"point"=>$point);
				modify_account($data,$user_id,"您在".to_date(NOW_TIME)."签到成功");
				$sign_log['user_id'] = $user_id;
				$sign_log['sign_date'] = NOW_TIME;
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_sign_log",$sign_log);
				if($point)
				$result['point'] = "+".$point."经验值";
				if($score)
				$result['score'] = "+".$score."积分";
				if($money)
				$result['money'] = "+".format_price($money);
			}

			$result['status'] = 1;
			$result['info'] = $user_info['user_name'].",欢迎您回来".$msg;
			$result['jump'] = url("index","index#index");

		}
		return $result;

	}


	/**
	 * 加载实时的会员数据
	 * @param unknown_type $user_id
	 */
	function load_user($user_id,$reload=false)
	{
	    $user_id = intval($user_id);
		$key = "user_info_".$user_id;
		if(!$reload)
		{
			static $uinfo;
			if($uinfo[$user_id])
			{
				return $uinfo[$user_id];
			}
		}
		if($GLOBALS['distribution_cfg']['CACHE_TYPE']!="File"&&!$reload)
		{
			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/user_infos/");
			$uinfo[$user_id] = $GLOBALS['cache']->get($key);
		}
		else
		{
			$uinfo[$user_id] = false;
		}

		if($uinfo[$user_id]===false)
		{

			$uinfo[$user_id] = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where is_delete = 0 and is_effect = 1 and id = ".$user_id);
			if($uinfo[$user_id])
			{
				$uinfo[$user_id]['msg_count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."msg_box where user_id = ".intval($uinfo[$user_id]['id'])." and is_read = 0 and is_delete = 0");
				if($GLOBALS['distribution_cfg']['CACHE_TYPE']!="File")
				{
					$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/user_infos/");
					$GLOBALS['cache']->set($key,$uinfo[$user_id],SESSION_TIME);
				}
			}
		}
		return $uinfo[$user_id];
	}


	/**
	 * 为会员发放系统的群发消息
	 */
	function send_system_msg($user_id)
	{
		$user_id_key = str_pad($user_id, 6,0,STR_PAD_LEFT);
		$msg_systems = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."msg_system where (end_time = 0 or end_time > ".NOW_TIME.") and user_ids = '' or (match(user_ids) against('".$user_id_key."' IN BOOLEAN MODE))");
		foreach($msg_systems as $msg)
		{
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."msg_box where user_id = ".$user_id." and data_id = ".$msg['id']." and type = 'system'")==0)
			{
				send_msg($user_id, $msg['content'], "system", $msg['id']);
			}
		}
	}
	
	



?>
