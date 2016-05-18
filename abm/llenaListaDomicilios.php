<?php

include_once("clases/class.domicilioVW.php");
		$tipocont=$_GET['t'];
		$cont=$_GET['tc'];
		$domVW = new DomicilioVW();
		$domVW->vistaTablaVW($tipocont,$cont,'v');

?>