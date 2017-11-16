<?php
class shareApiModule extends MainBaseApiModule{

	/**
	 * 晒单列表接口
     * 输入：
     * page:int 当前的页数
     * 
     * 输出：
     * page => Array
        (
            [total] => 20
            [page_size] => 10
        )

    	list => Array
        (
            [list] => Array
                (
                    [0] => Array
                        (
                            [id] => 22
                            [duobao_item_id] => 100000005
                            [title] => 运气不错咯
                            [content] => 运气不错咯运气不错咯运气不错咯运气不错咯运气不错咯运气不错咯运气不错咯运气不错咯运气不错咯运气不错咯运气不错咯
                            [create_time] => 05-17 02:33
                            [type] => 1
                            [user_id] => 222
                            [user_name] => fanwe
                            [is_effect] => 1
                            [xpoint] => 
                            [ypoint] => 
                            [is_recommend] => 1
                            [is_top] => 1
                            [images_count] => 6
                            [image_list] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 19
                                            [share_id] => 4
                                            [name] => 22.png
                                            [filesize] => 382117
                                            [create_time] => 1463438002
                                            [user_id] => 222
                                            [user_name] => fanwe
                                            [path] => http://localhost/yydb/public/comment/201605/17/14/93df6e08d764311f8641657d7c08345c70_255x255.jpg
                                            [o_path] => http://localhost/yydb/public/comment/201605/17/14/93df6e08d764311f8641657d7c08345c70.jpg
                                            [width] => 1080
                                            [height] => 1920
                                        )
                                    )
                         )
                  )
        )                       
	 */
    public function index(){
    	    	
    	$page_size = app_conf("PAGE_SIZE");
    	$page = intval($GLOBALS['request']['page']);
    	$data_id = intval($GLOBALS['request']['data_id']);
    	if ($page == 0) $page = 1;
    	$limit = (($page - 1) * $page_size) . "," . $page_size;

    	require_once APP_ROOT_PATH."system/model/share.php";
    	$share = new share();
    	if ($data_id) {
    	    $excondition = " duobao_id={$data_id} and is_effect=1 ";
    	}else{
    	    $excondition = " is_effect=1 ";
    	}
    	 
    	$result_list = $share->get_share_list($limit, $excondition);
    	
    	
    	foreach($result_list['list'] as $k=>$v){
    		$result_list['list'][$k]['user_avatar']=get_user_avatar($v['user_id'],$type='small');
    		$result_list['list'][$k]['create_time']=to_date($v['create_time'],"m-d H:i");
    		$image_list=unserialize($v['image_list']);
    		foreach($image_list as $kk=>$vv){
    			$image_list[$kk]['path']=get_abs_img_root($vv['path']);
    			$image_list[$kk]['o_path']=get_abs_img_root($vv['o_path']);
    			
			    $path       = APP_ROOT_PATH.substr($vv['path'], 1);
			    $o_path     = APP_ROOT_PATH.substr($vv['o_path'], 1);
			
			    $exists     = file_exists( $path );
			    $o_exists   = file_exists( $o_path );
			
			    if (!$exists && $o_exists) {
			        $share_list[$kk]['image_list'][$k]['path'] = imagecropper( $o_path, 255, 255 );
			        $path = $share_list[$kk]['image_list'][$k]['path'];
			        $GLOBALS['db']->query("update ".DB_PREFIX."share_image set path ='".$path."' where id = {$vv['id']}");
			    }
    			
    			 
    			
    		}
    		$result_list['list'][$k]['image_list']=$image_list;
    	}
    	
    	$sql_count="select count(*) from ".DB_PREFIX."share where {$excondition}";
    	$total = intval($GLOBALS['db']->getOne($sql_count));
    	$page_data['total'] = $total;
    	$page_data['page_size'] = $page_size;
    		
    	/* 分页 */
    	$root['page'] = $page_data;
    	$root['list']=$result_list;
    	$root['page_title']="晒单分享";
    	
    	return output($root);
    	
    	
    }
    
    
    /**
     * 晒单列表接口
     * 输入：
     * id:int 当前晒单数据的ID
     *
     * 输出：
     */
    public function detail(){
    
    	$root=array();
    	$id = intval($GLOBALS['request']['id']);
        $user_info = $GLOBALS['user_info'];
    	$sql="select * from ".DB_PREFIX."share where is_effect=1 and id=".$id;
    	 
    	$share_info=$GLOBALS['db']->getRow($sql);
         
    	if(!$share_info){
    		return output($root,0,"晒单数据不存在");
    	}
    	$image_list=unserialize($share_info['image_list']);
    	foreach($image_list as $kk=>$vv){
    		$image_list[$kk]['path']=get_abs_img_root($vv['path']);
    		$image_list[$kk]['o_path']=get_abs_img_root($vv['o_path']);
    	}
    	$share_info['image_list']=$image_list;
    	$share_info['duobao_item']=unserialize($share_info['cache_duobao_item_data']);
    	$share_info['create_time']=to_date($share_info['create_time']);
    	$share_info['duobao_item']['lottery_time']=to_date($share_info['duobao_item']['lottery_time']);
    	unset($share_info['cache_duobao_item_data']);
    	$root['share_info']=$share_info;
    	$root['page_title']="晒单详情";
    	 
    	return output($root);
    	 
    	 
    }
    
}