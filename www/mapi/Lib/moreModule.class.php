<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/8
 * Time: 15:56
 */
class moreApiModule extends MainBaseApiModule{
    public function index(){
        // 发现首页列表
        $indexs_list = $GLOBALS['cache']->get("WAP_MORE_INDEX");
        if($indexs_list===false){
            $indexs = $GLOBALS['db']->getAll(" select * from ".DB_PREFIX."m_more where status = 1 and mobile_type = 1 order by sort asc");
            $indexs_list = array();
            foreach($indexs as $k=>$v) {
                $indexs_list[$k]['id'] = $v['id'];
                $indexs_list[$k]['name'] = $v['name'];
                $indexs_list[$k]['img'] = $v['img'];//图标名 http://fontawesome.io/icon/bars/
                $indexs_list[$k]['desc'] = $v['desc'];//颜色
                $indexs_list[$k]['data'] = $v['data'] = unserialize($v['data']);
                $indexs_list[$k]['ctl'] = $v['ctl'];
            }
            $GLOBALS['cache']->set("WAP_MORE_INDEX", $indexs_list,300);
        }

        $root['indexs'] = $indexs_list?$indexs_list:array();
        return output($root);
    }
}