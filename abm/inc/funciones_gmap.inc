<?php
function mapaGmap(){
print "<script type='text/javascript' language='javascript'>\n";

		print "function cargaAddress(address) {\n";
		print "if (geocoder) {\n";
		print "geocoder.getLocations(address,cargaDatos);\n";
		print "}\n";
		print "}\n";

		print "function cargaDatos(response) {\n";
		print "if (!response || response.Status.code != 200) {\n";
		print "		alert(\"Status Code:\" + response.Status.code);\n";
		print "retorno='';\n";
		print "} else {\n";
		print "	place = response.Placemark[0];\n";
		print "	point = new GLatLng(place.Point.coordinates[1],\n";
		print "	place.Point.coordinates[0]);\n";
		print "	document.getElementById(\"goglat\").value=place.Point.coordinates[1];\n";
		print "	document.getElementById(\"goglong\").value=place.Point.coordinates[0];\n";
		print "}\n";
		print "}\n";

		print "function popupMapa(origen) {\n";
		print "	address='';\n";
		//print "	elemLoca=document.getElementById('id_loca').selectedIndex;\n";
		//print "	elemZona=document.getElementById('id_zona').selectedIndex;\n";
		print "	if(origen!='e'){\n";
		print "		address = document.getElementById('calle').value + ' ' + document.getElementById('nro').value + ',';\n";
		print "	}\n";
		print "	zona=document.getElementById('txtUbica').innerHTML;\n";
		//print "	zona=document.getElementById('id_zona').options[elemZona].text;\n";
		//print "	if(zona=='Ciudad Autónoma de Buenos Aires'){";
		print 		"address += zona+',Argentina';\n";
		//print "	}else{\n";
		//print "		address += document.getElementById('id_loca').options[elemLoca].text+','+zona+',Argentina';\n";
		//print "	}\n";
		print "	window.open('muestraMapa.php?a='+address,'GoogleMaps', 'location=1,status=1,scrollbars=1, width=630,height=600');\n";
		print "}\n";
		print "</script>\n";
}
?>
