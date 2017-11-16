<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class duobaoApiModule extends MainBaseApiModule
{

    /**
     * 夺宝商品详细页
     * 输入：data_id  int  商品ID
     * page:int 夺宝记录分页页数
     * 输出：
     *
Array
(
    [user_login_status] => 1  //登录状态

    [next_id] => 10000422  //下期 期 号
    [my_duobao_log] => Array 我的夺宝记录
        (
        )

    [my_duobao_count] => 0 我夺宝记录总数
    [duobao_order_list] => Array  参与的人
        (
            [0] => Array
                (
                    [id] => 431
                    [deal_id] => 121
                    [duobao_id] => 311
                    [duobao_item_id] => 10000358
                    [number] => 5
                    [unit_price] => 1.0000
                    [total_price] => 5.0000
                    [delivery_status] => 0
                    [name] => 飘飘龙 泰迪熊公仔毛绒玩具熊
                    [return_score] => 0
                    [return_total_score] => 0
                    [verify_code] =>
                    [order_sn] => 2016012407181468
                    [order_id] => 402
                    [is_arrival] => 0
                    [deal_icon] => ./public/attachment/201601/22/21/56a22a7bc77cf.jpg
                    [user_id] => 229
                    [lottery_sn_send] => 1
                    [lottery_sn] => 0
                    [order_ip] =>
                    [order_to_area] =>
                    [duobao_ip] => 61.232.157.182
                    [duobao_area] => 辽宁省大连市
                    [user_name] => 必中
                    [avatar] => http://localhost/yydb/public/avatar/noavatar_small.gif
                    [update_time] => 1453605494
                    [update_time_format] => 2016-01-24 19:18:14
                )



        )

    [page] => Array  //分页
        (
            [page] => 1
            [page_total] => 2
            [page_size] => 20
            [data_total] => 27
        )

    [cart_data] => Array
        (
            [cart_item_num] => 0
        )

    [item_data] => Array //夺宝数据
        (
            [id] => 10000358
            [deal_id] => 121
            [duobao_id] => 311
            [name] => 飘飘龙 泰迪熊公仔毛绒玩具熊
            [cate_id] => 43
            [description] => <p><img src="./public/attachment/201601/22/21/56a22a6eb1014.jpg" alt="" border="0" /><br />
</p>
<p><img src="./public/attachment/201601/22/21/56a22a75e2315.jpg" alt="" border="0" /><br />
</p>

            [is_effect] => 1
            [brief] => 陪着你每晚每晚制造好梦 1.2米 (颜色随机)
            [icon] => http://localhost/yydb/public/attachment/201601/22/21/56a22a7bc77cf.jpg
            [brand_id] => 32
            [deal_gallery] => Array
                (
                    [0] => http://localhost/yydb/public/attachment/201601/22/21/56a22a9342a56_800x800.jpg
                    [1] => http://localhost/yydb/public/attachment/201601/22/21/56a22a8db123f_800x800.jpg
                    [2] => http://localhost/yydb/public/attachment/201601/22/21/56a22a89198c1_800x800.jpg
                    [3] => http://localhost/yydb/public/attachment/201601/22/21/56a22a8508a39_800x800.jpg
                    [4] => http://localhost/yydb/public/attachment/201601/22/21/56a22a7bc77cf_800x800.jpg
                )

            [create_time] => 1453605233
            [duobao_score] => 0
            [invite_score] => 0
            [max_buy] => 118
            [min_buy] => 1
            [fair_type] => wy
            [robot_end_time] => 120
            [robot_is_db] => 1
            [current_buy] => 118
            [progress] => 100
            [lottery_sn] => 100000081
            [has_lottery] => 1
            [success_time] => 1453611386
            [lottery_time] => 1453612814
            [fair_sn] => 89187
            [fair_sn_local] => 82083060273
            [fair_period] => 160124090
            [luck_user_id] => 230
            [click_count] => 0
            [duobao_status] => 2
            [surplus_count] => 0
            [lottery_time_format] => 2016-01-24 21:20:14
            [create_time_format] => 2016-01-24 19:13:53
            [luck_lottery] => Array
                (
                    [id] => 106281
                    [deal_id] => 121
                    [duobao_id] => 311
                    [duobao_item_id] => 10000358
                    [lottery_sn] => 100000081
                    [user_id] => 230
                    [order_id] => 903
                    [order_item_id] => 932
                    [create_time] => 1453610272.1195
                    [is_luck] => 1
                    [duobao_ip] => 171.12.161.45
                    [duobao_area] => 河南省
                    [user_name] => 小宝宝
                    [user_total] => 5
                )

        )

    [page_title] => 奖品详情
    [ctl] => duobao
    [act] => index
    [status] => 1
    [info] =>
    [city_name] =>
    [return] => 1
    [sess_id] => f164a8boa212sugq2b1lbsv3p1
    [ref_uid] =>
)

     *
     */
	public function index()
	{
	    /*参数列表*/
		$id = intval($GLOBALS['request']['data_id']);
		$dbid = intval($_REQUEST['dbid']);
		$page = intval($GLOBALS['request']['page'])?intval($GLOBALS['request']['page']):1; //当前分页

		$root = array();

		if($dbid)
		{
		    $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where duobao_id=".$dbid." and progress < 100 order by create_time desc");
		}else{
            $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$id." and is_effect = 1");
		}
        if(empty($item_data))
        {
        	return output(array(),0,"夺宝活动已过期");
        }
        // 和夺宝计划共用库存
        $item_data['total_buy_stock'] = $GLOBALS['db']->getOne("select total_buy_stock from ".DB_PREFIX."deal where id=".$item_data['deal_id']);
        
        $item_data['deal_gallery'] = unserialize($item_data['deal_gallery']);
        foreach($item_data['deal_gallery'] as $k=>$v)
        {
        	$item_data['deal_gallery'][$k] = get_abs_img_root(get_spec_image($v,400,400,1));
        }
		//用户状态
        $user_login_status = check_login();
        if($user_login_status == LOGIN_STATUS_LOGINED){
        	$user_info = $GLOBALS['user_info'];
        	//用户参与的数据
        	$root['user_login_status'] = $user_login_status;
        }


        //商品状态
        $duobao_status = 0;  //0 进行中  1 倒计时  2已揭晓
        if($item_data['has_lottery']==0){//未开奖
        	if($item_data['success_time']==0){//未成功
        		$duobao_status = 0;
        	}else{
        		$duobao_status=1;
        	}
        }else{
        	$duobao_status = 2;
        }
        $item_data['duobao_status']         = $duobao_status;
        $item_data['icon']                  = get_abs_img_root($item_data['icon']);
        $item_data['surplus_count']         = $item_data['max_buy'] - $item_data['current_buy'];
        $item_data['lottery_time_format']   = to_date($item_data['lottery_time']);
        $item_data['create_time_format']    = to_date($item_data['create_time']);
        $item_data['total_buy_price']       = format_sprintf_price($item_data['total_buy_price']);
        
    	$item_data['click_count']++;
    	$sql="update ".DB_PREFIX."duobao_item set click_count =".$item_data['click_count']." where id=".$id;
    	$GLOBALS['db']->query($sql);
    	
        //输出揭晓的相关数据
        if($duobao_status==2)
        {
            require_once APP_ROOT_PATH."system/model/user.php";
        	$duobao_item_log = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($item_data['id'])." where lottery_sn = '".$item_data['lottery_sn']."' and duobao_item_id = ".$item_data['id']);
        	$luck_user_info = load_user($item_data['luck_user_id']);
        	
        	$duobao_item_log['user_name'] = $luck_user_info['user_name'];
        	$duobao_item_log['user_logo'] = $luck_user_info['user_logo'];
        	$duobao_item_log['avatar'] = get_abs_img_root(get_muser_avatar($luck_user_info['user_id'],"big"))?get_abs_img_root(get_muser_avatar($luck_user_info['user_id'],"big")):"";
        	
        	$duobao_item_log['user_total'] = $item_data['luck_user_buy_count'];
        	$item_data['luck_lottery'] = $duobao_item_log;
        }
        
        //倒计时
        if($duobao_status==1)
        {
        	$item_data['now_time'] = NOW_TIME;
        }

        if($duobao_status!=0)
        {
        	$root['next_id'] = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."duobao_item where duobao_id = ".$item_data['duobao_id']." and success_time = 0");
        }

        //我的参与
        $root['my_duobao_log'] = $GLOBALS['db']->getAll("select lottery_sn from ".duobao_item_log_table($item_data)." where user_id = ".intval($GLOBALS['user_info']['id'])." and duobao_item_id = ".$item_data['id']);
        $root['my_duobao_count'] = count($root['my_duobao_log']);

        //所有参与记录
        $page_size = PAGE_SIZE;
        $limit = (($page - 1) * $page_size) . "," . $page_size;
        $order_list_sql = "select doi.*,u.user_name,u.avatar,u.user_logo from ".DB_PREFIX."deal_order_item as doi left join ".DB_PREFIX."user as u on u.id = doi.user_id where doi.duobao_item_id = ".$item_data['id']." and doi.lottery_sn=0 and doi.refund_status = 0 and doi.pay_status = 2  and doi.lottery_sn_send=1 order by doi.create_time desc limit ".$limit;

        $root['duobao_order_list'] = $GLOBALS['db']->getAll($order_list_sql);
        
        
        $total = $GLOBALS['db']->getOne("select count(doi.id) from ".DB_PREFIX."deal_order_item as doi where doi.duobao_item_id = ".$item_data['id']." and doi.refund_status = 0 and doi.pay_status = 2 and doi.lottery_sn_send=1");

        $page_total = ceil($total/$page_size);
        $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$total);

        foreach($root['duobao_order_list'] as $k=>$v)
        {
            $root['duobao_order_list'][$k]['duobao_ip'] = substr($v['duobao_ip'], 0,(stripos($v['duobao_ip'],".")+1))."****".substr($v['duobao_ip'], strripos($v['duobao_ip'],"."));
            $root['duobao_order_list'][$k]['f_create_time'] = to_date($v['create_time']);
        	$root['duobao_order_list'][$k]['avatar'] = get_abs_img_root(get_user_avatar($v['user_id'], "small"));
        }
        //购物车数据
        $root['cart_data'] = array();
        if($user_info){
        	require_once APP_ROOT_PATH.'system/model/duobao.php';
        	$root['cart_data'] = duobao::getcart($user_info['id']);
        }

		$root['item_data'] = $item_data;
		$root['page_title'].="奖品详情";
		
		return output($root);
	}

	public function get_duobao_status()
	{
	    /*参数列表*/
	    $id = intval($GLOBALS['request']['data_id']);
	  
	 
	    $root = array();
	
	     
        $item_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$id." and is_effect = 1");
	     
	    if(empty($item_data))
	    {
	        return output(array(),0,"夺宝活动已过期");
	    }
	 
	
	   
	    //用户状态
	    $user_login_status = check_login();
	    if($user_login_status == LOGIN_STATUS_LOGINED){
	        $user_info = $GLOBALS['user_info'];
	        //用户参与的数据
	        $root['user_login_status'] = $user_login_status;
	    }
	
	
	    //商品状态
	    $duobao_status = 0;  //0 进行中  1 倒计时  2已揭晓
	    if($item_data['has_lottery']==0){//未开奖
	        if($item_data['success_time']==0){//未成功
	            $duobao_status = 0;
	        }else{
	            $duobao_status=1;
	        }
	    }else{
	        $duobao_status = 2;
	    }
	    $item_data['duobao_status']         = $duobao_status;
	    $item_data['icon']                  = get_abs_img_root($item_data['icon']);
	    $item_data['surplus_count']         = $item_data['max_buy'] - $item_data['current_buy'];
	    $item_data['lottery_time_format']   = to_date($item_data['lottery_time']);
	    $item_data['create_time_format']    = to_date($item_data['create_time']);
	    $item_data['total_buy_price']       = format_sprintf_price($item_data['total_buy_price']);
	
	    $item_data['click_count']++;
	  
	    //输出揭晓的相关数据
	    if($duobao_status==2)
	    {
	        require_once APP_ROOT_PATH."system/model/user.php";
	        $duobao_item_log = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($item_data['id'])." where lottery_sn = '".$item_data['lottery_sn']."' and duobao_item_id = ".$item_data['id']);
	        $luck_user_info = load_user($item_data['luck_user_id']);
	         
	        $duobao_item_log['user_name'] = $luck_user_info['user_name'];
	        $duobao_item_log['user_logo'] = $luck_user_info['user_logo'];
	        $duobao_item_log['avatar'] = get_abs_img_root(get_muser_avatar($luck_user_info['user_id'],"big"))?get_abs_img_root(get_muser_avatar($luck_user_info['user_id'],"big")):"";
	         
	        $duobao_item_log['user_total'] = $item_data['luck_user_buy_count'];
	        $item_data['luck_lottery'] = $duobao_item_log;
	    }
	
	 
	    //我的参与
	    $root['my_duobao_log'] = $GLOBALS['db']->getAll("select lottery_sn from ".duobao_item_log_table($item_data)." where user_id = ".intval($GLOBALS['user_info']['id'])." and duobao_item_id = ".$item_data['id']);
	    $root['my_duobao_count'] = count($root['my_duobao_log']);
	 
	    $root['item_data'] = $item_data;
	    
	    return output($root);
	}

	//图文
	public function more()
	{
		$data_id = intval($GLOBALS['request']['data_id']);
		$desc = $GLOBALS['db']->getOne("select description from ".DB_PREFIX."duobao_item where id = ".$data_id);

		$root['desc'] = get_abs_img_root(format_html_content_image($desc, 720));
		//$root['sql'] = "select description from ".DB_PREFIX."duobao_item where id = ".$data_id;
		$root['page_title'].="图文详情";
		return output($root);
	}


	/**
	 * 计算详情
	 */
	public function detail()
	{
		$data_id = intval($GLOBALS['request']['data_id']);
		$duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$data_id);

		$root['value_a'] = $duobao_item['fair_sn_local']; //数值A
		$root['value_b'] = $duobao_item['fair_sn']?sprintf("%05d", $duobao_item['fair_sn']):$duobao_item['fair_sn']; //数值B
		$root['lottery_sn'] = $duobao_item['lottery_sn']; //开奖号
		$root['fair_period'] = $duobao_item['fair_period']; //期号
        $root['is_topspeed']=$duobao_item['is_topspeed'];
		$root['default_value_b'] = DEFAULT_LOTTERY;
		if($duobao_item['lottery_time'])
		{
			$fair_type = $duobao_item['fair_type'];
			
			if($fair_type=="yydb")
			{
				
			}
			else
			{
				$cname = $fair_type."_fair_fetch";
				
				require_once APP_ROOT_PATH."system/fair_fetch/".$cname.".php";
				$fetch_obj = new $cname;
				
				$root['fair_name'] = $fetch_obj->name;//开奖第三方名称
				$root['fair_check_link'] = $fetch_obj->get_check_link(to_date($duobao_item['lottery_time'],"Ymd")); //连接
			}

		}

		$duobao_item_log_50_cache = load_dynamic_cache("duobao_item_log_50_".$duobao_item['id']);
		// 		echo $sql = "select * from ".DB_PREFIX."deal_order_item where lottery_sn=0 and create_time <=".$item_data['success_time']." order by create_time desc limit 55";exit;
		if($duobao_item_log_50_cache===false)
		{
		    $sql = "select id,create_time,user_name from ".DB_PREFIX."deal_order_item  where lottery_sn=0 and create_time <=".$duobao_item['success_time_50']." order by create_time desc limit 50";
		    
		    $duobao_order_log = $GLOBALS['db']->getAll($sql);
		    set_dynamic_cache("duobao_item_log_50_".$duobao_item['id'], $duobao_order_log);
		}else{
		    $duobao_order_log=$duobao_item_log_50_cache;
		}
		

		$root['duobao_item_log'] = $duobao_order_log;

		foreach($root['duobao_item_log'] as $k=>$v)
		{

			$create_time = $v['create_time'];
			$data_arr = explode(".", $create_time);
			$date_str = to_date(intval($data_arr[0]),"H:i:s");
			$full_date_str = to_date(intval($data_arr[0]));
			$mmtime = trim($data_arr[1]);

			$res = intval(str_replace(":", "", $date_str).$mmtime);
			$fair_sn_local=$res;

			$root['duobao_item_log'][$k]['create_time_format'] = $full_date_str.".".$mmtime;
			$root['duobao_item_log'][$k]['fair_sn_local'] = $fair_sn_local;
		}

		$root['page_title'].="计算详情";
		return output($root);

	}


	/**
	 * 揭晓列表接口
	 * 输入：
	 * page:int 当前的页数
	 *
	 * 输出：
	 array (
	 'page' =>
	 array (
	 'total' => '7',  分页总数
	 'page_size' => 20, 分页大小
	 ),
	 'list' =>
	 array (
	 0 =>
	 array (
	 'id' => '10000299',   夺宝活动id
	 'deal_id' => '71',    商品id
	 'duobao_id' => '252', 夺宝计划id
	 'duobaoitem_name' => '【包邮】长江7号音箱朱古力', 夺宝商品名称
	 'icon' => './public/attachment/201509/19/10/55fcce7364dba.jpg',   夺宝商品小图
	 'lottery_sn' => '123',    中奖号
	 'has_lottery' => '1',     是否开奖
	 'success_time' => '0',    成功时间
	 'lottery_time' => '1453450975',   开奖时间
	 'fair_sn' => '0',     公证号
	 'luck_user_id' => '146',  中奖用户
	 'max_buy' => '1000',      总需要次数
	 'current_buy' => '1000',  当前购买量
	 'user_name' => 'fanwe1',  用户名
	 )
	 )
	 */
   public function duobao_record(){

        $page_size = PAGE_SIZE;
        $page      = intval($GLOBALS['request']['page']);
        $duobao_id = intval($GLOBALS['request']['data_id']);

        $last_time = NOW_TIME - (60*60);
        $sql_count = "SELECT count(*)
                FROM
                	".DB_PREFIX."duobao_item DuobaoItem
                    LEFT JOIN ".DB_PREFIX."user USER ON USER .id = DuobaoItem.luck_user_id
		        WHERE
		            DuobaoItem.is_effect = 1 AND
		            DuobaoItem.progress = 100 ";


       	$sql_count.=" and DuobaoItem.duobao_id = ".$duobao_id." ";

        if ($page == 0) $page = 1;
        $limit = (($page - 1) * $page_size) . "," . $page_size;
        $total = $GLOBALS['db']->getOne($sql_count);
        $page_data['total'] = $total;
        $page_data['page_size'] = $page_size;

        $sql = "SELECT 
                	DuobaoItem.id AS id,
                	DuobaoItem.deal_id AS deal_id,
                	DuobaoItem.duobao_id AS duobao_id,
                	DuobaoItem. NAME AS duobaoitem_name,
                	DuobaoItem.icon AS icon,
                	DuobaoItem.lottery_sn AS lottery_sn,
                	DuobaoItem.has_lottery AS has_lottery,
                	DuobaoItem.success_time AS success_time,
                	DuobaoItem.lottery_time AS lottery_time,
                	DuobaoItem.fair_sn AS fair_sn,
                	DuobaoItem.luck_user_id AS luck_user_id,
                	DuobaoItem.max_buy,
                	DuobaoItem.current_buy,
                	DuobaoItem.luck_user_id,
                	DuobaoItem.luck_user_name as user_name,
                    DuobaoItem.duobao_ip,
                    DuobaoItem.duobao_area
            
                FROM
                	".DB_PREFIX."duobao_item DuobaoItem
                 
                WHERE
		            	DuobaoItem.is_effect = 1
                        AND DuobaoItem.has_lottery = 1";


        $sql.=" and DuobaoItem.duobao_id = ".$duobao_id." ";

        $sql.= " ORDER BY DuobaoItem.lottery_time DESC";

        $list = $GLOBALS['db']->getAll($sql ." limit " . $limit);

        require_once APP_ROOT_PATH.'/system/model/user.php';
        foreach($list as $k=>$v)
        {
            $user_info = null;
            $user_info = load_user($v['luck_user_id']);
            $user_info['user_avatar'] = get_abs_img_root(get_muser_avatar($user_info['id'],"big"))?get_abs_img_root(get_muser_avatar($user_info['id'],"big")):"";

            $list[$k]['user_logo'] = $user_info['user_logo']?get_abs_img_root($user_info['user_logo']):$user_info['user_avatar'];
        	$list[$k]['icon'] = get_abs_img_root(get_spec_image($v['icon'],200,200,1));
        	
        	$list[$k]['user_avatar'] = get_abs_img_root(get_muser_avatar($v['luck_user_id'],"big"))?get_abs_img_root(get_muser_avatar($v['luck_user_id'],"big")):"";
        }

        /* 分页 */
        $data['page'] = $page_data;

        $data['list'] = $list;


		$data['page_title'].="往期揭晓";
		return output($data);
    }
    
    /**
     * 检测夺宝开将状态
     * 输入 duobao_item_id
     * 输出 status 
     */
    public function duobao_status()
    {
    	$status = intval($GLOBALS['db']->getOne("select has_lottery from ".DB_PREFIX."duobao_item where id = ".intval($GLOBALS['request']['duobao_item_id'])));
    	return output(array(),$status);
    }
    
    /**
     * 获取商品数量
     */
    public function get_duobao_item_num()
    {
        $root['user_login_status'] = 0;
        //用户状态
        $user_login_status = check_login();
        if($user_login_status == LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
            
            //用户参与的数据
            $data_id = intval($GLOBALS['request']['data_id']);
            $duobao_item = $GLOBALS['db']->getRow("select max_buy, current_buy, min_buy,unit_price,icon from ".DB_PREFIX."duobao_item where id = ".$data_id);
            $duobao_item['icon']=get_abs_img_root(get_spec_image($duobao_item['icon'],280,280,1));
             
            $root['duobao_number'] = $duobao_item;
        }
        
        return output($root);
    
    }

}
?>
