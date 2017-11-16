<?php
// 白名单数组 二维数组(数组格式)
/**
 * array(
 * 'html标签名'=>array(
 * 'property允许的属性'=>array(
 * '如：size',
 * 'border',
 * 'width'
 * ),
 * 'class允许的类'=>array(
 * '如：color',
 * 'background-color',
 * 'font-size'
 * ),
 * )
 * );
 */
// 黑名单数组 一维数组(数组格式)
/**
 * array(
 * '要替换的内容'=>'替换的值',
 * '\\'=>'/',
 * '>'=>'',
 * '%%'=>'%'
 * );
 */
// 取得白名单，黑名单

// 先去除白名单以外的所有标签，仅保留白名单允许的标签
// 去除不可见字符====【未开发】
// 替换掉黑名单====【未开发】
// 对允许的标签进行匹配，过滤除白名单外的属性，过滤Class 中的类名


function no_xss($str,$white_config)
{
    static $white_config;
    if(empty($white_config)){
       $white_config = require_once APP_ROOT_PATH.'system/white_config.php';
    }
    //过滤屌白名单以外 的标签
    $str = filter_html_tag($str,$white_config);
    $str = filter_property_class($str,$white_config);

    return $str;
}
/**
 * 过滤HTML标签 除 白名单以外的标签
 * @param string $str
 * @param $white_config 白名单配置
 * @return string
 */
function filter_html_tag($str,$white_config){
    $white_html_tag = array();
    $preg = '/<(?!';
    foreach ($white_config as $k=>$v){
        $white_html_tag[] = $k;
        $preg.=$k."\b|\/".$k."\b|";
    }
    $preg=substr($preg,0,-1);
    $preg.=")[^>]*>/i";
    return preg_replace($preg,"",$str);

}
/**
 * 过滤标签中的 属性和类
 * @param string $str
 * @param array $white_config 白名单配置
 * @return string
 */
function filter_property_class($str,$white_config){
    //匹配出白名单允许的标签和内容 数组化
    foreach ($white_config as $k=>$v){
        preg_match_all('/<'.$k.'\b[^>]*?>/i',$str,$match_all);
        $data[$k]['str_arr'] = $match_all[0];

    }
    //过滤允许标签中的属性
    foreach ($data as $k=>$v){
        $temp_preg = "/\s(?!".implode("|", $white_config[$k]['property']).")\w+=[\"|\'][^\"|\']*[\"|\']/";
        foreach ($v['str_arr'] as $sub_k=>$sub_v){
            $data[$k]['pre_arr'][] = preg_replace($temp_preg, "", $sub_v);
        }
    }
 
    
    //过滤样式里面允许的以外的内容
    // color:#333333; font-size:14px; font-family:arial, 宋体, sans-serif; line-height:24px; background-color:#FFFFFF; 
    // \s(?!line-height|font-family)[\w|-]+\:[^;]+; （开头空格， 后面判断 分号+空格 ; ）
    foreach($data as $k=>$v){
        $temp_preg = "/\s(?!".implode("|", $white_config[$k]['class']).")[\w|-]+\:[^;]+;/";

        foreach ($v['pre_arr'] as $sub_k=>$sub_v){
            preg_match("/style=\"(.*?)\"/", $sub_v,$temp_matches);
            if($temp_matches){
                $temp_style = " ".str_replace(";", "; ", $temp_matches[1]);
                $temp_replace = preg_replace($temp_preg, "", $temp_style);
                $data[$k]['pre_arr'][$sub_k] = preg_replace("/style=\"(.*?)\"/", 'style="'.$temp_replace.'"', $data[$k]['pre_arr'][$sub_k]);
            }
            
        }
        
    }

    foreach ($data as $k=>$v){    	
        $str = str_replace($v['str_arr'], $v['pre_arr'], $str);
    }
    return $str;
}

?>