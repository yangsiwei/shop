<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
require APP_ROOT_PATH."system/wechat/wechat.class.php";
class WeixinAccountAction extends WeixinSingleAction{
	public function index()
	{
		if(WEIXIN_TYPE=="platform")
			$config = M("WeixinAccountConf")->where("name='mappid' or name='mappsecret'")->order("sort asc")->findAll();
		else
			$config = M("WeixinAccountConf")->where("is_conf=1 and is_effect=1")->order("sort asc")->findAll();

		$this->assign("WEIXIN_TYPE",WEIXIN_TYPE);
		$this->assign("config",$config);
		$this->display();
	}

	public  function wx_templ(){

		$this->assign("config",true);
		$weixin_conf = $this->option;
		$this->assign("weixin_conf",$weixin_conf);
		$platform= new Wechat($this->option);
		$platform_authorizer_token=$platform->checkAuth();
		if($platform_authorizer_token){

			$return_url=SITE_DOMAIN.APP_ROOT."/wx.php?a=platform_get_auth_code&type=1";
			$return_url=urlencode($return_url);
			$sq_url='https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$weixin_conf['appid'].'&pre_auth_code='.$platform_pre_auth_code.'&redirect_uri='.$return_url;
			$this->assign("sq_url",$sq_url);
		}else{
			$error="请填写正确的微信账号信息";

		}
		if($error)
		{
			$this->assign("jumpUrl",u("WeixinConf/index"));
			$this->error($error);
		}



		$verify_type_array=array(-1=>'未认证',0=>'微信认证',1=>'新浪微博认证',2=>'腾讯微博认证',3=>'已资质认证通过但还未通过名称认证',4=>'已资质认证通过、还未通过名称认证，但通过了新浪微博认证',5=>'已资质认证通过、还未通过名称认证，但通过了腾讯微博认证');
		$service_type_array=array(0=>'订阅号',1=>'由历史老帐号升级后的订阅号',2=>'服务号');
		$this->assign("verify_type",$verify_type_array[$this->account['verify_type_info']]);
		$this->assign("service_type",$service_type_array[$this->account['service_type_info']]);

		$industry_list = require_once APP_ROOT_PATH."system/wechat/wx_industry_cfg.php";
		$this->assign("industry_list",$industry_list);
		$this->assign("syn_industry_url",U("WeixinAccount/syn_industry"));


		$template_list = require_once APP_ROOT_PATH."system/wechat/wx_template_cfg.php";
		foreach($template_list as $k=>$v)
		{
			$template_list[$k]['data'] = M("WeixinTmpl")->where("account_id=0 and template_id_short='".$k."'")->find();
		}
		$this->assign("template_list",$template_list);
		$this->assign("syn_template_url",U("WeixinAccount/syn_template"));
		$this->assign("del_template_url",U("WeixinAccount/del_template"));
		$this->assign("send_test_template_url",U("WeixinAccount/send_test_template"));

		$this->display();
	}
	public function update()
	{
		foreach($_POST as $k=>$v)
		{
			M("WeixinAccountConf")->where("name='".$k."'")->setField("value",strim($v));
			if($k=="appid"){
			    M("conf")->where("name='WEIXIN_APPID'")->setField("value",strim($v));
			}elseif ($k=='appsecret'){
			    M("conf")->where("name='WEIXIN_APPSCECRET'")->setField("value",strim($v));
			}
		}
		
		rm_auto_cache("weixin_conf");
		 $sys_configs = M("Conf")->findAll();
        $config_str = "<?php\n";
        $config_str .= "return array(\n";
        foreach($sys_configs as $k=>$v)
        {
            $config_str.="'".$v['name']."'=>'".addslashes($v['value'])."',\n";
        }
        $config_str.=");\n ?>";
        $filename = get_real_path()."public/sys_config.php";
        
    	file_put_contents($filename, $config_str);
		$this->success("保存成功");
	}


	public function nav_setting(){
		$account = $this->account;

		$main_navs=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_nav where account_id=0 and pid = 0 ");

		foreach($main_navs as $k=>$v){
			$temp = unserialize($v['data']);
			$v['data'] = $temp[$this->navs[$v['ctl']]['field']];
			$v['data_name'] = $this->navs[$v['ctl']]['fname'];
			$result_navs[] = $v;

			$sub_navs = M("WeixinNav")->where(array('account_id'=>0,'pid'=>$v['id']))->order('sort asc')->findAll();
			foreach($sub_navs as $kk=>$vv){
				$temp = unserialize($vv['data']);
				$vv['data'] = $temp[$this->navs[$vv['ctl']]['field']];
				$vv['data_name'] = $this->navs[$vv['ctl']]['fname'];
				$result_navs[] = $vv;
			}

		}

		$this->assign("result_navs",$result_navs);

		$this->assign("navs",$this->navs);
		$this->assign("navs_json",json_encode($this->navs));
		$this->display();
	}

	public function nav_save(){
		$ids = $_POST['id'];
		if(count($ids) == 0){
			$GLOBALS['db']->query("delete from ".DB_PREFIX."weixin_nav where account_id=0");
			$this->success("保存成功",$this->isajax);
		}

		//先验证
		$main_count = 0;
		$sub_count = array();
		foreach($_POST['row_type'] as $k=>$v){
			if($v=="main"){
				$main_count++;
				foreach($_POST['pid'] as $kk=>$pid){
					if(intval($pid)>0&&intval($pid)==intval($_POST['id'][$k])){
						$sub_count[$pid] = intval($sub_count[$pid])+1;
					}
				}
			}
		}

		if($main_count>3){
			$this->error("主菜单个数不能超过三个",$this->isajax);
			//$this->showFrmErr("主菜单个数不能超过三个",$this->isajax);
		}
		foreach ($sub_count as $sub_c)
		{
			if(intval($sub_c)>5){
				$this->error("子菜单个数不能超过五个",$this->isajax);
			}
		}

		$saved_ids = array();
		//var_dump($_REQUEST);exit;
		foreach($ids as $k=>$id){
			$id = intval($id);
			if($id>0){
				//更新
				$nav_data['name'] = trim($_REQUEST['name'][$k]);
				$nav_data['sort'] = intval($_REQUEST['sort'][$k]);
				$nav_data['pid'] = intval($_REQUEST['pid'][$k]);
				$nav_data['ctl'] = strim($_REQUEST['ctl'][$k]);

				$data = strim($_REQUEST['data'][$k]);
				$field = $this->navs[$nav_data['ctl']]['field'];
				if($field)
				{
					$nav_data['data'] = serialize(array($field=>$data));
				}

				$nav_data['status'] = 0;

				$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_nav",$nav_data,'update',"id=".$id);
				//$this->sdb->table('weixin_nav')->where(array('id'=>$id,'seller_id'=>$this->seller_id))->silent()->update($nav_data);
				array_push($saved_ids, $id);
			}else{
				//新增
				$nav_data['name'] = trim($_REQUEST['name'][$k]);
				$nav_data['sort'] = intval($_REQUEST['sort'][$k]);
				$nav_data['pid'] = intval($_REQUEST['pid'][$k]);
				$nav_data['ctl'] = strim($_REQUEST['ctl'][$k]);

				$data = strim($_REQUEST['data'][$k]);
				$field = $this->navs[$nav_data['ctl']]['field'];
				if($field)
				{
					$nav_data['data'] = serialize(array($field=>$data));
				}

				$nav_data['status'] = 0;
				//$nid = $this->sdb->table('weixin_nav')->silent()->insert($nav_data);
				$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_nav",$nav_data);
				$nid = $GLOBALS['db']->insert_id();
				array_push($saved_ids,intval($nid));
			}
		}

		//$del_items = $this->sdb->table('weixin_nav')->where(array('seller_id'=>$this->seller_id,'id'=>array('not in',$saved_ids)))->getAll();
		$condition['account_id'] = 0;
		$condition['id'] = array('not in',$saved_ids);
		$del_items = M("WeixinNav")->where($condition)->findAll();
		//$del_items = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_nav where account_id".$this->account_id." and id not in ");
		foreach($del_items as $it){
			M("WeixinNav")->where(array('pid'=>$it['id']))->delete();
		}
		//$this->sdb->table('weixin_nav')->where(array('seller_id'=>$this->seller_id,'id'=>array('not in',$saved_ids)))->delete();
		M("WeixinNav")->where(array('account_id'=>0,'id'=>array('not in',$saved_ids)))->delete();
		$this->success("保存成功",$this->isajax);
	}


	public function new_nav_row(){
		$row_type= strim($_REQUEST['row_type']) == "main" ? "main" : "sub";
		if($row_type=="sub"){
			$pid = intval($_REQUEST['id']);
			$item['pid'] = $pid;

			$this->assign("item",$item);
		}
		$this->assign("row_type",$row_type);
		$this->assign("navs",$this->navs);
		echo $this->fetch("new_nav_row");
	}

	public function syn_to_weixin(){
		//开始获取微信的token

		$platform= new Wechat($this->option);
		$platform_authorizer_token=$platform->checkAuth();
		if($platform_authorizer_token)
		{
			//开始读取菜单配置
			$navs =$GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_nav where account_id=0 and pid=0 order by sort asc");
			foreach($navs as $k=>$v){
				$data = unserialize($v['data']);
				if($v['ctl']=="url")
					$navs[$k]['url'] = $data['url'];
				else
					$navs[$k]['url'] = SITE_DOMAIN.wap_url("index",$v['ctl'],$data);

				$sub_navs = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."weixin_nav where account_id=0 and pid=".$v['id']." order by sort asc");
				foreach($sub_navs as $kk=>$vv)
				{
					$data = unserialize($vv['data']);
					if($vv['ctl']=="url")
						$sub_navs[$kk]['url'] = $data['url'];
					else
						$sub_navs[$kk]['url'] = SITE_DOMAIN.wap_url("index",$vv['ctl'],$data);

				}
				$navs[$k]['sub_button'] = $sub_navs;
			}
			$button_data = array();
			foreach($navs as $k=>$v){
				$button_data[$k]['name'] = $v['name'];
				if(count($v['sub_button'])==0){

					if(strtolower(substr($v['url'], 0,7))=="http://"||strtolower(substr($v['url'], 0,8))=="https://"){
						$button_data[$k]['type'] = "view";
						$button_data[$k]['url'] = $v['url'];

					}else{
						$button_data[$k]['type'] = "click";
						$button_data[$k]['key'] = $v['url'];
					}

				}else{
					$sub_button_data = array();
					foreach($v['sub_button'] as $kk=>$vv){
						$sub_button_data[$kk]['name'] = $vv['name'];

						if(strtolower(substr($vv['url'], 0,7))=="http://"||strtolower(substr($vv['url'], 0,8))=="https://"){
							$sub_button_data[$kk]['type'] = "view";
							$sub_button_data[$kk]['url'] = $vv['url'];
						}else{
							$sub_button_data[$kk]['type'] = "click";
							$sub_button_data[$kk]['key'] = $vv['url'];
						}
					}
					$button_data[$k]['sub_button'] = $sub_button_data;
				}
			}

			$json_data['button'] = $button_data;
			
			$result=$platform->createMenu($json_data);

			if($result){
				if(!isset($result['errcode']) || intval($result['errcode'])==0){
					$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_nav",array('status'=>1),'UPDATE',"account_id=0");
					//$this->sdb->table('weixin_nav')->where(array('seller_id'=>$this->seller_id))->setField('status',1);
					$this->success("同步成功",$this->isajax);
				}else{
					$this->error("同步出错，错误代码".$result['errcode'].":".$result['errmsg'],$this->isajax);
				}
			}else{
				$this->error($platform->errMsg);
			}
		}else{
			$this->error("通讯出错，请重试");
		}
	}


	public function syn_industry()
	{
		$industry_list = require_once APP_ROOT_PATH."system/wechat/wx_industry_cfg.php";
		$k=1;
		foreach($industry_list as $key => $v)
		{
			M("WeixinAccountConf")->where(" name = 'industry_".$k."' ")->setField("value",$key);
			$this->account['industry_'.$k] = $key;
			$k++;
		}

		//开始获取微信的token
		$industry_1 = intval($this->account['industry_1']);
		$industry_2 = intval($this->account['industry_2']);

 		$platform= new Wechat($this->option);
		$platform_authorizer_token=$platform->checkAuth();
 		if($platform_authorizer_token){
			$result=$platform->setTMIndustry($industry_1,$industry_2);

 			if($result){
				if(!isset($result['errcode']) || intval($result['errcode'])==0){
					//$this->sdb->table('weixin_nav')->where(array('seller_id'=>$this->seller_id))->setField('status',1);
					//$data=array('industry_1_status'=>1,'industry_2_status'=>1);
					M("WeixinAccountConf")->where(" name = 'industry_1_status' ")->setField("value",1);
					M("WeixinAccountConf")->where(" name = 'industry_2_status' ")->setField("value",1);
					//$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_account",$data,'UPDATE',' id='.$this->account['id']);
					$this->success("同步成功",$this->isajax);
				}else{
					if($result['errcode']==43100){
						$this->error("同步频率太高,一个月只可修改一次",$this->isajax);
					}else{
						$this->error("同步出错，错误代码".$result['errcode'].":".$result['errmsg'],$this->isajax);
					}
				}
			}else{
				$this->error($platform->errCode.",信息：".$platform->errMsg,1);
			}
		}else{
			$this->error("通讯出错，请重试",1);
		}
	}


	public function syn_template()
	{
		$template_list = require_once APP_ROOT_PATH."system/wechat/wx_template_cfg.php";
		$success_count = 0;
		foreach($template_list as $k=>$v)
		{
			$name = strim($v['name']);
			$template_id_short = strim($k);

			$row = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_tmpl where template_id_short='".$template_id_short."' and account_id=0");
			if(!$row)
			{
				$platform= new Wechat($this->option);
 				$result=$platform->addTemplateMessage($template_id_short);
				//var_dump($platform->errCode.':'.$platform->errMsg);exit;
				if($result)
				{
					if(intval($result['errcode'])==0)
					{
						$data = array('first'=>$name,'remark'=>array('value'=>$v['remark'],'color'=>'#173177'));
						$msg = serialize($data);
						$GLOBALS['db']->autoExecute(DB_PREFIX."weixin_tmpl",array('name'=>$name,'template_id'=>$result,'template_id_short'=>$template_id_short,'account_id'=>0,'msg'=>$msg));
						if(!$GLOBALS['db']->error())
						{
							$success_count++;
						}
						else
						{
							$err[$template_id_short] = "DB:".$GLOBALS['db']->error();
						}
					}
					else
					{
						$err[$template_id_short] = $result['errmsg'];
					}
				}
			}
			else
			{
				$success_count++;
			}//end install
		}//end foreach

		if($success_count==count($template_list))
		{
			$this->success("同步成功");
		}
		else
		{
			$msg = "";
			foreach($err as $kk=>$vv)
			{
				$msg.="模板".$kk."同步失败：".$vv."<br />";
			}
			$this->error($msg);
		}
	}


	public function del_template()
	{
		M("WeixinTmpl")->where("account_id=0")->delete();
		$this->success("删除成功");
	}

	public function send_test_template()
	{
		$wx_user = strim($_REQUEST['weixin_user']);
		$template_id_short = trim($_REQUEST['template_id_short']);
		$user_id = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where user_name = '".$wx_user."'");
		$wx_account = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."weixin_account where type = 1 and user_id = 0");
		$rs = send_wx_msg($template_id_short,$user_id, $wx_account);
		$this->success("发送成功",1);
	}
}
?>