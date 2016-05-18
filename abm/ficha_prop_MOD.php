<?php
ob_start();
session_start();
include_once('clases/class.loginWebUserBSN.php');

$usrBSN = new LoginWebUserBSN();
$usrBSN->cargaById($_SESSION['UserId']);
$usrNombre = $usrBSN->getObjeto()->getNombre();
$usrApellido = $usrBSN->getObjeto()->getApellido();

header('Content-type: text/html; charset=utf-8');
include_once("./generic_class/class.cargaConfiguracion.php");

$conf = new CargaConfiguracion();
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

$reemp[0] = 'á';
$reemp[1] = 'é';
$reemp[2] = 'í';
$reemp[3] = 'ó';
$reemp[4] = 'ú';
$reemp[5] = 'ñ';
$reemp[6] = ' ';
$reemp[7] = 'Á';
$reemp[8] = 'É';
$reemp[9] = 'Í';
$reemp[10] = 'Ó';
$reemp[11] = 'Ú';
$reemp[12] = 'Ñ';


//date_default_timezone_set('America/Argentina/Buenos_Aires');

if(isset($_POST['id_prop'])) {
    $id_prop = $_POST['id_prop'];
}else {
    $id_prop = $_GET['id'];
}

function ListarLocalidad($id_zona){
	require_once("clases/class.localidad.php");
	require_once("clases/class.localidadBSN.php");
	$loc = new Localidad();
	$loc->setId_zona($id_zona);
	$tpBSN = new LocalidadBSN($loc);
	$colec=$tpBSN->cargaColeccionByZona($id_zona);
	return $colec;
}

function Propiedad($id_prop){
	require_once("clases/class.propiedadBSN.php");
	$prop = new PropiedadBSN();
	$prop->cargaById($id_prop);
	$colec=$prop->getObjetoView();
	return $colec;
}

function ListarTipoProp($tipo){
	require_once ("clases/class.tipo_propBSN.php");
	$tipo_prop = new Tipo_propBSN($tipo);
	$colec=$tipo_prop->getObjetoView();
	return $colec;
}

function ListarDatosPropiedad($id_prop){
	require_once("clases/class.datospropBSN.php");
	$prop = new DatospropBSN();
	$colec=$prop->coleccionCaracteristicasProp($id_prop);
	return $colec;
}

function ListarFotosPropiedad($id_prop){
	require_once("clases/class.fotoBSN.php");
//	require_once("generic_class/class.cargaConfiguracion.php");
	$conf=new CargaConfiguracion();
	$path=$conf->leeParametro('path_fotos');
	$foto = new FotoBSN();
	$colec=$foto->cargaColeccionFormByPropiedad($id_prop);
	return $colec;
}

$prop = Propiedad($id_prop);
//print_r($prop);

$loca = ListarLocalidad($prop['id_zona']);
//print_r($loca);

$carac = ListarDatosPropiedad($id_prop);
//print_r($carac);

$fotos = ListarFotosPropiedad($id_prop);
//print_r($fotos);
//die();

$tipoprop=ListarTipoProp($prop['id_tipo_prop']);

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
$pdf->SetCreator("Achaval Cornejo Propiedades");
$pdf->SetAuthor('Achaval Cornejo Propiedades');
$pdf->SetTitle('Achaval Cornejo Propiedades');
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

// -------------------------------------------------------------------

// add a page
$pdf->AddPage();

// set JPEG quality
$pdf->setJPEGQuality(80);

// Image method signature:
// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)






$pdf->SetX(8);

//$pdf->Titulo("Barrio: ". $barrio . " - Código: ". $prop['id_sucursal'] . str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop);
$pdf->Titulo2(busca_valor(257, $carac)." \n ". $barrio , $tipoprop['tipo_prop']." - ".$prop['operacion'],$prop['id_sucursal'] . str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop);
//$pdf->Ln();
//$pdf->TextoB(busca_valor(257, $carac));


$precio=busca_valor(165, $carac) . " " .  number_format(busca_valor(161, $carac),0,",",".");

$sup=busca_valor(5,$carac);
/*
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 5) {
        if($carac[$j]['contenido'] != "") {
            $sup=$carac[$j]['contenido']);
        }else{
            $sup='';
        }
    }
}
  */
$descripcion=busca_valor(255, $carac);
  
$fotopre='http://abm.achavalcornejo.com/fotos/'.$fotos[0]['hfoto'];  
  
$pdf->BloqueFotoDesc($fotopre,$precio,$sup,$descripcion);

$pdf->TextoB("Precio: ". busca_valor(165, $carac) . " " .  number_format(busca_valor(161, $carac),0,",","."));
$pdf->TextoB("Descripción: ");
$pdf->SetFont('helvetica','',10);
$pdf->SetTextColor(60,60,60);
$pdf->Write(5,nl2br(busca_valor(255, $carac)),'');
$pdf->Ln();
$pdf->Ln();

$pdf->Image('http://maps.google.com/maps/api/staticmap?center=' .  $prop['goglat'] . "," . $prop['goglong'] . '&zoom=14&size=220x425&markers=color:blue|label:A|' .  $prop['goglat'] . "," . $prop['goglong'] . '&maptype=roadmap&sensor=false', 120, $pdf->GetY(), 77, 150, 'PNG', '');

$pdf->SubTitulo("Detalles de la Propiedad");
for($j = 0; $j < count($carac); $j++) {
    if($carac[$j]['id_prop_carac'] == 5) {
        if($carac[$j]['contenido'] != "") {
            $pdf->Linea($carac[$j]['titulo'],$carac[$j]['contenido']);
        }
    }
}
$pdf->Ln();
$pdf->Ln();

$pdf->SetX(8);
$pdf->SubTitulo("Detalles de la Propiedad");
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
$pdf->Ln();

$pdf->SubTitulo("Caracteristicas del edificio");
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
$pdf->AddPage();

$inicio = 1;
$salto_alto = 60;
//$contador = 0;

for($i=0; $i<count($fotos); $i++){
	$dim_foto = getimagesize('http://abm.achavalcornejo.com/fotos/' . $fotos[$i]['hfoto']);
	$ancho_cm = $dim_foto[0] / 37.795275591;
	$coef = 90 / $ancho_cm;
	$alto_cm = $dim_foto[1] / 37.795275591;
	if($inicio == 1){
		$x = 10;
		$y = $pdf->GetY();
		$inicio++;
	}else{
		$x = 110;
		$nuevo_y = $y+($alto_cm * $coef) + 5;
		$pdf->SetY($nuevo_y);
		$inicio = 1;
//		$contador++;
	}
	switch ($dim_foto['mime']){
		case 'image/jpeg':
			$tipo = 'JPG';
			break;
		case 'image/gif':
			$tipo = 'GIF';
			break;
	}
	$pdf->Image('http://abm.achavalcornejo.com/fotos/'.$fotos[$i]['hfoto'], $x, $y , 90, ($alto_cm * $coef),$tipo,'');
	if($nuevo_y > 200){
		$pdf->AddPage();
		$inicio = 1;
		$nuevo_y = 0;
	}
}

$pdf->AliasNbPages();
$nombre = $prop['id_sucursal'] . str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop.'.pdf';
ob_end_flush();
$pdf->Output($nombre,'I'); //download pdf
//$pdf->Output($nombre,'F'); //graba pdf

//-------FIN PDF

?>
