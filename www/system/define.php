<?php 
define("IS_DEBUG",0);
define("SHOW_DEBUG",0);
define("SHOW_LOG",0);
define("MAX_DYNAMIC_CACHE_SIZE",1000);  //动态缓存最数量
define("SMS_TIMESPAN",60);  //短信验证码发送的时间间隔
define("SMS_EXPIRESPAN",300);  //短信验证码失效时间
define("NOW_TIME",get_gmtime());   //当前UTC时间戳
define("CLIENT_IP",get_client_ip());  //当前客户端IP
define("SITE_DOMAIN",get_domain());   //站点域名
define("PIN_PAGE_SIZE",80);
define("PIN_SECTOR",10);
define("MAX_SP_IMAGE",20); //商家的最大图片量
define("MAX_LOGIN_TIME",1200);  //登录的过期时间
define("SESSION_TIME",3600*24); //session超时时间
define("ORDER_DELIVERY_EXPIRE",7);  //延期收货天
define("PI",3.14159265); //圆周率
define("EARTH_R",6378137); //地球平均半径(米)
define("FX_LEVEL",3);  //分销的等级 
define("DISCOVER_DISTANCE",5000);  //discover栏目显示5公里以内的动态 此配置需移至public/sys_config.php
define("APP_SMS_VERIFY",1);  //手机端是否开启短信验证码
define("DEFAULT_LOTTERY",111111); //采集失败时的默认开奖号
define("WEIXIN_TYPE",'account'); //account:微信公众号，platform:微信第三方平台
?>