$(document).ready(function(){	
	load_ofc("sale_line_data_chart",sale_line_data_url,"100%",300);
    $("#export_excel").bind("click",function(){
        var me=$(this);
        var query="";
        query+="month="+$("select[name=month]").val();
        query+="&type="+$("select[name=type]").val();
        query+="&year="+$("select[name=year]").val();
        var url=me.attr("url");
        location.href=url+query;
    })
});

function clear_balance(url)
{
	if(confirm("确定要清空当前的报表数据吗？ 清空后不能还原。"))
	{
		location.href = url;
	}
}
