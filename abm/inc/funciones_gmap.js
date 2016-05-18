function initialize(elemento) {
	if (GBrowserIsCompatible()) {
		geocoder = new GClientGeocoder();
		document.getElementById(elemento).style.display='none';
	}
}

function initializeMapa(){
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("map_canvas"));
		map.setCenter(new GLatLng(-34.6049311,-58.3865514), 13);
	}	
}

function showAddress(address) {
	if (geocoder) {
		geocoder.getLocations(address,muestraDatos);
	}
}



function muestraDatos(response) {
	map.clearOverlays();
	if (!response || response.Status.code != 200) {
		alert("Status Code:" + response.Status.code);
		retorno='';
	} else {
		place = response.Placemark[0];
		point = new GLatLng(place.Point.coordinates[1],
		place.Point.coordinates[0]);
		marker = new GMarker(point);
		map.addOverlay(marker);
		marker.openInfoWindowHtml(
		'<b>orig latlng:</b>' + response.name + '<br/>' +
		'<b>latlng:</b>' + place.Point.coordinates[1] + "," + place.Point.coordinates[0] + '<br>' +
		'<b>Status Code:</b>' + response.Status.code + '<br>' +
		'<b>Status Request:</b>' + response.Status.request + '<br>' +
		'<b>Address:</b>' + place.address + '<br>' +
		'<b>Accuracy:</b>' + place.AddressDetails.Accuracy + '<br>' +
		'<b>Country code:</b> ' + place.AddressDetails.Country.CountryNameCode);
	}
}



function muestraMapa(address,elemento,tipo){
	if(document.getElementById(elemento).style.display=='none'){
		initializeMapa();
		if(tipo=='tr'){
			document.getElementById(elemento).style.display='table-row';
		}else{
			document.getElementById(elemento).style.display="block";
		}
		showAddress(address);
	}else{
		document.getElementById(elemento).style.display='none';
	}
}
