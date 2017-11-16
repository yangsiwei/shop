<?php
class ShareModel extends Model {
    protected $_auto = array (
        array('create_time','getNowTime', Model:: MODEL_INSERT, 'callback' ),
        array('type','1'),
    );
    
    public function getNowTime(){
        return NOW_TIME;
    }
    
  
    
}
?>