<?php $_from = $this->_var['index_duobao_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
<li class="tuan_li split-line">
    <a class="blw" href="<?php
echo parse_url_tag("u:index|duobao#index|"."data_id=".$this->_var['item']['id']."".""); 
?>">
    <div class="pic">
        <?php if ($this->_var['item']['min_buy'] == 10 || $this->_var['item']['unit_price'] == 10): ?>
        <div class="tenyen"></div>
        <?php endif; ?>
        <?php if ($this->_var['item']['unit_price'] == 100): ?>
        <div class="hundredyen"></div>
        <?php endif; ?>
        <img src="<?php echo $this->_var['item']['icon']; ?>" lazy="true" />
    </div>
    </a>
    <div class="info">
        <div class="info-tit">
            <?php if ($this->_var['item']['is_topspeed']): ?>
            <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
            <?php endif; ?>
            <?php echo $this->_var['item']['name']; ?>
        </div>

        <div class="progress-box">
            <div class="left-box">
                <progress max="<?php echo $this->_var['item']['max_buy']; ?>" value="<?php echo $this->_var['item']['current_buy']; ?>"></progress>
                <div class="fl">
                    <p class="txt-red"><?php echo $this->_var['item']['current_buy']; ?></p>
                    <p>已参与人次</p>
                </div>
                <div class="fr">
                    <p class="txt-red"><?php echo $this->_var['item']['surplus_buy']; ?></p>
                    <p>剩余人次</p>
                </div>
            </div>
            <a data-id="<?php echo $this->_var['item']['id']; ?>" class="right-box add_cart_item" unit_price="<?php echo $this->_var['item']['unit_price']; ?>" buy_num="<?php echo $this->_var['item']['min_buy']; ?>" data_id="<?php echo $this->_var['item']['id']; ?>" rel="<?php echo $this->_var['item']['icon']; ?>"><i class="iconfont">&#xe658;</i></a>
        </div>
    </div>
</li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
