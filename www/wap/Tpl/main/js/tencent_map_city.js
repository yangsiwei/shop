var qqGeocoder,qqMap,qqMarker,citylocation = null;
jQuery(function($){
	$('.addr_save').html("前往");
	
	$(window).load(function(){
        var mapCenter = null;
        qqMap = new qq.maps.Map(document.getElementById('qqmapcontainer'),{
            zoom: 14
        });
        qqMarker = new qq.maps.Marker({
            map:qqMap,
            draggable:false,
        });
		
//        qq.maps.event.addListener(qqMarker, 'dragend', function(event) {console.log(event);
//            qqMarker.setPosition(event.latLng);
//			$("#xpoint").val(event.latLng.getLng());
//			$("#ypoint").val(event.latLng.getLat());
//            qqcGeocoder.getAddress(event.latLng);
//        });
        qq.maps.event.addListener(qqMap, 'click', function(event) {
            qqMarker.setPosition(event.latLng);
			$("#xpoint").val(event.latLng.getLng());
			$("#ypoint").val(event.latLng.getLat());
            qqcGeocoder.getAddress(event.latLng);
        });

        qqcGeocoder = new qq.maps.Geocoder({
            complete : function(result){
                $("#detail_input").val(result.detail.address);
            }
        });

        qqGeocoder = new qq.maps.Geocoder({
            complete : function(result){
                qqMap.setCenter(result.detail.location);
                qqMarker.setPosition(result.detail.location);
				$("#xpoint").val(result.detail.location.getLng());
				$("#ypoint").val(result.detail.location.getLat());
                $("#detail_input").val(result.detail.address);
            }
        });

        $(".addr_search").click(function(){
            if($.trim($("#addr_input").val()) != ""){
                qqGeocoder.getLocation($("#addr_input").val());
                $("#detail_input").val($("#addr_input").val());
            }
            $("#searchaddr-form").submit();
        });
        $("#searchaddr-form").submit(function() {
        	/* Act on the event */
        	if($.trim($("#addr_input").val()) != ""){
                qqGeocoder.getLocation($("#addr_input").val());
                $("#detail_input").val($("#addr_input").val());
            }
        });
		
		
		/**选好地址，确定**/
		$(".addr_save").click(function(){
			var address =  $.trim($("#detail_input").val());
			var xpoint = $.trim($("#xpoint").val());
			var ypoint = $.trim($("#ypoint").val());
			var latitude = ypoint; //纬度
			var longitude = xpoint;
			//将坐标返回到服务端;
			var query = new Object();
			query.m_latitude = latitude;
			query.m_longitude = longitude;
			$.ajax({
				url:geo_url,
				data:query,
				type:"post",
				dataType:"json",
				success:function(data){
					//已经有了地理位置信息，首页不需要再弹出定位失败的提示
					setCookie("cancel_geo",1,1);
					location.href = city_url+"&city_id="+data.city.id;
				}
				,error:function(){		
				}
			});
			
		});
		
		if(ip_city_json){
			$('.city_table a').each(function(index, element) {
				if( $(this).html()==ip_city_json.name ){
					map_show($(this));
					$('#header_back_btn').attr('backurl','-1');
					return false
				}
			});
		}
		
	});
});	

/**
 * 显示地图
*/
function map_show(_this){
	//修改header里的后退数值
	try{
		$('#header_back_btn').attr('backurl',window.location.href);
	}catch(e){};
	
	$('#city_content_box').hide();
	$('.map_select_box').show();
	
	var url = $(_this).attr('rel');
	var city_name = $(_this).html();
	
	$("#addr_input").val(city_name);
	$("#detail_input").val(city_name);
	$(".addr_save").attr('rel',url);
	
	qqGeocoder.getLocation(city_name);
	
}

/**
 * 写入cookies
*/
function setCookie(name, value, iDay){ 
    /* iDay 表示过期时间   

    cookie中 = 号表示添加，不是赋值 */   

    var oDate=new Date();   

    oDate.setDate(oDate.getDate()+iDay);       

    document.cookie=name+'='+value+';expires='+oDate;

}