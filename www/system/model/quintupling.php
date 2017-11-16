<?php
/**
 * Created by PhpStorm.
 * User: 杨思伟
 * Date: 2017/10/26
 * Time: 14:24
 */
/**
 * 夺宝类
 * @author hc
 *
 */
class quintupling
{
    var $duobao_item;

    /**
     *
     * @param unknown_type $id 夺宝期号
     */
    public function __construct($id)
    {

        $this->duobao_item = $GLOBALS['db']->getAll("select * from " . DB_PREFIX . "duobao_item where id = '" . $id . "'");


        if ($this->duobao_item['progress'] < 100) {
            $pool_count = $GLOBALS['db']->getOne("select count(*) from " . duobao_item_log_table($this->duobao_item) . " where duobao_item_id = " . $id);
            if ($pool_count < $this->duobao_item['max_buy']) {
                self::create_lottery_pool($id);
            }

            if ($this->duobao_item['robot_is_db']) {
                $sql = "select count(*) from " . DB_PREFIX . "schedule_list where type in ('robot','robot_cfg') and exec_status = 0 and dest = '" . $id . "'";
                $robot_schedule_count = $GLOBALS['db']->getOne($sql);
                if ($robot_schedule_count == 0)
                    self::init_robot($id);
            }
        }
    }
    /**
     * 生成新的一期夺宝
     * 1. 为夺宝计划更新已开期数
     * 2. 同步夺宝计划表的相关数据，动态生成当前的夺宝活动
     * 3. 依据机器人的设置生成机器人计划任务
     * return array("status"=>1,"info"=>"xxx","duobao_item"=>NULL);
     */
    public static function new_duobao($duobao_id)
    {

        $res = $GLOBALS['db']->query("update ".DB_PREFIX."duobao set current_schedule = current_schedule + 1,is_five=1 where id = ".$duobao_id." and current_schedule + 1 <= max_schedule and is_effect = 1 and is_pk=0");
        //var_dump("update ".DB_PREFIX."duobao set current_schedule = current_schedule + 1 where id = ".$duobao_id." and current_schedule + 1 <= max_schedule and is_effect = 1 and is_pk=0");exit;
        if($res)
        {

            $duobao = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao where id = ".intval($duobao_id));

            $duobao_item['deal_id'] = $duobao[0]['deal_id'];
            $duobao_item['duobao_id'] = $duobao[0]['id'];
            $duobao_item['name'] = $duobao[0]['name'];
            $duobao_item['cate_id'] = $duobao[0]['cate_id'];
            $duobao_item['description'] = $duobao[0]['description'];
            $duobao_item['is_effect'] = $duobao[0]['is_effect'];
            $duobao_item['brief'] = $duobao[0]['brief'];
            $duobao_item['icon'] = $duobao[0]['icon'];
            $duobao_item['brand_id'] = $duobao[0]['brand_id'];
            $duobao_item['deal_gallery'] = $duobao[0]['deal_gallery'];
            $duobao_item['create_time'] = NOW_TIME;
            $duobao_item['duobao_score'] = $duobao[0]['duobao_score'];
            $duobao_item['invite_score'] = $duobao[0]['invite_score'];
            $duobao_item['max_buy'] = $duobao[0]['max_buy'];
            $duobao_item['min_buy'] = $duobao[0]['min_buy'];
            $duobao_item['fair_type'] = five;
            $duobao_item['robot_end_time'] = $duobao[0]['robot_end_time'];
            $duobao_item['robot_is_db'] = $duobao[0]['robot_is_db'];
            $duobao_item['origin_price'] = $duobao[0]['origin_price'];
            $duobao_item['unit_price'] = $duobao[0]['unit_price'];
            $duobao_item['user_max_buy'] = $duobao[0]['user_max_buy'];
            $duobao_item['total_buy_price'] = $duobao[0]['total_buy_price'];
            //五倍专场
            $duobao_item['is_five']=$duobao[0]['is_five'];
            $GLOBALS['db']->autoExecute(DB_PREFIX."duobao_item",$duobao_item,"INSERT");

            $duobao_item_id = $GLOBALS['db']->insert_id();
             // print_r($duobao_item);die;


            self::init_robot($duobao_item_id);

            //生成开奖池
            $total = self::create_lottery_pool($duobao_item_id);
            return array("status"=>1,"info"=>"夺宝开启","duobao_item"=>new quintupling($duobao_item_id));
        }
        else
        {
            return array("status"=>0,"info"=>"夺宝活动已期满");
        }
    }

    /**
     * @param unknown_type 初始化机器人
     */
    public static function init_robot($duobao_item_id)
    {
        $duobao_item = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where id=".$duobao_item_id);
        $duobao = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao where id =".intval($duobao_item['duobao_id']));

        $robot_is_db = $duobao_item['robot_is_db'];
        if($robot_is_db)
        {
            require_once APP_ROOT_PATH."system/model/robot.php";
            if($duobao['robot_type']==0)
                $result = robot::set_robot_schedule($duobao_item['robot_end_time'], $duobao_item_id);
            else
                $result = robot::set_robot_schedule_by_cfg(
                    array(
                        "robot_buy_min_time"=>$duobao['robot_buy_min_time'],
                        "robot_buy_max_time"=>$duobao['robot_buy_max_time'],
                        "robot_buy_min"=>$duobao['robot_buy_min'],
                        "robot_buy_max"=>$duobao['robot_buy_max']
                    )
                    , $duobao_item_id);
        }

    }

    /**
     * 生成开奖池
     * @param unknown $duobao_item_id
     * @param unknown $num
     */
    public static function create_lottery_pool($duobao_item_id){

        /**
         * 1：完全新生成
         * 2：数量不够新增
         * 3:生成失败，删除生成的数据，并删除这一期
         */

        set_time_limit(60);
        ini_set('memory_limit','512M');

        //获取期号对应的计划
        $duobao_item_info = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where id=".$duobao_item_id);
        $duobao_info = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao where id =".intval($duobao_item_info['duobao_id']));


        $deal_id        = $duobao_info['deal_id'];
        $duobao_id      = $duobao_info['id'];


        $vals = '';
        $sql = "INSERT INTO ".DB_PREFIX."duobao_item_log(`deal_id`, `duobao_id`, `duobao_item_id`, `lottery_sn`) VALUES";
        for($i=0;$i<$duobao_item_info['max_buy'];$i++)
        {
            $lottery_sn = 100000001+$i;
            $vals .= " ('{$deal_id}', '{$duobao_id}', '{$duobao_item_id}', {$lottery_sn}),";

        }

        $sql = $sql.$vals;
        $sql = substr($sql, 0, -1);
        $GLOBALS['db']->query($sql);
        unset($sql);
        unset($vals);

        return intval($GLOBALS['db']->affected_rows());
    }

    /**
     * 站内开奖，随机指定五个中奖号
     * @param unknown_type $lottery_sn  //指定的中奖号
     * @return boolean
     */
//    public function draw_lottery_five($lottery_sn='')
//    {
//        if($this->duobao_item['has_lottery']==1)return false; //已开奖跳过
//            $lottery_sn = $GLOBALS['db']->getAll("select lottery_sn ,id from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$this->duobao_item['id']." order by lottery_sn desc limit 1");
//            $duobao = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where id = ".$this->duobao_item['id']. " and has_lottery=0 ");
//        if($duobao[0]['total_buy_price'] ==$lottery_sn[0]['lottery_sn '] && $duobao[0]['is_five'] ==1 && $duobao[0]['has_lottery']==0){//判断这个商品是否卖完，卖完就开奖
////            require_once APP_ROOT_PATH . "system/model/quintupling.php";
////            $duobao_item = quintupling::draw_lottery_five(100000001);
////			print_r($duobao);die;
//            $lottery_sn = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log where duobao_item_id = ".$duobao[0]['id']." order by lottery_sn desc limit 1");
////            print_r($lottery_sn);die;
//            for ($i=0;$i<5;$i++){//随机五个中奖码，修改对应表数据
//                $rand_array = range(100000001,$lottery_sn[0]['lottery_sn']);
//                $key = rand(0,4);//获取不超过数组长度的随机数
//                $value = $rand_array[$key];//获得一个随机夺宝号码
//                $rand[] = mt_rand( 100000001,$lottery_sn[0]['lottery_sn']);
//                //修改duobao_item_log中的is_luck为1
//                $GLOBALS['db']->query("update ".duobao_item_log_table($duobao[0])." set is_luck = 1 where lottery_sn = ".$rand[$i]." and duobao_item_id = ".$duobao[0]['id']." and is_luck = 0");
//                //duobao_item中的has_lottery为1是开奖状态 展示中奖人信息
//                $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set has_lottery = 1 where id=".$duobao[0]['id']);
////                $user_id= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log where duobao_item_id=".$duobao[0]['id']." and is_luck=1 ");
////                $dq =$GLOBALS['db']->getAll("select * from ".DB_PREFIX."user where id=".$user_id[$i]['user_id']);
//
//            }
//
//            //五倍开奖中奖人存到duobao_item表
//            $duobao1 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item where id = ".$data['item_data']['id']. " and has_lottery=1");
//            $user_id1= $GLOBALS['db']->getAll("select * from ".DB_PREFIX."duobao_item_log where duobao_item_id=".$duobao1[0]['id']." and is_luck=1 ");
//            $arr =array($user_id1[0]['lottery_sn'],$user_id1[1]['lottery_sn'],$user_id1[2]['lottery_sn'],$user_id1[3]['lottery_sn'],$user_id1[4]['lottery_sn']);
//            $lottery_sn = implode(" ,",$arr);
//            $arr1 = array($user_id1[0]['user_id'],$user_id1[1]['user_id'],$user_id1[2]['user_id'],$user_id1[3]['user_id'],$user_id1[4]['user_id']);
//            $username = implode(" ,",$arr1);
//            $GLOBALS['db']->query("update ".DB_PREFIX."duobao_item set lottery_sn =concat_ws(',',{$lottery_sn}) ,luck_user_id=concat_ws(',',$username) where id=".$duobao1[0]['id']);
//            //中奖消息提示msg_box
//            for($i=0;$i<5;$i++){
//                $name =$duobao1[0]['name'];
//                $lottery_sn =  $user_id1[$i]['lottery_sn'];
//                $GLOBALS['db']->query("insert into ".DB_PREFIX."msg_box (content,user_id,create_time,is_read,is_delete,type,data,data_id) values ('恭喜您，您参与的 $name 夺宝活动中奖了！', ".$user_id1[$i]['user_id'].",".time().",0,0,'orderitem',0,'$lottery_sn')" );
//            }
//        }
//    }
//
//

















//
//
//        foreach ($rand as $k){
//
//            $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_item",$duobao_item,"UPDATE","id =".$this->duobao_item['id']." and has_lottery = 0");
//            $GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set is_luck = 1 where lottery_sn = '".$k."'."," and duobao_item_id = ".$this->duobao_item['id']." and is_luck = 0");
//        }
//
//        if(!$lottery_sn)
//        {
//            $robot_is_lottery = $GLOBALS['db']->getAll("select robot_is_lottery from ".DB_PREFIX."duobao where id = ".intval($this->duobao_item['duobao_id'])); //机器人必中
//            if($robot_is_lottery)
//            {
//                $sql = "select dil.lottery_sn from ".duobao_item_log_table($this->duobao_item)." as dil left join ".DB_PREFIX."user as u on dil.user_id = u.id where dil.duobao_item_id = ".$this->duobao_item['id']." and u.is_robot = 1 order by rand() limit 1,5";
//                $lottery_sn = $GLOBALS['db']->getAll($sql);
//                //logger::write($sql);
//            }
//
//            if(!$lottery_sn)
//                $lottery_sn = $GLOBALS['db']->getAll("select lottery_sn from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$this->duobao_item['id']." order by rand() limit 1 ,5");
//        }
//
//        require_once APP_ROOT_PATH."system/model/user.php";
//        $GLOBALS['db']->query("update ".duobao_item_log_table($this->duobao_item)." set is_luck = 1 where lottery_sn = '".$lottery_sn."' and duobao_item_id = ".$this->duobao_item['id']." and is_luck = 0");
//        if($GLOBALS['db']->affected_rows()||$this->duobao_item['fair_sn']>0)
//        {
//            //开始生成数值B
//            $mod = $lottery_sn - 100000001;  //应得的余数
//            $rand_b = rand(111111,999999); //随机生成的数值B
//            $fair_a_b = intval($rand_b)+floatval($this->duobao_item['fair_sn_local']);//a,b值总和
//            $rand_mod = fmod($fair_a_b,intval($this->duobao_item['max_buy']));  //随机数值b产生的余数
//            $mod_offset = $mod-$rand_mod;  //余数的差额
//            $rand_b+=$mod_offset;
//
//
//            $duobao_item_log_luck = $GLOBALS['db']->getRow("select * from ".duobao_item_log_table($this->duobao_item)." where is_luck = 1 and duobao_item_id = ".$this->duobao_item['id']);
//            $luck_user = load_user($duobao_item_log_luck['user_id']);
//
//            //累加机器人下单量
//            $robot_buy_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where is_robot = 1 and duobao_item_id = ".$this->duobao_item['id']);
//            $luck_user_buy_count = $GLOBALS['db']->getOne("select count(*) from ".duobao_item_log_table($this->duobao_item)." where duobao_item_id = ".$this->duobao_item['id']." and user_id=".$luck_user['id']);
//
//            $duobao_item_data = array(
//                "lottery_sn"=>$lottery_sn,
//                "fair_sn"=>$rand_b,
//                "lottery_time" => NOW_TIME,
//                "has_lottery"=>1,
//                "luck_user_id"=>$duobao_item_log_luck['user_id'],
//                "luck_user_name"=>$luck_user['user_name'],
//                "robot_buy_count"=>$robot_buy_count,
//                "luck_user_buy_count"=>$luck_user_buy_count,
//                "duobao_ip"=>$duobao_item_log_luck['duobao_ip'],
//                "duobao_area"=>$duobao_item_log_luck['duobao_area'],
//            );
//            $GLOBALS['db']->autoExecute(DB_PREFIX."duobao_item",$duobao_item_data,"UPDATE","id =".$this->duobao_item['id']." and has_lottery = 0");
//
//            //将相关订单的duobao_status更新为2表示为已开奖
//            $GLOBALS['db']->query("update ".DB_PREFIX."deal_order_item set duobao_status = 2 where duobao_item_id = ".$this->duobao_item['id']);
//
//
//            if($luck_user['is_robot']==0) //机器人不生成中奖单
//            {
//                //生成中奖订单
//                $duobao_deal_order_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order_item where id = ".$duobao_item_log_luck['order_item_id']);
//                $duobao_deal_order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$duobao_deal_order_item['order_id']);
//
//                unset($duobao_deal_order['id']);
//                $duobao_deal_order['order_sn'] = $duobao_deal_order['order_sn']."_".$this->duobao_item['id'];
//                $duobao_deal_order['type'] = 0; //改为中奖订单
//                $duobao_deal_order['order_status'] = 0; //进行中
//
//                $duobao_deal_order['create_time'] = NOW_TIME;
//                $duobao_deal_order['update_time'] = NOW_TIME;
//                $duobao_deal_order['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
//                $duobao_deal_order['create_date_y'] = to_date(NOW_TIME,"Y-m");
//                $duobao_deal_order['create_date_y'] = to_date(NOW_TIME,"Y");
//                $duobao_deal_order['create_date_m'] = to_date(NOW_TIME,"m");
//                $duobao_deal_order['create_date_d'] = to_date(NOW_TIME,"d");
//
//                $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order",$duobao_deal_order);
//                $order_id = $GLOBALS['db']->insert_id();
//                if($order_id)
//                {
//                    unset($duobao_deal_order_item['id']);
//
//                    $duobao_deal_order_item['buy_create_time']=substr($duobao_deal_order_item['create_time'], 0,strpos($duobao_deal_order_item['create_time'],"."));
//                    $duobao_deal_order_item['buy_number']=$duobao_deal_order_item['number'];
//
//                    $duobao_deal_order_item['order_id'] = $order_id;
//                    $duobao_deal_order_item['number'] = 1;
//                    $duobao_deal_order_item['lottery_sn'] = $duobao_item_log_luck['lottery_sn'];
//
//                    $duobao_deal_order_item['create_time'] = NOW_TIME;
//                    $duobao_deal_order_item['create_date_ymd'] = to_date(NOW_TIME,"Y-m-d");
//                    $duobao_deal_order_item['create_date_y'] = to_date(NOW_TIME,"Y-m");
//                    $duobao_deal_order_item['create_date_y'] = to_date(NOW_TIME,"Y");
//                    $duobao_deal_order_item['create_date_m'] = to_date(NOW_TIME,"m");
//                    $duobao_deal_order_item['create_date_d'] = to_date(NOW_TIME,"d");
//                    $duobao_deal_order_item['type'] = 0;
//
//                    $GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_item",$duobao_deal_order_item);
//                }
//
//                send_msg($duobao_item_log_luck['user_id'], "恭喜您，您参与的".$this->duobao_item['name']."夺宝活动，中奖了", "orderitem",$duobao_item_log_luck['duobao_item_id']);
//
//                send_wx_msg("OPENTM204623681", $duobao_item_log_luck['user_id'], array(),array("duobao_item_id"=>$duobao_item_log_luck['duobao_item_id']));
//
//                send_lottery_sms($duobao_item_log_luck['user_id'],$this->duobao_item);
//            }
//
//            $order_item_sql = "update ".DB_PREFIX."deal_order_item set create_date_ymd = '".to_date(NOW_TIME,"Y-m-d")."',create_date_ym = '".to_date(NOW_TIME,"Y-m")."',create_date_y = '".to_date(NOW_TIME,"Y")."',create_date_m = '".to_date(NOW_TIME,"m")."',create_date_d = '".to_date(NOW_TIME,"d")."' where type = 2 and  duobao_item_id = '".$this->duobao_item['id']."'";
//            $GLOBALS['db']->query($order_item_sql);
//
//            //$this->move_duobao_log();
//            send_schedule_plan("logmoving", $this->duobao_item['name']."奖池迁移", array("duobao_item_id"=>$this->duobao_item['id']), NOW_TIME,$this->duobao_item['id']); //奖夺宝数据迁移改为生成计划任务
//
//            return true;
//        }
//        else
//        {
//            return false;
//        }


}