<?php
include_once('generic_class/class.upload.php');
$paramFoto = array(
    "pathDestino" => "/Library/WebServer/Documents/achaval/fotos/prueba/",
    "anchoMaximo" => 400,
    "altoMaximo" => 200 ,
    "tamanoMaximo" => 15000000
);

if(file_exists('/Library/WebServer/Documents/achaval/fotos/HPIM7242.JPG')){
	$imgDim = getimagesize('/Library/WebServer/Documents/achaval/fotos/HPIM7242.JPG');
}

//print_r($imgDim);
//die();
$objCarga = new Upload('/Library/WebServer/Documents/achaval/fotos/HPIM7242.JPG');
$ancho = ($imgDim[0] - $paramFoto['anchoMaximo'])/2;
$alto = ($imgDim[1] - $paramFoto['altoMaximo'])/2;



$objCarga->file_is_image = true;
$objCarga->image_ratio = true;
$objCarga->image_resize = true;
$objCarga->image_x = $paramFoto['anchoMaximo'];
$objCarga->image_y = $paramFoto['altoMaximo'];
$objCarga->image_ratio_y = true;

$objCarga->image_ratio_crop = true;
//$objCarga->image_crop = array(50,40,30,20);
$objCarga->image_crop = array($alto,$alto,$ancho,$ancho);

$objCarga->Process($paramFoto['pathDestino']);
if ($objCarga->processed) {
    echo 'image resized';
    $objCarga->clean();
} else {
    echo 'error : ' . $objCarga->error;
}

?>