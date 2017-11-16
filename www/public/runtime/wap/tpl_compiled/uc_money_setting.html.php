
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/uc_money_setting.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/uc_duobao_record.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/uc_duobao_record.js";

?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<div class="uc-list-view split-line-top split-line">
   <ul class="uc-list-v-items">
       <li class="uc-v-item split-line">
           <a href="<?php
echo parse_url_tag("u:index|uc_money#balance|"."".""); 
?>">
              <div class="sub-uc-v-item">
               <i class="iconfont green-color">&#xe6f3;</i>
               <span>我的余额</span>
               <!--<p><em><?php echo $this->_var['money1']; ?></em>夺宝币</font></p>-->
                <i class="right-jt iconfont">&#xe6fa;</i>
              </div>
          </a>
       </li>
        <li class="uc-v-item split-line">
            <a href="<?php
echo parse_url_tag("u:index|uc_money#index|"."".""); 
?>">
                <div class="sub-uc-v-item">
                  <i class="iconfont green-color">&#xe6f3;</i>
                  <span>资金记录</span>
                  <!--<p><em><?php echo $this->_var['money']; ?></em> 夺宝币</p>-->
                  <i class="right-jt iconfont">&#xe6fa;</i>
                </div>
              </a>
          </li>
          <li class="uc-v-item split-line">
              <a href="<?php
echo parse_url_tag("u:index|uc_charge|"."".""); 
?>">
                  <div class="sub-uc-v-item">
                    <i class="iconfont pinker-color">&#xe6f2;</i>
                      <span>立即充值</span>

                    <i class="right-jt iconfont">&#xe6fa;</i>
                  </div>
                </a>
          </li>
       <li class="uc-v-item split-line">
           <a href="<?php
echo parse_url_tag("u:index|uc_money_cash#index|"."".""); 
?>" >
           <div class="sub-uc-v-item">
               <i class="iconfont pinker-color">&#xe6f2;</i>
               <span>申请提现</span>

               <i class="right-jt iconfont">&#xe6fa;</i>
           </div>
           </a>
       </li>
          <li class="uc-v-item split-line">
              <a href="<?php
echo parse_url_tag("u:index|uc_money_cash#withdraw_log|"."".""); 
?>">
                  <div class="sub-uc-v-item">
                    <i class="iconfont pinker-color">&#xe6f2;</i>
                      <span>提现记录</span>

                    <i class="right-jt iconfont">&#xe6fa;</i>
                  </div>
                </a>
          </li>
   </ul>
 </div>
<?php echo $this->fetch('inc/footer_index.html'); ?>