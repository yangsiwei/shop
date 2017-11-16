<?php
class AgreementModel extends Model {
    protected $_auto = array (
        array('create_time','getNowTime', Model:: MODEL_INSERT, 'callback' ),
    );
    
    public function getNowTime(){
        return NOW_TIME;
    }
    
}
?>