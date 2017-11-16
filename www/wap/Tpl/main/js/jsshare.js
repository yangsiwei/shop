$(document).ready(function(){
	wx.config({
		  debug: false,
		  appId: appId,
		  timestamp: timestamp,
		  nonceStr: nonceStr,
		  signature: signature,
		  jsApiList: [
		    // 所有要调用的 API 都要加到这个列表中
		    'onMenuShareAppMessage',
		    'onMenuShareTimeline',
		    'onMenuShareQQ',
		    'onMenuShareWeibo',
		    'onMenuShareQZone',
		  ]
		});
	
		// 分享给朋友
		wx.ready(function () {
		  // 在这里调用 API
			wx.onMenuShareAppMessage({
			    title: page_title, // 分享标题
			    link: shar_url, // 分享链接
			    imgUrl: imgUrl, // 分享图标
			    success: function () { 
			        // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
			
			// 分享到朋友圈
			wx.onMenuShareTimeline({
			    title: page_title, // 分享标题
			    link: shar_url, // 分享链接
			    imgUrl: imgUrl, // 分享图标
			    success: function () { 
			        // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
			
			// 分享到qq
			wx.onMenuShareQQ({
			    title: page_title, // 分享标题
			    link: shar_url, // 分享链接
			    imgUrl: imgUrl, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			       // 用户取消分享后执行的回调函数
			    }
			});
			
			// 分享到腾讯微博
			wx.onMenuShareWeibo({
			    title: page_title, // 分享标题
			    link: shar_url, // 分享链接
			    imgUrl: imgUrl, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
			
			// 分享到qq空间
			wx.onMenuShareQZone({
			    title: page_title, // 分享标题
			    link: shar_url, // 分享链接
			    imgUrl: imgUrl, // 分享图标
			    success: function () { 
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
			
			
		});
		
		
});
