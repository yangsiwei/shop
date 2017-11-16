<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class PromoteMsgAction extends CommonAction{
	public function mail_index()
	{
		$condition['type'] = 1;
		$this->assign("default_map",$condition);
		parent::index();
	}
	public function sms_index()
	{
		$condition['type'] = 0;
		$this->assign("default_map",$condition);
		parent::index();
	}
	public function add_mail()
	{
		//输出团购城市
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		$this->assign("city_list",$city_list);
		
		//输出会员组
		$group_list = M("UserGroup")->findAll();
		$this->assign("group_list",$group_list);
		
		parent::index();
	}
	public function add_sms()
	{
		//输出团购城市
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		$this->assign("city_list",$city_list);
		
		//输出会员组
		$group_list = M("UserGroup")->findAll();
		$this->assign("group_list",$group_list);
		
		parent::index();
	}

	
	
	public function import_mail()
	{
	//开始验证
		if(intval($_REQUEST['mail_type'])==0)//普通邮件
		{
			if($_REQUEST['title']=='')
			{
				$this->success(L("MAIL_TITLE_EMPTY_TIP"),1);
			}
			if($_REQUEST['content']=='')
			{
				$this->success(l("MAIL_CONTENT_EMPTY_TIP"),1);
			}
		}
		else
		{
			if(intval($_REQUEST['deal_id'])==0||M("Deal")->where("is_delete=0 and id =".intval($_REQUEST['deal_id']))->count()==0)
			{
				$this->success(l("DEAL_ID_ERROR"),1);
			}
		}
		
		if(intval($_REQUEST['send_type'])==2)
		{
			if($_REQUEST['send_define_data']=='')
			{
				$this->success(l("SEND_DEFINE_DATE_EMPTY_TIP"),1);
			}
		}
		
		$msg_data['type'] = 1;
		$msg_data['title'] = $_REQUEST['title'];
		$msg_data['content'] = $_REQUEST['content'];
		$msg_data['is_html'] = intval($_REQUEST['is_html']);
		if($_REQUEST['mail_type']==1)
		{
			$msg_data['title'] = M("Deal")->where("is_delete=0 and id =".intval($_REQUEST['deal_id']))->getField("sub_name")."团购通知邮件";
			if($msg_data['content']=='')
			$msg_data['content'] = get_deal_mail_content(intval($_REQUEST['deal_id']));
			$msg_data['is_html'] = 1;
		}
		
		$msg_data['send_time'] = strim($_REQUEST['send_time'])==''?NOW_TIME:to_timespan($_REQUEST['send_time']);
		$msg_data['send_status'] = 0;
		$msg_data['deal_id'] = intval($_REQUEST['deal_id']);
		$msg_data['send_type'] = intval($_REQUEST['send_type']);
		switch($msg_data['send_type'])
		{
			case 0:
				//会员组
				$msg_data['send_type_id'] = intval($_REQUEST['group_id']);
				break;
			case 1:
				//订阅城市
				$msg_data['send_type_id'] = intval($_REQUEST['city_id']);
				break;
			case 2:
				//自定义号码
				$msg_data['send_type_id'] = 0;
				break;
		}
		$msg_data['send_define_data'] = $_REQUEST['send_define_data'];
		
		$page = intval($_REQUEST['page']);
		$page = $page==0?1:$page;
		$limit = (($page-1)*1000).",1000";
		$end = false;
		//开始设置
		if(intval(app_conf("EDM_ON"))==1)
		{
			set_time_limit(0);
			//edm 发送
			switch($msg_data['send_type'])
			{
				case 0:
					//会员组
					$condition = "is_effect = 1 and is_delete = 0";
					if($msg_data['send_type_id']>0)					
					$condition .= " and group_id = ".$msg_data['send_type_id'];
					$mail_list = M("User")->where($condition)->field("email")->limit($limit)->findAll();					
					$email = '';
					foreach($mail_list as $k=>$v)
					{
						$email.=$v['email'].",";
					}
					if($email!='')
					$email = substr($email,0,-1);
					if($email=='')$end = true;
					break;
				case 1:
					//订阅城市		
					$city_id = intval($msg_data['send_type_id']);	
					$condition = "is_effect = 1";
					if($city_id>0)	
					{
						require_once APP_ROOT_PATH."system/utils/child.php";
						$ids_util = new child("deal_city");											
						$ids = $ids_util->getChildIds($city_id);
						$ids[] = $city_id;					
						$ids_str = implode(",",$ids);
						$condition.=" and city_id in (".$ids_str.")";
					}					
					$mail_list = M("MailList")->where($conditon)->field("mail_address")->limit($limit)->findAll();
					$email = '';
					foreach($mail_list as $k=>$v)
					{
						$email.=$v['mail_address'].",";
					}
					if($email!='')
					$email = substr($email,0,-1);
					if($email=='')$end = true;
					break;
				case 2:
					//自定义号码
					$email = $msg_data['send_define_data'];
					$end = true;
					break;
			}
			if($email=='')
			{
				$this->success(L("EDM_INSERT_SUCCESS"),1);
			}
			
			require 'edm.php';
			$rs = send_mail($email,$msg_data['title'],app_conf("REPLY_ADDRESS"),app_conf("SHOP_TITLE"),$msg_data['content'],strim($_REQUEST['send_time']),$client,$token);
			if($rs == 'success')
			{				
				//status == 0  error() 时, 继续下页
				if($end)
				$this->success(L("EDM_INSERT_SUCCESS"),1);
				else
				$this->error(L("EDM_INSERT_SUCCESS"),1);
			}
			else 
			{
				$this->success($rs,1);
			}
		}
	}
	
	public function insert_mail()
	{		
		
		//开始验证
		if(intval($_REQUEST['mail_type'])==0)//普通邮件
		{
			if($_REQUEST['title']=='')
			{
				$this->error(L("MAIL_TITLE_EMPTY_TIP"));
			}
			if($_REQUEST['content']=='')
			{
				$this->error(l("MAIL_CONTENT_EMPTY_TIP"));
			}
		}
		else
		{
			if(intval($_REQUEST['deal_id'])==0||M("Deal")->where("is_delete=0 and id =".intval($_REQUEST['deal_id']))->count()==0)
			{
				$this->error(l("DEAL_ID_ERROR"));
			}
		}
		
		if(intval($_REQUEST['send_type'])==2)
		{
			if($_REQUEST['send_define_data']=='')
			{
				$this->error(l("SEND_DEFINE_DATE_EMPTY_TIP"));
			}
		}
		
		$msg_data['type'] = 1;
		$msg_data['title'] = $_REQUEST['title'];
		$msg_data['content'] = $_REQUEST['content'];
		$msg_data['is_html'] = intval($_REQUEST['is_html']);
		if($_REQUEST['mail_type']==1)
		{
			$msg_data['title'] = M("Deal")->where("is_delete=0 and id =".intval($_REQUEST['deal_id']))->getField("sub_name")."团购通知邮件";
			if($msg_data['content']=='')
			$msg_data['content'] = get_deal_mail_content(intval($_REQUEST['deal_id']));
			$msg_data['is_html'] = 1;
		}
		
		$msg_data['send_time'] = strim($_REQUEST['send_time'])==''?NOW_TIME:to_timespan($_REQUEST['send_time']);
		$msg_data['send_status'] = 0;
		$msg_data['deal_id'] = intval($_REQUEST['deal_id']);
		$msg_data['send_type'] = intval($_REQUEST['send_type']);
		switch($msg_data['send_type'])
		{
			case 0:
				//会员组
				$msg_data['send_type_id'] = intval($_REQUEST['group_id']);
				break;
			case 1:
				//订阅城市
				$msg_data['send_type_id'] = intval($_REQUEST['city_id']);
				break;
			case 2:
				//自定义号码
				$msg_data['send_type_id'] = 0;
				break;
		}
		$msg_data['send_define_data'] = $_REQUEST['send_define_data'];
		
		$rs = M("PromoteMsg")->add($msg_data);
		if($rs)
		{
				send_schedule_plan("mass", "群发计划", array("id"=>$rs), $msg_data['send_time']);
				save_log($msg_data['title'].L("INSERT_SUCCESS"),1);
				$this->success(L("INSERT_SUCCESS"));
		}
		else
		{			
				$this->error(L("INSERT_FAILED"));
		}
		
		
	}

	public function insert_sms()
	{		
		//开始验证
		if(intval($_REQUEST['sms_type'])==0)//普通短信
		{
			if($_REQUEST['content']=='')
			{
				$this->error(l("SMS_CONTENT_EMPTY_TIP"));
			}
		}
		else
		{
			if(intval($_REQUEST['deal_id'])==0||M("Deal")->where("is_delete=0 and id =".intval($_REQUEST['deal_id']))->count()==0)
			{
				$this->error(l("DEAL_ID_ERROR"));
			}
		}
		
		if(intval($_REQUEST['send_type'])==2)
		{
			if($_REQUEST['send_define_data']=='')
			{
				$this->error(l("SEND_DEFINE_DATE_EMPTY_TIP"));
			}
		}
		
		$msg_data['type'] = 0;
		$msg_data['content'] = $_REQUEST['content'];
		if($_REQUEST['sms_type']==1)
		{
			if($msg_data['content']=='')
			$msg_data['content'] = get_deal_sms_content(intval($_REQUEST['deal_id']));
		}
		
		$msg_data['send_time'] = strim($_REQUEST['send_time'])==''?NOW_TIME:to_timespan($_REQUEST['send_time']);
		$msg_data['send_status'] = 0;
		$msg_data['deal_id'] = intval($_REQUEST['deal_id']);
		$msg_data['send_type'] = intval($_REQUEST['send_type']);
		switch($msg_data['send_type'])
		{
			case 0:
				//会员组
				$msg_data['send_type_id'] = intval($_REQUEST['group_id']);
				break;
			case 1:
				//订阅城市
				$msg_data['send_type_id'] = intval($_REQUEST['city_id']);
				break;
			case 2:
				//自定义号码
				$msg_data['send_type_id'] = 0;
				break;
		}
		$msg_data['send_define_data'] = $_REQUEST['send_define_data'];
		$rs = M("PromoteMsg")->add($msg_data);
		if($rs)
		{
			send_schedule_plan("mass", "群发计划", array("id"=>$rs), $msg_data['send_time']);
			save_log($msg_data['content'].L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		}
		else
		{			
			$this->error(L("INSERT_FAILED"));
		}
		
	}
	public function edit_mail() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		//输出团购城市
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		$this->assign("city_list",$city_list);
		
		//输出会员组
		$group_list = M("UserGroup")->findAll();
		$this->assign("group_list",$group_list);
		
		$this->display ();
	}
	public function edit_sms() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		//输出团购城市
		$city_list = M("DealCity")->where('is_delete = 0')->findAll();
		$city_list = D("DealCity")->toFormatTree($city_list,'name');
		$this->assign("city_list",$city_list);
		
		//输出会员组
		$group_list = M("UserGroup")->findAll();
		$this->assign("group_list",$group_list);
		
		$this->display ();
	}
	
public function update_mail()
	{		
		//开始验证
		if(intval($_REQUEST['mail_type'])==0)//普通邮件
		{
			if($_REQUEST['title']=='')
			{
				$this->error(L("MAIL_TITLE_EMPTY_TIP"));
			}
			if($_REQUEST['content']=='')
			{
				$this->error(L("MAIL_CONTENT_EMPTY_TIP"));
			}
		}
		else
		{
			if(intval($_REQUEST['deal_id'])==0||M("Deal")->where("is_delete=0 and id =".intval($_REQUEST['deal_id']))->count()==0)
			{
				$this->error(l("DEAL_ID_ERROR"));
			}
		}
		
		if(intval($_REQUEST['send_type'])==2)
		{
			if($_REQUEST['send_define_data']=='')
			{
				$this->error(l("SEND_DEFINE_DATE_EMPTY_TIP"));
			}
		}
		
		
		
		$msg_data['type'] = 1;
		$msg_data['title'] = $_REQUEST['title'];
		$msg_data['content'] = $_REQUEST['content'];
		$msg_data['is_html'] = intval($_REQUEST['is_html']);
		if($_REQUEST['mail_type']==1)
		{
			$msg_data['title'] = M("Deal")->where("is_delete=0 and id =".intval($_REQUEST['deal_id']))->getField("sub_name")."团购通知邮件";
			if($msg_data['content']=='')
			$msg_data['content'] = get_deal_mail_content(intval($_REQUEST['deal_id']));
			$msg_data['is_html'] = 1;
		}
		
		$msg_data['send_time'] = strim($_REQUEST['send_time'])==''?NOW_TIME:to_timespan($_REQUEST['send_time']);
		$msg_data['deal_id'] = intval($_REQUEST['deal_id']);
		$msg_data['send_type'] = intval($_REQUEST['send_type']);
		switch($msg_data['send_type'])
		{
			case 0:
				//会员组
				$msg_data['send_type_id'] = intval($_REQUEST['group_id']);
				break;
			case 1:
				//订阅城市
				$msg_data['send_type_id'] = intval($_REQUEST['city_id']);
				break;
			case 2:
				//自定义号码
				$msg_data['send_type_id'] = 0;
				break;
		}
		$msg_data['send_define_data'] = $_REQUEST['send_define_data'];
		$msg_data['id'] = intval($_REQUEST['id']);
		if(intval($_REQUEST['resend'])==1)
		{
			$msg_data['send_status'] = 0;
			M("PromoteMsgList")->where("msg_id=".intval($msg_data['id']))->delete();
		}
		$rs = M("PromoteMsg")->save($msg_data); 
		if($rs)
		{
			send_schedule_plan("mass", "群发计划", array("id"=>$msg_data['id']), $msg_data['send_time']);
			save_log($msg_data['title'].L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		}
		else
		{			
			$this->error(L("UPDATE_FAILED"));
		}
		
	}
	
	public function update_sms()
	{		
		//开始验证
		if(intval($_REQUEST['sms_type'])==0)//普通短信
		{
			if($_REQUEST['content']=='')
			{
				$this->error(L("SMS_CONTENT_EMPTY_TIP"));
			}
		}
		else
		{
			if(intval($_REQUEST['deal_id'])==0||M("Deal")->where("is_delete=0 and id =".intval($_REQUEST['deal_id']))->count()==0)
			{
				$this->error(l("DEAL_ID_ERROR"));
			}
		}
		
		if(intval($_REQUEST['send_type'])==2)
		{
			if($_REQUEST['send_define_data']=='')
			{
				$this->error(l("SEND_DEFINE_DATE_EMPTY_TIP"));
			}
		}
		
		$msg_data['type'] = 0;
		$msg_data['content'] = $_REQUEST['content'];
		if($_REQUEST['sms_type']==1)
		{
			if($msg_data['content']=='')
			$msg_data['content'] = get_deal_sms_content(intval($_REQUEST['deal_id']));
		}
		
		$msg_data['send_time'] = strim($_REQUEST['send_time'])==''?NOW_TIME:to_timespan($_REQUEST['send_time']);
		$msg_data['deal_id'] = intval($_REQUEST['deal_id']);
		$msg_data['send_type'] = intval($_REQUEST['send_type']);
		switch($msg_data['send_type'])
		{
			case 0:
				//会员组
				$msg_data['send_type_id'] = intval($_REQUEST['group_id']);
				break;
			case 1:
				//订阅城市
				$msg_data['send_type_id'] = intval($_REQUEST['city_id']);
				break;
			case 2:
				//自定义号码
				$msg_data['send_type_id'] = 0;
				break;
		}
		$msg_data['send_define_data'] = $_REQUEST['send_define_data'];
		$msg_data['id'] = intval($_REQUEST['id']);
		if(intval($_REQUEST['resend'])==1)
		{
			$msg_data['send_status'] = 0;
			M("PromoteMsgList")->where("msg_id=".intval($msg_data['id']))->delete();
		}
		$rs = M("PromoteMsg")->save($msg_data); 
		if($rs)
		{
			send_schedule_plan("mass", "群发计划", array("id"=>$msg_data['id']), $msg_data['send_time']);
			save_log($msg_data['content'].L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		}
		else
		{			
			$this->error(L("UPDATE_FAILED"));
		}
		
	}
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();	
			
				if ($list!==false) {
					M("PromoteMsgList")->where(array ('msg_id' => array ('in', explode ( ',', $id ) ) ))->delete();
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
}
?>