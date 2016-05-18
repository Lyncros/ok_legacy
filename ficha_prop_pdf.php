<?php

ob_start();
session_start();
include_once ('clases/class.loginwebuserBSN.php');
include_once ('clases/class.telefonosBSN.php');

$usrBSN = new LoginwebuserBSN ();
$usrBSN->cargaById($_SESSION ['UserId']);
$usrId = $usrBSN->getObjeto()->getId_user();
$usrNombre = $usrBSN->getObjeto()->getNombre();
$usrApellido = $usrBSN->getObjeto()->getApellido();
$usrMail = $usrBSN->getObjeto()->getEmail();
$telBSN = new TelefonosBSN();
$usrTelArray = $telBSN->principalByUsuarios($usrId);
$usrTel = '(' . $usrTelArray['codarea'] . ') ' . $usrTelArray['numero'];

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

function ListarUbicacion($id_ubica) {
    require_once ("clases/class.ubicacionpropiedadBSN.php");

    $ubiBSN = new UbicacionpropiedadBSN();
    //$nombreUbi = $ubiBSN->armaNombreZonaAbr($id_ubica);
    $nombreUbi = $ubiBSN->armaNombreZona($id_ubica);

    return $nombreUbi;
}

function Propiedad($id_prop) {
    require_once ("clases/class.propiedadBSN.php");
    $prop = new PropiedadBSN ();
    $prop->cargaById($id_prop);
    $colec = $prop->getObjetoView();
    return $colec;
}

function TipoPropiedad($id_tipo_prop) {
    require_once ("clases/class.tipo_propBSN.php");
    $tipoprop = new Tipo_propBSN ();
    $tipoprop->cargaById($id_tipo_prop);
    $colec = $tipoprop->getObjetoView();
    return $colec;
}

function ListarDatosPropiedad($id_prop) {
    require_once ("clases/class.datospropBSN.php");
    $prop = new DatospropBSN ();
    $colec = $prop->coleccionCaracteristicasProp($id_prop, 1);
    return $colec;
}

function ListarFotosPropiedad($id_prop) {
    require_once ("clases/class.fotoBSN.php");
    //require_once("generic_class/class.cargaConfiguracion.php");
    $conf = CargaConfiguracion::getInstance('');
    $path = $conf->leeParametro('path_fotos');
    $foto = new FotoBSN ();
    $colec = $foto->cargaColeccionFormByPropiedad($id_prop);
    return $colec;
}

$prop = Propiedad($id_prop);
//print_r($prop);
//die();


$tipo_prop = TipoPropiedad($prop ['id_tipo_prop']);
//print_r($tipo_prop);
//die();


$ubica = ListarUbicacion($prop ['id_ubica']);
//print_r($loca);


$carac = ListarDatosPropiedad($id_prop);
//print_r($carac);
//die();


$fotos = ListarFotosPropiedad($id_prop);

//print_r($fotos);
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
$pdf->SetMargins(10, 25, 10);
$pdf->SetLeftMargin(10);
$pdf->SetHeaderMargin(25);
$pdf->SetFooterMargin(20);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

//set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//set some language-dependent strings
//$pdf->setLanguageArray($l);
switch ($prop ['id_sucursal']) {
    case 'ACH' :
        $direccion = 'Av. Callao 1515 2� piso - C.A.B.A.';
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

if ($prop ['operacion'] == "Alquiler") {
    $moneda = busca_valor(166, $carac);
    $precio = busca_valor(164, $carac);
} else {
    $moneda = busca_valor(165, $carac);
    $precio = busca_valor(161, $carac);
}

//$fdo_gris = array('width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'phase' => 0, 'color' => array(51, 51, 51));
//$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(51, 51, 51)));

$pdf->SetFont('helvetica', '', 9);

$tituloTipoCarac = "";
//$pdf->SetXY(10, $pdf->GetY());
//$pdf->SubTitulo("Detalles de la Propiedad");

$pdf->SetFillColor(235, 235, 235);
$pdf->SetTextColor(60, 60, 60);
$pdf->SetDrawColor(255, 255, 255);
//        $pdf->SetLineWidth(0);
$pdf->RoundedRect(10, 23, 190, 13, 2, '1111', 'DF');

$pdf->SetXY(12, 25);
$txt = $tipo_prop ['tipo_prop'] . " en " . $prop ['operacion'];
$txt .= " - ID " . str_repeat("0", 5 - strlen(strval($id_prop))) . $id_prop;
$pdf->TextoB($txt);

$txt = $prop['calle'] . " " . $prop['nro'];
if ($prop['piso'] != '') {
    $txt .= " - Piso: " . $prop['piso'];
}
if ($prop['dpto'] != '') {
    $txt .= " - Depto.: " . $prop['dpto'];
}

//$txt .= " - " . $ubica;
$txt = $ubica;
//$pdf->SetFillColor(255);
//$pdf->SetTextColor(0, 85, 45);
$pdf->MultiCell(90, 4, $txt, 'TB', 'L', false, $ln = 0, $x = 12, $pdf->GetY(), $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 4, $valign = 'M', $fitcell = false);



$pdf->SetXY(106, 25);
if($prop['publicaprecio'] != 0){
	$texto = "Precio: " . $moneda . " " . number_format($precio, 0, ",", ".");
}else{
	$texto = "Precio: Consultar";
}
if ($prop ['id_tipo_prop'] == 6 || $prop ['id_tipo_prop'] == 16) {
    $unidad = " Ha";
} else {
    $unidad = " m2";
}
$texto .= " - Superficie: " . busca_valor(198, $carac) . $unidad;
$pdf->TextoB($texto);


/*
  $pdf->Titulo($prop['calle'] . " ". $prop['nro']);
  $pdf->Titulo($barrio);
  $pdf->Titulo("CÃ³digo: ". $prop['id_sucursal'] . str_repeat("0", 5-strlen(strval($id_prop))) . $id_prop);
 */
//$y_inicio = $pdf->GetY();
//$txt = $prop['calle'] . " ". $prop['nro'] ."\n" . $barrio;
//$txt = substr ($prop['calle'] . " ". $prop['nro'] busca_valor ( 257, $carac ), 0, 50 ) . " - " . $barrio;

if (count($fotos) > 0) {
    $img0 = 'fotos/' . $fotos [0] ['hfoto'];
    if (file_exists($img0)) {
        $dim_foto = getimagesize($img0);
        switch ($dim_foto ['mime']) {
            case 'image/jpeg' :
                $tipo = 'JPG';
                break;
            case 'image/gif' :
                $tipo = 'GIF';
                break;
        }
    } else {
        $img0 = 'images/noDisponible.gif';
        $tipo = 'GIF';
    }
} else {
    $img0 = 'images/noDisponible.gif';
    $tipo = 'GIF';
}
$pdf->Image($img0, 10, 38, 92, 61, $tipo, '');

if ($prop ['goglat'] != 0) {
//    $pdf->Image ( 'images/flechita_pdf.gif', 116, 100.6, 3, 3, 'GIF', '' );
//    $pdf->SetTextColor ( 0, 85, 45 );
//    $pdf->SetFont ( 'helvetica', 'B', 9 );
//    $pdf->SetX(7);
//    $pdf->Cell(8);
//    $pdf->MultiCell ( 85, 0.5, 'Ubicación', 0, 'L', false, 1, 120, 100 );
//    $mapa='http://maps.google.com/maps/api/staticmap?center=' . $prop ['goglat'] . "," . $prop ['goglong'] . '&amp;zoom=14&amp;size=253x167&amp;format=jpg&amp;markers=color:blue%7Clabel:A%7C' . $prop ['goglat'] . "," . $prop ['goglong'] . '&amp;maptype=hybrid&amp;sensor=false';
//    $pdf->Image($mapa, 107, 38, 92, 61, 'JPG', '');
}

$pdf->Ln();
//$pdf->TextoB(busca_valor(257, $carac));
$pdf->SetXY(10, 105);
$pdf->TextoB("Descripción:");
$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(60, 60, 60);
$pdf->MultiCell(190, 0, busca_valor(255, $carac), 0, 'L', false, 1, 10, $pdf->GetY());
//$pdf->MultiCell ( 85, 0.5, substr ( busca_valor ( 255, $carac ), 0, 480 ), 0, 'L', false, 0, 115, 49 );
//$pdf->Ln();
//$pdf->Ln();
//$pdf->SetX(10);
$y_max = 270;
$col = 1;
$inicio = 0;
$Yinicial = $pdf->GetY() + 4;
foreach ($carac as $elemCarac) {
    if ($elemCarac ['id_tipo'] != 26 && $elemCarac ['id_tipo'] != 29) {
        if ($inicio == 0 && $pdf->GetY() > $y_max) {
            $col = 2;
            $inicio++;
            $pdf->SetY($Yinicial);
            //$pdf->SubTitulo($elemCarac ['tipo_carac'], $col);
        }
        if ($tituloTipoCarac != $elemCarac ['tipo_carac'] && $elemCarac ['tipo_publica'] == 1) {
            $tituloTipoCarac = $elemCarac ['tipo_carac'];
            if ($inicio == 0 && ($pdf->GetY() + 3) > $y_max - 3) {
                $col = 2;
                $inicio++;
                $pdf->SetY($Yinicial - 1);
            } else {
                $pdf->SetY($pdf->GetY() + 3);
            }
            $pdf->SetXY(10, $pdf->GetY());
            $pdf->SubTitulo($elemCarac ['tipo_carac'], $col);
            $pdf->Linea($elemCarac ['titulo'], $elemCarac ['contenido'], $col);
        } else {
            if ($elemCarac ['publica'] == 1) {
                $pdf->Linea($elemCarac ['titulo'], $elemCarac ['contenido'], $col);
                if($col == 2 && $pdf->GetY() > $y_max){
                    $pdf->AddPage();
                    $col = 1;
                    $inicio = 0;
                    $Yinicial = 25;
                }
            }
        }
    }
}

if ($prop ['plano1'] != "" && file_exists('fotos/' . $prop ['plano1'])) {
    $img0 = 'fotos/' . $prop ['plano1'];
    if (file_exists($img0)) {
        $dim_foto = getimagesize($img0);
        switch ($dim_foto ['mime']) {
            case 'image/jpeg' :
                $tipo = 'JPG';
                break;
            case 'image/gif' :
                $tipo = 'GIF';
                break;
            case 'image/png' :
                $tipo = 'PNG';
                break;
        }
    }
    if ($img0 [0] > $img0 [1]) {
        $ancho_cm = $img0 [0] / 37.795275591;
        $coef = 92 / $ancho_cm;
        $alto_cm = $img0 [1] / 37.795275591;
        $imgAncho = 92;
        $imgAlto = $alto_cm * $coef;
    } else {
        $alto_cm = $img0 [1] / 37.795275591;
        $coef = 61 / $alto_cm;
        $ancho_cm = $img0 [0] / 37.795275591;
        $imgAncho = $ancho_cm * $coef;
        $imgAlto = 61;
    }
    $pdf->Image('fotos/' . $prop ['plano1'], 107, 38, $imgAncho, $imgAlto, $tipo, '');
}


if (count($fotos) > 1) {
    $pdf->AddPage();

    $inicio = 1;
    $salto_alto = 60;
    //$contador = 0;
    if (count($fotos) > 6) {
        $cantFotos = 6;
    } else {
        $cantFotos = count($fotos);
    }

    for ($i = 1; $i < $cantFotos; $i++) {
        if (file_exists("fotos/" . $fotos [$i] ['hfoto'])) {
            $dim_foto = getimagesize('http://abm.okeefe.com.ar/fotos/' . $fotos [$i] ['hfoto']);
            $ancho_cm = $dim_foto [0] / 37.795275591;
            $coef = 90 / $ancho_cm;
            $alto_cm = $dim_foto [1] / 37.795275591;
            if ($inicio == 1) {
                $x = 10;
                $y = $pdf->GetY();
                $inicio++;
            } else {
                $x = 110;
                $nuevo_y = $y + ($alto_cm * $coef) + 5;
                $pdf->SetY($nuevo_y);
                $inicio = 1;

                //		$contador++;
            }
            switch ($dim_foto ['mime']) {
                case 'image/jpeg' :
                    $tipo = 'JPG';
                    break;
                case 'image/gif' :
                    $tipo = 'GIF';
                    break;
                case 'image/png' :
                    $tipo = 'PNG';
                    break;
            }
            $pdf->Image('http://abm.okeefe.com.ar/fotos/' . $fotos [$i] ['hfoto'], $x, $y, 90, ($alto_cm * $coef), $tipo, '');
        }
    }
}
//$pdf->AliasNbPages();
$nombre = str_repeat("0", 5 - strlen(strval($id_prop))) . $id_prop . '.pdf';
//$pdf->Output($nombre,'I'); //download pdf
//$pdf->Output($nombre,'F'); //graba pdf
if (isset($_POST ['op']) && $_POST ['op'] == "mail") {
    //$stream = $pdf->Output ( $nombre, "S" );
    //echo $stream;
    $pdf->Output('pdfs/' . $nombre, 'F');

    include_once("inc/class.mail.php");
    $file1 = "pdfs/" . $nombre;

    $para = explode(';', $_POST['email']);
    $mailpara = array();
    if (count($para) > 1) {
        for ($i = 0; $i <= count($para); $i++) {
            $mailpara = array($para[$i] => '');
        }
    } else {
        $mailpara = array($_POST['email'] => '');
    }

    $head = array(
        'to' => array($_POST['desde'] => 'Okeefe Propieades'),
        'from' => $mailpara
            //       'cc'      =>array('email3@email.net'=>'Admin'),
            //       'bcc'     =>array('email4@email.net'=>'Admin'),
    );

    if (strlen($usrId) == 1) {
        $pie = "pie0" . $usrId . ".jpg";
    } else {
        $pie = "pie" . $usrId . ".jpg";
    }

    $subject = $_POST['asunto'];

    $body = nl2br($_POST['comentario']);

    $body .= "<br /%gt;<br /%gt;<img src=\"http://abm.okeefe.com.ar/images/piesMails/" . $pie . "\" width=\"800\" /%gt;";

    $files = array($file1);

    //mail::send($head,$subject,$body);//$files are optional param
    mail::send($head, $subject, $body, $files);
    header("location: mailEnviado.html");
} else {
    $pdf->Output($nombre, "I");
}
ob_end_flush();
//-------FIN PDF
?>
