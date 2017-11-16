<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class ShareImageAction extends CommonAction{
	public function delete()
	{
		$id = intval($_REQUEST['id']);
		$tid = intval($_REQUEST['tid']);
		$data = M("ShareImage")->getById($id);
		if(!$data)$this->ajaxReturn(l("IMAGE_NOT_EXIST"),"",0);
			
		$info = $data['share_id'].l("SHARE_IMAGE");
//		@unlink(APP_ROOT_PATH.$data['path']);
//		@unlink(APP_ROOT_PATH.$data['o_path']);
		
		$list =M("ShareImage")->where("id=".$id)->delete();
		if ($list!==false) {
			$Model = new Model();
			$img_array=$Model->query("select path,o_path,width,height,id,name from ".DB_PREFIX."share_image where share_id=".$tid);
			$count_img=count($img_array);
	
			$img_cache= serialize($img_array);			
			$Model->query("update ".DB_PREFIX."share set image_list = '".$img_cache."' ,images_count=".$count_img." where id = ".$tid);				
			
			save_log($info.l("DELETE_SUCCESS"),0);
			$this->ajaxReturn("","",1);
		}else{
			save_log($info.l("删除图片失败"),0);
			$this->error (l("DELETE_FAILED"),$ajax);
		}

	}
	
}
?>