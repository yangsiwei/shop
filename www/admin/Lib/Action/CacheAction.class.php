<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class CacheAction extends CommonAction{

	public function index()
	{
		$this->assign("oss_type",$GLOBALS['distribution_cfg']['OSS_TYPE']);
		$this->display();
	}
	
	public function clear_data()
	{
		set_time_limit(0);
		es_session::close();

// 		$GLOBALS['db']->query("update ".DB_PREFIX."topic set is_cached = 0");
// 		$GLOBALS['db']->query("update ".DB_PREFIX."supplier_location set dp_group_point = '',tuan_youhui_cache = ''");
// 		$GLOBALS['db']->query("update ".DB_PREFIX."msg_box set data = ''");
		$GLOBALS['cache']->clear();
		
		clear_dir_file(APP_ROOT_PATH."public/runtime/admin/");
		clear_dir_file(APP_ROOT_PATH."public/runtime/app/");
		clear_dir_file(APP_ROOT_PATH."public/runtime/data/");
		clear_dir_file(APP_ROOT_PATH."public/runtime/wap/");
		clear_dir_file(APP_ROOT_PATH."public/runtime/mapi/");
		clear_dir_file(APP_ROOT_PATH."public/runtime/statics/");			
		
		
		
		
		header("Content-Type:text/html; charset=utf-8");
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
	}

	
	public function syn_data()
	{
		set_time_limit(0);
		es_session::close();
		
		$day = intval($_REQUEST['day']);

		delete_lotteryed_data($day);
		
		$this->assign("jumpUrl",U("Cache/index"));
			$ajax = intval($_REQUEST['ajax']);
			 
       		$data['status'] = 1;
       		$data['info'] = "<div style='line-height:50px; text-align:center; color:#f30;'>清除成功</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>";
			header("Content-Type:text/html; charset=utf-8");
            exit(json_encode($data));	
	}
	
	public function clear_image()
	{
		set_time_limit(0);
		es_session::close();
		$path  = APP_ROOT_PATH."public/attachment/";
		$this->clear_image_file($path);
		$path  = APP_ROOT_PATH."public/images/";
		$this->clear_image_file($path);		
		$path  = APP_ROOT_PATH."public/comment/";
		$this->clear_image_file($path);
		
		$qrcode_path = APP_ROOT_PATH."public/images/qrcode/";
		$this->clear_qrcode($qrcode_path);
	
		$GLOBALS['db']->query("update ".DB_PREFIX."topic set is_cached = 0");
		$GLOBALS['db']->query("update ".DB_PREFIX."supplier_location set dp_group_point = '',tuan_youhui_cache = ''");
		$GLOBALS['db']->query("update ".DB_PREFIX."msg_box set data = ''");
		$GLOBALS['cache']->clear();
		
		clear_dir_file(APP_ROOT_PATH."public/runtime/admin/");
		clear_dir_file(APP_ROOT_PATH."public/runtime/app/");
		clear_dir_file(APP_ROOT_PATH."public/runtime/data/");
		clear_dir_file(APP_ROOT_PATH."public/runtime/wap/");
		clear_dir_file(APP_ROOT_PATH."public/runtime/statics/");
		
		header("Content-Type:text/html; charset=utf-8");
       	exit("<div style='line-height:50px; text-align:center; color:#f30;'>".L('CLEAR_SUCCESS')."</div><div style='text-align:center;'><input type='button' onclick='$.weeboxs.close();' class='button' value='关闭' /></div>");
	}
	
	private function clear_qrcode($path)
	{
	
	   if ( $dir = opendir( $path ) )
	   {
	            while ( $file = readdir( $dir ) )
	            {
	                $check = is_dir( $path. $file );
	                if ( !$check )
	                {
	                    @unlink ( $path . $file);                       
	                }
	                else 
	                {
	                 	if($file!='.'&&$file!='..')
	                 	{
	                 		$this->clear_qrcode($path.$file."/");              			       		
	                 	} 
	                 }           
	            }
	            closedir( $dir );
	            return true;
	   }
	}
	
	private function clear_image_file($path)
	{
	   if ( $dir = opendir( $path ) )
	   {
	            while ( $file = readdir( $dir ) )
	            {
	                $check = is_dir( $path. $file );
	                if ( !$check )
	                {
	                	if(preg_match("/_(\d+)x(\d+)/i",$file,$matches))
	                    @unlink ( $path . $file);                       
	                }
	                else 
	                {
	                 	if($file!='.'&&$file!='..')
	                 	{
	                 		$this->clear_image_file($path.$file."/");              			       		
	                 	} 
	                 }           
	            }
	            closedir( $dir );
	            return true;
	   }
	}
}
?>