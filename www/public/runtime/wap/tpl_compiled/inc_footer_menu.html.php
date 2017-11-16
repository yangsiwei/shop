<div class="footer-menu-box">
    <div class="f_menu split-line-top">
      <ul class="menu_box">
        <li class="menu_item <?php if ($this->_var['MODULE_NAME'] == 'index'): ?>cur<?php endif; ?>">
          <a href="<?php
echo parse_url_tag("u:index|index#index|"."".""); 
?>">
            <p style="width:2.6rem;height:2rem;"><img src="./wap/Tpl/main/images/menu/homeb.png" style="width:100%"/></p>
          </a>
        </li>
          <li class="menu_item <?php if ($this->_var['MODULE_NAME'] == 'anno'): ?>cur<?php endif; ?>">
            <a href="<?php
echo parse_url_tag("u:index|anno#index|"."".""); 
?>">
                <p style="width:2.6rem;height:2rem;"><img src="./wap/Tpl/main/images/menu/jieb.png" style="width:100%"/></p>
            </a>
          </li>
          <li class="menu_item <?php if ($this->_var['MODULE_NAME'] == 'cart'): ?>cur<?php endif; ?>">
              <a href="<?php
echo parse_url_tag("u:index|cart#index|"."".""); 
?>">
              <p style="width:2.6rem;height:2rem;"><img src="./wap/Tpl/main/images/menu/goub.png" style="width:100%"/></p>
              </a>
          </li>
        <li class="menu_item <?php if ($this->_var['MODULE_NAME'] == 'helps'): ?>cur<?php endif; ?>" >
          <a href="<?php
echo parse_url_tag("u:index|helps#index|"."".""); 
?>">
             <p style="width:2.6rem;height:2rem;"><img src="./wap/Tpl/main/images/menu/helpb.png" style="width:100%"/></p>
          </a>
        </li>
        <li class="menu_item <?php if ($this->_var['MODULE_NAME'] == 'user_center'): ?>cur<?php endif; ?>">
          <a href="<?php
echo parse_url_tag("u:index|user_center#index|"."".""); 
?>" onclick="mt_rand(this);">
            <p style="width:2.6rem;height:2rem;"><img src="./wap/Tpl/main/images/menu/meb.png" style="width:100%"/></p>
          </a>
        </li>
    </ul>
    <a href="/o2onew/wap/biz.php?ctl=more"></a>
  </div>
</div>
<script type="text/javascript">
/*  <?php if ($this->_var['signin_result']): ?>
  show_signin_message(<?php echo $this->_var['signin_result']; ?>);
  <?php endif; ?>*/
</script>