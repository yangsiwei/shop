<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class uc_fxinviteApiModule extends MainBaseApiModule
{

    /**
     * 	 会员中心我的推荐接口
     *
     * 	  输入：
     *  page:int 当前的页数
     *
     *  输出：
     * user_login_status:int   0表示未登录   1表示已登录
     * login_info:string 未登录状态的提示信息，已登录时无此项
     * page_title:string 页面标题
     * pname:fanwe  string  推荐人名称

     * list:array:array 下线会员列表，结构如下
     *  Array
    (
    [0] => Array
    (
    [id] => 19 int 下线会员id
    [money] =>¥3.9 string 我从该会员获取的推广推广奖
    [user_name] => fanwe1  string  被推荐人名称
    )
    )
    share_register_qrcode=>[string]  注册邀请地址二维码图 http://localhost/sqo2o/public/images/qrcode/c0/0929771add5729179915aeadc8981571.png
    share_register_url =>[string] 注册邀请地址 http://localhost/sqo2o/wap/index.php?r=NzM%3D
     */
    public function index()
    {

        $root = array();

        $user_data = $GLOBALS['user_info'];
        $user_id = intval($user_data['id']);
        $page = intval($GLOBALS['request']['page'])?intval($GLOBALS['request']['page']):1; //当前分页


        //分页初始化
        //$page = intval($_REQUEST['p']);
        if($page<=0)	$page = 1;
        $page_size =  app_conf("PAGE_SIZE");
        $limit = (($page-1)*$page_size).",".$page_size;


        $user_login_status = check_login();

        if($user_login_status!=LOGIN_STATUS_LOGINED){

            $root['user_login_status'] = $user_login_status;
        }
        else{
            $root['user_login_status'] = 1;

            if($user_data['pid']==0){
                $root['pname']="无推荐人";
            }else{
                $root['pname'] = $GLOBALS['db']->getOne("select user_name from ".DB_PREFIX."user where id = ".$user_data['pid']);
            }


            $inte_sql="SELECT u.user_name, SUM(r.money) AS money, SUM(r.score) AS score, SUM(r.coupons) AS coupons FROM ".DB_PREFIX."user AS u LEFT JOIN ".DB_PREFIX."referrals AS r ON u.id = r.rel_user_id WHERE u.pid = $user_id AND u.pid = r.user_id GROUP BY r.rel_user_id limit ".$limit;

            $total_list=$GLOBALS['db']->getAll($inte_sql);
            $count_sql = "SELECT u.user_name FROM ".DB_PREFIX."user AS u LEFT JOIN ".DB_PREFIX."referrals AS r ON u.id = r.rel_user_id WHERE u.pid = $user_id AND u.pid = r.user_id GROUP BY r.rel_user_id";
            $count_list = $GLOBALS['db']->getAll($count_sql);
            $count = count($count_list);

            foreach ($total_list as $k => $v){
                $root['total_money'] += $v['money'];
                $root['total_score'] += $v['score'];
                $root['total_coupons'] += $v['coupons'];
            }
            $root['fx_money'] = $user_data['fx_money'];
            $root['admin_money'] = $user_data['admin_money'];
            $root['total_money'] = intval($root['total_money']);
            $root['total_score'] = intval($root['total_score']);
            $root['total_coupons'] = intval($root['total_coupons']);
            $root['share_register_qrcode'] = get_abs_img_root(gen_qrcode(SITE_DOMAIN.wap_url("index","user#register",array("r"=>base64_encode($user_data['id'])))));
            $root['share_register_url'] = SITE_DOMAIN.wap_url("index","index",array("r"=>base64_encode($user_data['id'])));
            $root['list'] = $total_list? $total_list:array();
            $root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$count);

            $root['page_title'].="邀请好友";

        }

        return output($root);

    }


    public function index1()
    {
        $root = array();

        $user_data = $GLOBALS['user_info'];
        $user_id = intval($user_data['id']);
        $user_login_status = check_login();
        $now_time = to_date(NOW_TIME, 'Y-m-d');
        $start_time = strtotime($now_time);
        $end_time   = $start_time + 24*60*60;
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = 1;
            // 我的分销用户
            $first_all_fx_user = $GLOBALS['db']->getAll("select id, user_name, pid,fx_level from ".DB_PREFIX."user where pid = ".$user_data['id']);
            $root['first_user_count'] = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."user where pid = ".$user_data['id']);
            $user_list = $GLOBALS['db']->getAll("select user_name,id from ".DB_PREFIX."user where is_delete=0 and is_effect=1 and is_robot=0");
            $today_amount = $GLOBALS['db']->getAll("select sum(pay_amount) today_money,user_id uid from ".DB_PREFIX."deal_order where  (create_time > {$start_time} and create_time < {$end_time})  and type = 1 group by user_id");

            // 总的
            $amount   = $GLOBALS['db']->getAll("select u.id uid, sum(r.pay_amount) amount_money  from ".DB_PREFIX."deal_order r left join ".DB_PREFIX."user u on u.id=r.user_id where r.type=1 "." group by r.user_id");
            // 我的一级分销用户
            foreach ($first_all_fx_user as $key=>$value){
                $first_uid[] = $value['id'];
                $root['first_fx_user'][$key]['user_name'] = $value['user_name'];
                $root['first_fx_user'][$key]['id'] = $value['id'];
                foreach($today_amount as $k1=>$v1){
                    if($value['id'] == $v1['uid']){
                        $root['first_fx_user'][$key]['today_amount'] = $v1['today_money'];
                    }
                }
                foreach($amount as $k2=>$v2){
                    if($value['id'] == $v2['uid']){
                        $root['first_fx_user'][$key]['amount_money'] = $v2['amount_money'];
                    }
                }
                foreach($user_list as $k3=>$v3){
                    if($value['pid'] == $v3['id']){
                        $root['first_fx_user'][$key]['pid'] = $v3['user_name'];
                    }
                }
            }

            $first_uid = join(',', $first_uid);
            $second_all_fx_user = '';
            $root['second_user_count'] = 0;
            if ($first_uid) {
                $second_all_fx_user = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo,avatar,fx_level from ".DB_PREFIX."user where pid in ({$first_uid})");
                $root['second_user_count'] = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."user where pid in ({$first_uid})");
            }


            // 我的二级分销用户
            foreach ($second_all_fx_user as $key=>$value){
                $second_uid[] = $value['id'];
                $root['second_fx_user'][$key]['user_name'] = $value['user_name'];
                $root['second_fx_user'][$key]['id'] = $value['id'];
                foreach($today_amount as $k1=>$v1){
                    if($value['id'] == $v1['uid']){
                        $root['second_fx_user'][$key]['today_amount'] = $v1['today_money'];
                    }
                }
                foreach($amount as $k2=>$v2){
                    if($value['id'] == $v2['uid']){
                        $root['second_fx_user'][$key]['amount_money'] = $v2['amount_money'];
                    }
                }
                foreach($user_list as $k3=>$v3){
                    if($value['pid'] == $v3['id']){
                        $root['second_fx_user'][$key]['pid'] = $v3['user_name'];
                    }
                }
            }
            $second_uid = join(',', $second_uid);

            // 我的三级分销用户
            $three_fx_user = '';
            $root['three_user_count'] = 0;
            if ($second_uid) {
                $three_fx_user = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo,avatar from ".DB_PREFIX."user where pid in ({$second_uid})");
                foreach ($three_fx_user as $key=>$value){
                    $third_uid[] = $value['id'];
                    $root['three_fx_user'][$key]['user_name'] = $value['user_name'];
                    $root['three_fx_user'][$key]['id'] = $value['id'];
                    foreach($today_amount as $k1=>$v1){
                        if($value['id'] == $v1['uid']){
                            $root['three_fx_user'][$key]['today_amount'] = $v1['today_money'];
                        }
                    }
                    foreach($amount as $k2=>$v2){
                        if($value['id'] == $v2['uid']){
                            $root['three_fx_user'][$key]['amount_money'] = $v2['amount_money'];
                        }
                    }
                    foreach($user_list as $k3=>$v3){
                        if($value['pid'] == $v3['id']){
                            $root['three_fx_user'][$key]['pid'] = $v3['user_name'];
                        }
                    }
                }
                $root['three_user_count'] = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."user where pid in ({$second_uid})");
            }
            //三级邀请用户推广奖
            $three_all_fx_user = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo,avatar from ".DB_PREFIX."user where pid in ({$second_uid})");
            foreach($three_all_fx_user as $value){
                $third_uid[] = $value['id'];
            }
            $third_uid = join(',', $third_uid);




// 			// 我的一级分销
// 			$three_info = $GLOBALS['db']->getRow("select sum(r.money) total_money,  sum(r.order_money) total_order_money, count(*) t_count from ".DB_PREFIX."user u left join ".DB_PREFIX."fx_user_reward r on u.id=r.user_id where r.fx_level=1 and r.pid = ".$user_data['id']);

// 			// 我的二级分销
// 			$second_info = $GLOBALS['db']->getRow("select sum(r.money) total_money, sum(r.order_money) total_order_money, count(*) t_count from ".DB_PREFIX."user u left join ".DB_PREFIX."fx_user_reward r on u.id=r.user_id where r.fx_level=1 and r.pid in ({$first_uid})");

// 			// 我的三级分销
// 			$first_info = $GLOBALS['db']->getRow("select sum(r.money) total_money, sum(r.order_money) total_order_money, count(*) t_count from ".DB_PREFIX."user u left join ".DB_PREFIX."fx_user_reward r on u.id=r.user_id where r.fx_level=1 and r.pid in ({$second_uid})");


            // 获取推广奖分成多少
//			$fx_salary = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."fx_salary");
            // 定额0， 比率1
//			$fx_salary_type = $fx_salary[0]['fx_salary_type'];




            //今日一级分销累计充值金额
            $root['first_money'] = $GLOBALS['db']->getOne("select sum(pay_amount) money from ".DB_PREFIX."deal_order where user_id in ({$first_uid}) and create_time > {$start_time} and create_time < {$end_time} and type = 1 ");
            $root['first_total_money'] = $GLOBALS['db']->getOne("select sum(pay_amount) money from ".DB_PREFIX."deal_order where user_id in ({$first_uid}) and type = 1 ");
            //今日二级分销累计充值金额
            $root['second_money'] = $GLOBALS['db']->getOne("select sum(pay_amount) money from ".DB_PREFIX."deal_order where user_id in ({$second_uid}) and create_time > {$start_time} and create_time < {$end_time} and type = 1 ");
            $root['second_total_money'] = $GLOBALS['db']->getOne("select sum(pay_amount) money from ".DB_PREFIX."deal_order where user_id in ({$second_uid}) and type = 1 ");
            //今日三级分销累计充值金额
            $root['third_money'] = $GLOBALS['db']->getOne("select sum(pay_amount) money from ".DB_PREFIX."deal_order where user_id in ({$third_uid}) and create_time > {$start_time} and create_time < {$end_time} and type = 1 ");
            $root['third_total_money'] = $GLOBALS['db']->getOne("select sum(pay_amount) money from ".DB_PREFIX."deal_order where user_id in ({$third_uid}) and type = 1 ");

            if($GLOBALS['user_info']['fx_level'] = 4 && $third_uid){
                $root['four_fx'] = $this->four_fx_user($third_uid);
            }

            //计算今日获得推广奖情况
//            $root['fx_salary'] = $GLOBALS['db']->getAll("select fx_salary from ".DB_PREFIX."fx_salary");
//            $root['first_fx_money'] = $root['first_money'][0]['money']*$root['fx_salary'][2]['fx_salary'];
//            $root['second_fx_money'] = $root['second_money'][0]['money']*$root['fx_salary'][1]['fx_salary'];
//            $root['third_fx_money'] = $root['third_money'][0]['money']*$root['fx_salary'][0]['fx_salary'];
//
//            $root['fx_salary']['first'] = $root['fx_salary'][0]['fx_salary']*100;
//            $root['fx_salary']['second'] = $root['fx_salary'][1]['fx_salary']*100;
//            $root['fx_salary']['third'] = $root['fx_salary'][2]['fx_salary']*100;

//			// 今日三级分销金额信息
//			$today_three_info = $GLOBALS['db']->getRow("select sum(r.money) total_money,  sum(r.order_money) total_order_money, count(*) t_count from ".DB_PREFIX.
//	           "user u left join ".DB_PREFIX."fx_user_reward r on u.id=r.user_id where r.fx_salary_type={$fx_salary_type} and r.pid = ".$user_data['id']." and r.fx_level=1 and (r.create_time > {$start_time} and r.create_time < {$end_time}) " );
//
//			// 今日我的二级金额信息
//			if ($first_uid) {
//			    $today_second_info = $GLOBALS['db']->getRow("select sum(r.money) total_money,  sum(r.order_money)  total_order_money, count(*) t_count from ".DB_PREFIX.
//			        "user u left join ".DB_PREFIX."fx_user_reward r on u.id=r.user_id where r.fx_salary_type={$fx_salary_type} and r.pid in ({$first_uid}) and r.fx_level=1 and (r.create_time > {$start_time} and r.create_time < {$end_time}) " );
//			}
//
//			// 今日我的一级金额信息
//			if ($second_uid) {
//			    $today_first_info = $GLOBALS['db']->getRow("select sum(r.money) total_money,  sum(r.order_money)  total_order_money, count(*) t_count from ".DB_PREFIX.
//			        "user u left join ".DB_PREFIX."fx_user_reward r on u.id=r.user_id where r.fx_salary_type={$fx_salary_type} and r.pid in ({$second_uid}) and r.fx_level=1 and (r.create_time > {$start_time} and r.create_time < {$end_time}) " );
//			}
//
//			$root['fx_count']['three_fx_count']  = intval( $today_three_info['t_count']);
//			$root['fx_count']['second_fx_count'] = intval( $today_second_info['t_count'] );
//			$root['fx_count']['first_fx_count']  = intval( $today_first_info['t_count'] );
//
//			foreach ($fx_salary as $value){
//			    // 定额0， 比率1
//			    $root['fx_salary_type'] = $value['fx_salary_type'];
//
//			    if($value['fx_level'] == 1){
//			        if ( $root['fx_salary_type'] == 1) {
//			            $root['fx_level_one_salary'] = round($value['fx_salary']*100, 2);
//			            // 今日我的一级分销创收
//			            $root['today_first_money'] = round($today_three_info['total_order_money'] * $value['fx_salary'], 2);
//
//			        }else{
//			            $root['today_first_money'] = round($value['fx_salary'] * $today_three_info['t_count'], 2);
//
//			            $root['fx_level_one_salary'] = round($value['fx_salary'], 2);
//			        }
//
//			    }
//			    if($value['fx_level'] == 2){
//			        if ( $root['fx_salary_type'] == 1) {
//			            $root['fx_level_two_salary'] = round($value['fx_salary']*100, 2);
//			            // 今日我二级分销创收
//			            $root['today_second_money'] = round($today_second_info['total_order_money'] * $value['fx_salary'], 2);
//			        }else{
//			            $root['today_second_money'] = round($value['fx_salary'] * $today_second_info['t_count'], 2);
//			            $root['fx_level_two_salary'] = round($value['fx_salary'], 2);
//			        }
//			    }
//
//			    if($value['fx_level'] == 3){
//			        if ( $root['fx_salary_type'] == 1) {
//			            $root['fx_level_three_salary'] = round($value['fx_salary']*100, 2);
//			            // 今日我三级分销创收
//			            $root['today_three_money'] = round($today_first_info['total_order_money'] * $value['fx_salary'], 2);
//			        }else{
//			            $root['today_three_money'] = round($value['fx_salary'] * $today_first_info['t_count'], 2);
//			            $root['fx_level_three_salary'] = round($value['fx_salary'], 2);
//			        }
//			    }
//
//			}

            // 邀请好友累积收入
            $root['total_brokerage_money'] = $GLOBALS['db']->getOne("select fx_total_balance from ".DB_PREFIX."user where id = ".$user_data['id']);
            $root['total_brokerage_money'] = round($root['total_brokerage_money'], 2);

            // 今日获得多宝币
            $root['today_total_brokerage_money'] = round($root['first_fx_money'] + $root['second_fx_money'] + $root['third_fx_money'], 2);


//			// 今日第一级分销交易金额
//			$root['today_first_order_money'] = round($today_three_info['total_order_money'], 2);
//
//			// 今日第二级分销交易金额
//			$root['today_second_order_money'] = round($today_second_info['total_order_money'], 2);
//
//			// 今日第三级分销交易金额
//			$root['today_three_order_money'] = round($today_first_info['total_order_money'], 2);
//
//			// 今日交易的总金额
//			$root['today_total_money'] = round($today_first_info['total_order_money'] + $today_second_info['total_order_money'] + $today_three_info['total_order_money'], 2);

            $root['no_user_logo'] = SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar_small.gif";

        }

        return output($root);

    }

    public function four_fx_user($user_id,$total_money=0,$today_money=0,$user_uid=array()){
        $now_time = to_date(NOW_TIME, 'Y-m-d');
        $start_time = strtotime($now_time);
        $end_time   = $start_time + 24*60*60;
        $user_info = $GLOBALS['db']->getAll("select id from ".DB_PREFIX."user where pid in ({$user_id}) and is_effect = 1 group by id");
        foreach($user_info as $v){
            $user_ids[] = $v['id'];
            $user_uid[] = $v['id'];
        }
        $user_id = join(',', $user_ids);
        $order_info = $GLOBALS['db']->getAll("select sum(pay_amount) money from ".DB_PREFIX."deal_order where user_id in ({$user_id}) and type = 1 group by id");
        $today_order_info = $GLOBALS['db']->getOne("select sum(pay_amount) money from ".DB_PREFIX."deal_order where user_id in ({$user_id}) and create_time > {$start_time} and create_time < {$end_time} and type = 1 ");

        foreach($order_info as $vv){
            $total_money += $vv['money'];
        }
        foreach($today_order_info as $v3){
            $today_money += $v3['money'];
        }
        if($user_info){
            $this->four_fx_user($user_id,$total_money,$today_money,$user_uid);
        }
        $data['user_count'] = count($user_uid);
        $data['total_money'] = $total_money;
        $data['today_money'] = $today_money;
        return $data;
    }

    /**
     * 我的佃户
     */
    public function uc_fxinvite_user1(){
        $user_data = $GLOBALS['user_info'];
        $user_id = intval($user_data['id']);

        $user_login_status = check_login();
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = 1;
            $user_info = $GLOBALS['db']->getAll("select id, user_name, user_logo from ".DB_PREFIX."user where pid = ".$user_id);
        }
        $root['no_user_logo'] = SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar_small.gif";
        foreach ($user_info as $key=>$value){
            $fx_user_id[] = $value['id'];
        }


        $now_time = to_date(NOW_TIME, 'Y-m-d');
        $start_time = strtotime($now_time);
        $end_time   = $start_time + 24*60*60;

        $fx_user_id = join(',', $fx_user_id);

        if ($fx_user_id) {
            $today_amount = $GLOBALS['db']->getAll("select sum(pay_amount) today_money,count(id) order_number,user_id uid from ".DB_PREFIX."deal_order where user_id in ({$fx_user_id}) and (create_time > {$start_time} and create_time < {$end_time})  and type = 1 and pay_amount>=100 group by user_id");

            // 总的
            $amount   = $GLOBALS['db']->getAll("select u.id uid, sum(r.pay_amount) amount_money  from ".DB_PREFIX."deal_order r left join ".DB_PREFIX."user u on u.id=r.user_id where u.id in({$fx_user_id}) and r.pay_amount>=100  and r.type=1 "." group by r.user_id");
            // 用户信息
            $user_list = $GLOBALS['db']->getAll("select id uid, user_name, user_logo from ".DB_PREFIX."user where id in({$fx_user_id})");
        }

        $fx_salary = $GLOBALS['db']->getOne("select fx_salary from ".DB_PREFIX."fx_salary where fx_level = 1");

        foreach ($user_list as $key=>$value){
            foreach ($today_amount as $k1=>$v1){
                if ($value['uid'] == $v1['uid']) {
                    $user_list[$key]['today_money'] = round($v1['today_money'], 2);
                    $user_list[$key]['order_number'] = intval($v1['today_money']*$fx_salary);
                }
            }

            foreach ($amount as $k2=>$v2){
                if ($value['uid'] == $v2['uid']) {
                    $user_list[$key]['amount_money'] = round($v2['amount_money']*$fx_salary, 2);
                }
            }

            $user_list[$key]['today_money']  = $user_list[$key]['today_money'] ? $user_list[$key]['today_money'] : 0;
            $user_list[$key]['order_number'] = $user_list[$key]['order_number'] ? $user_list[$key]['order_number'] : 0;

            $user_list[$key]['amount_money'] = $user_list[$key]['amount_money'] ? $user_list[$key]['amount_money'] : 0;

            $user_list[$key]['user_logo'] = $value['user_logo']?get_user_avatar($value['uid'],'middle'):'';
        }

        $root['amount_data'] = $user_list;
        return output($root);

    }

    /**
     * 我的佃户的佃户
     */
    public function uc_fxinvite_user2(){
        $user_data = $GLOBALS['user_info'];
        $user_id = intval($user_data['id']);
        $user_login_status = check_login();
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = 1;

            // 我的佃户
            $first_user = $GLOBALS['db']->getAll("select id, user_name, user_logo from ".DB_PREFIX."user where pid = ".$user_id);

            // 我的佃户的佃户
            foreach ($first_user as $value){
                $first_uid[] = $value['id'];
            }
            $first_uid = join(',', $first_uid);
            if ($first_uid) {
                $user_info = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo from ".DB_PREFIX."user where pid in ({$first_uid})");
            }

        }
        $root['no_user_logo'] = SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar_small.gif";
        foreach ($user_info as $key=>$value){
            $fx_user_id[] = $value['id'];
        }

        $now_time = to_date(NOW_TIME, 'Y-m-d');
        $start_time = strtotime($now_time);
        $end_time   = $start_time + 24*60*60;

        $fx_user_id = join(',', $fx_user_id);

        if ($fx_user_id) {
            // 今天
            $today_amount = $GLOBALS['db']->getAll("select sum(pay_amount) today_money,count(id) order_number,user_id uid from ".DB_PREFIX."deal_order where user_id in ({$fx_user_id}) and (create_time > {$start_time} and create_time < {$end_time}) and total_price>=100 and pay_status=2  and type = 1 group by user_id");

            // 总的
            $amount   = $GLOBALS['db']->getAll("select u.id uid, sum(r.pay_amount) amount_money  from ".DB_PREFIX."deal_order r left join ".DB_PREFIX."user u on u.id=r.user_id where u.id in({$fx_user_id}) and r.total_price>=100 and r.pay_status=2 and r.type=1 "." group by r.user_id");
            // 用户信息
            $user_list = $GLOBALS['db']->getAll("select id uid, user_name, user_logo from ".DB_PREFIX."user where id in({$fx_user_id})");
        }

        $fx_salary = $GLOBALS['db']->getOne("select fx_salary from ".DB_PREFIX."fx_salary where fx_level = 2");

        foreach ($user_list as $key=>$value){
            foreach ($today_amount as $k1=>$v1){
                if ($value['uid'] == $v1['uid']) {
                    $user_list[$key]['today_money'] = round($v1['today_money'], 2);
                    $user_list[$key]['order_number'] = intval($v1['today_money']*$fx_salary);
                }
            }

            foreach ($amount as $k2=>$v2){
                if ($value['uid'] == $v2['uid']) {
                    $user_list[$key]['amount_money'] = round($v2['amount_money']*$fx_salary, 2);
                }
            }

            $user_list[$key]['today_money']  = $user_list[$key]['today_money'] ? $user_list[$key]['today_money'] : 0;
            $user_list[$key]['order_number'] = $user_list[$key]['order_number'] ? $user_list[$key]['order_number'] : 0;

            $user_list[$key]['amount_money'] = $user_list[$key]['amount_money'] ? $user_list[$key]['amount_money'] : 0;


            $user_list[$key]['user_logo'] = $value['user_logo']?get_user_avatar($value['uid'],'middle'):'';
        }

        $root['amount_data'] = $user_list;
        return output($root);
    }

    /**
     * 我的佃户的佃户的佃户
     */
    public function uc_fxinvite_user3(){
        $user_data = $GLOBALS['user_info'];
        $user_id = intval($user_data['id']);

        $user_login_status = check_login();
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = 1;

            // 我的佃户
            $first_user = $GLOBALS['db']->getAll("select id, user_name, user_logo from ".DB_PREFIX."user where pid = ".$user_id);

            // 我的佃户的佃户
            foreach ($first_user as $value){
                $first_uid[] = $value['id'];
            }
            $first_uid = join(',', $first_uid);
            if ($first_uid) {
                $second_user = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo from ".DB_PREFIX."user where pid in ({$first_uid})");;
            }

            // 我的佃户的佃户的佃户
            foreach ($second_user as $value){
                $second_uid[] = $value['id'];
            }
            $second_uid = join(',', $second_uid);
            if ($second_uid) {
                $user_info = $GLOBALS['db']->getAll("select id, user_name, pid, user_logo from ".DB_PREFIX."user where pid in ({$second_uid})");
            }

        }
        $root['no_user_logo'] = SITE_DOMAIN.APP_ROOT."/public/avatar/noavatar_small.gif";
        foreach ($user_info as $key=>$value){
            $fx_user_id[] = $value['id'];
        }

        $now_time = to_date(NOW_TIME, 'Y-m-d');
        $start_time = strtotime($now_time);
        $end_time   = $start_time + 24*60*60;

        $fx_user_id = join(',', $fx_user_id);

        if ($fx_user_id) {
            // 今日
            $today_amount = $GLOBALS['db']->getAll("select sum(pay_amount) today_money,count(id) order_number,user_id uid from ".DB_PREFIX."deal_order where user_id in ({$fx_user_id}) and (create_time > {$start_time} and create_time < {$end_time})  and type = 1 and pay_amount>=100 group by user_id");

            // 总的
            $amount   = $GLOBALS['db']->getAll("select u.id uid, sum(r.pay_amount) amount_money  from ".DB_PREFIX."deal_order r left join ".DB_PREFIX."user u on u.id=r.user_id where u.id in({$fx_user_id}) and r.pay_amount>=100  and r.type=1 "." group by r.user_id");
            // 用户信息
            $user_list = $GLOBALS['db']->getAll("select id uid, user_name, user_logo from ".DB_PREFIX."user where id in({$fx_user_id})");
        }

        $fx_salary = $GLOBALS['db']->getOne("select fx_salary from ".DB_PREFIX."fx_salary where fx_level = 3");

        foreach ($user_list as $key=>$value){
            foreach ($today_amount as $k1=>$v1){
                if ($value['uid'] == $v1['uid']) {
                    $user_list[$key]['today_money'] = round($v1['today_money'], 2);
                    $user_list[$key]['order_number'] = intval($v1['today_money']*$fx_salary);
                }
            }

            foreach ($amount as $k2=>$v2){
                if ($value['uid'] == $v2['uid']) {
                    $user_list[$key]['amount_money'] = round($v2['amount_money']*$fx_salary, 2);
                }
            }

            $user_list[$key]['today_money']  = $user_list[$key]['today_money'] ? $user_list[$key]['today_money'] : 0;
            $user_list[$key]['order_number'] = $user_list[$key]['order_number'] ? $user_list[$key]['order_number'] : 0;

            $user_list[$key]['amount_money'] = $user_list[$key]['amount_money'] ? $user_list[$key]['amount_money'] : 0;

            $user_list[$key]['user_logo'] = $value['user_logo']?get_user_avatar($value['uid'],'middle'):'';
        }

        $root['amount_data'] = $user_list;
        return output($root);
    }


}
?>