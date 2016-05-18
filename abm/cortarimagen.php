<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'generic_class/class.upload.php';

$handle = new upload('/Library/WebServer/Documents/achaval/fotos/HPIM7242.JPG');

//$ancho = $handle->image_src_x;
//$alto = $handle->image_src_y;

$wmax = 200;
$hmax = 150;

$t = ($alto-$hmax)/2;
$b = ($alto-$hmax)/2;;
$l = ($ancho-$wmax)/2;
$r = ($ancho-$wmax)/2;

$handle->image_resize = true;
$handle->image_ratio_crop = 'TB';
$handle->image_ratio = true;
//$handle->image_crop = '$t $r $b $l';

$handle->image_src_x = 200;
$handle->image_src_y = 120;


//echo $ancho ." x ".$alto;
$handle->process('/Library/WebServer/Documents/achaval/fotos/');

if ($handle->processed) {
          echo 'image resized';
          $handle->clean();
      } else {
          echo 'error : ' . $handle->error;
      }
?>
