<?php
session_start();
require_once('lib-nusoap/nusoap.php');

$id_empre = $_GET['i'];

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


?>
<?php include_once('cabezalBuscador.php'); ?>
<div style=" border-top:thin solid #CCC;">
  <div id="izqEmpre">
  <div id="nombreEmpre"><?php echo $empre_sel['nombre'];?></div>
    <?php if($empre_sel['logo'] != "") {?>
    <div id="logoEmpre"><img src="http://abm.okeefe.com.ar/fotos/<?php echo $empre_sel['logo'];?>" border="0" style="max-height:150px;max-width:150px;" /></div>
    <?php } ?>
    <div id="menuEmpre">
      <?php
		for($i=0; $i < count($carac); $i++) {
				?>
      <li><a href="javascript:ajax_loadContent('contenidoEmpre','caracEmprendimiento.php?i=<?php echo $id_empre; ?>&car=<?php echo $carac[$i]['id_carac'];?>');"><?php echo $carac[$i]['titulo']; ?></a></li>
      <?php } ?>
    </div>
        <div id="botonesEmpre">
          <div id="botonEmpre"><a href="busqueda.php?opcEmprendimiento=<?php echo $id_empre; ?>">
            <div id="icoEmpre"><img src="images/verPlano.gif" width="21" height="23" alt="Ver video" /></div>
            <div id="txticoEmpre">Propiedades Disponibles</div></a>
            <div class="clearfix"></div>
          </div>
          <div id="botonEmpre"><a href="mapa_google_empre.php?lat=<?php echo $empre_sel['goglat'];?>&long=<?php echo $empre_sel['goglong'];?>&TB_iframe=true&height=405&width=580&modal=false" title="Vista en GoogleMaps" class="thickbox">
            <div id="icoEmpre"><img src="images/verUbicacion.gif" width="21" height="23" alt="Ver video" /></div>
            <div id="txticoEmpre">Ver ubicación</div>
            <div class="clearfix"></div></a>
          </div>
          <div id="botonEmpre"><a href="form_recomendar.php?id=<?php echo $prop['id_sucursal'] . str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;?>&TB_iframe=true&height=250&width=300&modal=false" class="thickbox">
            <div id="icoEmpre"><img src="images/enviarDetalle.gif" width="21" height="23" alt="Ver video" border="0" /></div>
            <div id="txticoEmpre">Enviar a</div></a>
            <div class="clearfix"></div>
          </div>
      </div>
  </div>
  <div id="contenidoEmpre"></div>
  </div>
  <script type="text/javascript">
  ajax_loadContent('contenidoEmpre','caracEmprendimiento.php?i=<?php echo $id_empre; ?>&car=72');
  </script>
  <?php include_once("pie.php"); ?>