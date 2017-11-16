$(document).ready(function() {
    init_button();
	$(".pk-now").click(function() {
        var data={};
        data['max_buy']=$(this).attr('max_buy');
        data['duobao_id']=$(this).attr('duobao_id');
        data['pk_min_number']=parseInt($(this).attr('pk_min_number'));
        if(!data['pk_min_number']||data['pk_min_number']<1)data['pk_min_number']=1;
        if(init_button_data(data)){
           check_login(function(){
                $(".mask").addClass('active');
                $("#pk_password").val('');
                $(".pk-box").show();
                $("body").css("overflow","hidden");
                $(".pk-box").addClass('active');
                $("#buyer_number").trigger('blur');
           });
        }
	});
    $(".pk-nowing").click(function(){
        $.showSuccess("pk发起中");
    });
	$(".mask").click(function() {
		$(this).removeClass('active');
        $(".pk-box").hide();
        $("body").css("overflow","scroll");
		$(".pk-box").removeClass('active');
	});
	$(".close-pk-box").click(function() {
		$(".mask").removeClass('active');
        $(".pk-box").hide();
        $("body").css("overflow","scroll");
		$(".pk-box").removeClass('active');
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
            $.showErr(check['info']);
        }
    });
});
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
function init_button(){
    $("#button_minus.active").bind("click",function(){
        var data={};
        data['max_buy']=parseInt($("#buyer_number").data('max_buy'));
        data['pk_min_number']=parseInt($('#buyer_number').data('pk_min_number'));
        data['buyer_number']=parseInt($('#buyer_number').val())-1;
        var res=culate_number(data,-1);
        if(res!=-1){
            $('#buyer_number').val(res);
            $('#button_plus').addClass('active');
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
            $(this).removeClass("active");
        }
    });

}
function init_button_data(data){
    if(!data)return 0;
    if(isNaN(data['pk_min_number'])||!data['pk_min_number'])data['pk_min_number']=2;
    $('#buyer_number').val(data['pk_min_number']);
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
                $.showErr(da.info,function(){
                    console.log(da.jump);
                    window.location.href=da.jump;
                })
            }else{
                $.showErr(da.info)
            }
        },
        error:function(da){
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