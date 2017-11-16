<?php
/**
*
* @author hhcycj
*/
class  DealOrderViewModel extends ViewModel{
    public $viewFields = array(

        'DealOrder'=>array( '*','order_sn'=>'deal_order_sn', '_type'=>'left'),
    		
    	'User'=>array( 'is_robot', '_on'=>'DealOrder.user_id=User.id', '_type'=>'left'),
    	'DealOrderItem'=>array( 'name','number','duobao_item_id','delivery_status'=>'delivery_status_item','lottery_sn','_on'=>'DealOrderItem.order_id=DealOrder.id', '_type'=>'left'),
    		
    
    );
}


 
 
 
 