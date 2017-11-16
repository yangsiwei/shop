$(document).ready(function(){
	load_zt_preview();
	$("select[name='zt_moban']").bind("change",function(){
		load_zt_preview();
	});
});

function load_zt_preview()
{
	var zt_file = $("select[name='zt_moban']").val();
	var t = zt_file.split(".");
	var zt_key = t[0];
	$("#preview").html("<img src='"+APP_ROOT+"/mapi/mobile_zt/preview/"+zt_key+".png' />");
}