<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: hhcycj
// +----------------------------------------------------------------------
class uc_shareApiModule extends MainBaseApiModule{
    /**
     * 
     * 输入：
     * id : int 夺宝期号中奖ID
     *
     * 输出：
    */
    public function index(){
        $page_size = PAGE_SIZE;
        $page      = intval($GLOBALS['request']['page']);
        $data_id   = intval($GLOBALS['request']['data_id']);
		
        $user_info = $GLOBALS['user_info'];
        $user_id   = intval($user_info['id']);
		$user_login_status = check_login();
        //验证用户是否登录
        if($user_login_status!=LOGIN_STATUS_LOGINED && $data_id <=0){
            $data['user_login_status'] = $user_login_status;
        }else{
            $data['user_login_status'] = 1;
			if ($page == 0) $page = 1;
			$limit = (($page - 1) * $page_size) . "," . $page_size;
			
			if ($data_id > 0) {
			    $share_list = $GLOBALS['db']->getAll("select a.id,a.duobao_item_id,a.name,a.deal_icon,a.is_send_share,b.is_check,b.id as share_id,b.user_id,b.user_name,b.create_time,b.is_effect,b.title,b.content,b.image_list from ".DB_PREFIX."deal_order_item as a left join ".DB_PREFIX."share as b on a.duobao_item_id = b.duobao_item_id where b.duobao_id={$data_id} and b.is_effect=1 and a.user_id = b.user_id and a.is_send_share=1 and a.type=0 order by a.id desc"." limit " . $limit);
			    $share_list_count = $GLOBALS['db']->getOne("select count(a.id) from ".DB_PREFIX."deal_order_item as a left join ".DB_PREFIX."share as b on a.duobao_item_id = b.duobao_item_id where b.duobao_id={$data_id} and b.is_effect=1 and a.user_id = b.user_id and a.is_send_share=1 and a.type=0");
			}else{
			    $luck_list = $GLOBALS['db']->getAll("select id,duobao_item_id,name,deal_icon,is_send_share from ".DB_PREFIX."deal_order_item where user_id = ".$user_id." and delivery_status=1 and is_arrival=1 and is_send_share=0 and type=0 order by id desc");
			    $share_list = $GLOBALS['db']->getAll("select a.id,a.duobao_item_id,a.name,a.deal_icon,a.is_send_share,b.is_check,b.id as share_id,b.user_id,b.user_name,b.create_time,b.is_effect,b.title,b.content,b.image_list from ".DB_PREFIX."deal_order_item as a left join ".DB_PREFIX."share as b on a.duobao_item_id = b.duobao_item_id where a.user_id = ".$user_id." and a.user_id = b.user_id and a.is_send_share=1 and a.type=0 order by a.id desc"." limit " . $limit);
			    $share_list_count = $GLOBALS['db']->getOne("select count(a.id) from ".DB_PREFIX."deal_order_item as a left join ".DB_PREFIX."share as b on a.duobao_item_id = b.duobao_item_id where a.user_id = ".$user_id." and a.user_id = b.user_id and a.is_send_share=1 and a.type=0");
			}
			
			
			foreach ($share_list as $key => $value) {
				$share_list[$key]['create_time']=to_date($value['create_time'],"m-d H:i");
				
				$share_list[$key]['image_list']=unserialize($value['image_list']);
				
				foreach ($share_list[$key]['image_list'] as $k=>$v){
				        $path       = APP_ROOT_PATH.substr($v['path'], 1);
				        $o_path     = APP_ROOT_PATH.substr($v['o_path'], 1);
				        
				        $exists     = file_exists( $path );
				        $o_exists   = file_exists( $o_path );
				        
				        if (!$exists && $o_exists) {
				            $share_list[$key]['image_list'][$k]['path'] = imagecropper( $o_path, 255, 255 );
				            $path = $share_list[$key]['image_list'][$k]['path'];
				            $GLOBALS['db']->query("update ".DB_PREFIX."share_image set path ='".$path."' where id = {$v['id']}");
				        }
				        
				}
			}
			$page_data['total'] = $share_list_count;
			$page_data['page_size'] = $page_size;
			 /* 分页 */
			$data['page'] = $page_data;
			$data['luck_list']=$luck_list;
			$data['share_list']=$share_list;
            $data['page_title'].="晒单列表";
        }
        
        return output($data);
    }
    /**
     * 晒单发布
     */
    public function publish(){
        
        $id = intval($GLOBALS['request']['id']);
        $user_info = $GLOBALS['user_info'];
        $user_login_status = check_login();
        //验证用户是否登录
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        
        }else{
            
            $root['user_login_status'] = $user_login_status;
            require_once APP_ROOT_PATH."system/model/duobao.php";
        
            $duobao = new duobao($id);
            $duobao_item = $duobao->duobao_item;
            //获取夺宝记录
            $duobao_item_log = $GLOBALS['db']->getRow("select id,duobao_id,order_id,order_item_id,is_luck from ".duobao_item_log_table($duobao_item)." where duobao_item_id=".$duobao_item['id']." and is_luck=1 and user_id=".$user_info['id']);
            if($duobao_item_log){//必须存在中奖记录
                //获取购买订单信息
                $order_info = $GLOBALS['db']->getRow("select id,order_sn from ".DB_PREFIX."deal_order where id=".$duobao_item_log['order_id']." and pay_status=2 and user_id=".$user_info['id']." and order_status =1");
                //获取中奖订单对象信息(占时不使用联查)
               //$lottery_order_item_info = $GLOBALS['db']->getRow("select t_doi.id,t_doi.order_sn,t_doi.delivery_status,t_do.order_status from ".DB_PREFIX."deal_order_item t_doi LEFT JOIN ".DB_PREFIX."deal_order t_do on t_do.id= t_doi.order_id  where t_doi.order_sn='".$order_info['order_sn']."' and t_doi.user_id=".$user_info['id']." and t_doi.lottery_sn='".$duobao_item['lottery_sn']."'");                
                //获取中奖订单对象
                $lottery_order_info = $GLOBALS['db']->getRow("select id,order_sn,order_status from ".DB_PREFIX."deal_order where order_sn='".$order_info['order_sn']."_".$duobao_item['id']."' and user_id=".$user_info['id']);

                if($lottery_order_item_info['order_status']==0){
                    $root['status'] = 0;
                    $root['info'] = "订单未完成";
                }else{
                    $root['data'] = $duobao_item;
                    $root['status'] = 1;
                    $root['info'] = "订单未完成";
                }
            }else{
                $root['status'] = 0;
                $root['info'] = "很遗憾没有中奖记录";
            }
            $data['page_title'].="晒单列表";
        }
        
        
        return output($data);
    }
    
    public function save(){
        global_run();
        $id = intval($GLOBALS['request']['id']);
        $title = strim($GLOBALS['request']['title']);
        $content = strim($GLOBALS['request']['content']);
        $attach_list = $GLOBALS['request']['attach_list'];
        
        $user_info = $GLOBALS['user_info'];
        
        $user_login_status = check_login();
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
            return output($root,0);
        }
        $root['user_login_status'] = $user_login_status;
        
        //判断是否是这个用户中奖的晒单
        $duobao_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."duobao_item where id=".$id." and luck_user_id=".$user_info['id']);
        
        if(empty($duobao_item))
            return output($root,0,"数据不存在");
        
        if($duobao_item['is_send_share']){
            return output($root,0,"已经发布过晒单");
        }
        
        require_once APP_ROOT_PATH.'system/model/share.php';
        $share_obj = new share();
        
        foreach ($attach_list as $k=>$v){
            $attach_list[$k]['id']=intval($v['id']);
        }
        $share_id = $share_obj->insert_share($duobao_item, $title, $content,$attach_list);
        
        if($share_id){
            $root['share_id'] = $share_id;
            return output($root,1,"晒单成功");
        }else{
            return output($root,0,"数据错误");
        }
        
        
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
        $user_id   = intval($user_info['id']);
        
        $user_login_status = check_login();
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
            return output($root,0);
        }
        $root['user_login_status'] = $user_login_status;
        
        
        $sql="select * from ".DB_PREFIX."share where user_id='{$user_id}' and id=".$id;
    
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

?>
