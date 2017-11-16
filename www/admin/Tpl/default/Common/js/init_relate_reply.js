function close_pop()
{
	$.weeboxs.close();
}

$(document).ready(function(){
    $("#add_relate_goods").bind("click",function(){
    	$.weeboxs.open(LOAD_GOODS_LIST_URL, {boxid:'relate_goods_win',contentType:'ajax',showButton:true, showCancel:true, showOk:true,title:'选择要关联的商品',width:550,height:310,onopen:onOpenRelate,onok:onConfirmRelate,onclose:onCancelRelate});
    });

    $(".remove_relate").live("click",function(){
        $(this).parent().parent().remove();
        if($("input[name='relate_goods_id[]']").length==0)
        {
            $("#relate_table").remove();
            $("#relate_table_div").hide();
        }
    });

    

    
});

function onConfirmRelate()
{
    var rowsCbo = $("input[rel='relate_goods_id']:checked");
    if(rowsCbo.length>0)
    {
        var relate_table = $("#relate_table");
        if(relate_table.length==0)
        {
            var relate_table = $("<table class='dataTable' id='relate_table'><tr><th>操作</th><th>缩略图</th><th>商品名称</th></tr></table>");
            $("#relate_goods_box").append(relate_table);
            $("#relate_table_div").show();
        }
        
        //关联个数限制
        if($("input[name='relate_goods_id[]']").length>=relate_goods_num){
        	alert("最多 "+relate_goods_num+" 个关联商品!");
        	return false;
        }
    
        $.each(rowsCbo,function(i,o){
            //alert($(o).val());
            if($("#relate_goods_id_"+$(o).val()).length==0){
                if($("input[name='relate_goods_id[]']").length>=relate_goods_num){
                    close_pop();
                    return;
                }
                var row = $("<tr><td><a href='javascript:void(0);' class='remove_relate'>删除</a></td><td><input type='hidden' id='relate_goods_id_"+$(o).val()+"' name='relate_goods_id[]' value='"+$(o).val()+"' />"+$(o).parent().parent().find(".goods_image").html()+"</td><td>"+$(o).parent().parent().find(".goods_name").html()+"</td></tr>");
                $(relate_table).append(row);

            }
        });
    }
    close_pop();
}
function onCancelRelate()
{

}

function onOpenRelate(){    

	$("#ajax_news_form").bind("submit",function(){

        //改用ajax提交表单
        var ajaxurl = $(this).attr("action");
        var query = $(this).serialize();

        $.ajax({
            url: ajaxurl,
            data:query,
            type: "POST",
            success: function(html){
                $("#relate_goods_win").find(".dialog-content").html(html);
				onOpenRelate();
            },
            error:function(ajaxobj){}
        });
        //end

        return false;
    });
	
	$("#ajax_news_page").find("a").bind("click",function(){

        //改用ajax提交表单
        var url = $(this).attr('href');
        url+="&page="+$(this).attr("page");
        var query =  $("#ajax_news_form").serialize();
        $.ajax({
            url: url,
            data:query,
            type: "POST",
            success: function(html){
                $("#relate_goods_win").find(".dialog-content").html(html);
				onOpenRelate();

            },
            error:function(ajaxobj)
            {

            }
        });
        //end

        return false;
    });

    $("#relate_goods_win").find(".check_all").bind("click",function(){
    	var checked = $(this).attr("checked");

    	if(checked){
    		$("#relate_goods_win").find("input[type='checkbox']").attr("checked",checked);
    	}else{
    		$("#relate_goods_win").find("input[type='checkbox']").attr("checked",false);
    	}	
    });
}
