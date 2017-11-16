<?php
/**
 * 为订单发放分销的推广奖
 * @param unknown_type $order_id 订单ID
 */
function send_fx_order_salary($order_info)
{
    require_once APP_ROOT_PATH."system/model/user.php";
    
    // 获取分销设置
    $fx_salary = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."fx_salary");
    
    // 如果没有开启分销，直接return
    if ($fx_salary[0]['fx_is_open'] != 1) {
        return false;
    }
    
    // 获取上一级，和上一级的上一级用户，分销计算，先获取订单金额，判断固额还是比例计算费用，加入表fanwe_fx_user_reward
    $pid = $GLOBALS['db']->getOne("select pid from ".DB_PREFIX."user where is_fx=1 and id = ".$order_info['user_id']);
    	
    if ($pid) {
        $p_pid = $GLOBALS['db']->getOne("select pid from ".DB_PREFIX."user where is_fx=1 and id = ".$pid);
    }
    	
    if ($p_pid) {
        $p_p_pid = $GLOBALS['db']->getOne("select pid from ".DB_PREFIX."user where is_fx=1 and id = ".$p_pid);
    }
    	
    
    
    // 查看是否退款，如果有退款，分销的只有没退款部分的金额
    if ($order_info['refund_amount'] > 0) {
        $real_money = $order_info['pay_amount'] - $order_info['refund_amount'];
    }else{
        if ($order_info['refund_amount'] == $order_info['pay_amount']) {
            logger::write("订单：{$order_info['order_sn']}, 全部退款，取消分销");
            return false;
        }
        
        $real_money = $order_info['pay_amount'];
    }
    
    foreach ($fx_salary as $value){
        // 定额0， 比率1
        $fx_salary_type = $value['fx_salary_type'];
    
        if($value['fx_level'] == 1){
            $fx_level_one_salary = $value['fx_salary'];
        }
        if($value['fx_level'] == 2){
            $fx_level_two_salary = $value['fx_salary'];
        }
         
        if($value['fx_level'] == 3){
            $fx_level_three_salary = $value['fx_salary'];
        }
         
    }
    	
    if($fx_salary_type == 1){
        $level_one_money = $real_money * $fx_level_one_salary;
        $level_two_money = $real_money * $fx_level_two_salary;
        $level_three_money = $real_money * $fx_level_three_salary;
    }else{
        $level_one_money = $fx_level_one_salary;
        $level_two_money = $fx_level_two_salary;
        $level_three_money = $fx_level_three_salary;
    }
    	
    // 更新给1级分销用户钱款
    if ($pid) {
        // 更新分销营业额和分销利润
        $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_total_money=fx_total_money+{$real_money}, fx_total_balance=fx_total_balance+{$level_one_money} where id = ".$pid);
        //存储分销记录
        $data['pid']     =  $pid;
        $data['user_id'] = $order_info['user_id'];
        $data['cur_user_id'] = $order_info['user_id'];
        $data['money']   =   $level_one_money;
        $data['order_money'] =  $real_money;
        $data['create_time'] =  NOW_TIME;
        $data['order_sn']    = $order_info['order_sn'];
        $data['fx_level']    = 1;
        $data['fx_salary_type'] = $fx_salary_type;
        // 更新分销记录表
        $GLOBALS['db']->autoExecute(DB_PREFIX."fx_user_reward",$data);
        // 用户资金账户函数
        modify_account(array('money'=>$level_one_money),$pid, '一级分销获取推广奖额');
    }
    	
    // 更新给2级分销用户钱款
    if($p_pid){
        // 更新分销营业额和分销利润
        $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_total_money=fx_total_money+{$real_money}, fx_total_balance=fx_total_balance+{$level_two_money} where id = ".$p_pid);
        //存储分销记录
        $data['pid']     =  $p_pid;
        $data['user_id'] = $pid;
        $data['cur_user_id'] = $order_info['user_id'];
        $data['money']   =   $level_two_money;
        $data['order_money'] =  $real_money;
        $data['create_time'] =  NOW_TIME;
        $data['order_sn']    = $order_info['order_sn'];
        $data['fx_level']    = 2;
        $data['fx_salary_type'] = $fx_salary_type;
        // 更新分销记录表
        $GLOBALS['db']->autoExecute(DB_PREFIX."fx_user_reward",$data);
        // 用户资金账户函数
        modify_account(array('money'=>$level_two_money),$p_pid, '二级分销获取推广奖额');
    }
    	
    // 更新给3级分销用户钱款
    if($p_p_pid){
        // 更新分销营业额和分销利润
        $GLOBALS['db']->query("update ".DB_PREFIX."user set fx_total_money=fx_total_money+{$real_money}, fx_total_balance=fx_total_balance+{$level_three_money} where id = ".$p_p_pid);
        //存储分销记录
        $data['pid']     =  $p_p_pid;
        $data['user_id'] = $p_pid;
        $data['cur_user_id'] = $order_info['user_id'];
        $data['money']   =   $level_three_money;
        $data['order_money'] =  $real_money;
        $data['create_time'] =  NOW_TIME;
        $data['order_sn']    = $order_info['order_sn'];
        $data['fx_level']    = 3;
        $data['fx_salary_type'] = $fx_salary_type;
        // 更新分销记录表
        $GLOBALS['db']->autoExecute(DB_PREFIX."fx_user_reward",$data);
        // 用户资金账户函数
        modify_account(array('money'=>$level_three_money),$p_p_pid, '三级分销获取推广奖额');
    }
}


 

 

 






?>