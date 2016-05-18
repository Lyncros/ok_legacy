<?php
header("content-type: application/x-javascript"); 

require_once('../Connections/config.php');
require_once('../lib-nusoap/nusoap.php');

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

//header('Content-type: text/html; charset=utf-8');

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
if(isset($_GET['d'])){
	$tipoDest = strip_tags($_GET['d']);
}else{
	$tipoDest=0;
}

$paramD = array('id_tipo_prop'=>$tipoDest, 'operacion'=>'');
$destacados = $client->call('listarDestacados',$paramD,'');
//print_r($destacados);
//print $tipoDest;
//die();
if(count($destacados) < 1){
	$paramD = array('id_tipo_prop'=>0, 'operacion'=>'');
	$destacados = $client->call('listarDestacados',$paramD,'');
}

$lista_id_prop="";
for($i=0; $i < count($destacados); $i++) {
	$lista_id_prop .= $destacados[$i]['id_prop'] . ",";
}
$lista_id_prop = substr($lista_id_prop, 0, -1);
$lista_id_carac = "198,255,257,303";
//$lista_id_carac = "42,255,257";
$param=array('inprop'=>$lista_id_prop,'incarac'=>$lista_id_carac);
$carac = $client->call('DatosConjuntoPropiedades',$param,'');




?>
var id_dest = new Array();
var titu_dest = new Array();
var foto_dest = new Array();
var desc_dest = new Array();
<?php
for($i=0; $i < count($destacados); $i++) {
	$id_prop = $destacados[$i]['id_prop'];
	// BUSCA FOTOS ----------
	$param=array('id_prop'=>$id_prop);
	$fotos = $client->call('ListarFotosPropiedad',$param,'');
	//print_r($fotos);
	//-----------------------
	$paramU=array('id_ubica' => $destacados[$i]['id_ubica'],'modo' => 'c');
	$loca = $client->call('detallaNombreZona',$paramU,'');
	foreach($tipoProp as $tipo){
		if($tipo['id_tipo_prop'] == $destacados[$i]['id_tipo_prop']){
			$txtTipo = $tipo['tipo_prop'];
			break;
		}
	}

	echo "id_dest[".$i."]=".$id_prop.";\n";
	echo "titu_dest[".$i."]='".$loca." - ".$txtTipo."';\n";
	echo "foto_dest[".$i."]='".$fotos[0]['foto']."'; \n";
	$desc = nl2br(busca_valor($destacados[$i]['id_prop'], 255, $carac));
	$MaxLENGTH = 80;
	if (strlen($desc) > $MaxLENGTH) {
		$desc = substr(strip_tags($desc), 0, strrpos(substr(strip_tags($desc), 0, $MaxLENGTH), " "));
		$desc .= '...';
	}

	echo "desc_dest[".$i."]='". trim(preg_replace( '/\s+/', ' ', $desc))."';\n";
}
?>			  
var contador = 0;
setInterval('mostrarDestacado()',10000);

function mostrarDestacado(){
	if(contador == <?php echo count($destacados) ?>){
		contador = 0;
	}

	document.getElementById('tituDestacado').innerHTML=titu_dest[contador];
	document.getElementById('txtDestacado').innerHTML='<a href="detalleProp.php?iddest='+id_dest[contador]+'">'+desc_dest[contador]+'</a>';
	document.getElementById('fotoDestacado').innerHTML='<a href="detalleProp.php?iddest='+id_dest[contador]+'"><img src="http://abm.okeefe.com.ar/fotos_th/'+foto_dest[contador]+'" width="235" /></a>';
	document.getElementById('vermasDestacado').innerHTML='<a href="detalleProp.php?iddest='+id_dest[contador]+'" rel="nofollow"><img src="images/vermashome.gif" width="53" height="11" border="0" />';
	contador++;
}
function sigDestacado(){
	//contador++;
	if(contador >= <?php echo count($destacados) ?>){
		contador = 0;
	}
	mostrarDestacado();
}
function antDestacado(){
	contador-=2;
	if(contador < 0){
		contador = <?php echo count($destacados) ?>;
	}
	//alert(contador);
	mostrarDestacado();
}