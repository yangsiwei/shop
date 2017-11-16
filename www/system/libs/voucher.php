<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

//关于代金券的全局函数
/**
 * 代金券发放
 * @param $ecv_type_id 代金券类型ID
 * @param $user_id  发放给的会员。0为线下模式的发放
 */
function send_voucher($ecv_type_id,$user_id=0,$is_password=false, $order_info=null)
{
    if ( is_array( $ecv_type_id ) ) {
        $ecv_type = $ecv_type_id;
        $ecv_type_id = $ecv_type['id'];
        
        $data_money = json_decode($ecv_type['data'], 1);
        if( $ecv_type['sm_way'] == 1 ){
            // 随机金额
            $ecv_type['money'] = round( $data_money['rand_value1'] + mt_rand() / mt_getrandmax() * ( $data_money['rand_value2'] - $data_money['rand_value1'] ), 2);
        }else if ($ecv_type['sm_way'] == 2){
            // 百分比金额，主要是充值的时候发放的, 订单金额的百分比
            $ecv_type['money'] = round( $order_info['pay_amount'] * ($ecv_type['money'] / 100), 2);
        }else {
            ;
        }
       
    }else{
        $GLOBALS['db']->query("update ".DB_PREFIX."ecv_type set gen_count = gen_count + 1 where id = ".$ecv_type_id." and (total_limit = 0 or gen_count + 1 <= total_limit)");
        if(!$GLOBALS['db']->affected_rows())
        {
            return -1;
        }
        
        $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where id = ".$ecv_type_id);
    }
	
   
    
	if(!$ecv_type)return false;
	
	if($ecv_type['money'] <= 0) return false;
	
	if($is_password)$ecv_data['password'] = rand(10000000,99999999);
	
	$ecv_data['use_limit']     = $ecv_type['use_limit'];
	$ecv_data['begin_time']    = $ecv_type['begin_time'];
	$ecv_data['end_time']      = $ecv_type['end_time'];
	$ecv_data['money']         = $ecv_type['money'];
	$ecv_data['ecv_type_id']   = $ecv_type_id;
	$ecv_data['user_id']       = $user_id;	
	$ecv_data['data']          = $ecv_type['data'];
	$ecv_data['is_all']        = $ecv_type['is_all'];
	$ecv_data['meet_amount']   = $ecv_type['meet_amount'];
	 
	do{
		$sn = unpack('H12',str_shuffle(md5(uniqid())));
		$sn = $sn[1];
		$ecv_data['sn'] = $sn;
		//$ecv_data['sn'] = md5(NOW_TIME);
		$GLOBALS['db']->autoExecute(DB_PREFIX."ecv",$ecv_data,'INSERT','','SILENT');
		$insert_id = $GLOBALS['db']->insert_id();
	}while(intval($insert_id) == 0);
	if(!$insert_id)
	{
		$GLOBALS['db']->query("update ".DB_PREFIX."ecv_type set gen_count = gen_count - 1 where id = ".$ecv_type_id);		
	}
	return $insert_id;
}

?>