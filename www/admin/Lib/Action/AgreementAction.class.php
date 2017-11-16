<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class AgreementAction extends CommonAction{
    public function index()
    {
        parent::index();
    }
    public function add()
    {
        


        $this->display();
    }
    public function edit() {        
        $id = intval($_REQUEST ['id']);
        $condition['id'] = $id;     
        $vo = M(MODULE_NAME)->where($condition)->find();
        $this->assign ( 'vo', $vo );
        $this->display ();
    }
    
    
    public function foreverdelete() {
        //彻底删除指定记录
        $ajax = intval($_REQUEST['ajax']);
        $id = $_REQUEST ['id'];
        if (isset ( $id )) {
                $condition = array ('id' => array ('in', explode ( ',', $id ) ) );
                $rel_data = M(MODULE_NAME)->where($condition)->findAll();               
                foreach($rel_data as $data)
                {
                    $info[] = $data['smtp_server']; 
                }
                if($info) $info = implode(",",$info);
                $list = M(MODULE_NAME)->where ( $condition )->delete(); 
        
                if ($list!==false) {
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
    
    public function insert() {
        B('FilterString');
        $ajax = intval($_REQUEST['ajax']);
        $model = D($this->getActionName());  //  实例化 User 对象
        //  根据表单提交的 POST 数据创建数据对象
        $data = $model->create ();

        //开始验证有效性
        $this->assign("jumpUrl",u(MODULE_NAME."/add"));
        if(!check_empty($data['agreement']))
        {
            $this->error(L("TOPIC_CONTENT_EMPTY_TIP"));
        }
        //更新数据
        $log_info = $data['smtp_server'];
        $list=M(MODULE_NAME)->add($data);
        if (false !== $list) {
            //成功提示
            save_log($log_info.L("INSERT_SUCCESS"),1);
            $this->success(L("INSERT_SUCCESS"));
        } else {
            //错误提示
            save_log($log_info.L("INSERT_FAILED"),0);
            $this->error(L("INSERT_FAILED"));
        }
    }   
    
    public function update() {
        B('FilterString');
        $data = M(MODULE_NAME)->create ();

        $log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("mail_server");
        //开始验证有效性
        $this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));

        if(!check_empty($data['agreement']))
        {
            $this->error(L("TOPIC_CONTENT_EMPTY_TIP"));
        }
        // 更新数据
        $list=M(MODULE_NAME)->save ($data);
        if (false !== $list) {
            //成功提示
            save_log($log_info.L("UPDATE_SUCCESS"),1);
            $this->success(L("UPDATE_SUCCESS"));
        } else {
            //错误提示
            save_log($log_info.L("UPDATE_FAILED"),0);
            $this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
        }
    }

    public function set_effect()
    {
        $id = intval($_REQUEST['id']);
        $ajax = intval($_REQUEST['ajax']);
        $info = M(MODULE_NAME)->where("id=".$id)->getField("mail_server");
        $c_is_effect = M(MODULE_NAME)->where("id=".$id)->getField("is_effect");  //当前状态
        $n_is_effect = $c_is_effect == 0 ? 1 : 0; //需设置的状态
        M(MODULE_NAME)->where("id=".$id)->setField("is_effect",$n_is_effect);   
        save_log($info.l("SET_EFFECT_".$n_is_effect),1);
        $this->ajaxReturn($n_is_effect,l("SET_EFFECT_".$n_is_effect),1) ;   
    }
    
    public function send_demo()
    {
        $test_mail = $_REQUEST['test_mail'];
        require_once APP_ROOT_PATH."system/utils/es_mail.php";
        $mail = new mail_sender();

        $mail->AddAddress($test_mail);
        $mail->IsHTML(0);                 // 设置邮件格式为 HTML
        $mail->Subject = l("DEMO_MAIL");   // 标题
        $mail->Body = l("DEMO_MAIL");  // 内容    

        if(!$mail->Send())
        {
            $this->error(l("ERROR_INFO") . $mail->ErrorInfo,1); 
        }
        else
        {
            $this->success(l("SEND_SUCCESS"),1);
        }
    }
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		logger::write($id.'|'.$sort);
		$log_info = M("Adv")->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M(MODULE_NAME)->where("id=".$id)->setField("sort",$sort);
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
}
?>