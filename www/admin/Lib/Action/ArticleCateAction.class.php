<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class ArticleCateAction extends CommonAction{
	public function index()
	{
		$name=$this->getActionName();
		$model = M($name);
		$result = $model->order('id desc')->select();
		$this->assign("list",$result);
		$this->display ();
	}
	
	public function add()
	{
		$cate_tree = M(MODULE_NAME)->where('is_delete = 0 and pid=0 and type_id<>1 and type_id<>3 ')->findAll();
		$cate_tree = D(MODULE_NAME)->toFormatTree($cate_tree);
		$this->assign("cate_tree",$cate_tree);
		$this->assign("new_sort", M(MODULE_NAME)->where("is_delete=0")->max("sort")+1);		
		$this->display();
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		$this->display ();
	}

	public function insert() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['title']))
		{
			$this->error(L("ARTICLECATE_TITLE_EMPTY_TIP"));
		}	
		//开始同步type_id
		if($data['pid']>0)
		{
			$data['type_id'] = M("ArticleCate")->where("id=".$data['pid'])->getField("type_id");
		}
		if($data['type_id']!=1)
		{
			$data['iconfont'] = "";
		}
		// 更新数据
		$log_info = $data['title'];
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			 
			 
			 
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M(MODULE_NAME)->where("id=".$id)->getField("title");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M(MODULE_NAME)->where("id=".$id)->setField("sort",$sort);
		 
		 
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
    public function set_effect()
	{
		$id = intval($_REQUEST['id']);
		$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField("is_effect");  //当前状态
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M(MODULE_NAME)->where("id=".$id)->setField("is_effect",$n_is_effect);	
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("title");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['title']))
		{
			$this->error(L("ARTICLECATE_TITLE_EMPTY_TIP"));
		}			
		if($data['pid']>0)
		{
			$data['type_id'] = M("ArticleCate")->where("id=".$data['pid'])->getField("type_id");
		}
		if($data['type_id']!=1)
		{
			$data['iconfont'] = "";
		}
		//开始同步type_id
		$ids = D("ArticleCate")->getChildIds($data['id']);		
		M("ArticleCate")->where(array("id"=>array("in",$ids)))->setField("type_id",$data['type_id']);

		
		// 更新数据
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			 
			 
			 
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}

	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				if(M("ArticleCate")->where(array ('pid' => array ('in', explode ( ',', $id ) )))->count()>0)
				{
					$this->error (l("SUB_ARTICLECATE_EXIST"),$ajax);
				}
				if(M("Article")->where(array ('cate_id' => array ('in', explode ( ',', $id ) ) ))->count()>0)
				{
					$this->error (l("SUB_ARTICLE_EXIST"),$ajax);
				}

				$rel_data = M(MODULE_NAME)->where($condition)->findAll();			
				foreach($rel_data as $data)
				{
					$info[] = $data['title'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();

				if ($list!==false) {
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