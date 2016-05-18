<?php
////date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once('lib-nusoap/nusoap.php');

$id_prop = $_GET['id'];
$URLNow = $_SERVER['SERVER_NAME'];
$_SERVER['HTTP_REFERER'] = $URLNow;

//$wsdl="http://www.zgroupsa.com.ar/achaval/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$wsdl="http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice

//¿ocurrio error al llamar al web service?
if ($client->fault) { // si
    echo 'No se pudo completar la operación';
    die();
}else { // no
    $error = $client->getError();
    if ($error) { // Hubo algun error
        echo 'Error:' . $error;
    }
}

$param=array('id_prop'=>$id_prop);
$prop = $client->call('Propiedad',$param,'');
//print_r($prop);

$tipo_prop = $client->call('ListarTipoProp', array(),'');
//print_r($tipo_prop);

$param=array('id_prop'=>$id_prop);
$carac = $client->call('ListarDatosPropiedad',$param,'');
//print_r($carac);

function busca_valor($id_carac, $arreglo) {
    for($j = 0; $j < count($arreglo); $j++) {
        if($arreglo[$j]['id_carac'] == $id_carac) {
            if($arreglo[$j]['contenido'] == "") {
                $valor = "-";
            }else {
                $valor = $arreglo[$j]['contenido'];
            }
            return $valor;
            break;
        }
    }
}
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_carac'] == 42) {
        if(is_null($carac[$j]['contenido'])) {
            $estado = "Sin definir";
        }else {
            $estado = $carac[$j]['contenido'];
        }
    }
    if($prop['operacion'] =="Venta") {
        if($carac[$j]['id_carac'] == 165) {
            if(is_null($carac[$j]['contenido'])) {
                $moneda = "Sin definir";
            }else {
                $moneda = $carac[$j]['contenido'];
            }
        }
        if($carac[$j]['id_carac'] == 161) {
            if(is_null($carac[$j]['contenido'])) {
                $valor = "Sin definir";
            }else {
                $valor = $carac[$j]['contenido'];
            }
        }
    }else {
        if($carac[$j]['id_carac'] == 166) {
            if(is_null($carac[$j]['contenido'])) {
                $moneda = "Sin definir";
            }else {
                $moneda = $carac[$j]['contenido'];
            }
        }
        if($carac[$j]['id_carac'] == 164) {
            if(is_null($carac[$j]['contenido'])) {
                $valor = "Sin definir";
            }else {
                $valor = $carac[$j]['contenido'];
            }
        }
    }
    if($carac[$j]['id_carac'] == 198) {
        if($carac[$j]['contenido'] == "") {
            $superficie = "-";
        }else {
            $superficie = $carac[$j]['contenido'];
        }
    }
    if($carac[$j]['id_carac'] == 208) {
        if(is_null($carac[$j]['contenido'])) {
            $ambientes = "-";
        }else {
            $ambientes = $carac[$j]['contenido'];
        }
    }
    if($carac[$j]['id_carac'] == 255) {
        if($carac[$j]['contenido'] == "") {
            $desc = "";
        }else {
            $desc = $carac[$j]['contenido'];
        }
    }
    if($carac[$j]['id_carac'] == 257) {
        if($carac[$j]['contenido'] == "") {
            $titulo = "-";
        }else {
            $titulo = $carac[$j]['contenido'];
        }
    }
}
for($k=0;$k < count($tipo_prop);$k++) {
    if($tipo_prop[$k]['id_tipo_prop'] == $prop['id_tipo_prop']) {
        $tipo = $tipo_prop[$k]['tipo_prop'];
        break;
    }
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>O'keefe Propiedades</title>
    <link href="css/okeefeVentanas.css" rel="stylesheet" type="text/css" />
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATOByPfalmn8LL-xW__zMG7CbdW5olvOI"type="text/javascript"></script> -->
    <!-- <script src="http://maps.google.com/maps?file=api&v=2&key=AIzaSyDwRS0eQiLLCJyhoNAKyAD7oKmOJVA7KLw" type="text/javascript"></script> -->
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvMBtQ50gLwwl2b_LOsVVWRDmnv5bj_kY&callback=initMap">
    </script>
    <script type="text/javascript">

    var lat = <?php echo $prop['goglat']?>;
    var lon = <?php echo $prop['goglong']?>;


    var map;

    function initMap() {
      var uluru = {lat: lat, lng: lon};
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 16,
        center: uluru
    });

      var contentString = '\
      <table width="100%" border="0" cellspacing="1" cellpadding="0" style="background-color:#FFF;background-position: 30px 0px; background-repeat:repeat-y; height:121px; padding-left: 10px; padding-right: 10px;">\
      <tr>\
      <td class="blanco_resumen" style="font-weight:bold; color:#ccdd00;"><?php echo $prop['operacion']; ?></td>\
      <td class="blanco_resumen" align="right">ID <?php echo str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;?></td>\
      </tr>\
      <tr>\
      <td class="blanco_resumen" colspan="2"><?php echo $tipo; ?></td>\
      </tr>\
      <tr>\
      <td class="blanco_resumen" colspan="2">Superficie: <?php echo $superficie; ?> m2</td>\
      </tr>\
      </table>';

      var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

      var marker = new google.maps.Marker({
        position: uluru,
        map: map
    });
      marker.addListener('click', function() {
        infowindow.open(map, marker);
    });
  }
  </script>
</head>
<body>
    <!-- <body onLoad="initialize()"> -->
    <div id="map" style="width: 600px; height: 400px;"></div>
</body>
</html>
