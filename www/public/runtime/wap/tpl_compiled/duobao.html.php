<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/weebox.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/fanweUI.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/duobao.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/relate_goods.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/layer.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pull_refresh.css";


$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/TouchSlide.1.1.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/onload.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/swipe.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/duobao.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/duobao.js";


$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/relate_goods.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/relate_goods.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/layer.m/layer.m.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/layer.m/layer.m.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/duoobao_item_num.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/duoobao_item_num.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/lib/touche.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";


?>
<?php if ($this->_var['item_data']['is_pk'] == 1): ?>
<?php
   $this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pk_show_pkgoods_status.css";
   $this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pk_show_pkgoods_status.js";
   $this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pk_show_pkgoods_status.js";
   $this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pk_index.js";
   $this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pk_index.js";
 ?>
<?php endif; ?>
<?php echo $this->fetch('inc/header_title_home.html'); ?>
<script type="text/javascript">
var cart_conf_json = <?php echo $this->_var['cart_conf_json']; ?>;
var cart_data_json = <?php echo $this->_var['cart_data_json']; ?>;
var to_cart_url = "<?php
echo parse_url_tag("u:index|cart#index|"."".""); 
?>";
var totalbuy_cart_url='<?php
echo parse_url_tag("u:index|totalbuy#index|"."".""); 
?>';
var duobao_detail_info = 1;
</script>

<?php endif; ?>
<div class="wrap page_detail loading_container" id="loading_container">

<div class="content">
    <!--关于商品-->
    <div class="goods-box goods-abbr">
		<div class="top">
		<?php if ($this->_var['item_data']['unit_price'] == 100): ?><div class="hundredyen"></div><?php endif; ?>
        <div class="tenyen"></div>
            <div class="flash" id="containerFlashAnimation">
                <section>
                     	<div id="banner_box" class="banner_box banner_box_half <?php if ($this->_var['item_data']['deal_gallery']): ?>has_img<?php endif; ?>">
						<div class="bd">
							<ul>
								 <?php $_from = $this->_var['item_data']['deal_gallery']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');if (count($_from)):
    foreach ($_from AS $this->_var['img']):
?>
		                            <li style="vertical-align: top; width: 100%; display: table-cell;height:8.5rem">
		                                <img src="<?php echo $this->_var['img']; ?>" style="height:8.5rem;margin:0 auto;display:block;"/>
		                            </li>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							</ul>
						</div>
						<div class="hd"><ul></ul></div>
					</div>
                </section>
            </div>
        </div>
         <div class="r-content1">
             <div class="notice-box1 split-line1" style="width: 241px;height: 37px;background-color: black;opacity: 0.55;border-radius: 31px;position: fixed;top: 200px;left: 10px;overflow: hidden;display:none;z-index:999;">
                      <ul class="n-list-box1" style="text-align:center;line-height:37px;color:white;float:left">
                     <?php $_from = $this->_var['data']['duobao_order_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_order');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_order']):
?>
                        <li class="n-item1" style="width:241px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><img src="<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['duobao_order']['user_id'],
  'type' => 'small',
);
echo $k['name']($k['uid'],$k['type']);
?>" style="z-index: 999;border-radius: 84px;width: 30px;height: 30px;float: left;margin-left: 9px;margin-top: 3px;">&nbsp;<span><?php echo $this->_var['duobao_order']['user_name']; ?></span>&nbsp;&nbsp;参与了<span class="spId"><?php echo $this->_var['duobao_order']['number']; ?></span>人次</li>
                     
                         <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                      </ul>
                </div>      
         </div>
         <!--  <?php $_from = $this->_var['data']['duobao_order_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_order');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_order']):
?>
                    <li class="n-item1"><span><?php echo $this->_var['duobao_order']['user_name']; ?></span>参与了<span><?php echo $this->_var['duobao_order']['number']; ?></span>人次</li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> -->
<script type="text/javascript">
$(document).ready(function () {

        init_notice();
});
//弹幕滚动
function init_notice()
{
    $(".notice-box1").everyTime(3000, function () {
        roll_news();
    });
}
function roll_news()
{
	 var lock = false;
     var spId =  $(".notice-box1 ul").find("li:first").children(".spId").html();
     // console.log(spId);
     if (spId !=null && spId !=undefined && spId !="") {
     	lock = true;
        $(".notice-box1").animate({bottom: 450 + "px"}, 3000,function(){
            $(".notice-box1").css("display","block");
          });
        $(".notice-box1").animate({top: 90 + "px"}, 3000,function(){
            $(".notice-box1").css("display","none");
             $(".notice-box1").css("bottom","");
             $(".notice-box1").css("top","");
          });
       
       
         setTimeout(function(){
                 $(".notice-box1 ul").find("li:first").animate({marginTop: "-" + $(".notice_board1 ul").find("li:first").height() + "px"}, 3100, function () {
                var li = $(this);
                $(".notice-box1 ul").append('<li class="n-item1">' + $(li).html() + '</li>');
                $(li).remove();
            });
          },3000);
        
    }else{
       lock = false;
    }
}
</script>    
        <?php if ($this->_var['item_data']['duobao_status'] == 0): ?>
         <!-- 进行中 -->
        <div class="good-on">
            <em class="on">进行中</em>
            <p>
                <?php if ($this->_var['item_data']['fair_type'] == five): ?>
                <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">五倍</em>
                <?php endif; ?>
            	<?php if ($this->_var['item_data']['is_topspeed']): ?>
                <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
                <?php endif; ?>
                <?php if ($this->_var['item_data']['is_number_choose'] == 1): ?>
                <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">选号</em>
                <?php endif; ?>
                <?php if ($this->_var['item_data']['is_pk'] == 1): ?>
                <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">PK</em>
                <?php if ($this->_var['item_data']['has_password'] == 1): ?>
                <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">密</em>
                <?php endif; ?>
                <?php endif; ?>
                <?php echo $this->_var['item_data']['name']; ?> <span><?php echo $this->_var['item_data']['brief']; ?></span>

            </p>
            <?php if ($this->_var['item_data']['unit_price'] == 100): ?>

            <span style="color:#df5667; display:block; margin-bottom:.2rem;">1人次=100夺宝币&nbsp;&nbsp; <?php if ($this->_var['item_data']['user_max_buy'] > 0): ?>每人限购 <?php echo $this->_var['item_data']['user_max_buy']; ?> 次<?php endif; ?> </span>
            <?php endif; ?>
            <div class="progress">
                <p>期号：<?php echo $this->_var['item_data']['id']; ?></p>
                <progress max="<?php echo $this->_var['item_data']['max_buy']; ?>" value="<?php echo $this->_var['item_data']['current_buy']; ?>"></progress>
                <p class="fl">总需<em id="pro-max"><?php echo $this->_var['item_data']['max_buy']; ?></em>人次</p>
                <p class="fr">剩余<span id="pro-rest"><?php echo $this->_var['item_data']['surplus_count']; ?></span></p>
                <div class="clear"></div>
            </div>
        </div>
         <?php elseif ($this->_var['item_data']['duobao_status'] == 1): ?>
        <!-- 倒计时 -->
        <div class="good-countdown">
             <em class="countdown">倒计时</em>
             <p><?php echo $this->_var['item_data']['name']; ?> <span><?php echo $this->_var['item_data']['brief']; ?></span>
             </p>
             <div class="countdown-box">
                 <p>期号：<?php echo $this->_var['item_data']['id']; ?> </p>
                 <p class="fl">揭晓倒计时</p>
                 <time class="fl w-countdown-nums" duobao_item_id="<?php echo $this->_var['item_data']['id']; ?>" nowtime="<?php echo $this->_var['item_data']['now_time']; ?>" endtime="<?php echo $this->_var['item_data']['lottery_time']; ?>"></time>
                   <?php if ($this->_var['lott']): ?>
                   <?php else: ?>
                 <a href="<?php
echo parse_url_tag("u:index|duobao#detail|"."data_id=".$this->_var['item_data']['id']."".""); 
?>">计算详情</a>
                 <?php endif; ?>
             </div>
        </div>
        <?php elseif ($this->_var['item_data']['duobao_status'] == 2): ?>
        <!-- 已揭晓 -->
        <div class="good-announced">
             <em class="announced">已揭晓</em>
             <p><?php echo $this->_var['item_data']['name']; ?> <span><?php echo $this->_var['item_data']['brief']; ?></span>
             </p>
             <div class="announced-box">
                <div class="announcer">
                </div>
                 <div class="imgbox" style="background:url(<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['item_data']['luck_lottery']['user_id'],
  'type' => 'small',
);
echo $k['name']($k['uid'],$k['type']);
?>);background-size: contain">

                 </div>
                 <ul>
                     <li>
                         <p>获奖者：</p>
                         <div class="fl">
                           <?php if ($this->_var['lott']): ?>
                             <?php $_from = $this->_var['lott']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['value']):
?>
                             <span style="color:#0C3CB0;"><?php echo $this->_var['value']['user_id']; ?>,</span>
                             <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                             <?php else: ?>
                                <span style="color:#0C3CB0;"><?php echo $this->_var['item_data']['luck_lottery']['user_name']; ?></span>

                             <?php endif; ?>
                         
                             <!-- <em><?php echo $this->_var['item_data']['luck_lottery']['duobao_ip']; ?>(<?php echo $this->_var['item_data']['luck_lottery']['duobao_area']; ?>)</em> -->
                         </div>
                         <div class="clear"></div>
                     </li>
                     <li>
                         <p>用户ID：</p><?php echo $this->_var['item_data']['luck_user_id']; ?>（唯一不变标识）
                     </li>
                     <li>
                         <p>期 &nbsp;&nbsp;&nbsp;号：</p>
                         <?php echo $this->_var['item_data']['id']; ?>
                     </li>
                     <li>
                         <p>本期参与：</p>
                         <span>
                         <?php if ($this->_var['lott']): ?>
                          <?php echo $this->_var['duobao1']['max_buy']; ?>
                         <?php else: ?>
                          <?php echo $this->_var['item_data']['luck_lottery']['user_total']; ?>
                         <?php endif; ?>
                        </span>人次
                     </li>
                     <li>
                     <p>揭晓时间：</p>
                     <?php echo $this->_var['item_data']['lottery_time_format']; ?>
                     </li>
                 </ul>
                 <div class="clear"></div>
                 <div class="luckycode">
                     <p class="fl">幸运号码：
                     </p>
                      <!-- 五倍开奖-->
                     <?php if ($this->_var['lott']): ?>
                         <?php $_from = $this->_var['lott']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['value']):
?>
                         <div class="fl"><?php echo $this->_var['value']['lottery_sn']; ?>,</div>
                         <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                         <?php else: ?>
                         <em class="fl"><?php echo $this->_var['item_data']['lottery_sn']; ?></em> <a href="<?php
echo parse_url_tag("u:index|duobao#detail|"."data_id=".$this->_var['item_data']['id']."".""); 
?>">计算详情</a>
                     <?php endif; ?>
                     <!--end 五倍开奖-->
                 </div>
             </div>
        </div>
        <!-- 已揭晓 -->
        <?php endif; ?>

		<?php if ($this->_var['data']['user_login_status'] == 0): ?>
        <div class="login-to-check">
            <p><a href="<?php
echo parse_url_tag("u:index|user#login|"."".""); 
?>">登录</a>以查看你的夺宝号码~</p>
        </div>
        <!-- 未登录 -->
		<?php else: ?>
		<?php if (! $this->_var['data']['my_duobao_log']): ?>
        <div class="notin">
            <p>您没有参与本期夺宝哦！</p>
        </div>
        <!-- 未参与 -->

		<?php else: ?>
        <div class="joined">
            <p>您参与了：<span><?php echo $this->_var['data']['my_duobao_count']; ?></span>人次</p>
            <dl id="duobao_sn_list">
                <dt>夺宝号码：</dt>
				<?php $_from = $this->_var['data']['my_duobao_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'log');if (count($_from)):
    foreach ($_from AS $this->_var['log']):
?>
                <dd><?php echo $this->_var['log']['lottery_sn']; ?></dd>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </dl>
            <a id="func" href="javascript:void(0);" style="float:right; margin-right:.36rem;color:#39f;margin-top:.4rem;font-size: .52rem;">展开<i class="iconfont">&#xe6c3;</i></a>
            <div class="clear"></div>
        </div>
		<?php endif; ?>
        <!-- 参与信息 -->
		<?php endif; ?>
    </div>
    <?php if ($this->_var['item_data']['is_total_buy'] == 1 && $this->_var['item_data']['total_buy_stock'] > 0 && $this->_var['item_data']['duobao_status'] == 0): ?> 
    <div class="zg-area">
        <div class="zg-info">
            <h2>全价购买</h2>
            <p>无需等待，直接获得商品！</p>
            <p class="price txt-red">&yen;<?php echo $this->_var['item_data']['total_buy_price']; ?></p>
        </div>
        <a href="javascript:;"  data_id="<?php echo $this->_var['item_data']['id']; ?>" onclick="add_total_buy_cart_item(this)"  class="zg-btn">全价购买</a>
    </div>
    <?php endif; ?>
    <div class="infomation">
        <ul>
            <li><a href="<?php
echo parse_url_tag("u:index|duobao#more|"."data_id=".$this->_var['item_data']['id']."".""); 
?>">图文详情<i class="iconfont">&#xe6fa;</i><span class="fr">建议在wifi下查看</span></a></li>
            <div class="info-border"></div>
          <?php if ($this->_var['item_data']['is_pk'] != 1): ?>
            <li><a href="<?php
echo parse_url_tag("u:index|duobao#helper|"."data_id=".$this->_var['item_data']['duobao_id']."&goods_id=".$this->_var['item_data']['id']."".""); 
?>">夺宝助手<i class="iconfont">&#xe6fa;</i></a></li>
            <div class="info-border"></div>
            <li><a href="<?php
echo parse_url_tag("u:index|duobao#duobao_record|"."data_id=".$this->_var['item_data']['duobao_id']."".""); 
?>">往期揭晓<i class="iconfont">&#xe6fa;</i></a></li>
            <div class="info-border"></div>
            <li><a href="<?php
echo parse_url_tag("u:index|share#index|"."data_id=".$this->_var['item_data']['duobao_id']."".""); 
?>">晒单记录<i class="iconfont">&#xe6fa;</i></a></li>
          <?php endif; ?>
        </ul>
    </div>
    <!-- 信息 -->
	<?php if ($this->_var['data']['duobao_order_list']): ?>
    <div class="join-data ">
        <div class="all-data split-line">
                     所有参与记录<span class="fr">(<time><?php echo $this->_var['item_data']['create_time_format']; ?></time>开始)</span>
        </div>
        <dl >
            <dt>
            <time><?php echo $this->_var['item_data']['create_time_format']; ?></time>
            </dt>
            <div class="scroll_bottom_list">


			<?php $_from = $this->_var['data']['duobao_order_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'duobao_order');if (count($_from)):
    foreach ($_from AS $this->_var['duobao_order']):
?>
            <dd>
                <div class="imgbox" style="background:url(<?php 
$k = array (
  'name' => 'get_user_avatar',
  'uid' => $this->_var['duobao_order']['user_id'],
  'type' => 'small',
);
echo $k['name']($k['uid'],$k['type']);
?>);background-size: contain"></div>
                <div class="txtbox">
                    <!-- <a href="javascript:void(0);"><?php echo $this->_var['duobao_order']['user_name']; ?></a> -->
                    <span style="color:#0C3CB0;"><?php echo $this->_var['duobao_order']['user_name']; ?></span>

                    <!-- <a href="<?php
echo parse_url_tag("u:index|anno_user_center|"."lucky_user_id=".$this->_var['item']['luck_user_id']."".""); 
?>"><?php echo $this->_var['item']['luck_user_name']; ?></a> -->
                    <!-- <em>(<?php echo $this->_var['duobao_order']['duobao_area']; ?> IP:<?php echo $this->_var['duobao_order']['duobao_ip']; ?>)</em> -->
                    <p>参与了<span><?php echo $this->_var['duobao_order']['number']; ?></span>人次 <time><?php echo $this->_var['duobao_order']['f_create_time']; ?></time></p>
                </div>
                <div class="clear"></div>
            </dd>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <!-- 个人参与记录 -->
</div>
        </dl>
		<?php if ($this->_var['pages']): ?>
			<div class="fy scroll_bottom_page">
				<?php echo $this->_var['pages']; ?>
			</div>
		<?php endif; ?>

    </div>
	<?php endif; ?>

	<?php if ($this->_var['data']['next_id']): ?>
    <div class="gotonew">
        <div class="gotonew-box">
        <p>新一期正在火热进行...</p>

        <a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['data']['next_id']."".""); 
?>">立即前往</a>
        </div>
    </div>
	<?php endif; ?>
    <?php if ($this->_var['item_data']['is_number_choose'] == 1): ?>
        <div class="joinin">
            <div class="joinin-box">
                <a class="jionin-in joinin-btn right-box" href="<?php
echo parse_url_tag("u:index|number_choose#select|"."data_id=".$this->_var['item_data']['id']."".""); 
?>">立即选号</a>
                <a href="<?php
echo parse_url_tag("u:index|cart#index|"."".""); 
?>" class="iconfont-box" style="margin-right:0.4rem;">
            </div>
        </div>
   <?php elseif ($this->_var['item_data']['is_pk'] == 1): ?>
        <?php if ($this->_var['data']['my_duobao_count'] < 1): ?>
        <div class="joinin">
            <div class="joinin-box">
                <?php if ($this->_var['item_data']['has_password'] == 1): ?>
                <a class="jionin-in joinin-btn  pk-now"  style="margin-right: 0.4rem;" href="javascript:void(0);" url="<?php
echo parse_url_tag("u:index|pk#check_password|"."data_id=".$this->_var['item_data']['id']."".""); 
?>">参与PK</a>
                <?php else: ?>
                <a class="jionin-in joinin-btn  submit"  style="margin-right: 0.4rem;" href="javascript:void(0);" url="<?php
echo parse_url_tag("u:index|pk#check_password|"."data_id=".$this->_var['item_data']['id']."".""); 
?>">参与PK</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    <?php else: ?>
    <div class="joinin">
        <div class="joinin-box">
                <input type="hidden" name="data_id" value="<?php echo $this->_var['item_data']['id']; ?>"/>
                <a data-id="<?php echo $this->_var['item_data']['id']; ?>" class="jionin-in joinin-btn right-box" href="javascript:void(0);"  data-type="1">立即参与</a>
                <a href="<?php
echo parse_url_tag("u:index|cart#index|"."".""); 
?>" class="iconfont-box">
                <i class="iconfont">&#xe658;</i>
                <div class="goods-in-list">1</div>
                </a>
        </div>
    </div>
    <?php endif; ?>
</div>

</div>
<?php if ($this->_var['item_data']['is_pk'] == 1 && $this->_var['item_data']['has_password'] == 1): ?>
<div class="mask"></div>
<div class="pk-box" style="display:none;">
    <div class="pk-box-hd">
        <h2 class="pk-box-tit">输入密码</h2>
        <a href="javascript:void(0)" class="close-pk-box"><i class="iconfont">&#xe64f;</i></a>
    </div>
    <div class="pk-box-bd">
        <div class="input-wrap flex-box">
            <p>设置密码：</p>
            <input type="password" name="pk_password" id="pk_password" maxlength="6" class="flex-1" placeholder="请输入密码" check-login='<?php
echo parse_url_tag("u:index|user#check_login|"."".""); 
?>'>
        </div>
        <a href="javascript:void(0);" id="pk_submit" class="pk-confirm" id="submit">确认</a>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['ajax_refresh'] == 0): ?>

<?php if ($this->_var['item_data']['duobao_status'] == 0): ?>
 <?php echo $this->fetch('inc/add_to_list.html'); ?>
<?php endif; ?>

<div class="blank50"></div>
<?php echo $this->fetch('inc/no_footer.html'); ?>

<?php endif; ?>
