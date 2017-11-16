<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class UserAction extends CommonAction{
	public function __construct()
	{	
		parent::__construct();
		require_once APP_ROOT_PATH."/system/model/user.php";
	}
	public function index()
	{
		$group_list = M("UserGroup")->findAll();
		$this->assign("group_list",$group_list);
		
		//定义条件
		$map[DB_PREFIX.'user.is_delete'] = 0;

		if(intval($_REQUEST['group_id'])>0)
		{
			$map[DB_PREFIX.'user.group_id'] = intval($_REQUEST['group_id']);
		}
		
		if(strim($_REQUEST['user_name'])!='')
		{
			$map[DB_PREFIX.'user.user_name'] = array('like',"%".strim($_REQUEST['user_name'])."%");
		}
		
		if(strim($_REQUEST['id'])!='')
		{
		    $map[DB_PREFIX.'user.id'] = array('eq',strim($_REQUEST['id']));
		}
		
		if(strim($_REQUEST['email'])!='')
		{
			$map[DB_PREFIX.'user.email'] = array('eq',strim($_REQUEST['email']));
		}
		if(strim($_REQUEST['mobile'])!='')
		{
			$map[DB_PREFIX.'user.mobile'] = array('eq',strim($_REQUEST['mobile']));
		}
		if(strim($_REQUEST['pid_name'])!='')
		{
			$pid = M("User")->where("user_name='".strim($_REQUEST['pid_name'])."'")->getField("id");
			$map[DB_PREFIX.'user.pid'] = $pid;
		}
		
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
	}
	public function trash()
	{
		$condition['is_delete'] = 1;
		$this->assign("default_map",$condition);
		parent::index();
	}
	public function add()
	{
		$group_list = M("UserGroup")->findAll();
		$this->assign("group_list",$group_list);
		
		$cate_list = M("TopicTagCate")->findAll();
		$this->assign("cate_list",$cate_list);
		
		$field_list = M("UserField")->order("sort desc")->findAll();
		foreach($field_list as $k=>$v)
		{
			$field_list[$k]['value_scope'] = preg_split("/[ ,]/i",$v['value_scope']);
		}
		$this->assign("field_list",$field_list);
		$this->display();
	}
	
	//excel导入机器人
	public function batch_add(){
	    
	    $this->display();
	}
	//excel导入机器人
	public function modify_batch_add(){
	    //文件上传
	    import("ORG.Net.UploadFile");
        $upload = new UploadFile(); // 实例化上传类
        $upload->allowExts  = array('xls', 'xlsx'); // 设置附件上传类型
        $upload->savePath =  './admin/public/upfile/excel/'; // 设置附件上传目录
        $upload->saveRule =  'time'; // 设置上传文件的保存规则
        
        if(!$upload->upload()) { // 上传错误提示错误信息
            $this->error($upload->getErrorMsg());
        }else{ // 上传成功获取上传文件信息
            $info = $upload->getUploadFileInfo();
        }
        $file_ext = $info[0]['extension'];//上传的excel文件扩展名
        
        require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
        $image = new es_imagecls();
        //头像图片
        $dir = APP_ROOT_PATH.'admin/public/robot';//批量上传的机器人头像放在该路径下
        $list = scandir( $dir );
        foreach ($list as $kk => $vv){
            $ext=$image->fileExt($vv);
            $is_img=$image->isImageExt($ext);
            if( $vv != '.' && $vv != '..' &&$is_img){
                $list['location_dir'][] =  $dir . '/' . $vv;//图片地址
            }
        }
        $count = count($list['location_dir']);//图片总数
        
        //获取上传的php文件内容
        vendor("Classes.PHPExcel");
        if( $file_ext=='xls'){
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
        }
        if( $file_ext=='xlsx'){
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        }
        $file_name=$info[0]['savepath'].$info[0]['savename'];
        $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
        $sheet = $objPHPExcel->getSheet(0);
        
        $icon_star = M("Conf")->where(" name ="." 'ROBOT_LOGO_SORT' ")->getField("value");//上次批量导入的头像下标
        
        if($icon_star>=$count){
            $icon_star=0;
        }else{
            $icon_star++;
        }
        
        $highestRow = $sheet->getHighestDataRow(); // 取得总行数,包括第一行
        for($i=2; $i<=$highestRow; $i++){
            $data['user_name']= $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            $data['user_name'] = strim($data['user_name']);
            if (empty($data['user_name'])) {
                continue;
            }
            if (M('User')->where('user_name='. '"' .$data['user_name']. '"' )->getField('user_name')) {
                continue;
            }
            $data['login_ip']= strim($objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue());
            $data['user_logo'] = $list['location_dir'][$icon_star];
            
            require_once APP_ROOT_PATH."system/model/robot.php";
            $robot = new robot($data['user_name'],$data['login_ip'],0,$data['user_logo']);//插入数据库
            $robot = (array)$robot;
            move_avatar_file($data['user_logo'], $robot['id']);
            
            $icon_star++;
            if ($icon_star >= $count) {
                $icon_star=0;
            }
        }   
        
        //数据库中记录的是当前已使用到最后一张的图片数组的下标值
        if($icon_star==0){
            ;
        }
        elseif($icon_star>0){
            $icon_star--;
        }
        
        $GLOBALS['db']->query("update ".DB_PREFIX."conf set value = ".$icon_star  ." where name = ". "'".ROBOT_LOGO_SORT."'");
        $this->success('导入成功！');
	}
	
	
	public function insert() {
		
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M(MODULE_NAME)->create ();

		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
	
		if($data['is_robot']==1)
		{
			if(!check_empty($data['user_name']))
			{
				$this->error("请输入机器人名称");
			}
			require_once APP_ROOT_PATH."system/model/robot.php";
			$robot = new robot($data['user_name'],$data['login_ip'],0,$data['user_logo']);
			$this->success("添加成功");
		}
		
		if(!check_empty($data['user_pwd']))
		{
			$this->error(L("USER_PWD_EMPTY_TIP"));
		}	
		if($data['user_pwd']!=$_REQUEST['user_confirm_pwd'])
		{
			$this->error(L("USER_PWD_CONFIRM_ERROR"));
		}
		$res = save_user($_REQUEST);
		if($res['status']==0)
		{
			$error_field = $res['data'];
			if($error_field['error'] == EMPTY_ERROR)
			{
				if($error_field['field_name'] == 'user_name')
				{
					$this->error(L("USER_NAME_EMPTY_TIP"));
				}
				elseif($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_EMPTY_TIP"));
				}
				else
				{
					$this->error(sprintf(L("USER_EMPTY_ERROR"),$error_field['field_show_name']));
				}
			}
			if($error_field['error'] == FORMAT_ERROR)
			{
				if($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_FORMAT_TIP"));
				}
				if($error_field['field_name'] == 'mobile')
				{
					$this->error(L("USER_MOBILE_FORMAT_TIP"));
				}
				if($error_field['field_name'] == 'user_name')
				{
					$this->error("会员名格式错误");
				}
			}
			
			if($error_field['error'] == EXIST_ERROR)
			{
				if($error_field['field_name'] == 'user_name')
				{
					$this->error(L("USER_NAME_EXIST_TIP"));
				}
				if($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_EXIST_TIP"));
				}
				if($error_field['field_name'] == 'mobile')
				{
					$this->error("手机号已被其他会员绑定");
				}
			}
		}
		$user_id = intval($res['user_id']);
		foreach($_REQUEST['auth'] as $k=>$v)
		{
			foreach($v as $item)
			{
				$auth_data = array();
				$auth_data['m_name'] = $k;
				$auth_data['a_name'] = $item;
				$auth_data['user_id'] = $user_id;
				M("UserAuth")->add($auth_data);
			}
		}
		
		
		foreach($_REQUEST['cate_id'] as $cate_id)
		{
			$link_data = array();
			$link_data['user_id'] = $user_id;
			$link_data['cate_id'] = $cate_id;
			M("UserCateLink")->add($link_data);
		}
		
		// 更新数据
		$log_info = $data['user_name'];
		delete_avatar($user_id);
		save_log($log_info.L("INSERT_SUCCESS"),1);
		$this->success(L("INSERT_SUCCESS"));
		
	}
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['is_delete'] = 0;
		$condition['id'] = $id;		
//		$condition['is_robot'] = 0;
		$vo = M(MODULE_NAME)->where($condition)->find();
		if(empty($vo))
			$this->error("非法的会员");
		
		//$vo['user_logo'] = get_user_avatar($id, "big");
		if(substr($vo['user_logo'],0,4)!='http')
		    $vo['user_logo'] = get_user_avatar($id, "big");
		
		$this->assign ( 'vo', $vo );

		$group_list = M("UserGroup")->findAll();
		$this->assign("group_list",$group_list);
		
		$cate_list = M("TopicTagCate")->findAll();
		foreach($cate_list as $k=>$v)
		{
			$cate_list[$k]['checked'] = M("UserCateLink")->where("user_id=".$vo['id']." and cate_id = ".$v['id'])->count();
		}
		$this->assign("cate_list",$cate_list);		
		$field_list = M("UserField")->order("sort desc")->findAll();
		foreach($field_list as $k=>$v)
		{
			$field_list[$k]['value_scope'] = preg_split("/[ ,]/i",$v['value_scope']);
			$field_list[$k]['value'] = M("UserExtend")->where("user_id=".$id." and field_id=".$v['id'])->getField("value");
		}
		$this->assign("field_list",$field_list);
		
		$rs = M("UserAuth")->where("user_id=".$id." and rel_id = 0")->findAll();
		foreach($rs as $row)
		{
			$auth_list[$row['m_name']][$row['a_name']] = 1;
		}
		//线下会员
        $user = M('user');
		$order = M('deal_order');
		$user_level = $user->field('fx_level')->where(array('id'=>$id))->find();
            $first_user = $user->field('id')->where(array('pid'=>$id))->select();
            $first_count = 0;
            foreach($first_user as $vv){
                $first_uid[] = $vv['id'];
                $first_count ++;
            }
            $where1['user_id'] = array('in',$first_uid);
            $where1['type'] = array('eq',1);
            $where1['pay_amount'] = array('egt',100);
            $first_order = $order->field("sum(pay_amount) as money")->where($where1)->select();

            $pid_2['pid'] = array('in',$first_uid);
            $second_user = $user->field('id')->where($pid_2)->select();
            $second_count = 0;
            foreach($second_user as $v){
                $second_uid[] = $v['id'];
                $second_count++;
            }
            $where2['user_id'] = array('in',$second_uid);
            $where2['type'] = array('eq',1);
            $where2['pay_amount'] = array('egt',100);
            $second_order = $order->field("sum(pay_amount) as money")->where($where2)->select();


            $pid_3['pid'] = array('in',$second_uid);
            $third_user = $user->field('id')->where($pid_3)->select();
            $third_count = 0;
            foreach($third_user as $v){
                $third_uid[] = $v['id'];
                $third_count++;
            }
            $where3['user_id'] = array('in',$third_uid);
            $where3['type'] = array('eq',1);
            $where3['pay_amount'] = array('egt',100);
            $third_order = $order->field("sum(pay_amount) as money")->where($where3)->select();
        $user_count['first'] = $first_count;
        $user_count['second'] = $second_count;
        $user_count['third'] = $third_count;

		$this->assign("user_count",$user_count);
		$this->assign("first",$first_order);
		$this->assign("second",$second_order);
		$this->assign("third",$third_order);
		$this->assign("auth_list",$auth_list);
		$this->display ();
	}
	
	
	public function delete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['user_name'];	
				}
				if($info) $info = implode(",",$info);
				$ids = explode ( ',', $id );
				foreach($ids as $uid)
				{
					delete_user($uid);
				}
				save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
				 
				$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
		
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("user_name");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
                
        if($data['is_robot']==1)
		{
			if(!check_empty($data['user_name']))
			{
				$this->error("请输入机器人名称");
			}
                        if(!check_empty($data['login_ip']))
			{
				$this->error("请输入IP地址");
			}
			require_once APP_ROOT_PATH."system/model/robot.php";
			$robot = new robot($data['user_name'],$data['login_ip'],intval($data['id']),$data['user_logo']);
			delete_avatar($data['id']);
			$this->success("更新成功");
		}
                
		if(!check_empty($data['user_pwd'])&&$data['user_pwd']!=$_REQUEST['user_confirm_pwd'])
		{
			$this->error(L("USER_PWD_CONFIRM_ERROR"));
		}

		$res = save_user($_REQUEST,'UPDATE');

        if(isset($_REQUEST['fx_money'])){
            $aa = M('user');
            $aa->where(array('id'=>$_REQUEST['id']))->setField('fx_money',$_REQUEST['fx_money']);
        }
        if(isset($_REQUEST['admin_money'])){
            $aa = M('user');
            $aa->where(array('id'=>$_REQUEST['id']))->setField('admin_money',$_REQUEST['admin_money']);
        }
        if(isset($_REQUEST['fx_level'])){
            $aa = M('user');
            $aa->where(array('id'=>$_REQUEST['id']))->setField('fx_level',$_REQUEST['fx_level']);
        }
		if($res['status']==0)
		{
			$error_field = $res['data'];
			if($error_field['error'] == EMPTY_ERROR)
			{
				if($error_field['field_name'] == 'user_name')
				{
					$this->error(L("USER_NAME_EMPTY_TIP"));
				}
				elseif($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_EMPTY_TIP"));
				}
				else
				{
					$this->error(sprintf(L("USER_EMPTY_ERROR"),$error_field['field_show_name']));
				}
			}
			if($error_field['error'] == FORMAT_ERROR)
			{
				if($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_FORMAT_TIP"));
				}
				if($error_field['field_name'] == 'mobile')
				{
					$this->error(L("USER_MOBILE_FORMAT_TIP"));
				}
				if($error_field['field_name'] == 'user_name')
				{
					$this->error("会员名格式错误");
				}
			}
			
			if($error_field['error'] == EXIST_ERROR)
			{
				if($error_field['field_name'] == 'user_name')
				{
					$this->error(L("USER_NAME_EXIST_TIP"));
				}
				if($error_field['field_name'] == 'email')
				{
					$this->error(L("USER_EMAIL_EXIST_TIP"));
				}
				if($error_field['field_name'] == 'mobile')
				{
					$this->error("手机号已被其他会员绑定");
				}
			}
		}
		
		//更新权限
		
		M("UserAuth")->where("user_id=".$data['id']." and rel_id = 0")->delete();
		foreach($_REQUEST['auth'] as $k=>$v)
		{
			foreach($v as $item)
			{
				$auth_data = array();
				$auth_data['m_name'] = $k;
				$auth_data['a_name'] = $item;
				$auth_data['user_id'] = $data['id'];
				M("UserAuth")->add($auth_data);
			}
		}
		//开始更新is_effect状态
		M("User")->where("id=".intval($_REQUEST['id']))->setField("is_effect",intval($_REQUEST['is_effect']));
		$user_id = intval($_REQUEST['id']);		
		M("UserCateLink")->where("user_id=".$user_id)->delete();
		foreach($_REQUEST['cate_id'] as $cate_id)
		{
			$link_data = array();
			$link_data['user_id'] = $user_id;
			$link_data['cate_id'] = $cate_id;
			M("UserCateLink")->add($link_data);
		}
		delete_avatar($user_id);
             
		save_log($log_info.L("UPDATE_SUCCESS"),1);
		$this->success(L("UPDATE_SUCCESS"));
		
	}

	public function set_effect()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$info = M(MODULE_NAME)->where("id=".$id)->getField("user_name");
		$c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField("is_effect");  //当前状态
		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M(MODULE_NAME)->where("id=".$id)->setField("is_effect",$n_is_effect);	
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
	
	public function account()
	{
		$user_id = intval($_REQUEST['id']);
		$user_info = M("User")->getById($user_id);
		$this->assign("user_info",$user_info);
		$this->display();
	}
	public function modify_account()
	{
		$user_id = intval($_REQUEST['id']);
		$money = floatval($_REQUEST['money']);
		$score = intval($_REQUEST['score']);
		$coupons = intval($_REQUEST['coupons']);
		$point = intval($_REQUEST['point']);
		$msg = strim($_REQUEST['msg'])==''?l("ADMIN_MODIFY_ACCOUNT"):strim($_REQUEST['msg']);
		modify_account(array('money'=>$money,'score'=>$score,'point'=>$point,'coupons'=>$coupons),$user_id,$msg);
		save_log(l("ADMIN_MODIFY_ACCOUNT"),1);
		$this->success(L("UPDATE_SUCCESS")); 
	}
	
	public function account_detail()
	{
		$user_id = intval($_REQUEST['id']);
		$user_info = M("User")->getById($user_id);
		$this->assign("user_info",$user_info);
		$map['user_id'] = $user_id;
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$model = M ("UserLog");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	//我的邀请人列表
	public function inte_detail(){
	    $user_id = intval($_REQUEST['id']);
		$user_info = M("User")->getById($user_id);
		$this->assign("user_info",$user_info);
		$map['user_id'] = $user_id;
		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$model = M ("Referrals");
		if (! empty ( $model )) {
			$this->_group_list ( $model, $map);
		}
		$this->display ();
		return;
	}

	protected function _group_list($model, $map, $sortBy = '', $asc = false,$count=-1) {
	    //排序字段 默认为主键名
	    if (isset ( $_REQUEST ['_order'] )) {
	        $order = $_REQUEST ['_order'];
	    } else {
	        $order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();
	    }
	    //排序方式默认按照倒序排列
	    //接受 sost参数 0 表示倒序 非0都 表示正序
	    if (isset ( $_REQUEST ['_sort'] )) {
	        $sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
	    } else {
	        $sort = $asc ? 'asc' : 'desc';
	    }
	    //var_dump($map['user_id']);exit();
	    //取得满足条件的记录数
	    if($count==-1)
	        $count = $model->where($map)->distinct(true)->field(rel_user_id)->select();
            
            $count=count($count);
	    
	    if ($count > 0) {
	        //创建分页对象
	        if (! empty ( $_REQUEST ['listRows'] )) {
	            $listRows = $_REQUEST ['listRows'];
	        } else {
	            $listRows = '';
	        }
	      
	        $p = new Page ( $count, $listRows );
	        //分页查询数据
	        $voList = $model->where($map)->order( "`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->group('rel_user_id')->field("*,sum(score) as sum_score,sum(coupons) as sum_coupons")->select();
	        //echo $model->getLastSql();
	        //分页跳转的时候保证查询条件
	        foreach ( $map as $key => $val ) {
	            if (! is_array ( $val )) {
	                $p->parameter .= "$key=" . urlencode ( $val ) . "&";
	            }
	        }
	        //分页显示
	
	        $page = $p->show ();
	        //列表排序显示
	        $sortImg = $sort; //排序图标
	        $sortAlt = $sort == 'desc' ? l("ASC_SORT") : l("DESC_SORT"); //排序提示
	        $sort = $sort == 'desc' ? 1 : 0; //排序方式
	        //模板赋值显示
	        $this->assign ( 'list', $voList );
	        $this->assign ( 'sort', $sort );
	        $this->assign ( 'order', $order );
	        $this->assign ( 'sortImg', $sortImg );
	        $this->assign ( 'sortType', $sortAlt );
	        $this->assign ( "page", $page);
	        $this->assign ( "nowPage",$p->nowPage);
	    }
	    return;
	}
	
	public function foreverdelete_account_detail()
	{
		
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("UserLog")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("UserLog")->where ( $condition )->delete();	
				
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
	
	
	public function export_csv($page = 1)
	{
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		//定义条件
		$map[DB_PREFIX.'user.is_delete'] = 0;

		if(intval($_REQUEST['group_id'])>0)
		{
			$map[DB_PREFIX.'user.group_id'] = intval($_REQUEST['group_id']);
		}
		
		if(strim($_REQUEST['user_name'])!='')
		{
			$map[DB_PREFIX.'user.user_name'] = array('eq',strim($_REQUEST['user_name']));
		}
		if(strim($_REQUEST['email'])!='')
		{
			$map[DB_PREFIX.'user.email'] = array('eq',strim($_REQUEST['email']));
		}
		if(strim($_REQUEST['mobile'])!='')
		{
			$map[DB_PREFIX.'user.mobile'] = array('eq',strim($_REQUEST['mobile']));
		}
		if(strim($_REQUEST['pid_name'])!='')
		{
			$pid = M("User")->where("user_name='".strim($_REQUEST['pid_name'])."'")->getField("id");
			$map[DB_PREFIX.'user.pid'] = $pid;
		}
		
		$list = M(MODULE_NAME)
				->where($map)
				->join(DB_PREFIX.'user_group ON '.DB_PREFIX.'user.group_id = '.DB_PREFIX.'user_group.id')
				->field(DB_PREFIX.'user.*,'.DB_PREFIX.'user_group.name')
				->limit($limit)->findAll();


		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$user_value = array('id'=>'""','user_name'=>'""','email'=>'""','mobile'=>'""','group_id'=>'""');
			if($page == 1)
	    	$content = iconv("utf-8","gbk","编号,用户名,电子邮箱,手机号,会员组");
	    	
	    	
	    	//开始获取扩展字段
	    	$extend_fields = M("UserField")->order("sort desc")->findAll();
	    	foreach($extend_fields as $k=>$v)
	    	{
	    		$user_value[$v['field_name']] = '""';
	    		if($page==1)
	    		$content = $content.",".iconv('utf-8','gbk',$v['field_show_name']);
	    	}   
	    	if($page==1) 	
	    	$content = $content . "\n";
	    	
	    	foreach($list as $k=>$v)
			{	
				$user_value = array();
				$user_value['id'] = iconv('utf-8','gbk','"' . $v['id'] . '"');
				$user_value['user_name'] = iconv('utf-8','gbk','"' . $v['user_name'] . '"');
				$user_value['email'] = iconv('utf-8','gbk','"' . $v['email'] . '"');
				$user_value['mobile'] = iconv('utf-8','gbk','"' . $v['mobile'] . '"');
				$user_value['group_id'] = iconv('utf-8','gbk','"' . $v['name'] . '"');

				//取出扩展字段的值
				$extend_fieldsval = M("UserExtend")->where("user_id=".$v['id'])->findAll();
				foreach($extend_fields as $kk=>$vv)
				{
					foreach($extend_fieldsval as $kkk=>$vvv)
					{
						if($vv['id']==$vvv['field_id'])
						{
							$user_value[$vv['field_name']] = iconv('utf-8','gbk','"'.$vvv['value'].'"');
							break;
						}
					}
					
				}
			
				$content .= implode(",", $user_value) . "\n";
			}	
			
			
			header("Content-Disposition: attachment; filename=user_list.csv");
	    	echo $content;  
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}
		
	}
	

	
	/*
	 * 会员提现
	 */
	public function withdrawal_index()
	{
		if(isset($_REQUEST['is_paid']))
		{
			if(intval($_REQUEST['is_paid'])==0)
			{
				$map['is_paid'] = intval($_REQUEST['is_paid']);
				$map['is_delete'] = 0;
			}
		}
		$model = D ("Withdraw");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	
	
	/*
	 * 会员提现编辑
	 */	
	public function withdrawal_edit()
	{
		$id = intval($_REQUEST['id']);

		$withdrawal_info = M("Withdraw")->getById($id);
		$user_info= M("User")->where("id=".$withdrawal_info['user_id'])->find();
		$withdrawal_info['user_name']=$user_info['user_name'];
		$withdrawal_info['user_money']= $user_info['money'];
		$this->assign("withdrawal_info",$withdrawal_info);
		$this->display();
	}		
	

	/*
	 * 商户提现审核
	 */	
	public function do_withdrawal()
	{
		$id = intval($_REQUEST['id']);
		$log=strim($_REQUEST['log']);
		require_once APP_ROOT_PATH."system/model/user.php";
		
		$withdrawal_info = M("Withdraw")->getById($id);
		$user_info=M("User")->getById($withdrawal_info['user_id']);
		$withdrawal_info['money']=floatval($_REQUEST['money']);
		if($withdrawal_info['money']<=0)$this->error("提现金额必须大于0");
		

		if($withdrawal_info['money']>$user_info['money'])$this->error("提现超额");				
		
		if($withdrawal_info['is_paid']==0)
		{
			M("Withdraw")->where("id=".$id)->setField("is_paid",1);
			M("Withdraw")->where("id=".$id)->setField("money",$withdrawal_info['money']);					
			modify_account(array('money'=>"-".$withdrawal_info['money']),$withdrawal_info['user_id'],$user_info['user_name']."提现".format_price($withdrawal_info['money'])."元审核通过。".$log);
			modify_statements($withdrawal_info['money'],3,$user_info['user_name']."提现".format_price($withdrawal_info['money'])."元审核通过。".$log);
			modify_statements($withdrawal_info['money'],4,$user_info['user_name']."提现".format_price($withdrawal_info['money'])."元审核通过。".$log);
			//发短信与邮件
			send_user_withdraw_sms($user_info['id'],$withdrawal_info['money']);
			send_user_withdraw_mail($user_info['id'],$withdrawal_info['money']);
			
                        if($withdrawal_info['is_bind'] == 1){
                            if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_bank where bank_account ='".$withdrawal_info['bank_account']."' ")){
                                
                            }else{
                                $ins_data['user_id'] = $withdrawal_info['user_id'];
                                $ins_data['bank_name'] = $withdrawal_info['bank_name'];
                                $ins_data['bank_user'] = $withdrawal_info['bank_user'];
                                $ins_data['bank_account'] = $withdrawal_info['bank_account'];
                                $ins_data['bank_mobile'] = $withdrawal_info['bank_mobile'];
                                
                                if($user_info['real_name']=='' || $user_info['real_name'] == $withdrawal_info['bank_user']){
                                    if($user_info['real_name']==''){
                                        $GLOBALS['db']->autoExecute(DB_PREFIX."user",array('real_name'=>$withdrawal_info['bank_user']),"UPDATE","id=".$user_info['id']);
                                    }
                                    $GLOBALS['db']->autoExecute(DB_PREFIX."user_bank",$ins_data);
                                }
                                
                                
                            }
                            
                        }
                        
			save_log($user_info['user_name']."提现".format_price($withdrawal_info['money'])."元审核通过。".$log,1);					
			$this->success("确认提现成功");
		}
		else
		{
			$this->error("已提现过，无需再次提现");
		}
	
		
	}	
	
	
	public function del_withdrawal()
	{
		$id = intval($_REQUEST['id']);
		$withdrawal = M("Withdraw")->getById($id);
		
		$list = M("Withdraw")->where ("id=".$id )->delete();		
		if ($list!==false) {					 
				save_log($withdrawal['user_id']."号会员提现".$withdrawal['money']."元记录".l("FOREVER_DELETE_SUCCESS"),1);
				$this->success (l("FOREVER_DELETE_SUCCESS"),1);
		} else {
				save_log($withdrawal['user_id']."号商户提现".$withdrawal['money']."元记录".l("FOREVER_DELETE_FAILED"),0);
				$this->error (l("FOREVER_DELETE_FAILED"),1);
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
				$info[] = $data['user_name'];
			}
			if($info) $info = implode(",",$info);
			$ids = explode ( ',', $id );
			foreach($ids as $uid)
			{
				delete_user($uid);
			}
			save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
				
			$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
	
		} else {
			$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	
}
?>