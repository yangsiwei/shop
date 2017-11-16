<?php
define('APP_TYPE','main');
define('FANWE',0);

$dist_cfg =APP_ROOT_PATH."system/dist_cfg.php";
if(file_exists($dist_cfg))$distribution_cfg =require_once $dist_cfg;
$cfg_file =APP_ROOT_PATH.'system/config.php';
if(file_exists($cfg_file))
{
    $sys_config =require_once APP_ROOT_PATH.'system/config.php';
}
if(!function_exists('app_conf'))
{
    function app_conf($name)
    {
        return stripslashes($GLOBALS['sys_config'][$name]);
    }
}
if(function_exists('date_default_timezone_set'))date_default_timezone_set(app_conf('DEFAULT_TIMEZONE'));
$define_file =APP_ROOT_PATH."system/definei.php";
if(file_exists($define_file))require_once $define_file;
define('DB_PREFIX', app_conf('DB_PREFIX'));
if(!function_exists('load_fanwe_cache'))
{
    function load_fanwe_cache()
    {
        global $distribution_cfg;
        $type =$distribution_cfg["CACHE_TYPE"];
        $cacheClass ='Cache'.ucwords(strtolower(strim($type)))."Service";
        if(file_exists(APP_ROOT_PATH.'system/cache/'.$cacheClass.".php"))
        {
            require_once APP_ROOT_PATH."system/cache/".$cacheClass.".php";
            if(class_exists($cacheClass))
            {
                $cache =new $cacheClass();
            }
            return $cache;
        }
        else 
        {
            $file_cache_file =APP_ROOT_PATH.'system/cache/CacheFileService.php';
            if(file_exists($file_cache_file))require_once APP_ROOT_PATH.'system/cache/CacheFileService.php';
            if(class_exists('CacheFileService'))$cache =new CacheFileService();
            return $cache;
        }
    }
}
$cache_service_file =APP_ROOT_PATH."system/cache/Cache.php";
if(file_exists($cache_service_file))require_once $cache_service_file;
if(class_exists('CacheService'))$cache =CacheService::getInstance();
$db_cls_file =APP_ROOT_PATH."system/db/db.php";
if(file_exists($db_cls_file))
{
    require_once $db_cls_file;
    if(class_exists('mysql_db'))
    {
        if(!file_exists(APP_ROOT_PATH.'public/runtime/app/db_caches/'))mkdir(APP_ROOT_PATH.'public/runtime/app/db_caches/',0777);
        $pconnect =false;
        $db =new mysql_db(app_conf('DB_HOST').":".app_conf('DB_PORT'), app_conf('DB_USER'),app_conf('DB_PWD'),app_conf('DB_NAME'),'utf8',$pconnect);
    }
}
$tmpl_cls_file =APP_ROOT_PATH.'system/template/template.php';
if(file_exists($tmpl_cls_file))
{
    require_once $tmpl_cls_file;
    if(class_exists('AppTemplate'))
    {
        if(!file_exists(APP_ROOT_PATH.'public/runtime/app/tpl_caches/'))mkdir(APP_ROOT_PATH.'public/runtime/app/tpl_caches/',0777);
        if(!file_exists(APP_ROOT_PATH.'public/runtime/app/tpl_compiled/'))mkdir(APP_ROOT_PATH.'public/runtime/app/tpl_compiled/',0777);
        $tmpl =new AppTemplate;
    }
}
$lang_file =APP_ROOT_PATH.'/app/Lang/'.app_conf("SHOP_LANG").'/lang.php';
if(file_exists($lang_file))$lang =require_once $lang_file;
if(!function_exists('replace_public'))
{
    function replace_public($str)
    {
        if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
        {
            $domain =$GLOBALS['distribution_cfg']['OSS_DOMAIN'];
        }
        else 
        {
            $domain =SITE_DOMAIN.APP_ROOT;
        }
        return str_replace($domain."/public/","./public/",$str);
    }
}
if(!function_exists('format_image_path'))
{
    function format_image_path($out)
    {
        if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
        {
            $domain =$GLOBALS['distribution_cfg']['OSS_DOMAIN'];
        }
        else 
        {
            $domain =SITE_DOMAIN.APP_ROOT;
        }
        $out =str_replace(APP_ROOT."./public/",$domain."/public/",$out);
        $out =str_replace("./public/",$domain."/public/",$out);
        return $out;
    }
}
if(!function_exists('syn_to_remote_image_server'))
{
    function syn_to_remote_image_server($url)
    {
        if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
        {
            if($GLOBALS['distribution_cfg']['OSS_TYPE']=="ES_FILE")
            {
                $pathinfo =pathinfo($url);
                $file =$pathinfo['basename'];
                $dir =$pathinfo['dirname'];
                $dir =str_replace("./public/", "", $dir);
                $filefull =SITE_DOMAIN.APP_ROOT."/public/".$dir."/".$file;
                $syn_url =$GLOBALS['distribution_cfg']['OSS_DOMAIN']."/es_file.php?username=".$GLOBALS['distribution_cfg']['OSS_ACCESS_ID']."&password=".$GLOBALS['distribution_cfg']['OSS_ACCESS_KEY']."&file=". $filefull."&path=".$dir."/&name=".$file."&act=0";
                @file_get_contents($syn_url);
            }
            elseif($GLOBALS['distribution_cfg']['OSS_TYPE']=="ALI_OSS")
            {
                $pathinfo =pathinfo($url);
                $file =$pathinfo['basename'];
                $dir =$pathinfo['dirname'];
                $dir =str_replace("./public/", "public/", $dir);
                $ali_oss_sdk =APP_ROOT_PATH."system/alioss/sdk.class.php";
                if(file_exists($ali_oss_sdk))
                {
                    require_once $ali_oss_sdk;
                    if(class_exists('ALIOSS'))
                    {
                        $oss_sdk_service =new ALIOSS();
                        $oss_sdk_service->set_debug_mode(FALSE);
                        $bucket =$GLOBALS['distribution_cfg']['OSS_BUCKET_NAME'];
                        $object =$dir."/".$file;
                        $file_path =APP_ROOT_PATH.$dir."/".$file;
                        $oss_sdk_service->upload_file_by_file($bucket,$object,$file_path);
                    }
                }
            }
        }
    }
}
if(!function_exists('syn_to_remote_file_server'))
{
    function syn_to_remote_file_server($url)
    {
        if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
        {
            if($GLOBALS['distribution_cfg']['OSS_TYPE']=="ES_FILE")
            {
                $pathinfo =pathinfo($url);
                $file =$pathinfo['basename'];
                $dir =$pathinfo['dirname'];
                $dir =str_replace("public/", "", $dir);
                $filefull =SITE_DOMAIN.APP_ROOT."/public/".$dir."/".$file;
                $syn_url =$GLOBALS['distribution_cfg']['OSS_DOMAIN']."/es_file.php?username=".$GLOBALS['distribution_cfg']['OSS_ACCESS_ID']."&password=".$GLOBALS['distribution_cfg']['OSS_ACCESS_KEY']."&file=". $filefull."&path=".$dir."/&name=".$file."&act=0";
                @file_get_contents($syn_url);
            }
            elseif($GLOBALS['distribution_cfg']['OSS_TYPE']=="ALI_OSS")
            {
                $pathinfo =pathinfo($url);
                $file =$pathinfo['basename'];
                $dir =$pathinfo['dirname'];
                $ali_oss_sdk =APP_ROOT_PATH."system/alioss/sdk.class.php";
                if(file_exists($ali_oss_sdk))
                {
                    require_once $ali_oss_sdk;
                    if(class_exists('ALIOSS'))
                    {
                        $oss_sdk_service =new ALIOSS();
                        $oss_sdk_service->set_debug_mode(FALSE);
                        $bucket =$GLOBALS['distribution_cfg']['OSS_BUCKET_NAME'];
                        $object =$dir."/".$file;
                        $file_path =APP_ROOT_PATH.$dir."/".$file;
                        $oss_sdk_service->upload_file_by_file($bucket,$object,$file_path);
                    }
                }
            }
        }
    }
}
if(!class_exists('FanweSessionHandler'))
{
    class FanweSessionHandler 
    {
        private $savePath;
        private $mem;
        private $db;
        private $table;
        function open($savePath, $sessionName)
        {
            $this->savePath =APP_ROOT_PATH.$GLOBALS['distribution_cfg']['SESSION_FILE_PATH'];
            if($GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL")
            {
                $this->mem =require_once APP_ROOT_PATH."system/cache/MemcacheSASL/MemcacheSASL.php";
                $this->mem =new MemcacheSASL;
                $this->mem->addServer($GLOBALS['distribution_cfg']['SESSION_CLIENT'], $GLOBALS['distribution_cfg']['SESSION_PORT']);
                $this->mem->setSaslAuthData($GLOBALS['distribution_cfg']['SESSION_USERNAME'],$GLOBALS['distribution_cfg']['SESSION_PASSWORD']);
            }
            elseif($GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
            {
                $pconnect =false;
                $session_client =$GLOBALS['distribution_cfg']['SESSION_CLIENT']==""?app_conf('DB_HOST'):$GLOBALS['distribution_cfg']['SESSION_CLIENT'];
                $session_port =$GLOBALS['distribution_cfg']['SESSION_PORT']==""?app_conf('DB_PORT'):$GLOBALS['distribution_cfg']['SESSION_PORT'];
                $session_username =$GLOBALS['distribution_cfg']['SESSION_USERNAME']==""?app_conf('DB_USER'):$GLOBALS['distribution_cfg']['SESSION_USERNAME'];
                $session_password =$GLOBALS['distribution_cfg']['SESSION_PASSWORD']==""?app_conf('DB_PWD'):$GLOBALS['distribution_cfg']['SESSION_PASSWORD'];
                $session_db =$GLOBALS['distribution_cfg']['SESSION_DB']==""?app_conf('DB_NAME'):$GLOBALS['distribution_cfg']['SESSION_DB'];
                $this->db =new mysql_db($session_client.":".$session_port, $session_username,$session_password,$session_db,'utf8',$pconnect);
                $this->table =$GLOBALS['distribution_cfg']['SESSION_TABLE']==""?DB_PREFIX."session":$GLOBALS['distribution_cfg']['SESSION_TABLE'];
            }
            else 
            {
                if (!is_dir($this->savePath))
                {
                    @mkdir($this->savePath, 0777);
                }
            }
            return true;
        }
        function close()
        {
            return true;
        }
        function read($id)
        {
            $sess_id ="sess_".$id;
            if($GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL")
            {
                return $this->mem->get("$this->savePath/$sess_id");
            }
            elseif($GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
            {
                $session_data =$this->db->getRow("select session_data,session_time from ".$this->table." where session_id = '".$sess_id."'",true);
                if($session_data['session_time']<NOW_TIME)
                {
                    return false;
                }
                else 
                {
                    return $session_data['session_data'];
                }
            }
            else 
            {
                $file ="$this->savePath/$sess_id";
                if (filemtime($file)+ SESSION_TIME < time()&& file_exists($file))
                {
                    @unlink($file);
                }
                $data =(string)@file_get_contents($file);
                return $data;
            }
        }
        function write($id, $data)
        {
            $sess_id ="sess_".$id;
            if($GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL")
            {
                return $this->mem->set("$this->savePath/$sess_id",$data,SESSION_TIME);
            }
            elseif($GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
            {
                $session_data =$this->db->getRow("select session_data,session_time from ".$this->table." where session_id = '".$sess_id."'",true);
                if($session_data)
                {
                    $session_data['session_data'] =$data;
                    $session_data['session_time'] =NOW_TIME+SESSION_TIME;
                    $this->db->autoExecute($this->table, $session_data,"UPDATE","session_id = '".$sess_id."'");
                }
                else 
                {
                    $session_data['session_id'] =$sess_id;
                    $session_data['session_data'] =$data;
                    $session_data['session_time'] =NOW_TIME+SESSION_TIME;
                    $this->db->autoExecute($this->table, $session_data);
                }
                return true;
            }
            else 
            {
                return file_put_contents("$this->savePath/$sess_id", $data)=== false ? false : true;
            }
        }
        function destroy($id)
        {
            $sess_id ="sess_".$id;
            if($GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL")
            {
                $this->mem->delete($sess_id);
            }
            elseif($GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
            {
                $this->db->query("delete from ".$this->table." where session_id = '".$sess_id."'");
            }
            else 
            {
                $file ="$this->savePath/$sess_id";
                if (file_exists($file))
                {
                    @unlink($file);
                }
            }
            return true;
        }
        function gc($maxlifetime)
        {
            if($GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL")
            {
            }
            elseif($GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
            {
                $this->db->query("delete from ".$this->table." where session_time < ".NOW_TIME);
            }
            else 
            {
                foreach (glob("$this->savePath/sess_*")as $file)
                {
                    if (filemtime($file)+ SESSION_TIME < time()&& file_exists($file))
                    {
                        unlink($file);
                    }
                }
            }
            return true;
        }
    }
}
if(!function_exists('es_session_start'))
{
    function es_session_start($session_id)
    {
        session_set_cookie_params(0,$GLOBALS['distribution_cfg']['COOKIE_PATH'],$GLOBALS['distribution_cfg']['DOMAIN_ROOT'],false,true);
        if($GLOBALS['distribution_cfg']['SESSION_FILE_PATH']!=""||$GLOBALS['distribution_cfg']['SESSION_TYPE']=="MemcacheSASL"||$GLOBALS['distribution_cfg']['SESSION_TYPE']=="Db")
        {
            $handler =new FanweSessionHandler();
            session_set_save_handler(array($handler, 'open'), array($handler, 'close'), array($handler, 'read'), array($handler, 'write'), array($handler, 'destroy'), array($handler, 'gc'));
        }
        if($session_id)session_id($session_id);
        @session_start();
    }
}
function get_distance($lng1, $lat1, $lng2, $lat2)
{
    $pi80 =PI / 180;
    $lat1 *= $pi80;
    $lng1 *= $pi80;
    $lat2 *= $pi80;
    $lng2 *= $pi80;
    $dlat =$lat2 - $lat1;
    $dlng =$lng2 - $lng1;
    $a =sin($dlat/2)*sin($dlat/2)+cos($lat1)*cos($lat2)*sin($dlng/2)*sin($dlng/2);
    $c =2 * atan2(sqrt($a), sqrt(1 - $a));
    $m =EARTH_R * $c;
    return $m;
}
function show_adv($group,$content="",$width=0,$height=0)
{
    $page_module =APP_INDEX."|".MODULE_NAME."#".ACTION_NAME;
    $city_id =intval($GLOBALS['city']['id']);
    static $adv_data;
    if(!$adv_data)$adv_data =load_auto_cache("adv",array("page_module"=>$page_module,"city_id"=>$city_id));
    $html ="";
    if($content)
    {
        while(count($adv_data[$group])>0)
        {
            $adv_row =array_shift($adv_data[$group]);
            if($adv_row['u_module']=="")$url =$adv_row['url'];
            else 
            {
                $route =$adv_row['u_module'];
                if($adv_row['u_action']!='')$route.="#".$adv_row['u_action'];
                $app_index =$adv_row['app_index'];
                $str ="u:".$app_index."|".$route."|".$adv_row['u_param'];
                $url =parse_url_tag($str);
            }
            if($width>0&&$height>0)
            {
                $size_attr ="width='".$width."' height='".$height."'";
                $image_src =get_spec_image($adv_row['image'],$width,$height,1);
            }
            else 
            {
                $size_attr ="";
                $image_src =$adv_row['image'];
            }
            if($url)$adv_code ="<a href='".$url."' target='_blank'><img src='".$image_src."' $size_attr /></a>";
            else $adv_code ="<img src='".$image_src."' $size_attr />";
            $html.=str_replace("__ADV_CODE__", $adv_code, $content);
        }
    }
    else 
    {
        $adv_row =array_shift($adv_data[$group]);
        if($adv_row)
        {
            if($adv_row['u_module']=="")$url =$adv_row['url'];
            else 
            {
                $route =$adv_row['u_module'];
                if($adv_row['u_action']!='')$route.="#".$adv_row['u_action'];
                $app_index =$adv_row['app_index'];
                $str ="u:".$app_index."|".$route."|".$adv_row['u_param'];
                $url =parse_url_tag($str);
            }
            if($width>0&&$height>0)
            {
                $size_attr ="width='".$width."' height='".$height."'";
                $image_src =get_spec_image($adv_row['image'],$width,$height,1);
            }
            else 
            {
                $size_attr ="";
                $image_src =$adv_row['image'];
            }
            if($url)$html ="<a href='".$url."' target='_blank'><img src='".$image_src."' $size_attr /></a>";
            else $html ="<img src='".$image_src."' $size_attr />";
        }
    }
    return $html;
}
function load_mobile_biz_nav()
{
    $nav_list =require APP_ROOT_PATH."system/biz_cfg/".APP_TYPE."/m_biznav_cfg.php";
    if($GLOBALS['account_info']['is_main']||$GLOBALS['account_info']['is_staff']==1)
    {
        foreach($nav_list as $k=>$v)
        {
            $module_name =$k;
            foreach($v['node'] as $kk=>$vv)
            {
                $module_name =$vv['module'];
                $action_name =$vv['action'];
                $nav_list[$k]['node'][$kk]['url'] =SITE_DOMAIN.wap_url("biz",$module_name."#".$action_name);
                $nav_list[$k]['node'][$kk]['is_auth'] =intval($vv['is_auth']);
            }
            $nav_list[$k]['m_name'] =strtolower($k);
        }
    }
    else
    {
        $result =$GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."supplier_account_auth WHERE supplier_account_id=".$GLOBALS['account_info']['id']);
        if(empty($result))
        {
            return false;
        }
        foreach($result as $k=>$v)
        {
            $has_module[] =$v['module'];
        }
        $has_module =array_unique($has_module);
        foreach($nav_list as $k=>$v)
        {
            $note_count =0;
            $module_name =$k;
            foreach($v['node'] as $kk=>$vv)
            {
                if(in_array($kk, $has_module)||$vv['is_auth']==0)
                {
                    $module_name =$vv['module'];
                    $action_name =$vv['action'];
                    $nav_list[$k]['node'][$kk]['is_auth'] =intval($vv['is_auth']);
                    $nav_list[$k]['node'][$kk]['url'] =SITE_DOMAIN.wap_url("biz",$module_name."#".$action_name);
                    $note_count++;
                }
                else
                {
                    unset($nav_list[$k]['node'][$kk]);
                }
            }
            $nav_list[$k]['m_name'] =strtolower($k);
            if($note_count == 0)
            {
                unset($nav_list[$k]);
            }
        }
    }
    if($GLOBALS['account_info']['platform_status']==0)
    {
        unset($nav_list['Wx']);
    }
    foreach($nav_list as $k=>$group)
    {
        foreach($group['node'] as $kk=>$node)
        {
            if(!in_array($GLOBALS['account_info']['service_type'], $node['service_type'])&&$node['is_auth']==1)
            {
                unset($nav_list[$k]['node'][$kk]);
            }
            if($GLOBALS['account_info']['is_staff']==1&&$node['staff']!=1&&$node['is_auth']==1)
            {
                unset($nav_list[$k]['node'][$kk]);
            }
        }
        if(count($nav_list[$k]['node'])==0)
        {
            unset($nav_list[$k]);
        }
        if(count($nav_list[$k]['node'])== 1)
        {
            foreach($nav_list[$k]['node'] as $kkk=>$vvv)
            {
                $g_module_name =$vvv['module'];
                $g_action_name =$vvv['action'];
            }
            $nav_list[$k]['url'] =wap_url("biz",$g_module_name."#".$g_action_name);
        }
    }
    return $nav_list;
}
function load_biz_nav()
{
    $nav_list =require APP_ROOT_PATH."system/biz_cfg/".APP_TYPE."/biznav_cfg.php";
    if($GLOBALS['account_info']['is_main']||$GLOBALS['account_info']['is_staff']==1)
    {
        foreach($nav_list as $k=>$v)
        {
            $module_name =$k;
            foreach($v['node'] as $kk=>$vv)
            {
                $module_name =$vv['module'];
                $action_name =$vv['action'];
                $nav_list[$k]['node'][$kk]['url'] =url("biz",$module_name."#".$action_name);
            }
        }
    }
    else
    {
        $result =$GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."supplier_account_auth WHERE supplier_account_id=".$GLOBALS['account_info']['id']);
        if(empty($result))
        {
            return false;
        }
        foreach($result as $k=>$v)
        {
            $has_module[] =$v['module'];
        }
        $has_module =array_unique($has_module);
        foreach($nav_list as $k=>$v)
        {
            $note_count =0;
            $module_name =$k;
            foreach($v['node'] as $kk=>$vv)
            {
                if(in_array($kk, $has_module))
                {
                    $module_name =$vv['module'];
                    $action_name =$vv['action'];
                    $nav_list[$k]['node'][$kk]['url'] =url("biz",$module_name."#".$action_name);
                    $note_count++;
                }
                else
                {
                    unset($nav_list[$k]['node'][$kk]);
                }
            }
            if($note_count == 0)
            {
                unset($nav_list[$k]);
            }
        }
    }
    if($GLOBALS['account_info']['platform_status']==0)
    {
        unset($nav_list['Wx']);
    }
    foreach($nav_list as $k=>$group)
    {
        foreach($group['node'] as $kk=>$node)
        {
            if(!in_array($GLOBALS['account_info']['service_type'], $node['service_type']))
            {
                unset($nav_list[$k]['node'][$kk]);
            }
            if($GLOBALS['account_info']['is_staff']==1&&$node['staff']!=1)
            {
                unset($nav_list[$k]['node'][$kk]);
            }
        }
        if(count($nav_list[$k]['node'])==0)
        {
            unset($nav_list[$k]);
        }
    }
    return $nav_list;
}
function request_api($ctl,$act="index",$request_param=array())
{
    $api_url =$GLOBALS['wap_config']['API_URL'];
    if(empty($api_url))
    {
        $api_url =SITE_DOMAIN.APP_ROOT."/mapi/index.php";
    }
    $request_param['ctl']=$ctl;
    $request_param['act']=$act;
    $request_param['from']='wap';
    $request_param['sess_id'] =$GLOBALS['sess_id'];
    $request_param['email'] =$GLOBALS['cookie_uname'];
    $request_param['pwd'] =$GLOBALS['cookie_upwd'];
    $request_param['biz_uname'] =$GLOBALS['cookie_biz_uname'];
    $request_param['biz_upwd'] =$GLOBALS['cookie_biz_upwd'];
    $request_param['client_ip'] =CLIENT_IP;
    $request_param['image_zoom'] =2;
    $request_param['ref_uid'] =$GLOBALS['ref_uid'];
    $request_param['spid'] =$GLOBALS['supplier_info']['id'];
    $request_param['city_id'] =$GLOBALS['city']['id'];
    $request_param['m_longitude'] =$GLOBALS['geo']['xpoint'];
    $request_param['m_latitude'] =$GLOBALS['geo']['ypoint'];
    filter_request($request_param);
    require_once APP_ROOT_PATH.'/system/libs/crypt_aes.php';
    $aes =new CryptAES();
    $aes->set_key(FANWE_AES_KEY);
    $aes->require_pkcs5();
    $json =json_encode($request_param);
    $encText =$aes->encrypt($json);
    $param=array();
    $param['r_type']=4;
    $param['i_type']=4;
    $param['requestData']=$encText;
    $param['client_ip'] =CLIENT_IP;
    es_session::write();
    $request_data =$GLOBALS['transport']->request($api_url,$param);
    $data=$request_data['body'];
    if ($param['r_type'] == 4)
    {
        $data =$aes->decrypt($data);
        $data=json_decode($data,1);
    }
    else
    {
        $data=json_decode(base64_decode($data),1);
    }
    return $data;
}
function send_schedule_plan($type,$name,$schedule_data,$schedule_time,$dest="")
{
    $data['type'] =$type;
    $data['name'] =$name;
    if($dest)$data['dest'] =$dest;
    $data['data'] =serialize($schedule_data);
    if($schedule_time>0)$schedule_exec_time =$schedule_time;
    else $schedule_exec_time =NOW_TIME;
    $data['schedule_date'] =to_date($schedule_exec_time,"Y-m-d");
    $data['schedule_time'] =$schedule_exec_time;
    $GLOBALS['db']->autoExecute(DB_PREFIX."schedule_list",$data);
    $data['id'] =$GLOBALS['db']->insert_id();
    if($schedule_time==0&&$data['id'])
    {
        exec_schedule_plan($data);
    }
}
function exec_schedule_plan($schedule_data)
{
    $type =$schedule_data['type'];
    $cname =$type."_schedule";
    require_once APP_ROOT_PATH.'system/schedule/'.$cname.".php";
    $c =new $cname;
    $item_data =unserialize($schedule_data['data']);
    $result =$c->exec($item_data);
    if($schedule_data['exec_status']==0)$schedule_data['exec_begin_time'] =NOW_TIME;
    if($result['info'])
    {
        $schedule_data['exec_info'] =$result['info'];
    }
    else 
    {
        unset($schedule_data['exec_info']);
    }
    if($result['attemp'])
    {
        $schedule_data['exec_status'] =1;
    }
    else 
    {
        $schedule_data['exec_status'] =2;
        $schedule_data['exec_end_time'] =NOW_TIME;
    }
    $GLOBALS['db']->autoExecute(DB_PREFIX."schedule_list",$schedule_data,"UPDATE","id='".$schedule_data['id']."'","SILENT");
    return $result;
}
?>