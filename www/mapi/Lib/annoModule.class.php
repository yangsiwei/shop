<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: hhcycj
// +----------------------------------------------------------------------
class annoApiModule extends MainBaseApiModule{
    /**
     * 最新揭晓列表接口
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
              'id' => '10000299',   购买活动id
              'deal_id' => '71',    商品id
              'duobao_id' => '252', 购买计划id
              'duobaoitem_name' => '【包邮】长江7号音箱朱古力', 购买商品名称
              'icon' => './public/attachment/201509/19/10/55fcce7364dba.jpg',   购买商品小图
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
    public function index(){
        $page_size = PAGE_SIZE;
        $page      = intval($GLOBALS['request']['page']);
        $duobao_id = intval($GLOBALS['request']['duobao_id']);

        $last_time = NOW_TIME - (60*60);
        $sql_count = "SELECT count(*)
                FROM
                	".DB_PREFIX."duobao_item DuobaoItem
                    LEFT JOIN ".DB_PREFIX."user USER ON USER .id = DuobaoItem.luck_user_id
		        WHERE
		            DuobaoItem.is_effect = 1 AND
		            DuobaoItem.progress = 100 AND fair_type!='five'" ;

        if($duobao_id>0)
        	$sql_count.=" and DuobaoItem.duobao_id = ".$duobao_id." ";

        if ($page == 0) $page = 1;
        $limit = (($page - 1) * $page_size) . "," . $page_size;
        $total = $GLOBALS['db']->getOne($sql_count);
        $page_data['total'] = $total;
        $page_data['page_size'] = $page_size;

        $sql = "SELECT
                	DuobaoItem.id AS id, DuobaoItem.deal_id AS deal_id, DuobaoItem.duobao_id AS duobao_id, DuobaoItem. NAME AS duobaoitem_name,
		            DuobaoItem.icon AS icon, DuobaoItem.lottery_sn AS lottery_sn, DuobaoItem.has_lottery AS has_lottery, DuobaoItem.success_time AS success_time,
		            DuobaoItem.lottery_time AS lottery_time, DuobaoItem.fair_sn AS fair_sn, DuobaoItem.luck_user_id AS luck_user_id, DuobaoItem.max_buy, DuobaoItem.current_buy,DuobaoItem.min_buy,
                	DuobaoItem.luck_user_name,DuobaoItem.luck_user_buy_count
                FROM
                	".DB_PREFIX."duobao_item DuobaoItem
		        WHERE
		            DuobaoItem.is_effect = 1 AND
		            DuobaoItem.progress = 100  AND fair_type!='five'";

        if($duobao_id>0)
        	$sql.=" and DuobaoItem.duobao_id = ".$duobao_id." ";

        $sql.= " ORDER BY
                	 DuobaoItem.has_lottery,DuobaoItem.lottery_time DESC";

        $list = $GLOBALS['db']->getAll($sql ." limit " . $limit);

    	foreach($list as $k=>$v)
        {
        	$list[$k]['icon'] = get_abs_img_root(get_spec_image($v['icon'],200,200,1));
        }

        /* 分页 */
       $data['page'] = $page_data;

       require_once APP_ROOT_PATH."system/model/duobao.php";
       $cart_info=duobao::getcart($GLOBALS['user_info']['id']);
       $data['cart_info']=$cart_info;


       $data['list'] = $list;

       $data['page_title'].="最新揭晓";
       return output($data);


    }

//     public function get_has_lottery() {
//         $id = $GLOBALS['request']['id'];
//         $data = array('status'=>0);
//         $sql = " SELECT
//                 	DuobaoItem.id AS id, DuobaoItem.deal_id AS deal_id, DuobaoItem.duobao_id AS duobao_id, DuobaoItem. NAME AS duobaoitem_name,
// 		            DuobaoItem.icon AS icon, DuobaoItem.lottery_sn AS lottery_sn, DuobaoItem.has_lottery AS has_lottery, DuobaoItem.success_time AS success_time,
// 		            DuobaoItem.lottery_time AS lottery_time, DuobaoItem.fair_sn AS fair_sn, DuobaoItem.luck_user_id AS luck_user_id, DuobaoItem.max_buy, DuobaoItem.current_buy,
//                 	USER.user_name AS user_name
//                 FROM
//                 	".DB_PREFIX."duobao_item DuobaoItem
//                     LEFT JOIN ".DB_PREFIX."user USER ON USER .id = DuobaoItem.luck_user_id
// 		        WHERE
// 		           DuobaoItem.id = ".$id;

//         $result = $GLOBALS['db']->getRow($sql);
//         if ($result) {
//             $result['status'] = 1;
//             return output($result);
//         }
//         return output($data['info']='获取失败');

//     }
}

?>
