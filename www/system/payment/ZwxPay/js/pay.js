(function($){
    var PopUpWin = function(ele,opts){
        opts = $.extend({
            id:'',
            content:undefined,//内容
            closeCallback:undefined,//关闭时调用的方法
            openCallback:undefined,//打开窗口回调方法
        },opts);
        this.init(ele,opts);
    }

    PopUpWin.prototype = {
        template:'<div class="pop-wraper" id="{id}">\
                <div class="pop-mask"></div>\
                <div class="pop-outer">\
                    <div class="pop-inner">\
                        <div class="pop-content">\
                            {content}\
                        </div>\
                        <div class="btn btn_cancel"><i class="ico_cancel"></i></div>\
                    </div>\
                </div>\
            </div>',
        init:function(ele,opts){
            this.render(ele,opts);
            this.initEvent(ele,opts);
        },
        initEvent:function(ele,opts){
            var self = this;
            ele.find('.btn_cancel').click(function(){
                ele.find('#'+self.id).remove();
                if(opts.closeCallback !== undefined && $.isFunction(opts.closeCallback)){
                    opts.closeCallback();
                }
            });
        },
        elId:function(){//自动生成7位8进制DOM元素ID
            return 'win-xxx'.replace(/[x]/g,function(c){
                var r = Math.random() * 16|0, v = c === 'x' ? r : (r&0x3|0x8);
                return v.toString(8);
            }).toLocaleLowerCase();
        },
        render:function(ele,opts){
            if(ele === undefined){
                ele = $('body');
            }
            
            var content = opts.content;
            this.id = this.elId();
            
            if($.isFunction(content)){
                content  = content(this);
            }
                tpl = this.template.replace(/\{id\}/,this.id).replace(/\{content\}/,content);
            ele.append(tpl);
            if(opts.openCallback && $.isFunction(opts.openCallback)){
                opts.openCallback.call(this);
            }
        }
    };

    $.fn.popUpWin = function(opts){
        return this.each(function(){
             var that = $(this);
             var popUp = new PopUpWin(that,opts);
        });
    };

})(jQuery);

//二维码处理
(function(win,$){
    $.fn.load = function( url, params, callback ) {
        if ( typeof url !== "string" && _load ) {
            return _load.apply( this, arguments );
        }

        var selector, type, response,
            self = this,
            off = url.indexOf(" ");

        if ( off >= 0 ) {
            selector = jQuery.trim( url.slice( off ) );
            url = url.slice( 0, off );
        }

        if ( jQuery.isFunction( params ) ) {
            callback = params;
            params = undefined;

        } else if ( params && typeof params === "object" ) {
            type = "POST";
        }

        if ( self.length > 0 ) {
            jQuery.ajax({
                url: url,
                type: type,
                dataType: "html",
                async:true,
                data: params
            }).done(function( responseText ) {
                response = arguments;
                self.html( selector ?
                    jQuery("<div>").append( jQuery.parseHTML( responseText ) ).find( selector ) :
                    responseText );

            }).complete( callback && function( jqXHR, status ) {
                self.each( callback, response || [ jqXHR.responseText, status, jqXHR ] );
            });
        }

        return this;
    };
})(window,jQuery);

(function(win,$,h){
    $(document).ready(function(){
        var routeUrl = {
            'orderInfo':'order.html',//订单提交页面
            'orderInfo_method':'submitOrderInfo',//订单提交action方法
            'queryOrder':'queryOrder.html',
            'queryOrder_method':'queryOrder',
            'refundTest':'refundTest.html',
            'refundTest_method':'submitRefund',
            'queryRefund':'queryRefund.html',
            'queryRefund_method':'queryRefund'
        }, validateField = {//需要验证的字段
            'orderInfo':['out_trade_no','body','total_fee','mch_create_ip'],//字段名
            'orderInfo_msg':['商户订单号','商品描述','总金额','终端IP'],//字段对应的中文名
            'refundTest':['out_refund_no','total_fee','refund_fee'],
            'refundTest_msg':['商户退款单号','总金额','退款金额']
        },loadHtml = function(url,suffix){
            $('#auto_center').empty().load(url+' #'+suffix,function(tpl, status, obj){
                $('#auto_center').html(tpl);
                if(suffix === 'orderInfo'){
                    $('input[name=out_trade_no]').val((''+Math.random() * 10).substr(2));
                }else if(suffix === 'refundTest'){
                    $('input[name=out_refund_no]').val((''+Math.random() * 10).substr(2));
                }
            });
        }, curPage = 'orderInfo',
        number = /^(\d?)/g,ip = /^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/g,
        time = /^(\d{14})?/;
        //初始化加载的页面
        loadHtml(routeUrl['orderInfo'],'orderInfo');

        $('div.menu li').bind('click',function(e){
            var curTarget = $(e.currentTarget), href = curTarget.attr('href'),suffix = href.substring(href.lastIndexOf('\.'));
            curTarget.addClass('cur').siblings('.cur').removeClass('cur');
            loadHtml(routeUrl[href],suffix);
            curPage = suffix;
        });

        $('#pay_platform').delegate('span','click',function(e){
            if(e.target.className.indexOf('submit') === -1){
                return;
            }

            var input = $('div.form_wrap').find('input,select'), param = {method:'submitOrderInfo'}, vField = validateField[curPage];
            input.each(function(i,item){
                item = $(item);
                var vType = item.attr('vtype'), ind = 0;
                param[item.attr('name')] = item.val();
            });

            //判断不能为空的字段
            if(vField !== undefined){
                for(var i=0, field='', msg = ''; i<vField.length; i++){
                    field = vField[i];
                    msg = validateField[curPage+'_msg'][i];
                    if(param[field] === ''){
                        $('body').popUpWin({
                            content:msg+'不能为空！'
                        });
                        return;
                    }
                }
            }
            //设计提交方法
            param['method']=routeUrl[curPage+'_method'];

            var mask = $('<div class="mask"></div>');
                $('body').append(mask);
            $.post('/payInterface/request.php',param,function(res){
                $('body').find('.mask').remove();
                if(typeof(res) === 'string'){
                    res = JSON.parse(res);
                }

                if(res.status === 500){
                    _content = res.msg;
                    $('body').popUpWin({
                        content:res.msg
                    });
                }else{
                    if(curPage === 'orderInfo'){
                        console.log(res);
                        $('body').popUpWin({
                            content:'<img src="'+res.code_img_url+'" /><p>订单状态查询中...</p>',
                            openCallback:function(){
                                var self = this, interval = setInterval(function(){
                                    $.ajax({
                                        type:'post',
                                        url:'/payInterface/request.php?method=queryOrder',
                                        data:'out_trade_no='+res['out_trade_no'],
                                        success:function(_res){
                                            if(typeof(_res) === 'string'){
                                                _res = JSON.parse(_res);
                                            }
                                            if(_res['data']['trade_state'] == 'SUCCESS'){
                                                clearInterval(interval);
                                                $('body').popUpWin({
                                                    content:_res['msg']
                                                });
                                                $('#'+self.id).remove();
                                            }
                                        }
                                    });
                                },2000)
                            }
                        });
                    }else{
                        $('body').popUpWin({
                            content:res.msg
                        });
                    }
                }

                
            });
        });
    });
})(window,jQuery);