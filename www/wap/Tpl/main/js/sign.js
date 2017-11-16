var calUtil = {  
  //当前日历显示的年份  
  showYear:2017,
  //当前日历显示的月份  
  showMonth:11,
  //当前日历显示的天数  
  showDays:10,
  eventName:"load",  
  //初始化日历  
  init:function(signList){  
    calUtil.setMonthAndDay();  
    calUtil.draw(signList);  
    calUtil.bindEnvent();
    calUtil.getData();  
  },  
  draw:function(signList){  
    //绑定日历  
    var str = calUtil.drawCal(calUtil.showYear,calUtil.showMonth,signList); 
/*   var img1= <img src="">;*/
      var xia="<img class='calendar_month_next' src='./Tpl/main/images/right.png'>";
      var shang="<img class='calendar_month_prev' src='./Tpl/main/images/left.png'>";
    $("#calendar").html(str);  
    //绑定日历表头
      var str = calUtil.showMonth;
    var calendarName=calUtil.showYear+"年"+str+"月";
    $(".calendar_month_span").html(shang+'&nbsp;&nbsp;'+calendarName+'&nbsp;&nbsp;'+xia);    
  },  
  //绑定事件  
  bindEnvent:function(){  
    //绑定上个月事件  
    $(".calendar_month_prev").click(function(){  
      //ajax获取日历json数据 
      var nowMonth=$(".calendar_month_span").html().split("年")[1].split("月")[0];
      calUtil.showMonth=parseInt(nowMonth)-1;
        if(calUtil.showMonth==0)
        {
            calUtil.showMonth=12;
          
        }
           $.ajax({
               type: "POST",
               url: "./index.php?ctl=index&act=monthn",
               data: "showMonth="+calUtil.showMonth+"&"+"showYear="+calUtil.showYear,
               // dataType: "json",
               success: function(datas){
                   if (datas!=="") {
                       var signList = eval('(' + datas + ')');
                       // console.log(signList);
                       calUtil.eventName = "prev";
                       calUtil.init(signList);
                       $('.zise').html("<img src='./Tpl/main/images/liwu.png' >");
                   }else{
                       var signList=[{"dateline":""}];
                       // console.log(signList);
                       calUtil.eventName = "prev";
                       calUtil.init(signList);
                   }
               }
           });
    });
    //绑定下个月事件  
    $(".calendar_month_next").click(function(){  
      //ajax获取日历json数据 
      var nowMonth=$(".calendar_month_span").html().split("年")[1].split("月")[0];  
        calUtil.showMonth=parseInt(nowMonth)+1;  
        if(calUtil.showMonth==13)  
        {  
            calUtil.showMonth=1;  
           
        }
        $.ajax({
            type: "POST",
            url: "./index.php?ctl=index&act=months",
            data: "showMonth="+calUtil.showMonth+"&"+"showYear="+calUtil.showYear,
            // dataType: "json",
            success: function(datas){
                var signList=eval('('+datas+')');
                // var signList=[{"dateline":"1"},{"dateline":"13"},{"dateline":"21"},{"dateline":"19"}];
               if (datas!==""){
                   var signList=eval('('+datas+')');
                   calUtil.eventName="next";
                   calUtil.init(signList);
                   $('.zise').html("<img src='./Tpl/main/images/liwu.png' >");
               }else{
                   var signList=[{"dateline":""}];
                   calUtil.eventName="next";
                   calUtil.init(signList);
               }

            }
        });
    });

  },  
  //获取当前选择的年月  
  setMonthAndDay:function(){  
    switch(calUtil.eventName)  
    {  
      case "load":  
        var current = new Date();  
        calUtil.showYear=current.getFullYear();  
        calUtil.showMonth=current.getMonth() + 1;   
        break;  
      case "prev":  
        var nowMonth=$(".calendar_month_span").html().split("年")[1].split("月")[0];  
        calUtil.showMonth=parseInt(nowMonth)-1;  
        if(calUtil.showMonth==0)  
        {  
            calUtil.showMonth=12;  
            calUtil.showYear-=1;  
        }  
        break;  
      case "next":  
        var nowMonth=$(".calendar_month_span").html().split("年")[1].split("月")[0];  
        calUtil.showMonth=parseInt(nowMonth)+1;  
        if(calUtil.showMonth==13)  
        {  
            calUtil.showMonth=1;  
            calUtil.showYear+=1;  
        }  
        break;  
    }  
  },  
  getDaysInmonth : function(iMonth, iYear){  
   var dPrevDate = new Date(iYear, iMonth, 0); 
   /*console.log(dPrevDate.getDate()) ;*/
   return dPrevDate.getDate();  
  },  
  bulidCal : function(iYear, iMonth) {  
   var aMonth = new Array();  
   aMonth[0] = new Array(7);  
   aMonth[1] = new Array(7);  
   aMonth[2] = new Array(7);  
   aMonth[3] = new Array(7);  
   aMonth[4] = new Array(7);  
   aMonth[5] = new Array(7);  
   aMonth[6] = new Array(7);  
   var dCalDate = new Date(iYear, iMonth - 1, 1);  
   var iDayOfFirst = dCalDate.getDay();  
   var iDaysInMonth = calUtil.getDaysInmonth(iMonth, iYear);  
   var iVarDate = 1;  
   var d, w;  
   aMonth[0][0] = "日";  
   aMonth[0][1] = "一";  
   aMonth[0][2] = "二";  
   aMonth[0][3] = "三";  
   aMonth[0][4] = "四";  
   aMonth[0][5] = "五";  
   aMonth[0][6] = "六";  
   for (d = iDayOfFirst; d < 7; d++) {  
    aMonth[1][d] = iVarDate;  
    iVarDate++;  
   }  
   for (w = 2; w < 7; w++) {  
    for (d = 0; d < 7; d++) {  
     if (iVarDate <= iDaysInMonth) {  
      aMonth[w][d] = iVarDate;  
      iVarDate++;  
     }  
    }  
   }  
   return aMonth;  
  },  
  ifHasSigned : function(signList,day){  
   var signed = false;  
   $.each(signList,function(index,item){  
    if(item.dateline == day) {
     signed = true;  
     return false;  
    }  
   });  
   return signed ;  
  },  
  drawCal : function(iYear, iMonth ,signList) {  
   var myMonth = calUtil.bulidCal(iYear, iMonth);  
   var htmls = new Array();  
   htmls.push("<div class='sign_main' id='sign_layer'>");  
   htmls.push("<div class='sign_succ_calendar_title'>");  
   htmls.push("<div class='calendar_month_span'></div>");  
   htmls.push("</div>");  
   htmls.push("<div class='sign' id='sign_cal'>");  
   htmls.push("<table>");  
   htmls.push("<tr>");  
   htmls.push("<th>" + myMonth[0][0] + "</th>");  
   htmls.push("<th>" + myMonth[0][1] + "</th>");  
   htmls.push("<th>" + myMonth[0][2] + "</th>");  
   htmls.push("<th>" + myMonth[0][3] + "</th>");  
   htmls.push("<th>" + myMonth[0][4] + "</th>");  
   htmls.push("<th>" + myMonth[0][5] + "</th>");  
   htmls.push("<th>" + myMonth[0][6] + "</th>");  
   htmls.push("</tr>");  
   var d, w;  
   htmls.push("<tr>");
   for (d = 0; d < 8; d++) {  
      htmls.push("<td style='height:20px;'>");
      htmls.push("</td>"); 
    }
    htmls.push("</tr>");
   for (w = 1; w < 7; w++) {  
    htmls.push("<tr>");  
    for (d = 0; d < 8; d++) {  
     var ifHasSigned = calUtil.ifHasSigned(signList,myMonth[w][d]);  
     if(ifHasSigned){  
      htmls.push("<td class='zise'>" + (!isNaN(myMonth[w][d]) ? myMonth[w][d] : " ") + "</td>");
     } else {  
      htmls.push("<td>" + (!isNaN(myMonth[w][d]) ? myMonth[w][d] : " ") + "</td>");
     } 
    }
    htmls.push("</tr>");  
   }  
   htmls.push("</table>");
    htmls.push("</div>");  
   htmls.push("</div>");
   return htmls.join('');  
  } ,
  getData:function(){
    // $('.sign>table>tbody>tr>td').click(function(){
    //     var lock=false;
    //     if(!lock) {
    //                 lock=true;
    //         $.ajax({
    //                   type: "POST",
    //                   url: "{url x=\"index\" r=\"index#qid\"}",
    //                   data: "do=sign",
    //                   dataType: "json",
    //                   success: function(datas){
    //                      lock=false;
    //                    if(datas.status = 'success'){
    //                           var info = datas.info;
    //                             var msg = datas.msg;
    //                             $(".signin_msg").html(msg);
    //                             $(".signin_price").html(info);
    //                             $("#msg_dom1").css("display","block");
    //                     }
    //                 }
    //             });
    //       }

        /*var data=new Date().getDate();
        var FullYear=new Date().getFullYear();
        var month=new Date().getMonth()+1;
        var pjie='  '+FullYear+'年'+month+'月'+'  ';
        var yemian=$(this).html();
        var yemianyue=$('.calendar_month_span').text();
        console.log(pjie);
        console.log(yemianyue);
        if(data==yemian){
          $(this).html("<img src='./Tpl/main/images/liwu.png' >");
        }else{
          alert('请点击当天签到！');
        }*/
   // });
  } ,
};  
                