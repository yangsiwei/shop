<?php
/**
*
* @author hhcycj
*/
class  DealOrderHistoryViewModel extends ViewModel{
    
    public function __construct($name="")
    {
       $cache_fields = F("_fields/DealOrderHistory");
       $cache_fields['_type'] = "left";
       $this->viewFields =  array(

         'DealOrderHistory'=>$cache_fields,
    		
    	 'User'=>array( 'is_robot', '_on'=>'DealOrderHistory.user_id=User.id', '_type'=>'left'),   		
    
        );       
       parent::__construct($name);
    }    
    
    public $viewFields;    

}


 
 
 
 