<?php
/* PHP SDK
 * @version 2.0.0
 * @author connect@qq.com
 * @copyright © 2013, Tencent Corporation. All rights reserved.
 */

require_once(CLASS_PATH."ErrorCase.class.php");
class Recorder{
    private static $data;
    private $inc;
    private $error;

    public function __construct(){
        $this->error = new ErrorCase();

        //-------设置配置信息
        $scope = array("get_user_info","add_share","list_album","add_album","upload_pic","add_topic","add_one_blog","add_weibo","check_page_fans","add_t","add_pic_t","del_t","get_repost_list","get_info","get_other_info","get_fanslist","get_idolist","add_idol","del_idol","get_tenpay_addr");
        $scope = join(',', $scope);
        $callback_url = get_domain().'/qq_login';
      /*  if ( isMobile() || IS_MOBILE == 1 ) {
            $callback_url = get_domain().wap_url("index", "index#index", array('wb_login'=>1));
        }*/

        $is_app = $GLOBALS['is_app'];
        if($is_app){
            $mc_data = $GLOBALS['db']->getAll("select code, val from ".DB_PREFIX."m_config where code = 'qq_app_secret' or code='qq_app_key' ");
            foreach ($mc_data as $key=>$value){
                if ( $value['code'] == 'qq_app_secret' ) {
                    $mc['qq_app_secret'] = $value['val'];
                }
                if ( $value['code'] == 'qq_app_key' ) {
                    $mc['qq_app_key'] = $value['val'];
                }
            }
        }
         
        $appid  = $is_app ? $mc['qq_app_key'] : app_conf('QQ_HL_APPID');
        $appkey = $is_app ? $mc['qq_app_secret'] : app_conf('QQ_HL_APPKEY');
        
      
        
        $incFileContents['appid']       =  $appid;
        $incFileContents['appkey']      =  $appkey;
        $incFileContents['callback']    =   $callback_url;
        $incFileContents['scope']       =   $scope; // 授权列表
        $incFileContents['errorReport'] =   true; // 是否开户调试

        $incFileContents = json_encode($incFileContents);
        $incFileContents = json_decode($incFileContents);

        $this->inc = $incFileContents;
        if(empty($this->inc)){
            $this->error->showError("20001");
        }
        $QC_userData =  es_session::get('QC_userData');

        if(empty( $QC_userData )){
            self::$data = array();
        }else{
            self::$data =  $QC_userData;
        }

    }

    public function write($name,$value){
        self::$data[$name] = $value;
    }

    public function read($name){
        if(empty(self::$data[$name])){
            return null;
        }else{
            return self::$data[$name];
        }
    }

    public function readInc($name){
        if(empty($this->inc->$name)){
            return null;
        }else{
            return $this->inc->$name;
        }
    }

    public function delete($name){
        unset(self::$data[$name]);
    }

    function __destruct(){
        es_session::set('QC_userData', self::$data);
    }
}
