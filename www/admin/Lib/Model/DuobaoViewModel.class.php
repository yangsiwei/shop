<?php
/**
*
* @author hhcycj
*/
class  DuobaoViewModel extends ViewModel{
    public $viewFields = array(
        
        'Duobao'=>array( 'origin_price','is_topspeed',"pk_min_number",'is_pk','is_number_choose','unit_price', 'user_max_buy', 'robot_is_lottery', 'robot_buy_min_time', 'robot_buy_max_time', 'robot_buy_min', 'robot_buy_max','robot_type','id', 'deal_id', 'name'=>'duobao_name', 'cate_id', 'description', 'is_effect', 'brief', 'icon', 'brand_id', 'deal_gallery', 'duobao_score', 'invite_score', 'round(max_buy / min_buy)'=>'real_max_buy', 'max_buy', 'min_buy','is_recomend',
                        'max_schedule', 'current_schedule', 'fair_type', 'robot_end_time', 'robot_is_db','is_coupons', 'is_total_buy', 'total_buy_price','is_five','_type'=>'left'),
    
//          'Deal'=>array('id','name','cate_id', 'description' ,'origin_price', 'current_price', 'is_effect', 
//                       'brief', 'icon', 'create_time', 'brand_id', 'sale_count' ),
        
        'DealCate'=>array('name'=>'cate_name', '_on'=>'DealCate.id=Duobao.cate_id', '_type'=>'left'),
        
        'Brand'  => array('name'=>'brand_name', '_on'=>'Brand.id=Duobao.brand_id', '_type'=>'left')
    );
}


 