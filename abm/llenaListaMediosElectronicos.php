<?php

include_once("clases/class.medioselectronicosVW.php");
		$tipocont=$_GET['t'];
		$cont=$_GET['tc'];
		$objVW = new MedioselectronicosVW();
		$objVW->vistaTablaVW($tipocont,$cont,'v');

?>