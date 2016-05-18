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
$suc = 15;
?>
<?php include_once('cabezal.php'); ?>
<?php include_once("menu.php");?>
<div id="contenido">
  <div id="izq">
    <div id="revistas">
      <div id="revistaActual">
        <div style="margin:5px; text-align:left">
        <h1><span style="color:#C60; font-size:1.5em; font-weight:bold;">Revista 2014</span></h1></div>
        <div><a href="revista.php"><img src="revistas/tapas/ultima.jpg" width="266" height="378" border="1" /></a></div>
        <div style="margin:10px;"><a href="revista.php" target="_blank" rel="nofollow"><span id="revistaDigital">&gt; Ver revista digital</span></a></div>
      </div>
      <div id="revistaAnteriores">
      <div style="margin:5px; text-align:left; font-size:14px;"><h2>Ediciones anteriores</h2></div>
      <table border="0" cellspacing="18" cellpadding="0" align="center">
  <tr>
    <td><img src="revistas/tapas/news2012.jpg" width="110" height="155" /><br />
O'Keefe NEWS 2012 <a href="revistas/revista-05-web/index.html" target="_blank" rel="nofollow"><span style="background-color:#f26322;color:#FFF; line-height:50%;">+</span> Ver</a></td>
    <td><img src="revistas/tapas/r4.jpg" width="110" height="155" /><br />
Marzo 2011 <a href="revistas/revista-04-web/index.html" target="_blank" rel="nofollow"><span style="background-color:#f26322;color:#FFF; line-height:50%;">+</span> Ver</a></td>
  </tr>
  <tr>
     <td><img src="revistas/tapas/r3.jpg" width="110" height="155" /><br />
Marzo 2010 <a href="revistas/revista-03-web/index.html" target="_blank" rel="nofollow"><span style="background-color:#f26322;color:#FFF; line-height:50%;">+</span> Ver</a></td>
   <td><img src="revistas/tapas/r2.jpg" width="110" height="155" /><br />
O'keefe NEWS 2011 <a href="revistas/revista-02-web/index.html" target="_blank" rel="nofollow"><span style="background-color:#f26322;color:#FFF; line-height:50%;">+</span> Ver</a></td>
<!--    <td><img src="revistas/tapas/r1.jpg" width="110" height="155" /><br />
O'keefe NEWS 2010 <a href="revistas/revista-01-web/index.html" target="_blank" rel="nofollow"><span style="background-color:#f26322;color:#FFF; line-height:50%;">+</span> Ver</a></td>
 -->  </tr>
</table>
<div style="margin:10px; text-align:center; font-size:1.2em;"><a href="form_revista.php?TB_iframe=true&height=200&width=350&modal=false" class="thickbox" rel="nofollow"><span id="revistaDigital">&gt; Suscribir a Nuestra Revista</span></a></div>
</div>
    </div>
    <div class="clearfix"></div>
<?php include_once("menuBajo.php"); ?>
  </div>
  <div id="derecha">
    <?php include_once("buscadorVertical.php"); ?>
  </div>
  <?php include_once("pie.php"); ?>
