<?php

include_once("clases/class.eventocomponente.php");
		$comp=$_GET['ev'];
		$evVW = new EventocomponenteVW();
		$evVW->vistaTablaVW($comp,'v');

?>
