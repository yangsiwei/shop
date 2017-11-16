$(document).ready(function(){
    $("#chang_user_name").bind('click',function(){
        var query={};
        query.user_name=$("#user_name").val();
        var name_rul = /^[\w\u4e00-\u9fa5]+$/;
        if(query.user_name==""){
        	$.showErr("用户名不能为空");
			return false;
		}
        else if(! name_rul.test(query.user_name)){
        	$.showErr("您输入的用户名不合法！");
        	return false;
        }
        
        $.ajax({
            url:user_change_name_url,
            dataType:"json",
            type:"post",
            data:query,
            success:function(da){
                if(da.status==0){
                    $.showErr(da.info);
                }else{
                    $.showSuccess(da.info,function(){
                        window.location.href=user_center_index_url;
                    })
                }
            },error:function(da){
                $.showErr("服务器提交失败");
            }
        })
    });
});