<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class WeixinConfAction extends CommonAction{
	public function index()
	{
		$config = M("WeixinConf")->where("is_conf=1")->order("sort asc")->findAll();
		foreach($config as $k=>$v){
			
 			if($v['name']=="platform_token"&&$v['value']=="")
 			{
 				$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 				
 				$token = substr($str,rand(0,strlen($str)-1),1).substr($str,rand(0,strlen($str)-1),1).substr($str,rand(0,strlen($str)-1),1).substr($str,rand(0,strlen($str)-1),1).substr($str,rand(0,strlen($str)-1),1);
 				M("WeixinConf")->where("name='".$v['name']."'")->setField("value",$token);
 				rm_auto_cache("weixin_conf");
 			}
 			
 			if($v['name']=="platform_encodingAesKey"&&$v['value']=="")
 			{
 				$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
 				for($i=0;$i<43;$i++)
 				{
 					$key .= substr($str,rand(0,strlen($str)-1),1);
 				}
 				M("WeixinConf")->where("name='".$v['name']."'")->setField("value",$key);
 				rm_auto_cache("weixin_conf");
 			}
 				
		}
		$config = M("WeixinConf")->where("is_conf=1")->order("sort asc")->findAll();
		foreach($config as $k=>$v){
			if($v['type']==4){
				$config[$k]['value_scope']=explode(',',$v['value_scope']);
			}else{
				$config[$k]['value_scope']='';
			}	
 		
		}
		
		$this->assign("testurl",SITE_DOMAIN.APP_ROOT."/wx.php?a=valid_url");
		$this->assign("authurl",SITE_DOMAIN.APP_ROOT."/wx.php?a=accept");
		$this->assign("gzurl",SITE_DOMAIN.APP_ROOT."/wx.php?a=gz_accept&appid=");
		$this->assign("config",$config);
		$this->display();
	}
	
	public function update()
	{		 
		foreach($_POST as $k=>$v)
		{
			M("WeixinConf")->where("name='".$k."'")->setField("value",$v);
		}
		rm_auto_cache("weixin_conf");
		$this->success("保存成功");
	}
 }
?>