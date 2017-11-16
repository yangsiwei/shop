var is_all_select=0;
$(document).ready(function () {
    $("#all_select").click(function () {
        is_all_select=1;
        change_number_by_array({'choose_number':count_leave,"leave_number":0});
        $(".code-box").addClass('active');
        $(".disable .code-box").removeClass('active');
        $(this).hide();
        $("#un_all_select").show();
    });
    $("#un_all_select").click(function () {
        is_all_select=0;
        change_number_by_array({'choose_number':0,"leave_number":count_leave});
        $(".code-box").removeClass('active');
        $(this).hide();
        $("#all_select").show();
    });
    $(".show-btn").click(function () {
        $(this).toggleClass('active');
        $(".disable").toggleClass('active');
    });

    $("#buy_now").click(function () {
        var query = {}, url = $(this).attr('url');
        query['data_id'] = $('#duobao_item_id').val();
        if(is_all_select==1){
            var un_choose_number=[];
            query['is_all_choose']=1;
            if($(".all-select").css("display")!="none"){
                $(".code-box").each(function (i) {
                    if(!$(this).hasClass("active")&&!$(this).parent().hasClass('disable'))
                    un_choose_number.push($.trim($(this).children("p.code").html()));
                    query['un_choose_number']=un_choose_number.join(",");
                });
            }
        }else{
            var choose_number = [];
            $(".code-box.active").each(function (i) {
                choose_number.push($.trim($(this).children("p.code").html()));
            });
            query['buy_num'] = choose_number.length;
            query['choose_number'] = choose_number.join(',');
        }
        var config = {};
        config['url'] = url;
        config['query'] = query;
        exec_ajax(config);
    });
    init_check_box();
    init();
    init_scroll_bottom_number_choose(init_check_box)
});
function init() {
    //查找号码总数
    var total_number = data_total;
    $("#total_number").html(total_number);
    //已经选择的号码
    var choose_number = 0;
    $("#choose_number").html(choose_number);
    //计算剩余号码数
    var leave_number = count_leave;
    $("#leave_number").html(leave_number);

}
//初始化号码选择和点击事件
function init_check_box() {
    $(".code-box").unbind("click").bind("click", function () {
        if($("#all_select").css("display")=="none"){
            $("#all_select").show();
            $("#un_all_select").hide();
        }
        if ($(this).hasClass('active') && !$(this).parent().hasClass('disable')) {
            change_number(-1);
        } else if (!$(this).hasClass('active') && !$(this).parent().hasClass('disable')) {
            change_number(1);
        }
        $(this).toggleClass('active');
        $(".disable .code-box").removeClass('active');
    });
}
function change_number(i) {
    var choose_number = parseInt($('#choose_number').html());
    $('#choose_number').html(choose_number + i);
    var leave_number = parseInt($("#leave_number").html());
    $("#leave_number").html(leave_number - i);
}
function change_number_by_array(config){
    $('#choose_number').html(config['choose_number']);
    $('#leave_number').html(config['leave_number']);
}
function exec_ajax(config) {
    var url = config['url'], query = config['query'];
    $.ajax({
        url: url,
        data: query,
        type: "POST",
        dataType: "json",
        error: function (da) {
            $.showErr("系统错误");
        },
        success: function (obj) {
            if (obj.status == 1) {
                location.href = obj.jump;
            } else {
                $.showErr(obj.info, function () {
                    if (obj.jump) {
                        location.href = obj.jump;
                    }
                });
            }
        }
    })
}
var infinite_loading_number_choose = false;
var init_number_choose_scroll_bottom_back = "";
function init_scroll_bottom_number_choose(callback) {
    init_number_choose_scroll_bottom_back = callback;
    $(".scroll_bottom_page_number_choose").hide();
    if ($(".scroll_bottom_page_number_choose").length > 0) {
        $(".scroll_bottom_page_number_choose").after("<div class='page-load'></div>");
        $(".page-load").html("努力加载中...~");
    }
    $(window).unbind("scroll").scroll(function () {
        var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()) + 100;
        if ($(document).height() <= totalheight) {
            if (infinite_loading_number_choose)return;
            var next_dom = $(".scroll_bottom_page_number_choose").find("span.current").next();
            if (next_dom.length > 0) {
                var url = $(".scroll_bottom_page_number_choose").find("span.current").next().attr("href");
                $(".page-load").html("努力加载中...~");
                infinite_loading_number_choose = true;
                $.ajax({
                    url: url,
                    type: "POST",
                    success: function (html) {
                        var append_html='';
                        if(is_all_select==1){
                            $(html).find(".scroll_bottom_list_number_choose").each(function(i){
                                $(this).find(".code-box").addClass('active');
                                $(this).find(".disable .code-box").removeClass('active');
                                append_html+=$(this).html();
                            });
                        }else{
                           append_html=$(html).find(".scroll_bottom_list_number_choose").html();
                        }
                        $(".scroll_bottom_list_number_choose").append(append_html);
                        $(".scroll_bottom_page_number_choose").html($(html).find(".scroll_bottom_page_number_choose").html());
                        infinite_loading_number_choose = false;
                        $(".page-load").html("");
                        if (typeof init_number_choose_scroll_bottom_back == "function") {
                            init_number_choose_scroll_bottom_back();
                        }
                    },
                    error: function () {
                    }
                });
            }
            else {
                $(".page-load").html("已经到底部了...~");
            }
        }
    })
}