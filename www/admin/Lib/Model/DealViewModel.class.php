<?php
/**
*
* @author hhcycj
*/
class  DealViewModel extends ViewModel{
    public $viewFields = array(
    
        'Deal'=>array('id','name'=>'deal_name','cate_id', 'description' ,'origin_price', 'current_price', 'is_effect', 
                      'brief', 'icon', 'create_time', 'brand_id', 'total_buy_stock', '_type'=>'left' ),
        
        'DealCate'=>array('name'=>'cate_name', 'iconcolor', '_on'=>'DealCate.id=Deal.cate_id', '_type'=>'left'),
        
        'Brand'  => array('name'=>'brand_name', '_on'=>'Brand.id=Deal.brand_id', '_type'=>'left')
    );
}


 