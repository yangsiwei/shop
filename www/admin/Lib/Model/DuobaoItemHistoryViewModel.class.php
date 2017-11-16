<?php
/**
*
* @author hhcycj
*/
class  DuobaoItemHistoryViewModel extends ViewModel{
    public $viewFields = array(
        
        'DuobaoItemHistory'=>array('id', 'deal_id', 'duobao_id', 'name'=>'duobaoitem_name', 'cate_id', 'description', 'is_effect', 'brief', 'icon', 'brand_id', 'deal_gallery', 'create_time', 
                            'duobao_score', 'invite_score', 'max_buy', 'min_buy', 'current_buy', 'progress', 'lottery_sn', 'has_lottery', 'success_time', 'lottery_time', 'fair_sn',
                            'luck_user_id', 'click_count', 'fair_type', 'robot_end_time', 'robot_is_db', 'history_duobao_item_log', 'history_duobao_order', '_type'=>'left'),
        
        'User'=>array('user_name', '_on'=>'User.id=DuobaoItemHistory.luck_user_id', '_type'=>'left'),
    
        'DealCate'=>array('name'=>'cate_name', '_on'=>'DealCate.id=DuobaoItemHistory.cate_id', '_type'=>'left'),
        
        'Brand'  => array('name'=>'brand_name', '_on'=>'Brand.id=DuobaoItemHistory.brand_id', '_type'=>'left')
    );
}


 
 
 