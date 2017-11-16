<?php

/**
 * 兼容版本菜单配置
 * pc 端有更新到APP 功能，用于兼容低版本。
 * 针对有增加新的菜单节点进行配置
 * 
 * 命名规则
 * 菜单-模块-节点
 * 如：menu_user_withdraw  菜单-用户中心-提现
 */

//用户中心提现功能
$root['menu_user_withdraw'] = 1;
//用户中心充值功能
$root['menu_user_charge'] = 1;

