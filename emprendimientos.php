<?php
session_start ();
/*
include ("generic_class/class.cargaConfiguracion.php");

$conf = new CargaConfiguracion ();
*/
//$db_tipo=$conf->leeParametro("tipodb");
$db_host = "localhost";
//$db_port=$conf->leeParametro("port");
$db_name = "okeefe_abm";
$db_user = "abmAdmin";
$db_pass = "oke10abm";

// Esstablish connect to MySQL database
$con = mysql_connect ( $db_host, $db_user, $db_pass );
if (! $con)
	die ( 'Could not connect: ' . mysql_error () );

require_once ('lib-nusoap/nusoap.php');

$wsdl = "http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client = new nusoap_client ( $wsdl, 'wsdl' ); //instanciando un nuevo objeto cliente para consumir el webservice  


//¿ocurrio error al llamar al web service? 
if ($client->fault) { // si
	echo 'No se pudo completar la operación';
	die ();
} else { // no
	$error = $client->getError ();
	if ($error) { // Hubo algun error
		echo 'Error:' . $error;
	}
}

if(isset($_POST['pag'])){
    $pag = $_POST['pag'];
}else{
    $pag = 1;
}

$id_tipo = 0;
if(isset($_GET['t'])){
    $id_tipo = $_GET['t'];
}else{
	if(isset($_POST['t'])){
		$id_tipo = $_POST['t'];
	}
}

$estado = '';
$titu_estado='';
if(isset($_GET['e'])){
    $estado = $_GET['e'];
	$titu_estado=' en obra';
}else{
	if(isset($_POST['e'])){
		$estado = $_POST['e'];
		$titu_estado=' en obra';
	}
}


$param = array ('id_ubica' => 0, 'id_tipo_emp' => $id_tipo, 'estado'=>$estado, 'pagina' => $pag, 'activas' => 1 );
$empre = $client->call ( 'ListarEmprendimientos', $param, '' );
//print_r ( $empre );



$tipo_empre = $client->call ( 'ListarTipoEmp', '', '' );
//print_r($tipo_empre);

mysql_select_db ( $db_name, $con );
if($estado != ''){
	$query_cant = sprintf("SELECT count(*) FROM emprendimiento WHERE estado='%s' AND id_tipo_emp IN (%s) AND activa=1", $estado, $id_tipo);
}else{
	$query_cant = sprintf("SELECT count(*) FROM emprendimiento WHERE id_tipo_emp IN (%s) AND activa=1", $id_tipo);
}
$cant = mysql_query ( $query_cant, $con ) or die ( mysql_error () );
$row_cant = mysql_fetch_assoc ( $cant );
//$totalRows_destacados = mysql_num_rows($destacados);
$cantEmp = $row_cant ['count(*)']+1;
$suc = 4;

switch ($id_tipo){
	case '16,20':
		$titu_tipo = 'Countries &amp; Barrios';
		break;
	case 14:
		$titu_tipo = 'Condominios';
		break;
	case 18:
		$titu_tipo = 'Edificios';
		break;
	case 17:
		$titu_tipo = 'Loteos';
		break;
	case 22:
		$titu_tipo = 'Real Estate';
		break;
}

?>
<?php include_once('cabezalBuscador.php');?>
<div style="border-bottom:thin solid #CCC; padding-bottom:3px;">
  <div id="placaBusqueda">Emprendimientos - <?php echo $titu_tipo . $titu_estado; ?></div>
</div>
<?php
if(count($empre) > 0){
	for($i = 0; $i < count ( $empre ); $i ++) {
		$id_empre = $empre [$i] ['id_emp'];
		
		$param = array ('id_ubica'=>$empre[$i]['id_ubica'], 'modo'=>'i');
		$zona = $client->call ('detallaNombreZona', $param, '' );
	
	
			
		foreach ( $tipo_empre as $assoc_array ) {
			if ($assoc_array ['id_tipo_emp'] == $empre [$i] ['id_tipo_emp']) {
				$tipo = $assoc_array ['tipo_emp'];
				break;
			}
		}
		
		if ($empre [$i] ['foto'] != "") {
			$foto = "http://abm.okeefe.com.ar/fotos_th/" . $empre [$i] ['foto'];
		}else{
			$foto = "images/noDisponible.gif";
		}
		
		if ($empre[$i] ['logo'] != "") {
		//if (file_exists ("http://abm.achavalcornejo.com/fotos/" . $empre[$i] ['logo'])) {
			$logo = "http://abm.okeefe.com.ar/fotos/" . $empre[$i] ['logo'];
			$dimLogo = getimagesize("http://abm.okeefe.com.ar/fotos/" . $empre[$i] ['logo']);
			if(($dimLogo[0]/$dimLogo[1]) > 1.5){
				$attLogo = "width=\"180\"";
			}else{
				$attLogo = "height=\"120\"";
			}
		}
			?>
	  <div id="resultado" border="0">
		<div id="fotoResul"><a href="detalleEmprendimiento.php?i=<?php echo $empre [$i] ['id_emp']; ?>"><img src="<?php echo $foto; ?>" border="0"  style="max-height:107px;max-width:160px;" /></a>
			  </div>
		<div id="contenidoResul" style="float:right; width:830px; height:107px; margin-left:5px;" onclick="detalleEmprendimiento.php?i=<?php echo $empre [$i] ['id_emp']; ?>">
		  <div id="cabezaResul">
			<div id="zonaResul"><?php echo $empre [$i] ['nombre']; ?></div>
			<div id="codigoResul">ID <?php echo str_repeat("0", 5-strlen(strval($id_empre))) . $id_empre;?></div>
		  </div>
		  <div id="cuerpoResul" style="width:830px; clear:both;">
			<div id="datos1Resul"><span class="tituResult">Ubicación</span> <span class="caracResul"><?php echo $zona; ?></span><br />
			  <span class="tituResult">Tipo: </span><span class="caracResul"><?php echo $tipo; ?></span><br />
			  <!--<span class="caracResul"><img src="<?php echo $logo; ?>" <?php echo $attLogo; ?> /></span>-->
			</div>
			<div id="descripResul" onclick="detalleEmprendimiento.php?i=<?php echo $empre [$i] ['id_emp']; ?>">
			<div style="height:71px; max-height:71px; overflow:hidden;"><span class="tituResult">Descripción:</span><br />
			  <span><?php echo substr($empre [$i] ['descripcion'],0, 200); ?></span>
				</div>
				<div><a href="detalleEmprendimiento.php?i=<?php echo $empre [$i] ['id_emp']; ?>">
			  <div id="vermasResul">Más detalles</div></a>
			  </div>
			  
			</div>
			<div id="datos2Resul">
				<div style="margin-bottom:4px; padding:1px 0px;">&nbsp;</div>
				<div class="consultar" style="margin-bottom:4px;"><a href="form_recomendar.php?idempre=<?php echo $empre [$i] ['id_emp']; ?>&TB_iframe=true&height=250&width=300&modal=false" class="thickbox">&gt; Recomendar</a></div>
				<div class="consultar"><a href="form_consulta_prop.php?t=empre&id=<?php echo $empre [$i] ['id_emp']; ?>&TB_iframe=true&height=320&width=800&modal=false" class="thickbox">&gt; Consultar</a></div>
			</div>
		  </div>
		</div>
		<div class="clearfix"></div>
	  </div>
	<?php 
		}
	}else{
			  echo "<div id=\"sinResultado\">No existe informaci&oacute;n para la b&uacute;queda realizada</div>\n";
	}
	 ?>
  <div id="paginado">
<?php
						$paginas = ceil ( $cantEmp / 20 );
						if ($paginas > 0) {
							?>
                    <form action="<?php echo $_SERVER ['PHP_SELF']?>" name="paginacion" method="post">
                      <input type="hidden" id="pag" name="pag" value="<?php echo $pag; ?>">
                      <input type="hidden" id="t" name="t" value="<?php echo $id_tipo; ?>">
                      <input type="hidden" id="e" name="e" value="<?php echo $estado; ?>">
                    </form>
                    <?php
							if ($paginas > 1) {
								if ($pag == 1) {
									echo "< previa | ";
								} else {
									echo "<a href=\"javascript: document.getElementById('pag').value=". intval($pag - 1) ."; document.forms['paginacion'].submit();\" class=\"desc_resumen\">< previa</a> | ";
								}
							}
							for($k = 1; $k <= $paginas; $k ++) {
								if ($k == $pag) {
									echo $k . " | ";
								} else {
									echo "<a href=\"javascript:  document.getElementById('pag').value=". $k ."; document.forms['paginacion'].submit();\" class=\"desc_resumen\">" . $k . "</a> | ";
								}
							}
							if ($paginas > 1) {
								if ($pag == $paginas) {
									echo "siguiente >";
								} else {
									echo "<a href=\"javascript:document.getElementById('pag').value=". intval($pag + 1) ."; document.forms['paginacion'].submit();\" class=\"desc_resumen\">siguiente ></a>";
								}
							}
						}
						?></div>
  <div id="placaNaranja" style="clear:both;"><a href="form_consulta_buscador.php?TB_iframe=true&height=360&width=800&modal=false" class="thickbox"><img src="images/sinoencuentra.gif" width="702" height="18" border="0" /></a></div>
  <?php
include_once ("pie.php");
?>
