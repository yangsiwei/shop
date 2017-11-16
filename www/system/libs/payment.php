<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

/**
 * 
 * 支付接口类型
 * online_pay: 0:web线下支付 1:web在线支付 2:仅wap支付 3:仅app支付 4:兼容wap和app 5:app与wap的线下支付
 * 
 * 
 *
 */
interface payment{	
	/**
	 * online_pay 支付方式：1：在线支付；0：线下支付  2:仅wap支付 3:仅app支付 4:兼容wap和app
	 * 获取支付代码或提示信息
	 * @param integer $payment_log_id  支付单号ID
	 * web返回可提交的表单按钮
	 * 
	 * wap返回数组
	 * Array
	 * (
	 * 	"pay_info"=> string 支付的信息（商品名称等）
	 *  "pay_action" => 支付跳转换链接
	 *  "payment_name" => 支付接口显示的名称
	 *  "pay_money" => 应付的余额
	 *  "class_name" => 支付接口类名
	 * )
	 * 
	 * APP接口规范
	 * Array
	 * (
	 * 	"pay_info"=> string 支付的信息（商品名称等）
	 *  "payment_name" => 支付接口显示的名称
	 *  "pay_money" => 应付的余额
	 *  "class_name" => 支付接口类名
	 *  "config" => array() 根据每个sdk要求定义
	 * )
	 */
	function get_payment_code($payment_notice_id);
	
	//响应支付
	function response($request);
	
	//响应通知
	function notify($request);
	
	//获取接口的显示
	function get_display_code();	
}
?>