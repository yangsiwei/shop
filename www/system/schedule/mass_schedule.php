<?php

require_once(APP_ROOT_PATH.'system/libs/schedule.php');
class mass_schedule implements schedule {
	
	/**
	 * $data 格式
	 * array("id"=>群发计划表ID); 所有信息依赖于群发计划表
	 */
	public function exec($data){
		
	
			$promote_msg = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."promote_msg where send_status <> 2 and send_time <= ".NOW_TIME." and id ='".$data['id']."' limit 1");
			
			if($promote_msg)
			{
				$last_id = intval($promote_msg['last_user_id']);
				
				//开始更新为发送中
				$GLOBALS['db']->query("update ".DB_PREFIX."promote_msg set send_status = 1 where id = ".intval($promote_msg['id'])." and send_status <> 2");
				switch(intval($promote_msg['send_type']))
				{
					case 0: //群发					
						if($promote_msg['type']==0)
						{
							//短信
							$sql = "select u.id,u.mobile from ".DB_PREFIX."user as u where u.mobile <> '' ";
							$sql.=" and u.id > ".$last_id." order by u.id asc";   
							$res = $GLOBALS['db']->getRow($sql);
							$dest = $res['mobile'];
							$uid = $res['id'];
							$last_id = $res['id'];
						}
						
						if($promote_msg['type']==1)
						{
							//邮件
							$sql = "select u.id,u.email from ".DB_PREFIX."user as u where u.email <> '' ";
							$sql.=" and u.id > ".$last_id." order by u.id asc";   
							$res = $GLOBALS['db']->getRow($sql);
							$dest = $res['email'];
							$uid = $res['id'];
							$last_id = $res['id'];
						}	

						
						if($dest)
						{
							$msg_data = array();
							//开始创建一个新的发送队列
							$msg_data['dest'] = $dest;
							$msg_data['content'] = addslashes($promote_msg['content']);
							$msg_data['title'] = $promote_msg['title'];
							$msg_data['is_html'] = $promote_msg['is_html'];
							$msg_data['msg_id'] = $promote_msg['id'];
								
						
							if($promote_msg['type']==0)
							{
								send_schedule_plan("sms","短信群发",$msg_data,NOW_TIME,$dest);
							}
							else
							{
								send_schedule_plan("mail","邮件群发",$msg_data,NOW_TIME,$dest);
							}
								
							$GLOBALS['db']->query("update ".DB_PREFIX."promote_msg set last_user_id = ".intval($last_id)." where id='".$promote_msg['id']."'");
						}
						else //当没有目标可以发送时。完成发送
						{
							$GLOBALS['db']->query("update ".DB_PREFIX."promote_msg set send_status = 2,last_user_id = 0 where id = ".intval($promote_msg['id']));
						}
						
						break;					
					case 2: //自定义
						$send_define_data = trim($promote_msg['send_define_data']); //自定义的内容
						$dest_array = preg_split("/[ ,]/i",$send_define_data);
						
						$msg_data = array();
						//开始创建一个新的发送队列						
						$msg_data['content'] = addslashes($promote_msg['content']);
						$msg_data['title'] = $promote_msg['title'];
						$msg_data['is_html'] = $promote_msg['is_html'];
						$msg_data['msg_id'] = $promote_msg['id'];
						
						foreach($dest_array as $k=>$v)
						{
							$msg_data['dest'] = $v;
							if($promote_msg['type']==0)
							{
								send_schedule_plan("sms","短信群发",$msg_data,NOW_TIME,$dest);
							}
							else
							{
								send_schedule_plan("mail","邮件群发",$msg_data,NOW_TIME,$dest);
							}
						}	
						$GLOBALS['db']->query("update ".DB_PREFIX."promote_msg set send_status = 2,last_user_id = 0 where id = ".intval($promote_msg['id']));
						break;
				}
				
				
	        }
			
			
		$result['status'] = 1;
		if($last_id>0)
			$result['attemp'] = 1;
		else
			$result['attemp'] = 0;
		$result['info'] = "执行成功";
		return $result;
				
	}	
}
?>