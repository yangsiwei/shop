$(document).ready(function(){
    check_type();
    $("select[name=push_type]").bind("change",function(){
        check_type();
    });
    $("#search_user").bind("click",function(){
        var user_name=$.trim($("input[name='user_name']").val());
        if(user_name == ''){
            alert('请输入用户名');
            return false;
        }
        var url = ROOT+"?"+VAR_MODULE+"="+MODULE_NAME+"&"+VAR_ACTION+"=get_user_info";
        $.post(url,{user_name:user_name},function(data){
            if(data.status == 1){
                $("select[name='acceptor']").html(data.data);
            }
        },'json');
    })
    $("select[name='acceptor']").bind('change',function(){
        check_user();
    })
    if($("select[name=push_type]").val()==1){
            check_user();
    }
})
//检查推送类型，如果是单播将用户的值赋值到隐藏域中
function check_user(){
    var me=$("select[name='acceptor']").find("option:selected");
    var type=me.attr('dev_type');
    if(type=="android"){
        $("input[name=android_device_tokens]").val(me.attr("device_token"));
    }else if(type=='ios'){
        $("input[name=ios_device_tokens]").val(me.attr("device_token"));
    }
//    else{
//        alert("用户未使用手机登录或者使用机型非android或ios");
//    }
}
//检查推送类型，如果是单播显示人员选择
function check_type(){
    var val=$("select[name=push_type]").val();
    if(val==1){
        $("#tr_choose_user").show();
    }else if(val==4){
        $("#tr_choose_user").hide();
    }
}