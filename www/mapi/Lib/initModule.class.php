<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class initApiModule extends MainBaseApiModule
{
	
	
	/**
	 * app端初始化接口
	 * 输入：
	 * device_type: [string] 设备类型 ios/android
	 * 
	 * 
	 * 输出：
	 * user: [array]
	 * 结构如下
	 *  Array
        (
                    [id] => 18 [int] 会员ID
                    [user_name] => [string] 会员名
                    [user_pwd] => [string] md5加密的密码
                    [email] => [string] 邮箱
                    [mobile]	=> [string] 手机号
                    [is_tmp]  => [int] 是否为临时会员 0:否 1:是
        )
  
	 * city_id: [int] 当前城市ID
	 * citylist: [array] 城市列表
	 * 结构如下
	 *  Array
        (
            [0] => Array
                (
                    [id] => 18 [int] 城市ID
                    [name] => 北京 [string] 城市名称
                    [py] => beijing [string] 城市拼音
                )
        )
       hot_city:array 热门城市
	 * Array(
	 * 		Array(
	 * 			[id] => int 城市ID
	 * 			[name] => string 城市名称
	 * 		)
	 * )
	 * region_version： [int] 当前配送地区的数据版本(如果大于客户端的版本号,则客户端在选择，配送地区时会提示升级)
	 * only_one_delivery: [int] 是否支持多个配送地址,已固定返回0，都会支持多个配送
	 * version: [float] 接口版本号
	 * page_size: [int] 分页大小
	 * program_title: [string] 系统的标题
	 * index_logo:[string] 首页logo
	 * kf_email: string 客服邮箱
	 * kf_phone: string 客服电话
	 * about_info: int 关于我们文章ID
	 * 
	 * 以下是新浪对象
	 * api_sina: [int] 是否支持新浪登录 0否 1是
	 * sina_app_key:[string] 新浪key
	 * sina_app_secret:[string] 新浪secret
	 * sina_bind_url:[string] 新浪回调地址
	 * 
	 * 以下是QQ登录
	 * api_qq: [int] 是否支持QQ登录 0否 1是
	 * qq_app_key:[string] QQkey
	 * qq_app_secret:[string] QQsecret
	 * 
	 * 以下是微信分享
	 * api_wx: [int] 是否支持微信分享
	 * wx_app_key: [string] 微信key
	 * wx_app_secret:[string] 微信secret
	 * 
	 * start_page:[array] 启动页的自定义
	 * 结构如下
	 * Array(
                Array(
                    [id] => 21 [int] 广告的ID
                    [type]	=>	[int] 广告类型
                    [name] => 商品明细 [string] 广告名称
                    [img] => http://localhost/o2onew/public/attachment/sjmapi/5451eb7862ae7.jpg [string] 广告图片 640x960
                    [data] => Array [array] 以key->value方式存储的内容 用于url参数组装
                        (
                            [url] => http://www 
                        )

                    [ctl] => url [string] 定义的ctl
                )
       )
	 * 
	 */
	public function index()
	{
		$device_type=strim($GLOBALS['request']['device_type']);//苹果端值是：ios  安卓端值是：android
		$cur_city_id = $GLOBALS['city']['id'];
		$city_name =  $GLOBALS['city']['name'];
		

		$root = array();

		$root['city_id'] = $cur_city_id;

		$city_list_rs = load_auto_cache("city_list_result");
		foreach($city_list_rs['ls'] as $k=>$v)
		{
			$city_item = array();
			$city_item['id'] = $v['id'];
			$city_item['name'] = $v['name'];
			$city_item['py'] = $v['uname'];
			$city_list[] = $city_item;
		}
		$root['citylist'] = $city_list?$city_list:array();
		
		$hot_city = $GLOBALS['db']->getAll("select id,name from ".DB_PREFIX."deal_city where pid>0 and is_effect = 1 and is_hot = 1 order by uname asc");
		$root['hot_city'] = $hot_city;

		$root['region_version'] = intval($GLOBALS['m_config']['region_version']);//当前配送地区的数据版本(如果大于客户端的版本号,则客户端在选择，配送地区时会提示升级),int 数字类型
		$root['only_one_delivery'] = 0;//1：会员只有一个配送地址；0：会员可以有多个配送地址


		$root['version'] = VERSION; //接口版本号int
		$root['page_size'] = PAGE_SIZE;//默认分页大小
		
		$root['program_title'] = $GLOBALS['m_config']['program_title'];


		$root['index_logo'] = get_abs_img_root($GLOBALS['m_config']['index_logo']);
			
		if(allow_show_api())
		{		
			//新浪  分享，登陆 功能
			if(strim($GLOBALS['m_config']['sina_app_key'])!=""&&strim($GLOBALS['m_config']['sina_app_secret'])!="")
			{
				$root['api_sina'] = 1; 
				$root['sina_app_key'] = $GLOBALS['m_config']['sina_app_key'];
				$root['sina_app_secret'] = $GLOBALS['m_config']['sina_app_secret'];
				$root['sina_bind_url'] = $GLOBALS['m_config']['sina_bind_url'];
			}			
			else
			{
				$root['api_sina'] = 0;
				$root['sina_app_key'] = "";
				$root['sina_app_secret'] = "";
				$root['sina_bind_url'] = "";
			}
			
			//QQ登陆
			if(strim($GLOBALS['m_config']['qq_app_key'])!=""&&strim($GLOBALS['m_config']['qq_app_secret'])!="")
			{
				$root['api_qq'] = 1;
				$root['qq_app_key'] = $GLOBALS['m_config']['qq_app_key'];
				$root['qq_app_secret'] = $GLOBALS['m_config']['qq_app_secret'];
			}
			else
			{
				$root['api_qq'] = 0;
				$root['qq_app_key'] = "";
				$root['qq_app_secret'] = "";
			}
			
			//微信分享功能
			if(strim($GLOBALS['m_config']['wx_app_key'])!=""&&strim($GLOBALS['m_config']['wx_app_secret'])!="")
			{
				$root['api_wx'] = 1;
				$root['wx_app_key'] = $GLOBALS['m_config']['wx_app_key'];
				$root['wx_app_secret'] = $GLOBALS['m_config']['wx_app_secret'];
			}		
		}

		$sql = " select * from ".DB_PREFIX."m_adv where mobile_type = '0' and  position=1 and status = 1 order by sort desc ";
		$advs = $GLOBALS['db']->getAll($sql);
	
	
		$adv_list = array();
		foreach($advs as $k=>$v)
		{
			$adv_list[$k]['id'] = $v['id'];
			$adv_list[$k]['name'] = $v['name'];
			$adv_list[$k]['img'] = get_abs_img_root($v['img']);  //首页广告图片规格为 宽: 640px 高: 960px
			$adv_list[$k]['type'] = $v['type'];
			$adv_list[$k]['data'] = $v['data'] = unserialize($v['data']);
			$adv_list[$k]['ctl'] = $v['ctl'];
		}		
				
		//$rk = rand(0,count($adv_list)-1);
		//$start_page_item = $adv_list[$rk];		
		$root['start_page'] = $adv_list?$adv_list:null;
		$root['start_page_new'] = $adv_list?$adv_list:null;
		
		//返回会员信息
		if($GLOBALS['user_info'])
		{
			$user_data['id'] = $GLOBALS['user_info']['id'];
			$user_data['user_name'] = $GLOBALS['user_info']['user_name'];
			$user_data['user_pwd'] = $GLOBALS['user_info']['user_pwd'];
			$user_data['email'] = $GLOBALS['user_info']['email'];
			$user_data['mobile'] = $GLOBALS['user_info']['mobile'];
			$user_data['is_tmp'] = $GLOBALS['user_info']['is_tmp'];
			$root['user'] = $user_data;
		}
		else
		{
			$root['user'] = null;
		}
		
		$root['about_info'] = intval($GLOBALS['m_config']['about_info']);
		$root['kf_phone'] = $GLOBALS['m_config']['kf_phone'];
		$root['kf_email'] = $GLOBALS['m_config']['kf_email'];

		if(defined("FX_LEVEL"))
		$root['is_fx'] = 1;
                
                //兼容版本菜单节点配置
                require_once APP_ROOT_PATH.'/system/mobile_cfg/main/app_note_auth.php';
                
                //获取商户权限
                if($GLOBALS['account_info']){
                    $biz_account_auth = get_biz_account_auth();
                    if(empty($biz_account_auth)){
                        $root['m_biz_nav_list'] = array();
                    }else{
                        $root['m_biz_nav_list'] = assign_biz_nav_list();
                    }
                }
                
                
		return output($root);
	}
	
}
?>