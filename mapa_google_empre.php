<?php
////date_default_timezone_set('America/Argentina/Buenos_Aires');
//require_once('lib-nusoap/nusoap.php');

$lat = $_GET['lat'];
$long = $_GET['long'];


?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Achaval Cornejo Propiedades</title>
        <link href="css/achaval.css" rel="stylesheet" type="text/css" />
        <script src="http://maps.google.com/maps?file=api&v=2&key=AIzaSyDwRS0eQiLLCJyhoNAKyAD7oKmOJVA7KLw" type="text/javascript"></script>
        <script type="text/javascript">

            var map = null;
            var geocoder = null;
            var contextmenu;
            function load() {
                if (GBrowserIsCompatible()) {
                    var point;
                    map=new GMap2(document.getElementById("map"), { size: new GSize(600,400) });
                    map.setUIToDefault();
                    /*
                    map.addControl(new GOverviewMapControl());
                    map.enableDoubleClickZoom();
                    map.enableScrollWheelZoom();
                    map.addControl(new GMapTypeControl());
                    map.addControl(new GLargeMapControl());*/
                    createContextMenu(map);

            point = new GLatLng(<?php echo $lat . ",". $long?>);

            var marker = new GMarker(point);
            map.setCenter(point,14);
            map.addOverlay(marker);
            map.setMapType(G_MAPMAKER_NORMAL_MAP);
        }
    }

    function createContextMenu(map) {
        contextmenu = document.createElement("div");
        contextmenu.style.visibility="hidden";
        contextmenu.style.background="#ffffff";
        contextmenu.style.border="1px solid #8888FF";

        contextmenu.innerHTML = '<a href="javascript:zoomIn()"><div class="context">&nbsp;&nbsp;Zoom in&nbsp;&nbsp;</div></a>'
            + '<a href="javascript:zoomOut()"><div class="context">&nbsp;&nbsp;Zoom out&nbsp;&nbsp;</div></a>'
            + '<a href="javascript:zoomInHere()"><div class="context">&nbsp;&nbsp;Zoom in here&nbsp;&nbsp;</div></a>'
            + '<a href="javascript:zoomOutHere()"><div class="context">&nbsp;&nbsp;Zoom out here&nbsp;&nbsp;</div></a>'
            + '<a href="javascript:centreMapHere()"><div class="context">&nbsp;&nbsp;Centre map here&nbsp;&nbsp;</div></a>';

        map.getContainer().appendChild(contextmenu);
        GEvent.addListener(map,"singlerightclick",function(pixel,tile) {
            clickedPixel = pixel;
            var x=pixel.x;
            var y=pixel.y;
            if (x > map.getSize().width - 120)
            {
                x = map.getSize().width - 120
            }
            if (y > map.getSize().height - 100)
            {
                y = map.getSize().height - 100
            }
            var pos = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(x,y));
            pos.apply(contextmenu);
            contextmenu.style.visibility = "visible";
        });
        GEvent.addListener(map, "click", function() {
            contextmenu.style.visibility="hidden";
        });
    }
    function zoomIn() {
        map.zoomIn();
        contextmenu.style.visibility="hidden";
    }
    function zoomOut() {
        map.zoomOut();
        contextmenu.style.visibility="hidden";
    }
    function zoomInHere() {
        var point = map.fromContainerPixelToLatLng(clickedPixel)
        map.zoomIn(point,true);
        contextmenu.style.visibility="hidden";
    }
    function zoomOutHere() {
        var point = map.fromContainerPixelToLatLng(clickedPixel)
        map.setCenter(point,map.getZoom()-1);
        contextmenu.style.visibility="hidden";
    }
    function centreMapHere() {
        var point = map.fromContainerPixelToLatLng(clickedPixel)
        map.setCenter(point);
        contextmenu.style.visibility="hidden";
    }
        </script>

   </head>
    <body onload="load();" onunload="GUnload()">
        <div id="map" style="width: 600px; height: 400px;"></div>
    </body>
</html>