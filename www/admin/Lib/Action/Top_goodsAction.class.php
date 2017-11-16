<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jobinlin
// +----------------------------------------------------------------------
class Top_goodsAction extends CommonEnhanceAction{
    public function index()
    {
        $duobao = M('duobao');
        $res1 = $duobao->field("name,origin_price,max_buy")->where(array('place_id'=>1))->find();
        $res2 = $duobao->field("name,origin_price,max_buy")->where(array('place_id'=>2))->find();
        $res3 = $duobao->field("name,origin_price,max_buy")->where(array('place_id'=>3))->find();
        $res4 = $duobao->field("name,origin_price,max_buy")->where(array('place_id'=>4))->find();
        $res5 = $duobao->field("name,origin_price,max_buy")->where(array('place_id'=>5))->find();
        $res6 = $duobao->field("name,origin_price,max_buy")->where(array('place_id'=>6))->find();
        $this->assign("res1",$res1);
        $this->assign("res2",$res2);
        $this->assign("res3",$res3);
        $this->assign("res4",$res4);
        $this->assign("res5",$res5);
        $this->assign("res6",$res6);
        $this->assign("title_name","首页置顶商品");
        $this->display ();
    }

    public function choose(){
        $id = $_REQUEST['id'];
        $this->assign("place_id",$id);
        $this->assign("title_name","首页置顶商品-编辑");
        $this->display ();
    }

    public function delete(){
        $place_id = $_REQUEST['id'];
        $duobao = M('duobao');
        $res = $duobao->where(array('place_id'=>$place_id))->delete();
        if($res){
            $duobao->success('删除成功');
        }else{
            $duobao->error('删除失败');
        }
    }

    public function getGoodsName(){
        $name = $_POST['name'];
        $good = M('deal');
        $where['name'] = array('like',"%{$name}%");
        $res = $good->field("id,name")->where($where)->select();
        if($res){
            $data['status'] = 1;
            $data['info'] = $res;
        }else{
            $data['status'] = 0;
            $data['info'] = '';
        }
        ajax_return($data);
    }

    public function insert(){
        $place_id = $_REQUEST['place_id'];
        $duobao = M('duobao');
        $res = $duobao->where(array('place_id'=>$place_id))->find();
        if($res){
            $duobao->error('位置'.$place_id.'夺宝计划已存在');
        }

        if(intval($_REQUEST['max_schedule']) <=0 ){
            $this->error('最大举办期数需要大于0');
        }
        if(intval($_REQUEST['user_max_buy']) > intval($_REQUEST['max_buy'])){
            $this->error('限购次数不能大于总需人次');
        }

        if($_REQUEST['buy_type'] == 10 &&$_REQUEST['min_buy']==10&&intval($_REQUEST['user_max_buy'])%10 != 0){
            $this->error('限购次数应为10的倍数');
        }

        if(intval($_REQUEST['robot_is_db'])==1)
        {

            if(intval($_REQUEST['robot_type'])==0)
            {
                //计时
                if(intval($_REQUEST['robot_end_time']) < 5 ){
                    $this->error('夺宝时长不能低于5分钟');
                }
                $_REQUEST['robot_buy_min_time'] = 0;
                $_REQUEST['robot_buy_max_time'] = 0;
                $_REQUEST['robot_buy_min'] = 0;
                $_REQUEST['robot_buy_max'] = 0;
            }
            else
            {
                //按频率

                if(intval($_REQUEST['robot_buy_min_time'])<=0)
                {
                    $this->error('最小下单间隔不能小于0');
                }
                if(intval($_REQUEST['robot_buy_min'])<=0)
                {
                    $this->error('最小下单量不能小于0');
                }

                if(intval($_REQUEST['robot_buy_min_time'])>intval($_REQUEST['robot_buy_max_time']))
                {
                    $this->error('最小下单间隔不能大于最大间隔');
                }
                if(intval($_REQUEST['robot_buy_min'])>intval($_REQUEST['robot_buy_max']))
                {
                    $this->error('最小下单量不能大于最大量');
                }

                $_REQUEST['robot_end_time'] = 0;
            }

        }
        else
        {
            $_REQUEST['robot_is_lottery'] = 0;
            $_REQUEST['robot_type'] = 0;
            $_REQUEST['robot_buy_min_time'] = 0;
            $_REQUEST['robot_buy_max_time'] = 0;
            $_REQUEST['robot_buy_min'] = 0;
            $_REQUEST['robot_buy_max'] = 0;
            $_REQUEST['robot_end_time'] = 0;
        }


        if(intval($_REQUEST['robot_is_db'])==1&&M("User")->where("is_robot=1")->count()<5)
        {
            $this->error('机器人数量低于5个，不能使用机器人功能');
        }

        $_REQUEST['total_buy_price'] = round($_REQUEST['total_buy_price'], 2);
        $_REQUEST['is_total_buy']    = intval( $_REQUEST['is_total_buy'] );
        if ( $_REQUEST['is_total_buy'] == 1 && $_REQUEST['total_buy_price'] <= 0 ) {
            $this->error('直购价格必须大于0');
        }


        //处理buy_type
        if($_REQUEST['buy_type']==100)
        {
            $_REQUEST['unit_price'] = 100;
            $_REQUEST['min_buy'] = 1;
        }
        elseif($_REQUEST['buy_type']==10)
        {
            $_REQUEST['unit_price'] = 10;
            $_REQUEST['min_buy'] = 1;
        }
        else
        {
            $_REQUEST['unit_price'] = 1;
            $_REQUEST['min_buy'] = 1;
        }
        unset($_REQUEST['buy_type']);

        if($_REQUEST['spectial_area']==2){
            $_REQUEST['is_pk']=1;
            $_REQUEST['is_number_choose']=0;
            $_REQUEST['is_coupons']=0;
            $_REQUEST['is_recomend']=0;
            $_REQUEST['pk_min_number']=intval($_REQUEST['pk_min_number']);
            if(intval($_REQUEST['pk_min_number'])<1){
                $this->error("最小购买人数不得小于1");
            }else if(intval($_REQUEST['pk_min_number'])>intval($_REQUEST['max_buy'])){
                $this->error("最小购买人数不得大于总人次");
            }
        }else if($_REQUEST['spectial_area']==1){
            $_REQUEST['user_max_buy']=0;
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_recomend']=0;
            $_REQUEST['is_coupons']=0;
            $_REQUEST['is_number_choose']=1;
        }else if($_REQUEST['spectial_area']==3){
            $_REQUEST['is_coupons']=1;
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_recomend']=0;
            $_REQUEST['is_number_choose']=0;
        }else{
            $_REQUEST['is_pk']=0;
            $_REQUEST['is_coupons']=0;
            $_REQUEST['is_number_choose']=0;
        }
        $deal_id = intval($_REQUEST['deal_id']);
        // 获取商品名称
        $deal_model = M('Deal');
        $result_deal = $deal_model->field( array( 'id'=>'deal_id', 'name','cate_id','description','brief','icon','brand_id','origin_price' ) )->where("id=".$deal_id)->find();
        $_REQUEST['max_buy'] = $_REQUEST['max_buy'] * $_REQUEST['min_buy'];

        $data = array_merge($result_deal, $_REQUEST);

        //开始处理图片
        $gallery_model = M('DealGallery');
        $gallery_result = $gallery_model->field('img')->where('deal_id='.$deal_id)->select();
        foreach ($gallery_result as $val){
            $img_list[] = $val['img'];
        }
        $data['deal_gallery'] = serialize($img_list);
        $is_pk=$_REQUEST['is_pk'];
        $model = M('duobao');
        $id = $model->data($data)->add();
        if ($id) {
            if(!$is_pk){
                require_once APP_ROOT_PATH."system/model/duobao.php";
                $duobao_item = duobao::new_duobao($id);
            }
            $this->success('添加成功');
        }
        else{
            $this->error('添加失败');
        }

    }

    public function getDealInfo(){
        $deal_id = $_POST['deal_id'];
        $deal = M('deal');
        $info = $deal->where(array('id'=>$deal_id))->find();
        if($info){
            $data['status'] = 1;
            $data['info'] = $info;
        }else{
            $data['status'] = 0;
            $data['info'] = '';
        }
        ajax_return($data);
    }
}