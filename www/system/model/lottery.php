<?php

/**
 * 极速开奖计划任务
 */
class lottery{

    /**
     * $data 格式
     * array("duobao_item_id"=>xxx);
     */
    public function exec($data){
        require_once APP_ROOT_PATH."system/model/duobao.php";

        $duobao_item_id = $data['duobao_item_id'];
        $duobao_item = new duobao($duobao_item_id);
        if(!$duobao_item->duobao_item['id'])
        {
            $result['status'] = 1;
            $result['attemp'] = 0;
            $result['info'] = "活动过期";
            return $result;
        }
            $sql = "select * from ".DB_PREFIX."fair_fetch where fair_type = '".$duobao_item->duobao_item['fair_type']."' and period = '".$duobao_item->duobao_item['fair_period']."'";
            $fair_period = $GLOBALS['db']->getRow($sql);
            if($fair_period['number'])
            {
                //当前期已开奖
                $duobao_item->tospeed_draw_lottery($fair_period['period'], $fair_period['number']);
            }
            else
            {
                    $duobao_item->tospeed_draw_lottery($duobao_item->duobao_item['fair_period'], DEFAULT_LOTTERY);
            }
        $result['status'] = 1;
        $result['attemp'] = 0;
        $result['info'] = "开奖计划执行成功";
        return $result;



    }
}