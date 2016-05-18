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

if(isset($_GET['pag'])) {
    $pag = $_GET['pag'];
}else {
    $pag = 1;
}

if(isset($_POST["opcUbica"])) {
	$ubica = trim($_POST["opcUbica"]);
}else {
	$ubica = 0;
}
/*
if(isset($_POST["opcLocalidad"])) {
	$barrios = trim($_POST["opcLocalidad"]);
}else {
	$barrios = 0;
}
*/
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

if(isset($_GET['opcEmprendimiento'])){
	$param=array('id_emp' => $_GET['opcEmprendimiento']);
	$empre = $client->call('Emprendimiento',$param,'');
	//print_r($empre);
	//die();
}
$suc=0;

if(isset($_GET['mb'])){
	if($_GET['mb'] == 1){
		$lista_id_prop='';
		if (isset($_COOKIE['id'])) {
	//		$h = 0;
	//		$cant = 0;
			foreach ($_COOKIE['id'] as $name => $value) {
				$lista_id_prop .= $value . ",";
				$id_cookie = $name;
			}
		}
		$txtFiltro = "Favoritos";
	}else{
		if (isset($_SESSION['reciente'])) {
	//		$h = 0;
	//		$cant = 0;
			foreach ($_SESSION['reciente'] as $name => $value) {
				$lista_id_prop .= $value . ",";
				$id_cookie = $name;
			}
		}			
		$txtFiltro = "Recientemente vistos";
	}
	
	$lista_id_prop = substr($lista_id_prop, 0, -1);
	if($lista_id_prop != ""){
		include 'Connections/config.php';
	
		mysql_select_db('okeefe_abm', $config);
		$query_mb=sprintf("SELECT p.id_prop,p.id_ubica,p.calle,p.nro,p.id_tipo_prop,p.subtipo_prop,p.intermediacion,p.id_inmo,p.operacion,p.piso,p.dpto,p.id_cliente,p.activa,p.id_sucursal,p.id_emp, p.compartir,p.goglat,p.goglong,z.nombre_ubicacion,t.tipo_prop,st.suptot,sca.cantamb,smal.monalq,sal.valalq,smve.monven,sve.valven
				  FROM propiedad as p
							 INNER JOIN okeefe_abm.ubicacionpropiedad as z ON p.id_ubica = z.id_ubica INNER JOIN okeefe_abm.tipoprop as t ON p.id_tipo_prop = t.id_tipo_prop  
				  LEFT JOIN 
							(SELECT id_prop,contenido as suptot FROM okeefe_abm.propiedad_caracteristicas WHERE id_carac=198) as st ON p.id_prop=st.id_prop 
							 LEFT JOIN 
							(SELECT id_prop,contenido as cantamb FROM okeefe_abm.propiedad_caracteristicas WHERE id_carac=208) as sca ON p.id_prop=sca.id_prop
							LEFT JOIN
							(SELECT id_prop,contenido as monalq FROM okeefe_abm.propiedad_caracteristicas WHERE id_carac=166) as smal ON p.id_prop=smal.id_prop
							LEFT JOIN
							(SELECT id_prop,contenido as valalq FROM okeefe_abm.propiedad_caracteristicas WHERE id_carac=164) as sal ON p.id_prop=sal.id_prop
							LEFT JOIN
							(SELECT id_prop,contenido as monven FROM okeefe_abm.propiedad_caracteristicas WHERE id_carac=165) as smve ON p.id_prop=smve.id_prop
							LEFT JOIN
							(SELECT id_prop,contenido as valven FROM okeefe_abm.propiedad_caracteristicas WHERE id_carac=161) as sve ON p.id_prop=sve.id_prop 
					WHERE p.id_prop IN (%s)", $lista_id_prop);
					//echo $query_mb;
					//die();
			$mb = mysql_query($query_mb, $config) or die(mysql_error());
	
			$cant = 0;
			$prop = array();
			while($row_mb = mysql_fetch_array($mb)) {
				$prop[] = $row_mb;
				$cant++;
			}
	//    print_r($prop);
	//    die();
		$lista_id_carac = "255,257";
		$param=array('inprop'=>$lista_id_prop,'incarac'=>$lista_id_carac);
		$carac = $client->call('DatosConjuntoPropiedades',$param,'');
	}
//	print_r($carac);
}else{
	if($_GET['opcUbica'] || $_GET['opcTipoOper'] || $_GET['opcTipoProp'] || $_GET['opcAmbientes'] || $_GET['opcDespachos'] || $_GET['opcMonedaVenta'] || $_GET['opcPrecioVenta'] || $_GET['opcEmprendimiento'] || $_GET['opcSupTotal']){
	//print_r($_GET);
		$filtro=Array();
		$textoFiltro=Array();
		
		if($_GET['opcUbica'] != 0){
			array_push($filtro, array('opcUbica', $_GET['opcUbica']));
			for($i=0; $i < count($zona); $i++) {
				if($zona[$i]['id_ubica'] == $_GET['opcZona']){
	//				array_push($textoFiltro, array('Zona', $zona[$i]['nombre_ubicacion']));
					array_push($textoFiltro, array('', $zona[$i]['nombre_ubicacion']));
					break;
				}
			}
		}
		if($_GET['opcTipoOper'] != ""){
			array_push($filtro, array("opcTipoOper",$_GET['opcTipoOper']));
	//		array_push($textoFiltro, array('Operaci&oacute;n', $_GET['opcTipoOper']));
			array_push($textoFiltro, array('', $_GET['opcTipoOper']));
		}
		if($_GET['opcTipoProp'] != ''){
			$tipo = 0;
			switch($_GET['opcTipoProp']){
				case 'Departamentos y P.H.':
					$tipo = 1;
					$suc=2;
					break;
				case 'Campos':
					$tipo = 6;
					$suc=1;
					break;
				case 'Casas':
					$tipo = 9;
					$suc=2;
					break;
				case 16:
				case 'Chacras':
					$tipo = 16;
					$suc=1;
					break;
				case 'Cocheras':
					$tipo = 18;
					$suc=3;
					break;
				case 'Galpones':
					$tipo = 15;
					$suc=3;
					break;
				case 'Industrial':
					$tipo = 19;
					$suc=3;
					break;
				case 'Locales':
					$tipo = 2;
					$suc=3;
					break;
				case 'Terrenos';
				case 'Lotes':
					$tipo = 7;
					$suc=1;
					break;
				case 'Oficinas':
					$tipo = 11;
					$suc=3;
					break;
				case 'Quintas':
					$tipo = 17;
					$suc=2;
					break;
			}
		                                    
			array_push($filtro, array("opcTipoProp",$tipo));
			for($i=0; $i < count($tipoProp); $i++) {
				if($tipoProp[$i]['id_tipo_prop'] == $tipo){
	//				array_push($textoFiltro, array('Tipo', $tipoProp[$i]['tipo_prop']));
					array_push($textoFiltro, array('', $tipoProp[$i]['tipo_prop']));
					break;
				}
			}
			/*
			switch ($prop[$i]['id_tipo_prop']){
				case 6;
				case 7;
				case 16:
					$suc=1;
					break;
				case 1;
				case 9;
				case 3:
				case 17:
				case 18:
					$suc=2;
					break;
				case 18:
					$suc=3;
					break;
				case 18:
					$suc=4;
					break;
				default:
					$suc=0;
					break;
			}
			*/
		}
		if($_GET['opcAmbientes'] != ''){
			array_push($filtro, array("opcAmbientes", $_GET['opcAmbientes']));
			$ambientes = substr($_GET['opcAmbientes'],-1);
			array_push($textoFiltro, array('Ambientes: ', $ambientes));
		}
		if($_GET['opcDespachos'] != ''){
			array_push($filtro, array("opcDespachos", $_GET['opcDespachos']));
			$ambientes = substr($_GET['opcDespachos'],-1);
			array_push($textoFiltro, array('Despachos: ', $ambientes));
		}
		if($_GET['opcMonedaVenta'] != ''){
			array_push($filtro, array("opcMonedaVenta", $_GET['opcMonedaVenta']));
	//		array_push($textoFiltro, array('Moneda de Venta', $_GET['opcMonedaVenta']));
			array_push($textoFiltro, array('', $_GET['opcMonedaVenta']));
		}
		if($_GET['opcPrecioVenta'] != ''){
			array_push($filtro, array("opcPrecioVenta", $_GET['opcPrecioVenta']));
			$entre = 'Entre '.str_replace('AND','y',$_GET['opcPrecioVenta']);
	//		array_push($textoFiltro, array('Precio de Venta', $entre));
			array_push($textoFiltro, array('', $entre));
		}
		if($_GET['opcEmprendimiento'] != '' && $_GET['opcEmprendimiento'] != 0){
			array_push($filtro, array("opcEmprendimiento", $_GET['opcEmprendimiento']));
			array_push($textoFiltro, array('Emprendimiento: ', $empre['nombre']));
		}
		if($_GET['opcSupTotal'] != 0){
			array_push($filtro, array("opcSupTotal", $_GET['opcSupTotal']));
		//	array_push($textoFiltro, array('Superficie', $_GET['opcSupTotal']));
			array_push($textoFiltro, array('', $_GET['opcSupTotal']));
		}
		$contFiltro = "";
		$txtFiltro = "";
		foreach ($filtro as $fil){
				$contFiltro .= $fil[0].'-'. $fil[1] . '|';
		}
		foreach ($textoFiltro as $fil){
			//echo $fil;
			$txtFiltro .= $fil[0] . $fil[1] . ' - ';
		}
		$contFiltro = substr($contFiltro,0,-1);
		
		$auxCampo = $_GET['campo'];
		$orden = $_GET['orden'];

	/*	echo $contFiltro;
		echo $xxFiltro;
		die();
		*/
	}else{
		/*
		$filtro=Array();
		$textoFiltro=Array();
		
		$filtroGet = explode("|", $_POST['filtro']);
		$textoFiltroGet = explode("|", $_POST['textoFiltro']);
		foreach ($filtroGet as $fil){
			$cont = explode("-", $fil);
			array_push($filtro, $cont);
		}
		foreach ($textoFiltroGet as $fil){
			$cont = explode("-", $fil);
			array_push($textoFiltro, $cont);
		}
		*/
		if(isset($_GET['filtro']) && $_GET['filtro'] != ""){
			$contFiltro = $_GET['filtro'];
			$txtFiltro = $_GET['textoFiltro'];
			$auxCampo = $_GET['campo'];
			$orden = $_GET['orden'];
		}else{
			$contFiltro = $_POST['filtro'];
			$txtFiltro = $_POST['textoFiltro'];
			$auxCampo = $_POST['campo'];
			$orden = $_POST['orden'];
		}
	}
	//print_r($filtro);
	
//	$campo='CAST(TRIM(REPLACE(suptot, ",", ".")) AS DECIMAL)';
	switch($auxCampo){
		case 'superficie':
			$campo='CAST(TRIM(REPLACE(suptot, ",", ".")) AS DECIMAL)';
			break;
		case 'ambientes':
			$campo='CAST(cantamb AS DECIMAL)';
			break;
		case 'valor':
			$campo='CAST(valven AS DECIMAL)';
			break;
		default:
			$campo = '';
			break;
	}
	//$campo='';
	//$orden = 0;		
	$param1=array('txtFiltro'=>$contFiltro,'pagina'=>$pag,'campo'=>$campo, 'orden'=>$orden);
	$prop = $client->call('ListarPropiedadesFiltro',$param1,'');
	
	$param2=array('txtFiltro'=> $contFiltro);
	$cant = $client->call('cantidadPropiedadesFiltro',$param2,'');
	//echo "cant: $cant <br>";
	//print_r($cantDatos);
	

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

}

//print_r($prop);
//die();
?>
<?php include_once('cabezalBuscador.php'); ?>
  <form name="detalle" id="detalle" method="GET">
    <input type="hidden" name="filtro" id="filtro" value="<?php echo $contFiltro; ?>" />
    <input type="hidden" name="div" id="div" value="<?php echo $div; ?>" />
    <input type="hidden" name="textoFiltro" id="textoFiltro" value="<?php echo $txtFiltro; ?>" />
    <input type="hidden" name="dest" id="dest" value="" />
    <input type="hidden" name="campo" id="campo" value="<?php echo $campo; ?>" />
    <input type="hidden" name="orden" id="orden" value="<?php echo $orden; ?>" />
    <input type="hidden" name="id" id="id" value="" />
  </form>
  <!--<div style="border-bottom:thin solid #CCC; padding-bottom:3px;">
  <div id="placaNaranja"><a href="form_consulta_buscador.php?TB_iframe=true&height=360&width=800&modal=false" class="thickbox"><img src="images/sinoencuentra.gif" width="702" height="18" border="0" /></a></div>-->
  <div id="placaBusqueda">
  <div style="float:left; width:820px;"><?php echo $txtFiltro; ?></div>
  <div style="float:right; width:160px; text-align:right; padding-right:10px;">
  <?php if(!isset($_GET['mb'])){?>
  <form action="#">
  <select name="campoOrden" id="campoOrden" onchange="javascript: document.getElementById('AuxCampo').value=this.value; document.getElementById('AuxOrden').value=document.getElementById('ordenOrden').value; document.forms['paginacion'].submit();" style="border:#939598 thin solid; height:15px; font-size:10px; border-radius: 5px;	-ms-border-radius: 5px;	-moz-border-radius: 5px;-webkit-border-radius: 5px; -khtml-border-radius: 5px; color:#333;">
    <option value="" <?php if($auxCampo == ''){ echo 'selected="selected"'; }?>>Orden</option>
    <option value="superficie" <?php if($auxCampo == 'superficie'){ echo 'selected="selected"'; }?>>Superficie</option>
    <option value="ambientes" <?php if($auxCampo == 'ambientes'){ echo 'selected="selected"'; }?>>Ambientes</option>
    <option value="valor" <?php if($auxCampo == 'valor'){ echo 'selected="selected"'; }?>>Valor</option>
  </select>&nbsp;
  <select name="ordenOrden" id="ordenOrden" onchange="javascript: document.getElementById('AuxOrden').value=this.value; document.getElementById('AuxCampo').value=document.getElementById('campoOrden').value; document.forms['paginacion'].submit();" style="border:#939598 thin solid; height:15px; font-size:10px; border-radius: 5px;	-ms-border-radius: 5px;	-moz-border-radius: 5px;-webkit-border-radius: 5px; -khtml-border-radius: 5px; color:#333;">
    <option value="0" <?php if($orden == 0){ echo 'selected="selected"'; }?>>Asc</option>
    <option value="1" <?php if($orden == 1){ echo 'selected="selected"'; }?>>Desc</option>
  </select></form>
  <?php }?>
  </div>
  </div>
  </div>
  <?php
  if(count($prop) > 0){
    for($i=0; $i < count($prop); $i++) {
        $id_prop = $prop[$i]['id_prop'];
        // BUSCA FOTOS ----------
        $param=array('id_prop'=>$id_prop);
        $fotos = $client->call('ListarFotosPropiedad',$param,'');
        //print_r($fotos);
        //-----------------------
		$paramU=array('id_ubica' => $prop[$i]['id_ubica'],
						'modo' => 'c');
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
        ?>
  <div id="resultado">
    <div id="fotoResul" onclick="javascript: detalleProp(<?php echo $id_prop; ?>);"  border="0" style="cursor: pointer;">
      <?php if($fotos[0]['foto'] != "") { ?>
      <img src="http://abm.okeefe.com.ar/fotos_th/<?php echo $fotos[0]['foto']; ?>" width="160" height="107" border="0" style="cursor: pointer;" alt="<?php echo $prop[$i]['operacion'] . " " . $txtTipo . " " . $aptitudAmbientes1 . " " . $loca;?>" />
      <?php }else { ?>
      <img src="images/noDisponible.gif" width="160" height="107" alt="<?php echo $prop[$i]['operacion'] . " " . $txtTipo . " " . $aptitudAmbientes1 . " " . $loca;?>" />
      <?php }?>
    </div>
    <div id="contenidoResul" style="float:right; width:830px; height:107px; margin-left:5px;">
      <div id="cabezaResul">
        <div id="zonaResul"><?php echo substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100);?></div>
        <div id="codigoResul">ID <?php echo str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;?></div>
		<?php if($prop[$i]['oportunidad'] == 1){ ?>
        <div class="oportunidadResul">Oportunidad</div>          
        <?php } ?>
      </div>
      <div id="cuerpoResul" style="width:830px; clear:both;">
        <div id="datos1Resul"><span class="tituResult">Ubicación</span> <span class="caracResul"><?php echo  $loca;?></span><br />
<span class="tituResult">Superficie:</span> <span class="caracResul">
          <?php
		   echo busca_valor($prop[$i]['id_prop'], 198, $carac);
		   if($prop[$i]['id_tipo_prop'] == 6 || $prop[$i]['id_tipo_prop'] == 16){
			   echo "Ha.";
		   }else{
			   echo "m2";
		   }
	
		  switch ($prop[$i]['id_tipo_prop']){
			case 1;
			case 9:
				$aptitudAmbientes = "Ambientes: </span><span class=\"caracResul\">" . $ambientes;
				$aptitudAmbientes1 = $ambientes . " Ambientes";
				break;
			case 6;
			case 7;
			case 16:
			  	$buscar=303;
				$aptitudAmbientes = "Aptitud: </span><span class=\"caracResul\">" . busca_valor($prop[$i]['id_prop'], $buscar, $carac);
				$aptitudAmbientes1 = busca_valor($prop[$i]['id_prop'], $buscar, $carac);
				break;
			default:
				$aptitudAmbientes = "";
				$aptitudAmbientes1 = "";
				break;
		  }
		  ?>
          </span><br />
          <span class="tituResult"><?php echo $aptitudAmbientes;?></span><br />
          <span class="tituResult">Categoría:</span> <span class="caracResul"><?php echo $txtTipo;?></span><br />
          <span class="tituResult">Estado:</span> <span class="caracResul"><?php echo $prop[$i]['operacion'];?></span><br />
        </div>
        <div id="descripResul" onclick="javascript: detalleProp(<?php echo $id_prop; ?>);">
        <div style="height:71px; max-height:71px; overflow:hidden;"><span class="tituResult">Descripción:</span><br />
          <span>
          <?php
            $desc = busca_valor($prop[$i]['id_prop'], 255, $carac);
            $MaxLENGTH = 250;
            if (strlen($desc) > $MaxLENGTH) {
                $desc = substr(strip_tags($desc), 0, strrpos(substr(strip_tags($desc), 0, $MaxLENGTH), " "));
                $desc .= '...';
            }
            echo $desc;
            ?>
            </span>
            </div>
            <div>
            <div style="float: left; padding-top: 2px; font-weight: bold;"><?php echo $prop[$i]['operacion'] . " " . $txtTipo . " " . $aptitudAmbientes1 . " " . $loca;?> </div>
          <div id="vermasResul" onclick="javascript:detalleProp(<?php echo $id_prop; ?>);" style="cursor:pointer;">Más detalles</div>
          </div>
          
        </div>
        <div id="datos2Resul">
        <?php if($prop[$i]['id_emp'] != 0){ ?>
        	<div id="verEmpre" class="consultar" style="margin-bottom:4px;"><a href="detalleEmprendimiento.php?i=<?php echo $prop[$i]['id_emp'];?>" style="color:#333;">Emprendimiento</a></div>
        <?php }else{ ?>
        	<div style="height:18px;margin-bottom:4px;"></div>
        <?php } ?>
          <div class="consultar" style="margin-bottom:4px;"><a href="form_recomendar.php?id=<?php echo str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;?>&TB_iframe=true&height=250&width=300&modal=false" class="thickbox">&gt; Recomendar</a></div>
          <div class="consultar"><a href="form_consulta_prop.php?id=<?php echo str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;?>&t=<?php echo $prop[$i]['id_tipo_prop']; ?>&TB_iframe=true&height=250&width=800&modal=false" class="thickbox">&gt; Consultar</a></div>
          <div class="favoritos"><a href="javascript: Set_Cookie('id[<?php echo $id_prop; ?>]',<?php echo $id_prop; ?>);">Favoritos</a></div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
  <?php }
  }else{
	  echo "<div id=\"sinResultado\">No existe informaci&oacute;n para la b&uacute;queda realizada</div>\n";
  }
   ?>
  <?php
	$paginas = ceil($cant / 30);
	if($paginas > 0) {
		?>
  <div id="paginado">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="paginacion" method="get">
      <input type="hidden" name="filtro" value="<?php echo $contFiltro;?>" />
      <input type="hidden" name="textoFiltro" value="<?php echo $txtFiltro;?>" />
      <input type="hidden" name="pag" id="pag" value="<?php echo $pag;?>">
      <input type="hidden" name="campo" id="AuxCampo" value="<?php echo $auxCampo; ?>" />
      <input type="hidden" name="orden" id="AuxOrden" value="<?php echo $orden; ?>" />
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
				echo  "<a href=\"javascript: document.getElementById('pag').value=". intval($pag - 1) ."; document.forms['paginacion'].submit();\" class=\"pagina\">< previa</a> | ";
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
				echo  "<a href=\"javascript: document.getElementById('pag').value=". intval($pag + 1) ."; document.forms['paginacion'].submit();\" class=\"pagina\">siguiente ></a>";
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