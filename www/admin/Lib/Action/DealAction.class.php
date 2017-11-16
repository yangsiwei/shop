<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

class DealAction extends CommonEnhanceAction{

    public function index(){
		//列表过滤器，生成查询Map对象
		$model = D ('DealView');
		$map = $this->_search ($model);
		$this->_list ( $model, $map );
		$this->display ();
	}
	
	public function edit(){
	    $id     = intval($_REQUEST['id']);
	    $model  = D ('DealView');
	    $result = $model->where( $this->getActionName().'.id='.$id )->find();
	    
	     
	    // 如果夺宝计划引用的商品，则状态不可以取消
	    $result['is_has_duobao'] = 0;
        $duobao_model = M("Duobao");
        $duo_bao_map['deal_id'] = $id;
        $duobao_result = $duobao_model->where($duo_bao_map)->find();
        if ($duobao_result) {
            $result['is_has_duobao'] = 1;
        }
        $result['origin_price'] = round($result['origin_price']);
        $result['current_price'] = round($result['current_price']);
        $this->assign('result', $result);
        
	    // 获取分类
	    $cate_model  = M('DealCate');
	    $cate_result = $cate_model->where('is_effect=1')->order('sort desc')->select();
	    $this->assign('cate_result', $cate_result);
	    
	    // 获取品牌
	    $brand_model  = M('Brand');
	    $brand_result = $brand_model->order('sort desc')->select();
	    $this->assign('brand_result', $brand_result);
	     
	    // 获取图集
	    $gallery_model  = M("DealGallery");
	    $gallery_result = $gallery_model->where('deal_id='.$id)->select();
	    foreach ($gallery_result as $key=>$value){
	        $img_list[] = $value['img'];
	    }
	    $this->assign('img_list', $img_list);
	     
	     
	    $this->display();
	}
	
	public function update(){
	    $model = M($this->getActionName());  //  实例化 User 对象
	    //  根据表单提交的 POST 数据创建数据对象
	    if ($model->create()) {
	        if (false===$model->save()){
	            $this->error('更新失败：'.$model->getDbError());
	        }
	         
	    }else {
	        $this->error('更新失败：'.$model->getDbError());
	    }
	     
	    //开始处理图片
	    $id = intval($_REQUEST['id']);
	    $imgs = $_REQUEST['img'];
	    $gallery_model = M('DealGallery');
	    $gallery_model->where("deal_id=".$id)->delete();
	    $imgs = $_REQUEST['img'];
	    $imgs = array_filter($imgs);
	    foreach($imgs as $k=>$v)
	    {
            $img_data['deal_id'] = $id;
            $img_data['img'] = $v;
            $img_data['sort'] = $k;
            $gallery_model->add($img_data);
	    }
	    
	    // 如果夺宝计划中有这个商品，需要修改这个夺宝计划
	    $data['name'] = $_REQUEST['name'];
	    $data['cate_id'] = $_REQUEST['cate_id'];
	    $data['description'] = $_REQUEST['description'];
	    $data['brief'] = $_REQUEST['brief'];
	    $data['icon'] = $_REQUEST['icon'];
	    $data['brand_id'] = $_REQUEST['brand_id'];
	    $data['deal_gallery'] = serialize($imgs);
	    $duobao_model = M('Duobao');
	    $duobao_model->where('deal_id='.$id)->save($data);
	    
	    $this->success('更新成功');
	}
	
	public  function  add(){
	    // 获取分类
	    $cate_model  = M('DealCate');
	    $cate_result = $cate_model->where('is_effect=1')->order('sort desc')->select();
	    $this->assign('cate_result', $cate_result);
	    
	    // 获取品牌
	    $brand_model  = M('Brand');
	    $brand_result = $brand_model->order('sort desc')->select();
	    $this->assign('brand_result', $brand_result);
	    
	    $this->display();
	}
	
	public function insert(){
	    $model = D($this->getActionName());  //  实例化 User 对象
	    //  根据表单提交的 POST 数据创建数据对象
	    if ($model->create()) {
	        $id = $model->add();
	    }else {
	        $this->error('添加失败：'.$model->getDbError());
	    }
	     
	
	    //开始处理图片
	    if ($id) {
	        $imgs = $_REQUEST['img'];
	        $gallery_model = M('DealGallery');
	        $imgs = $_REQUEST['img'];
	        foreach($imgs as $k=>$v)
	        {
	            if($v!='')
	            {
	                $img_data['deal_id'] = $id;
	                $img_data['img'] = $v;
	                $img_data['sort'] = $k;
	                $gallery_model->add($img_data);
	            }
	        }
	    }
	    $this->success('添加成功');
	}
	
	public function toogle_status()
	{
	    $id = intval($_REQUEST['id']);
	    
	   
	    
	    $field = $_REQUEST['field'];
	    $info = $id."_".$field;
	    $c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField($field);  //当前状态
	    $n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
	    
	    // 如果有效的夺宝计划引用的商品，则状态不可以取消
	    if($n_is_effect == 0){
	        $duobao_model = M("Duobao");
	        $duo_bao_map['deal_id'] = $id;
	        $duobao_result = $duobao_model->where($duo_bao_map)->find();
	        if($duobao_result){
	            $this->error('夺宝计划使用的商品，不能取消状态');
	        }
	    }
	    
	    
	    M(MODULE_NAME)->where("id=".$id)->setField($field,$n_is_effect);
	    save_log($info.l("SET_EFFECT_".$n_is_effect),1);
	    $this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;
	}
	
	public function foreverdelete() {
	    //彻底删除指定记录
	    $ajax = intval($_REQUEST['ajax']);
	    $id = $_REQUEST ['id'];
	    
	    $duobao_model = M("Duobao");
	    $duo_bao_map['deal_id'] = $id;
	    $duobao_result = $duobao_model->where($duo_bao_map)->find();
	    if($duobao_result){
	        $this->error('夺宝计划使用的商品，不能删除！');
	    }
	    
	    if (isset ( $id )) {
	        $condition = array ('id' => array ('in', explode ( ',', $id ) ) );
	        $list = M(MODULE_NAME)->where ( $condition )->delete();
	        if ($list!==false) {
	            save_log(l("FOREVER_DELETE_SUCCESS"),1);
	            $this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
	        } else {
	            save_log(l("FOREVER_DELETE_FAILED"),0);
	            $this->error (l("FOREVER_DELETE_FAILED"),$ajax);
	        }
	    } else {
	        $this->error (l("INVALID_OPERATION"),$ajax);
	    }
	}
	
}
?>