$(document).ready(function(){
	load_web_preview();
	$("select[name='zt_moban']").bind("change",function(){
		load_web_preview();
	});
	
});

function load_web_preview()
{

		var zt_file = $("select[name='zt_moban']").val();
		var t = zt_file.split(".");
		var zt_key = t[0];
		$("#preview").html("<img src='"+APP_ROOT+"/app/Tpl/main/web_zt/preview/"+zt_key+".jpg' />");	
	

}
