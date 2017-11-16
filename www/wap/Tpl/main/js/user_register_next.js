$(function(){
    var upfile_data = new Array();
    /*图片上传*/
    new uploadPreview({ UpBtn: "file-btn", DivShow: "item-add", ImgShow: "fileimg" });
    $("#change_info").bind("click",function(){

        var me=$("#file-btn")[0];
        var user_name=$("#user_name").val();
        if(!me.files[0]){
            $.showErr("头像未上传");
            return;
        }
        if(!user_name){
            $.showErr("昵称不能为空");
            return;
        }

        if(me.files[0].type=='image/png'||me.files[0].type=='image/jpeg'||me.files[0].type=='image/gif'){
            // 也可以传入图片路径：lrz('../demo.jpg', ...
            var is_err = 0;
            lrz(me.files[0], {
                width:1200,
                height:900,
                before: function() {
                    //压缩开始
                },
                fail: function(err) {
                    //console.error(err);
                    is_err = 1;
                    alert(err);
                },
                always: function() {
                    //压缩结束
                },
                done: function (results) {
                    // 你需要的数据都在这里，可以以字符串的形式传送base64给服务端转存为图片。
                    if(is_err !=1){
//                        var data = new Array();
                        var data = {
                            base64: results.base64,
                            size: results.base64.length // 校验用，防止未完整接收
                        };
                        upfile_data = JSON.stringify(data);
                        var query ={};
                        query.img_data = upfile_data;
                        query.user_name=user_name;
                        $.ajax({
                            url:suce_url,
                            data:query,
                            type:"post",
                            dataType:"json",
                            success:function(data){
                                if(data.status==0){
                                    $.showErr(data.info)
                                }else{
                                    $.showSuccess(data.info,function(){
                                        window.location.href="https://www.aliduobaodao.com/appDownLoad/index.html";
                                    })
                                }
                            },
                            error:function(){
                                $.showErr("服务器提交错误");
                            }
                        });
                    }
                }
            });

        }else{
            $.showErr("上传的文件格式有误");
        }
    });
    $("#skip_change").bind("click",function(){
        window.location.href="https://www.aliduobaodao.com/appDownLoad/index.html";
    });

});