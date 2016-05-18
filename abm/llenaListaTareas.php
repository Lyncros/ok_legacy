<?php

include_once("clases/class.eventoVW.php");
		$tipocont=$_GET['t'];
		$cont=$_GET['tc'];
		$evVW = new EventoVW();
		$evVW->vistaTablaVW($tipocont,$cont,'v');

?>
