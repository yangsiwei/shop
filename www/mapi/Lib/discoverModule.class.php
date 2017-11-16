<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class discoverApiModule extends MainBaseApiModule
{
	
	/**
	 * 发现
	 * 输入：
	 * page [int] 分页
	 * tag [string] 标签
	 *
	 *
	 * 输出：
	 * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
	 *
    	[tag_is_check] => 0    :int 标签是否有选中的
        [tag_list] => Array     :array 标签列表
            (
                [0] => Array
                    (
                        [id] => 12          :int ID
                        [name] => 美食                      :string 名称
                        [is_check] => 0     :int  是否选中   0未选中，1选中
                    )
             )
	   [data_list] => Array( //列表数据集
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

        [share_obj] => Array    ：app 用到的对象
        (
            [id] => 73
            [content] => 团购推荐：明视眼镜[【37店通用】明视眼镜]
            [url] => http://localhost/o2onew/index.php?ctl=deal&act=73&r=NzE%3D
            [o_path] => http://localhost/o2onew/public/comment/201508/18/12/d86cd4c12616f17389388d266af2fce089.jpg
            [image] => http://localhost/o2onew/public/comment/201508/18/12/d86cd4c12616f17389388d266af2fce089_100x100.jpg   50*50
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

            ),
		[distance]	=>	距离469米	

    )
     * page:array 分页信息 array("page"=>当前页数,"page_total"=>总页数,"page_size"=>分页量,"data_total"=>数据总量);
	 * page_title:string 页面标题			
	 *
	 * */
	public function index()
	{
	    $root = array();
	    /*参数初始化*/
	    $app_type = strim($GLOBALS['request']['from'])=='android'||strim($GLOBALS['request']['from'])=='ios'?'app':'wap';
	     
	    $user = $GLOBALS['user_info'];
	    $user_id  = intval($user['id']);
	    $user_login_status = check_login();
	    $root['user_login_status'] = $user_login_status;

	    $cid = intval($GLOBALS['request']['cid']); //预留暂时没用
	  
	    $tag = strim($GLOBALS['request']['tag']);
	    $root['tag_is_check'] = $tag?1:0;
	
	    if($cid==0)
	        $tag_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."topic_tag where is_recommend = 1 order by sort desc limit 10");
	    foreach ($tag_list as $k=>$v){
	        $temp = array();
	        $temp['id'] = $v['id'];
	        $temp['name'] = $v['name'];
	        if($v['name']==$tag){
	            $temp['is_check'] =1;
	        }else{
	            $temp['is_check'] =0;
	        }
	        $tag_data[] = $temp;
	    }
	    
	    $root['tag_list'] = $tag_data;
	    
        //返回数据列表
        require_once APP_ROOT_PATH."system/model/topic.php";
        //分页
        $page = intval($GLOBALS['request']['page']);
        $page=$page==0?1:$page;
        
        $page_size = PAGE_SIZE;
        $limit = (($page-1)*$page_size).",".$page_size;

        $condition = ' is_effect = 1 and is_delete = 0 ';
		$param['cid'] = 0;
		$param['tag'] = $tag;
		$param_condition = build_topic_filter_condition($param);
		$condition.=" ".$param_condition;
		$condition.=" and fav_id = 0 and relay_id = 0 and has_image = 1 and type in ('share','sharedeal','shareyouhui','shareevent') ";
		$excondition = " fav_id = 0 and relay_id = 0 and has_image = 1 and type in ('share','sharedeal','shareyouhui','shareevent') ";
		
		//开始身边团购的地理定位
		//return output($current_geo);
		$tname = 't';
		$current_geo = es_session::get("current_geo");
		$ypoint =  $current_geo['ypoint'];  //ypoint
		$xpoint =  $current_geo['xpoint'];  //xpoint
		$pi = PI;  //圆周率
		$r = EARTH_R;  //地球平均半径(米)
		$field_append = ", (ACOS(SIN(($ypoint * $pi) / 180 ) *SIN(($tname.ypoint * $pi) / 180 ) +COS(($ypoint * $pi) / 180 ) * COS(($tname.ypoint * $pi) / 180 ) *COS(($xpoint * $pi) / 180 - ($tname.xpoint * $pi) / 180 ) ) * $r) as distance ";
		if(empty($sort_field)){
			$sort_field = " distance asc ";
		}
		//5公里以内
		$excondition .= " and ($tname.xpoint=0 or $tname.ypoint=0 or (ACOS(SIN(($ypoint * $pi) / 180 ) *SIN(($tname.ypoint * $pi) / 180 ) +COS(($ypoint * $pi) / 180 ) * COS(($tname.ypoint * $pi) / 180 ) *COS(($xpoint * $pi) / 180 - ($tname.xpoint * $pi) / 180 ) ) * $r) <= ".DISCOVER_DISTANCE.") ";
				
        $result_list = get_topic_list($limit,array("cid"=>$cid,"tag"=>$tag),"",$excondition,$sort_field,$tname,$field_append);

        $count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."topic where ".$condition);
        $page_total = ceil($count/$page_size);
        $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);
        //end 分页
        
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
        $data_list = array();    
        foreach ($result_list['list'] as $k=>$v){
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
                
                foreach ($images as $ik=>$iv){
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
                $temp_data['share_obj']['image'] = get_abs_img_root(get_spec_image($v['images'][0]['o_path'],50,50,1));
                $temp_data['image'] = get_abs_img_root(get_spec_image($v['images'][0]['o_path'],50,50,1));
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
		$root['geo']=$GLOBALS['geo']?$GLOBALS['geo']:array();
        $root['data_list'] = $data_list;
		$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
		$root['page_title'].="发现";
		
		return output($root);
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