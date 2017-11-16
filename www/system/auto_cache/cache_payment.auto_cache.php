<?php
//支付列表
class cache_payment_auto_cache extends auto_cache{
	public function load($param)
	{
		$param = array();
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$payment_list = $GLOBALS['cache']->get($key);
		if($payment_list===false)
		{
			/* online_pay 支付方式：1：在线支付；0：线下支付; 2:手机支付(wap); 3:手机SDK */
			$payment_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."payment where online_pay in (0,1,6,7) and is_effect = 1 order by sort desc");
			
			foreach($payment_list as $k=>$v)
			{
				$directory = APP_ROOT_PATH."system/payment/";
				$file = $directory. '/' .$v['class_name']."_payment.php";
				if(file_exists($file))
				{
					require_once($file);
					$payment_class = $v['class_name']."_payment";
					$payment_object = new $payment_class();
					$payment_list[$k]['display_code'] = $payment_object->get_display_code();
					$payment_list[$k]['is_bank'] = intval($payment_object->is_bank);
						
				}
				else
				{
					unset($payment_list[$k]);
				}
			}
			

			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$payment_list);
		}	
		return $payment_list;
	}
	public function rm($param)
	{
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$GLOBALS['cache']->rm($key);
	}
	public function clear_all()
	{
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$GLOBALS['cache']->clear();
	}
}
?>