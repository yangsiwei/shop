<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class redsetApiModule extends MainBaseApiModule
{
    public function index(){
        global_run();
        $order_sn=$GLOBALS['request']['order_sn'];
        $user_data = $GLOBALS['user_info'];
        
        $root['draw_count'] = $GLOBALS['db']->getOne("select draw_count from ".DB_PREFIX."ecv_type where send_type = 3");
        $deal_order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where order_sn = ".$order_sn." and pay_status = 2");
        if($deal_order){
            $root['user_ecv']=$GLOBALS['db']->getAll("select e.*,u.user_name,u.avatar from ".DB_PREFIX."ecv as e left join ".DB_PREFIX."user as u on e.user_id = u.id where e.order_sn = ".$order_sn);
            foreach ($root['user_ecv'] as $k => $v){
                $root['user_ecv'][$k]['get_time']=to_date($v['get_time'],'y-m-d H:i');
                $root['user_ecv'][$k]['money']=number_format($v['money'],2);
            }
            $conf=$GLOBALS['db']->getOne("select value from ".DB_PREFIX."conf where name = 'SPLIT_RED_MONEY'");
            if($conf==1){
                $ecv=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."ecv where order_sn = ".$order_sn);
                $user_login_status = check_login();
                if($user_login_status!=LOGIN_STATUS_LOGINED){
                    if($ecv<$deal_order['send_limit']){
                        $root['has']=1;
                        $root['order_sn']=$order_sn;
                    }else{
                        $root['name'] = "红包已被领完";
                    }
                }else{
                    $send_ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv where order_sn = ".$order_sn." and user_id = ".$user_data['id']);
                    if($send_ecv){
                        $root['send_ecv']="￥".number_format($send_ecv['money'],2);
                        $root['user_data']=$user_data;
                    }else{
                        if($ecv<$deal_order['send_limit']){
                            $bedin_time = strtotime( date('Ymd', NOW_TIME) );
                            $end_time   = strtotime( date('Ymd', NOW_TIME) ) + ( 24 * 60 * 60 );
                            //已领取次数
                            $draw_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."ecv where user_id = ".$user_data['id']." and get_time > ".$bedin_time." and get_time < ".$end_time);
                            if($root['draw_count']>$draw_count){
                                $root['order_sn']=$order_sn;
                                $root['id']=$user_data['id'];
                            }else{
                                $root['name'] = "您今天领取红包次数已用完";
                            }
                        }else{
                            $root['name'] = "红包已被领完";
                        }
                    }
                }
            }
        }else{
            $root['name']="订单信息错误";
        }
        $root['page_title'].="拆分红包";
        return output($root);
        
    }
    
    
    /**
     * 	 手机短信登录接口
     *
     * 	 输入:
     *  mobile: string 手机号
     *  sms_verify: string 验证码
     *
     *  输出:
     *  status:int 结果状态 0失败 1成功
     *  info:信息返回
     *
     *  以下六项仅在status为1时会返回
     *  id:int 会员ID
     *  user_name:string 会员名
     *  user_pwd:string 加密过的密码
     *  email:string 邮箱
     *  mobile:string 手机号
     *  is_tmp: int 是否为临时会员 0:否 1:是
     */
    public function dophlogin()
    {
        global_run();
        $order_sn=$GLOBALS['request']['order_sn'];
        $user_info = $GLOBALS['user_info'];
        $deal_order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where order_sn = ".$order_sn." and pay_status = 2");
        
        if($deal_order){
            if($user_info)
            {
                $send_ecv = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv where order_sn = ".$order_sn." and user_id = ".$user_info['id']);
                if($send_ecv){
                    return output($data,0,"您已领过该红包");
                }else{
                    
                    $bedin_time = strtotime( to_date( NOW_TIME,'Ymd') );
                    $end_time   = strtotime( to_date( NOW_TIME,'Ymd') ) + ( 24 * 60 * 60 );
                    //已领取次数
                    $draw_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."ecv where user_id = ".$user_info['id']." and get_time > ".$bedin_time." and get_time < ".$end_time);
                    $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 3");
                    if($ecv_type['draw_count']>$draw_count){
                        if($deal_order['pay_amount']>=$ecv_type['minchange_money']){
                            $conf=$GLOBALS['db']->getOne("select value from ".DB_PREFIX."conf where name = 'SPLIT_RED_MONEY'");
                            if($conf==1){
                                $ecv=$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."ecv where order_sn = ".$order_sn);
                                if($ecv<$deal_order['send_limit']){
                                    $bonus_items=unserialize($deal_order['sendrandbonus']);
                                    if($bonus_items){
                                        //获取数组第一个值作为金额
                                        $money= reset($bonus_items);
                                        //删除第一个值
                                        array_shift($bonus_items);
                                        //重新写入红包冗余字段
                                        $GLOBALS['db']->query("update ".DB_PREFIX."deal_order set sendrandbonus = '".serialize($bonus_items)."' where order_sn = '".$deal_order['order_sn']."'");
                                        $this->send_vouchers($user_info['id'],$money,$order_sn,1);
                                        send_msg($user_info['id'], "通过".$order_sn."订单分享抢到".$money."元红包啦", "notify");
                                        return output($data,1);
                                    }else{
                                        return output($data,2,"红包已被领完");
                                    }
                                }else{
                                    return output($data,2,"红包已被领完");
                                }
                            }
                        }
                    }else{
                        return output($data,2,"您今天领取红包次数已用完");
                    }
                }
                 
            }else{
                return output($data,0,"您还未登录，请先登陆");
            }
        }else{
            return output($data,0,"订单信息错误");
        }
    }
    /**
     * 代金券发放
     * @param $money 代金券金额
     * @param $order_sn 订单id
     * @param $user_id  发放给的会员。0为线下模式的发放
     * @param 分享红包，拆分红包专用
     */
    public function send_vouchers($user_id=0,$money,$order_sn,$is_password=false)
    {
        if(!$GLOBALS['db']->affected_rows())
        {
            return -1;
        }
        $ecv_type_id=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where send_type = 3");
        if($is_password)$ecv_data['password'] = rand(10000000,99999999);
        $ecv_data['use_limit'] = 1;
        $ecv_data['begin_time'] = $ecv_type_id['begin_time'];
        $ecv_data['end_time'] = $ecv_type_id['end_time'];
        $ecv_data['money'] = $money;
        $ecv_data['ecv_type_id'] = $ecv_type_id['id'];
        $ecv_data['user_id'] = $user_id;
        $ecv_data['order_sn'] = $order_sn;
        $ecv_data['data'] = $ecv_type_id['data'];
        $ecv_data['is_all'] = $ecv_type_id['is_all'];
        $ecv_data['meet_amount'] = $ecv_type_id['meet_amount'];
        $ecv_data['get_time']= NOW_TIME;
    
        do{
            $sn = unpack('H12',str_shuffle(md5(uniqid())));
            $sn = $sn[1];
            $ecv_data['sn'] = $sn;
            //$ecv_data['sn'] = md5(NOW_TIME);
            $GLOBALS['db']->autoExecute(DB_PREFIX."ecv",$ecv_data,'INSERT','','SILENT');
            $insert_id = $GLOBALS['db']->insert_id();
        }while(intval($insert_id) == 0);
    }
}