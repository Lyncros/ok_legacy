<?php require_once('Connections/config.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
/*
function query_into_array($query) {
    settype($retval,"array");
    $result= mysql_query($query);
    $FILA=mysql_num_rows($result);
    $COLUMNA=mysql_num_fields($result);
    for($CONTADOR_FILA=0;
    $CONTADOR_FILA<$FILA;
    $CONTADOR_FILA++) {
        for($CONTADOR_COLUMNA=0;
        $CONTADOR_COLUMNA<$COLUMNA;
        $CONTADOR_COLUMNA++) {
            $retval[$CONTADOR_FILA][mysql_field_name($result,$CONTADOR_COLUMNA)] = mysql_result($result,$CONTADOR_FILA, mysql_field_name($result,$CONTADOR_COLUMNA));
        }
    }
    return $retval;
}
function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value))) {
            return $current_key;
        }
    }
    return false;
}
*/
mysql_select_db($database_config, $config);
$query_cedin = "SELECT * FROM cedin ORDER BY orden ASC";
$cedin = mysql_query($query_cedin, $config) or die(mysql_error());
//$row_cedin = mysql_fetch_assoc($cedin);
//$totalRows_cedin = mysql_num_rows($cedin);
//$array_cedin = query_into_array($query_cedin);

?>
<?php include_once("cabezalBuscador.php"); ?>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script> 
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js" type="text/javascript"></script>
  <style type="text/css">
	@import url("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css");
</style>
  <script type="text/xml">
<!--
<oa:widgets>
  <oa:widget wid="2174022" binding="#jQueryUIAccordion" />
</oa:widgets>
-->
</script>
  <div style="float:left; width:740px; border-right:thin solid #CCC; padding:0px 10px;">
    <div>
    	<div>
      <div style="color: #008046; font-size: 20px; font-family: 'Palatino Linotype', 'Book Antiqua', Palatino, serif; float:left">Lanzan CEDIN: confirman multiuso y que no vencerá</div>
      <div style="float:right; color:#FFF; width: 150px; margin-top:5px;"class="oportunidadResul"><a href="http://www.okeefe.com.ar/pdfs/Reglamentacion%20CEDIN-Com-A5447_12-jun-2013.pdf" target="_blank" style="color:#FFF;">Reglamentación CEDIN</a></div>
      <div class="clearfix"></div>
      </div>
      <p style="font-size:14px;">Finalmente, el Banco Central dio a conocer la reglamentación de los CEDIN. Tal como anticipó este miércoles Ambito Financiero, la entidad que dirige Marcó del Pont dispuso un amplio menú de usos a los que podrá estar destinado este certificado de inversión inmobiliaria.</p>
      <p style="font-size:14px;">Según surge de la reglamentación entre los destinos admitidos para acreditar una inversión a los efectos de obtener dólares a cambio de CEDIN figuran los siguientes: compra de terrenos, lotes o parcelas (urbanas y rurales), galpones, locales, oficinas, cocheras, viviendas construídas, construcción de nuevas viviendas, refacción, ampliación y mejora de inmuebles, así como también la adquisición de materiales de construcción.</p>
      <p style="font-size:14px;">Una vez que el título es utilizado y que el banco verifica que efectivamente existió la transacción de cualquiera de esas operaciones, le pondrá un selló con el rótulo &quot;Aplicado&quot;. Luego, el tenedor del CEDIN podrá cambiarlos dólares o endosarlos de manera indefinida.</p>
      <p style="font-size:14px;">Una novedad importante de la reglamentación, es que una vez que el CEDIN tiene el rótulo de &quot;aplicado&quot; podrá cobrarse inmediatamente. Según consta en el punto 8 de la reglamentación, &quot;El BCRA autorizará a la entidad que abonará el certificado quien emitirá un número de transacción que deberá ser incorporado al CEDIN con la leyenda &quot;PAGO&quot;, debiendo abonar la entidad financiera el CEDIN al beneficiario final en ese mismo acto&quot;. Esto, a priori, despeja las dudas que tenían muchos martilleros y que temían problemas a la hora de escriturar propiedades.</p>
      <p style="font-size:14px;">Otro dato interesante es que estos certificados no devengan ningún interés ni tienen fecha de vencimiento. Además, están exentos del &quot;Impuesto al cheque&quot;.</p>
      <p style="font-size:14px;"><strong>Aquí un cuestionario con las preguntas y respuestas sobre el CEDIN.</strong></p>
    </div>
    <div>
      <div id="jQueryUIAccordion">
        <h3><a href="#">¿Qué es el CEDIN? ¿Es similar a un cheque de viajero o a un cheque cancelatorio?</a></h3>
        <div>
          <p> El CEDIN es un medio de pago e instrumento financiero que se aplica en la realización de inversiones en el mercado inmobiliario y/o en proyectos de construcción de viviendas, con el claro objetivo de reactivar la actividad en dichos sectores.</p>

<p>El CEDIN no es asimilable a un cheque cancelatorio, ya que dicho instrumento está previsto por ley con tan sólo dos endosos, es decir que es considerado un instrumento de baja circulación y apunta a resolver situaciones puntuales de corto plazo. Tampoco es asimilable a un cheque de viajero, ya que éstos están nominados y sólo pueden ser cobrados por la persona a la cual le fueron emitidos. 
</p>
        </div>
        <h3><a href="#">¿Cómo se obtiene un CEDIN?</a></h3>
        <div>
          <p>Las personas interesadas en suscribir un CEDIN deberán solicitarlos en las entidades financieras, identificándose con la documentación que establece la normativa vigente.</p>
          <p>Los CEDIN se suscriben mediante la entrega de dólares estadounidenses billete, aplicándose para el caso de transferencias del exterior o arbitraje de moneda lo dispuesto al respecto por las normas pertinentes en materia cambiaria. El suscriptor podrá solicitar uno o más CEDIN, por un importe total equivalente a las sumas en dólares estadounidenses que efectivamente se transfieran al Banco Central de la República Argentina (BCRA). </p>
          <p>¿Qué uso se le puede dar al CEDIN? ¿Sólo se puede utilizar para operaciones inmobiliarias o desarrollos en el sector de la construcción? ¿O puede tener otros usos en la medida en que la contraparte los acepte? (Ej. Compra de paquetes turísticos, automóviles, electrodomésticos, etc.)</p>
          <p>El CEDIN puede ser utilizado para el pago de operaciones de toda índole, en la medida en que la contraparte lo acepte. Pero la contraparte debe tener presente que para su cobro debe haberse aplicado a los destinos previstos en la ley, con anterioridad o con posterioridad a su aceptación y recepción como medio de pago. Si el CEDIN fue &quot;aplicado&quot; a los destinos previstos, se encontrará identificado como tal, tanto en el cuerpo del documento (con la leyenda &quot;Aplicado&quot;) como en la información registrada en el BCRA.</p>
        </div>
        <h3><a href="#">¿El CEDIN tendrá fecha de vencimiento o un plazo de vigencia? Los CEDIN no tendrán fecha de vencimiento. ¿Qué medidas de seguridad tiene el CEDIN para evitar falsificaciones?</a></h3>
        <div>
          <p> Se empleará papel de seguridad, con marca de agua exclusiva de Casa de Moneda y fibras de seguridad. Tiene características de seguridad en la impresión entre las que se destacan tintas de seguridad visibles e invisibles, fondos de seguridad, microletra, roseta en tinta ópticamente variable y un código QR.</p>
        </div>
        <h3><a href="#">¿Cómo se puede verificar la legitimidad y las condiciones de seguridad del CEDIN?</a></h3>
        <div>
          <p> Las entidades financieras verificarán la legitimidad y las características formales y de seguridad del CEDIN. Las entidades financieras contarán con los elementos tecnológicos necesarios para verificar el cumplimiento de las características de seguridad del CEDIN. </p>
        </div>

        <h3><a href="#">¿Se puede pagar un inmueble parte en dólares o pesos y parte en CEDIN?</a></h3>
        <div>
          <p>Sí, dependerá del acuerdo entre las partes.</p>
        </div>
        <h3><a href="#">¿Los CEDIN van a estar registrados en el BCRA?</a></h3>
        <div>
          <p> Sí, los CEDIN se encontrarán registrados en el BCRA. La información que se encontrará registrada en la base del BCRA será la correspondiente a la suscripción del CEDIN, la aplicación a los destinos previstos en la ley, el cobro y eventuales cambios. También se incorporará a la base de datos del BCRA los endosos que se registren en las entidades bancarias. </p>
        </div>
        <h3><a href="#">¿Si se pierde un CEDIN, se puede recuperar?</a></h3>
        <div>
          <p> Sí. El tenedor desposeído debe comunicar de inmediato en cualquier casa de una entidad financiera, mediante nota recibida formalmente, lo sucedido y a más tardar el día hábil siguiente, deberá presentar acreditación fehaciente de la denuncia pertinente, efectuada ante la autoridad competente, de conformidad con lo previsto en la normativa vigente en la jurisdicción de que se trate. De tal modo, quedará inhabilitado el pago del CEDIN extraviado o sustraído.</p>
        </div>
        <h3><a href="#">¿Se pueden endosar los CEDIN? ¿Cuántas veces?</a></h3>
        <div>
          <p> El CEDIN podrá transmitirse mediante endoso simple o registrado en el espacio disponible al dorso del documento. Los endosos no tienen límite de cantidad.</p>
        </div>
        <h3><a href="#">¿Los endosos podrán ser registrados o certificados?</a></h3>
        <div>
          <p> La registración de endosos es optativa. Si los endosos no desean registrarse, la transmisión de los CEDIN es realizada de manera bilateral entre el endosante y endosatario. Si desean registrarse, la operación deberá concretarse en una entidad financiera, sin costo o cargo alguno. </p>
        </div>
        <h3><a href="#">¿Se podrán comprar CEDIN con pesos?</a></h3>
        <div>
          <p> Sí, una vez suscripto el CEDIN en una entidad financiera mediante la entrega de dólares estadounidenses billete o mediante transferencias del exterior, la compra de CEDIN en el mercado secundario dependerá del acuerdo entre las partes.</p>
        </div>
        <h3><a href="#">¿Cómo funcionará el mercado secundario de CEDIN?</a></h3>
        <div>
          <p> Se está trabajando para estructurar una modalidad para el mercado secundario de CEDIN.</p>
        </div>
        <h3><a href="#">¿Las casas de cambio van a poder operar con CEDIN y cambiarlos por pesos?</a></h3>
        <div>
          <p>Las Casas de Cambio no pueden suscribir CEDIN, pero podrán operar en el mercado secundario. </p>
          <p>El que vende un inmueble y recibe CEDIN, ¿podrá cambiarlos por dólares en el banco en el momento? ¿O habrá que avisar de la operación inmobiliaria con anticipación y entregar al banco los datos del CEDIN para que éste controle su legitimidad?</p>
          <p>La entidad financiera interviniente deberá proveer la información necesaria al titular o endosatario del CEDIN, así como a su eventual futuro receptor, y extremar su diligencia a fin de asegurar que el proceso de verificación, endoso y cobro por parte del beneficiario sea completado en el mismo acto de la presentación del CEDIN.</p>
        </div>
        <h3><a href="#">¿Se pueden dejar depositados los CEDIN en el banco?</a></h3>
        <div>
          <p> No.</p>
        </div>
        <h3><a href="#">¿Se podrán nominar CEDIN con número no redondos? (Ej. u$s 68.754). Cada CEDIN será emitido por un mínimo de U$S 100 y un máximo de u$s 100.000 y podrán ser nominados por números no redondos. ¿Cómo se certifican las refacciones para poder aplicar CEDIN?</a></h3>
        <div>
          <p>Deberá presentarse certificación de obra efectuada por profesional arquitecto o ingeniero con firma legalizada por el respectivo Consejo o Colegio Profesional o ente que corresponda. En ella deberán constar los datos del inmueble, costo de la refacción y fecha de su realización, nombre completo e identificación tributaria del sujeto que realiza el/los pago/s, el/los importe/s abonado/s por éste, la modalidad del/los pago/s y su/s fecha/s de realización.</p>
          <p>Alternativamente, se deberán presentar las respectivas facturas o recibos que permitan comprobar la aplicación de los fondos a la venta de materiales de construcción y/o al cobro de la prestación de servicios relacionados con la obra, proveniente de un Registro de Proveedores de Materiales para la Construcción, que reglamentará oportunamente el Ministerio de Economía. </p>
          <p>Toda la documentación que se presente debe tener fecha posterior a la de suscripción del CEDIN.</p>
        </div>
        <h3><a href="#">¿Cómo se certifican los avances de obras en los fideicomisos desde el pozo, para poder aplicar los CEDIN?</a></h3>
        <div>
          <p>En el caso de &quot;construcción de nuevas viviendas&quot; deberá presentarse certificación de obra efectuada por profesional arquitecto o ingeniero con firma legalizada por el respectivo Consejo o Colegio Profesional o ente que corresponda. En ella deberán constar los datos del inmueble, el destino habitacional de la construcción, su costo, nombre competo e identificación tributaria del sujeto que realiza el/los pago/s, el/los importe/s abonado/s por éste, la modalidad del/los pago/s y su/s fecha/s de realización. </p>
          <p>Toda la documentación que se presente debe tener fecha posterior a la de suscripción del CEDIN. </p>
          <p>Las operaciones que se hagan con CEDIN en las entidades financieras ¿tendrán algún costo o cargo por parte de éstas?</p>
          <p>No. Las entidades financieras no percibirán cargos, comisiones ni forma alguna de remuneración o recupero de gastos por tareas vinculadas con los CEDIN.</p>
        </div>
      </div>
      <script type="text/javascript">
jQuery("#jQueryUIAccordion").accordion({ 
		event: "click",
		collapsible: true,
		autoHeight: false,
		active: false
	});
	</script> 
    </div>
     <div style="padding:10px 0px;"><a href="http://www.ambito.com/noticia.asp?id=692753" target="_blank">Fuente: &Aacute;mbito financiero del miércoles 13/6/13</a></div>
  </div>
  <div style="float:right; width:215px; padding:5px 10px;">
    <p style="font-family: Georgia, 'Times New Roman', Times, serif; font-size: 18px; color:#008046">Links de Interés</p>
    <?php
	  while($row_cedin = mysql_fetch_assoc($cedin)){
	  ?>
    <li style="font-family: Georgia, 'Times New Roman', Times, serif; font-size: 14px; list-style-type: square; border-bottom: thin solid #CCC; margin:5px 0px 5px 10px;"> <a href="<?php echo $row_cedin['link_cedin']; ?>" target="_blank" style="text-decoration: none; color: #666; line-height: 90%;"><?php echo $row_cedin['titulo_cedin'];?></a> </li>
    <?php }?>
  </div>
  <div class="clearfix"></div>
</div>
<?php include_once("pie.php"); ?>
<?php
mysql_free_result($cedin);
?>
