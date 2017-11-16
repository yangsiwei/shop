<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class mobile_qrcodeApiModule extends MainBaseApiModule
{	
	/**
	 * 扫一扫接口，通过二维码识别pc端url，并定向到相应的app页
	 * 
	 * 输入
	 * pc_url: string 完整的pc端url
	 * 主要识别:团购/商品/积分/优惠/活动/商家的 列表，详细页
	 * 
	 * 输出
	 * type: int 系统app配置的对应列表与详细页的type
	 * params: array 相关页面对应传入的参数
	 * 参考相应的接口的输入参数
	 * 列表，一般只返回分类，小类，商圈与关键词
	 * 详细页，一般只返回data_id
	 * 
	 * 
	 * 如团购列表，一般只返回分类，小类，商圈与关键词
	 * 输入：
	 * cate_id: int 团购大分类ID
	 * tid: int 团购小分类ID
	 * qid: int 商圈ID
	 * 
	 * 则输出的params为
	 * array(
	 * 	cate_id:
	 * 	tid:
	 *  qid:
	 * )
	 * 
	 * 
	 */
	public function index()
	{
	
		$pc_url = strim($GLOBALS['request']['pc_url']);
		if(substr($pc_url, 0,7)=="http://"||substr($pc_url, 0,8)=="https://")
		{
			$is_url = true;
		}
		else
		{
			$is_url = false;
		}
				
		//开始识别
		$pc_url = str_replace("&amp;","&",$pc_url);
		//开始获取推荐人信息
		if(preg_match_all("/r(-|=)([^-|&]*)/i",$pc_url,$matches))
		{
			$GLOBALS['request']['ref_uid'] =	intval(base64_decode(urldecode($matches[2][0])));			
			if($GLOBALS['request']['ref_uid'])
			{
				$rid = intval($GLOBALS['request']['ref_uid']);
				$GLOBALS['ref_uid'] = intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where id = ".intval($rid)));
			}
			
			//判断是否为小店的扫码
			$preg_xd[] = "/index\.php\?ctl=uc_home&act=mall(.*)/i";
			$preg_xd[] = "/index\.php\?ctl=uc_fx&act=uc_mall(.*)/i";
			$preg_xd[] = "/(uc_home\/mall)(.*)/i";
			$is_match_xd = false;
			foreach($preg_xd as $preg_item)
			{
				if(preg_match_all($preg_item,$pc_url,$matches))
				{
					$is_match_xd = true;
					break;
				}
			}
			if($is_match_xd)
			{
				$root['type'] = 51;
				return output($root);
			}
		}
		
		//团购列表
		$preg[] = "/index\.php\?ctl=(tuan)(.*)/i";
		$preg[] = "/(tuan)(.*)/i";		
				
		//商城列表
		$preg[] = "/index\.php\?ctl=(cate)(.*)/i";
		$preg[] = "/(cate)(.*)/i";
		$preg[] = "/index\.php\?ctl=(mall)(.*)/i";
		$preg[] = "/(mall)(.*)/i";
		
		//积分列表
		$preg[] = "/index\.php\?ctl=(scores)(.*)/i";
		$preg[] = "/(scores)(.*)/i";
		
		//优惠列表
		$preg[] = "/index\.php\?ctl=(youhuis)(.*)/i";
		$preg[] = "/(youhuis)(.*)/i";
		
		//活动列表
		$preg[] = "/index\.php\?ctl=(events)(.*)/i";
		$preg[] = "/(events)(.*)/i";
		
		//商家列表
		$preg[] = "/index\.php\?ctl=(stores)(.*)/i";
		$preg[] = "/(stores)(.*)/i";

		//商品详细
		$preg[] = "/index\.php\?ctl=(deal)(.*)/i";
		$preg[] = "/(deal)(.*)/i";
		
		//优惠详细
		$preg[] = "/index\.php\?ctl=(youhui)(.*)/i";
		$preg[] = "/(youhui)(.*)/i";
		
		//活动详细
		$preg[] = "/index\.php\?ctl=(event)(.*)/i";
		$preg[] = "/(event)(.*)/i";
		
		//商家详细
		$preg[] = "/index\.php\?ctl=(store)(.*)/i";
		$preg[] = "/(store)(.*)/i";
				
		
		
		$is_match = false;
		foreach($preg as $preg_item)
		{
			if(preg_match_all($preg_item,$pc_url,$matches))
			{
				$is_match = true;
				break;
			}
		}
		
		
		if($is_match||$is_url)
		{
			$root = array();
			$ctl = trim($matches[1][0]);
			$params = trim($matches[2][0]);
			switch($ctl)
			{
				case "tuan":
					$root['type'] = 11;
					$root['params'] = $this->fetch_tuan_params($params);
					break;
				case "cate":
					$root['type'] = 12;
					$root['params'] = $this->fetch_cate_params($params);
					break;
				case "mall":
					$root['type'] = 12;
					$root['params'] = $this->fetch_params($params);
					break;
				case "scores":
					$root['type'] = 13;
					$root['params'] = $this->fetch_cate_params($params);
					break;
				case "events":
					$root['type'] = 14;
					$root['params'] = $this->fetch_tuan_params($params);
					break;
				case "youhuis":
					$root['type'] = 15;
					$root['params'] = $this->fetch_tuan_params($params);
					break;
				case "stores":
					$root['type'] = 16;
					$root['params'] = $this->fetch_tuan_params($params);
					break;
				case "deal":
					$root['type'] = 21;
					$root['params'] = $this->fetch_deal_params($params);
					break;
				case "event":
					$root['type'] = 24;
					$root['params'] = $this->fetch_event_params($params);
					break;
				case "youhui":
					$root['type'] = 25;
					$root['params'] = $this->fetch_youhui_params($params);
					break;
				case "store":
					$root['type'] = 26;
					$root['params'] = $this->fetch_store_params($params);
					break;
			
				default:
					if($is_url)
						$root['type'] = 0;  //无法识别，但是为url
					else
						$root['type'] = -1; //无法识别，不为url
					break;
			}
			
			return output($root);
		}
		else
		{
			return output(array(),0,"无法识别");
		}
		
		//

	}
	
	
	private function fetch_tuan_params($params_str)
	{
		$params = array();
		
		$preg = "/cid(-|=)(\d+)/i";
		preg_match_all($preg,$params_str,$matches);
		$params['cate_id'] = intval($matches[2][0]);
		
		$preg = "/tid(-|=)(\d+)/i";
		preg_match_all($preg,$params_str,$matches);
		$params['tid'] = intval($matches[2][0]);
		
		$preg = "/qid(-|=)(\d+)/i";
		preg_match_all($preg,$params_str,$matches);
		$params['qid'] = intval($matches[2][0]);
		
		if($params['qid']==0)
		{
			$preg = "/aid(-|=)(\d+)/i";
			preg_match_all($preg,$params_str,$matches);
			$params['qid'] = intval($matches[2][0]);
		}
		
		return $params;
		
	}
	
	private function fetch_cate_params($params_str)
	{
		$params = array();
		
		$preg = "/cid(-|=)(\d+)/i";
		preg_match_all($preg,$params_str,$matches);
		$params['cate_id'] = intval($matches[2][0]);
		
		$preg = "/bid(-|=)(\d+)/i";
		preg_match_all($preg,$params_str,$matches);
		$params['bid'] = intval($matches[2][0]);
		
		
		return $params;
	}
	
	private function fetch_deal_params($params_str)
	{
		$params = array();		
		$preg = "/act=(\w+)/i";
		preg_match_all($preg,$params_str,$matches);
		$params['data_id'] = strim($matches[1][0]);		
		if(empty($params['data_id']))
		{		
			$preg = "/\/(\w+)/i";
			preg_match_all($preg,$params_str,$matches);
			$params['data_id'] = strim($matches[1][0]);
		}		
		return $params;
	}
	
	private function fetch_event_params($params_str)
	{
		$params = array();
		$preg = "/act=(\d+)/i";
		preg_match_all($preg,$params_str,$matches);
		$params['data_id'] = intval($matches[1][0]);
		if(empty($params['data_id']))
		{
			$preg = "/\/(\w+)/i";
			preg_match_all($preg,$params_str,$matches);
			$params['data_id'] = intval($matches[1][0]);
		}
		return $params;
	}
	
	private function fetch_youhui_params($params_str)
	{
		$params = array();
		$preg = "/act=(\d+)/i";
		preg_match_all($preg,$params_str,$matches);
		$params['data_id'] = intval($matches[1][0]);
		if(empty($params['data_id']))
		{
			$preg = "/\/(\w+)/i";
			preg_match_all($preg,$params_str,$matches);
			$params['data_id'] = intval($matches[1][0]);
		}
		return $params;
	}
	
	private function fetch_store_params($params_str)
	{
		$params = array();
		$preg = "/act=(\d+)/i";
		preg_match_all($preg,$params_str,$matches);
		$params['data_id'] = intval($matches[1][0]);
		if(empty($params['data_id']))
		{
			$preg = "/\/(\w+)/i";
			preg_match_all($preg,$params_str,$matches);
			$params['data_id'] = intval($matches[1][0]);
		}
		return $params;
	}
	
	private function fetch_params($params_str)
	{
		return null;
	}
		
}
?>