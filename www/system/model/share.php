<?php
/**
 * 晒单类
 * @author jobin.lin
 *
 */
class share{

    public function __construct(){
    }
    

    
    public function insert_share($duobao_item,$title,$content ,$attach_list=array(),$xpoint="", $ypoint=""){
        $now_time = NOW_TIME;
        $ins_data = array();
        $ins_data['duobao_item_id'] = $duobao_item['id'];
        $ins_data['duobao_id'] = $duobao_item['duobao_id'];
        $ins_data['type'] = 1;
        $ins_data['create_time'] = $now_time;
        $ins_data['user_id'] = $GLOBALS['user_info']['id'];
        $ins_data['user_name'] = $GLOBALS['user_info']['user_name'];
        $ins_data['title'] = $title;
        $ins_data['content'] = $content;
        $ins_data['is_effect'] = 0; //需要通过后台审核
        $ins_data['xpoint'] = $xpoint;
        $ins_data['ypoint'] = $ypoint;
        $ins_data['images_count'] = count($attach_list);
        if($duobao_item['buy_number']==1)
            $ins_data['buy_number_1'] = 1;
        //是否为机器人
        $ins_data['is_robot'] = intval($GLOBALS['user_info']['is_robot']);
       
        $GLOBALS['db']->autoExecute(DB_PREFIX."share",$ins_data);
        $id = $GLOBALS['db']->insert_id();
        
       if($id){
          
            //更新图片缓存
            foreach($attach_list as $attach)
            {
                    //插入图片
                    $GLOBALS['db']->query("update ".DB_PREFIX."share_image set share_id = ".$id." where id = ".$attach['id']);
            }
            
            //删除所有创建超过一小时，且未被使用过的图片
            $del_list = $GLOBALS['db']->getAll("select id,path,o_path from ".DB_PREFIX."share_image where share_id = 0 and (".$now_time." - create_time > 1)");
            $GLOBALS['db']->query("delete from ".DB_PREFIX."share_image where share_id = 0 and (".$now_time." - create_time > 1)");
            foreach($del_list as $k=>$v)
            {
                @unlink(APP_ROOT_PATH.$v['path']);
                @unlink(APP_ROOT_PATH.$v['o_path']);
            }
            
            //缓存图集
            $img_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."share_image where share_id = ".$id);
            $cache_imgs = serialize($img_list);
            //缓存夺宝数据
            $cache_duobao_item = array();
            $cache_duobao_item['id'] = $duobao_item['id'];
            $cache_duobao_item['name'] = $duobao_item['name'];
            $cache_duobao_item['description'] = $duobao_item['description'];
            $cache_duobao_item['icon'] = $duobao_item['icon'];
            $cache_duobao_item['max_buy'] = $duobao_item['max_buy'];
            $cache_duobao_item['min_buy'] = $duobao_item['min_buy'];
            $cache_duobao_item['fair_type'] = $duobao_item['fair_type'];
            $cache_duobao_item['success_time'] = $duobao_item['success_time'];
            $cache_duobao_item['lottery_time'] = $duobao_item['lottery_time'];
            $cache_duobao_item['fair_sn'] = $duobao_item['fair_sn'];
            $cache_duobao_item['fair_sn_local'] = $duobao_item['fair_sn_local'];
            $cache_duobao_item['fair_period'] = $duobao_item['fair_period'];
            $cache_duobao_item['lottery_sn'] = $duobao_item['lottery_sn'];
            $cache_duobao_item['luck_user_id'] = $duobao_item['luck_user_id'];
            $cache_duobao_item['luck_user_name'] = $duobao_item['luck_user_name'];
            $cache_duobao_item['luck_user_buy_count'] = $duobao_item['luck_user_buy_count'];
            $cache_duobao_item['duobao_ip'] = $duobao_item['duobao_ip'];
            $cache_duobao_item['duobao_area'] = $duobao_item['duobao_area'];
            $cache_duobao_item['origin_price'] = $duobao_item['origin_price'];
            
            $GLOBALS['db']->autoExecute(DB_PREFIX."share",array("image_list"=>$cache_imgs,"cache_duobao_item_data"=>serialize($cache_duobao_item)),"UPDATE"," id=".$id);
            
            $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set share_id=".$id.",is_send_share=1 where id=".$duobao_item['id']);
            $GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set is_send_share=1 where type=0 and duobao_item_id=".$duobao_item['id']." and user_id = ".$duobao_item['luck_user_id']);
            
            
            return $id;
        }else{
            return false;
        }
    }
    
    public function get_share_list($limit,$where,$orderby=''){
        
        if($where){
            $condition = " and ".$where;
        }
        
        if($limit==''){
            $limit = " 0,".app_conf("PAGE_SIZE")." ";
        }
        
        $sql = "select * from ".DB_PREFIX."share where 1=1 ".$condition;

        if($orderby=='')
            $sql.=" order by create_time desc limit ".$limit;
        else
            $sql.=" order by ".$orderby." limit ".$limit;
        

        $result_list = $GLOBALS['db']->getAll($sql);
        
        foreach ($result_list as $k=>$v){
            $img_list = array();
            $img_list = unserialize($v['image_list']);
            $result_list[$k]['img'] = $img_list[0];
            $result_list[$k]['duobao_item'] = unserialize($v['cache_duobao_item_data']);
        }
        
        return array('list'=>$result_list,'condition'=>$condition);
    }
    
}

?>