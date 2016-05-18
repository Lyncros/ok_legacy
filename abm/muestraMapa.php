<?php
include_once("./generic_class/class.cargaConfiguracion.php");

$conf=CargaConfiguracion::getInstance('');
$anchoPagina=$conf->leeParametro("ancho_pagina");
$gmapkey=$conf->leeParametro("gmkey");
$address=$_GET['a'];
$lat=$conf->leeParametro("lat");
$long=$conf->leeParametro("long");

print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
print "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
print "<head>\n";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
print "<title>O'Keefe Propiedades</title>\n";
print "<link href=\"css/agenda.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
print "<script src=\"http://maps.google.com/maps?file=api&v=2&key=$gmapkey\" type=\"text/javascript\"></script>\n";

print "     <script type=\"text/javascript\">    \n";
print "     var map = null;\n";
print "     var geocoder = null;\n";
print "		var address;\n";

print "      function initialize() {\n";
print "      	if (GBrowserIsCompatible()) {\n";
print "      		map = new GMap2(document.getElementById(\"map_canvas\"),{ size: new GSize(600,400) } );\n";
print "      		map.setCenter(new GLatLng($lat,$long), 13);\n";
print "			map.setUIToDefault();\n";
//print "		map.setMapType(G_DEFAULT_MAP_TYPES);\n";
//print "      		map.addControl(new GOverviewMapControl());\n";
//print "      		map.enableDoubleClickZoom();\n";
//print "      		map.enableScrollWheelZoom();\n";
//print "      		map.addControl(new GMapTypeControl());\n";
//print "      		map.addControl(new GSmallMapControl());\n";

print "			GEvent.addListener(map,'click',getAddress);\n";
print "      		geocoder = new GClientGeocoder();\n";
print "	       		showAddress(\"$address\");\n";
print "      	}\n";
print "      }\n";

print "    function getAddress(overlay, latlng) {\n";
print "      if (latlng != null) {\n";
print "        address = latlng;\n";
print "        geocoder.getLocations(latlng, muestraDatos);\n";
print "      }\n";
print "    }\n";

print "      function actualizaMapa() {\n";
print "        address = document.getElementById(\"address\").value;\n";
print "      	if (geocoder) {\n";
print "      		geocoder.getLocations(address,muestraDatos);\n";
print "      	}\n";
print "		}\n";

print "      function showAddress(address) {\n";
print "      	if (geocoder) {\n";
print "      		geocoder.getLocations(address,muestraDatos);\n";
print "      	}\n";
print "		}\n";

print "      function muestraDatos(response) {\n";
//print "      	map.clearOverlays();\n";
print "      	if (!response || response.Status.code != 200) {\n";
print "      		alert(\"Status Code:\" + response.Status.code);\n";
print "      	} else {\n";
print "      		place = response.Placemark[0];\n";
print "      		point = new GLatLng(place.Point.coordinates[1],\n";
print "      		place.Point.coordinates[0]);\n";
print "      		marker = new GMarker(point, {draggable: true});\n";
print "      		map.addOverlay(marker);\n";
print "      		marker.openInfoWindowHtml(\n";
print "      		'<b>orig latlng:</b>' + response.name + '<br/>' +\n";
print "      		'<b>latlng:</b>' + place.Point.coordinates[1] + \",\" + place.Point.coordinates[0] + '<br>' +\n";
print "      		'<b>Status Code:</b>' + response.Status.code + '<br>' +\n";
print "      		'<b>Status Request:</b>' + response.Status.request + '<br>' +\n";
print "      		'<b>Address:</b>' + place.address + '<br>' +\n";
print "      		'<b>Accuracy:</b>' + place.AddressDetails.Accuracy + '<br>' +\n";
print "      		'<b>Country code:</b> ' + place.AddressDetails.Country.CountryNameCode);\n";
print "				document.getElementById(\"lat\").value=place.Point.coordinates[1];\n";
print "				document.getElementById(\"long\").value=place.Point.coordinates[0];\n";
print "				document.getElementById(\"addressmap\").value=place.address;\n";

print "				GEvent.addListener(marker, \"dragstart\", function() {\n";
print "				  	map.closeInfoWindow();\n";
print "				  });\n";

print "				GEvent.addListener(marker, \"dragend\", function() {\n";

print "					var point = marker.getPoint();\n";
print "					map.panTo(point);\n";

print "      			marker.openInfoWindowHtml(\n";
print "      			'<b>orig latlng:</b>' + response.name + '<br/>' +\n";
print "      			'<b>latlng:</b>' + point.lat() + \",\" + point.lng() + '<br>' +\n";
print "      			'<b>Status Code:</b>' + response.Status.code + '<br>' +\n";
print "      			'<b>Status Request:</b>' + response.Status.request + '<br>' +\n";
print "     	 		'<b>Address:</b>' + place.address + '<br>' +\n";
print "     	 		'<b>Accuracy:</b>' + place.AddressDetails.Accuracy + '<br>' +\n";
print "     	 		'<b>Country code:</b> ' + place.AddressDetails.Country.CountryNameCode);\n";
print "					document.getElementById(\"lat\").value=point.lat();\n";
print "					document.getElementById(\"long\").value=point.lng();\n";
print "					document.getElementById(\"addressmap\").value=place.address;\n";
print "				  });\n";

//print "				map.addOverlay(marker);\n";

print "      	}\n";
print "      }\n";
print "      function actualizaOrigen() {\n";
print "         opener.document.getElementById(\"goglat\").value=document.getElementById(\"lat\").value;\n";
print "			opener.document.getElementById(\"goglong\").value=document.getElementById(\"long\").value;\n";
print "			window.close();\n";
print "		}\n";



print "    </script>\n";
print "</head>\n";
print "<body onload=\"initialize()\" onunload=\"GUnload()\">\n";
print "    <form action=\"#\" onsubmit=\"showAddress(this.address.value); return false\">\n";
print "		<table><tr><td>Domicilio Original</td><td><input type=\"text\" size=\"60\" id=\"address\" value=\"$address\" /></td></tr>\n";
print "		   <tr><td colspan=2><input type=\"button\"  =\"60\" name=\"buscar\" value=\"Localizar\" onclick=\"actualizaMapa();\"/></td></tr>\n";
print "		   <tr><td>Domicilio Mapa</td><td><input type=\"text\" size=\"60\" id=\"addressmap\" /></td></tr>\n";
print "		   <tr><td>Latitud</td><td><input type=\"text\" size=\"60\" id=\"lat\" /></td></tr>\n";
print "		   <tr><td>Longitud</td><td><input type=\"text\" size=\"60\" id=\"long\" /></td></tr>\n";
print "		   <tr><td colspan=2><input type=\"button\" size=\"60\" name=\"enviar\" value=\"Enviar\" onclick=\"actualizaOrigen();\"/></td></tr></table>\n";
print "      <div id=\"map_canvas\" style=\"width: 600px; height: 400px\"></div>\n";
print "    </form>\n";
print "</body>\n";
print "</html>\n";
?>