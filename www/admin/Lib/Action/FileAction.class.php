<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class FileAction extends CommonAction{
	
	/**
	 * 图标上传
	 */
	public function do_upload_icon()
	{
		require_once APP_ROOT_PATH."system/utils/zip.php";
		$archive  = new PHPZip();
		$font_dir = APP_ROOT_PATH."public/iconfont";
		
		$result = $archive->unZip($_FILES['file']['tmp_name'], $font_dir);
		if(empty($result)||$result==-1)
		{
			ajax_return(array("status"=>false,"info"=>"图标库更新失败，请手动解压后上传文件到".$font_dir));
		}
		
		
		if ( $dir = opendir( $font_dir."/" ) )
		{
			while ( $file = readdir( $dir ) )
			{
				$check = is_dir( $font_dir."/". $file );
				if ( !$check )
				{
					@unlink( $font_dir ."/". $file );
				}
			}
		}
	
		
		
	
		$result = $archive->unZip($_FILES['file']['tmp_name'], $font_dir);		
		//清空原文件
		

		foreach($result as $k=>$v)
		{
			$file = APP_ROOT_PATH."public/iconfont/".$k;
			$file_arr = explode("/", $file);
			
			foreach($file_arr as $f)
			{
				if($f=="iconfont.css"||$f=="iconfont.eot"||$f=="iconfont.svg"||$f=="iconfont.ttf"||$f=="iconfont.woff")
				{
					//echo APP_ROOT_PATH."public/iconfont/".$f;
					@rename($file,APP_ROOT_PATH."public/iconfont/".$f);
					if($GLOBALS['distribution_cfg']['CSS_JS_OSS']&&$GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
					{
						syn_to_remote_file_server("public/iconfont/".$f);
					}
				}
			}
			
		}
		
		foreach($result as $k=>$v)
		{
			$file = APP_ROOT_PATH."public/iconfont/".$k;
			@unlink($file);
		}
		foreach($result as $k=>$v)
		{
			$file = APP_ROOT_PATH."public/iconfont/".$k;
			@rmdir($file);
		}
		ajax_return(array("status"=>true,"info"=>""));
	}
	
	public function fetch_icon()
	{
		$file = APP_ROOT_PATH."public/iconfont/iconfont.css";
		$cnt = file_get_contents($file);
		
		preg_match_all("/content[^\da-zA-Z]+([\da-zA-Z]+)/", $cnt, $matches);
		if($matches)
		{
			$html = "";
			foreach($matches[1] as $v)
			{
				$code = "&#x".$v.";";
				$html.="<a href='javascript:void(0);' class='diyfont pickfont' rel=".$code.">".$code."</a>";
				
			}
		}
		$html.="<a href='javascript:void(0);' class='diyfont pickfont' rel=''>清除</a>";
		$data['html'] =$html;
		ajax_return($data);
	}
	
	public function do_upload()
	{
		if(intval($_REQUEST['upload_type'])==0)
		$result = $this->uploadFile();
		else
		$result = $this->uploadImage();
		if($result['status'] == 1)
		{
			$list = $result['data'];
			if(intval($_REQUEST['upload_type'])==0)
			$file_url = ".".$list[0]['recpath'].$list[0]['savename'];
			else
			$file_url = ".".$list[0]['bigrecpath'].$list[0]['savename'];
			$html = '<html>';
			$html.= '<head>';
			$html.= '<title>Insert Image</title>';
			$html.= '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
			$html.= '</head>';
			$html.= '<body>';
			$html.= '<script type="text/javascript">';
			$html.= 'parent.parent.KE.plugin["image"].insert("' . $_POST['id'] . '", "' . $file_url . '","' . $_POST['imgTitle'] . '","' . $_POST['imgWidth'] . '","' . $_POST['imgHeight'] . '","' . $_POST['imgBorder'] . '","' . $_POST['align'] . '");';
			$html.= '</script>';
			$html.= '</body>';
			$html.= '</html>';
			echo $html;
		}
		else
		{
			echo "<script>alert('".$result['info']."');</script>";
		}
	}
	public function do_upload_img()
	{
		if(intval($_REQUEST['upload_type'])==0)
		$result = $this->uploadFile();
		else
		$result = $this->uploadImage();
		if($result['status'] == 1)
		{
			$list = $result['data'];
			if(intval($_REQUEST['upload_type'])==0)
			$file_url = ".".$list[0]['recpath'].$list[0]['savename'];
			else
			$file_url = ".".$list[0]['bigrecpath'].$list[0]['savename'];
			$html = '<html>';
			$html.= '<head>';
			$html.= '<title>Insert Image</title>';
			$html.= '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
			$html.= '</head>';
			$html.= '<body>';
			$html.= '<script type="text/javascript">';
			//$html.='alert("'.$_POST['id'].'");';
			//$html.='alert(parent.parent.document.getElementById("'.$_POST['id'].'").value);';
			//$html.='parent.parent.document.getElementById("'.$_POST['id'].'").value="'.$file_url.'";';
			$html.= 'parent.parent.KE.plugin["upload_image"].insert("' . $_POST['id'] . '", "' . $file_url . '","' . $_POST['imgTitle'] . '","' . $_POST['imgWidth'] . '","' . $_POST['imgHeight'] . '","' . $_POST['imgBorder'] . '","' . $_POST['align'] . '");';
			$html.= '</script>';
			$html.= '</body>';
			$html.= '</html>';
			echo $html;
		}
		else
		{
			echo "<script>alert('".$result['info']."');</script>";
		}
	}

	
	public function deleteImg()
	{
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$file = $_REQUEST['file'];
		$file = explode("..",$file);
		$file = $file[4];
		$file = substr($file,1);
		@unlink(get_real_path().$file);	
	    if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']=='ES_FILE')
        {
	      	$syn_url = $GLOBALS['distribution_cfg']['OSS_DOMAIN']."/es_file.php?username=".$GLOBALS['distribution_cfg']['OSS_ACCESS_ID']."&password=".$GLOBALS['distribution_cfg']['OSS_ACCESS_KEY']."&path=".$file."&act=1";
	      	@file_get_contents($syn_url);
      	}	

		save_log(l("DELETE_SUCCESS"),1);
		$this->success(l("DELETE_SUCCESS"),$ajax);
	}
}
?>