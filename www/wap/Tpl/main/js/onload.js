//  window.addEventListener("DOMContentLoaded", function(){
//        btn = document.getElementById("plug-btn");
//        btn.onclick = function(){
//            var divs = document.getElementById("plug-phone").querySelectorAll("div");
//            var className = className=this.checked?"on":"";
//            for(i = 0;i<divs.length; i++){
//                divs[i].className = className;
//            }
//            document.getElementById("plug-wrap").style.display = "on" == className? "block":"none";
//        }
//    }, false);
	 $(function(){
        new Swipe(document.getElementById('banner_box'), {
            speed:500,
            auto:3000,
            callback: function(){
                var lis = $(this.element).next("ol").children();
                lis.removeClass("on").eq(this.index).addClass("on");
            }
        });
    });