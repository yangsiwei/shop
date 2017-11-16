<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

class DuobaoItemAction extends CommonEnhanceAction{
    public function index(){
    	
    	if(!isset($_REQUEST['is_success']))$_REQUEST['is_success'] = -1;
    	if(!isset($_REQUEST['has_lottery']))$_REQUEST['has_lottery'] = -1;
    	if(!isset($_REQUEST['prepare_lottery']))$_REQUEST['prepare_lottery'] = -1;
    	if(!isset($_REQUEST['user_is_robot']))$_REQUEST['user_is_robot'] = -1;
        //列表过滤器，生成查询Map对象
        $model = D ('DuobaoItemView');
        $map = $this->_search ($model);
        
        $is_success = intval($_REQUEST['is_success']);
        if($is_success==0)
        {
        	$map['success_time'] = 0;
        }
        elseif($is_success==1)
        {
        	$map['progress'] = 100;
        }
        
        $has_lottery = intval($_REQUEST['has_lottery']);
        if($has_lottery==-1)
        {
        	unset($map['has_lottery']);
        }
        
        $prepare_lottery = intval($_REQUEST['prepare_lottery']);
        if($prepare_lottery==0)
        {
        	$map['lottery_time'] = 0;
        }
        elseif($prepare_lottery==1)
        {
        	$map['lottery_time'] = array("gt",0);
        }
        
        
        $is_robot = intval($_REQUEST['user_is_robot']);
        if($is_robot==-1)
        {
            unset($map['User.is_robot']);
        }else{
            $map['User.is_robot']=intval($_REQUEST['user_is_robot']);
        }
        
        foreach ($map as $key=>$value){
            if(stripos($key, 'create_time')){
                $k = str_replace('create_time', 'lottery_time', $key);
                $map[$k] = $value;
                unset($map[$key]);
            }
            
        }
        
        if ( intval($_REQUEST['classify']) > 0 ) {
            if ($_REQUEST['classify'] == 100) {
                $map['DuobaoItem.unit_price'] = 100;
                $map['DuobaoItem.min_buy'] = 1;
            }else if ($_REQUEST['classify'] == 10) {
                $map['DuobaoItem.unit_price'] = 10;
                $map['DuobaoItem.min_buy'] = 1;
            }else{
                $map['DuobaoItem.unit_price'] = 1;
                $map['DuobaoItem.min_buy'] = 1;
            }
        }
        
        if ( intval($_REQUEST['special_classify']) > 0 ) {
        
            if ($_REQUEST['special_classify'] == 1) {
                $map['DuobaoItem.is_coupons'] = 1;
            }
        
            if ($_REQUEST['special_classify'] == 2) {
                $map['DuobaoItem.is_number_choose'] = 1;
            }
        
            if($_REQUEST['special_classify'] == 3){
                $map['DuobaoItem.is_pk'] = 1;
            }
        }
        
        $this->_list( $model, $map );
        $this->display ();
    }
    
    public function foreverdelete(){
		set_time_limit(0);
        $id = $_REQUEST ['id'];
        $force = intval($_REQUEST['force']);
        $DuobaoItem_model = M('DuobaoItem');
		$map['id']  = array('in',$id);
        $item_result = $DuobaoItem_model->where($map)->select();
		foreach($item_result as $key=>$value){
			if ($value['has_lottery'] !=1&&$value['current_buy']>0&&$force==0) {
				$this->error('未开奖的夺宝活动，不能删除');
			}
		}
       foreach($item_result as $key=>$value){
		   require_once APP_ROOT_PATH."system/model/duobao.php";
		   $duobao = new duobao($value['id']);
		   $duobao->del_duobao();
			
		   save_log($duobao->duobao_item['name'].$duobao->duobao_item['id']."期".l("DELETE_SUCCESS"),1);
	   }
       $this->success (l("DELETE_SUCCESS"),1);
    }

    /**
     * 机器人限时凑单
     */
    public function prepare_lottery()
    {
    	set_time_limit(0);
    	$id = intval($_REQUEST['id']);
    	require_once APP_ROOT_PATH."system/model/duobao.php";
    	$duobao = new duobao($id);
    	if($duobao->duobao_item['current_buy']<$duobao->duobao_item['max_buy'])
    	{
    		//凑单
    		require_once APP_ROOT_PATH."system/model/robot.php";
    		$result = robot::set_robot_schedule(5, $id);
    		if($result['status']==1)
    		{
    			$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set robot_is_db = 1,robot_end_time=5 where id = ".$id);
    			$this->success($result['info'],1);
    		}
    		else
    		{
    			$this->error($result['info'],1);
    		}
    	}   	
    }
    /**
     * 机器人设置凑单
     */
    public function prepare_lottery_1()
    {
    	set_time_limit(0);
    	$id = intval($_REQUEST['id']);
    	require_once APP_ROOT_PATH."system/model/duobao.php";
    	$duobao = new duobao($id);
    	if($duobao->duobao_item['current_buy']<$duobao->duobao_item['max_buy'])
    	{
    		//凑单
    		require_once APP_ROOT_PATH."system/model/robot.php";
    		
    		$duobao_plan = M("Duobao")->getById($duobao->duobao_item['duobao_id']);
    		$result = robot::set_robot_schedule_by_cfg(
    				array(
    						"robot_buy_min_time"=>$duobao_plan['robot_buy_min_time'],
    						"robot_buy_max_time"=>$duobao_plan['robot_buy_max_time'],
    						"robot_buy_min"=>$duobao_plan['robot_buy_min'],
    						"robot_buy_max"=>$duobao_plan['robot_buy_max']
    				)
    				, $id);
    		if($result['status']==1)
    		{
    			//$GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set robot_is_db = 1,robot_end_time=5 where id = ".$id);
    			$this->success($result['info'],1);
    		}
    		else
    		{
    			$this->error($result['info'],1);
    		}
    	}
    }
    
    public function set_sort()
    {
        $id = intval($_REQUEST['id']);
        $sort = intval($_REQUEST['sort']);
    
        $log_info = M(MODULE_NAME)->where("id=".$id)->getField("name");
        if(!check_sort($sort))
        {
            $this->error(l("SORT_FAILED"),1);
        }
        M(MODULE_NAME)->where("id=".$id)->setField("sort",$sort);
        save_log($log_info.l("SORT_SUCCESS"),1);
    
        $this->success(l("SORT_SUCCESS"),1);
    }
    
    public function draw_lottery()
    {
    	$id = intval($_REQUEST['id']);
    	$lottery_sn = intval($_REQUEST['lottery_sn']);
    	$lottery_sn = 100000000 + $lottery_sn;
    	require_once APP_ROOT_PATH."system/model/duobao.php";
    	$duobao_item = new duobao($id);
    	if($duobao_item->duobao_item['current_buy']<$duobao_item->duobao_item['max_buy'])
    	{
    		$this->error('人需未满，无法开奖',1);
    	}

    	if($duobao_item->duobao_item['fair_type']=="yydb")
    	{
    		$duobao_item->draw_lottery_yydb($lottery_sn);
    	}
    	else
    	{
    		//人工开奖
    		$fair_type = $duobao_item->duobao_item['fair_type'];
    		$cname = $fair_type."_fair_fetch";
    		 
    		$sql = "select * from ".DB_PREFIX."fair_fetch where fair_type = '".$duobao_item->duobao_item['fair_type']."' and period = '".$duobao_item->duobao_item['fair_period']."'";
    		$fair_period = $GLOBALS['db']->getRow($sql);
    		if($fair_period['number'])
    		{
    			//当前期已开奖
    			$duobao_item->draw_lottery($fair_period['period'], $fair_period['number']);
    		}
    		else
    		{
    			if($duobao_item->duobao_item['fair_period']=="000000")//未指定
    			{
    				//采集最新的开奖
    				require_once APP_ROOT_PATH."system/fair_fetch/".$cname.".php";
    				$fetch_obj = new $cname;
    				$fetch_obj->createData();
    				$fetch_infos = $fetch_obj->collectData();  //开奖并获取开奖的信息
    				if($fetch_infos)
    					$fair_period = $fetch_infos[count($fetch_infos)-1];
    		
    				if($fair_period&&$fair_period['number'])
    				{
    					$duobao_item->draw_lottery($fair_period['period'], $fair_period['number']);
    				}
    				else
    				{
    					$duobao_item->draw_lottery($duobao_item->duobao_item['fair_period'], DEFAULT_LOTTERY);
    				}
    			}
    			else
    			{
    				$duobao_item->draw_lottery($duobao_item->duobao_item['fair_period'], DEFAULT_LOTTERY);
    			}
    		}
    	}
    	
    	
    	$this->success('开奖成功',1);   	
    	
    }
    //添加晒单
    public function robot_share_add() {
        $duobao_item_id = intval($_REQUEST ['id']);
        $condition['id'] = $duobao_item_id;
        $vo = M(MODULE_NAME)->where($condition)->find();
        if($vo['share_id']){
            $this->redirect("robot_share_view&id=".$duobao_item_id);//已经晒过单的，通过url访问时，直接跳转到查看晒单页
            
            $share = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."share where id=".$vo['share_id']);
            $share['image_list']=unserialize($share['image_list']);
            foreach ($share['image_list'] as $k => $v){
                $img_list[]=$v['path'];
            }
            $this->assign ( 'share', $share );
            $this->assign ( 'img_list', $img_list );
        }
        $this->assign ( 'vo', $vo );
        
        $this->display ();
    }
    
    public function robot_share_edit(){
        $duobao_item_id = intval($_REQUEST['duobao_item_id']);
        $user_id = intval($_REQUEST['user_id']);
        $user_name = $_REQUEST['user_name'];
        $share_data['title']=strim($_REQUEST['title']);
        $share_data['content']=strim($_REQUEST['content']);
        $share_data['is_effect']=intval($_REQUEST['is_effect']);
        $share_data['is_top']=intval($_REQUEST['is_top']);
        $share_data['is_recommend']=intval($_REQUEST['is_recommend']);
        $share_data['is_index']=intval($_REQUEST['is_index']);
        $imgs = $_REQUEST['img'];
        
        foreach($imgs as $k=>$v)
        {
            if($v!='')
            {
                $ShareImage = M('ShareImage');
                $share_image =array();
                $share_image['name'] =  basename(APP_ROOT_PATH.$v);
                $share_image['filesize'] =  filesize(APP_ROOT_PATH.$v);
                $share_image['create_time'] =  NOW_TIME;
                $share_image['user_id'] =  $user_id;
                $share_image['user_name'] =  $user_name;
                $share_image['path'] = imagecropper($v,255,255 );
                
                $share_image['o_path'] =  $v;
                $img_info = getimagesize($v);
                $share_image['width'] =  $img_info['0'];
                $share_image['height'] = $img_info['1'];
                
                $ShareImage_id=$ShareImage->add($share_image);
                $img_ids[]=intval($ShareImage_id);
            }
        }
        $duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$duobao_item_id." and luck_user_id =".$user_id);
        $duobao_item['user_id']=$user_id;
        
        require_once APP_ROOT_PATH.'/system/model/duobao.php';
        $share_obj = new duobao($duobao_item_id);
        
        $_REQUEST['share_image_ids']=$img_ids;
        $attach_list = get_share_attach_list();
        $id = $share_obj->insert_robot_share($duobao_item,  $share_data, $attach_list);
        
        $this->assign("jumpUrl",u(MODULE_NAME."/robot_share_view",array('id'=>$duobao_item_id)));
        $this->ajaxReturn(1,"添加成功",1)	;
    }
    //查看晒单，编辑晒单
    public function robot_share_view() {
        
        $duobao_item_id = intval($_REQUEST ['id']);
        $condition['id'] = $duobao_item_id;
        $vo = M(MODULE_NAME)->where($condition)->find();
        if($vo['share_id']){
            $share = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."share where id=".$vo['share_id']);
            $share['image_list']=unserialize($share['image_list']);
            foreach ($share['image_list'] as $k => $v){
                $img_list[]=$v['o_path'];
            }
             
            $this->assign ( 'share', $share );
            $this->assign ( 'img_list', $img_list );
        }
        $this->assign ( 'vo', $vo );
        
        $this->display ();
    }
    public function robot_share_update() {
        $user_id = intval($_REQUEST['user_id']);
        $user_name = strim($_REQUEST['user_name']);
        $share_data['title']=strim($_REQUEST['title']);
        $share_data['content']=strim($_REQUEST['content']);
        $share_data['is_effect']=intval($_REQUEST['is_effect']);
        $share_data['is_top']=intval($_REQUEST['is_top']);
        $share_data['is_recommend']=intval($_REQUEST['is_recommend']);
        $share_data['is_index']=intval($_REQUEST['is_index']);
        
        //删除上一次的晒单图片
        $share_id=intval($_REQUEST['share_id']);
        $GLOBALS['db']->query("delete FROM ".DB_PREFIX."share_image where share_id = ".$share_id);
        
        $imgs = $_REQUEST['img'];
        foreach($imgs as $k=>$v)
        {
            if($v!='')
            {
                $ShareImage = M('ShareImage');
                $share_image =array();
                $share_image['name'] =  basename(APP_ROOT_PATH.$v);
                $share_image['filesize'] =  filesize(APP_ROOT_PATH.$v);
                $share_image['create_time'] =  NOW_TIME;
                $share_image['user_id'] =  $user_id;
                $share_image['user_name'] =  $user_name;
                $share_image['path'] = imagecropper($v,255,255 );
                $share_image['o_path'] =  $v;
        
                $img_info = getimagesize($v);
                $share_image['width'] =  $img_info['0'];
                $share_image['height'] = $img_info['1'];
                $GLOBALS['db']->autoExecute(DB_PREFIX."share_image",$share_image);
                $img_ids[]=intval($GLOBALS['db']->insert_id());
            }
        }
        $_REQUEST['share_image_ids']=$img_ids;
        $attach_list = get_share_attach_list();
        //更新图片缓存
        foreach($attach_list as $attach)
        {
            //插入图片
            $GLOBALS['db']->query("update ".DB_PREFIX."share_image set share_id = ".$share_id." where id = ".$attach['id']);
        }
        
        //缓存图集
        $img_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."share_image where share_id = '".$share_id."' order by id ");
        $share_data['image_list'] = serialize($img_list);
        
        $GLOBALS['db']->autoExecute(DB_PREFIX."share",$share_data,"UPDATE"," id=".$share_id);
        
        $this->success('修改成功');
    }
   
}