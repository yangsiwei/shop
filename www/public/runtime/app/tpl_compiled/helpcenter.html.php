<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/index.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/helpcenter.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/utils/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/zone.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/goods_item.css";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.pngfix.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.animateToClass.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.SuperSlide.2.1.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/lazyload.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/login_panel.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/index.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/page_js/index.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/dc/js/page_js/slider.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/dc/js/page_js/slider.js";



?>
<?php echo $this->fetch('inc/header.html'); ?>
<div class="blank15"></div>
<div class="wrap_full_w clearfix">
    <div class="g-side"  >
        <div class="w-msg-news"  >
            <i class="diyfont"></i>
            <a href="<?php
echo parse_url_tag("u:index|news|"."t=notice".""); 
?>"><span class="font_h3" >
                系统公告
            </span></a>
        </div>
        <div class="w-msg-news"  >
            <i class="diyfont"></i>
            <a href="<?php
echo parse_url_tag("u:index|news|"."t=agreement".""); 
?>"><span class="font_h3" >
                服务协议
            </span></a>
        </div>
        <div class="w-msg-catlog">
            <div class="w-msg-catlog-hd" >
                <span class="font_h3s" >
                    帮助中心
                </span>
            </div>
            <?php if ($this->_var['deal_help']): ?>
            <div>
                <dl class="w-msg-catlog-list" >
                <?php $_from = $this->_var['deal_help']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'web_article_cate');if (count($_from)):
    foreach ($_from AS $this->_var['web_article_cate']):
?>
                <?php if ($this->_var['web_article_cate']): ?>
                <dt class="font_dt" >
                    <i class="diyfont"><?php echo $this->_var['web_article_cate']['iconfont']; ?></i>
                    <?php echo $this->_var['web_article_cate']['title']; ?>
                </dt>
                <?php endif; ?>
                <?php $_from = $this->_var['web_article_cate']['help_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'web');if (count($_from)):
    foreach ($_from AS $this->_var['web']):
?>
                <dd class="font_dd" >
                        <?php if ($this->_var['web_article_id'] == $this->_var['web']['id']): ?>
                            <p class="txt-red"><?php echo $this->_var['web']['title']; ?></p>
                        <?php else: ?>
                            <a href="<?php
echo parse_url_tag("u:index|help|"."id=".$this->_var['web']['id']."".""); 
?>">
                                <?php echo $this->_var['web']['title']; ?>
                            </a>
                        </li>
                        <?php endif; ?>
                </dd>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </dl>
            </div>
            <?php endif; ?>
        </div>
        <div class="w-msg-more" >
            <p>如果不能再帮助内容中找到答案，<br/>您还可以拨打：</p>
            <div class="w-msg-more-wrap">
                <p class="w-msg-more-call">
                    <i class="diyfont"></i>服务热线
                </p>
                <p class="w-msg-more-phoneNum"><?php echo $this->_var['shptel']; ?></p>
            </div>
        </div>
    </div>

    <div class="g-main">
		<ul class="web-map clearfix" style="padding-top:10px;margin-bottom:0px;padding-bottom:15px;font-size:14px;font-family:'Microsoft Yahei',verdana;">
			<?php if ($this->_var['is_agreement'] == 'notice'): ?>
				<li><a href="<?php
echo parse_url_tag("u:index|news|"."t=notice".""); 
?>">系统公告</a> ></li>
				<?php if ($this->_var['web_article_id']): ?>
					<li class="txt-red"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['agreement_one']['create_time'],
  'f' => 'Y-m-d',
);
echo $k['name']($k['v'],$k['f']);
?> 公告详情</li>
				<?php else: ?>
					<li class="txt-red">列表</li>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_var['is_agreement'] == 'agreement'): ?>
				<li><a href="<?php
echo parse_url_tag("u:index|news|"."t=agreement".""); 
?>">服务协议</a> ></li>
				<?php if ($this->_var['web_article_id']): ?>
					<li class="txt-red"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['agreement_one']['create_time'],
  'f' => 'Y-m-d',
);
echo $k['name']($k['v'],$k['f']);
?> 协议详情</li>
				<?php else: ?>
					<li class="txt-red">列表</li>
				<?php endif; ?>
			<?php endif; ?>
			<?php if (! $this->_var['is_agreement']): ?>
				<li><a href="<?php
echo parse_url_tag("u:index|index|"."".""); 
?>">首页</a> ></li>
				<li><a href="<?php
echo parse_url_tag("u:index|help|"."".""); 
?>">帮助中心</a> ></li>
				<li class="txt-red"><?php echo $this->_var['title']; ?></li>
			<?php endif; ?>
		</ul>
        <div class="m-helpcenter-detail" >
        <?php if ($this->_var['is_agreement']): ?>
            <?php if ($this->_var['agreement_one']): ?>
            <ul class="m-new-list">
                <li class="m-news-list-list">
                    <span class="date"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['agreement_one']['create_time'],
  'f' => 'Y-m-d',
);
echo $k['name']($k['v'],$k['f']);
?><span>
                    &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="font_span" ><?php echo $this->_var['agreement_one']['agreement_name']; ?></sapn>
                </li>
            </ul>
                <div class="m-news-bd" >
                    <?php echo $this->_var['agreement_one']['agreement']; ?>
                </div>
            <?php else: ?>
            <ul class="m-new-list">
                <?php $_from = $this->_var['agreement_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'agreement');if (count($_from)):
    foreach ($_from AS $this->_var['agreement']):
?>
                <li class="m-news-list-list">
                    <span class="date"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['agreement']['create_time'],
  'f' => 'Y-m-d',
);
echo $k['name']($k['v'],$k['f']);
?><span>
                    &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="<?php
echo parse_url_tag("u:index|news|"."t=".$this->_var['is_agreement']."&id=".$this->_var['agreement']['id']."".""); 
?>"><span class="font_span" ><?php echo $this->_var['agreement']['agreement_name']; ?></sapn></a>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <div class="pages"><?php echo $this->_var['pages']; ?></div>
            <?php endif; ?>
        <?php else: ?>
        <?php if ($this->_var['content']['content']): ?>
            <div class="m-helpcenter-detail-hd" >
                <h4><?php echo $this->_var['content']['title']; ?></h4>
            </div>
            <div class="m-helpcenter-detail-bd">
                <?php echo $this->_var['content']['content']; ?>
            </div>
        
        <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>
</div>
<?php echo $this->fetch('inc/footer.html'); ?>