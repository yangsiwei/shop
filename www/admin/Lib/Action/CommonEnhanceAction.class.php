<?php
class CommonEnhanceAction extends CommonAction{
    /**
     +----------------------------------------------------------
     * 根据表单生成查询条件
     * 进行列表过滤
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param Model $model 数据对象
     * @param HashMap $map 过滤条件
     * @param string $sortBy 排序
     * @param boolean $asc 是否正序
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    protected function _list($model, $map, $sortBy = '', $asc = false) {
        //排序字段 默认为主键名
        if (isset ( $_REQUEST ['_order'] )) {
            $order = $_REQUEST ['_order'];
        } else {
            $order = ! empty ( $sortBy ) ? $sortBy : $this->getActionName().'.'.$model->getPk ();
        }
        //排序方式默认按照倒序排列
        //接受 sost参数 0 表示倒序 非0都 表示正序
        if (isset ( $_REQUEST ['_sort'] )) {
            $sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
        } else {
            $sort = $asc ? 'asc' : 'desc';
        }
        //取得满足条件的记录数
        $count = $model->where ( $map )->count ();
       // echo($model->getLastSql());
        if ($count > 0 ) {
            //创建分页对象
            if (! empty ( $_REQUEST ['listRows'] )) {
                $listRows = $_REQUEST ['listRows'];
            } else {
                $listRows = '30';
            }
            $p = new Page ( $count, $listRows );
            //分页查询数据
    
            $voList = $model->where($map)->order( $order . " " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll ();

         
            //echo($model->getLastSql());exit;
            //分页跳转的时候保证查询条件
            foreach ( $map as $key => $val ) {
                if (! is_array ( $val )) {
                    $p->parameter .= "$key=" . urlencode ( $val ) . "&";
                }
            }
            //分页显示
    
            $page = $p->show ();
            //列表排序显示
            $sortImg = $sort; //排序图标
            $sortAlt = $sort == 'desc' ? l("ASC_SORT") : l("DESC_SORT"); //排序提示
            $sort = $sort == 'desc' ? 1 : 0; //排序方式
            //模板赋值显示
            $this->assign ( 'list', $voList );
            $this->assign ( 'sort', $sort );
            $this->assign ( 'order', $order );
            $this->assign ( 'sortImg', $sortImg );
            $this->assign ( 'sortType', $sortAlt );
            $this->assign ( "page", $page);
            $this->assign ( "nowPage",$p->nowPage);
        }
        return;
    }
    
    /**
     * 生成查询条件, 支持视图模型
     * @see CommonAction::_search()
     * @author hhcycj
     */
    protected function _search($model) {
        // 获取所有表查询的字段
        
        $fields = array();
        foreach ($model->viewFields as $key=>$value){
            foreach ($value as $k=>$v){
                if ($k !== '_on') {
                    if (is_numeric($k)) {
                        $fields[] = $v;
                    }else{
                        // 如果下标不为数字，说明有别名，多个表的字段会重名，所以要给字段加上表别名，查询才不会出错
                        $fields[$key.'.'.$k] = $v;
                    }
                }
            }
        }
         
        // 根据字段设置查询条件
        $map = array ();
        foreach ($fields as $key => $val ) {
            if (isset ( $_REQUEST [$val] ) && $_REQUEST [$val] != '') {
                $_REQUEST [$val] = trim($_REQUEST[$val]);
                $key_str = array_search($val, $fields);
                 
                // 设置id条件
                if ($fields[$key_str] == 'id') {
                    $map [$this->getActionName().'.id'] = $_REQUEST [$val];
                    continue;
                }
                 
    
                if (is_numeric($key_str)) {
                    $map [$val] = $_REQUEST [$val];
                }else {
                    // 如果下标不为数字，查询的条件字段加上表别名
                    $map[$key_str] = array('like','%'.$_REQUEST[$val].'%');
                    
                }
            }
        }

        
        $timezone = intval(C('TIME_ZONE'));
        $time = $timezone * 3600;
        // 时间条件
        $begin_time = strtotime(strim($_REQUEST['begin_time'])) ? strtotime(strim($_REQUEST['begin_time'])) - $time : 0;
        $end_time   = strtotime(strim($_REQUEST['end_time']))   ? strtotime(strim($_REQUEST['end_time'])) - $time : 0;
        
        if ($begin_time && $end_time) {
            $map[ $this->getActionName().'.create_time']  = array('between', "{$begin_time},{$end_time}");
    
        }else if($begin_time){
            $map[ $this->getActionName().'.create_time']  = array('egt', $begin_time);
        }else if($end_time){
            $map[$this->getActionName().'.create_time']  = array('elt', $end_time);
        }
        return $map;
    }
    
    public function delete() {
        $ajax = intval($_REQUEST['ajax']);
        $id = $_REQUEST ['id'];
        if (isset ( $id )) {
            $condition = array ('id' => array ('in', explode ( ',', $id ) ) );
            $model = M($this->getActionName());
            $list = $model->where ( $condition )->save(array('is_delete'=>1));
            if ($list!==false) {
                save_log('删除成功',1);
                $this->success ('删除成功',$ajax);
            } else {
                save_log('删除失败',0);
                $this->error ('删除失败',$ajax);
            }
        } else {
            $this->error ('删除失败',$ajax);
        }
    }
    
    
    public function foreverdelete() {
        //彻底删除指定记录
        $ajax = intval($_REQUEST['ajax']);
        $id = $_REQUEST ['id'];
        if (isset ( $id )) {
            $condition = array ('id' => array ('in', explode ( ',', $id ) ) );
            $list = M(MODULE_NAME)->where ( $condition )->delete();
            if ($list!==false) {
                save_log(l("FOREVER_DELETE_SUCCESS"),1);
                $this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
            } else {
                save_log(l("FOREVER_DELETE_FAILED"),0);
                $this->error (l("FOREVER_DELETE_FAILED"),$ajax);
            }
        } else {
            $this->error (l("INVALID_OPERATION"),$ajax);
        }
    }
    
    
}