<?php
session_start();
ob_start();
header('Content-type: text/html; charset=utf-8');
require_once('lib-nusoap/nusoap.php');


function busca_valor($id_prop, $id_carac, $arreglo) {
    for ($j = 0; $j < count($arreglo); $j++) {
        if ($arreglo[$j]['id_prop'] == $id_prop && $arreglo[$j]['id_carac'] == $id_carac) {
            if (isset ($arreglo[$j]['contenido']) && $arreglo[$j]['contenido'] != "") {
                $valor = $arreglo[$j]['contenido'];
            } else {
                $valor = "-";
            }
            return $valor;
            break;
        }
    }
}

/**
 * Reemplaza todos los acentos por sus equivalentes sin ellos
 *
 * @param $string
 *  string la cadena a sanear
 *
 * @return $string
 *  string saneada
 */
function sanear_string($s){

    $string = trim($s);
    //Esta parte se encarga de eliminar espacios dobles
    $s = str_replace(
        array("  "),
        ' ',
        $s
    );

//	$s = mb_convert_encoding($s, 'UTF-8','');
	$s = preg_replace("/á|à|â|ã|ª/","a",$s);
	$s = preg_replace("/Á|À|Â|Ã/","A",$s);
	$s = preg_replace("/é|è|ê/","e",$s);
	$s = preg_replace("/É|È|Ê/","E",$s);
	$s = preg_replace("/í|ì|î/","i",$s);
	$s = preg_replace("/Í|Ì|Î/","I",$s);
	$s = preg_replace("/ó|ò|ô|õ|º/","o",$s);
	$s = preg_replace("/Ó|Ò|Ô|Õ/","O",$s);
	$s = preg_replace("/ú|ù|û/","u",$s);
	$s = preg_replace("/Ú|Ù|Û/","U",$s);
	$s = str_replace(" ","-",$s);
	$s = str_replace("///","-",$s);
	$s = str_replace("ñ","n",$s);
	$s = str_replace("Ñ","N",$s);

	$s = preg_replace('/[^a-zA-Z0-9-]/', '', $s);
	//return $s;
    $s = str_replace(
        array("--"),
        '',
        $s
    );

    return $s;
}

//$wsdl="http://localhost/okeefe/webservice/servicioweb.php?wsdl";
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

if(isset($_POST['pag'])) {
    $pag = $_POST['pag'];
}else {
    $pag = 1;
}

$zona = $client->call('ListarZonaPrincipal',array(),'');
//print_r($zona);
//echo "ubica: " .$ubica . "<br /><br />";
//die();

//$param=array('id_padre' => $ubica);
//$param=array('id_padre' => 0);
//$loca = $client->call('ListarZonasDependientes',$param,'');
//print_r($loca);

$tipoProp = $client->call('ListarTipoProp',array(),'');
//print_r($tipoProp);
//die();

$paramP = array('operacion'=>"'Venta', 'Alquiler'");
$prop = $client->call('listarNovedades', $paramP, '');

$lista_id_prop="";
for($i=0; $i < count($prop); $i++) {
	$lista_id_prop .= $prop[$i]['id_prop'] . ",";
}
$lista_id_prop = substr($lista_id_prop, 0, -1);
$lista_id_carac = "198,255,257,303";
//         $lista_id_carac = "42,255,257";
$param=array('inprop'=>$lista_id_prop,'incarac'=>$lista_id_carac);
$carac = $client->call('DatosConjuntoPropiedades',$param,'');

//print_r($prop);
//print_r($carac);

$suc=11;
//print_r($prop);
?>
<?php include_once('cabezalBuscador.php'); ?>
  <div style="border-bottom:thin solid #CCC; padding-bottom:3px;">
  <div id="placaBusqueda" style="clear:both;"><h1>Novedades</h1></div>
  </div>
  <?php
    for($i=0; $i < count($prop); $i++) {
        $id_prop = $prop[$i]['id_prop'];
        // BUSCA FOTOS ----------
        $param=array('id_prop'=>$id_prop);
        $fotos = $client->call('ListarFotosPropiedad',$param,'');
        //print_r($fotos);
        //-----------------------
		$paramU=array('id_ubica' => $prop[$i]['id_ubica'],
						'modo' => 'l');
		$loca = $client->call('detallaNombreZona',$paramU,'');

/*		foreach($loca as $id){
			if($id['id_ubica'] == $prop[$i]['id_ubica']){
				$txtLoca=$id['nombre_ubicacion'];
				foreach($loca as $padre){
					if($padre['id_ubica'] == $id['id_padre']){
						$txtLoca = $padre['nombre_ubicacion'] . ' - '. $txtLoca;
						$id_padre = $padre['id_padre'];
						break;
					}
				}
			}
		}
        foreach($zona as $id){
			if($id['id_ubica'] == $id_padre){
				$txtZona = $id['nombre_ubicacion'];
				break;
			}
		}
*/        
        if(is_null($prop[$i]['cantamb'])) {
            $ambientes = "-";
        }else {
            $ambientes = intval($prop[$i]['cantamb']);
        }
        if($prop[$i]['suptot'] == "") {
            $superficie = "-";
        }else {
            $superficie = intval($prop[$i]['suptot']);
        }
        if($prop[$i]['publicaprecio'] == 0){
            $moneda = "";
            $valor = "Consulte";
        }else{
            if($prop[$i]['operacion'] == "Venta") {
                if(is_null($prop[$i]['monven']) || $prop[$i]['monven'] == "Sin definir") {
                    $moneda = "";
                }else {
                    $moneda = $prop[$i]['monven'];
                }
                if(is_null($prop[$i]['valven']) || $prop[$i]['valven'] == 0) {
                    $valor = "Consulte";
                }else {
                    $valor = number_format($prop[$i]['valven'],0,",",".");
                }
            }else{
                if(is_null($prop[$i]['monalq']) || $prop[$i]['monalq'] == "Sin definir") {
                    $moneda = "";
                }else {
                    $moneda = $prop[$i]['monalq'];
                }
                if(is_null($prop[$i]['valalq']) || $prop[$i]['valalq'] == 0) {
                    $valor = "Consulte";
                }else {
                    $valor = number_format($prop[$i]['valalq'],0,",",".");;
                }
            }
        }
		foreach($tipoProp as $tipo){
			if($tipo['id_tipo_prop'] == $prop[$i]['id_tipo_prop']){
				$txtTipo = $tipo['tipo_prop'];
				break;
			}
		}
  		include("inc/resultado.php");

   }
   
				$paginas = ceil($cant / 20);
				if($paginas > 0) {
					?>
  <div id="paginado">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="paginacion" method="post">
      <input type="hidden" name="filtro" value="<?php echo $txtFiltro;?>" />
      <input type="hidden" name="pag" id="pag" value="<?php echo $pag;?>">
      <?php if(isset($_POST['avanzada'])) { ?>
      <input type="hidden" name="avanzada" value="1">
      <input type="hidden" name="consulta" value="<?php echo urlencode($where);?>">
      <?php }?>
    </form>
    <?php
					if($paginas > 1) {
						if($pag == 1) {
							echo  "< previa | ";
						}else {
							echo  "<a href=\"javascript: document.getElementById('pag').value=". intval($pag - 1) ."; document.form('paginacion').submit();\" class=\"pagina\">< previa</a> | ";
						}
					}
					for($k=1; $k <= $paginas; $k++) {
						if($k == $pag) {
							echo  "<span style='color:#f26322;'>".$k . "</span> | ";
						}else {
							echo  "<a href=\"javascript:  document.getElementById('pag').value=". $k ."; document.forms['paginacion'].submit();\" class=\"pagina\">" . $k . "</a> | ";
						}
					}
					if($paginas > 1) {
						if($pag == $paginas) {
							echo  "siguiente >";
						}else {
							echo  "<a href=\"javascript: cargaCuadroDatos('$div',$filtro,". intval($pag + 1) .");\" class=\"pagina\">siguiente ></a>";
						}
					}
				}
				?>
  </div>
  <div id="placaNaranja" style="clear:both;"><a href="form_consulta_buscador.php?TB_iframe=true&height=360&width=800&modal=false" class="thickbox"><img src="images/sinoencuentra.gif" width="702" height="18" border="0" /></a></div>
  <?php 
  include_once('pie.php');
  ob_flush();
   ?>