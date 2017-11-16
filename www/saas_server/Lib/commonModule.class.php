<?php
/**
 * 通用接口
 * @author Administrator
 *
 */
class commonModule{

    /**
     * 获取分类数据
     * 输入：
     * 输出：
     *  [errcode] => 0  
        [errmsg] => 
        [data] => Array
            (
                [0] => Array
                    (
                        [id] => 40  id 分类ID
                        [name] => 手机平板      string 分类名称
                    )
     */
    function get_cate(){
       
        $cate_list = load_auto_cache("cate_list");
        $data = array();
        foreach ($cate_list as $k=>$v){
            $temp_data = array();
            $temp_data['id']= $v['id'];
            $temp_data['name'] = $v['name'];
            
            $data[] = $temp_data;
        }
        $ret = array('errcode'=>0,'errmsg'=>'','data'=>$data);
        
        echo $GLOBALS['saas_server']->toResponse($ret);
    }
    
    /**
     * 获取品牌数据
     * 输入：
     * 输出：
     * 
     */
    function get_brand(){
        
    }
    
}