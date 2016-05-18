<?php

include_once("clases/class.familiaresVW.php");
		$tipocont=$_GET['t'];
		$cont=$_GET['tc'];
		$objVW = new FamiliaresVW();
		$objVW->vistaTablaVW($tipocont,$cont,'v');

?>