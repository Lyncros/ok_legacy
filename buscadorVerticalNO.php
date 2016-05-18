<?php
header('Content-type: text/html; charset=utf-8');
require_once('lib-nusoap/nusoap.php');

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

$zona = $client->call('ListarZonaPrincipal',array(),'');
//print_r($zona);
//die();

$tipoProp = $client->call('ListarTipoProp',array(),'');
//print_r($tipoProp);
//die();

$empreBuscador = $client->call('ListarEmprendimientos',array(),'');
//print_r($empreBuscador);
//die();

?>

<table width="235" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center"><img src="images/sobre.gif" width="17" height="13" alt="mail" /></td>
    <td><a href="mailto:inmobiliaria@okeefe.com.ar" class="mailHome">inmobiliaria@okeefe.com.ar</a></td>
    <td class="telHome" align="right"><img src="images/redes.gif" alt="redes sociales" width="56" height="18" border="0" usemap="#Map" /></td>
  </tr>
  <tr>
    <td align="center" width="19"><img src="images/telefono.gif" width="19" height="13" alt="Teléfono" /></td>
    <td colspan="2" class="telHome">[5411]&shy; 4253&shy;-3961 / 02229&shy;-45&shy;-5003</td>
  </tr>
</table>
<div id="buscadorHome">
  <div class="buscarTitu">Buscar Propiedades</div>
  <form action="busqueda.php" name="buscador" id="buscador" method="GET" onsubmit="actualizaDesdeHasta(); actualizaUbica();">
    <div style="width:235px;">
      <div>
        <select name="opcTipoOper">
          <option value="" selected="selected">Operación</option>
          <option value="Venta">Venta</option>
          <option value="Alquiler">Alquiler</option>
          <option value="Alquiler Temporario">Alquiler temporario</option>
        </select>
      </div>
      <div>
        <select name="opcTipoProp" id="opcTipoProp" onchange="actualizaAmbientes(this.value);">
          <option value='0' selected="selected">Inmueble</option>
          <?php foreach($tipoProp as $tipo){ ?>
          <option value='<?php echo strtolower($tipo['tipo_prop']);?>'><?php echo $tipo['tipo_prop'];?></option>
          <?php } ?>
        </select>
      </div>
      <div>
        <select name="opcZona" id="opcZona">
          <option value='0' selected="selected">Zona</option>
          <?php foreach($zona as $ubi){ ?>
          <option value='<?php echo $ubi['id_ubica'];?>'><?php echo $ubi['nombre_ubicacion'];?></option>
          <?php } ?>
        </select>
      </div>
      <div id="Localidad">
        <select name="localidad" id="localidad" onclick="abreSelector();">
          <option value='0' selected="selected">Localidad</option>
        </select>
      </div>
      <div style="float:left; width:110px; display:block;" id="ambientes">
        <select name="opcAmbientes" id="opcAmbientes">
          <option value="" selected="selected">Ambientes</option>
          <option value=" =1">1</option>
          <option value=" =2">2</option>
          <option value=" =3">3</option>
          <option value=" =4">4</option>
          <option value=" >=5">5 o más</option>
        </select>
        </div>
      <div style="float:left; width:110px; display:none;" id="despachos">
        <select name="opcDespachos" id="opcDespachos">
          <option value="" selected="selected">Cant. Despachos</option>
          <option value=" =1">1</option>
          <option value=" =2">2</option>
          <option value=" =3">3</option>
          <option value=" =4">4</option>
          <option value=" >=5">5 o más</option>
        </select>
        </div>
      <div style="float:left; width:110px; display:none;" id="supTotal">
        <select name="opcSupTotal" id="opcSupTotal">
          <option value="0" selected="selected">Cantidad de Ha</option>
          <option value=" 50 AND 100 ">Entre 50 y 100Ha</option>
          <option value=" 100 AND 200">Entre 100 y 200Ha</option>
          <option value=" 200 AND 300">Entre 200 y 300Ha</option>
          <option value=" 300 AND 500">Entre 300 y 500Ha</option>
          <option value=" >=500">Más de 500Ha</option>
        </select>
      </div>
      <div style="float:right; width:110px;">
        <select name="opcMonedaVenta" id="opcMonedaVenta">
          <option value=''  selected="selected">Moneda</option>
          <option value='U$S'>u$s</option>
          <option value=' $'>$</option>
        </select>
      </div>
      <div class="clearfix"></div>
      <div style="float:left; width:110px;">
        <select name="desde" id="desde" onblur="actualizaDesdeHasta();">
          <option value=''  selected="selected">Desde</option>
          <option value='0'>0</option>
          <option value='100000'>100.000</option>
          <option value='150000'>150.000</option>
          <option value='200000'>200.000</option>
          <option value='250000'>250.000</option>
          <option value='300000'>300.000</option>
          <option value='400000'>400.000</option>
          <option value='500000'>500.000</option>
        </select>
      </div>
      <div style="float:right; width:110px;">
        <select name="hasta" id="hasta" onblur="actualizaDesdeHasta();" style="margin-top:3px;">
          <option value=''  selected="selected">Hasta</option>
          <option value='100000'>100.000</option>
          <option value='150000'>150.000</option>
          <option value='200000'>200.000</option>
          <option value='250000'>250.000</option>
          <option value='300000'>300.000</option>
          <option value='400000'>400.000</option>
          <option value='500000'>500.000 o más</option>
        </select>
      </div>
      <div class="clearfix"></div>
      <div id="emprendimientos" style="float:left; width:150px;">
        <select name="opcEmprendimiento" id="opcEmprendimiento">
          <option value='0' selected="selected">Emprendimientos</option>
          <?php foreach($empreBuscador as $emp){ ?>
          <option value='<?php echo $emp['id_emp'];?>'><?php echo $emp['nombre'];?></option>
          <?php } ?>
        </select>
      </div>
      <div style="width:68px; float:right;">
        <input name="enviar" type="image" src="images/buscarHome.gif" style="border:none; padding:0px;" />
      </div>
      <div class="clearfix"></div>
    </div>
    <input type="hidden" name="opcLocalidad" id="opcLocalidad" />
    <input type="hidden" name="opcUbica" id="opcUbica" />
    <input type="hidden" name="opcPrecioVenta" id="opcPrecioVenta" value="" />
  </form>
</div>
  <form action="detalleProp.php" method="get">
    <div style="width:235px; margin:5px 0px 5px 0px;">
      <div style="float:left; width:205px;">Código
        <input type="text" value="" name="codigo" id="codigo" max="6" style="color:#939393; border:#939598 solid thin; width:150px; height:16px; padding-left:4px; border-radius: 5px; -ms-border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;	-khtml-border-radius: 5px;" onChange="javascript:this.value=this.value.toUpperCase();" onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="if(this.value == '') this.value = this.defaultValue" />
      </div>
      <div style="float:right; width:20px;">
        <input name="enviar" type="image" src="images/lupa.gif" style="border:none; width:20px; height:20px;" />
      </div>
      <div class="clearfix"></div>
    </div>
  </form>
<div id="destacados" style="margin-top:10px;">
  <div class="destacTitu">Propiedades destacadas</div>
  <div id="tituDestacado" class="tituDestacado"></div>
  <div id="fotoDestacado" style="width:235px; height:138px; overflow:hidden;"></div>
  <div id="txtDestacado" class="txtDestacado"></div>
  <div style="margin-top:3px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><a href="javascript: antDestacado();"><img src="images/destacadoIzq.gif" width="12" height="14" border="0" /></a></td>
    <td id="vermasDestacado" align="center"></td>
    <td align="right"><a href="javascript: sigDestacado();"><img src="images/destacadoDer.gif" width="12" height="14" border="0" /></a></td>
  </tr>
</table>
</div>
</div>
<map name="Map" id="Map">
  <area shape="rect" coords="1,1,18,17" href="http://www.facebook.com/profile.php?id=100001722647049" target="_blank" />
  <area shape="rect" coords="38,0,56,17" href="callto://inmobiliariaokeefe/" target="_blank" />
</map>
