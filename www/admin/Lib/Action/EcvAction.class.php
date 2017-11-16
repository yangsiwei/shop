<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class EcvAction extends CommonAction{
	public function index()
	{
		$ecv_type = M("EcvType")->where("id=".intval($_REQUEST['ecv_type_id']))->find();
		if(!$ecv_type)
		{
			$this->error(l("INVALID_ECV_TYPE"));
		}
		else
		{
			$this->assign("ecv_type",$ecv_type);
		}
		$condition['ecv_type_id'] = intval($_REQUEST['ecv_type_id']);
		$this->assign("default_map",$condition);		
		parent::index();
	}
	
	public function export_csv($page = 1)
	{
		set_time_limit(0);
		$limit = (($page - 1)*intval(app_conf("BATCH_PAGE_SIZE"))).",".(intval(app_conf("BATCH_PAGE_SIZE")));
		
		$map['ecv_type_id'] = intval($_REQUEST['ecv_type_id']);
	
		
		$list = M(MODULE_NAME)
				->where($map)
				->limit($limit)->findAll ( );
			
		if($list)
		{
			register_shutdown_function(array(&$this, 'export_csv'), $page+1);
			
			$ecv_value = array('sn'=>'""', 'money'=>'""','use_limit'=>'""', 'begin_time'=>'""', 'end_time'=>'""');
	    	if($page == 1)
	    	{
		    	$content = iconv("utf-8","gbk","序列号,面额,使用数量,生效时间,过期时间");	    		    	
		    	$content = $content . "\n";
	    	}
	    	
			foreach($list as $k=>$v)
			{
				
				$ecv_value['sn'] = '"' . iconv('utf-8','gbk',$v['sn']) . '"';
				$ecv_value['money'] = '"' . iconv('utf-8','gbk',round($v['money'],2)."元") . '"';
				$ecv_value['use_limit'] = '"' . iconv('utf-8','gbk',$v['use_limit']) . '"';
				$ecv_value['begin_time'] = '"' . iconv('utf-8','gbk',to_date($v['begin_time'])) . '"';
				$ecv_value['end_time'] = '"' . iconv('utf-8','gbk',to_date($v['end_time'])) . '"';				
				
				$content .= implode(",", $ecv_value) . "\n";
			}	
			
			
			header("Content-Disposition: attachment; filename=voucher_list.csv");
	    	echo $content;  
		}
		else
		{
			if($page==1)
			$this->error(L("NO_RESULT"));
		}	
		
	}
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		$ecv_type_id = 0;
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
	
				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['sn'];	
					$ecv_type_id = $data['ecv_type_id'];
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();	
		
				if ($list!==false) {
					M("EcvType")->where("id=".$ecv_type_id)->setField("gen_count",M("Ecv")->where("ecv_type_id=".$ecv_type_id)->count());
					
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	
	
	
	
}
?>