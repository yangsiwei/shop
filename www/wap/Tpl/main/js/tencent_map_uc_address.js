var qqGeocoder,qqMap,qqMarker,citylocation = null;
jQuery(function($){
	$(window).load(function(){
        var mapCenter = null;
        qqMap = new qq.maps.Map(document.getElementById('qqmapcontainer'),{
            zoom: 14
        });
        qqMarker = new qq.maps.Marker({
            map:qqMap,
            draggable:true,
        });

        citylocation = new qq.maps.CityService({
            complete : function(result){
				var xpoint = $('#post_xpoint').val();
				var ypoint = $('#post_ypoint').val();
				if( !xpoint&&!ypoint ){
					xpoint = result.detail.latLng.getLng();
					ypoint = result.detail.latLng.getLat();
				}else{
					$('.map_address_str').show();
				}
				var latLng = new qq.maps.LatLng(ypoint,xpoint);
				
                qqMap.setCenter(latLng);
                qqMarker.setPosition(latLng);
				$("#xpoint").val(xpoint);
				$("#ypoint").val(ypoint);
                qqcGeocoder.getAddress(latLng);
            }
        });
        citylocation.searchLocalCity();
		
        qq.maps.event.addListener(qqMarker, 'dragend', function(event) {
            qqMarker.setPosition(event.latLng);
			$("#xpoint").val(event.latLng.getLng());
			$("#ypoint").val(event.latLng.getLat());
            qqcGeocoder.getAddress(event.latLng);
        });
        qq.maps.event.addListener(qqMap, 'click', function(event) {
            qqMarker.setPosition(event.latLng);
			$("#xpoint").val(event.latLng.getLng());
			$("#ypoint").val(event.latLng.getLat());
            qqcGeocoder.getAddress(event.latLng);
        });

        qqcGeocoder = new qq.maps.Geocoder({
            complete : function(result){
                $("#detail_input").val(result.detail.address);
				$('input[name="map_address"]').val(result.detail.address);
            }
        });

        qqGeocoder = new qq.maps.Geocoder({
            complete : function(result){
                qqMap.setCenter(result.detail.location);
                qqMarker.setPosition(result.detail.location);
				$("#xpoint").val(result.detail.location.getLng());
				$("#ypoint").val(result.detail.location.getLat());
                $("#detail_input").val(result.detail.address);
				$('input[name="map_address"]').val(result.detail.address);
            }
        });

        $(".addr_search").click(function(){
            if($.trim($("#addr_input").val()) != ""){
                qqGeocoder.getLocation($("#addr_input").val());
                $("#detail_input").val($("#addr_input").val());
            }
            $("#searchaddr-form").submit();
        });
        $("#searchaddr-form").submit(function(event) {
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
			//把值带回
			$("#post_xpoint").val(xpoint);
			$("#post_ypoint").val(ypoint);
			$('input[name="map_address"]').val(address);
			$('.map_address_str').show();
			//隐藏地图
			$('.map_select_box').hide();
		})
		
	});
});	