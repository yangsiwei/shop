$(document).ready(function(){
	copy();
});

function copy()
{
	$("#share-copy-button").click(function() {
		$("#share-copy-text").select();
		document.execCommand("Copy"); // 执行浏览器复制命令 
	});
};