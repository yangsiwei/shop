function resize() {
	var w_height=$(window).height();
	$(".mask").css('height', w_height);
}
$(document).ready(function() {
	resize();
	// 监听拖动窗口
	window.onresize = function(){
		resize();
	};
	$(".j-pk").click(function() {
        var me=$(this);
        check_login(function(){
            $("#pk-title").html(me.attr("goodName"));
            $("#span-pk-num").html(me.attr("goodNum"));
            $("#pk_password").val('');
            $(".pk-box").show();
            $("#pk_submit").data("url",me.attr("url"));
            $(".pk-set:first-child input").focus();
        });
	});
	$(".close-pk").click(function() {
		$(".pk-box").hide();
		$(".pk-set:last-child input").val("");
	});
    $(".submit").click(function(){
        var data={};
        var me=this;
        check_login(function(){
            data['url']=$(me).attr("url");
            submit(data);
        });
    });
    $("#pk_submit").click(function(){
        var me=this;
        var post={},data={};
        post['url']=$(me).data('url');
        data['pk_password']=$("#pk_password").val();
        data['is_md5']=0;
        post['data']=data;
        submit(post);
    });
});
function check_login(callback){
    $.ajax({
        url:$("#pk_password").attr("check-login"),
        dataType:"json",
        success:function(da){
            $(".pk-box").hide();
            if(da.status==0){
                window.location.href=da.jump;
            }else{
                callback();
            }
        },error:function(da){
            $(".pk-box").hide();
            $.showErr("系统错误");
        }
    })
}
function submit(config){
    $.ajax({
        url:config.url,
        data:config.data,
        dataType:"json",
        type:'POST',
        success:function(da){
            if(da.status==1)
            {
                location.href = da.jump;
            }else{
                $(".pk-box").hide();
                $.showErr(da.info);
            }
        },
        error:function(da){
            $(".pk-box").hide();
            $.showErr("系统错误");
        }
    });
}