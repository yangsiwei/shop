<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

class DuobaoAction extends CommonEnhanceAction{
    public function index(){
        //列表过滤器，生成查询Map对象
        $model = D ('DuobaoView');
        
        $map = $this->_search ($model);
        
        if ( intval($_REQUEST['classify']) > 0 ) {
            if ($_REQUEST['classify'] == 100) {
                $map['Duobao.unit_price'] = 100;
                $map['Duobao.min_buy'] = 1;
            }else if ($_REQUEST['classify'] == 10) {
                $map['Duobao.unit_price'] = 10;
                $map['Duobao.min_buy'] = 1;
            }else{
                $map['Duobao.unit_price'] = 1;
                $map['Duobao.min_buy'] = 1;
            }
        }
        
        if ( intval($_REQUEST['special_classify']) > 0 ) {
            
            if ($_REQUEST['special_classify'] == 1) {
                $map['Duobao.is_coupons'] = 1;
            }
            
            if ($_REQUEST['special_classify'] == 2) {
                $map['Duobao.is_number_choose'] = 1;
            }
            
            if($_REQUEST['special_classify'] == 3){
                $map['Duobao.is_pk'] = 1;
            }
            if($_REQUEST['special_classify'] == 4){
                $map['Duobao.is_five'] = 1;
            }
        }
         
        $this->_list ( $model, $map );
        $this->display ();
    }
    
    
    public function toogle_status()
    {
    	$id = intval($_REQUEST['id']);
    	$ajax = intval($_REQUEST['ajax']);
    	$field = $_REQUEST['field'];
    	$info = $id."_".$field;
    	$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField($field);  //当前状态
    	$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
    	    	
    	
    	M(MODULE_NAME)->where("id=".$id)->setField($field,$n_is_effect);
    	if($n_is_effect==1)
    	{
    		$rs = M("DuobaoItem")->where("progress<100 and duobao_id = ".$id)->count();
    		if(empty($rs))
    		{
    			require_once APP_ROOT_PATH."system/model/duobao.php";
    			duobao::new_duobao($id);
    		}
    		
    	}
    	
    	save_log($info.l("SET_EFFECT_".$n_is_effect),1);
    	$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;
    }
    
    public function edit(){
        $id     = intval($_REQUEST['id']);
        $model  = D ('DuobaoView');
        $result = $model->where( $this->getActionName().'.id='.$id )->find();
       

        $result['total_buy_price'] = round( $result['total_buy_price'], 2 );
        if($result['unit_price']==100&&$result['min_buy']==1)
        {
            $result['buy_type'] = 100;
        }
        elseif($result['unit_price']==10 || $result['min_buy']==10)
        {
            $result['buy_type'] = 10;
        }
        else
        {
            $result['buy_type'] = 1;
        }


//         $robot_list = unserialize($result['robot_list']);
        
//         $this->assign('robot_list', $robot_list);
        $this->assign('result', $result);
        
        
        // 获取分类
        $cate_model  = M('DealCate');
        $cate_result = $cate_model->where('is_effect=1')->select();
        $this->assign('cate_result', $cate_result);
    
        // 获取品牌
        $barnd_model = M('Brand');
        $brand_result = $barnd_model->select();
        $this->assign('brand_result', $brand_result);
        
        // 获取图集
        $gallery_model  = M("DealGallery");
        $gallery_result = $gallery_model->where('deal_id='.$id)->select();
        foreach ($gallery_result as $key=>$value){
            $img_list[] = $value['img'];
        }
        $img_list = unserialize($result['deal_gallery']);
        $this->assign('img_list', $img_list);
    
    
        $this->display();
    }
    
    public function update(){
        if(intval($_REQUEST['max_schedule'] <=0) ){
            $this->error('最大举办期数必须大于0');
        }
           
        if(intval($_REQUEST['user_max_buy']) > intval($_REQUEST['max_buy'])){
            $this->error('限购次数不能大于总需人次');
        }
        
        if($_REQUEST['buy_type'] == 10 && intval($_REQUEST['user_max_buy'])%10 != 0){
            $this->error('限购次数应为10的倍数');
        }
    
        if(intval($_REQUEST['robot_is_db'])==1)
        {
        	 
        	if(intval($_REQUEST['robot_type'])==0)
        	{
        		//计时
        		if(intval($_REQUEST['robot_end_time']) < 5 ){
        			$this->error('夺宝时长不能低于5分钟');
        		}
        		$_REQUEST['robot_buy_min_time'] = 0;
        		$_REQUEST['robot_buy_max_time'] = 0;
        		$_REQUEST['robot_buy_min'] = 0;
        		$_REQUEST['robot_buy_max'] = 0;
        	}
        	else
        	{
        		//按频率
        
        		if(intval($_REQUEST['robot_buy_min_time'])<=0)
        		{
        			$this->error('最小下单间隔不能小于0');
        		}
        		if(intval($_REQUEST['robot_buy_min'])<=0)
        		{
        			$this->error('最小下单量不能小于0');
        		}
        
        		if(intval($_REQUEST['robot_buy_min_time'])>intval($_REQUEST['robot_buy_max_time']))
        		{
        			$this->error('最小下单间隔不能大于最大间隔');
        		}
        		if(intval($_REQUEST['robot_buy_min'])>intval($_REQUEST['robot_buy_max']))
        		{
        			$this->error('最小下单量不能大于最大量');
        		}
        
        		$_REQUEST['robot_end_time'] = 0;
        	}
        	 
        }
        else
        {
        	$_REQUEST['robot_is_lottery'] = 0;
        	$_REQUEST['robot_type'] = 0;
        	$_REQUEST['robot_buy_min_time'] = 0;
        	$_REQUEST['robot_buy_max_time'] = 0;
        	$_REQUEST['robot_buy_min'] = 0;
        	$_REQUEST['robot_buy_max'] = 0;
        	$_REQUEST['robot_end_time'] = 0;
        }
        if($_REQUEST['spectial_area']==2){
            $_REQUEST['is_pk']=1;
            $_REQUEST['is_recomend']=0;
            $_REQUEST['is_number_choose']=0;
            $_REQUEST['is_coupons']=0;
            $_REQUEST['pk_min_number']=intval($_REQUEST['pk_min_number']);
            if(intval($_REQUEST['pk_min_number'])<1){
                $this->error("最小购买人数不得小于1");
            }else if(intval($_REQUEST['pk_min_number'])>intval($_REQUEST['max_buy'])){
                $this->error("最小购买人数不得大于总人次");
            }
        }else if($_REQUEST['spectial_area']==1){
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_recomend']=0;
            $_REQUEST['is_number_choose']=1;
            $_REQUEST['is_coupons']=0;
        }else if($_REQUEST['spectial_area']==3){
            $_REQUEST['is_coupons']=1;
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_recomend']=0;
            $_REQUEST['is_number_choose']=0;
        }else if($_REQUEST['spectial_area']==4){
            $_REQUEST['is_five']=1;
            $_REQUEST['is_coupons']=0;
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_recomend']=0;
            $_REQUEST['is_number_choose']=0;
        }else{
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_number_choose']=0;
            $_REQUEST['is_coupons']=0;
             $_REQUEST['is_five']=0;
        }

        if(intval($_REQUEST['robot_is_db'])==1&&M("User")->where("is_robot=1")->count()<5)
        {
        	$this->error('机器人数量低于5个，不能使用机器人功能');
        }
        
        //处理buy_type
        if($_REQUEST['buy_type']==100)
        {
            $_REQUEST['unit_price'] = 100;
            $_REQUEST['min_buy'] = 1;
        }
        elseif($_REQUEST['buy_type']==10)
        {
            $_REQUEST['unit_price'] = 10;
            $_REQUEST['min_buy'] = 1;
        }
        else
        {
            $_REQUEST['unit_price'] = 1;
            $_REQUEST['min_buy'] = 1;
        }
        unset($_REQUEST['buy_type']);
        
        $_REQUEST['total_buy_price'] = round($_REQUEST['total_buy_price'], 2);
        $_REQUEST['is_total_buy']    = intval( $_REQUEST['is_total_buy'] );
        if ( $_REQUEST['is_total_buy'] == 1 && $_REQUEST['total_buy_price'] <= 0 ) {
            $this->error('直购价格必须大于0');
        }
        
        $deal_id = intval($_REQUEST['deal_id']);
        // 获取商品名称
        $deal_model = M('Deal');
        $result_deal = $deal_model->field( array( 'id'=>'deal_id', 'name','cate_id','description','brief','icon','brand_id' ) )->where("id=".$deal_id)->find();
        $_REQUEST['max_buy'] = $_REQUEST['max_buy'] * $_REQUEST['min_buy'];
        
        $data = array_merge($result_deal, $_REQUEST);
        //开始处理图片
        $gallery_model = M('DealGallery');
        $gallery_result = $gallery_model->field('img')->where('deal_id='.$deal_id)->select();
        foreach ($gallery_result as $val){
            $img_list[] = $val['img'];
        }
        $data['deal_gallery'] = serialize($img_list);
        
        $model = D($this->getActionName());
        if( false === $model->data($data)->save() ){
        	
            $this->error('更新失败');
        }
        $is_pk=$_REQUEST['is_pk'];
        if(!$is_pk){
            require_once APP_ROOT_PATH."system/model/duobao.php";
            duobao::new_duobao($data['id']);
        }
        $this->success('更新成功');
         
    }
    
    public  function  add(){
        // 获取分类
        $cate_model  = M('DealCate');
        $cate_result = $cate_model->select();
        $this->assign('cate_result', $cate_result);
        $this->display();
    }
    
    public function insert(){
        
        if(intval($_REQUEST['max_schedule']) <=0 ){
            $this->error('最大举办期数需要大于0');
        }
        if(intval($_REQUEST['user_max_buy']) > intval($_REQUEST['max_buy'])){
            $this->error('限购次数不能大于总需人次');
        }
        
        if($_REQUEST['buy_type'] == 10 &&$_REQUEST['min_buy']==10&&intval($_REQUEST['user_max_buy'])%10 != 0){
            $this->error('限购次数应为10的倍数');
        }
        
        if(intval($_REQUEST['robot_is_db'])==1)
        {
        	
        	if(intval($_REQUEST['robot_type'])==0)
        	{
        		//计时
        		if(intval($_REQUEST['robot_end_time']) < 5 ){
        			$this->error('夺宝时长不能低于5分钟');
        		}
        		$_REQUEST['robot_buy_min_time'] = 0;
        		$_REQUEST['robot_buy_max_time'] = 0;
        		$_REQUEST['robot_buy_min'] = 0;
        		$_REQUEST['robot_buy_max'] = 0;
        	}
        	else
        	{
        		//按频率
        		
        		if(intval($_REQUEST['robot_buy_min_time'])<=0)
        		{
        			$this->error('最小下单间隔不能小于0');
        		}
        		if(intval($_REQUEST['robot_buy_min'])<=0)
        		{
        			$this->error('最小下单量不能小于0');
        		}
        		
        		if(intval($_REQUEST['robot_buy_min_time'])>intval($_REQUEST['robot_buy_max_time']))
        		{
        			$this->error('最小下单间隔不能大于最大间隔');
        		}
        		if(intval($_REQUEST['robot_buy_min'])>intval($_REQUEST['robot_buy_max']))
        		{
        			$this->error('最小下单量不能大于最大量');
        		}

        		$_REQUEST['robot_end_time'] = 0;
        	}
        	
        }
        else
        {
        	$_REQUEST['robot_is_lottery'] = 0;
        	$_REQUEST['robot_type'] = 0;
        	$_REQUEST['robot_buy_min_time'] = 0;
        	$_REQUEST['robot_buy_max_time'] = 0;
        	$_REQUEST['robot_buy_min'] = 0;
        	$_REQUEST['robot_buy_max'] = 0;
        	$_REQUEST['robot_end_time'] = 0;
        }
        
        
        if(intval($_REQUEST['robot_is_db'])==1&&M("User")->where("is_robot=1")->count()<5)
        {
        	$this->error('机器人数量低于5个，不能使用机器人功能');
        }
        
        $_REQUEST['total_buy_price'] = round($_REQUEST['total_buy_price'], 2);
        $_REQUEST['is_total_buy']    = intval( $_REQUEST['is_total_buy'] );
        if ( $_REQUEST['is_total_buy'] == 1 && $_REQUEST['total_buy_price'] <= 0 ) {
            $this->error('直购价格必须大于0');
        }
        

        //处理buy_type
        if($_REQUEST['buy_type']==100)
        {
        	$_REQUEST['unit_price'] = 100;
        	$_REQUEST['min_buy'] = 1;
        }
        elseif($_REQUEST['buy_type']==10)
        {
        	$_REQUEST['unit_price'] = 10;
        	$_REQUEST['min_buy'] = 1;
        }
        else
        {
        	$_REQUEST['unit_price'] = 1;
        	$_REQUEST['min_buy'] = 1;
        }
        unset($_REQUEST['buy_type']);
        
     if($_REQUEST['spectial_area']==2){
            $_REQUEST['is_pk']=1;
            $_REQUEST['is_number_choose']=0;
            $_REQUEST['is_coupons']=0;
            $_REQUEST['is_recomend']=0;

            $_REQUEST['pk_min_number']=intval($_REQUEST['pk_min_number']);
            if(intval($_REQUEST['pk_min_number'])<1){
                $this->error("最小购买人数不得小于1");
            }else if(intval($_REQUEST['pk_min_number'])>intval($_REQUEST['max_buy'])){
                $this->error("最小购买人数不得大于总人次");
            }
        }else if($_REQUEST['spectial_area']==1){
            $_REQUEST['user_max_buy']=0;
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_recomend']=0;
            $_REQUEST['is_coupons']=0;
            $_REQUEST['is_number_choose']=1;
        }else if($_REQUEST['spectial_area']==3){
            $_REQUEST['is_coupons']=1;
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_recomend']=0;
            $_REQUEST['is_number_choose']=0;
        }else if($_REQUEST['spectial_area']==4){
            $_REQUEST['is_five']=1;
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_recomend']=0;
            $_REQUEST['is_number_choose']=0;
            $_REQUEST['is_coupons']=0;
        }else{
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_coupons']=0;
            $_REQUEST['is_number_choose']=0;
        }
        $deal_id = intval($_REQUEST['deal_id']);
        // 获取商品名称
        $deal_model = M('Deal');
        $result_deal = $deal_model->field( array( 'id'=>'deal_id', 'name','cate_id','description','brief','icon','brand_id','origin_price' ) )->where("id=".$deal_id)->find(); 
        $_REQUEST['max_buy'] = $_REQUEST['max_buy'] * $_REQUEST['min_buy'];
        
//         // 生成机器人
//         if ($_REQUEST['robot_create_type'] == 1 && $_REQUEST['robot_count'] >0 ) {
//             // 查找用户名进行生成
//             $user_result = M()->query("SELECT DISTINCT(user_name) FROM ".DB_PREFIX."user WHERE is_robot=1 ORDER BY RAND() LIMIT ".$_REQUEST['robot_count']."");
//             include_once(APP_ROOT_PATH . 'system/model/robot.php');
//             $robot_list = array();
//             foreach ($user_result as $key=>$value){
//                 $robot = new robot($value['user_name']);
//                 $robot_list[$key]['id'] = $robot->id;
//                 $robot_list[$key]['user_name'] = $robot->user_name;
//                 $robot_list[$key]['ip'] = $robot->ip;
//             }
//             $_REQUEST['robot_count'] = count($user_result);
//             $_REQUEST['robot_list'] = serialize($robot_list);
//         }elseif ($_REQUEST['robot_create_type'] == 2 && $_REQUEST['robot_count'] >0){
//             $_REQUEST['robot'] = array_filter($_REQUEST['robot']);
//             if($_REQUEST['robot']){
//                 include_once(APP_ROOT_PATH . 'system/model/robot.php');
//                 $robot_list = array();
//                 foreach ($_REQUEST['robot'] as $key=>$value){
//                     if ($value) {
//                         $robot = new robot($value);
//                         $robot_list[$key]['id'] = $robot->id;
//                         $robot_list[$key]['user_name'] = $robot->user_name;
//                         $robot_list[$key]['ip'] = $robot->ip;
//                     }
//                 }
//                 $_REQUEST['robot_list'] = serialize($robot_list);
//             }
//         }
        
        $data = array_merge($result_deal, $_REQUEST);
        
        //开始处理图片
    //     $gallery_model = M('DealGallery');
    //     $gallery_result = $gallery_model->field('img')->where('deal_id='.$deal_id)->select();
    //     foreach ($gallery_result as $val){
    //         $img_list[] = $val['img'];
    //     }
    //     $data['deal_gallery'] = serialize($img_list);
    //     $is_pk=$_REQUEST['is_pk'];
    //     $model = D($this->getActionName());
    //     $id = $model->data($data)->add();
    //     if ($id) {
    //     	if(!$is_pk){
    //             require_once APP_ROOT_PATH."system/model/duobao.php";
    //             $duobao_item = duobao::new_duobao($id);
    //         }
    //         $this->success('添加成功');
    //     }
    //     else{
    //         $this->error('添加失败');
    //     }
        
    // }
        $gallery_model = M('DealGallery');
        $gallery_result = $gallery_model->field('img')->where('deal_id='.$deal_id)->select();
        foreach ($gallery_result as $val){
            $img_list[] = $val['img'];
        }
        $data['deal_gallery'] = serialize($img_list);
        $is_pk=$_REQUEST['is_pk'];
        $is_five =$_REQUEST['is_five'];
        if ( $is_five==1){
             $_REQUEST['is_five']=1;
            $_REQUEST['total_buy_price']=$_REQUEST['max_buy'] = $_REQUEST['max_buy'] * $_REQUEST['min_buy']*5;
            $data = array_merge($result_deal, $_REQUEST);

            //开始处理图片
            $gallery_model = M('DealGallery');
            $gallery_result = $gallery_model->field('img')->where('deal_id='.$deal_id)->select();
            foreach ($gallery_result as $val){
                $img_list[] = $val['img'];
            }
            $data['deal_gallery'] = serialize($img_list);
            $model =M("Duobao");
            $id = $model->data($data)->add();
            if ($id) {
                require_once APP_ROOT_PATH . "system/model/quintupling.php";
                $duobao_item = quintupling::new_duobao($id);
                    $this->success('五倍开奖添加成功');
                } else {
                    $this->error('五倍开奖添加失败');

            }

        }
        if ($is_five !==1) {
            $data['is_five']=$_REQUEST['is_five']=0;
            $model = D($this->getActionName());
            $id = $model->data($data)->add();
            if ($id) {
                if (!$is_pk) {
                    require_once APP_ROOT_PATH . "system/model/duobao.php";
                    $duobao_item = duobao::new_duobao($id);
                }
                $this->success('添加成功');
            } else {
                $this->error('添加失败');
            }
        }

        
    }
    
    public function get_deal_option(){
        $name = strim($_REQUEST['deal_key']);
        $deal_model = M('Deal');
        $map[ 'name'] = array('like','%'.$name.'%');
        $map[ 'is_effect'] = 1;
        $deal_result = $deal_model->where($map)->select();
        $option = '<option value="0">==请选取商品==</option>';
        foreach ($deal_result as $key=>$val){
            $option .= '<option price="'.$val['current_price'].'" value="'.$val['id'].'">'.$val['name'].'</option>';
        }
                
       $this->ajaxReturn($option);
        
        
    }
    
    public function foreverdelete() {
        //彻底删除指定记录
        $ajax = intval($_REQUEST['ajax']);
        $id = $_REQUEST ['id'];
         
        $duobao_model = M("DuobaoItem");
        $duo_bao_map['duobao_id'] = $id;
        $duobao_result = $duobao_model->where($duo_bao_map)->find();
        if($duobao_result){
            $this->error('夺宝活动存在的夺宝计划，不能删除！');
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