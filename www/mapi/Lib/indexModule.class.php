<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class indexApiModule extends MainBaseApiModule
{
	
	
	/**
	 * wap版首页接口
	 * 输入：
	 * 无
	 * 
	 * 输出：
	 * advs: array 首页广告
	 * 结构如下
	 * Array
       (
            [0] => Array
                (
                    [id] => 21 [int] 广告的ID
                    [name] => 商品明细 [string] 广告名称
                    [img] => http://localhost/o2onew/public/attachment/sjmapi/5451eb7862ae7.jpg [string] 广告图片 640x360
                    [data] => Array [array] 以key->value方式存储的内容 用于url参数组装
                        (
                            [url] => http://www 
                        )

                    [ctl] => url [string] 定义的ctl
                )
       )
	 * indexs: array 首页菜单
	 * 结构如下
	 * Array
        (
            [0] => Array
                (
                    [id] => 71 [int] 菜单ID
                    [name] => 9.9包邮 [string] 菜单名称
                    [icon_name] => [string] 菜单图标 
                    [color] => #39b778 [string] 菜单颜色
                    [data] => Array [array] 以key->value方式存储的内容 用于url参数组装
                        (
                            [cate_id] => 
                        )

                    [ctl] => tuan [string] 定义的ctl
               )
       )
		
	 * newest_lottery_list :array 最新中奖列表
	 * 结构如下
	 Array
        (
            [0] => Array
                (
                    [name] => 荷兰牛栏Nutrilon 婴儿奶粉
                    [id] => 10000313
                    [lottery_time] => 1453451975
                    [user_name] => fanwe1
                    [span_time] => 24
                )

        )


	 * newest_doubao_list :array 最新揭晓列表
	 * 结构如下
	 * Array
        (
            [0] => Array
                (
                    [id] => 10000319
                    [icon] => http://localhost/yydb/public/attachment/201509/19/11/55fcd7ded00da_280x280.jpg
                    [lottery_time] => 1455450975
                )

        )
	
	 * page_title:string 页面标题
	 * mobile_btns_download:string 手机下载链接
	 * 
	 */
	public function wap()
	{
		global $is_app;
		$root = array();
		$root['return'] = 1;
		
		$city_id = $GLOBALS['city']['id'];
		$city_name =  $GLOBALS['city']['name'];
		$user_data = $GLOBALS['user_info'];
		$user_login_status = check_login();
		if($user_login_status==LOGIN_STATUS_LOGINED){
		    $root['mobile']=$user_data['mobile'];
		    $root['id']=$user_data['id'];
		}
		
		$page = intval($GLOBALS['request']['page']);
		if($page==0){
			$page = 1;
		}
		
		$order = strim($GLOBALS['request']['order']);
		$order_dir=intval($GLOBALS['request']['order_dir']);
		
		//首页导航标签排序
		$res = $GLOBALS['db']->getRow("select value from ".DB_PREFIX."conf where name = 'MNAV_SORT' ");
		$config_list=unserialize($res['value']);
		$mnav_sort=array();
		$new_config_list=array();
		foreach ($config_list as $k => $v){
		    if ($v['is_effect']==1) {
		        $mnav_sort[]=$config_list[$k]['sort'];
		        $new_config_list[$k]=$v;
		    }
		}
		$min_key=min($mnav_sort);
		array_multisort($mnav_sort,$new_config_list);
		foreach ($config_list as $kk => $vv){
		    if ($vv['sort']==$min_key) {
		        $sort=$vv['config_name'];
		    }
		}
		
		$root['mnav_sort']=$new_config_list;
		if($order==''){
		    $order=$sort;
		}
		$root['city_id'] = $city_id;
		$root['city_name'] = $city_name;
		$adv_list = $GLOBALS['cache']->get("WAP_INDEX_ADVS_".intval($city_id));
		 
		//广告列表
		if($adv_list===false)
		{		
			if($is_app)
			{
				$sql = " select * from ".DB_PREFIX."m_adv where mobile_type = '0' and position=0 and city_id in (0,".intval($city_id).") and status = 1 order by sort desc ";
				$advs = $GLOBALS['db']->getAll($sql);
				if(empty($advs))
				{
					$sql = " select * from ".DB_PREFIX."m_adv where mobile_type = '1' and position=0 and city_id in (0,".intval($city_id).") and status = 1 order by sort desc ";
					$advs = $GLOBALS['db']->getAll($sql);
				}
			}			
			else
			{
				$sql = " select * from ".DB_PREFIX."m_adv where mobile_type = '1' and position=0 and city_id in (0,".intval($city_id).") and status = 1 order by sort desc ";
				$advs = $GLOBALS['db']->getAll($sql);
			}
				
				
			$adv_list = array();
			foreach($advs as $k=>$v)
			{
				$adv_list[$k]['id'] = $v['id'];
				$adv_list[$k]['name'] = $v['name'];
				$adv_list[$k]['img'] = get_abs_img_root($v['img']);  //首页广告图片规格为 宽: 640px 高: 240px
				$adv_list[$k]['type'] = $v['type'];
				$adv_list[$k]['data'] = $v['data'] = unserialize($v['data']);
				$adv_list[$k]['ctl'] = $v['ctl'];
			}
			$GLOBALS['cache']->set("WAP_INDEX_ADVS_".intval($city_id),$adv_list,300);
		}
		$root['advs'] = $adv_list?$adv_list:array();
		//$domain = app_conf("PUBLIC_DOMAIN_ROOT")==''?get_domain().APP_ROOT:app_conf("PUBLIC_DOMAIN_ROOT");
		//$root['get_domain'] = $domain;
		//return output($root);
		
		//首页菜单列表
		$indexs_list = $GLOBALS['cache']->get("WAP_INDEX_INDEX_".intval($city_id));
		if($indexs_list===false)
		{
			if($is_app)
			{
				$indexs = $GLOBALS['db']->getAll(" select * from ".DB_PREFIX."m_index where status = 1 and mobile_type = 0 and city_id in (0,".intval($city_id).") order by sort asc");
				if(empty($indexs))
					$indexs = $GLOBALS['db']->getAll(" select * from ".DB_PREFIX."m_index where status = 1 and mobile_type = 1 and city_id in (0,".intval($city_id).") order by sort asc");
			}
			else
			$indexs = $GLOBALS['db']->getAll(" select * from ".DB_PREFIX."m_index where status = 1 and mobile_type = 1 and city_id in (0,".intval($city_id).") order by sort asc");
			$indexs_list = array();
			foreach($indexs as $k=>$v)
			{
				$indexs_list[$k]['id'] = $v['id'];
				$indexs_list[$k]['name'] = $v['name'];
				$indexs_list[$k]['icon_name'] = $v['vice_name'];//图标名 http://fontawesome.io/icon/bars/
				$indexs_list[$k]['color'] = $v['desc'];//颜色
				$indexs_list[$k]['data'] = $v['data'] = unserialize($v['data']);
				$indexs_list[$k]['ctl'] = $v['ctl'];
			}
				
			$indexs_list=array_chunk($indexs_list,8);	
			$GLOBALS['cache']->set("WAP_INDEX_INDEX_".intval($city_id),$indexs_list,300);
		}
		
		$root['indexs'] = $indexs_list?$indexs_list:array();	
		
		

		require_once APP_ROOT_PATH."system/model/duobao.php";

		$index_duobao = duobao::get_list(0,$order,false,$page,$order_dir);
		$index_duobao_list=$index_duobao['list'];
		$total=$index_duobao['count'];
		foreach($index_duobao_list as $k=>$v)
		{
			$index_duobao_list[$k]['icon']=get_abs_img_root(get_spec_image($v['icon'],280,280,1));
			$index_duobao_list[$k]['progress']=round($v['current_buy']/$v['max_buy'],2)*100;
		}

		$root['index_duobao_list'] = $index_duobao_list?$index_duobao_list:array();
		
		
		$newest_doubao_list=duobao::get_newest_list(3);
		
		foreach($newest_doubao_list as $k=>$v)
		{
			$newest_doubao_list[$k]['icon']=get_abs_img_root(get_spec_image($v['icon'],280,280,1));

			
		}
		$root['newest_doubao_list']=$newest_doubao_list;
		
		
		$newest_lottery_list=duobao::get_lottery_list(10);
		
		foreach($newest_lottery_list as $k=>$v)
		{
			$newest_lottery_list[$k]['span_time']=duobao::format_lottery_time($v['lottery_time']);
		}
		
		//购物车
		$root['cart_info']=duobao::getcart($GLOBALS['user_info']['id']);
		
		//分页
		$page_size = PAGE_SIZE;
		$page_total = ceil($total/$page_size);
		$root['order']=$order;
		$root['order_dir']=$order_dir;
		
		$root['newest_lottery_list']=$newest_lottery_list;
		$root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$total);
		//推荐位
		$root['zt_html'] = load_zt();
		$root['now_time']=NOW_TIME;
		$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
		$root['page_title'].="首页";
		$root['mobile_btns_download'] = wap_url("index","app_download");
		
		
		return output($root);
	}
        
        /**
         * 异步加载首页数据
         */
        public function wap_load_page(){
            $page = intval($GLOBALS['request']['page']);
            $order = strim($GLOBALS['request']['order']);
            $order_dir=intval($GLOBALS['request']['order_dir']);
            
           
            
            //首页导航标签排序
            $res = $GLOBALS['db']->getRow("select value from ".DB_PREFIX."conf where name = 'MNAV_SORT' ");
            $config_list=unserialize($res['value']);
            $mnav_sort=array();
            $new_config_list=array();
            foreach ($config_list as $k => $v){
                if ($v['is_effect']==1) {
                    $mnav_sort[]=$config_list[$k]['sort'];
                    $new_config_list[$k]=$v;
                }
            }
            $min_key=min($mnav_sort);
            array_multisort($mnav_sort,$new_config_list);
            foreach ($config_list as $kk => $vv){
                if ($vv['sort']==$min_key) {
                    $sort=$vv['config_name'];
                }
            }
            $root['mnav_sort']=$new_config_list;
            if($order==''){
                $order=$sort;
            }
            
            require_once APP_ROOT_PATH."system/model/duobao.php";

            $index_duobao = duobao::get_list(0,$order,false,$page,$order_dir);
            $index_duobao_list=$index_duobao['list'];
            $total=$index_duobao['count'];
            foreach($index_duobao_list as $k=>$v)
            {
                    $index_duobao_list[$k]['icon']=get_abs_img_root(get_spec_image($v['icon'],280,280,1));
                    $index_duobao_list[$k]['progress']=round($v['current_buy']/$v['max_buy'],2)*100;
            }

            $root['index_duobao_list'] = $index_duobao_list?$index_duobao_list:array();
            //分页
            $page_size = PAGE_SIZE;
            $page_total = ceil($total/$page_size);
            $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$total);
            $root['order']=$order;
            $root['order_dir']=$order_dir;

            return output($root);
        }
        
        //注册送夺宝币
        public function ecv(){
            $user_data = $GLOBALS['user_info'];
            //新用户送夺宝币是否开启
            $conf_red_packet=$GLOBALS['db']->getOne(" select value from ".DB_PREFIX."conf where name = 'USER_REGISTER_BRIBERY_MONEY' and is_effect = 1");
            if($conf_red_packet==1){
                es_cookie::set("send_money","1");
                //新用户送夺宝币金额
                $conf_red_packet_money=$GLOBALS['db']->getOne(" select value from ".DB_PREFIX."conf where name = 'USER_REGISTER_MONEY' and is_effect = 1");
                $account_data['send_money'] = 1;
                $account_data['money'] = $conf_red_packet_money;
                $log_msg="首次注册送您".$conf_red_packet_money."夺宝币哦";
                modify_account($account_data, $user_data['id'] ,$log_msg);
                send_msg($user_data['id'], $log_msg, "notify");
            }
        }

	
}
?>