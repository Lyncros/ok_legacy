<?php
session_start();
//date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once('lib-nusoap/nusoap.php');

if(isset($_GET['codigo'])) {
    $id_prop = intval($_GET['codigo']);
    $retorno = "javascript: history.back();";
}else {
	if(isset($_GET['iddest']) && $_GET['iddest'] != ''){
    	$id_prop = $_GET['iddest'];
    	$retorno = "javascript: history.back();";
	}else {
		$id_prop = $_GET['id'];
		if( $_GET['filtro'] == ""){
			$retorno = "javascript: history.back();";
		}else{
			$div = $_GET['div'];
			$filtro = $_GET['filtro'];
			$textoFiltro = $_GET['textoFiltro'];
			$dest = $_GET['dest'];
			$retorno = "busqueda.php?filtro=" . $filtro . "&textoFiltro=" . $textoFiltro . "&div=" . $div . "&dest=" . $dest;
		}
	}
}

if (isset($_SESSION['reciente'])){
	$key=array_search($id_prop,$_SESSION['reciente']);
    if($key == false) $_SESSION['reciente'][$id_prop] = $id_prop;
}else{
	$_SESSION['reciente'] = array();
	$_SESSION['reciente'][$id_prop] = $id_prop;
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

//$zona = $client->call('ListarZonaPrincipal',array(),'');
////print_r($zona);
//
////$param=array('id_padre' => $ubica);
//$param=array('id_padre' => 0);
//$loca = $client->call('ListarZonasDependientes',$param,'');
////print_r($loca);

$tipoProp = $client->call('ListarTipoProp',array(),'');
//print_r($tipoProp);

$param=array('id_prop'=>$id_prop);
$prop = $client->call('Propiedad',$param,'');
//print_r($prop);
if($prop['activa'] != 1){
//	header("location: index.php");
	echo "<script>window.location='index.php';</script>";
}

$paramU=array('id_ubica' => $prop['id_ubica'],
				'modo' => 'l');
$loca = $client->call('detallaNombreZona',$paramU,'');

$param=array('id_prop'=>$id_prop);
$carac = $client->call('ListarDatosPropiedad',$param,'');
//print_r($carac);
//die();

$param=array('id_prop'=>$id_prop);
$fotosProp = $client->call('ListarFotosPropiedad',$param,'');
//print_r($fotos);

foreach($tipoProp as $tipo){
	if($tipo['id_tipo_prop'] == $prop['id_tipo_prop']){
		$txtTipo = $tipo['tipo_prop'];
		break;
	}
}


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
$suc=14;
$descripcion = "Inmobiliaria Rural y Urbana O’Keefe - ". $txtFiltro . " - ". $prop['operacion'] . " - " . $txtTipo . " - " . $loca ;
$titulo = "Inmobiliaria Rural y Urbana O’Keefe - ".$txtFiltro . " - ". $prop['operacion'] . " - " . $txtTipo . " - " . $loca ;

?>
<?php include_once('cabezalBuscador.php'); ?>
  <script src="js/classic/galleria-1.2.8.min.js"></script>
  <link rel="stylesheet" type="text/css" href="js/classic/galleria.classic.css"/>
  <form name="detalle" id="detalle" action="detalle_prop.php" method="get">
    <input type="hidden" name="filtro" id="filtro" value="<?php echo $filtro; ?>" />
    <input type="hidden" name="div" id="div" value="<?php echo $div; ?>" />
    <input type="hidden" name="textoFiltro" id="textoFiltro" value="" />
    <input type="hidden" name="dest" id="dest" value="" />
    <input type="hidden" name="id" id="id" value="" />
  </form>
  <div id="detalle">
    <div id="cabezaResul">
      <div id="zonaResul"><?php echo $prop['operacion'] . " - " . $txtTipo . " - " . $loca;?></div>
      <div id="codigoResul">ID <?php echo str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;?></div>
      <div class="favoritos" style="float:right; margin: 0px 10px; width:110px; background-position: 25px 1px; padding-left:20px;"><a href="javascript: Set_Cookie('id[<?php echo $id_prop; ?>]',<?php echo $id_prop; ?>);">Favoritos</a></div>
      <div class="clearfix"></div>
    </div>
    <div id="contenidoResul">
      <div id="fotosDetalle">
        <?php for($i=0; $i < count($fotosProp); $i++){ ?>
        <a href="http://abm.okeefe.com.ar/fotos/<?php echo $fotosProp[$i]['foto']; ?>"><img src="http://abm.okeefe.com.ar/fotos_th/<?php echo $fotosProp[$i]['foto']; ?>" /></a>
        <?php } ?>
      </div>
      <script>
		Galleria.configure({
			imageCrop: true,
			transition: 'fade',
			showImagenav: true,
			pauseOnInteraction: true,
			autoplay: 7000 // will move forward every 7 seconds
		});
		Galleria.loadTheme('js/classic/galleria.classic.min.js');
		Galleria.run('#fotosDetalle');
        </script>
      <div id="col2Detalle">
        <div id="txtcol2"> <span class="tituDetalle"><?php echo busca_valor(257, $carac); ?></span><br />
          <span class="tituCarac">Superficie:</span> <span class="caracResul">
          <?php
               echo busca_valor(198, $carac);
               if($prop['id_tipo_prop'] == 6 || $prop['id_tipo_prop'] == 16){
                   echo "Ha.";
               }else{
                   echo "m2";
               }
               ?>
          </span><br />
          <?php
		  switch ($prop['id_tipo_prop']){
			case 6;
			case 7;
			case 16:
			  	$buscar=303;
				$tituBuscar="Aptitud";
				break;
			default:
			  	$buscar=208;
				$tituBuscar="Ambientes";
				break;
		  }
		$moneda = $precio = "";
        if($prop[$i]['publicaprecio'] == 0){
            $moneda = "";
            $precio = "Consulte";
        }else{
            if($prop[$i]['operacion'] == "Venta") {
                if(is_null($prop[$i]['monven']) || $prop[$i]['monven'] == "Sin definir") {
                    $moneda = "";
                }else {
                    $moneda = $prop[$i]['monven'];
                }
                if(is_null($prop[$i]['valven']) || $prop[$i]['valven'] == 0) {
                    $precio = "Consulte";
                }else {
                    $precio = number_format($prop[$i]['valven'],0,",",".");
                }
            }else{
                if(is_null($prop[$i]['monalq']) || $prop[$i]['monalq'] == "Sin definir") {
                    $moneda = "";
                }else {
                    $moneda = $prop[$i]['monalq'];
                }
                if(is_null($prop[$i]['valalq']) || $prop[$i]['valalq'] == 0) {
                    $precio = "Consulte";
                }else {
                    $precio = number_format($prop[$i]['valalq'],0,",",".");;
                }
            }
        }

		  ?>
          <span class="tituCarac"><?php echo $tituBuscar;?>:</span> <span class="caracResul"><?php echo busca_valor($buscar, $carac);?></span> <br />
          <span class="tituCarac">Categoría:</span> <span class="caracResul"><?php echo $txtTipo;?></span> <br />
          <span class="tituCarac">Estado:</span> <span class="caracResul"><?php echo $prop['operacion'];?></span><br />
          <span class="tituCarac">Precio:</span> <span class="caracResul"><?php echo $moneda . $precio;?></span><br />
          <?php
		  $fpago = busca_valor(172, $carac);
		  if($fpago != ""){
			  ?>
          <span class="tituCarac">Forma de pago:</span> <span class="caracResul"><?php echo substr($fpago, 0, 250);?></span>
          <?php
		  } 
		  if($prop['oportunidad'] == 1){?>
          <p align="center"><img src="images/oportunidad.gif" width="75" height="50" alt="Oportunidad" /></p>
          <?php } ?>
        </div>
        <div id="botonesDetalle">
          <div id="botonDetalles">
            <div id="icoDetalles"><img src="images/verVideo.gif" width="21" height="23" alt="Ver video" /></div>
            <div id="txticoDetalles">Ver video</div>
          </div>
          <div id="botonDetalles"><!--<a href="ficha_prop_pdf.php?id=<?php echo $id_prop;?>&dest=D">-->
            <div id="icoDetalles"><img src="images/bajarFicha.gif" width="21" height="23" alt="Ver video" /></div>
            <div id="txticoDetalles">Bajar ficha</div>
          </div>
          <div id="botonDetalles"><!--<a href="ficha_prop_pdf.php?id=<?php echo $id_prop;?>&dest=I">-->
            <div id="icoDetalles"><img src="images/imprimirFicha.gif" width="21" height="23" alt="Ver video" /></div>
            <div id="txticoDetalles">Imprimir ficha</div>
          </div>
          <div id="botonDetalles">
            <?php if($prop['plano1'] != ""){?>
            <a href="http://abm.okeefe.com.ar/fotos/<?php echo $prop['plano1'];?>" class="thickbox">
            <div id="icoDetalles"><img src="images/verPlano.gif" width="21" height="23" alt="Ver video" /></div>
            <div id="txticoDetalles">Ver plano</div>
            </a>
            <?php }else{ ?>
            <div id="icoDetalles"><img src="images/verPlano.gif" width="21" height="23" alt="Ver video" /></div>
            <div id="txticoDetalles">Ver plano</div>
            <?php } ?>
          </div>
          <div id="botonDetalles">
            <?php if($prop['goglat'] != 0){?>
            <a href="mapa_google.php?id=<?php echo $id_prop;?>&TB_iframe=true&height=405&width=605&modal=false" class="thickbox">
            <div id="icoDetalles"><img src="images/verUbicacion.gif" alt="Ver video" width="21" height="23" border="0" /></div>
            <div id="txticoDetalles">Ver ubicación</div>
            </a>
            <?php }else{ ?>
            <div id="icoDetalles"><img src="images/verUbicacion.gif" alt="Ver video" width="21" height="23" border="0" /></div>
            <div id="txticoDetalles">Ver ubicación</div>
            <?php } ?>
          </div>
          <div id="botonDetalles"><a href="form_recomendar.php?id=<?php echo str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;?>&TB_iframe=true&height=250&width=300&modal=false" class="thickbox">
            <div id="icoDetalles"><img src="images/enviarDetalle.gif" width="21" height="23" alt="Ver video" border="0" /></div>
            <div id="txticoDetalles">Enviar a</div>
            </a> </div>
          <div id="botonDetalles"><a href="<?php echo $retorno;?>">
            <div id="icoDetalles"><img src="images/volverDetalle.gif" alt="Ver video" width="21" height="23" border="0" /></div>
            <div id="txticoDetalles">Volver</div>
            </a> </div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="clearfix"></div>
    </div>
    <div id="col1Detalle">
      <div id="detalleIzq">
        <div id="txtDescrip"> <span class="tituDetalle">Descripción:</span><br />
          <span>
          <?php
                $desc = busca_valor(255, $carac);
                $MaxLENGTH = 4000;
                if (strlen($desc) > $MaxLENGTH) {
                    $desc = substr(strip_tags($desc), 0, strrpos(substr(strip_tags($desc), 0, $MaxLENGTH), " "));
                    $desc .= '...';
                }
                echo nl2br($desc);
                ?>
          </span> </div>
        <br />
        <?php
		  switch ($prop['id_tipo_prop']){
			case 6;
			case 16:
			  	$buscar="Mejoras de trabajo";
				$tituBuscar="Mejoras de trabajo";
				break;
			default:
			  	$buscar="Características del inmueble";
				$tituBuscar="Detalles de la Propiedad";
				break;
		  }
		  ?>
        <?php
$tituloTipoCarac = "";
				
foreach ( $carac as $elemCarac ) {
	//print_r($elemCarac);
	if($elemCarac ['contenido'] == 'on'){
		$contenido = "<img src=\"images/tilde.png\" border=\"0\" />";
	}else{
		$contenido = $elemCarac ['contenido'];
	}
	if ($elemCarac ['id_tipo'] != 26 && $elemCarac ['id_tipo'] != 29 && $elemCarac ['orden_tipo'] <= 6) {
		if ($tituloTipoCarac != $elemCarac ['tipo_carac']) {
			$tituloTipoCarac = $elemCarac ['tipo_carac'];
			echo "<div id=\"tituloCarac\">".$elemCarac ['tipo_carac']."</div>\n";
			echo "<div id=\"textoCarac\">\n";
			echo "	<div id=\"carac\">".$elemCarac ['titulo']."</div>\n";
			echo "	<div id=\"caracCont\">".$contenido."</div>\n";
			echo "	<div class=\"clearfix\"></div>\n";
			echo "</div>\n";
		} else {
			//if ($elemCarac ['publica'] == 1) {
				echo "<div id=\"textoCarac\">\n";
				echo "	<div id=\"carac\">".$elemCarac ['titulo']."</div>\n";
				echo "	<div id=\"caracCont\">".$contenido."</div>\n";
				echo "	<div class=\"clearfix\"></div>\n";
				echo "</div>\n";
			//}
		}
	}
}

				
				
/*				
			                          Array
(
    [id_prop_carac] => 3
    [orden_tipo] => 3
    [orden] => 97
    [id_prop] => 519
    [id_carac] => 198
    [tipo_carac] => Ambientes y Medidas
    [titulo] => Superficie Total
    [contenido] => 8
    [comentario] => 
)	*/
                                                                            ?>
      </div>
      <div id="detalleDer">
              <?php
		  switch ($prop['id_tipo_prop']){
			case 6;
			case 16:
			  	$buscar="Mejoras de trabajo";
				$tituBuscar="Mejoras de trabajo";
				break;
			default:
			  	$buscar="Características del inmueble";
				$tituBuscar="Detalles de la Propiedad";
				break;
		  }
		  ?>
        <?php
$tituloTipoCarac = "";
				
foreach ( $carac as $elemCarac ) {
	//print_r($elemCarac);
	if($elemCarac ['contenido'] == 'on'){
		$contenido = "<img src=\"images/tilde.png\" border=\"0\" />";
	}else{
		$contenido = $elemCarac ['contenido'];
	}
	if ($elemCarac ['id_tipo'] != 26 && $elemCarac ['id_tipo'] != 29 && $elemCarac ['orden_tipo'] > 6) {
		if ($tituloTipoCarac != $elemCarac ['tipo_carac']) {
			$tituloTipoCarac = $elemCarac ['tipo_carac'];
			echo "<div id=\"tituloCarac\">".$elemCarac ['tipo_carac']."</div>\n";
			echo "<div id=\"textoCarac\">\n";
			echo "	<div id=\"carac\">".$elemCarac ['titulo']."</div>\n";
			echo "	<div id=\"caracCont\">".$contenido."</div>\n";
			echo "	<div class=\"clearfix\"></div>\n";
			echo "</div>\n";
		} else {
			//if ($elemCarac ['publica'] == 1) {
				echo "<div id=\"textoCarac\">\n";
				echo "	<div id=\"carac\">".$elemCarac ['titulo']."</div>\n";
				echo "	<div id=\"caracCont\">".$contenido."</div>\n";
				echo "	<div class=\"clearfix\"></div>\n";
				echo "</div>\n";
			//}
		}
	}
}

				
				
/*				
			                          Array
(
    [id_prop_carac] => 3
    [orden_tipo] => 3
    [orden] => 97
    [id_prop] => 519
    [id_carac] => 198
    [tipo_carac] => Ambientes y Medidas
    [titulo] => Superficie Total
    [contenido] => 8
    [comentario] => 
)	*/
                                                                            ?>

      </div>
      <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
  </div>
  <?php include_once('pie.php'); ?>