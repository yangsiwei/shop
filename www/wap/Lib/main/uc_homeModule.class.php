<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

/**
 * 我的朋友圈
 * @author jobin.lin
 *
 */
class uc_homeModule extends MainBaseModule
{

	public function index()
	{
		global_run();		
		init_app_page();
		
		$param=array();
		$param['page'] = intval($_REQUEST['page']);
		$param['id'] = intval($_REQUEST['id']);

		$data = call_api_core("uc_home","index",$param);
              
		if($data['is_why']==0 && $data['user_login_status']!=LOGIN_STATUS_LOGINED){
		    app_redirect(wap_url("index","user#login"));
		}

		if(isset($data['page']) && is_array($data['page'])){
			//感觉这个分页有问题,查询条件处理;分页数10,需要与sjmpai同步,是否要将分页处理移到sjmapi中?或换成下拉加载的方式,这样就不要用到分页了
			$page = new Page($data['page']['data_total'],$data['page']['page_size']);   //初始化分页对象
			//$page->parameter
			$p  =  $page->show();
			//print_r($p);exit;
			$GLOBALS['tmpl']->assign('pages',$p);
		}

		$GLOBALS['tmpl']->assign("ajax_url",wap_url("index","uc_home"));
		$GLOBALS['tmpl']->assign("data",$data);	
		$GLOBALS['tmpl']->assign("data_list",$data['data_list']);
		$GLOBALS['tmpl']->display("uc_home.html");
	}
	
	public function show()
	{
	    global_run();
	    init_app_page();
	
	    $param=array();
	    $param['id'] = intval($_REQUEST['id']);
	
	    $data = call_api_core("uc_home","show",$param);
	
	    if($data['is_why']==0 && $data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        app_redirect(wap_url("index","user#login"));
	    }

	
	    $GLOBALS['tmpl']->assign("ajax_url",wap_url("index","uc_home"));
	    $GLOBALS['tmpl']->assign("data",$data);
	    $GLOBALS['tmpl']->assign("row",$data['data']);
	    $GLOBALS['tmpl']->display("uc_home_show.html");
	}
	
	public function do_fav_topic(){
	    global_run();
	    
	    $param=array();
	    $param['id'] = intval($_REQUEST['id']);
	    
	    $data = call_api_core("uc_home","do_fav_topic",$param);
	    
	    if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        $data['status'] = -1;
	        $data['jump'] = wap_url("index","user#login");
	    }
	    
	    ajax_return($data);
	}
	
	public function do_reply(){
	    global_run();
	    $param=array();
	    $param['id'] = intval($_REQUEST['reply_tid']);
	    $param['reply_id'] = intval($_REQUEST['reply_rid']);
	    $param['content'] = strim($_REQUEST['reply_txt']);
	     
	    $data = call_api_core("uc_home","do_reply",$param);
	     
	    if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        $data['status'] = -1;
	        $data['jump'] = wap_url("index","user#login");
	    }else{
	        $u_url = wap_url("index","uc_home#index",array("id"=>$reply_data['user_id']));
	        $reply_data = $data['reply_data'];
	        $data['reply_html'] = '<li class="r-item r_sub_data_id_'.$reply_data['reply_id'].'"><a class="name_link" href="'.$u_url.'">'.$reply_data['user_name'].'</a>：<div class="r-con"  onclick="submit_reply('.$param['id'].','.$reply_data['reply_id'].')">'.$reply_data['content'].'</div></li>';
	    }
	     
	    ajax_return($data);
	}
	
	public function check_reply_user(){
	    global_run();
	    $param=array();
	    $param['reply_id'] = intval($_REQUEST['reply_id']);
	    
	    $data = call_api_core("uc_home","check_reply_user",$param);

	    if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        $data['status'] = -1;
	        $data['info'] = "请先登录后回复";
	        $data['jump'] = wap_url("index","user#login");
	    }
	    
	    ajax_return($data);
	}
	
	public function del_reply(){
	    global_run();
	    $param=array();
	    $param['id'] = intval($_REQUEST['reply_id']);
	     
	    $data = call_api_core("uc_home","del_reply",$param);
	
	    if($data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        $data['status'] = -1;
	        $data['info'] = "请先登录后回复";
	        $data['jump'] = wap_url("index","user#login");
	    }
	     
	    ajax_return($data);
	}
	public function load_move_reply(){
	    global_run();
	    $param=array();
		$param['page'] = intval($_REQUEST['page']);
		$param['id'] = intval($_REQUEST['id']);
		
	    $data = call_api_core("uc_home","load_move_reply",$param);
	    
	    if($param['id']==0 && $data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        $data['status'] = -1;
	        $data['info'] = "请先登录后操作";
	        $data['jump'] = wap_url("index","user#login");
	    }elseif($param['id']==0){
	        $data['status'] = -1;
	        $data['info'] = "操作错误";
	        $data['jump'] = wap_url("index","uc_home#index");
	    }
	    if($data['reply_data']){
	        $data['status'] = 1;
            foreach ($data['reply_data'] as $k=>$v){
                $u_url = wap_url("index","uc_home#index",array("id"=>$v['user_id']));
                $data['reply_html'] .= '<li class="r-item r_sub_data_id_'.$v['id'].'">'.
	            '<a class="name_link" href="'.$u_url.'">'.$v['user_name'].'</a>：'.
	            '<div class="r-con"  onclick="submit_reply('.$param['id'].','.$v['reply_id'].')">'.$v['content'].'</div>'.
	            '</li>';
            }
            if($param['page']==$data['page']['page_total']){
                $data['is_lock'] = 1;
            }
	    }
	    
	    
	    
	    ajax_return($data);
	}
	
	public function publish(){
	    global_run();
	    init_app_page();
	
	    $param=array();
	
	    $data = call_api_core("uc_home","publish",$param);
	
	    if($data['is_why']==0 && $data['user_login_status']!=LOGIN_STATUS_LOGINED){
	        app_redirect(wap_url("index","user#login"));
	    }
	    $expression = array();
	    foreach ($data['expression'] as $k=>$v){
	        if ($k=='qq'){
	            $qq_count=1;
	            foreach ($v as $sk=>$sv){
	                $type_n = 'qq';
	                if($qq_count>39){
	                    $type_n='qq_2';
	                }
	                
	                $qq_e[$type_n][] = $sv;
	                $qq_count++;
	            }
	        }
	    }
	    $expression = $qq_e;

	    $GLOBALS['tmpl']->assign("ajax_url",wap_url("index","uc_home"));
	    $GLOBALS['tmpl']->assign("data",$data);
	    $GLOBALS['tmpl']->assign("expression",$expression);
	    $GLOBALS['tmpl']->display("publish.html");
	}
	
	public function do_publish(){
	    $img_data = $_REQUEST['img_data'];
	    $content = strim($_REQUEST['content']);
	    $file_path = array();
	    if($img_data){
	        
	        //清除空数据
	        $img_data = array_filter($img_data);
	        if(count($img_data)>3){
	            $result['status'] = 0;
	            $result['info'] = '每次最多3涨图片';
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
	                $temp_arr['file_name'] = time().md5(rand(0,100)).'.'.$temp_arr['ext'];
	                $f_img_data[] = $temp_arr;
	            }
	        }

	        foreach ($f_img_data as $k=>$v){
	            if (file_put_contents($dir."/".$v['file_name'], base64_decode($v['img_data']))===false) {
	                $result['status'] = 0;
	                $result['info'] = '上传文件失败';
	                ajax_return($result);
	            }else{
	                $file_path['file_'.$k] = "@".$dir."/".$v['file_name'];
	            }
	        }
	        
	        
	        
	        
	    }
	    $request_param = array();
	    //定义基础数据
	    $request_param['ctl']='uc_home';
	    $request_param['act']='do_publish';
	    $request_param['r_type']=1;
	    $request_param['i_type']=1;
	    $request_param['from']='wap';
	    $request_param['sess_id'] = $GLOBALS['sess_id'];
	     
	    $request_param['client_ip'] = CLIENT_IP;
	    $request_param['image_zoom'] = 2;
	     
	     
	    $data['app_type'] = 'wap';
	    $data['content'] = $content;
	    
	    $data = array_merge($data,$file_path,$request_param);

	    $host = str_replace(array('http://','https://'),array('',''),SITE_DOMAIN);
	    $url = SITE_DOMAIN.APP_ROOT."/mapi/index.php?ctl=uc_home&act=do_publish";//这里换成你服务器的地址
	    

	    $curl_result = $this->http_curl_post($host,$data,$url);
	    $curl_result = json_decode($curl_result,1);
	    
	    $ajax_data = array();
	    
	    if($curl_result['user_login_status']!=LOGIN_STATUS_LOGINED){
	         
	        $ajax_data['status'] = -1;
	        $ajax_data['info'] = "请先登录后操作";
	        $ajax_data['jump'] = wap_url("index","user#login");
	    }else{
	        $ajax_data['status'] = $curl_result['status'];
	        $ajax_data['info'] = $curl_result['info'];
	        $ajax_data['jump'] = wap_url("index","uc_home#index");
	    }
	    
	    ajax_return($ajax_data);
	}
	
	public function http_curl_post($host,$data,$url)
	{
	    $ch = curl_init();
	    $res= curl_setopt ($ch, CURLOPT_URL,$url);

	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt ($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch,CURLOPT_HTTPHEADER,$host);
	    $result = curl_exec ($ch);
	    
	    
	    curl_close($ch);
	
	    return $result;
	}
	
}
?>