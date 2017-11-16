<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/index.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pull_refresh.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/tb/iconfont.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/icon_font/iconfont.ttf";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/online.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/sign.css";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/sign.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.bgiframe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.weebox.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.touchwipe.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/TouchSlide.1.1.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.fly.min.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/onload.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/swipe.js";


$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/index.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/index.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/duoobao_item_num.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/duoobao_item_num.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/lib/touche.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";

?>

<?php echo $this->fetch('inc/header_index_new.html'); ?>
 
<?php if ($this->_var['is_send_reg_ecv'] == 1): ?>
		<?php echo $this->fetch('inc/log_ecv.html'); ?>
<?php endif; ?>

<script type="text/javascript">
//减少移动端触发"Click"事件时300毫秒的时间差
window.addEventListener('load', function() {
    FastClick.attach(document.body);
}, false);
var host_url='<?php echo $this->_var['host']; ?>';
var share_url='<?php echo $this->_var['index_share_url']; ?>';
var login_url='<?php
echo parse_url_tag("u:index|user#login|"."".""); 
?>';
var is_index_set  = 1;

var title='<?php echo $this->_var['data']['page_title']; ?>';

$(function(){
    var daren = getCookie('is_daren');
    if(daren == 1){
        $(".tongzhi").hide();
    }else{
        $(".tishi_aaa").show("slow");
    }
	var send_money='<?php echo $this->_var['send_money']; ?>';
	if(send_money=='1'){
		setTimeout(function (){
			$(".ecv_log_suc").addClass('active');
		},250);
		setTimeout(function () {
			$(".ecv_log_suc").removeClass('active');
		},2000);
	}


	$(".tongzhi").click(function(){
        $(".tishi_aaa").hide("slow");
        setCookie("is_daren",1,1);
        $.ajax({
            type:"POST",
            url:"http://www.gagoods.cn/wap/index.php?ctl=index&act=xiaoxi",
            data:"",
            dataType:"json",
            success:function(data){
                if(data['status'] == 1){
                    $(".tongzhi").css('display','none');
                }
            }
        });
        window.location.href="http://www.gagoods.cn/wap/index.php?ctl=prize&show_prog=1";
    });
	$('.menu_box>li').eq(0).children('a').children('p').children('img').attr('src','./wap/Tpl/main/images/menu/homer.png ');

    function setCookie(cname,cvalue,exdays)
    {
        var d = new Date();
        d.setTime(d.getTime()+(exdays*24*60*60*1000));
        var expires = "expires="+d.toGMTString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }

    function getCookie(cname)
    {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++)
        {
            var c = ca[i].trim();
            if (c.indexOf(name)==0) return c.substring(name.length,c.length);
        }
        return "";
    }

});

</script>
<?php endif; ?>

<?php if ($this->_var['msg_system']): ?>
<div class="tongzhi tishi_aaa" style="
	position: fixed;top:0;left: 0;
	display:none;
    z-index: 1000;
    background-color:rgba(77,77,77,.7);
    width:100%;height:100%">
	<!-- <p class="msg_stytem" style="    font-size: 14px;
    line-height: 25px;
    margin-top: 3%;
    width: 90%;
    margin-left: 5%;
    color:#FFF"> 【夺宝联盟】双十一只会花钱？NO，夺宝联盟送你大转盘，带你一边花钱，一边赚钱。 </p> -->
	<img src="./wap/Tpl/main/images/2017110810162111.png"  class="tishi_aaa" style="width:80%;position: fixed;top:20%;left: 10%;display:none;">
</div>

<?php endif; ?>
<div class="wrap loading_container"  id="loading_container">
	<?php if ($this->_var['data']['advs']): ?>
		<div class="top">
			<section>
				<div id="index-adv-box" class="index-adv-box">
					<div class="bd">
						<ul>
						<?php $_from = $this->_var['data']['advs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'adv');if (count($_from)):
    foreach ($_from AS $this->_var['adv']):
?>
							<li>
								<a href="<?php echo $this->_var['adv']['url']; ?>"><img src="<?php echo $this->_var['adv']['img']; ?>" alt="" border="0" style="width:100%;"></a>
							</li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</ul>
					</div>
					<div class="hd"><ul></ul></div>
				</div>
			</section>
		</div>
		<script>
		TouchSlide({
			slideCell:"#index-adv-box",
			titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航夺宝币素包裹层
			mainCell:".bd ul",
			effect:"leftLoop", 
			autoPage:true,//自动分页
			autoPlay:true, //自动播放
			delayTime:750
		});
		</script>
	<?php endif; ?>


	

	<?php if ($this->_var['data']['indexs']): ?>
	<div class="sort_nav" id="sort_nav">
		  <div class="sort_list split-line">
		  <ul>
			<?php $i=0; ?>
		  	<?php $_from = $this->_var['data']['indexs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'indexs');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['indexs']):
?>
			<?php $i++; ?>
		  	<li>
		  	<?php $_from = $this->_var['indexs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
				<a class="sort_li" href="<?php echo $this->_var['item']['url']; ?>">
 	    			<i class="diyfont yuan" style="background-color:<?php echo $this->_var['item']['color']; ?>"><?php echo $this->_var['item']['icon_name']; ?></i>
				<span class="txt"><?php echo $this->_var['item']['name']; ?></span>
				</a>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		  	</li>
		  	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		  </ul>
		  </div>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		var i=<?php echo $i; ?>;
		if (i>1) {
			TouchSlide({
			slideCell:"#sort_nav",
			effect:"leftLoop",
			mainCell:"#sort_nav ul",
			autoPlay:false,
			pnLoop:false,
			});
		};
	});
	</script>
	<?php endif; ?>
	
    <!--公告-->
    <?php if ($this->_var['data']['newest_lottery_list']): ?>
    <div class="notice-box split-line">
        <div class="left-icon">
          <span class="iconfont">&#xe6c0;</span>
        </div>
        <div class="r-content">
          <ul class="n-list-box">
           <?php $_from = $this->_var['data']['newest_lottery_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lottery_unit');if (count($_from)):
    foreach ($_from AS $this->_var['lottery_unit']):
?>
            <li class="n-item">
				<a href="<?php
echo parse_url_tag("u:index|duobao#index|"."data_id=".$this->_var['lottery_unit']['id']."".""); 
?>">
				<span>恭喜</span>
				<?php echo $this->_var['lottery_unit']['user_name']; ?>
				<span><?php echo $this->_var['lottery_unit']['span_time']; ?>获得<?php echo $this->_var['lottery_unit']['name']; ?></span></a>
			</li>
		   <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </ul>
        </div>
    </div>
    <?php endif; ?>
    <!--end 公告-->
    
	<!--最新揭晓-->
	<?php if ($this->_var['data']['newest_doubao_list']): ?>
	<div class="m-index-mod split-line">
	  <div class="m-index-mod-hd split-line">
	    <h3 style="color:#DB3652;font-weight: 600;font-size: 0.6rem;">最新揭晓</h3>
	  </div>
	  <div class="m-index-mod-cont">
	    <ul class="w-goodslist-item">
	    <?php $_from = $this->_var['data']['newest_doubao_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'newest_unit');if (count($_from)):
    foreach ($_from AS $this->_var['newest_unit']):
?>
	      <li class="w-goods-item">
	        <div class="w-goods-pic">
	          <a href="<?php
echo parse_url_tag("u:index|duobao#index|"."data_id=".$this->_var['newest_unit']['id']."".""); 
?>" style="display: block;">
	            <img src="<?php echo $this->_var['newest_unit']['icon']; ?>"/>
	          </a>
	        </div>
	
			<?php if ($this->_var['newest_unit']['has_lottery'] == 0): ?>
	        <!--即将开奖-->
	        <div class="w-countdown" data-pro="">
	          <span class="w-countdown-title">倒计时</span>
	          <span id="no1" class="w-countdown-nums" nowtime="<?php echo $this->_var['data']['now_time']; ?>" endtime="<?php echo $this->_var['newest_unit']['lottery_time']; ?>"></span>
	        </div>
	        <div class="w-countwaiting">
	          <div class="w-countwaiting" data-pro="countdownwaiting" style="display:none">正在计算...</div>
	        </div>
	        <!--end即将开奖-->
			<?php else: ?>
	        <!--中奖了-->
		        <p class="w-goods-user f-txtabb" style="color:#DB3652;font-weight: 600">恭喜&nbsp;<a href="<?php
echo parse_url_tag("u:index|anno_user_center|"."lucky_user_id=".$this->_var['newest_unit']['luck_user_id']."".""); 
?>"><?php echo $this->_var['newest_unit']['luck_user_name']; ?></a>中奖</p>
				<p class="w-goods-sn f-txtabb" style="width: 120px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">幸运号<span><?php echo $this->_var['newest_unit']['lottery_sn']; ?></span></p>
        	<!--end 中奖了-->
			<?php endif; ?>
	      </li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	    </ul>
	  </div>
	</div>
	<?php endif; ?>
	<!--end 最新揭晓-->
	<div id="set-top-height"></div>
	<div class="slider-nav split-line-top split-line">
	  <ul style="margin-right: 15px;">
	    <?php $_from = $this->_var['data']['mnav_sort']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sort_config');if (count($_from)):
    foreach ($_from AS $this->_var['sort_config']):
?>
	    <?php if ($this->_var['sort_config']['is_effect'] == 1): ?>
    		<?php if ($this->_var['sort_config']['config_name'] != max_buy): ?>
		  	<li class="nav-item"><a onclick="order_sort(this);" sort="<?php echo $this->_var['sort_config']['config_name']; ?>" class="<?php if ($this->_var['data']['order'] == $this->_var['sort_config']['config_name']): ?> cur<?php endif; ?>" href="javascript:;" value="<?php echo $this->_var['sort_config']['config_name']; ?>"><?php echo $this->_var['sort_config']['name']; ?></a></li>
	    	<?php else: ?>
	    	<li class="nav-item">
	    	<a onclick="order_sort(this);" sort="<?php echo $this->_var['sort_config']['config_name']; ?>" order_dir="0" class="sort-box last <?php if ($this->_var['data']['order'] == $this->_var['sort_config']['config_name']): ?> cur f-down<?php endif; ?>" href="javascript:void(0);"><?php echo $this->_var['sort_config']['name']; ?>
	    		<span class="sort-icon iconfont i-up <?php if ($this->_var['data']['order_dir'] == 1): ?>xz<?php endif; ?>" order_dir="1">&#xe6c4;</span>
		        <span class="sort-icon iconfont i-down <?php if ($this->_var['data']['order_dir'] == 0): ?>xz<?php endif; ?>"  order_dir="0">&#xe6c3;</span>
	        </a>
	        </li>
	    	<?php endif; ?>
	    <?php endif; ?>
  		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	  </ul>
	</div>
	
	<div style="width:100%; text-align:center; padding:0.2rem 0; " class="loading-list">
			<img style="width:1rem; height:1rem;" src="./wap/Tpl/main/images/refreshing_1.gif" />
	</div>
	
    <div class="content">
      <?php if ($this->_var['data']['index_duobao_list']): ?>
       <!--在此处改变类名改变样式 tuan-ul-list  tuan-ul-img-->
       <ul class="tuan-ul tuan-ul-img split-line-top">
		<?php $_from = $this->_var['res']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
		   <li class="tuan_li split-line">
			   <a class="blw" href="<?php
echo parse_url_tag("u:index|duobao#index|"."data_id=".$this->_var['v']['id']."".""); 
?>">
			   <div class="pic">
				   <?php if ($this->_var['v']['min_buy'] == 10 || $this->_var['v']['unit_price'] == 10): ?>
				   <div class="tenyen"></div>
				   <?php endif; ?>
				   <?php if ($this->_var['v']['unit_price'] == 100): ?>
				   <div class="hundredyen"></div>
				   <?php endif; ?>
				   <img src="<?php echo $this->_var['v']['icon']; ?>" lazy="true" />
			   </div>
			   </a>
			   <div class="info">
				   <div class="info-tit">
					   <?php if ($this->_var['v']['is_topspeed']): ?>
					   <em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">极速</em>
					   <?php endif; ?>
					 
					   	<?php if ($this->_var['v']['fair_type'] == five): ?>
					 	<em style="background-color:#ff2300;padding: 3px 5px;color: #fff;font-size: 12px;">五倍</em>
					  <?php endif; ?>
					   <?php echo $this->_var['v']['name']; ?>
				   </div>

				   <div class="progress-box">
					   <div class="left-box">
						   <progress max="<?php echo $this->_var['v']['max_buy']; ?>" value="<?php echo $this->_var['v']['current_buy']; ?>"></progress>
						   <div class="fl">
							   <p class="txt-red"><?php echo $this->_var['v']['current_buy']; ?></p>
							   <p>已参与人次</p>
						   </div>
						   <div class="fr">
							   <p class="txt-red"><?php echo $this->_var['v']['surplus_buy']; ?></p>
							   <p>剩余人次</p>
						   </div>
					   </div>
					   <a data-id="<?php echo $this->_var['v']['id']; ?>" class="right-box add_cart_item" unit_price="<?php echo $this->_var['v']['unit_price']; ?>" buy_num="<?php echo $this->_var['v']['min_buy']; ?>" data_id="<?php echo $this->_var['v']['id']; ?>" rel="<?php echo $this->_var['v']['icon']; ?>"><i class="iconfont">&#xe658;</i></a>
				   </div>
			   </div>
		   </li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      	<?php $_from = $this->_var['data']['index_duobao_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
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
       </ul>

        <?php if ($this->_var['pages']): ?>
        	<a href="<?php
echo parse_url_tag("u:index|duobaos|"."".""); 
?>" class="more_duobao page-load">查看更多</a>
        <?php endif; ?>
      <?php else: ?>
       <div class="null_data">
        <p class="icon"><i class="iconfont">&#xe6e8;</i></p>
        <p class="message">暂无数据</p>
       </div>
      <?php endif; ?>
    </div>
</div>
<!--右侧悬浮菜单-->
<!-- <div class="slide">
	<ul class="icon">
		<li class="up" title="上一页"></li>
		<li class="qq"></li>
		<li class="tel"></li>
		<li class="wx"></li>
		<li class="down" title="下一页"></li>
	</ul>
	<ul class="info">
		<li class="qq">
			<p>在线沟通，请点我<a href="mqqwpa://im/chat?chat_type=wpa&uin=1141122187&version=1&src_type=web&web_src=qq.com" target="_blank"><img border="0" src="http://wpa.qq.com/pa?p=2:784208462:52" alt="点击这里给我发消息"> 在线咨询</a>
			</p>
		</li>
		<li class="tel">
			<p>咨询热线：<br>027-59260885 <br></p>

		</li>
		<li class="wx">
			<div class="img"><img src="Tpl/main/images/weixin.jpg" /></div>
		</li>
	</ul>
</div>
<div id="btn" class="index_cy"></div> -->

<?php if ($this->_var['ajax_refresh'] == 0): ?>

<div class="ecv_log_suc" style="z-index: 9999;">
    <i class="iconfont" >&#xe70a;</i>
    <p>夺宝币已存入余额</p>
    <p>付款时可以直接使用</p>
</div>

<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/add_to_list.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>

<?php endif; ?>
