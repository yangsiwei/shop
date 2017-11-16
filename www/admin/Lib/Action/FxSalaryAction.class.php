<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jobinlin
// +----------------------------------------------------------------------
class FxSalaryAction extends CommonAction{
	public function index()
	{
	    $fx_name = M('Conf');
	    $name =  $fx_name->where("name='FX_SET_NAME'")->find();
	    $this->assign("fx_name",$name['value']);
	    $this->assign("title_name","全局邀请推广奖设置");
		$this->display ();
	}

	public function load_fx_level(){
	    $fx_salary_type = intval($_REQUEST['fx_salary_type']);
	    
	    $is_init = intval($_REQUEST['is_init']);
	    
        $FxSalary = M("FxSalary");
         
        if ($is_init) {
            $data = $FxSalary->where()->select();
            
            $fx_salary_type     = $data[0]['fx_salary_type'];
            
            $fx_type_qrcode     = $data[0]['fx_type_qrcode'];
            
            $fx_default_status  = $data[0]['fx_default_status'];
            
            $fx_is_open         = $data[0]['fx_is_open'];
        }
        
        if($data){
            
            foreach ($data as $k=>$v){
                $f_data[$v['fx_level']] = $v;
            }
        }
        
	    if($fx_salary_type==1){
	        $type_html = '<select name="fx_salary_type" ><option value="0" >定额</option><option value="1" selected="selected">比率</option></select>';
	    }else{
	        $type_html = '<select name="fx_salary_type" ><option value="0" selected="selected">定额</option><option value="1">比率</option></select>';
	    }
	    
	    if($fx_type_qrcode==1){
	        $qrcode_html = '<select name="fx_type_qrcode" ><option value="0" >临时</option><option value="1" selected="selected">永久</option></select>';
	    }else{
	        $qrcode_html = '<select name="fx_type_qrcode"><option selected="selected"  value="0" >临时</option><option value="1">永久</option></select>';
	    }
	    
	    if($fx_default_status==1){
	        $default_html = '<select name="fx_default_status" ><option value="1" selected="selected">是</option><option value="0" >否</option></select>';
	    }else{
	        $default_html = '<select name="fx_default_status" ><option value="1">是</option><option selected="selected" value="0" >否</option></select>';
	    }
	    
	    if($fx_is_open==1){
	        $fx_is_open_html = '<select name="fx_is_open" ><option value="1" selected="selected">是</option><option value="0" >否</option></select>';
	    }else{
	        $fx_is_open_html = '<select name="fx_is_open" ><option value="1">是</option><option  selected="selected" value="0" >否</option></select>';
	    }
	    
	    
	    
	    $level_html = '';
	    $type_str = $fx_salary_type==0?'元':'%';
	    
	    if($data){
	        
	        for ($i=1;$i<=FX_LEVEL;++$i){
	            $str = $i==0?"邀请推广奖":$i."级邀请推广奖";
	            $s_data = $f_data[$i];
	           
	            $s_data['fx_salary'] = $fx_salary_type?$s_data['fx_salary']*100:$s_data['fx_salary'];
	            $level_html .='<div><span style="text-align:left;width:100px;display: inline-block;*zoom:1;*display:inline;">'.$str.'</span><input type="text" class="textbox" name="fx_salary[]" value="'.round($s_data['fx_salary'],2).'" />&nbsp;'.$type_str.'</div><div class="blank5"></div>';
	        }
	    }else{
	        for ($i=1;$i<=FX_LEVEL;++$i){
	            $str = $i==0?"邀请推广奖":$i."级邀请推广奖";
	            $level_html .='<div><span style="text-align:left;width:100px;display: inline-block;*zoom:1;*display:inline;">'.$str.'</span><input type="text" class="textbox" name="fx_salary[]" value="" />&nbsp;'.$type_str.'</div><div class="blank5"></div>';
	        }
	    }
	    
	    $result['fx_is_open_html']      = $fx_is_open_html;
	    $result['default_html']    = $default_html;
	    $result['qrcode_html']     = $qrcode_html;
	    $result['type_html']       = $type_html;
	    $result['level_html']      = $level_html;
	    ajax_return($result);
	}
	 
	
	/**
	 * 保存
	 */
	public function save(){
	    $ajax = intval($_REQUEST['is_ajax']);
	    $fx_set_type = intval($_REQUEST['fx_set_type']);
	    
	    $data = $_REQUEST;
	    
	    $conf = M('Conf');
	    $name =  $conf->where("name='FX_SET_NAME'")->data(array('value'=>$data['fx_name']))->save();
	    
	    
	    $FxSalary = M("FxSalary");
	    //删除旧数据
	    $FxSalary->where('level_id=0')->delete();
	    
	    $s_data = array();
	    $fx_salary_type    = intval($data['fx_salary_type']);
	    $fx_type_qrcode    = intval($data['fx_type_qrcode']);
	    $fx_default_status = intval($data['fx_default_status']);
	    $fx_is_open        = intval($data['fx_is_open']);
	    $s_data['fx_salary_type']      = $fx_salary_type;
	    $s_data['fx_type_qrcode']      = $fx_type_qrcode;
	    $s_data['fx_default_status']   = $fx_default_status;
	    $s_data['fx_is_open']          = $fx_is_open;
	    $s_data['level_id'] = 0;
	    foreach ($data['fx_salary'] as $k=>$v){ //邀请等级由0开始
	        $ins_data['fx_level'] = $k+1;
	        $ins_data['fx_salary'] = $fx_salary_type?floatval($v/100):floatval($v);
	        $FxSalary->add(array_merge($s_data,$ins_data));
	       
	    }
	    
	    $result['jump'] = u(MODULE_NAME."/index");
	    if ($ajax){
	        ajax_return($result);
	    }else{
	        $this->assign("jumpUrl",$result['jump']);
	        $this->success(L("UPDATE_SUCCESS"));
	    }    
	    
	}
 
  
}
?>