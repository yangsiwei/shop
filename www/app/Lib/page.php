<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------


class Page  {
    // 起始行数
    public $firstRow	;
    // 列表每页显示行数
    public $listRows	;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页栏每页显示的页数
    protected $rollPage   ;
    
    protected $style; //样式
	// 分页显示定制
    protected $config  =	array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页',
    		'theme'=>array(
    			'default'=>' %upPage% %downPage% %first%  %prePage%  %linkPage% %nextPage%',
    			'short'=>' %totalRow% %header% %upPage%  %first%  %prePage%  %linkPage%   %end%',
    		));

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
    public function __construct($totalRows,$listRows,$parameter='',$style="default") {
    	$this->style = $style;
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->rollPage = 5;
        $this->listRows = !empty($listRows)?$listRows:C('PAGE_LISTROWS');
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = intval($_GET['p'])?intval($_GET['p']):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

   private function get_page_link($url,$page)
    {
    	if(app_conf("URL_MODEL")==1)
    	{
    		$is_param = false;
    		foreach($_GET as $k=>$v)
    		{
    			if($k!="ctl"&&$k!="act"&&$k!="p"&&$k!="city")
    			{
    				$is_param = true;
    				break;
    			}
    		}
    		
    		if($is_param)
    		$url.="-p-".$page;
    		elseif(substr($url,-1)=='/')
    		$url.="p-".$page;
    		else
    		$url.="/p-".$page;
    	}
    	else
    	{
    		if(substr($url,-1)=="?")
	    	$url.="p=".$page;
	    	elseif(strpos($url,'?')&&substr($url,-1)!="&")
	    	$url.="&p=".$page;
	    	elseif(strpos($url,'?')&&substr($url,-1)!="?")
	    	$url.="p=".$page;
	    	elseif(!strpos($url,'?'))
	    	$url.="?p=".$page;
	    	else
	    	$url.="?&p=".$page;
    	}
    	return $url;
    }
    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p = 'p';
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        
        if(app_conf("URL_MODEL")==1)$url = $GLOBALS['current_url'];
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a href='".$this->get_page_link($url,$upRow)."'>".$this->config['prev']."</a>";
        }else{
            $upPage="<span class='current'>".$this->config['prev']."</span>";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a href='".$this->get_page_link($url,$downRow)."'>".$this->config['next']."</a>";
        }else{
            $downPage="<span class='current'>".$this->config['next']."</span>";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<a href='".$this->get_page_link($url,$preRow)."' >上".$this->rollPage."页</a>";
            $theFirst = "<a href='".$this->get_page_link($url,1)."' >".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            if($nextRow>$this->totalPages)$nextRow = $this->totalPages;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href='".$this->get_page_link($url,$nextRow)."' >下".$this->rollPage."页</a>";
            $theEnd = "<a href='".$this->get_page_link($url,$theEndRow)."' >".$this->config['last']."</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "<a href='".$this->get_page_link($url,$page)."'>".$page."</a> ";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "<span class='current'>".$page."</span> ";
                }else{
                	$upPage="";
                	$downPage="";
                }
            }
        }
        
        $pageStr	 =	 str_replace(
        		array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
        		array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$theFirst,$upPage,$prePage,$linkPage,$nextPage,$downPage,$theEnd),$this->config['theme'][$this->style]);
      // echo $pageStr;exit; 
        return $pageStr;
    }

}
?>