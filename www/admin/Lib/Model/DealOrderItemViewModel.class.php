<?php
/**
*
* @author hhcycj
*/
class  DealOrderItemViewModel extends ViewModel{
    public $viewFields = array(
        
        'DealOrderItem'=>array('id','order_id', 'order_sn'=>'deal_order_sn','name','duobao_item_id','user_id','total_price','lottery_sn', '_type'=>'left'),
        
        'DealOrder'=>array( 'type','create_time','pay_status','is_delete', '_on'=>'DealOrder.id=DealOrderItem.order_id', '_type'=>'left'),
    		
    	'User'=>array( 'is_robot', '_on'=>'DealOrder.user_id=User.id', '_type'=>'left'),
    		
    
    );
}


 
 
 
 