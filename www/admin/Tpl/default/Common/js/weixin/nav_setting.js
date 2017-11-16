function do_confirm(text,jump)
{
	if(confirm(text))
	{
		if(jump)
		{
			location.href = jump;
		}
	}
}

$(document).ready(function(){
	
	$(".nav_row").each(function(i,o){
		init_nav_row(o);
	});
    init_add_btn();
    $("#syn_weixin").bind("click",function(){
        var ajaxurl = $(this).attr("rel");       
                                    
        $.ajax({ 
            url: ajaxurl,
            type: "POST",
            dataType: "json",
            success: function(ajaxobj){
              if(ajaxobj.status==1)
              {
                 alert(ajaxobj.info);
                 location.reload();
              }
              else
              {
            	  alert(ajaxobj.info);
              }
            },
            error:function(ajaxobj)
            {
                
            }
        }); 
    });
    $(".del_nav").live("click",function(){
        var id = $(this).parent().parent().find("input[name='id[]']").val();
        if($(".sub_"+id).length>0)
        {
            alert("请选删除子菜单");
        }
        else
        $(this).parent().parent().remove();
    });
    $(".add_sub_nav").live("click",function(){
        var pid = $(this).attr("pid");
        var tr = $(this).parent().parent();
        if($(".sub_"+pid).length>=5)
        {
            alert("子菜单数量超过不能超过五个");
        }
        else
        {           
                    var ajaxurl = $(this).attr("rel");    
                                                        
                    $.ajax({ 
                        url: ajaxurl,
                        type: "POST",
                        success: function(html){
                          var dom = $(html);
                          $(tr).after(dom);
                          init_nav_row(dom);
                        },
                        error:function(ajaxobj)
                        {
                   
                        }
                    }); 
 
        }
    });
});

function init_add_btn()
{
    $("#add_weixin_main_nav").bind("click",function(){
        
            if($("#listTable").find("tr.main").length>=3)
            {
                alert("主菜单数量超过不能超过三个");
            }
            else
            {
                  //改用ajax提交表单
                    var ajaxurl = $(this).attr("rel");       
                                    
                    $.ajax({ 
                        url: ajaxurl,
                        type: "POST",
                        success: function(html){
                           var dom = $(html);
                           $("#listTable").append(dom);
                           init_nav_row(dom);
                        },
                        error:function(ajaxobj)
                        {
                   
                        }
                    }); 
                    //end
            }
           
        
    });
}

function init_nav_row_state(row)
{
	var key = $(row).find(".ctl").val();

	var field_id = navs[key]['field'];
	var field_name = navs[key]['fname'];

	if(field_id)
	{
		$(row).find(".data").show();
	}
	else
	{
		$(row).find(".data").hide();
	}
	
	$(row).find(".field_name").html(field_name);
	if(field_name!="")
	{
		$(row).find(".field_name").show();
	}
	else
	{
		$(row).find(".field_name").hide();
	}
}

function init_nav_row(row)
{	
	init_nav_row_state(row);
	$(row).find(".ctl").bind("change",function(){
		init_nav_row_state(row);
	});
}


