<?php
$id_prop = $_POST['id_prop'];

ob_start();
session_start();
include_once('clases/class.loginwebuserBSN.php');

$usrBSN = new LoginwebuserBSN();
$usrBSN->cargaById($_SESSION['UserId']);
$usrNombre = $usrBSN->getObjeto()->getNombre();
$usrApellido = $usrBSN->getObjeto()->getApellido();
$usrMail = $usrBSN->getObjeto()->getEmail();
$usrTel =  $usrBSN->getObjeto()->getTelefono();

header('Content-type: text/html; charset=utf-8');
include_once("./generic_class/class.cargaConfiguracion.php");

$conf = CargaConfiguracion::getInstance('');
//$anchoPagina=$conf->leeParametro("ancho_pagina");
$gmapkey = $conf->leeParametro("gmkey");

//-------GENERA PDF
require_once 'tcpdf/encpdf.php';

$acentos[0] = '/&aacute;/';
$acentos[1] = '/&eacute;/';
$acentos[2] = '/&iacute;/';
$acentos[3] = '/&oacute;/';
$acentos[4] = '/&uacute;/';
$acentos[5] = '/&ntilde;/';
$acentos[6] = '/&nbsp;/';
$acentos[7] = '/&Aacute;/';
$acentos[8] = '/&Eacute;/';
$acentos[9] = '/&Iacute;/';
$acentos[10] = '/&Oacute;/';
$acentos[11] = '/&Uacute;/';
$acentos[12] = '/&Ntilde;/';

$reemp[0] = 'Ã¡';
$reemp[1] = 'Ã©';
$reemp[2] = 'Ã­';
$reemp[3] = 'Ã³';
$reemp[4] = 'Ãº';
$reemp[5] = 'Ã±';
$reemp[6] = ' ';
$reemp[7] = 'Ã�';
$reemp[8] = 'Ã‰';
$reemp[9] = 'Ã�';
$reemp[10] = 'Ã“';
$reemp[11] = 'Ãš';
$reemp[12] = 'Ã‘';


//date_default_timezone_set('America/Argentina/Buenos_Aires');

function ListarLocalidad($id_zona) {
    require_once("clases/class.localidad.php");
    require_once("clases/class.localidadBSN.php");
    $loc = new Localidad();
    $loc->setId_zona($id_zona);
    $tpBSN = new LocalidadBSN($loc);
    $colec=$tpBSN->cargaColeccionByZona($id_zona);
    return $colec;
}

function Propiedad($id_prop) {
    require_once("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN();
    $prop->cargaById($id_prop);
    $colec=$prop->getObjetoView();
    return $colec;
}

function TipoPropiedad($id_tipo_prop) {
    require_once("clases/class.tipo_propBSN.php");
    $tipoprop = new Tipo_propBSN();
    $tipoprop->cargaById($id_tipo_prop);
    $colec=$tipoprop->getObjetoView();
    return $colec;
}

function ListarDatosPropiedad($id_prop) {
    require_once("clases/class.datospropBSN.php");
    $prop = new DatospropBSN();
    $colec=$prop->coleccionCaracteristicasProp($id_prop);
    return $colec;
}

function ListarFotosPropiedad($id_prop) {
    require_once("clases/class.fotoBSN.php");
//require_once("generic_class/class.cargaConfiguracion.php");
    $conf=CargaConfiguracion::getInstance('');
    $path=$conf->leeParametro('path_fotos');
    $foto = new FotoBSN();
    $colec=$foto->cargaColeccionFormByPropiedad($id_prop);
    return $colec;
}

$prop = Propiedad($id_prop);
//print_r($prop);
//die();

$tipo_prop = TipoPropiedad($prop['id_tipo_prop']);
//print_r($tipo_prop);
//die();

$loca = ListarLocalidad($prop['id_zona']);
//print_r($loca);

$carac = ListarDatosPropiedad($id_prop);
//print_r($carac);

$fotos = ListarFotosPropiedad($id_prop);
//print_r($fotos);
//die();

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

foreach ( $loca as $assoc_array ) {
    if ( $assoc_array['id_loca'] == $prop['id_loca'] ) {
        $barrio = $assoc_array['nombre_loca'];
        break;
    }
}

$orientacion = "P";
$unidad = "mm";
$formato = "A4";

// create new PDF document
$pdf = new PDF($orientacion, $unidad, $formato, true, 'UTF-8', false);

// set document information
$pdf->SetCreator("O'Keefe Propiedades");
$pdf->SetAuthor('O'Keefe Propiedades');
$pdf->SetTitle('O'Keefe Propiedades');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 009', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(15, 20, 10);
$pdf->SetLeftMargin(15);
$pdf->SetHeaderMargin(20);
$pdf->SetFooterMargin(20);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

//set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
//$pdf->setLanguageArray($l);

switch($prop['id_sucursal']){
    case 'ACH':
        $direccion = 'Av. Callao 1515 2Âº piso - C.A.B.A.';
        break;
    case 'MAD':
        $direccion = 'Av. Alicia M. de Justo 750 Dock 5 - C.A.B.A.';
        break;
    case 'NOR':
        $direccion = 'Av. de Los Lagos 6855 local 9 â€“ Nordelta';
        break;
    case 'VLP':
        $direccion = 'Av. del Libertador 1680 â€“ Vte Lopez';
        break;
    case 'BAR':
        $direccion = 'Libertad 299 3ÂºB - San Carlos de Bariloche ';
        break;
    default:
        $direccion = 'Av. Callao 1515 2Âº piso - C.A.B.A.';
        break;        
}
$nombre = $usrNombre . ' ' . $usrApellido;
$pdf->setNombre($nombre);
$pdf->setMail($usrMail);
$pdf->setTelefono($usrTel);
$pdf->setDireccion($direccion);

// -------------------------------------------------------------------

// add a page
$pdf->AddPage();

// set JPEG quality
$pdf->setJPEGQuality(100);

// Image method signature:
// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)






$pdf->SetX(8);
$pdf->SetFont('helvetica','',10);

/*
$pdf->Titulo($prop['calle'] . " ". $prop['nro']);
$pdf->Titulo($barrio);
$pdf->Titulo("CÃ³digo: ". $prop['id_sucursal'] . str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop);
*/
$y_inicio=$pdf->GetY();
//$txt = $prop['calle'] . " ". $prop['nro'] ."\n" . $barrio;
$txt = substr(busca_valor(257, $carac), 0, 50) ."\n" . $barrio;
$pdf->SetFillColor(255);
$pdf->SetTextColor(0, 121, 194);
$pdf->MultiCell(95, 0.5, $txt, 0, 'L', false);

$txt = $tipo_prop['tipo_prop'] ."\n" . $prop['operacion'];
$pdf->MultiCell(85, 0.5, $txt, 0, 'L', false, 0, 115, $y_inicio);
$txt = $prop['id_sucursal'] . str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;
$pdf->MultiCell(85, 0.5, $txt, 0, 'R', false, 0, 115, $y_inicio);

$img0 = 'fotos/' . $fotos[0]['hfoto'];
if(file_exists($img0)){
    $dim_foto = getimagesize($img0);
    switch ($dim_foto['mime']) {
        case 'image/jpeg':
            $tipo = 'JPG';
            break;
        case 'image/gif':
            $tipo = 'GIF';
            break;
    }
}else{
    $img0 = 'images/noDisponible.gif';
    $tipo = 'GIF';
}
$pdf->Image($img0, 10, 30 , 100, 65, $tipo,'');

$pdf->Ln();
//$pdf->TextoB(busca_valor(257, $carac));
if($prop['operacion'] == "Alquiler") {
    $moneda = busca_valor(166, $carac);
    $precio = busca_valor(164, $carac);
}else {
    $moneda = busca_valor(165, $carac);
    $precio = busca_valor(161, $carac);
}

//$fdo_gris = array('width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'phase' => 0, 'color' => array(51, 51, 51));

//$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(51, 51, 51)));

$pdf->SetFillColor(245,245,245);
$pdf->SetTextColor(60,60,60);
$pdf->SetDrawColor(255,255,255);
//        $pdf->SetLineWidth(0);
$pdf->RoundedRect(115, 30, 85, 13, 2, '1111', 'DF');
$pdf->SetXY(116,34);
$pdf->TextoB("Precio: ". $moneda . " " .  number_format($precio,0,",","."));
$pdf->SetXY(161,34);
$pdf->SetFont('helvetica','B',9);
$texto = "Sup. " . busca_valor(198, $carac) . "m2";
$pdf->Cell(34,5,$texto,'',0,'R',1);

$pdf->SetFont('helvetica','',9);
//$pdf->TextoB("DescripciÃ³n: ");

$pdf->MultiCell(85, 0.5, "DescripciÃ³n:", 0, 'L', false, 0, 115, 45);
$pdf->SetTextColor(60,60,60);
$pdf->MultiCell(85, 0.5, substr(busca_valor(255, $carac),0,480), 0,'L', false, 0, 115, 49);
//$pdf->Ln();
//$pdf->Ln();

$pdf->Image('images/flechita_pdf.gif',116, 100.6, 3, 3,'GIF','');
$pdf->SetTextColor(49,107,178);
$pdf->SetFont('helvetica','B',9);
//$pdf->SetX(7);
//$pdf->Cell(8);
$pdf->MultiCell(85, 0.5, 'UbicaciÃ³n', 0, 'L', false, 1, 120, 100);
$pdf->Image('http://maps.google.com/maps/api/staticmap?center=' .  $prop['goglat'] . "," . $prop['goglong'] . '&zoom=14&size=220x220&markers=color:blue|label:A|' .  $prop['goglat'] . "," . $prop['goglong'] . '&maptype=roadmap&sensor=false', 120, $pdf->GetY(), 80, 80, 'PNG', '');

if($prop['plano1'] != "" && file_exists('fotos/' . $prop['plano1'])) {
//    $pdf->Image('F:\\inetpub\\wwwroot\\abm\\images\\flechita_pdf.gif',116, 190.6, 3, 3,'GIF','');
//    $pdf->SetTextColor(49,107,178);
//    $pdf->SetFont('helvetica','B',9);
//$pdf->SetX(7);
//$pdf->Cell(8);
//    $pdf->MultiCell(85, 0.5, 'Plano', 0, 'L', false, 1, 120, 190);
    $pdf->Image('fotos/' . $prop['plano1'], 120, 190, 80, 80, 'jpg', '');
}

$pdf->SetXY(10, 100);
$pdf->SubTitulo("Detalles de la Propiedad");
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 5) {
        if($carac[$j]['contenido'] != "") {
            $pdf->Linea($carac[$j]['titulo'],$carac[$j]['contenido']);
        }
    }
}
//$pdf->Ln();
//$pdf->Ln();

//$pdf->SetX(8);
//$pdf->SubTitulo("Detalles de la Propiedad");
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 7) {
        if($carac[$j]['contenido'] != "" || $carac[$j]['contenido'] != "off") {
            $pdf->Linea($carac[$j]['titulo'],$carac[$j]['contenido']);
        }
    }
}
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 8) {
        if($carac[$j]['contenido'] != "") {
            $pdf->Linea($carac[$j]['titulo'],$carac[$j]['contenido']);
        }
    }
}
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 9) {
        if($carac[$j]['contenido'] != "") {
            $pdf->Linea($carac[$j]['titulo'],$carac[$j]['contenido']);
        }
    }
}
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 10) {
        if($carac[$j]['contenido'] != "") {
            $pdf->Linea($carac[$j]['titulo'],$carac[$j]['contenido']);
        }
    }
}
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 11) {
        if($carac[$j]['contenido'] != "") {
            $pdf->Linea($carac[$j]['titulo'],$carac[$j]['contenido']);
        }
    }
}
//$pdf->Ln();

//$pdf->SubTitulo("Caracteristicas del edificio");
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 12) {
        if($carac[$j]['contenido'] != "") {
            $pdf->Linea($carac[$j]['titulo'],$carac[$j]['contenido']);
        }
    }
}
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 13) {
        if($carac[$j]['contenido'] != "") {
            $pdf->Linea($carac[$j]['titulo'],$carac[$j]['contenido']);
        }
    }
}
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 14) {
        if($carac[$j]['contenido'] != "") {
            $pdf->Linea($carac[$j]['titulo'],$carac[$j]['contenido']);
        }
    }
}

if(count($fotos) > 0) {
    $pdf->AddPage();

    $inicio = 1;
    $salto_alto = 60;
//$contador = 0;
    if(count($fotos) > 6){
         $cantFotos = 6;
    }else{
         $cantFotos  = count($fotos);
    }

    for($i=0; $i<$cantFotos; $i++) {
      if(file_exists("fotos/" . $fotos[$i]['hfoto'])){
        $dim_foto = getimagesize('http://www.zgroupsa.com.ar/okeefe/fotos/' . $fotos[$i]['hfoto']);
        $ancho_cm = $dim_foto[0] / 37.795275591;
        $coef = 90 / $ancho_cm;
        $alto_cm = $dim_foto[1] / 37.795275591;
        if($inicio == 1) {
            $x = 10;
            $y = $pdf->GetY();
            $inicio++;
        }else {
            $x = 110;
            $nuevo_y = $y+($alto_cm * $coef) + 5;
            $pdf->SetY($nuevo_y);
            $inicio = 1;
//		$contador++;
        }
        switch ($dim_foto['mime']) {
            case 'image/jpeg':
                $tipo = 'JPG';
                break;
            case 'image/gif':
                $tipo = 'GIF';
                break;
        }
        $pdf->Image('http://abm.achavalcornejo.com/fotos/'.$fotos[$i]['hfoto'], $x, $y , 90, ($alto_cm * $coef),$tipo,'');
      }
    }
}
//$pdf->AliasNbPages();
$nombre = $prop['id_sucursal'] . str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop.'.pdf';
ob_end_flush();
//$pdf->Output($nombre,'I'); //download pdf
$pdf->Output($nombre,'F'); //graba pdf

//-------FIN PDF

require("inc/class.phpmailer.php");

$mail = new PHPMailer();

$mail->IsSMTP();   						// set mailer to use SMTP
//$mail->Host = "smtp.achavalcornejo.com";			// specify main and backup server
$mail->Host = "ns2.zgroupsa.com.ar";			// specify main and backup server
$mail->SMTPAuth = true; 					// turn on SMTP authentication
//$mail->Username = "abm_admin@achavalcornejo.com"; 		// SMTP username
$mail->Username = "zgroupsa.achaval"; 		// SMTP username
//$mail->Password = "ABMadmin";                                   // SMTP password
$mail->Password = "achaval";                                   // SMTP password

$mail->From = $_POST['desde'];
$mail->FromName = $usrNombre . ' ' .$usrApellido;

$mail->AddAddress($_POST['desde']);

$para = explode(';', $_POST['email']);    
if(count($para) > 1){
    for($i=0; $i <= count($para); $i++){
        $mail->AddAddress($para[$i]);
    }
}else{
    $mail->AddAddress($_POST['email']);
}
    
//	$mail->AddAddress("ellen@example.com");                  // name is optional
//	$mail->AddReplyTo("info@example.com", "Information");

//	$mail->WordWrap = 50;                                 	// set word wrap to 50 characters
/*
if($_POST['adjunto'] != ''){
    foreach ($_FILES as $vAdjunto) {
        if ($vAdjunto["size"] > 0) {

            $oFichero = fopen($vAdjunto["tmp_name"], 'r');
            $sContenido = fread($oFichero, filesize($vAdjunto["tmp_name"]));
            $sAdjuntos .= chunk_split(base64_encode($sContenido));
            fclose($oFichero);
            
            $mail->AddAttachment("tmp/" . $oFichero, $oFichero);         	// add attachments
        }
    }
}
*/

$mail->AddAttachment($nombre, $nombre);    	// optional name
$mail->IsHTML(true);                                 	 // set email format to HTML

$mail->Subject = $_POST['asunto'];

if($_POST['comentario'] == ""){
  $body = "Muchas gracias por contactarnos.\nAdjunto la ficha de referencia.\nO'Keefe";
}else{
  $body = $_POST['comentario'];
}

$mail->Body = $body;
//	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";


if(!$mail->Send()){
   echo "Message could not be sent.";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	        <head>
	        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	        <title>O'Keefe - Nordelta</title>
	        <link href="css/achaval.css" rel="stylesheet" type="text/css" />
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
                <td class="txt_verde" height="100" align="center" style="padding:10px;">Se ha enviado el mail a <b><?php echo $_POST['email']; ?></b> con toda la informaci&oacute;n</td>
              </tr>
            </table>
</body>
</html>
<?php
}
?>