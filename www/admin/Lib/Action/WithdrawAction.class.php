<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jobinlin
// +----------------------------------------------------------------------
class WithdrawAction extends CommonAction{
	public function index()
	{
		$res = M('Rest_withdraw')->where(array('id'=>1))->find();
	    $this->assign("res",$res);
	    $this->assign("title_name","提现限制");
		$this->display ();
	}

	public function edit(){
		$rest = D('Rest_withdraw');
		$create = $rest->create();
		if($create){
			$res = $rest->where("id = 1")->save();
			if($res){
				$this->error('修改成功');
			}else{
				$this->error('修改失败');
			}
		}
	}
}