<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class uc_homeApiModule extends MainBaseApiModule
{
	
	/**
	 * 我的个人主页
	 * 输入：
	 * page [int] 分页
	 * id [int] 可选，未登录情况下必须穿ID ，登录情况下默认为自己
	 * 
	 * 输出：
	 * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
	 *
	 *
	 *[is_fav] =>1         ：int 0未关注，1已经关注
      [is_why] => 2   ：int 0未登录也不查看其它人要求登录  ,1 自己，2其它登录用户看，3未登录用户看
	  [user_data] => Array  ：array 用户头部数据
    	 (
    	     [id]=>73  :int 用户ID 
        	 [user_name] => fanwe    ：string 用户名
        	 [user_avatar] => http://localhost/o2onew/public/avatar/000/00/00/71virtual_avatar_big.jpg       ：string 用户头像    页面上限定 85*85
        	 [uc_home_bg] => http://localhost/o2onew/public/attachment/201506/16/15/nofxm23fs_740x300.jpg    ：string 我的分销背景图片    360*150
    	 )
	如果是app ios 或者 android 应用还会返回表情数据（wap不做返回）
	[expression_replace_array] => Array
        (
            [search] => Array
                (
                    [0] => [傲慢]
                    [1] => [白眼]
                )
            [replace] => Array
            (
                [0] => http://192.168.1.190/o2onew/public/expression/qq/aoman.gif
                [1] => http://192.168.1.190/o2onew/public/expression/qq/baiyan.gif
             )
         ) 编号和图片一一对应
	
	       [data_list] => Array( //列表数据集
	       [0] =>array(
                    [id] => 284         :id 主题数据ID
                    [title] =>          :string 主题标题    文章分享时候才有存在
                    [content] => 爱爱爱         ：string 主题内容（app 不返回带HTML 的内容）
                    [o_path] => http://192.168.1.242/o2onew/public/comment/201507/24/18/d808d7474d0b6ac3577531934d2d642276.jpg  ：string 展示图原图地址
                    [image] => http://192.168.1.242/o2onew/public/comment/201507/24/18/d808d7474d0b6ac3577531934d2d642276_300x0.jpg     ：string 展示图缩略图  150*~
                    [image_count] => 3  ：int 图片总张数
                    [s_img] => Array        :array  小图集合
                        (
                            [0] => http://localhost/o2onew/public/comment/201508/18/12/d86cd4c12616f17389388d266af2fce089_200x200.jpg
                        )

                    [b_img] => Array        ：array 大图集合
                        (
                            [0] => http://localhost/o2onew/public/comment/201508/18/12/d86cd4c12616f17389388d266af2fce089.jpg
                        )

                    [share_obj] => Array    ：app 用到的对象
                        (
                            [id] => 73
                            [content] => 团购推荐：明视眼镜[【37店通用】明视眼镜]
                            [url] => http://localhost/o2onew/index.php?ctl=deal&act=73&r=NzE%3D
                            [o_path] => http://localhost/o2onew/public/comment/201508/18/12/d86cd4c12616f17389388d266af2fce089.jpg
                            [image] => http://localhost/o2onew/public/comment/201508/18/12/d86cd4c12616f17389388d266af2fce089_100x100.jpg
                        )

                    [type] => share     ：string 主题类型 :share 分享 ,dealcomment 商品点评 ,youhuicomment 优惠券购物点评, eventcomment 活动点评 ,slocationcomment 门店点评  ,eventsubmit 活动报名 ,sharedeal 分享商品,shareyouhui 分享优惠券,shareevent 分享活劝
                    [type_txt] => 分享了一个分享   ：string 分享类型说明
                    [user_id] => 71 ：int 发布用户ID
                    [user_name] => fanwe    :string 发布用户名称
                    [user_avatar] => http://192.168.1.242/o2onew/public/avatar/000/00/00/71virtual_avatar_big.jpg   ：string 发布用户头像
                    [reply_count] => 24     ：int 回复数量
                    [fav_count] => 0        ：int 喜欢数量
                    [show_time] => 6天前              ：string 发布时间格式化
                    [reply_is_move] => 1    ：int 是否有更多回复
                    [reply_list] => Array   ：array 回复列表
                        (
                            [0] => Array
                                (
                                    [id] => 77  ：int 回复数据ID
                                    [topic_id] => 284   ：int 回复主题ID
                                    [user_id] => 71     ：int 回复用户ID
                                    [user_name] => fanwe    ：string 回复用户名称
                                    [content] => 听妈妈的话，别让她受伤    ：string 回复内容
                                    [create_time] => 1438281179 ：string 回复时间
                                    [reply_id] => 0     ：int 回复，其它回复人ID
                                    [reply_user_id] => 0    ：int 被回复人的ID  
                                    [reply_user_name] =>    ：string 被回复人的名称
                                    [format_create_time] => 2015-07-31 10:32  ：string 格式化时间
                                )

                        )
					[distance]	=>	距离469米

                )
       )
     * page:array 分页信息 array("page"=>当前页数,"page_total"=>总页数,"page_size"=>分页量,"data_total"=>数据总量);
	 * page_title:string 页面标题			
	 *
	 * */
	public function index()
	{

	    $root = array();
	    /*参数初始化*/
	    $id = $GLOBALS['request']['id']; //用户推荐ID
	    $app_type = strim($GLOBALS['request']['from'])=='android'||strim($GLOBALS['request']['from'])=='ios'?'app':'wap';

	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $user_login_status = check_login();
	    $root['user_login_status'] = $user_login_status;
	 
	    $is_why = 0; //0未登录也不查看其它人要求登录 ，1 自己，2其它登录用户看，3未登录用户看
	    if($id){
	        if($id == $user_id)
	        {
	            $is_why = 1;
	            $home_user_info = $user;
	        }
	        else
	        {
	            $is_why = 3;
	            $home_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$id);
	            if($home_user_info){
					$is_why = 2;
					$is_fav = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_focus where focus_user_id=".$user['id']." and focused_user_id=".$id)) ;

				}
	        }
	    }else{
	        if(empty($user))
	        {
	            $is_why = 0;
	        }
	        else
	        {
	            $is_why = 1;
	            $home_user_info = $user;
	        }
	    }
	    $root['is_fav']= intval($is_fav);
	    $root['is_why'] = $is_why;
        
        //返回会员信息
        $user_data = array();
        $user_data['id'] = $home_user_info['id'];
        $user_data['user_name'] = $home_user_info['user_name'];
        $user_data['user_avatar'] = get_abs_img_root(get_muser_avatar($home_user_info['id'],"big"))?get_abs_img_root(get_muser_avatar($home_user_info['id'],"big")):"";
        $user_data['user_logo'] = $user_data['user_logo']?get_abs_img_root($user_data['user_logo']):$root['user_avatar'];
        $user_data['uc_home_bg'] = SITE_DOMAIN.APP_ROOT."/mapi/image/nouchomebg.jpg";
         
        $root['user_data'] = $user_data;

        
        //返回数据列表
        require_once APP_ROOT_PATH."system/model/topic.php";
        //分页
        $page = intval($GLOBALS['request']['page']);
        $page=$page==0?1:$page;
        
        $page_size = PAGE_SIZE;
        $limit = (($page-1)*$page_size).",".$page_size;
        //我关注的用户
        $focus_user_list = load_auto_cache("cache_focus_user",array("uid"=>$home_user_info['id']));
        $t_ids[] = $home_user_info['id'];
        foreach($focus_user_list as $k=>$v){
            $t_ids[] = $v['id'];
        }
        $condition =" user_id in (".implode(",", $t_ids).") and is_effect = 1 and is_delete = 0  and fav_id = 0 and relay_id = 0  and type in ('share','dealcomment','youhuicomment','eventcomment','slocationcomment','eventsubmit','sharedeal','shareyouhui','shareevent') ";
		
		//开始身边团购的地理定位
		//return output($current_geo);
		$tname = 't';
		$current_geo = es_session::get("current_geo");
		$ypoint = intval($current_geo['ypoint']);  //ypoint
		$xpoint = intval($current_geo['xpoint']);  //xpoint
		$pi = PI;  //圆周率
		$r = EARTH_R;  //地球平均半径(米)
		$field_append = ", (ACOS(SIN(($ypoint * $pi) / 180 ) *SIN(($tname.ypoint * $pi) / 180 ) +COS(($ypoint * $pi) / 180 ) * COS(($tname.ypoint * $pi) / 180 ) *COS(($xpoint * $pi) / 180 - ($tname.xpoint * $pi) / 180 ) ) * $r) as distance ";
		if(empty($sort_field)){
                    if($is_why !=1)
			$sort_field = " t.id desc, distance asc ";
                    else
                        $sort_field = " distance asc ";
		}
                if($is_why !=1)
	$condition .= " AND ( (ACOS(SIN(($ypoint * $pi) / 180 ) *SIN(($tname.ypoint * $pi) / 180 ) +COS(($ypoint * $pi) / 180 ) * COS(($tname.ypoint * $pi) / 180 ) *COS(($xpoint * $pi) / 180 - ($tname.xpoint * $pi) / 180 ) ) * $r)<=5000 OR (ACOS(SIN(($ypoint * $pi) / 180 ) *SIN(($tname.ypoint * $pi) / 180 ) +COS(($ypoint * $pi) / 180 ) * COS(($tname.ypoint * $pi) / 180 ) *COS(($xpoint * $pi) / 180 - ($tname.xpoint * $pi) / 180 ) ) * $r)=0 )";					
        $result_list = get_topic_list($limit,null,"",$condition,$sort_field,$tname,$field_append);

        $count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."topic where ".$condition);
        $page_total = ceil($count/$page_size);
        $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
        //end 分页
        
        //定义类型的范围
        $type_array = array(
            'share'=>'分享',
            'dealcomment'=>'商品点评',
            'youhuicomment'=>'优惠券购物点评', 
            'eventcomment'=>'活动点评',
            'slocationcomment'=>'门店点评',  
            'eventsubmit'=>'活动报名',  
            'sharedeal'=>'商品',  
            'shareyouhui'=>'优惠券', 
            'shareevent'=>'活动'
        );
        //表情替换
        
        if ($app_type=='app'){
            $expression_replace_array = load_auto_cache("expression_replace_array",array('type'=>'url'));
            foreach ($expression_replace_array['replace'] as $k=>$v){
                $expression_replace_array['replace'][$k] = str_replace('.gif','.png',$v);
                $temp_arr[$expression_replace_array['search'][$k]] = $expression_replace_array['replace'][$k];
            }
            $root['expression_replace_array'] = $temp_arr;
        }else{
            $expression_replace_array = load_auto_cache("expression_replace_array");
        }
        $data_list = array();    
        foreach ($result_list['list'] as $k=>$v){
//    print_r($v);exit;
            $temp_data = array();
            $temp_data['id'] = $v['id'];
            $temp_data['title'] = $v['title'];
            $temp_data['content'] = $v['content'];  
            //表情替换
            if($app_type=='wap')
                $temp_data['content'] = str_replace($expression_replace_array['search'],$expression_replace_array['replace'],$temp_data['content']);
            
            $f_images = array();
            $s_img = array();
            $b_img = array();
            if(count($v['images'])>0){
                foreach ($v['images'] as $ik=>$iv){
                    $s_img[] = get_abs_img_root(get_spec_image($iv['o_path'],100,100,1));
                    $b_img[] = get_abs_img_root($iv['o_path']);
                }
            }
            $temp_data['s_img'] = $s_img;
            $temp_data['b_img'] = $b_img;
            $temp_data['share_obj'] = $v['share_obj'];
            $temp_data['share_obj']['o_path'] = get_abs_img_root($v['images'][0]['o_path']);
            $temp_data['o_path'] = get_abs_img_root($v['images'][0]['o_path']);

            if ($v['type'] == 'share'){
                $temp_data['share_obj']['image'] = get_abs_img_root(get_spec_image($v['images'][0]['o_path'],150,0));
                $temp_data['image'] = get_abs_img_root(get_spec_image($v['images'][0]['o_path'],150,0));
            }else{
                if ($v['type']=='dealcomment' || $v['type']=='youhuicomment' || $v['type']=='eventcomment' || $v['type']=='slocationcomment' || $v['type'] =='eventsubmit'){//点评数据
                    $item_obj = array();
                    $item_field = '';
                    $item_obj = explode("#",$v['rel_route']);
                    $table_name = '';
                    switch ($v['type']){
                        case 'dealcomment':
                            $table_name = DB_PREFIX.'deal';
                            $item_field = ' id,name,icon ';
                            break;
                        case 'youhuicomment':
                            $table_name = DB_PREFIX.'youhui';
                            $item_field = ' id,name,icon ';
                            break;
                        case 'eventcomment':
                            $table_name = DB_PREFIX.'event';
                            $item_field = ' id,name,icon ';
                            break;
                        case 'slocationcomment':
                            $table_name = DB_PREFIX.'supplier_location';
                            $item_field = ' id,name,preview ';
                            break;
                        case 'eventsubmit':
                            $table_name = DB_PREFIX.'event';
                            $item_field = ' id,name,icon ';
                    }
                    $item_obj_value = $GLOBALS['db']->getRow("select ".$item_field." from ".$table_name." where id=".$item_obj[1]);
                    $img_url = '';
                    $img_url = $item_obj_value['icon']?$item_obj_value['icon']:$item_obj_value['preview'];
                    $temp_data['share_obj']['type'] = $v['type'];
                    $temp_data['share_obj']['id'] = $item_obj_value['id'];
                    $temp_data['share_obj']['content'] = $item_obj_value['name'];
                    $temp_data['share_obj']['image'] = get_abs_img_root(get_spec_image($img_url,50,50,1));
                    $temp_data['share_obj']['url'] = SITE_DOMAIN.url("index",$v['rel_route'],array('r'=>base64_encode($v['user_id'])));
                    $temp_data['image'] = get_abs_img_root(get_spec_image($img_url,50,50,1));
                }else{
                    $temp_data['share_obj']['image'] = get_abs_img_root(get_spec_image($v['images'][0]['o_path'],50,50,1));
                    $temp_data['image'] = get_abs_img_root(get_spec_image($v['images'][0]['o_path'],50,50,1));
                }
				
                
            }
            
            $temp_data['image_count'] = count($v['images']);
//             $temp_data['images'] = $f_images;
            
            $temp_data['type'] = $v['type'];
            $temp_data['type_txt'] = "分享了一个".$type_array[$v['type']];
            $temp_data['user_id'] = $v['user_id'];
            $temp_data['user_name'] = $v['user_name'];
            $temp_data['user_avatar'] = get_abs_img_root(get_muser_avatar($v['user_id'],"big"))?get_abs_img_root(get_muser_avatar($v['user_id'],"big")):"";
            $temp_data['reply_count'] = $v['reply_count'];
            $temp_data['fav_count'] = $v['fav_count'];
            $temp_data['show_time'] = $this->format_show_date($v['create_time']);
            $temp_data['reply_is_move'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."topic_reply where topic_id=".$v['id'])>PAGE_SIZE?1:0;
            $reply_list = get_topic_reply_list($v['id'],'0,'.PAGE_SIZE);
            $temp_data['reply_list'] = $reply_list?$reply_list:array();
			
			$distance = $v['distance'];
			$distance_str = "";
			if( $v['xpoint']>0||$v['ypoint']>0 ){
				if($distance<=50){
					$distance_str = "距离 50 米内";
				}else if($distance>50){
					if($distance>1500){
						$distance_str = "距离".round($distance/1000)."公里";
					}else{
						$distance_str = "距离".round($distance)."米";
					}
				}
				
				$temp_data['distance'] = $distance_str;
			}
			
			if( $v['distance']>10000000 ){
				unset($temp_data['distance']);
			}

            $data_list[] = $temp_data;
        }
        $root['data_list'] = $data_list;
		$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
		$root['page_title'].="朋友圈";
		ob_clean();
		return output($root);
	}
	
	/**
	 * 我的个人主页
	 * 输入：
	 * id [int] 可选，未登录情况下必须穿ID ，登录情况下默认为自己
	 *
	 * 输出：
	 * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
	 *
	 *
	 *[is_fav] =>1         ：int 0未关注，1已经关注
      [is_why] => 2   ：int 0未登录也不查看其它人要求登录  ,1 自己，2其它登录用户看，3未登录用户看

	[data] => Array( //列表数据集
	      
                    [id] => 284         :id 主题数据ID
                    [title] =>          :string 主题标题    文章分享时候才有存在
                    [content] => 爱爱爱         ：string 主题内容
                    [o_path] => http://192.168.1.242/o2onew/public/comment/201507/24/18/d808d7474d0b6ac3577531934d2d642276.jpg  ：string 展示图原图地址
                    [image] => http://192.168.1.242/o2onew/public/comment/201507/24/18/d808d7474d0b6ac3577531934d2d642276_300x0.jpg     ：string 展示图缩略图  150*~
                    [image_count] => 3  ：int 图片总张数
                    [s_img] => Array    ：小图数组 100*100
                        (
                            [0] => http://localhost/o2onew/public/comment/201508/18/12/d86cd4c12616f17389388d266af2fce089_200x200.jpg
                        )

                    [b_img] => Array    ：原图数组
                        (
                            [0] => http://localhost/o2onew/public/comment/201508/18/12/d86cd4c12616f17389388d266af2fce089.jpg
                        )

                    [share_obj] => Array    :冗余数据给APP使用
                        (
                            [content] => 团购推荐：明视眼镜[【37店通用】明视眼镜]
                            [url] => http://localhost/o2onew/index.php?ctl=deal&act=73&r=NzE%3D
                            [o_path] => http://localhost/o2onew/public/comment/201508/18/12/d86cd4c12616f17389388d266af2fce089.jpg
                            [image] => http://localhost/o2onew/public/comment/201508/18/12/d86cd4c12616f17389388d266af2fce089_100x100.jpg   100*100
                        )

                    [type] => share     ：string 主题类型
                    [type_txt] => 分享了一个分享   ：string 分享类型说明
                    [user_id] => 71 ：int 发布用户ID
                    [user_name] => fanwe    :string 发布用户名称
                    [user_avatar] => http://192.168.1.242/o2onew/public/avatar/000/00/00/71virtual_avatar_big.jpg   ：string 发布用户头像
                    [reply_count] => 24     ：int 回复数量
                    [fav_count] => 0        ：int 喜欢数量
                    [show_time] => 6天前              ：string 发布时间格式化
                    [reply_is_move] => 1    ：int 是否有更多回复
                    [reply_list] => Array   ：array 回复列表
                        (
                            [0] => Array
                                (
                                    [id] => 77  ：int 回复数据ID
                                    [topic_id] => 284   ：int 回复主题ID
                                    [user_id] => 71     ：int 回复用户ID
                                    [user_name] => fanwe    ：string 回复用户名称
                                    [content] => 听妈妈的话，别让她受伤    ：string 回复内容
                                    [create_time] => 1438281179 ：string 回复时间
                                    [reply_id] => 0     ：int 回复，其它回复人ID
                                    [reply_user_id] => 0    ：int 被回复人的ID  
                                    [reply_user_name] =>    ：string 被回复人的名称
                                    [format_create_time] => 2015-07-31 10:32  ：string 格式化时间
                                )

                        )

       )	
       
       app 多返回的内容 ,表情替换连接
       [expression_replace_array] => Array
        (
            [[傲慢]] => http://localhost/o2onew/public/expression/qq/aoman.png
            [[白眼]] => http://localhost/o2onew/public/expression/qq/baiyan.png
            [[鄙视]] => http://localhost/o2onew/public/expression/qq/bishi.png
        )
	 *
	 * */
	public function show()
	{
	    $root = array();
	    /*参数初始化*/
	    $id = $GLOBALS['request']['id']; //用户推荐ID
	     
	    $app_type = strim($GLOBALS['request']['from'])=='android'||strim($GLOBALS['request']['from'])=='ios'?'app':'wap';	     
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $user_login_status = check_login();
	    $root['user_login_status'] = $user_login_status;
	
	    $is_why = 0; //0未登录也不查看其它人要求登录 ，1 自己，2其它登录用户看，3未登录用户看
	    if($id){
	        if($id == $user_id)
	        {
	            $is_why = 1;
	            $home_user_info = $user;
	        }
	        else
	        {
	            $is_why = 3;
	            $home_user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".$id);
	            if($home_user_info){
	                $is_why = 2;
	                $is_fav = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_focus where focus_user_id=".$user['id']." and focused_user_id=".$id)) ;
	
	            }
	        }
	    }else{
	        if(empty($user))
	        {
	            $is_why = 0;
	        }
	        else
	        {
	            $is_why = 1;
	            $home_user_info = $user;
	        }
	    }
	    $root['is_fav']= intval($is_fav);
	    $root['is_why'] = $is_why;
	
	
	
	    //返回数据列表
	    require_once APP_ROOT_PATH."system/model/topic.php";
	    
	  
	    $result = get_topic_item($id);
	
	  
	
	    //定义类型的范围
	    $type_array = array(
	        "share"=>'分享',
	        "dealcomment"=>'商品点评',
	        'youhuicomment'=>'优惠券购物点评',
	        'eventcomment'=>'活动点评',
	        'slocationcomment'=>'门店点评',
	        'eventsubmit'=>'活动报名',
	        'sharedeal'=>'商品',
	        'shareyouhui'=>'优惠券',
	        'shareevent'=>'活劝'
	    );
	    //表情替换
	    if ($app_type=='app'){
	        $expression_replace_array = load_auto_cache("expression_replace_array",array('type'=>'url'));
	        foreach ($expression_replace_array['replace'] as $k=>$v){
	            $expression_replace_array['replace'][$k] = str_replace('.gif','.png',$v);
	            $temp_arr[$expression_replace_array['search'][$k]] = $expression_replace_array['replace'][$k];
	        }
	        $root['expression_replace_array'] = $temp_arr;
	    }else{
	        $expression_replace_array = load_auto_cache("expression_replace_array");
	    }
	    $temp_data = array();
        $temp_data['id'] = $result['id'];
        $temp_data['title'] = $result['title'];
        $temp_data['content'] = $result['content'];
        
        //表情替换
        if($app_type=='wap')
            $temp_data['content'] = str_replace($expression_replace_array['search'],$expression_replace_array['replace'],$temp_data['content']);
        
        
        $images = $result['images'];
        $f_images = array();
        $s_img = array();
        $b_img = array();
        if(count($result['images'])>0){
            
            foreach ($images as $ik=>$iv){
                $s_img[] = get_abs_img_root(get_spec_image($iv['o_path'],100,100,1));
                $b_img[] = get_abs_img_root($iv['o_path']);
            }
        }
        $temp_data['s_img'] = $s_img;
        $temp_data['b_img'] = $b_img;
        $temp_data['share_obj'] = $result['share_obj'];
        $temp_data['share_obj']['o_path'] = get_abs_img_root($result['images'][0]['o_path']);
        $temp_data['o_path'] = get_abs_img_root($result['images'][0]['o_path']);
        
        if ($result['type'] == 'share'){
            $temp_data['share_obj']['image'] = get_abs_img_root(get_spec_image($result['images'][0]['o_path'],150,0));
            $temp_data['image'] = get_abs_img_root(get_spec_image($result['images'][0]['o_path'],150,0));
        }else{
            if ($result['type']=='dealcomment' || $result['type']=='youhuicomment' || $result['type']=='eventcomment' || $result['type']=='slocationcomment' || $result['type'] =='eventsubmit'){//点评数据
                $item_obj = array();
                $item_field = '';
                $item_obj = explode("#",$result['rel_route']);
                $table_name = '';
                switch ($result['type']){
                    case 'dealcomment':
                        $table_name = DB_PREFIX.'deal';
                        $item_field = ' id,name,icon ';
                        break;
                    case 'youhuicomment':
                        $table_name = DB_PREFIX.'youhui';
                        $item_field = ' id,name,icon ';
                        break;
                    case 'eventcomment':
                        $table_name = DB_PREFIX.'event';
                        $item_field = ' id,name,icon ';
                        break;
                    case 'slocationcomment':
                        $table_name = DB_PREFIX.'supplier_location';
                        $item_field = ' id,name,preview ';
                        break;
                    case 'eventsubmit':
                        $table_name = DB_PREFIX.'event';
                        $item_field = ' id,name,icon ';
                }
                $item_obj_value = $GLOBALS['db']->getRow("select ".$item_field." from ".$table_name." where id=".$item_obj[1]);
                $img_url = '';
                $img_url = $item_obj_value['icon']?$item_obj_value['icon']:$item_obj_value['preview'];
                $temp_data['share_obj']['type'] = $result['type'];
                $temp_data['share_obj']['id'] = $item_obj_value['id'];
                $temp_data['share_obj']['content'] = $item_obj_value['name'];
                $temp_data['share_obj']['image'] = get_abs_img_root(get_spec_image($img_url,50,50,1));
                $temp_data['share_obj']['url'] = SITE_DOMAIN.url("index",$v['rel_route'],array('r'=>base64_encode($v['user_id'])));
                $temp_data['image'] = get_abs_img_root(get_spec_image($img_url,50,50,1));
            }else{
                $temp_data['share_obj']['image'] = get_abs_img_root(get_spec_image($result['images'][0]['o_path'],50,50,1));
                $temp_data['image'] = get_abs_img_root(get_spec_image($result['images'][0]['o_path'],50,50,1));
            }
        }

        $temp_data['image_count'] = count($result['images']);
        $temp_data['images'] = $f_images;
        $temp_data['type'] = $result['type'];
        $temp_data['type_txt'] = "分享了一个".$type_array[$result['type']];
        $temp_data['user_id'] = $result['user_id'];
        $temp_data['user_name'] = $result['user_name'];
        $temp_data['user_avatar'] = get_abs_img_root(get_muser_avatar($result['user_id'],"big"))?get_abs_img_root(get_muser_avatar($result['user_id'],"big")):"";
        $temp_data['reply_count'] = $result['reply_count'];
        $temp_data['fav_count'] = $result['fav_count'];
        $temp_data['show_time'] = $this->format_show_date($result['create_time']);
        $reply_list = get_topic_reply_list($result['id'],'0,'.PAGE_SIZE);
        $temp_data['reply_list'] = $reply_list?$reply_list:array();
        $temp_data['reply_is_move'] = 1;//$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."topic_reply where topic_id=".$result['id'])>PAGE_SIZE?1:0;
        
	    $root['data'] = $temp_data;
	    $root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
	    $root['page_title'].="详情";
	
	    return output($root);
	}
    
	/**
	 * 加载更多的回复
	 * 输入：
	 * id [int] 主题ID
	 * page [int] 分页
	 * 输出：
	 * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
	 * 
	 * [reply_data] => Array   ：array 回复列表
                        (
                            [0] => Array
                                (
                                    [id] => 77  ：int 回复数据ID
                                    [topic_id] => 284   ：int 回复主题ID
                                    [user_id] => 71     ：int 回复用户ID
                                    [user_name] => fanwe    ：string 回复用户名称
                                    [content] => 听妈妈的话，别让她受伤    ：string 回复内容
                                    [create_time] => 1438281179 ：string 回复时间
                                    [reply_id] => 0     ：int 回复，其它回复人ID
                                    [reply_user_id] => 0    ：int 被回复人的ID  
                                    [reply_user_name] =>    ：string 被回复人的名称
                                    [format_create_time] => 2015-07-31 10:32  ：string 格式化时间
                                )

                        )
     * page:array 分页信息 array("page"=>当前页数,"page_total"=>总页数,"page_size"=>分页量,"data_total"=>数据总量);
	 * page_title:string 页面标题		
	 */
	public function load_move_reply(){
	    $root = array();
	    /*参数初始化*/
	    $id = $GLOBALS['request']['id']; //用户推荐ID
	    
	    
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $user_login_status = check_login();
	    $root['user_login_status'] = $user_login_status;
	    
	    //分页
	    $page = intval($GLOBALS['request']['page']);
	    $page=$page==0?1:$page;
	    
	    $page_size = PAGE_SIZE;
	    $limit = (($page-1)*$page_size).",".$page_size;
	    //返回数据列表
	    require_once APP_ROOT_PATH."system/model/topic.php";
	    $reply_data = get_topic_reply_list($id,$limit);
	    $root['reply_data'] = $reply_data;
	    
	    $count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."topic_reply where topic_id=".$id);
	    $page_total = ceil($count/$page_size);
	    $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
	    //end 分页
	    
	    return output($root);
	}
	
	/**
	 * 喜欢这个分享
	 * 输入：
	 * id：int 分享ID
	 * 
	 * 输出：
	 * status: int 0 失败 ，1成功
	 * info：string 消息内容
	 */
	public function do_fav_topic()
	{
	    $root = array();
	    /*参数初始化*/
	    $id = intval($GLOBALS['request']['id']); //主题ID
	    
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $user_login_status = check_login();

	    if($user_login_status!=LOGIN_STATUS_LOGINED){
	        $root['user_login_status'] = $user_login_status;
	    }
	    else
	    {
	        $root['user_login_status'] = $user_login_status;

	        $topic = $GLOBALS['db']->getRow("select id,user_id from ".DB_PREFIX."topic where id = ".$id);
	        if(!$topic)
	        {
	            return output($root,0,$GLOBALS['lang']['TOPIC_NOT_EXIST']);
	        }
	        else
	        {
	            if($topic['user_id']==$user_id)
	            {
	                return output($root,0,$GLOBALS['lang']['TOPIC_SELF']);
	            }
	            else
	            {
	                $count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."topic where (fav_id = ".$id." or (origin_id = ".$id." and fav_id <> 0)) and user_id = ".$user_id);
	                if($count>0)
	                {
	                    return output($root,0,$GLOBALS['lang']['TOPIC_FAVED']);
	                }
	                else
	                {
	                    require_once APP_ROOT_PATH."system/model/topic.php";
	                    $tid = insert_topic($content,$title="",$type="",$group="", $relay_id = 0, $id);
	                    if($tid)
	                    {
	                        increase_user_active(intval($GLOBALS['user_info']['id']),"喜欢了一则分享");
	                        $GLOBALS['db']->query("update ".DB_PREFIX."topic set source_name = '网站' where id = ".intval($tid));
	                    }
	                    return output($root,1,$GLOBALS['lang']['FAV_SUCCESS']);
	                }
	            }
	        }
	    }
	    return output($root);
	}
	
	/**
	 * 提交回复
	 * 输入：
	 * id [int] 主题ID
	 * content [string] 内容
	 * reply_id [int] 被回复的ID
	 * 
	 * 输出：
	 * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
	 * 
	 * ['data']=>array(
	 *         'reply_id' => 11  ：int 回复ID
	 *         'user_name'=> jobin  :string 回复用户名称
	 *         'user_id'=>12 :int 回复用户ID
	 *         'content'=> fdlkj   ：string 回复内容
	 * )
	 * 
	 * status ：int 0失败，1成功
	 * info    ：string  错误提示
	 */
	public function do_reply(){
	    
	    $root = array();
	    /*参数初始化*/
	    $id = intval($GLOBALS['request']['id']); //
	    $content = strim($GLOBALS['request']['content']); //
	    $reply_id = intval($GLOBALS['request']['reply_id']);
	    
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $user_login_status = check_login();
	    
	    if($user_login_status!=LOGIN_STATUS_LOGINED){
	        $root['user_login_status'] = $user_login_status;
	    }
	    else
	    {
	        $root['user_login_status'] = $user_login_status;
	        /*验证每天只允许评论5次*/
	        $day_send_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."topic_reply where create_time>".to_timespan(to_date(NOW_TIME,"Y-m-d"),"Y-m-d")." and create_time<".NOW_TIME);
// 	        if($day_send_count>=8){
// 	            return output($root,0,"今天你已经发很多了哦~");
// 	        }
	        if(!check_ipop_limit(get_client_ip(),"message",intval(app_conf("SUBMIT_DELAY")),0))
	        {
	            return output($root,0,$GLOBALS['lang']['MESSAGE_SUBMIT_FAST']);
	        }
	        $topic_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."topic where id = ".$id);
	        if(!$topic_info)
	            return output($root,0,$GLOBALS['lang']['TOPIC_NOT_EXIST']);
	         
	        $reply_data = array();
	        $reply_data['topic_id'] = $id;
	        $reply_data['user_id'] = $user_id;
	        $reply_data['user_name'] = $user['user_name'];
	        $reply_data['reply_id'] = $reply_id;
	        $reply_data['create_time'] = NOW_TIME;
	        $reply_data['is_effect'] = 1;
	        $reply_data['is_delete'] = 0;
	        $reply_data['topic_user_id'] = $topic_info['user_id'];
	        $reply_data['topic_title'] = $topic_info['title'];
	        $reply_data['content'] = strim(valid_str(addslashes($content)));
	        require_once APP_ROOT_PATH.'system/model/topic.php';
	        $reply_id = insert_topic_reply($reply_data);
	        //返回页面的数据
	        $data = array();
	        $data['reply_id'] = $reply_id;
	        $data['user_name'] = $user['user_name'];
	        $data['user_id'] = $user_id;
	        $data['content'] = $reply_data['content'];
	        $root['reply_data'] = $data;
	    }
	    
	    return output($root,1,"回复成功");
	}
	
	/**
	 * 检测是不是自己
	 * 
	 * 输入
	 * reply_id :int 主题的回复ID
	 * 输出
	 * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
	 * status 1为自己，0为别人
	 * 当为0的时候返回，主题回复的内容
	 * r_data=>array(
	 *     'id'=>1,        :int 回复数据ID
	 *     'topic_id'=>2,  ：int 主题编号ID
	 *     'user_id'=>12,  :int 回复用户ID
	 *     'user_name'=>会计法    ：string 用户名称
	 * )
	 * 
	 * status ：int 0失败，1成功
	 * info    ：string  错误提示
	 * */
	public function check_reply_user(){
	    $root = array();
	    /*参数初始化*/
	    $reply_id = strim($GLOBALS['request']['reply_id']);
	     
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $user_login_status = check_login();
	     
	    if($user_login_status!=LOGIN_STATUS_LOGINED){
	        $root['user_login_status'] = $user_login_status;
	    }
	    else
	    {
	        $root['user_login_status'] = $user_login_status;
	        $r_topic = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."topic_reply where id=".$reply_id);
	        if($r_topic){
	            if ($r_topic['user_id'] ==  $user_id){
	                return output($root,1);
	            }else{
	                $r_data = array();
	                $r_data['id'] = $r_topic['id'];
	                $r_data['topic_id'] = $r_topic['topic_id'];
	                $r_data['user_id'] = $r_topic['user_id'];
	                $r_data['user_name'] = $r_topic['user_name'];
	                $root['r_data'] = $r_data;
	                return output($root,0);
	            }
	        }
	    }
	}
	
	
	/**
	 * 用户回复删除
	 *
	 * 输入
	 * id :int 主题的回复ID
	 * 输出
	 * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
	 * status ：int 0失败，1成功
	 * info    ：string  错误提示
	 * */
	public function del_reply(){
	    $root = array();
	    /*参数初始化*/
	    $id = intval($GLOBALS['request']['id']);
	
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $user_login_status = check_login();
	
	    if($user_login_status!=LOGIN_STATUS_LOGINED){
	        $root['user_login_status'] = $user_login_status;
	    }
	    else
	    {
	        $root['user_login_status'] = $user_login_status;
	        $r_topic = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."topic_reply where id=".$id." and user_id=".$user_id);
	        if($r_topic){
	            $GLOBALS['db']->query("delete from ".DB_PREFIX."topic_reply where id=".$id." and user_id=".$user_id);
	            return output($root,1,"删除成功");
	        }else{
	            return output($root,0,"没有删除的权限");
	        }
	    }
	}
	
	
	/**
	 * 关注
	 *
	 * 输入
	 * uid :int 用户ID
	 * 输出
	 * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
	 * tag     ：int  1取消关注， 2关注， 3不能关注自己， 4用户未登录
	 * html    ：string 状态提示
	 * status  ：int 0失败，1成功
	 * info    ：string  错误提示
	 * */
	public function focus()
	{
	    $root = array();
	    /*参数初始化*/
	    $uid = intval($GLOBALS['request']['uid']);
	
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $user_login_status = check_login();
	
	    if($user_login_status!=LOGIN_STATUS_LOGINED){
	        $data['tag'] = 4;
	        $root['user_login_status'] = $user_login_status;
	    }
	    else
	    {
	        $root['user_login_status'] = $user_login_status;
	    }
	    
	    $focus_uid = $uid;
	    if($user_id==$focus_uid)
	    {
	        $root['tag'] = 3;
	        $root['html'] = $GLOBALS['lang']['FOCUS_SELF'];
	        return output($root,0,$GLOBALS['lang']['FOCUS_SELF']);
	    }
	
	    $focus_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_focus where focus_user_id = ".$user_id." and focused_user_id = ".$focus_uid);
	    //刷新用户列表
	    rm_auto_cache("cache_focus_user",array("id"=>$user_id));
	    rm_auto_cache("cache_focus_user",array("id"=>$focus_uid));
	
	    if(!$focus_data&&$user_id>0&&$focus_uid>0)
	    {
	        $focused_user_name = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".$focus_uid);
	        $focus_data = array();
	        $focus_data['focus_user_id'] = $user_id;
	        $focus_data['focused_user_id'] = $focus_uid;
	        $focus_data['focus_user_name'] = $GLOBALS['user_info']['user_name'];
	        $focus_data['focused_user_name'] = $focused_user_name;
	        	
	        	
	        $GLOBALS['db']->autoExecute(DB_PREFIX."user_focus",$focus_data,"INSERT");
	        //判断是否互相关注
	        if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_focus where focus_user_id = ".$focus_uid." and focused_user_id=".$user_id)){
	            $GLOBALS['db']->query("update ".DB_PREFIX."user_focus set to_focus = 1 where (focus_user_id = ".$focus_uid." and focused_user_id=".$user_id.") or (focus_user_id = ".$user_id." and focused_user_id=".$focus_uid.")");
	        }
	        	
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set focus_count = focus_count + 1 where id = ".$user_id);
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set focused_count = focused_count + 1 where id = ".$focus_uid);
	        //刷新用户缓存
	        load_user($user_id,true);
	        load_user($focus_uid,true);
	        $root['tag'] = 1;
	        $root['html'] = $GLOBALS['lang']['CANCEL_FOCUS'];
	        return output($root,1,'关注成功');
	    }
	    elseif($focus_data&&$user_id>0&&$focus_uid>0)
	    {
	        $GLOBALS['db']->query("delete from ".DB_PREFIX."user_focus where focus_user_id = ".$user_id." and focused_user_id = ".$focus_uid);
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set focus_count = focus_count - 1 where id = ".$user_id);
	        $GLOBALS['db']->query("update ".DB_PREFIX."user set focused_count = focused_count - 1 where id = ".$focus_uid);
	        $GLOBALS['db']->query("update ".DB_PREFIX."user_focus set to_focus = 0 where (focus_user_id = ".$focus_uid." and focused_user_id=".$user_id.") or (focus_user_id = ".$user_id." and focused_user_id=".$focus_uid.")");
	        //刷新用户缓存
	        load_user($user_id,true);
	        load_user($focus_uid,true);
	        $root['tag'] =2;
	        $root['html'] = $GLOBALS['lang']['FOCUS_THEY'];
	        return output($root,1,'取消关注成功');
	    }
	    return output($root);
	
	}
	
	/**
	 * 发布接口
	 * 输入：
	 * app_type [string] 请求类型，APP  ，wap
	 * content [string] 分享内容
	 * [file] => Array
	 *
	 * 输出：
	 * user_login_status:int 用户登录状态(1 已经登录/0 用户未登录/2临时用户)
	 * ['expression']=>array(  
	 *     [qq] => Array
                (
                    [0] => Array
                        (
                            [type] => qq
                            [title] => 傲慢
                            [emotion] => 傲慢
                            [filename] => http://localhost/o2onew/public/expression/qq/aoman.gif
                        )
            )
	 * )
	 *
	 */
	public function publish(){
	    $app_type = strim($GLOBALS['request']['from'])=='android'||strim($GLOBALS['request']['from'])=='ios'?'app':'wap';
	    //检查用户,用户密码
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $root = array();
	    
	    $user_login_status = check_login();
	    if($user_login_status!=LOGIN_STATUS_LOGINED){
	        $root['user_login_status'] = $user_login_status;
	        return output($root,0,'请先登录');
	    }
	    else
	    {
	        $root['user_login_status'] = $user_login_status;
	        //输出表情数据html
	        $result = $GLOBALS['db']->getAll("select `type`,`title`,`emotion`,`filename` from ".DB_PREFIX."expression order by type");
	        $expression = array();
	        $qq_count = 0;
	        $tusiji_count = 0;
	        foreach($result as $k=>$v)
	        {
	            $type = $v['type'];
	            
	            if($v['type']=='qq'){
	                $v['filename'] = get_abs_img_root("./public/expression/".$v['type']."/".$v['filename']);
	                if($app_type=='app')
	                   $v['filename'] = str_replace('.gif','.png',$v['filename']);
	                if($app_type=='wap')
	                    $v['emotion'] = str_replace(array('[',']'),array('',''),$v['emotion']);
	                $expression[$type][] = $v;
	            }
	            
	        }
	        $root['expression'] = $expression;
	    }
	    $root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
	    $root['page_title'].="发表";
	    return output($root);
	    
	    
	}
	
	/**
	 * 发布提交接口
	 * 输入：
	 * content [string] 分享内容
	 * [file] => Array
	 (
    	 [name] => array(
    	       [0]=>0b46f21fbe096b63376be90e0f338744ebf8ac7a.jpg
    	 )     [array] 图片名称数组
    	 [type] => array(
    	       [0]=>image/jpeg
    	 )     [array] 图片类型数组
    	 [tmp_name] => array(
    	       [0]=>C:\Windows\Temp\phpBAA4.tmp
    	 )     [array] 图片临时文件
    	 [error] => array(
    	       [0]=>0
    	 )     [array] 图片报错
    	 [size] => array(
    	       [0]=>37393
    	 )     [array] 图片大小
	 )
	 *
	 * 输出：
	 * user_login_status:int 用户登录状态(1 已经登录/0 用户未登录/2临时用户)
	 * topic_id int 分享ID
	 * status ：int 0失败，1成功
	 * info    ：string  错误提示
	 */
	public function do_publish(){
	    
	    $app_type = strim($GLOBALS['request']['from'])=='android'||strim($GLOBALS['request']['from'])=='ios'?'app':'wap';
	    
	    //检查用户,用户密码
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $root = array();
	    
	    $user_login_status = check_login();
	    if($user_login_status!=LOGIN_STATUS_LOGINED){
	        $root['user_login_status'] = $user_login_status;
	        return output($root,0,'请先登录');
	    }
	    else
	    {
	        $root['user_login_status'] = $user_login_status;
	        $content = strim($GLOBALS['request']['content']);
	        require_once APP_ROOT_PATH."system/libs/words.php";
	        $tags = words::segment($content);
	        if($app_type == 'wap'){//wap 需要处理文件数组
	           if(count($_FILES)>0){
	               $file = array();
	               foreach ($_FILES as $k=>$v){
	                   $temp_name[] = $v['name'];
	                   $temp_type[] = $v['type'];
	                   $temp_tmp_name[] = $v['tmp_name'];
	                   $temp_error[] = $v['error'];
	                   $temp_size[] = $v['size'];
	               }
	               $file['file']['name'] = $temp_name;
	               $file['file']['type'] = $temp_type;
	               $file['file']['tmp_name'] = $temp_tmp_name;
	               $file['file']['error'] = $temp_error;
	               $file['file']['size'] = $temp_size;
	                
	               if(count($file['file']['name'])>3)
	               {
	                   return output($root,0,'上传图片不能超过3张');
	               }
	           }
	        }elseif ($app_type == "app"){
	            $file = $_FILES;
	            if(count($file['file']['name'])>9)
	            {
	                return output($root,0,'上传图片不能超过9张');
	            }
	        } 
	       $attach_list = array();
           if(count($file['file']['name'])>0){
               
                //同步图片
                foreach($file['file']['name'] as $k=>$v)
                {
                    $_files['file']['name'] = $v;
                    $_files['file']['type'] = $file['file']['type'][$k];
                    $_files['file']['tmp_name'] = $file['file']['tmp_name'][$k];
                    $_files['file']['error'] = $file['file']['error'][$k];
                    $_files['file']['size'] = $file['file']['size'][$k];
        
                    $res = upload_topic($_files);
        
                    if($res['error']==0)
                    {
                        $topic_image =array();
                        $topic_image['type'] = "image";
                        $topic_image['id'] =  intval($res['id']);
                        $attach_list[] = $topic_image;
                    }
                }
                
                
            }
            $type = "share";
            $group = "share";
            
            require_once APP_ROOT_PATH.'/system/model/topic.php';
            $id = insert_topic($content,$title,$type,$group, $relay_id = 0, $fav_id = 0,"",$attach_list,array(),$tags,'','','',0,0);
            $root['topic_id'] = $id;
            if($id)
            {
                $GLOBALS['db']->query("update ".DB_PREFIX."topic set source_name = '网站' where id = ".intval($id));
                increase_user_active($user_id,"发表了一则分享");
            }
            
            return output($root,1,"发布成功");
            
	    }
	}
	
	
	public function format_show_date($time){
        $t=NOW_TIME-$time;
        $f=array(
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        );
        foreach ($f as $k=>$v)    {
            if (0 !=$c=floor($t/(int)$k)) {
                return $c.$v.'前';
            }
        }
    }
}
?>