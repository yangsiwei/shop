<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class ConfAction extends CommonAction{
	public function index()
	{
		$conf_res = M("Conf")->where("is_effect = 1 and is_conf = 1")->order("group_id asc,sort asc")->findAll();
		foreach($conf_res as $k=>$v)
		{
			$v['value'] = htmlspecialchars($v['value']);
			if($v['name']=='TEMPLATE')
			{
				
				//输出现有模板文件夹
				$directory = APP_ROOT_PATH."wap/Tpl/".APP_TYPE."/";
				$dir = @opendir($directory);
			    $tmpls     = array();
			
			    while (false !== ($file = @readdir($dir)))
			    {
			    	if($file!='.'&&$file!='..'&&$file!="biz"&&substr($file, 0,5)=="tmpl_")
			        $tmpls[] = substr($file, 5);
			    }
			    @closedir($dir);
				//end
				
				$v['input_type'] = 1;
				$v['value_scope'] = $tmpls;
			}
			elseif($v['name']=='SHOP_LANG')
			{
				//输出现有语言包文件夹
				$directory = APP_ROOT_PATH."app/Lang/";
				$dir = @opendir($directory);
			    $tmpls     = array();
			
			    while (false !== ($file = @readdir($dir)))
			    {
			    	if($file!='.'&&$file!='..')
			        $tmpls[] = $file;
			    }
			    @closedir($dir);
				//end
				
				$v['input_type'] = 1;
				$v['value_scope'] = $tmpls;
			}
			else
			$v['value_scope'] = explode(",",$v['value_scope']);
			$conf[$v['group_id']][] = $v;
		}
		$this->assign("conf",$conf);
		//print_r($conf);die();
		$this->display();
	}
	
	public function update()
	{
		$conf_res = M("Conf")->where("is_effect = 1 and is_conf = 1")->findAll();
		foreach($conf_res as $k=>$v)
		{
			conf($v['name'],$_REQUEST[$v['name']]);
			if($v['name']=='URL_MODEL'&&$v['value']!=$_REQUEST[$v['name']])
			{
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				 
				clear_dir_file(get_real_path()."public/runtime/app/data_caches/");	
				clear_dir_file(get_real_path()."public/runtime/app/tpl_caches/");	
				clear_dir_file(get_real_path()."public/runtime/app/tpl_compiled/");	
				
				clear_dir_file(get_real_path()."public/runtime/app/data_caches/");	
				clear_dir_file(get_real_path()."public/runtime/data/page_static_cache/");
				clear_dir_file(get_real_path()."public/runtime/data/dynamic_avatar_cache/");	
			}
		}
		
			//开始写入配置文件
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
		
			
		save_log(l("CONF_UPDATED"),1);		
		//clear_cache();
		write_timezone();
		$this->success(L("UPDATE_SUCCESS"));
	}
	
	public function mobile()
	{
		$conf_res = M("MConfig")->order("group_name asc,sort asc")->findAll();
		$wx_appid='';
		$wx_secrit='';
		$wx_url='';
		
		foreach($conf_res as $k=>$v)
		{
			$v['value'] = htmlspecialchars($v['value']);
			
		
			//$v['value_scope'] = explode(",",$v['value_scope']);
			$config[$v['group_name']][] = $v;
		}		
		
	
		$this->assign("config",$config);
		$this->display();
	}
	
	public function savemobile()
	{
		foreach($_POST as $k=>$v)
		{
			M("MConfig")->where("code='".$k."'")->setField("val",$v);
		}
		$this->success("保存成功");
	}
	
	public function insertnews()
	{
			//B('FilterString');
		$name="MConfigList";
		$model = D ($name);
		if (false ===$data= $model->create ()) {
			$this->error ( $model->getError () );
		}
		$data['is_verify'] = 1;
		$data['group'] = 4;
		//保存当前数据对象
		$list=$model->add ($data);
		if ($list!==false) { //保存成功
			//$this->saveLog(1,$list);
			$this->success (L('INSERT_SUCCESS'));
		} else {
			//失败提示
			//$this->saveLog(0,$list);
			$this->error (L('INSERT_FAILED'));
		}
	}
	function edit() {
		$name = "MConfigList";
		$model = D($name);
		
		$id = $_REQUEST [$model->getPk ()];
		$vo = $model->getById($id);
		$this->assign ( 'vo', $vo );
		$this->display ();
	}
	public function news()
	{
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$map['group'] = 4;
		$name=$this->getActionName();
		$model = D ("MConfigList");
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
		return;
	}
	
	function updatenews() {
		//B('FilterString');
		$name="MConfigList";
		$model = D ( $name );
		if (false === $data = $model->create ()) {
			$this->error ( $model->getError () );
		}
		// 更新数据
		$list=$model->save ($data);
		$id = $data[$model->getPk()];
		if (false !== $list) {
			//成功提示
			//$this->saveLog(1,$id);
			$this->success (L('UPDATE_SUCCESS'));
		} else {
			//错误提示
			//$this->saveLog(0,$id);
			$this->error (L('UPDATE_FAILED'));
		}
	}
	
	public function foreverdelete() {
		//删除指定记录
		$result = array('isErr'=>0,'content'=>'');
		$id = $_REQUEST['id'];
		if(!empty($id))
		{
			$name="MConfigList";
			$model = D($name);
			$pk = $model->getPk ();
			$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
			if(false !== $model->where ( $condition )->delete ())
			{
				//$this->saveLog(1,$id);
			}
			else
			{
				//$this->saveLog(0,$id);
				$result['isErr'] = 1;
				$result['content'] = L('FOREVER_DELETE_SUCCESS');
			}
		}
		else
		{
			$result['isErr'] = 1;
			$result['content'] = L('FOREVER_DELETE_FAILED');
		}
		
		die(json_encode($result));
	}
	

	public function toogle_status()
	{
		$id = intval($_REQUEST['id']);
		$ajax = intval($_REQUEST['ajax']);
		$field = $_REQUEST['field'];
		$info = $id."_".$field;
		$c_is_effect = M("MConfigList")->where("id=".$id)->getField($field);  //当前状态

		$n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
		M("MConfigList")->where("id=".$id)->setField($field,$n_is_effect);
		
		save_log($info.l("SET_EFFECT_".$n_is_effect),1);
		$this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1)	;	
	}
	
}
?>