$(document).ready(function() {
    resize();
    // 监听拖动窗口
    window.onresize = function(){
        resize();
    };
    init_button();
    $(".j-pk").click(function() {
        var me=$(this);
        var data={};
        $("#pk-title").html(me.attr("goodName"));
        $("#span-pk-num").html(me.attr("max_buy"));
        data['max_buy']=$(this).attr('max_buy');
        data['duobao_id']=$(this).attr('duobao_id');
        data['pk_min_number']=parseInt($(this).attr('pk_min_number'));
        if(!data['pk_min_number']||data['pk_min_number']<1)data['pk_min_number']=1;
        if(init_button_data(data)){
            check_login(function(){
                $(".pk-box").show();
                $("#buyer_number").trigger("blur");
            })

        }
    });
    $('.j-pking').click(function(){
        $.showSuccess("pk活动有人发起中");
    });
    $(".mask").click(function() {
        $(".pk-box").hide();
    });
    $(".close-pk").click(function() {
        $(".pk-box").hide();
    });
    $("#buyer_number").blur(function(){
        var data={};
        data['max_buy']=parseInt($("#buyer_number").data('max_buy'));
        data['pk_min_number']=parseInt($('#buyer_number').data('pk_min_number'));
        data['buyer_number']=parseInt($('#buyer_number').val());
        if(data['buyer_number']>data['max_buy']){
            data['buyer_number']=data['max_buy'];
            $('#buyer_number').val(culate_number(data,-1));
        }else if(data['buyer_number']<data['pk_min_number']){
            data['buyer_number']=data['pk_min_number'];
            $('#buyer_number').val(culate_number(data,1));
        }else{
            var res=culate_number(data,1);
            if(res==-1){
                res=culate_number(data,-1);
            }
            $('#buyer_number').val(res);
        }
    });

    $("#submit").click(function(){
        var config={},data={};
        config['url']=$("#buyer_number").attr("url");
        data['duobao_id']=$("#buyer_number").data('duobao_id');
        data['pk_password']=$("#pk_password").val();
        data['buyer_number']=parseInt($("#buyer_number").val());
        data['pk_min_number']=parseInt($("#buyer_number").data("pk_min_number"));
        data['max_buy']=parseInt($("#buyer_number").data("max_buy"));
        config['data']=data;
        var check=check_config(config);
        if(check['status']){
            submit_pkgoods(config);
        }else{
            $(".pk-box").hide();
            $.showErr(check['info']);
        }
    })
});
function resize() {
    var w_height=$(window).height();
    $(".mask").css('height', w_height);
}
function init_button(){
    $("#button_minus.active").bind("click",function(){
        var data={};
        data['max_buy']=parseInt($("#buyer_number").data('max_buy'));
        data['pk_min_number']=parseInt($('#buyer_number').data('pk_min_number'));
        data['buyer_number']=parseInt($('#buyer_number').val())-1;
        var res=culate_number(data,-1);
        if(res!=-1){
            $('#buyer_number').val(res);
            $('#button_plus').addClass("active");
        }else{
            $(this).removeClass("active");
        }
    });
    $("#button_plus.active").bind("click",function(){
        var data={};
        data['max_buy']=parseInt($("#buyer_number").data('max_buy'));
        data['pk_min_number']=parseInt($('#buyer_number').data('pk_min_number'));
        data['buyer_number']=parseInt($('#buyer_number').val())+1;
        var res=culate_number(data,1);
        if(res!=-1){
            $('#buyer_number').val(res);
            $('#button_minus').addClass('active');
        }else{
            $(this).removeClass('active');
        }
    });

}
function check_login(callback){
    $.ajax({
        url:$("#buyer_number").attr("check-login"),
        dataType:"json",
        success:function(da){
            if(da.status==0){
                    window.location.href=da.jump;
            }else{
                callback();
            }
        },error:function(da){
            $.showErr("系统错误");
        }
    })
}
function init_button_data(data){
    if(!data)return 0;
    if(isNaN(data['pk_min_number'])||!data['pk_min_number'])data['pk_min_number']=2;
    $('#buyer_number').val(data['pk_min_number']);
    $('#pk_password').val('');
    $('#buyer_number').data('max_buy',data['max_buy']);
    $('#buyer_number').data('duobao_id',data['duobao_id']);
    $('#buyer_number').data('pk_min_number',data['pk_min_number']);
    return 1;
}
function culate_number(data,cul){
    if(!data['buyer_number']){
        data['buyer_number']=data['pk_min_number'];
        return culate_number(data,cul);
    }else{
        if(data['max_buy']%data['buyer_number']==0&&data['buyer_number']>=data['pk_min_number']){
            return data['buyer_number'];
        }else{
            if(data['buyer_number']>data['max_buy']||data['buyer_number']<data['pk_min_number'])
                return -1;
            data['buyer_number']=data['buyer_number']+cul;
            return culate_number(data,cul);
        }
    }
}
function submit_pkgoods(config){
    $.ajax({
        url:config.url,
        data:config.data,
        dataType:"json",
        type:'POST',
        success:function(da){
            if(da.status==1)
            {
                location.href = da.jump;
            }else if(da.status==-1){
                $(".pk-box").hide();
                $.showErr(da.info,function(){
                    window.location.href=da.jump;
                })
            }else{
                $(".pk-box").hide();
                $.showErr(da.info)
            }
        },
        error:function(da){
            $(".pk-box").hide();
            $.showErr("系统错误");
        }
    })
}
function check_config(config){
    var check={};
    check['status']=1;
    if(config.data['max_buy']%config.data['buyer_number']!=0){
        check['status']=0;
        check['info']="购买数量不符合规则";
    }
    return check;
}