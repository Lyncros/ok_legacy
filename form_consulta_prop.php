<?php 

require_once('Connections/config.php'); 
require_once('inc/funciones.php');

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


$nombre = "";
$apellido = "";
$telefono = "";
$mail = "";
$consulta = "";
if (isset($_GET['id'])) {
	$id=strip_tags($_GET['id']);
}
if (isset($_GET['t'])) {
	$tipoForm=strip_tags($_GET['t']);
}

if ((isset($_POST["contacto"])) && ($_POST["contacto"] == "1")) {
	include_once ('inc/securimage.php');
	$securimage = new Securimage();
	$captcha=$_POST['captcha'];

	if ($securimage->check($captcha) == false) {
		$nombre = $_POST['nombre'];
		$apellido = $_POST['apellido'];
		$telefono = $_POST['telefono'];
		$mail = $_POST['email'];
		$consulta = $_POST['consulta'];
		$id = $_POST['id'];
		$tipoForm = $_POST['tipo'];
	} else {
		header('Content-type: text/html; charset=utf-8');
		require_once('lib-nusoap/nusoap.php');
		
		//$wsdl="http://localhost/okeefe/webservice/servicioweb.php?wsdl";
		$wsdl="http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
		$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice
		
		$zona = $client->call('ListarZonaPrincipal',array(),'');
		//print_r($zona);
		//echo "ubica: " .$ubica . "<br /><br />";
		//die();
		
		$tipoProp = $client->call('ListarTipoProp',array(),'');
		//print_r($tipoProp);
		//die();
		
		if ($_POST['tipo'] != "empre") {
			$lista_id_carac = "198,255,257,303,369";
			//         $lista_id_carac = "42,255,257";
			$param=array('inprop'=>$_POST["id"],'incarac'=>$lista_id_carac);
			$carac = $client->call('DatosConjuntoPropiedades',$param,'');
			//print_r($carac);
			//die();
			
			$responsables = explode(",", busca_valor($_POST["id"], 369, $carac));
			$remarketing = "";
			
			if(empty($responsables[0])){
				switch ($_POST['tipo']){
					case 6:
					case 7:
					case 16:
						$destino="dennis@okeefe.com.ar, felipe.atucha@okeefe.com.ar";
						$nom_usuario = "Dennis	O'keefe";
						$mail_usuario = "dennis@okeefe.com.ar";
						$remarketing = "<!-- Google Code for AO Rural Conversion Page -->
<script type=\"text/javascript\">
/* <![CDATA[ /
var google_conversion_id = 973708058;
var google_conversion_language = \"en\";
var google_conversion_format = \"2\";
var google_conversion_color = \"ffffff\";
var google_conversion_label = \"KH0zCMa6yQgQmram0AM\";
var google_remarketing_only = false;
/ ]]> */
</script>
<script type=\"text/javascript\" src=\"//www.googleadservices.com/pagead/conversion.js\">
</script>
<noscript>
<div style=\"display:inline;\">
<img height=\"1\" width=\"1\" style=\"border-style:none;\" alt=\"\" src=\"//www.googleadservices.com/pagead/conversion/973708058/?label=KH0zCMa6yQgQmram0AM&guid=ON&script=0\"/>
</div>
</noscript>";
						break;
					case 1:
					case 9:
					case 3:
					case 17:
					case 18:
						$nom_usuario = "Paulo Hubeñak";
						$mail_usuario = "paulo@okeefe.com.ar";
						$destino="paulo@okeefe.com.ar";
						$remarketing = "<!-- Google Code for AO Residencial Conversion Page -->
<script type=\"text/javascript\">
/* <![CDATA[ */
var google_conversion_id = 973708058;
var google_conversion_language = \"en\";
var google_conversion_format = \"2\";
var google_conversion_color = \"ffffff\";
var google_conversion_label = \"qcE1CM65yQgQmram0AM\";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type=\"text/javascript\" src=\"//www.googleadservices.com/pagead/conversion.js\">
</script>
<noscript>
<div style=\"display:inline;\">
<img height=\"1\" width=\"1\" style=\"border-style:none;\" alt=\"\" src=\"//www.googleadservices.com/pagead/conversion/973708058/?label=qcE1CM65yQgQmram0AM&amp;guid=ON&amp;script=0\"/>
</div>
</noscript>";
						break;
					case 2:
					case 11:
					case 15:
						$nom_usuario = "Tomas O'Keefe";
						$mail_usuario = "tomas@okeefe.com.ar";
						$destino="tomas@okeefe.com.ar";
						break;
					case "empre":
						$nom_usuario = "Ignacio O'Keefe";
						$mail_usuario = "ignacio@okeefe.com.ar";
						$destino="ignacio@okeefe.com.ar";
						$remarketing = "<!-- Google Code for AO Emprendimientos Conversion Page -->
<script type=\"text/javascript\">
/* <![CDATA[ /
var google_conversion_id = 973708058;
var google_conversion_language = \"en\";
var google_conversion_format = \"2\";
var google_conversion_color = \"ffffff\";
var google_conversion_label = \"pGhFCL67yQgQmram0AM\";
var google_remarketing_only = false;
/ ]]> */
</script>
<script type=\"text/javascript\" src=\"//www.googleadservices.com/pagead/conversion.js\">
</script>
<noscript>
<div style=\"display:inline;\">
<img height=\"1\" width=\"1\" style=\"border-style:none;\" alt=\"\" src=\"//www.googleadservices.com/pagead/conversion/973708058/?label=pGhFCL67yQgQmram0AM&guid=ON&script=0\"/>
</div>
</noscript>";
						break;
					default:
						$nom_usuario = "Inmobiliaria O'Keefe";
						$mail_usuario = "info@okeefe.com.ar";
						$destino="info@okeefe.com.ar";
						break;
				}
			} else {
				$destino="";
				
				foreach($responsables as $id_user){
					switch ($id_user){
						case 1:
							$nom_usuario = "Tomas O'Keefe";
							$mail_usuario = "tomas@okeefe.com.ar";
							break;
						case 2:
							$nom_usuario = "Verónica Pouso";
							$mail_usuario = "veronica.pouso@okeefe.com.ar";
							break;
						case 3:
							$nom_usuario = "Patricia Chover";
							$mail_usuario = "patricia.chover@okeefe.com.ar";
							break;
						case 4:
							$nom_usuario = "Charlie O'Keefe";
							$mail_usuario = "charlie@okeefe.com.ar";
							break;
						case 6:
							$nom_usuario = "Dennis	O'keefe";
							$mail_usuario = "dennis@okeefe.com.ar";
							break;
						case 7:
							$nom_usuario = "Felipe Aller Atucha";
							$mail_usuario = "felipe.atucha@okeefe.com.ar";
							break;
						case 75:
							$nom_usuario = "Edgardo Martinez";
							$mail_usuario = "edgardo@zgroupsa.com.ar";
							break;
						case 99:
							$nom_usuario = "Gustavo Martinez";
							$mail_usuario = "gustavo@zgroupsa.com.ar";
							break;
						case 100:
							$nom_usuario = "Camila Varela Gomez";
							$mail_usuario = "camila.varela@okeefe.com.ar";
							break;
						case 101:
							$nom_usuario = "Ignacio O'Keefe";
							$mail_usuario = "ignacio@okeefe.com.ar";
							break;
						case 20120719153029:
							$nom_usuario = "Susana";
							break;
						case 20121212144852:
							$nom_usuario = "Matias Losada";
							$mail_usuario = "matias.losada@okeefe.com.ar";
							break;
						case 20121228125428:
							$nom_usuario = "Paulo Hubeñak";
							$mail_usuario = "paulo@okeefe.com.ar";
							break;
						case 20130316133204:
							$nom_usuario = "Luciana Fernandez";
							$mail_usuario = "luciana.fernandez@okeefe.com.ar";
							break;
						case 20130515124616:
							$nom_usuario = "Tatiana Indarte";
							$mail_usuario = "tatiana.indarte@okeefe.com.ar";
							break;
						case 20130604170119:
							$nom_usuario = "Belisario Reynal";
							$mail_usuario = "belisario@proceo.com.ar";
							break;
						case 20131104103244:
							$nom_usuario = "Hector Miño";
							$mail_usuario = "hector@okeefe.com.ar";
							break;
						case 20140117125831:
							$nom_usuario = "Alejandra Duboe";
							$mail_usuario = "alejandra.duboe@okeefe.com.ar";
							break;
						case 20140121020800:
							$nom_usuario = "Nancy Veltri";
							$mail_usuario = "nancy.veltri@okeefe.com.ar";
							break;
						case 20140127102717:
							$nom_usuario = "Marina Greco";
							$mail_usuario = "marina.greco@okeefe.com.ar";
							break;
						case 20140205190501:
							$nom_usuario = "Francisco De Pol";
							$mail_usuario = "francisco.depol@okeefe.com.ar";
							break;
						case 20140305171341:
							$nom_usuario = "Monica Duva";
							$mail_usuario = "monica.duva@okeefe.com.ar";
							break;
						case 20140310183605:
							$nom_usuario = "Jerónimo Garcia";
							$mail_usuario = "jeronimo.garcia@okeefe.com.ar";
							break;
						default:
							$nom_usuario = "Inmobiliaria O'Keefe";
							$mail_usuario = "info@okeefe.com.ar";
							break;
	
					}
					$destino .= $mail_usuario . ',';
				}
				$destino = rtrim($destino, ',');
			}
			
			
		} else {
			$txtconsulta = "CONSULTA EMPRENDIMIENTO ";
			$nom_usuario = "Ignacio O'Keefe";
			$mail_usuario = "ignacio@okeefe.com.ar";
			$destino="ignacio@okeefe.com.ar";
		}

		if (empty($destino)) {
			$mail_usuario = "info@okeefe.com.ar";
			$destino = "info@okeefe.com.ar";
		}

		
		$insertSQL = sprintf("INSERT INTO consultas (nombre, apellido, telefono, email, consulta, categoria, destino, acceso) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['nombre'], "text"),
						   GetSQLValueString($_POST['apellido'], "text"),
						   GetSQLValueString($_POST['telefono'], "text"),
						   GetSQLValueString($_POST['email'], "text"),
						   GetSQLValueString($_POST['consulta'], "text"),
						   GetSQLValueString($txtconsulta.$_POST['id'], "text"),
						   GetSQLValueString($destino, "text"),
						   GetSQLValueString($_SESSION['ingreso'], "text"));
		
		mysql_select_db($database_config, $config);
		$Result1 = mysql_query($insertSQL, $config) or die(mysql_error());
		
		$de = $_POST['email'];
		$deNombre = $_POST['nombre'] . ' ' .$_POST['apellido'];
		
		
		$mensaje = $deNombre . "<br />";
		$mensaje .= $_POST['telefono'] . "<br />";
		$mensaje .= $de . "<br />";
		$mensaje .= $_POST['consulta'] . "<br />";
		$propList = explode(",", $_POST['id']);
		
		if($_POST['tipo'] != "empre"){
			for($i=0; $i < count($propList); $i++) {
				$mensaje .= "Consulta sobre ID ". $propList[$i] . "<br />";
			}
			
			$Subject = 'Consulta sobre la Propiedad ID'. $_POST['id'];
		}else{
			$mensaje .= "Consulta sobre Emprendimiento ID ". $propList[$i] . "<br />";
			$Subject = 'Consulta sobre la Emprendimiento ID'. $_POST['id'];
		}
		
		//$destino = "gustavo@zgroupsa.com.ar";
		
		//so we use the MD5 algorithm to generate a random hash
		$random_hash = md5(date('r', time()));
		//define the headers we want passed. Note that they are separated with \r\n
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$headers .= "To: " . $destino . "\r\n";
		$headers .= "From: ".$deNombre." <".$de.">\r\nReply-To: ".$deNombre." <".$de.">\r\n";
		//add boundary string and mime type specification
		//$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";
		
		
		if (mail($destino, $Subject, $mensaje, $headers)) {
			$mensaje = "";
			if ($_POST['tipo'] != "empre") {
				$mensaje .= '<table width="850" border="0" cellspacing="0" cellpadding="5" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">';
				$mensaje .= '  <tr><td colspan="3"><hr /></td></tr>';
				for ($i = 0; $i < count($propList); $i++) {
					$id_prop = $propList[$i];
					
					$param = array('id_prop'=>$id_prop);
					$prop = $client->call('Propiedad',$param,'');
					//print_r($prop);
			
					// BUSCA FOTOS ----------
					$param=array('id_prop'=>$id_prop);
					$fotos = $client->call('ListarFotosPropiedad',$param,'');
					//print_r($fotos);
					//-----------------------
					$paramU=array('id_ubica' => $prop['id_ubica'],
									'modo' => 'c');
					$loca = $client->call('detallaNombreZona',$paramU,'');
			
					if (is_null($prop['cantamb'])) {
						$ambientes = "-";
					} else {
						$ambientes = intval($prop['cantamb']);
					}
					if ($prop['suptot'] == "") {
						$superficie = "-";
					} else {
						$superficie = intval($prop['suptot']);
					}
					$moneda = $precio = "";
					if ($prop['publicaprecio'] == 0){
						$moneda = "";
						$precio = "Consulte";
					} else {
						if ($prop['operacion'] == "Venta") {
							if(is_null($prop['monven']) || $prop['monven'] == "Sin definir") {
								$moneda = "";
							} else {
								$moneda = $prop['monven'];
							}
							if (is_null($prop['valven']) || $prop['valven'] == 0) {
								$precio = "Consulte";
							} else {
								$precio = number_format($prop['valven'],0,",",".");
							}
						} else {
							if (is_null($prop['monalq']) || $prop['monalq'] == "Sin definir") {
								$moneda = "";
							} else {
								$moneda = $prop['monalq'];
							}
							if (is_null($prop['valalq']) || $prop['valalq'] == 0) {
								$precio = "Consulte";
							} else {
								$precio = number_format($prop['valalq'],0,",",".");;
							}
						}
					}
					foreach($tipoProp as $tipo){
						if ($tipo['id_tipo_prop'] == $prop['id_tipo_prop']){
							$txtTipo = $tipo['tipo_prop'];
							break;
						}
					}
			
			//--------------------------
					$mensaje .= '  <tr>';
					$mensaje .= '    <td width="160" height="107" rowspan="2" valign="top"><a href="http://www.okeefe.com.ar/' . strtolower(sanear_string($txtTipo)) . '_' . strtolower(sanear_string($prop['operacion'])) . '_' . strtolower(sanear_string(trim($loca))) . '_' . strtolower(sanear_string(substr(busca_valor($prop['id_prop'], 257, $carac), 0, 100))) . '_' . $id_prop . '" target="_blank">';
					if($fotos[0]['foto'] != "") { 
						$mensaje .='<img src="http://abm.okeefe.com.ar/fotos_th/'.$fotos[0]['foto'].'" width="160" height="107" border="0" alt="'. substr(busca_valor($prop['id_prop'], 257, $carac), 0, 100).'" />';
					}else { 
						$mensaje .='<img src="images/noDisponible.gif" width="160" height="107" alt="'. substr(busca_valor($prop['id_prop'], 257, $carac), 0, 100).'>" />';
					}
					$mensaje .='</a></td>';
					$mensaje .= '    <td colspan="2"><table width="665" border="0" cellspacing="0" cellpadding="0" style="font-size:13px; line-height:13px;">';
					$mensaje .= '        <tr>';
					$mensaje .= '          <td width="473" style="font-weight:bold;display:block;cursor:pointer;"><a href="http://www.okeefe.com.ar/' . strtolower(sanear_string($txtTipo)) . '_' . strtolower(sanear_string($prop['operacion'])) . '_' . strtolower(sanear_string(trim($loca))) . '_' . strtolower(sanear_string(substr(busca_valor($prop['id_prop'], 257, $carac), 0, 100))) . '_' . $id_prop . '" target="_blank" style="text-decoration:none; color:#008046;">'. substr(busca_valor($prop['id_prop'], 257, $carac), 0, 100).'</a></td>';
					$mensaje .= '          <td width="161">';
					$mensaje .='    <div style="border:thin solid #008046;border-radius: 5px;-ms-border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;-khtml-border-radius: 5px;width:90px;float:right;text-align:center;font-size:1.1em;">ID '.str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop.'</div>';
					$mensaje .= '			</td>';
					$mensaje .= '        </tr>';
					$mensaje .= '      </table></td>';
					$mensaje .= '  </tr>';
					$mensaje .= '  <tr>';
					$mensaje .= '    <td width="250" height="90" valign="top" style="line-height:14px;">';
					$mensaje .= '		<span style="font-weight:bold;">Ubicación</span> <span style="font-size: 12px;">'.substr($loca, 0, 45).'</span><br />';
					$mensaje .='<span style="font-weight:bold;">Superficie:</span> <span style="font-size: 12px;">';
					$mensaje .= busca_valor($prop['id_prop'], 198, $carac);
					if($prop['id_tipo_prop'] == 6 || $prop['id_tipo_prop'] == 16){
					   $mensaje .= "Ha.";
					}else{
					   $mensaje .= "m2";
					}
					
					switch ($prop['id_tipo_prop']){
					case 6;
					case 7;
					case 16:
						$buscar=303;
						$tituBuscar="Aptitud";
						$valor=busca_valor($prop['id_prop'], 303, $carac);
						break;
					default:
						$valor=$ambientes;
						$tituBuscar="Ambientes";
						break;
					}
					$mensaje .='     </span><br />';
					$mensaje .='     <span style="font-weight:bold;">'.$tituBuscar . ': </span><span style="font-size: 12px;">' . $valor.'</span><br />';
					$mensaje .='     <span style="font-weight:bold;">Categoría:</span> <span style="font-size: 12px;">'.$txtTipo.'</span><br />';
					$mensaje .='     <span style="font-weight:bold;">Estado:</span> <span style="font-size: 12px;">'.$prop['operacion'].'</span><br />';
					$mensaje .='     <span style="font-weight:bold;">Precio:</span> <span style="font-size: 12px;">'. $moneda . $precio.'</span>';
					$mensaje .= '		</td>';
					$mensaje .= '    <td width="400" valign="top">';
					$mensaje .='      <div style="height:71px; max-height:71px; overflow:hidden;"><span style="font-weight:bold;">Descripción:</span><br />';
					$mensaje .='        <span>';
					
					$desc = busca_valor($prop['id_prop'], 255, $carac);
					$MaxLENGTH = 250;
					if (strlen($desc) > $MaxLENGTH) {
						$desc = substr(strip_tags($desc), 0, strrpos(substr(strip_tags($desc), 0, $MaxLENGTH), " "));
						$desc .= '...';
					}
					$mensaje .= $desc;
					
					$mensaje .='         </span>';
					$mensaje .='         </div></a>';
					$mensaje .='       <div style="float:right;border-radius: 5px;-ms-border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;-khtml-border-radius: 5px;background-color: #008046;text-align:center;color:#FFF;padding:1px 0px;width:90px;"><a href="http://www.okeefe.com.ar/' . strtolower(sanear_string($txtTipo)) . '_' . strtolower(sanear_string($prop['operacion'])) . '_' . strtolower(sanear_string(trim($loca))) . '_' . strtolower(sanear_string(substr(busca_valor($prop['id_prop'], 257, $carac), 0, 100))) . '_' . $id_prop.'" target="_blank" style="color:#FFF; text-decoration:none" rel="nofollow">Más detalles</a></div>';
					$mensaje .='      </div>';
					$mensaje .= '	</td>';
					$mensaje .= '  </tr>';
					$mensaje .= '  <tr><td colspan="3"><hr /></td></tr>';
				 }

				 $mensaje .= '  <tr><td colspan="3"><img src="http://www.okeefe.com.ar/images/pie_general.gif" width="800" height="158" /></td></tr>';
				 $mensaje .='         </table>';
				  //echo $mensaje;
				  //die();
			//-------------------------------------------------
				$Subject = 'Consulta de propiedades en Inmobiliaria Okeefe';
			} else {
				$mensaje = "Consulta sobre el Emprendimiento: ID" . $_POST['id'];
				$Subject = 'Consulta de Emprendimientos en Inmobiliaria Okeefe';		
			}
			$para = $de;
			
			//so we use the MD5 algorithm to generate a random hash
			$random_hash = md5(date('r', time()));
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=UTF-8\r\n";
			$headers .= "To: " . $de . "\r\n";
			$headers .= "From: $nom_usuario <$mail_usuario>\r\nReply-To: $nom_usuario <$mail_usuario>\r\n";
			//add boundary string and mime type specification
			//$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";
			if (mail($para, $Subject, $mensaje, $headers)) {
				//mail( $para, $Subject, $mensaje, $headers );
//				header("location: mailEnviado.html");
?>
				<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
						<title>O'keefe Propiedades</title>
						<link href="css/okeefeVentanas.css" rel="stylesheet" type="text/css" />
						<script language="javascript" type="text/javascript">
							var StayAlive = 3;
							function KillMe(){
								setTimeout("parent.tb_remove()",StayAlive * 1000);
							}
						</script>
					</head>
					<body onLoad="KillMe();">
						<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
						  <tr>
							<td class="txt_verde" height="100" align="center" style="padding:10px; text-align: center;">Muchas gracias por contactarse con nosotros.<br>
A la brevedad ser&aacute; contactado por uno de nuestros asesores comerciales.</td>
						  </tr>
						</table>
						<!-- Google Code for Formulario Completo Conversion Page -->
						<script type="text/javascript">
							/* <![CDATA[ */
							var google_conversion_id = 933390723;
							var google_conversion_language = "en";
							var google_conversion_format = "3";
							var google_conversion_color = "ffffff";
							var google_conversion_label = "5IZfCMacmGIQg9OJvQM";
							var google_remarketing_only = false;
							/* ]]> */
						</script>
						<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
						</script>
						<noscript>
							<div style="display:inline;">
							<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/933390723/?label=5IZfCMacmGIQg9OJvQM&amp;guid=ON&amp;script=0"/>
							</div>
						</noscript>

						<!-- Facebook Conversion Code for Formulario Completo -->
						<script>(function() {
						  var _fbq = window._fbq || (window._fbq = []);
						  if (!_fbq.loaded) {
						    var fbds = document.createElement('script');
						    fbds.async = true;
						    fbds.src = '//connect.facebook.net/en_US/fbds.js';
						    var s = document.getElementsByTagName('script')[0];
						    s.parentNode.insertBefore(fbds, s);
						    _fbq.loaded = true;
						  }
						})();
						window._fbq = window._fbq || [];
						window._fbq.push(['track', '6038599761236', {'value':'0.00','currency':'USD'}]);
						</script>
						<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6038599761236&amp;cd[value]=0.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
						<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '957821560969509');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=957821560969509&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->


<!-- Google Code for Remarketing Tag -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 933390723;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/933390723/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
					</body>
				</html>
<?php
				
			}else{
				echo "Error al enviar";
				die();
			}
		}else{
			echo "Error al enviar";
			die();
		}
	}
}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="MSSmartTagsPreventParsing" content="true" />
<title>O'Keefe Propiedades</title>
<script language="javascript" src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="css/okeefeVentanas.css" rel="stylesheet" type="text/css" />
</head>
<body style="width: 800px; margin:10px;">
<table width="100%" border="0" cellspacing="10" cellpadding="0" bgcolor="#FFFFFF">
  <tr>
    <td><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="solicitar" name="solicitar">
        <table width="100%" border="0" cellspacing="5" cellpadding="0" bgcolor="#FFFFFF">
          <tr>
            <td class="txt_verde" width="50%"><span id="sprynombre">Nombre <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
              <input name="nombre" type="text" value="<?php echo $nombre;?>" style="width:92%; border:thin #CCC solid; height:18px;" />
              </span> *</td>
            <td rowspan="4" class="txt_verde" valign="top">Consulta<br />
              <textarea name="consulta" style="width:95%; height: 150px; border:thin #CCC solid;" ><?php echo $consulta;?></textarea></td>
          </tr>
          <tr>
            <td class="txt_verde"><span id="spryapellido">Apellido <span class="textfieldRequiredMsg">Un valor es requerido.</span><br />
              <input name="apellido" type="text" value="<?php echo $apellido;?>" style="width:92%; border:thin #CCC solid; height:18px;" />
              </span> *</td>
          </tr>
          <tr>
            <td class="txt_verde">Teléfono<br />
              <input name="telefono" type="text" value="<?php echo $telefono;?>" style="width:92%; border:thin #CCC solid; height:18px;" /></td>
          </tr>
          <tr>
            <td class="txt_verde"><span id="spryemail">E-mail <span class="textfieldRequiredMsg">Un valor es requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span><br />
              <input name="email" type="text" value="<?php echo $mail;?>" style="width:92%; border:thin #CCC solid; height:18px;" />
              </span> *</td>
          </tr>
          <tr>
            <td><div style="float:left; width:240px;">
                <div style="float:left; width:150px;"><img id="siimage" style="border: 1px solid #f26322; margin-right: 5px" src="inc/securimage_show.php?sid=<?php echo md5(uniqid()); ?>" alt="CAPTCHA" align="left" /></div>
                <div style="float:left; width:30px; margin-left:10px;">
                  <object type="application/x-shockwave-flash" data="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" height="20" width="20">
                    <param name="movie" value="inc/securimage_play.swf?bgcol=#ffffff&amp;icon_file=inc/images/audio_icon.png&amp;audio_file=inc/securimage_play.php" />
                  </object>
                  <br />
                  <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'inc/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="inc/images/refresh.png" alt="Reload Image" width="20" height="20" vspace="5" border="0" align="bottom" onclick="this.blur()" /></a></div>
                <div class="clearfix"></div>
              </div></td>
            <td><div><span class="tasacionTituForm">Ingrese el c&oacute;digo*:</span><br />
                <?php echo @$_SESSION['ctform']['captcha_error']; ?>
                <input type="text" name="captcha" size="12" maxlength="8" />
              </div></td>
          </tr>
          <tr>
            <td class="txt_verde" height="20">* Campos obligatorios </td>
            <td align="right" style="padding-right: 20px;"><input type="hidden" name="id" value="<?php echo $id; ?>" />
              <input type="hidden" name="tipo" value="<?php echo $tipoForm; ?>" />
              <input name="enviar" type="submit" class="submitBtn" value="Enviar" /></td>
          </tr>
        </table>
        <input type="hidden" name="contacto" value="1" />
      </form></td>
  </tr>
</table>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '957821560969509');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=957821560969509&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->


<!-- Google Code for Remarketing Tag -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 933390723;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/933390723/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Code for Consultas Conversion Page --> 

<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 985947123;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "xIUeCOWDyQUQ87eR1gM";
var google_conversion_value = 0;
/* ]]> */
</script> 
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;"> <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/985947123/?value=0&amp;label=xIUeCOWDyQUQ87eR1gM&amp;guid=ON&amp;script=0"/> </div>
</noscript>
<script type="text/javascript">
		<!--
		var sprytextfield1 = new Spry.Widget.ValidationTextField("sprynombre");
		var sprytextfield2 = new Spry.Widget.ValidationTextField("spryapellido");
		var sprytextfield3 = new Spry.Widget.ValidationTextField("spryemail", "email");
        //-->
   </script>
</body>
</html>
<?php
}
?>