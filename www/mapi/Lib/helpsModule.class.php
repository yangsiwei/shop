<?php
class helpsApiModule extends MainBaseApiModule{
	
	/**
	 * 帮助文章列表接口
	 * 
	 * 输入： 
	 * 
	 * 
	 * 
	 * 
	 * 输出：
	 * page_title:string 页面标题
	 * 
	 * list:array:array 帮助文章列表，结构如下
	 *  Array
        (
		    [1] => Array
		        (
		            [id] => 19 分类id
		            [title] => 系统文章  分类名称
		            [article_list] => Array
		                (
		                    [0] => Array
		                        (
		                            [id] => 27 帮助文章id
		                            [title] => 免责条款   帮助文章标题
		                        )
		
		                )
		
		        )
         ) 
	 * 
	 */	
    public function index(){
    	$root = array();        
        $list = load_auto_cache('cache_helps');
        $root['list'] = $list?$list:array();

		$root['page_title'].="帮助";
        return output($root);
    }

    
	/**
	 * 帮助文章详细页接口
	 * 
	 * 输入： 
	 * id:int 帮助文章id 
	 * 
	 * 
	 * 输出：
	 * page_title:string 页面标题	 
	 * result:array 文章详细内容，结构如下
	 *  Array
	 *  (
                [name] => 贵安温泉自驾游 [string] 帮助文章标题 
                [content]=>也推进了中国的工业化进程，但大部分利润并没有留在国内。 [string]  文章内容                   
				[create_time] => 2015-04-07 [string] 文章发布时间
         ) 
	 * 
	 */    
    
    public function show(){
    	$root = array();
        $id = intval($GLOBALS['request']['id']);
		$result = $GLOBALS['db']->getRow("select title,content,create_time from ".DB_PREFIX."article where is_effect=1 and id=".$id);
		if($result){
			$result['create_time']=to_date($result['create_time'],"Y-m-d");
			$result['content'] = get_abs_img_root(format_html_content_image($result['content'],720));
		}		
        $root['result'] = $result?$result:array();

		$root['page_title'].="帮助";
        return output($root);
    }
}