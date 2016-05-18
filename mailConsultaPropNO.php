<?php require_once('Connections/config.php'); ?>
<?php
//print_r($_POST);
//die();
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

$lista_id_carac = "198,255,257,303";
//         $lista_id_carac = "42,255,257";
$param=array('inprop'=>$_POST["id"],'incarac'=>$lista_id_carac);
$carac = $client->call('DatosConjuntoPropiedades',$param,'');
//print_r($carac);

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

switch ($_POST['tipo']){
	case 6:
	case 7:
	case 16:
		$destino="dennis@okeefe.com.ar, felipe.atucha@okeefe.com.ar";
		break;
	case 1:
	case 9:
	case 3:
	case 17:
	case 18:
		$destino="tomas@okeefe.com.ar";
		break;
	case 2:
	case 11:
	case 15:
		$destino="tomas@okeefe.com.ar";
		break;
	default:
		$destino="info@okeefe.com.ar";
		break;
}

$insertSQL = sprintf("INSERT INTO consultas (nombre, apellido, telefono, email, consulta, categoria, destino, acceso) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
				   GetSQLValueString($_POST['nombre'], "text"),
				   GetSQLValueString($_POST['apellido'], "text"),
				   GetSQLValueString($_POST['telefono'], "text"),
				   GetSQLValueString($_POST['email'], "text"),
				   GetSQLValueString($_POST['consulta'], "text"),
				   GetSQLValueString("CONSULTA ".$_POST['id'], "text"),
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
for($i=0; $i < count($propList); $i++) {
	$mensaje .= "Consulta sobre ID ". $propList[$i] . "<br />";
}


/*
    for($i=0; $i < count($propList); $i++) {
        $id_prop = $propList[$i];
		
		$param=array('id_prop'=>$id_prop);
		$prop = $client->call('Propiedad',$param,'');
		//print_r($prop);

        // BUSCA FOTOS ----------
        $param=array('id_prop'=>$id_prop);
        $fotos = $client->call('ListarFotosPropiedad',$param,'');
        //print_r($fotos);
        //-----------------------
		$paramU=array('id_ubica' => $prop[$i]['id_ubica'],
						'modo' => 'c');
		$loca = $client->call('detallaNombreZona',$paramU,'');

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
		foreach($tipoProp as $tipo){
			if($tipo['id_tipo_prop'] == $prop[$i]['id_tipo_prop']){
				$txtTipo = $tipo['tipo_prop'];
				break;
			}
		}

//--------------------------
$mensaje .='<div style="margin-top:5px;font-size:.85em;border-bottom: thin solid #CCC;padding-bottom:5px;">
    <div style="float:left;width:160px;height:106px;text-align:center;" border="0"><a href="' . strtolower(sanear_string($txtTipo)) . '_' . strtolower(sanear_string($prop[$i]['operacion'])) . '_' . strtolower(sanear_string(trim($loca))) . '_' . strtolower(sanear_string(substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100))) . '_' . $id_prop . '">';
      if($fotos[0]['foto'] != "") { 
      		$mensaje .='<img src="http://abm.okeefe.com.ar/fotos_th/'.$fotos[0]['foto'].'" width="160" height="107" border="0" alt="'. substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100).'" />';
      }else { 
      		$mensaje .='<img src="images/noDisponible.gif" width="160" height="107" alt="'. substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100).'>" />';
      }
    $mensaje .='</a></div>';
    $mensaje .='<div id="contenidoResul" style="float:right; width:830px; height:107px; margin-left:5px;">';
    $mensaje .='  <div id="cabezaResul">';
    $mensaje .='    <div style="max-width:600px;float:left;color:#008046;font-size:1.1em;font-weight:bold;display:block;cursor:pointer;"><h2><a href="' . strtolower(sanear_string($txtTipo)) . '_' . strtolower(sanear_string($prop[$i]['operacion'])) . '_' . strtolower(sanear_string(trim($loca))) . '_' . strtolower(sanear_string(substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100))) . '_' . $id_prop . '" style="text-decoration:none; color:inherit;">'. substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100).'</a></h2></div>';
    $mensaje .='    <div style="border:thin solid #008046;border-radius: 5px;-ms-border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;-khtml-border-radius: 5px;width:90px;float:right;text-align:center;font-size:1.1em;">ID '.str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop.'</div>';
    $mensaje .='  </div>';
    $mensaje .='  <div id="cuerpoResul" style="width:830px; clear:both;">';
    $mensaje .='    <div style="float:left;width:320px;max-height:90px;height:90px;line-height:12px;"><span style="font-weight:bold;">Ubicación</span> <span style="font-size: 12px;line-height:normal;
">'.substr($loca, 0, 45).'</span><br />';
$mensaje .='<span style="font-weight:bold;">Superficie:</span> <span style="font-size: 12px;line-height:normal;
">';

		   $mensaje .= busca_valor($prop[$i]['id_prop'], 198, $carac);
		   if($prop[$i]['id_tipo_prop'] == 6 || $prop[$i]['id_tipo_prop'] == 16){
			   echo "Ha.";
		   }else{
			   echo "m2";
		   }
	
		  switch ($prop[$i]['id_tipo_prop']){
			case 6;
			case 7;
			case 16:
			  	$buscar=303;
				$tituBuscar="Aptitud";
				$valor=busca_valor($prop[$i]['id_prop'], 303, $carac);
				break;
			default:
			  	$valor=$ambientes;
				$tituBuscar="Ambientes";
				break;
		  }

     $mensaje .='     </span><br />';
     $mensaje .='     <span style="font-weight:bold;">'.$tituBuscar . ': </span><span style="font-size: 12px;line-height:normal;">' . $valor.'</span><br />';
     $mensaje .='     <span style="font-weight:bold;">Categoría:</span> <span style="font-size: 12px;line-height:normal;">'.$txtTipo.'</span><br />';
     $mensaje .='     <span style="font-weight:bold;">Estado:</span> <span style="font-size: 12px;line-height:normal;">'.$prop[$i]['operacion'].'</span><br />';
     $mensaje .='     <span style="font-weight:bold;">Precio:</span> <span style="font-size: 12px;line-height:normal;">'. $moneda . $precio.'</span>';
     $mensaje .='   </div>';
     $mensaje .='   <div style="float:left;width:400px;max-height:90px;height:90px;border-left: solid thin #CCC;padding-left:5px;margin-left:5px;"><a href="' . strtolower(sanear_string($txtTipo)) . '_' . strtolower(sanear_string($prop[$i]['operacion'])) . '_' . strtolower(sanear_string(trim($loca))) . '_' . strtolower(sanear_string(substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100))) . '_' . $id_prop.'">';
     $mensaje .='      <div style="height:71px; max-height:71px; overflow:hidden;"><span style="font-weight:bold;">Descripción:</span><br />';
     $mensaje .='        <span>';

            $desc = busca_valor($prop[$i]['id_prop'], 255, $carac);
            $MaxLENGTH = 250;
            if (strlen($desc) > $MaxLENGTH) {
                $desc = substr(strip_tags($desc), 0, strrpos(substr(strip_tags($desc), 0, $MaxLENGTH), " "));
                $desc .= '...';
            }
      $mensaje .= $desc;
     
	  $mensaje .='         </span>';
      $mensaje .='         </div></a>';
      $mensaje .='         <div>';
      $mensaje .='       <div style="float:right;border-radius: 5px;-ms-border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;-khtml-border-radius: 5px;background-color: #008046;text-align:center;color:#FFF;padding:1px 0px;width:90px;"><a href="' . strtolower(sanear_string($txtTipo)) . '_' . strtolower(sanear_string($prop[$i]['operacion'])) . '_' . strtolower(sanear_string(trim($loca))) . '_' . strtolower(sanear_string(substr(busca_valor($prop[$i]['id_prop'], 257, $carac), 0, 100))) . '_' . $id_prop.'" style="color:#FFF; text-decoration:none" rel="nofollow">Más detalles</a></div>';
      $mensaje .='      </div>';
          
      $mensaje .='      </div>';
      $mensaje .='</div>';
    $mensaje .='</div>';
    $mensaje .='<div style="visibility: hidden;display: block;font-size: 0;content: " ";clear: both;height: 0px;
"></div>';
  $mensaje .='</div>';
//----------------------------------


 	 }

echo $mensaje;
die();
*/

$Subject = 'Consulta sobre la Propiedad ID'. $_POST['id'];

$para = $destino;
//$para = "gustavo@zgroupsa.com.ar";

//so we use the MD5 algorithm to generate a random hash
$random_hash = md5(date('r', time()));
//define the headers we want passed. Note that they are separated with \r\n
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "To: " . $para . "\r\n";
$headers .= "From: ".$deNombre." <".$de.">\r\nReply-To: ".$deNombre." <".$de.">\r\n";
//add boundary string and mime type specification
//$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";


if(mail( $para, $Subject, $mensaje, $headers )){
	header("location:mailEnviado.html");
}else{
	echo "Error al enviar";
}
?>
