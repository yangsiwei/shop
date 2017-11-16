var is_all_select=0;
$(document).ready(function(){
    resize();
	number_li();
	// 监听拖动窗口
	window.onresize = function(){
		resize();
	};
	//全选
	$(".all-choose").click(function() {
        is_all_select=1;
		$(this).hide();
		$(".cancel-all-choose").show();
		$(".number-list li").addClass('active');
		$(".number-list .disable").removeClass('active');
	});
	//取消全选
	$(".cancel-all-choose").click(function() {
        is_all_select=0;
		$(this).hide();
		$(".all-choose").show();
		$(".number-list li").removeClass('active');
	});
	//只显示可选号码
	$(".can-choose-only").click(function() {
		$(this).toggleClass('active');
		$(".number-list .disable").toggleClass('hide');
		number_li();
	});
	//选择号码
	$(".number-list li").click(function() {
		$(this).toggleClass('active');
		$(".number-list .disable").removeClass('active');
	});
    //关闭窗口
    $(".close-choose").click(function(){
        $("#number-list").html("<div class='page-load'>努力加载中...~</div>");
        $(".number-choose-box").css("display","none");
    });
    //加载弹出界面的选号
    $(".duobao-now").click(function(){
        var me=this;
        check_login(function(){
            var duobao_item_id=$(me).attr("duobao-item-id");
            $("#confirm").data("duobao_item_id",duobao_item_id);
            var url=$(me).attr('url');
            $(".number-choose-box").css("display","block");
            var config={duobao_item_id:duobao_item_id,url:url,page:1};
            $("#number-list").data("config",config);
            ajax_chooose_number(config,init_scroll_bottom);
        });
    });
    $("#confirm").click(function(){
        var query={},url=$(this).attr('url');
        query['data_id']=$(this).data("duobao_item_id");
        var choose_number=[];
        if(is_all_select==1){
            var un_choose_number=[];
            query['is_all_choose']=1;
            if($(".all-select").css("display")!="none"){
                $("#number-list").find("li").each(function (i) {
                    if(!$(this).hasClass("active")&&!$(this).hasClass('disable'))
                        un_choose_number.push($.trim($(this).html()));
                        query['un_choose_number']=un_choose_number.join(",");
                });
            }
        }else{
            $(".number-list li.active").each(function(i){
                choose_number.push($.trim($(this).text()));
            });
            query['buy_num']=choose_number.length;
            query['choose_number']=choose_number.join(',');
        }
        var config={};
        config['url']=url;
        config['query']=query;
        exec_ajax(config);
    })
});
//弹出层背景高度自适应
function resize() {
    var w_height=$(window).height();
    $(".mask").css('height', w_height);
}
//选号宽度自适应
function number_li() {
    var w_list = $(".number-list").width();
    var scrollbarWidth = document.getElementById('number-list').offsetWidth - document.getElementById('number-list').scrollWidth;
    var main_list = w_list- scrollbarWidth;
    $(".number-list li").css('width', main_list/5-17);
}
function init_choose_number(){
    $(".number-list li").unbind("click").click(function() {
        if($(".all-choose").css("display")=="none"){
            $(".all-choose").show();
            $(".cancel-all-choose").hide();
        }
        $(this).toggleClass('active');
        $(".number-list .disable").removeClass('active');
    });
}
function check_login(callback){
    $.ajax({
        url:$("#confirm").attr("check-login"),
        dataType:"json",
        success:function(da){
            if(da.status==0){
//                $.showErr(da.info,function(){
                    window.location.href=da.jump;
//                });
            }else{
                callback();
            }
        },error:function(da){
            $.showErr("系统错误");
        }
    })
}
function ajax_chooose_number(config,callback){
    $.ajax({
        type:"POST",
        data:{data_id:config.duobao_item_id,page:config.page},
        dataType:"json",
        url:config.url,
        success:function(da){
            var html="";
            var list=da['list'];
            for(var i in list){
                if(list[i].user_id!='0'){
                    html+='<li class="disable">'+list[i].lottery_sn+'</li>';
                }else if(is_all_select){
                    html+='<li class="active">'+list[i].lottery_sn+'</li>';
                }else{
                    html+='<li>'+list[i].lottery_sn+'</li>';
                }
            };
            if(config.page==1){
                da.page.page++;
                $("#number-list").data("page",da.page);
                $("#number-list").html(html);

            }else{
                $("#number-list").append(html);
            }
            init_choose_number();//让号码可以点击
            if(callback){callback()};
        },error:function(){
            $(".number-choose-box").css("display","none");
            $.showErr("系统错误");
        }
    })
}
function exec_ajax(config){
    var url=config['url'],query=config['query'];
    $.ajax({
        url:url,
        data:query,
        type:"POST",
        dataType:"json",
        error:function(da){
            $(".close-choose").trigger("click");
            alert("系统错误");
        },
        success:function(obj){
            if(obj.status==1||obj.status==-1)
            {
                location.href = obj.jump;
            }else{
                $(".close-choose").trigger("click");
                $.showErr(obj.info,function(){
                    if(obj.jump)
                    {
                        location.href = obj.jump;
                    }
                });
            }
        }
    })
}
var infinite_loading=false;
function init_scroll_bottom()
{
    var page=$("#number-list").data("page");
    if(page.page_total>1&&page.page<page.page_total){
        $("#number-list li:last").after("<div class='page-load'>努力加载中...~</div>");
    }
    $("#number-list").scroll(function(){

        var totalheight = parseFloat($(".number-list")[0].scrollHeight)-parseFloat($(".number-list")[0].scrollTop)-20;
        if($(".number-list").height()>= totalheight)
        {

            var config=$("#number-list").data("config");
            var page=$("#number-list").data("page");
            if(infinite_loading)return;
            if(page.page<=page.page_total)
            {
                infinite_loading = true;
                var config={duobao_item_id:config.duobao_item_id,url:config.url,page:page.page};
                ajax_chooose_number(config,function(){
                    $(".page-load").remove();
                    $("#number-list li:last").after("<div class='page-load'>努力加载中...~</div>");
                    infinite_loading=false;
                    var page=$("#number-list").data("page");
                    page.page++;
                    $("#number-list").data("page",page);
                });
            }
            else
            {
                $(".page-load").html("已经到底部了...~");
            }
        }

    });
}