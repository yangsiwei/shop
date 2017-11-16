$(document).ready(function(){
    init_cart_down();
    whole();
    delcart();
    init_cart_checkbox();
    $("#buy_form").live("submit",function(){
    	if(!$("#button_check").hasClass("go_check")){
    		
    		return false;
    	}
    	
    	if($("input[name='check_agreement']").length > 0){
            if($("input[name='check_agreement']").attr("checked")){
                $("#buy_form").submit();
            }else{
                 $.showErr("请先阅读并同意《服务协议》！");
                 
            }
        }else{
            $("#buy_form").submit();
        }
    	return false;
    });
});

function init_cart_down()
{
    $("#new").click(function(){
       var url = main_url;
            var query = new Object();
            query.ctl = "cart";
            query.act = "cart_duobao_new";
            $.ajax({
                url: url,
                type: "POST",
                data:query,
                dataType: "json",
                success: function(data){
                    $(".ui-list").html(data.html);
                    init_ui_list();
                },
                 error:function(o){
                 
                }
        });
    });
    
}
function del_carts(objs,id,callback)
{
	var len=$("#tab tr").length;
	if(len>3){
		$(objs).parents("tr").remove();
		jsondata[id]=0;
		$(".cart-list-footer-total .txt-red").html(parseInt($(".cart-list-footer-total .txt-red").text())-parseInt($(objs).parents("tr").find(".txt-red").text())+"夺宝币");
	}
	$.ajax({
        url:main_url,
        data:{"act":"del_cart","id":id,"ctl":"cart"},
        type:"POST",
        dataType:"json",
        success:function(obj){
            if(obj.status)
            {
				if(len<=3){
					$("#cart_form").html(obj.data.html);
				}
               
            }

        }
    });
}
function plus_cart(obj,type)
{

	var obj_ele=$(obj).parents("tr.cart-list").find("input[name='selected[]']");
	if($(obj_ele).attr("checked")){
		var is_effect=1;
	}else{
		var is_effect=0;
	}

    var query = new Object();
    query.ctl = "cart";
    query.act = "adjusted";
    query.is_effect = is_effect;
    query.type = type;
    query.duobao_item_id = parseInt($(obj).attr('duobao_item_id'));
    if(type==2){
        query.buy_num = parseInt($(obj).attr('value'));
    }else{
        query.buy_num = parseInt($(obj).attr('buy_num'));
    }
    query.data_id = parseInt($(obj).attr('data-id'));
    $.ajax({
        url:main_url,
        data:query,
        type:"POST",
        dataType:"json",
        success:function(obj){
            if(obj.status)
            {
                $("#cart_form").html(obj.data.html);
               
            }
        }
    });
}
function whole()
{
    $("input[name='whole']").live('click',function(){
        if($("input[name='whole']").attr("checked")){
            $("input[name^='selected']").attr("checked", true);
            $("input[name='whole_2']").attr("checked", true);
            syn_whole_change(1);
        	
        }else{
            $("input[name^='selected']").attr("checked", false);
            $("input[name='whole_2']").attr("checked", false);
            syn_whole_change(0);
        }
    });
    
    $("input[name='whole_2']").live('click',function(){
        if($("input[name='whole_2']").attr("checked")){
            $("input[name^='selected']").attr("checked", true);
            $("input[name='whole']").attr("checked", true);
            syn_whole_change(1);
        }else{
            $("input[name^='selected']").attr("checked", false);
            $("input[name='whole']").attr("checked", false);
            syn_whole_change(0);
        }
    });
}
function delcart()
{
    $("#del_cart_whole").live('click',function(){
         
        var s=''; 
        for(var i=0; i<$("input[name^='selected']").length; i++){ 
            if($("input[name^='selected']")[i].checked) 
            s+=$("input[name^='selected']")[i].value+','; //如果选中，将value添加到变量s中 
        } 
        
        if(!s){
            $.showErr("未选中商品",function(){
               $("input[name='del_cart']").attr("checked", false);
            });
        }else{
            $.showConfirm("确定要删除吗？",function(){
				$('.weedialog').hide();
				$('.dialog-mask').hide();
                $.ajax({
                    url:main_url,
                    data:{"act":"del_cart","id":s,"ctl":"cart"},
                    type:"POST",
                    dataType:"json",
                    success:function(obj){
                        if(obj.status)
                        {
                            $("#cart_form").html(obj.data.html);
                          
                           
                        }
                    }
                });
            
            });
            return false;
        }
    })

}


function init_cart_checkbox(){

	
	$("#cart_form td input[name='selected[]']").live("click",function(){
		var obj_ele=$(this).parents("tr.cart-list").find(".select-bar .select .cart_input");
		
		
		var id=$(obj_ele).attr("data-id");
		var num=$(obj_ele).val();
		if($(this).attr("checked")){
			var type=1;
		}else{
			var type=0;
		}
		
		var check_count=0;
		var count=0;
		$("#cart_form td input[name='selected[]']").each(function(i,obj){
			count++;
			if($(this).attr('checked')){
				check_count++;
			}
			
		});
		
		if(check_count==0){
			$("input[name='whole']").attr("checked", false);
			$("input[name='whole_2']").attr("checked", false);
		}else if(check_count==count){
			$("input[name='whole']").attr("checked", true);
			$("input[name='whole_2']").attr("checked", true);
		}
		
		recount_total(id,num,type);
		
	});
	
	
	$(".select-bar .minus").live("click",function(){
		var id = $(this).attr("data-id");
		var num = parseInt(jsondata[id].number);
		var min_buy = parseInt(jsondata[id].min_buy);
		
		var check_box=$(this).parents(".cart-list").find("td input[name='selected[]']");
		if($(check_box).attr("checked")){
			var type=1;
		}else{
			var type=0;
		}
		
		if(num-min_buy <= 0)
			num = min_buy;
		else
			num = num-min_buy;
		recount_total(id,num,type);
	});
	
	$(".select-bar .plus").live("click",function(){
		var id = $(this).attr("data-id");
		var num = parseInt(jsondata[id].number);
		var min_buy = parseInt(jsondata[id].min_buy);
		var residue_count = parseInt(jsondata[id].residue_count);
		
		var check_box=$(this).parents(".cart-list").find("td input[name='selected[]']");
		if($(check_box).attr("checked")){
			var type=1;
		}else{
			var type=0;
		}
		
		if(num+min_buy > residue_count)
			num = residue_count;
		else
			num = num+min_buy;
		recount_total(id,num,type);
	});
	
	$(".select-bar .cart_input").live("blur",function(){
		var id = $(this).attr("data-id");
		var number = parseInt(jsondata[id].number);
		var residue_count = parseInt(jsondata[id].residue_count);
		var min_buy = parseInt(jsondata[id].min_buy);
		var unit_price = parseInt(jsondata[id].unit_price);
		var cart_num =$(this).val();
		if(cart_num==''){
			cart_num = min_buy;
		}
		if(cart_num>=residue_count){
			cart_num = residue_count;
		}else{
			 var multiple = parseInt(parseInt($(this).val())/min_buy);
			  if(multiple>0){
				  cart_num = min_buy*multiple;
			  }else{
				  cart_num = min_buy;
			  }
		}
		jsondata[id].number =cart_num;
        $(this).val(jsondata[id].number);
		
		var check_box=$(this).parents(".cart-list").find("td input[name='selected[]']");
		if($(check_box).attr("checked")){
			var type=1;
		}else{
			var type=0;
		}
	
		if($.trim($(this).val())!=""&&!isNaN($(this).val()))
		{
			num = parseInt($(this).val());
			
		}else{
			num=number;
		}
		if(num<=0)num=min_buy;
		if(num>residue_count)num=residue_count;
		recount_total(id,num,type);
	});
	
}

function syn_whole_change(is_effect){        

	var last_id;
	var last_id_num;
	$.each(jsondata,function(id,row){
		jsondata[id].type=is_effect;
		last_id=id;
		last_id_num=jsondata[id].number;
	});
	recount_total(last_id,last_id_num,is_effect)
}


function recount_total(id,num,type)
{
	jsondata[id].number = parseInt(num);
	jsondata[id].type = type;
	
	
	if(jsondata[id].number + jsondata[id].order_number > jsondata[id].user_max_buy && jsondata[id].user_max_buy > 0){
		$.showErr("该商品每个用户限购"+jsondata[id].user_max_buy+"次,已购"+jsondata[id].order_number+"次");
		jsondata[id].number = jsondata[id].user_max_buy - jsondata[id].order_number;
	}
	jsondata[id].total_price = jsondata[id].number * parseFloat(jsondata[id].unit_price);
	
		var total_price = 0;
		$.each(jsondata,function(i,row){
			$(".select-bar .cart_input[data-id='"+row.id+"']").val(parseInt(row.number));
			//$(".select-bar .cart_input[data-id='"+row.id+"']").parents(".select-bar_td").siblings(".select-bar_total").find(".txt-red").html(Math.round(parseFloat(row.total_price)*100)/100+"夺宝币");
			$(".select-bar .cart_input[data-id='"+row.id+"']").parents(".select-bar_td").siblings(".select-bar_total").find(".txt-red").html(Math.round(parseFloat(row.total_price)*100)/100);
			if(row.type==1){
				total_price+=parseFloat(row.total_price);
			}
			
		});
		//$(".cart-list-footer-total .txt-red").html(Math.round(total_price*100)/100+"夺宝币");
		$(".cart-list-footer-total .txt-red").html(Math.round(total_price*100)/100);
		$("#button_check").removeClass("go_check no_go_check");
		if(total_price >0){
			$("#button_check").addClass("go_check");
		}else{
			$("#button_check").addClass("no_go_check");
		}
	
}
