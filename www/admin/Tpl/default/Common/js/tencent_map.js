var qqgeocoder,qqmap,qqmarker,qqcircle = null;
$(document).ready(function(){
	init_geo_map();
	init_geo_round();
});


function init_geo_round()
{
	var current_value = $("input[name='scale_meter']").val();
	current_value = (!current_value||isNaN(current_value))?0:$.trim(current_value);

	$( "#slider" ).slider({
		min:0,
		max:10000,
		value:current_value,
		change: function( event, ui ) {			
			var xpoint = $("#Xpoint").val()?$("#Xpoint").val():0;
			var ypoint = $("#Ypoint").val()?$("#Ypoint").val():0;
			var mapcenter = new qq.maps.LatLng(ypoint,xpoint);
			$("input[name='scale_meter']").val(ui.value);
			$("#show_scale_meter").html(ui.value);
			qqcircle.setRadius(ui.value);
			qqcircle.setCenter(mapcenter);			
			
		}
	});
	
	var xpoint = $("#Xpoint").val()?$("#Xpoint").val():0;
	var ypoint = $("#Ypoint").val()?$("#Ypoint").val():0;
	
	
	var mapcenter = new qq.maps.LatLng(ypoint,xpoint);
	qqcircle = new qq.maps.Circle({
        map: qqmap,
        center: mapcenter,
        radius: parseInt(current_value),       
        strokeWeight: 3
    });
	
}


/**
 * 初始化地图
 */
function init_geo_map()
{
	var xpoint = $("#Xpoint").val()?$("#Xpoint").val():0;
	var ypoint = $("#Ypoint").val()?$("#Ypoint").val():0;
	
	
	var mapcenter = new qq.maps.LatLng(ypoint,xpoint);
	qqmap = new qq.maps.Map(document.getElementById('qqmapcontainer'),{
	    center: mapcenter,
	    zoom: 13
	});
	
	qqmarker = new qq.maps.Marker({
	    map:qqmap,
	    draggable:true,
	    position: mapcenter
	});
	qq.maps.event.addListener(qqmarker, 'dragend', function(event) {
	    $("#Xpoint").val(event.latLng.getLng());
	    $("#Ypoint").val(event.latLng.getLat());
	    qqcircle.setCenter(event.latLng);
	});
	
	if(xpoint==0&&ypoint==0)
	{
		var citylocation = new qq.maps.CityService({
		    complete : function(result){
		        qqmap.setCenter(result.detail.latLng);
		        qqmarker.setPosition(result.detail.latLng);
		        $("#Xpoint").val(result.detail.latLng.getLng());
		        $("#Ypoint").val(result.detail.latLng.getLat());
		    }
		});
		citylocation.searchLocalCity();
	}
	
	qqgeocoder = new qq.maps.Geocoder({
	    complete : function(result){
	        qqmap.setCenter(result.detail.location);
	        qqmarker.setPosition(result.detail.location);
	        $("#Xpoint").val(result.detail.location.getLng());
	        $("#Ypoint").val(result.detail.location.getLat());
	        
	        var current_value = $("input[name='scale_meter']").val(); 
	        current_value = (!current_value||isNaN(current_value))?0:$.trim(current_value);

	        qqcircle.setRadius(parseInt(current_value));
			qqcircle.setCenter(result.detail.location);
	    }
	});
	
	$("#lbs_btn").click(function(){
	    if($.trim($("#api_address").val()) != ""){
	        qqgeocoder.getLocation($("#api_address").val());
	    }
	});
}

/**
 * 根据城市名称搜索地图，初始化
 * @param city_name
 */
function load_geo_map_cityname(city_name)
{
	//设置城市信息查询服务
    citylocation = new qq.maps.CityService();
    citylocation.searchCityByName(city_name);
   
    //请求成功回调函数
    citylocation.setComplete(function(result) {
        qqmap.setCenter(result.detail.latLng);
        qqmarker.setPosition(result.detail.latLng);
        $("#Xpoint").val(result.detail.latLng.getLng());
        $("#Ypoint").val(result.detail.latLng.getLat());
        
        
        var current_value = $("input[name='scale_meter']").val();   
        current_value = (!current_value||isNaN(current_value))?0:$.trim(current_value);

        qqcircle.setRadius(parseInt(current_value));
		qqcircle.setCenter(result.detail.latLng);	
        
    	
    });
}

/**
 * 根据经纬度重置地图
 * @param xpoint
 * @param ypoint
 */
function load_geo_map_xy(xpoint,ypoint)
{
	var mapcenter = new qq.maps.LatLng(ypoint,xpoint);
	if(qqmap!=null)
	{
		qqmap.setCenter(mapcenter);
	    qqmarker.setPosition(mapcenter);
	}
	 
     $("#Xpoint").val(xpoint);
     $("#Ypoint").val(ypoint);
     
     if(qqcircle!=null)
     {
    	 var current_value = $("input[name='scale_meter']").val();
    	 current_value = (!current_value||isNaN(current_value))?0:$.trim(current_value);

    	 qqcircle.setRadius(parseInt(current_value));
    	 qqcircle.setCenter(mapcenter);	 
     }
     
     
}