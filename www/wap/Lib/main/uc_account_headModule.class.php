<?php
class uc_account_headModule extends MainBaseModule
{
    public function submit_cert()
    {
        global_run();
        $img_data = $_REQUEST['img_data'];
        $user_data = $GLOBALS['user_info'];
        $file_path = array();
             
            //上传处理
            //创建avatar临时目录
        $user_id = $user_data['id'];
        
        //开始移动图片到相应位置
        
        $uid = sprintf("%09d", $user_id);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $path = $dir1.'/'.$dir2.'/'.$dir3;
        
        //创建相应的目录
        if (!is_dir(APP_ROOT_PATH."public/avatar/".$dir1)) {
            @mkdir(APP_ROOT_PATH."public/avatar/".$dir1);
            @chmod(APP_ROOT_PATH."public/avatar/".$dir1, 0777);
        }
        if (!is_dir(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2)) {
            @mkdir(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2);
            @chmod(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2, 0777);
        }
        if (!is_dir(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2.'/'.$dir3)) {
            @mkdir(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2.'/'.$dir3);
            @chmod(APP_ROOT_PATH."public/avatar/".$dir1.'/'.$dir2.'/'.$dir3, 0777);
        }
        
        $id = str_pad($user_id, 2, "0", STR_PAD_LEFT);
        $id = substr($user_id,-2);
        $dir = APP_ROOT_PATH."public/avatar/".$path."/".$id."avatar.jpg";
        
        $main="./public/avatar/temp/";
         
        $max_image_size = app_conf("MAX_IMAGE_SIZE");
        
        $f_img_data = array();
        $temp_arr = array();
        $json_arr = array();
        $json_arr = (array)json_decode($img_data);
        if ($json_arr['size']<=$max_image_size){
            preg_match("/data:image\/(jpg|jpeg|png|gif);base64,/i",$json_arr['base64'],$res);
            $temp_arr['ext'] = $res[1];
            if(!in_array($temp_arr['ext'],array("jpg","jpeg","png","gif"))){
                $result['status'] = 0;
                $result['info'] = '上传文件格式有误';
                ajax_return($result);
            }
            $temp_arr['size'] = $json_arr['size'];
            $temp_arr['img_data'] = preg_replace("/data:image\/(jpg|jpeg|png|gif);base64,/i","",$json_arr['base64']);
            $temp_arr['file_name'] = time().md5(rand(0,100)).'.'.$temp_arr['ext'];
            $f_img_data[] = $temp_arr;
        }
        foreach ($f_img_data as $k=>$v){
            delete_avatar($user_id);
            if (file_put_contents($dir, base64_decode($v['img_data']))===false) {
                $result['status'] = 0;
                $result['info'] = '上传文件失败';
                ajax_return($result);
            }else{
                $dir = "./public/avatar/".$path."/".$id."avatar.jpg";
                $GLOBALS['db']->query("update ".DB_PREFIX."user set avatar = '".$dir."' where id =' ".$user_id );
                
                //上传头像可领取优惠币数量(限一次)
                $change_logo_coupons = app_conf('USER_CHANGE_LOGO_COUPONS');    
                $has_change_logo = $GLOBALS['db']->query("select has_change_logo from ".DB_PREFIX."user where and id = ".$user_id );
                if (intval($has_change_logo) == 0) {
                    $GLOBALS['db']->query("update ".DB_PREFIX."user set has_change_logo=1, coupons = coupons + ".$change_logo_coupons." where has_change_logo = 0 and id = ".$user_id );
                    
                }
//                 $avatar_key = md5("USER_AVATAR_".$user_id);
//                 unset($GLOBALS['dynamic_avatar_cache'][$avatar_key]);
//                 $GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/avatar_cache/");
//                 $GLOBALS['cache']->set("AVATAR_DYNAMIC_CACHE",$GLOBALS['dynamic_avatar_cache']); //头像的动态缓存
                $data['small_url'] = get_user_avatar($user_id,"small");
                $data['middle_url'] = get_user_avatar($user_id,"middle");
                $data['big_url'] = get_user_avatar($user_id,"big");
                $data['info'] = '修改头像成功';

             
            }
        }
        $data['status']=1;
        ajax_return($data);
    }
}
?>