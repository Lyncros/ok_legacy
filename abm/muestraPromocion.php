<?php
include_once ("clases/class.promocionVW.php");

if(isset($_GET['promo'])){
	$objVW = new PromocionVW($_GET['promo']);
	$objVW->vistaDatos();	
}

?>