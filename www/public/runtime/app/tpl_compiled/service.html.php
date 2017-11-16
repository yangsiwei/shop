<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>客服专用</title>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://www.gongjuji.net/Content/files/jquery.md5.js"></script>
    <style>
        .dologin_bgc{
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index:100;
        }
        .login_box{
            position: absolute;
            width: 40%;
            height: 40%;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px #000;
            top: 24%;
            left: 30%;
        }
        .input-group{
            position: relative;
            display: table;
            border-collapse: separate;
            width: 80%;
            left: 10%;
        }
        .login_btn{
            position: absolute;
            top: 67%;
            left: 30%;
            width: 40%;
        }
        .login_p{
            text-align: center;
            font-size: 24px;
            line-height: 33px;
            margin-top: 9%;
        }
        .logout{
            float: right;
        }
        .page_title{
            font-size: 20px;
            color: #fff;
            margin-left: 20%;
        }
    </style>
</head>
<body>
<?php if ($this->_var['dologin']): ?>
<div class="dologin_bgc">
    <div class="login_box">
        <p class="login_p">亲！请验证身份</p>
        <form action="" method="post">
            <div class="input-group">
                <span class="input-group-addon">账号</span>
                <input type="text" class="form-control login_input" aria-label="Amount (to the nearest dollar)" placeholder="请输入账号" name="user">
                <span class="input-group-addon"></span>
            </div>
            <div class="input-group" style="margin-top:20px;">
                <span class="input-group-addon">密码</span>
                <input type="password" class="form-control login_input" aria-label="Amount (to the nearest dollar)" placeholder="请输入密码" name="pwd">
                <span class="input-group-addon"></span>
            </div>
            <button type="submit" class="btn btn-primary login_btn">确认登录</button>
        </form>

    </div>
</div>
<?php endif; ?>
<!--//导航条-->
<nav class="navbar navbar-inverse">
    <div class="container">
        <button type="button" class="btn btn-default navbar-btn charge_btn">充值订单</button>
        <button type="button" class="btn btn-default navbar-btn withdraw_btn">提现订单</button>
        <button type="button" class="btn btn-default navbar-btn user_btn">会员列表</button>
         <button type="button" class="btn btn-default navbar-btn prize_btn">抽签列表</button>
        <?php if ($this->_var['user_info_list'] || $this->_var['user_log']): ?>
        <a href="<?php echo $this->_var['url_user_list']; ?>">返回列表</a>
        <?php endif; ?>
        <span class="page_title"><?php echo $this->_var['page_title']; ?></span>
        <button type="button" class="btn btn-warning navbar-btn logout">退出登录</button>
    </div>
</nav>
<div class="container">
    <!--//搜索区域-->
    <?php if ($this->_var['charge_order']): ?>
    <div class="row"  style="background:#19c4c1;">
        <form class="form-inline" action="" method="post">
            <div class="form-group">
                <label class="sr-only" for="exampleInputEmail3">单号</label>
                <input type="text" class="form-control" name="order_sn" id="exampleInputEmail3" placeholder="单号" value="<?php echo $this->_var['root']['order_sn']; ?>">
            </div>
            <div class="form-group">
                <label class="sr-only" for="exampleInputPassword3">用户</label>
                <input type="text" class="form-control" name="user_name" id="exampleInputPassword3" placeholder="用户" value="<?php echo $this->_var['root']['user_name']; ?>">
            </div>
            <div class="form-group">
                <label class="sr-only" for="exampleInputPassword3">用户</label>
                <input type="text" class="form-control" name="money" id="exampleInputPassword3" placeholder="金额" value="<?php echo $this->_var['root']['money']; ?>">
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="order_status" value="1" <?php if ($this->_var['root']['order_status'] == 1): ?>checked<?php endif; ?>> 红包已发
                </label>
            </div>
            <button type="submit" class="btn btn-primary">搜索</button>
            <button type="submit" class="btn btn-danger charge_clear">清空搜索条件</button>
        </form>
    </div>
    <div class="row" style="height:20px;"></div>
    <!--//充值订单列表-->
    <div class="row">
        <table class="table table-hover table-bordered">
            <tr>
                <th>id</th>
                <th>订单号</th>
                <th>会员名称</th>
                <th>充值金额</th>
                <th>充值时间</th>
                <th>是否已发红包</th>
                <th>操作</th>
            </tr>
            <?php $_from = $this->_var['charge_order']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
            <tr>
                <td class="charge_id"><?php echo $this->_var['v']['id']; ?></td>
                <td><?php echo $this->_var['v']['order_sn']; ?></td>
                <td><a href="http://www.gagoods.cn/index.php?ctl=service&act=user_list&id=<?php echo $this->_var['v']['user_id']; ?>"><?php echo $this->_var['v']['user_name']; ?></a> </td>
                <td <?php if ($this->_var['v']['pay_amount'] == 100): ?>style="color:red;"<?php endif; ?> ><?php echo $this->_var['v']['pay_amount']; ?></td>
                <td><?php echo $this->_var['v']['create_time']; ?></td>
                <td class="charge_red"><?php if ($this->_var['v']['order_status'] == 1): ?>已发<?php else: ?>未发<?php endif; ?></td>
                <td> <button type="submit" class="btn btn-success order_done" data-id="<?php echo $this->_var['v']['id']; ?>">发红包</button> </td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
    </div>
    <?php endif; ?>
    <?php if ($this->_var['withdraw_order']): ?>
    <div class="row" style="background:#19c4c1;">

    </div>
    <div class="row">
        <table class="table table-bordered table-hover">
            <tr class="success">
                <th>id</th>
                <th>提现金额</th>
                <th>提现款项</th>
                <th>是否复充</th>
                <th>申请日期</th>
                <th>会员名</th>
                <th>银行名称</th>
                <th>银行账号</th>
                <th>银行户名</th>
                <th>付款状态</th>
                <th>用户状态</th>
                <th>操作</th>
            </tr>
            <?php $_from = $this->_var['withdraw_order']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'w');if (count($_from)):
    foreach ($_from AS $this->_var['w']):
?>
            <tr class="with_tr">
                <td class="with_id"><?php echo $this->_var['w']['id']; ?></td>
                <td><?php if ($this->_var['w']['money'] == 100): ?> <span style="color:red;"><?php echo $this->_var['w']['money']; ?></span><?php else: ?><?php echo $this->_var['w']['money']; ?><?php endif; ?> 元</td>
                <td class="with_method"><?php echo $this->_var['w']['withdraw_method']; ?></td>
                <td><?php if ($this->_var['w']['repeat'] == 1): ?><p style="color:#E77F0D;">复充</p><?php else: ?><p style="color:#0DDCE7;">首冲</p><?php endif; ?></td>
                <td><?php echo $this->_var['w']['create_time']; ?></td>
                <td><a href="http://www.gagoods.cn/index.php?ctl=service&act=user_list&id=<?php echo $this->_var['w']['user_id']; ?>"><?php echo $this->_var['w']['user_name']; ?></a> </td>
                <td><?php echo $this->_var['w']['bank_name']; ?></td>
                <td><?php echo $this->_var['w']['bank_account']; ?></td>
                <td><?php echo $this->_var['w']['bank_user']; ?></td>
                <td class="with_sta"><?php if ($this->_var['w']['is_paid'] == 1): ?><p style="color:#0DDCE7;">已付款</p><?php else: ?><p style="color:#E77B0D;">未付款</p><?php endif; ?></td>
                <td class="with_sta"><?php if ($this->_var['w']['is_paid'] == 1): ?><p style="color:#0DDCE7;">已付款</p><?php else: ?><?php if ($this->_var['w']['is_delete'] == 1): ?><p style="color:#E70D0D;">用户已删除</p><?php else: ?><p style="color:#E77B0D;">待付款</p><?php endif; ?><?php endif; ?></td>
                <td width="12%"> <button type="button" class="btn btn-primary with_done">付款</button> <button type="button" class="btn btn-danger with_del">删除</button> </td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
    </div>
    <?php endif; ?>
<!--//抽奖列表-->
    <?php if ($this->_var['prize_order']): ?>
    <div class="row" style="background:#19c4c1;">
        <form class="form-inline" action="" method="post">
            <div class="form-group">
                <label class="sr-only" for="exampleInputEmail3">用户ID</label>
                <input type="text" class="form-control" name="order_sn" id="exampleInputEmail3" placeholder="用户ID" value="<?php echo $this->_var['root']['order_sn']; ?>">
            </div>
            <div class="form-group">
                <label class="sr-only" for="exampleInputPassword3">用户名称</label>
                <input type="text" class="form-control" name="user_name" id="exampleInputPassword3" placeholder="用户名称" value="<?php echo $this->_var['root']['user_name']; ?>">
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="order_status" value="1" <?php if ($this->_var['root']['order_status'] == 1): ?>checked<?php endif; ?>> 红包已发
                </label>
            </div>
            <button type="submit" class="btn btn-primary">搜索</button>
            <button type="submit" class="btn btn-danger charge_clear">清空搜索条件</button>
        </form>
    </div>
    <div class="row" style="height:20px;"></div>
    <!--//充值订单列表-->
    <div class="row">
        <table class="table table-hover table-bordered">
            <tr>
                <th>id</th>
                <th>会员名称</th>
                <th>充值金额</th>
                <th>充值时间</th>
                <th>是否已发红包</th>
                <th>操作</th>
            </tr>
            <?php $_from = $this->_var['prize_order']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
            <tr>
                <td class="prize_id"><?php echo $this->_var['v']['uid']; ?></td>
                <td><a href="http://www.gagoods.cn/index.php?ctl=service&act=user_list&id=<?php echo $this->_var['v']['uid']; ?>"><?php echo $this->_var['v']['user_name']; ?></a> </td>
                <td><?php echo $this->_var['v']['prize']; ?></td>
                <td><?php echo $this->_var['v']['addtime']; ?></td>
                <td class="prize_red"><?php if ($this->_var['v']['order_status'] == 1): ?>已发<?php else: ?>未发<?php endif; ?></td>
                <td> <button type="submit" class="btn btn-success prize_done" data-id="<?php echo $this->_var['v']['id']; ?>">发红包</button> </td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
    </div>
    <?php endif; ?>

<!--//会员列表-->
    <?php if ($this->_var['user']): ?>
    <div class="row" style="background:#19c4c1;">
        <form class="form-inline" action="" method="post">
            <div class="form-group">
                <label class="sr-only" for="exampleInputAmount"></label>
                <div class="input-group">
                    <div class="input-group-addon">用户名:</div>
                    <input type="text" class="form-control" name="user_name" id="exampleInputAmount" placeholder="name" value="<?php echo $this->_var['data']['user_name']; ?>">
                    <div class="input-group-addon">编号:</div>
                    <input type="text" class="form-control" name="id" id="exampleInputAmount" placeholder="id" value="<?php echo $this->_var['data']['id']; ?>">
                    <div class="input-group-addon">邮箱:</div>
                    <input type="text" class="form-control" name="email" id="exampleInputAmount" placeholder="E-mail" value="<?php echo $this->_var['data']['email']; ?>">
                    <div class="input-group-addon">手机:</div>
                    <input type="text" class="form-control" name="mobile" id="exampleInputAmount" placeholder="Tel-phone" value="<?php echo $this->_var['data']['mobile']; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">搜索</button>
            <div class="form-group">
                <label class="sr-only" for="exampleInputAmount"></label>
                <div class="input-group">
                    <div class="input-group-addon">推荐人:</div>
                    <input type="text" class="form-control" name="pid" id="exampleInputAmount" placeholder="推荐人用户名" value="<?php echo $this->_var['data']['pid']; ?>">
                    <div class="input-group-addon">余额:</div>
                    <input type="text" class="form-control" name="money" id="exampleInputAmount" placeholder="余额大于多少" value="<?php echo $this->_var['data']['money']; ?>">
                    <div class="input-group-addon">等级:</div>
                    <input type="text" class="form-control" name="fx_level" id="exampleInputAmount" placeholder="fx_level" value="<?php echo $this->_var['data']['fx_level']; ?>">
                    <div class="input-group-addon">预留:</div>
                    <input type="text" class="form-control" id="exampleInputAmount" placeholder="预留">
                </div>
            </div>
            <button type="submit" class="btn btn-danger user_clear">清空</button>
        </form>
    </div>
    <div class="row" style="margin-top:20px;">
        <table class="table table-hover  table-bordered">
            <tr class="success">
                <th>id</th>
                <th>用户名</th>
                <th>邮件</th>
                <th>手机号</th>
                <th>余额</th>
                <th>推广奖</th>
                <th>赠送金额</th>
                <th>管理奖</th>
                <th>推荐人</th>
                <th>等级</th>
                <th>经销商等级</th>
                <th>首冲时间</th>
                <th>操作</th>
            </tr>
            <?php $_from = $this->_var['user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
            <tr>
                <td><?php echo $this->_var['v']['id']; ?></td>
                <td><a href="http://www.gagoods.cn/index.php?ctl=service&act=user_list&id=<?php echo $this->_var['v']['id']; ?>"><?php echo $this->_var['v']['user_name']; ?></a> </td>
                <td><?php echo $this->_var['v']['email']; ?></td>
                <td><?php echo $this->_var['v']['mobile']; ?></td>
                <td><?php echo $this->_var['v']['money']; ?></td>
                <td><?php echo $this->_var['v']['fx_money']; ?></td>
                <td><?php echo $this->_var['v']['can_use_give_money']; ?></td>
                <td><?php echo $this->_var['v']['admin_money']; ?></td>
                <td><a href="http://www.gagoods.cn/index.php?ctl=service&act=user_list&id=<?php echo $this->_var['v']['pid']; ?>"> <?php echo $this->_var['v']['pid_name']; ?></a></td>
                <td><?php echo $this->_var['v']['level_id']; ?></td>
                <td><?php echo $this->_var['v']['fx_level']; ?></td>
                <td><?php echo $this->_var['v']['first_pay_date']; ?></td>
                <td>删除 <a href="http://www.gagoods.cn/index.php?ctl=service&act=user_log&id=<?php echo $this->_var['v']['id']; ?>">账户明细</a> <a
                        href="http://www.gagoods.cn/index.php?ctl=service&act=fx&id=<?php echo $this->_var['v']['id']; ?>">线下人员</a></td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
    </div>
    <?php endif; ?>

    <!--用户详情页-->
    <?php if ($this->_var['user_info_list']): ?>
    <div class="row">
        <form action="" method="post">
            <table class="table table-bordered">
                <tr>
                    <th class="info">项目</th>
                    <th>属性 <a href="http://www.gagoods.cn/index.php?ctl=service&act=user_log&id=<?php echo $this->_var['user_info_list']['id']; ?>">账户明细</a> <a
                            href="http://www.gagoods.cn/index.php?ctl=service&act=fx&id=<?php echo $this->_var['user_info_list']['id']; ?>">线下人员</a> </th>
                </tr>
                <tr>
                    <td class="info">用户名</td>
                    <td>
                        <input type="text" name="user_name" class="form-control" id="exampleInputName2" placeholder="Jane Doe" value="<?php echo $this->_var['user_info_list']['user_name']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="info">修改密码</td>
                    <td>
                        <input type="password" name="password" class="form-control" id="exampleInputName2" placeholder="密码">
                    </td>
                </tr>
                <tr>
                    <td class="info">邮箱</td>
                    <td>
                        <input type="email" name="email" class="form-control" id="exampleInputName2" placeholder="Jane Doe" value="<?php echo $this->_var['user_info_list']['email']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="info">手机号</td>
                    <td>
                        <input type="text" name="mobile" class="form-control" id="exampleInputName2" placeholder="Jane Doe" value="<?php echo $this->_var['user_info_list']['mobile']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="info">推广奖</td>
                    <td>
                        <input type="text" name="fx_money" class="form-control" id="exampleInputName2" placeholder="Jane Doe" value="<?php echo $this->_var['user_info_list']['fx_money']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="info">管理奖</td>
                    <td>
                        <input type="text" name="admin_money" class="form-control" id="exampleInputName2" placeholder="Jane Doe" value="<?php echo $this->_var['user_info_list']['admin_money']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="info">赠送金额</td>
                    <td>
                        <input type="text" name="give_money" class="form-control" id="exampleInputName2" placeholder="Jane Doe" value="<?php echo $this->_var['user_info_list']['give_money']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="info">余额</td>
                    <td>
                        <input type="text" name="money" class="form-control" id="exampleInputName2" placeholder="Jane Doe" value="<?php echo $this->_var['user_info_list']['money']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="info">经销商等级</td>
                    <td>
                        <input type="text" name="fx_level" class="form-control" id="exampleInputName2" placeholder="Jane Doe" value="<?php echo $this->_var['user_info_list']['fx_level']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="info">推荐人</td>
                    <td>
                        <input type="text" name="pid" class="form-control" id="exampleInputName2" placeholder="Jane Doe" value="<?php echo $this->_var['user_info_list']['pid']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="info">操作</td>
                    <td><button type="button" class="btn btn-primary">提交</button></td>
                </tr>
            </table>
        </form>
    </div>
    <?php endif; ?>
    <?php if ($this->_var['user_log']): ?>
    <div class="row">
        <table class="table table-striped table-bordered table-hover">
            <tr  class="active">
                <th>编号</th>
                <th>内容</th>
                <th>时间</th>
                <th>当前金额</th>
                <th>管理员</th>
            </tr>
            <?php $_from = $this->_var['user_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ul');if (count($_from)):
    foreach ($_from AS $this->_var['ul']):
?>
            <tr>
                <td><?php echo $this->_var['ul']['id']; ?></td>
                <td><?php echo $this->_var['ul']['log_info']; ?></td>
                <td><?php echo $this->_var['ul']['log_time']; ?></td>
                <td><?php echo $this->_var['ul']['money']; ?></td>
                <td><?php echo $this->_var['ul']['log_admin']; ?></td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
    </div>
    <?php endif; ?>
    <?php if ($this->_var['fx_user']): ?>
    <div class="row">
        <p>一级线下人员</p>
        <table class="table table-striped table-bordered table-hover">
            <tr  class="active">
                <th>id</th>
                <th>用户名</th>
                <th>手机号</th>
                <th>注册时间</th>
                <th>邀请人</th>
                <th>累计充值</th>
                <th>邀请用户数量</th>
                <th>操作</th>
            </tr>
            <?php $_from = $this->_var['fx_user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
            <tr>
                <td><?php echo $this->_var['v']['id']; ?></td>
                <td><?php echo $this->_var['v']['user_name']; ?></td>
                <td><?php echo $this->_var['v']['mobile']; ?></td>
                <td><?php echo $this->_var['v']['create_time']; ?></td>
                <td><?php echo $this->_var['v']['p_user']; ?></td>
                <td><?php echo $this->_var['v']['total_money']; ?></td>
                <td><?php echo $this->_var['v']['sid']; ?></td>
                <td><a href="http://www.gagoods.cn/index.php?ctl=service&act=user_log&id=<?php echo $this->_var['v']['id']; ?>">账户明细</a> <a
                        href="http://www.gagoods.cn/index.php?ctl=service&act=fx&id=<?php echo $this->_var['v']['id']; ?>">线下人员</a></td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
        <p>二级线下人员</p>
        <table class="table table-striped table-bordered table-hover">
            <tr  class="active">
                <th>id</th>
                <th>用户名</th>
                <th>手机号</th>
                <th>注册时间</th>
                <th>邀请人</th>
                <th>累计充值</th>
                <th>邀请用户数量</th>
                <th>操作</th>
            </tr>
            <?php $_from = $this->_var['second_user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
            <tr>
                <td><?php echo $this->_var['v']['id']; ?></td>
                <td><?php echo $this->_var['v']['user_name']; ?></td>
                <td><?php echo $this->_var['v']['mobile']; ?></td>
                <td><?php echo $this->_var['v']['create_time']; ?></td>
                <td><?php echo $this->_var['v']['p_user']; ?></td>
                <td><?php echo $this->_var['v']['total_money']; ?></td>
                <td><?php echo $this->_var['v']['sid']; ?></td>
                <td><a href="http://www.gagoods.cn/index.php?ctl=service&act=user_log&id=<?php echo $this->_var['v']['id']; ?>">账户明细</a> <a
                        href="http://www.gagoods.cn/index.php?ctl=service&act=fx&id=<?php echo $this->_var['v']['id']; ?>">线下人员</a></td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
        <p>三级线下人员</p>
        <table class="table table-striped table-bordered table-hover">
            <tr  class="active">
                <th>id</th>
                <th>用户名</th>
                <th>手机号</th>
                <th>注册时间</th>
                <th>邀请人</th>
                <th>累计充值</th>
                <th>邀请用户数量</th>
                <th>操作</th>
            </tr>
            <?php $_from = $this->_var['three_user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
            <tr>
                <td><?php echo $this->_var['v']['id']; ?></td>
                <td><?php echo $this->_var['v']['user_name']; ?></td>
                <td><?php echo $this->_var['v']['mobile']; ?></td>
                <td><?php echo $this->_var['v']['create_time']; ?></td>
                <td><?php echo $this->_var['v']['p_user']; ?></td>
                <td><?php echo $this->_var['v']['total_money']; ?></td>
                <td><?php echo $this->_var['v']['sid']; ?></td>
                <td><a href="http://www.gagoods.cn/index.php?ctl=service&act=user_log&id=<?php echo $this->_var['v']['id']; ?>">账户明细</a> <a
                        href="http://www.gagoods.cn/index.php?ctl=service&act=fx&id=<?php echo $this->_var['v']['id']; ?>">线下人员</a></td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
        <p>四级以后人员</p>
        <table class="table table-striped table-bordered table-hover">
            <tr  class="active">
                <th>id</th>
                <th>用户名</th>
                <th>手机号</th>
                <th>注册时间</th>
                <th>邀请人</th>
                <th>累计充值</th>
                <th>邀请用户数量</th>
                <th>操作</th>
            </tr>
            <?php $_from = $this->_var['four_user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
            <tr>
                <td><?php echo $this->_var['v']['id']; ?></td>
                <td><?php echo $this->_var['v']['user_name']; ?></td>
                <td><?php echo $this->_var['v']['mobile']; ?></td>
                <td><?php echo $this->_var['v']['create_time']; ?></td>
                <td><?php echo $this->_var['v']['p_user']; ?></td>
                <td><?php echo $this->_var['v']['total_money']; ?></td>
                <td><?php echo $this->_var['v']['sid']; ?></td>
                <td><a href="http://www.gagoods.cn/index.php?ctl=service&act=user_log&id=<?php echo $this->_var['v']['id']; ?>">账户明细</a> <a
                        href="http://www.gagoods.cn/index.php?ctl=service&act=fx&id=<?php echo $this->_var['v']['id']; ?>">线下人员</a></td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </table>
    </div>
    <?php endif; ?>
</div>

<script>
    $(function(){
        $(".user_clear").click(function(){
            $("input[name='user_name']").val('');
            $("input[name='id']").val('');
            $("input[name='email']").val('');
            $("input[name='mobile']").val('');
            $("input[name='pid']").val('');
            $("input[name='money']").val('');
            $("input[name='fx_level']").val('');
        });
        $(".with_done").click(function(){
            var self = $(".with_done").index($(this));
            var id = $(".with_id").eq(self).text();
            var r=confirm("是否确定红包已发送");
            if (r==true)
            {
                $.ajax({
                    type: "POST",
                    url: "http://www.gagoods.cn/index.php?ctl=service&act=with_done",
                    data: {id:id},
                    dataType: "json",
                    success:function(data){
                        if(data['status'] == 1){
                            $(".with_sta").eq(self).text('已付款');
                            alert(data['info']);
                        }else{
                            alert(data['info']);
                        }
                    }
                });
            }
        });
        $(".with_del").click(function(){
            var self = $(".with_done").index($(this));
            var id = $(".with_id").eq(self).text();
            var r=confirm("是否确认不通过");
            if (r==true)
            {
                $.ajax({
                    type: "POST",
                    url: "http://www.gagoods.cn/index.php?ctl=service&act=with_del",
                    data: {id:id},
                    dataType: "json",
                    success:function(data){
                        if(data['status'] == 1){
                            $(".with_tr").eq(self).remove();
                            alert(data['info']);
                        }else{
                            alert(data['info']);
                        }
                    }
                });
            }
        });
        $(".order_done").click(function(){
            var self = $(".order_done").index($(this));
            var id = $(".charge_id").eq(self).text();
            var r=confirm("是否确定红包已发送");
            if (r==true)
            {
                $.ajax({
                    type: "POST",
                    url: "http://www.gagoods.cn/index.php?ctl=service&act=order_done",
                    data: {id:id},
                    dataType: "json",
                    success:function(data){
                        if(data['status'] == 1){
                            $(".charge_red").eq(self).text('已发');
                        }
                    }
                });
            }
        });
        $(".charge_clear").click(function(){
            $("#exampleInputEmail3").val('');
            $("#exampleInputPassword3").val('');
            $("input[name='order_status']").removeAttr("checked");
        });
        $(".logout").click(function(){
            window.location.href="http://www.gagoods.cn/index.php?ctl=service&act=logout";
        });
        $(".charge_btn").click(function(){
            window.location.href="http://www.gagoods.cn/index.php?ctl=service&act=index";
        });
        $(".withdraw_btn").click(function(){
            window.location.href="http://www.gagoods.cn/index.php?ctl=service&act=withdraw";
        });
        $(".user_btn").click(function(){
            window.location.href="http://www.gagoods.cn/index.php?ctl=service&act=user";
        });
        $(".prize_btn").click(function(){
            window.location.href="http://www.gagoods.cn/index.php?ctl=service&act=prize";
        });
    });
</script>
</body>
</html>