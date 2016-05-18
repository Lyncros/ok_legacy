<?php

ob_start();
session_start();
include_once ('clases/class.loginwebuserBSN.php');
include_once 'clases/class.caracteristicaBSN.php';
include_once ("clases/class.tipo_caracBSN.php");
include_once ("clases/class.auxiliaresPGDAO.php");
include_once("clases/class.relacion.php");
include_once ("clases/class.clienteBSN.php");
include_once ("clases/class.domicilioBSN.php");
include_once ("clases/class.telefonosBSN.php");



$usrBSN = new LoginwebuserBSN ();
$usrBSN->cargaById($_SESSION ['UserId']);
$usrNombre = $usrBSN->getObjeto()->getNombre();
$usrApellido = $usrBSN->getObjeto()->getApellido();
$usrMail = $usrBSN->getObjeto()->getEmail();
$usrTel = $usrBSN->getObjeto()->getTelefono();

header('Content-type: text/html; charset=utf-8');
include_once ("./generic_class/class.cargaConfiguracion.php");

$conf = CargaConfiguracion::getInstance('');
//$anchoPagina=$conf->leeParametro("ancho_pagina");
$gmapkey = $conf->leeParametro("gmkey");

//-------GENERA PDF
require_once 'tcpdf/encpdf.php';

$acentos [0] = '/&aacute;/';
$acentos [1] = '/&eacute;/';
$acentos [2] = '/&iacute;/';
$acentos [3] = '/&oacute;/';
$acentos [4] = '/&uacute;/';
$acentos [5] = '/&ntilde;/';
$acentos [6] = '/&nbsp;/';
$acentos [7] = '/&Aacute;/';
$acentos [8] = '/&Eacute;/';
$acentos [9] = '/&Iacute;/';
$acentos [10] = '/&Oacute;/';
$acentos [11] = '/&Uacute;/';
$acentos [12] = '/&Ntilde;/';

$reemp [0] = 'á';
$reemp [1] = 'é';
$reemp [2] = 'í';
$reemp [3] = 'ó';
$reemp [4] = 'ú';
$reemp [5] = 'ñ';
$reemp [6] = ' ';
$reemp [7] = 'Á';
$reemp [8] = 'É';
$reemp [9] = 'Í';
$reemp [10] = 'Ó';
$reemp [11] = 'Ú';
$reemp [12] = 'Ñ';

//date_default_timezone_set('America/Argentina/Buenos_Aires');


if (isset($_POST ['id_prop'])) {
    $id_prop = $_POST ['id_prop'];
} else {
    $id_prop = $_GET ['id'];
}

function Propiedad($id_prop) {
    require_once ("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN ();
    $prop->cargaById($id_prop);
    $colec = $prop->getObjetoView();
    return $colec;
}

function Ubicacion($id_ubica) {
    require_once ("clases/class.ubicacionpropiedadBSN.php");
    $ubiBSN = new UbicacionpropiedadBSN ();
    $nombre = $ubiBSN->armaNombreZonaAbr($id_ubica);
    return $nombre;
}

function TipoPropiedad($id_tipo_prop) {
    require_once ("clases/class.tipo_propBSN.php");
    $tipoprop = new Tipo_propBSN ();
    $tipoprop->cargaById($id_tipo_prop);
    $colec = $tipoprop->getObjetoView();
    return $colec;
}

function ListarTipoCaracteristicas($id_tipo_prop) {
    $auxiliar = new AuxiliaresPGDAO ();
    return $auxiliar->coleccionTipopropCaracTasacion($id_tipo_prop);
    /* 	
      require_once("clases/class.tipo_caracBSN.php");
      $prop = new Tipo_caracBSN();
      $colec=$prop->cargaColeccion();
      return $colec;
     */
}

function ListarCaracteristicasTasacion($id_tipo) {
    //require_once("clases/class.caracteristicaBSN.php");
    $prop = new CaracteristicaBSN ();
    $colec = $prop->cargaColeccionTasacion($id_tipo);
    return $colec;
}

$relBSN = new RelacionBSN();
$colecRel = $relBSN->coleccionRelaciones(0, $id_prop, 0);
//print_r($colecRel);
//die();

$datosRelBSN = new ClienteBSN();
$datosRel = $datosRelBSN->buscaDetalleCliente($colecRel[0]['id_pc']);
//print_r($datosRel);
//die();

$dirRelBSN = new DomicilioBSN();
$dirRel = $dirRelBSN->coleccionByCliente($colecRel[0]['id_pc']);
//print_r($dirRel);
//die();

$telRelBSN = new TelefonosBSN();
$telRel = $telRelBSN->coleccionByCliente($colecRel[0]['id_pc']);
//print_r($telRel);
//die();


$prop = Propiedad($id_prop);
//print_r($prop);
//die();


$ubica = Ubicacion($prop ['id_ubica']);
//echo($ubica);
//die();


$tipo_prop = TipoPropiedad($prop ['id_tipo_prop']);
//print_r($tipo_prop);
//die();


$tipo_carac = ListarTipoCaracteristicas($prop ['id_tipo_prop']);

//print_r($tipo_carac);
//die();


function busca_valor($id_carac, $arreglo) {
    for ($j = 0; $j < count($arreglo); $j++) {
        if ($arreglo [$j] ['id_carac'] == $id_carac) {
            if ($arreglo [$j] ['contenido'] == "") {
                $valor = "-";
            } else {
                $valor = $arreglo [$j] ['contenido'];
            }
            return $valor;
            break;
        }
    }
}

foreach ($loca as $assoc_array) {
    if ($assoc_array ['id_loca'] == $prop ['id_loca']) {
        $barrio = $assoc_array ['nombre_loca'];
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
$pdf->SetAuthor("O'Keefe Propiedades");
$pdf->SetTitle("O'Keefe Propiedades");
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
$pdf->SetMargins(15, 25, 10);
$pdf->SetLeftMargin(15);
$pdf->SetHeaderMargin(25);
$pdf->SetFooterMargin(15);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

//set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//set some language-dependent strings
//$pdf->setLanguageArray($l);
switch ($prop ['id_sucursal']) {
    case 'ACH' :
        $direccion = 'Av. Callao 1515 2º piso - C.A.B.A.';
        break;
    case 'MAD' :
        $direccion = 'Av. Alicia M. de Justo 750 Dock 5 - C.A.B.A.';
        break;
    case 'NOR' :
        $direccion = 'Av. de Los Lagos 6855 local 9 â€“ Nordelta';
        break;
    case 'VLP' :
        $direccion = 'Av. del Libertador 1680 â€“ Vte Lopez';
        break;
    case 'BAR' :
        $direccion = 'Libertad 299 3ÂºB - San Carlos de Bariloche ';
        break;
    default :
        $direccion = 'Mitre 491 - Quilmes  - Bs.As.';
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
$pdf->SetFont('helvetica', '', 9);

$direccionProp = $prop ['calle'] . ' ' . $prop ['nro'];
if ($prop ['piso'] != '') {
    $direccionProp .= $prop ['piso'];
}
if ($prop ['dpto'] != '') {
    $direccionProp .= $prop ['dpto'];
}
if ($prop ['entre1'] != '') {
    $direccionProp .= '<br />' . $prop ['entre1'] . ' y ' . $prop ['entre2'];
}
if ($prop ['cantamb'] != 0) {
    $ambientes = $prop ['cantamb'];
} else {
    $ambientes = '';
}

$cabezal = '<table border="1" cellpadding="2" cellspacing="0">';
$cabezal .= '<tr style="text-align:center;"><td width="100">' . $tipo_prop ['tipo_prop'] . '</td><td rowspan="2" width="340">' . $direccionProp . '</td><td width="100">' . date("d/m/Y") . '</td></tr>';
$cabezal .= '<tr style="text-align:center;"><td>' . $ambientes . ' amb</td><td>ID ' . str_repeat("0", 5 - strlen(strval($id_prop))) . $id_prop . '</td></tr>';
$cabezal .= '</table>';
$pdf->writeHTML($cabezal, true, false, true, true, '');

/*
  $pdf->Titulo($prop['calle'] . " ". $prop['nro']);
  $pdf->Titulo($barrio);
  $pdf->Titulo("CÃ³digo: ". $prop['id_sucursal'] . str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop);
 */
$y_inicio = $pdf->GetY() + 5;
//$txt = $prop['calle'] . " ". $prop['nro'] ."\n" . $barrio;
//$txt = substr(busca_valor(257, $carac), 0, 50) ."\n" . $barrio;
//$pdf->SetFillColor(255);
//$pdf->SetTextColor(0,85,45);
//$pdf->MultiCell(95, 0.5, $txt, 0, 'L', false);
//$txt = $tipo_prop['tipo_prop'] ."\n" . $prop['operacion'];
//$pdf->MultiCell(85, 0.5, $txt, 0, 'L', false, 0, 115, $y_inicio);
//$txt = str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop;
//$pdf->MultiCell(85, 0.5, $txt, 0, 'R', false, 0, 115, $y_inicio);
//$pdf->MultiCell(85, 0.5, 'Ubicaci�n', 0, 'L', false, 1, 120, 100);
//$pdf->Image ( 'http://maps.google.com/maps/api/staticmap?center=' . $prop ['goglat'] . "," . $prop ['goglong'] . '&zoom=14&size=275x179&markers=color:blue|label:A|' . $prop ['goglat'] . "," . $prop ['goglong'] . '&maptype=roadmap&sensor=false', 10, $y_inicio, 92, 65, 'PNG', '' );

$pdf->Ln();

//$fdo_gris = array('width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'phase' => 0, 'color' => array(51, 51, 51));
//$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(51, 51, 51)));


$pdf->SetFillColor(245, 245, 245);
$pdf->SetTextColor(60, 60, 60);
$pdf->SetDrawColor(255, 255, 255);
//        $pdf->SetLineWidth(0);
$pdf->RoundedRect(9, $y_inicio, 93, 55, 2, '1111', 'DF');
//$pdf->SetXY(116,34);
//$pdf->TextoB("Precio: ");
//$pdf->SetXY(161,34);
//$pdf->SetFont('helvetica','B',9);
//$texto = "Sup.            m2";
//$pdf->Cell(34,5,$texto,'',0,'R',1);


$pdf->SetFont('helvetica', 'B', 9);
$pdf->MultiCell(85, 0.5, "Comentarios:", 0, 'L', false, 1, 11, ($y_inicio + 0.5));
$pdf->SetTextColor(60, 60, 60);
$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(60, 60, 60);
$pdf->MultiCell(85, 0.5, $prop['comentario'], 0, 'L', false, 1, 11, $pdf->GetY());
//$pdf->Ln();
//$pdf->Ln();

$pdf->RoundedRect(107, $y_inicio, 93, 55, 2, '1111', 'DF');

$pdf->SetFont('helvetica', 'B', 9);
$pdf->MultiCell(85, 0.5, "Datos del propietario:", 0, 'L', false, 1, 110, ($y_inicio + 0.5));
$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(60, 60, 60);
$pdf->MultiCell(85, 0.5, 'Nombre: ' . $colecRel[0]['desc_pc'], 0, 'L', false, 1, 110, $pdf->GetY());
$pdf->Ln();
for ($i = 0; $i < count($telRel); $i++) {
    $pdf->MultiCell(85, 0.5, $telRel[$i]['tipotel'] . ': ' . $telRel[$i]['numero'], 0, 'L', false, 1, 110, $pdf->GetY());
}
//$pdf->Ln();


$caracBSN = new CaracteristicaBSN ();
$carac = new Caracteristica ();
$datosProp2 = new Datosprop ();
$propBSN = new PropiedadBSN($id_prop);
$arrayCarac = ListarTipoCaracteristicas($prop ['id_tipo_prop']);
//print_r($arrayCarac);
//die();


$tituloTipoCarac = "";
$y_carac = 95;
$y_max = 270;
$pdf->SetXY(10, $y_carac);
$cantidad = count($arrayCarac) / 2;
$cont = 0;
$col = 1;
$ini = 1;

foreach ($arrayCarac as $elemCarac) {
//    if ($cont < $cantidad) {
    if ($pdf->GetY() < $y_max && $col == 1) {
        $col = 1;
        $x_carac = 10;
    } else {
        $col = 2;
        if ($ini == 1) {
            $x_carac = 108;
            $pdf->SetY($y_carac + 8);
            $ini++;
        } else {
            if ($pdf->GetY() > ($y_max - 3) && $col == 2) {
                $pdf->AddPage();
                $y_carac = 10;
                $col = 1;
                $ini = 1;
            }
        }
    }
    if ($tituloTipoCarac != $elemCarac ['tipo_carac']) {
        $tituloTipoCarac = $elemCarac ['tipo_carac'];

        $pdf->SetXY($x_carac, $pdf->GetY() + 3);
        $pdf->SubTitulo($elemCarac ['tipo_carac'], $col);
    }
    /* 	switch($elemCarac['tipo']){
      case "CheckBox":
      $textoCarac = "SI - NO";
      break;
      case "Lista":
      $textoCarac = str_replace(";", " - ", substr($elemCarac['lista'],10));
      break;
      }
     */
    $pdf->LineaTasacion($elemCarac ['titulo'], '', $col);
    $cont++;
}

/*
  $pdf->Image('images/flechita_pdf.gif',116, 100.6, 3, 3,'GIF','');
  $pdf->SetTextColor(0,85,45);
  $pdf->SetFont('helvetica','B',9);
  //$pdf->SetX(7);
  //$pdf->Cell(8);

  $pdf->SetXY(10, 100);
  $pdf->SubTitulo("Detalles de la Propiedad");
  $detalles = ListarCaracteristicasTasacion(5);
  print_r($detalles);
  die();
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
  $pdf->Image('http://www.zgroupsa.com.ar/okeefe/fotos/'.$fotos[$i]['hfoto'], $x, $y , 90, ($alto_cm * $coef),$tipo,'');
  }
  }
  }
 */
//$pdf->AliasNbPages();
$nombre = str_repeat("0", 5 - strlen(strval($id_prop))) . $id_prop . '.pdf';
//$pdf->Output($nombre,'I'); //download pdf
//$pdf->Output($nombre,'F'); //graba pdf
if (isset($_GET ['op']) && $_GET ['op'] == "mail") {
    $stream = $pdf->Output($nombre, "S");
    echo $stream;
} else {
    $pdf->Output($nombre, "I");
}
ob_end_flush();
//-------FIN PDF
?>
