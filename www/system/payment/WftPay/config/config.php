<?php
class Config{
    private $cfg = array(
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
        //'mchId'=>'7551000001',
        //'key'=>'9d101c97133837e13dde2d32a5054abb',
        'version'=>'2.0'
       );
    
    public function __construct(){
        $payment_info = $GLOBALS['db']->getRow("select id,config,logo,name from ".DB_PREFIX."payment where class_name='Wft'");
        $config = unserialize($payment_info['config']);
		
		
		if(es_session::get("trade_type")){
			
			$trade_type = es_session::get("trade_type");
			if($trade_type=='pay.weixin.jspay' || $trade_type=='pay.weixin.native'){
				$this->cfg['mchId'] = $config['mch_id'];
				$this->cfg['key'] = $config['key'];
			}else{
				$this->cfg['mchId'] = $config['wap_mch_id'];
				$this->cfg['key'] = $config['wap_key'];
			}
		}else{
		
			$this->cfg['mchId'] = $config['mch_id'];
			$this->cfg['key'] = $config['key'];
			$this->cfg['wap_mch_id'] = $config['wap_mch_id'];
			$this->cfg['wap_key'] = $config['wap_key'];
		}
        
        
    }
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>