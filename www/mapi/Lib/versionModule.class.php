<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class versionApiModule extends MainBaseApiModule
{
	
	/**
	 * 检测版本自动升级接口
	 * 输入
	 * dev_type: string 设备类型 android/ios
	 * version:float app当前版本号
	 * 
	 * 输出
	 * ==android==
	 * serverVersion: float 新的app版本号
	 * filename:string apk的下载路径
	 * android_upgrad:string 升级的内容
	 * hasfile: int 是否有下载文件 0:无 1:有
	 * forced_upgrade: int 是否强制升级 0否 1是
	 * 
	 * 
	 * ==ios==
	 * serverVersion: float 新的app版本号
	 * ios_down_url:string ios升级的页面地址
	 * ios_upgrade: string ios升级内容
	 * has_upgrade: int 是否可升级0否 1是
	 * forced_upgrade: int 是否强制升级 0否 1是
	 * 
	 */
	public function index()
	{

		$site_url = SITE_DOMAIN.APP_ROOT;//站点域名;
	
		//客服端手机类型dev_type=android
		$dev_type = $GLOBALS['request']['dev_type'];
		$version = $GLOBALS['request']['version'];
		
		$root = array();
		if ($dev_type == 'android'){
			$root['serverVersion'] = $GLOBALS['m_config']['android_version'];//android版本号
			if ($version < $root['serverVersion']){
				$root['filename'] = $site_url.$GLOBALS['m_config']['android_filename'];//android下载包名
				$root['android_upgrade'] = $GLOBALS['m_config']['android_upgrade'];//android版本升级内容
				
				if (substr($GLOBALS['m_config']['android_filename'], 0,7)=='http://' || substr($GLOBALS['m_config']['android_filename'], 0,3)=='www')
				{
					if (substr($GLOBALS['m_config']['android_filename'], 0,3)=='www'){
						$root['filename'] = 'http://'.$GLOBALS['m_config']['android_filename'];
					}else{
						$root['filename'] = $GLOBALS['m_config']['android_filename'];
					}					
					$root['hasfile'] = 1;
					$root['forced_upgrade'] = intval($GLOBALS['m_config']['android_forced_upgrade']);//0:非强制升级;1:强制升级
				}else{
					if(file_exists(APP_ROOT_PATH.$GLOBALS['m_config']['android_filename']))
					{
						$root['hasfile'] = 1;
						$root['forced_upgrade'] = intval($GLOBALS['m_config']['android_forced_upgrade']);//0:非强制升级;1:强制升级
					}
					else
					{
						$root['hasfile'] = 0;
						$root['forced_upgrade'] = 0;
					}
				}				
			}else{
				$root['hasfile'] = 0;
				$root['has_upgrade'] = 0;//1:可升级;0:不可升级
			}
		}else if ($dev_type == 'ios'){
			$root['serverVersion'] = $GLOBALS['m_config']['ios_version'];//ios版本号
				
			if ($version < $root['serverVersion']){
				$root['ios_down_url'] = $GLOBALS['m_config']['ios_down_url'];//ios下载地址
				$root['ios_upgrade'] = $GLOBALS['m_config']['ios_upgrade'];//ios版本升级内容
				$root['has_upgrade'] = 1;//1:可升级;0:不可升级
				$root['forced_upgrade'] = intval($GLOBALS['m_config']['ios_forced_upgrade']);//0:非强制升级;1:强制升级
			}else{
				$root['has_upgrade'] = 0;//1:可升级;0:不可升级
				$root['forced_upgrade'] = 0;
			}				
		}
		
		return output($root);
	}
	
}
?>

