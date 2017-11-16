<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class ReferralsAction extends CommonAction{
	public function index()
	{
		$this->assign("default_map",$condition);
		parent::index();
	}
	public function pay()
	{
		$id = intval($_REQUEST['id']);
		$res = pay_referrals($id);
		if($res)
		{
			save_log("ID:".$id.l("REFERRALS_PAY_SUCCESS"),1);
			$this->success(l("REFERRALS_PAY_SUCCESS"));
		}
		else
		{
			save_log("ID:".$id.l("REFERRALS_PAY_FAILED"),0);
			$this->error(l("REFERRALS_PAY_FAILED"));
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
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();	
		
				if ($list!==false) {
					//将已返利的数字减一
					foreach($rel_data as $data)
					{
						M("User")->setDec('referral_count',"id=".$data['rel_user_id']); // 用户返利次数减一						
					}
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