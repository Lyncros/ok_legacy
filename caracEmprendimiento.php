<?php
session_start();
require_once('lib-nusoap/nusoap.php');

$id_empre = $_GET['i'];
$id_carac = $_GET['car'];

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
/*
//$param=array('id_zona'=>30);
$param=array('id_zona' => 30);
$loca = $client->call('ListarLocalidad',$param,'');
//print_r($loca);
*/
$param=array('id_emp'=>$id_empre);
$empre_sel = $client->call('Emprendimiento',$param,'');
//print_r($empre_sel);
//die();

$param=array('id_emp'=>$id_empre);
$carac = $client->call('ListarDatosEmprendimiento',$param,'');
//print_r($carac);
//die();


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

if(isset($_GET['car'])) {
	for($i=0; $i < count($carac); $i++) {
		if($carac[$i]['id_carac'] == $_GET['car']) {
			$contenido =  str_replace('/imgEmprendimientos/', 'http://abm.okeefe.com.ar/imgEmprendimientos/', $carac[$i]['contenido']);
			$titulo = $carac[$i]['titulo'];
	/*		if($_GET['car'] == 75) {
				?>
				<script type="text/javascript" language="javascript">
                var flashvars = {
                    id_empre: <?php echo $id_empre;?>
                    };
                var params = {
                  quality: "high",
                  align: "top",
                  play: "true",
                  loop: "true",
                  scale: "showall",
                  wmode: "opaque",
                  devicefont: "false",
                  bgcolor: "#FFFFFF",
                  menu: "true",
                  allowFullScreen: "false",
                  allowScriptAccess:"sameDomain"
                };
                var attributes = {};
                 
                swfobject.embedSWF("images/galeria_ficha_empre.swf", "galeriaEmpre", "490", "400", "9.0.0","Scripts/expressInstall.swf", flashvars, params, attributes);
                </script>

<?php 		}*/
		}
	}
}
?>
<div id="caracEmpre">
	<div id="tituloCaracEmpre"><?php echo $titulo; ?></div>
	<div><?php echo $contenido; ?></div>
	<div id="galeriaEmpre"></div>
</div>