<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/winlog.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/jquery.confirm.css";


$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.spanr.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.confirm.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/anno.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/anno.js";

?>

<?php echo $this->fetch('inc/header_title_home.html'); ?>

<div class="wrap">
    <div class="content">
      <header class="uc-info-head">
        <div class="head-pic fl">
        <img src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['data']['user_id'],
  'type' => 'small',
);
echo $k['name']($k['uid'],$k['type']);
?>"  width="100%" height="100%">
        </div>
        <div class="user-box fl">
        <p class="user-name"><?php echo $this->_var['data']['user_name']; ?></p>
        <p class="user-id">ID:<span><?php echo $this->_var['data']['user_id']; ?></span></p>
        </div>
      </header>
        <?php if ($this->_var['duobao']): ?>
        <ul class="win-list scroll_bottom_list">
            <?php $_from = $this->_var['duobao']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>

            <li class="split-line">
                <a href="<?php echo $this->_var['item']['url']; ?>">
                    <div class="goods-img">
                        <img src="<?php echo $this->_var['item']['icon']; ?>" alt="">
                    </div>
                </a>
                <div class="txtbox">
                    <a href="<?php echo $this->_var['item']['url']; ?>">
                        <div class="flex-box">
                            <h2><?php echo $this->_var['item']['name']; ?></h2>
                        </div>
                    </a>
                    <dl>

                        <dd>参与期号:<span class="code"><?php echo $this->_var['item']['id']; ?></span></dd>
                        <dd>幸运号码:<span class="luckycode"><?php echo $this->_var['item']['lottery_sn2']; ?></span></dd>

                        <dd>下单时间:<span><?php echo $this->_var['item']['create_time']; ?></span></dd>
                        <?php if ($this->_var['item']['take_effect'] == 0): ?>
                                 <dd>状态:<span class="status_info"><a href="http://www.gagoods.cn/wap/index.php?ctl=uc_address&show_prog=1" style="color:#df5667;">未发货,请填写地址</a>
                            <?php else: ?>
                                <dd>状态:<span class="status_info">已发货
                        <?php endif; ?>
              </span></dd>
                    </dl>
                </div>
                <div class="clear"></div>
            </li>
            </a>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <?php endif; ?>

      <?php if ($this->_var['data']['list']): ?>
      <ul class="win-list scroll_bottom_list">
        <?php $_from = $this->_var['data']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>

        <li class="split-line">
        <a href="<?php echo $this->_var['item']['url']; ?>">
          <div class="goods-img">
            <img src="<?php echo $this->_var['item']['deal_icon']; ?>" alt="">
          </div>
          </a>
          <div class="txtbox">
          <a href="<?php echo $this->_var['item']['url']; ?>">
          <div class="flex-box">
            <h2><?php echo $this->_var['item']['name']; ?></h2>
          </div>
          </a>
            <dl>

              <dd>参与期号:<span class="code"><?php echo $this->_var['item']['duobao_item_id']; ?></span></dd>
              <dd>幸运号码:<span class="luckycode"><?php echo $this->_var['item']['lottery_sn']; ?></span></dd>

              <dd>下单时间:<span><?php echo $this->_var['item']['create_time']; ?></span></dd>
              <dd>状态:<span class="status_info">
              <?php if ($this->_var['item']['cate_id']): ?>
                 <?php if ($this->_var['item']['delivery_status'] == 0): ?>
                等待商品派发
               <?php elseif ($this->_var['item']['is_arrival'] == 0 && $this->_var['item']['delivery_status'] == 1): ?>
            <a href='<?php
echo parse_url_tag("u:index|uc_order#verify_delivery|"."item_id=".$this->_var['item']['id']."".""); 
?>' class="confirm">确认收货</a>
            待收货
         <?php elseif ($this->_var['item']['is_arrival'] == 1 && $this->_var['item']['delivery_status'] == 1): ?>
         <a class="fictitious_info" href="javascript:;" action="<?php echo $this->_var['item']['fictitious_info']; ?>">查看信息</a> 
         <?php if ($this->_var['item']['is_send_share'] == 0): ?>
         <a href='<?php
echo parse_url_tag("u:index|uc_share#rule|"."id=".$this->_var['item']['duobao_item_id']."".""); 
?>'>晒单</a>
         <?php endif; ?>
         已收货
         <?php endif; ?>
              <?php else: ?>
                <?php if ($this->_var['item']['delivery_status'] == 5): ?>
                无需发货
               <?php endif; ?>
         <?php if ($this->_var['item']['delivery_status'] == 0): ?>
            <?php if ($this->_var['item']['is_set_consignee'] == 0): ?>
            <!-- 请完善配送地址或联系客服，否则奖品在7天后失效 <a href='<?php
echo parse_url_tag("u:index|uc_address|"."".""); 
?>'>&nbsp;完善地址</a> -->
            <a href='<?php
echo parse_url_tag("u:index|uc_winlog#winlog_address|"."order_item_id=".$this->_var['item']['id']."".""); 
?>'>选择配送地址</a>
            <?php else: ?>
            等待商品派发
            <?php endif; ?>
         <?php endif; ?>
         <?php if ($this->_var['item']['is_arrival'] == 0 && $this->_var['item']['delivery_status'] == 1): ?>
              <a href="<?php
echo parse_url_tag("u:index|uc_order#check_delivery|"."item_id=".$this->_var['item']['id']."".""); 
?>" >查看物流</a>
              <a href="<?php
echo parse_url_tag("u:index|uc_order#verify_delivery|"."item_id=".$this->_var['item']['id']."".""); 
?>" class="confirm">确认收货</a>

         <?php endif; ?>
         <?php if ($this->_var['item']['is_arrival'] == 1): ?>
            已收货
          <?php if ($this->_var['item']['is_send_share'] == 0): ?>
          <a href='<?php
echo parse_url_tag("u:index|uc_share#rule|"."id=".$this->_var['item']['duobao_item_id']."".""); 
?>'>晒单</a>
          <?php endif; ?>
           <?php endif; ?>
         <?php if ($this->_var['item']['is_arrival'] == 2): ?>
           维权中
         <?php endif; ?>
               <?php endif; ?>
              </span></dd>
            </dl>
          </div>
          <div class="clear"></div>
        </li>
        </a>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      </ul>
      <?php if ($this->_var['pages']): ?>
      <div class="fy scroll_bottom_page">
        <?php echo $this->_var['pages']; ?>
      </div>
    <?php endif; ?>
<div class="clear"></div>
<?php else: ?>
        <?php if ($this->_var['duobao'] || $this->_var['data']['list']): ?><?php else: ?>
  <!-- 无数据↓ -->
    <div class="lose">
      <div class="bgbox">
      </div>
      <h1>您还没有中奖记录</h1>
      <a href='<?php
echo parse_url_tag("u:index|index|"."".""); 
?>'><button>立即夺宝</button></a>
    </div>
        <?php endif; ?>
        <?php endif; ?>

    </div>
</div>
<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
