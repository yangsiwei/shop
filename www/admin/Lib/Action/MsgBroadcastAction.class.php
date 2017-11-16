<?php

/**
 * Class BroadcastAction
 * 安卓和ios的推送消息
 * 可选择类型进行推送
 */
class MsgBroadcastAction extends CommonAction
{
    public function index(){
        parent::index();
    }
    public function add()
    {
        $this->assign("default_end_time", to_date(NOW_TIME + 3600 * 24 * 7));
        $this->display();
    }

    public function edit()
    {
        $id = intval($_REQUEST ['id']);
        $condition['id'] = $id;
        $vo = M(MODULE_NAME)->where($condition)->find();
        //去用户名
        if($vo['push_type']==1){
            $user_condition['id']=$vo['acceptor'];

            $user=M("user")->where($user_condition)->find();
            $vo['user_name']=$user['user_name'];
            $vo['dev_type']=$user['dev_type'];
            $vo['device_token']=$user['device_token'];
        }
        $vo['is_edit']=1;
        $vo['end_time'] = $vo['end_time'] != 0 ? to_date($vo['end_time']) : '';
        $this->assign('vo', $vo);
        $this->display();
    }

    public function insert()
    {
        B('FilterString');
//        $ajax = intval($_REQUEST['ajax']);
        $data = M(MODULE_NAME)->create();
        $end_time=$_REQUEST['end_time'];
        $data['end_time'] = strim($end_time) == '' ? 0 : to_timespan($end_time);
        $data['create_time'] = NOW_TIME;
        $data['is_read']=0;
        $data['is_delete']=0;
        $data['type']=-1;
//        $data['push_type']=$_REQUEST['push_type'];
//        $data['ios_data']="";
//        $data['android_data']="";
        $user_info=es_session::get(md5(conf("AUTH_KEY")));
        $data['pusher']= $user_info['adm_name'];

        //开始验证有效性
        $this->assign("jumpUrl", u(MODULE_NAME . "/add"));
        if (!check_empty($data['content'])) {
            $this->error(L("MSY_CONTENT_EMPTY_TIP"));
        }
        //检查单播数据
        if($data['push_type']==1&&!($data['ios_device_tokens']||$data['android_device_tokens'])){
            $this->error("该用户未登录过手机,无法进行推送");
        }
        // 更新数据
        $log_info = $data['title'];

        $list = M(MODULE_NAME)->add($data);
        if (false !== $list) {
            //成功提示
            save_log($log_info . L("INSERT_SUCCESS"), 1);
            $this->success(L("INSERT_SUCCESS"));
        } else {
            //错误提示
            save_log($log_info . L("INSERT_FAILED"), 0);
            $this->error(L("INSERT_FAILED"));
        }
    }

    public function update()
    {
        B('FilterString');
        $data = M(MODULE_NAME)->create();

        $data['end_time'] = strim($data['end_time']) == '' ? 0 : to_timespan($data['end_time']);

        $log_info = M(MODULE_NAME)->where("id=" . intval($data['id']))->getField("title");
        //开始验证有效性
        $this->assign("jumpUrl", u(MODULE_NAME . "/edit", array("id" => $data['id'])));

        if (!check_empty($data['content'])) {
            $this->error(L("MSY_CONTENT_EMPTY_TIP"));
        }
        //检查单播数据
        if($data['push_type']==1&&!($data['ios_device_tokens']||$data['android_device_tokens'])){
            $this->error("该用户未登录过手机,无法进行推送");
        }
        //插入或者更新数据
        if(!$data['id']){
            $data['create_time'] = NOW_TIME;
            $data['is_read']=0;
            $data['is_delete']=0;
            $data['type']=-1;
            $list=M(MODULE_NAME)->add($data);
            if (false !== $list) {
                //成功提示
                save_log($log_info . L("INSERT_SUCCESS"), 1);
                $this->success(L("INSERT_SUCCESS"));
            } else {
                //错误提示
                save_log($log_info . L("INSERT_FAILED"), 0);
                $this->error(L("INSERT_FAILED"));
            }
        }else{
            $list = M(MODULE_NAME)->save($data);
            if (false !== $list) {
                //成功提示
                save_log($log_info . L("UPDATE_SUCCESS"), 1);
                $this->success(L("UPDATE_SUCCESS"));

            } else {
                //错误提示
                save_log($log_info . L("UPDATE_FAILED"), 0);
                $this->error(L("UPDATE_FAILED"), 0, $log_info . L("UPDATE_FAILED"));
            }
        }

    }

    public function foreverdelete()
    {
        //彻底删除指定记录
        $ajax = intval($_REQUEST['ajax']);
        $id = $_REQUEST ['id'];
        if (isset ($id)) {
            $condition = array('id' => array('in', explode(',', $id)));

            $rel_data = M(MODULE_NAME)->where($condition)->findAll();
            foreach ($rel_data as $data) {
                $info[] = $data['id'];
            }
            if ($info) $info = implode(",", $info);
            $list = M(MODULE_NAME)->where($condition)->delete();
            if ($list !== false) {
                M("MsgBroadcast")->where(array('id' => array('in', explode(',', $id))))->delete();
                save_log($info . l("FOREVER_DELETE_SUCCESS"), 1);
                $this->success(l("FOREVER_DELETE_SUCCESS"), $ajax);
            } else {
                save_log($info . l("FOREVER_DELETE_FAILED"), 0);
                $this->error(l("FOREVER_DELETE_FAILED"), $ajax);
            }
        } else {
            $this->error(l("INVALID_OPERATION"), $ajax);
        }
    }

    public function view()
    {
        $id = intval($_REQUEST ['id']);
        $condition['id'] = $id;
        $vo = M(MODULE_NAME)->where($condition)->find();
        //去用户名
        if($vo['push_type']==1){
            $user_condition['id']=$vo['acceptor'];

            $user=M("user")->where($user_condition)->find();
            $vo['user_name']=$user['user_name'];
            $vo['dev_type']=$user['dev_type'];
            $vo['device_token']=$user['device_token'];
        }
        $vo['is_edit']=0;
        $vo['id']="";
        $vo['end_time'] = $vo['end_time'] != 0 ? to_date($vo['end_time']) : '';
        $this->assign('vo', $vo);
        $this->display("edit");
//        $id = intval($_REQUEST ['id']);
//        $condition['id'] = $id;
//        $vo = M(MODULE_NAME)->where($condition)->find();
////        $vo = load_msg($vo['type'], $vo);
//
//        $this->assign ( 'vo', $vo );
//        $this->display ();
    }

    /**
     * 执行推送推送计划
     */
    public function appbroadcast(){
        $type=$_REQUEST['type'];
        $id=$_REQUEST['id'];
        $model=M(MODULE_NAME);
        $data=array();
        $broadData=$model->where("id=$id")->find();
        //检查传过来的id
        if(!$broadData){
            $this->error("所推送的消息不存在",1);
        }
        //设置广播类型
        switch($_REQUEST['push_type']){
            case '1':
                $broad=$this->get_push_model('Unit');
                break;
            case '2':
                $broad=$this->get_push_model('Customized');
                break;
            case '3':
                $broad=$this->get_push_model('Group');
                break;
            case '4':
                $broad=$this->get_push_model('Broad');
                break;
            case '5':
                $broad=$this->get_push_model('File');
                break;
            default: $broad=$this->get_push_model('Broad');
        }
       //检查传过来的type
        if($type==0){
            $android_data=$broad->exec_android($broadData);
            $this->check_status($android_data);
            $data['android_data']=$android_data['info'];
            $data['type']=1;
            $model->where("id=$id")->save($data);
            $ios_data=$broad->exec_ios($broadData);
            $this->check_status($ios_data);
            $data['ios_data']=$ios_data['info'];
            $data['type']=0;
            $data['is_read']=1;
            $model->where("id=$id")->save($data);
        }
        else if($type==1){
            $android_data=$broad->exec_android($broadData);
            $this->check_status($android_data);
            $data['android_data']=$android_data['info'];
            $data['type']=1;
            $data['is_read']=1;
            $model->where("id=$id")->save($data);
        }
        else if($type==2){
            $ios_data=$broad->exec_ios($broadData);
            $this->check_status($ios_data);
            $data['ios_data']=$ios_data['info'];
            $data['type']=2;
            $data['is_read']=1;
            $model->where("id=$id")->save($data);
        }else{
            $this->error("推送的类型不存在",1);
        }
        $this->success("推送消息成功",1);
    }

    /**
     * 检查接口返回状态
     * @param $data
     */
    public function check_status($data){
       if(!$data||!$data['status'])$this->error($data['info'],1);
   }

    /**
     * 获取推送消息的模型
     * @param $model
     * @return mixed
     */
    public function get_push_model($model){
       try{
           require_once(APP_ROOT_PATH. '/system/model/Msg'.$model.'cast.php');
           $model="Msg".$model."cast";
       }catch(Exception $e){
           $this->error(print_r($e),1);
       }
       return new $model();
   }
    public function get_user_info(){
        $name = strim($_REQUEST['user_name']);
        $deal_model = M('user');
        $map[ 'user_name'] = array('like','%'.$name.'%');
        $deal_result = $deal_model->where($map)->select();
        $option = '<option value="">==请选择用户==</option>';
        foreach ($deal_result as $key=>$val){
            $option .= '<option device_token="'.$val['device_token'].'" dev_type="'.$val['dev_type'].'" value="'.$val['id'].'">'.$val['user_name'].'</option><';
        }

        $this->ajaxReturn($option);


    }
}
