(function($) {   
	

	/**
	 * 下拉框的UI初始化
	 * options {id:唯一ID,event:hover/click用于响应移上事件还是点击事件,refresh:刷新}
	 */
	$.fn.ui_select = function(options) {		
		var op = {id:0,event:"click",refresh:false};
		options = $.extend({},op, options);
		var o = $(this);
		var height = $(o).attr("height");
		$(o).hide();
		
		if(options.refresh)
		{
			$(o).show();			
			options.id = $(o).next().attr("id");
			$(o).next().remove();			
		}
		
		//以下是初始化虚拟夺宝币素
		var DLselect = $("<dl id='"+options.id+"'></dl>"); //创建dl形态的select外框
		$(DLselect).attr("class",$(o).attr("class")); //复制样式
		$(DLselect).attr("name",$(o).attr("name")); //复制名称
		$(DLselect).css({"display":"inline-block"});
		
		var DTselect = $("<dt></dt>"); //已选中的
		$(DLselect).append(DTselect);
		$(DTselect).attr("class","ui-select-selected");
		var selectNode = $(o).find("option:selected");
		$(DTselect).html("<span>"+selectNode.html()+"</span><i>▼</i>");
		$(DTselect).attr("value",selectNode.attr("value"));
		
		var DDselect = $("<dd></dd>"); //下拉列表		
		$(DLselect).append(DDselect);
		
		$(o).find("option").each(function(ii,oo){
			var SPANselect = $("<a href='javascript:void(0);'></a>");
			$(SPANselect).css({"display":"block"});
			$(SPANselect).attr("value",$(oo).attr("value"));
			$(SPANselect).html($(oo).html());
			if(selectNode.attr("value")==$(oo).attr("value"))
				SPANselect.addClass("current");
			$(DDselect).append(SPANselect);
		});
		$(o).after(DLselect);
		
		//开始为下拉框初始化样式
		$(DDselect).css({"position":"absolute","z-index":50});
		$(DDselect).addClass("ui-select-drop");
		var top = $(DLselect).position().top + $(DLselect).height();
		var left = $(DLselect).position().left;
		$(DDselect).css("left",left);
		$(DDselect).css("top",top);
		if(height&&$(DDselect).height()>parseInt(height))
		{
			$(DDselect).css("height",parseInt(height));
		}
		$(DDselect).hide();
		
		if(options.refresh)
		$(o).hide();
		
		//以下绑定虚拟夺宝币素事件
		if(options.event=="click")
		{
			$(DLselect).bind("click",function(){
				var top = $(this).position().top + $(this).height();
				var ui_top_left = 'FW-XS-Y-160725071';
				var left = $(this).position().left;
				$(this).find("dd").css("left",left);
				$(this).find("dd").css("top",top);			
				$(this).find("dd").slideDown("fast");
				$(this).addClass("dropdown");
			});
		}
		else
		{
			$(DLselect).hover(function(){
				$(this).oneTime(100,function(){
					var top = $(this).position().top + $(this).height();
					var left = $(this).position().left;
					$(this).find("dd").css("left",left);
					$(this).find("dd").css("top",top);			
					$(this).find("dd").slideDown("fast");
					$(this).addClass("dropdown");
				});
				
			},function(){
				$(this).stopTime();
				$(this).find("dd").fadeOut("fast");
				$(this).removeClass("dropdown");
			});
		}
		
		$(DLselect).find("dd a").bind("click",function(){
			var dl = $(this).parent().parent();
			var span = $(this);
			$(dl).find("dt").html("<span>"+$(span).html()+"</span><i>▼</i>");
			$(dl).find("dt").attr("value",$(span).attr("value"));			
			$(dl).prev().val($(span).attr("value"));
			$(dl).prev().trigger("change");
			$(dl).find("dd a").removeClass("current");
			$(this).addClass("current");
		});
		
	},
	
	
	/**
	 * 初始化按钮
	 */
	$.fn.ui_button = function(){

		var btn = $(this);
		if(btn.css("display")=="none")return;
		$(btn).hide();
		
		var o = $("<div><div><span></span></div></div>");
		$(btn).after(o);
		
		$(o).attr("class",$(btn).attr("class"));
		$(o).addClass($(btn).attr("rel"));
		$(o).attr("rel",$(btn).attr("rel"));
		$(o).find("span").html($(btn).html());
		
		$(o).bind("click",function(){
			if(btn.attr("type")=="submit")	
			{
				//开始寻找父级的表单
				var parent = btn.parent();
				try{
					while(parent.get(0).tagName.toLowerCase()!="form")
					{
						parent = parent.parent();
					}
					parent.submit();
				}
				catch(e)
				{
					$(btn).click();
				}
			}
			else
			{
				if(btn.parent()[0].tagName=="A")
				{

				}
				else
				$(btn).click();
			}
			
		});
		
		$(o).bind("mouseover",function(){
			$(o).removeClass($(o).attr("rel")+"_hover");
			$(o).removeClass($(o).attr("rel")+"_active");
			$(o).removeClass($(o).attr("rel"));
			$(o).addClass($(o).attr("rel")+"_hover");		
			
		});
		
		$(o).bind("mouseout",function(){
			$(o).removeClass($(o).attr("rel")+"_hover");
			$(o).removeClass($(o).attr("rel")+"_active");
			$(o).removeClass($(o).attr("rel"));
			$(o).addClass($(o).attr("rel"));
		});
		
		$(o).bind("mousedown",function(){
			$(o).removeClass($(o).attr("rel")+"_hover");
			$(o).removeClass($(o).attr("rel")+"_active");
			$(o).removeClass($(o).attr("rel"));
			$(o).addClass($(o).attr("rel")+"_active");
		});
		
		$(o).bind("mouseup",function(){
			$(o).removeClass($(o).attr("rel")+"_hover");
			$(o).removeClass($(o).attr("rel")+"_active");
			$(o).removeClass($(o).attr("rel"));
			$(o).addClass($(o).attr("rel")+"_hover");
		});
		
	},
	
	
	/**
	 * 初始化输入框
	 */
	$.fn.ui_textbox = function(options){
		
		var op = {refresh:false}; 
		options = $.extend({},op, options);
	
		var obj = $(this);
		if(options.refresh)
		{
			var holder = $(obj).prev();
			 if($.trim($(obj).val())!="")
             {
                 $(holder).css("display","none");
             }
			return;
		}
		
		//添加聚焦与取消的事件样式
		$(obj).bind("focus",function(){    	
            $(obj).removeClass("hover");
            $(obj).removeClass("normal");
            $(obj).addClass("hover");
	    });
	    $(obj).bind("blur",function(){
	            $(obj).removeClass("hover");
	            $(obj).removeClass("normal");
	            $(obj).addClass("normal");
	    });
	    
	    
	    //初始化holder
	    if($(obj).attr("holder")==""||!$(obj).attr("holder"))return;
	    if('placeholder' in document.createElement('input'))
        {
             $(obj).attr("placeholder",$(obj).attr("holder"));
        }
        else 	 
        { 	  
             var holder = $(obj).prev();
             if($(holder).attr("rel")!="holder"&&$(obj).attr("holder"))
             {
	                holder = $("<span style='position:absolute; color:#666;' class='fanwe-ui-holder' rel='holder'>"+$(obj).attr("holder")+"</span>");
	                $(holder).css({"font-size":$(obj).css("font-size"),"line-height":$(obj).css("line-height"),"padding-left":$(obj).css("padding-left"),"padding-right":$(obj).css("padding-right"),"padding-top":$(obj).css("padding-top"),"padding-bottom":$(obj).css("padding-bottom")});
	                $(holder).css("left",0);
	                $(holder).css("top",0);
	                //$(holder).css("top",$(obj).position().top);
	                //$(holder).css("width",$(obj).width());
	                var outer = $(obj).wrap("<i style='font-style:normal; display:block;'></i>");
	                 
	                $(obj).parent().css("position","relative");
	                $(obj).before(holder);
             }
             if($.trim($(obj).val())!="")
             {
                 $(holder).css("display","none");
             }
            
             $(holder).click(function(){
                 $(obj).focus();
             });    
             
             $(obj).focus(function(){
                 $(holder).css("display","none");
             });
             $(obj).blur(function(){
                 if($.trim($(obj).val())=="")
                 $(holder).show();
             });
        }
	},
	
	/**
	 * 初始化复选框,该UI必为label，且内部需要有checkbox
	 */
	$.fn.ui_checkbox = function(options){
		
		var op = {refresh:false};
		options = $.extend({},op, options);
		
		var ImgCbo = $(this); //label
		var o = $(ImgCbo).find("input[type='checkbox']");
		$(o).hide();
		var checked = $(o).attr("checked");
		var relClass = $(ImgCbo).attr("rel");
		$(ImgCbo).addClass(relClass);
		$(ImgCbo).attr("name",$(o).attr("name")); //复制名称
		$(ImgCbo).css({"display":"inline-block"});
		$(ImgCbo).attr("checked",checked?true:false);
		if(checked)
		{
			$(ImgCbo).removeClass(relClass);
			$(ImgCbo).removeClass(relClass+"_hover");
			$(ImgCbo).removeClass(relClass+"_checked");
			$(ImgCbo).addClass(relClass+"_checked");
		}
		else
		{
			$(ImgCbo).removeClass(relClass);
			$(ImgCbo).removeClass(relClass+"_hover");
			$(ImgCbo).removeClass(relClass+"_checked");		
			$(ImgCbo).addClass(relClass);
		}
		
		if(options.refresh)return;
		
				
		$(o).bind("click",function(){
			return false;
		});		
		
		$(ImgCbo).hover(function(){
			var cbo = $(this).find("input[type='checkbox']");
			var checked = $(cbo).attr("checked");
			var relClass = $(ImgCbo).attr("rel");
			if(!checked)
			$(this).addClass(relClass+"_hover");
		},function(){
			$(this).removeClass(relClass+"_hover");
		});
		$(ImgCbo).bind("click",function(){
			var img = $(this);
			var cbo = $(img).find("input[type='checkbox']");
			var checked = $(cbo).attr("checked");
			var relClass = $(ImgCbo).attr("rel");
			checked = checked?false:true;
			$(cbo).attr("checked",checked);	
					
			$(img).attr("checked",checked);
			$(img).removeClass(relClass+"_hover");
			if(checked)
			{
				$(cbo).trigger("checkon");
				$(img).removeClass(relClass);
				$(img).removeClass(relClass+"_checked");
				$(img).addClass(relClass+"_checked");
			}
			else
			{
				$(cbo).trigger("checkoff");
				$(img).removeClass(relClass);
				$(img).removeClass(relClass+"_checked");
				$(img).addClass(relClass);
			}
		});
	},
	
	/**
	 * 初始化单选框,该UI必为label，且内部需要有radio
	 */
	$.fn.ui_radiobox = function(options){
		
		var op = {refresh:false};
		options = $.extend({},op, options);
		
		var ImgCbo = $(this); //label
		var o = $(ImgCbo).find("input[type='radio']");
		$(o).hide();
		var checked = $(o).attr("checked");
		var relClass = $(ImgCbo).attr("rel");
		$(ImgCbo).addClass(relClass);
		$(ImgCbo).attr("name",$(o).attr("name")); //复制名称
		$(ImgCbo).css({"display":"inline-block"});
		$(ImgCbo).attr("checked",checked?true:false);
		if(checked)
		{
			$(ImgCbo).removeClass(relClass);
			$(ImgCbo).removeClass(relClass+"_checked");
			$(ImgCbo).addClass(relClass+"_checked");
		}
		else
		{
			$(ImgCbo).removeClass(relClass);
			$(ImgCbo).removeClass(relClass+"_checked");		
			$(ImgCbo).addClass(relClass);
		}
		
		if(options.refresh)return;
		
				
		$(o).bind("click",function(){
			return false;
		});		
		
		$(ImgCbo).hover(function(){
			var cbo = $(this).find("input[type='radio']");
			var checked = $(cbo).attr("checked");
			var relClass = $(ImgCbo).attr("rel");
			if(!checked)
			$(this).addClass(relClass+"_hover");
		},function(){
			$(this).removeClass(relClass+"_hover");
		});
		$(ImgCbo).bind("click",function(){
			var img = $(this);
			var cbo = $(img).find("input[type='radio']");
			var checked = $(cbo).attr("checked");
			var relClass = $(ImgCbo).attr("rel");
			var ochecked = checked; //原始选中状态				
			checked = true;
			$(cbo).attr("checked",checked);	
					
			$(img).attr("checked",checked);
			$(img).removeClass(relClass+"_hover");
			
			$("input[name='"+img.attr("name")+"'][type='radio']").parent().each(function(i,olb){
				$(olb).ui_radiobox({refresh:true});
			});
			
			if(!ochecked)
			{
				$(cbo).trigger("checked");
				$(img).removeClass(relClass);
				$(img).removeClass(relClass+"_checked");
				$(img).addClass(relClass+"_checked");
			}
		});
	},
	
	
	/**
	 * 初始星级投票框
	 * 依赖input夺宝币素生成星级投票ui value：为当前的分值, disabled:true不可操作
	 * 引发事件//onchange:值的变化的回调, uichange:移动时的实时变化 
	 */
	$.fn.ui_starbar = function(options){
		
		var op = {refresh:false,max:5}; 
		options = $.extend({},op, options);
		
		var ipt = $(this); //input
		$(ipt).hide();
		
		var disabled = $(ipt).attr("disabled");		
		//对值的修正
		var val = $(ipt).val();
		if(isNaN(val))val = 0;
		if(val<0)val = 0;
		if(val>options.max)val = options.max;//end		
		
		if(!options.refresh)
		$(ipt).wrap("<span><span></span></span>");
		var outBar = $(ipt).parent().parent();
		outBar.attr("class",$(ipt).attr("class"));
		
		$(outBar).find("span").css("width",(parseFloat(val)/options.max*100)+"%");
		
		if(!options.refresh&&!disabled) //非禁用且非刷新时绑定事件
		{
			var total_width = $(outBar).width();
		    var sec_width = total_width / options.max;
		    
		    $(outBar).bind("mousemove mouseover",function(event){
		    	//绑定移动事件
		    	var pageX = event.pageX; //左移量
		    	var left = $(outBar).offset().left;
		    	var move_left = pageX - left;	    	
		    	var sector = Math.ceil(move_left/sec_width);
		    	var cssWidth = (sector * sec_width) + "px";
		    	$(outBar).find("input").attr("sector",sector);
		    	$(outBar).find("span").css("width",cssWidth);	  

		    	$(outBar).find("input").trigger("uichange");
		    });
		    $(outBar).bind("mouseout",function(){
		    	var current_sec = $(outBar).find("span").find("input").val();
		    	var cssWidth = (current_sec * sec_width) + "px";		    	
		    	$(outBar).find("span").css("width",cssWidth);	
		    	$(outBar).find("input").attr("sector",current_sec);
		    	
		    	$(outBar).find("input").trigger("uichange");
		    });
		    $(outBar).bind("click",function(){
		    	var current_sec = $(outBar).find("input").attr("sector");
		    	$(outBar).find("span").find("input").val(current_sec);	

		    	$(outBar).find("input").trigger("onchange");
		    });
		}
		
	},
	
	/**
	 * 基于plupload的上传控件
	 */
	$.fn.ui_upload = function(options){
		
		var op = {url:UPLOAD_URL,multi:true,FilesAdded:null,FileUploaded:null,UploadComplete:null,Error:null}; 
		options = $.extend({},op, options);		
		var btn = $(this); 

		var uploader = new plupload.Uploader({
			browse_button : btn[0], 
			url : options.url,
			flash_swf_url : UPLOAD_SWF,
			silverlight_xap_url : UPLOAD_XAP,
			multi_selection:options.multi,
			filters : {
				max_file_size : MAX_IMAGE_SIZE,
				mime_types: [
					{title : "Image files", extensions : ALLOW_IMAGE_EXT}
				]
			}
		});

		uploader.init();
		

		/**
		 * 当文件添加到上传队列后触发
		 * 监听函数参数：(uploader,files)
		 * uploader为当前的plupload实例对象，files为一个数组，里面的夺宝币素为本次添加到上传队列里的文件对象
		 */
		uploader.bind('FilesAdded',function(uploader,files){
			if(options.FilesAdded!=null)
			{
				if(options.FilesAdded.call(null,files)!=false)
				{
					uploader.start();
				}
			}
			else
			{
				//添加完直接上传
				uploader.start();
			}	
			
		});
		
		

		/**
		 * 当队列中的某一个文件上传完成后触发
		 * 监听函数参数：(uploader,file,responseObject)
		 * uploader为当前的plupload实例对象，file为触发此事件的文件对象，responseObject为服务器返回的信息对象，它有以下3个属性：response
		 * responseHeaders：服务器返回的头信息
		 * status：服务器返回的http状态码，比如200
		 * 
		 * 返回到外部的为ajaxobj数据，status为false中止上传
		 */
		uploader.bind('FileUploaded',function(uploader,file,responseObject){
			if(options.FileUploaded!=null)
			{
				var ajaxobj = $.parseJSON(responseObject.response);
				options.FileUploaded.call(null,ajaxobj);
				if(ajaxobj.error!=0)
				{
					uploader.stop();
				}
			}
				
		});
		

		/**
		 * 当上传队列中所有文件都上传完成后触发
		 * 监听函数参数：(uploader,files)
		 * uploader为当前的plupload实例对象，files为一个数组，里面的夺宝币素为本次已完成上传的所有文件对象
		 */
		uploader.bind('UploadComplete',function(uploader,files){
			if(options.UploadComplete!=null)
				options.UploadComplete.call(null,files);
		});
		
		
		/**
		 * 当发生触发时触发
		 * 监听函数参数：(uploader,errObject)
		 * uploader为当前的plupload实例对象，errObject为错误对象，它至少包含以下3个属性(因为不同类型的错误，属性可能会不同)：
		 * code：错误代码，具体请参考plupload上定义的表示错误代码的常量属性
		 * file：与该错误相关的文件对象
		 * message：错误信息
		 */
		uploader.bind('Error',function(uploader,errObject){
			if(options.Error!=null)
				options.Error.call(null,errObject);
		});
				
	};
	
	/**
	 * 延时加载图片
	 * 
	 */
	$.fn.ui_lazy = function(options){
		var op = {placeholder:"",src:"",refresh:false};
		options = $.extend({},op, options);
		var imgs = this;	
		imgs.each(function(){
			var img = $(this);			
			var scrolltop = $(window).scrollTop();
			var windheight = $(window).height();
			var imgoffset = img.offset().top;
			if(!img.attr("isload")||options.refresh)
			{
				$(img).attr("src",options.placeholder);
			    if(windheight+scrolltop>=imgoffset&&scrolltop<=imgoffset+img.height())
			    {			    	
			    	if(options.src!="")
			    		img.attr("src",options.src);
			    	else
			    		img.attr("src",img.attr("data-src"));
			    	img.attr("isload",true);			    	
			    }
			}			
		});			
	};
	
	
	
	//倒计时到指定的时间点为止，必需依赖jq.timer
	$.fn.count_down = function(options) {
		
		var fillZero = function (number, digits){  
		    number = String(number);  
		    var length = number.length;  
		    if(number.length<digits){  
		        for(var i=0;i<digits-length;i++){  
		            number = "0"+number;  
		        }  
		    }  
		    return number;  
		} ;
		
		var op = {endtime:0,timespan:0,interval:1,format:"%D天 %H:%M:%S.%MS",callback:null}; //endtime:结束时间(毫秒),timespan:服务器时间与客户端时间的时间差(毫秒),interval:时间间隔(毫秒)
		options = $.extend({},op, options);
		var o = $(this);
		$(o).everyTime(options.interval,function(){
			var nowtime = new Date().getTime(); //当间时间
			nowtime+=options.timespan; //修正过的当前时间
			timeless = options.endtime - nowtime;
			if(timeless<=0)
			{
				$(o).stopTime();
				if(options.callback!=null)
					options.callback.call();
				else
					$(o).html("");
			}
			else
			{
				var mS = fillZero(Math.round(Math.floor(timeless % 1000)/10),2); //毫秒
				if(mS==100)mS="10";
				var nS = fillZero(Math.floor(timeless / 1000 % 60),2);              // 计算秒
	            var nM = fillZero(Math.floor((timeless / 1000 / 60) % 60),2);       //计算分
	            var nH = fillZero(Math.floor((timeless / 1000 / 3600) % 24),2);       //计算小时
	            var nD = fillZero(Math.floor((timeless / 1000 / 3600) / 24),2);        //计算天
	            	
	            var BM=nM.substr(0,1);
	            var SM=nM.substr(1,1);
	            
	            var BS=nS.substr(0,1);
	            var SS=nS.substr(1,1);
	            
	            var BMS=mS.substr(0,1);
	            var SMS=mS.substr(1,1);
	            
	            var html = options.format;
	            html = html.replace("%BM",BM);
	            html = html.replace("%SM",SM);
	            html = html.replace("%BS",BS);
	            html = html.replace("%SS",SS);
	            html = html.replace("%BMS",BMS);
	            html = html.replace("%SMS",SMS);
//	            
//	            if($(o).html().length!=html.length&&$(o).html().length>0)
//	            {
//	            	alert($(o).html()+"    "+html);
//	            	$(o).stopTime();
//	            }
//	            
//	            
	            	
	            
	            $(o).html(html); 
			}
		});		
	};
	
	
	
})(jQuery); 



//uipin
(function($) {   
	
	//瀑布流默认配置
	$.pin_config = {
		_op:{width:225,hSpan:30,wSpan:10,isAnimate:false,speed:300,pin_col_init_height:[],pin_col_init_width:0}
	},
	
	$.pin_info = {
		pin_col_array:null, //列的信息	每列数据结构 {height:"",size:"",items:[]}
		pin_layout:null, //瀑布流外盒
		layout_width:null, //布局宽
		col_size:null, //列的数量
		left_array:null, //每列的左边距
		height_span:null, //顶部高margin
		items:[]  //所有子对象，即pin_box
	},

	//选项包含width(单个pin宽度),hSpan(每个pin的高度间隔),wSpan(每个pin的宽度间隔)
	//pin_col_init_height(整型数组，每一列的初始高度),pin_col_init_width(整型，第一列的左padding),isAnimate(动画表现),speed(动画表现的速度)
	$.fn.init_pin = function(options) {
		
		var op = $.extend({},$.pin_config._op, options);
		$.pin_config._op.width = op.width;
		$.pin_config._op.hSpan = op.hSpan;
		$.pin_config._op.wSpan = op.wSpan;
		$.pin_config._op.pin_col_init_height = op.pin_col_init_height;
		$.pin_config._op.pin_col_init_width = op.pin_col_init_width;
		$.pin_config._op.isAnimate = op.isAnimate;
		$.pin_config._op.speed = op.speed;
		$.pin_info.pin_layout = $(this); //外框盒子
		$.pin_info.layout_width = $.pin_info.pin_layout.width(); //外框总宽
		$.pin_info.col_size = Math.floor(($.pin_info.layout_width-$.pin_config._op.pin_col_init_width+$.pin_config._op.wSpan)/($.pin_config._op.width+$.pin_config._op.wSpan));	
		$.pin_info.pin_col_array = new Array($.pin_info.col_size);
		$.pin_info.left_array = new Array($.pin_info.col_size);
		$.pin_info.height_span = $.pin_config._op.hSpan;
		var span = $.pin_config._op.wSpan;
		
		var theightArray = new Array();
		//计算每列的左边距，及初始化列的数据集
		for(i=0;i<$.pin_info.col_size;i++)
		{
			if(i==0)
				$.pin_info.left_array[i] = 0+$.pin_config._op.pin_col_init_width;
			else
				$.pin_info.left_array[i] = ($.pin_config._op.width+span)*i+$.pin_config._op.pin_col_init_width;
			
			var init_height = 0;
			try
			{
				init_height = $.pin_config._op.pin_col_init_height[i];
				if(isNaN(init_height))init_height = 0;
			}
			catch(ex)
			{
				init_height = 0;
			}
			$.pin_info.pin_col_array[i] = {height:init_height,size:0,items:[]};
			theightArray[i] = $.pin_info.pin_col_array[i].height;	
		}
			

		var maxH = Math.max.apply(null,theightArray);
        var maxHIndex = $.inArray(maxH,theightArray); //找到最大高度的列的引
		$.pin_info.pin_layout.height(maxH+$.pin_info.height_span);
		
	},
	
	
	$.fn.pin = function(html){
		var pin_item = $(html);
		$.pin_info.pin_layout.append(pin_item);
		
		
		var heightArray = new Array();
		for(i=0;i<$.pin_info.col_size;i++)
		{
			heightArray[i] = $.pin_info.pin_col_array[i].height;			
		}
		
		var minH = Math.min.apply(null,heightArray);

        var minHIndex = $.inArray(minH,heightArray); //找到最小高度的列的引
        
        
        
       
        var left = $.pin_info.left_array[minHIndex];
        if($.pin_info.pin_col_array[minHIndex].height>0)
        var top = $.pin_info.pin_col_array[minHIndex].height+$.pin_info.height_span;
        else
        var top = 0;

        if($.pin_config._op.isAnimate)
        {
        	var css = {"position":"absolute","top":minH,"left":0}; //重定义样式
            pin_item.css(css);
            
            pin_item.animate({ 
            	top: top,
            	left: left
              }, $.pin_config._op.speed );
        }
        else
        {
        	var css = {"position":"absolute","top":top,"left":left}; //重定义样式
            pin_item.css(css);
        }
        

        
        if($.pin_info.pin_col_array[minHIndex].height>0)
        $.pin_info.pin_col_array[minHIndex].height+=(pin_item.height()+$.pin_info.height_span);
        else
    	$.pin_info.pin_col_array[minHIndex].height+=pin_item.height();
        $.pin_info.pin_col_array[minHIndex].size++;
        $.pin_info.pin_col_array[minHIndex].items.push(pin_item);
        $.pin_info.items.push(pin_item);
		
        
        var theightArray = new Array();
		for(i=0;i<$.pin_info.col_size;i++)
		{
			theightArray[i] = $.pin_info.pin_col_array[i].height;			
		}
		var maxH = Math.max.apply(null,theightArray);
        var maxHIndex = $.inArray(maxH,theightArray); //找到最大高度的列的引
        
        $.pin_info.pin_layout.height(maxH+$.pin_info.height_span);
        
	},
	
	$.fn.reposition = function()
	{
		$.pin_info.pin_layout = $(this); //外框盒子
		if($.pin_info.layout_width == $.pin_info.pin_layout.width())return;
		$.pin_info.layout_width = $.pin_info.pin_layout.width(); //外框总宽
		$.pin_info.col_size = Math.floor($.pin_info.layout_width/($.pin_config._op.width+$.pin_config._op.wSpan));	

		$.pin_info.pin_col_array = new Array($.pin_info.col_size);
		$.pin_info.left_array = new Array($.pin_info.col_size);
		$.pin_info.height_span = $.pin_config._op.hSpan;
		
		var span = Math.floor(($.pin_info.layout_width - $.pin_config._op.width*$.pin_info.col_size)/($.pin_info.col_size-1));
		
		var theightArray = new Array();
		//计算每列的左边距，及初始化列的数据集
		for(i=0;i<$.pin_info.col_size;i++)
		{
			if(i==0)
				$.pin_info.left_array[i] = 0;
			else
				$.pin_info.left_array[i] = ($.pin_config._op.width+span)*i;
			
			var init_height = 0;
			try
			{
				init_height = $.pin_config._op.pin_col_init_height[i];
				if(isNaN(init_height))init_height = 0;
			}
			catch(ex)
			{
				init_height = 0;
			}
			$.pin_info.pin_col_array[i] = {height:init_height,size:0,items:[]};
			theightArray[i] = $.pin_info.pin_col_array[i].height;	
		}
			

		var maxH = Math.max.apply(null,theightArray);
        var maxHIndex = $.inArray(maxH,theightArray); //找到最大高度的列的引
		$.pin_info.pin_layout.height(maxH+$.pin_info.height_span);
		
		
		//重定位
		for(idx=0;idx<$.pin_info.items.length;idx++)
		{
			var pin_item = $.pin_info.items[idx];
			var heightArray = new Array();
			for(i=0;i<$.pin_info.col_size;i++)
			{
				heightArray[i] = $.pin_info.pin_col_array[i].height;			
			}
			
			var minH = Math.min.apply(null,heightArray);

	        var minHIndex = $.inArray(minH,heightArray); //找到最小高度的列的引
	        
	        
	        
	       
	        var left = $.pin_info.left_array[minHIndex];
	        if($.pin_info.pin_col_array[minHIndex].height>0)
	        var top = $.pin_info.pin_col_array[minHIndex].height+$.pin_info.height_span;
	        else
	        var top = 0;

	        
	        if($.pin_config._op.isAnimate)
	        {
	            pin_item.animate({ 
	            	top: top,
	            	left: left
	              }, $.pin_config._op.speed );
	        }
	        else
	        {
	        	var css = {"position":"absolute","top":top,"left":left}; //重定义样式
	            pin_item.css(css);
	        }
	        
	        if($.pin_info.pin_col_array[minHIndex].height>0)
	        $.pin_info.pin_col_array[minHIndex].height+=(pin_item.height()+$.pin_info.height_span);
	        else
	    	$.pin_info.pin_col_array[minHIndex].height+=pin_item.height();
	        $.pin_info.pin_col_array[minHIndex].size++;
	        $.pin_info.pin_col_array[minHIndex].items.push(pin_item);

			
	        
	        var theightArray = new Array();
			for(i=0;i<$.pin_info.col_size;i++)
			{
				theightArray[i] = $.pin_info.pin_col_array[i].height;			
			}
			var maxH = Math.max.apply(null,theightArray);
	        var maxHIndex = $.inArray(maxH,theightArray); //找到最大高度的列的引
	        
	        $.pin_info.pin_layout.height(maxH+$.pin_info.height_span);
		}

		
	}
	
})(jQuery); 






//用于未来扩展的提示正确错误的JS
$.showErr = function(str,func)
{
	$.weeboxs.open(str, {boxid:'fanwe_error_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'错误',width:250,type:'wee',onopen:function(){
		init_ui_button();
	},onclose:func});
};

$.showSuccess = function(str,func)
{
	$.weeboxs.open(str, {boxid:'fanwe_success_box',contentType:'text',showButton:true, showCancel:false, showOk:true,title:'提示',width:250,type:'wee',onopen:function(){
		init_ui_button();
	},onclose:func});
};

$.showConfirm = function(str,funcok,funcclose)
{
	var okfunc = function(){
		$.weeboxs.close("fanwe_confirm_box");
		funcok.call();
	};
	$.weeboxs.open(str, {boxid:'fanwe_confirm_box',contentType:'text',showButton:true, showCancel:true, showOk:true,title:'确认',width:250,type:'wee',onopen:function(){
		init_ui_button();
	},onclose:funcclose,onok:okfunc});
};
