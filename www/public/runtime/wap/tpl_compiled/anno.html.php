<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/style.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/color.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/public.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/anno.css";
$this->_var['pagecss'][] = $this->_var['TMPL_REAL']."/css/pull_refresh.css";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/fastclick.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery-1.6.2.min.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/utils/jquery.timer.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/fanwe_utils/fanweUI.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/script.js";



$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/anno.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/anno.js";

$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/lib/touche.js";
$this->_var['pagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";
$this->_var['cpagejs'][] = $this->_var['TMPL_REAL']."/js/pull_refresh/pull-refresh.js";

?>

<?php echo $this->fetch('inc/header_title_only.html'); ?>

<?php endif; ?> 

<div class="wrap loading_container" id="loading_container">
    
      <ul class="goods-list scroll_bottom_list">

                <!--start 五倍开奖-->
          <?php if ($this->_var['duobao']): ?>
          <?php $_from = $this->_var['duobao']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
          <li>
              <a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">
              <div class="imgbox">
                  <?php if ($this->_var['item']['min_buy'] == 10): ?>
                  <div class="tenyen"></div>
                  <?php endif; ?>
                  <a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">
                  <img src="<?php echo $this->_var['item']['icon']; ?>" alt="">
                  </a>
              </div>
              <div class="txtbox announced">
                  <h1><a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">
                      <?php echo $this->_var['item']['name']; ?>
                      </a></h1>
                  <ul class="txtlist fl">
                      <li>期<em></em><em></em><em></em><em></em>号：<span class="code"><?php echo $this->_var['item']['id']; ?></sapn></li>
                      <li>获<em></em>得<em></em>者：<span class="user"><?php echo $this->_var['item']['luck_user_id']; ?></span></li>
                      <li>参与人次：<span class="people"><?php echo $this->_var['item']['min_buy']; ?></span></li>
                      <li>幸运号码：<span class="luckycode" style="width: 100px; overflow: hidden; text-overflow:ellipsis; white-space: nowrap;"><?php echo $this->_var['item']['lottery_sn']; ?></span></li>
                      <li>揭晓时间：<time><?php echo $this->_var['item']['lottery_time']; ?></time></li>
                  </ul>
              </div>
          </li>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          <?php endif; ?>
          <!--end 五倍开奖-->
         <?php if ($this->_var['list']): ?>
      	<?php $_from = $this->_var['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
        <li>
        <a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">
            <div class="imgbox">
            	<?php if ($this->_var['item']['min_buy'] == 10): ?>
                	<div class="tenyen"></div>
              	<?php endif; ?>
              	<a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">
            		<img src="<?php echo $this->_var['item']['icon']; ?>" alt="">
           		</a>
            </div>

            <?php if ($this->_var['item']['has_lottery'] == 1): ?>
            <div class="txtbox announced">
              <h1><a href="<?php
echo parse_url_tag("u:index|duobao|"."data_id=".$this->_var['item']['id']."".""); 
?>">
            		<?php echo $this->_var['item']['duobaoitem_name']; ?>
           		</a></h1>
              <ul class="txtlist fl">
                <li>期<em></em><em></em><em></em><em></em>号：<span class="code"><?php echo $this->_var['item']['id']; ?></sapn></li>
                <li>获<em></em>得<em></em>者：<span class="user"><a href="<?php
echo parse_url_tag("u:index|anno_user_center|"."lucky_user_id=".$this->_var['item']['luck_user_id']."".""); 
?>"><?php echo $this->_var['item']['luck_user_name']; ?></a></span></li>
                <li>参与人次：<span class="people"><?php echo $this->_var['item']['luck_user_buy_count']; ?></span></li>
                <li>幸运号码：<span class="luckycode"><?php echo $this->_var['item']['lottery_sn']; ?></span></li>
                <li>揭晓时间：<time><?php echo $this->_var['item']['date']; ?><?php echo $this->_var['item']['lottery_time_show']; ?></time></li>
              </ul>
            </div>
            <!--
            <div style="display:none;" class="txtbox announcing">
	              <h1>中国黄金 AU9999万足金50g薄片</h1>
	              <p class="code">
	               		 期号：
	                <span>301202399</span>
	              </p>
	              <h2>
	                <i class="iconfont">&#xe629;</i>
	                	<span class="set_hint_info">即将开奖</span>
	              </h2>

	              <time class="w-countdown-nums" nowtime="<?php echo $this->_var['now_time']; ?>000" endtime="<?php echo $this->_var['item']['lottery_time']; ?>000">11:36:79</time>
             </div>
            -->
            <?php else: ?>

            <!--
            <div style="display:none;" class="txtbox announced">
              <div class="flexbox">
              <h1></h1>
              </div>
              <ul class="txtlist fl">
                <li>期号</li>
                <li>获得者</li>
                <li>参与人次</li>
                <li>幸运号码</li>
                <li>揭晓时间</li>
              </ul>
              <ul class="fl">
                <li>:<span class="code"></sapn></li>
                <li>:<span class="user"></span></li>
                <li>:<span class="people"></span></li>
                <li>:<span class="luckycode"></span></li>
                <li>:<time></time></li>
              </ul>
              <div class="clear"></div>
            </div>
            -->
            <div class="txtbox announcing">
	              <h1><?php echo $this->_var['item']['duobaoitem_name']; ?></h1>
	              <p class="code">
	               		 期号：
	                <span><?php echo $this->_var['item']['id']; ?></span>
	              </p>
	              <h2>
	                <i class="iconfont">&#xe629;</i>
	                	<span class="set_hint_info">即将开奖</span>
	              </h2>

	              <time class="w-countdown-nums ex_count_down" nowtime="<?php echo $this->_var['now_time']; ?>" endtime="<?php echo $this->_var['item']['lottery_time']; ?>">00:00:00</time>
                      <div class="w-countwaiting" data-pro="countdownwaiting" style="">正在计算...</div>
	              <!-- <time class="w-countdown-nums" nowtime="1453335545750" endtime="1453335546770">11:36:79</time>  -->
             </div>

            <?php endif; ?>

          </a>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<div class="clear"></div>
    </ul>
	<?php if ($this->_var['pages']): ?>
	<div class="fy scroll_bottom_page">
		<?php echo $this->_var['pages']; ?>
	</div>
	<?php endif; ?>
	<!-- 无数据↓ -->
	<?php else: ?>
    <div class="null_data">
      <p class="icon"><i class="iconfont">&#xe6e8;</i></p>
      <p class="message">暂无数据</p>
    </div>
	<?php endif; ?>
</div>
<!-- 大喜报 -->
  <div id="imgs1" style="display: none;width: 100%;height: 100%;background-color: black;opacity: 0.6;z-index: 999;position: fixed;top: 0;left: 0;"></div>
  <div id="qrcod1" style="display: none;z-index: 9999;width: 320px;position:fixed;height: 320px;top: 30%;left: 50%;margin: -160px 0 0 -156px;">
  <div style="text-align:center;line-height:320px">
    <img src="http://122.114.94.153/wap/Tpl/main/images/daxibao.png" width="310px" height="310px">
  </div>
  <div id="worder" style="display: none;text-align:center;color:#fff">
    <span style="text-align:center;color:#fff;font-size:20px;"><b>夺宝联盟恭喜您获得</b></span><br/>
      
      <?php if ($this->_var['res2']): ?>
       <div id="commodity" style="text-align:center;color:#D68034;font-size:20px;height:34px;width:310px;white-space:nowrap;text-overflow:ellipsis;overflow: hidden;"><?php echo $this->_var['res2']['name']; ?></div><br/>
      <div id="bot" style="text-align:center;height: 35px;width: 150px;background: #F8D362;border-radius:80px;box-shadow: 0 8px 16px 0 rgba(0,0,0,0.7), 0 6px 20px 0 rgba(0,0,0,0.7);margin-left: 81px;">
       <div style="color:#CA472F;font-size:20px;line-height:35px;">商品详情</div>
      </div>
      <?php else: ?>
       <div id="commodity" style="text-align:center;color:#D68034;font-size:20px;height:34px;width:310px;white-space:nowrap;text-overflow:ellipsis;overflow: hidden;"><?php echo $this->_var['res1']['name']; ?></div><br/>
        <div id="bot1" style="text-align:center;height: 35px;width: 150px;background: #F8D362;border-radius:80px;box-shadow: 0 8px 16px 0 rgba(0,0,0,0.7), 0 6px 20px 0 rgba(0,0,0,0.7);margin-left: 81px;">
    	<div style="color:#CA472F;font-size:20px;line-height:35px;">填写配送地址</div>
    	</div>
      <?php endif; ?>
      
    
  </div>
  <div id="res1" style="display:none">
     <?php echo $this->_var['res1']['id']; ?>
  </div>
</div>
<script type="text/javascript">
//大喜报
$(function(){
	$('.menu_box>li').eq(1).children('a').children('p').children('img').attr('src','./wap/Tpl/main/images/menu/jier.png');
    var commodity = $("#commodity").html();
    var lock = false;
  /*设置cookie，cookie是以字符串形式存储的，可以有很多参数，但必要的一个是cookie 的名称tiaozhuan*/
    function setcookie(){
      var d=new Date();
      var res1 = $.trim($("#res1").html());
      d.setTime(d.getTime()+24*60*60*1000);  //设置过去时间为当前时间增加一天
      document.cookie="tanchuang="+res1+";expires="+d.toGMTString(); //expires是cookie的一个可选参数，设置cookie的过期时间
      var res=document.cookie;
      return res;  //返回cookie字符串
    }
    //获取cookie字符串
    var strCookie=document.cookie;
    //将多cookie切割为多个名/值对
    var arrCookie=strCookie.split("; ");
    var tanchuang;
    //遍历cookie数组，处理每个cookie对
    for(var i=0;i<arrCookie.length;i++){
       var arr=arrCookie[i].split("=");
       //找到名称为tiaozhuan的cookie，并返回它的值
       if("tanchuang"==arr[0]){
              tanchuang=arr[1];
              break;
       }               
    }
    res2 = $.trim($("#res1").html());
    // console.log(res2);
    if(tanchuang != +res2){
       if (commodity !=null && commodity !=undefined && commodity !="") {
        lock = true;
          setTimeout(function(){
            $("#imgs1").css("display","block");
            $("#qrcod1").css("display","block");
            $("#worder").css("display","block");
             setcookie();
          },1000);
      }else{
        lock = false;
      }
    }else{
      $("#imgs1").css("display","none");
      $("#qrcod1").css("display","none");
      $("#worder").css("display","none");
    }
    $("#bot").click(function(){
      var res1 = $.trim($("#res1").html());
        window.location.href="http://122.114.94.153/wap/index.php?ctl=anno&act=ticket";
        $("#imgs1").css("display","none");
      	$("#qrcod1").css("display","none");
      	$("#worder").css("display","none");
    });
    $("#bot1").click(function(){
      var res1 = $.trim($("#res1").html());
        window.location.href="http://122.114.94.153/wap/index.php?ctl=uc_address&show_prog=1";
        $("#imgs1").css("display","none");
      	$("#qrcod1").css("display","none");
      	$("#worder").css("display","none");
    });
});
</script>

<?php if ($this->_var['ajax_refresh'] == 0): ?>
<?php echo $this->fetch('inc/footer_menu.html'); ?>
<?php echo $this->fetch('inc/footer_index.html'); ?>
<?php endif; ?>
