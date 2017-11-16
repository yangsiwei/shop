<?php
/**
*
* @author hhcycj
*/
class  TotalbuyViewModel extends ViewModel{
    public $viewFields = array(
        'DealOrder'=>array('order_sn'=>'deal_order_sn', 'user_id', 'create_time', 'pay_status','order_status','region_info','total_price', 'pay_amount',  '_type'=>'left'),
    	'DealOrderItem'=>array( 'id','name','number','duobao_item_id','order_id','delivery_status'=>'delivery_status_item', '_on'=>'DealOrderItem.order_id=DealOrder.id', '_type'=>'left'),
    );
}


 
  