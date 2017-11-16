<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class ecvApiModule extends MainBaseApiModule
{
    
    /**
     * 活动详情接口
     * 输入：
     * data_id: int 活动ID
     *
     * 输出：
     * user_login_status:int 用户登录状态(1 已经登录/0用户未登录/2临时用户)
     * 

     *
     * */
    public function index(){
        $root = array();
        /*参数列表*/
        $exchange_sn = strim($GLOBALS['request']['exchange_sn']);
        
        /*业务逻辑*/
        //检查用户,用户密码
        $user = $GLOBALS['user_info'];
        $user_id  = intval($user['id']);
        
        $root['user_login_status'] = check_login();
        
        $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where exchange_sn=".$exchange_sn);
        $ecv_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv where ecv_type_id=".$ecv_type['id']." and user_id = ".$user_id);
        if($ecv_data){
            $GLOBALS['tmpl']->assign("status",1);
            $GLOBALS['tmpl']->assign("ecv_data",$ecv_data);
        }else{
            $GLOBALS['tmpl']->assign("status",0);
        }
        $GLOBALS['tmpl']->assign("status",0);
        $GLOBALS['tmpl']->assign("data",$ecv_type);
        $root['html'] = $GLOBALS['tmpl']->fetch(APP_ROOT_PATH."system/ecv_tpl/".$ecv_type['tpl']);
        $root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
        $root['page_title'].="红包领取";
        
        return output($root);
    }
    
    public function do_snexchange(){
        $root = array();
        /*参数列表*/
        $exchange_sn = trim($GLOBALS['request']['exchange_sn']);
        
        /*业务逻辑*/
        //检查用户,用户密码
        $user = $GLOBALS['user_info'];
        $user_id  = intval($user['id']);
        $user_login_status = check_login();
        if($user_login_status!=LOGIN_STATUS_LOGINED){
            $root['user_login_status'] = $user_login_status;
        }else{
            $root['user_login_status'] = 1;
            $ecv_type = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv_type where exchange_sn=".$exchange_sn);
          
            if(!$ecv_type)
            {
                $GLOBALS['tmpl']->assign("status",-1);
                $GLOBALS['tmpl']->assign("data",$ecv_type);
                $GLOBALS['tmpl']->assign("info","此红包已经失效");
                $root['html'] = $GLOBALS['tmpl']->fetch(APP_ROOT_PATH."system/ecv_tpl/".$ecv_type['tpl']);
                return output($root,0,"领取失败");
            }
            else
            {
                $exchange_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."ecv where ecv_type_id = ".$ecv_type['id']." and user_id = ".$user_id);

                if($ecv_type['exchange_limit']>0&&$exchange_count>=$ecv_type['exchange_limit'])
                {

                    $GLOBALS['tmpl']->assign("status",-1);
                    $GLOBALS['tmpl']->assign("data",$ecv_type);
                    $GLOBALS['tmpl']->assign("info","每个会员只能领取".$ecv_type['exchange_limit']."张");
                    $root['html'] = $GLOBALS['tmpl']->fetch(APP_ROOT_PATH."system/ecv_tpl/".$ecv_type['tpl']);
                    return output($root,0,"领取失败");
                }
                else
                {
                    require_once APP_ROOT_PATH."system/libs/voucher.php";
                    $rs = send_voucher($ecv_type['id'],$GLOBALS['user_info']['id'],1);
                    if($rs>0)
                    {
                        $ecv_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."ecv where id=".$rs);
                        $GLOBALS['tmpl']->assign("status",1);
                        $GLOBALS['tmpl']->assign("data",$ecv_type);
                        $GLOBALS['tmpl']->assign("ecv_data",$ecv_data);
                        $root['html'] = $GLOBALS['tmpl']->fetch(APP_ROOT_PATH."system/ecv_tpl/".$ecv_type['tpl']);
                        return output($root,1,"领取成功");
                    }
                    else if($rs==-1)
                    {
                    	return output($root,0,"您来晚了，红包已领光了!");
                    }
                    else
                    {
                        return output($root,0,"领取失败");
                    }
                }
            }
        }
        
        return output($root);
    }
    
    

    
    

}

