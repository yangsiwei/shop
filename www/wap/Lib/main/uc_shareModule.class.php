<?php

class uc_shareModule extends MainBaseModule
{
	public function index()
	{
		global_run();
		init_app_page();
        
		//判断是用户自己的，还是查看他人
		$param['page']         = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$param['data_id']      = intval($_REQUEST['data_id']);
		$data = call_api_core("uc_share","index",$param);
		if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
			app_redirect(wap_url("index","user#login"));
		}
	    $page = new Page($data['page']['total'], $data['page']['page_size']); // 初始化分页对象
		$p = $page->show();
		/* 数据 */
		$GLOBALS['tmpl']->assign('pages', $p);
		$GLOBALS['tmpl']->assign("now_time", NOW_TIME);
		$GLOBALS['tmpl']->assign("luck_list", $data['luck_list']);
		$GLOBALS['tmpl']->assign("share_list", $data['share_list']);
		$GLOBALS['tmpl']->assign("data_id", $param['data_id']);
		$GLOBALS['tmpl']->assign("data", $data);
		$GLOBALS['tmpl']->display("uc_share.html");
	}
	
	/**
	 * 发布中奖信息
	 */
	public function add(){
	    global_run();
	    init_app_page();

	    $data['page_title'] = "晒单发布";
	    $GLOBALS['tmpl']->assign("id",intval($_REQUEST['id']));
	    $GLOBALS['tmpl']->assign("data",$data);
	    $GLOBALS['tmpl']->display("uc_share_add.html");
	}
	
	public function do_save(){
	    global_run();
	    $id = intval($_REQUEST['id']);
	    $title= strim($_REQUEST['title']);
	    $img_data = $_REQUEST['img_data'];
	    $content = strim($_REQUEST['content']);
	    if(mb_strlen($title,'UTF-8')<6){
	        $result['status'] = 0;
	        $result['info'] = "请留下一个最少6个字的晒单主题吧~";
	        ajax_return($result);
	    }
	    
	    if(mb_strlen($content,'UTF-8')<30){
	        $result['status'] = 0;
	        $result['info'] = "幸运感言，字不在多最少30个~";
	        ajax_return($result);
	    }
	    
	    $file_path = array();
	    if($img_data){
	         
	        //清除空数据
	        $img_data = array_filter($img_data);
	        if(count($img_data)>3){
	            $result['status'] = 0;
	            $result['info'] = '每次最多3张图片';
	            ajax_return($result);
	        }
	        
	        //上传处理
	        //创建comment目录
	        if (!is_dir(APP_ROOT_PATH."public/comment")) {
	            @mkdir(APP_ROOT_PATH."public/comment");
	            @chmod(APP_ROOT_PATH."public/comment", 0777);
	        }
	         
	        $dir = to_date(NOW_TIME,"Ym");
	        if (!is_dir(APP_ROOT_PATH."public/comment/".$dir)) {
	            @mkdir(APP_ROOT_PATH."public/comment/".$dir);
	            @chmod(APP_ROOT_PATH."public/comment/".$dir, 0777);
	        }
	         
	        $dir = $dir."/".to_date(NOW_TIME,"d");
	        if (!is_dir(APP_ROOT_PATH."public/comment/".$dir)) {
	            @mkdir(APP_ROOT_PATH."public/comment/".$dir);
	            @chmod(APP_ROOT_PATH."public/comment/".$dir, 0777);
	        }
	         
	        $dir = $dir."/".to_date(NOW_TIME,"H");
	        if (!is_dir(APP_ROOT_PATH."public/comment/".$dir)) {
	            @mkdir(APP_ROOT_PATH."public/comment/".$dir);
	            @chmod(APP_ROOT_PATH."public/comment/".$dir, 0777);
	        }
	        $dir = APP_ROOT_PATH."public/comment/".$dir;
	        $max_image_size = app_conf("MAX_IMAGE_SIZE");
	    
	        $f_img_data = array();
	        
	        foreach ($img_data as $k=>$v){
	            $temp_arr = array();
	            $json_arr = array();
	            $json_arr = (array)json_decode($v);
	            if ($json_arr['size']<=$max_image_size){
	                preg_match("/data:image\/(jpg|jpeg|png|gif);base64,/i",$json_arr['base64'],$res);
	                $temp_arr['ext'] = $res[1];
	                if(!in_array($temp_arr['ext'],array("jpg","jpeg","png","gif"))){
	                    $result['status'] = 0;
	                    $result['info'] = '上传文件格式有误';
	                    ajax_return($result);
	                }
	                $temp_arr['size'] = $json_arr['size'];
	                $temp_arr['img_data'] = preg_replace("/data:image\/(jpg|jpeg|png|gif);base64,/i","",$json_arr['base64']);
	                $temp_arr['file_name'] = time().md5(rand(0,100)).'.'.($temp_arr['ext'] == "jpeg" ? "jpg" : $temp_arr['ext']);
	                $f_img_data[] = $temp_arr;
	            }
	        }
	         
	        foreach ($f_img_data as $k=>$v){
	            if (file_put_contents($dir.$v['file_name'], base64_decode($v['img_data']))===false) {
	                $result['status'] = 0;
	                $result['info'] = '上传文件失败';
	                ajax_return($result);
	            }else{
	                $file_path['file_'.$k] = $dir.$v['file_name'];
	                $img_info = array();
	                $img_info['url'] = str_replace(APP_ROOT_PATH."public", "./public", $dir.$v['file_name']);
	                $img_info['path'] = $dir.$v['file_name'];
	                $img_info['name'] = substr($v['file_name'], 0,stripos($v['file_name'], "."));
	                $list[] = $img_info;
	            }
	            
	        }
	         

	        $list = $this->image_water($list,array('preview'=>array(255,255,0,app_conf("IS_WATER_MARK"))),app_conf("IS_WATER_MARK"));
            
	        require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
	        $image = new es_imagecls();
	        
	        foreach ($list as $k=>$v){
	            $info = $image->getImageInfo($v['path']);
	             
	            $image_data['width'] = intval($info[0]);
	            $image_data['height'] = intval($info[1]);
	            $image_data['name'] = valid_str($v['name']);
	            $image_data['filesize'] = filesize($v['path']);
	            $image_data['create_time'] = NOW_TIME;
	            $image_data['user_id'] = intval($GLOBALS['user_info']['id']);
	            $image_data['user_name'] = strim($GLOBALS['user_info']['user_name']);
	            $image_data['path'] = $v['thumb']['preview']['url'];
	            $image_data['o_path'] = $v['url'];
	            $GLOBALS['db']->autoExecute(DB_PREFIX."share_image",$image_data);
	            $data_result_id[] = array("id"=>intval($GLOBALS['db']->insert_id()));
	        }
	        
	    }
        
	    $parma = array();
	    $parma['id'] = $id;
	    $parma['title'] = $title;
	    $parma['content'] = $content;
	    $parma['attach_list'] = $data_result_id;

	    $data = call_api_core("uc_share","save",$parma);
	    $data['jump'] = wap_url("index","uc_share#detail",array("id"=>$data['share_id']));
	    ajax_return($data);
	    //请求MAPI接口
	    
	}
	
	public function detail(){
	
	    global_run();
	    init_app_page();
	    $param = array();
	    $param['id'] = intval($_REQUEST['id']);
	    $data = call_api_core("uc_share","detail",$param);
	    	
	    if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        app_redirect(wap_url("index","user#login"));
	    }
	   
	    if ($data['status']==0){
	        app_redirect(wap_url("index","uc_share#index"));
	    }
	
	    $GLOBALS['tmpl']->assign("data",$data);
	    $GLOBALS['tmpl']->display("share_detail.html");
	}
	
	public function image_water($list,$whs=array(),$is_water=false){
	    $water_image = APP_ROOT_PATH.app_conf("WATER_MARK");
	    $alpha = app_conf("WATER_ALPHA");
	    $place = app_conf("WATER_POSITION");
	    require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
	    $image = new es_imagecls();

	    if($list){
    	   foreach($list as $lkey=>$item)
    		{
    				//循环生成规格图
    				foreach($whs as $tkey=>$wh)
    				{
    					$list[$lkey]['thumb'][$tkey]['url'] = false;
    					$list[$lkey]['thumb'][$tkey]['path'] = false;
    					if($wh[0] > 0 || $wh[1] > 0)  //有宽高度
    					{
    						$thumb_type = isset($wh[2]) ? intval($wh[2]) : 0;  //剪裁还是缩放， 0缩放 1剪裁
    						if($thumb = $image->thumb($item['path'],$wh[0],$wh[1],$thumb_type))
    						{
    							$list[$lkey]['thumb'][$tkey]['url'] = $thumb['url'];
    							$list[$lkey]['thumb'][$tkey]['path'] = $thumb['path'];
    							if(isset($wh[3]) && intval($wh[3]) > 0)//需要水印
    							{
    								$paths = pathinfo($list[$lkey]['thumb'][$tkey]['path']);
    								$path = $paths['dirname'];
    				        		$path = $path."/origin/";
    				        		if (!is_dir($path)) { 
    						             @mkdir($path);
    						             @chmod($path, 0777);
    					   			}   	    
    				        		$filename = $paths['basename'];
    								@file_put_contents($path.$filename,@file_get_contents($list[$lkey]['thumb'][$tkey]['path']));      
    								$image->water($list[$lkey]['thumb'][$tkey]['path'],$water_image,$alpha, $place);
    							}
    						}
    					}
    				}
    			if($is_water)
    			{
    				$paths = pathinfo($item['path']);
    				$path = $paths['dirname'];
            		$path = $path."/origin/";
            		if (!is_dir($path)) { 
    		             @mkdir($path);
    		             @chmod($path, 0777);
    	   			}   	    
            		$filename = $paths['basename'];
    				@file_put_contents($path.$filename,@file_get_contents($item['path']));        		
    				$image->water($item['path'],$water_image,$alpha, $place);
    			}
    		}	
	    }
	    return $list;
	    
	}
	
	/**
	 * 晒单须知
	 */
	public function rule(){
	    global_run();
	    init_app_page();
		$data['page_title'].="晒单须知";
		$GLOBALS['tmpl']->assign("data", $data);
		$GLOBALS['tmpl']->assign("id",intval($_REQUEST['id']));
	    $GLOBALS['tmpl']->display("uc_share_rule.html");
	}
		/**
	 * 晒单
	 */
	public function upload(){
	    global_run();
	    init_app_page();
		
		$GLOBALS['tmpl']->assign("id",intval($_REQUEST['id']));
	    $GLOBALS['tmpl']->display("uc_share_upload.html");
	}
	
}
?>
