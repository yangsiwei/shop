<?php
class RegionConfAction extends CommonAction{
	public function index()
	{
		$this->updateRegionJS();
	}
	
	private function updateRegionJS()
	{
		$jsStr = "var regionConf = ".$this->getRegionJS();
		$path = get_real_path()."system/region.js";
		@file_put_contents($path,$jsStr);
	}
	
	private function getRegionJS()
	{
		$jsStr = "";
		$childRegionList = M("RegionConf")->where("region_level = 2")->order("id asc")->findAll();
		
		foreach($childRegionList as $childRegion)
		{
			if(empty($jsStr))
				$jsStr .= "{";
			else
				$jsStr .= ",";
				
			$childStr = $this->getRegionChildJS($childRegion['id']);
			$jsStr .= "\"r$childRegion[id]\":{\"i\":$childRegion[id],\"n\":\"$childRegion[name]\",\"c\":$childStr}";
		}
		
		if(!empty($jsStr))
			$jsStr .= "}";
		else
			$jsStr .= "\"\"";
				
		return $jsStr;
	}
	private function getRegionChildJS($pid)
	{
		$jsStr = "";
		$childRegionList = M("RegionConf")->where("pid=".$pid)->order("id asc")->findAll();
		
		foreach($childRegionList as $childRegion)
		{
			if(empty($jsStr))
				$jsStr .= "{";
			else
				$jsStr .= ",";
				
			$childStr = $this->getRegionChildJS($childRegion['id']);
			$jsStr .= "\"r$childRegion[id]\":{\"i\":$childRegion[id],\"n\":\"$childRegion[name]\",\"c\":$childStr}";
		}
		
		if(!empty($jsStr))
			$jsStr .= "}";
		else
			$jsStr .= "\"\"";
				
		return $jsStr;
	}
}
?>