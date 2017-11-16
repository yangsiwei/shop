<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class noticeApiModule extends MainBaseApiModule
{
	
	/**
	 * 文章列表接口
	 * 
	 * 输入： 
	 * page:int 当前的页数
	 * 
	 * 
	 * 
	 * 输出：
	 * page_title:string 页面标题
	 * page:array 分页信息 array("page"=>当前页数,"page_total"=>总页数,"page_size"=>分页量,"data_total"=>数据总量);
	 * list:array:array 文章列表，结构如下
	 *  Array
        (
            [0] => Array
                (
                    [id] => 4  [int] 文章ID
                    [name] => 贵安温泉自驾游 [string] 文章标题                    
                )
         ) 
	 * 
	 */
	public function index()
	{
	
		$root = array();
		
		$page = intval($GLOBALS['request']['page']); //分页

		$page=$page==0?1:$page;

		$page_size = PAGE_SIZE;
		$limit = (($page-1)*$page_size).",".$page_size;			
		$result = $GLOBALS['db']->getAll("select id,name from ".DB_PREFIX."m_notice where is_effect=1 order by sort limit ".$limit);
		$data_total = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."m_notice where is_effect=1");
		$page_total = ceil($data_total/$page_size);
		
		$root['list'] = $result?$result:array();			
		
		$root['page'] = array("page"=>$page,"page_total"=>$page_total,"page_size"=>$page_size,"data_total"=>$data_total);
		
		$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
		$root['page_title'].="站点公告";
		
		return output($root);
	}
	
	

	
	
	/**
	 * 文章详细页接口
	 * 
	 * 输入： 
	 * id:int 文章id 
	 * 
	 * 
	 * 输出：
	 * page_title:string 页面标题	 
	 * result:array 文章详细内容，结构如下
	 *  Array
	 *  (
                [name] => 贵安温泉自驾游 [string] 文章标题 
                [content]=>也推进了中国的工业化进程，但大部分利润并没有留在国内。 [string]  文章内容                   
				[create_time] => 2015-04-07 [string] 文章发布时间
         ) 
	 * 
	 */
	public function detail()
	{
	
		$root = array();
		
		$id = intval($GLOBALS['request']['id']);
		
		$result = $GLOBALS['db']->getRow("select name,content,create_time from ".DB_PREFIX."m_notice where is_effect=1 and id=".$id);
		if($result){
			$result['create_time']=to_date($result['create_time'],"Y-m-d");
			$result['content'] = get_abs_img_root(format_html_content_image($result['content'],720));
		}		
		
		$root['result'] = $result?$result:null;
		
		$root['page_title'] = $GLOBALS['m_config']['program_title']?$GLOBALS['m_config']['program_title']." - ":"";
		$root['page_title'].="站点公告";


		
		return output($root);
	}
	
}
?>