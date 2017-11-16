$(document).ready(function() {
    $(".pk-now").click(function() {
        var data={};
        data['url']=$(this).attr('url');
        if(init_button_data(data)){
            check_login(function(){
                $(".mask").addClass('active');
                $(".pk-box").show();
                $("#pk_password").val('');
                $(".pk-box").addClass('active');
            })
        }
    });
    $(".mask").click(function() {
        $(this).removeClass('active');
        $(".pk-box").hide();
        $(".pk-box").removeClass('active');
    });
    $(".close-pk-box").click(function() {
        $(".mask").removeClass('active');
        $(".pk-box").hide();
        $(".pk-box").removeClass('active');
    });
    $(".submit").click(function(){
        var data={};
        data['url']=$(this).attr("url");
        submit(data);
    });
    $("#pk_submit").click(function(){
        var post={},data={};
        post['url']=$(this).data('url');
        data['pk_password']=$(this).data('pk_password');
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
            if(da.status==0){
                $.showErr(da.info,function(){
                    window.location.href=da.jump;
                });
            }else{
                callback();
            }
        },error:function(da){
            $.showErr("系统错误");
        }
    })
};
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
                $.showErr(da.info);
            }
        },
        error:function(da){
            $.showErr("系统错误");
        }
    });
};
function init_button_data(data){
    if(!data)return 0;
    $('#pk_submit').data('url',data['url']);
    return 1;
}