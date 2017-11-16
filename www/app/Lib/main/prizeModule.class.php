<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class prizeModule extends MainBaseModule
{
    public  $prize_arr = array(
        '0' => array('id'=>1,'prize'=>'11111夺宝币','v'=>0),
        '1' => array('id'=>2,'prize'=>'1111夺宝币','v'=>0),
        '2' => array('id'=>3,'prize'=>'111夺宝币','v'=>10),
        '3' => array('id'=>4,'prize'=>'20现金红包','v'=>1000),
        '4' => array('id'=>5,'prize'=>'88现金红包','v'=>10),
        '5' => array('id'=>6,'prize'=>'再来一次','v'=>10),
    );

    public function index()
	{
		global_run();
		init_app_page();
        $user_login_status = check_save_login();
        if ($user_login_status != LOGIN_STATUS_LOGINED) {
            echo "<script>window.location.href='http://www.aliduobaodao.com/wap/index.php?ctl=user&act=login&show_prog=1'</script>";
            die;
        }
        $restaraunts = [];
		foreach ($this->prize_arr as $k=>$v){
			array_push($restaraunts ,$v['prize']);
        }
        $restaraunts = implode(',',$restaraunts);
        $data['page_title'] = '大转盘';
        $sql ="select * from ".DB_PREFIX."prize order by addtime desc limit 10";
        $winner = $GLOBALS['db']->getAll($sql);
        $user = $GLOBALS['db']->getAll("select user_name,id from ".DB_PREFIX."user");
        $count = 0;
        foreach($winner as &$v){
            foreach($user as $u){
                if($v['uid'] == $u['id']){
                    $v['user_name'] = $u['user_name'];
                }
            }
            $v['addtime'] = date('H:i:s',$v['addtime']);
            $count++;
        }
//        var_dump($count);die;
        $GLOBALS['tmpl']->assign('count',$count);
        $GLOBALS['tmpl']->assign('winner',$winner);
     	$GLOBALS['tmpl']->assign('data',$data);
     	$GLOBALS['tmpl']->assign('restaraunts',$restaraunts);
        $GLOBALS['tmpl']->display('prize.html');
	}

	public function  get_rand($proArr)
    {
    $result = '';
    $proSum = array_sum($proArr);
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
            } else {
        $proSum -= $proCur;
         }
         }
        unset ($proArr);
        return $result;
    }

     public function ajax(){
         global_run();
         $arr = [];
         $res = [];
         $user_info = $GLOBALS['user_info'];
         $lucky_draw = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."lucky_draw where user_id = ".$user_info['id']);
         if($lucky_draw['number']<1){
            $data['status'] = 0;
            $data['info'] = '您还未获得抽奖次数';
            ajax_return($data);
         }else{
             $GLOBALS['db']->query("update ".DB_PREFIX."lucky_draw set number = number-1,create_time=".time()." where user_id = ".$user_info['id']);
         }
         //中奖项目
         $award = array(11111,1111,111,20,88,'再来一次');
         foreach ($this->prize_arr as $k=>$v){
             $arr[$v['id']] = $v['v'];
         }
         $rid = $this->get_rand($arr);
         //处理中奖后的逻辑
         if($rid == 3){
             //抽中夺宝币，添加到赠送金额字段
             $key = $rid-1;
            $money = $award[$key];
            $res = $GLOBALS['db']->query("update ".DB_PREFIX."user set give_money = give_money+".$money.",can_use_give_money = can_use_give_money+".$money." where id = ".$user_info['id']);
         }elseif($rid == 6){
             //再来一次，次数加一
             $GLOBALS['db']->query("update ".DB_PREFIX."lucky_draw set number = number+1 where user_id = ".$user_info['id']);
         }

         foreach($this->prize_arr as $k=>$v){
             if($v['id']==$rid){
                 $res['prize'] = $v['prize'];
             }
         }
         $data['prize'] = $res['prize'];
         $data['addtime'] = time();
         $data['uid'] = $GLOBALS['user_info']['id'];
         $result = $GLOBALS['db']->autoExecute(DB_PREFIX."prize",$data,'INSERT');
         if($result){
             $data['info'] =$rid;
             $data['status'] =1;
         }else{
             $data['info'] ='下次没准就能中哦';
             $data['status'] =0;
         }
         ajax_return($data);

     }




}
?>
