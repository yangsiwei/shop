<?php
/**
*
* @author hhcycj
*/
class  DuobaoItemLogViewModel extends ViewModel{
    public $viewFields = array(
        'DuobaoItemLog'=>array('id', 'deal_id', 'duobao_id', 'duobao_item_id', 'lottery_sn', 'user_id', 'order_id', 'order_item_id', 'create_time', 'is_luck', 'duobao_ip', 'duobao_area', '_type'=>'left'),
        'User'=>array('user_name', '_on'=>'User.id=DuobaoItemLog.user_id', '_type'=>'left'),
    );
}

 
 
 
 