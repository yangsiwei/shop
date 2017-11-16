$(document).ready(function(){
	$("select[name='input_type']").bind("change",function(){
		load_field_form();
	});
	load_field_form();
});

function load_field_form()
{
	input_type = $("select[name='input_type']").val();
	if(input_type == 0)
	{		
		$("#scope_input_row").hide();
		$("#must_input_row").show();
	}
	else
	{
		$("#scope_input_row").show();
		$("#must_input_row").hide();
		$("select[name='is_must']").val(0);
	}
}
