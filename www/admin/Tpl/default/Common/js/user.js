function inte_detail(user_id)
{
	location.href = ROOT + '?m=User&a=inte_detail&id='+user_id;

}

function account(user_id)
{
	$.weeboxs.open(ROOT+'?m=User&a=account&id='+user_id, {contentType:'ajax',showButton:false,title:LANG['USER_ACCOUNT'],width:700,height:260});
}
function account_detail(user_id)
{
	location.href = ROOT + '?m=User&a=account_detail&id='+user_id;
}
function batch_add(){
	$.weeboxs.open(ROOT+'?m=User&a=batch_add', {contentType:'ajax',showButton:false,title:"excel导入机器人",width:600,height:250});
}

