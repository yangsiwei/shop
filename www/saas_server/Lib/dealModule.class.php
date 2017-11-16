<?php
/**
 * 商品接口
 * @author jobinlin
 *
 */
class dealModule{

    /**
     * 添加商品接口
     * 输入：序列化的商品数组
     *  {
     *      name: string 商品名称
     *      cate_id: int 分类ID (通过一元夺宝的分类接口获取对应)
     *      description: string 描述详情 (图片地址必须携带HTTP://)
     *      origin_price: floot 原价
     *      current_price: floot 当前价格
     *      brief: string 商品简介  --[非必填]
     *      icon: string (图片地址必须携带HTTP://)
     *      brand_id: int (通过一元夺宝品牌接口获取对应)
     *  }
     * 输出：
     * array(
     *   "status"=>1,
     *   "info"=>"添加商品成功!",
     *   "deal_id"=>1,
     * 
     * )
     * 
     */
    function add_deal(){
       
        
    }
}