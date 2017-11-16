<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class ArticleAction extends CommonAction{
	public function index()
	{
		if(strim($_REQUEST['title'])!='')
		{
			$condition['title'] = array('like','%'.strim($_REQUEST['title']).'%');			
		}
		$this->assign("default_map",$condition);
		parent::index();
	}

	public function add()
	{
		$cate_tree = M("ArticleCate")->findAll();
		$this->assign("cate_tree",$cate_tree);
		$this->assign("new_sort", M("Article")->where("is_delete=0")->max("sort")+1);
		$this->display();
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$this->assign ( 'vo', $vo );
		
		$cate_tree = M("ArticleCate")->findAll();
		$this->assign("cate_tree",$cate_tree);

		$this->display ();
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
				//删除相关预览图
//				foreach($rel_data as $data)
//				{
//					@unlink(get_real_path().$data['preview']);
//				}			
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
	
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M(MODULE_NAME)->create ();
		
		 
		if(!check_empty($data['title']))
		{
			$this->error(L("ARTICLE_TITLE_EMPTY_TIP"));
		}	
		
		if($data['cate_id']==0)
		{
			$this->error(L("ARTICLE_CATE_EMPTY_TIP"));
		}
 
		
		if(!check_empty($data['content']))
		{
			$this->error(L("ARTICLE_CONTENT_EMPTY_TIP"));
		}	
		
		// 更新数据
		$data['create_time'] = NOW_TIME;
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			$this->success('添加成功');
		} else {
			//错误提示
			$this->error('添加失败');
		}
	}	
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		//开始验证有效性
		if(!check_empty($data['title']))
		{
			$this->error(L("ARTICLE_TITLE_EMPTY_TIP"));
		}	
		
		if($data['cate_id']==0)
		{
			$this->error(L("ARTICLE_CATE_EMPTY_TIP"));
		}
 

		if(!check_empty($data['content']))
		{
			$this->error(L("ARTICLE_CONTENT_EMPTY_TIP"));
		}			
		
		// 更新数据
		$data['update_time'] = NOW_TIME;
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			//成功提示
			$this->success('编辑成功');
		} else {
			//错误提示
			$this->error('编辑失败');
		}
	}
	
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M("Article")->where("id=".$id)->getField("title");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M("Article")->where("id=".$id)->setField("sort",$sort);
		save_log($log_info.l("SORT_SUCCESS"),1);
		 
		$this->success(l("SORT_SUCCESS"),1);
	}
	public function set_effect()
	{
		$id = intval($_REQUEST['id']);
		 
		$info = M(MODULE_NAME)->where("id=".$id)->getField("title");
		$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField("is_effect");  //当前状态
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M(MODULE_NAME)->where("id=".$id)->setField("is_effect",$n_is_effect);	
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
}
?>