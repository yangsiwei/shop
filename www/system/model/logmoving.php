<?php
class logmoving {

    /**
     * $data 格式
     * array("duobao_item_id"=>xxx);
     */
    public function exec($data){
        require_once APP_ROOT_PATH."system/model/duobao.php";

        $duobao_item_id = $data['duobao_item_id'];
        $duobao_item = new duobao($duobao_item_id);
        if($duobao_item->duobao_item['log_moved']==1)
        {
            $result['status'] = 1;
            $result['attemp'] = 0;
            $result['info'] = "数据已迁移";
            return $result;
        }
        else
        {
            $duobao_item->move_duobao_log();
        }



        $result['status'] = 1;
        $result['attemp'] = 0;
        $result['info'] = "数据迁移成功";
        return $result;



    }
}