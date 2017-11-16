<?php

/**
 * Class MsgGroupcast
 * app推送消息组播
 *
 */
class MsgGroupcast{
    /**
     * @param $data
     * @return array|mixed
     * filter数据类型array("and"=>array(array("app_version"=>"1.0")))
     * 可以过滤的字段
     *"app_version"(应用版本)
     *"channel"(渠道)
     *"device_model"(设备型号)
     *"province"(省)
     *"tag"(用户标签)
     *"country"(国家) //"country"和"province"的类型定义请参照 附录J
     *"language"(语言)
     *"launch_from"(一段时间内活跃)
     *"not_launch_from"(一段时间内不活跃)
     */
    public function exec_android($data){
        require_once(APP_ROOT_PATH. 'system/umeng/notification/android/AndroiduGroupcast.php');
        try {
            $appMasterSecret = $GLOBALS['db']->getOne("select val from ".DB_PREFIX."m_config where code = 'android_master_secret'");
            $appkey = $GLOBALS['db']->getOne("select val from ".DB_PREFIX."m_config where code = 'android_app_key'");


            $title = app_conf("SHOP_TITLE");

            $filter[]=array("where"=>$data['filter']);//必填，限制条件
            $groupcast = new AndroidGroupcast();
            $groupcast->setAppMasterSecret($appMasterSecret);
            $groupcast->setPredefinedKeyValue("appkey",           $appkey);
            $groupcast->setPredefinedKeyValue("timestamp",        strval(time()));// 必填 时间戳，10位或者13位均可，时间戳有效期为10分钟 NOW_TIME
            // Set your device tokens here
            $groupcast->setPredefinedKeyValue("filter",$filter);
            $groupcast->setPredefinedKeyValue("ticker",           $data['content']);//必填 通知栏提示文字
            $groupcast->setPredefinedKeyValue("title",            $title);// 必填 通知标题
            $groupcast->setPredefinedKeyValue("text",             $data['content']);// 必填 通知文字描述
            $groupcast->setPredefinedKeyValue("after_open",       "go_app");//"go_app": 打开应用;"go_url": 跳转到URL;"go_activity": 打开特定的activity;"go_custom": 用户自定义内容。
            // Set 'production_mode' to 'false' if it's a test device.
            // For how to register a test device, please see the developer doc.
            $groupcast->setPredefinedKeyValue("production_mode", "true");//可选 正式/测试模式。测试模式下，只会将消息发给测试设备。
            // Set extra fields
            //$unicast->setExtraField("test", "helloworld");
            //print("Sending unicast notification, please wait...\r\n");
            //json_decode($data) {"ret":"SUCCESS","data":{"msg_id":"uu05362143574400482600"}}
            $result = $groupcast->send();
            //print_r($result);
            $res = json_decode($result,1);
            //print("Sent SUCCESS\r\n");
            if ($res['ret'] == 'SUCCESS'){
                $is_success = 1;
            }else{
                $is_success = 0;
                $message = addslashes(print_r($result,true));
            }

        } catch (Exception $e) {
            $is_success = 0;
            $message = addslashes($e->getMessage());

        }

        $result = array();
        $result['status'] = $is_success;
        $result['attemp'] = 0;
        $result['info'] = $message;
        return $result;
    }
    public function exec_ios($data){


        require_once(APP_ROOT_PATH. 'system/umeng/notification/ios/IOSGroupcast.php');

        try {
            $appMasterSecret = $GLOBALS['db']->getOne("select val from ".DB_PREFIX."m_config where code = 'ios_master_secret'");
            $appkey = $GLOBALS['db']->getOne("select val from ".DB_PREFIX."m_config where code = 'ios_app_key'");

            $filter[]=array("where"=>$data['filter']);
            $groupcast = new IOSGroupcast();
            $groupcast->setAppMasterSecret($appMasterSecret);
            $groupcast->setPredefinedKeyValue("appkey",           $appkey);
            $groupcast->setPredefinedKeyValue("timestamp",        strval(time()));
            // Set your device tokens here
            $groupcast->setPredefinedKeyValue("filter",$filter);
            $groupcast->setPredefinedKeyValue("alert", $data['content']);
            $groupcast->setPredefinedKeyValue("badge", 1);
            $groupcast->setPredefinedKeyValue("sound", "chime");
            // Set 'production_mode' to 'true' if your app is under production mode
            $groupcast->setPredefinedKeyValue("production_mode", "true");
            $result = $groupcast->send();

            $res = json_decode($result,1);
            //print("Sent SUCCESS\r\n");
            if ($res['ret'] == 'SUCCESS'){
                $is_success = 1;
            }else{
                $is_success = 0;
                $message = addslashes(print_r($result,true));
            }

        } catch (Exception $e) {
            $is_success = 0;
            $message = strim($e->getMessage());
//            return false;
        }

        $result = array();
        $result['status'] = $is_success;
        $result['attemp'] = 0;
        $result['info'] = $message;
        return $result;
    }
}