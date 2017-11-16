<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
class FxUserAction extends CommonAction
{

    public function index()
    {
        $group_list = M("UserGroup")->findAll();
        $this->assign("group_list", $group_list);
        
        // 定义条件
        $map[DB_PREFIX . 'user.is_delete'] = 0;
        
        if (intval($_REQUEST['user_id']) > 0) {
            $map[DB_PREFIX . 'user.pid'] = intval($_REQUEST['user_id']);
        }
        
        if (intval($_REQUEST['id']) > 0) {
            $map[DB_PREFIX . 'user.id'] = intval($_REQUEST['id']);
        }
        
        if (intval($_REQUEST['group_id']) > 0) {
            $map[DB_PREFIX . 'user.group_id'] = intval($_REQUEST['group_id']);
        }
        
        if (strim($_REQUEST['user_name']) != '') {
            $map[DB_PREFIX . 'user.user_name'] = array(
                'LIKE',
                strim('%'.$_REQUEST['user_name'].'%')
            );
        }
        if (strim($_REQUEST['email']) != '') {
            $map[DB_PREFIX . 'user.email'] = array(
                'LIKE',
                strim('%'.$_REQUEST['email'].'%')
            );
        }
        if (strim($_REQUEST['mobile']) != '') {
            $map[DB_PREFIX . 'user.mobile'] = array(
                'LIKE',
                strim('%'.$_REQUEST['mobile'].'%')
            );
        }
        
        if (strim($_REQUEST['remark_name']) != '') {
            $map[DB_PREFIX . 'user.remark_name'] = array(
                'LIKE',
                strim('%'.$_REQUEST['remark_name'].'%')
            );
        }
        
        if (strim($_REQUEST['pid_name']) != '') {
            $pid = M("User")->where("user_name='" . strim($_REQUEST['pid_name']) . "'")->getField("id");
            $map[DB_PREFIX . 'user.pid'] = $pid;
        }
        
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        
        $map['is_robot'] = 0;
        
        // 整体给我的分销金额，1级给我的分销金额，2级给我的分销金额，3级给我的分销金额
        $user_id = intval($_REQUEST['user_id']);
        if ($user_id) {
            $user_model = M('User');
            $user_info = $user_model->where('id='.$user_id)->find();
            $money_data['fx_total_money']   = $user_info['fx_total_money']; // 分销的累积营业额
            $money_data['fx_total_balance'] = $user_info['fx_total_balance']; // 分销的累积利润
            
            
            $m = new Model();
             
            
            // 1级分销金额信息
            $one_info = $GLOBALS['db']->getRow("select  round(ifnull(sum(money),0), 2)  total_money,  round(ifnull(sum(order_money),0), 2) total_order_money from ".DB_PREFIX."fx_user_reward where fx_level=1 and pid = ".$user_id );
            //  我的2级金额信息
            $two_info = $GLOBALS['db']->getRow("select  round(ifnull(sum(money),0), 2) total_money,   round(ifnull(sum(order_money),0), 2) total_order_money  from ".DB_PREFIX."fx_user_reward where fx_level=2 and pid in ({$user_id}) " );
            //  我的3级金额信息
            $three_info = $GLOBALS['db']->getRow("select round(ifnull(sum(money),0), 2) total_money,  round(ifnull(sum(order_money),0), 2) total_order_money from ".DB_PREFIX."fx_user_reward where fx_level=3 and pid in ({$user_id})" );
            
            
            $money_data['three_info'] = $three_info;
            $money_data['two_info'] = $two_info;
            $money_data['one_info'] = $one_info;
            
            $this->assign('user_name', $user_info['user_name']);
            $this->assign('money_data', $money_data);
            
            
        }
       
        
        
        
        $name = $this->getActionName();
        $model = D("User");
        if (! empty($model)) {
            $this->_list($model, $map);
        }
        $this->display();
    }
    
    public function get_scan(){
        $user_id = intval($_REQUEST['user_id']);
        include_once APP_ROOT_PATH."system/model/weixin_jssdk.php";
        getQrCode($user_id);
    }
    
    public function edit_remark(){
        $user_id = intval($_REQUEST['id']);
        $remark_name = M("User")->where("id=" . $user_id)->getField("remark_name");
       
        $this->assign("remark_name", $remark_name);
        $this->assign("id", $user_id);
        $this->display();
    } 
    
    public function update_remark()
    {
        $user_id = intval($_REQUEST['id']);
        $remark_name = strim($_REQUEST['remark_name']);
     
        if ($user_id > 0 ) {
            M("User")->where("id=" . $user_id)->setField("remark_name", $remark_name);
            $this->success("设置成功", 0); 
        } else {
            $this->error("用户不存在，无法设置。", 0); 
        }
    }

    public function edit_referrer()
    {
        $user_id = intval($_REQUEST['id']);
        $user_pid = M("User")->where("id=" . $user_id)->getField("pid");
        $referrer_name = M("User")->where("id=" . $user_pid)->getField("user_name");
        $this->assign("referrer_name", $referrer_name);
        $this->assign("id", $user_id);
        $this->display();
    }

    public function update_referrer()
    {
        $user_id = intval($_REQUEST['id']);
        $referrer = strim($_REQUEST['referrer']);
        
        $pid = M("User")->where("user_name='" . $referrer . "'")->getField("id");
        if ($pid == $user_id) {
            $this->error("推荐人不能是自己", 0);
        }
        if ($pid > 0 || $referrer == "") {
            M("User")->where("id=" . $user_id)->setField("pid", $pid);
            save_log($user_id . "号会员更改推荐人为" . $referrer, 1);
            $this->success("设置成功", 0);
        } else {
            $this->error("推荐人不存在", 0);
        }
    }

    public function foreverdelete()
    {
        // 彻底删除指定记录
        $ajax = intval($_REQUEST['ajax']);
        $id = $_REQUEST['id'];
        
        if (isset($id)) {
            $condition = array(
                'id' => array(
                    'in',
                    explode(',', $id)
                )
            );
            $rel_data = M("UserDeal")->where($condition)->findAll();
            foreach ($rel_data as $data) {
                $info[] = $data['user_id'];
            }
            if ($info)
                $info = implode(",", $info);
            $ids = explode(',', $id);
            foreach ($ids as $uid) {
                $GLOBALS['db']->query("delete from " . DB_PREFIX . "user_deal where id = " . $uid);
            }
            save_log($info . l("FOREVER_DELETE_SUCCESS"), 1);
            
            $this->success(l("FOREVER_DELETE_SUCCESS"), $ajax);
        } else {
            $this->error(l("INVALID_OPERATION"), $ajax);
        }
    }
    
    // 会员分销状态设置
    public function set_effect()
    {
        $id = intval($_REQUEST['id']);
        $ajax = intval($_REQUEST['ajax']);
        $info = M(MODULE_NAME)->where("id=" . $id)->getField("user_name");
        $c_is_effect = M("User")->where("id=" . $id)->getField("is_fx"); // 当前状态
        $n_is_effect = $c_is_effect == 0 ? 1 : 0; // 需设置的状态
        M("User")->where("id=" . $id)->setField("is_fx", $n_is_effect);
        save_log($info . l("SET_EFFECT_" . $n_is_effect), 1);
        $this->ajaxReturn($n_is_effect, l("SET_EFFECT_" . $n_is_effect), 1);
    }

    /**
     * 添加
     */
    public function save()
    {
        
        // 彻底删除指定记录
        $ajax = intval($_REQUEST['ajax']);
        $user_id = intval($_REQUEST['user_id']);
        $ids = strim($_REQUEST['check_ids']);
        
        if (isset($ids)) {
            $condition = "user_id=" . $user_id . " and deal_id in(" . $ids . ") ";
            $data = M("UserDeal")->where($condition)->findAll();
            if ($data) {
                $result['status'] = 0;
                $result['info'] = "已经添加的商品不能再添加";
                ajax_return($result);
            }
            
            $deal_ids = explode(',', $ids);
            foreach ($deal_ids as $deal_id) {
                $datas['user_id'] = $user_id;
                $datas['add_time'] = NOW_TIME;
                $datas['deal_id'] = $deal_id;
                $datas['is_effect'] = 1;
                $datas['type'] = 1;
                $list = M("UserDeal")->add($datas);
            }
            save_log($user_id . "号用户添加" . $ids . "号商品成功", 1);
            
            $this->success("添加成功", $ajax);
        } else {
            $this->error(l("INVALID_OPERATION"), $ajax);
        }
        
        // if ($ajax){
        // ajax_return($result);
        // }else{
        // $this->assign("jumpUrl",$result['jump']);
        // $this->success(L("UPDATE_SUCCESS"));
        // }
    }

    public function money_log()
    {
        $model = M("FxUserReward");
        $map['pid'] = intval($_REQUEST['user_id']);
        if (! empty($model)) {
            $this->_list($model, $map);
        }
        $user = M('User');
        $user_name = $user->where("id={$map['pid']}")->find();
        
        $this->assign('user_name', $user_name['user_name']);
        $this->display();
    }

    public function log_delete()
    {
        $ajax = intval($_REQUEST['ajax']);
        $id = $_REQUEST['id'];
        
        if (isset($id)) {
            $condition = array(
                'id' => array(
                    'in',
                    explode(',', $id)
                )
            );
            $rel_data = M("FxUserReward")->where($condition)->findAll();
            foreach ($rel_data as $data) {
                $info[] = $data['pid'] . "号会员" . $data['id'] . "号分销资金日志删除成功，";
            }
            if ($info)
                $info = implode(",", $info);
            $ids = explode(',', $id);
            foreach ($ids as $sid) {
                $GLOBALS['db']->query("delete from " . DB_PREFIX . "fx_user_reward where id = " . $sid);
            }
            save_log($info . l("FOREVER_DELETE_SUCCESS"), 1);
            
            $this->success(l("FOREVER_DELETE_SUCCESS"), $ajax);
        } else {
            $this->error(l("INVALID_OPERATION"), $ajax);
        }
    }
    
    public function close_fx_all(){
        $data['status'] = 0;
        $GLOBALS['db']->query("update " . DB_PREFIX . "user set is_open_scan=0");
        $status = $GLOBALS['db']->affected_rows();
        if ($status != -1) {
            $data['status'] = 1;
            $data['info'] = '设置成功';
        }
        
        ajax_return($data);
        
    }
    public function open_fx_all(){
        $data['status'] = 0;
        $GLOBALS['db']->query("update " . DB_PREFIX . "user set is_open_scan=1");
        $status = $GLOBALS['db']->affected_rows();
        if ($status != -1) {
            $data['status'] = 1;
            $data['info'] = '设置成功';
        }
        ajax_return($data);
    }
}
?>