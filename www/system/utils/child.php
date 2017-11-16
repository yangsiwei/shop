<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

//获取所有子集的类
class child
{
	public function __construct($tb_name)
	{
		$this->tb_name = $tb_name;	
	}
	private $tb_name;
	private $childIds;
	private function _getChildIds($pid = '0', $pk_str='id' , $pid_str ='pid',$sort=array())
	{
//		$childItem_arr = $this->field('id')->where($pid_str."=".$pid)->findAll();
		if($sort){
			$orderby= " order by ";
			foreach($sort as $v){
				$orderby.=$v." desc ";
				if($v!=end($sort)){
					$orderby .=" , " ;
				}
			}
			$sql="select id from ".DB_PREFIX.$this->tb_name." where ".$pid_str."=".$pid.$orderby;
		}else{
			$sql="select id from ".DB_PREFIX.$this->tb_name." where ".$pid_str."=".$pid;
		}
		$childItem_arr = $GLOBALS['db']->getAll($sql);
		if($childItem_arr)
		{
			foreach($childItem_arr as $childItem)
			{
				$this->childIds[] = $childItem[$pk_str];
				$this->_getChildIds($childItem[$pk_str],$pk_str,$pid_str);
			}
		}
	}
	public function getChildIds($pid = '0', $pk_str='id' , $pid_str ='pid',$sort=array())
	{
		$this->childIds = array();
		$this->_getChildIds($pid,$pk_str,$pid_str,$sort);
		return $this->childIds;
	}
}
?>