<?php
/**
 * Created by PhpStorm.
 * User: 杨思伟
 * Date: 2017/10/23
 * Time: 11:42
 */
class uc_qdhbModule extends MainBaseModule
{
    public function index(){
        global_run();

        init_app_page();
        $uid = $GLOBALS['user_info']['id'];
        $time = time();
        $todayBegin=strtotime(date('Y-m-d')." 00:00:00");
        $todayEnd= strtotime(date('Y-m-d')." 23:59:59");
        $checkSignSql="SELECT * FROM ".DB_PREFIX."sign WHERE `uid` = ".$uid." AND `dateline` < ".$todayEnd." AND `dateline` > ".$todayBegin;
        $checkContinuYesterday = $GLOBALS['db']->getAll($checkSignSql);//查询今天有没有签到
        $red_pactket_total = $GLOBALS['user_info']['red_packet_total'];

        //签到记录
        $hbjl = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."sign WHERE `uid` = ".$uid." ORDER BY dateline DESC  ");
        foreach ( $hbjl as $k=>$v){
            $hbjl[$k]['dateline']=date("Y-m-d H:i",$v['dateline']);
        }
        //签到红包取出记录
        $qdhb = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."red_log WHERE `uid` = ".$uid." ORDER BY `time` DESC  ");
        foreach ( $qdhb as $k=>$v){
            $qdhb[$k]['time']=date("Y-m-d H:i",$v['time']);
        }
        $data['page_title']="签到红包";
        $GLOBALS['tmpl']->assign("hbjl", $hbjl);
        $GLOBALS['tmpl']->assign("qdhb", $qdhb);
        $GLOBALS['tmpl']->assign("data", $data);
        $GLOBALS['tmpl']->assign('red_pactket_total',$red_pactket_total);
        $GLOBALS['tmpl']->assign('red_packet',$checkContinuYesterday[0]['red_packet']);
        $GLOBALS['tmpl']->display("uc_qdhb.html");
    }
    public function withdrawals(){
        global_run();

        init_app_page();
        $red_packet_total = $_POST['do'];
        $uid = $GLOBALS['user_info']['id'];
        $red_packet_total = $GLOBALS['user_info']['red_packet_total'];
        if ($red_packet_total !=="0" && $red_packet_total !==""){
            if($red_packet_total >= "58"){
                $GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET `give_money` = `give_money`+{$red_packet_total} WHERE `id` =".$uid);
                $can_use_give_money = $GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET `can_use_give_money` = `can_use_give_money`+{$red_packet_total} WHERE `id` =".$uid);
                if ($can_use_give_money){
                    $can_use_give_money = $GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET `red_packet_total` =0 WHERE `id` =".$uid);
                    $time= time();
                    $GLOBALS['db']->query("INSERT INTO ".DB_PREFIX."red_log(`uid`,`red_packet_record`,`time`) VALUES ( ".$uid.",".$red_packet_total.",".$time.")" );//红包存入记录
                    $data['status'] = 'success';
                    $data['info'] = "签到红包已存到余额里，请去查看";
                    echo json_encode($data);
                }else{
                    $data['status'] = 'success';
                    $data['info'] = "签到红包存到余额失败！，请联系客服";
                    echo json_encode($data);
                }
            }else{
                $data['status'] = 'success';
                $data['info'] = "满58元才可存到余额账户";
                echo json_encode($data);
            }
        }else{
            $data['status'] = 'success';
            $data['info'] = "签到红包没有啦，请去签到";
            echo json_encode($data);
        }
    }
}