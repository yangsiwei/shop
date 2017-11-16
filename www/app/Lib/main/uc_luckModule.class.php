<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------



class uc_luckModule extends MainBaseModule
{
	public function index()
	{
	   
		global_run();
		init_app_page();
		if(check_save_login()!=LOGIN_STATUS_LOGINED)
		{
		    app_redirect(url("index","user#login"));
		}
        $user_info = $GLOBALS['user_info'];
        
//         var_dump($user_info);exit;
		$page =intval($_REQUEST['p']);
        $page_size =app_conf("PAGE_SIZE");

        //分页
        require_once APP_ROOT_PATH."app/Lib/page.php";
        if ($page == 0) $page = 1;
        $limit = (($page - 1) * $page_size) . "," . $page_size;
       
        $sql = "select
                    i.id,
                    i.is_set_consignee,
                    i.id as order_item_id,
                    i.duobao_item_id,
                    i.delivery_status,
                    i.name,
                    i.is_arrival,
                    i.deal_icon,
                    i.lottery_sn,
                    i.buy_create_time as create_time,
                    i.buy_number as number
                 from  ".DB_PREFIX."deal_order_item as i
                     where i.user_id = ".$GLOBALS['user_info']['id']." 
                         and i.type=0 
                         order by i.create_time desc,i.is_send_share asc limit ".$limit;

        $sql_count = "select count(*) from ".DB_PREFIX."deal_order as o where o.user_id = ".$GLOBALS['user_info']['id']." and o.type=0";
        $list = $GLOBALS['db']->getAll($sql);
        $sql_id = "select id from ".DB_PREFIX."deal_cate  c where  c.is_fictitious=1";
        $id = $GLOBALS['db']->getAll($sql_id);
        foreach ($id as $k=>$v){
            $id[$k]=$v['id'];
        }
        $count = $GLOBALS['db']->getOne($sql_count);
        

        foreach($list as $k=>$v)
        {
            //获得参与次数
            
            $create_time = $v['create_time'];
            $rel = explode(".", $create_time);
            $list[$k]['create_time'] = to_date($rel[0]);
            $list[$k]['deal_icon']=get_spec_image($list[$k]['deal_icon'],200,200,1);
            $list[$k]['region_status'] = $region_status;
            $duobao_item = array();
            $duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$v['duobao_item_id']);
            /*  foreach ($id as $k=>$v){
                if($duobao_item['cate_id']==$v['id']){
                    $list[$k]['cate_id'] = $duobao_item['cate_id'];
                    break;
                }
            }*/
            if(in_array($duobao_item['cate_id'], $id)){
                $list[$k]['cate_id'] = $duobao_item['cate_id'];
               
            }
           
            
            $list[$k]['max_buy'] = $duobao_item['max_buy'];
            $list[$k]['lottery_time'] = to_date($duobao_item['lottery_time']);
            $list[$k]['number'] = $duobao_item['luck_user_buy_count'];
            $list[$k]['is_send_share'] = $duobao_item['is_send_share'];
            $list[$k]['share_id'] = $duobao_item['share_id'];
            
             
        }
        $data['count']=$count;
        $data['list']=$list;


        $page = new Page($count, $page_size); // 初始化分页对象
        $p = $page->show();

        $GLOBALS['tmpl']->assign('pages', $p);
        $GLOBALS['tmpl']->assign('id', $id);
        $GLOBALS['tmpl']->assign("list", $data['list']);
        $GLOBALS['tmpl']->assign("data", $data);
		assign_uc_nav_list();//左侧导航菜单	
		$GLOBALS['tmpl']->display("uc/uc_luck.html");
	    
	}
	
	/**
	 * 中奖商品状态页
	 */
	public function detail(){
	    global_run();
	    init_app_page();
	    
	    $id = intval($_REQUEST['id']);
        $user_id=intval($GLOBALS['user_info']['id']);
        
        //查询是否存在
        $sql = "select
                    i.id,
                    i.is_set_consignee,
                    i.id as order_item_id,
                    i.duobao_item_id,
                    i.delivery_status,
                    i.name,
                    i.is_arrival,
                    i.deal_icon,
                    i.lottery_sn,
                    i.buy_create_time as create_time,
                    i.buy_number as number,
                    i.consignee,
                    i.mobile,
                    i.region_info,
                    i.zip
                 from  ".DB_PREFIX."deal_order_item as i
                
                     where i.id=".$id." and i.user_id = ".$GLOBALS['user_info']['id']."
                         and i.type=0";
      
        $order = $GLOBALS['db']->getRow($sql);
        
   
        if (!$order){
            showErr("数据不存在",0,url("index","uc_luck"));
        }
        
        $duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id = ".$order['duobao_item_id']);
        $sql_id = "select id from ".DB_PREFIX."deal_cate  c where  c.is_fictitious=1";
        $cate_id = $GLOBALS['db']->getAll($sql_id);
        foreach ($cate_id as $k=>$v){
            $cate_id[$k]=$v['id'];
        }
        if(in_array($duobao_item['cate_id'], $cate_id)){
            $order['cate_id'] = $duobao_item['cate_id'];
             
        }
        $order['max_buy'] = $duobao_item['max_buy'];
        $order['price']   = $duobao_item['max_buy'] * $duobao_item['unit_price'];
        $order['lottery_time'] = $duobao_item['lottery_time'];
        $order['is_send_share'] = $duobao_item['is_send_share'];
        $order['share_id'] = $duobao_item['share_id'];
        
        //输出所有配送方式
        $consignee_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_consignee where user_id = ".$user_id);
        foreach($consignee_list as $k=>$v){
            $consignee_info=load_auto_cache("consignee_info",array("consignee_id"=>$v['id']));          
            $consignee_list[$k]['del_url']=url('index','uc_consignee#del',array('id'=>$v['id']));
            $consignee_list[$k]['dfurl']=url('index','uc_consignee#set_default',array('id'=>$v['id']));
            $consignee_list[$k]['region_lv2']=  $consignee_info['consignee_info']['region_lv2_name'];       
            $consignee_list[$k]['region_lv3']=  $consignee_info['consignee_info']['region_lv3_name'];   
            $consignee_list[$k]['region_lv4']=  $consignee_info['consignee_info']['region_lv4_name'];
        }
        
        //夺宝商品信息
		$duobao_item['value_price'] = $duobao_item['max_buy']*$duobao_item['unit_price'];
        $duobao_item['origin_price'] = round($duobao_item['origin_price'],2);
        
        //快递信息和虚拟商品信息  fictitious_info
        $delivery_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."delivery_notice where order_item_id = ".$order['id']);
//         var_dump($order['id']);exit;
        if($delivery_notice){
            $express_list = require_once APP_ROOT_PATH."system/express_cfg.php";
            $express_info = $express_list[$delivery_notice['express_id']];
            $delivery_notice['express_name'] = $express_info['name'];
        }
        
        
        $GLOBALS['tmpl']->assign("order",$order);
        $GLOBALS['tmpl']->assign("duobao_item",$duobao_item);
        $GLOBALS['tmpl']->assign("delivery_notice",$delivery_notice);
        $GLOBALS['tmpl']->assign("consignee_list",$consignee_list);
        $GLOBALS['tmpl']->assign("count_consignee",count($consignee_list));
	    $GLOBALS['tmpl']->display("uc/uc_luck_detail.html");
	}
	public function check_delivery(){
	    global_run();
	    init_app_page();
	    
	    $item_id = intval($_REQUEST['item_id']);
	    $user_id = intval($GLOBALS['user_info']['id']);
	    require_once APP_ROOT_PATH."system/model/deal_order.php";
	    $order_table_name = get_user_order_table_name($user_id);
	
	    $delivery_notice = $GLOBALS['db']->getRow("select n.* from ".DB_PREFIX."delivery_notice as n left join ".$order_table_name." as o on n.order_id = o.id where n.order_item_id = ".$item_id." and o.user_id = ".$user_id." order by delivery_time desc");
	    if($delivery_notice)
	    {
	        $express_id = intval($delivery_notice['express_id']);
	        $typeNu = strim($delivery_notice["notice_sn"]);
	        $express_list = require_once APP_ROOT_PATH."system/express_cfg.php";
	        $express_info = $express_list[$express_id];
	        $typeCom = $express_info['code'];
	        if(isset($typeCom)&&isset($typeNu))
	        {
	            $data['url'] = "http://m.kuaidi100.com/index_all.html?type=".$typeCom."&postid=".$typeNu;
	            app_redirect($data['url']);
	        }
	        else
	        {
	            showErr("无效的快递查询");
	        }
	    }
	    else
	    {
	        showErr("非法操作");
	    }
	}
}
?>